@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">Equipment List</h1>
        
        <!-- Add Equipment Button -->
        <a href="{{ route('equipments.create') }}" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition duration-200 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Add New Equipment
        </a>
    </div>
    
    <!-- Search and Filter Section -->
    <div class="bg-white p-4 rounded-lg shadow-sm mb-4">
        <form action="{{ route('equipments.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search by Name</label>
                <div class="relative">
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           placeholder="Enter equipment name...">
                    @if(request('search'))
                        <a href="{{ route('equipments.index') }}" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif
                </div>
            </div>
            <div class="flex-none self-end">
                <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-200 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                    Search
                </button>
            </div>
        </form>
    </div>
    
    <!-- Search Results Info -->
    @if(request('search'))
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4 rounded-md">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700">
                    Showing results for search: <span class="font-bold">"{{ request('search') }}"</span>
                    <a href="{{ route('equipments.index') }}" class="ml-2 font-medium underline">Clear filter</a>
                </p>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Responsive Table Container -->
    <div class="overflow-x-auto bg-white rounded-lg shadow-sm">
        <table class="min-w-full border border-gray-300">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-3 px-4 border-b text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Equipment Name</th>
                    <th class="py-3 px-4 border-b text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Quantity</th>
                    <th class="py-3 px-4 border-b text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Price (UGX)</th>
                    <th class="py-3 px-4 border-b text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
                    <th class="py-3 px-4 border-b text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($equipment as $item)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4 whitespace-nowrap">{{ $item->name }}</td>
                    <td class="py-3 px-4 whitespace-nowrap">{{ $item->quantity }}</td>
                    <td class="py-3 px-4 whitespace-nowrap"> {{ number_format($item->unit_cost, 0) }}</td>
                    <td class="py-3 px-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($item->status == 'Available') bg-green-100 text-green-800 
                            @elseif($item->status == 'In Use') bg-blue-100 text-blue-800 
                            @elseif($item->status == 'Maintenance') bg-yellow-100 text-yellow-800 
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ $item->status }}
                        </span>
                    </td>
                    <td class="py-3 px-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <!-- Show Button -->
                            <a href="{{ route('equipments.show', $item->id) }}" class="text-blue-600 hover:text-blue-900" title="View details">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            
                            <!-- Edit Button -->
                            <a href="{{ route('equipments.edit', $item->id) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                            </a>
                            
                            <!-- Delete Button -->
                            <form action="{{ route('equipments.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirmDelete()">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="mt-4">
        {{ $equipment->links() }}
    </div>
    
    <!-- No results message -->
    @if(count($equipment) == 0)
    <div class="bg-white p-4 rounded-lg shadow-sm text-center mt-4">
        <p class="text-gray-500">No equipment records found.</p>
    </div>
    @endif
</div>

<script>
    // JavaScript function to confirm before delete
    function confirmDelete() {
        return confirm("Are you sure you want to delete this equipment?");
    }
</script>

<style>
    /* Ensure the pagination links look good with Tailwind */
    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 1rem;
    }
    
    .pagination > * {
        margin: 0 0.25rem;
        padding: 0.5rem 0.75rem;
        border-radius: 0.25rem;
        color: #4B5563;
    }
    
    .pagination > .active {
        background-color: #3B82F6;
        color: white;
    }
    
    .pagination > *:hover:not(.active) {
        background-color: #E5E7EB;
    }
    
    /* Make pagination responsive */
    @media (max-width: 640px) {
        .pagination > * {
            padding: 0.35rem 0.5rem;
            font-size: 0.875rem;
        }
    }
</style>
@endsection