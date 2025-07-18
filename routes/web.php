<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\ExpenditureSampleController;
use App\Http\Controllers\admin\ClientController;
use App\Http\Controllers\admin\ProfileController;
use App\Http\Controllers\client\EmployeeController;
use App\Http\Controllers\client\HolidayController;
use App\Http\Controllers\client\NoticeController;
use App\Http\Controllers\client\LeavesController;
use App\Http\Controllers\client\DepartmentController;
use App\Http\Controllers\client\LeaveTypeController;
use App\Http\Controllers\employee\LeaveController;
use App\Http\Controllers\employee\LeaveController as EMPLEAVE;
use App\Http\Controllers\employee\MyProfileController;
use App\Http\Controllers\client\MyProfileController as MPF;
use App\Http\Controllers\PunchController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\client\PayrollController;
use App\Http\Controllers\client\PayheadController;
use App\Http\Controllers\admin\RunPayrollController as RPC;
use App\Http\Controllers\client\RunPayrollController;
use App\Http\Controllers\client\ReportController;
use App\Http\Controllers\admin\SettingController;
use App\Http\Middleware\CheckPunchin;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Http\Controllers\client\PayrollReportController;
use App\Http\Controllers\client\ScheduleController;


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
	Route::get('dashboard', [App\Http\Controllers\admin\DashboardController::class, 'index'])->name('admin.dashboard');

	//New
	Route::get('fetch-calendar-data', [App\Http\Controllers\admin\DashboardController::class, 'fetchCalendarData']);
	Route::get('recent-payroll', [App\Http\Controllers\admin\DashboardController::class, 'getRecentPayroll']);
	Route::get('get-approved-employees-count', [App\Http\Controllers\admin\DashboardController::class, 'getApprovedEmployeesCount'])->name('getApprovedEmployeesCount');
	Route::get('notices', [NoticeController::class, 'fetchNotices']);
	Route::resource('notice', NoticeController::class);


	Route::post('login-as-client', [ClientController::class, 'loginAs'])->name('login-as-client');
	Route::resource('client', ClientController::class);

	Route::get('attendance/getData/', [AttendanceController::class, 'getData'])->name('attendance.getData');
	Route::resource('attendance', AttendanceController::class);

	Route::get('run-payroll/step-1', [RPC::class, 'stepOne'])->name('admin.list.step1');
	Route::post('run-payroll/step-1', [RPC::class, 'storeStepOne'])->name('admin.store.Step1');

	Route::get('run-payroll/step-2', [RPC::class, 'stepTwo'])->name('admin.list.step2');
	Route::post('run-payroll/step-2', [RPC::class, 'storeStepTwo'])->name('admin.store.Step2');

	// Route::resource('run-payroll', RPC::class);

	Route::get('calculating-payroll', function () {
		return view('admin.payroll.thanks');
	})->name('admin.calculating-payroll');

	Route::get('settings/tax', [SettingController::class, 'create'])->name('settings.create');
	Route::post('settings/tax', [SettingController::class, 'updateSettings'])->name('settings.update');

});

