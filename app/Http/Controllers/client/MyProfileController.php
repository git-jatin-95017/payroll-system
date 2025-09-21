<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CompanyProfile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\PaymentDetail;

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
		$company = User::find($id);
		
		// Fetch existing administrators for this company
		$existingAdmins = User::where('created_by', auth()->user()->id)
			->where('role_id', 2) //Client role - admins created by client have client role
			->select('id', 'name', 'email', 'created_at')
			->get();

	   	return view('client.profile.edit', compact('company', 'existingAdmins'));
	}

	public function update(Request $request, $id)
	{
		$data = $request->all();
		
		$company = User::find($id);

		if ($data['update_request'] == 'payment') {

			$paymentdata = [
				'routing_number' => $request->routing_number ?? '',
				'account_number' => $request->account_number ?? '',
				'account_type' => $request->account_type ?? '',
				'bank_name' => $request->bank_name ?? '',
				'bank_address' => $request->bank_address ?? '',
				'payment_method' => $request->payment_method ?? ''
			];

			unset($data['update_request']);

			PaymentDetail::updateOrCreate(
			    ['user_id' => $company->id],
			    $paymentdata
			);
		} else if ($data['update_request'] == 'admin') {
			// Handle existing administrators updates
			if ($request->has('existing_admin_id') && is_array($request->existing_admin_id)) {
				// Validate existing admin data
				foreach ($request->existing_admin_id as $index => $adminId) {
					if (!empty($adminId)) {
						$admin = User::find($adminId);
						if ($admin && $admin->created_by == $id) {
							// Validate existing admin fields
							$this->validate($request, [
								'existing_name.' . $index => 'required|string|max:255',
								'existing_email.' . $index => 'required|email|unique:users,email,' . $adminId,
							], [
								'existing_name.' . $index . '.required' => 'The name field is required for existing administrator.',
								'existing_name.' . $index . '.string' => 'The name must be a string.',
								'existing_name.' . $index . '.max' => 'The name may not be greater than 255 characters.',
								'existing_email.' . $index . '.required' => 'The email field is required for existing administrator.',
								'existing_email.' . $index . '.email' => 'The email must be a valid email address.',
								'existing_email.' . $index . '.unique' => 'The email has already been taken.',
							]);
						}
					}
				}
				
				// Update existing admins after validation
				foreach ($request->existing_admin_id as $index => $adminId) {
					if (!empty($adminId)) {
						$admin = User::find($adminId);
						if ($admin && $admin->created_by == $id) {
							// Update existing admin
							$admin->name = $request->existing_name[$index];
							$admin->email = $request->existing_email[$index];
							$admin->save();
						}
					}
				}
			}
			
			// Handle new administrators creation
			if ($request->has('name') && is_array($request->name)) {
				// Validate new admin data
				foreach ($request->input('name') as $index => $name) {
					if (!empty($name)) { // Only validate if name is not empty
						$userFriendlyIndex = $index + 1;

						$this->validate($request, [
							'name.' . $index => 'required|string|max:255',
							'email.' . $index => 'required|email|unique:users,email',
							'password.' . $index => ['required', 'string', 'min:8'],
							'password_confirmation.' . $index => 'required_with:password.' . $index,
						], [
							'name.' . $index . '.required' => 'The name field is required for row ' . $userFriendlyIndex . '.',
							'email.' . $index . '.required' => 'The email field is required for row ' . $userFriendlyIndex . '.',
							'email.' . $index . '.email' => 'The email must be a valid email address for row ' . $userFriendlyIndex . '.',
							'email.' . $index . '.unique' => 'The email has already been taken for row ' . $userFriendlyIndex . '.',
							'password.' . $index . '.required' => 'The password field is required for row ' . $userFriendlyIndex . '.',
							'password.' . $index . '.string' => 'The password must be a string for row ' . $userFriendlyIndex . '.',
							'password.' . $index . '.min' => 'The password must be at least :min characters for row ' . $userFriendlyIndex . '.',
							'password_confirmation.' . $index . '.required_with' => 'The password confirmation field is required for row ' . $userFriendlyIndex . '.',
						]);
					}
				}

				// Create new admins
				foreach ($request->input('name') as $index => $name) {
					if (!empty($name)) { // Only create if name is not empty
						$user = new User();
						$user->name = $request->input('name')[$index];
						$user->email = $request->input('email')[$index];
						$user->password = Hash::make($request->input('password')[$index]);
						$user->role_id = 2; //Client role - when client adds admin, they become client role
						$user->status = 1; //Active status
						$user->created_by = $id; // Set to the client company being edited
						$user->save();
					}
				}
			}

			return redirect()->back()->with('message', 'Administrators updated successfully!');
		} else if ($data['update_request'] == 'changepwd') {

			// Loop through the submitted data and validate
		    foreach ($request->input('name') as $index => $name) {
		        $userFriendlyIndex = $index + 1;

		        $this->validate($request, [
		            'name.' . $index => 'required|string|max:255',
		            'email.' . $index => 'required|email|unique:users,email',
		            'password.' . $index => ['required', 'string', 'min:8'],
					'password_confirmation.' . $index => 'required_with:password.' . $index,
		        ], [
		            'name.' . $index . '.required' => 'The name field is required for row ' . $userFriendlyIndex . '.',
		            'email.' . $index . '.required' => 'The email field is required for row ' . $userFriendlyIndex . '.',
		            'email.' . $index . '.email' => 'The email must be a valid email address for row ' . $userFriendlyIndex . '.',
		            'email.' . $index . '.unique' => 'The email has already been taken for row ' . $userFriendlyIndex . '.',
		            'password.' . $index . '.required' => 'The password field is required for row ' . $userFriendlyIndex . '.',
		            'password.' . $index . '.string' => 'The password must be a string for row ' . $userFriendlyIndex . '.',
		            'password.' . $index . '.min' => 'The password must be at least :min characters for row ' . $userFriendlyIndex . '.',
					// 'password.' . $index . '.confirmed' => 'The password confirmation does not match for row ' . $userFriendlyIndex . '.',
		           	'password_confirmation.' . $index . '.required_with' => 'The password confirmation field is required for row ' . $userFriendlyIndex . '.',
		        ]);
		    }

		    // Manually compare password and password_confirmation
		    if ($request->input("password.$index") !== $request->input("password_confirmation.$index")) {
		        $validator = \Validator::make([], []); // Create an empty validator
		        $validator->errors()->add("password.$index", "The password confirmation does not match for row " . ($index + 1) . ".");
		        // throw new \Illuminate\Validation\ValidationException($validator);
		       	return redirect()->back()->with('error', $validator);
		    }

		    foreach ($request->input('name') as $index => $name) {
		        $user = new User();
			    $user->name = $request->input('name')[$index];
			    $user->email = $request->input('email')[$index];
			    $user->password = Hash::make($request->input('password')[$index]);
			    $user->role_id = 2; //Client role - when client adds admin, they become client role
			    $user->status = 1; //Active status
			    $user->created_by = auth()->user()->id; // Set to the client company being edited
			    $user->save();
		    }

			/*
			$request->validate([
				'old_password' => ['required'],
				'password' => ['required', 'string', 'min:8', 'confirmed'],
				'password_confirmation' => 'required_with:password',
			], [], [
				'old_password' => 'current password'
			]);

			unset($data['update_request']);

			$company->password = Hash::make($data['password_confirmation']);
			
			$company->save();
			*/
		}  else if ($data['update_request'] == 'changepwdown') {
			$request->validate([
				// 'old_password' => ['required'],
				'password' => ['required', 'string', 'min:8', 'confirmed'],
				'password_confirmation' => 'required_with:password',
			], [], [
				'old_password' => 'current password'
			]);

			unset($data['update_request']);

			$company->password = Hash::make($data['password_confirmation']);
			
			$company->save();
		} else {
			$request->validate([
				'company_name' => ['required'],
				'country' => ['required'],
				'city' => ['required'],
				'address' => ['required'],
				'email' => ['required', 'email', 'max:191', 'unique:users,email,'.$company->id],	
				// 'my_pwd' => ['min:8'],
				// 'password_confirmation' => 'required_with:password',
				'file' => 'nullable|mimes:png,jpg,jpeg|max:2048'
			], [], [
				'levy_id_no' => 'Education Levy ID Number',
				// 'doj' => 'Start Date',
			]);

			if (!empty($request->my_pwd)) {
				$company->password = Hash::make($data['my_pwd']);				
			}

			$company->update([
				'name' => $request->company_name,
				'email' =>$request->email,
				'phone_number' => $request->phone_number,
			]);

			$data = [
				'company_name' => $request->company_name,
				'city' => $request->city,
				'address' => $request->address,
				'state' => NULL,
				'country' => $request->country,
				'phone_number' => $request->phone_number,
				'medical_no' => $request->medical_no,
				'ssr_no' => $request->ssr_no,
				'levy_id_no' => $request->levy_id_no,
				'bank_name' => $request->bank_name,
				'account_number' => !empty($request->account_number) ? $request->account_number : NULL,
				'bank_address' => !empty($request->bank_address) ? $request->bank_address : NULL,
				'routing_number' => !empty($request->routing_number) ? $request->routing_number : NULL,
				'fb_url' => $request->fb_url ?? null,
				'linkden_url' => $request->linkden_url ?? null,
			];

		   	//Logo
		   	if ($request->file('file')) {
				$oldFile = $company->companyProfile->file;
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
				$oldLogo = $company->companyProfile->logo;
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
		   	CompanyProfile::updateOrCreate(
			    ['user_id' => auth()->user()->id],
			    $data
			);
			// $company->companyProfile->update($data);
		}
	
		return redirect()->back()->with('message', 'Records updated successfully.');	
	}

	/**
	 * Delete administrator method
	 * 
	 * @param int $admin_id
	 * @return \Illuminate\Http\Response
	 */
	public function deleteAdmin($admin_id)
	{
		try {
			$admin = User::findOrFail($admin_id);
			
			// Check if the admin belongs to the current user's company
			// if ($admin->created_by != auth()->user()->id) {
			// 	return response()->json([
			// 		'status' => false, 
			// 		'message' => 'Unauthorized to delete this administrator.'
			// 	], 403);
			// }
			
			$admin->delete();
			
			return response()->json([
				'status' => true, 
				'message' => 'Administrator deleted successfully.'
			]);
		} catch (\Exception $e) {
			return response()->json([
				'status' => false, 
				'message' => 'Error deleting administrator: ' . $e->getMessage()
			], 500);
		}
	}
}
