@extends('layouts.app')

@section('title', 'Sales')

@section('content')
<div class="container mx-auto px-4 py-6" x-data="salesManagement()">
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-4 sm:mb-0">Sales</h1>
        <button @click="openCreateModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            New Sale
        </button>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Sales Table -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Branch</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Buyer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount (UGX)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($sales as $sale)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $sale->branch->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $sale->buyer ? $sale->buyer->name : 'Walk-in' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ number_format($sale->items->sum('total_amount'), 0) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $sale->is_paid ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $sale->is_paid ? 'Paid' : 'Pending' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button @click="openShowModal('{{ $sale->id }}')" class="text-blue-600 hover:text-blue-900">View</button>
                                    <button @click="openEditModal('{{ $sale->id }}')" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                    <form action="{{ route('sales.destroy', $sale) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this sale?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">No sales found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-white border-t border-gray-200">
            {{ $sales->links() }}
        </div>
    </div>

    <!-- Create Sale Modal -->
    <div x-show="showCreateModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" @click.away="showCreateModal = false">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                <form action="{{ route('sales.store') }}" method="POST" @submit.prevent="submitSale()">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">New Sale</h3>

                        <!-- Sale Details -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="branch_id" class="block text-gray-700 text-sm font-bold mb-2">Branch</label>
                                <select name="branch_id" id="branch_id" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option value="">Select Branch</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="buyer_id" class="block text-gray-700 text-sm font-bold mb-2">Buyer</label>
                                <select name="buyer_id" id="buyer_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option value="">Walk-in Customer</option>
                                    @foreach($buyers as $buyer)
                                        <option value="{{ $buyer->id }}">{{ $buyer->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Add to Cart Section -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Add Product to Cart</label>
                            <div class="flex flex-col sm:flex-row gap-2 mb-2">
                                <select x-model="newItem.product_id" class="shadow appearance-none border rounded w-full sm:w-1/2 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option value="">Select Product</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" x-bind:data-price="{{ $product->default_price }}">{{ $product->product_type }} - UGX {{ number_format($product->default_price, 0) }}</option>
                                    @endforeach
                                </select>
                                <input type="number" x-model.number="newItem.quantity" placeholder="Quantity" min="1" class="shadow appearance-none border rounded w-full sm:w-24 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <input type="number" x-model.number="newItem.unit_price" placeholder="Unit Price (UGX)" step="0.01" class="shadow appearance-none border rounded w-full sm:w-32 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <button type="button" @click="addToCart()" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Add to Cart</button>
                            </div>
                        </div>

                        <!-- Cart Summary -->
                        <div class="mb-4" x-show="cartItems.length > 0">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Cart</label>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price (UGX)</th>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total (UGX)</th>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <template x-for="(item, index) in cartItems" :key="index">
                                            <tr>
                                                <td class="px-3 py-2 whitespace-nowrap">
                                                    <span x-text="getProductName(item.product_id)"></span>
                                                    <input type="hidden" :name="'items['+index+'][product_id]'" x-bind:value="item.product_id">
                                                </td>
                                                <td class="px-3 py-2 whitespace-nowrap">
                                                    <span x-text="item.quantity"></span>
                                                    <input type="hidden" :name="'items['+index+'][quantity]'" x-bind:value="item.quantity">
                                                </td>
                                                <td class="px-3 py-2 whitespace-nowrap">
                                                    <span x-text="formatNumber(item.unit_price)"></span>
                                                    <input type="hidden" :name="'items['+index+'][unit_price]'" x-bind:value="item.unit_price">
                                                </td>
                                                <td class="px-3 py-2 whitespace-nowrap" x-text="formatNumber(item.quantity * item.unit_price)"></td>
                                                <td class="px-3 py-2 whitespace-nowrap">
                                                    <button type="button" @click="removeFromCart(index)" class="bg-red-500 text-white px-2 rounded hover:bg-red-600">×</button>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-2 text-right">
                                <span class="font-bold">Total: </span>
                                <span x-text="'UGX ' + formatNumber(cartItems.reduce((sum, item) => sum + (item.quantity * item.unit_price), 0))"></span>
                            </div>
                        </div>

                        <!-- Payment Details -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="payment_method" class="block text-gray-700 text-sm font-bold mb-2">Payment Method</label>
                                <select name="payment_method" id="payment_method" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option value="CASH">Cash</option>
                                    <option value="CARD">Card</option>
                                    <option value="MOBILE">Mobile Money</option>
                                    <option value="CREDIT">Credit</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Payment Status</label>
                                <div class="flex items-center">
                                    <input type="checkbox" name="is_paid" id="is_paid" x-model="isPaid" class="mr-2" checked>
                                    <label for="is_paid">Paid</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4" x-show="!isPaid">
                            <label for="balance" class="block text-gray-700 text-sm font-bold mb-2">Balance (UGX)</label>
                            <input type="number" name="balance" id="balance" step="0.01" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Create Sale
                        </button>
                        <button type="button" @click="showCreateModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Show Sale Modal -->
    <div x-show="showShowModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" @click.away="showShowModal = false">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Sale Details</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Date</label>
                            <p class="text-gray-900" x-text="showSaleData.sale_date"></p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Branch</label>
                            <p class="text-gray-900" x-text="showSaleData.branch ? showSaleData.branch.name : 'N/A'"></p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Buyer</label>
                            <p class="text-gray-900" x-text="showSaleData.buyer ? showSaleData.buyer.name : 'Walk-in'"></p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Items</label>
                            <div class="mt-2">
                                <template x-for="item in showSaleData.items" :key="item.id">
                                    <div class="flex justify-between py-1">
                                        <span x-text="item.product.product_type"></span>
                                        <span x-text="item.quantity + ' x ' + formatNumber(item.unit_price)"></span>
                                        <span x-text="'UGX ' + formatNumber(item.total_amount)"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Payment Method</label>
                            <p class="text-gray-900" x-text="showSaleData.payment_method"></p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Payment Status</label>
                            <p class="text-gray-900" x-text="showSaleData.is_paid ? 'Paid' : 'Pending'"></p>
                        </div>
                        <div x-show="showSaleData.balance">
                            <label class="text-sm font-medium text-gray-500">Balance</label>
                            <p class="text-gray-900" x-text="'UGX ' + formatNumber(showSaleData.balance)"></p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" @click="showShowModal = false" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function salesManagement() {
    return {
        showCreateModal: false,
        showShowModal: false,
        isPaid: true,
        newItem: { product_id: '', quantity: 1, unit_price: 0 },
        cartItems: [],
        showSaleData: {
            sale_date: '',
            branch: null,
            buyer: null,
            items: [],
            payment_method: '',
            is_paid: true,
            balance: null
        },
        products: @json($products->map(function ($product) {
            return ['id' => $product->id, 'name' => $product->product_type, 'price' => $product->default_price];
        })),

        addToCart() {
            if (!this.newItem.product_id || this.newItem.quantity < 1) {
                alert('Please select a product and enter a valid quantity.');
                return;
            }
            if (!this.newItem.unit_price) {
                const selectedProduct = this.products.find(p => p.id == this.newItem.product_id);
                this.newItem.unit_price = selectedProduct ? selectedProduct.price : 0;
            }
            this.cartItems.push({ ...this.newItem });
            this.newItem = { product_id: '', quantity: 1, unit_price: 0 };
        },

        removeFromCart(index) {
            this.cartItems.splice(index, 1);
        },

        getProductName(productId) {
            const product = this.products.find(p => p.id == productId);
            return product ? product.name : 'Unknown';
        },

        formatNumber(number) {
            return Number(number || 0).toLocaleString('en-UG', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
        },

        openCreateModal() {
            this.cartItems = [];
            this.newItem = { product_id: '', quantity: 1, unit_price: 0 };
            this.isPaid = true;
            this.showCreateModal = true;
        },

        async openShowModal(saleId) {
            try {
                const response = await fetch(`/sales/${saleId}`);
                const data = await response.json();
                this.showSaleData = data;
                this.showShowModal = true;
            } catch (error) {
                console.error('Error fetching sale data:', error);
            }
        },

        submitSale() {
            if (this.cartItems.length === 0) {
                alert('Please add at least one item to the cart.');
                return;
            }
            this.$el.submit();
        }
    }
}
</script>
@endpush
@endsection