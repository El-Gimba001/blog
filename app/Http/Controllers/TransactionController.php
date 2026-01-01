<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Emporia;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Show sales entry form
     */
    public function create()
    {
        try {
            // Simple: Always use Main Store (ID: 1)
            $emporia = Emporia::find(1);
            
            if (!$emporia) {
                return redirect()->back()->with('error', 'Main store not found.');
            }

            $products = Product::where('emporia_id', 1) // Use ID 1 directly
                ->where('quantity', '>', 0)
                ->orderBy('name', 'asc')
                ->get(['id', 'name', 'quantity', 'selling_price', 'cost_price']);

            return view('Transaction.sales-entry', [
                'products' => $products,
                'emporia' => $emporia,
                'reference' => 'SALE-' . now()->format('YmdHis')
            ]);

        } catch (\Exception $e) {
            \Log::error('Error loading sales entry form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load sales entry form: ' . $e->getMessage());
        }
    }

    /**
     * Store a new sales transaction
     */
    public function store(Request $request)
    {

        $request->validate([
            'reference' => 'required|string|unique:transactions,reference',
            'customer_name' => 'required|string|max:255',
            'payment_type' => 'required|in:Cash,POS,Bank Transfer,Credit',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // FIXED: Always use Main Store (emporia_id = 1)
            $emporiaId = 1;
            
            // Quick verification (optional)
            if (!Emporia::where('id', $emporiaId)->where('is_active', 1)->exists()) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Main store is not active or not found.',
                ], 422);
            }

            $payment_type = $request->payment_type;
            // Calculate totals
            $totalAmount = 0;
            $totalProfit = 0;

            // Create transaction - NO MORE NULL ERROR!
            $transaction = Transaction::create([
                'emporia_id' => $emporiaId, // This is now always 1
                'user_id' => Auth::id(),
                'reference' => $request->reference,
                'customer_name' => $request->customer_name,
                'total_amount' => 0, // Will update after calculation
                'profit' => 0, // Will update after calculation
                'payment_type' => $request->payment_type, // Now gets value from 'payment_type'
                'status' => 'completed',
            ]);

            $transaction->update([
                'total_amount' => $totalAmount,
                'profit' => $totalProfit,
            ]);

            // Process items
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                // Check stock availability
                if ($product->quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$product->name}. Available: {$product->quantity}");
                }

                // Calculate item totals
                $itemTotal = $item['quantity'] * $item['unit_price'];
                $itemCost = $item['quantity'] * $product->cost_price;
                $itemProfit = $itemTotal - $itemCost;

                $totalAmount += $itemTotal;
                $totalProfit += $itemProfit;

                // Create transaction item
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'cost_price' => $product->cost_price,
                    'total' => $itemTotal,
                    'profit' => $itemProfit,
                ]);

                // Update product quantity
                $product->decrement('quantity', $item['quantity']);
            }

            // Update transaction with final totals
            $transaction->update([
                'total_amount' => $totalAmount,
                'profit' => $totalProfit,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sale completed successfully!',
                'transaction_id' => $transaction->id,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Sale transaction failed: ' . $e->getMessage());
            \Log::error('User: ' . Auth::id() . ', Emporia: 1');
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to process sale: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display daily transactions
     */
    public function dailyTransactions()
{
    $emporiaId = 1;

    $emporia = Emporia::findOrFail($emporiaId);

    $today = now()->toDateString();

    $totalProfit = Transaction::where('emporia_id', $emporiaId)
        ->whereDate('created_at', $today)
        ->whereIn('payment_type',['Cash', 'POS', 'Bank Transfer'])
        ->sum('profit');

    $transactions = Transaction::with(['transactionItems.product'])
        ->where('emporia_id', $emporiaId)
        ->whereDate('created_at', $today)
        ->whereIn('payment_type',['Cash', 'POS', 'Bank Transfer'])
        ->orderBy('created_at', 'desc')
        ->paginate(20);

    $dailyTotal = Transaction::where('emporia_id', $emporiaId)
        ->whereDate('created_at', $today)
        ->whereIn('payment_type',['Cash', 'POS', 'Bank Transfer'])
        ->sum('total_amount');

    return view('transaction.daily', compact(
        'transactions',
        'dailyTotal',
        'totalProfit',
        'today',
        'emporia'
    ));
}

    /**
     * Display sold items
     */
    public function soldItems()
    {
        try {
            $emporiaId = 1;
            $emporia = Emporia::find($emporiaId);
            
            if (!$emporia) {
                return redirect()->back()->with('error', 'Main store not found.');
            }
            
            // Get sold items (transaction items) for today
            $today = now()->format('Y-m-d');
            $soldItems = TransactionItem::with(['transaction', 'product'])
                ->whereHas('transaction', function($query) use ($emporiaId, $today) {
                    $query->where('emporia_id', $emporiaId)
                          ->whereDate('created_at', $today)
                          ->whereIn('payment_type',['Cash', 'POS', 'Bank Transfer']);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(20);
            
            $totalSoldValue = TransactionItem::whereHas('transaction', function($query) use ($emporiaId, $today) {
                    $query->where('emporia_id', $emporiaId)
                          ->whereDate('created_at', $today)
                          ->whereIn('payment_type',['Cash', 'POS', 'Bank Transfer']);
                })
                ->sum('total');
            
            return view('transactions.sold-items', [
                'soldItems' => $soldItems,
                'totalSoldValue' => $totalSoldValue,
                'today' => $today,
                'emporia' => $emporia
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error loading sold items: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load sold items.');
        }
    }

    /**
     * Display stock out items
     */
    public function stockOut()
    {
        try {
            $emporiaId = 1;
            $emporia = Emporia::find($emporiaId);
            
            if (!$emporia) {
                return redirect()->back()->with('error', 'Main store not found.');
            }
            
            // Get products with zero or low stock
            $lowStockItems = Product::where('emporia_id', $emporiaId)
                ->where('quantity', '<=', 10) // Adjust threshold as needed
                ->orderBy('quantity', 'asc')
                ->paginate(20);
            
            $outOfStockCount = Product::where('emporia_id', $emporiaId)
                ->where('quantity', 0)
                ->count();
            
            $lowStockCount = Product::where('emporia_id', $emporiaId)
                ->where('quantity', '>', 0)
                ->where('quantity', '<=', 10)
                ->count();
            
            return view('transactions.stock-out', [
                'lowStockItems' => $lowStockItems,
                'outOfStockCount' => $outOfStockCount,
                'lowStockCount' => $lowStockCount,
                'emporia' => $emporia
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error loading stock out items: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load stock out items.');
        }
    }

    /**
     * Display transaction details
     */
    public function showDetails($id)
    {
        try {
            $transaction = Transaction::with(['transactionItems.product', 'user'])
                ->where('id', $id)
                ->first();
            
            if (!$transaction) {
                return redirect()->back()->with('error', 'Transaction not found.');
            }
            
            return view('transactions.details', [
                'transaction' => $transaction
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error loading transaction details: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load transaction details.');
        }
    }

    /**
     * Display all transactions (optional - if you need it)
     */
    public function index()
    {
        $emporiaId = 1;
        
        $transactions = Transaction::with(['transactionItems.product'])
            ->where('emporia_id', $emporiaId)
            ->whereIn('payment_type',['Cash', 'POS', 'Bank Transfer'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('transactions.index', compact('transactions'));
    }
}