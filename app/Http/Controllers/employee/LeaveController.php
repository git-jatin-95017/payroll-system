<?php

namespace App\Http\Controllers\employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Leave;
// use DataTables;
// use Yajra\DataTables\Html\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LeaveController extends Controller
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
		return view('employee.leaves.index');
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
            $totalRecords = Leave::select('count(*) as allcount')->count();
            $totalRecordswithFilter = Leave::select('count(*) as allcount')->count();

            // Get records, also we have included search filter as well
            $records = Leave::orderBy($columnName, $columnSortOrder)                
	            ->join('users', function($join) {
	                $join->on('users.id', '=', 'leaves.user_id');
	            })         
	            ->join('employee_profile', function($join) {
	                $join->on('users.id', '=', 'employee_profile.user_id');
	            })                 
                ->orWhere('users.user_code', 'like', '%' . $searchValue . '%')
                ->orWhere('leaves.leave_subject', 'like', '%' . $searchValue . '%')
                ->orWhere('leaves.leave_message', 'like', '%' . $searchValue . '%')
                ->orWhere('leaves.leave_type', 'like', '%' . $searchValue . '%')
                ->orWhere('leaves.leave_status', 'like', '%' . $searchValue . '%')
                ->orWhere('leaves.apply_date', 'like', '%' . $searchValue . '%')
                ->select(
                    'leaves.id',
                    'users.user_code',                   
                    'leaves.leave_dates',
                    'leaves.leave_subject',
                    'leaves.leave_message',
                    'leaves.leave_type',
                    'leaves.leave_status',
                    'leaves.apply_date'
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
	   return view('employee.leaves.create');
	}

	public function store(Request $request)
	{   
		$data = $request->all();

		$request->validate([
			'leave_subject' => 'required|max:255',		
			'leave_dates' => ['required', 'max:500'],
			'leave_message' => ['required'],
			'leave_type' => ['required']
		]);

		$leave_dates   = addslashes($data['leave_dates']);

		Leave::create([
			'user_id' => auth()->user()->id,
			'leave_subject' => $data['leave_subject'],
			'leave_dates' => $leave_dates,
			'leave_message' => $data['leave_message'],
			'leave_type' => $data['leave_type'],
			'leave_status' => 'pending',
			'apply_date' => date('Y-m-d H:i:s')
		]);		

		
		return redirect()->route('my-leaves.index')->with('message', 'Leave applied successfully.');	
	}

	public function show(Leave $leave) {   
		return view('employee.leaves.show', compact('leave'));
	}

	public function edit(Leave $leave)
	{
	   return view('holidays.edit', compact('leave'));
	}

	public function update(Request $request, Leave $leave)
	{
		$data = $request->all();

		$request->validate([
			'title' => 'required|max:255',		
			'description' => ['required', 'max:500'],
			'holiday_date' => ['required', 'date'],
			'type' => ['required']
		]);

		$holiday->update([
			'title' => $data['title'],
			'description' => $data['description'],
			'holiday_date' => $data['holiday_date'],
			'type' => $data['type']
		]);		
	
		return redirect()->route('holidays.index')->with('message', 'Holiday updated successfully.');	
	}   

	protected function permanentDelete($id){
        $trash = Holiday::find($id);

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

			return response()->json(['status'=>true, 'message'=>"Holiday deleted successfully."]);
		}
	}
}