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
use App\Http\Controllers\KioskController;


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

// Kiosk Routes
Route::prefix('kiosk')->name('kiosk.')->group(function () {
    Route::get('/', [App\Http\Controllers\KioskController::class, 'showKiosk'])->name('login');
    Route::post('/verify-company', [App\Http\Controllers\KioskController::class, 'verifyCompany'])->name('verify-company');
    Route::get('/start', [App\Http\Controllers\KioskController::class, 'showStart'])->name('start');
    Route::get('/face-recognition', [App\Http\Controllers\KioskController::class, 'initiateFaceRecognition'])->name('face-recognition');
    Route::post('/verify-face', [App\Http\Controllers\KioskController::class, 'verifyFace'])->name('verify-face');
    Route::get('/pin-verification', [App\Http\Controllers\KioskController::class, 'showPinVerification'])->name('pin-verification');
    Route::post('/verify-pin', [App\Http\Controllers\KioskController::class, 'verifyPin'])->name('verify-pin');
    Route::get('/clock', [App\Http\Controllers\KioskController::class, 'showClock'])->name('clock');
    Route::get('/status', [App\Http\Controllers\KioskController::class, 'getStatus'])->name('status');
    Route::get('/history', [App\Http\Controllers\KioskController::class, 'getHistory'])->name('history');
    Route::post('/clock-in-out', [App\Http\Controllers\KioskController::class, 'clockInOut'])->name('clock-in-out');
    Route::get('/back', [App\Http\Controllers\KioskController::class, 'goBack'])->name('back');
});
