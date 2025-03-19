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
    // Get country codes
    $countryCodes = $this->getCountryCodes();
    Log::info('Country Codes Loaded: ' . json_encode($countryCodes));

    // Get the default branch of the logged-in user
    $user = auth()->user();
    $defaultBranchId = $user->branch_id; // Assuming 'branch_id' is stored in users table

    try {
        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone_country_code' => 'required|in:' . implode(',', array_keys($countryCodes)),
            'phone_number' => [
                'required',
                'regex:/^[1-9][0-9]{8,14}$/',
                function ($attribute, $value, $fail) use ($request) {
                    Log::info('Checking phone: ' . $request->phone_country_code . ' ' . $value);
                    if (Buyer::where('phone_country_code', $request->phone_country_code)
                            ->where('phone_number', $value)
                            ->exists()) {
                        Log::warning('Duplicate phone number detected.');
                        $fail('This phone number is already registered.');
                    }
                },
            ],
            'email' => 'nullable|email|max:255|unique:buyers,email',
            'buyer_type' => 'required|in:WALKIN,REGULAR',
        ]);

        Log::info('Validation Passed. Data: ' . json_encode($validated));

        // Assign the default branch
        $validated['branch_id'] = $defaultBranchId;
        Log::info("Using Default Branch ID: $defaultBranchId");

        // Create Buyer
        Buyer::create($validated);
        Log::info('Buyer Created Successfully.');

        return redirect()->route('buyers.index')
            ->with('success', 'Buyer created successfully.');

    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('Validation failed: ' . json_encode($e->errors()));
        return redirect()->back()
            ->withInput()
            ->withErrors($e->errors());
    } catch (\Exception $e) {
        Log::error('Unexpected Error Creating Buyer: ' . $e->getMessage());
        return redirect()->back()
            ->withInput()
            ->with('error', 'Failed to create buyer. Please try again.');
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