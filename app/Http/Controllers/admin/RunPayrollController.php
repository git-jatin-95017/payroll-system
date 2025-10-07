<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PayrollSheet;
use App\Models\User;
use App\Models\Attendance;
use App\Models\LeaveType;
use App\Models\PayrollAmount;
use App\Models\AdditionalEarning;
use DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RunPayrollController extends Controller
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
	public function stepOne()
	{
		$employees = User::where('role_id', 3)->get();

		$from = date('Y-m-01'); //date('m-01-Y');
		$to = date('Y-m-t'); //date('m-t-Y');

		return view('admin.payroll.step1', compact('employees', 'from', 'to'));
	}

	public function storeStepOne(Request $request)
	{   		
		$data = $request->all();

		if (!empty($data['input'])) {
			foreach($data['input'] as $k => $v) {
				if (!empty($v['id'])) {
					$payroll =  PayrollAmount::findOrFail($v['id']);

					$payroll->update([
						// 'user_id' => $k,
						// 'start_date' => $v['start_date'],
						// 'end_date' => $v['end_date'],
						'total_hours' => (float) $v['working_hrs'],
						'notes' => $v['notes']??null,
						'gross' => (float) $v['gross'],
						'reimbursement' => (float) $v['reimbursement'] ?? 0,
						'overtime_hrs' => (float) $v['overtime_hrs'] ?? 0,
						'doubl_overtime_hrs' => (float) $v['double_overtime_hrs'] ?? 0,
						'overtime_calc' => (float) $v['overtime_calc'] ?? 0,
						'doubl_overtime_calc' => (float) $v['doubl_overtime_calc'] ?? 0,
						'holiday_pay' => (float) $v['holiday_pay'] ?? 0,						
						'gross' => (float) $v['gross'] ?? 0,
						'medical' => (float) $v['medical'] ?? 0,
						'security' => (float) $v['security'] ?? 0,
						'net_pay' => (float) $v['net_pay'] ?? 0,
						'edu_levy' => (float) $v['edu_levy'] ?? 0,
						'status' => 1,
					]);
				} else {
					$run = PayrollAmount::create([
						'user_id' => $k,
						'start_date' => $v['start_date'],
						'end_date' => $v['end_date'],
						'total_hours' => (float) $v['working_hrs'],
						'notes' => $v['notes']??null,
						'gross' => (float) $v['working_hrs'],
						'reimbursement' => (float) $v['reimbursement'] ?? 0,
						'overtime_hrs' => (float) $v['overtime_hrs'] ?? 0,
						'doubl_overtime_hrs' => (float) $v['double_overtime_hrs'] ?? 0,
						'overtime_calc' => (float) $v['overtime_calc'] ?? 0,
						'doubl_overtime_calc' => (float) $v['doubl_overtime_calc'] ?? 0,
						'holiday_pay' => (float) $v['holiday_pay'] ?? 0,	
						'gross' => (float) $v['gross'] ?? 0,
						'medical' => (float) $v['medical'] ?? 0,
						'security' => (float) $v['security'] ?? 0,
						'net_pay' => (float) $v['net_pay'] ?? 0,
						'edu_levy' => (float) $v['edu_levy'] ?? 0,
						'status' => 1,
					]);
				}

				foreach($v['earnings'] as $key => $value) {
					if (!empty($value['id'])) {
						$ae =  AdditionalEarning::findOrFail($value['id']);
						$ae->update([
							// 'payroll_amount_id' => $run->id,
							// 'user_id' => $run->user_id,
							'payhead_id' => $value['payhead_id'],
							'amount' => (float) $value['amount']
						]);					
					} else {
						AdditionalEarning::create([
							'payroll_amount_id' => $run->id,
							'user_id' => $run->user_id,
							'payhead_id' => $value['payhead_id'],
							'amount' => (float) $value['amount']
						]);					
					}
				}
			}
		}

		return redirect()->route('admin.list.step2')->with('message', 'Pyaroll approved succesfully.');	
		
	}

	
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function stepTwo()
	{
		$employees = User::where('role_id', 3)->get();

		$from = date('Y-m-01'); //date('m-01-Y');
		$to = date('Y-m-t'); //date('m-t-Y');

		return view('admin.payroll.step2', compact('employees', 'from', 'to'));
	}

	public function storeStepTwo(Request $request)
	{   		
		$data = $request->all();

		if (!empty($data['input'])) {
			foreach($data['input'] as $k => $v) {
				if (!empty($v['id'])) {
					$payroll =  PayrollAmount::findOrFail($v['id']);

					$payroll->update([						
						'vacation_hrs' => (float) $v['vacation_hrs'],
						'sick_hrs' => (float) $v['sick_hrs'],					
						'status' => 1,
					]);
				}
			}
		}

		return redirect()->route('admin.calculating-payroll')->with('message', 'Payroll approved succesfully!!!');	
		
	}

}