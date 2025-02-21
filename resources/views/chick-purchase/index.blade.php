@extends('layouts.app')

@section('title', 'Chick Purchases')

@section('content')
    <div class="container mx-auto px-4 py-6" x-data="chickPurchaseManagement()">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Chick Purchases</h1>
            <button @click="openCreateModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                New Chick Purchase
            </button>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Branch</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Breed</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purchase Age</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>

                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @foreach($chickPurchases as $purchase)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $purchase->branch->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $purchase->breed }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $purchase->purchase_age }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $purchase->quantity }}</td>

                        <td class="px-6 py-4 whitespace-nowrap">{{ $purchase->created_at->format('d-m-Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button @click="openShowModal('{{ $purchase->id }}')" class="text-blue-600 hover:text-blue-900 mr-3">View</button>
                            <button @click="openEditModal('{{ $purchase->id }}')" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                            <form action="{{ route('chick-purchases.destroy', $purchase) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this purchase?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <!-- Create Purchase Modal -->
        <div x-show="showCreateModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form action="{{ route('chick-purchases.store') }}" method="POST">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Create New Chick Purchase</h3>
                            <div class="mb-4">
{{--                                if user is admin lets show all branches otherwise only show the branch of the user--}}
                                <label for="branch_id" class="block text-gray-700 text-sm font-bold mb-2">Branch</label>
                                @if(auth()->user()->hasRole('admin'))
                                    <select name="branch_id" id="branch_id" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        <option value="">Select Branch</option>
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="text" value="{{ auth()->user()->branch?->name?? 'No branch assigned' }}" readonly class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <input type="hidden" name="branch_id" value="{{ auth()->user()->branch?->id }}">

                                @endif

                            </div>
                            <div class="mb-4">
                                <label for="supplier_id" class="block text-gray-700 text-sm font-bold mb-2">Supplier</label>
                                <select name="supplier_id" id="supplier_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option value="">Select Supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="breed" class="block text-gray-700 text-sm font-bold mb-2">Breed</label>
                                <input type="text" name="breed" id="breed" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
                            <div class="mb-4">
                                <label for="purchase_age" class="block text-gray-700 text-sm font-bold mb-2">Purchase Age (in days)</label>
                                <input type="number" name="purchase_age" id="purchase_age" min="0" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
                            <div class="mb-4">
                                <label for="quantity" class="block text-gray-700 text-sm font-bold mb-2">Quantity</label>
                                <input type="number" name="quantity" id="quantity" min="1" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
                            <div class="mb-4">
                                <label for="unit_cost" class="block text-gray-700 text-sm font-bold mb-2">Unit Cost</label>
                                <input type="number" step="0.01" name="unit_cost" id="unit_cost" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
{{--                            total cost calculated in the backend--}}
{{--                            date handled in the backend--}}
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Create
                            </button>
                            <button type="button" @click="showCreateModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Purchase Modal -->
        <div x-show="showEditModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form :action="'/chick-purchasess/' + editPurchaseId" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Chick Purchase</h3>
                            <div class="mb-4">
                                <label for="edit_branch_id" class="block text-gray-700 text-sm font-bold mb-2">Branch</label>
                                <select name="branch_id" id="edit_branch_id" x-model="editPurchaseData.branch_id" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option value="">Select Branch</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="edit_supplier_id" class="block text-gray-700 text-sm font-bold mb-2">Supplier</label>
                                <select name="supplier_id" id="edit_supplier_id" x-model="editPurchaseData.supplier_id" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option value="">Select Supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="edit_breed" class="block text-gray-700 text-sm font-bold mb-2">Breed</label>
                                <input type="text" name="breed" id="edit_breed" x-model="editPurchaseData.breed" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
                            <div class="mb-4">
                                <label for="edit_purchase_age" class="block text-gray-700 text-sm font-bold mb-2">Purchase Age (in days)</label>
                                <input type="number" name="purchase_age" id="edit_purchase_age" x-model="editPurchaseData.purchase_age" min="0" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
                            <div class="mb-4">
                                <label for="edit_quantity" class="block text-gray-700 text-sm font-bold mb-2">Quantity</label>
                                <input type="number" name="quantity" id="edit_quantity" x-model="editPurchaseData.quantity" min="1" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
                            <div class="mb-4">
                                <label for="edit_unit_cost" class="block text-gray-700 text-sm font-bold mb-2">Unit Cost</label>
                                <input type="number" step="0.01" name="unit_cost" id="edit_unit_cost" x-model="editPurchaseData.unit_cost" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
                            <div class="mb-4">
                                <label for="edit_total_cost" class="block text-gray-700 text-sm font-bold mb-2">Total Cost</label>
                                <input type="number" step="0.01" name="total_cost" id="edit_total_cost" x-model="editPurchaseData.total_cost" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
                            <div class="mb-4">
                                <label for="edit_date" class="block text-gray-700 text-sm font-bold mb-2">Date</label>
                                <input type="date" name="date" id="edit_date" x-model="editPurchaseData.date" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Update
                            </button>
                            <button type="button" @click="showEditModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Show Purchase Modal -->
        <div x-show="showShowModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Purchase Details</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Branch</label>
                                <p class="text-gray-900" x-text="showPurchaseData.branch ? showPurchaseData.branch.name : 'N/A'"></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Supplier</label>
                                <p class="text-gray-900" x-text="showPurchaseData.supplier ? showPurchaseData.supplier.name : 'N/A'"></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Breed</label>
                                <p class="text-gray-900" x-text="showPurchaseData.breed"></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Purchase Age</label>
                                <p class="text-gray-900" x-text="showPurchaseData.purchase_age"></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Quantity</label>
                                <p class="text-gray-900" x-text="showPurchaseData.quantity"></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Unit Cost</label>
                                <p class="text-gray-900" x-text="showPurchaseData.unit_cost"></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Total Cost</label>
                                <p class="text-gray-900" x-text="showPurchaseData.total_cost"></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Date</label>
                                <p class="text-gray-900" x-text="showPurchaseData.date"></p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" @click="showShowModal = false" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:w-auto sm:text-sm">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function chickPurchaseManagement() {
                return {
                    showCreateModal: false,
                    showEditModal: false,
                    showShowModal: false,
                    editPurchaseId: null,
                    editPurchaseData: {
                        branch_id: '',
                        supplier_id: '',
                        breed: '',
                        purchase_age: 0,
                        quantity: 0,
                        unit_cost: 0,
                        total_cost: 0,
                        date: ''
                    },
                    showPurchaseData: {
                        branch: null,
                        supplier: null,
                        breed: '',
                        purchase_age: 0,
                        quantity: 0,
                        unit_cost: 0,
                        total_cost: 0,
                        date: ''
                    },
                    openCreateModal() {
                        this.showCreateModal = true;
                    },
                    async openEditModal(purchaseId) {
                        this.editPurchaseId = purchaseId;
                        try {
                            const response = await fetch(`/chick-purchasess/${purchaseId}/edit`);
                            const data = await response.json();
                            this.editPurchaseData = data;
                            this.showEditModal = true;
                        } catch (error) {
                            console.error('Error fetching purchase data:', error);
                        }
                    },
                    async openShowModal(purchaseId) {
                        try {
                            const response = await fetch(`/chick-purchasess/${purchaseId}`);
                            const data = await response.json();
                            this.showPurchaseData = data;
                            this.showShowModal = true;
                        } catch (error) {
                            console.error('Error fetching purchase data:', error);
                        }
                    }
                }
            }
        </script>
    @endpush
@endsection
