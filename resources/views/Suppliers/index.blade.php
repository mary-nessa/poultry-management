@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 sm:p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Suppliers</h2>
        <p class="text-sm text-gray-600">Manage your supplier information</p>
    </div>
    
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
        <!-- Actions Section -->
        <div>
            <a href="{{ route('suppliers.create') }}"
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded inline-flex items-center transition duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Add New Supplier
            </a>
        </div>
        
        <!-- Filters Section -->
        <div class="bg-white p-4 rounded-lg shadow flex flex-col sm:flex-row gap-3">
            <form action="{{ route('suppliers.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3">
                <div class="w-full sm:w-auto">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Filter by Name</label>
                    <input type="text" name="name" id="name" value="{{ request('name') }}" 
                        class="w-full sm:w-64 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        placeholder="Enter supplier name">
                </div>
                
                <div class="w-full sm:w-auto">
                    <label for="branch" class="block text-sm font-medium text-gray-700 mb-1">Filter by Branch</label>
                    <select name="branch" id="branch" 
                        class="w-full sm:w-48 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        <option value="">All Branches</option>
                        @foreach(\App\Models\Branch::all() as $branch)
                            <option value="{{ $branch->id }}" {{ request('branch') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex items-end gap-2 mt-2 sm:mt-0">
                    <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded transition duration-200">
                        Filter
                    </button>
                    <a href="{{ route('suppliers.index') }}" class="text-gray-600 hover:text-gray-800 px-2 py-2">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    @if (session('success'))
        <div class="bg-green-500 text-white px-4 py-3 rounded mb-6 flex justify-between items-center shadow-md">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                {{ session('success') }}
            </div>
            <button type="button" onclick="this.parentElement.style.display='none'" class="text-white hover:text-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    @endif
    
    {{-- <!-- Results Count -->
    <div class="mb-4 text-sm text-gray-600">
        Showing {{ $suppliers->firstItem() ?? 0 }} to {{ $suppliers->lastItem() ?? 0 }} of {{ $suppliers->total() }} suppliers
    </div> --}}
    
    <!-- Table Section -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 uppercase text-xs leading-normal">
                        <th class="py-3 px-6 text-left">Name</th>
                        <th class="py-3 px-6 text-left">Phone</th>
                        <th class="py-3 px-6 text-left">Email</th>
                        <th class="py-3 px-6 text-left">Branch</th>
                        <th class="py-3 px-6 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm">
                    @forelse($suppliers as $supplier)
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="py-3 px-6 text-left">
                                <div class="font-medium">{{ $supplier->name }}</div>
                            </td>
                            <td class="py-3 px-6 text-left">
                                <span class="font-medium">{{ $supplier->phone_country_code }} {{ $supplier->phone_number }}</span>
                            </td>
                            <td class="py-3 px-6 text-left">
                                {{ $supplier->email ?? 'N/A' }}
                            </td>
                            <td class="py-3 px-6 text-left">
                                {{ $supplier->branch->name ?? 'N/A' }}
                            </td>
                            <td class="py-3 px-6 text-center">
                                <div class="flex item-center justify-center gap-2">
                                    <a href="{{ route('suppliers.show', $supplier) }}" 
                                        class="text-blue-500 hover:text-blue-700 bg-blue-100 hover:bg-blue-200 rounded-full p-1"
                                        title="View details">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('suppliers.edit', $supplier) }}" 
                                        class="text-yellow-500 hover:text-yellow-700 bg-yellow-100 hover:bg-yellow-200 rounded-full p-1"
                                        title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                            class="text-red-500 hover:text-red-700 bg-red-100 hover:bg-red-200 rounded-full p-1"
                                            onclick="return confirm('Are you sure you want to delete this supplier?')"
                                            title="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <span class="mt-2">No suppliers found</span>
                                    <a href="{{ route('suppliers.create') }}" class="mt-1 text-blue-500 hover:underline">Add your first supplier</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Pagination -->
    <div class="mt-6">
        {{ $suppliers->withQueryString()->links() }}
    </div>
</div>

<script>
    // Simple script to make alerts dismissible
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const alerts = document.querySelectorAll('.bg-green-500');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 1s';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.style.display = 'none';
                }, 1000);
            });
        }, 5000); // Auto dismiss after 5 seconds
    });
</script>
@endsection