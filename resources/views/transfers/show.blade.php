@extends('layouts.app')

@section('title', 'Transfer Details')

@section('content')
    <div class="container mx-auto my-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-semibold">Transfer Details</h1>
            <div>
                <a href="{{ route('transfers.index') }}" class="bg-gray-300 text-gray-700 py-2 px-4 rounded hover:bg-gray-400 transition duration-200">Back to List</a>
                <a href="{{ route('transfers.edit', $transfer->id) }}" class="ml-2 bg-yellow-500 text-white py-2 px-4 rounded hover:bg-yellow-700 transition duration-200">Edit</a>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h2 class="text-xl font-semibold mb-4">Transfer Information</h2>
                        
                        {{-- <div class="mb-4">
                            <span class="text-gray-600 font-medium">Transfer ID:</span>
                            <span class="ml-2">{{ $transfer->id }}</span>
                        </div> --}}
                        
                        <div class="mb-4">
                            <span class="text-gray-600 font-medium">Type:</span>
                            <span class="ml-2 capitalize">{{ $transfer->type }}</span>
                        </div>
                        
                        @if($transfer->type === 'birds' && $transfer->breed)
                        <div class="mb-4">
                            <span class="text-gray-600 font-medium">Breed:</span>
                            <span class="ml-2">{{ $transfer->breed->name }}</span>
                        </div>
                        @endif
                        
                        <div class="mb-4">
                            <span class="text-gray-600 font-medium">From Branch:</span>
                            <span class="ml-2">{{ $transfer->fromBranch->name }}</span>
                        </div>
                        
                        <div class="mb-4">
                            <span class="text-gray-600 font-medium">To Branch:</span>
                            <span class="ml-2">{{ $transfer->toBranch->name }}</span>
                        </div>
                    </div>
                    
                    <div>
                        <h2 class="text-xl font-semibold mb-4">Additional Details</h2>
                        
                        <div class="mb-4">
                            <span class="text-gray-600 font-medium">Quantity:</span>
                            <span class="ml-2">{{ $transfer->quantity }}</span>
                        </div>
                        
                        <div class="mb-4">
                            <span class="text-gray-600 font-medium">Status:</span>
                            <span class="ml-2">
                                @if($transfer->status === 'pending')
                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-sm">Pending</span>
                                @elseif($transfer->status === 'approved')
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-sm">Approved</span>
                                @elseif($transfer->status === 'rejected')
                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-sm">Rejected</span>
                                @endif
                            </span>
                        </div>
                        
                        <div class="mb-4">
                            <span class="text-gray-600 font-medium">Created By:</span>
                            <span class="ml-2">{{ $transfer->user->name }}</span>
                        </div>
                        
                        <div class="mb-4">
                            <span class="text-gray-600 font-medium">Created At:</span>
                            <span class="ml-2">{{ $transfer->created_at->format('M d, Y H:i') }}</span>
                        </div>
                        
                        <div class="mb-4">
                            <span class="text-gray-600 font-medium">Updated At:</span>
                            <span class="ml-2">{{ $transfer->updated_at->format('M d, Y H:i') }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6">
                    <h2 class="text-xl font-semibold mb-4">Notes</h2>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        {{ $transfer->notes ?: 'No notes available.' }}
                    </div>
                </div>
                
                <div class="mt-6 flex justify-between">
                    <div>
                        @if($transfer->status === 'pending')
                            <form action="{{ route('transfers.update', $transfer->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="approved">
                                <input type="hidden" name="type" value="{{ $transfer->type }}">
                                <input type="hidden" name="breed_id" value="{{ $transfer->breed_id }}">
                                <input type="hidden" name="from_branch_id" value="{{ $transfer->from_branch_id }}">
                                <input type="hidden" name="to_branch_id" value="{{ $transfer->to_branch_id }}">
                                <input type="hidden" name="quantity" value="{{ $transfer->quantity }}">
                                <input type="hidden" name="notes" value="{{ $transfer->notes }}">
                                <input type="hidden" name="user_id" value="{{ $transfer->user_id }}">
                                <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-700 transition duration-200">Approve Transfer</button>
                            </form>
                            
                            <form action="{{ route('transfers.update', $transfer->id) }}" method="POST" class="inline ml-2">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="rejected">
                                <input type="hidden" name="type" value="{{ $transfer->type }}">
                                <input type="hidden" name="breed_id" value="{{ $transfer->breed_id }}">
                                <input type="hidden" name="from_branch_id" value="{{ $transfer->from_branch_id }}">
                                <input type="hidden" name="to_branch_id" value="{{ $transfer->to_branch_id }}">
                                <input type="hidden" name="quantity" value="{{ $transfer->quantity }}">
                                <input type="hidden" name="notes" value="{{ $transfer->notes }}">
                                <input type="hidden" name="user_id" value="{{ $transfer->user_id }}">
                                <button type="submit" class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-700 transition duration-200">Reject Transfer</button>
                            </form>
                        @endif
                    </div>
                    
                    <form action="{{ route('transfers.destroy', $transfer->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this transfer? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-700 transition duration-200">Delete Transfer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection