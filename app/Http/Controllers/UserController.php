<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Branch;
use App\Models\Manager;

class UserController extends Controller
{
    public function index()
    {
        // Eager-load branch and dailyActivities relationships
        $users = User::with(['branch', 'dailyActivities'])->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        // Pass available branches (for non-admins)
        $branches = Branch::all();
        return view('users.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|string|min:6|confirmed',
            'role'      => 'required|string',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $user = User::create($validated);

        // If the user is a manager, create a Manager record
        if ($user->role === 'MANAGER') {
            Manager::create([
                'user_id'   => $user->id,
                'branch_id' => $validated['branch_id'],
            ]);
        }

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $user->load(['branch', 'dailyActivities']);
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $branches = Branch::all();
        return view('users.edit', compact('user', 'branches'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,'.$user->id,
            'role'      => 'required|string',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        $user->update($validated);
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
