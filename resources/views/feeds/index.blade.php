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
        <a href="{{ route('feeds.create') }}" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 inline-block">         
            Add New Feed     
        </a>

        <!-- Search Form -->
        <div class="relative">
            <form action="{{ route('feeds.index') }}" method="GET" class="flex items-center">
                <input type="text" 
                       name="search" 
                       placeholder="Search feeds..." 
                       value="{{ request('search') }}"
                       class="border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit" class="ml-2 bg-gray-500 text-white py-2 px-4 rounded-md hover:bg-gray-600">
                    Search
                </button>
            </form>
        </div>
    </div>

    <!-- Responsive Table -->
    <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg mb-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Feed Type
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Quantity (kg)
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Purchase Date
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($feeds as $feed)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $feed->type }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ number_format($feed->quantity_kg, 2) }} kg</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($feed->purchase_date)->format('M d, Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('feeds.show', $feed->id) }}" 
                                       class="bg-blue-500 text-white py-1 px-3 rounded-md hover:bg-blue-600 inline-block">
                                        View
                                    </a>
                                    <a href="{{ route('feeds.edit', $feed->id) }}" 
                                       class="bg-yellow-500 text-white py-1 px-3 rounded-md hover:bg-yellow-600 inline-block">
                                        Edit
                                    </a>
                                    <form action="{{ route('feeds.destroy', $feed->id) }}" 
                                          method="POST" 
                                          class="inline-block" 
                                          onsubmit="return confirmDelete()">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="bg-red-500 text-white py-1 px-3 rounded-md hover:bg-red-600">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
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
    <div class="mt-4">
        {{ $feeds->links() }}
    </div>
</div>  

<script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete this feed? This action cannot be undone.");
    }

    // Add responsive table functionality
    document.addEventListener('DOMContentLoaded', function() {
        const table = document.querySelector('table');
        let isDragging = false;
        let startX;
        let scrollLeft;

        table.addEventListener('mousedown', (e) => {
            isDragging = true;
            startX = e.pageX - table.offsetLeft;
            scrollLeft = table.parentElement.scrollLeft;
        });

        table.addEventListener('mouseleave', () => {
            isDragging = false;
        });

        table.addEventListener('mouseup', () => {
            isDragging = false;
        });

        table.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            e.preventDefault();
            const x = e.pageX - table.offsetLeft;
            const walk = (x - startX) * 2;
            table.parentElement.scrollLeft = scrollLeft - walk;
        });
    });
</script>
@endsection