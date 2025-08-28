<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    /**
     * Display the permission management dashboard.
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::get()->groupBy('module');
        $modules = Permission::select('module')->distinct()->pluck('module');
        
        return view('admin.permissions.index', compact('roles', 'permissions', 'modules'));
    }

    /**
     * Show the form for creating a new role.
     */
    public function createRole()
    {
        $permissions = Permission::get()->groupBy('module');
        $modules = Permission::select('module')->distinct()->pluck('module');
        
        return view('admin.permissions.create-role', compact('permissions', 'modules'));
    }

    /**
     * Store a newly created role.
     */
    public function storeRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role = Role::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'is_active' => true
        ]);

        if ($request->has('permissions')) {
            $role->permissions()->attach($request->permissions);
        }

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Role created successfully!');
    }

    /**
     * Show the form for editing a role.
     */
    public function editRole(Role $role)
    {
        $permissions = Permission::get()->groupBy('module');
        $modules = Permission::select('module')->distinct()->pluck('module');
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        
        return view('admin.permissions.edit-role', compact('role', 'permissions', 'modules', 'rolePermissions'));
    }

    /**
     * Update the specified role.
     */
    public function updateRole(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|string',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description
        ]);

        // Sync permissions
        $role->permissions()->sync($request->permissions ?? []);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Role updated successfully!');
    }

    /**
     * Remove the specified role.
     */
    public function destroyRole(Role $role)
    {
        // Check if role is being used by any users
        if ($role->users()->count() > 0) {
            return redirect()->route('admin.permissions.index')
                ->with('error', 'Cannot delete role. It is assigned to ' . $role->users()->count() . ' user(s).');
        }

        $role->permissions()->detach();
        $role->delete();

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Role deleted successfully!');
    }

    /**
     * Show the form for creating a new permission.
     */
    public function createPermission()
    {
        $modules = Permission::select('module')->distinct()->pluck('module');
        
        return view('admin.permissions.create-permission', compact('modules'));
    }

    /**
     * Store a newly created permission.
     */
    public function storePermission(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'module' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        Permission::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'module' => $request->module,
            'description' => $request->description,
            'is_active' => true
        ]);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission created successfully!');
    }

    /**
     * Show the form for editing a permission.
     */
    public function editPermission(Permission $permission)
    {
        $modules = Permission::select('module')->distinct()->pluck('module');
        
        return view('admin.permissions.edit-permission', compact('permission', 'modules'));
    }

    /**
     * Update the specified permission.
     */
    public function updatePermission(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'module' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $permission->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'module' => $request->module,
            'description' => $request->description
        ]);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission updated successfully!');
    }

    /**
     * Remove the specified permission.
     */
    public function destroyPermission(Permission $permission)
    {
        // Check if permission is assigned to any roles
        if ($permission->roles()->count() > 0) {
            return redirect()->route('admin.permissions.index')
                ->with('error', 'Cannot delete permission. It is assigned to ' . $permission->roles()->count() . ' role(s).');
        }

        $permission->delete();

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission deleted successfully!');
    }

    /**
     * Show users assigned to a specific role.
     */
    public function roleUsers(Role $role)
    {
        $users = $role->users()->with('employeeProfile')->get();
        
        return view('admin.permissions.role-users', compact('role', 'users'));
    }

    /**
     * Assign role to user.
     */
    public function assignRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id'
        ]);

        $user = User::findOrFail($request->user_id);
        $user->update(['role_id' => $request->role_id]);

        return redirect()->back()
            ->with('success', 'Role assigned to user successfully!');
    }

    /**
     * Remove role from user.
     */
    public function removeRole(User $user)
    {
        $user->update(['role_id' => null]);

        return redirect()->back()
            ->with('success', 'Role removed from user successfully!');
    }

    /**
     * Assign permissions to roles.
     */
    public function assignPermissions(Request $request)
    {
        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'array',
            'permissions.*.*' => 'exists:permissions,id'
        ]);

        try {
            // Get all roles
            $roles = Role::all();
            
            foreach ($roles as $role) {
                $permissionIds = $request->input('permissions.' . $role->id, []);
                $role->permissions()->sync($permissionIds);
            }

            return response()->json([
                'success' => true,
                'message' => 'Permission assignments saved successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save permission assignments: ' . $e->getMessage()
            ], 500);
        }
    }
}