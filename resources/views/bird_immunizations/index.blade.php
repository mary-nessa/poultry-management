@extends('layouts.app')

@section('title', 'Bird Immunizations')

@section('content')
    <div class="container mx-auto px-4 py-6" x-data="immunizationManagement()">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Bird Immunizations</h1>
            <button @click="openCreateModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                New Immunization
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

        <!-- Immunizations Table -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="overflow-x-auto"> <!-- Makes the table responsive -->
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bird Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vaccine</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Next Due Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($immunizations as $immunization)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $immunization->chickPurchase->batch_id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $immunization->vaccine->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($immunization->immunization_date)->format('Y-m-d') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($immunization->next_due_date)->format('Y-m-d') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button @click="openShowModal('{{ $immunization->id }}')" class="text-blue-600 hover:text-blue-900 mr-3">View</button>
                                <button @click="openEditModal('{{ $immunization->id }}')" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                <form action="{{ route('bird-immunizations.destroy', $immunization) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this immunization record?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                No immunization records found.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 bg-white border-t border-gray-200">
                {{ $immunizations->links() }}
            </div>
        </div>

        <!-- Create Immunization Modal -->
        <div x-show="showCreateModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form action="{{ route('bird-immunizations.store') }}" method="POST">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">New Bird Immunization</h3>

                            <!-- Select Chick Purchase -->
                            <div class="mb-4">
                                <label for="chick_purchase_id" class="block text-gray-700 text-sm font-bold mb-2">Bird</label>
                                <select name="chick_purchase_id" id="chick_purchase_id" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option value="">Select Batch</option>
                                    @foreach($chickPurchases as $chickPurchase)
                                        <option value="{{ $chickPurchase->id }}">{{ $chickPurchase->batch_id }} </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Select Vaccine -->
                            <div class="mb-4">
                                <label for="vaccine_id" class="block text-gray-700 text-sm font-bold mb-2">Vaccine</label>
                                <select name="vaccine_id" id="vaccine_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option value="">Select Vaccine</option>
                                    @foreach($vaccines as $vaccine)
                                        <option value="{{ $vaccine->id }}">{{ $vaccine->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="next_due_date" class="block text-gray-700 text-sm font-bold mb-2">Next Due Date</label>
                                <input type="date" name="next_due_date" id="next_due_date" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>

                            <div class="mb-4">
                                <label for="notes" class="block text-gray-700 text-sm font-bold mb-2">Notes</label>
                                <textarea name="notes" id="notes" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                            </div>

                            <div class="mb-4">
                                <label for="number_immunized" class="block text-gray-700 text-sm font-bold mb-2">Number Immunized</label>
                                <input type="number" name="number_immunized" id="number_immunized" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>

                            <div class="mb-4">
                                <label for="age_category" class="block text-gray-700 text-sm font-bold mb-2">Age Category</label>
                                <input type="text" name="age_category" id="age_category" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
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

        <!-- Edit Immunization Modal -->
        <div x-show="showEditModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form :action="'/bird-immunizations/' + editImmunizationId" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Bird Immunization</h3>

                            <!-- Select Chick Purchase -->
                            <div class="mb-4">
                                <label for="edit_chick_purchase_id" class="block text-gray-700 text-sm font-bold mb-2">Bird Batch</label>
                                <select name="chick_purchase_id" id="edit_chick_purchase_id" x-model="editImmunizationData.chick_purchase_id" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option value="">Select Bird</option>
                                    @foreach($chickPurchases as $chickPurchase)
                                        <option value="{{ $chickPurchase->id }}">{{ $chickPurchase->batch_id }} </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Select Vaccine -->
                            <div class="mb-4">
                                <label for="edit_vaccine_id" class="block text-gray-700 text-sm font-bold mb-2">Vaccine</label>
                                <select name="vaccine_id" id="edit_vaccine_id" x-model="editImmunizationData.vaccine_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option value="">Select Vaccine</option>
                                    @foreach($vaccines as $vaccine)
                                        <option value="{{ $vaccine->id }}">{{ $vaccine->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="edit_immunization_date" class="block text-gray-700 text-sm font-bold mb-2">Immunization Date</label>
                                <input type="date" name="immunization_date" id="edit_immunization_date" x-model="editImmunizationData.immunization_date" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>

                            <div class="mb-4">
                                <label for="edit_next_due_date" class="block text-gray-700 text-sm font-bold mb-2">Next Due Date</label>
                                <input type="date" name="next_due_date" id="edit_next_due_date" x-model="editImmunizationData.next_due_date" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>

                            <div class="mb-4">
                                <label for="edit_notes" class="block text-gray-700 text-sm font-bold mb-2">Notes</label>
                                <textarea name="notes" id="edit_notes" x-model="editImmunizationData.notes" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                            </div>

                            <div class="mb-4">
                                <label for="edit_number_immunized" class="block text-gray-700 text-sm font-bold mb-2">Number Immunized</label>
                                <input type="number" name="number_immunized" id="edit_number_immunized" x-model="editImmunizationData.number_immunized" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>

                            <div class="mb-4">
                                <label for="edit_age_category" class="block text-gray-700 text-sm font-bold mb-2">Age Category</label>
                                <input type="text" name="age_category" id="edit_age_category" x-model="editImmunizationData.age_category" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
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

        <!-- Show Immunization Modal -->
        <div x-show="showShowModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Immunization Details</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Bird Batch</label>
                                <p class="text-gray-900" x-text="showImmunizationData.chick_purchase.batch_id ?? 'N/A'"></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Branch</label>
                                <p class="text-gray-900" x-text="showImmunizationData.chick_purchase.branch.name ?? 'N/A'"></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Vaccine</label>
                                <p class="text-gray-900" x-text="showImmunizationData.vaccine ? showImmunizationData.vaccine.name : 'N/A'"></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Immunization Date</label>
                                <p class="text-gray-900" x-text="showImmunizationData.immunization_date"></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Next Due Date</label>
                                <p class="text-gray-900" x-text="showImmunizationData.next_due_date"></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Notes</label>
                                <p class="text-gray-900" x-text="showImmunizationData.notes || 'No notes'"></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Number Immunized</label>
                                <p class="text-gray-900" x-text="showImmunizationData.number_immunized"></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Age Category</label>
                                <p class="text-gray-900" x-text="showImmunizationData.age_category"></p>
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
            function immunizationManagement() {
                return {
                    showCreateModal: false,
                    showEditModal: false,
                    showShowModal: false,
                    editImmunizationId: null,
                    editImmunizationData: {
                        chick_purchase_id: '',
                        vaccine_id: '',
                        immunization_date: '',
                        next_due_date: '',
                        notes: '',
                        number_immunized: '',
                        age_category: ''
                    },
                    showImmunizationData: {
                        chick_purchase: null, // Changed from chickPurchase to match JSON response
                        vaccine: null,
                        immunization_date: '',
                        next_due_date: '',
                        notes: '',
                        number_immunized: '',
                        age_category: ''
                    },
                    openCreateModal() {
                        this.showCreateModal = true;
                    },
                    async openEditModal(immunizationId) {
                        this.editImmunizationId = immunizationId;
                        try {
                            const response = await fetch(`{{ route('bird-immunizations.edit', ':immunizationId') }}`.replace(':immunizationId', immunizationId));
                            this.editImmunizationData = await response.json();
                            this.showEditModal = true;
                        } catch (error) {
                            console.error('Error fetching immunization data:', error);
                        }
                    },
                    async openShowModal(immunizationId) {
                        try {
                            const response = await fetch(`{{ route('bird-immunizations.show', '') }}/${immunizationId}`);
                            const data = await response.json();
                            this.showImmunizationData = data;
                            this.showShowModal = true;
                        } catch (error) {
                            console.error('Error fetching immunization data:', error);
                        }
                    }
                }
            }
        </script>
    @endpush
@endsection