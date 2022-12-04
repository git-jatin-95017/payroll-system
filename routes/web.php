<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\ExpenditureSampleController;
use App\Http\Controllers\admin\ClientController;
use App\Http\Controllers\admin\ProfileController;
use App\Http\Controllers\client\EmployeeController;
use App\Http\Controllers\client\HolidayController;
use App\Http\Controllers\client\LeavesController;
use App\Http\Controllers\client\DepartmentController;
use App\Http\Controllers\client\LeaveTypeController;
use App\Http\Controllers\employee\LeaveController;
use App\Http\Controllers\employee\MyProfileController;
use App\Http\Controllers\PunchController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\client\PayrollController;
use App\Http\Controllers\client\PayheadController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
	return view('auth.login');
});

Route::get('sample-download/{file}', [App\Http\Controllers\admin\DashboardController::class, 'downloadSample'])->name('download-sample');

Route::prefix('admin')->group(function () {
	//Dashboard
	Route::get('dashboard', [App\Http\Controllers\admin\DashboardController::class, 'index'])->name('dashboard');	
	Route::resource('client', ClientController::class);

	Route::get('attendance/getData/', [AttendanceController::class, 'getData'])->name('attendance.getData');
	Route::resource('attendance', AttendanceController::class);	
});

Route::prefix('client')->group(function () {
	//Dashboard
	Route::get('dashboard', [App\Http\Controllers\client\DashboardController::class, 'index'])->name('dashboard');	

	Route::get('employee/getData/', [EmployeeController::class, 'getData'])->name('employee.getData');
	Route::resource('employee', EmployeeController::class);	
	Route::get('leaves/getData/', [LeavesController::class, 'getData'])->name('leaves.getData');
	Route::resource('leaves', LeavesController::class);	

	Route::resource('holidays', HolidayController::class);	

	Route::get('attendance/getData/', [AttendanceController::class, 'getData'])->name('attendance.getData');
	Route::resource('attendance', AttendanceController::class);	
	Route::get('register-entry', [PayrollController::class, 'registerEntry']);
	Route::resource('payroll', PayrollController::class);

	Route::get('department/getData/', [DepartmentController::class, 'getData'])->name('department.getData');
	Route::resource('department', DepartmentController::class);		

	Route::get('leave-type/getData/', [LeaveTypeController::class, 'getData'])->name('leave-type.getData');
	Route::resource('leave-type', LeaveTypeController::class);

	Route::get('pay-head/getData/', [PayheadController::class, 'getData'])->name('pay-head.getData');
	Route::resource('pay-head', PayheadController::class);
});

Route::prefix('employee')->group(function () {
	//Dashboard
	Route::get('dashboard', [App\Http\Controllers\employee\DashboardController::class, 'index'])->name('dashboard');	

	Route::get('my-leaves/getData/', [LeaveController::class, 'getData'])->name('my.leaves.getData');
	Route::resource('my-leaves', LeaveController::class);	
	
	Route::resource('holidays', HolidayController::class);	

	Route::resource('emp-my-profile', MyProfileController::class);
});

Route::resource('edit-my-profile', ProfileController::class);

Route::resource('punch', PunchController::class);

Auth::routes();

//Logout
Route::get('/employee/logout', function() {
	//logout user
    auth()->logout();
    
    // redirect to homepage
    return redirect('/');
});

Route::get('/logout', function() {
	//logout user
    auth()->logout();
    
    // redirect to homepage
    return redirect('/');
});
