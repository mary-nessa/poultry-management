@extends('layouts.app')

@section('title', 'Edit Feed')

@section('content')
<div class="container">
    <h1 class="text-3xl font-bold mb-6">Edit Feed</h1>

    <form action="{{ route('feeds.update', $feed->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="type" class="block text-sm font-medium text-gray-700">Feed Type</label>
            <input type="text" name="type" id="type" class="mt-1 block w-full border-gray-300 rounded-md" 
                   value="{{ old('type', $feed->type) }}" required>
        </div>

        <div class="mb-4">
            <label for="quantity_kg" class="block text-sm font-medium text-gray-700">Quantity (kg)</label>
            <input type="number" name="quantity_kg" id="quantity_kg" class="mt-1 block w-full border-gray-300 rounded-md" 
                   value="{{ old('quantity_kg', $feed->quantity_kg) }}" required>
        </div>

        <div class="mb-4">
            <label for="unit_cost" class="block text-sm font-medium text-gray-700">Unit Cost</label>
            <input type="number" name="unit_cost" id="unit_cost" class="mt-1 block w-full border-gray-300 rounded-md" 
                   value="{{ old('unit_cost', $feed->unit_cost) }}" required>
        </div>

        <div class="mb-4">
            <input type="date" name="purchase_date" id="purchase_date" 
       class="mt-1 block w-full border-gray-300 rounded-md" 
       value="{{ old('purchase_date', $feed->purchase_date ? date('Y-m-d', strtotime($feed->purchase_date)) : '') }}"
       required max="{{ date('Y-m-d') }}">
        </div>

        <div class="mb-4">
            <label for="supplier_id" class="block text-sm font-medium text-gray-700">Supplier (Optional)</label>
            <select name="supplier_id" id="supplier_id" class="mt-1 block w-full border-gray-300 rounded-md">
                <option value="">No Supplier</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ old('supplier_id', $feed->supplier_id) == $supplier->id ? 'selected' : '' }}>
                        {{ $supplier->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">Update Feed</button>
        </div>
    </form>

    <!-- Close Button -->
    <div class="mt-4">
        <button onclick="window.history.back()" class="bg-gray-500 text-white py-2 px-4 rounded-md hover:bg-gray-600">Close</button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const purchaseDateInput = document.getElementById('purchase_date');

        function updateExpiryMin() {
            if (purchaseDateInput.value) {
                // Set expiry date min to one day after purchase date
                let purchaseDate = new Date(purchaseDateInput.value);
                purchaseDate.setDate(purchaseDate.getDate() + 1);
                const year = purchaseDate.getFullYear();
                const month = ('0' + (purchaseDate.getMonth() + 1)).slice(-2);
                const day = ('0' + purchaseDate.getDate()).slice(-2);
                const minDate = `${year}-${month}-${day}`;
                // If you still need to reference expiry date logic, you'd update here
            }
        }

        purchaseDateInput.addEventListener('change', updateExpiryMin);
        updateExpiryMin(); // Initialize expiry date min restriction
    });
</script>
@endsection
