<?php

namespace App\Http\Controllers;

use App\Models\ImmunizationRecord;
use App\Models\ChickPurchase;
use App\Models\Medicine;
use Illuminate\Http\Request;

class BirdImmunizationController extends Controller
{
    public function index()
    {
        $immunizations = ImmunizationRecord::with([
            'chickPurchase.poultry',
            'chickPurchase.branch',
            'vaccine'
        ])->get();

        // Collections for the create/edit modals
        $chickPurchases = ChickPurchase::with(['poultry', 'branch'])->get();
        $vaccines = Medicine::all();

        return view('bird_immunizations.index', compact('immunizations', 'chickPurchases', 'vaccines'));
    }

    public function create()
    {
        $chickPurchases = ChickPurchase::with(['bird', 'branch'])->get();
        $vaccines = Medicine::all();

        return view('bird_immunizations.create', compact('chickPurchases', 'vaccines'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'chick_purchase_id' => 'required|exists:chick_purchases,id',
            'vaccine_id'        => 'required|exists:medicines,id',
            'immunization_date' => 'required|date',
            'next_due_date'     => 'required|date',
            'notes'             => 'nullable|string',
            'number_immunized'  => 'required|integer',
            'age_category'      => 'required|string',
        ]);

        ImmunizationRecord::create($validated);

        return redirect()
            ->route('bird-immunizations.index')
            ->with('success', 'Immunization record created successfully.');
    }

    public function show(ImmunizationRecord $immunizationRecord)
    {
        // Load the related chickPurchase, bird, branch, and vaccine data
        $immunizationRecord->load(['chickPurchase.bird', 'chickPurchase.branch', 'vaccine']);

        // Return JSON for modal display
        return response()->json($immunizationRecord);
    }

    public function edit(ImmunizationRecord $immunizationRecord)
    {
        // Return JSON for modal population
        return response()->json($immunizationRecord);
    }

    public function update(Request $request, ImmunizationRecord $immunizationRecord)
    {
        $validated = $request->validate([
            'chick_purchase_id' => 'required|exists:chick_purchases,id',
            'vaccine_id'        => 'required|exists:medicines,id',
            'immunization_date' => 'required|date',
            'next_due_date'     => 'required|date',
            'notes'             => 'nullable|string',
            'number_immunized'  => 'required|integer',
            'age_category'      => 'required|string',
        ]);

        $immunizationRecord->update($validated);

        return redirect()
            ->route('bird-immunizations.index')
            ->with('success', 'Immunization record updated successfully.');
    }

    public function destroy(ImmunizationRecord $immunizationRecord)
    {
        $immunizationRecord->delete();

        return redirect()
            ->route('bird-immunizations.index')
            ->with('success', 'Immunization record deleted successfully.');
    }
}
