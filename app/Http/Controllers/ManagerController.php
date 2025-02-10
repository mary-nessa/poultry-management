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
        return view('managers.index', compact('managers'));
    }

    public function create()
    {
        $users = User::where('role', 'MANAGER')->get();
        $branches = Branch::all();
        return view('managers.create', compact('users', 'branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'   => 'required|exists:users,id',
            'branch_id' => 'required|exists:branches,id',
        ]);

        Manager::create($validated);
        return redirect()->route('managers.index')->with('success', 'Manager created successfully.');
    }

    public function show(Manager $manager)
    {
        $manager->load(['user', 'branch']);
        return view('managers.show', compact('manager'));
    }

    public function edit(Manager $manager)
    {
        $users = User::where('role', 'MANAGER')->get();
        $branches = Branch::all();
        return view('managers.edit', compact('manager', 'users', 'branches'));
    }

    public function update(Request $request, Manager $manager)
    {
        $validated = $request->validate([
            'user_id'   => 'required|exists:users,id',
            'branch_id' => 'required|exists:branches,id',
        ]);

        $manager->update($validated);
        return redirect()->route('managers.index')->with('success', 'Manager updated successfully.');
    }

    public function destroy(Manager $manager)
    {
        $manager->delete();
        return redirect()->route('managers.index')->with('success', 'Manager deleted successfully.');
    }
}
