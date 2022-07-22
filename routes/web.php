<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\LocationCodeController;
use App\Http\Controllers\admin\ExpenditureSampleController;
use App\Http\Controllers\admin\GsCodeSampleController;
use App\Http\Controllers\admin\GsQuantitySampleController;
use App\Http\Controllers\admin\HousingCodeController;
use App\Http\Controllers\admin\HousingSampleController;
use App\Http\Controllers\admin\LocationPriceSampleController;
use App\Http\Controllers\admin\NationalSampleController;
use App\Http\Controllers\admin\PropertyTaxSampleController;
use App\Http\Controllers\admin\SaleTaxSampleController;
use App\Http\Controllers\admin\SupermarketSampleController;
use App\Http\Controllers\admin\HousingFinalPriceController;
use App\Http\Controllers\admin\HousingFinalPriceCountryController;
use App\Http\Controllers\admin\GsRawPriceController;
use App\Http\Controllers\admin\GsCleanedPriceController;

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
	Route::resource('location-codes', LocationCodeController::class);	
	Route::resource('expenditure', ExpenditureSampleController::class);
	Route::resource('gs-code', GsCodeSampleController::class);
	Route::resource('gs-quantity', GsQuantitySampleController::class);
	Route::resource('housing-code', HousingCodeController::class);
	Route::resource('housing', HousingSampleController::class);
	Route::resource('location-price', LocationPriceSampleController::class);
	Route::resource('national-data', NationalSampleController::class);
	Route::resource('property-tax', PropertyTaxSampleController::class);
	Route::resource('sale-tax', SaleTaxSampleController::class);
	Route::resource('super-market', SupermarketSampleController::class);

	//ERD CALC
	Route::resource('housing-final-prices', HousingFinalPriceController::class);
	Route::resource('housing-final-prices-country', HousingFinalPriceCountryController::class);
	Route::resource('gs-raw-prices', GsRawPriceController::class);
	Route::resource('gs-cleaned-prices', GsCleanedPriceController::class);	

});

Auth::routes();