<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transfer;
use App\Models\Branch;
use App\Models\Breed;
use App\Models\User;

class TransferController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters from request
        $type = $request->input('type');
         $status = $request->input('status');
        $from_branch_id = $request->input('from_branch_id');
        $to_branch_id = $request->input('to_branch_id');
        $breed_id = $request->input('breed_id');

        // Start with base query
        $query = Transfer::with(['fromBranch', 'toBranch', 'user', 'breed']);

        // Apply filters if they exist
        if ($type) {
            $query->where('type', $type);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($from_branch_id) {
            $query->where('from_branch_id', $from_branch_id);
        }

        if ($to_branch_id) {
            $query->where('to_branch_id', $to_branch_id);
        }

        if ($breed_id) {
            $query->where('breed_id', $breed_id);
        }

        // Get paginated results
        $transfers = $query->paginate(5)->withQueryString();

        // Get data for filter dropdowns
        $branches = Branch::all();
        $breeds = Breed::all();
        
        // Define available options for select dropdowns
        $typeOptions = ['birds', 'eggs'];
        $statusOptions = ['pending', 'approved', 'rejected'];

        return view('transfers.index', compact(
            'transfers', 
            'branches', 
            'breeds',
            'typeOptions',
            'statusOptions',
            'type',
            'status',
            'from_branch_id',
            'to_branch_id',
            'breed_id'
        ));
    }

    public function create()
    {
        $branches = Branch::all();
        $users = User::all();
        $breeds = Breed::all();
        return view('transfers.create', compact('branches', 'users', 'breeds'));
    }

    // Rest of the controller methods remain unchanged
    public function store(Request $request)
    {
        // Add custom validation to check that from_branch_id and to_branch_id are different
        $validated = $request->validate([
            'type'           => 'required|string|in:birds,eggs',
            'breed_id'       => 'nullable|exists:breeds,id', // Now validating breed_id instead of breed string
            'from_branch_id' => 'required|exists:branches,id|different:to_branch_id', // Ensure from and to branches are different
            'to_branch_id'   => 'required|exists:branches,id',
            'user_id'        => 'nullable|exists:users,id', // Will be set to current user if not provided
            'status'         => 'required|string|in:pending,approved,rejected',
            'quantity'       => 'required|integer|min:1',
            'notes'          => 'nullable|string',
        ]);

        // Set the user_id to the authenticated user if it's not provided
        $user_id = $request->input('user_id', auth()->user()->id);

        // Breed is required only if the type is 'birds'
        if ($request->type == 'birds' && !$request->breed_id) {
            return back()->withErrors(['breed_id' => 'Breed is required when transferring birds.'])->withInput();
        }

        // Store the transfer
        Transfer::create([
            'type'           => $validated['type'],
            'breed_id'       => $validated['breed_id'], // Using breed_id now
            'from_branch_id' => $validated['from_branch_id'],
            'to_branch_id'   => $validated['to_branch_id'],
            'user_id'        => $user_id,
            'status'         => $validated['status'],
            'quantity'       => $validated['quantity'],
            'notes'          => $validated['notes'],
        ]);

        return redirect()->route('transfers.index')->with('success', 'Transfer created successfully.');
    }

    public function show(Transfer $transfer)
    {
        $transfer->load(['fromBranch', 'toBranch', 'user', 'breed']);
        return view('transfers.show', compact('transfer'));
    }

    public function edit(Transfer $transfer)
    {
        $branches = Branch::all();
        $users = User::all();
        $breeds = Breed::all();
        return view('transfers.edit', compact('transfer', 'branches', 'users', 'breeds'));
    }

    public function update(Request $request, Transfer $transfer)
    {
        // Add custom validation to check that from_branch_id and to_branch_id are different
        $validated = $request->validate([
            'type'           => 'required|string|in:birds,eggs',
            'breed_id'       => 'nullable|exists:breeds,id', // Now validating breed_id instead of breed string
            'from_branch_id' => 'required|exists:branches,id|different:to_branch_id', // Ensure from and to branches are different
            'to_branch_id'   => 'required|exists:branches,id',
            'user_id'        => 'nullable|exists:users,id', // Will be set to current user if not provided
            'status'         => 'required|string|in:pending,approved,rejected',
            'quantity'       => 'required|integer|min:1',
            'notes'          => 'nullable|string',
        ]);

        // Set the user_id to the authenticated user if it's not provided
        $user_id = $request->input('user_id', auth()->user()->id);

        // Breed is required only if the type is 'birds'
        if ($request->type == 'birds' && !$request->breed_id) {
            return back()->withErrors(['breed_id' => 'Breed is required when transferring birds.'])->withInput();
        }

        // Update the transfer
        $transfer->update([
            'type'           => $validated['type'],
            'breed_id'       => $validated['breed_id'], // Using breed_id now
            'from_branch_id' => $validated['from_branch_id'],
            'to_branch_id'   => $validated['to_branch_id'],
            'user_id'        => $user_id,
            'status'         => $validated['status'],
            'quantity'       => $validated['quantity'],
            'notes'          => $validated['notes'],
        ]);

        return redirect()->route('transfers.index')->with('success', 'Transfer updated successfully.');
    }

    public function destroy(Transfer $transfer)
    {
        $transfer->delete();
        return redirect()->route('transfers.index')->with('success', 'Transfer deleted successfully.');
    }
}