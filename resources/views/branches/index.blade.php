@extends('layouts.app')

@section('title', 'Branches')

@section('content')
<div class="container mx-auto px-4 py-6" x-data="branchManagement()">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Branches</h1>
        <button @click="openCreateModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Add New Branch
        </button>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Filters Form -->
    <div class="mb-6 bg-white shadow-md rounded-lg p-4">
        <form @submit.prevent="applyFilters" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="filter_name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                <input 
                    type="text" 
                    id="filter_name" 
                    x-model="filters.name" 
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    placeholder="Filter by name..."
                >
            </div>
            <div>
                <label for="filter_location" class="block text-gray-700 text-sm font-bold mb-2">Location</label>
                <input 
                    type="text" 
                    id="filter_location" 
                    x-model="filters.location" 
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    placeholder="Filter by location..."
                >
            </div>
            <div class="flex items-end">
                <button 
                    type="submit" 
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2"
                >
                    Apply Filters
                </button>
                <button 
                    type="button" 
                    @click="clearFilters" 
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                >
                    Clear
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Manager</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" x-html="branchRows"></tbody>
            </table>
        </div>
        
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            <div class="flex justify-between items-center">
                <button 
                    @click="prevPage" 
                    :disabled="currentPage === 1" 
                    class="px-4 py-2 border rounded disabled:opacity-50"
                >
                    Previous
                </button>
                <span>
                    Page <span x-text="currentPage"></span> of <span x-text="totalPages"></span>
                </span>
                <button 
                    @click="nextPage" 
                    :disabled="currentPage === totalPages" 
                    class="px-4 py-2 border rounded disabled:opacity-50"
                >
                    Next
                </button>
            </div>
        </div>
    </div>

    <!-- Create Branch Modal -->
    <div x-show="showCreateModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('branches.store') }}" method="POST" @submit="handleCreateSubmit">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Create New Branch</h3>
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Branch Name</label>
                            <input type="text" name="name" id="name" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="location" class="block text-gray-700 text-sm font-bold mb-2">Location</label>
                            <input type="text" name="location" id="location" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
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

    <!-- Edit Branch Modal -->
    <div x-show="showEditModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form :action="'/branches/' + editBranchId" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Branch</h3>
                        <div class="mb-4">
                            <label for="edit_name" class="block text-gray-700 text-sm font-bold mb-2">Branch Name</label>
                            <input type="text" name="name" id="edit_name" x-model="editBranchData.name" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="edit_location" class="block text-gray-700 text-sm font-bold mb-2">Location</label>
                            <input type="text" name="location" id="edit_location" x-model="editBranchData.location" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="manager_id" class="block text-gray-700 text-sm font-bold mb-2">Manager</label>
                            <select name="manager_id" id="manager_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">Select Manager</option>
                                @foreach($managers as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
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

    <!-- Show Branch Modal -->
    <div x-show="showShowModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Branch Details</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Branch Name</label>
                            <p class="text-gray-900" x-text="showBranchData.name"></p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Location</label>
                            <p class="text-gray-900" x-text="showBranchData.location"></p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Manager</label>
                            <p class="text-gray-900" x-text="showBranchData.manager ? showBranchData.manager.name : 'No Manager'"></p>
                        </div>
                        <div>
                            <h4 class="text-lg font-medium text-gray-900 mb-2">Staff Members</h4>
                            <template x-if="showBranchData.users && showBranchData.users.length > 0">
                                <ul class="space-y-2">
                                    <template x-for="user in showBranchData.users" :key="user.id">
                                        <li class="text-gray-700" x-text="user.name"></li>
                                    </template>
                                </ul>
                            </template>
                            <template x-if="!showBranchData.users || showBranchData.users.length === 0">
                                <p class="text-gray-500">No staff members assigned.</p>
                            </template>
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

    @include('partials.modals.assign-branch')
</div>

@push('scripts')
<script>
function branchManagement() {
    return {
        showCreateModal: false,
        showEditModal: false,
        showShowModal: false,
        showAssignBranchModal: false,
        editBranchId: null,
        assignUserBranchId: null,
        assignUserBranchName: '',
        currentPage: 1,
        perPage: 10,
        totalPages: 1,
        branchRows: '',
        filters: {
            name: '',
            location: ''
        },
        editBranchData: {
            name: '',
            location: ''
        },
        showBranchData: {
            name: '',
            location: '',
            manager: null,
            users: []
        },
        openCreateModal() {
            this.showCreateModal = true;
        },
        async openEditModal(branchId) {
            this.editBranchId = branchId;
            try {
                const response = await fetch(`{{ route('branches.edit', ':branchId') }}`.replace(':branchId', branchId));
                const data = await response.json();
                this.editBranchData = {
                    name: data.name,
                    location: data.location
                };
                this.showEditModal = true;
            } catch (error) {
                console.error('Error fetching branch data:', error);
            }
        },
        async openShowModal(branchId) {
            try {
                const response = await fetch(`{{ route('branches.show', '') }}/${branchId}`);
                const data = await response.json();
                this.showBranchData = data;
                this.showShowModal = true;
            } catch (error) {
                console.error('Error fetching branch data:', error);
            }
        },
        openAssignBranchModal(branchId, branchName) {
            this.assignUserBranchId = branchId;
            this.assignUserBranchName = branchName;
            this.showAssignBranchModal = true;
        },
        async assignBranch() {
            event.preventDefault();
            const form = document.getElementById('branchesForm');
            const formData = new FormData(form);
            const params = new URLSearchParams();
            
            for (const pair of formData.entries()) {
                params.append(pair[0], pair[1]);
            }
            
            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: params.toString()
                });
                const data = await response.json();
                if (data.status === 'success') {
                    this.showAssignBranchModal = false;
                    this.fetchBranches();
                }
            } catch (error) {
                console.error('Error:', error);
            }
        },
        async fetchBranches() {
            try {
                const params = new URLSearchParams({
                    page: this.currentPage,
                    per_page: this.perPage,
                    name: this.filters.name,
                    location: this.filters.location
                });
                const response = await fetch(`/branches?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const data = await response.json();
                
                this.totalPages = data.pagination.last_page;
                this.currentPage = data.pagination.current_page;
                
                this.branchRows = data.branches.map(branch => `
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">${branch.name}</td>
                        <td class="px-6 py-4 whitespace-nowrap">${branch.location}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            ${branch.manager ? branch.manager.name : 'No Manager'}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex flex-wrap gap-2">
                                <button @click="openShowModal('${branch.id}')" class="text-blue-600 hover:text-blue-900">View</button>
                                <button @click="openEditModal('${branch.id}')" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                <form action="/branches/${branch.id}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this branch?')">
                                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                `).join('');
            } catch (error) {
                console.error('Error fetching branches:', error);
            }
        },
        applyFilters() {
            this.currentPage = 1;
            this.fetchBranches();
        },
        clearFilters() {
            this.filters.name = '';
            this.filters.location = '';
            this.currentPage = 1;
            this.fetchBranches();
        },
        goToPage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.currentPage = page;
                this.fetchBranches();
            }
        },
        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
                this.fetchBranches();
            }
        },
        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.fetchBranches();
            }
        },
        handleResize() {
            const width = window.innerWidth;
            if (width < 640) {
                this.perPage = 5;
            } else {
                this.perPage = 10;
            }
            this.fetchBranches();
        },
        async handleCreateSubmit(event) {
            event.preventDefault();
            const form = event.target;
            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const data = await response.json();
                if (data.status === 'success') {
                    this.showCreateModal = false;
                    this.fetchBranches();
                }
            } catch (error) {
                console.error('Error creating branch:', error);
            }
        },
        init() {
            window.addEventListener('resize', this.handleResize.bind(this));
            this.handleResize();
            this.fetchBranches();
        }
    };
}
</script>
@endpush
@endsection