<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bird;
use App\Models\Branch;

class BirdController extends Controller
{
    public function index()
    {
        $birds = Bird::with('branch')->get();
        return view('birds.index', compact('birds'));
    }

    public function create()
    {
        $branches = Branch::all();
        return view('birds.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type'          => 'required|string',
            'counts'        => 'required|integer',
            'dates'         => 'required|date',
            'purchase_cost' => 'required|numeric',
            'branch_id'     => 'required|exists:branches,id',
            // Additional fields can be validated here
        ]);

        Bird::create($validated);
        return redirect()->route('birds.index')->with('success', 'Bird created successfully.');
    }

    public function show(Bird $bird)
    {
        $bird->load('branch', 'birdImmunisations');
        return view('birds.show', compact('bird'));
    }

    public function edit(Bird $bird)
    {
        $branches = Branch::all();
        return view('birds.edit', compact('bird', 'branches'));
    }

    public function update(Request $request, Bird $bird)
    {
        $validated = $request->validate([
            'type'          => 'required|string',
            'counts'        => 'required|integer',
            'dates'         => 'required|date',
            'purchase_cost' => 'required|numeric',
            'branch_id'     => 'required|exists:branches,id',
        ]);

        $bird->update($validated);
        return redirect()->route('birds.index')->with('success', 'Bird updated successfully.');
    }

    public function destroy(Bird $bird)
    {
        $bird->delete();
        return redirect()->route('birds.index')->with('success', 'Bird deleted successfully.');
    }
}
