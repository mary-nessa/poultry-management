@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-semibold mb-4">Add New Equipment</h1>

    <!-- Equipment Form -->
    <form action="{{ route('equipments.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow-sm" onsubmit="return validateEquipmentName()">
        @csrf

        <!-- Equipment Name -->
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Equipment Name</label>
            <input type="text" id="name" name="name" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" value="{{ old('name') }}" required pattern="[A-Za-z\s]+" title="Only letters and spaces are allowed">
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
            {{-- <div>
                
                {{-- <button type="button" class="btn btn-outline-primary" onclick="openModal()">Add New Supplier</button>
            <i class="fas fa-plus"></i>
            </div> --}}
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
{{-- 
    <!-- Modal Overlay -->
<div id="modal-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden flex items-center justify-center">
    <!-- Modal -->
    <div id="supplier-modal" class="bg-white rounded-lg shadow-xl w-full max-w-lg mx-4 z-50 overflow-y-auto max-h-screen">
        <div class="flex justify-between items-center border-b p-4">
            <h3 class="text-xl font-bold">Add New Supplier</h3>
            <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <div class="p-6">
            <form action="{{ route('suppliers.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="modal_name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" id="modal_name" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <div class="flex gap-2">
                        <select id="phone_country_code" name="phone_country_code" class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 w-1/3">
                            @foreach($countryCodes as $code => $country)
                                <option value="{{ $code }}">{{ $country }} ({{ $code }})</option>
                            @endforeach
                        </select>
                        <input type="text" id="phone_number" name="phone_number" class="w-2/3 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter phone number" required>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="modal_email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="modal_email" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="mb-4">
                    <label for="branch_id" class="block text-sm font-medium text-gray-700 mb-1">Branch</label>
                    <select name="branch_id" id="branch_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        @foreach($branches as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition duration-200">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-200">
                        Save Supplier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> --}}

</div>

<script>
    // Client-side validation for duplicate entry check
    function validateEquipmentName() {
        const name = document.getElementById('name').value;
        const errorMessage = document.getElementById('name-error');
        
        // Prevent form submission if the name contains numbers
        if (/\d/.test(name)) {
            alert("Equipment name cannot contain numbers.");
            return false;
        }

        return true;
    }

    function openModal() {
        document.getElementById('modal-overlay').classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevent scrolling behind modal
    }
    
    function closeModal() {
        document.getElementById('modal-overlay').classList.add('hidden');
        document.body.style.overflow = 'auto'; // Re-enable scrolling
    }
    
    // Close modal if clicked outside of it
    document.getElementById('modal-overlay').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
    
    // Prevent leading zero in phone number
    document.getElementById("phone_number").addEventListener("input", function(e) {
        if (this.value.startsWith("0")) {
            this.value = this.value.replace(/^0+/, "");
        }
    });
    
    // Initialize TomSelect for country codes if available
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof TomSelect !== 'undefined') {
            new TomSelect("#phone_country_code", { create: false, sortField: "text" });
        }
    });
</script>
@endsection
