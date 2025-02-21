<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Supplier;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index()
    {
        
        $medicines = Medicine::with('supplier')->get();
        return view('medicine.index', compact('medicines'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        return view('medicine.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string',
            'quantity'     => 'required|integer',
            'unit_cost'    => 'required|numeric',
            'expiry_date'  => 'nullable|date',
            'total_cost'   => 'required|numeric',
            'supplier_id'  => 'required|exists:suppliers,id',
            'purpose'      => 'required|string',
        ]);

        Medicine::create($validated);
        return redirect()->route('medicine.index')->with('success', 'Medicine created successfully.');
    }

    public function show(Medicine $medicine)
    {
        $medicine->load('supplier');
        return view('medicine.show', compact('medicine'));
    }

    public function edit(Medicine $medicine)
    {
        $suppliers = Supplier::all();
        return view('medicine.edit', compact('medicine', 'suppliers'));
    }

    public function update(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'name'         => 'required|string',
            'quantity'     => 'required|integer',
            'unit_cost'    => 'required|numeric',
            'expiry_date'  => 'nullable|date',
            'total_cost'   => 'required|numeric',
            'supplier_id'  => 'required|exists:suppliers,id',
            'purpose'      => 'required|string',
        ]);

        $medicine->update($validated);
        return redirect()->route('medicine.index')->with('success', 'Medicine updated successfully.');
    }

    public function destroy(Medicine $medicine)
    {
        $medicine->delete();
        return redirect()->route('medicine.index')->with('success', 'Medicine deleted successfully.');
    }
}
