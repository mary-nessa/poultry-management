<?php

namespace App\Http\Controllers;

use App\Models\FeedType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FeedTypeController extends Controller
{
    // Display a listing of the feed types
    public function index()
    {
        $feedTypes = FeedType::all();
        return view('feedtypes.index', compact('feedTypes'));
    }

    // Show the form for creating a new feed type
    public function create()
    {
        // Get all Feed Types for the select input in the form
        $feedTypes = FeedType::all();
        return view('feedtypes.create', compact('feedTypes'));
    }

    // Store a newly created feed type in the database
    public function store(Request $request)
    {
        // Validate the input data
        $request->validate([
            'name' => 'required|unique:feed_types,name|regex:/^[\pL\s]+$/u|max:255', // Ensure the feed type name is unique and contains only letters and spaces
            'description' => 'nullable|regex:/^[\pL\s]*$/u|max:255', // Ensure description contains only letters and spaces
        ]);

        // Create a new FeedType
        $feedType = new FeedType();
        $feedType->name = $request->input('name');
        $feedType->description = $request->input('description');
        $feedType->save();

        return redirect()->route('feedtypes.index')->with('success', 'Feed Type added successfully.');
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
        
        // Validate the request ensuring that only letters and spaces are allowed (no numbers)
        $request->validate([
            'name' => ['required', 'regex:/^[\pL\s]+$/u', 'unique:feed_types,name,' . $feedType->id, 'max:255'], // Ensure no numbers, unique except for current feed type
            'description' => ['nullable', 'regex:/^[\pL\s]*$/u', 'max:500'], // Allow empty description but ensure it contains only letters and spaces
        ]);

        $feedType->name = $request->input('name');
        $feedType->description = $request->input('description');
        $feedType->save();

        return redirect()->route('feedtypes.index')
                        ->with('success', 'Feed Type updated successfully.');
    }

    // Remove the specified feed type from the database
    public function destroy($id)
    {
        try {
            $feedType = FeedType::findOrFail($id);
            $feedType->delete();
            return redirect()->route('feedtypes.index')
                            ->with('success', 'Feed Type deleted successfully.');
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error deleting feed type: ' . $e->getMessage());
            
            // Redirect back with an error message
            return redirect()->route('feedtypes.index')
                            ->with('error', 'Feed Type cannot be deleted as it is associated with a feed.');
        }
    }
}