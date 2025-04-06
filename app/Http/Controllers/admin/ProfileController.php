<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use DataTables;
use Yajra\DataTables\Html\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
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
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		return view('admin.profile.edit');
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id = NULL)
	{
		$data = $request->all();

		$id = auth()->user()->id;

		$admin = User::findOrFail($id);
		//Logo
	   	// if ($request->file('logo_c')) {

	    //     $file2 = $request->file('logo_c');
	    //     $filename2 = time().'_'.$file2->getClientOriginalName();

	    //     // File upload location
	    //     $location2 = 'files';

	    //     // Upload file
	    //     $file2->move($location2, $filename2);

	    //     $request['logo'] = $filename2;
	   	// } else 
		
		if ($data['update_request'] == 'adminsadd') {

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
			    $user->role_id = 2; //Company as admin
			    $user->status = 1; //Company as admin
				$user->created_by = auth()->user()->id;
			    $user->save();
		    }
		}  else if ($data['update_request'] == 'personal') {
			$request->validate([
				'name' => 'required|string|max:255',
				'email' => 'unique:users,email,'.$id.'|email|required|max:191',
				// 'email' => ['required', 'email', 'max:191', 'unique:users,email,'.$id],
				'phone_number' => ['required']                  
			]);

			$admin->update($request->all());
		} else if ($data['update_request'] == 'changepwdown') {
			$request->validate([
				// 'old_password' => ['required'],
				'password' => ['required', 'string', 'min:8', 'confirmed'],
				'password_confirmation' => 'required_with:password',
			], [], [
				'old_password' => 'current password'
			]);

			unset($data['update_request']);

			$admin->password = Hash::make($data['password_confirmation']);
			
			$admin->save();
		}

		return back()->with('message','Profile updated successfully.');
	}
}
