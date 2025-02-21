@extends('layouts.app')

@section('content')
<div class="container mx-auto my-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-extrabold text-gray-800">Medicines</h1>
        <a href="{{ route('medicine.create') }}" class="btn btn-primary py-2 px-4 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">Add New Medicine</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success bg-green-100 text-green-700 p-4 rounded-md mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="table-auto w-full border-collapse">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="px-6 py-3 text-left">Name</th>
                    <th class="px-6 py-3 text-left">Quantity</th>
                    <th class="px-6 py-3 text-left">Unit Cost</th>
                    <th class="px-6 py-3 text-left">Total Cost</th>
                    <th class="px-6 py-3 text-left">Expiry Date</th>
                    <th class="px-6 py-3 text-left">Supplier</th>
                    <th class="px-6 py-3 text-left">Purpose</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($medicines as $medicine)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $medicine->name }}</td>
                    <td class="px-6 py-4">{{ $medicine->quantity }}</td>
                    <td class="px-6 py-4">{{ $medicine->unit_cost }}</td>
                    <td class="px-6 py-4">{{ $medicine->total_cost }}</td>
                    <td class="px-6 py-4">
                        {{ $medicine->expiry_date 
                            ? \Carbon\Carbon::parse($medicine->expiry_date)->format('d/m/Y') 
                            : 'N/A' 
                        }}
                    </td>
                    <td class="px-6 py-4">{{ $medicine->supplier->name ?? 'No Supplier' }}</td>
                    <td class="px-6 py-4">{{ $medicine->purpose }}</td>
                    <td class="px-6 py-4 flex gap-2">
                        <a href="{{ route('medicine.edit', $medicine) }}" class="btn btn-warning py-1 px-3 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 focus:ring-2 focus:ring-yellow-400">Edit</a>
                        <a href="{{ route('medicine.show', $medicine) }}" class="btn btn-info py-1 px-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:ring-2 focus:ring-blue-400">View</a>
                        <form action="{{ route('medicine.destroy', $medicine) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger py-1 px-3 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:ring-2 focus:ring-red-400"
                                onclick="return confirm('Are you sure you want to delete this medicine?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
