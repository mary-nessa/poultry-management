@extends('layouts.app')

@section('title', 'Expenses')

@section('content')
<div class="container mx-auto px-4 py-6" x-data="expenseManagement()">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Expenses</h1>
        <button @click="openCreateModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            New Expense
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

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Branch</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($expenses as $expense)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $expense->category }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">KES {{ number_format($expense->amount, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $expense->expense_date }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $expense->expense_type === 'RECURRING' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                {{ $expense->expense_type }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $expense->branch->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button @click="openShowModal('{{ $expense->id }}')" class="text-blue-600 hover:text-blue-900 mr-3">View</button>
                            <button @click="openEditModal('{{ $expense->id }}')" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                            <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this expense?')">
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

    <!-- Create Expense Modal -->
    <div x-show="showCreateModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('expenses.store') }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Create New Expense</h3>
                        
                        <div class="mb-4">
                            <label for="category" class="block text-gray-700 text-sm font-bold mb-2">Category</label>
                            <input type="text" name="category" id="category" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>

                        <div class="mb-4">
                            <label for="amount" class="block text-gray-700 text-sm font-bold mb-2">Amount</label>
                            <input type="number" step="0.01" name="amount" id="amount" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>

                        <div class="mb-4">
                            <label for="expense_date" class="block text-gray-700 text-sm font-bold mb-2">Expense Date</label>
                            <input type="datetime-local" name="expense_date" id="expense_date" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>

                        <div class="mb-4">
                            <label for="expense_type" class="block text-gray-700 text-sm font-bold mb-2">Expense Type</label>
                            <select name="expense_type" id="expense_type" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="RECURRING">Recurring</option>
                                <option value="TEMPORARY">Temporary</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="branch_id" class="block text-gray-700 text-sm font-bold mb-2">Branch</label>
                            <select name="branch_id" id="branch_id" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">Select Branch</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                            <textarea name="description" id="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="3"></textarea>
                        </div>

                        <!-- Optional Related Items -->
                        <div class="mb-4">
                            <label for="chick_purchase_id" class="block text-gray-700 text-sm font-bold mb-2">Related Chick Purchase</label>
                            <select name="chick_purchase_id" id="chick_purchase_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">None</option>
                                @foreach($chickPurchases as $purchase)
                                    <option value="{{ $purchase->id }}">{{ $purchase->breed }} - {{ $purchase->quantity }} chicks</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="feed_id" class="block text-gray-700 text-sm font-bold mb-2">Related Feed</label>
                            <select name="feed_id" id="feed_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">None</option>
                                @foreach($feeds as $feed)
                                    <option value="{{ $feed->id }}">{{ $feed->type }} - {{ $feed->quantity_kg }}kg</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="medicine_id" class="block text-gray-700 text-sm font-bold mb-2">Related Medicine</label>
                            <select name="medicine_id" id="medicine_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">None</option>
                                @foreach($medicines as $medicine)
                                    <option value="{{ $medicine->id }}">{{ $medicine->name }} - {{ $medicine->quantity }} units</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="equipment_id" class="block text-gray-700 text-sm font-bold mb-2">Related Equipment</label>
                            <select name="equipment_id" id="equipment_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">None</option>
                                @foreach($equipments as $equipment)
                                    <option value="{{ $equipment->id }}">{{ $equipment->name }} - {{ $equipment->quantity }} units</option>
                                @endforeach
                            </select>
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

    <!-- Edit Expense Modal -->
    <div x-show="showEditModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form :action="'/expenses/' + editExpenseId" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Expense</h3>
                        
                        <div class="mb-4">
                            <label for="edit_category" class="block text-gray-700 text-sm font-bold mb-2">Category</label>
                            <input type="text" name="category" id="edit_category" x-model="editExpenseData.category" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>

                        <div class="mb-4">
                            <label for="edit_amount" class="block text-gray-700 text-sm font-bold mb-2">Amount</label>
                            <input type="number" step="0.01" name="amount" id="edit_amount" x-model="editExpenseData.amount" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>

                        <div class="mb-4">
                            <label for="edit_expense_date" class="block text-gray-700 text-sm font-bold mb-2">Expense Date</label>
                            <input type="datetime-local" name="expense_date" id="edit_expense_date" x-model="editExpenseData.expense_date" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>

                        <div class="mb-4">
                            <label for="edit_expense_type" class="block text-gray-700 text-sm font-bold mb-2">Expense Type</label>
                            <select name="expense_type" id="edit_expense_type" x-model="editExpenseData.expense_type" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="RECURRING">Recurring</option>
                                <option value="TEMPORARY">Temporary</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="edit_branch_id" class="block text-gray-700 text-sm font-bold mb-2">Branch</label>
                            <select name="branch_id" id="edit_branch_id" x-model="editExpenseData.branch_id" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">Select Branch</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="edit_description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                            <textarea name="description" id="edit_description" x-model="editExpenseData.description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="3"></textarea>
                        </div>

                        <!-- Optional Related Items -->
                        <div class="mb-4">
                            <label for="edit_chick_purchase_id" class="block text-gray-700 text-sm font-bold mb-2">Related Chick Purchase</label>
                            <select name="chick_purchase_id" id="edit_chick_purchase_id" x-model="editExpenseData.chick_purchase_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">None</option>
                                @foreach($chickPurchases as $purchase)
                                    <option value="{{ $purchase->id }}">{{ $purchase->breed }} - {{ $purchase->quantity }} chicks</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="edit_feed_id" class="block text-gray-700 text-sm font-bold mb-2">Related Feed</label>
                            <select name="feed_id" id="edit_feed_id" x-model="editExpenseData.feed_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">None</option>
                                @foreach($feeds as $feed)
                                    <option value="{{ $feed->id }}">{{ $feed->type }} - {{ $feed->quantity_kg }}kg</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="edit_medicine_id" class="block text-gray-700 text-sm font-bold mb-2">Related Medicine</label>
                            <select name="medicine_id" id="edit_medicine_id" x-model="editExpenseData.medicine_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">None</option>
                                @foreach($medicines as $medicine)
                                    <option value="{{ $medicine->id }}">{{ $medicine->name }} - {{ $medicine->quantity }} units</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="edit_equipment_id" class="block text-gray-700 text-sm font-bold mb-2">Related Equipment</label>
                            <select name="equipment_id" id="edit_equipment_id" x-model="editExpenseData.equipment_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">None</option>
                                @foreach($equipments as $equipment)
                                    <option value="{{ $equipment->id }}">{{ $equipment->name }} - {{ $equipment->quantity }} units</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Update
                        </button>
                        <button type="button" @click="showEditModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Show Expense Modal -->
    <div x-show="showShowModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Expense Details</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Category</label>
                            <p class="text-gray-900" x-text="showExpenseData.category"></p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Amount</label>
                            <p class="text-gray-900" x-text="'KES ' + showExpenseData.amount"></p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Expense Date</label>
                            <p class="text-gray-900" x-text="showExpenseData.expense_date"></p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Expense Type</label>
                            <p class="text-gray-900" x-text="showExpenseData.expense_type"></p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Branch</label>
                            <p class="text-gray-900" x-text="showExpenseData.branch ? showExpenseData.branch.name : 'N/A'"></p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Description</label>
                            <p class="text-gray-900" x-text="showExpenseData.description || 'No description'"></p>
                        </div>
                        <div x-show="showExpenseData.chick_purchase">
                            <label class="text-sm font-medium text-gray-500">Related Chick Purchase</label>
                            <p class="text-gray-900" x-text="showExpenseData.chick_purchase ? showExpenseData.chick_purchase.breed + ' - ' + showExpenseData.chick_purchase.quantity + ' chicks' : 'None'"></p>
                        </div>
                        <div x-show="showExpenseData.feed">
                            <label class="text-sm font-medium text-gray-500">Related Feed</label>
                            <p class="text-gray-900" x-text="showExpenseData.feed ? showExpenseData.feed.type + ' - ' + showExpenseData.feed.quantity_kg + 'kg' : 'None'"></p>
                        </div>
                        <div x-show="showExpenseData.medicine">
                            <label class="text-sm font-medium text-gray-500">Related Medicine</label>
                            <p class="text-gray-900" x-text="showExpenseData.medicine ? showExpenseData.medicine.name + ' - ' + showExpenseData.medicine.quantity + ' units' : 'None'"></p>
                        </div>
                        <div x-show="showExpenseData.equipment">
                            <label class="text-sm font-medium text-gray-500">Related Equipment</label>
                            <p class="text-gray-900" x-text="showExpenseData.equipment ? showExpenseData.equipment.name + ' - ' + showExpenseData.equipment.quantity + ' units' : 'None'"></p>
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
function expenseManagement() {
    return {
        showCreateModal: false,
        showEditModal: false,
        showShowModal: false,
        editExpenseId: null,
        editExpenseData: {
            category: '',
            amount: '',
            expense_date: '',
            expense_type: '',
            branch_id: '',
            description: '',
            chick_purchase_id: '',
            feed_id: '',
            medicine_id: '',
            equipment_id: ''
        },
        showExpenseData: {
            category: '',
            amount: '',
            expense_date: '',
            expense_type: '',
            branch: null,
            description: '',
            chick_purchase: null,
            feed: null,
            medicine: null,
            equipment: null
        },
        openCreateModal() {
            this.showCreateModal = true;
        },
        async openEditModal(expenseId) {
            this.editExpenseId = expenseId;
            try {
                const response = await fetch(`/expenses/${expenseId}/edit`);
                const data = await response.json();
                this.editExpenseData = data;
                this.showEditModal = true;
            } catch (error) {
                console.error('Error fetching expense data:', error);
            }
        },
        async openShowModal(expenseId) {
            try {
                const response = await fetch(`/expenses/${expenseId}`);
                const data = await response.json();
                this.showExpenseData = data;
                this.showShowModal = true;
            } catch (error) {
                console.error('Error fetching expense data:', error);
            }
        }
    }
}
</script>
@endpush
@endsection 