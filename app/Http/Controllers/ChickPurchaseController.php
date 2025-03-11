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
            
            if ($request->has('branch') && !empty($request->branch)) {
                $query->where('branch_id', $request->branch);
            }
            
            if ($request->has('breed') && !empty($request->breed)) {
                $query->whereHas('breed', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->breed . '%');
                });
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
            $validated = $request->validate([
                'branch_id'     => 'required|exists:branches,id',
                'supplier_id'   => 'nullable|exists:suppliers,id',
                'breed_id'      => 'required|exists:breeds,id',
                'purchase_age'  => 'required|integer|min:0',
                'purchase_date' => 'required|date|before_or_equal:today',
                'quantity'      => 'required|integer|min:1',
                'unit_cost'     => 'required|numeric|min:0',
                'total_cost'    => 'required|numeric|min:0',
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
            if ($chickPurchase->poultry()->exists()) {
                return redirect()->route('chick-purchase.index')
                    ->with('error', 'Cannot delete purchase with associated poultry.');
            }

            $chickPurchase->delete();
            return redirect()->route('chick-purchases.index')->with('success', 'Chick purchase deleted successfully.');
        }
    }