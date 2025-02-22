@extends('layouts.app')


@section('title', 'Add New Supplier')

@section('content')
<div class="container mx-auto px-4 py-6 flex justify-center">
    <div class="w-full max-w-md bg-white shadow-lg rounded-lg p-6">
        <div class="flex justify-between items-center mb-6 border-b pb-3">
            <h1 class="text-2xl font-bold text-gray-800">Add New Supplier</h1>
            <a href="{{ route('suppliers.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold transition">&larr; Back</a>
        </div>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('suppliers.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-gray-700 text-sm font-semibold mb-1" for="name">Supplier Name</label>
                <input class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring focus:ring-blue-300 focus:outline-none" 
                    id="name" 
                    type="text" 
                    name="name" 
                    value="{{ old('name') }}" 
                    placeholder="Enter supplier name" 
                    required>
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-semibold mb-1" for="contact_info">Contact Information</label>
                <textarea class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring focus:ring-blue-300 focus:outline-none resize-none" 
                    id="contact_info" 
                    name="contact_info" 
                    rows="4" 
                    placeholder="Enter contact details"
                    required>{{ old('contact_info') }}</textarea>
            </div>

            <div class="flex justify-end">
                <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition shadow-md">Create Supplier</button>
            </div>
        </form>
    </div>
</div>
@endsection
