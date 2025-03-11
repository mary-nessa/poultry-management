@extends('layouts.app')

@section('title', 'Breeds')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Breeds</h1>
        <button type="button" onclick="openCreateModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Add New Breed
        </button>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <!-- Search Filter -->
    <div class="bg-white shadow-md rounded-lg p-4 mb-6">
        <form action="{{ route('breeds.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Search by name or description">
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
                    Search
                </button>
                <a href="{{ route('breeds.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Responsive Table -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="block md:hidden">
            @forelse($breeds as $breed)
                <div class="border-b border-gray-200 p-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-900">#{{ $breeds->firstItem() + $loop->index }}</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $breed->name }}</p>
                            <p class="text-gray-600">{{ $breed->description ?? '-' }}</p>
                        </div>
                        <div class="flex space-x-2">
                            <button type="button" class="text-blue-600 hover:text-blue-900" onclick="openShowModal({{ $breed->id }})">View</button>
                            <button type="button" class="text-indigo-600 hover:text-indigo-900" onclick="openEditModal({{ $breed->id }})">Edit</button>
                            <form action="{{ route('breeds.destroy', $breed) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this breed?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-4 text-center text-gray-500">No breeds found.</div>
            @endforelse
        </div>
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($breeds as $index => $breed)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $breeds->firstItem() + $index }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $breed->name }}</td>
                            <td class="px-6 py-4">{{ $breed->description ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button type="button" class="text-blue-600 hover:text-blue-900" onclick="openShowModal({{ $breed->id }})">View</button>
                                    <button type="button" class="text-indigo-600 hover:text-indigo-900" onclick="openEditModal({{ $breed->id }})">Edit</button>
                                    <form action="{{ route('breeds.destroy', $breed) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this breed?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">No breeds found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 bg-white border-t border-gray-200">
            <nav class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
                <div class="text-sm text-gray-700">
                    Showing {{ $breeds->firstItem() }} to {{ $breeds->lastItem() }} of {{ $breeds->total() }} breeds
                </div>
                <div class="flex space-x-2">
                    @if ($breeds->onFirstPage())
                        <span class="px-3 py-2 text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">Previous</span>
                    @else
                        <a href="{{ $breeds->previousPageUrl() }}" class="px-3 py-2 text-blue-600 bg-blue-100 hover:bg-blue-200 rounded-md">Previous</a>
                    @endif
                    @foreach ($breeds->links()->elements[0] as $page => $url)
                        @if ($page == $breeds->currentPage())
                            <span class="px-3 py-2 text-white bg-blue-500 rounded-md">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-3 py-2 text-blue-600 bg-blue-100 hover:bg-blue-200 rounded-md">{{ $page }}</a>
                        @endif
                    @endforeach
                    @if ($breeds->hasMorePages())
                        <a href="{{ $breeds->nextPageUrl() }}" class="px-3 py-2 text-blue-600 bg-blue-100 hover:bg-blue-200 rounded-md">Next</a>
                    @else
                        <span class="px-3 py-2 text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">Next</span>
                    @endif
                </div>
            </nav>
        </div>
    </div>

    <!-- Modal -->
    <div id="modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div id="modal-header">
                <h3 id="modal-title" class="text-lg font-medium"></h3>
            </div>
            <div id="modal-body">
                <form id="breed-form" action="" method="POST">
                    @csrf
                    <input type="hidden" id="method" name="_method" value="">
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" id="close-modal" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">Cancel</button>
                        <button type="submit" id="submit-form" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Save</button>
                    </div>
                </form>
                <div id="show-content" class="hidden">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <p id="show-name" class="mt-1 text-gray-900"></p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <p id="show-description" class="mt-1 text-gray-900"></p>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" id="close-show" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    function openCreateModal() {
        document.getElementById('modal-title').textContent = 'Create New Breed';
        document.getElementById('breed-form').action = '{{ route('breeds.store') }}';
        document.getElementById('method').value = '';
        document.getElementById('name').value = '';
        document.getElementById('description').value = '';
        document.getElementById('breed-form').classList.remove('hidden');
        document.getElementById('show-content').classList.add('hidden');
        document.getElementById('submit-form').textContent = 'Create';
        document.getElementById('modal').classList.remove('hidden');
    }

    function openEditModal(id) {
        fetch(`/breeds/${id}`, {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('modal-title').textContent = 'Edit Breed';
            document.getElementById('breed-form').action = `/breeds/${id}`;
            document.getElementById('method').value = 'PUT';
            document.getElementById('name').value = data.name;
            document.getElementById('description').value = data.description || '';
            document.getElementById('breed-form').classList.remove('hidden');
            document.getElementById('show-content').classList.add('hidden');
            document.getElementById('submit-form').textContent = 'Update';
            document.getElementById('modal').classList.remove('hidden');
        })
        .catch(error => console.error('Error:', error));
    }

    function openShowModal(id) {
        fetch(`/breeds/${id}`, {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('modal-title').textContent = 'Breed Details';
            document.getElementById('show-name').textContent = data.name;
            document.getElementById('show-description').textContent = data.description || 'N/A';
            document.getElementById('breed-form').classList.add('hidden');
            document.getElementById('show-content').classList.remove('hidden');
            document.getElementById('modal').classList.remove('hidden');
        })
        .catch(error => console.error('Error:', error));
    }

    document.getElementById('close-modal').addEventListener('click', function() {
        document.getElementById('modal').classList.add('hidden');
    });

    document.getElementById('close-show').addEventListener('click', function() {
        document.getElementById('modal').classList.add('hidden');
    });

    document.getElementById('modal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });

    document.getElementById('breed-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const method = document.getElementById('method').value || 'POST';
        fetch(form.action, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                name: document.getElementById('name').value,
                description: document.getElementById('description').value
            })
        })
        .then(response => {
            if (response.ok) {
                return response.json().then(data => ({ data, status: response.status }));
            } else {
                return response.json().then(data => Promise.reject({ data, status: response.status }));
            }
        })
        .then(({ data, status }) => {
            if (status === 200 || status === 201) {
                alert('Breed saved successfully!');
                document.getElementById('modal').classList.add('hidden');
                location.reload();
            }
        })
        .catch(({ data, status }) => {
            if (status === 422) {
                const errors = data.errors;
                let errorMessage = 'Validation errors:\n';
                for (const field in errors) {
                    errorMessage += `- ${field}: ${errors[field].join(', ')}\n`;
                }
                alert(errorMessage);
            } else {
                alert('Error: ' + (data.message || 'Unknown error'));
            }
        });
    });
</script>
@endsection