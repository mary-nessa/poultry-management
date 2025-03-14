@extends('layouts.app')

@section('content')
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

    @if ($errors->any())
        <div class="bg-yellow-500 text-white p-3 rounded mb-3">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-bold mb-4">Edit Buyer</h2>

        <form action="{{ route('buyers.update', $buyer->id) }}" method="POST" class="bg-white p-6 rounded shadow-md">
            @csrf
            @method('PUT')

            <label class="block mb-2">Name:</label>
            <input type="text" name="name" class="w-full border p-2 rounded mb-3" value="{{ old('name', $buyer->name) }}" required>

            <label class="block mb-2">Phone:</label>
            <div class="flex gap-2 mb-3">
                <select id="country_code" name="phone_country_code" class="border p-2 rounded w-1/3">
                    @foreach($countryCodes as $code => $country)
                        <option value="{{ $code }}" {{ old('phone_country_code', $buyer->phone_country_code) == $code ? 'selected' : '' }}>
                            {{ $country }} ({{ $code }})
                        </option>
                    @endforeach
                </select>
                <input type="text" id="phone_number" name="phone_number"
                    class="border p-2 rounded w-2/3"
                    placeholder="Enter phone number"
                    value="{{ old('phone_number', $buyer->phone_number) }}" required>
            </div>

            <label class="block mb-2">Email:</label>
            <input type="email" name="email" class="w-full border p-2 rounded mb-3" value="{{ old('email', $buyer->email) }}">

            <label class="block mb-2">Buyer Type:</label>
            <select id="buyer_type" name="buyer_type" class="w-full border p-2 rounded mb-3" required>
                <option value="WALKIN" {{ old('buyer_type', $buyer->buyer_type) == 'WALKIN' ? 'selected' : '' }}>Walk-in</option>
                <option value="REGULAR" {{ old('buyer_type', $buyer->buyer_type) == 'REGULAR' ? 'selected' : '' }}>Regular</option>
            </select>

            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Update Buyer</button>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            new TomSelect("#country_code", { create: false, sortField: "text" });
            new TomSelect("#buyer_type", { create: false, sortField: "text" });

            // Prevent leading zero in phone number
            document.getElementById("phone_number").addEventListener("input", function (e) {
                if (this.value.startsWith("0")) {
                    this.value = this.value.replace(/^0+/, "");
                }
            });
        });
    </script>
@endsection
