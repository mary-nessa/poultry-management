@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-3xl font-bold mb-6 text-center">Feeds Management</h1>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('feeds.create') }}" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 mb-4 inline-block">Add New Feed</a>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Feed Type</th>
                    <th class="py-2 px-4 border-b">Quantity (kg)</th>
                    <th class="py-2 px-4 border-b">Unit Cost</th>
                    <th class="py-2 px-4 border-b">Total Cost</th>
                    <th class="py-2 px-4 border-b">Purchase Date</th>
                    <th class="py-2 px-4 border-b">Supplier</th>
                    <th class="py-2 px-4 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($feeds as $feed)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $feed->type }}</td>
                        <td class="py-2 px-4 border-b">{{ $feed->quantity_kg }}</td>
                        <td class="py-2 px-4 border-b">{{ number_format((int)$feed->unit_cost) }}</td> <!-- Removed decimals -->
                        <td class="py-2 px-4 border-b">{{ number_format((int)$feed->total_cost) }}</td> <!-- Removed decimals -->
                        <td class="py-2 px-4 border-b">{{ $feed->purchase_date }}</td>
                        <td class="py-2 px-4 border-b">{{ $feed->supplier ? $feed->supplier->name : 'No Supplier' }}</td>
                        <td class="py-2 px-4 border-b text-center">
                            <a href="{{ route('feeds.edit', $feed->id) }}" class="bg-yellow-500 text-white py-1 px-3 rounded-md hover:bg-yellow-600">Edit</a>

                            <form action="{{ route('feeds.destroy', $feed->id) }}" method="POST" class="inline-block ml-2" onsubmit="return confirmDelete()">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white py-1 px-3 rounded-md hover:bg-red-600">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    // JavaScript function to confirm before delete
    function confirmDelete() {
        return confirm("Are you sure you want to delete this feed?");
    }
</script>
@endsection
