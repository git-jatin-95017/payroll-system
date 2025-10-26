<?php

namespace App\Http\Controllers\employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PaymentDetail;
use App\Models\EmployeeProfile;

use App\Models\PayrollSheet;
use App\Models\Attendance;
use App\Models\LeaveType;
use App\Models\PayrollAmount;
use App\Models\AdditionalEarning;
use App\Models\AdditionalPaid;
use App\Models\AdditionalUnPaid;
use DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Barryvdh\Snappy\Facades\SnappyPdf;
use App\Models\Setting;
use App\Traits\PayrollCalculationTrait;

class MyProfileController extends Controller
{
	use PayrollCalculationTrait;
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function edit($id)
	{		
		$employee = User::find($id);

		$disabled = '';
		$disabledDrop = false;

		if ($employee->is_proifle_edit_access == "1") {
			$disabled = 'readonly="readonly"';
			$disabledDrop = (int) true;
		}

	   	return view('employee.profile.edit', compact('employee', 'disabled', 'disabledDrop'));
	}

	public function update(Request $request, $id)
	{
		$data = $request->all();
		
		$employee = User::find($id);

		if ($data['update_request'] == 'empprofile') {
			$request->validate([
				'doj' => ['required', 'date'],
				'emp_type' => ['required'],
				'pay_type' => ['required'],				
				'pay_rate' => ['required']				
			], [], [
				'pay_rate' => 'Amount',
				'doj' => 'Start Date',
			]);

			unset($data['update_request']);
			EmployeeProfile::updateOrCreate(
			    ['user_id' => auth()->user()->id],
			    $data
			);

		} else if ($data['update_request'] == 'payment') {
			$request->validate([
				'payment_method' =>['required'],
				'routing_number' => ['required_if:payment_method,==,deposit'],
				'account_number' => ['required_if:payment_method,==,deposit'],
				'account_type' => ['required_if:payment_method,==,deposit']
			], [], []);

			$data = [
				'routing_number' => $request->routing_number ?? '',
				'account_number' => $request->account_number ?? '',
				'account_type' => $request->account_type ?? '',
				'payment_method' => $request->payment_method ?? '',
				'bank_name' => $request->bank_name,
				// 'bank_acc_number' => !empty($request->bank_acc_number) ? $request->bank_acc_number : NULL,
			];

			unset($data['update_request']);

			PaymentDetail::updateOrCreate(
			    ['user_id' => auth()->user()->id],
			    $data
			);
		} else if ($data['update_request'] == 'changepwd') {

			// $user = User::where('email', $request->email_address)->first();

			// if (!$user) {
			// 	return redirect()->back()->with('error', 'Email does not exist.');
			// }

			$request->validate([
	//			'email_address' => ['required', 'email'],
				// 'old_password' => ['required'],
				'password' => ['required', 'string', 'min:8', 'confirmed'],
				'password_confirmation' => 'required_with:password',
			], [], [
				'old_password' => 'current password'
			]);

			unset($data['update_request']);

			$employee->password = Hash::make($data['password_confirmation']);
			
			$employee->save();
		} else {
			$request->validate([
				'first_name' => ['required'],
				'last_name' => ['required'],
				'marital_status' => ['required'],
				// 'emp_type' => ['required'],
				// 'pay_type' => ['required'],
				'dob' => ['required', 'date'],
				// 'doj' => ['required', 'date'],
				'country' => ['required'],
				'address' => ['required'],
				'nationality' => ['required'],
				// 'pay_rate' => ['required'],
				// 'mobile' => ['required'],
				'email' => ['required', 'email', 'max:191', 'unique:users,email,'.$employee->id],	
				// 'identity_document' => ['required'],
				// 'identity_number' => ['required'],
				// 'emp_type' => ['required'],
				// 'doj' => ['required', 'date'],
				// 'designation' => ['required'],
				// 'department' => ['required'],
				// 'password' => ['required', 'string', 'min:8', 'confirmed'],
				// 'password_confirmation' => 'required_with:password',
				'file' => 'nullable|mimes:png,jpg,jpeg|max:2048'
			], [], [
				'emp_code' => 'Employee ID number',
				// 'doj' => 'Start Date',
			]);

			$employee->update([
				'name' => $data['first_name'] . ' '. $data['last_name'],
				'email' => $data['email'],
				'phone_number' => $data['phone_number'],
			]);

			$data = [
				'first_name' => $request->first_name,
				'last_name' => $request->last_name,
				'dob' => $request->dob,
				'gender' => $request->gender,
				'marital_status' => $request->marital_status,
				'nationality' => $request->nationality,
				'blood_group' => NULL,
				'city' => $request->city,
				'address' => $request->address,
				'state' => NULL,
				'country' => $request->country,
				'mobile' => NULL,
				'phone_number' => $request->phone_number,
				'identity_document' => $request->identity_document,
				'identity_number' => $request->identity_number,
				// 'emp_type' => $request->emp_type,
				// 'pay_type' => $request->pay_type,
				// 'pay_rate' => $request->pay_rate,
				// 'doj' => $request->doj,
				// 'designation' => $request->designation,
				// 'department' => $request->department,
				//'pan_number' => $request->pan_number,
				'bank_name' => $request->bank_name,
				'bank_acc_number' => !empty($request->bank_acc_number) ? $request->bank_acc_number : NULL,
				// 'ifsc_code' => $request->ifsc_code,
				// 'pf_account_number' => $request->pf_account_number,
				'manager_position' => $request->manager_position ?? NULL,
				'manager' => $request->manager ?? NULL,
				'em_name' => $request->em_name ?? NULL,
				'em_number' => $request->em_number ?? NULL,
				'fb_url' => $request->fb_url ?? NULL,
				'linkden_url' => $request->linkden_url ?? NULL,
				
			];

			if ($request->file('file')) {
				$oldFile = $employee->employeeProfile->file;
				if (\File::exists(public_path('files/'.$oldFile))) {
					\File::delete(public_path('files/'.$oldFile));
				}

		        $file = $request->file('file');
		        $filename = time().'_'.$file->getClientOriginalName();

		        // File upload location
		        $location = 'files';

		        // Upload file
		        $file->move($location, $filename);

		        $data['file'] = $filename;
		   	}

		   	//Logo
		   	if ($request->file('logo')) {
				$oldLogo = $employee->employeeProfile->logo;
				if (\File::exists(public_path('files/'.$oldLogo))) {
					\File::delete(public_path('files/'.$oldLogo));
				}

		        $file2 = $request->file('logo');
		        $filename2 = time().'_'.$file2->getClientOriginalName();

		        // File upload location
		        $location2 = 'files';

		        // Upload file
		        $file2->move($location2, $filename2);

		        $data['logo'] = $filename2;
		   	}

		   	unset($data['update_request']);
			$employee->employeeProfile->update($data);
		}
	
		return redirect()->back()->with('message', 'Records updated successfully.');	
	}   


