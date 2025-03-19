<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feed;
use App\Models\Supplier;
use App\Models\FeedType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class FeedController extends Controller 
{
    public function index(Request $request)
    {
        $query = Feed::with('supplier', 'feedType');
    
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('feed_type_id', 'like', "%{$search}%")
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
    
        // Dynamic pagination
        $perPage = $request->get('entries', 5);
        $feeds = $query->paginate($perPage)->appends($request->query()); 
    
        $suppliers = Supplier::all();
    
        return view('feeds.index', compact('feeds', 'suppliers', 'perPage'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $feedTypes = FeedType::all();
        return view('feeds.create', compact('suppliers', 'feedTypes'));
    }
    public function store(Request $request)
    {
        // Get the logged-in user and their branch
        $user = auth()->user();
        $defaultBranchId = $user->branch_id; // Assuming users have a branch_id field
    
        // Check if multiple feeds are submitted (cart-style submission)
        if ($request->has('feeds') && is_array($request->input('feeds'))) {
            $feeds = $request->input('feeds');
    
            $validator = Validator::make($feeds, [
                '*.feed_type_id' => 'required|exists:feed_types,id',
                '*.quantity_kg' => 'required|numeric|min:0.01',
                '*.unit_cost' => 'required|numeric|min:0.01',
                '*.purchase_date' => ['required', 'date', 'before_or_equal:' . now()->format('Y-m-d')],
                '*.supplier_id' => 'nullable|exists:suppliers,id',
                '*.notes' => 'nullable|string|max:255',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
    
            try {
                foreach ($feeds as $feedData) {
                    $feedData['total_cost'] = $feedData['quantity_kg'] * $feedData['unit_cost'];
                    $feedData['purchase_date'] = Carbon::parse($feedData['purchase_date'])->format('Y-m-d');
                    $feedData['branch_id'] = $defaultBranchId; // Assign default branch
    
                    Feed::create($feedData);
                }
    
                return response()->json([
                    'success' => true,
                    'message' => 'Feeds recorded successfully'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error saving feeds: ' . $e->getMessage()
                ], 500);
            }
        } else {
            // Handle single feed submission
            $validated = $request->validate([
                'feed_type_id' => 'required|exists:feed_types,id',
                'quantity_kg' => 'required|numeric|min:0.01',
                'unit_cost' => 'required|numeric|min:0.01',
                'purchase_date' => ['required', 'date', 'before_or_equal:' . now()->format('Y-m-d')],
                'supplier_id' => 'nullable|exists:suppliers,id',
                'notes' => 'nullable|string|max:255',
            ]);
    
            $validated['total_cost'] = $validated['quantity_kg'] * $validated['unit_cost'];
            $validated['purchase_date'] = Carbon::parse($validated['purchase_date'])->format('Y-m-d');
            $validated['branch_id'] = $defaultBranchId; // Assign default branch
    
            Feed::create($validated);
    
            return redirect()->route('feeds.index')->with('success', 'Feed record has been created successfully.');
        }
    }
    
    public function show(Feed $feed)
    {
        $feed->load('supplier', 'feedType');
        return view('feeds.show', compact('feed'));
    }

    public function edit(Feed $feed)
    {
        $suppliers = Supplier::orderBy('name')->get();
        $feedTypes = FeedType::all();
        return view('feeds.edit', compact('feed', 'suppliers', 'feedTypes'));
    }

    public function update(Request $request, Feed $feed)
    {
        $validated = $request->validate([
            'feed_type_id' => 'required|exists:feed_types,id',
            'quantity_kg' => 'required|numeric|min:0.01',
            'unit_cost' => 'required|numeric|min:0.01',
            'purchase_date' => ['required', 'date', 'before_or_equal:' . now()->format('Y-m-d')],
            'supplier_id' => 'nullable|exists:suppliers,id',
            'notes' => 'nullable|string|max:255',
        ]);

        $validated['total_cost'] = $validated['quantity_kg'] * $validated['unit_cost'];
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
            return redirect()->route('feeds.index')->with('error', 'Failed to delete feed record: ' . $e->getMessage());
        }
    }
}