<?php
namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\ChickPurchase;
use App\Models\Supplier;
use App\Models\Breed;
use Illuminate\Http\Request;

class ChickPurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->has('date_from') && $request->has('date_to')) {
            $dateFrom = $request->date_from;
            $dateTo = $request->date_to;
            
            if (!empty($dateFrom) && !empty($dateTo) && strtotime($dateFrom) > strtotime($dateTo)) {
                return redirect()->back()->with('error', 'Date From cannot be later than Date To');
            }
        }
        
        $query = ChickPurchase::with(['branch', 'breed']);
        
        // If user is not admin, restrict to their branch only
        if (!auth()->user()->hasRole('admin')) {
            $query->where('branch_id', auth()->user()->branch_id);
        } 
        // If admin and branch filter is applied
        elseif ($request->has('branch') && !empty($request->branch)) {
            $query->where('branch_id', $request->branch);
        }

        if ($request->has('breed') && !empty($request->breed)) {
            $query->where('breed_id', $request->breed);
        }
        
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('purchase_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('purchase_date', '<=', $request->date_to);
        }
        
        $chickPurchases = $query->orderBy('purchase_date', 'desc')->paginate(5);
        
        $branches = Branch::all();
        $suppliers = Supplier::all();
        $breeds = Breed::all();
        
        return view('chick-purchase.index', compact('chickPurchases', 'branches', 'suppliers', 'breeds'));
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
                'breed_id'      => 'required|exists:breeds,id',
                'purchase_age'  => 'required|integer|min:0',
                'purchase_date' => ['required', 'date', 'before_or_equal:' . now()->format('Y-m-d')],
                'quantity'      => 'required|integer|min:1',
                'unit_cost'     => 'required|numeric|min:0',
            ]);
            
            // Ensure non-admin users can only add to their own branch
            if (!auth()->user()->hasRole('admin')) {
                $validated['branch_id'] = auth()->user()->branch_id;
            }
            
            \Log::info($validated);
            $breed = Breed::find($validated['breed_id']);
            if (!$breed) {
                return redirect()->back()->with('error', 'Invalid breed selected.');
            }
            $breedName = $breed->name;

            $validated['total_cost'] = $validated['unit_cost'] * $validated['quantity'];
            $validated['batch_id'] = 'CHICK-' . strtoupper(substr($breedName, 0, 3)) . '-' . date('Y') . '-' . ChickPurchase::count();

            ChickPurchase::create($validated);

            return redirect()->route('chick-purchases.index')->with('success', 'Chick purchase created successfully.');
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            return redirect()->route('chick-purchases.index')->with('error', 'An error occurred while creating chick purchase.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ChickPurchase $chickPurchase)
    {
        // Check if user has access to this purchase
        if (!auth()->user()->hasRole('admin') && $chickPurchase->branch_id != auth()->user()->branch_id) {
            return redirect()->route('chick-purchases.index')->with('error', 'You do not have permission to view this record.');
        }
        
        if (request()->ajax()) {
            return response()->json($chickPurchase->load(['branch', 'supplier', 'breed']));
        }
        return view('chick-purchase.show', compact('chickPurchase'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ChickPurchase $chickPurchase)
    {
        // Check if user has access to this purchase
        if (!auth()->user()->hasRole('admin') && $chickPurchase->branch_id != auth()->user()->branch_id) {
            return redirect()->route('chick-purchases.index')->with('error', 'You do not have permission to edit this record.');
        }
        
        $branches = Branch::all();
        $suppliers = Supplier::all();
        $breeds = Breed::all();
        
        return view('chick-purchase.edit', compact('chickPurchase', 'branches', 'suppliers', 'breeds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ChickPurchase $chickPurchase)
    {
        // Check if user has access to this purchase
        if (!auth()->user()->hasRole('admin') && $chickPurchase->branch_id != auth()->user()->branch_id) {
            return redirect()->route('chick-purchases.index')->with('error', 'You do not have permission to update this record.');
        }
        
        $validated = $request->validate([
            'branch_id'     => 'required|exists:branches,id',
            'supplier_id'   => 'nullable|exists:suppliers,id',
            'breed_id'      => 'required|exists:branches,id',
            'purchase_age'  => 'required|integer|min:0',
            'purchase_date' => 'required|date|before_or_equal:today',
            'quantity'      => 'required|integer|min:1',
            'unit_cost'     => 'required|numeric|min:0',
            'total_cost'    => 'required|numeric|min:0',
        ]);

        // Ensure non-admin users can only update to their own branch
        if (!auth()->user()->hasRole('admin')) {
            $validated['branch_id'] = auth()->user()->branch_id;
        }

        $chickPurchase->update($validated);
        
        return redirect()->route('chick-purchases.index')
            ->with('success', 'Bird purchase updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChickPurchase $chickPurchase)
    {
        // Check if user has access to this purchase
        if (!auth()->user()->hasRole('admin') && $chickPurchase->branch_id != auth()->user()->branch_id) {
            return redirect()->route('chick-purchases.index')->with('error', 'You do not have permission to delete this record.');
        }
        
        if ($chickPurchase->poultry()->exists()) {
            return redirect()->route('chick-purchases.index')
                ->with('error', 'Cannot delete purchase with associated poultry.');
        }

        $chickPurchase->delete();
        return redirect()->route('chick-purchases.index')->with('success', 'Chick purchase deleted successfully.');
    }
}