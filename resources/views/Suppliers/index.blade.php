<!-- resources/views/suppliers/index.blade.php -->
@extends('layouts.app')

@section('title', 'Supplier Management')

@section('content')
<div class="container" x-data="supplierManagement()">
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 font-weight-bold text-primary">Suppliers Management</h5>
                <button type="button" class="btn btn-primary" @click="openCreateModal()">
                    <i class="fas fa-plus-circle mr-1"></i> Add New Supplier
                </button>
            </div>
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Name</th>
                            <th>Contact Information</th>
                            <th>Products</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($suppliers as $supplier)
                            <tr>
                                <td>{{ $supplier->name }}</td>
                                <td>{{ $supplier->contact_info }}</td>
                                <td>
                                    <span class="badge badge-info">{{ $supplier->products_count }} Products</span>
                                </td>
                                <td class="text-center">
                                    <button @click="openShowModal('{{ $supplier->id }}')" class="btn btn-sm btn-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button @click="openEditModal('{{ $supplier->id }}')" class="btn btn-sm btn-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" 
                                            onclick="confirmDelete('{{ $supplier->id }}')" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <form id="delete-form-{{ $supplier->id }}" 
                                          action="{{ route('suppliers.destroy', $supplier) }}" 
                                          method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <i class="fas fa-box-open fa-2x mb-3 d-block"></i>
                                    No suppliers found. Click "Add New Supplier" to create one.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create Supplier Modal -->
    <div class="modal fade" id="createSupplierModal" tabindex="-1" role="dialog" aria-labelledby="createSupplierModalLabel" aria-hidden="true" x-show="showCreateModal" style="display: none;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('suppliers.store') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-light">
                        <h5 class="modal-title font-weight-bold text-primary" id="createSupplierModalLabel">
                            <i class="fas fa-plus-circle mr-2"></i>Add New Supplier
                        </h5>
                        <button type="button" class="close" @click="showCreateModal = false" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name" class="font-weight-bold">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" 
                                   placeholder="Enter supplier name" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="contact_info" class="font-weight-bold">Contact Information <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('contact_info') is-invalid @enderror" 
                                     id="contact_info" name="contact_info" rows="3" 
                                     placeholder="Enter phone, email, address, etc." required>{{ old('contact_info') }}</textarea>
                            @error('contact_info')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="type" class="font-weight-bold">Supplier Type <span class="text-danger">*</span></label>
                            <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="">Select Type</option>
                                <option value="local">Local</option>
                                <option value="international">International</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" @click="showCreateModal = false">
                            <i class="fas fa-times mr-1"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Supplier Modal -->
    <div class="modal fade" id="editSupplierModal" tabindex="-1" role="dialog" aria-labelledby="editSupplierModalLabel" aria-hidden="true" x-show="showEditModal" style="display: none;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form :action="'/suppliers/' + editSupplierId" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-light">
                        <h5 class="modal-title font-weight-bold text-primary" id="editSupplierModalLabel">
                            <i class="fas fa-edit mr-2"></i>Edit Supplier
                        </h5>
                        <button type="button" class="close" @click="showEditModal = false" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_name" class="font-weight-bold">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_name" name="name" 
                                   x-model="editSupplierData.name" placeholder="Enter supplier name" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_contact_info" class="font-weight-bold">Contact Information <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="edit_contact_info" name="contact_info" rows="3" 
                                     x-model="editSupplierData.contact_info" placeholder="Enter phone, email, address, etc." required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="edit_type" class="font-weight-bold">Supplier Type <span class="text-danger">*</span></label>
                            <select class="form-control" id="edit_type" name="type" x-model="editSupplierData.type" required>
                                <option value="">Select Type</option>
                                <option value="local">Local</option>
                                <option value="international">International</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" @click="showEditModal = false">
                            <i class="fas fa-times mr-1"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Show Supplier Modal -->
    <div class="modal fade" id="showSupplierModal" tabindex="-1" role="dialog" aria-labelledby="showSupplierModalLabel" aria-hidden="true" x-show="showShowModal" style="display: none;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title font-weight-bold text-primary" id="showSupplierModalLabel">
                        <i class="fas fa-info-circle mr-2"></i>Supplier Details
                    </h5>
                    <button type="button" class="close" @click="showShowModal = false" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card border-0">
                        <div class="card-body p-0">
                            <div class="form-row mb-3">
                                <div class="col-md-4">
                                    <label class="font-weight-bold text-muted">Name:</label>
                                </div>
                                <div class="col-md-8">
                                    <p class="mb-0" x-text="showSupplierData.name"></p>
                                </div>
                            </div>
                            <div class="form-row mb-3">
                                <div class="col-md-4">
                                    <label class="font-weight-bold text-muted">Contact Info:</label>
                                </div>
                                <div class="col-md-8">
                                    <p class="mb-0" x-text="showSupplierData.contact_info"></p>
                                </div>
                            </div>
                            <div class="form-row mb-3">
                                <div class="col-md-4">
                                    <label class="font-weight-bold text-muted">Type:</label>
                                </div>
                                <div class="col-md-8">
                                    <p class="mb-0" x-text="showSupplierData.type"></p>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-4">
                                    <label class="font-weight-bold text-muted">Products:</label>
                                </div>
                                <div class="col-md-8">
                                    <span class="badge badge-info" x-text="showSupplierData.products_count + ' Products'"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" @click="showShowModal = false">
                        <i class="fas fa-times mr-1"></i> Close
                    </button>
                    <button type="button" class="btn btn-primary" @click="openEditModal(showSupplierData.id)">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function supplierManagement() {
    return {
        showCreateModal: false,
        showEditModal: false,
        showShowModal: false,
        editSupplierId: null,
        editSupplierData: {
            name: '',
            contact_info: '',
            type: ''
        },
        showSupplierData: {
            id: null,
            name: '',
            contact_info: '',
            type: '',
            products_count: 0
        },
        openCreateModal() {
            this.showCreateModal = true;
            $('#createSupplierModal').modal('show');
        },
        async openEditModal(supplierId) {
            this.editSupplierId = supplierId;
            try {
                const response = await fetch(`/suppliers/${supplierId}/edit`);
                const data = await response.json();
                this.editSupplierData = data;
                this.showEditModal = true;
                $('#showSupplierModal').modal('hide');
                setTimeout(() => {
                    $('#editSupplierModal').modal('show');
                }, 500);
            } catch (error) {
                console.error('Error fetching supplier data:', error);
            }
        },
        async openShowModal(supplierId) {
            try {
                const response = await fetch(`/suppliers/${supplierId}`);
                const data = await response.json();
                this.showSupplierData = data;
                this.showShowModal = true;
                $('#showSupplierModal').modal('show');
            } catch (error) {
                console.error('Error fetching supplier data:', error);
            }
        }
    }
}

function confirmDelete(supplierId) {
    if (confirm('Are you sure you want to delete this supplier?')) {
        document.getElementById('delete-form-' + supplierId).submit();
    }
}
</script>
@endpush
@endsection