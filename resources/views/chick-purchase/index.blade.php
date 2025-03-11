@extends('layouts.app')

@section('title', 'Bird Purchases')

@section('content')
    <div class="container mx-auto px-4 py-6" x-data="chickPurchaseManagement()">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Bird Purchases</h1>
            <button @click="openCreateModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                New Bird Purchase
            </button>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Filter Section -->
        <div class="bg-white shadow-md rounded-lg p-4 mb-6">
            <h2 class="text-lg font-semibold mb-3">Filters</h2>
            <form action="{{ route('chick-purchases.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="branch_filter" class="block text-sm font-medium text-gray-700 mb-1">Branch</label>
                    <select name="branch" id="branch_filter" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="">All Branches</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ request('branch') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="breed_filter" class="block text-sm font-medium text-gray-700 mb-1">Breed</label>
                    <select name="breed" id="breed_filter" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="">All Breeds</option>
                        @foreach($breeds as $breed)
                            <option value="{{ $breed->id }}" {{ request('breed') == $breed->id ? 'selected' : '' }}>{{ $breed->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>
                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                    <input 
                        type="date" 
                        name="date_to" 
                        id="date_to" 
                        value="{{ request('date_to') }}" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                        x-on:change="validateDateRange()"
                    >
                    <p x-show="dateError" x-text="dateError" class="text-red-500 text-xs mt-1" style="display: none;"></p>
                </div>
                <div class="md:col-span-4 flex justify-end">
                    <button type="submit" x-on:click.prevent="submitForm($event)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
                        Filter
                    </button>
                    <a href="{{ route('chick-purchases.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Responsive Table -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Branch</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Breed</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purchase Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($chickPurchases as $index => $purchase)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $chickPurchases->firstItem() + $index }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $purchase->branch->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $purchase->breed->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $purchase->quantity }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ date('d-m-Y', strtotime($purchase->purchase_date)) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('chick-purchases.show', $purchase->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                    <a href="{{ route('chick-purchases.edit', $purchase->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                    <form action="{{ route('chick-purchases.destroy', $purchase) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this purchase?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 bg-white border-t border-gray-200">
                {{ $chickPurchases->appends(request()->query())->links() }}
            </div>
        </div>

        <!-- Create Purchase Modal -->
        <div x-show="showCreateModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form action="{{ route('chick-purchases.store') }}" method="POST">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Create Bird Purchase</h3>
                            <div class="mb-4">
                                <label for="branch_id" class="block text-gray-700 text-sm font-bold mb-2">Branch</label>
                                @if(auth()->user()->hasRole('admin'))
                                    <select name="branch_id" id="branch_id" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        <option value="">Select Branch</option>
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="text" value="{{ auth()->user()->branch?->name ?? 'No branch assigned' }}" readonly class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <input type="hidden" name="branch_id" value="{{ auth()->user()->branch?->id }}">
                                @endif
                            </div>
                            <div class="mb-4">
                                <label for="supplier_id" class="block text-gray-700 text-sm font-bold mb-2">Supplier</label>
                                <select name="supplier_id" id="supplier_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option value="">Select Supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="breed_id" class="block text-gray-700 text-sm font-bold mb-2">Breed</label>
                                <select name="breed_id" id="breed_id" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option value="">Select Breed</option>
                                    @foreach($breeds as $breed)
                                        <option value="{{ $breed->id }}">{{ $breed->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="purchase_age" class="block text-gray-700 text-sm font-bold mb-2">Purchase Age (in days)</label>
                                <input type="number" name="purchase_age" id="purchase_age" min="0" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
                            <div class="mb-4">             
                                <label for="purchase_date" class="block text-sm font-medium text-gray-700">Purchase Date</label>             
                                <input type="date" 
                                       name="purchase_date" 
                                       id="purchase_date" 
                                       class="mt-1 block w-full border-gray-300 rounded-md"                    
                                       value="{{ old('purchase_date') }}" 
                                       required 
                                       max="{{ date('Y-m-d') }}">         
                            </div>                              
                            <div class="mb-4">
                                <label for="quantity" class="block text-gray-700 text-sm font-bold mb-2">Quantity</label>
                                <input type="number" name="quantity" id="quantity" min="1" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
                            <div class="mb-4">
                                <label for="unit_cost" class="block text-gray-700 text-sm font-bold mb-2">Unit Cost</label>
                                <input type="number" step="0.01" name="unit_cost" id="unit_cost" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Create
                            </button>
                            <button type="button" @click="showCreateModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function chickPurchaseManagement() {
                return {
                    showCreateModal: false,
                    dateError: null,
                    
                    openCreateModal() {
                        this.showCreateModal = true;
                    },
                    
                    validateDateRange() {
                        const dateFrom = document.getElementById('date_from').value;
                        const dateTo = document.getElementById('date_to').value;
                        
                        this.dateError = null;
                        
                        if (dateFrom && dateTo) {
                            const fromDate = new Date(dateFrom);
                            const toDate = new Date(dateTo);
                            
                            if (fromDate > toDate) {
                                this.dateError = 'End date must be after start date';
                                return false;
                            }
                        }
                        return true;
                    },
                    
                    submitForm(event) {
                        if (this.validateDateRange()) {
                            event.target.form.submit();
                        }
                    }
                }
            }
        </script>
    @endpush
@endsection