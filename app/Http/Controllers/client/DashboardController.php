<?php

namespace App\Http\Controllers\client;

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
		$countLc 	= 0;
		$countHc 	= 0;
		$countEx 	= 0;
		$countGss 	= 0;
		$countGsQ 	= 0;
		$countHs 	= 0;
		$countLps 	= 0;
		$countNsp 	= 0;
		$countPts 	= 0;
		$countSts 	= 0;
		$countSms 	= 0;

		return view('client.dashboard.index', compact(
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
