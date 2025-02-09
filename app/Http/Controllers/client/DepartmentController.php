<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\EmpDepartment;
// use DataTables;
// use Yajra\DataTables\Html\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
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
		$locations = Department::orderBy('departments.id', 'desc')
			->where('departments.created_by', auth()->user()->id)
			->where(function ($query) use ($searchValue) {
				$query
					->where(function ($query) use ($searchValue) {
						$query->where('departments.dep_name', 'like', '%' . $searchValue . '%');
					});

			})
			->paginate(10);

		return view('client.department.index', compact('locations'));
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
			$totalRecords = Department::select('count(*) as allcount')->where('created_by', auth()->user()->id)->count();
			$totalRecordswithFilter = Department::select('count(*) as allcount')->where('created_by', auth()->user()->id)->count();

			// Get records, also we have included search filter as well
			$records = Department::orderBy($columnName, $columnSortOrder)
				->where('departments.created_by', auth()->user()->id)             	 
				->where(function ($query) use($searchValue) {
			        $query
			        	->orWhere('departments.dep_name', 'like', '%' . $searchValue . '%');
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
	   return view('client.department.create');
	}

	public function store(Request $request)
	{   
		$data = $request->all();

		$request->validate([
			'dep_name' => 'required|max:255'
		]);

		Department::create([
			'dep_name' => $data['dep_name']
		]);		

		
		return redirect()->route('department.index')->with('message', 'Location added successfully.');	
	}

	public function show(Department $department) {   
		return view('client.department.show', compact('leave'));
	}

	public function edit($id)
	{
		$department = Department::findOrFail($id);
	  	return view('client.department.edit', compact('department'));
	}

	public function update(Request $request, $id)
	{
		$data = $request->all();

		$department = Department::findOrFail($id);
		
		$request->validate([
			'dep_name' => 'required|max:255'
		]);

		$department->update([
			'dep_name' => $data['dep_name']
		]);		
	
		return redirect()->route('department.index')->with('message', 'Location updated successfully.');	
	}   

	protected function permanentDelete($id){
		$trash = Department::find($id);

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

			return response()->json(['status'=>true, 'message'=>"Location deleted successfully."]);
		}
	}

	public function assign(Request $request) {
		if (request()->ajax()) {
			$result = array();
			$requestData =$request->all();

			$payheads = $requestData['selected_locations'];
			// $default_salary = $requestData['pay_amounts'];
			$emp_code = $requestData['empcodelocation'];
			
			$checkSQL = EmpDepartment::where('user_id', $emp_code);
			// if ( $checkSQL->count()  > 0) {
				if ( !empty($payheads) && !empty($emp_code) ) {
					if ( $checkSQL->count() == 0 ) {
						foreach ( $payheads as $payhead ) {
							EmpDepartment::create([
								'user_id' => $emp_code,
								'department_id' => $payhead,
							]);
						}
						$result['result'] = 'Locations are successfully assigned to employee.';
						$result['code'] = 0;
					} else {
						EmpDepartment::where('user_id', $emp_code)->delete();						
						foreach ( $payheads as $payhead ) {
							EmpDepartment::create([
								'user_id' => $emp_code,
								'department_id' => $payhead,
							]);
						}
						$result['result'] = 'Locations are successfully re-assigned to employee.';
						$result['code'] = 0;
					}
				} else {
					$result['result'] = 'Please select Locations and employee to assign.';
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
			
			$assignLocationsids = EmpDepartment::select('department_id')->
				join('departments', function($join) {
	            	$join->on('departments.id', '=', 'emp_departments.department_id');
	           	})
	           	->where('user_id', $emp_code)->pluck('department_id');
	        $result = EmpDepartment::
				join('departments', function($join) {
	            	$join->on('departments.id', '=', 'emp_departments.department_id');
	           	})
	           	->where('user_id', $emp_code)->get();

	        $query = Department::select('*');
	        if (count($assignLocationsids) > 0) {
	        	$query->whereNotIn('departments.id', $assignLocationsids);
	        }

	        $alllocations = $query->where('departments.created_by', auth()->user()->id)->get();

			return response()->json([
				'result' => $result,
				'code' => 0,
				'alllocations' => $alllocations,
			]);
		}
	}
}