# 🛡️ Permission System Guide

## Overview
This comprehensive permission system allows you to create custom roles (like "Sub Admin") and assign specific permissions to control access to different modules in your payroll system.

## 🚀 Quick Start

### 1. Access Permission Management
- **Login as Admin** → Go to **Admin Dashboard**
- **Click "Permissions"** in the sidebar
- **Manage Roles & Permissions** with the beautiful interface

### ⚠️ Important: Role IDs
The system uses specific role IDs to match your live database:
- **ID 1**: Admin
- **ID 2**: Client  
- **ID 3**: Employee
- **ID 4**: Subadmin

### 2. Create a Sub Admin Role
1. **Click "Create Role"**
2. **Name**: "Sub Admin" (or any custom name)
3. **Select Permissions**: Choose what modules they can access
4. **Save** → Role created!

### 3. Assign Role to User
1. **Go to "Role Users"** for any role
2. **Select User** from dropdown
3. **Assign Role** → User now has those permissions

## 📋 Available Modules & Permissions

### 🏠 Dashboard
- `view-dashboard` - View dashboard
- `manage-dashboard` - Manage dashboard settings

### 👥 Employees
- `view-employees` - View employee list
- `create-employee` - Add new employees
- `edit-employee` - Edit employee information
- `delete-employee` - Remove employees
- `export-employee-data` - Export employee data

### 💰 Payroll
- `view-payroll` - View payroll information
- `create-payroll` - Create new payroll runs
- `process-payroll` - Process and calculate payroll
- `approve-payroll` - Approve payroll for payment
- `delete-payroll` - Delete payroll runs
- `download-payroll-pdf` - Download payroll reports

### 🔄 Run Payroll (Admin)
- `view-run-payroll` - View run payroll interface
- `step1-payroll` - Access to payroll step 1
- `step2-payroll` - Access to payroll step 2
- `confirm-payroll` - Confirm payroll processing

### 📊 Reports
- `view-reports` - View various reports
- `export-reports` - Export reports
- `generate-reports` - Generate custom reports
- `download-report-pdf` - Download reports as PDF
- `download-report-excel` - Download reports as Excel

### 📈 Payroll Reports
- `view-payroll-reports` - View payroll specific reports
- `payroll-summary-report` - Payroll summary reports
- `payroll-taxes-report` - Payroll taxes reports
- `employee-earnings-report` - Employee earnings reports
- `employer-payments-report` - Employer payments reports
- `statutory-deductions-report` - Statutory deductions reports

### ⏰ Attendance
- `view-attendance` - View attendance records
- `manage-attendance` - Manage attendance records
- `export-attendance` - Export attendance data
- `attendance-reports` - Generate attendance reports

### 🏖️ Leaves
- `view-leaves` - View leave applications
- `create-leave` - Create leave applications
- `edit-leave` - Edit leave applications
- `approve-leaves` - Approve/reject leave applications
- `delete-leave` - Delete leave applications

### 📋 Leave Types
- `view-leave-types` - View leave types
- `create-leave-type` - Create new leave types
- `edit-leave-type` - Edit leave types
- `delete-leave-type` - Delete leave types
- `assign-leave-policies` - Assign leave policies

### 🎉 Holidays
- `view-holidays` - View holiday calendar
- `create-holiday` - Add new holidays
- `edit-holiday` - Edit holiday information
- `delete-holiday` - Delete holidays

### 🏢 Departments
- `view-departments` - View department information
- `create-department` - Create new departments
- `edit-department` - Edit department information
- `delete-department` - Delete departments
- `assign-locations` - Assign locations to departments

### 💵 Pay Heads
- `view-pay-heads` - View pay head information
- `create-pay-head` - Create new pay heads
- `edit-pay-head` - Edit pay head information
- `delete-pay-head` - Delete pay heads
- `assign-pay-heads` - Assign pay heads to employees

### 👤 Clients
- `view-clients` - View client information
- `create-client` - Add new clients
- `edit-client` - Edit client information
- `delete-client` - Remove clients
- `login-as-client` - Login as client for testing
- `manage-client-admins` - Manage client administrators

### 📢 Notices
- `view-notices` - View system notices
- `create-notice` - Create new notices
- `edit-notice` - Edit notice information
- `delete-notice` - Delete notices

### ⚙️ Settings
- `view-settings` - View system settings
- `manage-settings` - Manage system settings
- `tax-settings` - Manage tax settings
- `calculation-settings` - Manage calculation settings

### 👤 Profile
- `view-profile` - View user profile
- `edit-profile` - Edit user profile

### 🛡️ Permissions
- `view-permissions` - View permission management
- `create-role` - Create new user roles
- `edit-role` - Edit existing roles
- `delete-role` - Delete user roles
- `manage-roles` - Manage all aspects of roles
- `create-permission` - Create new permissions
- `edit-permission` - Edit existing permissions
- `delete-permission` - Delete permissions
- `manage-permissions` - Manage all aspects of permissions
- `assign-roles` - Assign roles to users

