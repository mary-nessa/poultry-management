<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipment;
use App\Models\Supplier;

class EquipmentController extends Controller
{
    public function index()
    {
        $equipment = Equipment::with('supplier')->get();
        return view('equipments.index', compact('equipment'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        return view('equipment.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string',
            'quantity'     => 'required|integer',
            'cost'         => 'required|numeric',
            'purchase_date'=> 'required|date',
            'status'       => 'required|string',
            'supplier_id'  => 'required|exists:suppliers,id',
        ]);

        Equipment::create($validated);
        return redirect()->route('equipment.index')->with('success', 'Equipment added successfully.');
    }

    public function edit(Equipment $equipment)
    {
        $suppliers = Supplier::all();
        return view('equipment.edit', compact('equipment', 'suppliers'));
    }

    public function update(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'name'         => 'required|string',
            'quantity'     => 'required|integer',
            'cost'         => 'required|numeric',
            'purchase_date'=> 'required|date',
            'status'       => 'required|string',
            'supplier_id'  => 'required|exists:suppliers,id',
        ]);

        $equipment->update($validated);
        return redirect()->route('equipment.index')->with('success', 'Equipment updated successfully.');
    }

    public function destroy(Equipment $equipment)
    {
        $equipment->delete();
        return redirect()->route('equipment.index')->with('success', 'Equipment deleted successfully.');
    }
}
