<?php

namespace App\Http\Controllers;

use App\Models\AuditReport;
use App\Models\Emporia;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;



class AuditorController extends Controller
{
    private function mainEmporia(){
    return Emporia::where('id', 1)->where('is_active', 1)->first();
}

    public function dashboard()
    {
        return view('auditor.dashboard');
    }

    public function getOverstockProducts()
{
    try {
        \Log::info('Getting products for auditor', ['user_id' => Auth::id()]);
        
        // Get the emporia assigned to this auditor
        $emporia = $this->mainEmporia();
        
        if (!$emporia) {
            \Log::warning('No emporia assigned to auditor', ['user_id' => Auth::id()]);
            
            // TEMPORARY FIX: Use Main Store (ID: 1) for testing
            $emporia = Emporia::find(1);
            if (!$emporia) {
                return response()->json([
                    'error' => 'No emporia available in system'
                ], 400);
            }
            \Log::info('Using Main Store as fallback', ['emporia_id' => $emporia->id]);
        }

        // Use the correct column names from your products table
        $products = Product::where('emporia_id', $emporia->id)
            ->orderBy('name', 'asc')
            ->get(['id', 'name', 'quantity', 'selling_price', 'cost_price']);

        \Log::info('Products loaded successfully', [
            'emporia_id' => $emporia->id,
            'emporia_name' => $emporia->name,
            'count' => $products->count()
        ]);

        return response()->json($products);

    } catch (\Exception $e) {
        \Log::error('Error getting products: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to load products'], 500);
    }
}

    public function getAuditorStats()
    {
        try {
            // Get the emporia assigned to this auditor
        $emporia = $this->mainEmporia();            
            if (!$emporia) {
                return response()->json([
                    'total_products' => 0,
                    'reports_this_month' => 0,
                    'pending_reviews' => 0,
                    'total_adjustments' => 0,
                    'error' => 'No emporia assigned'
                ]);
            }

            $stats = [
                'total_products' => Product::where('emporia_id', $emporia->id)->count(),
                'reports_this_month' => AuditReport::where('auditor_id', Auth::id())
                    ->where('emporia_id', $emporia->id)
                    ->whereMonth('created_at', now()->month)
                    ->count(),
                'pending_reviews' => AuditReport::where('auditor_id', Auth::id())
                    ->where('emporia_id', $emporia->id)
                    ->where('status', 'pending')
                    ->count(),
                'total_adjustments' => AuditReport::where('auditor_id', Auth::id())
                    ->where('emporia_id', $emporia->id)
                    ->count(),
                'emporia_name' => $emporia->name
            ];
            
            return response()->json($stats);
        } catch (\Exception $e) {
            \Log::error('Error getting auditor stats: ' . $e->getMessage());
            return response()->json([
                'total_products' => 0,
                'reports_this_month' => 0,
                'pending_reviews' => 0,
                'total_adjustments' => 0
            ], 500);
        }
    }

    public function getAuditReports()
    {
        try {
            // Get the emporia assigned to this auditor
        $emporia = $this->mainEmporia();
            
            if (!$emporia) {
                return response()->json(['error' => 'No emporia assigned'], 400);
            }

            $reports = AuditReport::with(['product'])
                ->where('auditor_id', Auth::id())
                ->where('emporia_id', $emporia->id)
                ->orderBy('created_at', 'desc')
                ->get();
                
            return response()->json($reports);
        } catch (\Exception $e) {
            \Log::error('Error getting audit reports: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }

    public function sendAuditReport(Request $request)
{
    $request->validate([
        'reference' => 'required|string',
        'customer_name' => 'required|string|max:255',
        'location' => 'required|string',
        'payment_type' => 'required|string',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.unit_price' => 'required|numeric|min:0',
        'items.*.discount' => 'required|numeric|min:0',
        'items.*.quantity' => 'required|numeric|min:0.01',
        'items.*.total' => 'required|numeric|min:0',
        'total_amount' => 'required|numeric|min:0',
        'reason' => 'required|string|max:500',
        'discrepancy_notes' => 'required|string'
    ]);

    // Get the auditor's assigned emporia
    $emporia = $this->mainEmporia();    
    if (!$emporia) {
        return response()->json([
            'success' => false,
            'message' => 'No emporia assigned to you. Please contact administrator.'
        ], 400);
    }

    try {
        \DB::beginTransaction();

        // Verify all products belong to auditor's assigned emporia
        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);
            
            if ($product->emporia_id !== $emporia->id) {
                \DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => "Product '{$product->name}' does not belong to your assigned emporia '{$emporia->name}'."
                ], 403);
            }

            if ($item['quantity'] > $product->quantity) {
                \DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => "Quantity for '{$product->name}' cannot exceed current stock of {$product->quantity} units."
                ], 400);
            }
        }

        // Calculate totals
        $total_sales = $request->total_amount;
        $total_cost = 0;
        $total_profit = 0;

        // Create audit transaction - THIS WAS MISSING
        $transaction = Transaction::create([
            'emporia_id' => $emporia->id,
            'user_id' => Auth::id(),
            'reference' => $request->reference,
            'customer_name' => $request->customer_name,
            'total_amount' => $total_sales,
            'total_profit' => $total_profit,
            'transaction_type' => 'audit_adjustment',
            'payment_method' => $request->payment_type,
            'location' => $request->location,
            'status' => 'completed',
            'notes' => 'Audit adjustment - ' . $emporia->name
        ]);

        // Process each item and create transaction items
        $itemsDetails = [];
        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);
            
            // Store original quantity for report
            $original_quantity = $product->quantity;
            
            // Update product quantity (deduct the adjusted quantity)
            $new_quantity = $product->quantity - $item['quantity'];
            $product->update(['quantity' => $new_quantity]);

            // Calculate cost and profit for this item
            $item_cost = $item['quantity'] * $product->cost_price;
            $item_profit = $item['total'] - $item_cost;
            
            $total_cost += $item_cost;
            $total_profit += $item_profit;

            // Create transaction item with ALL required fields
            $transactionItem = TransactionItem::create([
                'transaction_id' => $transaction->id,
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'cost_price' => $product->cost_price,
                'total' => $item['total'],
                'profit' => $item_profit, // This was missing
            ]);

            // Store item details for report
            $itemsDetails[] = [
                'product_name' => $product->name,
                'original_quantity' => $original_quantity,
                'adjusted_quantity' => $item['quantity'],
                'new_quantity' => $new_quantity,
                'unit_price' => $item['unit_price'],
                'cost_price' => $product->cost_price,
                'discount' => $item['discount'],
                'total_sales' => $item['total'],
                'total_cost' => $item_cost,
                'profit' => $item_profit
            ];
        }

        // Update transaction with calculated profit
        $transaction->update(['total_profit' => $total_profit]);

        // Generate detailed statement
        $statement = $this->generateAuditStatement($itemsDetails, $request, $total_sales, $total_cost, $total_profit, $emporia);

        // Create audit report
        // Create audit report
