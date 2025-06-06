<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\EmployeeProfile;
use App\Models\PaymentDetail;
use App\Models\Payhead;
use App\Models\Department;
use App\Models\LeaveType;
use Illuminate\Support\Facades\DB;
// use DataTables;
// use Yajra\DataTables\Html\Builder;
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
	public function index(Request $request)
	{		
		$payheadList = Payhead::get();
		$locations = Department::get();
		$leavePolicies = LeaveType::get();

		// Search input
		$searchValue = $request->input('search', '');

		// Fetching data with search and pagination
		$employees = User::orderBy('users.id', 'desc')
			->join('employee_profile', function ($join) {
				$join->on('users.id', '=', 'employee_profile.user_id');
			})
			->where('users.created_by', auth()->user()->id)
			->where(function ($query) use ($searchValue) {
				$query->where(DB::raw("CONCAT(employee_profile.first_name, ' ', employee_profile.last_name)"), 'like', '%' . $searchValue . '%')
					->orWhere('users.user_code', 'like', '%' . $searchValue . '%')
					->orWhere('employee_profile.phone_number', 'like', '%' . $searchValue . '%')
					->orWhere('employee_profile.pan_number', 'like', '%' . $searchValue . '%')
					->orWhere('employee_profile.ifsc_code', 'like', '%' . $searchValue . '%')
					->orWhere('employee_profile.designation', 'like', '%' . $searchValue . '%')
					->orWhere('employee_profile.pay_rate', 'like', '%' . $searchValue . '%');
			})
			->select(
				'users.id',
				'users.user_code',
				DB::raw("CONCAT(employee_profile.first_name, ' ', employee_profile.last_name) AS name"),
				'employee_profile.file',
				DB::raw('DATE_FORMAT(employee_profile.dob, "%m/%d/%Y") as date_of_birth'),
				DB::raw('DATE_FORMAT(employee_profile.doj, "%m/%d/%Y") as start_date'),
				'employee_profile.phone_number',
				'employee_profile.pan_number',
				'employee_profile.ifsc_code',
				'employee_profile.designation',
				'employee_profile.pay_rate'
			)
			->paginate(10);

			
		return view('client.employee.index', compact('payheadList', 'leavePolicies', 'locations', 'employees'));
	}

	public function getData(Request $request)
	{
		if ($request->ajax()) {
            $draw = $request->get('draw');
            $start = $request->get("start");
            $rowperpage = $request->get("length"); // total number of rows per page

            $columnIndex_arr = $request->get('order');
            $columnName_arr = $request->get('columns');
            $order_arr = $request->get('order');
            $search_arr = $request->get('search');

            $columnIndex = $columnIndex_arr[0]['column']; // Column index
            $columnName = $columnName_arr[$columnIndex]['data']; // Column name
            $columnSortOrder = $order_arr[0]['dir']; // asc or desc
            $searchValue = $search_arr['value']; // Search value

            $folder = date('Y-m-d', strtotime($request->filter_date));

            // Total records
            $totalRecords = User::select('count(*) as allcount')->where('users.created_by', auth()->user()->id) ->count();
            $totalRecordswithFilter = User::select('count(*) as allcount')->where('users.created_by', auth()->user()->id) ->join('employee_profile', function($join) {
                $join->on('users.id', '=', 'employee_profile.user_id');
            })
            // ->where('users.email', 'like', '%' . $searchValue . '%')
            ->count();

            // Get records, also we have included search filter as well
            $records = User::orderBy($columnName, $columnSortOrder)
                ->join('employee_profile', function($join) {
	                $join->on('users.id', '=', 'employee_profile.user_id');
	            }) 
	            ->where('users.created_by', auth()->user()->id)             	 
				->where(function ($query) use($searchValue) {
			        $query
			        	->orWhere(DB::raw("CONCAT(employee_profile.first_name, ' ', employee_profile.last_name)"), 'like', '%' . $searchValue . '%')
		                ->orWhere('users.user_code', 'like', '%' . $searchValue . '%')
		                ->orWhere('employee_profile.phone_number', 'like', '%' . $searchValue . '%')
		                ->orWhere('employee_profile.pan_number', 'like', '%' . $searchValue . '%')
		                ->orWhere('employee_profile.ifsc_code', 'like', '%' . $searchValue . '%')
		                ->orWhere('employee_profile.designation', 'like', '%' . $searchValue . '%')
		                ->orWhere('employee_profile.pay_rate', 'like', '%' . $searchValue . '%');
			    })
                ->select(
                    'users.id',
                    'users.user_code',
                   	DB::raw("CONCAT(employee_profile.first_name, ' ' , employee_profile.last_name) AS name"),
                    'employee_profile.file',
                    DB::raw('DATE_FORMAT(employee_profile.dob, "%m/%d/%Y") as date_of_birth'),
                    DB::raw('DATE_FORMAT(employee_profile.doj, "%m/%d/%Y") as start_date'),
                    'employee_profile.phone_number',
                    'employee_profile.pan_number',
                    'employee_profile.ifsc_code',
                    'employee_profile.designation',
                    'employee_profile.pay_rate',
                    
                )
                ->skip($start)
                ->take($rowperpage)
                ->get();

            // $data_arr = array();

            // foreach ($records as $k => $record) {
            //     $createdDate = date('m/d/Y', strtotime($record->created_date));
            //     $records[$k]['created_date'] = $createdDate;
            //     // $data_arr[$k] = $record;
            // }

            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => $totalRecords,
                "iTotalDisplayRecords" => $totalRecordswithFilter,
                "aaData" => $records,
            );

            return response()->json($response);
        }
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

	private function processFaceData($faceData)
	{
		try {
			$decodedData = json_decode($faceData, true);
			if (!is_array($decodedData)) {
				throw new \Exception('Invalid face data format');
			}

			// Process each face capture
			$processedFaces = array_map(function($face) {
				if (!isset($face['image']) || !isset($face['features'])) {
					throw new \Exception('Missing required face data fields');
				}

				// Validate features array
				if (!is_array($face['features'])) {
					throw new \Exception('Invalid feature vector format - not an array');
				}

				// Each landmark (eye, nose, etc.) has x,y coordinates
				// 6 landmarks * 2 coordinates = 12 features minimum
				if (count($face['features']) < 12) {
					throw new \Exception('Invalid feature vector format - insufficient features');
				}

				// Validate feature values (normalized coordinates)
				foreach ($face['features'] as $value) {
					if (!is_numeric($value) || $value < 0 || $value > 1) {
						throw new \Exception('Invalid feature values - must be normalized between 0 and 1');
					}
				}

				return [
					'image' => $face['image'],
					'features' => $face['features']
				];
			}, $decodedData);

			return json_encode($processedFaces);
		} catch (\Exception $e) {
			throw new \Exception('Face data processing failed: ' . $e->getMessage());
		}
	}

	public function store(Request $request)
	{   
		$data = $request->all();

		$request->validate([
			// 'emp_code' => 'required|max:255',		
			'first_name' => ['required'],
			'last_name' => ['required'],
			'marital_status' => ['required'],
			'emp_type' => ['required'],
			'pay_type' => ['required'],
			'dob' => ['required', 'date'],
			'doj' => ['required', 'date'],
			'country' => ['required'],
			'address' => ['required'],
			'nationality' => ['required'],
			'pay_rate' => ['required'],
			//'gender' => ['required'],
			// 'blood_group' => ['required'],
			// 'state' => ['required'],
			// 'mobile' => ['required'],
			'email' => ['required', 'email', 'max:191', 'unique:users'],			
			// 'identity_document' => ['required'],
			// 'identity_number' => ['required'],
			// 'designation' => ['required'],
			// 'department' => ['required'],
			'kiosk_code' => ['nullable', 'digits:4'],
			'password' => ['required', 'string', 'min:8', 'confirmed'],
			'password_confirmation' => 'required_with:password',
			'file' => 'mimes:png,jpg,jpeg|max:2048',
			'payment_method' =>['required'],
			'routing_number' => ['required_if:payment_method,==,deposit'],
			'account_number' => ['required_if:payment_method,==,deposit'],
			'account_type' => ['required_if:payment_method,==,deposit'],
			'face_data' => ['nullable', 'json'],
		],[],[
			// 'emp_code' => 'Employee ID number',
			'doj' => 'Start Date',
			'pay_rate' => 'amount',
			'kiosk_code' => 'Kiosk PIN'
		]);

		$user = User::create([
			'name' => $data['first_name'] . ' '. $data['last_name'],
			'email' => $data['email'],
			'phone_number' => $data['phone_number'],
			'password' => Hash::make($data['password']),
			'role_id' => 3,
			'user_code' => strtoupper(uniqid()), //$request->emp_code,
			'kiosk_code' => $data['kiosk_code'],
			'is_proifle_edit_access' => $request->is_proifle_edit_access,
			'status' => $request->status ?? 1,
		]);		

		$employeeData = [
			'user_id' => $user->id,
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
			'emp_type' => $request->emp_type,
			'pay_type' => $request->pay_type,
			'pay_rate' => $request->pay_rate,
			'doj' => $request->doj,
			'designation' => $request->designation,
			'department' => $request->department,
			'pan_number' => $request->pan_number,
			'bank_name' => $request->bank_name,
			'bank_acc_number' => $request->bank_acc_number,
			'ifsc_code' => $request->ifsc_code,
			'pf_account_number' => $request->pf_account_number,
			'manager_position' => $request->manager_position,
			'manager' => $request->manager,
			'em_name' => $request->em_name,
			'em_number' => $request->em_number,
			'fb_url' => $request->fb_url,
			'linkden_url' => $request->linkden_url,
			'is_visible_calendar' => $request->is_visible_calendar,
		];

		// Process face data if present
		if ($request->has('face_data') && $request->face_data != null) {
			try {
				$processedFaceData = $this->processFaceData($request->face_data);
				$employeeData['face_data'] = $processedFaceData;
				$employeeData['face_captured_at'] = now();
			} catch (\Exception $e) {
				\Log::error('Face data processing error: ' . $e->getMessage());
				return redirect()->back()
					->with('error', 'Failed to process face data: ' . $e->getMessage())
					->withInput();
			}
		}

		if ($request->file('file')) {			
	        $file = $request->file('file');
	        $filename = time().'_'.$file->getClientOriginalName();

	        // File upload location
	        $location = 'files';

	        // Upload file
	        $file->move($location,$filename);

	        $employeeData['file'] = $filename;
	   	}

	   	//Logo
	   	if ($request->file('logo')) {

	        $file2 = $request->file('logo');
	        $filename2 = time().'_'.$file2->getClientOriginalName();

	        // File upload location
	        $location2 = 'files';

	        // Upload file
	        $file2->move($location2, $filename2);

	        $employeeData['logo'] = $filename2;
	   	}
	   	
	   	unset($employeeData['status']);

		$employee = EmployeeProfile::create($employeeData);
		
		$paymentdata = [
			'routing_number' => $request->routing_number ?? '',
			'account_number' => $request->account_number ?? '',
			'account_type' => $request->account_type ?? '',
			'payment_method' => $request->payment_method ?? '',
			'bank_name' => $request->bank_name ?? ''
		];

		PaymentDetail::updateOrCreate(
		    ['user_id' => $user->id],
		    $paymentdata
		);

		// Mail::to($employee->email)->send(new StaffCreated($data));	

		return redirect()->route('employee.index')->with('message', 'Employee created successfully.');	
	}

	public function show(User $employee) {   
		return view('client.employee.show', compact('employee'));
	}

	public function edit(User $employee)
	{
		$disabled = '';
		$disabledDrop = false;
		if ($employee->is_proifle_edit_access == "1") {
			$disabled = 'readonly="readonly"';$disabledDrop = (int) true;
		}

	   	return view('client.employee.edit', compact('employee', 'disabled', 'disabledDrop'));
	}

	public function update(Request $request, $id)
	{
		try {
			$employee = User::findOrFail($id);
			// $this->authorize('update', $employee);

			$request->validate([
				'first_name' => ['required'],
				'last_name' => ['required'],
				'marital_status' => ['required'],
				'emp_type' => ['required'],
				'pay_type' => ['required'],
				'dob' => ['required', 'date'],
				'doj' => ['required', 'date'],
				'country' => ['required'],
				'address' => ['required'],
				'nationality' => ['required'],
				'pay_rate' => ['required'],
				'email' => ['required', 'email', 'max:191', 'unique:users,email,' . $id],
				'kiosk_code' => ['nullable', 'digits:4'],
				'file' => 'nullable|mimes:png,jpg,jpeg|max:2048',
				'face_data' => ['nullable', 'json'],
			]);

			// Update user data
			$employee->update([
				'name' => $request->first_name . ' ' . $request->last_name,
				'email' => $request->email,
				'phone_number' => $request->phone_number,
				'kiosk_code' => $request->kiosk_code,
			]);

			$employeeData = [
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
				'emp_type' => $request->emp_type,
				'pay_type' => $request->pay_type,
				'pay_rate' => $request->pay_rate,
				'doj' => $request->doj,
				'designation' => $request->designation,
				'department' => $request->department,
				'pan_number' => $request->pan_number,
				'bank_name' => $request->bank_name,
				'bank_acc_number' => $request->bank_acc_number,
				'ifsc_code' => $request->ifsc_code,
				'pf_account_number' => $request->pf_account_number,
				'manager_position' => $request->manager_position,
				'manager' => $request->manager,
				'em_name' => $request->em_name,
				'em_number' => $request->em_number,
				'fb_url' => $request->fb_url,
				'linkden_url' => $request->linkden_url,
				'is_visible_calendar' => $request->is_visible_calendar,
			];

			// Process face data if present
			if ($request->has('face_data') && $request->face_data != null) {
				try {
					$processedFaceData = $this->processFaceData($request->face_data);
					$employeeData['face_data'] = $processedFaceData;
					$employeeData['face_captured_at'] = now();
				} catch (\Exception $e) {
					\Log::error('Face data processing error for employee ' . $id . ': ' . $e->getMessage());
					return redirect()->back()
						->with('error', 'Failed to process face data: ' . $e->getMessage())
						->withInput();
				}
			}

			// Handle file upload if provided
			if ($request->hasFile('file')) {
				$oldFile = $employee->employeeProfile->file;
				if (\File::exists(public_path('files/'.$oldFile))) {
					\File::delete(public_path('files/'.$oldFile));
				}

				$file = $request->file('file');
				$filename = time().'_'.$file->getClientOriginalName();
				$file->move(public_path('files'), $filename);
				$employeeData['file'] = $filename;
			}

			// Update employee profile
			$employee->employeeProfile()->update($employeeData);

			return redirect()->route('employee.index')
				->with('success', 'Employee updated successfully');

		} catch (\Exception $e) {
			\Log::error('Employee update error: ' . $e->getMessage());
			return redirect()->back()
				->with('error', 'Failed to update employee: ' . $e->getMessage())
				->withInput();
		}
	}

	protected function permanentDelete($id) {
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