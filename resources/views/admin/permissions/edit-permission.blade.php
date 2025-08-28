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
                        <li class="breadcrumb-item active">Edit Permission</li>
                    </ol>
                </div>
                <h4 class="page-title">Edit Permission: {{ $permission->name }}</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit"></i> Permission Information
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.permissions.update-permission', $permission) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Permission Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $permission->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Example: View Dashboard, Create Employee, Edit Payroll</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="module" class="form-label">Module <span class="text-danger">*</span></label>
                                    <select class="form-select @error('module') is-invalid @enderror" 
                                            id="module" name="module" required>
                                        <option value="">Select Module</option>
                                        @foreach($modules as $module)
                                            <option value="{{ $module }}" 
                                                    {{ old('module', $permission->module) == $module ? 'selected' : '' }}>
                                                {{ ucfirst($module) }}
                                            </option>
                                        @endforeach
                                        <option value="custom" {{ old('module') == 'custom' ? 'selected' : '' }}>
                                            Custom (Enter below)
                                        </option>
                                    </select>
                                    @error('module')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row" id="custom-module-row" style="display: none;">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="custom_module" class="form-label">Custom Module Name</label>
                                    <input type="text" class="form-control @error('custom_module') is-invalid @enderror" 
                                           id="custom_module" name="custom_module" value="{{ old('custom_module') }}">
                                    @error('custom_module')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3">{{ old('description', $permission->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Describe what this permission allows users to do.</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Permission
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

    <!-- Permission Usage Info -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle"></i> Permission Usage
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Assigned to Roles</h6>
                            @if($permission->roles->count() > 0)
                                <ul class="list-group list-group-flush">
                                    @foreach($permission->roles as $role)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            {{ $role->name }}
                                            <span class="badge bg-primary rounded-pill">{{ $role->users->count() }} users</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted">This permission is not assigned to any roles.</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h6>Permission Details</h6>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Slug:</span>
                                    <code>{{ $permission->slug }}</code>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Module:</span>
                                    <span class="badge bg-info">{{ ucfirst($permission->module) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Status:</span>
                                    <span class="badge {{ $permission->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $permission->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Created:</span>
                                    <small class="text-muted">{{ $permission->created_at->format('M d, Y') }}</small>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page_scripts')
<script>
    $(document).ready(function() {
        // Show/hide custom module input
        $('#module').on('change', function() {
            if ($(this).val() === 'custom') {
                $('#custom-module-row').show();
                $('#custom_module').prop('required', true);
            } else {
                $('#custom-module-row').hide();
                $('#custom_module').prop('required', false);
            }
        });

        // Auto-generate slug from name
        $('#name').on('input', function() {
            const name = $(this).val();
            const slug = name.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim('-');
            
            // You can show the generated slug to the user if needed
            // $('#slug-preview').text(slug);
        });
    });
</script>
@endpush
