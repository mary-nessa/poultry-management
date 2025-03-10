@extends('layouts.app')

@section('title', 'View Breed')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-lg">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Breed Details</h1>

    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Name</label>
            <p class="mt-1 text-gray-900">{{ $breed->name }}</p>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Description</label>
            <p class="mt-1 text-gray-900">{{ $breed->description ?? 'N/A' }}</p>
        </div>
        <div class="flex justify-end">
            <a href="{{ route('breeds.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                Back
            </a>
        </div>
    </div>
</div>
@endsection