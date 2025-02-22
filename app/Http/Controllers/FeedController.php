<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feed;
use App\Models\Supplier;
use Carbon\Carbon;

class FeedController extends Controller 
{
    public function index(Request $request)
    {
        $query = Feed::with('supplier');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('type', 'like', "%{$search}%")
                  ->orWhere('quantity_kg', 'like', "%{$search}%")
                  ->orWhere('purchase_date', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Sort functionality
        $sortField = $request->get('sort', 'purchase_date');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $feeds = $query->paginate(10)->withQueryString();
        $suppliers = Supplier::all();

        return view('feeds.index', compact('feeds', 'suppliers'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        return view('feeds.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'string', 'regex:/^[A-Za-z\s]+$/'],
            'quantity_kg' => 'required|numeric|min:0.01',
            'unit_cost' => 'required|numeric|min:0.01',
            'purchase_date' => [
                'required',
                'date',
                'before_or_equal:' . now()->format('Y-m-d')
            ],
            'supplier_id' => 'nullable|exists:suppliers,id',
        ], [
            'type.regex' => 'Feed type must contain only letters and spaces',
            'quantity_kg.min' => 'Quantity must be greater than 0',
            'unit_cost.min' => 'Unit cost must be greater than 0',
            'purchase_date.before_or_equal' => 'Purchase date cannot be in the future',
        ]);

        // Calculate total cost
        $validated['total_cost'] = $validated['quantity_kg'] * $validated['unit_cost'];
        
        // Format dates
        $validated['purchase_date'] = Carbon::parse($validated['purchase_date'])->format('Y-m-d');

        Feed::create($validated);
        
        return redirect()
            ->route('feeds.index')
            ->with('success', 'Feed record has been created successfully.');
    }

    public function show(Feed $feed)
    {
        $feed->load('supplier');
        return view('feeds.show', compact('feed'));
    }

    public function edit(Feed $feed)
    {
        $suppliers = Supplier::orderBy('name')->get();
        return view('feeds.edit', compact('feed', 'suppliers'));
    }

    public function update(Request $request, Feed $feed)
    {
        $validated = $request->validate([
            'type' => ['required', 'string', 'regex:/^[A-Za-z\s]+$/'],
            'quantity_kg' => 'required|numeric|min:0.01',
            'unit_cost' => 'required|numeric|min:0.01',
            'purchase_date' => [
                'required',
                'date',
                'before_or_equal:' . now()->format('Y-m-d')
            ],
            'supplier_id' => 'nullable|exists:suppliers,id',
        ], [
            'type.regex' => 'Feed type must contain only letters and spaces',
            'quantity_kg.min' => 'Quantity must be greater than 0',
            'unit_cost.min' => 'Unit cost must be greater than 0',
            'purchase_date.before_or_equal' => 'Purchase date cannot be in the future',
        ]);

        // Calculate total cost
        $validated['total_cost'] = $validated['quantity_kg'] * $validated['unit_cost'];
        
        // Format dates
        $validated['purchase_date'] = Carbon::parse($validated['purchase_date'])->format('Y-m-d');

        $feed->update($validated);
        
        return redirect()
            ->route('feeds.index')
            ->with('success', 'Feed record has been updated successfully.');
    }

    public function destroy(Feed $feed)
    {
        try {
            $feed->delete();
            return redirect()
                ->route('feeds.index')
                ->with('success', 'Feed record has been deleted successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->route('feeds.index')
                ->with('error', 'Failed to delete feed record. Please try again.');
        }
    }
}