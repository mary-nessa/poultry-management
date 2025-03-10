@extends('layouts.app')

@section('title', 'Add New Feed')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-6xl">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Add New Feed</h1>
        <p class="text-gray-600 mt-2">Enter feed details and manage your cart</p>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow" role="alert">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium">Please correct the following errors:</p>
                    <ul class="mt-1 text-sm list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Two-column layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Left Column: Form -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <form id="feedForm" method="POST">
                @csrf
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Feed Type -->
                        <div class="col-span-1 md:col-span-2">
                            <label for="feed_type_id" class="block text-sm font-medium text-gray-700 mb-1">Feed Type <span class="text-red-500">*</span></label>
                            <div class="flex">
                                <div class="relative flex-grow">
                                    <select name="feed_type_id" id="feed_type_id" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 rounded-md" required>
                                        <option value="">Select Feed Type</option>
                                        @foreach($feedTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="button" onclick="document.getElementById('createModal').classList.remove('hidden')" class="ml-2 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700" title="Add New Feed Type">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Quantity -->
                        <div>
                            <label for="quantity_kg" class="block text-sm font-medium text-gray-700 mb-1">Quantity (kg) <span class="text-red-500">*</span></label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="number" name="quantity_kg" id="quantity_kg" class="block w-full pr-12 border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="0.00" min="0.01" step="0.01" required>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">kg</span>
                                </div>
                            </div>
                        </div>

                        <!-- Unit Cost -->
                        <div>
                            <label for="unit_cost" class="block text-sm font-medium text-gray-700 mb-1">Unit Cost <span class="text-red-500">*</span></label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">UGX</span>
                                </div>
                                <input type="number" name="unit_cost" id="unit_cost" class="block w-full pl-12 border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="0.00" min="0.01" step="0.01" required>
                            </div>
                        </div>

                        <!-- Purchase Date -->
                        <div>
                            <label for="purchase_date" class="block text-sm font-medium text-gray-700 mb-1">Purchase Date <span class="text-red-500">*</span></label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="date" name="purchase_date" id="purchase_date" class="block w-full pl-10 border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" required max="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <!-- Supplier -->
                        <div class="col-span-1 md:col-span-2">
                            <label for="supplier_id" class="block text-sm font-medium text-gray-700 mb-1">Supplier <span class="text-gray-500 text-xs ml-1">(Optional)</span></label>
                            <select name="supplier_id" id="supplier_id" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 rounded-md">
                                <option value="">No Supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Notes -->
                        <div class="col-span-1 md:col-span-2">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes <span class="text-gray-500 text-xs ml-1">(Optional)</span></label>
                            <textarea name="notes" id="notes" rows="3" class="block w-full border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="Add any additional information..."></textarea>
                        </div>
                    </div>

                    <!-- Total Cost Preview -->
                    <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-blue-800">Total Cost:</span>
                            <span id="totalCost" class="text-lg font-bold text-blue-800">UGX 0.00</span>
                        </div>
                    </div>

                    <!-- Add to Cart Button -->
                    <div class="mt-4">
                        <button type="button" id="addToCart" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add to Cart
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Right Column: Cart -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Feed Cart</h2>
                <div class="overflow-x-auto">
                    <table id="cartTable" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Feed Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Cost</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="cartBody" class="bg-white divide-y divide-gray-200">
                            <!-- Cart items will be added here dynamically -->
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-blue-800">Grand Total:</span>
                        <span id="grandTotal" class="text-lg font-bold text-blue-800">UGX 0.00</span>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
                <button type="button" id="submitCart" class="inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700" disabled>
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Save All Feeds
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Create Feed Type Modal -->
<div id="createModal" class="fixed inset-0 z-10 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('createModal').classList.add('hidden')"></div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Create New Feed Type</h3>
                        <div class="mt-2">
                            <form id="createForm" method="POST" action="{{ route('feedtypes.store') }}">
                                @csrf
                                <input type="hidden" name="redirect_back" value="true">
                                <div class="space-y-6">
                                    <div>
                                        <label for="create_name" class="block text-sm font-medium text-gray-700">Name <span class="text-red-500">*</span></label>
                                        <input type="text" name="name" id="create_name" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Enter feed type name" required>
                                        <p class="mt-2 text-sm text-gray-500">Only letters and spaces are allowed.</p>
                                    </div>
                                    <div>
                                        <label for="create_description" class="block text-sm font-medium text-gray-700">Description</label>
                                        <textarea id="create_description" name="description" rows="4" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Enter description (optional)"></textarea>
                                        <p class="mt-2 text-sm text-gray-500">Only letters and spaces are allowed.</p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" id="saveFeedTypeBtn" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">Save Feed Type</button>
                <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const feedForm = document.getElementById('feedForm');
    const addToCartBtn = document.getElementById('addToCart');
    const cartBody = document.getElementById('cartBody');
    const grandTotalDisplay = document.getElementById('grandTotal');
    const submitCartBtn = document.getElementById('submitCart');
    const quantityInput = document.getElementById('quantity_kg');
    const unitCostInput = document.getElementById('unit_cost');
    const purchaseDateInput = document.getElementById('purchase_date');
    const totalCostDisplay = document.getElementById('totalCost');
    let cartItems = [];

    // Set max date for purchase date to today
    purchaseDateInput.max = new Date().toISOString().split('T')[0];

    // Calculate and update total cost
    function updateTotalCost() {
        const quantity = parseFloat(quantityInput.value) || 0;
        const unitCost = parseFloat(unitCostInput.value) || 0;
        const total = quantity * unitCost;
        totalCostDisplay.textContent = `UGX ${total.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
    }

    // Event listeners for total cost update
    quantityInput.addEventListener('input', updateTotalCost);
    unitCostInput.addEventListener('input', updateTotalCost);

    // Prevent negative input
    [quantityInput, unitCostInput].forEach(input => {
        input.addEventListener('keydown', e => { if (e.key === '-' || e.keyCode === 189) e.preventDefault(); });
        input.addEventListener('input', function() { if (this.value < 0) this.value = 0; });
    });

    // Add to Cart
    addToCartBtn.addEventListener('click', function() {
        const feedTypeId = document.getElementById('feed_type_id').value;
        const quantity = parseFloat(quantityInput.value);
        const unitCost = parseFloat(unitCostInput.value);
        const purchaseDate = purchaseDateInput.value;
        const supplierId = document.getElementById('supplier_id').value;
        const notes = document.getElementById('notes').value;

        // Validation
        if (!feedTypeId || quantity <= 0 || unitCost <= 0 || !purchaseDate) {
            alert('Please fill all required fields with valid values');
            return;
        }

        const today = new Date();
        today.setHours(0, 0, 0, 0);
        if (new Date(purchaseDate) > today) {
            alert('Purchase date cannot be in the future');
            return;
        }

        const feedTypeName = document.getElementById('feed_type_id').selectedOptions[0].text;
        const supplierName = supplierId ? document.getElementById('supplier_id').selectedOptions[0].text : 'No Supplier';
        const total = quantity * unitCost;

        cartItems.push({
            feed_type_id: feedTypeId,
            quantity_kg: quantity,
            unit_cost: unitCost,
            purchase_date: purchaseDate,
            supplier_id: supplierId || null,
            notes: notes,
            feed_type_name: feedTypeName,
            supplier_name: supplierName,
            total: total
        });

        updateCartDisplay();
        feedForm.reset();
        document.getElementById('feed_type_id').value = '';
        document.getElementById('supplier_id').value = '';
        updateTotalCost();
    });

    // Update Cart Display
    function updateCartDisplay() {
        cartBody.innerHTML = '';
        let grandTotal = 0;

        cartItems.forEach((item, index) => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.feed_type_name}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.quantity_kg.toFixed(2)} kg</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">UGX ${item.unit_cost.toLocaleString()}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">UGX ${item.total.toLocaleString()}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.purchase_date}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.supplier_name}</td>
                <td class="px-6 py-4 text-sm text-gray-900">${item.notes || '-'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    <button type="button" class="text-red-600 hover:text-red-900 remove-item" data-index="${index}">Remove</button>
                </td>
            `;
            cartBody.appendChild(row);
            grandTotal += item.total;
        });

        grandTotalDisplay.textContent = `UGX ${grandTotal.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
        submitCartBtn.disabled = cartItems.length === 0;
    }

    // Remove Item
    cartBody.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item')) {
            const index = parseInt(e.target.dataset.index);
            cartItems.splice(index, 1);
            updateCartDisplay();
        }
    });

    // Submit Cart with Redirect
    submitCartBtn.addEventListener('click', function() {
        if (cartItems.length === 0) return;

        fetch('{{ route('feeds.store') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ feeds: cartItems })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Feeds saved successfully!');
                cartItems = [];
                updateCartDisplay();
                // Redirect to index page
                window.location.href = '{{ route('feeds.index') }}';
            } else {
                alert('Error saving feeds: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while saving feeds');
        });
    });

    // Feed Type Modal Handling
    const saveFeedTypeBtn = document.getElementById('saveFeedTypeBtn');
    const createForm = document.getElementById('createForm');

    saveFeedTypeBtn.addEventListener('click', function() {
        const nameInput = document.getElementById('create_name');
        if (!nameInput.value.trim()) {
            alert('Feed type name is required');
            nameInput.focus();
            return;
        }

        const payload = {
            name: nameInput.value.trim(),
            description: document.getElementById('create_description').value.trim()
        };

        fetch(createForm.action, {
            method: 'POST',
            body: JSON.stringify(payload),
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const feedTypeSelect = document.getElementById('feed_type_id');
                const newOption = document.createElement('option');
                newOption.value = data.feedType.id;
                newOption.textContent = data.feedType.name;
                feedTypeSelect.appendChild(newOption);
                feedTypeSelect.value = data.feedType.id;
                document.getElementById('createModal').classList.add('hidden');
                createForm.reset();
                alert('Feed type added successfully');
            } else {
                alert('Error adding feed type: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred: ' + error.message);
        });
    });

    // Initial total cost
    updateTotalCost();
});
</script>
@endsection