<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;

class PunchController extends Controller
{
	public function store(Request $request)
	{
		if (request()->ajax()) {
			$emp_code = auth()->user()->user_code;

			$attendance_date = date('Y-m-d');

			$attendanceSQL = Attendance::where('emp_code', auth()->user()->user_code)
				->whereDate('attendance_date', date('Y-m-d'))->get();

			if (!$attendanceSQL->isEmpty()) {
				$attendanceROW = $attendanceSQL->count();
				if ( $attendanceROW == 0 ) {
					$action_name = 'punchin';
				} else {
					$attendanceRecord = $attendanceSQL->first();
					if ( $attendanceRecord->action_name == 'punchin' ) {
						$action_name = 'punchout';
					} else {
						$action_name = 'punchin';
					}
				}
			} else {
				$attendanceROW = 0;
				$action_name = 'punchin';
			}

			$action_time = date('H:i:s');

			$emp_desc = addslashes($request->emp_desc);

			$attendance = Attendance::create([
				'emp_code' => $emp_code,
				'user_id' => auth()->user()->id,
				'attendance_date' => $attendance_date,
				'action_name' => $action_name,
				'action_time' => $action_time,
				'emp_desc' => $emp_desc,
			]);

			if ( $attendance ) {
				$result['next'] = ($action_name == 'punchin' ? 'Punch Out' : 'Punch In');
				$result['complete'] = $attendanceROW + 1;
				$result['result'] = 'You have successfully punched in.';
				$result['code'] = 0;
			} else {
				$result['result'] = 'Something went wrong, please try again.';
				$result['code'] = 1;
			}

			return response()->json([
				'status'=>true, 
				'result'=> $result
			]);
		
		}           
	}
}
