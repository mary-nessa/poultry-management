@extends('layouts.app')

@section('title', 'Feeds Management - Farm System')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-3xl font-bold mb-6 text-center">Feeds Management</h1>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('feeds.create') }}" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">
            Add New Feed
        </a>

        <div class="flex space-x-4 items-center">
            <form method="GET" action="{{ route('feeds.index') }}" id="entriesForm">
                <label for="entries" class="text-gray-700">Show:</label>
                <select name="entries" id="entries" class="border rounded-lg px-2 py-1" onchange="this.form.submit()">
                    <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                </select>
            </form>

            <form action="{{ route('feeds.index') }}" method="GET" class="flex items-center">
                <input type="text" name="search" placeholder="Search feeds..."
                       value="{{ request('search') }}"
                       class="border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit" class="ml-2 bg-gray-500 text-white py-2 px-4 rounded-md hover:bg-gray-600">
                    Search
                </button>
            </form>
        </div>
    </div>

    <!-- Responsive Table -->
    <div class="shadow overflow-hidden border border-gray-200 sm:rounded-lg mb-6">
        <div class="overflow-hidden">
            <table class="w-full table-auto border-collapse divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No.</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Feed Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity (kg)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purchase Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($feeds as $index => $feed)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                                {{ ($feeds->currentPage() - 1) * $feeds->perPage() + $index + 1 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($feed->feedType)
                                    {{ $feed->feedType->name }}
                                @else
                                    <span class="text-red-500">Feed type is required</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($feed->quantity_kg, 2) }} kg</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($feed->purchase_date)->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('feeds.show', $feed->id) }}" class="bg-blue-500 text-white py-1 px-3 rounded-md hover:bg-blue-600">
                                        View
                                    </a>
                                    <a href="{{ route('feeds.edit', $feed->id) }}" class="bg-yellow-500 text-white py-1 px-3 rounded-md hover:bg-yellow-600">
                                        Edit
                                    </a>
                                    <form action="{{ route('feeds.destroy', $feed->id) }}" method="POST" class="inline-block" onsubmit="return confirmDelete()">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white py-1 px-3 rounded-md hover:bg-red-600">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                No feeds found. 
                                @if(request('search'))
                                    <a href="{{ route('feeds.index') }}" class="text-blue-500 hover:underline">Clear search</a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="flex justify-between items-center mt-4">
        <p class="text-gray-700">Showing {{ $feeds->firstItem() }} to {{ $feeds->lastItem() }} of {{ $feeds->total() }} entries</p>
        {{ $feeds->links('vendor.pagination.tailwind') }}
    </div>
</div>

<script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete this feed? This action cannot be undone.");
    }
</script>
@endsection
