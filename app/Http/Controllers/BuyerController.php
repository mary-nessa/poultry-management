<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class BuyerController extends Controller
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
                            $countryCodes['+' . $code] = $country['name']['common'];
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
        // Fallback country codes in case API fails
        return [
            '+1' => 'United States',
            '+44' => 'United Kingdom',
            '+91' => 'India',
            '+33' => 'France',
            '+61' => 'Australia',
        ];
    }

    public function index()
    {
        $buyers = Buyer::with('branch')->paginate(5);
        $branches = Branch::all();
        return view('buyers.index', compact('buyers', 'branches'));
    }

    public function create()
    {
        $countryCodes = $this->getCountryCodes();
        $branches = Branch::all();
        return view('buyers.create', compact('countryCodes', 'branches'));
    }

    public function store(Request $request)
    {
        $countryCodes = $this->getCountryCodes();
    
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'phone_country_code' => 'required|in:' . implode(',', array_keys($countryCodes)),
                'phone_number' => [
                    'required',
                    'regex:/^[1-9][0-9]{8,14}$/', // Prevents leading zero
                    function ($attribute, $value, $fail) use ($request) {
                        if (Buyer::where('phone_country_code', $request->phone_country_code)
                                ->where('phone_number', $value)
                                ->exists()) {
                            $fail('This phone number is already registered.');
                        }
                    },
                ],
                'email' => 'nullable|email|max:255|unique:buyers,email',
                'buyer_type' => 'required|in:WALKIN,REGULAR',
                'branch_id' => 'required|exists:branches,id',
            ]);
    
            Buyer::create($validated);
    
            return redirect()->route('buyers.index')
                ->with('success', 'Buyer created successfully.');
    
        } catch (\Exception $e) {
            Log::error('Error creating buyer: ' . $e->getMessage());
    
            return redirect()->back()
                ->withInput() // Keep old input values
                ->with('error', 'Failed to create buyer,buyer already in the system. Please try again.');
        }
    }
    
    public function update(Request $request, $id)
    {
        $countryCodes = $this->getCountryCodes();
        $buyer = Buyer::findOrFail($id);
    
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'phone_country_code' => 'required|in:' . implode(',', array_keys($countryCodes)),
                'phone_number' => [
                    'required',
                    'regex:/^[1-9][0-9]{8,14}$/', // Prevents leading zero
                    function ($attribute, $value, $fail) use ($request, $id) {
                        if (Buyer::where('phone_country_code', $request->phone_country_code)
                                ->where('phone_number', $value)
                                ->where('id', '!=', $id) // Exclude current buyer
                                ->exists()) {
                            $fail('This phone number is already registered.');
                        }
                    },
                ],
                'email' => 'nullable|email|max:255|unique:buyers,email,' . $id,
                'buyer_type' => 'required|in:WALKIN,REGULAR',
                'branch_id' => 'required|exists:branches,id',
            ]);
    
            $buyer->update($validated);
    
            return redirect()->route('buyers.index')
                ->with('success', 'Buyer updated successfully!');
    
        } catch (\Exception $e) {
            Log::error('Error updating buyer: ' . $e->getMessage());
    
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update buyer. Please try again.');
        }
    }
    
    public function destroy($id)
    {
        try {
            $buyer = Buyer::findOrFail($id);
            $buyer->delete();
    
            return redirect()->route('buyers.index')
                ->with('success', 'Buyer deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting buyer: ' . $e->getMessage());
    
            return redirect()->route('buyers.index')
                ->with('error', 'Failed to delete buyer. Please try again.');
        }
    }
    

    public function edit($id)
    {
        $buyer = Buyer::findOrFail($id);
        $countryCodes = $this->getCountryCodes();
        $branches = Branch::all();
        return view('buyers.edit', compact('buyer', 'countryCodes', 'branches'));
    }
}