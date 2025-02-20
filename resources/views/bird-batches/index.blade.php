@extends('layouts.app')

@section('title', 'Bird Batches')

@section('content')
<div class="container mx-auto px-4 py-6" x-data="birdBatchManagement()">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Bird Batches</h1>
        <button @click="openCreateModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            New Bird Batch
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($birdBatches as $batch)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $batch->branch->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($batch->purchase_method) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $batch->purchased_quantity }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $batch->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                   ($batch->status === 'hatched' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($batch->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button @click="openShowModal('{{ $batch->id }}')" class="text-blue-600 hover:text-blue-900 mr-3">View</button>
                            <button @click="openEditModal('{{ $batch->id }}')" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                            <form action="{{ route('bird-batches.destroy', $batch) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this batch?')">
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

    <!-- Create Batch Modal -->
    <div x-show="showCreateModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('bird-batches.store') }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Create New Bird Batch</h3>
                        <div class="mb-4">
                            <label for="branch_id" class="block text-gray-700 text-sm font-bold mb-2">Branch</label>
                            <select name="branch_id" id="branch_id" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">Select Branch</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
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
                            <label for="purchase_method" class="block text-gray-700 text-sm font-bold mb-2">Purchase Method</label>
                            <select name="purchase_method" id="purchase_method" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">Select Method</option>
                                <option value="egg">Egg</option>
                                <option value="chick">Chick</option>
                                <option value="adult">Adult</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="purchased_quantity" class="block text-gray-700 text-sm font-bold mb-2">Purchased Quantity</label>
                            <input type="number" name="purchased_quantity" id="purchased_quantity" min="1" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="unknown_gender" class="block text-gray-700 text-sm font-bold mb-2">Unknown Gender Count</label>
                            <input type="number" name="unknown_gender" id="unknown_gender" min="0" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="hen_count" class="block text-gray-700 text-sm font-bold mb-2">Hen Count</label>
                            <input type="number" name="hen_count" id="hen_count" min="0" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="cock_count" class="block text-gray-700 text-sm font-bold mb-2">Cock Count</label>
                            <input type="number" name="cock_count" id="cock_count" min="0" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="egg_laid_date" class="block text-gray-700 text-sm font-bold mb-2">Egg Laid Date</label>
                            <input type="date" name="egg_laid_date" id="egg_laid_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="hatch_date" class="block text-gray-700 text-sm font-bold mb-2">Hatch Date</label>
                            <input type="date" name="hatch_date" id="hatch_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="actual_hatched" class="block text-gray-700 text-sm font-bold mb-2">Actual Hatched</label>
                            <input type="number" name="actual_hatched" id="actual_hatched" min="0" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="purchase_price" class="block text-gray-700 text-sm font-bold mb-2">Purchase Price</label>
                            <input type="number" step="0.01" name="purchase_price" id="purchase_price" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="acquisition_date" class="block text-gray-700 text-sm font-bold mb-2">Acquisition Date</label>
                            <input type="date" name="acquisition_date" id="acquisition_date" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                            <select name="status" id="status" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="pending">Pending</option>
                                <option value="hatched">Hatched</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
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

    <!-- Edit Batch Modal -->
    <div x-show="showEditModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form :action="'/bird-batches/' + editBatchId" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Bird Batch</h3>
                        <div class="mb-4">
                            <label for="edit_branch_id" class="block text-gray-700 text-sm font-bold mb-2">Branch</label>
                            <select name="branch_id" id="edit_branch_id" x-model="editBatchData.branch_id" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">Select Branch</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="edit_supplier_id" class="block text-gray-700 text-sm font-bold mb-2">Supplier</label>
                            <select name="supplier_id" id="edit_supplier_id" x-model="editBatchData.supplier_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">Select Supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="edit_purchase_method" class="block text-gray-700 text-sm font-bold mb-2">Purchase Method</label>
                            <select name="purchase_method" id="edit_purchase_method" x-model="editBatchData.purchase_method" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">Select Method</option>
                                <option value="egg">Egg</option>
                                <option value="chick">Chick</option>
                                <option value="adult">Adult</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="edit_purchased_quantity" class="block text-gray-700 text-sm font-bold mb-2">Purchased Quantity</label>
                            <input type="number" name="purchased_quantity" id="edit_purchased_quantity" x-model="editBatchData.purchased_quantity" min="1" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="edit_unknown_gender" class="block text-gray-700 text-sm font-bold mb-2">Unknown Gender Count</label>
                            <input type="number" name="unknown_gender" id="edit_unknown_gender" x-model="editBatchData.unknown_gender" min="0" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="edit_hen_count" class="block text-gray-700 text-sm font-bold mb-2">Hen Count</label>
                            <input type="number" name="hen_count" id="edit_hen_count" x-model="editBatchData.hen_count" min="0" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="edit_cock_count" class="block text-gray-700 text-sm font-bold mb-2">Cock Count</label>
                            <input type="number" name="cock_count" id="edit_cock_count" x-model="editBatchData.cock_count" min="0" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="edit_egg_laid_date" class="block text-gray-700 text-sm font-bold mb-2">Egg Laid Date</label>
                            <input type="date" name="egg_laid_date" id="edit_egg_laid_date" x-model="editBatchData.egg_laid_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="edit_hatch_date" class="block text-gray-700 text-sm font-bold mb-2">Hatch Date</label>
                            <input type="date" name="hatch_date" id="edit_hatch_date" x-model="editBatchData.hatch_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="edit_actual_hatched" class="block text-gray-700 text-sm font-bold mb-2">Actual Hatched</label>
                            <input type="number" name="actual_hatched" id="edit_actual_hatched" x-model="editBatchData.actual_hatched" min="0" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="edit_purchase_price" class="block text-gray-700 text-sm font-bold mb-2">Purchase Price</label>
                            <input type="number" step="0.01" name="purchase_price" id="edit_purchase_price" x-model="editBatchData.purchase_price" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="edit_acquisition_date" class="block text-gray-700 text-sm font-bold mb-2">Acquisition Date</label>
                            <input type="date" name="acquisition_date" id="edit_acquisition_date" x-model="editBatchData.acquisition_date" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="edit_status" class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                            <select name="status" id="edit_status" x-model="editBatchData.status" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="pending">Pending</option>
                                <option value="hatched">Hatched</option>
                                <option value="completed">Completed</option>
                            </select>
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

    <!-- Show Batch Modal -->
    <div x-show="showShowModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Batch Details</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Branch</label>
                            <p class="text-gray-900" x-text="showBatchData.branch ? showBatchData.branch.name : 'N/A'"></p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Supplier</label>
                            <p class="text-gray-900" x-text="showBatchData.supplier ? showBatchData.supplier.name : 'N/A'"></p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Purchase Method</label>
                            <p class="text-gray-900" x-text="showBatchData.purchase_method ? (showBatchData.purchase_method.charAt(0).toUpperCase() + showBatchData.purchase_method.slice(1)) : 'N/A'"></p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Purchased Quantity</label>
                            <p class="text-gray-900" x-text="showBatchData.purchased_quantity"></p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Bird Counts</label>
                            <p class="text-gray-900">
                                <span x-text="'Unknown: ' + (showBatchData.unknown_gender || 0)"></span><br>
                                <span x-text="'Hens: ' + (showBatchData.hen_count || 0)"></span><br>
                                <span x-text="'Cocks: ' + (showBatchData.cock_count || 0)"></span>
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Dates</label>
                            <p class="text-gray-900">
                                <span x-text="'Egg Laid: ' + (showBatchData.egg_laid_date || 'N/A')"></span><br>
                                <span x-text="'Hatch: ' + (showBatchData.hatch_date || 'N/A')"></span><br>
                                <span x-text="'Acquisition: ' + (showBatchData.acquisition_date || 'N/A')"></span>
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Status</label>
                            <p class="text-gray-900" x-text="showBatchData.status ? (showBatchData.status.charAt(0).toUpperCase() + showBatchData.status.slice(1)) : 'N/A'"></p>
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
function birdBatchManagement() {
    return {
        showCreateModal: false,
        showEditModal: false,
        showShowModal: false,
        editBatchId: null,
        editBatchData: {
            branch_id: '',
            supplier_id: '',
            purchase_method: '',
            purchased_quantity: 0,
            unknown_gender: 0,
            hen_count: 0,
            cock_count: 0,
            egg_laid_date: '',
            hatch_date: '',
            actual_hatched: 0,
            purchase_price: 0,
            acquisition_date: '',
            status: ''
        },
        showBatchData: {
            branch: null,
            supplier: null,
            purchase_method: '',
            purchased_quantity: 0,
            unknown_gender: 0,
            hen_count: 0,
            cock_count: 0,
            egg_laid_date: '',
            hatch_date: '',
            actual_hatched: 0,
            purchase_price: 0,
            acquisition_date: '',
            status: ''
        },
        openCreateModal() {
            this.showCreateModal = true;
        },
        async openEditModal(batchId) {
            this.editBatchId = batchId;
            try {
                const response = await fetch(`/bird-batches/${batchId}/edit`);
                const data = await response.json();
                this.editBatchData = data;
                this.showEditModal = true;
            } catch (error) {
                console.error('Error fetching batch data:', error);
            }
        },
        async openShowModal(batchId) {
            try {
                const response = await fetch(`/bird-batches/${batchId}`);
                const data = await response.json();
                this.showBatchData = data;
                this.showShowModal = true;
            } catch (error) {
                console.error('Error fetching batch data:', error);
            }
        }
    }
}
</script>
@endpush
@endsection 
