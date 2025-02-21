<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Manager;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::with(['manager', 'users'])->get();
        $managers = User::where('branch_id', null)
            ->whereHas('roles', function($query) {
                $query->where('name', 'manager');
            })->get();
        return view('branches.index', compact('branches', 'managers'));
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

    public function assignBranch(Request $request)
    {
        try {
            $validated = $request->validate([
                'branch_id' => 'required|exists:branches,id',
                'manager_id' => 'required|exists:users,id',
            ]);

            // Check if the manager is already assigned to a branch
            $existingManager = Branch::where('manager_id', $validated['manager_id'])
                ->where('id', '!=', $validated['branch_id'])
                ->first();

            if ($existingManager) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Manager is already assigned to another branch.'
                ]);
            }

            // Begin transaction
            DB::beginTransaction();

            try {
                // Update user's branch
                $user = User::findOrFail($validated['manager_id']);
                $user->branch_id = $validated['branch_id'];
                $user->save();

                // Update branch's manager
                $branch = Branch::findOrFail($validated['branch_id']);
                $branch->manager_id = $validated['manager_id'];
                $branch->save();

                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Manager assigned successfully'
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while assigning the manager'
            ], 500);
        }
    }

    public function show(Branch $branch)
    {

        return response()->json($branch->load(['manager', 'users']));
    }

    public function edit(Branch $branch)
    {

            return response()->json($branch);

    }

    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255|unique:branches,name,'.$branch->id,
            'location' => 'required|string|max:255',
            'manager_id' => 'nullable|exists:users,id',
        ]);

        if($validated['manager_id'] != null){
            $branch->manager_id = $validated['manager_id'];
        }
        $branch->name = $validated['name'];
        $branch->location = $validated['location'];
        $branch->save();

//        $branch->update($validated);
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
