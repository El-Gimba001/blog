<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Redirect based on user role to existing pages
        if ($user->isAdministrator()) {
            return view('users-panels.administrator');  // New admin panel
        } elseif ($user->isManager()) {
            return view('users-panels.manager');  // Current dashboard (to be moved)
        } elseif ($user->isAuditor()) {
            return view('users-panels.auditor');  // New auditor panel
        } elseif ($user->isSalesUser()) {
            return redirect()->route('sales.entry');  // Existing sales entry
        } elseif ($user->isStoreManager()) {
            return redirect()->route('products.index');  // Existing stock list
        }

        // Fallback to manager panel
        return view('users-panels.manager');
    }
}