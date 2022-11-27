<?php

namespace App\Http\Controllers\employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\AssignLeave;
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
		$leavetypes = LeaveType::where('status', 1)->get();		
		return view('employee.leaves.index', compact('leavetypes'));
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
	            ->join('leave_types', function($join) {
	                $join->on('leave_types.id', '=', 'leaves.type_id');
	            })                  
                ->orWhere('users.user_code', 'like', '%' . $searchValue . '%')
                ->orWhere('leaves.leave_subject', 'like', '%' . $searchValue . '%')
                ->orWhere('leaves.leave_message', 'like', '%' . $searchValue . '%')
                ->orWhere('leaves.leave_type', 'like', '%' . $searchValue . '%')
                ->orWhere('leaves.leave_status', 'like', '%' . $searchValue . '%')
                ->orWhere('leaves.apply_date', 'like', '%' . $searchValue . '%')
                ->orWhere('leave_types.name', 'like', '%' . $searchValue . '%')
                ->orWhere('leaves.start_date', 'like', '%' . $searchValue . '%')
                ->orWhere('leaves.end_date', 'like', '%' . $searchValue . '%')
                ->select(
                    'leaves.id',
                    'users.user_code',                   
                    'leaves.start_date',
                    'leaves.end_date',
                    'leaves.leave_subject',
                    'leaves.leave_message',
                    'leaves.leave_type',
                    'leaves.leave_status',
                    'leaves.apply_date',
                    'leave_types.name'
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

    protected function getEmpAssignLeaveType($emid,$typeId,$year) {
    	$result = AssignLeave::where('emp_id', $emid)->where('type_id', $typeId)->where('dateyear', $year)->first();
        
        return $result;
    }

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(Request $request)
	{
		if ($request->ajax()) {			
			$employeeID = $request->employeeID;

            $leaveID = $request->leaveID;

            $year = date('Y');
            
            $daysTaken = $this->getEmpAssignLeaveType($employeeID, $leaveID, $year);
            
            $leavetypes = LeaveType::findOrFail($leaveID);

            if (empty($daysTaken->hour)) {
                $daysTakenval = '0';
            } else {
                $daysTakenval = $daysTaken->hour / 8;
            }

            if ($leaveID =='5') {
            	// $earnTaken = $this->leave_model->emEarnselectByLeave($employeeID);
                $totalday = 0;//'Earned Balance: '.($earnTaken->hour / 8).' Days';
            } else {
                //$totalday   = $leavetypes->leave_day . '/' . ($daysTaken/8);
                $totalday = 'Leave Balance: '.($leavetypes->leave_day - $daysTakenval).' Days Of '.$leavetypes->leave_day;
            }

            return response()->json(['totalday' => $totalday]);

           /* $daysTaken = $this->leave_model->GetemassignLeaveType('Sah1804', 2, 2018);
            $leavetypes = $this->leave_model->GetleavetypeInfoid($leaveID);
            // $totalday   = $leavetypes->leave_day . '/' . $daysTaken['0']->day;
            echo $daysTaken['0']->day;
            echo $leavetypes->leave_day;*/
		} else {
	   		return view('employee.leaves.create');
		}
	}

	public function store(Request $request)
	{   
		$data = $request->all();

		$request->validate([
			'leave_subject' => 'required|max:255',		
			'typeid' => ['required'],
			'type' => ['required'],
			'startdate' => ['required', 'date'],
			'leave_message' => ['required'],
			// 'leave_type' => ['required'],
			// 'hourAmount' => ['required'],
		],[],[
			'typeid' => 'leave type',
			'type' => 'leave duration',
			'leave_message' => 'reason'
		]);
		
        $typeid       = $data['typeid'];
        $applydate    = date('Y-m-d');
        $appstartdate = $data['startdate'];
        $appenddate   = $data['enddate'];
        $hourAmount   = $data['hourAmount'];
        // $reason       = $data['reason'];
        $type         = $data['type'];
        // $duration     = $this->input->post('duration');

        if($type == 'Half Day') {
            $duration = $hourAmount;
        } else if($type == 'Full Day') { 
            $duration = 8;
        } else { 
            $formattedStart = new \DateTime($appstartdate);
            $formattedEnd = new \DateTime($appenddate);

            $duration = $formattedStart->diff($formattedEnd)->format("%d");
            $duration = $duration * 8;
        }

        $postData = [
        	'user_id' => auth()->user()->id,
        	'leave_subject' => $data['leave_subject'],
        	'leave_message' => $data['leave_message'],
            'type_id' => $typeid,
            'apply_date' => $applydate,
            'start_date' => $appstartdate,
            'end_date' => $appenddate,
            // 'reason' => $reason,
            'leave_type' => $type,
            'leave_duration' => $duration,
            'leave_status' => 'pending',
        ];

		Leave::create($postData);		
		
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