Route::prefix('client')->group(function () {
	//Dashboard
	Route::get('dashboard', [App\Http\Controllers\client\DashboardController::class, 'index'])->name('client.dashboard');

	Route::get('fetch-calendar-data', [App\Http\Controllers\client\DashboardController::class, 'fetchCalendarData']);
	Route::get('recent-payroll', [App\Http\Controllers\client\DashboardController::class, 'getRecentPayroll']);

	Route::get('get-approved-employees-count', [App\Http\Controllers\client\DashboardController::class, 'getApprovedEmployeesCount'])->name('getApprovedEmployeesCount');


	Route::get('employee/getData/', [EmployeeController::class, 'getData'])->name('employee.getData');
	Route::resource('employee', EmployeeController::class);
	Route::get('leaves/getData/', [LeavesController::class, 'getData'])->name('leaves.getData');
	Route::get('edit-leave/{id}/{emp_id}', [LeavesController::class, 'edit']);
	Route::put('edit-leave/{id}', [LeavesController::class, 'updateLeave'])->name('edit-leave.save');
	Route::resource('leaves', LeavesController::class);

	Route::get('fetch/emp-data', [EMPLEAVE::class, 'create'])->name('emp-my-leaves.create');

	Route::resource('holidays', HolidayController::class);

	Route::get('attendance/getData/', [AttendanceController::class, 'getData'])->name('attendance.getData');
	Route::resource('attendance', AttendanceController::class);
	Route::get('register-entry', [PayrollController::class, 'registerEntry']);
	Route::get('autocomplete', [PayrollController::class, 'search'])->name('search.autocomplete');
	Route::post('payroll/create', [PayrollController::class, 'create'])->name('payroll.create.post');
	Route::resource('payroll', PayrollController::class);

	Route::get('department/getData/', [DepartmentController::class, 'getData'])->name('department.getData');
	Route::post('location-assign', [DepartmentController::class, 'assign'])->name('assign.locations');
	Route::get('get-locations-assigned', [DepartmentController::class, 'assignedPayhead'])->name('assigned.locations');
	Route::resource('department', DepartmentController::class);

	Route::get('leave-type/getData/', [LeaveTypeController::class, 'getData'])->name('leave-type.getData');
	Route::post('leave-type-assign', [LeaveTypeController::class, 'assign'])->name('assign.leave.policies');
	Route::get('get-leave-type-assigned', [LeaveTypeController::class, 'assignedPayhead'])->name('assigned.leave.policies');
	Route::resource('leave-type', LeaveTypeController::class);

	Route::get('pay-head/getData/', [PayheadController::class, 'getData'])->name('pay-head.getData');
	Route::post('pay-head-assign', [PayheadController::class, 'assign'])->name('assign.payhead');
	Route::get('get-pay-head-assigned', [PayheadController::class, 'assignedPayhead'])->name('assigned.payhead');
	Route::resource('pay-head', PayheadController::class);

	Route::get('get-pending-payroll-list', [RunPayrollController::class, 'listPayroll'])->name('list.payroll');
	Route::get('run-payroll/step-1', [RunPayrollController::class, 'stepOne'])->name('list.step1');
	Route::post('run-payroll/step-1', [RunPayrollController::class, 'storeStepOne'])->name('store.Step1');

	Route::get('run-payroll/step-2', [RunPayrollController::class, 'stepTwo'])->name('list.step2');
	Route::post('run-payroll/step-2', [RunPayrollController::class, 'storeStepTwo'])->name('store.Step2');

	Route::post('save-name-payroll', [RunPayrollController::class, 'saveName'])->name('save.name.payroll');

	Route::get('payroll-confirmation', [RunPayrollController::class, 'showConfirmation'])->name('payroll.confirmation');
	Route::post('payroll-confirmation', [RunPayrollController::class, 'saveConfirmation'])->name('save.confirmation');

	// Route::resource('run-payroll', RunPayrollController::class);

	Route::get('calculating-payroll', function (Request $request) {
		return view('client.payroll.thanks', ['start_date' => $request->start_date, 'end_date' => $request->end_date, 'appoval_number' => $request->appoval_number]);
	})->name('calculating-payroll');

	Route::get('delete/payroll', [RunPayrollController::class, 'deletePayroll'])->name('delete.payroll');
	Route::get('download-pdf', [RunPayrollController::class, 'downloadPdf'])->name('download.pdf');
	Route::resource('my-profile', MPF::class);

	Route::post('show-medical-form', [ReportController::class, 'showMedicalForm'])->name('report.showMedicalForm');
	Route::resource('report', ReportController::class);

	Route::get('notices', [NoticeController::class, 'fetchNotices']);
	Route::resource('notice', NoticeController::class);

	// Payroll Reports
	Route::get('payroll/reports/summary', [PayrollReportController::class, 'summary'])->name('payroll.reports.summary');
	Route::get('payroll/reports/taxes', [PayrollReportController::class, 'taxes'])->name('payroll.reports.taxes');
	Route::get('reports/employee-earnings', [PayrollReportController::class, 'employeeEarnings'])->name('payroll.reports.employee-earnings');
	Route::get('reports/employer-payments', [PayrollReportController::class, 'employerPayments'])->name('payroll.reports.employer-payments');
	Route::get('reports/download-pdf/{type}', [PayrollReportController::class, 'downloadPdf'])->name('payroll.reports.download-pdf');
	Route::get('payroll/reports/download-report-pdf/{type}', [PayrollReportController::class, 'downloadReportPdf'])->name('payroll.reports.download-report-pdf');
	Route::get('payroll/reports/download-report-excel/{type}', [PayrollReportController::class, 'downloadReportExcel'])->name('payroll.reports.download-report-excel');

	// Reports Routes
	Route::prefix('reports')->name('reports.')->group(function () {
		//Atttendence Report
		Route::get('/attendance', [PayrollReportController::class, 'attendance'])->name('attendance-report');
		Route::get('/download-report-excel/{type}', [PayrollReportController::class, 'downloadAttendanceReportExcel'])->name('download-attendance-report-excel');
		Route::get('/download-report-pdf/{type}', [PayrollReportController::class, 'downloadAttendanceReportPdf'])->name('download-attendance-report-pdf');
		
		//Employee Gross Earnings Report
		Route::get('/employee-gross-earnings', [PayrollReportController::class, 'employeeGrossEarnings'])->name('employee-gross-earnings');
		Route::get('/download-employee-gross-earnings-excel', [PayrollReportController::class, 'downloadEmployeeGrossEarningsExcel'])->name('download-employee-gross-earnings-excel');
		Route::get('/download-employee-gross-earnings-pdf', [PayrollReportController::class, 'downloadEmployeeGrossEarningsPdf'])->name('download-employee-gross-earnings-pdf');

		// Statutory Deductions Report
		Route::get('/statutory-deductions', [PayrollReportController::class, 'statutoryDeductions'])->name('statutory-deductions');
		Route::get('/download-statutory-deductions-excel', [PayrollReportController::class, 'downloadStatutoryDeductionsExcel'])->name('download-statutory-deductions-excel');
		Route::get('/download-statutory-deductions-pdf', [PayrollReportController::class, 'downloadStatutoryDeductionsPdf'])->name('download-statutory-deductions-pdf');

		// Additions & Deductions Report
		Route::get('/additions-deductions', [PayrollReportController::class, 'additionsDeductions'])->name('additions-deductions');
		Route::get('/download-additions-deductions-excel', [PayrollReportController::class, 'downloadAdditionsDeductionsExcel'])->name('download-additions-deductions-excel');
		Route::get('/download-additions-deductions-pdf', [PayrollReportController::class, 'downloadAdditionsDeductionsPdf'])->name('download-additions-deductions-pdf');

		// Leave Report
		Route::get('/leave', [PayrollReportController::class, 'leave'])->name('leave');
		Route::get('/download-leave-excel', [PayrollReportController::class, 'downloadLeaveExcel'])->name('download-leave-excel');
		Route::get('/download-leave-pdf', [PayrollReportController::class, 'downloadLeavePdf'])->name('download-leave-pdf');
	});

	Route::get('schedule/published-status', [App\Http\Controllers\client\ScheduleController::class, 'publishedStatus']);
	Route::post('schedule/publish', [App\Http\Controllers\client\ScheduleController::class, 'publish']);
	Route::resource('schedule', ScheduleController::class)->only(['index', 'store', 'show', 'update', 'destroy']);

});

