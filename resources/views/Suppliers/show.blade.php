@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-bold mb-4">Supplier Details</h2>

        <div class="bg-white p-6 rounded shadow-md">
            <p><strong>Name:</strong> {{ $supplier->name }}</p>
            <p><strong>Phone:</strong> {{ $supplier->phone_country_code }} {{ $supplier->phone_number }}</p>
            <p><strong>Email:</strong> {{ $supplier->email ?? 'N/A' }}</p>
            <p><strong>Branch:</strong> {{ $supplier->branch->name ?? 'N/A' }}</p>
        </div>

        <a href="{{ route('suppliers.edit', $supplier) }}" class="bg-yellow-500 text-white p-3 rounded mt-4 inline-block">Edit Supplier</a>
    </div>
@endsection
