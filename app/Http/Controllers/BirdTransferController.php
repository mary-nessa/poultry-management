<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BirdTransfer;
use App\Models\Bird;
use App\Models\Branch;

class BirdTransferController extends Controller
{
    public function index()
    {
        $birdTransfers = BirdTransfer::with(['fromBranch', 'toBranch', 'user'])->get();
        $branches = Branch::all();
        return view('bird-transfers.index', compact('birdTransfers', 'branches'));
    }

    public function create()
    {
        $birds = Bird::all();
        $branches = Branch::all();
        return view('bird_transfers.create', compact('birds', 'branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bird_id'         => 'required|exists:birds,id',
            'from_branch_id'  => 'required|exists:branches,id',
            'to_branch_id'    => 'required|exists:branches,id',
            'quantity'        => 'required|integer',
            'transfer_date'   => 'required|date',
            'notes'           => 'nullable|string',
        ]);

        BirdTransfer::create($validated);
        return redirect()->route('bird_transfers.index')->with('success', 'Bird Transfer created successfully.');
    }

    public function show(BirdTransfer $birdTransfer)
    {
        $birdTransfer->load(['bird', 'fromBranch', 'toBranch']);
        return view('bird_transfers.show', compact('birdTransfer'));
    }

    public function edit(BirdTransfer $birdTransfer)
    {
        $birds = Bird::all();
        $branches = Branch::all();
        return view('bird_transfers.edit', compact('birdTransfer', 'birds', 'branches'));
    }

    public function update(Request $request, BirdTransfer $birdTransfer)
    {
        $validated = $request->validate([
            'bird_id'         => 'required|exists:birds,id',
            'from_branch_id'  => 'required|exists:branches,id',
            'to_branch_id'    => 'required|exists:branches,id',
            'quantity'        => 'required|integer',
            'transfer_date'   => 'required|date',
            'notes'           => 'nullable|string',
        ]);

        $birdTransfer->update($validated);
        return redirect()->route('bird_transfers.index')->with('success', 'Bird Transfer updated successfully.');
    }

    public function destroy(BirdTransfer $birdTransfer)
    {
        $birdTransfer->delete();
        return redirect()->route('bird_transfers.index')->with('success', 'Bird Transfer deleted successfully.');
    }
}
