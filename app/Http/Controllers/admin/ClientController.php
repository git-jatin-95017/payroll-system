<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use DataTables;
use Yajra\DataTables\Html\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\CompanyProfile;
use App\Models\PaymentDetail;

class ClientController extends Controller
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
		if (request()->ajax()) {
			$usersQuery = User::query();
 
			$start_date = (!empty($_GET["start_date"])) ? ($_GET["start_date"]) : ('');
			$end_date = (!empty($_GET["end_date"])) ? ($_GET["end_date"]) : ('');
	 
			if ($start_date && $end_date) {
	 
				$start_date = date('Y-m-d', strtotime($start_date));
				$end_date = date('Y-m-d', strtotime($end_date));	
				$usersQuery->whereRaw("date(created_at) >= '" . $start_date . "' AND date(created_at) <= '" . $end_date . "'");
			}

			$data = $usersQuery->select('*')->where('role_id', 2);
			
			return Datatables::of($data)
					->addIndexColumn()                   
					->addColumn('action', function ($row) {
						return '<input type="checkbox" class="delete_check" id="delcheck_'.$row->id.'" onclick="checkcheckbox();" value="'.$row->id.'">';
					})
					->addColumn('action_button', function($data){
							$btn = "<div class='table-actions'>                           
							<a class='btn btn-sm btn-primary' href='".route("client.edit",$data->id)."'><i class='fas fa-pen'></i></a>
							<a data-href='".route("client.destroy",$data->id)."' class='btn btn-sm btn-danger delete' style='color:#fff;'><i class='fas fa-trash'></i></a>";
							return $btn;
					})
					->addColumn('action_button2', function($data) {
							$btn1 = "<div class='table-actions'>                         
							<form action='".route("login-as-client")."' method='post'>
								".csrf_field()."
									<input type='hidden' name='user_id' value=".$data->id.">
									<button type='submit' class='btn-primary btn'>
										Login
									</button>
								</form>
							</div>";
							return $btn1;
					})
					->rawColumns(['action', 'action_button', 'action_button2'])
					->make(true);
		}

		return view('admin.client.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
	   return view('admin.client.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) 
	{
		$data = $request->all();

		$request->validate([
			'company_name' => ['required'],
			'country' => ['required'],
			'city' => ['required'],
			'address' => ['required'],
			'email_address' => ['required', 'email', 'max:191', Rule::unique('users', 'email')],	
			'passwordc' => ['required', 'string', 'min:8'],
			// 'password_confirmationc' => 'required_with:passwordc',
			'file' => 'nullable|mimes:png,jpg,jpeg|max:2048'
		], [], [
			'levy_id_no' => 'Education Levy ID Number',
			// 'doj' => 'Start Date',
		]);

		$user = User::create([
			'name' => $request->company_name,
			'email' => $request->email_address,
			'phone_number' => $request->phone_number,
			'password' => Hash::make( $request->passwordc),
			'role_id' => 2,
			'status' => 1,
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
		];

		//Logo
		if ($request->file('file')) {
			$oldFile = $user->companyProfile->file;
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
			$oldLogo = $user->companyProfile->logo;
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
		
		CompanyProfile::updateOrCreate(
			['user_id' => $user->id],
			$data
		);

		$paymentdata = [
			'routing_number' => $request->routing_number ?? '',
			'account_number' => $request->account_number ?? '',
			'account_type' => $request->account_type ?? '',
			'bank_name' => $request->bank_name ?? '',
			'payment_method' => $request->payment_method ?? ''
		];

		PaymentDetail::updateOrCreate(
			['user_id' => $user->id],
			$paymentdata
		);

		// Loop through the submitted data and validate
		foreach ($request->input('name') as $index => $name) {
			$userFriendlyIndex = $index + 1;

			$this->validate($request, [
				'name.' . $index => 'nullable|string|max:255',
				'email.' . $index => 'nullable|email|unique:users,email',
				'password.' . $index => ['nullable', 'min:8'],
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
			if(!empty($request->input('name')[$index])) {
				$user = new User();
				$user->name = $request->input('name')[$index];
				$user->email = $request->input('email')[$index];
				$user->password = Hash::make($request->input('password')[$index]);
				$user->role_id = 2; //Company as admin
				$user->status = 1;
				$user->save();
			}
		}

		// Mail::to($user->email)->send(new StaffCreated($data));	

		return redirect()->route('client.index')->with('message', 'Client created successfully.');
	}

	/**
	 * Write code on Method
	 *
	 * @return response()
	 */
	protected function generateUniqueNumber()
	{
		do {
			$code = random_int(100000, 999999);
		} while (User::where("user_code", "=", $code)->first());
  
		return $code;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$company = User::find($id);		

		return view('admin.client.edit', compact('company'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		// $request->validate([
		// 	'name' => 'required|string|max:255',
		// 	'email' => ['required', 'email', 'max:191', 'unique:users,email,'.$id],
		// 	'phone_number' => ['required']                  
		// ]);

		// $client = User::findOrFail($id);

		// if (!empty($request->password)) {
		// 	$request['password'] =  Hash::make($request->password);
		// } else {
		// 	unset($request['password']);
		// }

		// $client->update($request->all());

		$data = $request->all();
		
		$company = User::find($id);

		if ($data['update_request'] == 'payment') {
			$paymentdata = [
				'routing_number' => $request->routing_number ?? '',
				'account_number' => $request->account_number ?? '',
				'account_type' => $request->account_type ?? '',
				'bank_name' => $request->bank_name ?? '',
				'payment_method' => $request->payment_method ?? ''
			];

			unset($data['update_request']);

			PaymentDetail::updateOrCreate(
				['user_id' => $company->id],
				$paymentdata
			);
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
				$user->role_id = 2; //Company as admin
				$user->status = 1; //Company as admin
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
		} else {
			$request->validate([
				'company_name' => ['required'],
				'country' => ['required'],
				'city' => ['required'],
				'address' => ['required'],
				'email' => ['required', 'email', 'max:191', 'unique:users,email,'.$company->id],	
				// 'password' => ['required', 'string', 'min:8', 'confirmed'],
				// 'password_confirmation' => 'required_with:password',
				'file' => 'nullable|mimes:png,jpg,jpeg|max:2048'
			], [], [
				'levy_id_no' => 'Education Levy ID Number',
				// 'doj' => 'Start Date',
			]);

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
				['user_id' => $company->id],
				$data
			);
			// $company->companyProfile->update($data);
		}

		return redirect()->route('client.index')->with('message','Record updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		if (request()->ajax()) {
			 $trash = User::find($id);

			if (!empty($trash->companyProfile->file)) {
				$oldFile = $trash->companyProfile->file;

				if (\File::exists(public_path('files/'.$oldFile))) {
					\File::delete(public_path('files/'.$oldFile));
				}
			}

			$trash->delete();

			return response()->json(['status'=>true, 'message'=>"Client deleted successfully."]);
		}
	}

	public function deleteAll(Request $request)  
	{  
		if (request()->ajax()) {

			if (request()->is_delete_request) {

				User::whereIn('id', $request->get('ids'))->delete();

				return response()->json(['status'=>true,'message'=>"Records deleted successfully."]);
			}

			if (request()->is_delete_request_all) {

				User::delete();

				return response()->json(['status'=>true,'message'=>"All Records deleted successfully."]);
			}
		} 
	} 

	/**
	 * loginas client method
	 * 
	 * @param \Illuminate\Http\Request $request
	 */
	public function loginAs(Request $request)
	{
		$userId = $request->get('user_id');
		//if session exists remove it and return login to original user
		if (session()->has('hasClonedUser')) {
			auth()->loginUsingId(session()->get('hasClonedUser'));
			session()->remove('hasClonedUser');
			return redirect()->back();
		}

		// Set session for client cloning
		session()->put('hasClonedUser', auth()->user()->id);
		auth()->loginUsingId($userId);
		return redirect()->route('client.dashboard');
	}
}
