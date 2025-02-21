<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\ChickPurchase;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ChickPurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $chickPurchases = ChickPurchase::with(['branch', 'supplier'])->get();
        $branches = Branch::all();
        $suppliers = Supplier::all();
        return view('chick-purchase.index', compact('chickPurchases', 'branches', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'branch_id'     => 'required|exists:branches,id',
                'supplier_id'   => 'nullable|exists:suppliers,id',
                'breed'         => 'required|string|max:255',
                'purchase_age'  => 'required|integer|min:0',
                'quantity'      => 'required|integer|min:1',
                'unit_cost'     => 'required|numeric|min:0',
            ]);

            $validated['total_cost'] = $validated['unit_cost'] * $validated['quantity'];
            //generate meaningful batch id incrementally
            $validated['batch_id'] = 'CHICK-' . strtoupper(substr($validated['breed'], 0, 3)) . '-' . date('Y') . '-' . ChickPurchase::count();


            ChickPurchase::create($validated);

            return redirect()->route('chick-purchases.index')->with('success', 'Chick purchase created successfully.');
        }catch (\Exception $e) {
            \Log::info($e->getMessage());
            return redirect()->route('chick-purchases.index')->with('error', 'An error occurred while creating chick purchase.');
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(ChickPurchase $chickPurchase)
    {
        if (request()->ajax()) {
            return response()->json($chickPurchase->load(['branch', 'supplier']));
        }
        return view('chick-purchase.show', compact('chickPurchase'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ChickPurchase $chickPurchase)
    {
        if (request()->ajax()) {
            return response()->json($chickPurchase->load(['branch', 'supplier']));
        }
        return view('chick-purchase.edit', compact('chickPurchase'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ChickPurchase $chickPurchase)
    {
        $validated = $request->validate([
            'branch_id'     => 'required|exists:branches,id',
            'supplier_id'   => 'nullable|exists:suppliers,id',
            'breed'         => 'required|string|max:255',
            'purchase_age'  => 'required|integer|min:0',
            'quantity'      => 'required|integer|min:1',
            'unit_cost'     => 'required|numeric|min:0',
            'total_cost'    => 'required|numeric|min:0',
            'date'          => 'required|date',
        ]);

        $chickPurchase->update($validated);
        return redirect()->route('chick-purchase.index')->with('success', 'Chick purchase updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChickPurchase $chickPurchase)
    {
        // Check if the purchase has any associated poultry records before deletion
        if ($chickPurchase->poultry()->exists()) {
            return redirect()->route('chick-purchase.index')
                ->with('error', 'Cannot delete purchase with associated poultry.');
        }

        $chickPurchase->delete();
        return redirect()->route('chick-purchase.index')->with('success', 'Chick purchase deleted successfully.');
    }
}
