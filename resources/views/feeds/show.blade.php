@extends('layouts.app')

@section('title', 'Feed Details')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="px-6 py-4">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Feed Details</h2>

            <div class="mb-4">
                <label class="block text-gray-600 font-semibold">Feed Type:</label>
                <p class="text-gray-800">{{ $feed->feedType->name ?? 'N/A' }}</p>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-600 font-semibold">Quantity (kg):</label>
                    <p class="text-gray-800">{{ $feed->quantity_kg }} kg</p>
                </div>
                <div>
                    <label class="block text-gray-600 font-semibold">Unit Cost (UGX):</label>
                    <p class="text-gray-800">UGX {{ number_format($feed->unit_cost, 0) }}</p>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-600 font-semibold">Total Cost (UGX):</label>
                <p class="text-gray-800 font-bold">UGX {{ number_format($feed->quantity_kg * $feed->unit_cost, 0) }}</p>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-600 font-semibold">Purchase Date:</label>
                    <p class="text-gray-800">{{ \Carbon\Carbon::parse($feed->purchase_date)->format('d M, Y') }}</p>
                </div>
                <div>
                    <label class="block text-gray-600 font-semibold">Expiry Date:</label>
                    <p class="text-gray-800">{{ $feed->expiry_date ? \Carbon\Carbon::parse($feed->expiry_date)->format('d M, Y') : 'N/A' }}</p>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-600 font-semibold">Supplier:</label>
                <p class="text-gray-800">{{ $feed->supplier ? $feed->supplier->name : 'No Supplier' }}</p>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-100 text-right">
            <a href="{{ route('feeds.index') }}" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
                Back to Feeds
            </a>
            <a href="{{ route('feeds.edit', $feed->id) }}" class="bg-yellow-500 text-white py-2 px-4 rounded hover:bg-yellow-600 ml-2">
                Edit Feed
            </a>
        </div>
    </div>
</div>
@endsection
