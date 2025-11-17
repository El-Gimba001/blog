<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index()
    {
        $products = Product::latest()->get();
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        return view('products.create-product');
    }

    /**
     * Store a newly created product in the database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:50',
            'quantity' => 'required|integer|min:1',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
        ]);

        $user = auth()->user();
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
        $products = Product::all();
        return view('products.edit-product', compact('product', 'products'));
    }

    /**
     * Update an existing product.
     */
    public function update(Request $request, Product $product)
    {
        // ✅ Validate only the fields that are provided (no required)
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:50',
            'quantity' => 'nullable|integer|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
        ]);

        // ✅ Merge new values with existing data
        $data = [
            'name' => $validated['name'] ?? $product->name,
            'category' => $validated['category'] ?? $product->category,
            'unit' => $validated['unit'] ?? $product->unit,
            'quantity' => $validated['quantity'] ?? $product->quantity,
            'cost_price' => $validated['cost_price'] ?? $product->cost_price,
            'selling_price' => $validated['selling_price'] ?? $product->selling_price,
        ];

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
        $products = Product::all();
        return view('products.restock', compact('products'));
    }

    /**
     * Handle stock restock submission.
     */
    public function updateStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'additional_quantity' => 'nullable|integer|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Add quantity
        $product->quantity += $request->additional_quantity;

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
     * Remove the specified product.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
}