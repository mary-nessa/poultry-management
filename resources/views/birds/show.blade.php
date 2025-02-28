@extends('layouts.app')

@section('title', 'Bird Group Details')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Bird Group Details</h1>

        <div class="bg-white shadow-md rounded-lg p-6">
            <table class="min-w-full border border-gray-200">
                <tbody>
                    <tr class="border-b">
                        <td class="px-6 py-3 font-semibold text-gray-700">Batch ID</td>
                        <td class="px-6 py-3">{{ $bird->chickPurchase ? $bird->chickPurchase->batch_id : 'N/A' }}</td>
                    </tr>
                    <tr class="border-b">
                        <td class="px-6 py-3 font-semibold text-gray-700">Total Birds</td>
                        <td class="px-6 py-3">{{ $bird->total_birds }}</td>
                    </tr>
                    <tr class="border-b">
                        <td class="px-6 py-3 font-semibold text-gray-700">Hens</td>
                        <td class="px-6 py-3">{{ $bird->hen_count }}</td>
                    </tr>
                    <tr class="border-b">
                        <td class="px-6 py-3 font-semibold text-gray-700">Cocks</td>
                        <td class="px-6 py-3">{{ $bird->cock_count }}</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-3 font-semibold text-gray-700">Created At</td>
                        <td class="px-6 py-3">{{ $bird->created_at->format('d M, Y') }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="flex justify-end mt-6">
                <a href="{{ route('birds.edit', $bird->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">
                    Edit
                </a>
                <a href="{{ route('birds.index') }}" class="ml-4 text-gray-600 hover:underline">Back to List</a>
            </div>
        </div>
    </div>
@endsection
