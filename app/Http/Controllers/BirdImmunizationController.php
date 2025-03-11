<?php

namespace App\Http\Controllers;

use App\Models\ImmunizationRecord;
use App\Models\ChickPurchase;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BirdImmunizationController extends Controller
{
    public function index(Request $request)
    {
        $query = ImmunizationRecord::with([
            'chickPurchase.birds',
            'chickPurchase.branch',
            'chickPurchase.supplier',
            'vaccine.supplier'
        ]);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('chickPurchase', function($q) use ($search) {
                    $q->where('batch_id', 'like', "%{$search}%");
                })
                ->orWhereHas('vaccine', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhere('age_category', 'like', "%{$search}%");
            });
        }

        $immunizations = $query->latest()->paginate(10);

        // Collections for the create/edit modals
        $chickPurchases = ChickPurchase::with(['birds', 'branch', 'supplier'])
            ->latest()
            ->get();
            
        $vaccines = Medicine::where('purpose', 'like', '%vaccine%')
            ->orWhere('purpose', 'like', '%immunization%')
            ->get();

        return view('bird_immunizations.index', compact('immunizations', 'chickPurchases', 'vaccines'));
    }

    public function create()
    {
        $chickPurchases = ChickPurchase::with(['birds', 'branch'])->get();
        $vaccines = Medicine::where('purpose', 'like', '%vaccine%')
            ->orWhere('purpose', 'like', '%immunization%')
            ->get();

        return view('bird_immunizations.create', compact('chickPurchases', 'vaccines'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'chick_purchase_id' => 'required|exists:chick_purchases,id',
                'vaccine_id' => 'required|exists:medicines,id',
                'next_due_date' => 'required|date|after:today',
                'notes' => 'nullable|string',
                'number_immunized' => 'required|integer|min:1',
                'age_category' => 'required|string',
            ]);

            DB::beginTransaction();
            
            // Set immunization date to now
            $validated['immunization_date'] = now();

            // Verify number of birds being immunized doesn't exceed available birds
            $chickPurchase = ChickPurchase::find($validated['chick_purchase_id']);
            if ($validated['number_immunized'] > $chickPurchase->quantity) {
                throw new \Exception('Number of birds to immunize exceeds available birds.');
            }

            ImmunizationRecord::create($validated);

            DB::commit();
            return redirect()
                ->route('bird-immunizations.index')
                ->with('success', 'Immunization record created successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()
                ->route('bird-immunizations.index')
                ->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function show(ImmunizationRecord $immunizationRecord)
    {
        $immunizationRecord->load([
            'chickPurchase.birds',
            'chickPurchase.branch',
            'chickPurchase.supplier',
            'vaccine.supplier'
        ]);
        
        return response()->json($immunizationRecord);
    }

    public function edit(ImmunizationRecord $immunizationRecord)
    {
        $immunizationRecord->load([
            'chickPurchase.birds',
            'chickPurchase.branch',
            'vaccine'
        ]);
        return response()->json($immunizationRecord);
    }

    public function update(Request $request, ImmunizationRecord $immunizationRecord)
    {
        try {
            $validated = $request->validate([
                'chick_purchase_id' => 'required|exists:chick_purchases,id',
                'vaccine_id' => 'required|exists:medicines,id',
                'immunization_date' => 'required|date',
                'next_due_date' => 'required|date|after:immunization_date',
                'notes' => 'nullable|string',
                'number_immunized' => 'required|integer|min:1',
                'age_category' => 'required|string',
            ]);

            DB::beginTransaction();

            // Verify number of birds being immunized doesn't exceed available birds
            $chickPurchase = ChickPurchase::find($validated['chick_purchase_id']);
            if ($validated['number_immunized'] > $chickPurchase->quantity) {
                throw new \Exception('Number of birds to immunize exceeds available birds.');
            }

            $immunizationRecord->update($validated);

            DB::commit();
            return redirect()
                ->route('bird-immunizations.index')
                ->with('success', 'Immunization record updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()
                ->route('bird-immunizations.index')
                ->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function destroy(ImmunizationRecord $immunizationRecord)
    {
        try {
            $immunizationRecord->delete();
            return redirect()
                ->route('bird-immunizations.index')
                ->with('success', 'Immunization record deleted successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->route('bird-immunizations.index')
                ->with('error', 'An error occurred while deleting the record.');
        }
    }
}