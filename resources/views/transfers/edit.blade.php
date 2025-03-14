@extends('layouts.app')

@section('title', 'Edit Transfer')

@section('content')
    <div class="container mx-auto my-4">
        <h1 class="text-3xl font-semibold">Edit Transfer</h1>
        
        <form action="{{ route('transfers.update', $transfer->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                <select id="type" name="type" class="mt-1 block w-full border-gray-300 rounded-md" required>
                    <option value="">Select Type</option>
                    <option value="birds" {{ old('type', $transfer->type) === 'birds' ? 'selected' : '' }}>Birds</option>
                    <option value="eggs" {{ old('type', $transfer->type) === 'eggs' ? 'selected' : '' }}>Eggs</option>
                </select>
                @error('type') <div class="text-red-500">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4" id="breed-container">
                <label for="breed_id" class="block text-sm font-medium text-gray-700">Breed</label>
                <select id="breed_id" name="breed_id" class="mt-1 block w-full border-gray-300 rounded-md">
                    <option value="">Select Breed</option>
                    @foreach ($breeds as $breed)
                        <option value="{{ $breed->id }}" {{ old('breed_id', $transfer->breed_id) == $breed->id ? 'selected' : '' }}>{{ $breed->name }}</option>
                    @endforeach
                </select>
                @error('breed_id') <div class="text-red-500">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label for="from_branch_id" class="block text-sm font-medium text-gray-700">From Branch</label>
                <select id="from_branch_id" name="from_branch_id" class="mt-1 block w-full border-gray-300 rounded-md" required>
                    <option value="">Select Branch</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}" {{ old('from_branch_id', $transfer->from_branch_id) == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
                @error('from_branch_id') <div class="text-red-500">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label for="to_branch_id" class="block text-sm font-medium text-gray-700">To Branch</label>
                <select id="to_branch_id" name="to_branch_id" class="mt-1 block w-full border-gray-300 rounded-md" required>
                    <option value="">Select Branch</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}" {{ old('to_branch_id', $transfer->to_branch_id) == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
                @error('to_branch_id') <div class="text-red-500">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label for="user_id" class="block text-sm font-medium text-gray-700">User</label>
                <input type="text" class="mt-1 block w-full border-gray-300 rounded-md" value="{{ $transfer->user->name }}" disabled>
                <input type="hidden" name="user_id" value="{{ $transfer->user_id }}">
            </div>

            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-md" required>
                    <option value="pending" {{ old('status', $transfer->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ old('status', $transfer->status) === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ old('status', $transfer->status) === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                @error('status') <div class="text-red-500">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                <input type="number" id="quantity" name="quantity" class="mt-1 block w-full border-gray-300 rounded-md" min="1" value="{{ old('quantity', $transfer->quantity) }}" required>
                @error('quantity') <div class="text-red-500">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                <textarea id="notes" name="notes" class="mt-1 block w-full border-gray-300 rounded-md">{{ old('notes', $transfer->notes) }}</textarea>
                @error('notes') <div class="text-red-500">{{ $message }}</div> @enderror
            </div>

            <div class="flex">
                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded mr-2 hover:bg-blue-700 transition duration-200">Update Transfer</button>
                <a href="{{ route('transfers.index') }}" class="bg-gray-300 text-gray-700 py-2 px-4 rounded hover:bg-gray-400 transition duration-200">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('type').addEventListener('change', function () {
            var breedContainer = document.getElementById('breed-container');
            if (this.value === 'birds') {
                breedContainer.style.display = 'block';
            } else {
                breedContainer.style.display = 'none';
            }
        });

        // Trigger the event to show/hide breed field based on initial type value
        document.getElementById('type').dispatchEvent(new Event('change'));
    </script>
@endsection