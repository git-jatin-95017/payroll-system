<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PayrollSheet;
use App\Models\User;
use App\Models\Attendance;
use App\Models\LeaveType;
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
		
		$input = $request->all();

		if (array_key_exists('daterange', $input)) {
			$arr = explode(' - ', $input['daterange']);

			$request['start_date'] = date('Y-m-d', strtotime($arr[0]));

			$request['end_date'] = date('Y-m-d', strtotime($arr[1]));
		} else{
			$request['start_date'] = date('Y-m-d');

			$request['end_date'] = date('Y-m-d', strtotime('+1 week'));
		}

		$query = User::select(
			'users.id',
			'users.role_id',
			'users.name',
			'users.email',
			'users.user_code',
			'users.phone_number',
			'users.status',
			'employee_profile.first_name',
			'employee_profile.last_name',
			'employee_profile.dob',
			'employee_profile.file',
			'employee_profile.logo',
			'employee_profile.user_id',
		)->leftJoin('employee_profile', function($join) {
                $join->on('users.id', '=', 'employee_profile.user_id');
        });

		if(!empty($request->search)) {
			$searchValue = $request->search;
			
			$query->leftJoin('emp_departments', function($join) use($searchValue) {
                $join->on('users.id', '=', 'emp_departments.user_id');
        	})->leftJoin('departments', function($join) use($searchValue) {
                $join->on('departments.id', '=', 'emp_departments.department_id');
        	});

			$query->where('employee_profile.first_name', 'like', '%' . $searchValue . '%');
			$query->orWhere('employee_profile.last_name', 'like', '%' . $searchValue . '%');
			$query->orWhere('departments.dep_name', 'like', '%' . $searchValue . '%');
		}

		$employees = $query->where('role_id', 3)->groupBy('users.id')->get();

		$tempDatesArr = [];

		foreach ($employees as $k => $v) {
			if (!empty($v->payrollSheet)) {
				foreach ($v->payrollSheet as $key => $value) {	
					$tempDatesArr[$v->id][$value->payroll_date] = [
						'hrs' => $value->daily_hrs,
						'approval_status' => $value->approval_status
					];
				}
			}
		}
		// dd($tempDatesArr);

	   	return view('client.payroll.create', compact('employees', 'tempDatesArr', 'request'));
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
		} else {
			//Approve
			$data = $request->all();

			$arrDates = $data['dates'];

			if (!empty($data['check'])) {
				foreach($data['check'] as $k => $v) {
					if($v == 0) {
						if (array_key_exists($k, $arrDates)) {
							$arr = $arrDates[$k];

							foreach($arr as $dateKey => $value) {
								if (!is_null($value)) {
									$isExist = PayrollSheet::where('emp_id', $k)->where('payroll_date', $dateKey)->first();
									$isExist->approval_status = 1;
									$isExist->save();
								}
							}
						}
					}
				}
			}

			return redirect()->route('payroll.create', ['week_search' => 2])->with('message', 'Payroll entered data approved successfully.');	
		}	
	}

	public function search(Request $request)
	{
		$search = $request->get('codes');
		
		$result = LeaveType::where('name', 'LIKE', '%'. $search. '%')->take(5)->get();
		
		if (count($result) > 0) {
			$data = array();

			foreach ($result as $k => $v)
			{
				$data[$k] = [					
					'full_name' => $v->name. ' ('.ucwords($v->short_name).')',
					'short_name' => $v->short_name					
				];
			}

			return response()->json($data);
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