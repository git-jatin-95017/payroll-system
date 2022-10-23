<?php

namespace App\Http\Controllers\employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MyProfileController extends Controller
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

	public function edit($id)
	{		
		$employee = User::find($id);

		$disabled = 'disabled="disabled"';

		if ($employee->is_proifle_edit_access == 1) {
			$disabled = '';
		}

	   	return view('employee.profile.edit', compact('employee', 'disabled'));
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
			$employee->employeeProfile->updateOrCreate(
			    ['user_id' => auth()->user()->id],
			    $data
			);

		} else if ($data['update_request'] == 'payment') {
			$request->validate([
				'routing_number' => ['required'],
				'account_number' => ['required'],
				'account_type' => ['required'],
				'payment_method' => ['required'],
			], [], []);

			$data = [
				'routing_number' => $request->routing_number,
				'account_number' => $request->account_number,
				'account_type' => $request->account_type,
				'payment_method' => $request->payment_method
			];

			unset($data['update_request']);

			$employee->paymentProfile->updateOrCreate(
			    ['user_id' => auth()->user()->id],
			    $data
			);
		} else if ($data['update_request'] == 'changepwd') {

			$user = User::where('email', $request->email_address)->first();

			if (!$user) {
				return redirect()->back()->with('error', 'Email does not exist.');
			}

			$request->validate([
				'email_address' => ['required', 'email'],
				'old_password' => ['required'],
				'password' => ['required', 'string', 'min:8', 'confirmed'],
				'password_confirmation' => 'required_with:password',
			], [], [
				'old_password' => 'current password'
			]);

			unset($data['update_request']);

			$user->password = Hash::make($data['password_confirmation']);
			
			$user->save();
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
				// 'bank_name' => $request->bank_name,
				// 'bank_acc_number' => $request->bank_acc_number,
				// 'ifsc_code' => $request->ifsc_code,
				// 'pf_account_number' => $request->pf_account_number,
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

		   	unset($data['update_request']);
			$employee->employeeProfile->update($data);
		}
	
		return redirect()->back()->with('message', 'Records updated successfully.');	
	}   
}
