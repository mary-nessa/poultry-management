<!-- resources/views/suppliers/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Suppliers</h1>
        <a href="{{ route('suppliers.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Add New Supplier
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded my-6">
        <table class="min-w-full table-auto">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm">
                    <th class="py-3 px-6 text-left">Name</th>
                    <th class="py-3 px-6 text-left">Contact Info</th>
                    <th class="py-3 px-6 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-600">
                @foreach($suppliers as $supplier)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-4 px-6">{{ $supplier->name }}</td>
                        <td class="py-4 px-6">{{ $supplier->contact_info }}</td>
                        <td class="py-4 px-6 text-center">
                            <a href="{{ route('suppliers.show', $supplier) }}" class="text-blue-500 hover:text-blue-700 mr-3">
                                View
                            </a>
                            <a href="{{ route('suppliers.edit', $supplier) }}" class="text-yellow-500 hover:text-yellow-700 mr-3">
                                Edit
                            </a>
                            <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Are you sure you want to delete this supplier?')">
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
