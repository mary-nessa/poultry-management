<?php
namespace App\Http\Controllers;

use App\Models\Breed;
use App\Models\Branch;
use Illuminate\Http\Request;

class BreedController extends Controller
{
    /**
     * Display a listing of breeds with search functionality.
     */
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

    /**
     * Store a newly created breed in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:breeds',
            'description' => 'nullable|string',
        ]);

        $breed = Breed::create($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Breed created successfully'], 201);
        }

        return redirect()->route('breeds.index')->with('success', 'Breed created successfully.');
    }

    /**
     * Display the specified breed.
     */
    public function show(Breed $breed)
    {
        if (request()->wantsJson()) {
            return response()->json($breed);
        }

        return view('breeds.show', compact('breed'));
    }

    /**
     * Update the specified breed in storage.
     */
    public function update(Request $request, Breed $breed)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:breeds,name,' . $breed->id,
            'description' => 'nullable|string',
        ]);

        $breed->update($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Breed updated successfully'], 200);
        }

        return redirect()->route('breeds.index')->with('success', 'Breed updated successfully.');
    }

    /**
     * Remove the specified breed from storage.
     */
    public function destroy(Breed $breed, Request $request)
    {
        try {
            $breed->delete();
            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Breed deleted successfully'], 200);
            }
            return redirect()->route('breeds.index')->with('success', 'Breed deleted successfully.');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to delete breed'], 500);
            }
            return redirect()->route('breeds.index')->with('error', 'Failed to delete breed.');
        }
    }
}