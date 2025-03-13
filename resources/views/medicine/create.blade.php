@extends('layouts.app')

@section('content')
<div class="container mx-auto my-8">
    <div class="max-w-3xl mx-auto p-6 bg-white rounded-lg shadow-lg">
        <!-- Title Styled -->
        <h1 class="text-4xl font-extrabold text-center text-blue-600 mb-4">Add New Medicine</h1>  <!-- Larger, Bold Title -->
        <h2 class="text-2xl font-semibold text-center text-gray-700 mb-6">Medicine Purchase Form</h2>  <!-- Subtitle -->

        <form action="{{ route('medicine.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Medicine Name -->
                <div class="form-group">
                    <label for="name" class="block text-sm font-medium text-gray-700">Medicine Name</label>
                    <input type="text" name="name" id="name" class="form-control mt-2 p-2 border rounded-md w-full focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter medicine name" required>
                </div>
                
                <!-- Quantity -->
                <div class="form-group">
                    <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                    <input type="number" name="quantity" id="quantity" class="form-control mt-2 p-2 border rounded-md w-full focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter quantity" required oninput="calculateTotal()">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <!-- Unit Cost -->
                <div class="form-group">
                    <label for="unit_cost" class="block text-sm font-medium text-gray-700">Unit Cost</label>
                    <input type="number" step="0.01" name="unit_cost" id="unit_cost" class="form-control mt-2 p-2 border rounded-md w-full focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter unit cost" required oninput="calculateTotal()">
                </div>

                <!-- Total Cost -->
                <div class="form-group">
                    <label for="total_cost" class="block text-sm font-medium text-gray-700">Total Cost</label>
                    <input type="number" step="0.01" name="total_cost" id="total_cost" class="form-control mt-2 p-2 border rounded-md w-full focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Total cost" readonly>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <!-- Expiry Date -->
                <div class="form-group">
                    <label for="expiry_date" class="block text-sm font-medium text-gray-700">Expiry Date</label>
                    <input type="date" name="expiry_date" id="expiry_date" class="form-control mt-2 p-2 border rounded-md w-full focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>

                <!-- Supplier -->
                <div class="form-group">
                    <label for="supplier_id" class="block text-sm font-medium text-gray-700">Supplier</label>
                    <select name="supplier_id" id="supplier_id" class="form-control mt-2 p-2 border rounded-md w-full focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        <option value="">Select Supplier</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Purpose -->
            <div class="form-group mt-6">
                <label for="purpose" class="block text-sm font-medium text-gray-700">Purpose</label>
                <input type="text" name="purpose" id="purpose" class="form-control mt-2 p-2 border rounded-md w-full focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter the purpose of the medicine" required>
            </div>

            <div class="mt-6 text-center">
                <button type="submit" class="btn btn-success mt-3 py-2 px-6 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-300">Add Medicine</button>

            <a href="{{ route('medicine.index') }}" class="mt-4 inline-block px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 focus:ring-2 focus:ring-gray-400">Back to Medicines</a>
            
            </div>

        </form>
    </div>

    
</div>

<script>
    function calculateTotal() {
        const quantity = parseFloat(document.getElementById('quantity').value);
        const unitCost = parseFloat(document.getElementById('unit_cost').value);
        const totalCostField = document.getElementById('total_cost');
        
        if (!isNaN(quantity) && !isNaN(unitCost)) {
            const totalCost = quantity * unitCost;
            totalCostField.value = totalCost.toFixed(2);
        } else {
            totalCostField.value = '';
        }
    }
</script>
@endsection
