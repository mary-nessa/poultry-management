<?php
namespace App\Http\Controllers;

use App\Models\Breed;
use Illuminate\Http\Request;

class BreedController extends Controller
{
    public function index(Request $request)
    {
        $query = Breed::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        $breeds = $query->paginate(10);

        return view('breeds.index', compact('breeds'));
    }

    public function create()
    {
        return view('breeds.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:breeds',
            'description' => 'nullable|string',
        ]);

        Breed::create($validated);

        return redirect()->route('breeds.index')->with('success', 'Breed created successfully.');
    }

    public function show(Breed $breed)
    {
        return view('breeds.show', compact('breed'));
    }

    public function edit(Breed $breed)
    {
        return view('breeds.edit', compact('breed'));
    }

    public function update(Request $request, Breed $breed)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:breeds,name,' . $breed->id,
            'description' => 'nullable|string',
        ]);

        $breed->update($validated);

        return redirect()->route('breeds.index')->with('success', 'Breed updated successfully.');
    }

    public function destroy(Breed $breed)
    {
        try {
            $breed->delete();
            return redirect()->route('breeds.index')->with('success', 'Breed deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('breeds.index')->with('error', 'Failed to delete breed.');
        }
    }
}