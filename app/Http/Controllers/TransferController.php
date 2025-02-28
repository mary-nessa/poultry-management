<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transfer;
use App\Models\Branch;
use App\Models\User;

class TransferController extends Controller
{
    public function index()
    {
        $transfers = Transfer::with(['fromBranch', 'toBranch', 'user'])->get();
        return view('transfers.index', compact('transfers'));
    }

    public function create()
    {
        $branches = Branch::all();
        $users = User::all();
        return view('transfers.create', compact('branches', 'users'));
    }

    public function store(Request $request)
    {
        
        // Add custom validation to check that from_branch_id and to_branch_id are different
        $validated = $request->validate([
            'type'           => 'required|string',
            'breed'          => 'nullable|string', // Breed is nullable by default
            'from_branch_id' => 'required|exists:branches,id|different:to_branch_id', // Ensure from and to branches are different
            'to_branch_id'   => 'required|exists:branches,id',
            'user_id'        => 'nullable|exists:users,id', // Will be set to current user if not provided
            'status'         => 'required|string|in:pending,approved,rejected',
            'quantity'       => 'required|integer|min:1',
            'notes'          => 'nullable|string',
        ]);

        // Set the user_id to the authenticated user if it's not provided
        $user_id = $request->input('user_id', auth()->user()->id);

        // Breed is required only if the type is 'birds', otherwise set it to null for 'eggs'
        // $breed = ($request->type == 'birds' && !$request->breed) ? 
        //          back()->withErrors(['breed' => 'Breed is required when transferring birds.']) : 
        //          ($request->type == 'birds' ? $request->breed : null);

        // Store the transfer
        Transfer::create([
            'type'           => $validated['type'],
            'breed'          => $validated['breed'],
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
        $transfer->load(['fromBranch', 'toBranch', 'user']);
        return view('transfers.show', compact('transfer'));
    }

    public function edit(Transfer $transfer)
    {
        $branches = Branch::all();
        $users = User::all();
        return view('transfers.edit', compact('transfer', 'branches', 'users'));
    }

    public function update(Request $request, Transfer $transfer)
    {
        // Add custom validation to check that from_branch_id and to_branch_id are different
        $validated = $request->validate([
            'type'           => 'required|string',
            'breed'          => 'nullable|string', // Breed is nullable by default
            'from_branch_id' => 'required|exists:branches,id|different:to_branch_id', // Ensure from and to branches are different
            'to_branch_id'   => 'required|exists:branches,id',
            'user_id'        => 'nullable|exists:users,id', // Will be set to current user if not provided
            'status'         => 'required|string|in:pending,approved,rejected',
            'quantity'       => 'required|integer|min:1',
            'notes'          => 'nullable|string',
        ]);

        // Set the user_id to the authenticated user if it's not provided
        $user_id = $request->input('user_id', auth()->user()->id);

        // Breed is required only if the type is 'birds', otherwise set it to null for 'eggs'
        $breed = ($request->type == 'birds' && !$request->breed) ? 
                 back()->withErrors(['breed' => 'Breed is required when transferring birds.']) : 
                 ($request->type == 'birds' ? $request->breed : null);

        // Update the transfer
        $transfer->update([
            'type'           => $validated['type'],
            'breed'          => $breed, // Only set breed if it's birds
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
