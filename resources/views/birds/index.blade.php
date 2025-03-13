@extends('layouts.app')

@section('title', 'Birds Groups')

@section('content')
    <div class="container mx-auto px-4 py-6" x-data="birdManagement()">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Birds</h1>
            <button @click="openCreateModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Group Bird
            </button>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif
        <!-- Filters with searchable dropdown -->
<div class="bg-white shadow-md rounded-lg overflow-hidden mb-6 p-4">
    <form action="{{ route('birds.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
        <div x-data="{ searchTerm: '' }">
            <label for="batch_id" class="block text-sm font-medium text-gray-700 mb-1">Filter by Batch ID</label>
            <div class="relative">
                <!-- Search field above dropdown -->
                <input 
                    type="text" 
                    x-model="searchTerm" 
                    placeholder="Search batches..." 
                    class="mb-2 w-full shadow border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                >
                
                <!-- Standard dropdown with filtered options -->
                <select 
                    name="batch_id" 
                    id="batch_id" 
                    class="w-full shadow border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                >
                    <option value="">All Batches</option>
                    <template x-for="purchase in {{ json_encode($chickPurchases) }}.filter(item => 
                        item.batch_id.toLowerCase().includes(searchTerm.toLowerCase()))" 
                        :key="purchase.batch_id"
                    >
                        <option 
                            :value="purchase.batch_id"
                            :selected="purchase.batch_id == '{{ request('batch_id') }}'"
                            x-text="purchase.batch_id"
                        ></option>
                    </template>
                </select>
            </div>
        </div>
        <div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Filter
            </button>
            @if(request()->has('batch_id') && request('batch_id') !== '')
                <a href="{{ route('birds.index') }}" class="ml-2 text-blue-500 hover:text-blue-700">
                    Clear
                </a>
            @endif
        </div>
    </form>
