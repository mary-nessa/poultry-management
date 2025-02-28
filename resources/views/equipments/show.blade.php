@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-semibold mb-4">Equipment Details</h1>

    <!-- Equipment Details -->
    <div class="bg-white shadow-lg p-6 rounded-lg">
        <p><strong>Name:</strong> {{ $equipment->name }}</p>
        <p><strong>Quantity:</strong> {{ $equipment->quantity }}</p>
        <p><strong>Unit Cost:</strong> {{ number_format($equipment->unit_cost, 2) }}</p>
        <p><strong>Total Cost:</strong> {{ number_format($equipment->total_cost, 2) }}</p>
        <p><strong>Purchase Date:</strong> {{ \Carbon\Carbon::parse($equipment->purchase_date)->format('d M, Y') }}</p>
        <p><strong>Status:</strong> {{ $equipment->status }}</p>
        <p><strong>Supplier:</strong> {{ $equipment->supplier ? $equipment->supplier->name : 'No Supplier' }}</p>

        <!-- Back Button -->
        <a href="{{ route('equipments.index') }}" class="mt-4 inline-block bg-gray-500 text-white py-2 px-4 rounded-md hover:bg-gray-600">Back to List</a>
    </div>
</div>
@endsection
