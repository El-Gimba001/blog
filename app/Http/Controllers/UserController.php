<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Show form for creating a new user (for managers).
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'min:6', 'regex:/^[A-Za-z0-9]+$/'],
            'role'     => 'required|string|in:administrator,manager,auditor,sales_user,store_manager', // UPDATED
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'], // FIXED: was $request->role
        ]);

        return redirect()->route('dashboard')->with('success', 'User created successfully!');
    }

    /**
     * Show all users for management.
     */
    public function manage()
    {
        $users = User::all();
        return view('users.manage-users', compact('users'));
    }

    /**
     * Edit a user.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    /**
     * Update a user's details (name, email, role).
     */
    public function update(Request $request, $id)
    {
        // Debug: Log the incoming request
        \Log::info('=== USER UPDATE DEBUG START ===');
        \Log::info('User Update Request Data:', [
            'user_id' => $id,
            'submitted_name' => $request->name,
            'submitted_email' => $request->email,
            'submitted_role' => $request->role,
            'all_request_data' => $request->all()
        ]);

        // Validate the incoming request
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email,' . $id,
            'role'     => 'required|string|in:administrator,manager,auditor,sales_user,store_manager', // UPDATED
        ]);

        \Log::info('Validation passed with data:', $validated);

        // Find the user and update
        $user = User::findOrFail($id);
        
        \Log::info('User before update:', [
            'current_name' => $user->name,
            'current_email' => $user->email,
            'current_role' => $user->role
        ]);

        $user->update($validated);

        // Debug: Log the result
        \Log::info('User after update:', [
            'new_name' => $user->name,
            'new_email' => $user->email,
            'new_role' => $user->role
        ]);
        \Log::info('=== USER UPDATE DEBUG END ===');

        return redirect()->route('users.manage')->with('success', 'User updated successfully!');
    }

    /**
     * Update a user's password.
     */
    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'password' => ['required', 'min:6', 'regex:/^[A-Za-z0-9]+$/'],
        ]);

        $user = User::findOrFail($id);
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('users.manage')->with('success', 'Password updated successfully!');
    }

    /**
     * Delete a user.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.manage')->with('success', 'User deleted successfully!');
    }
}