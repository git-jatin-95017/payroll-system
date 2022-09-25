<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\EmployeeProfile;
use DataTables;
use Yajra\DataTables\Html\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
// use App\Models\{User};
// use App\Http\Requests\EmployeeRequest;
// use App\Mail\StaffCreated;
// use DataTables;
// use Mail;

class EmployeeController extends Controller
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

			$data = $usersQuery->select('*')->where('role_id', 3)->get();
			
			return Datatables::of($data)
					->addIndexColumn()   
					->addColumn('avatar', function($data) {
						if(!empty($data->employeeProfile->file)) {
							$avatar = "<img src='/files/{$data->employeeProfile->file}' width='65' height='65' class='table-user-thumb'>";
						} else{
							$avatar = "<img src='/img/user2-160x160.jpg' width='65' height='65' class='table-user-thumb'>";
						}
						return $avatar;
					})
					->addColumn('mobile', function($data){
                        return $data->employeeProfile->mobile;
                    })
                    ->addColumn('identity_document', function($data){
                        return $data->employeeProfile->identity_document;
                    }) 
                    ->addColumn('dob', function($data){
                        return $data->employeeProfile->dob;
                    }) 
                    ->addColumn('doj', function($data){
                        return $data->employeeProfile->doj;
                    }) 
                    ->addColumn('blood_group', function($data){
                        return $data->employeeProfile->blood_group;
                    }) 
                    ->addColumn('emp_type', function($data){
                        return $data->employeeProfile->emp_type;
                    })                 
					->addColumn('action', function ($row) {
						return '<input type="checkbox" class="delete_check" id="delcheck_'.$row->id.'" onclick="checkcheckbox();" value="'.$row->id.'">';
					})
					->addColumn('action_button', function($data){
							$btn = "<div class='table-actions'>                           
							<a href='".route("employee.show",$data->id)."' class='btn btn-sm btn-info'><i class='fas fa-eye'></i></a>
							<a href='".route("employee.edit",$data->id)."' class='btn btn-sm btn-primary'><i class='fas fa-pen'></i></a>
							<a data-href='".route("employee.destroy",$data->id)."' class='btn btn-sm btn-danger delete'><i class='fas fa-trash'></i></a>
							</div>";
							return $btn;
					})
					->rawColumns(['action', 'action_button', 'avatar', 'mobile', 'identity_document', 'dob', 'doj', 'blood_group', 'emp_type'])
					->make(true);
		}

		return view('client.employee.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
	   return view('client.employee.create');
	}

	public function store(Request $request)
	{   
		$data = $request->all();

		$request->validate([
			'emp_code' => 'required|max:255',		
			'first_name' => ['required'],
			'last_name' => ['required'],
			'dob' => ['required', 'date'],
			'gender' => ['required'],
			'marital_status' => ['required'],
			'nationality' => ['required'],
			'blood_group' => ['required'],
			'city' => ['required'],
			'state' => ['required'],
			'address' => ['required'],
			'country' => ['required'],
			'mobile' => ['required'],
			'email' => ['required', 'email', 'max:191', 'unique:users'],			
			'identity_document' => ['required'],
			'identity_number' => ['required'],
			'emp_type' => ['required'],
			'doj' => ['required', 'date'],
			'designation' => ['required'],
			'department' => ['required'],
			'password' => ['required', 'string', 'min:8', 'confirmed'],
			'password_confirmation' => 'required_with:password',
			'file' => 'mimes:png,jpg,jpeg|max:2048'
		]);

		$user = User::create([
			'name' => $data['first_name'] . ' '. $data['last_name'],
			'email' => $data['email'],
			'phone_number' => $data['phone_number'],
			'password' => Hash::make($data['password']),
			'role_id' => 3,
			'user_code' => $request->emp_code
		]);		

		$data = [
			'user_id' => $user->id,
			'first_name' => $request->first_name,
			'last_name' => $request->last_name,
			'dob' => $request->dob,
			'gender' => $request->gender,
			'marital_status' => $request->marital_status,
			'nationality' => $request->nationality,
			'blood_group' => $request->blood_group,
			'city' => $request->city,
			'address' => $request->address,
			'state' => $request->state,
			'country' => $request->country,
			'mobile' => $request->mobile,
			'phone_number' => $request->phone_number,
			'identity_document' => $request->identity_document,
			'identity_number' => $request->identity_number,
			'emp_type' => $request->emp_type,
			'doj' => $request->doj,
			'designation' => $request->designation,
			'department' => $request->department,
			'pan_number' => $request->pan_number,
			'bank_name' => $request->bank_name,
			'bank_acc_number' => $request->bank_acc_number,
			'ifsc_code' => $request->ifsc_code,
			'pf_account_number' => $request->pf_account_number,
		];

		if ($request->file('file')) {			
	        $file = $request->file('file');
	        $filename = time().'_'.$file->getClientOriginalName();

	        // File upload location
	        $location = 'files';

	        // Upload file
	        $file->move($location,$filename);

	        $data['file'] = $filename;
	   	}

		EmployeeProfile::create($data);
	
		// Mail::to($employee->email)->send(new StaffCreated($data));	

		return redirect()->route('employee.index')->with('message', 'Employee created successfully.');	
	}

	public function show(User $employee) {   
		return view('client.employee.show', compact('employee'));
	}

	public function edit(User $employee)
	{
	   return view('client.employee.edit', compact('employee'));
	}

	public function update(Request $request, User $employee)
	{
		$data = $request->all();

		$request->validate([
			'emp_code' => 'required|max:255',		
			'first_name' => ['required'],
			'last_name' => ['required'],
			'dob' => ['required', 'date'],
			'gender' => ['required'],
			'marital_status' => ['required'],
			'nationality' => ['required'],
			'blood_group' => ['required'],
			'city' => ['required'],
			'state' => ['required'],
			'address' => ['required'],
			'country' => ['required'],
			'mobile' => ['required'],
			'email' => ['required', 'email', 'max:191', 'unique:users,email,'.$employee->id],	
			'identity_document' => ['required'],
			'identity_number' => ['required'],
			'emp_type' => ['required'],
			'doj' => ['required', 'date'],
			'designation' => ['required'],
			'department' => ['required'],
			// 'password' => ['required', 'string', 'min:8', 'confirmed'],
			// 'password_confirmation' => 'required_with:password',
			'file' => 'nullable|mimes:png,jpg,jpeg|max:2048'
		]);

		$employee->update([
			'name' => $data['first_name'] . ' '. $data['last_name'],
			'email' => $data['email'],
			'phone_number' => $data['phone_number'],
			'user_code' => $request->emp_code
		]);

		$data = [
			'first_name' => $request->first_name,
			'last_name' => $request->last_name,
			'dob' => $request->dob,
			'gender' => $request->gender,
			'marital_status' => $request->marital_status,
			'nationality' => $request->nationality,
			'blood_group' => $request->blood_group,
			'city' => $request->city,
			'address' => $request->address,
			'state' => $request->state,
			'country' => $request->country,
			'mobile' => $request->mobile,
			'phone_number' => $request->phone_number,
			'identity_document' => $request->identity_document,
			'identity_number' => $request->identity_number,
			'emp_type' => $request->emp_type,
			'doj' => $request->doj,
			'designation' => $request->designation,
			'department' => $request->department,
			'pan_number' => $request->pan_number,
			'bank_name' => $request->bank_name,
			'bank_acc_number' => $request->bank_acc_number,
			'ifsc_code' => $request->ifsc_code,
			'pf_account_number' => $request->pf_account_number,
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

		$employee->employeeProfile->update($data);
	
		return redirect()->route('employee.index')->with('message', 'Employee updated successfully.');	
	}   

	protected function permanentDelete($id){
        $trash = User::find($id);

        if (!empty($trash->employeeProfile->file)) {
            $oldFile = $trash->employeeProfile->file;

			if (\File::exists(public_path('files/'.$oldFile))) {
				\File::delete(public_path('files/'.$oldFile));
			}
        }

        $trash->delete();

        return true;
    }

   	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request, $id)
	{
		if (request()->ajax()) {
			 $trash = $this->permanentDelete($id);

			return response()->json(['status'=>true, 'message'=>"Employee deleted successfully."]);
		}
	}
}