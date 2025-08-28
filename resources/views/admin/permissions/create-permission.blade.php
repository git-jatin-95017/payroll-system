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
                        <li class="breadcrumb-item active">Create Permission</li>
                    </ol>
                </div>
                <h4 class="page-title">Create New Permission</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-plus-circle"></i> Permission Information
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.permissions.store-permission') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Permission Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
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
                                            <option value="{{ $module }}" {{ old('module') == $module ? 'selected' : '' }}>
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
                                              id="description" name="description" rows="3">{{ old('description') }}</textarea>
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
                                        <i class="fas fa-save"></i> Create Permission
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

    <!-- Available Modules Info -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle"></i> Available Modules
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <h6>Dashboard</h6>
                            <ul class="list-unstyled">
                                <li><small class="text-muted">• View Dashboard</small></li>
                                <li><small class="text-muted">• Manage Dashboard</small></li>
                            </ul>
                        </div>
                        <div class="col-md-3">
                            <h6>Employees</h6>
                            <ul class="list-unstyled">
                                <li><small class="text-muted">• View Employees</small></li>
                                <li><small class="text-muted">• Create Employee</small></li>
                                <li><small class="text-muted">• Edit Employee</small></li>
                                <li><small class="text-muted">• Delete Employee</small></li>
                            </ul>
                        </div>
                        <div class="col-md-3">
                            <h6>Payroll</h6>
                            <ul class="list-unstyled">
                                <li><small class="text-muted">• View Payroll</small></li>
                                <li><small class="text-muted">• Create Payroll</small></li>
                                <li><small class="text-muted">• Process Payroll</small></li>
                                <li><small class="text-muted">• Approve Payroll</small></li>
                            </ul>
                        </div>
                        <div class="col-md-3">
                            <h6>Reports</h6>
                            <ul class="list-unstyled">
                                <li><small class="text-muted">• View Reports</small></li>
                                <li><small class="text-muted">• Export Reports</small></li>
                                <li><small class="text-muted">• Generate Reports</small></li>
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
