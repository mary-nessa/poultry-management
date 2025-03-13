@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Equipment Details</h1>
        <a href="{{ route('equipments.index') }}" class="flex items-center text-blue-600 hover:text-blue-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to List
        </a>
    </div>
    
    <!-- Equipment Details Card -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200">
        <!-- Card Header -->
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">{{ $equipment->name }}</h2>
            <span class="inline-block mt-1 px-3 py-1 text-sm rounded-full {{ $equipment->status == 'Available' ? 'bg-green-100 text-green-800' : ($equipment->status == 'In Use' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800') }}">
                {{ $equipment->status }}
            </span>
        </div>
        
        <!-- Card Body -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Basic Information</h3>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <span class="text-gray-500 w-32">Quantity:</span>
                            <span class="font-medium">{{ $equipment->quantity }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-gray-500 w-32">Unit Cost:</span>
                            <span class="font-medium">UGX {{ number_format($equipment->unit_cost, 0) }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-gray-500 w-32">Total Cost:</span>
                            <span class="font-medium text-lg text-blue-700">UGX {{ number_format($equipment->total_cost, 0) }}</span>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Additional Details</h3>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <span class="text-gray-500 w-32">Purchase Date:</span>
                            <span class="font-medium">{{ \Carbon\Carbon::parse($equipment->purchase_date)->format('d M, Y') }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-gray-500 w-32">Supplier:</span>
                            <span class="font-medium">{{ $equipment->supplier ? $equipment->supplier->name : 'No Supplier' }}</span>
                        </div>
                        @if($equipment->warranty_end)
                        <div class="flex items-center">
                            <span class="text-gray-500 w-32">Warranty Until:</span>
                            <span class="font-medium">{{ \Carbon\Carbon::parse($equipment->warranty_end)->format('d M, Y') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Card Footer -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
            <a href="{{ route('equipments.edit', $equipment) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit
            </a>
            <form action="{{ route('equipments.destroy', $equipment) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Are you sure you want to delete this equipment?')" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>
@endsection