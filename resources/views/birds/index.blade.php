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

        <!-- Birds Table -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Batch Id</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Birds</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hens</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cocks</th>
{{--                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Branch</th>--}}
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @foreach($birds as $bird)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $bird->chickPurchase ? $bird->chickPurchase->batch_id : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $bird->total_birds }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $bird->hen_count }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $bird->cock_count }}</td>
{{--                        <td class="px-6 py-4 whitespace-nowrap">{{ $bird->branch->name }}</td>--}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button @click="openShowModal('{{ $bird->id }}')" class="text-blue-600 hover:text-blue-900 mr-3">View</button>
                            <button @click="openEditModal('{{ $bird->id }}')" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                            <form action="{{ route('birds.destroy', $bird) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this bird record?')">
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
                            <!-- Select a Chick Purchase -->
                            <div class="mb-4">
                                <label for="chick_purchase_id" class="block text-gray-700 text-sm font-bold mb-2">Bird Group</label>
                                <select name="chick_purchase_id" id="chick_purchase_id" required
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                        @change="updateBranch($event)">
                                    <option value="">Select Group</option>
                                    @foreach($chickPurchases as $purchase)
                                        <option value="{{ $purchase->id }}">
                                            {{ $purchase->breed ?? ('Purchase ' . $purchase->id) }} - {{ $purchase->date }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Hen Count -->
                            <div class="mb-4">
                                <label for="hen_count" class="block text-gray-700 text-sm font-bold mb-2">Number of Hens</label>
                                <input type="number" name="hen_count" id="hen_count" min="0" required
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
                            <!-- Cock Count -->
                            <div class="mb-4">
                                <label for="cock_count" class="block text-gray-700 text-sm font-bold mb-2">Number of Cocks</label>
                                <input type="number" name="cock_count" id="cock_count" min="0" required
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>


                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
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

        <!-- (Edit and Show modals remain unchanged.) -->
    </div>

    @push('scripts')
        <script>
            function birdManagement() {
                return {
                    showCreateModal: false,
                    showEditModal: false,
                    showShowModal: false,
                    editBirdId: null,
                    editBirdData: {
                        chick_purchase_id: '',
                        total_birds: 0,
                        hen_count: 0,
                        cock_count: 0,
                        branch_id: '',
                        laying_cycle_start_date: '',
                        laying_cycle_end_date: ''
                    },
                    showBirdData: {
                        chickPurchase: null,
                        total_birds: 0,
                        hen_count: 0,
                        cock_count: 0,
                        branch: null,
                        laying_cycle_start_date: '',
                        laying_cycle_end_date: ''
                    },

                    openCreateModal() {
                        this.showCreateModal = true;
                    },
                    async openEditModal(birdId) {
                        this.editBirdId = birdId;
                        try {
                            const response = await fetch(`/birds/${birdId}/edit`);
                            const data = await response.json();
                            this.editBirdData = data;
                            this.showEditModal = true;
                        } catch (error) {
                            console.error('Error fetching bird data:', error);
                        }
                    },
                    async openShowModal(birdId) {
                        try {
                            const response = await fetch(`/birds/${birdId}`);
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
