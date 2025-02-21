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
                <th class="py-2 px-4 border-b">Equipment Name</th>
                <th class="py-2 px-4 border-b">Quantity</th>
                <th class="py-2 px-4 border-b">Unit Cost</th>
                <th class="py-2 px-4 border-b">Total Cost</th>
                <th class="py-2 px-4 border-b">Purchase Date</th>
                <th class="py-2 px-4 border-b">Status</th>
                <th class="py-2 px-4 border-b">Supplier</th>
                <th class="py-2 px-4 border-b">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($equipment as $item)
            <tr>
                <td class="py-2 px-4 border-b">{{ $item->name }}</td>
                <td class="py-2 px-4 border-b">{{ $item->quantity }}</td>
                <td class="py-2 px-4 border-b">{{ number_format($item->unit_cost, 2) }}</td>
                <td class="py-2 px-4 border-b">{{ number_format($item->total_cost, 2) }}</td>
                <td class="py-2 px-4 border-b">{{ \Carbon\Carbon::parse($item->purchase_date)->format('d M, Y') }}</td>
                <td class="py-2 px-4 border-b">{{ $item->status }}</td>
                <td class="py-2 px-4 border-b">{{ $item->supplier ? $item->supplier->name : 'No Supplier' }}</td>
                <td class="py-2 px-4 border-b">
                    <a href="{{ route('equipments.edit', $item->id) }}" class="bg-yellow-500 text-white py-1 px-3 rounded-md hover:bg-yellow-600">Edit</a>
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
        return confirm("Are you sure you want to delete this feed?");
    }
</script>
@endsection
