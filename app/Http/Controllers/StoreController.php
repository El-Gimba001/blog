<?php

namespace App\Http\Controllers;

use App\Models\Emporia;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    /**
     * Display store dashboard (default to user's primary store)
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // If user has no store assigned, redirect to general dashboard
        if (!$user->emporia_id) {
            return redirect()->route('dashboard')->with('error', 'No store assigned to your account.');
        }
        
        // Redirect to specific store dashboard
        return redirect()->route('store.dashboard.store', $user->emporia_id);
    }
    
    /**
     * Display dashboard for a specific store
     */
    // In StoreController.php, update the dashboardWithStore method:
    public function dashboardWithStore(Emporia $emporium)
        {
            $user = Auth::user();
            
            // Check if user has access to this store
            if (!$user->hasEmporiumAccess($emporium->id)) {
                abort(403, 'You do not have access to this store.');
            }
            
            // Store current store in session for context
            session(['current_emporium_id' => $emporium->id]);
            
            // Get products for this store only
            $products = Product::where('emporia_id', $emporium->id)
                ->orderBy('name')
                ->paginate(50);
            
            // Pass store info to your existing products view
            return view('products.index', [
                'products' => $products,
                'emporium' => $emporium, // Add store info
                'accessibleStores' => $user->getAllAccessibleEmporia(), // For store switcher
            ]);
        }
    /**
     * Switch store context
     */
    public function switchStore(Request $request)
    {
        $request->validate([
            'emporium_id' => 'required|exists:emporia,id'
        ]);
        
        $user = Auth::user();
        $emporiumId = $request->emporium_id;
        
        if (!$user->hasEmporiumAccess($emporiumId)) {
            return redirect()->back()->with('error', 'You do not have access to this store.');
        }
        
        // Store in session
        session(['current_emporium_id' => $emporiumId]);
        
        return redirect()->route('store.dashboard.store', $emporiumId)
            ->with('success', 'Store switched successfully.');
    }
    
    /**
     * Display store products
     */
    public function products(Emporia $emporium)
    {
        $user = Auth::user();
        
        if (!$user->hasEmporiumAccess($emporium->id)) {
            abort(403, 'You do not have access to this store.');
        }
        
        $products = Product::where('emporia_id', $emporium->id)
            ->orderBy('name')
            ->paginate(50);
            
        return view('store.products', compact('emporium', 'products'));
    }
    
    /**
     * Display store customers
     */
    public function customers(Emporia $emporium)
    {
        $user = Auth::user();
        
        if (!$user->hasEmporiumAccess($emporium->id)) {
            abort(403, 'You do not have access to this store.');
        }
        
        $customers = Customer::where('store_id', $emporium->id)
            ->orderBy('name')
            ->paginate(50);
            
        return view('store.customers', compact('emporium', 'customers'));
    }
    
    /**
     * Display store transactions
     */
    public function transactions(Emporia $emporium)
    {
        $user = Auth::user();
        
        if (!$user->hasEmporiumAccess($emporium->id)) {
            abort(403, 'You do not have access to this store.');
        }
        
        $transactions = Transaction::with('customer')
            ->where('emporia_id', $emporium->id)
            ->orderBy('created_at', 'desc')
            ->paginate(50);
            
        return view('store.transactions', compact('emporium', 'transactions'));
    }
    
    /**
     * Display store reports
     */
    public function reports(Emporia $emporium)
    {
        $user = Auth::user();
        
        if (!$user->hasEmporiumAccess($emporium->id)) {
            abort(403, 'You do not have access to this store.');
        }
        
        // You can add report data here
        return view('store.reports', compact('emporium'));
    }
}