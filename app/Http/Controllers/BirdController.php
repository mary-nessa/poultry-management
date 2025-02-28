<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bird;
use App\Models\Branch;
use App\Models\ChickPurchase;

class BirdController extends Controller
{
    public function index()
    {
        $birds = Bird::with(['branch', 'chickPurchase'])->paginate(10);
        $chickPurchases = ChickPurchase::all();
        $branches = Branch::all();
        return view('birds.index', compact('birds', 'branches', 'chickPurchases'));
    }

    public function create()
    {
        // Empty method as we're using a modal for creation
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'chick_purchase_id' => 'required|exists:chick_purchases,id',
                'hen_count' => 'required|integer|min:0',
                'cock_count' => 'required|integer|min:0',
                'laying_cycle_start_date' => 'nullable|date',
                'laying_cycle_end_date' => 'nullable|date|after:laying_cycle_start_date',
            ]);

            // Get the chick purchase to check available quantity
            $chickPurchase = ChickPurchase::findOrFail($validated['chick_purchase_id']);
            
            // Calculate total birds being added
            $totalBirdsToAdd = $validated['hen_count'] + $validated['cock_count'];
            
            // Get total birds already assigned to this purchase
            $totalAssignedBirds = Bird::where('chick_purchase_id', $validated['chick_purchase_id'])->sum('total_birds');
            
            // Check if the total would exceed the purchase quantity
            if ($totalAssignedBirds + $totalBirdsToAdd > $chickPurchase->quantity) {
                return redirect()->route('birds.index')->with('error', 
                    'Cannot add more birds than available. Available: ' . 
                    ($chickPurchase->quantity - $totalAssignedBirds) . 
                    ' birds for this purchase.');
            }

            $validated['total_birds'] = $totalBirdsToAdd;
            $validated['branch_id'] = $chickPurchase->branch_id; // Inherit branch from purchase

            Bird::create($validated);
            return redirect()->route('birds.index')->with('success', 'Bird group created successfully.');

        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            return redirect()->route('birds.index')->with('error', 'An error occurred while creating bird group: ' . $e->getMessage());
        }
    }
    
    public function show(Bird $bird)
    {
        $bird->load('branch', 'chickPurchase'); // Remove 'immunizationRecords'
        return view('birds.show', compact('bird'));
    }
    

    public function edit(Bird $bird)
    {
        $branches = Branch::all();
        $chickPurchases = ChickPurchase::all();
        return view('birds.edit', compact('bird', 'branches', 'chickPurchases'));
    }

    public function update(Request $request, Bird $bird)
    {
        try {
            $validated = $request->validate([
                'chick_purchase_id' => 'required|exists:chick_purchases,id',
                'hen_count' => 'required|integer|min:0',
                'cock_count' => 'required|integer|min:0',
                // 'branch_id' => 'required|exists:branches,id',
                'laying_cycle_start_date' => 'nullable|date',
                'laying_cycle_end_date' => 'nullable|date|after_or_equal:laying_cycle_start_date',
            ]);

            // Calculate new total birds
            $newTotalBirds = $validated['hen_count'] + $validated['cock_count'];
            
            // If changing purchase or increasing birds, verify limits
            if ($bird->chick_purchase_id != $validated['chick_purchase_id'] || $newTotalBirds > $bird->total_birds) {
                $chickPurchase = ChickPurchase::findOrFail($validated['chick_purchase_id']);
                
                // Calculate how many are already used excluding current bird
                $totalAssignedBirds = Bird::where('chick_purchase_id', $validated['chick_purchase_id'])
                    ->where('id', '!=', $bird->id)
                    ->sum('total_birds');
                
                // Check if edit would exceed the limit
                if ($totalAssignedBirds + $newTotalBirds > $chickPurchase->quantity) {
                    return redirect()->route('birds.index')->with('error', 
                        'Cannot add more birds than available. Available: ' . 
                        ($chickPurchase->quantity - $totalAssignedBirds) . 
                        ' birds for this purchase.');
                }
            }

            $validated['total_birds'] = $newTotalBirds;
            $bird->update($validated);
            
            return redirect()->route('birds.index')->with('success', 'Bird group updated successfully.');
            
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            return redirect()->route('birds.index')->with('error', 'An error occurred while updating bird group: ' . $e->getMessage());
        }
    }

    public function destroy(Bird $bird)
    {
        try {
            $bird->delete();
            return redirect()->route('birds.index')->with('success', 'Bird group deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('birds.index')->with('error', 'An error occurred while deleting bird group: ' . $e->getMessage());
        }
    }
    
    // API methods for the modals
    public function getBirdData(Bird $bird)
    {
        $bird->load('branch', 'chickPurchase');
        return response()->json($bird);
    }
    
    public function getEditData(Bird $bird)
    {
        $bird->load('branch', 'chickPurchase');
        return response()->json($bird);
    }
    
    public function getAvailableBirds($purchaseId)
    {
        $purchase = ChickPurchase::findOrFail($purchaseId);
        $totalAssigned = Bird::where('chick_purchase_id', $purchaseId)->sum('total_birds');
        $available = $purchase->quantity - $totalAssigned;
        
        return response()->json([
            'available' => $available,
            'total' => $purchase->quantity,
            'assigned' => $totalAssigned,
            'branch_id' => $purchase->branch_id
        ]);
    }
}