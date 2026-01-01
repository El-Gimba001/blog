<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Emporia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlertController extends Controller
{
    /**
     * Display low stock alerts
     */
    public function lowStock(Request $request)
    {
        $user = Auth::user();
        
        // Get stores user has access to
        $accessibleStores = $user->getAllAccessibleEmporia();
        $storeIds = $accessibleStores->pluck('id')->toArray();
        
        // Query low stock products
        $query = Product::whereIn('emporia_id', $storeIds)
            ->whereColumn('quantity', '<=', 'reorder_point')
            ->where(function($q) {
                $q->whereNull('alert_sent_at')
                  ->orWhere('alert_sent_at', '<', now()->subHours(24));
            })
            ->with('emporium');
            
        // Filter by store if specified
        if ($request->has('store_id')) {
            $storeId = $request->store_id;
            if ($user->hasEmporiumAccess($storeId)) {
                $query->where('emporia_id', $storeId);
            }
        }
        
        // Filter by severity
        if ($request->has('severity')) {
            if ($request->severity == 'critical') {
                $query->whereRaw('quantity <= (reorder_point * 0.5)');
            } elseif ($request->severity == 'warning') {
                $query->whereRaw('quantity > (reorder_point * 0.5) AND quantity <= reorder_point');
            }
        }
        
        $lowStockProducts = $query->orderBy('quantity', 'asc')->paginate(50);
        
        // Get stores for filter dropdown
        $stores = $accessibleStores;
        
        // Count alerts by severity
        $alertCounts = [
            'total' => Product::whereIn('emporia_id', $storeIds)
                ->whereColumn('quantity', '<=', 'reorder_point')
                ->count(),
            'critical' => Product::whereIn('emporia_id', $storeIds)
                ->whereRaw('quantity <= (reorder_point * 0.5)')
                ->count(),
            'warning' => Product::whereIn('emporia_id', $storeIds)
                ->whereRaw('quantity > (reorder_point * 0.5) AND quantity <= reorder_point')
                ->count(),
        ];
        
        return view('alerts.low-stock', compact('lowStockProducts', 'stores', 'alertCounts'));
    }
    
    /**
     * Mark low stock alert as read
     */
    public function markAsRead(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
        ]);
        
        $user = Auth::user();
        
        // Get stores user has access to
        $accessibleStores = $user->getAllAccessibleEmporia();
        $storeIds = $accessibleStores->pluck('id')->toArray();
        
        // Update alert_sent_at for accessible products only
        $updated = Product::whereIn('id', $request->product_ids)
            ->whereIn('emporia_id', $storeIds)
            ->update(['alert_sent_at' => now()]);
            
        return response()->json([
            'success' => true,
            'message' => "{$updated} alert(s) marked as read.",
            'updated_count' => $updated
        ]);
    }
    
    /**
     * Get low stock alert count for notification badge
     */
    public function getAlertCount(Request $request)
    {
        $user = Auth::user();
        
        // Get stores user has access to
        $accessibleStores = $user->getAllAccessibleEmporia();
        $storeIds = $accessibleStores->pluck('id')->toArray();
        
        $count = Product::whereIn('emporia_id', $storeIds)
            ->whereColumn('quantity', '<=', 'reorder_point')
            ->where(function($q) {
                $q->whereNull('alert_sent_at')
                  ->orWhere('alert_sent_at', '<', now()->subHours(24));
            })
            ->count();
            
        return response()->json([
            'count' => $count,
            'has_alerts' => $count > 0
        ]);
    }
    
    /**
     * Generate low stock report
     */
    public function generateReport(Request $request)
    {
        $user = Auth::user();
        
        // Get stores user has access to
        $accessibleStores = $user->getAllAccessibleEmporia();
        $storeIds = $accessibleStores->pluck('id')->toArray();
        
        $lowStockProducts = Product::whereIn('emporia_id', $storeIds)
            ->whereColumn('quantity', '<=', 'reorder_point')
            ->with('emporium')
            ->orderBy('emporia_id')
            ->orderBy('quantity', 'asc')
            ->get();
            
        // Group by store
        $groupedByStore = $lowStockProducts->groupBy('emporia_id');
        
        // Prepare report data
        $reportData = [
            'generated_at' => now()->format('Y-m-d H:i:s'),
            'total_alerts' => $lowStockProducts->count(),
            'stores' => [],
            'summary' => [
                'critical' => $lowStockProducts->filter(fn($p) => $p->quantity <= ($p->reorder_point * 0.5))->count(),
                'warning' => $lowStockProducts->filter(fn($p) => $p->quantity > ($p->reorder_point * 0.5) && $p->quantity <= $p->reorder_point)->count(),
            ]
        ];
        
        foreach ($groupedByStore as $storeId => $products) {
            $store = $products->first()->emporium;
            $reportData['stores'][] = [
                'store_name' => $store->name,
                'store_code' => $store->code,
                'alert_count' => $products->count(),
                'products' => $products->map(fn($p) => [
                    'name' => $p->name,
                    'code' => $p->code,
                    'current_quantity' => $p->quantity,
                    'reorder_point' => $p->reorder_point,
                    'severity' => $p->quantity <= ($p->reorder_point * 0.5) ? 'critical' : 'warning',
                    'last_alert' => $p->alert_sent_at ? $p->alert_sent_at->format('Y-m-d H:i') : 'Never',
                ])->toArray()
            ];
        }
        
        return response()->json($reportData);
    }
}