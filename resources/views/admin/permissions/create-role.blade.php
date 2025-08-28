@extends('layouts.new_layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.permissions.index') }}">Permission Management</a></li>
                        <li class="breadcrumb-item active">Create Role</li>
                    </ol>
                </div>
                <h4 class="page-title">Create New Role</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-plus-circle"></i> Role Information
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.permissions.store-role') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Role Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>

                        <h5 class="mb-3">
                            <i class="fas fa-key"></i> Assign Permissions
                        </h5>

                        @if($permissions->count() > 0)
                            @foreach($permissions as $module => $modulePermissions)
                                <div class="mb-4">
                                    <div class="card border">
                                        <div class="card-header bg-light">
                                            <div class="form-check">
                                                <input class="form-check-input module-checkbox" 
                                                       type="checkbox" 
                                                       id="module_{{ $module }}" 
                                                       data-module="{{ $module }}">
                                                <label class="form-check-label fw-bold" for="module_{{ $module }}">
                                                    <i class="fas fa-folder"></i> {{ ucfirst($module) }} Module
                                                    <span class="badge bg-primary ms-2">{{ $modulePermissions->count() }}</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                @foreach($modulePermissions as $permission)
                                                    <div class="col-md-4 col-lg-3 mb-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input permission-checkbox" 
                                                                   type="checkbox" 
                                                                   name="permissions[]" 
                                                                   value="{{ $permission->id }}" 
                                                                   id="permission_{{ $permission->id }}"
                                                                   data-module="{{ $module }}"
                                                                   {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                                {{ $permission->name }}
                                                                <br>
                                                                <small class="text-muted">{{ $permission->description ?? 'No description' }}</small>
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                No permissions found. Please create permissions first.
                                <a href="{{ route('admin.permissions.create-permission') }}" class="btn btn-sm btn-warning ms-2">
                                    Create Permission
                                </a>
                            </div>
                        @endif

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Create Role
                                    </button>
                                    <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page_scripts')
<script>
    $(document).ready(function() {
        // Module checkbox functionality
        $('.module-checkbox').on('change', function() {
            const module = $(this).data('module');
            const isChecked = $(this).is(':checked');
            
            // Check/uncheck all permissions in this module
            $(`.permission-checkbox[data-module="${module}"]`).prop('checked', isChecked);
        });

        // Permission checkbox functionality
        $('.permission-checkbox').on('change', function() {
            const module = $(this).data('module');
            const moduleCheckbox = $(`#module_${module}`);
            const modulePermissions = $(`.permission-checkbox[data-module="${module}"]`);
            const checkedPermissions = modulePermissions.filter(':checked');
            
            // Update module checkbox state
            if (checkedPermissions.length === 0) {
                moduleCheckbox.prop('checked', false).prop('indeterminate', false);
            } else if (checkedPermissions.length === modulePermissions.length) {
                moduleCheckbox.prop('checked', true).prop('indeterminate', false);
            } else {
                moduleCheckbox.prop('checked', false).prop('indeterminate', true);
            }
        });

        // Initialize module checkbox states on page load
        $('.module-checkbox').each(function() {
            const module = $(this).data('module');
            const modulePermissions = $(`.permission-checkbox[data-module="${module}"]`);
            const checkedPermissions = modulePermissions.filter(':checked');
            
            if (checkedPermissions.length === 0) {
                $(this).prop('checked', false).prop('indeterminate', false);
            } else if (checkedPermissions.length === modulePermissions.length) {
                $(this).prop('checked', true).prop('indeterminate', false);
            } else {
                $(this).prop('checked', false).prop('indeterminate', true);
            }
        });
    });
</script>
@endpush
