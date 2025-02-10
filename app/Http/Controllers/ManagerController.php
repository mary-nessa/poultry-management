<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manager;
use App\Models\User;
use App\Models\Branch;

class ManagerController extends Controller
{
    public function index()
    {
        $managers = Manager::with(['user', 'branch'])->get();
        $users = User::where('role', 'MANAGER')->get();
        $branches = Branch::all();
        return view('managers.index', compact('managers', 'users', 'branches'));
    }

    public function create()
    {
        if (request()->ajax()) {
            $users = User::where('role', 'MANAGER')->get();
            $branches = Branch::all();
            return response()->json([
                'users' => $users,
                'branches' => $branches
            ]);
        }
        return abort(404);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'   => 'required|exists:users,id',
            'branch_id' => 'required|exists:branches,id',
        ]);

        // Check if user is already a manager
        if (Manager::where('user_id', $validated['user_id'])->exists()) {
            return back()->withErrors(['user_id' => 'This user is already a manager.']);
        }

        // Check if branch already has a manager
        if (Manager::where('branch_id', $validated['branch_id'])->exists()) {
            return back()->withErrors(['branch_id' => 'This branch already has a manager.']);
        }

        // Update user role to MANAGER if not already
        $user = User::find($validated['user_id']);
        if ($user->role !== 'MANAGER') {
            $user->update(['role' => 'MANAGER']);
        }

        Manager::create($validated);
        return redirect()->route('managers.index')->with('success', 'Manager assigned successfully.');
    }

    public function show(Manager $manager)
    {
        if (request()->ajax()) {
            return response()->json($manager->load(['user', 'branch']));
        }
        return abort(404);
    }

    public function edit(Manager $manager)
    {
        if (request()->ajax()) {
            return response()->json($manager->load(['user', 'branch']));
        }
        return abort(404);
    }

    public function update(Request $request, Manager $manager)
    {
        $validated = $request->validate([
            'user_id'   => 'required|exists:users,id',
            'branch_id' => 'required|exists:branches,id',
        ]);

        // Check if another manager exists for this user
        if (Manager::where('user_id', $validated['user_id'])
            ->where('id', '!=', $manager->id)
            ->exists()) {
            return back()->withErrors(['user_id' => 'This user is already a manager.']);
        }

        // Check if another manager exists for this branch
        if (Manager::where('branch_id', $validated['branch_id'])
            ->where('id', '!=', $manager->id)
            ->exists()) {
            return back()->withErrors(['branch_id' => 'This branch already has a manager.']);
        }

        // Update user role to MANAGER if not already
        $user = User::find($validated['user_id']);
        if ($user->role !== 'MANAGER') {
            $user->update(['role' => 'MANAGER']);
        }

        // If the old user is being replaced, update their role if they have no other manager positions
        if ($manager->user_id !== $validated['user_id']) {
            $oldUser = User::find($manager->user_id);
            if (!Manager::where('user_id', $oldUser->id)->where('id', '!=', $manager->id)->exists()) {
                $oldUser->update(['role' => 'WORKER']);
            }
        }

        $manager->update($validated);
        return redirect()->route('managers.index')->with('success', 'Manager updated successfully.');
    }

    public function destroy(Manager $manager)
    {
        // Update user role to WORKER if they have no other manager positions
        $user = User::find($manager->user_id);
        if (!Manager::where('user_id', $user->id)->where('id', '!=', $manager->id)->exists()) {
            $user->update(['role' => 'WORKER']);
        }

        $manager->delete();
        return redirect()->route('managers.index')->with('success', 'Manager removed successfully.');
    }
}
