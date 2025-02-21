<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use Illuminate\Http\Request;

class BuyerController extends Controller
{
    // Display a listing of buyers
    public function index()
    {
        $buyers = Buyer::all();
        return view('buyers.index', compact('buyers'));
    }

    // Show the form for creating a new buyer
    public function create()
    {
        return view('buyers.create');
    }

    // Store a newly created buyer in the database
    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'contact_info' => 'nullable|string',
        'buyer_type' => 'required|in:WALKIN,REGULAR',  // This ensures only valid enum values
    ]);
    
    $buyer = Buyer::create([
        'name' => $validated['name'],
        'contact_info' => $validated['contact_info'],
        'buyer_type' => $validated['buyer_type'],  // This will now be either WALKIN or REGULAR
    ]);

    return redirect()->route('buyers.index')
        ->with('success', 'Buyer created successfully');
}
    // Show the form for editing the specified buyer
    public function edit($id)
    {
        $buyer = Buyer::findOrFail($id);
        return view('buyers.edit', compact('buyer'));
    }

    // Update the specified buyer in the database
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_info' => 'required|string',
            'buyer_type' => 'required|string',
        ]);

        $buyer = Buyer::findOrFail($id);
        $buyer->update([
            'name' => $request->input('name'),
            'contact_info' => $request->input('contact_info'),
            'buyer_type' => $request->input('buyer_type'),
        ]);

        return redirect()->route('buyers.index')->with('success', 'Buyer updated successfully!');
    }

    // Remove the specified buyer from the database
    public function destroy($id)
    {
        $buyer = Buyer::findOrFail($id);
        $buyer->delete();

        return redirect()->route('buyers.index')->with('success', 'Buyer deleted successfully!');
    }
}
