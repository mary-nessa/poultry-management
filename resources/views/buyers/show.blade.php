@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-4">Buyer Details</h2>

        @if (session('success'))
            <div class="bg-green-500 text-white p-3 rounded mb-3">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-500 text-white p-3 rounded mb-3">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-2 gap-4">
            <div>
                <strong>Name:</strong>
                <p class="text-gray-700">{{ $buyer->name }}</p>
            </div>

            <div>
                <strong>Phone:</strong>
                <p class="text-gray-700">{{ $buyer->phone_country_code }} {{ $buyer->phone_number }}</p>
            </div>

            <div>
                <strong>Email:</strong>
                <p class="text-gray-700">{{ $buyer->email ?? 'N/A' }}</p>
            </div>

            <div>
                <strong>Buyer Type:</strong>
                <p class="text-gray-700">{{ ucfirst(strtolower($buyer->buyer_type)) }}</p>
            </div>

            <div>
                <strong>Created At:</strong>
                <p class="text-gray-700">{{ $buyer->created_at->format('d M, Y H:i') }}</p>
            </div>

            <div>
                <strong>Updated At:</strong>
                <p class="text-gray-700">{{ $buyer->updated_at->format('d M, Y H:i') }}</p>
            </div>
        </div>

        <div class="mt-6 flex gap-3">
            <a href="{{ route('buyers.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Back</a>
            <a href="{{ route('buyers.edit', $buyer->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded">Edit</a>

            <form action="{{ route('buyers.destroy', $buyer->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this buyer?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Delete</button>
            </form>
        </div>
    </div>
</div>
@endsection
