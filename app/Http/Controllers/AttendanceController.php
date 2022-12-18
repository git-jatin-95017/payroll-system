<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Attendance;

class AttendanceController extends Controller
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

    public function index() {
        return view('attendance.index');
    }

    public function getData(Request $request) { 

        if ($request->ajax()) {
            $requestData = $request->all();

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
            $totalRecords = Attendance::select('count(*) as allcount')->count();
            $totalRecordswithFilter = Attendance::select('count(*) as allcount')->join('users', function($join) {
                $join->on('users.id', '=', 'attendances.user_id');
            })
            // ->where('users.email', 'like', '%' . $searchValue . '%')
            ->count();

            // Get records, also we have included search filter as well
            $records = Attendance::orderBy($columnName, $columnSortOrder)
                ->select(
                    'users.id',
                    'users.user_code',
                    'attendances.attendance_date',
                    DB::raw("CONCAT(employee_profile.first_name, ' ' , employee_profile.last_name) AS name"),                                    
                    DB::raw('GROUP_CONCAT(attendances.action_time) as times'),
                    DB::raw('GROUP_CONCAT(attendances.emp_desc) as descs')
                )
                // ->selectRaw('GROUP_CONCAT(attendances.action_time) as times')
                // ->selectRaw('GROUP_CONCAT(attendances.emp_desc) as descs')
                ->join('users', function($join) {
                    $join->on('users.id', '=', 'attendances.user_id');
                }) 
                ->join('employee_profile', function($join) {
                    $join->on('users.id', '=', 'employee_profile.user_id');
                })                         
                ->orWhere(DB::raw("CONCAT(employee_profile.first_name, ' ', employee_profile.last_name)"), 'like', '%' . $searchValue . '%')
                ->orWhere('users.user_code', 'like', '%' . $searchValue . '%')
                ->orWhereDate('attendances.attendance_date', 'like', '%' . $searchValue . '%')
                ->groupBy(
                    'users.user_code', 
                    'attendance_date'
                )
                ->having('times', 'like', '%' . $searchValue . '%')
                ->having('descs', 'like', '%' . $searchValue . '%')
                ->skip($start)
                ->take($rowperpage)
                ->get();
               
            $data = array();

            foreach($records as $k => $row) {
                $nestedData = array();
                $nestedData['attendance_date'] = date('m/d/Y', strtotime($row['attendance_date']));
                $nestedData['user_code'] = $row["user_code"];
                $nestedData['name'] = '<a target="_blank" href="' . '/' . 'reports/' . $row["emp_code"] . '/">' . $row["name"] . '</a>';
                $times = explode(',', $row["times"]);
                $descs = explode(',', $row["descs"]);
                $nestedData['punchin'] = isset($times[0]) ? date('h:i:s A', strtotime($times[0])) : '';
                $nestedData['punchin_message'] = isset($descs[0]) ? $descs[0] : '';
                $nestedData['punchout'] = isset($times[1]) ? date('h:i:s A', strtotime($times[1])) : '';
                $nestedData['punchout_message'] = isset($descs[1]) ? $descs[1] : '';

                if (!empty($times[0]) && !empty($times[1])) {            
                    $datetime1 = new \DateTime($times[0]);
                    $datetime2 = new \DateTime($times[1]);
                    $interval = $datetime1->diff($datetime2);
                $nestedData['work_hrs'] = (isset($times[0]) && isset($times[1])) ? $interval->format('%h') . " Hrs  | " . $interval->format('%i') . " Min" : 0 . "H";
                }

                $data[] = $nestedData;
            }
            
            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => $totalRecords,
                "iTotalDisplayRecords" => $totalRecordswithFilter,
                "aaData" => $data,
            );

            return response()->json($response);
        }      
    }
}
