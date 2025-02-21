<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feed;
use App\Models\Supplier;

class FeedController extends Controller
{
    public function index()
    {
        $feeds = Feed::with('supplier')->get();
        $suppliers = Supplier::all(); // Fetch suppliers and pass them to the view
        return view('feeds.index', compact('feeds', 'suppliers'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        return view('feeds.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type'         => 'required|string',
            'quantity_kg'  => 'required|integer',
            'unit_cost'    => 'required|numeric',
            'purchase_date'=> 'required|date',
            'expiry_date'  => 'nullable|date',
            'supplier_id'  => 'nullable|exists:suppliers,id', // Make supplier optional
        ]);

        // Calculate total cost
        $validated['total_cost'] = $validated['quantity_kg'] * $validated['unit_cost'];

        Feed::create($validated);
        return redirect()->route('feeds.index')->with('success', 'Feed created successfully.');
    }

    public function show(Feed $feed)
    {
        $feed->load('supplier');
        return view('feeds.show', compact('feed'));
    }

    public function edit(Feed $feed)
    {
        $suppliers = Supplier::all();
        return view('feeds.edit', compact('feed', 'suppliers'));
    }

    public function update(Request $request, Feed $feed)
    {
        $validated = $request->validate([
            'type'         => 'required|string',
            'quantity_kg'  => 'required|integer',
            'unit_cost'    => 'required|numeric',
            'purchase_date'=> 'required|date',
            'expiry_date'  => 'nullable|date',
            'supplier_id'  => 'nullable|exists:suppliers,id', // Make supplier optional
        ]);

        // Calculate total cost
        $validated['total_cost'] = $validated['quantity_kg'] * $validated['unit_cost'];

        $feed->update($validated);
        return redirect()->route('feeds.index')->with('success', 'Feed updated successfully.');
    }

    public function destroy(Feed $feed)
    {
        $feed->delete();
        return redirect()->route('feeds.index')->with('success', 'Feed deleted successfully.');
    }
}
