<?php

use Illuminate\Support\Facades\Route;
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
Route::prefix('/')->name('kiosk.')->group(function () {
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
    Route::get('/kiosk/back', [\App\Http\Controllers\KioskController::class, 'goBack'])->name('kiosk.back');
    Route::get('/face-confirmation', [App\Http\Controllers\KioskController::class, 'showFaceConfirmation'])->name('face-confirmation');
    Route::post('/face-confirmation', [App\Http\Controllers\KioskController::class, 'handleFaceConfirmation'])->name('face-confirmation.post');
});
