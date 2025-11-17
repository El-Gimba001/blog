<?php

namespace app\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        // 🧾 Show daily transactions
        $transactions = Transaction::with('items')
            ->whereDate('created_at', now()->toDateString())
            ->latest()
            ->get();

        return view('transactions.daily', compact('transactions'));
    }

    public function daily()
        {
            $transactions = Transaction::with('items.product')
                ->whereDate('created_at', now()->toDateString())
                ->latest()
                ->get();

            $totalSales = $transactions->sum('total_amount');
            $totalProfit = $transactions->sum(function($transaction) {
                return $transaction->items->sum('profit');
            });
            $totalItems = $transactions->sum(function($transaction) {
                return $transaction->items->sum('quantity');
            });

            // Change this line to use 'transaction.daily' instead of 'transactions.daily'
            return view('transaction.daily', compact('transactions', 'totalSales', 'totalProfit', 'totalItems'));
        }

    public function showDetails($id)
        {
            $transaction = Transaction::with('items.product')->findOrFail($id);
            
            $html = view('transaction.partials.details', compact('transaction'))->render();
            
            return response()->json(['html' => $html]);
        }

    

    public function store(Request $request)
        {
            \Log::info('=== TRANSACTION STORE METHOD CALLED ===');
            \Log::info('Request data:', $request->all());

            try {
                $request->validate([
                    'reference' => 'required|unique:transactions,reference',
                    'customer_name' => 'required|string',
                    'payment_type' => 'required|string',
                    'items' => 'required|array|min:1',
                    'items.*.product_id' => 'required|exists:products,id',
                    'items.*.unit_price' => 'required|numeric|min:0',
                    'items.*.quantity' => 'required|numeric|min:0.01',
                    'items.*.total' => 'required|numeric|min:0',
                ]);

                \Log::info('Validation passed');

                DB::beginTransaction();

                // Create the main transaction
                $transaction = Transaction::create([
                    'reference' => $request->reference,
                    'customer_name' => $request->customer_name,
                    'location' => $request->location,
                    'payment_type' => $request->payment_type,
                    'total_amount' => collect($request->items)->sum('total'),
                    'user_id' => auth()->id(),
                    'status' => 'completed',
                ]);

                \Log::info('Transaction created with ID: ' . $transaction->id);

                $totalProfit = 0;

                // Create transaction items with profit calculation
                foreach ($request->items as $item) {
                    // Get the product to access cost_price
                    $product = \App\Models\Product::find($item['product_id']);
                    
                    if (!$product) {
                        throw new \Exception("Product not found with ID: " . $item['product_id']);
                    }

                    // Check if there's enough stock
                    if ($product->quantity < $item['quantity']) {
                        throw new \Exception("Insufficient stock for {$product->name}. Available: {$product->quantity}, Requested: {$item['quantity']}");
                    }

                    // Calculate profit per unit: (selling price - cost price)
                    $costPrice = $product->cost_price ?? 0;
                    $profitPerUnit = $item['unit_price'] - $costPrice;
                    $itemProfit = $profitPerUnit * $item['quantity'];
                    
                    $totalProfit += $itemProfit;

                    \Log::info("Product: {$product->name}, Cost: {$costPrice}, Unit Profit: {$profitPerUnit}, Total Profit: {$itemProfit}");

                    // Create transaction item with profit
                    TransactionItem::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $item['product_id'],
                        'unit_price' => $item['unit_price'],
                        'discount' => $item['discount'] ?? 0,
                        'quantity' => $item['quantity'],
                        'total' => $item['total'],
                        'profit' => $itemProfit,
                    ]);

                    // ✅ FIX: Update product stock using 'quantity' column instead of 'stock'
                    $product->decrement('quantity', $item['quantity']);
                    \Log::info("Updated stock for {$product->name}. New quantity: {$product->quantity}");
                }

                DB::commit();

                \Log::info('=== TRANSACTION COMPLETED SUCCESSFULLY ===');
                \Log::info('Total profit for transaction: ' . $totalProfit);

                return response()->json([
                    'success' => true,
                    'message' => 'Transaction saved successfully!',
                    'transaction_id' => $transaction->id,
                    'total_profit' => $totalProfit,
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('TRANSACTION ERROR: ' . $e->getMessage());
                \Log::error('File: ' . $e->getFile());
                \Log::error('Line: ' . $e->getLine());

                return response()->json([
                    'success' => false,
                    'message' => 'Transaction failed: ' . $e->getMessage(),
                ], 500);
            }
        }
}