<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show the login page.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle the login process.
     */
    public function store(Request $request): RedirectResponse
    {
        // ✅ Validate the inputs (basic checks before database lookup)
        $request->validate([
            'email' => ['required', 'email'],
            'password' => [
                'required',
                'min:6',
                'regex:/^[A-Za-z0-9]+$/', // only letters and digits
            ],
        ]);

        // 🔍 Check if the user exists
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // ❌ Email not found
            return back()->withErrors([
                'email' => 'Oops! this account didn\'t exist.',
            ])->onlyInput('email');
        }

        // 🔑 Check password match
        if (!Hash::check($request->password, $user->password)) {
            // ❌ Wrong password
            return back()->withErrors([
                'password' => 'Incorrect password! Please checkout the password.',
            ])->onlyInput('email');
        }

        // ✅ Success: log the user in
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        // 🎯 ROLE-BASED REDIRECTION AFTER LOGIN
        if ($user->isSalesUser()) {
            return redirect()->route('sales.entry'); // Direct to sales entry
        } elseif ($user->isStoreManager()) {
            return redirect()->route('products.index'); // Direct to stock list
        } elseif ($user->isAdministrator() || $user->isManager() || $user->isAuditor()) {
            return redirect()->route('dashboard'); // Goes to respective panels
        }

        // Fallback
        return redirect()->route('dashboard');
    } // ← THIS CLOSING BRACE WAS MISSING

    /**
     * Logout and destroy the session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}