@extends('layouts.app')  
@section('content') 
<div class="container">     
    <h1 class="text-3xl font-bold mb-6">Add New Feed</h1>      
    
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('feeds.store') }}" method="POST">         
        @csrf          
       <div class="mb-4">
    <label for="feed_type_id" class="block text-gray-700 text-sm font-bold mb-2">Feed Type</label>
    <select name="feed_type_id" id="feed_type_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        <option value="">Select Feed Type</option>
        @foreach($feedTypes as $type)
            <option value="{{ $type->id }}" {{ old('feed_type_id', $feed->feed_type_id ?? '') == $type->id ? 'selected' : '' }}>
                {{ $type->name }}
            </option>
        @endforeach
    </select>
</div>

        <div class="mb-4">             
            <label for="quantity_kg" class="block text-sm font-medium text-gray-700">Quantity (kg)</label>             
            <input type="number" 
                   name="quantity_kg" 
                   id="quantity_kg" 
                   class="mt-1 block w-full border-gray-300 rounded-md" 
                   value="{{ old('quantity_kg') }}" 
                   min="0"
                   step="0.01"
                   required>         
        </div>          
        
        <div class="mb-4">             
            <label for="unit_cost" class="block text-sm font-medium text-gray-700">Unit Cost</label>             
            <input type="number" 
                   name="unit_cost" 
                   id="unit_cost" 
                   class="mt-1 block w-full border-gray-300 rounded-md" 
                   value="{{ old('unit_cost') }}" 
                   min="0"
                   step="0.01"
                   required>         
        </div>          
        
        <div class="mb-4">             
            <label for="purchase_date" class="block text-sm font-medium text-gray-700">Purchase Date</label>             
            <input type="date" 
                   name="purchase_date" 
                   id="purchase_date" 
                   class="mt-1 block w-full border-gray-300 rounded-md"                    
                   value="{{ old('purchase_date') }}" 
                   required 
                   max="{{ date('Y-m-d') }}">         
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const typeInput = document.getElementById('type');
    const quantityInput = document.getElementById('quantity_kg');
    const unitCostInput = document.getElementById('unit_cost');
    const purchaseDateInput = document.getElementById('purchase_date');

    // Set max date for purchase date to today
    purchaseDateInput.max = new Date().toISOString().split('T')[0];

    form.addEventListener('submit', function(e) {
        // Validate feed type (letters and spaces only)
        if (!/^[A-Za-z\s]+$/.test(typeInput.value.trim())) {
            e.preventDefault();
            alert('Feed type should contain only letters and spaces');
            typeInput.focus();
            return;
        }

        // Validate quantity
        if (parseFloat(quantityInput.value) <= 0) {
            e.preventDefault();
            alert('Quantity must be greater than 0');
            quantityInput.focus();
            return;
        }

        // Validate unit cost
        if (parseFloat(unitCostInput.value) <= 0) {
            e.preventDefault();
            alert('Unit cost must be greater than 0');
            unitCostInput.focus();
            return;
        }

        // Validate purchase date
        const selectedDate = new Date(purchaseDateInput.value);
        const today = new Date();
        if (selectedDate > today) {
            e.preventDefault();
            alert('Purchase date cannot be in the future');
            purchaseDateInput.focus();
            return;
        }
    });

    // Prevent typing negative signs in number inputs
    [quantityInput, unitCostInput].forEach(input => {
        input.addEventListener('keydown', function(e) {
            if (e.key === '-' || e.keyCode === 189) {
                e.preventDefault();
            }
        });

        // Additional validation on input
        input.addEventListener('input', function() {
            if (this.value < 0) {
                this.value = 0;
            }
        });
    });

    // Feed type validation on input
    typeInput.addEventListener('input', function() {
        this.value = this.value.replace(/[^A-Za-z\s]/g, '');
    });
});
</script>

@endsection