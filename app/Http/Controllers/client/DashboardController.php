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
use App\Models\User;
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

	public function fetchCalendarData()
    {
        $birthdays = DB::table('users')
            ->join('employee_profile', 'users.id', '=', 'employee_profile.user_id')
            ->select(DB::raw('CONCAT(employee_profile.first_name, " ", employee_profile.last_name, "’s Birthday") as title'), 'employee_profile.dob as start', DB::raw('"birthday" as type'))
            ->get();

        $leaves = DB::table('leaves')
			->join('leave_types', 'leave_types.id', '=', 'leaves.type_id')
            ->select('leave_types.name as title', 'start_date as start', DB::raw('"leave" as type'))
			->where('leave_status', 'approved')
            ->get();

        $publicHolidays = DB::table('holidays')
            ->select('title', 'holiday_date as start', DB::raw('"public_holiday" as type'))
			->where('type', 1)
            ->get();

        $voluntaryHolidays = DB::table('holidays')
            ->select('title', 'holiday_date as start', DB::raw('"voluntary_holiday" as type'))
			->where('type', 3)
            ->get();

        // Merge all events
        $events = $birthdays->merge($leaves)->merge($publicHolidays)->merge($voluntaryHolidays);

        return response()->json($events);
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
		$totalEmp = User::join('employee_profile', function($join) {
			$join->on('users.id', '=', 'employee_profile.user_id');
		}) 
		->where('users.created_by', auth()->user()->id)->count();

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
			'totalEmp'
		));
	}


	public function downloadSample($file) {
		$filepath = public_path("sample/{$file}");
        return Response::download($filepath); 
	}

	public function getRecentPayroll()
    {
        // Query to get the latest 2 payrolls
        /*
		$payrolls = DB::table('payrolls')
            ->select('month', 'total_amount')
            ->orderBy('created_at', 'desc')
            ->limit(2)
            ->get();
		*/
		
		$payrolls = [
			[
				'month' => 'January',
				'total_amount' => 5000
			],
			[
				'month' => 'February',
				'total_amount' => 7000
			]
		];

        return response()->json($payrolls);
    }
}
