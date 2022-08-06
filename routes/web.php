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
use App\Http\Controllers\admin\GsRawPriceController;
use App\Http\Controllers\admin\GsCleanedPriceController;
use App\Http\Controllers\admin\GreenDataController;
use App\Http\Controllers\admin\GSCItemPriceLocationController;
use App\Http\Controllers\admin\GSCItemPriceCityController;
use App\Http\Controllers\admin\GSCItemPriceCountryController;
use App\Http\Controllers\admin\GSCItemPriceAdjustCityController;
use App\Http\Controllers\admin\GSCityBudgetController;
use App\Http\Controllers\admin\GsItemBudgetController;
use App\Http\Controllers\admin\GSFinalItemPriceController;
use App\Http\Controllers\admin\ExchangeRateController;
use App\Http\Controllers\admin\HousingFinalPriceController;
use App\Http\Controllers\admin\HousingFinalPriceCountryController;
use App\Http\Controllers\admin\HousingFinalRentalPriceController;
use App\Http\Controllers\admin\HousingHomeIndicePriceController;
use App\Http\Controllers\admin\HousingPropertyTaxIndicePriceController;
use App\Http\Controllers\admin\HousingRentalIndicePriceController;

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

	Route::post('/location-codes/multi-delete', [LocationCodeController::class, 'deleteAll'])->name('location-codes.multi-delete');
	Route::resource('location-codes', LocationCodeController::class);

	Route::post('/expenditure/multi-delete', [ExpenditureSampleController::class, 'deleteAll'])->name('expenditure.multi-delete');	
	Route::resource('expenditure', ExpenditureSampleController::class);

	Route::post('/gs-code/multi-delete', [GsCodeSampleController::class, 'deleteAll'])->name('gs-code.multi-delete');
	Route::resource('gs-code', GsCodeSampleController::class);

	Route::post('/gs-quantity/multi-delete', [GsQuantitySampleController::class, 'deleteAll'])->name('gs-quantity.multi-delete');
	Route::resource('gs-quantity', GsQuantitySampleController::class);

	Route::post('/housing-code/multi-delete', [HousingCodeController::class, 'deleteAll'])->name('housing-code.multi-delete');
	Route::resource('housing-code', HousingCodeController::class);

	Route::post('/housing/multi-delete', [HousingSampleController::class, 'deleteAll'])->name('housing.multi-delete');
	Route::resource('housing', HousingSampleController::class);

	Route::post('/location-price/multi-delete', [LocationPriceSampleController::class, 'deleteAll'])->name('location-price.multi-delete');
	Route::resource('location-price', LocationPriceSampleController::class);

	Route::post('/national-data/multi-delete', [NationalSampleController::class, 'deleteAll'])->name('national-data.multi-delete');
	Route::resource('national-data', NationalSampleController::class);

	Route::post('/property-tax/multi-delete', [PropertyTaxSampleController::class, 'deleteAll'])->name('property-tax.multi-delete');
	Route::resource('property-tax', PropertyTaxSampleController::class);

	Route::post('/sale-tax/multi-delete', [SaleTaxSampleController::class, 'deleteAll'])->name('sale-tax.multi-delete');
	Route::resource('sale-tax', SaleTaxSampleController::class);

	Route::post('/super-market/multi-delete', [SupermarketSampleController::class, 'deleteAll'])->name('super-market.multi-delete');
	Route::resource('super-market', SupermarketSampleController::class);

	//CALCULATIONS & GS
	Route::post('/gs-raw-prices/multi-delete', [GsRawPriceController::class, 'deleteAll'])->name('gs-raw-prices.multi-delete');
	Route::resource('gs-raw-prices', GsRawPriceController::class);

	Route::post('/gs-cleaned-prices/multi-delete', [GsCleanedPriceController::class, 'deleteAll'])->name('gs-cleaned-prices.multi-delete');
	Route::resource('gs-cleaned-prices', GsCleanedPriceController::class);

	Route::post('gsc-itemprice-locations/multi-delete', [GSCItemPriceLocationController::class, 'deleteAll'])->name('gsc-itemprice-locations.multi-delete');
	Route::resource('gsc-itemprice-locations', GSCItemPriceLocationController::class);	

	Route::post('gsc-itemprice-cities/multi-delete', [GSCItemPriceCityController::class, 'deleteAll'])->name('gsc-itemprice-cities.multi-delete');
	Route::resource('gsc-itemprice-cities', GSCItemPriceCityController::class);

	Route::post('gsc-itemprice-countries/multi-delete', [GSCItemPriceCountryController::class, 'deleteAll'])->name('gsc-itemprice-countries.multi-delete');
	Route::resource('gsc-itemprice-countries', GSCItemPriceCountryController::class);

	Route::post('gsc-itemprice-adjusted-cities/multi-delete', [GSCItemPriceAdjustCityController::class, 'deleteAll'])->name('gsc-itemprice-adjusted-cities.multi-delete');
	Route::resource('gsc-itemprice-adjusted-cities', GSCItemPriceAdjustCityController::class);

	Route::post('gs-city-budgets/multi-delete', [GSCityBudgetController::class, 'deleteAll'])->name('gs-city-budgets.multi-delete');
	Route::resource('gs-city-budgets', GSCityBudgetController::class);

	Route::post('gs-item-budgets/multi-delete', [GsItemBudgetController::class, 'deleteAll'])->name('gs-item-budgets.multi-delete');
	Route::resource('gs-item-budgets', GsItemBudgetController::class);

	Route::post('gs-final-item-prices/multi-delete', [GSFinalItemPriceController::class, 'deleteAll'])->name('gs-final-item-prices.multi-delete');
	Route::resource('gs-final-item-prices', GSFinalItemPriceController::class);

	Route::post('exchange-rates/multi-delete', [ExchangeRateController::class, 'deleteAll'])->name('exchange-rates.multi-delete');
	Route::resource('exchange-rates', ExchangeRateController::class);
	
	//CALCULATIONS & HOUSING
	Route::post('housing-final-prices/multi-delete', [HousingFinalPriceController::class, 'deleteAll'])->name('housing-final-prices.multi-delete');
	Route::resource('housing-final-prices', HousingFinalPriceController::class);

	Route::post('housing-final-prices-country/multi-delete', [HousingFinalPriceCountryController::class, 'deleteAll'])->name('housing-final-prices-country.multi-delete');
	Route::resource('housing-final-prices-country', HousingFinalPriceCountryController::class);

	Route::post('housing-final-rental-prices/multi-delete', [HousingFinalRentalPriceController::class, 'deleteAll'])->name('housing-final-rental-prices.multi-delete');
	Route::resource('housing-final-rental-prices', HousingFinalRentalPriceController::class);

	Route::post('housing-home-indices-prices/multi-delete', [HousingHomeIndicePriceController::class, 'deleteAll'])->name('housing-home-indices-prices.multi-delete');
	Route::resource('housing-home-indices-prices', HousingHomeIndicePriceController::class);

	Route::post('housing-prop-tax-ind-prices/multi-delete', [HousingPropertyTaxIndicePriceController::class, 'deleteAll'])->name('housing-prop-tax-ind-prices.multi-delete');
	Route::resource('housing-prop-tax-ind-prices', HousingPropertyTaxIndicePriceController::class);

	Route::post('housing-rental-indices-prices/multi-delete', [HousingRentalIndicePriceController::class, 'deleteAll'])->name('housing-rental-indices-prices.multi-delete');
	Route::resource('housing-rental-indices-prices', HousingRentalIndicePriceController::class);

	Route::get('run-script-view', [GreenDataController::class, 'index'])->name('run-script-view');
	Route::post('run-script', [GreenDataController::class, 'runScript'])->name('run-script');
	Route::post('exhange-rate', [GreenDataController::class, 'exchangeRate'])->name('exhange-rate');
	Route::post('flush-data', [GreenDataController::class, 'store'])->name('flush-data');

});

Auth::routes();