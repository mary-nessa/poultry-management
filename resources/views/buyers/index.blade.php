@extends('layouts.app')

@section('title', 'Buyers')

@section('content')
<div class="container mx-auto px-4 py-6" x-data="buyerManagement()">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Buyers</h1>
        <button @click="openCreateModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            New Buyer
        </button>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($buyers as $buyer)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $buyer->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $buyer->contact }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $buyer->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button @click="openEditModal({{ $buyer->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                            <form action="{{ route('buyers.destroy', $buyer) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this buyer?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Create Buyer Modal -->
    <div x-show="showCreateModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">New Buyer</h3>
                <form action="{{ route('buyers.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                        <input type="text" name="name" required class="w-full border rounded px-3 py-2">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Contact</label>
                        <input type="text" name="contact" required class="w-full border rounded px-3 py-2">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                        <input type="email" name="email" class="w-full border rounded px-3 py-2">
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
                        <button type="button" @click="showCreateModal = false" class="ml-2 bg-gray-300 px-4 py-2 rounded">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Buyer Modal -->
    <div x-show="showEditModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Buyer</h3>
                <form :action="'/buyers/' + editBuyerId" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                        <input type="text" name="name" x-model="editBuyerData.name" required class="w-full border rounded px-3 py-2">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Contact</label>
                        <input type="text" name="contact" x-model="editBuyerData.contact" required class="w-full border rounded px-3 py-2">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                        <input type="email" name="email" x-model="editBuyerData.email" class="w-full border rounded px-3 py-2">
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
                        <button type="button" @click="showEditModal = false" class="ml-2 bg-gray-300 px-4 py-2 rounded">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function buyerManagement() {
    return {
        showCreateModal: false,
        showEditModal: false,
        editBuyerId: null,
        editBuyerData: {
            name: '',
            contact: '',
            email: ''
        },
        openCreateModal() {
            this.showCreateModal = true;
        },
        openEditModal(id) {
            fetch(`/buyers/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    this.editBuyerId = id;
                    this.editBuyerData = data;
                    this.showEditModal = true;
                })
                .catch(error => console.error('Error:', error));
        }
    };
}
</script>

@endsection
