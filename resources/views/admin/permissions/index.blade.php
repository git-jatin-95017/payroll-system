@extends('layouts.new_layout')

@section('content')
<div>
    <div class="page-heading d-flex justify-content-between align-items-center gap-3 mb-3">
        <div>
            <h3>Permission Management</h3>
            <p class="mb-0">Manage roles and permissions for your system</p>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {{ session('error') }}
        </div>
    @endif

    <!-- Action Buttons -->
    {{-- <div class="d-flex gap-3 align-items-center justify-content-between mb-4">
        <div class="d-flex gap-3">
            <a href="{{ route('admin.permissions.create-role') }}" class="d-flex justify-content-center gap-2 primary-add">
                <x-heroicon-o-plus width="16" />
                <span>Create Role</span>
            </a>
            <a href="{{ route('admin.permissions.create-permission') }}" class="d-flex justify-content-center gap-2" style="background: #28a745; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none;">
                <x-heroicon-o-plus width="16" />
                <span>Create Permission</span>
            </a>
        </div>
    </div> --}}

    <!-- Roles Section -->
    <!-- <div class="bg-white p-4 mb-4">
        <h5 class="mb-3">
            <x-bx-user-check class="w-20 h-20" /> Roles Management
        </h5>
        @if($roles->count() > 0)
            <div class="table-responsive">
                <table class="table db-custom-table">
                    <thead>
                        <tr>
                            <th>Role Name</th>
                            <th>Description</th>
                            <th>Permissions Count</th>
                            <th>Users Count</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $role)
                            <tr>
                                <td>
                                    <strong>{{ $role->name }}</strong>
                                    <br>
                                    <small class="text-muted">ID: {{ $role->id }} | {{ $role->slug }}</small>
                                </td>
                                <td>{{ $role->description ?? 'No description' }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $role->permissions->count() }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $role->users->count() }}</span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn action-dropdown-toggle dropdown-toggle" type="button" id="dropdownMenuButton{{$role->id}}" data-bs-toggle="dropdown" aria-expanded="false">
                                            <x-bx-dots-horizontal-rounded class="w-20 h-20" />
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{$role->id}}">
                                            <li>
                                                <a href="{{ route('admin.permissions.edit-role', $role) }}" class="dropdown-item">
                                                    <x-bx-edit-alt class="w-16 h-16" /> Edit Role
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.permissions.role-users', $role) }}" class="dropdown-item">
                                                    <x-bx-user class="w-16 h-16" /> Manage Users
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" data-href="{{ route('admin.permissions.destroy-role', $role) }}" class="dropdown-item delete" style="color:#dc3545;">
                                                    <x-heroicon-o-trash class="w-16 h-16" /> Delete Role
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-4">
                <x-bx-user-check class="w-20 h-20 text-muted mb-3" style="font-size: 3rem;" />
                <h5 class="text-muted">No roles found</h5>
                <p class="text-muted">Create your first role to get started.</p>
                <a href="{{ route('admin.permissions.create-role') }}" class="d-flex justify-content-center gap-2 primary-add" style="display: inline-flex !important;">
                    <x-heroicon-o-plus width="16" />
                    <span>Create Role</span>
                </a>
            </div>
        @endif
    </div> -->

    <!-- Role Permissions Assignment Section -->
    <div class="bg-white rounded-3 shadow-sm border-0">
        <div class="p-4 border-bottom bg-gradient-purple text-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="mb-1 fw-bold text-white" style="font-size: 1.1rem;">
                        <x-bx-key class="w-16 h-16 text-white me-2" /> Permission Assignment
                    </h5>
                    <p class="text-white-50 mb-0" style="font-size: 0.8rem;">Manage role-based access control for your system</p>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="text-end">
                        <div class="fw-bold text-white" style="font-size: 0.9rem;">{{ $roles->count() }} Roles</div>
                        <small class="text-white-50" style="font-size: 0.75rem;">{{ $permissions->flatten()->count() }} Permissions</small>
                    </div>
                </div>
            </div>
        </div>
        
        @if($roles->count() > 0 && $permissions->count() > 0)
            <form id="permission-assignment-form" method="POST" action="{{ route('admin.permissions.assign-permissions') }}">
                @csrf
                <div class="p-4">
                    <div class="row g-4">
                        @foreach($roles as $role)
                            <div class="col-lg-3 col-md-6">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-header bg-gradient-purple text-white border-0">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <h6 class="mb-1 fw-bold" style="font-size: 0.95rem;">
                                                   {{ $role->name }}
                                                </h6>
                                                <small class="" style="font-size: 0.7rem;">ID: {{ $role->id }}</small>
                                            </div>
                                            <div class="text-end">
                                                <div class="badge bg-opacity-20 text-white" style="font-size: 0.7rem;">
                                                    {{ $role->permissions->count() }}/{{ $permissions->flatten()->count() }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="permission-modules" style="max-height: 500px; overflow-y: auto;">
                                            @foreach($permissions as $module => $modulePermissions)
                                                <div class="module-group mb-3">
                                                    <div class="d-flex align-items-center mb-2 p-2 rounded" style="background: #f8f9fa;">
                                                        <input type="checkbox" 
                                                               class="form-check-input module-checkbox me-3" 
                                                               data-module="{{ $module }}"
                                                               data-role="{{ $role->id }}"
                                                               id="module_{{ $role->id }}_{{ $module }}"
                                                               {{ $role->permissions->where('module', $module)->count() == $modulePermissions->count() ? 'checked' : '' }}>
                                                        <label class="form-check-label fw-semibold text-dark flex-grow-1" for="module_{{ $role->id }}_{{ $module }}" style="font-size: 0.8rem;">
                                                            {{ ucfirst(str_replace('-', ' ', $module)) }}
                                                        </label>
                                                        <span class="badge bg-primary bg-opacity-10 text-primary" style="font-size: 0.65rem;">{{ $modulePermissions->count() }}</span>
                                                    </div>
                                                    
                                                    <div class="permissions-list ms-3">
                                                        @foreach($modulePermissions as $permission)
                                                            <div class="form-check mb-2">
                                                                <input type="checkbox" 
                                                                       class="form-check-input permission-checkbox" 
                                                                       name="permissions[{{ $role->id }}][]"
                                                                       value="{{ $permission->id }}"
                                                                       data-module="{{ $module }}"
                                                                       data-role="{{ $role->id }}"
                                                                       id="permission_{{ $role->id }}_{{ $permission->id }}"
                                                                       {{ $role->permissions->contains($permission->id) ? 'checked' : '' }}>
                                                                <label class="form-check-label small text-muted" for="permission_{{ $role->id }}_{{ $permission->id }}" style="font-size: 0.7rem;">
                                                                    {{ $permission->name }}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="p-4 border-top bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 text-dark" style="font-size: 0.9rem;">Save Changes</h6>
                            <small class="text-muted" style="font-size: 0.75rem;">All permission assignments will be updated</small>
                        </div>
                        <button type="submit" class="btn btn-purple btn-lg px-4 py-2 d-flex align-items-center gap-2">
                            <x-bx-save class="text-white w-16 h-16" />
                            <span class="text-white" style="font-size: 0.95rem;">Save Assignments</span>
                        </button>
                    </div>
                </div>
            </form>
        @else
            <div class="text-center py-5">
                <div class="mb-4">
                    <x-bx-key class="w-20 h-20 text-muted" style="font-size: 4rem; opacity: 0.3;" />
                </div>
                <h5 class="text-muted mb-2">No Data Available</h5>
                <p class="text-muted">Please ensure roles and permissions are properly configured.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('page_css')
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    }
    
    .bg-gradient-purple {
        background: #1f1c71 !important;
    }
    
    .card {
        transition: all 0.3s ease;
        border-radius: 12px !important;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    }
    
    .module-group {
        border-left: 3px solid #e9ecef;
        transition: all 0.2s ease;
    }
    
    .module-group:hover {
        border-left-color: #007bff;
        background: rgba(0,123,255,0.02);
        border-radius: 6px;
        padding: 8px;
        margin: -8px;
    }
    
    .form-check-input:checked {
        background-color: #007bff;
        border-color: #007bff;
    }
    
    .form-check-input:focus {
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
    }
    
    .permission-modules::-webkit-scrollbar {
        width: 6px;
    }
    
    .permission-modules::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    
    .permission-modules::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }
    
    .permission-modules::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
    
    .badge {
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        border: none;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(0,123,255,0.3);
    }
    
    .btn-purple {
        background: #1f1c71 !important;
        border: none;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        color: white;
        font-size: 0.875rem;
    }
    
    .btn-purple:hover {
        background: #1a1859 !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(31,28,113,0.3);
        color: white;
    }
    
    .text-primary {
        color: #1f1c71 !important;
    }
    
    .shadow-sm {
        box-shadow: 0 2px 10px rgba(0,0,0,0.08) !important;
    }
    
    .border-0 {
        border: none !important;
    }
    
    .rounded-3 {
        border-radius: 12px !important;
    }
