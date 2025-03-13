@extends('layouts.app')

@section('title', 'Users')

@section('content')
<div class="container mx-auto px-4 py-6" x-data="userManagement()">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-4 md:mb-0">Users</h1>
        <button @click="openCreateModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Add New User
        </button>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    
    <!-- Filter Section -->
    <div class="bg-white shadow-md rounded-lg p-4 mb-6">
        <h2 class="text-lg font-semibold mb-3">Filters</h2>
        <form action="{{ route('users.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input type="text" name="name" id="name" value="{{ $nameFilter ?? '' }}" class="shadow-sm border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="text" name="email" id="email" value="{{ $emailFilter ?? '' }}" class="shadow-sm border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            
            <!-- Role dropdown in the filter section -->
<div x-data="{ 
    open: false, 
    search: '', 
    selected: '{{ $roleFilter ?? '' }}', 
    roles: Alpine.store('formData').roles 
}" x-init="roles = Alpine.store('formData').roles">
   <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
   <div class="relative">
       <button @click="open = !open" 
               type="button"
               class="shadow-sm border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline flex justify-between items-center">
           <span x-text="selected ? selected : 'All Roles'"></span>
           <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
               <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
           </svg>
       </button>
       
       <div x-show="open" 
            @click.away="open = false"
            class="absolute z-10 w-full mt-1 bg-white shadow-lg rounded-md py-1" 
            style="display: none;">
           <div class="px-3 py-2">
               <input type="text" 
                      x-model="search"
                      placeholder="Search roles..."
                      class="shadow-sm border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
           </div>
           <ul class="max-h-60 overflow-auto">
               <li @click="selected = ''; open = false;" 
                   class="px-3 py-2 hover:bg-gray-100 cursor-pointer">
                   All Roles
               </li>
               
               <template x-for="role in roles.filter(r => r.toLowerCase().includes(search.toLowerCase()))" :key="role">
                   <li @click="selected = role; open = false;" 
                       :class="{'bg-blue-100': selected === role}"
                       class="px-3 py-2 hover:bg-gray-100 cursor-pointer" 
                       x-text="role"></li>
               </template>
           </ul>
       </div>
       <input type="hidden" name="role" x-bind:value="selected" />
   </div>
</div>

<!-- Branch dropdown in the filter section -->
<div x-data="{ 
    open: false, 
    search: '', 
    selected: '', 
    selectedId: '{{ $branchFilter ?? '' }}', 
    branches: Alpine.store('formData').branches 
}" x-init="branches = Alpine.store('formData').branches">
   <label for="branch" class="block text-sm font-medium text-gray-700 mb-1">Branch</label>
   <div class="relative">
       <button @click="open = !open" 
               type="button"
               class="shadow-sm border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline flex justify-between items-center">
           <span x-text="selected ? selected : 'All Branches'"></span>
           <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
               <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
           </svg>
       </button>
       
       <div x-show="open" 
            @click.away="open = false"
            class="absolute z-10 w-full mt-1 bg-white shadow-lg rounded-md py-1" 
            style="display: none;">
           <div class="px-3 py-2">
               <input type="text" 
                      x-model="search"
                      placeholder="Search branches..."
                      class="shadow-sm border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
           </div>
           <ul class="max-h-60 overflow-auto">
               <li @click="selected = ''; selectedId = ''; open = false;" 
                   class="px-3 py-2 hover:bg-gray-100 cursor-pointer">
                   All Branches
               </li>
               
               <template x-for="branch in branches.filter(b => b.name.toLowerCase().includes(search.toLowerCase()))" :key="branch.id">
                   <li @click="selected = branch.name; selectedId = branch.id; open = false;" 
                       :class="{'bg-blue-100': selectedId === branch.id}"
                       class="px-3 py-2 hover:bg-gray-100 cursor-pointer" 
                       x-text="branch.name"></li>
               </template>
           </ul>
       </div>
       <input type="hidden" name="branch" x-bind:value="selectedId" />
   </div>
</div>
            
            <div class="md:col-span-2 lg:col-span-4 flex justify-end space-x-3">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Apply Filters
                </button>
                <a href="{{ route('users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Branch</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($users as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">@if($user->roles->isNotEmpty())
                                    @foreach($user->roles as $role)
                                        {{ $role->name }}
                                    @endforeach
                                    <br>
                                    <form action="{{ route('roles.revoke', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to revoke this role?')">
                                        @csrf
                                        @method('POST')

                                        <input type="hidden" name="role" value="{{ $role->name }}">
                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                        <button type="submit" class="text-red-600 hover:text-red-900 ">Revoke</button>
                                    </form>
                                @else
                                    <button type="button"
                                            class="text-blue-600 hover:text-blue-900 text-sm font-medium"
                                            @click="openAssignRoleModal('{{ $user->id }}', '{{ $user->name }}')">
                                        Assign Role
                                    </button>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{  $user->branch->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex flex-wrap space-x-2">
                                    <button @click="openShowModal('{{ $user->id }}')" class="text-blue-600 hover:text-blue-900">View</button>
                                    <button @click="openEditModal('{{ $user->id }}')" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this user?')">
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
        <div class="px-6 py-4 border-t">
            {{ $users->withQueryString()->links() }}
        </div>
    </div>

    <!-- Create User Modal -->
    <div x-show="showCreateModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Create New User</h3>
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                            <input type="text" name="name" id="name" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                            <input type="email" name="email" id="email" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                            <input type="password" name="password" id="password" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="branch_id" class="block text-gray-700 text-sm font-bold mb-2">Branch</label>
                            <select name="branch_id" id="branch_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">Select Branch</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="role" class="block text-gray-700 text-sm font-bold mb-2">Role</label>
                            <select name="role" id="role" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">Select Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
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

    <!-- Edit User Modal -->
    <div x-show="showEditModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form :action="'/users/' + editUserId" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Edit User</h3>
                        <div class="mb-4">
                            <label for="edit_name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                            <input type="text" name="name" id="edit_name" x-model="editUserData.name" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="edit_email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                            <input type="email" name="email" id="edit_email" x-model="editUserData.email" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="edit_role" class="block text-gray-700 text-sm font-bold mb-2">Role</label>
                            <select name="role" id="edit_role" x-model="editUserData.role" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">Select Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="old_role" x-model="editUserData.roles?.[0].name">
                        </div>
                        <div class="mb-4">
                            <label for="edit_branch_id" class="block text-gray-700 text-sm font-bold mb-2">Branch</label>
                            <select name="branch_id" id="edit_branch_id" x-model="editUserData.branch_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">Select Branch</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
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

    <!-- Show User Modal -->
    <div x-show="showShowModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">User Details</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Name</label>
                            <p class="text-gray-900" x-text="showUserData.name"></p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Email</label>
                            <p class="text-gray-900" x-text="showUserData.email"></p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Role</label>
                            <p class="text-gray-900" x-text="showUserData.roles?.[0].name"></p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Branch</label>
                            <p class="text-gray-900" x-text="showUserData.branch ? showUserData.branch.name : 'N/A'"></p>
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
    @include('partials.modals.assign-role')
</div>

@push('scripts')
    <script>
        function userManagement() {
    return {
        // Modal state for various modals:
        showCreateModal: false,
        showEditModal: false,
        showShowModal: false,
        showAssignRoleModal: false,

        // Data for user modals:
        editUserId: null,
        editUserData: {
            name: '',
            email: '',
            role: '',
            branch_id: ''
        },
        showUserData: {
            name: '',
            email: '',
            role: '',
            branch: null,
            daily_activities: []
        },

        // Data for assign-role modal:
        assignRoleUserId: '',
        assignRoleUserName: '',

        // Populate dropdown data on page load
init() {
            // Get all available roles from the select options
            const roleElements = document.querySelectorAll('#role option');
            const roles = Array.from(roleElements)
                .map(el => el.value)
                .filter(val => val !== '');
            
            // Get all available branches from the select options
            const branchElements = document.querySelectorAll('#branch_id option');
            const branches = Array.from(branchElements)
                .map(el => ({
                    id: el.value,
                    name: el.textContent.trim()
                }))
                .filter(branch => branch.id !== '');
            
            // Make these available to all Alpine components
            Alpine.store('formData', {
                roles: roles,
                branches: branches
            });
        },

        // Methods for opening modals:
        openCreateModal() {
            this.showCreateModal = true;
        },
        async openEditModal(userId) {
            this.editUserId = userId;
            try {
                const response = await fetch(`/users/${userId}/edit`);
                this.editUserData = await response.json();
                this.showEditModal = true;
            } catch (error) {
                console.error('Error fetching user data:', error);
            }
        },
        async openShowModal(userId) {
            try {
                const response = await fetch(`/users/${userId}`);
                this.showUserData = await response.json();
                this.showShowModal = true;
            } catch (error) {
                console.error('Error fetching user data:', error);
            }
        },
        openAssignRoleModal(userId, userName) {
            this.assignRoleUserId = userId;
            this.assignRoleUserName = userName;
            this.showAssignRoleModal = true;
        },

        // Method to submit the assign-role form using fetch.
        async submitAssignRole(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            const params = new URLSearchParams(formData);

            try {
                const response = await fetch(event.target.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: params.toString()
                });
                const data = await response.json();
                if (data.status === 'success') {
                    window.location.reload();
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }
    };
}

// Initialize Alpine.js store
document.addEventListener('alpine:init', () => {
    Alpine.store('formData', {
        roles: [],
        branches: []
    });
});
    </script>
@endpush
@endsection