<?php

namespace App\Http\Controllers;

use App\Models\FeedType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FeedTypeController extends Controller
{
    // Display a listing of the feed types with search and dynamic pagination
    public function index(Request $request)
{
    $search = $request->query('search');
    $perPage = 6; // Fixed at 5 items per page
    
    $feedTypesQuery = FeedType::query();
    
    // Apply search filter if a search term is provided
    if ($search) {
        $feedTypesQuery->where(function ($query) use ($search) {
            $query->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('description', 'LIKE', '%' . $search . '%');
        });
    }
    
    // Paginate results with fixed page size
    $feedTypes = $feedTypesQuery->paginate($perPage)->appends([
        'search' => $search
    ]);
    
    return view('feedtypes.index', compact('feedTypes'));
}

    // Show the form for creating a new feed type
    public function create()
    {
        return view('feedtypes.create');
    }

    // Store a newly created feed type in the database
    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required', 'unique:feed_types,name', 'regex:/^[\pL\s]+$/u', 'max:255'
            ], 
            'description' => [
                'nullable', 'regex:/^[\pL\s]*$/u', 'max:500'
            ],
        ]);

        try {
            $feedType = new FeedType();
            $feedType->name = $request->input('name');
            $feedType->description = $request->input('description');
            $feedType->save();

            Log::info('Feed Type created: ' . $feedType->name);
            return redirect()->route('feedtypes.index')->with('success', 'Feed Type added successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating feed type: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while adding the feed type.');
        }
    }

    // Show the form for editing the specified feed type
    public function edit($id)
    {
        $feedType = FeedType::findOrFail($id);
        return view('feedtypes.edit', compact('feedType'));
    }

    // Update the specified feed type in the database
    public function update(Request $request, $id)
    {
        $feedType = FeedType::findOrFail($id);

        $request->validate([
            'name' => ['required', 'regex:/^[\pL\s]+$/u', 'unique:feed_types,name,' . $feedType->id, 'max:255'],
            'description' => ['nullable', 'regex:/^[\pL\s]*$/u', 'max:500'],
        ]);

        try {
            $feedType->name = $request->input('name');
            $feedType->description = $request->input('description');
            $feedType->save();

            Log::info('Feed Type updated: ' . $feedType->name);
            return redirect()->route('feedtypes.index')->with('success', 'Feed Type updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating feed type: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating the feed type.');
        }
    }

    // Remove the specified feed type from the database
    public function destroy($id)
    {
        try {
            $feedType = FeedType::findOrFail($id);
            $feedType->delete();

            Log::info('Feed Type deleted: ' . $feedType->name);
            return redirect()->route('feedtypes.index')->with('success', 'Feed Type deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting feed type: ' . $e->getMessage());
            return redirect()->route('feedtypes.index')->with('error', 'Feed Type cannot be deleted as it is associated with a feed.');
        }
    }
}
