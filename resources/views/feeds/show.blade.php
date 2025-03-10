@extends('layouts.app')

@section('title', 'Feed Details')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Feed Details</h1>
        <p class="text-gray-600 mt-2">View detailed information about this feed</p>
    </div>
    
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-6">
            <!-- Feed Type - Prominent display -->
            <div class="mb-8 pb-6 border-b border-gray-200">
                <span class="text-sm text-gray-500 block mb-1">Feed Type</span>
                <div class="flex items-center">
                    <span class="bg-green-100 text-green-800 py-1 px-3 rounded-full text-sm font-medium">
                        {{ $feed->feedType->name ?? 'N/A' }}
                    </span>
                </div>
            </div>
            
            <!-- Main Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Left Column -->
                <div class="space-y-6">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <span class="text-sm text-gray-500 block mb-1">Quantity</span>
                        <span class="text-xl font-semibold text-gray-800">{{ number_format($feed->quantity_kg, 2) }} kg</span>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <span class="text-sm text-gray-500 block mb-1">Unit Cost</span>
                        <span class="text-xl font-semibold text-gray-800">UGX {{ number_format($feed->unit_cost, 0) }}</span>
                    </div>
                    
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <span class="text-sm text-blue-500 block mb-1">Total Cost</span>
                        <span class="text-xl font-bold text-blue-700">UGX {{ number_format($feed->quantity_kg * $feed->unit_cost, 0) }}</span>
                    </div>
                </div>
                
                <!-- Right Column -->
                <div class="space-y-6">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <span class="text-sm text-gray-500 block mb-1">Purchase Date</span>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-lg font-medium text-gray-800">
                                {{ \Carbon\Carbon::parse($feed->purchase_date)->format('d M, Y') }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <span class="text-sm text-gray-500 block mb-1">Supplier</span>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            @if($feed->supplier)
                                <span class="text-lg font-medium text-gray-800">{{ $feed->supplier->name }}</span>
                            @else
                                <span class="text-lg text-gray-500">No Supplier</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
            <a href="{{ route('feeds.index') }}" class="inline-flex items-center text-gray-700 hover:text-gray-900 transition-colors">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Feeds
            </a>
            
            <div class="space-x-3">
                <a href="{{ route('feeds.edit', $feed->id) }}" class="inline-flex items-center bg-yellow-600 text-white py-2 px-4 rounded-md hover:bg-yellow-700 transition duration-150 ease-in-out shadow-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Feed
                </a>
                
                <form action="{{ route('feeds.destroy', $feed->id) }}" method="POST" class="inline-block" onsubmit="return confirmDelete()">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 transition duration-150 ease-in-out shadow-sm">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete this feed? This action cannot be undone.");
    }
</script>
@endsection