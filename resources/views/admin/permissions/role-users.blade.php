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
                        <li class="breadcrumb-item active">Role Users</li>
                    </ol>
                </div>
                <h4 class="page-title">Users with Role: {{ $role->name }}</h4>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users"></i> Users with {{ $role->name }} Role
                        <span class="badge bg-primary ms-2">{{ $users->count() }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    @if($users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Email</th>
                                        <th>Profile</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                                        <span class="text-white fw-bold">{{ substr($user->name, 0, 1) }}</span>
                                                    </div>
                                                    <div>
                                                        <strong>{{ $user->name }}</strong>
                                                        <br>
                                                        <small class="text-muted">ID: {{ $user->id }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @if($user->employeeProfile)
                                                    <span class="badge bg-info">Employee</span>
                                                @elseif($user->companyProfile)
                                                    <span class="badge bg-success">Company</span>
                                                @else
                                                    <span class="badge bg-secondary">Admin</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $user->status ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $user->status ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <form action="{{ route('admin.permissions.remove-role', $user) }}" 
                                                      method="POST" class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to remove this role from {{ $user->name }}?')">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-user-times"></i> Remove Role
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No users assigned to this role</h5>
                            <p class="text-muted">This role is not currently assigned to any users.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Assign Role to User -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-plus"></i> Assign Role to User
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.permissions.assign-role') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="user_id" class="form-label">Select User</label>
                                    <select class="form-select @error('user_id') is-invalid @enderror" 
                                            id="user_id" name="user_id" required>
                                        <option value="">Choose a user...</option>
                                        @php
                                            $usersWithoutRole = \App\Models\User::whereNull('role_id')
                                                ->orWhere('role_id', '!=', $role->id)
                                                ->get();
                                        @endphp
                                        @foreach($usersWithoutRole as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                                @if($user->role)
                                                    - Current: {{ $user->role->name }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="role_id" class="form-label">Role</label>
                                    <input type="hidden" name="role_id" value="{{ $role->id }}">
                                    <input type="text" class="form-control" value="{{ $role->name }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-user-plus"></i> Assign Role
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Role Permissions Summary -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-key"></i> Role Permissions Summary
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Role Information</h6>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Role Name:</span>
                                    <strong>{{ $role->name }}</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Slug:</span>
                                    <code>{{ $role->slug }}</code>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Description:</span>
                                    <span>{{ $role->description ?? 'No description' }}</span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Permissions ({{ $role->permissions->count() }})</h6>
                            @if($role->permissions->count() > 0)
                                <div class="permissions-list" style="max-height: 200px; overflow-y: auto;">
                                    @foreach($role->permissions->groupBy('module') as $module => $permissions)
                                        <div class="mb-2">
                                            <strong class="text-primary">{{ ucfirst($module) }}:</strong>
                                            <div class="ms-3">
                                                @foreach($permissions as $permission)
                                                    <span class="badge bg-light text-dark me-1 mb-1">{{ $permission->name }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted">No permissions assigned to this role.</p>
                            @endif
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
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
</script>
@endpush