</style>
@endpush

@push('page_scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);

        // Delete functionality
        $(document).on('click','a.delete',function(){
            var trashRecordUrl = $(this).data('href');
            moveToDelete(trashRecordUrl);
        });

        // Move to Delete single record by just pass the url of 
        function moveToDelete(trashRecordUrl) {
            Swal.fire({
                text: "You Want to Delete?",
                showCancelButton: true,
                confirmButtonText: '<i class="ik trash-2 ik-trash-2"></i> Permanent Delete!',
                cancelButtonText: 'Not Now!',
                reverseButtons: true,
                showCloseButton : true,
                allowOutsideClick:false,
            }).then((result)=>{
                var action = 'delete';
                if(result.value == true){
                    $.ajax({
                        url: trashRecordUrl,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        dataType:'JSON',
                        success:(result)=>{
                            location.reload();
                        }
                    });
                }
            });
        }

        // Module checkbox functionality
        $(document).on('change', '.module-checkbox', function() {
            var isChecked = $(this).is(':checked');
            var module = $(this).data('module');
            var roleId = $(this).data('role');
            
            // Check/uncheck all permissions in this module for this role
            $('.permission-checkbox[data-module="' + module + '"][data-role="' + roleId + '"]').prop('checked', isChecked);
        });

        // Permission checkbox functionality
        $(document).on('change', '.permission-checkbox', function() {
            var module = $(this).data('module');
            var roleId = $(this).data('role');
            
            // Check if all permissions in this module are checked
            var totalPermissions = $('.permission-checkbox[data-module="' + module + '"][data-role="' + roleId + '"]').length;
            var checkedPermissions = $('.permission-checkbox[data-module="' + module + '"][data-role="' + roleId + '"]:checked').length;
            
            // Update module checkbox
            var moduleCheckbox = $('#module_' + roleId + '_' + module);
            if (checkedPermissions === totalPermissions) {
                moduleCheckbox.prop('checked', true);
            } else {
                moduleCheckbox.prop('checked', false);
            }
        });

        // Form submission
        $('#permission-assignment-form').on('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Save Permission Assignments?',
                text: 'This will update all role permissions. Continue?',
                showCancelButton: true,
                confirmButtonText: 'Yes, Save!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: $(this).attr('action'),
                        type: 'POST',
                        data: $(this).serialize(),
                        success: function(response) {
                            Swal.fire('Success!', 'Permission assignments saved successfully!', 'success');
                        },
                        error: function(xhr) {
                            Swal.fire('Error!', 'Failed to save permission assignments.', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
