<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::with(['feeds', 'medicines', 'equipments', 'chickPurchases'])->get();
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'contact_info' => 'required|string',
        ]);

        $supplier = Supplier::create($validated);

        // If you want to associate other models like Feed, Medicine, Equipment, etc.
        // You can add logic here to associate those models as needed.
        
        return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully.');
    }

    public function show(Supplier $supplier)
    {
        // Include related models if needed
        $supplier->load(['feeds', 'medicines', 'equipments', 'chickPurchases']);
        return view('suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'contact_info' => 'required|string',
        ]);

        $supplier->update($validated);
        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    public function destroy(Supplier $supplier)
    {
        // Delete associated relationships before deleting the supplier (if necessary)
        // Example: $supplier->feeds()->delete(); (if you want to delete related feeds)
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
    }
}
