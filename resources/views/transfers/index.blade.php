@extends('layouts.app')

@section('title', 'Transfers')

@section('content')
    <div class="container mx-auto my-4">
        <h1 class="text-3xl font-semibold">Transfers</h1>
        
        <div class="my-4">
            <a href="{{ route('transfers.create') }}" class="btn btn-primary">Create Transfer</a>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        <!-- Responsive Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto border-collapse text-left bg-white shadow-md rounded-lg">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="border-b px-6 py-4">#</th> <!-- Row Number Column -->
                        <th class="border-b px-6 py-4">Type</th>
                        <th class="border-b px-6 py-4">Breed</th>
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
                            <td class="border-b px-6 py-4">{{ $index + 1 }}</td> <!-- Row number -->
                            <td class="border-b px-6 py-4">{{ $transfer->type }}</td>
                            <td class="border-b px-6 py-4">{{ $transfer->breed ?? 'N/A' }}</td> <!-- Show 'N/A' if breed is null -->
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
    </div>

    <!-- JavaScript for Delete Confirmation -->
    <script>
        function confirmDelete() {
            return confirm('Are you sure you want to delete this transfer? This action cannot be undone.');
        }
    </script>
@endsection
