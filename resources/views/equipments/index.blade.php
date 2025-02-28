@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-semibold mb-4">Equipment List</h1>

    <!-- Add Equipment Button -->
    <a href="{{ route('equipments.create') }}" class="inline-block mb-4 px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
        Add New Equipment
    </a>

    <!-- Equipment Table -->
    <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-sm">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b text-left">Equipment Name</th>
                <th class="py-2 px-4 border-b text-left">Quantity</th>
                <th class="py-2 px-4 border-b text-left">Price</th>
                <th class="py-2 px-4 border-b text-left">Status</th>
                <th class="py-2 px-4 border-b text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($equipment as $item)
            <tr>
                <td class="py-2 px-4 border-b">{{ $item->name }}</td>
                <td class="py-2 px-4 border-b">{{ $item->quantity }}</td>
                <td class="py-2 px-4 border-b">{{ number_format($item->unit_cost, 2) }}</td>
                <td class="py-2 px-4 border-b">{{ $item->status }}</td>
                <td class="py-2 px-4 border-b">
                    <!-- Show Button -->
                    <a href="{{ route('equipments.show', $item->id) }}" class="inline-block bg-blue-500 text-white py-1 px-3 rounded-md hover:bg-blue-600">Show</a>

                    <!-- Edit Button -->
                    <a href="{{ route('equipments.edit', $item->id) }}" class="inline-block bg-yellow-500 text-white py-1 px-3 rounded-md hover:bg-yellow-600 ml-2">Edit</a>
                    
                    <!-- Delete Button -->
                    <form action="{{ route('equipments.destroy', $item->id) }}" method="POST" class="inline-block ml-2" onsubmit="return confirmDelete()">
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

<script>
    // JavaScript function to confirm before delete
    function confirmDelete() {
        return confirm("Are you sure you want to delete this equipment?");
    }
</script>

@endsection
