<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function index()
    {
        $feeds = Feed::with('supplier')->get();
        return view('feeds.index', compact('feeds'));
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
            'quantity'     => 'required|integer',
            'cost'         => 'required|numeric',
            'purchase_date'=> 'required|date',
            'expiry_date'  => 'nullable|date',
            'supplier_id'  => 'required|exists:suppliers,id',
        ]);

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
            'quantity'     => 'required|integer',
            'cost'         => 'required|numeric',
            'purchase_date'=> 'required|date',
            'expiry_date'  => 'nullable|date',
            'supplier_id'  => 'required|exists:suppliers,id',
        ]);

        $feed->update($validated);
        return redirect()->route('feeds.index')->with('success', 'Feed updated successfully.');
    }

    public function destroy(Feed $feed)
    {
        $feed->delete();
        return redirect()->route('feeds.index')->with('success', 'Feed deleted successfully.');
    }
}
