@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-bold mb-4">Edit Supplier</h2>

        <form action="{{ route('suppliers.update', $supplier) }}" method="POST" class="bg-white p-6 rounded shadow-md">
            @csrf
            @method('PUT')

            <label class="block mb-2">Name:</label>
            <input type="text" name="name" value="{{ old('name', $supplier->name) }}" class="w-full border p-2 rounded mb-3" required>

            <label class="block mb-2">Phone:</label>
            <div class="flex gap-2 mb-3">
                <select id="country_code" name="phone_country_code" class="border p-2 rounded w-1/3">
                    @foreach($countryCodes as $code => $country)
                        <option value="{{ $code }}" {{ $supplier->phone_country_code == $code ? 'selected' : '' }}>
                            {{ $country }} ({{ $code }})
                        </option>
                    @endforeach
                </select>
                <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $supplier->phone_number) }}" class="border p-2 rounded w-2/3" placeholder="Enter phone number" required>
            </div>

            <label class="block mb-2">Email:</label>
            <input type="email" name="email" value="{{ old('email', $supplier->email) }}" class="w-full border p-2 rounded mb-3">

            <label class="block mb-2">Branch:</label>
            <select name="branch_id" class="w-full border p-2 rounded mb-3" required>
                @foreach($branches as $id => $name)
                    <option value="{{ $id }}" {{ $supplier->branch_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>

            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded">Update Supplier</button>
        </form>
    </div>
@endsection
