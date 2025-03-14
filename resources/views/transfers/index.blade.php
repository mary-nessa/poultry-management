@extends('layouts.app')

@section('title', 'Transfers')

@section('content')
    <div class="container mx-auto my-4">
        <h1 class="text-3xl font-semibold">Transfers</h1>
        
        <div class="my-4 flex justify-between items-center">
            <a href="{{ route('transfers.create') }}" class="btn btn-primary bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200">Create Transfer</a>
            
            <button id="toggleFilters" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition duration-200">
                <span id="filterButtonText">Show Filters</span>
            </button>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                {{ session('success') }}
            </div>
        @endif
        
        <!-- Filters Section -->
        <div id="filtersContainer" class="bg-gray-100 p-4 rounded-lg mb-4 hidden">
            <form action="{{ route('transfers.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Type Filter -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select name="type" id="type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="">All Types</option>
                        <option value="birds" {{ request('type') == 'birds' ? 'selected' : '' }}>Birds</option>
                        <option value="eggs" {{ request('type') == 'eggs' ? 'selected' : '' }}>Eggs</option>
                    </select>
                </div>
                
                {{-- <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" id="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                 --}}
                {{-- <!-- Breed Filter -->
                <div id="breedFilterContainer">
                    <label for="breed_id" class="block text-sm font-medium text-gray-700 mb-1">Breed</label>
                    <select name="breed_id" id="breed_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="">All Breeds</option>
                        @foreach($breeds ?? [] as $breed)
                            <option value="{{ $breed->id }}" {{ request('breed_id') == $breed->id ? 'selected' : '' }}>
                                {{ $breed->name }}
                            </option>
                        @endforeach
                    </select>
                </div> --}}
                
                <!-- From Branch Filter -->
                <div>
                    <label for="from_branch_id" class="block text-sm font-medium text-gray-700 mb-1">From Branch</label>
                    <select name="from_branch_id" id="from_branch_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="">All Branches</option>
                        @foreach($branches ?? [] as $branch)
                            <option value="{{ $branch->id }}" {{ request('from_branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- To Branch Filter -->
                <div>
                    <label for="to_branch_id" class="block text-sm font-medium text-gray-700 mb-1">To Branch</label>
                    <select name="to_branch_id" id="to_branch_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="">All Branches</option>
                        @foreach($branches ?? [] as $branch)
                            <option value="{{ $branch->id }}" {{ request('to_branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Filter Actions -->
                <div class="flex items-end space-x-2">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200">
                        Apply Filters
                    </button>
                    <a href="{{ route('transfers.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition duration-200">
                        Reset
                    </a>
                </div>
            </form>
        </div>
        
        <!-- Applied Filters Summary -->
        @if(request()->anyFilled(['type', 'status', 'from_branch_id', 'to_branch_id', 'breed_id']))
            <div class="bg-blue-50 p-3 rounded-md mb-4">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="font-semibold">Filters: </span>
                        @if(request('type'))
                            <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded mr-2 text-sm">
                                Type: {{ ucfirst(request('type')) }}
                            </span>
                        @endif
                        @if(request('status'))
                            <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded mr-2 text-sm">
                                Status: {{ ucfirst(request('status')) }}
                            </span>
                        @endif
                        @if(request('breed_id'))
                            <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded mr-2 text-sm">
                                Breed: {{ optional($breeds->firstWhere('id', request('breed_id')))->name }}
                            </span>
                        @endif
                        @if(request('from_branch_id'))
                            <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded mr-2 text-sm">
                                From: {{ optional($branches->firstWhere('id', request('from_branch_id')))->name }}
                            </span>
                        @endif
                        @if(request('to_branch_id'))
                            <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded mr-2 text-sm">
                                To: {{ optional($branches->firstWhere('id', request('to_branch_id')))->name }}
                            </span>
                        @endif
                    </div>
                    <a href="{{ route('transfers.index') }}" class="text-blue-600 hover:text-blue-800">
                        Clear All
                    </a>
                </div>
            </div>
        @endif
        
        <!-- Responsive Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto border-collapse text-left bg-white shadow-md rounded-lg">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        {{-- <th class="border-b px-6 py-4">#</th> <!-- Row Number Column --> --}}
                        <th class="border-b px-6 py-4">Type</th>
                        {{-- <th class="border-b px-6 py-4">Breed</th> --}}
                        <th class="border-b px-6 py-4">From Branch</th>
                        <th class="border-b px-6 py-4">To Branch</th>
                        <th class="border-b px-6 py-4">Status</th>
                        <th class="border-b px-6 py-4">Quantity</th>
                        <th class="border-b px-6 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600">
                    @foreach($transfers as $index => $transfer)
                        <tr class="hover:bg-gray-50">
                            {{-- <td class="border-b px-6 py-4">{{ $index + 1 }}</td> <!-- Row number --> --}}
                            <td class="border-b px-6 py-4">{{ $transfer->type }}</td>
                            {{-- <td class="border-b px-6 py-4">{{ $transfer->breed ? $transfer->breed->name : 'N/A' }}</td> <!-- Show breed name or 'N/A' --> --}}
                            <td class="border-b px-6 py-4">{{ $transfer->fromBranch->name }}</td>
                            <td class="border-b px-6 py-4">{{ $transfer->toBranch->name }}</td>
                            <td class="border-b px-6 py-4">{{ ucfirst($transfer->status) }}</td>
                            <td class="border-b px-6 py-4">{{ $transfer->quantity }}</td>
                            <td class="border-b px-6 py-4">
                                <!-- View Button -->
                                <a href="{{ route('transfers.show', $transfer->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200">View</a> |
                                
                                <!-- Edit Button -->
                                <a href="{{ route('transfers.edit', $transfer->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-700 transition duration-200">Edit</a> |
                                
                                <!-- Delete Button -->
                                <form action="{{ route('transfers.destroy', $transfer->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete()">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-700 transition duration-200">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="my-4">
            {{ $transfers->links() }}
        </div>
    </div>

    <!-- JavaScript for Delete Confirmation and Filter Toggle -->
    <script>
        function confirmDelete() {
            return confirm('Are you sure you want to delete this transfer? This action cannot be undone.');
        }
        
        // Filter toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButton = document.getElementById('toggleFilters');
            const filtersContainer = document.getElementById('filtersContainer');
            const filterButtonText = document.getElementById('filterButtonText');
            
            // Check if there are any active filters
            const hasActiveFilters = {{ request()->anyFilled(['type', 'status', 'from_branch_id', 'to_branch_id', 'breed_id']) ? 'true' : 'false' }};
            
            // Show filters by default if there are active filters
            if (hasActiveFilters) {
                filtersContainer.classList.remove('hidden');
                filterButtonText.textContent = 'Hide Filters';
            }
            
            toggleButton.addEventListener('click', function() {
                if (filtersContainer.classList.contains('hidden')) {
                    filtersContainer.classList.remove('hidden');
                    filterButtonText.textContent = 'Hide Filters';
                } else {
                    filtersContainer.classList.add('hidden');
                    filterButtonText.textContent = 'Show Filters';
                }
            });
            
            // Show/hide breed filter based on type selection
            const typeSelect = document.getElementById('type');
            const breedFilterContainer = document.getElementById('breedFilterContainer');
            
            function updateBreedVisibility() {
                if (typeSelect.value === 'birds' || typeSelect.value === '') {
                    breedFilterContainer.style.display = 'block';
                } else {
                    breedFilterContainer.style.display = 'none';
                }
            }
            
            // Set initial visibility
            updateBreedVisibility();
            
            // Update visibility when type changes
            typeSelect.addEventListener('change', updateBreedVisibility);
        });
    </script>
@endsection