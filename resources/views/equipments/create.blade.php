@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-semibold mb-4">Add New Equipment</h1>

    <!-- Equipment Form -->
    <form action="{{ route('equipments.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow-sm">
        @csrf

        <!-- Equipment Name -->
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Equipment Name</label>
            <input type="text" id="name" name="name" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" value="{{ old('name') }}" required>
            @error('name')
                <div class="text-red-500 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <!-- Quantity -->
        <div class="mb-4">
            <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
            <input type="number" id="quantity" name="quantity" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" value="{{ old('quantity') }}" required>
            @error('quantity')
                <div class="text-red-500 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <!-- Unit Cost -->
        <div class="mb-4">
            <label for="unit_cost" class="block text-sm font-medium text-gray-700">Unit Cost</label>
            <input type="number" id="unit_cost" name="unit_cost" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" value="{{ old('unit_cost') }}" required>
            @error('unit_cost')
                <div class="text-red-500 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <!-- Purchase Date -->
        <div class="mb-4">
            <label for="purchase_date" class="block text-sm font-medium text-gray-700">Purchase Date</label>
            <input type="date" id="purchase_date" name="purchase_date" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" value="{{ old('purchase_date') }}" required max="{{ date('Y-m-d') }}">
            @error('purchase_date')
                <div class="text-red-500 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <!-- Status -->
        <div class="mb-4">
            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
            <select id="status" name="status" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" required>
                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('status')
                <div class="text-red-500 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <!-- Supplier -->
        <div class="mb-4">
            <label for="supplier_id" class="block text-sm font-medium text-gray-700">Supplier (Optional)</label>
            <select id="supplier_id" name="supplier_id" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md">
                <option value="">Select Supplier (Optional)</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                @endforeach
            </select>
            @error('supplier_id')
                <div class="text-red-500 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <!-- Submit Button -->
        <div class="mt-6">
            <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">Add Equipment</button>
        </div>
        <!-- Close Button -->
     <div class="mt-4">
        <button onclick="window.history.back()" class="bg-gray-500 text-white py-2 px-4 rounded-md hover:bg-gray-600">Close</button>
    </div>
    </form>

     
</div>
@endsection