// Generate findings summary
$findings = "Stock audit adjustment performed by " . Auth::user()->name . " for {$emporia->name}. ";
$findings .= count($request->items) . " products adjusted with total sales of ₦" . number_format($total_sales, 2) . ". ";
$findings .= "Adjustment reason: {$request->reason}.";

// Create audit report
$report = AuditReport::create([
    'emporia_id' => $emporia->id,
    'auditor_id' => Auth::id(),
    'report_title' => 'Stock Audit - ' . $emporia->name . ' - ' . now()->format('M j, Y'),
    'findings' => $findings,
    'amount_adjusted' => $total_sales,
    'profit_adjusted' => $total_profit,
    'affected_products' => json_encode($itemsDetails),
    'manager_approval_status' => 'pending',
    'admin_approval_status' => 'pending',
    'status' => 'pending',
]);

        \DB::commit();

        return response()->json([
            'success' => true,
            'message' => "Audit report for {$emporia->name} successfully submitted for administrative review!",
            'report_id' => $report->id,
            'transaction_id' => $transaction->id,
            'emporia_name' => $emporia->name,
            'report_date' => Carbon::today()->format('M j, Y'),
            'total_items' => count($request->items),
            'total_sales' => $total_sales,
            'total_profit' => $total_profit
        ]);

    } catch (\Exception $e) {
        \DB::rollBack();
        \Log::error('Failed to create audit report: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to process audit adjustment: ' . $e->getMessage()
        ], 500);
    }
}

    private function generateAuditStatement($itemsDetails, $request, $total_sales, $total_cost, $total_profit, $emporia)
    {
        $statement = "AUDIT ADJUSTMENT REPORT\n\n";
        $statement .= "EMPORIA: {$emporia->name}\n";
        $statement .= "AUDIT REFERENCE: {$request->reference}\n";
        $statement .= "AUDIT DATE: " . now()->format('F j, Y') . "\n";
        $statement .= "AUDITOR: " . Auth::user()->name . "\n";
        $statement .= "CUSTOMER: {$request->customer_name}\n";
        $statement .= "LOCATION: {$request->location}\n";
        $statement .= "PAYMENT TYPE: {$request->payment_type}\n\n";
        
        $statement .= "EXECUTIVE SUMMARY:\n";
        $statement .= "Audit adjustment performed for {$emporia->name} to correct inventory discrepancies.\n\n";
        
        $statement .= "ADJUSTMENT TRANSACTION DETAILS:\n";
        $statement .= "• Total Products Adjusted: " . count($itemsDetails) . "\n";
        $statement .= "• Total Sales Amount: ₦" . number_format($total_sales, 2) . "\n";
        $statement .= "• Total Cost: ₦" . number_format($total_cost, 2) . "\n";
        $statement .= "• Total Profit Generated: ₦" . number_format($total_profit, 2) . "\n\n";
        
        $statement .= "ITEMIZED ADJUSTMENTS:\n";
        foreach ($itemsDetails as $index => $item) {
            $statement .= ($index + 1) . ". {$item['product_name']}:\n";
            $statement .= "   - Original Quantity: {$item['original_quantity']} units\n";
            $statement .= "   - Quantity Removed: {$item['adjusted_quantity']} units\n";
            $statement .= "   - New Quantity: {$item['new_quantity']} units\n";
            $statement .= "   - Sale Price: ₦" . number_format($item['unit_price'], 2) . "/unit\n";
            $statement .= "   - Cost Price: ₦" . number_format($item['cost_price'], 2) . "/unit\n";
            $statement .= "   - Discount: ₦" . number_format($item['discount'], 2) . "\n";
            $statement .= "   - Total Sales: ₦" . number_format($item['total_sales'], 2) . "\n";
            $statement .= "   - Profit: ₦" . number_format($item['profit'], 2) . "\n\n";
        }
        
        $statement .= "REASON FOR ADJUSTMENT:\n";
        $statement .= "{$request->reason}\n\n";
        
        $statement .= "AUDITOR'S FINDINGS AND IMPACT ANALYSIS:\n";
        $statement .= "{$request->discrepancy_notes}\n\n";
        
        $statement .= "CONCLUSION:\n";
        $statement .= "The inventory adjustment for {$emporia->name} has been completed. ";
        $statement .= "Stock levels have been updated and financial records reflect the changes made during this audit.";

        return $statement;
    }
}