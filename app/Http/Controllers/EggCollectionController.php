<?php

namespace App\Http\Controllers;

use App\Models\EggCollection;
use App\Models\User;
use App\Models\Branch;
use App\Models\GeneralInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EggCollectionController extends Controller
{
    // Show all egg collections
    public function index()
    {
        $eggCollections = EggCollection::with(['collectedBy', 'branch'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        $branches = Branch::all();
        $users = User::all();
        return view('egg_collections.index', compact('eggCollections', 'branches', 'users'));
    }

    // Show form to create a new egg collection
    public function create()
    {
        $users = User::all(); // You can customize this to get only necessary users (e.g., workers)
        $branches = Branch::all();
        return view('egg_collections.create', compact('users', 'branches'));
    }

    // Store a new egg collection in the database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'good_eggs' => 'required|integer|min:0',
            'damaged_eggs' => 'required|integer|min:0',
            'collected_by' => 'required|exists:users,id',
            'branch_id' => 'required|exists:branches,id',
        ]);

        DB::beginTransaction();
        try {
            // Create egg collection with calculated fields
            $eggCollection = new EggCollection($validated);
            $eggCollection->total_eggs = $validated['good_eggs'] + $validated['damaged_eggs'];
            $eggCollection->broken_eggs = 0; // Set default value
            
            // Calculate trays
            $totalGoodEggs = $validated['good_eggs'];
            $eggCollection->full_trays = floor($totalGoodEggs / 30);
            $remaining = $totalGoodEggs % 30;
            $eggCollection->{'1_2_trays'} = floor($remaining / 15);
            $eggCollection->single_eggs = $remaining % 15;
            
            $eggCollection->save();

            // Update general inventory with good eggs
            $inventory = GeneralInventory::firstOrCreate(
                ['branch_id' => $request->branch_id],
                ['breed' => 'Mixed', 'total_eggs' => 0, 'total_chicks' => 0, 'total_cocks' => 0, 'total_hens' => 0]
            );
            
            $inventory->total_eggs += $validated['good_eggs'];
            $inventory->save();

            DB::commit();
            return redirect()->route('egg-collections.index')
                ->with('success', 'Egg collection recorded successfully.');
        } catch (\Exception $e) {
            // \Log::error($e);
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Failed to record egg collection: ' . $e->getMessage());
        }
    }

    // Show the form for editing an existing egg collection
    public function edit(EggCollection $eggCollection)
    {
        $eggCollection->load(['collectedBy', 'branch']);
        return response()->json($eggCollection);
    }

    // Update an existing egg collection
    public function update(Request $request, EggCollection $eggCollection)
    {
        $validated = $request->validate([
            'good_eggs' => 'required|integer|min:0',
            'damaged_eggs' => 'required|integer|min:0',
            'collected_by' => 'required|exists:users,id',
            'branch_id' => 'required|exists:branches,id',
        ]);

        DB::beginTransaction();
        try {
            // Get the old good eggs count to adjust inventory
            $oldGoodEggs = $eggCollection->good_eggs;

            // Calculate new fields
            $totalGoodEggs = $validated['good_eggs'];
            $validated['total_eggs'] = $validated['good_eggs'] + $validated['damaged_eggs'];
            $validated['full_trays'] = floor($totalGoodEggs / 30);
            $remaining = $totalGoodEggs % 30;
            $validated['1_2_trays'] = floor($remaining / 15);
            $validated['single_eggs'] = $remaining % 15;
            $validated['broken_eggs'] = 0;

            // Update egg collection
            $eggCollection->update($validated);

            // Update general inventory
            $inventory = GeneralInventory::firstOrCreate(
                ['branch_id' => $request->branch_id],
                ['breed' => 'Mixed', 'total_eggs' => 0, 'total_chicks' => 0, 'total_cocks' => 0, 'total_hens' => 0]
            );
            
            // Adjust inventory by removing old count and adding new count
            $inventory->total_eggs = $inventory->total_eggs - $oldGoodEggs + $validated['good_eggs'];
            $inventory->save();

            DB::commit();
            return redirect()->route('egg-collections.index')
                ->with('success', 'Egg collection updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Failed to update egg collection: ' . $e->getMessage());
        }
    }

    // Delete an egg collection
    public function destroy(EggCollection $eggCollection)
    {
        DB::beginTransaction();
        try {
            // Update general inventory before deleting
            $inventory = GeneralInventory::where('branch_id', $eggCollection->branch_id)->first();
            if ($inventory) {
                $inventory->total_eggs -= $eggCollection->good_eggs;
                $inventory->save();
            }

            // Delete the egg collection
            $eggCollection->delete();

            DB::commit();
            return redirect()->route('egg-collections.index')
                ->with('success', 'Egg collection deleted successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Failed to delete egg collection: ' . $e->getMessage());
        }
    }

    public function show(EggCollection $eggCollection)
    {
        $eggCollection->load(['collectedBy', 'branch']);
        return response()->json($eggCollection);
    }
}
