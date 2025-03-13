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
    public function index(Request $request)
{
    $perPage = $request->input('per_page', 10);
    $query = Branch::with(['manager', 'users']);

    // Apply filters
    if ($request->has('name') && $request->name !== '') {
        $query->where('name', 'like', '%' . $request->name . '%');
    }

    if ($request->has('location') && $request->location !== '') {
        $query->where('location', 'like', '%' . $request->location . '%');
    }

    $branches = $query->paginate($perPage);
    
    $managers = User::where('branch_id', null)
        ->whereHas('roles', function($query) {
            $query->where('name', 'manager');
        })->get();
        
    if ($request->ajax()) {
        return response()->json([
            'branches' => $branches->items(),
            'pagination' => [
                'total' => $branches->total(),
                'per_page' => $branches->perPage(),
                'current_page' => $branches->currentPage(),
                'last_page' => $branches->lastPage(),
                'from' => $branches->firstItem(),
                'to' => $branches->lastItem()
            ]
        ]);
    }

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
        
        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Branch created successfully'
            ]);
        }
        
        return redirect()->route('branches.index')->with('success', 'Branch created successfully.');
    }

    public function assignBranch(Request $request)
    {
        try {
            $validated = $request->validate([
                'branch_id' => 'required|exists:branches,id',
                'manager_id' => 'required|exists:users,id',
            ]);

            $existingManager = Branch::where('manager_id', $validated['manager_id'])
                ->where('id', '!=', $validated['branch_id'])
                ->first();

            if ($existingManager) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Manager is already assigned to another branch.'
                ], 422);
            }

            DB::beginTransaction();

            try {
                $user = User::findOrFail($validated['manager_id']);
                $user->branch_id = $validated['branch_id'];
                $user->save();

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

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Branch updated successfully'
            ]);
        }

        return redirect()->route('branches.index')->with('success', 'Branch updated successfully.');
    }

    public function destroy(Branch $branch, Request $request)
    {
        if ($branch->users()->count() > 0) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cannot delete branch with assigned users.'
                ], 422);
            }
            return back()->withErrors(['error' => 'Cannot delete branch with assigned users.']);
        }

        if ($branch->manager) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cannot delete branch with an assigned manager.'
                ], 422);
            }
            return back()->withErrors(['error' => 'Cannot delete branch with an assigned manager.']);
        }

        $branch->delete();
        
        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Branch deleted successfully'
            ]);
        }

        return redirect()->route('branches.index')->with('success', 'Branch deleted successfully.');
    }
}