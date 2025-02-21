<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EggTransfer;
use App\Models\EggTray;
use App\Models\Branch;

class TransferController extends Controller
{
    public function index()
    {
        $eggTransfers = EggTransfer::with(['eggTray', 'fromBranch', 'toBranch'])->get();
        return view('egg_transfers.index', compact('eggTransfers'));
    }

    public function create()
    {
        $eggTrays = EggTray::all();
        $branches = Branch::all();
        return view('egg_transfers.create', compact('eggTrays', 'branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'egg_tray_id'     => 'required|exists:egg_trays,id',
            'from_branch_id'  => 'required|exists:branches,id',
            'to_branch_id'    => 'required|exists:branches,id',
            'quantity'        => 'required|integer',
            'transfer_date'   => 'required|date',
            'notes'           => 'nullable|string',
        ]);

        EggTransfer::create($validated);
        return redirect()->route('egg_transfers.index')->with('success', 'Egg Transfer created successfully.');
    }

    public function show(EggTransfer $eggTransfer)
    {
        $eggTransfer->load(['eggTray', 'fromBranch', 'toBranch']);
        return view('egg_transfers.show', compact('eggTransfer'));
    }

    public function edit(EggTransfer $eggTransfer)
    {
        $eggTrays = EggTray::all();
        $branches = Branch::all();
        return view('egg_transfers.edit', compact('eggTransfer', 'eggTrays', 'branches'));
    }

    public function update(Request $request, EggTransfer $eggTransfer)
    {
        $validated = $request->validate([
            'egg_tray_id'     => 'required|exists:egg_trays,id',
            'from_branch_id'  => 'required|exists:branches,id',
            'to_branch_id'    => 'required|exists:branches,id',
            'quantity'        => 'required|integer',
            'transfer_date'   => 'required|date',
            'notes'           => 'nullable|string',
        ]);

        $eggTransfer->update($validated);
        return redirect()->route('egg_transfers.index')->with('success', 'Egg Transfer updated successfully.');
    }

    public function destroy(EggTransfer $eggTransfer)
    {
        $eggTransfer->delete();
        return redirect()->route('egg_transfers.index')->with('success', 'Egg Transfer deleted successfully.');
    }
}
