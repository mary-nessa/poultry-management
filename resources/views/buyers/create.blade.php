@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-2xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Create New Buyer</h1>
            <a href="{{ route('buyers.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded">
                Back to List
            </a>
        </div>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('buyers.store') }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">
                        Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name') }}" 
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror"
                           required>
                </div>

                <div class="mb-4">
                    <label for="contact_info" class="block text-gray-700 text-sm font-bold mb-2">
                        Contact Information
                    </label>
                    <input type="text" 
                           name="contact_info" 
                           id="contact_info" 
                           value="{{ old('contact_info') }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('contact_info') border-red-500 @enderror"
                           placeholder="Phone number or email">
                </div>

                <div class="mb-6">
                    <label for="buyer_type" class="block text-gray-700 text-sm font-bold mb-2">
                        Buyer Type <span class="text-red-500">*</span>
                    </label>
                    <select name="buyer_type" 
                            id="buyer_type" 
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('buyer_type') border-red-500 @enderror"
                            required>
                        <option value="WALKIN" {{ old('buyer_type') == 'WALKIN' ? 'selected' : '' }}>Walk-in</option>
                        <option value="REGULAR" {{ old('buyer_type') == 'REGULAR' ? 'selected' : '' }}>Regular</option>
                    </select>
                </div>

                <div class="flex items-center justify-end gap-4">
                    <a href="{{ route('buyers.index') }}" class="text-gray-600 hover:text-gray-800">Cancel</a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Create Buyer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection