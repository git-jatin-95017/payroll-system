<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Checkin;
use Carbon\Carbon;

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
            try {
                $requestData = $request->all();

                $draw = $request->get('draw');
                $start = $request->get("start");
                $rowperpage = $request->get("length"); // total number of rows per page

                $columnIndex_arr = $request->get('order');
                $columnName_arr = $request->get('columns');
                $order_arr = $request->get('order');
                $search_arr = $request->get('search');

                // Set default values if arrays are empty
                $columnIndex = isset($columnIndex_arr[0]['column']) ? $columnIndex_arr[0]['column'] : 0;
                $columnName = isset($columnName_arr[$columnIndex]['name']) ? $columnName_arr[$columnIndex]['name'] : 'checked_in_at';
                $columnSortOrder = isset($order_arr[0]['dir']) ? $order_arr[0]['dir'] : 'desc';
                $searchValue = isset($search_arr['value']) ? $search_arr['value'] : '';

                // Total records
                $totalRecords = Checkin::select('count(*) as allcount')
                    ->join('users', function($join) {
                        $join->on('users.id', '=', 'checkins.user_id')
                            ->where('users.created_by', auth()->user()->id);
                    })
                    ->count();

                $totalRecordswithFilter = Checkin::select('count(*) as allcount')
                    ->join('users', function($join) {
                        $join->on('users.id', '=', 'checkins.user_id')
                            ->where('users.created_by', auth()->user()->id);
                    })
                    ->when($searchValue, function($query) use ($searchValue) {
                        return $query->where(function($q) use ($searchValue) {
                            $q->where(DB::raw("CONCAT(employee_profile.first_name, ' ', employee_profile.last_name)"), 'like', '%' . $searchValue . '%')
                              ->orWhere('users.user_code', 'like', '%' . $searchValue . '%')
                              ->orWhereDate('checkins.checked_in_at', 'like', '%' . $searchValue . '%');
                        });
                    })
                    ->count();

                // Get records
                $query = Checkin::select(
                    'users.id',
                    'users.user_code',
                    'checkins.checked_in_at',
                    'checkins.checked_out_at',
                    'checkins.note',
                    DB::raw("CONCAT(employee_profile.first_name, ' ' , employee_profile.last_name) AS name")
                )
                ->join('users', function($join) {
                    $join->on('users.id', '=', 'checkins.user_id')
                        ->where('users.created_by', auth()->user()->id);
                }) 
                ->join('employee_profile', function($join) {
                    $join->on('users.id', '=', 'employee_profile.user_id');
                });

                // Apply search
                if ($searchValue) {
                    $query->where(function($q) use ($searchValue) {
                        $q->where(DB::raw("CONCAT(employee_profile.first_name, ' ', employee_profile.last_name)"), 'like', '%' . $searchValue . '%')
                          ->orWhere('users.user_code', 'like', '%' . $searchValue . '%')
                          ->orWhereDate('checkins.checked_in_at', 'like', '%' . $searchValue . '%');
                    });
                }

                // Apply sorting
                if ($columnName === 'name') {
                    $query->orderBy(DB::raw("CONCAT(employee_profile.first_name, ' ', employee_profile.last_name)"), $columnSortOrder);
                } else {
                    $query->orderBy($columnName, $columnSortOrder);
                }

                $records = $query->skip($start)
                    ->take($rowperpage)
                    ->get();
                   
                $data = array();

                foreach($records as $row) {
                    $nestedData = array();
                    
                    // Format date
                    $nestedData['date'] = Carbon::parse($row['checked_in_at'])->format('m/d/Y');
                    
                    // Employee name with code
                    $nestedData['employee'] = $row["name"];// . ' (' . $row["user_code"] . ')';
                    
                    // Check in time
                    $nestedData['check_in'] = Carbon::parse($row['checked_in_at'])->format('h:i:s A');
                    
                    // Check out time
                    $nestedData['check_out'] = $row['checked_out_at'] ? Carbon::parse($row['checked_out_at'])->format('h:i:s A') : '-';
                    
                    // Calculate duration
                    if ($row['checked_out_at']) {
                        $checkIn = Carbon::parse($row['checked_in_at']);
                        $checkOut = Carbon::parse($row['checked_out_at']);
                        $duration = $checkIn->diff($checkOut);
                        $nestedData['duration'] = $duration->format('%h Hrs | %i Min');
                    } else {
                        $nestedData['duration'] = '-';
                    }
                    
                    // Note
                    $nestedData['note'] = $row['note'] ?? '-';
                    
                    // Status
                    $nestedData['status'] = $row['checked_out_at'] ? 
                        '<span class="badge bg-success">Completed</span>' : 
                        '<span class="badge bg-warning">In Progress</span>';

                    $data[] = $nestedData;
                }
                
                return response()->json([
                    "draw" => intval($draw),
                    "iTotalRecords" => $totalRecords,
                    "iTotalDisplayRecords" => $totalRecordswithFilter,
                    "aaData" => $data,
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    "draw" => intval($draw ?? 1),
                    "iTotalRecords" => 0,
                    "iTotalDisplayRecords" => 0,
                    "aaData" => [],
                    "error" => $e->getMessage()
                ], 500);
            }
        }
        return response()->json(['error' => 'Invalid request'], 400);
    }
}
