<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PayrollSheet;
use App\Models\User;
use App\Models\Attendance;
use DataTables;
use Yajra\DataTables\Html\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PayrollController extends Controller
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
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Builder $builder)
	{

		return view('payroll.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(Request $request)
	{
		$week = $request->week ?? date('W');

		$year = $request->year ?? date('Y');

		$month = date('F', strtotime($year.'-W'.$week));

		$employees = User::where('role_id', 3)->get();

		$tempDatesArr = [];

		foreach ($employees as $k => $v) {
			if (!empty($v->payrollSheet)) {
				foreach ($v->payrollSheet as $key => $value) {	
					$tempDatesArr[$v->id][$value->payroll_date] = $value->daily_hrs;
				}
			}
		}

	   	return view('client.payroll.create', compact('employees', 'tempDatesArr', 'week', 'year', 'month'));
	}

	public function store(Request $request)
	{   
		if ($request->ajax()) {
			$data = $request->all();

			$isExist = PayrollSheet::where('emp_id', $data['emp_id'])->where('payroll_date', $data['payroll_date'])->count();

			if ($isExist) {
				$isExist = PayrollSheet::where('emp_id', $data['emp_id'])->where('payroll_date', $data['payroll_date'])->first();
				$isExist->payroll_date = $data['payroll_date'];
				$isExist->daily_hrs = $data['daily_hrs'];
				$isExist->save();
			} else{
				PayrollSheet::create([
					'payroll_date' => $data['payroll_date'],
					'daily_hrs' => $data['daily_hrs'],
					'emp_id' => $data['emp_id'],
					'created_at' => date('Y-m-d H:i:s')
				]);	
			}	

			return response()->json(['status'=>true, 'message'=>"Record saved successfully."]);
		}	
	}

	protected function differenceInHours($startdate,$enddate){
		$starttimestamp = strtotime($startdate);
		$endtimestamp = strtotime($enddate);
		$difference = abs($endtimestamp - $starttimestamp)/3600;
		return $difference;
	}

	public function registerEntry() {
		$dateStart = 1;
		$dateEnd = date('t');

		$employees = User::where('role_id', 3)->get();

		foreach($employees as $k => $v) {
			for($i = 1; $i <= $dateEnd; $i++) {
				$dateS = date('Y-m-'.$i);

				$count = Attendance::where('attendance_date', $dateS)->count();				

				$hours = 8;

				if ($count == 2) {
					$attendance = Attendance::where('attendance_date', $dateS)->get();					

					$hours = $this->differenceInHours($attendance[0]->action_time, $attendance[1]->action_time);

				}

				$isExist = PayrollSheet::where('emp_id', $v->id)->where('payroll_date', $dateS)->first();

				if (!$isExist) {				
					PayrollSheet::create([
						'emp_id' => $v->id,
						'hourly_pay' => $v->employeeProfile->pay_rate,
						'payroll_date' => $dateS,
						'daily_hrs' => $hours,
						'created_at' => date('Y-m-d'),
					]);
				}
			}
		}

		die('-------SUCCESS-------');
	}

}