Route::prefix('employee')->group(function () {
	//Dashboard
	Route::get('dashboard', [App\Http\Controllers\employee\DashboardController::class, 'index'])->name('dashboard');
	//New
	Route::get('emp-fetch-calendar-data', [App\Http\Controllers\employee\DashboardController::class, 'fetchCalendarData']);
	Route::get('emp-recent-payroll', [App\Http\Controllers\employee\DashboardController::class, 'getRecentPayroll']);
	Route::get('emp-get-approved-employees-count', [App\Http\Controllers\employee\DashboardController::class, 'getApprovedEmployeesCount'])->name('emp-getApprovedEmployeesCount');
	Route::get('emp-notices', [NoticeController::class, 'fetchNotices']);
	Route::resource('emp-notice', NoticeController::class);


	Route::get('my-leaves/getData/', [LeaveController::class, 'getData'])->name('my.leaves.getData');//->middleware([CheckPunchin::class]);
	Route::resource('my-leaves', LeaveController::class);//->middleware([CheckPunchin::class]);

	Route::resource('holidays', HolidayController::class);//->middleware([CheckPunchin::class]);

	Route::resource('emp-my-profile', MyProfileController::class);//->middleware([CheckPunchin::class]);

	Route::get('my-payslip', [MyProfileController::class, 'myPayslip'])->name('my.payslip');

	Route::get('download-pdf', [MyProfileController::class, 'downloadPdf'])->name('empdownload.pdf');
	
	// Employee Schedules
	Route::get('my-schedules', [App\Http\Controllers\employee\EmployeeScheduleController::class, 'index'])->name('my-schedules');
	Route::get('my-schedules/{id}', [App\Http\Controllers\employee\EmployeeScheduleController::class, 'show'])->name('my-schedules.show');
	Route::get('schedules/ajax', [App\Http\Controllers\employee\EmployeeScheduleController::class, 'ajaxGrid'])->name('employee.schedules.ajax');
});

