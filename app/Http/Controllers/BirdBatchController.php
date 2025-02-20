<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BirdBatch;
use App\Models\Branch;
use App\Models\Supplier;

class BirdBatchController extends Controller
{
    public function index()
    {
        $birdBatches = BirdBatch::with(['branch', 'supplier'])->get();
        $branches = Branch::all();
        $suppliers = Supplier::all();
        return view('bird-batches.index', compact('birdBatches', 'branches', 'suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'purchase_method' => 'required|in:egg,chick,adult',
            'purchased_quantity' => 'required|integer|min:1',
            'unknown_gender' => 'nullable|integer|min:0',
            'hen_count' => 'nullable|integer|min:0',
            'cock_count' => 'nullable|integer|min:0',
            'egg_laid_date' => 'nullable|date',
            'hatch_date' => 'nullable|date',
            'actual_hatched' => 'nullable|integer|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'acquisition_date' => 'required|date',
            'status' => 'required|in:pending,hatched,completed'
        ]);

        BirdBatch::create($validated);
        return redirect()->route('bird-batches.index')->with('success', 'Bird batch created successfully.');
    }

    public function show(BirdBatch $birdBatch)
    {
        if (request()->ajax()) {
            return response()->json($birdBatch->load(['branch', 'supplier']));
        }
        return view('bird-batches.show', compact('birdBatch'));
    }

    public function edit(BirdBatch $birdBatch)
    {
        if (request()->ajax()) {
            return response()->json($birdBatch->load(['branch', 'supplier']));
        }
        return view('bird-batches.edit', compact('birdBatch'));
    }

    public function update(Request $request, BirdBatch $birdBatch)
    {
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'purchase_method' => 'required|in:egg,chick,adult',
            'purchased_quantity' => 'required|integer|min:1',
            'unknown_gender' => 'nullable|integer|min:0',
            'hen_count' => 'nullable|integer|min:0',
            'cock_count' => 'nullable|integer|min:0',
            'egg_laid_date' => 'nullable|date',
            'hatch_date' => 'nullable|date',
            'actual_hatched' => 'nullable|integer|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'acquisition_date' => 'required|date',
            'status' => 'required|in:pending,hatched,completed'
        ]);

        $birdBatch->update($validated);
        return redirect()->route('bird-batches.index')->with('success', 'Bird batch updated successfully.');
    }

    public function destroy(BirdBatch $birdBatch)
    {
        // Check if the batch has any associated birds before deletion
        if ($birdBatch->birds()->exists()) {
            return redirect()->route('bird-batches.index')
                ->with('error', 'Cannot delete batch with associated birds.');
        }

        $birdBatch->delete();
        return redirect()->route('bird-batches.index')->with('success', 'Bird batch deleted successfully.');
    }
}
