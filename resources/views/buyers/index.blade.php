@extends('layouts.app')


@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Buyers List</h2>
    
    <a href="{{ route('buyers.create') }}" 
       class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block transition duration-200">
        Add Buyer
    </a>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-200 mt-4">
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="border px-4 py-2">Name</th>
                    <th class="border px-4 py-2">Phone</th>
                    <th class="border px-4 py-2">Email</th>
                    <th class="border px-4 py-2">Type</th>
                    <th class="border px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($buyers as $buyer)
                <tr class="text-center hover:bg-gray-50">
                    <td class="border px-4 py-2">{{ $buyer->name }}</td>
                    <td class="border px-4 py-2">{{ $buyer->phone_country_code }} {{ $buyer->phone_number }}</td>
                    <td class="border px-4 py-2">{{ $buyer->email ?? 'N/A' }}</td>
                    <td class="border px-4 py-2">{{ $buyer->buyer_type }}</td>
                    <td class="border px-4 py-2">
                        <a href="{{ route('buyers.show', $buyer->id) }}" class="text-blue-500 hover:text-blue-700">View</a> |
                        <a href="{{ route('buyers.edit', $buyer->id) }}" class="text-yellow-500 hover:text-yellow-700">Edit</a> |
                        <form action="{{ route('buyers.destroy', $buyer->id) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700" 
                                    onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $buyers->links() }}
    </div>
</div>
@endsection