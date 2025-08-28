<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class PermissionHelper
{
    /**
     * Check if current user has a specific permission
     */
    public static function hasPermission($permissionSlug)
    {
        if (!Auth::check()) {
            return false;
        }
        
        return Auth::user()->hasPermission($permissionSlug);
    }

    /**
     * Check if current user has any permission from a module
     */
    public static function hasModulePermission($module)
    {
        if (!Auth::check()) {
            return false;
        }
        
        return Auth::user()->hasModulePermission($module);
    }

    /**
     * Get current user's role name
     */
    public static function getUserRole()
    {
        if (!Auth::check() || !Auth::user()->role) {
            return 'No Role';
        }
        
        return Auth::user()->role->name;
    }

    /**
     * Check if current user is admin
     */
    public static function isAdmin()
    {
        if (!Auth::check()) {
            return false;
        }
        
        return Auth::user()->role_id === 1;
    }

    /**
     * Check if current user is subadmin
     */
    public static function isSubadmin()
    {
        if (!Auth::check()) {
            return false;
        }
        
        return Auth::user()->role_id === 4;
    }

    /**
     * Check if current user is client
     */
    public static function isClient()
    {
        if (!Auth::check()) {
            return false;
        }
        
        return Auth::user()->role_id === 2;
    }

    /**
     * Check if current user is employee
     */
    public static function isEmployee()
    {
        if (!Auth::check()) {
            return false;
        }
        
        return Auth::user()->role_id === 3;
    }

    /**
     * Get all permissions for current user's role
     */
    public static function getUserPermissions()
    {
        if (!Auth::check() || !Auth::user()->role) {
            return collect();
        }
        
        return Auth::user()->role->permissions;
    }

    /**
     * Get modules that current user has access to
     */
    public static function getUserModules()
    {
        if (!Auth::check() || !Auth::user()->role) {
            return collect();
        }
        
        return Auth::user()->role->permissions->pluck('module')->unique();
    }
}