### 👨‍💼 Employee Self Service
- `view-my-leaves` - View own leave applications
- `create-my-leave` - Create own leave applications
- `edit-my-profile` - Edit own profile

### 📅 Schedule
- `view-schedule` - View employee schedules
- `create-schedule` - Create employee schedules
- `edit-schedule` - Edit employee schedules
- `delete-schedule` - Delete employee schedules
- `publish-schedule` - Publish schedules to employees

## 🎭 Pre-defined Roles

### Admin (ID: 1)
- **Access**: Full system access with all permissions (93 permissions)
- **Use Case**: System administrators, super users
- **Permissions**: Complete access to all modules including permission management

### Client (ID: 2)
- **Access**: Full client-side access (68 permissions)
- **Use Case**: Client companies, business owners
- **Permissions**: Dashboard, employees, attendance, leaves, payroll, reports, etc.

### Employee (ID: 3)
- **Access**: Self-service features only (8 permissions)
- **Use Case**: Regular employees
- **Permissions**: View own leaves, edit own profile, view schedules

### Subadmin (ID: 4)
- **Access**: Limited admin access with most permissions (83 permissions)
- **Use Case**: Department heads, senior managers, assistant administrators
- **Permissions**: All modules except permission management system

## 🔧 Technical Implementation

### Using Middleware in Routes
```php
// Check specific permission
Route::get('/employees', [EmployeeController::class, 'index'])
    ->middleware('permission:view-employees');

// Check module permission
Route::get('/payroll', [PayrollController::class, 'index'])
    ->middleware('module.permission:payroll');
```

### Using Helper Functions in Views
```php
// Check permission in Blade templates
@if(\App\Helpers\PermissionHelper::hasPermission('create-employee'))
    <a href="{{ route('employee.create') }}" class="btn btn-primary">Add Employee</a>
@endif

// Check module permission
@if(\App\Helpers\PermissionHelper::hasModulePermission('payroll'))
    <li><a href="{{ route('payroll.index') }}">Payroll</a></li>
@endif

// Get user role
<p>Current Role: {{ \App\Helpers\PermissionHelper::getUserRole() }}</p>
```

### Using Helper Functions in Controllers
```php
use App\Helpers\PermissionHelper;

public function index()
{
    if (!PermissionHelper::hasPermission('view-employees')) {
        return redirect()->back()->with('error', 'Access denied');
    }
    
    // Your code here
}

// Check specific roles
if (PermissionHelper::isAdmin()) {
    // Admin only code
}

if (PermissionHelper::isSubadmin()) {
    // Subadmin code
}

if (PermissionHelper::isClient()) {
    // Client code
}

if (PermissionHelper::isEmployee()) {
    // Employee code
}
```

## 🎨 Features

### User-Friendly Interface
- **Clean Design**: Modern, responsive interface
- **Module Organization**: Permissions grouped by modules
- **Bulk Assignment**: Check entire modules at once
- **Search & Filter**: Easy to find specific permissions

### Security Features
- **Middleware Protection**: Route-level security
- **Role-Based Access**: Granular permission control
- **Audit Trail**: Track permission changes
- **Super Admin Bypass**: Emergency access for system admins

### Management Features
- **Role Management**: Create, edit, delete roles
- **Permission Management**: Create, edit, delete permissions
- **User Assignment**: Easy role assignment to users
- **Bulk Operations**: Manage multiple permissions at once

## 🚨 Security Best Practices

1. **Principle of Least Privilege**: Only grant necessary permissions
2. **Regular Audits**: Review permissions periodically
3. **Role Templates**: Use pre-defined roles as templates
4. **Test Permissions**: Verify permissions work correctly
5. **Backup Roles**: Keep backup of important role configurations

## 🔍 Troubleshooting

### Common Issues

**"Route not defined" Error**
- Clear route cache: `php artisan route:clear`
- Check route names in views

**Permission Not Working**
- Verify user has assigned role
- Check permission slug matches exactly
- Clear application cache: `php artisan cache:clear`

**Middleware Not Working**
- Ensure middleware is registered in Kernel.php
- Check route middleware syntax

### Debug Commands
```bash
# Clear all caches
php artisan route:clear
php artisan config:clear
php artisan view:clear
php artisan cache:clear

# Check user permissions
php artisan tinker
>>> auth()->user()->hasPermission('view-employees')
>>> auth()->user()->role->permissions->pluck('name')
```

## 📞 Support

For technical support or questions about the permission system:
1. Check this documentation
2. Review the database structure
3. Test with different user roles
4. Contact system administrator

---

**🎉 Your permission system is now ready to use! Create custom roles, assign specific permissions, and control access to your payroll system with precision.**
