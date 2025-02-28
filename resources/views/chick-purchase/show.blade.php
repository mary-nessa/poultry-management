@extends('layouts.app')

@section('title', 'View Bird Purchase')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Bird Purchase Details</h1>
                <a href="{{ route('chick-purchases.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center mt-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to list
                </a>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('chick-purchases.edit', $chickPurchase) }}" class="flex items-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
                <form action="{{ route('chick-purchases.destroy', $chickPurchase) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this purchase?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="flex items-center bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <!-- Header with basic information -->
            <div class="bg-gray-50 px-6 py-4 border-b">
                <div class="flex flex-col md:flex-row justify-between">
                    <div class="flex items-center mb-3 md:mb-0">
                        <span class="bg-blue-100 text-blue-800 text-sm font-semibold px-3 py-1 rounded-full mr-3">{{ $chickPurchase->breed }}</span>
                        <span class="text-gray-700">{{ $chickPurchase->quantity }} birds</span>
                    </div>
                    <div class="flex flex-col md:flex-row md:items-center text-sm">
                        <span class="flex items-center mr-4 mb-2 md:mb-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Purchased: {{ date('d-m-Y', strtotime($chickPurchase->purchase_date)) }}
                        </span>
                        <span class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Total Cost: {{ number_format($chickPurchase->total_cost, 2) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Main details with cards layout -->
            <div class="px-6 py-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Location Information Card -->
                    <div class="bg-gray-50 rounded-lg p-4 border">
                        <h2 class="text-lg font-semibold text-gray-700 mb-3 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Location Details
                        </h2>
                        <div class="grid grid-cols-1 gap-3">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Branch</h3>
                                <p class="text-gray-900 font-semibold">{{ $chickPurchase->branch->name }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Supplier</h3>
                                <p class="text-gray-900 font-semibold">{{ $chickPurchase->supplier ? $chickPurchase->supplier->name : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Bird Details Card -->
                    <div class="bg-gray-50 rounded-lg p-4 border">
                        <h2 class="text-lg font-semibold text-gray-700 mb-3 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                            Bird Information
                        </h2>
                        <div class="grid grid-cols-1 gap-3">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Breed</h3>
                                <p class="text-gray-900 font-semibold">{{ $chickPurchase->breed }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Purchase Age</h3>
                                <p class="text-gray-900 font-semibold">{{ $chickPurchase->purchase_age }} days</p>
                            </div>
                        </div>
                    </div>

                    <!-- Purchase Details Card -->
                    <div class="bg-gray-50 rounded-lg p-4 border">
                        <h2 class="text-lg font-semibold text-gray-700 mb-3 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Purchase Information
                        </h2>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Quantity</h3>
                                <p class="text-gray-900 font-semibold">{{ $chickPurchase->quantity }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Unit Cost</h3>
                                <p class="text-gray-900 font-semibold">{{ number_format($chickPurchase->unit_cost, 2) }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Total Cost</h3>
                                <p class="text-gray-900 font-semibold">{{ number_format($chickPurchase->total_cost, 2) }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Purchase Date</h3>
                                <p class="text-gray-900 font-semibold">{{ date('d-m-Y', strtotime($chickPurchase->purchase_date)) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- System Information Card -->
                    <div class="bg-gray-50 rounded-lg p-4 border">
                        <h2 class="text-lg font-semibold text-gray-700 mb-3 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            System Information
                        </h2>
                        <div class="grid grid-cols-1 gap-3">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Created At</h3>
                                <p class="text-gray-900 font-semibold">{{ $chickPurchase->created_at->format('d-m-Y H:i') }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Last Updated</h3>
                                <p class="text-gray-900 font-semibold">{{ $chickPurchase->updated_at->format('d-m-Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection