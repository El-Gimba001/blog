<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Emporia;
use App\Models\CustomerLedger;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Customer::query();
        
        // Filter by store if user is not administrator
        if (!$user->isAdministrator()) {
            $currentStoreId = session('current_emporium_id', $user->emporia_id);
            $query->where('store_id', $currentStoreId);
        }
        
        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('customer_code', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Filter by customer type
        if ($request->has('type')) {
            $query->where('customer_type', $request->type);
        }
        
        // Filter by status
        if ($request->has('status')) {
            $query->where('is_active', $request->status == 'active');
        }
        
        $customers = $query->orderBy('name')->paginate(50);
        
        // Get stores for filter (if admin)
        $stores = $user->isAdministrator() ? Emporia::all() : collect();
        
        return view('customers.index', compact('customers', 'stores'));
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create()
    {
        $user = Auth::user();
        
        // Get stores user can assign customers to
        if ($user->isAdministrator()) {
            $stores = Emporia::where('is_active', true)->get();
        } else {
            $stores = $user->getAllAccessibleEmporia();
        }
        
        return view('customers.create', compact('stores'));
    }

    /**
     * Store a newly created customer.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'customer_type' => 'required|in:retail,wholesale,corporate',
            'credit_limit' => 'nullable|numeric|min:0',
            'store_id' => 'required|exists:emporia,id',
        ]);
        
        // Check if user has access to the selected store
        $user = Auth::user();
        if (!$user->isAdministrator() && !$user->hasEmporiumAccess($validated['store_id'])) {
            return redirect()->back()->with('error', 'You do not have access to this store.');
        }
        
        // Generate unique customer code
        $validated['customer_code'] = $this->generateCustomerCode($validated['customer_type']);
        $validated['current_balance'] = 0;
        $validated['is_active'] = true;
        $validated['created_by'] = $user->id;
        
        $customer = Customer::create($validated);
        
        return redirect()->route('customers.show', $customer)
            ->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified customer.
     */
    public function show(Customer $customer)
    {
        // Check access
        $user = Auth::user();
        if (!$user->isAdministrator() && !$user->hasEmporiumAccess($customer->store_id)) {
            abort(403, 'You do not have access to this customer.');
        }
        
        // Get ledger entries
        $ledgerEntries = CustomerLedger::where('customer_id', $customer->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        // Get recent transactions
        $recentTransactions = Transaction::where('customer_id', $customer->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        // Calculate summary
        $summary = [
            'total_debit' => CustomerLedger::where('customer_id', $customer->id)->sum('debit'),
            'total_credit' => CustomerLedger::where('customer_id', $customer->id)->sum('credit'),
            'total_transactions' => Transaction::where('customer_id', $customer->id)->count(),
        ];
        
        return view('customers.show', compact('customer', 'ledgerEntries', 'recentTransactions', 'summary'));
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(Customer $customer)
    {
        // Check access
        $user = Auth::user();
        if (!$user->isAdministrator() && !$user->hasEmporiumAccess($customer->store_id)) {
            abort(403, 'You do not have access to this customer.');
        }
        
        // Get stores user can assign customers to
        if ($user->isAdministrator()) {
            $stores = Emporia::where('is_active', true)->get();
        } else {
            $stores = $user->getAllAccessibleEmporia();
        }
        
        return view('customers.edit', compact('customer', 'stores'));
    }

    /**
     * Update the specified customer.
     */
    public function update(Request $request, Customer $customer)
    {
        // Check access
        $user = Auth::user();
        if (!$user->isAdministrator() && !$user->hasEmporiumAccess($customer->store_id)) {
            abort(403, 'You do not have access to this customer.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email,' . $customer->id,
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'customer_type' => 'required|in:retail,wholesale,corporate',
            'credit_limit' => 'nullable|numeric|min:0',
            'store_id' => 'required|exists:emporia,id',
            'is_active' => 'boolean',
        ]);
        
        // Check if user has access to the new store (if changed)
        if ($validated['store_id'] != $customer->store_id) {
            if (!$user->isAdministrator() && !$user->hasEmporiumAccess($validated['store_id'])) {
                return redirect()->back()->with('error', 'You do not have access to this store.');
            }
        }
        
        $customer->update($validated);
        
        return redirect()->route('customers.show', $customer)
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified customer.
     */
    public function destroy(Customer $customer)
    {
        // Check access
        $user = Auth::user();
        if (!$user->isAdministrator() && !$user->hasEmporiumAccess($customer->store_id)) {
            abort(403, 'You do not have access to this customer.');
        }
        
        // Check if customer has transactions
        if (Transaction::where('customer_id', $customer->id)->exists()) {
            return redirect()->back()->with('error', 'Cannot delete customer with existing transactions.');
        }
        
        // Check if customer has ledger entries
        if (CustomerLedger::where('customer_id', $customer->id)->exists()) {
            return redirect()->back()->with('error', 'Cannot delete customer with ledger entries.');
        }
        
        $customer->delete();
        
        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }
    
    /**
     * Display customer ledger
     */
    public function ledger(Customer $customer)
    {
        // Check access
        $user = Auth::user();
        if (!$user->isAdministrator() && !$user->hasEmporiumAccess($customer->store_id)) {
            abort(403, 'You do not have access to this customer.');
        }
        
        $ledgerEntries = CustomerLedger::where('customer_id', $customer->id)
            ->orderBy('created_at', 'desc')
            ->paginate(50);
            
        $summary = [
            'total_debit' => CustomerLedger::where('customer_id', $customer->id)->sum('debit'),
            'total_credit' => CustomerLedger::where('customer_id', $customer->id)->sum('credit'),
            'current_balance' => $customer->current_balance,
        ];
        
        return view('customers.ledger', compact('customer', 'ledgerEntries', 'summary'));
    }
    
    /**
     * Add ledger entry for customer
     */
    public function addLedgerEntry(Request $request, Customer $customer)
    {
        // Check access
        $user = Auth::user();
        if (!$user->isAdministrator() && !$user->hasEmporiumAccess($customer->store_id)) {
            abort(403, 'You do not have access to this customer.');
        }
        
        $validated = $request->validate([
            'transaction_type' => 'required|in:invoice,payment,credit_note,debit_note',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:500',
            'due_date' => 'nullable|date',
        ]);
        
        // Calculate new balance
        $oldBalance = $customer->current_balance;
        
        if (in_array($validated['transaction_type'], ['invoice', 'debit_note'])) {
            // Increases customer balance (debit)
            $newBalance = $oldBalance + $validated['amount'];
            $debit = $validated['amount'];
            $credit = 0;
        } else {
            // Decreases customer balance (credit)
            $newBalance = $oldBalance - $validated['amount'];
            $debit = 0;
            $credit = $validated['amount'];
        }
        
        // Create ledger entry
        CustomerLedger::create([
            'customer_id' => $customer->id,
            'transaction_type' => $validated['transaction_type'],
            'debit' => $debit,
            'credit' => $credit,
            'balance' => $newBalance,
            'description' => $validated['description'],
            'due_date' => $validated['due_date'],
            'status' => $validated['transaction_type'] == 'invoice' ? 'pending' : 'paid',
            'created_by' => $user->id,
        ]);
        
        // Update customer balance
        $customer->update(['current_balance' => $newBalance]);
        
        return redirect()->route('customers.ledger', $customer)
            ->with('success', 'Ledger entry added successfully.');
    }
    
    /**
     * Search customers for autocomplete
     */
    public function search(Request $request)
    {
        $user = Auth::user();
        $query = Customer::where('is_active', true);
        
        // Filter by store if user is not administrator
        if (!$user->isAdministrator()) {
            $currentStoreId = session('current_emporium_id', $user->emporia_id);
            $query->where('store_id', $currentStoreId);
        }
        
        if ($request->has('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('customer_code', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        $customers = $query->select('id', 'customer_code', 'name', 'phone', 'current_balance', 'credit_limit')
            ->orderBy('name')
            ->limit(20)
            ->get();
            
        return response()->json($customers);
    }
    
    /**
     * Generate unique customer code
     */
    private function generateCustomerCode($type)
    {
        $prefix = match($type) {
            'retail' => 'R',
            'wholesale' => 'W',
            'corporate' => 'C',
            default => 'C'
        };
        
        do {
            $code = $prefix . strtoupper(Str::random(6));
        } while (Customer::where('customer_code', $code)->exists());
        
        return $code;
    }
}