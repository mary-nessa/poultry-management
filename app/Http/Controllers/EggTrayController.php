<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EggTray;
use App\Models\Branch;

class EggTrayController extends Controller
{
    public function index()
    {
        $eggTrays = EggTray::with('branch')->get();
        return view('egg_trays.index', compact('eggTrays'));
    }

    public function create()
    {
        $branches = Branch::all();
        return view('egg_trays.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tray_type'    => 'required|string',
            'total_eggs'   => 'required|integer',
            'damaged_eggs' => 'nullable|integer',
            'collected_at' => 'required|date',
            'status'       => 'required|string',
            'branch_id'    => 'required|exists:branches,id',
        ]);

        EggTray::create($validated);
        return redirect()->route('egg_trays.index')->with('success', 'Egg Tray created successfully.');
    }

    public function show(EggTray $eggTray)
    {
        $eggTray->load('branch');
        return view('egg_trays.show', compact('eggTray'));
    }

    public function edit(EggTray $eggTray)
    {
        $branches = Branch::all();
        return view('egg_trays.edit', compact('eggTray', 'branches'));
    }

    public function update(Request $request, EggTray $eggTray)
    {
        $validated = $request->validate([
            'tray_type'    => 'required|string',
            'total_eggs'   => 'required|integer',
            'damaged_eggs' => 'nullable|integer',
            'collected_at' => 'required|date',
            'status'       => 'required|string',
            'branch_id'    => 'required|exists:branches,id',
        ]);

        $eggTray->update($validated);
        return redirect()->route('egg_trays.index')->with('success', 'Egg Tray updated successfully.');
    }

    public function destroy(EggTray $eggTray)
    {
        $eggTray->delete();
        return redirect()->route('egg_trays.index')->with('success', 'Egg Tray deleted successfully.');
    }
}
