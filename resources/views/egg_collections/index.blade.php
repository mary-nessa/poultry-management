@extends('layouts.app')

@section('title', 'Egg Collections')

@section('content')
<div class="container mx-auto px-4 py-6" x-data="eggCollectionManagement()">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Egg Collections</h1>
        <button @click="openCreateModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">    
            New Collection
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Collection Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Branch</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Good Eggs</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Full Trays</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Half Trays</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Singles</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($eggCollections as $collection)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $collection->collection_date ? $collection->collection_date->format('M d, Y H:i') : $collection->created_at->format('M d, Y H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $collection->branch->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $collection->good_eggs }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $collection->full_trays }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $collection->{'1_2_trays'} }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $collection->single_eggs }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button @click="openShowModal('{{ $collection->id }}')" class="text-blue-600 hover:text-blue-900 mr-3">View</button>
                            <button @click="openEditModal('{{ $collection->id }}')" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                            <form action="{{ route('egg-collections.destroy', $collection) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
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

    <!-- Create Modal -->
    <div x-show="showCreateModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('egg-collections.store') }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">New Egg Collection</h3>
                        
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
                            <label for="collection_date" class="block text-gray-700 text-sm font-bold mb-2">Collection Date</label>
                            <input type="datetime-local" name="collection_date" id="collection_date" required 
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>

                        <div class="mb-4">
                            <label for="good_eggs" class="block text-gray-700 text-sm font-bold mb-2">Good Eggs</label>
                            <input type="number" name="good_eggs" id="good_eggs" required min="0" 
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   @input="calculateTotalEggs()">
                        </div>

                        <div class="mb-4">
                            <label for="damaged_eggs" class="block text-gray-700 text-sm font-bold mb-2">Damaged Eggs</label>
                            <input type="number" name="damaged_eggs" id="damaged_eggs" required min="0" 
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   @input="calculateTotalEggs()">
                        </div>

                        <div class="mb-4">
                            <label for="collected_by" class="block text-gray-700 text-sm font-bold mb-2">Collected By</label>
                            <select name="collected_by" id="collected_by" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">Select Collector</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="bg-gray-50 mt-4 p-4 rounded-md">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Collection Summary</h4>
                            <p class="text-sm text-gray-600">Total Eggs: <span x-text="totalEggs">0</span></p>
                            <p class="text-sm text-gray-600">Full Trays: <span x-text="fullTrays">0</span></p>
                            <p class="text-sm text-gray-600">Half Trays: <span x-text="halfTrays">0</span></p>
                            <p class="text-sm text-gray-600">Single Eggs: <span x-text="singleEggs">0</span></p>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Save
                        </button>
                        <button type="button" @click="showCreateModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div x-show="showEditModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form :action="'/egg-collections/' + editCollectionId" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Egg Collection</h3>
                        
                        <div class="mb-4">
                            <label for="edit_branch_id" class="block text-gray-700 text-sm font-bold mb-2">Branch</label>
                            <select name="branch_id" id="edit_branch_id" x-model="editCollectionData.branch_id" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">Select Branch</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="edit_collection_date" class="block text-gray-700 text-sm font-bold mb-2">Collection Date</label>
                            <input type="datetime-local" name="collection_date" id="edit_collection_date" x-model="editCollectionData.collection_date" required 
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>

                        <div class="mb-4">
                            <label for="edit_good_eggs" class="block text-gray-700 text-sm font-bold mb-2">Good Eggs</label>
                            <input type="number" name="good_eggs" id="edit_good_eggs" x-model="editCollectionData.good_eggs" required min="0" 
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   @input="calculateEditTotalEggs()">
                        </div>

                        <div class="mb-4">
                            <label for="edit_damaged_eggs" class="block text-gray-700 text-sm font-bold mb-2">Damaged Eggs</label>
                            <input type="number" name="damaged_eggs" id="edit_damaged_eggs" x-model="editCollectionData.damaged_eggs" required min="0" 
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   @input="calculateEditTotalEggs()">
                        </div>

                        <div class="mb-4">
                            <label for="edit_collected_by" class="block text-gray-700 text-sm font-bold mb-2">Collected By</label>
                            <select name="collected_by" id="edit_collected_by" x-model="editCollectionData.collected_by" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">Select Collector</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="bg-gray-50 mt-4 p-4 rounded-md">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Collection Summary</h4>
                            <p class="text-sm text-gray-600">Total Eggs: <span x-text="editTotalEggs">0</span></p>
                            <p class="text-sm text-gray-600">Full Trays: <span x-text="editFullTrays">0</span></p>
                            <p class="text-sm text-gray-600">Half Trays: <span x-text="editHalfTrays">0</span></p>
                            <p class="text-sm text-gray-600">Single Eggs: <span x-text="editSingleEggs">0</span></p>
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

    <!-- Show Modal -->
    <div x-show="showShowModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Egg Collection Details</h3>
                        <button @click="showShowModal = false" class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Collection Information -->
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Collection Information</h4>
                        <div class="bg-gray-50 rounded-md p-4">
                            <p class="text-sm text-gray-600">Branch: <span class="text-gray-900" x-text="showCollectionData.branch?.name"></span></p>
                            <p class="text-sm text-gray-600 mt-1">Collection Date: <span class="text-gray-900" x-text="formatDate(showCollectionData.collection_date)"></span></p>
                            <p class="text-sm text-gray-600 mt-1">Collected By: <span class="text-gray-900" x-text="showCollectionData.collected_by?.name"></span></p>
                        </div>
                    </div>

                    <!-- Eggs Summary -->
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Eggs Summary</h4>
                        <div class="grid grid-cols-2 gap-4 bg-gray-50 rounded-md p-4">
                            <div>
                                <p class="text-sm text-gray-600">Total Eggs: <span class="text-gray-900" x-text="showCollectionData.total_eggs"></span></p>
                                <p class="text-sm text-gray-600 mt-1">Good Eggs: <span class="text-gray-900" x-text="showCollectionData.good_eggs"></span></p>
                                <p class="text-sm text-gray-600 mt-1">Damaged Eggs: <span class="text-gray-900" x-text="showCollectionData.damaged_eggs"></span></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Full Trays: <span class="text-gray-900" x-text="showCollectionData.full_trays"></span></p>
                                <p class="text-sm text-gray-600 mt-1">Half Trays: <span class="text-gray-900" x-text="showCollectionData['1_2_trays']"></span></p>
                                <p class="text-sm text-gray-600 mt-1">Single Eggs: <span class="text-gray-900" x-text="showCollectionData.single_eggs"></span></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" @click="showShowModal = false" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function eggCollectionManagement() {
        return {
            showCreateModal: false,
            showEditModal: false,
            showShowModal: false,
            editCollectionId: null,
            editCollectionData: {
                branch_id: '',
                collection_date: '',
                good_eggs: 0,
                damaged_eggs: 0,
                collected_by: '',
            },
            showCollectionData: {
                branch: null,
                collected_by: null,
                total_eggs: 0,
                good_eggs: 0,
                damaged_eggs: 0,
                full_trays: 0,
                '1_2_trays': 0,
                single_eggs: 0,
                collection_date: null
            },
            totalEggs: 0,
            fullTrays: 0,
            halfTrays: 0,
            singleEggs: 0,
            editTotalEggs: 0,
            editFullTrays: 0,
            editHalfTrays: 0,
            editSingleEggs: 0,

            calculateTotalEggs() {
                const goodEggs = parseInt(document.getElementById('good_eggs').value) || 0;
                const damagedEggs = parseInt(document.getElementById('damaged_eggs').value) || 0;
                this.totalEggs = goodEggs + damagedEggs;
                this.calculateTrays(goodEggs);
            },

            calculateEditTotalEggs() {
                const goodEggs = parseInt(this.editCollectionData.good_eggs) || 0;
                const damagedEggs = parseInt(this.editCollectionData.damaged_eggs) || 0;
                this.editTotalEggs = goodEggs + damagedEggs;
                this.calculateEditTrays(goodEggs);
            },

            calculateTrays(goodEggs) {
                this.fullTrays = Math.floor(goodEggs / 30);
                const remaining = goodEggs % 30;
                this.halfTrays = Math.floor(remaining / 15);
                this.singleEggs = remaining % 15;
            },

            calculateEditTrays(goodEggs) {
                this.editFullTrays = Math.floor(goodEggs / 30);
                const remaining = goodEggs % 30;
                this.editHalfTrays = Math.floor(remaining / 15);
                this.editSingleEggs = remaining % 15;
            },

            openCreateModal() {
                this.showCreateModal = true;
                // Set default collection date to current date and time
                document.getElementById('collection_date').value = new Date().toISOString().slice(0, 16);
            },

            async openEditModal(collectionId) {
                this.editCollectionId = collectionId;
                try {
                    const response = await fetch(`/egg-collections/${collectionId}/edit`);
                    const data = await response.json();
                    this.editCollectionData = {
                        ...data,
                        collection_date: data.collection_date ? new Date(data.collection_date).toISOString().slice(0, 16) : new Date().toISOString().slice(0, 16)
                    };
                    this.calculateEditTotalEggs();
                    this.showEditModal = true;
                } catch (error) {
                    console.error('Error fetching collection data:', error);
                }
            },

            async openShowModal(collectionId) {
                try {
                    const response = await fetch(`/egg-collections/${collectionId}`);
                    const data = await response.json();
                    this.showCollectionData = data;
                    this.showShowModal = true;
                } catch (error) {
                    console.error('Error fetching collection data:', error);
                }
            },

            formatDate(dateString) {
                if (!dateString) return '';
                const date = new Date(dateString);
                return date.toLocaleDateString('en-US', { 
                    year: 'numeric', 
                    month: 'short', 
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }
        }
    }
</script>
@endpush

@endsection