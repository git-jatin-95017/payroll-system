<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\AssignLeave;
use App\Models\EarnedLeave;
use App\Models\LeaveType;
// use DataTables;
// use Yajra\DataTables\Html\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class LeavesController extends Controller
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
		return view('client.leaves.index');
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
                ->orWhere('leaves.start_date', 'like', '%' . $searchValue . '%')
                ->orWhere('leaves.end_date', 'like', '%' . $searchValue . '%')
                ->select(
                    'leaves.id',
                    'leaves.user_id',
                    'leaves.type_id',
                    'users.name',                   
                    'leaves.start_date',
                    'leaves.end_date',
                    'leave_types.name as leave_name',
                    'leaves.leave_subject',
                    'leaves.leave_message',
                    'leaves.leave_type',
                    'leaves.leave_status',
                    'leaves.leave_duration',
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

    protected function determineIfNewLeaveAssign($employeeId, $type) {
        $count = AssignLeave::where('emp_id', $employeeId)->where('type_id', $type)->count();
        return $count;
    }

    protected function getLeaveTypeTotal($employeeId, $type) {
        $result = DB::select("SELECT SUM(`hour`) AS 'totalTaken' FROM `assign_leaves` WHERE `assign_leaves`.`emp_id`='$employeeId' AND `assign_leaves`.`type_id`='$type'");        
        return $result;
    }

    protected function updateLeaveAssignedInfo($employeeId, $type, $data) {
        DB::table('assign_leaves')
            ->where('type_id', $type)
            ->where('emp_id', $employeeId)
            ->update($data);    
    }

    
    protected function emEarnselectByLeave($employeeId) {
        $result = EarnedLeave::where('em_id', $employeeId)->first();
        
        return $result; 
    }

    // protected function updateEarnValue($employeeId) {
    //     $this->db->where('em_id', $emid);
    //     $this->db->update('earned_leave', $data); 
    // }
    

    protected function insertLeaveAssignedInfo($employeeId) {
        $this->db->insert('assign_leaves', $data);
    }

	public function update(Request $request, $id)
	{
		if (request()->ajax()) {
            $data = $request->all();

            $employeeId = $data['employeeId'];
            $id       = $data['lid'];
            $value    = $data['lvalue'];
            $duration = $data['duration'];
            $type     = $data['type'];

            $leave = Leave::find($id);

            if ($request->action == 'approve') {
                //Update leave status
                $leave->leave_status = 'approved';
                $leave->save();

                //Leave balance Logic
                $determineIfNew = $this->determineIfNewLeaveAssign($employeeId, $type);

                //How much taken
                $totalHour = $this->getLeaveTypeTotal($employeeId, $type);

                //If already taken some
                if($determineIfNew  > 0) {
                    $total    = $totalHour[0]->totalTaken + ($leave->leave_type == $type ? $duration : 0);
                    $data     = array();
                    $data     = array(
                        'hour' => $total
                    );
                    $success  = $this->updateLeaveAssignedInfo($employeeId, $type, $data);
                    
                    $earnval = $this->emEarnselectByLeave($employeeId); 

                    if ($earnval) {
                        $data = array();
                        $data = array(
                            'present_date' => $earnval->present_date - ($duration/8),
                            'hour' => $earnval->hour - $duration
                        );
                        $earnval->update($data);                        
                    }
                
                } else {
                    //If not taken yet
                    $dataal = array();
                    $dataal = array(
                        'emp_id' => $employeeId,
                        'type_id' => $type,
                        'hour' => $duration,
                        'dateyear' => date('Y')
                    );
                   
                   AssignLeave::create($dataal);
                }

                return response()->json(['status'=>true, 'message'=>"Leave approved successfully."]);
            }

            if ($request->action == 'reject') {
                $leave->leave_status = 'rejected';
                $leave->save();
                return response()->json(['status'=>true, 'message'=>"Leave rejected successfully."]);
            }        
        }			
	}

    public function edit($id, $empId)
    {
        $leave = Leave::find($id);

        $leavetypes = LeaveType::where('status', 1)->get();

        return view('client.leaves.edit', compact('leave', 'leavetypes', 'empId'));
    }

    public function updateLeave(Request $request, $id)
    {
        $data = $request->all();

        $leave = Leave::find($id);

        // $leave = Leave::where('user_id', $leave)->find($id);
        
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
            // 'user_id' => auth()->user()->id,
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
        
        $leave->update($postData);  

        return redirect()->route('leaves.index')->with('message', 'Leave updated successfully.');    
    }   
}