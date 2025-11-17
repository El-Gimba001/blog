<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration form.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle registration requests.
     */
    public function store(Request $request): RedirectResponse
    {
        // ✅ Validate input
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => [
                'required',
                'confirmed',
                'min:6',                     // at least 6 characters
                'regex:/^[A-Za-z0-9]+$/',    // letters and digits only
            ],
        ], 

        [
            // ✅ Custom error messages for clarity
            'password.regex' => 'Password must contain only letters and numbers.',
            'password.min' => 'Password must be at least 6 characters long.',
        ]);

        // ✅ Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // ✅ Fire registration event
        event(new Registered($user));

        // ✅ Auto-login the user
        Auth::login($user);

        // ✅ Redirect to dashboard
        return redirect()->route('dashboard');
    }
}