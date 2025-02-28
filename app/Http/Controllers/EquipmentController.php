<?php
namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Supplier;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function index()
    {
        $equipment = Equipment::with('supplier')->get();
        $suppliers = Supplier::all(); // Fetch all suppliers

        return view('equipments.index', compact('equipment', 'suppliers'));
    }

    public function create()
    {
        $suppliers = Supplier::all(); // Fetch all suppliers
        return view('equipments.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        // Validation of incoming request
        $request->validate([
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/', // Only allow letters and spaces
            'quantity' => 'required|numeric',
            'unit_cost' => 'required|numeric',
            'purchase_date' => 'required|date',
            'status' => 'required|string',
            'supplier_id' => 'nullable|exists:suppliers,id',  // Make supplier_id nullable
        ]);

        // Check if the equipment with the same name already exists
        if (Equipment::where('name', $request->name)->exists()) {
            return back()->withErrors(['name' => 'This equipment already exists.']);
        }

        // Create the equipment record
        $equipment = Equipment::create([
            'name' => $request->name,
            'quantity' => $request->quantity,
            'unit_cost' => $request->unit_cost,
            'total_cost' => $request->quantity * $request->unit_cost,
            'purchase_date' => $request->purchase_date,
            'status' => $request->status,
            'supplier_id' => $request->supplier_id,  // supplier_id can be null
        ]);

        // Redirect to the index page with a success message
        return redirect()->route('equipments.index')->with('success', 'Equipment added successfully');
    }

    public function edit($id)
    {
        // Retrieve the equipment with its supplier data
        $equipment = Equipment::with('supplier')->findOrFail($id);
        $suppliers = Supplier::all(); // Fetch all suppliers for the edit form

        // Return the edit view with equipment and suppliers
        return view('equipments.edit', compact('equipment', 'suppliers'));
    }

    public function update(Request $request, $id)
    {
        // Validation of the update request
        $request->validate([
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/', // Only allow letters and spaces
            'quantity' => 'required|numeric',
            'unit_cost' => 'required|numeric',
            'purchase_date' => 'required|date',
            'status' => 'required|string',
            'supplier_id' => 'nullable|exists:suppliers,id', // Make supplier_id nullable
        ]);

        // Check if the equipment with the same name already exists, but exclude the current equipment
        if (Equipment::where('name', $request->name)->where('id', '!=', $id)->exists()) {
            return back()->withErrors(['name' => 'This equipment name already exists.']);
        }

        // Find the equipment record to update
        $equipment = Equipment::findOrFail($id);

        // Update the equipment record
        $equipment->update([
            'name' => $request->name,
            'quantity' => $request->quantity,
            'unit_cost' => $request->unit_cost,
            'total_cost' => $request->quantity * $request->unit_cost,
            'purchase_date' => $request->purchase_date,
            'status' => $request->status,
            'supplier_id' => $request->supplier_id,  // supplier_id can be null
        ]);

        // Redirect to the index page with a success message
        return redirect()->route('equipments.index')->with('success', 'Equipment updated successfully');
    }

    public function destroy($id)
    {
        // Find the equipment record to delete
        $equipment = Equipment::findOrFail($id);
        
        // Delete the equipment record
        $equipment->delete();
        
        // Redirect to the index page with a success message
        return redirect()->route('equipments.index')->with('success', 'Equipment deleted successfully');
    }

    public function show($id)
    {
        $equipment = Equipment::findOrFail($id);  // Get the equipment by its ID
        return view('equipments.show', compact('equipment'));  // Pass to the show view
    }
}
