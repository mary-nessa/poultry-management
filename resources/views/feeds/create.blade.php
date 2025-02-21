@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-3xl font-bold mb-6">Add New Feed</h1>

    <form action="{{ route('feeds.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="type" class="block text-sm font-medium text-gray-700">Feed Type</label>
            <input type="text" name="type" id="type" class="mt-1 block w-full border-gray-300 rounded-md" value="{{ old('type') }}" required>
        </div>

        <div class="mb-4">
            <label for="quantity_kg" class="block text-sm font-medium text-gray-700">Quantity (kg)</label>
            <input type="number" name="quantity_kg" id="quantity_kg" class="mt-1 block w-full border-gray-300 rounded-md" value="{{ old('quantity_kg') }}" required>
        </div>

        <div class="mb-4">
            <label for="unit_cost" class="block text-sm font-medium text-gray-700">Unit Cost</label>
            <input type="number" name="unit_cost" id="unit_cost" class="mt-1 block w-full border-gray-300 rounded-md" value="{{ old('unit_cost') }}" required>
        </div>

        <div class="mb-4">
            <label for="purchase_date" class="block text-sm font-medium text-gray-700">Purchase Date</label>
            <input type="date" name="purchase_date" id="purchase_date" class="mt-1 block w-full border-gray-300 rounded-md"
                   value="{{ old('purchase_date') }}" required max="{{ date('Y-m-d') }}">
        </div>

        <div class="mb-4">
            <label for="expiry_date" class="block text-sm font-medium text-gray-700">Expiry Date</label>
            <input type="date" name="expiry_date" id="expiry_date" class="mt-1 block w-full border-gray-300 rounded-md" value="{{ old('expiry_date') }}">
        </div>

        <div class="mb-4">
            <label for="supplier_id" class="block text-sm font-medium text-gray-700">Supplier (Optional)</label>
            <select name="supplier_id" id="supplier_id" class="mt-1 block w-full border-gray-300 rounded-md">
                <option value="">No Supplier</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                        {{ $supplier->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">Save Feed</button>
        </div>
    </form>

    <!-- Close Button -->
    <div class="mt-4">
        <button onclick="window.history.back()" class="bg-gray-500 text-white py-2 px-4 rounded-md hover:bg-gray-600">Close</button>
    </div>
</div>
@endsection
