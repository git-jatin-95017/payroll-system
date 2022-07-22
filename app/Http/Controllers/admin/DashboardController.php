<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LocationCode;
use App\Models\ExpenditureSample;
use App\Models\GsCodeSample;
use App\Models\GsQuantitySample;
use App\Models\HousingCode;
use App\Models\HousingSample;
use App\Models\LocationPriceSample;
use App\Models\NationalSample;
use App\Models\PropertyTaxSample;
use App\Models\SaleTaxSample;
use App\Models\SupermarketSample;
use File;
use Response;
use DB;

class DashboardController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Contracts\Support\Renderable
	 */
	public function index()
	{
		$countLc 	= LocationCode::count();
		$countHc 	= HousingCode::count();
		$countEx 	= ExpenditureSample::count();
		$countGss 	= GsCodeSample::count();
		$countGsQ 	= GsQuantitySample::count();
		$countHs 	= HousingSample::count();
		$countLps 	= LocationPriceSample::count();
		$countNsp 	= NationalSample::count();
		$countPts 	= PropertyTaxSample::count();
		$countSts 	= SaleTaxSample::count();
		$countSms 	= SupermarketSample::count();

		return view('admin.dashboard.index', compact(
			'countLc',
			'countHc',
			'countEx',
			'countGss',
			'countGsQ',
			'countHs',
			'countLps',
			'countNsp',
			'countPts',
			'countSts',
			'countSms',
		));
	}


	public function downloadSample($file) {
		$filepath = public_path("sample/{$file}");
        return Response::download($filepath); 
	}
}
