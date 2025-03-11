@extends('layouts.app')

@section('title', 'Edit Bird Purchase')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Edit Bird Purchase</h1>
            <a href="{{ route('chick-purchases.index') }}" class="text-blue-600 hover:text-blue-800">← Back to list</a>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('chick-purchases.update', $chickPurchase) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="mb-4">
                        <label for="branch_id" class="block text-gray-700 text-sm font-bold mb-2">Branch</label>
                        @if(auth()->user()->hasRole('admin'))
                            <select name="branch_id" id="branch_id" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">Select Branch</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ $chickPurchase->branch_id == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                                @endforeach
                            </select>
                            @error('branch_id')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        @else
                            <input type="text" value="{{ auth()->user()->branch?->name ?? 'No branch assigned' }}" readonly class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <input type="hidden" name="branch_id" value="{{ auth()->user()->branch?->id }}">
                        @endif
                    </div>
                    <div class="mb-4">
                        <label for="supplier_id" class="block text-gray-700 text-sm font-bold mb-2">Supplier</label>
                        <select name="supplier_id" id="supplier_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Select Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ $chickPurchase->supplier_id == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                        @error('supplier_id')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="breed_id" class="block text-gray-700 text-sm font-bold mb-2">Breed</label>
                        <select name="breed_id" id="breed_id" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Select Breed</option>
                            @foreach($breeds as $breed)
                                <option value="{{ $breed->id }}" {{ $chickPurchase->breed_id == $breed->id ? 'selected' : '' }}>{{ $breed->name }}</option>
                            @endforeach
                        </select>
                        @error('breed_id')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="purchase_age" class="block text-gray-700 text-sm font-bold mb-2">Purchase Age (in days)</label>
                        <input type="number" name="purchase_age" id="purchase_age" min="0" required value="{{ old('purchase_age', $chickPurchase->purchase_age) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @error('purchase_age')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="purchase_date" class="block text-sm font-medium text-gray-700">Purchase Date</label>
                        <input type="date" 
                               name="purchase_date" 
                               id="purchase_date" 
                               class="mt-1 block w-full border-gray-300 rounded-md focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                               value="{{ old('purchase_date', $chickPurchase->purchase_date->format('Y-m-d')) }}"
                               required 
                               max="{{ date('Y-m-d') }}">
                        @error('purchase_date')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="quantity" class="block text-gray-700 text-sm font-bold mb-2">Quantity</label>
                        <input type="number" name="quantity" id="quantity" min="1" required value="{{ old('quantity', $chickPurchase->quantity) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @error('quantity')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="unit_cost" class="block text-gray-700 text-sm font-bold mb-2">Unit Cost</label>
                        <input type="number" step="0.01" name="unit_cost" id="unit_cost" required value="{{ old('unit_cost', $chickPurchase->unit_cost) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @error('unit_cost')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="total_cost" class="block text-gray-700 text-sm font-bold mb-2">Total Cost</label>
                        <input type="number" step="0.01" name="total_cost" id="total_cost" value="{{ old('total_cost', $chickPurchase->total_cost) }}" class="bg-gray-100 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight" readonly>
                        @error('total_cost')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="flex justify-end mt-6">
                    <a href="{{ route('chick-purchases.index') }}" class="mr-4 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const quantityInput = document.getElementById('quantity');
                const unitCostInput = document.getElementById('unit_cost');
                const totalCostInput = document.getElementById('total_cost');
                
                function calculateTotalCost() {
                    const quantity = parseFloat(quantityInput.value) || 0;
                    const unitCost = parseFloat(unitCostInput.value) || 0;
                    totalCostInput.value = (quantity * unitCost).toFixed(2);
                }
                
                // Initial calculation
                calculateTotalCost();
                
                // Recalculate on input change
                quantityInput.addEventListener('input', calculateTotalCost);
                unitCostInput.addEventListener('input', calculateTotalCost);
            });
        </script>
    @endpush
@endsection