	public function myPayslip() {
		$data = PayrollAmount::where('status', 1)->where('user_id', auth()->user()->id)->get();

		$settings = Setting::find(1);
		
		// Calculate amounts using the trait for consistency
		$calculatedData = [];
		foreach($data as $row) {
			$calculatedData[] = [
				'row' => $row,
				'amounts' => $this->calculatePayrollAmounts($row, $settings)
			];
		}

		return view('employee.profile.slip', compact('data', 'settings', 'calculatedData'));
	}


	public function downloadPdf(Request $request) {
		$settings = Setting::find(1);

	    $zip = new \ZipArchive();
	    $tempFilename = tempnam(sys_get_temp_dir(), 'pdf_zip_');
    	$zip->open($tempFilename, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

		$data = PayrollAmount::where('id', $request->id)->first();

		$empId = $data->user_id;
		// $allApprovedData = PayrollAmount::where('start_date', '>=', date('Y-01-01'))
		// 	->where('end_date', '<=', date('Y-12-31'))
		// 	->where('status', 1)->where('user_id', $empIds[$i])
		// 	->get();
		
		$parameters = [
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'approval_number' => $request->approval_number,
            'data' => $data,
            // 'allApprovedData' => $allApprovedData,
            'settings' => $settings
        ];

	    $pdf = SnappyPdf::loadView('client.pdf.salary', $parameters);
	    $pdf->setOption('margin-top', 10);
	    $pdf->setOption('margin-right', 10);
	    $pdf->setOption('margin-bottom', 10);
	    $pdf->setOption('margin-left', 10);

	    $filename = 'Salary_Slip_' . str_replace(' ', '_', ucfirst($data->user->name)) . '.pdf';
	    $zip->addFromString($filename, $pdf->output());
	
    	
    	// Add Direct Deposit PDF to the zip only if not skipping
    	if (!$request->has('no_dds_download')) {
    		$pdf2 = $this->generateDirectDepositPdf($request, $empId);
    		$zip->addFromString('Direct_Deposit_' . date('Y-m-d') . '.pdf', $pdf2->output());
    	}

	    $zip->close();
   
    	return response()->download($tempFilename, 'employees.zip')->deleteFileAfterSend(true);
	}

	private function generateDirectDepositPdf(Request $request, $empId) {
		$settings = Setting::find(1);
	    // Query by user_id (empId) for direct deposit
	    $data2 = PayrollAmount::with(['user.employeeProfile', 'user.paymentProfile', 'additionalEarnings.payhead'])
	    	->where('user_id', $empId)
	    	->where('id', $request->id)
	    	->get();

	    $parameters = [
	    	'data' => $data2,
	    	'settings' => $settings
	    ];
	    $pdf = SnappyPdf::loadView('client.pdf.deposit', $parameters);
	    $pdf->setOption('margin-top', 10);
	    $pdf->setOption('margin-right', 10);
	    $pdf->setOption('margin-bottom', 10);
	    $pdf->setOption('margin-left', 10);

	    return $pdf;
	}
}
