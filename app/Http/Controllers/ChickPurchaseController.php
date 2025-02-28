<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\ChickPurchase;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ChickPurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    // Validate the date range if both dates are provided
    if ($request->has('date_from') && $request->has('date_to')) {
        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;
        
        if (!empty($dateFrom) && !empty($dateTo) && strtotime($dateFrom) > strtotime($dateTo)) {
            return redirect()->back()->with('error', 'Date From cannot be later than Date To');
        }
    }
    
    // Start building the query
    $query = ChickPurchase::with('branch');
    
    // Apply filters
    if ($request->has('branch') && !empty($request->branch)) {
        $query->where('branch_id', $request->branch);
    }
    
    if ($request->has('breed') && !empty($request->breed)) {
        $query->where('breed', 'like', '%' . $request->breed . '%');
    }
    
    if ($request->has('date_from') && !empty($request->date_from)) {
        $query->whereDate('purchase_date', '>=', $request->date_from);
    }
    
    if ($request->has('date_to') && !empty($request->date_to)) {
        $query->whereDate('purchase_date', '<=', $request->date_to);
    }
    
    // Get the results with pagination (5 items per page)
    $chickPurchases = $query->orderBy('purchase_date', 'desc')->paginate(5);
    
    // Get data for dropdowns
    $branches = Branch::all();
    $suppliers = Supplier::all();
    
    return view('chick-purchase.index', compact('chickPurchases', 'branches', 'suppliers'));
}
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'branch_id'     => 'required|exists:branches,id',
                'supplier_id'   => 'nullable|exists:suppliers,id',
                'breed'         => 'required|string|max:255',
                'purchase_age'  => 'required|integer|min:0',
                'purchase_date' => ['required', 'date', 'before_or_equal:' . now()->format('Y-m-d')],
                'quantity'      => 'required|integer|min:1',
                'unit_cost'     => 'required|numeric|min:0',
            ]);

            $validated['total_cost'] = $validated['unit_cost'] * $validated['quantity'];
            //generate meaningful batch id incrementally
            $validated['batch_id'] = 'CHICK-' . strtoupper(substr($validated['breed'], 0, 3)) . '-' . date('Y') . '-' . ChickPurchase::count();


            ChickPurchase::create($validated);

            return redirect()->route('chick-purchases.index')->with('success', 'Chick purchase created successfully.');
        }catch (\Exception $e) {
            \Log::info($e->getMessage());
            return redirect()->route('chick-purchases.index')->with('error', 'An error occurred while creating chick purchase.');
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(ChickPurchase $chickPurchase)
    {
        if (request()->ajax()) {
            return response()->json($chickPurchase->load(['branch', 'supplier']));
        }
        return view('chick-purchase.show', compact('chickPurchase'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ChickPurchase $chickPurchase)
{
    $branches = Branch::all(); // Assuming you're already passing this
    $suppliers = Supplier::all(); // Add this line to fetch suppliers
    
    return view('chick-purchase.edit', compact('chickPurchase', 'branches', 'suppliers'));
}
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ChickPurchase $chickPurchase)
{
    $validated = $request->validate([
        'branch_id' => 'required|exists:branches,id',
        'supplier_id' => 'nullable|exists:suppliers,id',
        'breed' => 'required|string|max:255',
        'purchase_age' => 'required|integer|min:0',
        'purchase_date' => 'required|date|before_or_equal:today',
        'quantity' => 'required|integer|min:1',
        'unit_cost' => 'required|numeric|min:0',
        'total_cost' => 'required|numeric|min:0',
    ]);

    $chickPurchase->update($validated);
    
    return redirect()->route('chick-purchases.index')
        ->with('success', 'Bird purchase updated successfully');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChickPurchase $chickPurchase)
    {
        // Check if the purchase has any associated poultry records before deletion
        if ($chickPurchase->poultry()->exists()) {
            return redirect()->route('chick-purchase.index')
                ->with('error', 'Cannot delete purchase with associated poultry.');
        }

        $chickPurchase->delete();
        return redirect()->route('chick-purchases.index')->with('success', 'Chick purchase deleted successfully.');
    }
}