Route::resource('edit-my-profile', ProfileController::class);

Route::resource('punch', PunchController::class);

Auth::routes(['logout' => false]);

//Logout
Route::get('/employee/logout', function() {
	/*

	if (auth()->user()->role_id == 3) {
		$attendanceDate = date('Y-m-d');

        $isPunchInCount = Attendance::where('user_id', auth()->user()->id)
            ->whereDate('attendance_date', $attendanceDate)
            ->where('action_name', 'punchin')
            ->count();

        if ($isPunchInCount == 0) {
     	   return redirect()->route('dashboard')->with('error','Please punch in/out first!');
        }

        $isPunchOutCount = Attendance::where('user_id', auth()->user()->id)
            ->whereDate('attendance_date', $attendanceDate)
            ->where('action_name', 'punchout')
            ->count();

        if ($isPunchOutCount == 0) {
     	   return redirect()->route('dashboard')->with('error','Please punch out first!');
        }
	}

	*/
	//logout user
    auth()->logout();

    // redirect to homepage
    return redirect('/');
});

Route::get('/logout', function() {

	/*
	if (auth()->user()->role_id == 3) {
		$attendanceDate = date('Y-m-d');

        $isPunchInCount = Attendance::where('user_id', auth()->user()->id)
            ->whereDate('attendance_date', $attendanceDate)
            ->where('action_name', 'punchin')
            ->count();

        if ($isPunchInCount == 0) {
     	   return redirect()->route('dashboard')->with('error','Please punch in/out first!');
        }

        $isPunchOutCount = Attendance::where('user_id', auth()->user()->id)
            ->whereDate('attendance_date', $attendanceDate)
            ->where('action_name', 'punchout')
            ->count();

        if ($isPunchOutCount == 0) {
     	   return redirect()->route('dashboard')->with('error','Please punch out first!');
        }
	}

	*/
	//logout user
    auth()->logout();

    // redirect to homepage
    return redirect('/');
})->name('logout');


Route::get('client/step-4', function () {
	return view('client.payroll.step4');
});


Route::get('report', function(){
    return view('pages.r_five_form');
});

Route::get('employee_profile', function(){
    return view('pages.employee_profile');
});
