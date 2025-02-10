<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Manager;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::with(['manager.user', 'users'])->get();
        return view('branches.index', compact('branches'));
    }

    public function create()
    {
        if (request()->ajax()) {
            return response()->json([]);
        }
        return abort(404);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255|unique:branches',
            'location' => 'required|string|max:255',
        ]);

        Branch::create($validated);
        return redirect()->route('branches.index')->with('success', 'Branch created successfully.');
    }

    public function show(Branch $branch)
    {
        if (request()->ajax()) {
            return response()->json($branch->load(['manager.user', 'users']));
        }
        return abort(404);
    }

    public function edit(Branch $branch)
    {
        if (request()->ajax()) {
            return response()->json($branch);
        }
        return abort(404);
    }

    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255|unique:branches,name,'.$branch->id,
            'location' => 'required|string|max:255',
        ]);

        $branch->update($validated);
        return redirect()->route('branches.index')->with('success', 'Branch updated successfully.');
    }

    public function destroy(Branch $branch)
    {
        // Check if branch has any users
        if ($branch->users()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete branch with assigned users.']);
        }

        // Check if branch has a manager
        if ($branch->manager) {
            return back()->withErrors(['error' => 'Cannot delete branch with an assigned manager.']);
        }

        $branch->delete();
        return redirect()->route('branches.index')->with('success', 'Branch deleted successfully.');
    }
}
