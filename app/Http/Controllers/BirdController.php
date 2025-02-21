<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bird;
use App\Models\Branch;
use App\Models\ChickPurchase;

class BirdController extends Controller
{
    public function index()
    {
        $birds = Bird::with(['branch', 'chickPurchase'])->get();
        $chickPurchases = ChickPurchase::all();
        $branches = Branch::all();
        return view('birds.index', compact('birds', 'branches', 'chickPurchases'));
    }

    public function create()
    {

    }

    public function store(Request $request)
    {
        try {

            $validated = $request->validate([
                'chick_purchase_id' => 'required|exists:chick_purchases,id',
                'hen_count' => 'required|integer|min:0',
                'cock_count' => 'required|integer|min:0',
                'laying_cycle_start_date' => 'nullable|date',
                'laying_cycle_end_date' => 'nullable|date|after:laying_cycle_start_date',
            ]);

            $validated['total_birds'] = $validated['hen_count'] + $validated['cock_count'];

            Bird::create($validated);
            return redirect()->route('birds.index')->with('success', 'Bird created successfully.');

        }catch (\Exception $e) {
            \Log::info($e->getMessage());
            return redirect()->route('birds.index')->with('error', 'An error occurred while creating chick purchase.');
        }
    }

    public function show(Bird $bird)
    {
        $bird->load('branch', 'chickPurchase', 'immunizationRecords');
        return view('birds.show', compact('bird'));
    }

    public function edit(Bird $bird)
    {
        $branches = Branch::all();
        $chickPurchases = ChickPurchase::all();
        return view('birds.edit', compact('bird', 'branches', 'chickPurchases'));
    }

    public function update(Request $request, Bird $bird)
    {
        $validated = $request->validate([
            'chick_purchase_id'       => 'required|exists:chick_purchases,id',
            'total_birds'             => 'required|integer|min:1',
            'hen_count'               => 'required|integer|min:0',
            'cock_count'              => 'required|integer|min:0',
            'branch_id'               => 'required|exists:branches,id',
            'laying_cycle_start_date' => 'required|date',
            'laying_cycle_end_date'   => 'required|date|after_or_equal:laying_cycle_start_date',
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
