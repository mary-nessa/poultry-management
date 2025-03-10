@extends('layouts.app')

@section('title', 'Feeds Management - Farm System')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-6xl">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Feeds Management</h1>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow" role="alert">
            <div class="flex items-center">
                <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
            <a href="{{ route('feeds.create') }}" class="inline-flex items-center bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-150 ease-in-out shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add New Feed
            </a>

            <div class="flex flex-col sm:flex-row gap-4">
                <form method="GET" action="{{ route('feeds.index') }}" id="entriesForm" class="flex items-center">
                    <label for="entries" class="text-gray-700 mr-2 whitespace-nowrap">Show entries:</label>
                    <select name="entries" id="entries" class="border rounded-lg px-3 py-2 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" onchange="this.form.submit()">
                        <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </form>

                <form action="{{ route('feeds.index') }}" method="GET" class="flex items-center">
                    <div class="relative">
                        <input type="text" name="search" placeholder="Search feeds..."
                               value="{{ request('search') }}"
                               class="border rounded-lg pl-10 pr-4 py-2 w-full sm:w-64 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <button type="submit" class="ml-2 bg-gray-600 text-white py-2 px-4 rounded-md hover:bg-gray-700 transition duration-150 ease-in-out shadow-sm">
                        Search
                    </button>
                </form>
            </div>
        </div>

        <!-- Responsive Table -->
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No.</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Feed Type</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity (kg)</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purchase Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($feeds as $index => $feed)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                {{ ($feeds->currentPage() - 1) * $feeds->perPage() + $index + 1 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($feed->feedType)
                                    <span class="bg-green-100 text-green-800 py-1 px-2 rounded-full text-xs font-medium">
                                        {{ $feed->feedType->name }}
                                    </span>
                                @else
                                    <span class="bg-red-100 text-red-800 py-1 px-2 rounded-full text-xs font-medium">
                                        Feed type missing
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                {{ number_format($feed->quantity_kg, 2) }} kg
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="bg-blue-50 text-blue-700 py-1 px-2 rounded text-xs">
                                    {{ \Carbon\Carbon::parse($feed->purchase_date)->format('M d, Y') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('feeds.show', $feed->id) }}" 
                                       class="bg-blue-50 text-blue-600 py-1 px-3 rounded-md hover:bg-blue-100 transition-colors">
                                        <span>View</span>
                                    </a>
                                    <a href="{{ route('feeds.edit', $feed->id) }}" 
                                       class="bg-yellow-50 text-yellow-600 py-1 px-3 rounded-md hover:bg-yellow-100 transition-colors">
                                        <span>Edit</span>
                                    </a>
                                    <form action="{{ route('feeds.destroy', $feed->id) }}" method="POST" class="inline-block" onsubmit="return confirmDelete()">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="bg-red-50 text-red-600 py-1 px-3 rounded-md hover:bg-red-100 transition-colors">
                                            <span>Delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-gray-600 text-lg">No feeds found</p>
                                    @if(request('search'))
                                        <a href="{{ route('feeds.index') }}" class="mt-2 text-blue-500 hover:underline">Clear search</a>
                                    @else
                                        <p class="mt-2 text-gray-500 text-sm">Add a new feed to get started</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="bg-white rounded-lg shadow-md p-4">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-gray-700 text-sm">
                Showing 
                <span class="font-medium">{{ $feeds->firstItem() ?: 0 }}</span> 
                to 
                <span class="font-medium">{{ $feeds->lastItem() ?: 0 }}</span> 
                of 
                <span class="font-medium">{{ $feeds->total() }}</span> 
                entries
            </p>
            {{ $feeds->links('vendor.pagination.tailwind') }}
        </div>
    </div>
</div>

<script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete this feed? This action cannot be undone.");
    }
</script>
@endsection