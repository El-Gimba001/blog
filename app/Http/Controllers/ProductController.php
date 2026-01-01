<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Emporia;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Product::query();
        
        // AUTO-FILTER: If user has a store assigned, show only those products
        if ($user->emporia_id) {
            $query->where('emporia_id', $user->emporia_id);
        }
        
        // Your existing search logic
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('reference', 'like', "%{$search}%");
            });
        }
        
        $products = $query->orderBy('name')->paginate(50);
        
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $user = auth()->user();
        
        // If user has a store, pre-select it. Otherwise, show store dropdown for admins.
        $stores = null;
        $defaultStoreId = null;
        
        if ($user->emporia_id) {
            $defaultStoreId = $user->emporia_id;
        } elseif ($user->isAdministrator()) {
            // Admins can see all stores
            $stores = Emporia::where('is_active', true)->get();
        }
        
        return view('products.create-product', compact('stores', 'defaultStoreId'));
    }

    /**
     * Store a newly created product in the database.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:50',
            'quantity' => 'required|integer|min:1',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'emporia_id' => 'nullable|exists:emporia,id', // For admins
            'reorder_point' => 'nullable|integer|min:0', // Add reorder point for low stock alerts
        ]);

        // Determine store assignment
        $emporiaId = $request->emporia_id ?? $user->emporia_id;
        
        // Validate store access
        if ($emporiaId && !$user->isAdministrator() && !$user->hasEmporiumAccess($emporiaId)) {
            return back()->with('error', 'You do not have access to this store.');
        }

        $prefix = $user ? strtoupper(substr($user->name, 0, 2)) : 'ST';
        $reference = $prefix . now()->format('mydHis');

        $profit = $request->selling_price - $request->cost_price;

        Product::create([
            'name' => $request->name,
            'category' => $request->category,
            'unit' => $request->unit,
            'quantity' => $request->quantity,
            'cost_price' => $request->cost_price,
            'selling_price' => $request->selling_price,
            'profit' => $profit,
            'reference' => $reference,
            'emporia_id' => $emporiaId, // Store assignment
            'reorder_point' => $request->reorder_point ?? 10, // Default reorder point
            'code' => $request->code ?? strtoupper(substr(str_replace(' ', '', $request->name), 0, 8)), // Auto-generate code
        ]);

        return redirect()
            ->route('products.index')
            ->with('success', "Product registered successfully with reference: {$reference}");
    }

    /**
     * Show the form for editing a product.
     */
    public function edit(Product $product)
    {
        $user = auth()->user();
        
        // Check if user has access to this product's store
        if (!$user->isAdministrator() && $product->emporia_id != $user->emporia_id) {
            abort(403, 'You do not have access to edit this product.');
        }
        
        // Get stores for admin to change assignment
        $stores = null;
        if ($user->isAdministrator()) {
            $stores = Emporia::where('is_active', true)->get();
        }
        
        return view('products.edit-product', compact('product', 'stores'));
    }

    /**
     * Update an existing product.
     */
    public function update(Request $request, Product $product)
    {
        $user = auth()->user();
        
        // Check access before update
        if (!$user->isAdministrator() && $product->emporia_id != $user->emporia_id) {
            abort(403, 'You do not have access to update this product.');
        }
        
        // ✅ Validate only the fields that are provided (no required)
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:50',
            'quantity' => 'nullable|integer|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'emporia_id' => 'nullable|exists:emporia,id', // For admins to change store
            'reorder_point' => 'nullable|integer|min:0', // Low stock threshold
            'code' => 'nullable|string|max:50|unique:products,code,' . $product->id,
        ]);

        // ✅ Merge new values with existing data
        $data = [
            'name' => $validated['name'] ?? $product->name,
            'category' => $validated['category'] ?? $product->category,
            'unit' => $validated['unit'] ?? $product->unit,
            'quantity' => $validated['quantity'] ?? $product->quantity,
            'cost_price' => $validated['cost_price'] ?? $product->cost_price,
            'selling_price' => $validated['selling_price'] ?? $product->selling_price,
            'reorder_point' => $validated['reorder_point'] ?? $product->reorder_point,
            'code' => $validated['code'] ?? $product->code,
        ];

        // Handle store change (admins only)
        if (isset($validated['emporia_id']) && $user->isAdministrator()) {
            $data['emporia_id'] = $validated['emporia_id'];
        }

        // ✅ Recalculate profit
        $data['profit'] = $data['selling_price'] - $data['cost_price'];

        $product->update($data);

        return redirect()->back()->with('success', 'Product updated successfully!');
    }

    /**
     * Show the form for restocking existing products.
     */
    public function restock()
    {
        $user = auth()->user();
        $query = Product::query();
        
        // Filter by user's store
        if ($user->emporia_id) {
            $query->where('emporia_id', $user->emporia_id);
        }
        
        $products = $query->get();
        return view('products.restock', compact('products'));
    }

    /**
     * Handle stock restock submission.
     */
    public function updateStock(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'additional_quantity' => 'nullable|integer|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
        ]);

        $product = Product::findOrFail($request->product_id);
        
        // Check access
        if (!$user->isAdministrator() && $product->emporia_id != $user->emporia_id) {
            abort(403, 'You do not have access to update this product.');
        }

        // Add quantity
        if ($request->filled('additional_quantity')) {
            $product->quantity += $request->additional_quantity;
        }

        // Update cost/selling if provided
        if ($request->filled('cost_price')) {
            $product->cost_price = $request->cost_price;
        }

        if ($request->filled('selling_price')) {
            $product->selling_price = $request->selling_price;
        }

        // Recalculate profit
        $product->profit = $product->selling_price - $product->cost_price;

        $product->save();

        return back()->with('success', "{$product->name} Updated successfully!");
    }

    /**
     * Display the specified product.
     */
    public function show($id)
    {
        // Handle both product ID and 'restock' string
        if ($id === 'restock') {
            return $this->restock();
        }
        
        $product = Product::findOrFail($id);
        
        // Check access
        $user = auth()->user();
        if (!$user->isAdministrator() && $product->emporia_id != $user->emporia_id) {
            abort(403, 'You do not have access to view this product.');
        }
        
        return view('products.show', compact('product'));
    }

    /**
     * Remove the specified product.
     */
    public function destroy(Product $product)
    {
        $user = auth()->user();
        
        // Check access
        if (!$user->isAdministrator() && $product->emporia_id != $user->emporia_id) {
            abort(403, 'You do not have access to delete this product.');
        }
        
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
    
    /**
     * Get low stock products (for alerts)
     */
    public function lowStock(Request $request)
    {
        $user = auth()->user();
        $query = Product::query();
        
        // Filter by user's store(s)
        if ($user->emporia_id) {
            $query->where('emporia_id', $user->emporia_id);
        }
        
        // Get low stock products (quantity <= reorder_point)
        $query->whereColumn('quantity', '<=', 'reorder_point')
              ->orderBy('quantity', 'asc');
        
        $lowStockProducts = $query->paginate(50);
        
        return view('products.low-stock', compact('lowStockProducts'));
    }
    // Add to your ProductController.php

/**
 * Get low stock list for API
 */
public function lowStockList(Request $request)
{
    $user = auth()->user();
    $query = Product::query();
    
    if ($user->emporia_id) {
        $query->where('emporia_id', $user->emporia_id);
    }
    
    $products = $query->whereColumn('quantity', '<=', 'reorder_point')
        ->with('emporium')
        ->orderBy('quantity')
        ->limit(20)
        ->get();
    
    return response()->json($products);
}

/**
 * Check stock status for a specific product
 */
public function checkStockStatus(Product $product, Request $request)
{
    $user = auth()->user();
    
    // Check access
    if (!$user->isAdministrator() && $product->emporia_id != $user->emporia_id) {
        return response()->json(['error' => 'Access denied'], 403);
    }
    
    return response()->json([
        'is_low_stock' => $product->isLowOnStock(),
        'stock_status' => $product->stock_status,
        'quantity' => $product->quantity,
        'reorder_point' => $product->reorder_point,
    ]);
}
}