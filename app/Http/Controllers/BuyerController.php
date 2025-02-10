<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buyer;
use App\Models\Branch;

class BuyerController extends Controller
{
    public function index()
    {
        $buyers = Buyer::with('branch')->get();
        return view('buyers.index', compact('buyers'));
    }

    public function create()
    {
        $branches = Branch::all();
        return view('buyers.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'contact_info' => 'required|string',
            'buyer_type'   => 'required|string',
            'branch_id'    => 'nullable|exists:branches,id',
        ]);

        Buyer::create($validated);
        return redirect()->route('buyers.index')->with('success', 'Buyer created successfully.');
    }

    public function show(Buyer $buyer)
    {
        $buyer->load('branch', 'sales');
        return view('buyers.show', compact('buyer'));
    }

    public function edit(Buyer $buyer)
    {
        $branches = Branch::all();
        return view('buyers.edit', compact('buyer', 'branches'));
    }

    public function update(Request $request, Buyer $buyer)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'contact_info' => 'required|string',
            'buyer_type'   => 'required|string',
            'branch_id'    => 'nullable|exists:branches,id',
        ]);

        $buyer->update($validated);
        return redirect()->route('buyers.index')->with('success', 'Buyer updated successfully.');
    }

    public function destroy(Buyer $buyer)
    {
        $buyer->delete();
        return redirect()->route('buyers.index')->with('success', 'Buyer deleted successfully.');
    }
}
