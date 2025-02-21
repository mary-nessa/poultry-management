@extends('layouts.app')

@section('content')
<div class="container mx-auto my-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">{{ $medicine->name }} Details</h1>

    <div class="bg-white shadow-lg rounded-lg">
        <div class="bg-gray-800 text-white p-4 rounded-t-lg">
            <h2 class="text-xl font-semibold">Medicine Information</h2>
        </div>
        <div class="p-6">
            <table class="w-full table-auto border-collapse">
                <tr class="border-b">
                    <th class="px-4 py-2 text-left font-medium text-gray-700">Name</th>
                    <td class="px-4 py-2 text-gray-900">{{ $medicine->name }}</td>
                </tr>
                <tr class="border-b">
                    <th class="px-4 py-2 text-left font-medium text-gray-700">Quantity</th>
                    <td class="px-4 py-2 text-gray-900">{{ $medicine->quantity }}</td>
                </tr>
                <tr class="border-b">
                    <th class="px-4 py-2 text-left font-medium text-gray-700">Unit Cost</th>
                    <td class="px-4 py-2 text-gray-900">{{ $medicine->unit_cost }}</td>
                </tr>
                <tr class="border-b">
                    <th class="px-4 py-2 text-left font-medium text-gray-700">Total Cost</th>
                    <td class="px-4 py-2 text-gray-900">{{ $medicine->total_cost }}</td>
                </tr>
                <tr class="border-b">
                    <th class="px-4 py-2 text-left font-medium text-gray-700">Expiry Date</th>
                    <td class="px-4 py-2 text-gray-900">
                        {{ $medicine->expiry_date 
                            ? \Carbon\Carbon::parse($medicine->expiry_date)->format('d/m/Y') 
                            : 'N/A' 
                        }}
                    </td>
                </tr>
                <tr class="border-b">
                    <th class="px-4 py-2 text-left font-medium text-gray-700">Supplier</th>
                    <td class="px-4 py-2 text-gray-900">{{ $medicine->supplier->name ?? 'No Supplier' }}</td>
                </tr>
                <tr class="border-b">
                    <th class="px-4 py-2 text-left font-medium text-gray-700">Purpose</th>
                    <td class="px-4 py-2 text-gray-900">{{ $medicine->purpose }}</td>
                </tr>
            </table>
        </div>
    </div>

    <a href="{{ route('medicine.index') }}" class="mt-4 inline-block px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 focus:ring-2 focus:ring-gray-400">Back to Medicines</a>
</div>
@endsection