</div>

        <!-- Birds Table -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Batch Id</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Birds</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hens</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cocks</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($birds as $index => $bird)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $birds->firstItem() + $index }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $bird->chickPurchase ? $bird->chickPurchase->batch_id : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $bird->total_birds }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $bird->hen_count }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $bird->cock_count }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('birds.show', $bird->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                    <a href="{{ route('birds.edit', $bird->id) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                    <form action="{{ route('birds.destroy', $bird) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this bird record?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 bg-white border-t border-gray-200">
                {{ $birds->links() }}
            </div>
        </div>

        <!-- Create Bird Modal -->
        <div x-show="showCreateModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form action="{{ route('birds.store') }}" method="POST">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-4"> Group Bird </h3>
                            {{-- <!-- Select a Chick Purchase -->
                            <div class="mb-4">
                                <label for="chick_purchase_id" class="block text-gray-700 text-sm font-bold mb-2">Bird Group</label>
                                <select name="chick_purchase_id" id="chick_purchase_id" required
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                        @change="updateAvailableBirds($event.target.value)">
                                    <option value="">Select Group</option>
                                    @foreach($chickPurchases as $purchase)
                                        <option value="{{ $purchase->id }}">
                                            {{ $purchase->breed ?? ('Purchase ' . $purchase->id) }} - Batch: {{ $purchase->batch_id }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                             --}}

                            <!-- Select Chick Purchase -->
                            <div class="mb-4">
                                <label for="chick_purchase_id" class="block text-gray-700 text-sm font-bold mb-2">Bird</label>
                                <select name="chick_purchase_id" id="chick_purchase_id" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" @change="updateAvailableBirds($event.target.value)">
                                    <option value="">Select Batch</option>
                                    @foreach($chickPurchases as $chickPurchase)
                                        <option value="{{ $chickPurchase->id }}">{{ $chickPurchase->batch_id }} </option>
                                    @endforeach
                                </select>
                            </div>

                            <div x-show="availableBirds !== null" class="mb-4 p-3 bg-blue-50 rounded">
                                <p class="text-sm text-blue-800">
                                    <span class="font-bold">Available birds:</span> <span x-text="availableBirds"></span>
                                </p>
                            </div>
                            
                            <!-- Hen Count -->
                            <div class="mb-4">
                                <label for="hen_count" class="block text-gray-700 text-sm font-bold mb-2">Number of Hens</label>
                                <input type="number" name="hen_count" id="hen_count" min="0" required
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                       @input="validateBirdCount">
                            </div>
                            <!-- Cock Count -->
                            <div class="mb-4">
                                <label for="cock_count" class="block text-gray-700 text-sm font-bold mb-2">Number of Cocks</label>
                                <input type="number" name="cock_count" id="cock_count" min="0" required
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                       @input="validateBirdCount">
                            </div>
                            
                            <div x-show="validationError" class="mt-2 text-sm text-red-600" x-text="validationError"></div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" x-bind:disabled="validationError !== null"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                                Create
                            </button>
                            <button type="button" @click="showCreateModal = false"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @push('scripts')
        <script>
            function birdManagement() {
                return {
                    showCreateModal: false,
                    showEditModal: false,
                    showShowModal: false,
                    editBirdId: null,
                    availableBirds: null,
                    validationError: null,
                    editBirdData: {
                        id: null,
                        chick_purchase_id: '',
                        total_birds: 0,
                        hen_count: 0,
                        cock_count: 0,
                        branch_id: '',
                        laying_cycle_start_date: '',
                        laying_cycle_end_date: ''
                    },
                    showBirdData: {
                        id: null,
                        chickPurchase: null,
                        total_birds: 0,
                        hen_count: 0,
                        cock_count: 0,
                        branch: null,
                        laying_cycle_start_date: '',
                        laying_cycle_end_date: ''
                    },
                    editValidationError: null,
                    editAvailableBirds: null,
                    originalEditBird: {
                        chick_purchase_id: '',
                        total_birds: 0
                    },

                    openCreateModal() {
                        this.showCreateModal = true;
                        this.availableBirds = null;
                        this.validationError = null;
                    },
                    
                    async updateAvailableBirds(purchaseId) {
                        if (!purchaseId) {
                            this.availableBirds = null;
                            return;
                        }
                        
                        try {
                            const response = await fetch(`/birds/available/${purchaseId}`);
                            const data = await response.json();
                            this.availableBirds = data.available;
                            this.validateBirdCount();
                        } catch (error) {
                            console.error('Error fetching available birds:', error);
                        }
                    },
                    
                    validateBirdCount() {
                        const henCount = parseInt(document.getElementById('hen_count').value) || 0;
                        const cockCount = parseInt(document.getElementById('cock_count').value) || 0;
                        const total = henCount + cockCount;
                        
                        this.validationError = null;
                        
                        if (this.availableBirds !== null && total > this.availableBirds) {
                            this.validationError = `Total birds (${total}) exceeds available birds (${this.availableBirds})`;
                        }
                    },
                    
                    async openEditModal(birdId) {
                        this.editBirdId = birdId;
                        try {
                            const response = await fetch(`/birds/${birdId}/edit-data`);
                            const data = await response.json();
                            this.editBirdData = data;
                            this.originalEditBird = {
                                chick_purchase_id: data.chick_purchase_id,
                                total_birds: data.total_birds
                            };
                            this.showEditModal = true;
                            this.updateEditAvailableBirds(data.chick_purchase_id);
                        } catch (error) {
                            console.error('Error fetching bird data:', error);
                        }
                    },
                    
                    async updateEditAvailableBirds(purchaseId) {
                        if (!purchaseId) {
                            this.editAvailableBirds = null;
                            return;
                        }
                        
                        try {
                            const response = await fetch(`/birds/available/${purchaseId}`);
                            const data = await response.json();
                            
                            // If same purchase as original, add back the original count
                            if (purchaseId == this.originalEditBird.chick_purchase_id) {
                                this.editAvailableBirds = data.available + this.originalEditBird.total_birds;
                            } else {
                                this.editAvailableBirds = data.available;
                            }
                            
                            this.validateEditBirdCount();
                        } catch (error) {
                            console.error('Error fetching available birds:', error);
                        }
                    },
                    
                    validateEditBirdCount() {
                        const henCount = parseInt(document.getElementById('edit_hen_count').value) || 0;
                        const cockCount = parseInt(document.getElementById('edit_cock_count').value) || 0;
                        const total = henCount + cockCount;
                        
                        this.editValidationError = null;
                        
                        if (this.editAvailableBirds !== null && total > this.editAvailableBirds) {
                            this.editValidationError = `Total birds (${total}) exceeds available birds (${this.editAvailableBirds})`;
                        }
                    },
                    
                    async openShowModal(birdId) {
                        try {
                            const response = await fetch(`/birds/${birdId}/data`);
                            const data = await response.json();
                            this.showBirdData = data;
                            this.showShowModal = true;
                        } catch (error) {
                            console.error('Error fetching bird data:', error);
                        }
                    },

                }
            }
        </script>
    @endpush
@endsection