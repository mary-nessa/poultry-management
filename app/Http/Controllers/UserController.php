<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Branch;
use App\Models\Manager;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        // Eager-load branch and dailyActivities relationships
        $users = User::with(['branch', 'roles'])->get();
        $roles = Role::all();
        $branches = Branch::all();
        return view('users.index', compact('users', 'branches', 'roles'));
    }

    public function create()
    {
        if (request()->ajax()) {
            $branches = Branch::all();
            return response()->json([
                'branches' => $branches
            ]);
        }
        return abort(404);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|string|min:6|confirmed',
            'role'      => 'required|string|in:ADMIN,MANAGER,WORKER',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        // Hash the password
        $validated['password'] = Hash::make($validated['password']);

        // Create the user
        $user = User::create($validated);

        // If the user is a manager, create a Manager record
        if ($user->role === 'MANAGER' && $validated['branch_id']) {
            Manager::create([
                'user_id'   => $user->id,
                'branch_id' => $validated['branch_id'],
            ]);
        }

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        if (request()->ajax()) {
            return response()->json($user->load(['branch', 'dailyActivities']));
        }
        return abort(404);
    }

    public function edit(User $user)
    {
        if (request()->ajax()) {
            return response()->json($user->load('branch'));
        }
        return abort(404);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,'.$user->id,
            'role'      => 'required|string|in:ADMIN,MANAGER,WORKER',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        // Update user
        $user->update($validated);

        // Handle manager role changes
        if ($user->role === 'MANAGER') {
            // Create or update manager record
            Manager::updateOrCreate(
                ['user_id' => $user->id],
                ['branch_id' => $validated['branch_id']]
            );
        } else {
            // Remove manager record if role is changed from manager
            Manager::where('user_id', $user->id)->delete();
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        // Delete associated manager record if exists
        Manager::where('user_id', $user->id)->delete();

        // Delete the user
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
