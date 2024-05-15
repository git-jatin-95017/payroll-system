<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PayrollSheet;
use App\Models\User;
use App\Models\Attendance;
use App\Models\LeaveType;
use App\Models\PayrollAmount;
use App\Models\AdditionalEarning;
use App\Models\AdditionalPaid;
use App\Models\AdditionalUnPaid;
use DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\PaymentDetail;
use Barryvdh\Snappy\Facades\SnappyPdf;
use App\Models\Setting;

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

	public function listPayroll(Request $request) {
		// $results = PayrollSheet::where('approval_status', 1)->get();

		$results = PayrollSheet::where('approval_status', 1)
			->join('users', function($join) {
	            $join->on('users.id', '=', 'payroll_sheets.emp_id')
	            	 ->where('users.created_by', auth()->user()->id);
	        })
			->orderBy('appoval_number')->whereNotNull('date_range')->get()->groupBy(function($item) {
		     return $item->appoval_number;
		});

		return view('client.payroll.list', compact('results'));
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function stepOne(Request $request)
	{
		$employees = User::where('role_id', 3)->where('status', 1)->get();

		$from = $request->start_date; //date('Y-m-01'); //date('m-01-Y');
		$to = $request->end_date; //date('Y-m-t'); //date('m-t-Y');
		$appoval_number = $request->number; //date('Y-m-t'); //date('m-t-Y');

		$settings = Setting::find(1);

		return view('client.payroll.step1', compact(
			'employees', 'from', 'to', 'appoval_number',
			'settings'
		));
	}

	public function storeStepOne(Request $request)
	{   		
		$data = $request->all();

		$settings = Setting::find(1);

		if (!empty($data['input'])) {
			foreach($data['input'] as $k => $v) {
				if (!empty($v['id'])) {
					$payroll =  PayrollAmount::findOrFail($v['id']);

					$medical_less_60_amt = $v['medical_less_60_amt'] ?? $settings->medical_less_60_amt;
					$medical_gre_60_amt = $v['medical_gre_60_amt'] ?? $settings->medical_gre_60_amt;
					$social_security_amt = $v['social_security_amt'] ?? $settings->social_security_amt;
					$social_security_employer_amt = $v['social_security_employer_amt'] ?? $settings->social_security_employer_amt;
					$education_levy_amt = $v['education_levy_amt'] ?? $settings->education_levy_amt;

					$payroll->update([
						'user_id' => $k,
						'start_date' => $v['start_date'],
						'end_date' => $v['end_date'],
						'total_hours' => (float) $v['working_hrs'],
						'notes' => $v['notes']??null,
						'gross' => (float) $v['gross'],
						'reimbursement' => (float) $v['reimbursement'] ?? 0,
						'overtime_hrs' => (float) $v['overtime_hrs'] ?? 0,
						'doubl_overtime_hrs' => (float) $v['double_overtime_hrs'] ?? 0,
						'overtime_calc' => (float) $v['overtime_calc'] ?? 0,
						'doubl_overtime_calc' => (float) $v['doubl_overtime_calc'] ?? 0,
						'gross' => (float) $v['gross'] ?? 0,
						'medical' => (float) $v['medical'] ?? 0,
						'security' => (float) $v['security'] ?? 0,
						'security_employer' => (float) $v['security_employer'] ?? 0,
						'net_pay' => (float) $v['net_pay'] ?? 0,
						'edu_levy' => (float) $v['edu_levy'] ?? 0,
						'status' => 0,
						'medical_less_60' => $medical_less_60_amt,
						'medical_gre_60' => $medical_gre_60_amt,
						'social_security' => $social_security_amt,
						'social_security_employer' => $social_security_employer_amt,
						'education_levy' => $education_levy_amt
					]);
				} else {
					$medical_less_60_amt = $v['medical_less_60_amt'] ?? $settings->medical_less_60_amt;
					$medical_gre_60_amt = $v['medical_gre_60_amt'] ?? $settings->medical_gre_60_amt;
					$social_security_amt = $v['social_security_amt'] ?? $settings->social_security_amt;
					$social_security_employer_amt = $v['social_security_employer_amt'] ?? $settings->social_security_employer_amt;
					$education_levy_amt = $v['education_levy_amt'] ?? $settings->education_levy_amt;

					$payroll = PayrollAmount::create([
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
						'gross' => (float) $v['gross'] ?? 0,
						'medical' => (float) $v['medical'] ?? 0,
						'security' => (float) $v['security'] ?? 0,
						'security_employer' => (float) $v['security_employer'] ?? 0,
						'net_pay' => (float) $v['net_pay'] ?? 0,
						'edu_levy' => (float) $v['edu_levy'] ?? 0,
						'status' => 0,
						'medical_less_60' => $medical_less_60_amt,
						'medical_gre_60' => $medical_gre_60_amt,
						'social_security' => $social_security_amt,
						'social_security_employer' => $social_security_employer_amt,
						'education_levy' => $education_levy_amt
					]);
				}

				foreach($v['earnings'] as $key => $value) {

					AdditionalEarning::where('user_id', $k)->where('payroll_amount_id', $payroll->id)->where('payhead_id', $value['payhead_id'])->delete();
					// if (!empty($value['id'])) {
					// 	$ae =  AdditionalEarning::findOrFail($value['id']);
					// 	$ae->update([
					// 		// 'payroll_amount_id' => $run->id,
					// 		// 'user_id' => $run->user_id,
					// 			//'payhead_id' => $value['payhead_id'],
					// 		'amount' => (float) $value['amount']
					// 	]);					
					// } else {
						AdditionalEarning::create([
							'payroll_amount_id' => $payroll->id,
							'user_id' => $payroll->user_id,
							'payhead_id' => $value['payhead_id'],
							'amount' => (float) $value['amount']
						]);					
					// }
				}
			}
		}

		return redirect()->route('list.step2', ['start_date' => $request->start_date, 'end_date' => $request->end_date, 'appoval_number' => $request->appoval_number])->with('message', 'Payroll saved succesfully.');	
		
	}

	
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function stepTwo(Request $request)
	{
		$employees = User::where('role_id', 3)->where('status', 1)->get();

		$from = $request->start_date; //date('Y-m-01'); //date('m-01-Y');
		$to = $request->end_date; //date('Y-m-t'); //date('m-t-Y');
		$appoval_number = $request->appoval_number; //date('Y-m-t'); //date('m-t-Y');

		return view('client.payroll.step2', compact('employees', 'from', 'to', 'appoval_number'));
	}

	public function storeStepTwo(Request $request)
	{   		
		$data = $request->all();

		if (!empty($data['input'])) {
			foreach($data['input'] as $k => $v) {
				if (!empty($v['id'])) {
					$payroll =  PayrollAmount::findOrFail($v['id']);

					$newGross = 0;
					foreach($v['earnings'] as $key => $value) {
						AdditionalPaid::where('user_id', $k)->where('payroll_amount_id', $payroll->id)->where('leave_type_id', $value['leave_type_id'])->delete();
						// if (!empty($value['id'])) {
						// 	$ae =  AdditionalEarning::findOrFail($value['id']);
						// 	$ae->update([
						// 		// 'payroll_amount_id' => $run->id,
						// 		// 'user_id' => $run->user_id,
						// 			//'payhead_id' => $value['payhead_id'],
						// 		'amount' => (float) $value['amount']
						// 	]);					
						// } else {
							AdditionalPaid::create([
								'payroll_amount_id' => $payroll->id,
								'user_id' => $payroll->user_id,
								'leave_type_id' => $value['leave_type_id'],
								'amount' => (float) $value['amount'],							
								'leave_balance' => (float) $value['leave_balance'],							
							]);				

							$newGross += (float) $value['amount'];
						// }
					}

					if(array_key_exists('earnings_unpaid', $v) && count($v['earnings_unpaid']) > 0) {
						foreach($v['earnings_unpaid'] as $key => $value) {
							AdditionalUnPaid::where('user_id', $k)->where('payroll_amount_id', $payroll->id)->where('leave_type_id', $value['leave_type_id_unpaid'])->delete();
							AdditionalUnPaid::create([
								'payroll_amount_id' => $payroll->id,
								'user_id' => $payroll->user_id,
								'leave_type_id' => $value['leave_type_id_unpaid'],
								'amount' => (float) $value['amount_unpaid'],	//hrs actual field name wrong in db by mistake			
								'leave_balance' => (float) $value['leave_balance_unpaid'],				
							]);
						}
					}

					$payroll->update([						
						//'vacation_hrs' => (float) $v['vacation_hrs'],
						//'sick_hrs' => (float) $v['sick_hrs'],					
						'status' => 0,
						'paid_time_off' => (float) $v['paid_time_off']
						// 'gross' => $payroll->gross + $newGross,
					]);
				}

			}
		}

		return redirect()->route('calculating-payroll', ['start_date' => $request->start_date,'end_date' => $request->end_date,'appoval_number' => $request->appoval_number])->with('message', 'Payroll Computed succesfully!!!');	
		
	}

	public function saveName(Request $request) {
		$number = $request->get('key');

		$name = $request->get('name');

		$result = PayrollSheet::where('appoval_number', $number)->update(['payroll_name' => $name]);

		return response()->json($result);
	}

	public function deletePayroll(Request $request) {
		$number = $request->query('appoval_number');
		$record = PayrollSheet::where('appoval_number', $number)->first();

		$date = explode(' - ', $record->date_range);
		$result = PayrollSheet::where('appoval_number', $number)->update([
			'payroll_name' => NULL, 
			'approval_status' => 0
		]);

		$pprecords = PayrollAmount::where('start_date', '>=', $date[0])
			->where('end_date', '<=', $date[1])->get();

		foreach($pprecords as $k => $v) {
			$v->delete();
		}

		return back()->with('message','Record deleted successfully.');
	}

	public function showConfirmation (Request $request) {
		$empIds = PayrollSheet::where('approval_status', 1)
			->join('users', function($join) {
 	            $join->on('users.id', '=', 'payroll_sheets.emp_id')->where('status', 1);
 	        })
			->where('appoval_number', $request->appoval_number)
			->whereNotNull('payroll_date')
			->select('emp_id')
			->get()
			->pluck('emp_id');

		$data = PayrollAmount::where('start_date', '>=', $request->start_date)->where('end_date', '<=', $request->end_date)->where('status', !empty($request->is_green) ? 1 :0)->whereIn('user_id', $empIds)->get();

		$totalPayroll = collect($data)->sum(function ($row) { return (float) $row->gross; });
		
		
		$deductions = 0;
		$earnings = 0;

		if (count($data) > 0){
			foreach($data as $res) {
				foreach($res->additionalEarnings as $key => $val) {
					if($val->payhead->pay_type =='earnings') {
						$earnings += $val->amount;
					}

					if($val->payhead->pay_type =='deductions') {
						$deductions += $val->amount;
					}
				}
			}
		}
		
		$ids = collect($data)->pluck('id');

		$taxes = collect($data)->sum(function ($row) { return (float) ($row->medical + $row->security + $row->edu_levy); });

		$medicalTotal = collect($data)->sum(function ($row) { return (float) $row->medical; });
		$securityTotal = collect($data)->sum(function ($row) { return (float) $row->security; });
		$securityEmployerTotal = collect($data)->sum(function ($row) { return (float) $row->security_employer; });
		$eduLevytotal = collect($data)->sum(function ($row) { return (float) $row->edu_levy; });
		
		$userIds = collect($data)->pluck('user_id');

		$paymentDetails = PaymentDetail::whereIn('user_id', $userIds)->get();

		$directDeposits = collect($paymentDetails)->where('payment_method', 'Direct Deposit')->count();
		$cheques = collect($paymentDetails)->where('payment_method', 'check')->count();

		$dataGraph = [$totalPayroll, $taxes, $deductions];

		return view('client.payroll.confirmation', [
			'start_date' => $request->start_date, 
			'end_date' => $request->end_date, 
			'appoval_number' => $request->appoval_number,
			'totalPayroll' => $totalPayroll,
			'taxes' => $taxes,
			'directDeposits' => $directDeposits,
			'cheques' => $cheques,
			'medicalTotal' => $medicalTotal,
			'securityTotal' => $securityTotal,
			'eduLevytotal' => $eduLevytotal,
			'securityEmployerTotal' => $securityEmployerTotal,
			'data' => $data,
			'ids' => $ids,
			'dataGraph' => $dataGraph,
		]);
	}

	public function saveConfirmation(Request $request) {
		$ids = $request->ids;

		if (count($ids) > 0) {
			foreach($ids as $id) {
				$payroll = PayrollAmount::findOrFail($id);
				$payroll->update(['status' => 1]);
			}
		}

		return redirect()->route('list.payroll')->with('message', 'Payroll confirmed succesfully.');
	}

	public function downloadPdf(Request $request) {
		$empIds = PayrollSheet::where('approval_status', 1)
			->join('users', function($join) {
 	            $join->on('users.id', '=', 'payroll_sheets.emp_id')->where('status', 1);
 	        })
			->where('appoval_number', $request->appoval_number)
			->whereNotNull('payroll_date')
			->select('emp_id')
			->get()
			->pluck('emp_id')->unique()->values()->all();

	    $zip = new \ZipArchive();
	    $tempFilename = tempnam(sys_get_temp_dir(), 'pdf_zip_');
    	$zip->open($tempFilename, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

		for ($i = 0; $i < count($empIds); $i++) {
			$data = PayrollAmount::where('start_date', '>=', $request->start_date)
				->where('end_date', '<=', $request->end_date)
				->whereIn('status', [0,1])
				->where('user_id', $empIds[$i])
				->first();

			$allApprovedData = PayrollAmount::where('start_date', '>=', date('Y-01-01'))
				->where('end_date', '<=', date('Y-12-31'))
				->where('status', 1)->where('user_id', $empIds[$i])
				->get();
			
			$parameters = [
	            'start_date' => $request->start_date,
	            'end_date' => $request->end_date,
	            'approval_number' => $request->approval_number,
	            'data' => $data,
	            'allApprovedData' => $allApprovedData,
	        ];

		    $pdf = SnappyPdf::loadView('client.pdf.salary', $parameters);
		    $pdf->setOption('margin-top', 10);
		    $pdf->setOption('margin-right', 10);
		    $pdf->setOption('margin-bottom', 10);
		    $pdf->setOption('margin-left', 10);

		    $filename = 'Salary_Slip_' . str_replace(' ', '_', ucfirst($data->user->name)) . '.pdf';
		    $zip->addFromString($filename, $pdf->output());
		}
    	
    	// Add Direct Deposit PDF to the zip
    	$pdf2 = $this->generateDirectDepositPdf($request, $empIds);
	    $zip->addFromString('Direct_Deposit_' . date('Y-m-d') . '.pdf', $pdf2->output());

	    $zip->close();
   
    	return response()->download($tempFilename, 'employees.zip')->deleteFileAfterSend(true);
	}

	private function generateDirectDepositPdf(Request $request, $empIds) {
	    $data2 = PayrollAmount::where('start_date', '>=', $request->start_date)
	    	->where('end_date', '<=', $request->end_date)
	    	->whereIn('status', [0,1])
	    	->whereIn('user_id', $empIds)
	    	->get();

	    $parameters = [
	    	'data' => $data2,
	    ];
	    $pdf = SnappyPdf::loadView('client.pdf.deposit', $parameters);
	    $pdf->setOption('margin-top', 10);
	    $pdf->setOption('margin-right', 10);
	    $pdf->setOption('margin-bottom', 10);
	    $pdf->setOption('margin-left', 10);

	    return $pdf;
	}
}