<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class SupplierController extends Controller
{
    private function getCountryCodes()
    {
        try {
            $response = Http::get('https://restcountries.com/v3.1/all?fields=name,idd');
            if ($response->successful()) {
                $countries = $response->json();
                $countryCodes = [];
                foreach ($countries as $country) {
                    if (isset($country['idd']) && isset($country['idd']['root']) && isset($country['idd']['suffixes'])) {
                        foreach ($country['idd']['suffixes'] as $suffix) {
                            $code = $country['idd']['root'] . $suffix;
                            $countryCodes[$code] = $country['name']['common'];
                        }
                    }
                }
                return $countryCodes;
            }
            return $this->getFallbackCountryCodes(); // Fallback if API fails
        } catch (\Exception $e) {
            return $this->getFallbackCountryCodes(); // Fallback on error
        }
    }

    private function getFallbackCountryCodes()
    {
        return [
            '+1' => 'United States',
            '+44' => 'United Kingdom',
            '+91' => 'India',
            '+33' => 'France',
            '+61' => 'Australia',
        ];
    }

    public function index(Request $request)
{
    $query = Supplier::query()->with(['feeds', 'medicines', 'equipments', 'chickPurchases', 'branch']);
    
    // Apply name filter if provided
    if ($request->filled('name')) {
        $query->where('name', 'like', '%' . $request->name . '%');
    }
    
    // Apply branch filter if provided
    if ($request->filled('branch')) {
        $query->where('branch_id', $request->branch);
    }
    
    // Get paginated results
    $suppliers = $query->paginate(5)->withQueryString();
    
    return view('suppliers.index', compact('suppliers'));
}
    public function create()
    {
        $countryCodes = $this->getCountryCodes();
        $branches = Branch::all()->pluck('name', 'id')->toArray();
        return view('suppliers.create', compact('countryCodes', 'branches'));
    }

    public function store(Request $request)
{
    $countryCodes = $this->getCountryCodes();
    $branches = Branch::all()->pluck('id')->toArray();

    $validated = $request->validate([
        'name' => 'required|string|max:255|unique:suppliers,name',
        'phone_country_code' => 'required|in:' . implode(',', array_keys($countryCodes)),
        'phone_number' => 'required|regex:/^[0-9]{9,15}$/|unique:suppliers,phone_number',
        'email' => 'nullable|email|max:255|unique:suppliers,email',
        'branch_id' => 'required|in:' . implode(',', $branches),
    ]);

    $supplier = Supplier::create($validated);

    return redirect()->route('suppliers.index')
        ->with('success', 'Supplier created successfully.');
}

public function update(Request $request, Supplier $supplier)
{
    $countryCodes = $this->getCountryCodes();
    $branches = Branch::all()->pluck('id')->toArray();

    $validated = $request->validate([
        'name' => 'required|string|max:255|unique:suppliers,name,' . $supplier->id,
        'phone_country_code' => 'required|in:' . implode(',', array_keys($countryCodes)),
        'phone_number' => 'required|regex:/^[0-9]{9,15}$/|unique:suppliers,phone_number,' . $supplier->id,
        'email' => 'nullable|email|max:255|unique:suppliers,email,' . $supplier->id,
        'branch_id' => 'required|in:' . implode(',', $branches),
    ]);

    $supplier->update($validated);
    return redirect()->route('suppliers.index')
        ->with('success', 'Supplier updated successfully.');
}

    public function show(Supplier $supplier)
    {
        $supplier->load(['feeds', 'medicines', 'equipments', 'chickPurchases', 'branch']);
        return view('suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        $countryCodes = $this->getCountryCodes();
        $branches = Branch::all()->pluck('name', 'id')->toArray();
        return view('suppliers.edit', compact('supplier', 'countryCodes', 'branches'));
    }
    public function destroy(Supplier $supplier)
    {
        // Note: If you want to delete related records, uncomment and adjust as needed
        // $supplier->feeds()->delete();
        // $supplier->medicines()->delete();
        // $supplier->equipments()->delete();
        // $supplier->chickPurchases()->delete();
        
        $supplier->delete();
        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier deleted successfully.');
    }
}