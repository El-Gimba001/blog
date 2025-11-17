<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockAdjustment;
use App\Models\AuditReport;
use App\Models\Emporia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuditorController extends Controller
{
    /**
     * Show auditor dashboard with over-stock management
     */
    public function dashboard()
    {
        $auditorId = auth()->id();
        
        // Get auditor's assigned emporia (you'll need to implement this relationship)
        $emporia = Emporia::where('auditor_id', $auditorId)->first();
        
        $stats = [
            'pendingAuditsCount' => AuditReport::where('auditor_id', $auditorId)
                ->where('status', 'pending')->count(),
                
            'overStockItemsCount' => Product::where('emporia_id', $emporia->id ?? 1)
                ->where('quantity', '>', 0)->count(),
                
            'reportsGeneratedCount' => AuditReport::where('auditor_id', $auditorId)->count(),
                
            'totalAmountAdjusted' => StockAdjustment::where('auditor_id', $auditorId)
                ->sum('amount_impact'),
        ];
        
        $recentReports = AuditReport::with('emporia')
            ->where('auditor_id', $auditorId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        $recentAdjustments = StockAdjustment::with('product')
            ->where('auditor_id', $auditorId)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        $products = Product::where('emporia_id', $emporia->id ?? 1)
            ->where('quantity', '>', 0)
            ->get();

        return view('users-panels.auditor', array_merge($stats, [
            'recentReports' => $recentReports,
            'recentAdjustments' => $recentAdjustments,
            'products' => $products,
            'emporia' => $emporia,
        ]));
    }
    
    /**
     * Handle over-stock adjustment and generate report
     */
    public function adjustStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'physical_quantity' => 'required|numeric|min:0',
            'reason' => 'required|string|min:10',
        ]);
        
        return DB::transaction(function () use ($request) {
            $product = Product::findOrFail($request->product_id);
            $auditorId = auth()->id();
            
            $oldQuantity = $product->quantity;
            $newQuantity = $request->physical_quantity;
            $adjustment = $oldQuantity - $newQuantity;
            
            // Update product stock
            $product->update(['quantity' => $newQuantity]);
            
            // Create stock adjustment record
            $stockAdjustment = StockAdjustment::create([
                'emporia_id' => $product->emporia_id,
                'auditor_id' => $auditorId,
                'product_id' => $product->id,
                'old_quantity' => $oldQuantity,
                'new_quantity' => $newQuantity,
                'adjustment' => $adjustment,
                'reason' => $request->reason,
                'amount_impact' => $adjustment * $product->cost_price,
                'profit_impact' => $adjustment * $product->profit,
            ]);
            
            // Generate automatic audit report
            $this->generateAuditReport($stockAdjustment);
            
            return redirect()->route('auditor.dashboard')
                ->with('success', 'Stock adjusted successfully and report generated!');
        });
    }
    
    /**
     * Generate automatic audit report with detailed statement
     */
    private function generateAuditReport(StockAdjustment $adjustment)
    {
        $product = $adjustment->product;
        
        $statement = "During inventory audit, {$product->name} (Ref: {$product->reference}) showed system quantity of {$adjustment->old_quantity} " .
                     "but physical count revealed {$adjustment->new_quantity} units. The discrepancy of {$adjustment->adjustment} units " .
                     "amounts to $" . number_format(abs($adjustment->amount_impact), 2) . " with $" . 
                     number_format(abs($adjustment->profit_impact), 2) . " profit impact. " .
                     "Reason: {$adjustment->reason}. Stock has been adjusted to reflect actual physical inventory.";
        
        return AuditReport::create([
            'emporia_id' => $adjustment->emporia_id,
            'auditor_id' => $adjustment->auditor_id,
            'report_title' => "Stock Adjustment - {$product->name}",
            'findings' => $statement,
            'amount_adjusted' => $adjustment->amount_impact,
            'profit_adjusted' => $adjustment->profit_impact,
            'affected_products' => json_encode([
                'product_id' => $product->id,
                'product_name' => $product->name,
                'old_quantity' => $adjustment->old_quantity,
                'new_quantity' => $adjustment->new_quantity,
                'adjustment' => $adjustment->adjustment,
            ]),
            'status' => 'pending',
            'report_sent_at' => now(),
        ]);
    }
    
    /**
     * Show all audit reports
     */
    public function reports()
    {
        $reports = AuditReport::with(['emporia', 'product'])
            ->where('auditor_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('auditor.reports', compact('reports'));
    }
    
    /**
     * Show form for manual audit report creation
     */
    public function createReport()
    {
        $emporia = Emporia::where('auditor_id', auth()->id())->get();
        $products = Product::whereIn('emporia_id', $emporia->pluck('id'))->get();
        
        return view('auditor.create-report', compact('emporia', 'products'));
    }
    
    /**
     * Submit manual audit report
     */
    public function storeReport(Request $request)
    {
        $request->validate([
            'report_title' => 'required|string|max:255',
            'findings' => 'required|string|min:20',
            'emporia_id' => 'required|exists:emporia,id',
        ]);
        
        AuditReport::create([
            'emporia_id' => $request->emporia_id,
            'auditor_id' => auth()->id(),
            'report_title' => $request->report_title,
            'findings' => $request->findings,
            'amount_adjusted' => $request->amount_adjusted ?? 0,
            'profit_adjusted' => $request->profit_adjusted ?? 0,
            'affected_products' => $request->affected_products ? json_encode($request->affected_products) : null,
            'status' => 'pending',
            'report_sent_at' => now(),
        ]);
        
        return redirect()->route('auditor.reports')
            ->with('success', 'Audit report submitted successfully!');
    }
}