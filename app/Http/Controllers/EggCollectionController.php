<?php

namespace App\Http\Controllers;

use App\Models\EggCollection;
use App\Models\User;
use App\Models\Branch;
use App\Models\Sale;
use Illuminate\Http\Request;

class EggCollectionController extends Controller
{
    // Show all egg collections
    public function index()
    {
        $eggCollections = EggCollection::with(['collectedBy', 'branch'])->get();
        return view('egg_collections.index', compact('eggCollections'));
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
            'total_eggs' => 'required|numeric',
            'good_eggs' => 'required|numeric',
            'damaged_eggs' => 'required|numeric',
            'broken_eggs' => 'required|numeric',
            'full_trays' => 'required|numeric',
            '1_2_trays' => 'required|numeric',
            'single_eggs' => 'required|numeric',
            'collected_by' => 'required|exists:users,id',
            'branch_id' => 'required|exists:branches,id',
        ]);

        EggCollection::create($validated);

        return redirect()->route('egg-collections.index')->with('success', 'Egg Collection added successfully!');
    }

    // Show the form for editing an existing egg collection
    public function edit(EggCollection $eggCollection)
    {
        $users = User::all();
        $branches = Branch::all();
        return view('egg_collections.edit', compact('eggCollection', 'users', 'branches'));
    }

    // Update an existing egg collection
    public function update(Request $request, EggCollection $eggCollection)
    {
        $validated = $request->validate([
            'total_eggs' => 'required|numeric',
            'good_eggs' => 'required|numeric',
            'damaged_eggs' => 'required|numeric',
            'broken_eggs' => 'required|numeric',
            'full_trays' => 'required|numeric',
            '1_2_trays' => 'required|numeric',
            'single_eggs' => 'required|numeric',
            'collected_by' => 'required|exists:users,id',
            'branch_id' => 'required|exists:branches,id',
        ]);

        $eggCollection->update($validated);

        return redirect()->route('egg-collections.index')->with('success', 'Egg Collection updated successfully!');
    }

    // Delete an egg collection
    public function destroy(EggCollection $eggCollection)
    {
        $eggCollection->delete();

        return redirect()->route('egg-collections.index')->with('success', 'Egg Collection deleted successfully!');
    }
}
