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
use Illuminate\Support\Facades\DB;

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
	public function index(Request $request)
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

		// Search input
		$searchValue = $request->input('search', '');

		// Fetching data with search and pagination
		$clients = User::orderBy('users.id', 'desc')
			->where('role_id', 2)
			->where(function ($query) use ($searchValue) {
				$query->Where('users.name', 'like', '%' . $searchValue . '%')
					->orWhere('users.email', 'like', '%' . $searchValue . '%');
			})
			->select(
				'users.id',
				'users.name',
				'users.email',
				DB::raw('DATE_FORMAT(users.created_at, "%m/%d/%Y") as created_at'),
				DB::raw('DATE_FORMAT(users.updated_at, "%m/%d/%Y") as updated_at')
			)
			->paginate(10);

		return view('admin.client.index', compact('clients'));
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
			'password' => ['required', 'string', 'min:8'],
			'password_confirmation' => 'required_with:passwordc',
			'file' => 'nullable|mimes:png,jpg,jpeg|max:2048'
		], [], [
			'levy_id_no' => 'Education Levy ID Number',
			// 'doj' => 'Start Date',
		]);

		$user = User::create([
			'name' => $request->company_name,
			'email' => $request->email_address,
			'phone_number' => $request->phone_number,
			// 'password' => Hash::make( $request->passwordc),
			'password' => Hash::make($data['password_confirmation']),
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
			'fb_url' => $request->fb_url ?? null,
			'linkden_url' => $request->linkden_url ?? null,
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
			dd($request->all);
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
			'bank_address' => $request->bank_address ?? '',
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
				$user->role_id = 1; //Admin role - when admin adds admin, they become admin role
				$user->status = 1; //Active status
				$user->created_by = auth()->user()->id; // Set to the admin creating the user
				$user->is_extra_user = 'Y'; // Mark as extra user
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
		
		// Fetch existing administrators for this company
		$existingAdmins = User::where('created_by', auth()->user()->id)
			->where('role_id', 1)
			->select('id', 'name', 'email', 'created_at')
			->get();

		return view('admin.client.edit', compact('company', 'existingAdmins'));
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
				'payment_method' => $request->payment_method ?? '',
				'bank_address' => $request->bank_address ?? '',
			];

			unset($data['update_request']);

			PaymentDetail::updateOrCreate(
				['user_id' => $company->id],
				$paymentdata
			);
		} else if ($data['update_request'] == 'changepwd') {
			// Validate password change request (simpler structure like create.blade.php)
			$request->validate([
				'password' => ['required', 'string', 'min:8'],
				'password_confirmation' => ['required', 'same:password'],
			], [
				'password.required' => 'New password is required.',
				'password.string' => 'New password must be a string.',
				'password.min' => 'New password must be at least 8 characters.',
				'password_confirmation.required' => 'Password confirmation is required.',
				'password_confirmation.same' => 'Password confirmation does not match.',
			]);

			// Update the client's password (no current password check needed for admin editing)
			$company->password = Hash::make($request->password);
			$company->save();

			return redirect()->back()->with('message', 'Password updated successfully!');
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
							$admin->is_extra_user = 'Y'; // Mark as extra user
							$admin->save();
							
							\Log::info('Updated existing admin', [
								'admin_id' => $adminId,
								'name' => $admin->name,
								'email' => $admin->email,
								'company_id' => $id
							]);
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
						$user->role_id = 1; //Company as admin
						$user->status = 1; //Company as admin
						$user->created_by = auth()->user()->id; // Set to the client company being edited
						$user->is_extra_user = 'Y'; // Mark as extra user
						
						// Debug: Log the created_by value
						\Log::info('Creating new admin user', [
							'name' => $user->name,
							'email' => $user->email,
							'created_by' => $user->created_by,
							'company_id' => $id
						]);
						
						$user->save();
					}
				}
			}

			return redirect()->back()->with('message', 'Administrators updated successfully!');
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
				if (!empty($company->companyProfile->logo)) {
					$oldLogo = $company->companyProfile->logo;
					if (\File::exists(public_path('files/'.$oldLogo))) {
						\File::delete(public_path('files/'.$oldLogo));
					}
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
	 * Delete administrator method
	 * 
	 * @param int $company_id
	 * @param int $admin_id
	 * @return \Illuminate\Http\Response
	 */
	public function deleteAdmin($id)
	{
		try {
			$admin = User::findOrFail($id);
			
			// Check if the admin belongs to the company being edited
			// if ($admin->created_by != $company_id) {
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
