<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeaveType;
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
		return view('client.leave-type.index');
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
			$totalRecords = LeaveType::select('count(*) as allcount')->count();
			$totalRecordswithFilter = LeaveType::select('count(*) as allcount')->count();

			// Get records, also we have included search filter as well
			$records = LeaveType::orderBy($columnName, $columnSortOrder)                	           
				->orWhere('leave_types.name', 'like', '%' . $searchValue . '%')                
				->orWhere('leave_types.leave_day', 'like', '%' . $searchValue . '%')                
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
			'name' => 'required|max:255'
		]);

		LeaveType::create([
			'name' => $data['name'],
			'leave_day' => $data['no_of_day'],
			'status' => $data['status'],
		]);
		
		return redirect()->route('leave-type.index')->with('message', 'Leave type added successfully.');	
	}

	public function show(LeaveType $department) {   
		return view('client.leave-type.show', compact('leave'));
	}

	public function edit(LeaveType $department)
	{
	   return view('client.leave-type.edit', compact('leave'));
	}

	public function update(Request $request, LeaveType $department)
	{
		$data = $request->all();

		$request->validate([
			'name' => 'required|max:255'
		]);

		$department->update([
			'name' => $data['name'],
			'leave_day' => $data['no_of_day'],
			'status' => $data['status'],
		]);
	
		return redirect()->route('leave-type.index')->with('message', 'Leave type updated successfully.');	
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

			return response()->json(['status'=>true, 'message'=>"Department deleted successfully."]);
		}
	}
}