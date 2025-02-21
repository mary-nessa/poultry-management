@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-semibold mb-4">Edit Equipment</h1>

    <!-- Equipment Edit Form -->
    <form action="{{ route('equipments.update', $equipment->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Equipment Name</label>
            <input type="text" id="name" name="name" value="{{ old('name', $equipment->name) }}" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
            <input type="number" id="quantity" name="quantity" value="{{ old('quantity', $equipment->quantity) }}" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
            @error('quantity')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="unit_cost" class="block text-sm font-medium text-gray-700">Unit Cost</label>
            <input type="number" step="0.01" id="unit_cost" name="unit_cost" value="{{ old('unit_cost', $equipment->unit_cost) }}" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
            @error('unit_cost')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Purchase Date</label>
            @if($equipment->purchase_date)
                <p class="mt-1 p-2 border border-gray-300 rounded-md bg-gray-100">{{ $equipment->purchase_date }}</p>
                <input type="hidden" name="purchase_date" value="{{ $equipment->purchase_date }}">
            @else
                <input type="date" id="purchase_date" name="purchase_date" value="{{ old('purchase_date') }}" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
            @endif
            @error('purchase_date')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
            <select id="status" name="status" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                <option value="active" {{ $equipment->status == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $equipment->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('status')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="supplier_id" class="block text-sm font-medium text-gray-700">Supplier</label>
            <select id="supplier_id" name="supplier_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                <option value="">Select Supplier (Optional)</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ $supplier->id == $equipment->supplier_id ? 'selected' : '' }}>
                        {{ $supplier->name }}
                    </option>
                @endforeach
            </select>
            @error('supplier_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <button type="submit" class="inline-block px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                Update Equipment
            </button>
        </div>
    </form>

     <!-- Close Button -->
     <div class="mt-4">
        <button onclick="window.history.back()" class="bg-gray-500 text-white py-2 px-4 rounded-md hover:bg-gray-600">Close</button>
    </div>
</div>
@endsection
