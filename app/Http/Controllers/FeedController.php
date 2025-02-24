<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feed;
use App\Models\Supplier;
use App\Models\FeedType;
use Carbon\Carbon;

class FeedController extends Controller 
{
    public function index(Request $request)
    {
        $query = Feed::with('supplier')->with('feedType');
    
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('feed_type_id', 'like', "%{$search}%")  // Change 'type' to 'feed_type_id' for proper search
                  ->orWhere('quantity_kg', 'like', "%{$search}%")
                  ->orWhere('purchase_date', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }
    
        // Sorting functionality
        $sortField = $request->get('sort', 'purchase_date');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);
    
        // Dynamic pagination (entries per page)
        $perPage = $request->get('entries', 5); // Default to 5 entries per page
        $feeds = $query->paginate($perPage)->appends($request->query()); 
    
        $suppliers = Supplier::all();
    
        return view('feeds.index', compact('feeds', 'suppliers', 'perPage'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();  // Get all suppliers
        $feedTypes = FeedType::all();  // Get all feed types
        return view('feeds.create', compact('suppliers', 'feedTypes'));  // Pass feedTypes here
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'feed_type_id' => 'required|exists:feed_types,id', // Ensure feed_type_id validation
            'quantity_kg' => 'required|numeric|min:0.01',
            'unit_cost' => 'required|numeric|min:0.01',
            'purchase_date' => ['required', 'date', 'before_or_equal:' . now()->format('Y-m-d')],
            'supplier_id' => 'nullable|exists:suppliers,id',
        ]);

        // Calculate total cost
        $validated['total_cost'] = $validated['quantity_kg'] * $validated['unit_cost'];
        
        // Format the purchase_date
        $validated['purchase_date'] = Carbon::parse($validated['purchase_date'])->format('Y-m-d');

        // Create feed with validated data
        Feed::create($validated);
        
        return redirect()->route('feeds.index')->with('success', 'Feed record has been created successfully.');
    }

    public function show(Feed $feed)
    {
        $feed->load('supplier');
        return view('feeds.show', compact('feed'));
    }

    public function edit(Feed $feed)
    {
        $suppliers = Supplier::orderBy('name')->get();
        $feedTypes = FeedType::all(); // Get all feed types
        return view('feeds.edit', compact('feed', 'suppliers', 'feedTypes')); // Pass feedTypes here
    }

    public function update(Request $request, Feed $feed)
    {
        $validated = $request->validate([
            'feed_type_id' => 'required|exists:feed_types,id', // Ensure feed_type_id validation
            'quantity_kg' => 'required|numeric|min:0.01',
            'unit_cost' => 'required|numeric|min:0.01',
            'purchase_date' => ['required', 'date', 'before_or_equal:' . now()->format('Y-m-d')],
            'supplier_id' => 'nullable|exists:suppliers,id',
        ]);

        // Calculate total cost
        $validated['total_cost'] = $validated['quantity_kg'] * $validated['unit_cost'];
        
        // Format the purchase_date
        $validated['purchase_date'] = Carbon::parse($validated['purchase_date'])->format('Y-m-d');

        $feed->update($validated);
        
        return redirect()->route('feeds.index')->with('success', 'Feed record has been updated successfully.');
    }

    public function destroy(Feed $feed)
    {
        try {
            $feed->delete();
            return redirect()->route('feeds.index')->with('success', 'Feed record has been deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('feeds.index')->with('error', 'Failed to delete feed record. Please try again.');
        }
    }
}
