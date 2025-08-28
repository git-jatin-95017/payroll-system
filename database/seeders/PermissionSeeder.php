<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create comprehensive permissions for all modules in the system
        $permissions = [
            // Dashboard
            ['name' => 'View Dashboard', 'slug' => 'view-dashboard', 'module' => 'dashboard', 'description' => 'Access to view the dashboard'],
            ['name' => 'Manage Dashboard', 'slug' => 'manage-dashboard', 'module' => 'dashboard', 'description' => 'Manage dashboard settings and widgets'],
            
            // Admin Dashboard
            ['name' => 'View Admin Dashboard', 'slug' => 'view-admin-dashboard', 'module' => 'admin-dashboard', 'description' => 'Access to admin dashboard'],
            ['name' => 'Manage Admin Dashboard', 'slug' => 'manage-admin-dashboard', 'module' => 'admin-dashboard', 'description' => 'Manage admin dashboard settings'],
            
            // Employees
            ['name' => 'View Employees', 'slug' => 'view-employees', 'module' => 'employees', 'description' => 'View employee list and details'],
            ['name' => 'Create Employee', 'slug' => 'create-employee', 'module' => 'employees', 'description' => 'Add new employees to the system'],
            ['name' => 'Edit Employee', 'slug' => 'edit-employee', 'module' => 'employees', 'description' => 'Edit employee information'],
            ['name' => 'Delete Employee', 'slug' => 'delete-employee', 'module' => 'employees', 'description' => 'Remove employees from the system'],
            ['name' => 'Export Employee Data', 'slug' => 'export-employee-data', 'module' => 'employees', 'description' => 'Export employee data to Excel/PDF'],
            
            // Payroll
            ['name' => 'View Payroll', 'slug' => 'view-payroll', 'module' => 'payroll', 'description' => 'View payroll information'],
            ['name' => 'Create Payroll', 'slug' => 'create-payroll', 'module' => 'payroll', 'description' => 'Create new payroll runs'],
            ['name' => 'Process Payroll', 'slug' => 'process-payroll', 'module' => 'payroll', 'description' => 'Process and calculate payroll'],
            ['name' => 'Approve Payroll', 'slug' => 'approve-payroll', 'module' => 'payroll', 'description' => 'Approve payroll for payment'],
            ['name' => 'Delete Payroll', 'slug' => 'delete-payroll', 'module' => 'payroll', 'description' => 'Delete payroll runs'],
            ['name' => 'Download Payroll PDF', 'slug' => 'download-payroll-pdf', 'module' => 'payroll', 'description' => 'Download payroll reports as PDF'],
            
            // Run Payroll (Admin)
            ['name' => 'View Run Payroll', 'slug' => 'view-run-payroll', 'module' => 'run-payroll', 'description' => 'View run payroll interface'],
            ['name' => 'Step 1 Payroll', 'slug' => 'step1-payroll', 'module' => 'run-payroll', 'description' => 'Access to payroll step 1'],
            ['name' => 'Step 2 Payroll', 'slug' => 'step2-payroll', 'module' => 'run-payroll', 'description' => 'Access to payroll step 2'],
            ['name' => 'Confirm Payroll', 'slug' => 'confirm-payroll', 'module' => 'run-payroll', 'description' => 'Confirm payroll processing'],
            
            // Reports
            ['name' => 'View Reports', 'slug' => 'view-reports', 'module' => 'reports', 'description' => 'Access to view various reports'],
            ['name' => 'Export Reports', 'slug' => 'export-reports', 'module' => 'reports', 'description' => 'Export reports to different formats'],
            ['name' => 'Generate Reports', 'slug' => 'generate-reports', 'module' => 'reports', 'description' => 'Generate custom reports'],
            ['name' => 'Download Report PDF', 'slug' => 'download-report-pdf', 'module' => 'reports', 'description' => 'Download reports as PDF'],
            ['name' => 'Download Report Excel', 'slug' => 'download-report-excel', 'module' => 'reports', 'description' => 'Download reports as Excel'],
            
            // Payroll Reports
            ['name' => 'View Payroll Reports', 'slug' => 'view-payroll-reports', 'module' => 'payroll-reports', 'description' => 'View payroll specific reports'],
            ['name' => 'Payroll Summary Report', 'slug' => 'payroll-summary-report', 'module' => 'payroll-reports', 'description' => 'Access to payroll summary reports'],
            ['name' => 'Payroll Taxes Report', 'slug' => 'payroll-taxes-report', 'module' => 'payroll-reports', 'description' => 'Access to payroll taxes reports'],
            ['name' => 'Employee Earnings Report', 'slug' => 'employee-earnings-report', 'module' => 'payroll-reports', 'description' => 'Access to employee earnings reports'],
            ['name' => 'Employer Payments Report', 'slug' => 'employer-payments-report', 'module' => 'payroll-reports', 'description' => 'Access to employer payments reports'],
            ['name' => 'Statutory Deductions Report', 'slug' => 'statutory-deductions-report', 'module' => 'payroll-reports', 'description' => 'Access to statutory deductions reports'],
            
            // Attendance
            ['name' => 'View Attendance', 'slug' => 'view-attendance', 'module' => 'attendance', 'description' => 'View employee attendance records'],
            ['name' => 'Manage Attendance', 'slug' => 'manage-attendance', 'module' => 'attendance', 'description' => 'Manage and edit attendance records'],
            ['name' => 'Export Attendance', 'slug' => 'export-attendance', 'module' => 'attendance', 'description' => 'Export attendance data'],
            ['name' => 'Attendance Reports', 'slug' => 'attendance-reports', 'module' => 'attendance', 'description' => 'Generate attendance reports'],
            
            // Leaves
            ['name' => 'View Leaves', 'slug' => 'view-leaves', 'module' => 'leaves', 'description' => 'View leave applications and records'],
            ['name' => 'Create Leave', 'slug' => 'create-leave', 'module' => 'leaves', 'description' => 'Create new leave applications'],
            ['name' => 'Edit Leave', 'slug' => 'edit-leave', 'module' => 'leaves', 'description' => 'Edit leave applications'],
            ['name' => 'Approve Leaves', 'slug' => 'approve-leaves', 'module' => 'leaves', 'description' => 'Approve or reject leave applications'],
            ['name' => 'Delete Leave', 'slug' => 'delete-leave', 'module' => 'leaves', 'description' => 'Delete leave applications'],
            
            // Leave Types
            ['name' => 'View Leave Types', 'slug' => 'view-leave-types', 'module' => 'leave-types', 'description' => 'View leave types and policies'],
            ['name' => 'Create Leave Type', 'slug' => 'create-leave-type', 'module' => 'leave-types', 'description' => 'Create new leave types'],
            ['name' => 'Edit Leave Type', 'slug' => 'edit-leave-type', 'module' => 'leave-types', 'description' => 'Edit leave types'],
            ['name' => 'Delete Leave Type', 'slug' => 'delete-leave-type', 'module' => 'leave-types', 'description' => 'Delete leave types'],
            ['name' => 'Assign Leave Policies', 'slug' => 'assign-leave-policies', 'module' => 'leave-types', 'description' => 'Assign leave policies to employees'],
            
            // Holidays
            ['name' => 'View Holidays', 'slug' => 'view-holidays', 'module' => 'holidays', 'description' => 'View holiday calendar'],
            ['name' => 'Create Holiday', 'slug' => 'create-holiday', 'module' => 'holidays', 'description' => 'Add new holidays'],
            ['name' => 'Edit Holiday', 'slug' => 'edit-holiday', 'module' => 'holidays', 'description' => 'Edit holiday information'],
            ['name' => 'Delete Holiday', 'slug' => 'delete-holiday', 'module' => 'holidays', 'description' => 'Delete holidays'],
            
            // Departments
            ['name' => 'View Departments', 'slug' => 'view-departments', 'module' => 'departments', 'description' => 'View department information'],
            ['name' => 'Create Department', 'slug' => 'create-department', 'module' => 'departments', 'description' => 'Create new departments'],
            ['name' => 'Edit Department', 'slug' => 'edit-department', 'module' => 'departments', 'description' => 'Edit department information'],
            ['name' => 'Delete Department', 'slug' => 'delete-department', 'module' => 'departments', 'description' => 'Delete departments'],
            ['name' => 'Assign Locations', 'slug' => 'assign-locations', 'module' => 'departments', 'description' => 'Assign locations to departments'],
            
            // Pay Heads
            ['name' => 'View Pay Heads', 'slug' => 'view-pay-heads', 'module' => 'pay-heads', 'description' => 'View pay head information'],
            ['name' => 'Create Pay Head', 'slug' => 'create-pay-head', 'module' => 'pay-heads', 'description' => 'Create new pay heads'],
            ['name' => 'Edit Pay Head', 'slug' => 'edit-pay-head', 'module' => 'pay-heads', 'description' => 'Edit pay head information'],
            ['name' => 'Delete Pay Head', 'slug' => 'delete-pay-head', 'module' => 'pay-heads', 'description' => 'Delete pay heads'],
            ['name' => 'Assign Pay Heads', 'slug' => 'assign-pay-heads', 'module' => 'pay-heads', 'description' => 'Assign pay heads to employees'],
            
            // Clients
            ['name' => 'View Clients', 'slug' => 'view-clients', 'module' => 'clients', 'description' => 'View client information'],
            ['name' => 'Create Client', 'slug' => 'create-client', 'module' => 'clients', 'description' => 'Add new clients'],
            ['name' => 'Edit Client', 'slug' => 'edit-client', 'module' => 'clients', 'description' => 'Edit client information'],
            ['name' => 'Delete Client', 'slug' => 'delete-client', 'module' => 'clients', 'description' => 'Remove clients'],
            ['name' => 'Login As Client', 'slug' => 'login-as-client', 'module' => 'clients', 'description' => 'Login as client for testing'],
            ['name' => 'Manage Client Admins', 'slug' => 'manage-client-admins', 'module' => 'clients', 'description' => 'Manage client administrators'],
            
            // Notices
            ['name' => 'View Notices', 'slug' => 'view-notices', 'module' => 'notices', 'description' => 'View system notices'],
            ['name' => 'Create Notice', 'slug' => 'create-notice', 'module' => 'notices', 'description' => 'Create new notices'],
            ['name' => 'Edit Notice', 'slug' => 'edit-notice', 'module' => 'notices', 'description' => 'Edit notice information'],
            ['name' => 'Delete Notice', 'slug' => 'delete-notice', 'module' => 'notices', 'description' => 'Delete notices'],
            
            // Settings
            ['name' => 'View Settings', 'slug' => 'view-settings', 'module' => 'settings', 'description' => 'View system settings'],
            ['name' => 'Manage Settings', 'slug' => 'manage-settings', 'module' => 'settings', 'description' => 'Manage system settings'],
            ['name' => 'Tax Settings', 'slug' => 'tax-settings', 'module' => 'settings', 'description' => 'Manage tax settings'],
            ['name' => 'Calculation Settings', 'slug' => 'calculation-settings', 'module' => 'settings', 'description' => 'Manage calculation settings'],
            
            // Profile
            ['name' => 'View Profile', 'slug' => 'view-profile', 'module' => 'profile', 'description' => 'View user profile'],
            ['name' => 'Edit Profile', 'slug' => 'edit-profile', 'module' => 'profile', 'description' => 'Edit user profile'],
            
            // Permissions
            ['name' => 'View Permissions', 'slug' => 'view-permissions', 'module' => 'permissions', 'description' => 'View permission management'],
            ['name' => 'Create Role', 'slug' => 'create-role', 'module' => 'permissions', 'description' => 'Create new user roles'],
            ['name' => 'Edit Role', 'slug' => 'edit-role', 'module' => 'permissions', 'description' => 'Edit existing roles'],
            ['name' => 'Delete Role', 'slug' => 'delete-role', 'module' => 'permissions', 'description' => 'Delete user roles'],
            ['name' => 'Manage Roles', 'slug' => 'manage-roles', 'module' => 'permissions', 'description' => 'Manage all aspects of roles'],
            ['name' => 'Create Permission', 'slug' => 'create-permission', 'module' => 'permissions', 'description' => 'Create new permissions'],
            ['name' => 'Edit Permission', 'slug' => 'edit-permission', 'module' => 'permissions', 'description' => 'Edit existing permissions'],
            ['name' => 'Delete Permission', 'slug' => 'delete-permission', 'module' => 'permissions', 'description' => 'Delete permissions'],
            ['name' => 'Manage Permissions', 'slug' => 'manage-permissions', 'module' => 'permissions', 'description' => 'Manage all aspects of permissions'],
            ['name' => 'Assign Roles', 'slug' => 'assign-roles', 'module' => 'permissions', 'description' => 'Assign roles to users'],
            
            // Employee Self Service
            ['name' => 'View My Leaves', 'slug' => 'view-my-leaves', 'module' => 'employee-self-service', 'description' => 'View own leave applications'],
            ['name' => 'Create My Leave', 'slug' => 'create-my-leave', 'module' => 'employee-self-service', 'description' => 'Create own leave applications'],
            ['name' => 'Edit My Profile', 'slug' => 'edit-my-profile', 'module' => 'employee-self-service', 'description' => 'Edit own profile'],
            
            // Schedule
            ['name' => 'View Schedule', 'slug' => 'view-schedule', 'module' => 'schedule', 'description' => 'View employee schedules'],
            ['name' => 'Create Schedule', 'slug' => 'create-schedule', 'module' => 'schedule', 'description' => 'Create employee schedules'],
            ['name' => 'Edit Schedule', 'slug' => 'edit-schedule', 'module' => 'schedule', 'description' => 'Edit employee schedules'],
            ['name' => 'Delete Schedule', 'slug' => 'delete-schedule', 'module' => 'schedule', 'description' => 'Delete employee schedules'],
            ['name' => 'Publish Schedule', 'slug' => 'publish-schedule', 'module' => 'schedule', 'description' => 'Publish schedules to employees'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }

        // Create the 4 required roles with specific IDs to match live database
        $roles = [
            [
                'id' => 1,
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Full system access with all permissions',
                'permissions' => Permission::all()->pluck('id')->toArray()
            ],
            [
                'id' => 2,
                'name' => 'Client',
                'slug' => 'client',
                'description' => 'Client access with full client-side permissions',
                'permissions' => Permission::whereIn('module', [
                    'dashboard', 'employees', 'attendance', 'leaves', 'leave-types', 
                    'holidays', 'departments', 'pay-heads', 'payroll', 'run-payroll', 
                    'payroll-reports', 'reports', 'notices', 'schedule', 'profile'
                ])->pluck('id')->toArray()
            ],
            [
                'id' => 3,
                'name' => 'Employee',
                'slug' => 'employee',
                'description' => 'Employee self-service access only',
                'permissions' => Permission::whereIn('module', [
                    'employee-self-service', 'schedule'
                ])->pluck('id')->toArray()
            ],
            [
                'id' => 4,
                'name' => 'Subadmin',
                'slug' => 'subadmin',
                'description' => 'Limited admin access with most permissions except sensitive ones',
                'permissions' => Permission::whereNotIn('module', ['permissions'])->pluck('id')->toArray()
            ]
        ];

        foreach ($roles as $roleData) {
            $permissions = $roleData['permissions'];
            unset($roleData['permissions']);
            
            // Create role with specific ID
            $role = Role::create($roleData);
            
            // Sync permissions
            $role->permissions()->sync($permissions);
        }

        $this->command->info('Permissions and roles seeded successfully!');
    }
}