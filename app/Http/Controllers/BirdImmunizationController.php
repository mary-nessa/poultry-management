<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BirdImmunisation;
use App\Models\Bird;

class BirdImmunisationController extends Controller
{
    public function index()
    {
        $immunisations = BirdImmunisation::with('bird')->get();
        return view('bird_immunisations.index', compact('immunisations'));
    }

    public function create()
    {
        $birds = Bird::all();
        return view('bird_immunisations.create', compact('birds'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bird_id'            => 'required|exists:birds,id',
            'vaccine_name'       => 'required|string',
            'immunisation_date'  => 'required|date',
            // Validate additional fields as needed
        ]);

        BirdImmunisation::create($validated);
        return redirect()->route('bird_immunisations.index')->with('success', 'Immunisation record created successfully.');
    }

    public function show(BirdImmunisation $birdImmunisation)
    {
        $birdImmunisation->load('bird');
        return view('bird_immunisations.show', compact('birdImmunisation'));
    }

    public function edit(BirdImmunisation $birdImmunisation)
    {
        $birds = Bird::all();
        return view('bird_immunisations.edit', compact('birdImmunisation', 'birds'));
    }

    public function update(Request $request, BirdImmunisation $birdImmunisation)
    {
        $validated = $request->validate([
            'bird_id'           => 'required|exists:birds,id',
            'vaccine_name'      => 'required|string',
            'immunisation_date' => 'required|date',
        ]);

        $birdImmunisation->update($validated);
        return redirect()->route('bird_immunisations.index')->with('success', 'Immunisation record updated successfully.');
    }

    public function destroy(BirdImmunisation $birdImmunisation)
    {
        $birdImmunisation->delete();
        return redirect()->route('bird_immunisations.index')->with('success', 'Immunisation record deleted successfully.');
    }
}
