<!-- resources/views/equipment/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>Equipment Inventory</h2>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createEquipmentModal">
                        Add New Equipment
                    </button>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Quantity</th>
                                    <th>Cost</th>
                                    <th>Purchase Date</th>
                                    <th>Status</th>
                                    <th>Supplier</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($equipment as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ number_format($item->cost, 2) }}</td>
                                        <td>{{ $item->purchase_date ? date('M d, Y', strtotime($item->purchase_date)) : 'N/A' }}</td>
                                        <td>
                                            <span class="badge badge-{{ $item->status == 'available' ? 'success' : ($item->status == 'in_use' ? 'primary' : 'secondary') }}">
                                                {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                            </span>
                                        </td>
                                        <td>{{ $item->supplier ? $item->supplier->name : 'N/A' }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-warning edit-equipment" 
                                                        data-id="{{ $item->id }}" 
                                                        data-name="{{ $item->name }}"
                                                        data-quantity="{{ $item->quantity }}"
                                                        data-cost="{{ $item->cost }}"
                                                        data-purchase-date="{{ $item->purchase_date }}"
                                                        data-status="{{ $item->status }}"
                                                        data-supplier-id="{{ $item->supplier_id }}"
                                                        data-toggle="modal" 
                                                        data-target="#editEquipmentModal">
                                                    Edit
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger delete-equipment"
                                                        data-id="{{ $item->id }}"
                                                        data-name="{{ $item->name }}"
                                                        data-toggle="modal"
                                                        data-target="#deleteEquipmentModal">
                                                    Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                
                                @if(count($equipment) == 0)
                                    <tr>
                                        <td colspan="8" class="text-center">No equipment records found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Equipment Modal -->
<div class="modal fade" id="createEquipmentModal" tabindex="-1" role="dialog" aria-labelledby="createEquipmentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('equipments.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createEquipmentModalLabel">Add New Equipment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" required>
                    </div>
                    <div class="form-group">
                        <label for="cost">Cost</label>
                        <input type="number" step="0.01" class="form-control" id="cost" name="cost" required>
                    </div>
                    <div class="form-group">
                        <label for="purchase_date">Purchase Date</label>
                        <input type="date" class="form-control" id="purchase_date" name="purchase_date" required>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="available">Available</option>
                            <option value="in_use">In Use</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="retired">Retired</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="supplier_id">Supplier</label>
                        <select class="form-control" id="supplier_id" name="supplier_id" required>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Equipment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Equipment Modal -->
<div class="modal fade" id="editEquipmentModal" tabindex="-1" role="dialog" aria-labelledby="editEquipmentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="editEquipmentForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editEquipmentModalLabel">Edit Equipment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_name">Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_quantity">Quantity</label>
                        <input type="number" class="form-control" id="edit_quantity" name="quantity" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_cost">Cost</label>
                        <input type="number" step="0.01" class="form-control" id="edit_cost" name="cost" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_purchase_date">Purchase Date</label>
                        <input type="date" class="form-control" id="edit_purchase_date" name="purchase_date" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_status">Status</label>
                        <select class="form-control" id="edit_status" name="status" required>
                            <option value="available">Available</option>
                            <option value="in_use">In Use</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="retired">Retired</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_supplier_id">Supplier</label>
                        <select class="form-control" id="edit_supplier_id" name="supplier_id" required>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Equipment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Equipment Modal -->
<div class="modal fade" id="deleteEquipmentModal" tabindex="-1" role="dialog" aria-labelledby="deleteEquipmentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="deleteEquipmentForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteEquipmentModalLabel">Confirm Delete</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete the equipment: <span id="delete_equipment_name"></span>?</p>
                    <p class="text-danger">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Equipment</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Edit Equipment
        $('.edit-equipment').click(function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var quantity = $(this).data('quantity');
            var cost = $(this).data('cost');
            var purchaseDate = $(this).data('purchase-date');
            var status = $(this).data('status');
            var supplierId = $(this).data('supplier-id');
            
            $('#editEquipmentForm').attr('action', '/equipment/' + id);
            $('#edit_name').val(name);
            $('#edit_quantity').val(quantity);
            $('#edit_cost').val(cost);
            $('#edit_purchase_date').val(purchaseDate);
            $('#edit_status').val(status);
            $('#edit_supplier_id').val(supplierId);
        });
        
        // Delete Equipment
        $('.delete-equipment').click(function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            
            $('#deleteEquipmentForm').attr('action', '/equipment/' + id);
            $('#delete_equipment_name').text(name);
        });
    });
</script>
@endsection