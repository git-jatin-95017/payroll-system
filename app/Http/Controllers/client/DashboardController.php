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
use App\Models\Setting;
use App\Models\PayrollSheet;
use App\Models\PayrollAmount;
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

		$startD = date('Y-m-d', strtotime('-1 week'));

        $endD = date('Y-m-d');

		$totalEmp = PayrollSheet::join('users', function ($join) {
			$join->on('users.id', '=', 'payroll_sheets.emp_id')
				 ->where('status', 1);
		})
		->whereBetween('payroll_date', [$startD, $endD])
		->where('approval_status', 1)
		->distinct('payroll_sheets.emp_id')
		->where('payroll_sheets.created_by', auth()->user()->id)
		->count('payroll_sheets.emp_id');
	
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
        $settings = Setting::find(1);

		$payrollRecords = PayrollAmount::whereIn('status', [1])
			->latest()
			->take(2)
			->groupBy('start_date')
			->get();

		$totalRows = [];
		foreach($payrollRecords as $row) {
			$totalRows[] = $this->calculateRowTotals($row, $settings);
		}

		// $payrolls = [
		// 	[
		// 		'month' => 'January',
		// 		'total_amount' => 5000
		// 	],
		// 	[
		// 		'month' => 'February',
		// 		'total_amount' => 7000
		// 	]
		// ];

		return response()->json($totalRows);
    }

	function calculateRowTotals($row, $settings) {
		$medical_benefits11T = $social_security11T = $education_lvey11T = $social_security11T_employer = [];
		$TotalPayroll = 0;
	
		$medical_less_60_amt = $row->medical_less_60 ?? $settings->medical_less_60;
		$medical_gre_60_amt = $row->medical_gre_60 ?? $settings->medical_gre_60;
		$social_security_amt = $row->social_security ?? $settings->social_security;
		$social_security_employer_amt = $row->social_security_employer ?? $settings->social_security_employer;
		$education_levy_amt = $row->education_levy ?? $settings->education_levy;
		$education_levy_amt_5 = $row->education_levy_amt_5 > 0 ? $row->education_levy_amt_5 : $settings->education_levy_amt_5;
	
		$grossT = 0;
		$employeePayT = 0;
		$deductionsT = 0;
		$earningsT = 0;
		$nothingAdditionTonetPayT = 0;
	
		// Calculate additional earnings (if any)
		if (count($row->additionalEarnings) > 0) {
			foreach($row->additionalEarnings as $key => $val) {
				if($val->payhead->pay_type == 'earnings') {
					$earningsT += $val->amount;
				}
				if($val->payhead->pay_type == 'deductions') {
					$deductionsT += $val->amount;
				}
				if($val->payhead->pay_type == 'nothing') {
					$nothingAdditionTonetPayT += $val->amount;
				}
			}
		}
	
		// Calculate the gross amount
		$grossT = $row->gross + $row->paid_time_off;
	
		// Get the pay type
		$pay_type = $row->user->employeeProfile->pay_type;
		$diff = date_diff(date_create($row->user->employeeProfile->dob), date_create(date("Y-m-d")));
		$dob = $diff->format('%y');
		$days = $row->total_hours;
	
		// Calculate deductions and net pay based on pay type
		if ($pay_type == 'hourly' || $pay_type == 'weekly') {
            if ($dob <= 60) {
                $medical_benefitsT = ($grossT * $medical_less_60_amt) / 100;
            } else if ($dob > 60 && $dob <=79 ) {
                $medical_benefitsT = ($grossT * $medical_gre_60_amt) / 100;
            } else if ($dob > 70 ) {
                $medical_benefitsT = 0;
            }

            $social_securityT = ( $grossT>1500 ? ((1500*$social_security_amt) / 100) : ($grossT*$social_security_amt) / 100 );  
            $social_securityT_employer = ( $grossT>1500 ? ((1500*$social_security_employer_amt) / 100) : ($grossT*$social_security_employer_amt) / 100 );  
            $education_lveyT = ($grossT<=125?0: ($grossT>1154?( ((1154-125)*$education_levy_amt) / 100) + ( (($grossT-1154)*$education_levy_amt_5) / 100 ) : ( (($grossT-125)*$education_levy_amt) /100)));
            $mbse_deductions = $medical_benefitsT + $social_securityT + $education_lveyT;
            $net_pay = $grossT - $mbse_deductions;
        } else if ($pay_type == 'bi-weekly') {
            //$medical_benefitsT = ($grossT * 3.5) / 100;
            if ($dob <= 60) {
                $medical_benefitsT = ($grossT * $medical_less_60_amt) / 100;
            } else if ($dob > 60 && $dob <=79 ) {
                $medical_benefitsT = ($grossT * $medical_gre_60_amt) / 100;
            } else if ($dob > 70 ) {
                $medical_benefitsT = 0;
            }

            if ($days <= 7) {
                $social_securityT = ( $grossT>3000 ? ((3000*$social_security_amt) / 100) : ($grossT*$social_security_amt) / 100 ); 
                $social_securityT_employer = ( $grossT>3000 ? ((3000*$social_security_employer_amt) / 100) : ($grossT*$social_security_employer_amt) / 100 ); 
            } else {
                $social_securityT = ( $grossT>3000 ? ((3000*$social_security_amt) / 100) : ($grossT*$social_security_amt) / 100 ); 
                $social_securityT_employer = ( $grossT>3000 ? ((3000*$social_security_employer_amt) / 100) : ($grossT*$social_security_employer_amt) / 100 ); 
            }
            $education_lveyT = ($grossT<=250?0:($grossT>2308?(((2308-250)*$education_levy_amt)/100)+((($grossT-2308)*$education_levy_amt_5)/100):((($grossT-250)*$education_levy_amt)/100)));
            $mbse_deductions = $medical_benefitsT + $social_securityT + $education_lveyT;
            $net_pay = $grossT - $mbse_deductions;
            if ($days <= 7) {
            } else {
                $net_pay = 2 * $net_pay;                
            }
        } else if ($pay_type == 'semi-monthly') {
            if ($dob <= 60) {
                $medical_benefitsT = ($grossT * $medical_less_60_amt) / 100;
            } else if ($dob > 60 && $dob <=79 ) {
                $medical_benefitsT = ($grossT * $medical_gre_60_amt) / 100;
            } else if ($dob > 70 ) {
                $medical_benefitsT = 0;
            }
            $social_securityT = ( $grossT>3000 ? ((3000*$social_security_amt) / 100) : ($grossT*$social_security_amt) / 100 ); 
            $social_securityT_employer = ( $grossT>3000 ? ((3000*$social_security_employer_amt) / 100) : ($grossT*$social_security_employer_amt) / 100 ); 
            $education_lveyT = ($grossT<=125?0:($grossT>2500?(((2500-270.84)*$education_levy_amt)/100)+((($grossT-2500)*$education_levy_amt_5)/100):((($grossT-270.84)*$education_levy_amt)/100)));
            $mbse_deductions = $medical_benefitsT + $social_securityT + $education_lveyT;
            $net_pay = $grossT - $mbse_deductions;
        } else if ($pay_type == 'monthly') {
            if ($dob <= 60) {
                $medical_benefitsT = ($grossT * $medical_less_60_amt) / 100;
            } else if ($dob > 60 && $dob <=79 ) {
                $medical_benefitsT = ($grossT * $medical_gre_60_amt) / 100;
            } else if ($dob > 70 ) {
                $medical_benefitsT = 0;
            }
            $social_securityT = ( $grossT>6500 ? ((6500*$social_security_amt) / 100) : ($grossT*$social_security_amt) / 100 ); 
            $social_securityT_employer = ( $grossT>6500 ? ((6500*$social_security_employer_amt) / 100) : ($grossT*$social_security_employer_amt) / 100 ); 
            $education_lveyT = ($grossT<=125?0:($grossT>5000?(((5000-541.67)*$education_levy_amt)/100)+((($grossT-5000)*$education_levy_amt_5)/100):((($grossT-541.67)*$education_levy_amt)/100)));
            $mbse_deductions = $medical_benefitsT + $social_securityT + $education_lveyT;
            $net_pay = $grossT - $mbse_deductions;
        }
	
		// Calculate employee pay and total payroll
		$employeePayT = $grossT - $mbse_deductions + $nothingAdditionTonetPayT - $deductionsT;
		$TotalPayroll += $employeePayT + $mbse_deductions + $row->security_employer;
		
		$dateRange = date('M j, Y', strtotime($row->start_date)) . ' - ' . date('M j, Y', strtotime($row->end_date));
		
		// Get month from start_date
		$month = date('F Y', strtotime($row->start_date));

		// Return final array with totals
		return [
			'total_amount' => $employeePayT + $mbse_deductions + $row->security_employer,
			'month' => $month,
			'dateRange' => $dateRange
		];
	}
}
