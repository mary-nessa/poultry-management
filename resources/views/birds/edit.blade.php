@extends('layouts.app')

@section('title', 'Edit Bird Group')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Edit Bird Group</h1>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('birds.update', $bird->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Select Chick Purchase -->
                <div class="mb-4">
                    <label for="chick_purchase_id" class="block text-gray-700 text-sm font-bold mb-2">Bird Group</label>
                    <select name="chick_purchase_id" id="chick_purchase_id" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @foreach($chickPurchases as $purchase)
                            <option value="{{ $purchase->id }}" 
                                {{ $bird->chick_purchase_id == $purchase->id ? 'selected' : '' }}>
                                {{ $purchase->breed ?? ('Purchase ' . $purchase->id) }} - Batch: {{ $purchase->batch_id }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Hidden Branch ID (to be populated when a chick purchase is selected) -->
                <input type="hidden" name="branch_id" id="branch_id" value="{{ old('branch_id', $bird->branch_id) }}">

                <!-- Hen Count -->
                <div class="mb-4">
                    <label for="hen_count" class="block text-gray-700 text-sm font-bold mb-2">Number of Hens</label>
                    <input type="number" name="hen_count" id="hen_count" min="0" required
                           value="{{ $bird->hen_count }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <!-- Cock Count -->
                <div class="mb-4">
                    <label for="cock_count" class="block text-gray-700 text-sm font-bold mb-2">Number of Cocks</label>
                    <input type="number" name="cock_count" id="cock_count" min="0" required
                           value="{{ $bird->cock_count }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Update
                    </button>
                    <a href="{{ route('birds.index') }}" class="ml-4 text-gray-600 hover:underline">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // JavaScript to update the branch_id field when a chick_purchase is selected
        document.getElementById('chick_purchase_id').addEventListener('change', function() {
            var selectedPurchaseId = this.value;
            // Make an AJAX request to get the branch_id associated with the selected purchase
            fetch(`/api/getAvailableBirds/${selectedPurchaseId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('branch_id').value = data.branch_id;
                });
        });
    </script>
@endsection
