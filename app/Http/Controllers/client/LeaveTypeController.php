<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeaveType;
use App\Models\EmpLeavePolicy;
// use DataTables;
// use Yajra\DataTables\Html\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LeaveTypeController extends Controller
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
		// Search input
		$searchValue = $request->input('search', '');

		// Fetching data with search and pagination
		$leavTypes = LeaveType::orderBy('leave_types.id', 'desc')
			->where('leave_types.created_by', auth()->user()->id)
			->where(function ($query) use ($searchValue) {
				$query
					->where(function ($query) use ($searchValue) {
						$query->where('leave_types.name', 'like', '%' . $searchValue . '%')
							->orWhere('leave_types.leave_day', 'like', '%' . $searchValue . '%');
					});

			})
			->paginate(10);

		return view('client.leave-type.index', compact('leavTypes'));
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

			// Total records
			$totalRecords = LeaveType::select('count(*) as allcount')->where('created_by', auth()->user()->id)->count();
			$totalRecordswithFilter = LeaveType::select('count(*) as allcount')->where('created_by', auth()->user()->id)->count();

			// Get records, also we have included search filter as well
			$records = LeaveType::orderBy($columnName, $columnSortOrder)	
			    ->where('leave_types.created_by', auth()->user()->id)             	 
				->where(function ($query) use($searchValue) {
			        $query
			        	->orWhere('leave_types.name', 'like', '%' . $searchValue . '%')
			        	->orWhere('leave_types.leave_day', 'like', '%' . $searchValue . '%');
			    })           
				->skip($start)
				->take($rowperpage)
				->get();

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
	   return view('client.leave-type.create');
	}

	public function store(Request $request)
	{   
		$data = $request->all();

		$request->validate([
			'name' => 'required|max:255',
			'no_of_day' => 'required'
		]);

		LeaveType::create([
			'name' => $data['name'],
			'leave_day' => $data['no_of_day'],
			'status' => $data['status'],
			'date_after' => '2022-12-31', //$data['date_after'],
			'carry_over_amount' => $data['carry_over_amount'],
			'start_days' => $data['start_days'],
			'is_visible_calendar' => $data['is_visible_calendar']
		]);
		
		return redirect()->route('leave-type.index')->with('message', 'Leave policy added successfully.');	
	}

	public function show(LeaveType $department) {   
		return view('client.leave-type.show', compact('leave'));
	}

	public function edit($id)
	{
		$leaveType = LeaveType::find($id);
	   	return view('client.leave-type.edit', compact('leaveType'));
	}

	public function update(Request $request, $id)
	{
		$data = $request->all();

		$leaveType = LeaveType::find($id);

		$request->validate([
			'name' => 'required|max:255'
		]);

		$leaveType->update([
			'name' => $data['name'],
			'leave_day' => $data['no_of_day'],
			'status' => $data['status'],
			'date_after' => '2022-12-31', //$data['date_after'],
			'carry_over_amount' => $data['carry_over_amount'],
			'start_days' => $data['start_days'],
			'is_visible_calendar' => $data['is_visible_calendar']
		]);
	
		return redirect()->route('leave-type.index')->with('message', 'Leave policy updated successfully.');	
	}   

	protected function permanentDelete($id){
		$trash = LeaveType::find($id);

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

			return response()->json(['status'=>true, 'message'=>"Leave policy deleted successfully."]);
		}
	}



	public function assign(Request $request) {
		if (request()->ajax()) {
			$result = array();
			$requestData =$request->all();

			$payheads = $requestData['selected_leave_policies'];
			// $default_salary = $requestData['pay_amounts'];
			$emp_code = $requestData['empcodepolicy'];
			
			$checkSQL = EmpLeavePolicy::where('user_id', $emp_code);
			// if ( $checkSQL->count()  > 0) {
				if ( !empty($payheads) && !empty($emp_code) ) {
					if ( $checkSQL->count() == 0 ) {
						foreach ( $payheads as $payhead ) {
							EmpLeavePolicy::create([
								'user_id' => $emp_code,
								'leave_type_id' => $payhead,
							]);
						}
						$result['result'] = 'leave polocies are successfully assigned to employee.';
						$result['code'] = 0;
					} else {
						EmpLeavePolicy::where('user_id', $emp_code)->delete();						
						foreach ( $payheads as $payhead ) {
							EmpLeavePolicy::create([
								'user_id' => $emp_code,
								'leave_type_id' => $payhead,
							]);
						}
						$result['result'] = 'leave polocies are successfully re-assigned to employee.';
						$result['code'] = 0;
					}
				} else {
					$result['result'] = 'Please select leave polocies and employee to assign.';
					$result['code'] = 2;
				}
			// } else {
				// $result['result'] = 'Something went wrong, please try again.';
				// $result['code'] = 1;
			// }

			return response()->json($result);
		}
	}

	public function assignedPayhead(Request $request) {
		if (request()->ajax()) {
			$result = array();
			$requestData =$request->all();

			$emp_code = $requestData['emp_code'];
			
			$assignleaveids = EmpLeavePolicy::select('leave_type_id')->
				join('leave_types', function($join) {
	            	$join->on('leave_types.id', '=', 'emp_leave_policies.leave_type_id');
	           	})
	           	->where('user_id', $emp_code)->pluck('leave_type_id');

			$result = EmpLeavePolicy::
				join('leave_types', function($join) {
	            	$join->on('leave_types.id', '=', 'emp_leave_policies.leave_type_id');
	           	})
	           	->where('user_id', $emp_code)->get();

	        $query = LeaveType::select('*');
	        if (count($assignleaveids) > 0) {
	        	$query->whereNotIn('leave_types.id', $assignleaveids);
	        }

	        $leavePolicies = $query->where('leave_types.created_by', auth()->user()->id)->get();

			return response()->json([
				'result' => $result,
				'code' => 0,
				'leavePolicies' => $leavePolicies
			]);
		}
	}
}