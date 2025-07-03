<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\User;

class EmployeeScheduleController extends Controller
{
    public function index(Request $request)
    {
        $employee = auth()->user();
        
        // Get schedules for the logged-in employee
        $schedules = Schedule::where('employee_id', $employee->id)
            ->when($request->filled('start_datetime') && $request->filled('end_datetime'), function($query) use ($request) {
                return $query->where(function($q) use ($request) {
                    $q->whereBetween('start_datetime', [$request->start_datetime, $request->end_datetime])
                      ->orWhereBetween('end_datetime', [$request->start_datetime, $request->end_datetime])
                      ->orWhere(function($subQ) use ($request) {
                          $subQ->where('start_datetime', '<=', $request->start_datetime)
                               ->where('end_datetime', '>=', $request->end_datetime);
                      });
                });
            })
            ->where('is_published', 1)
            ->get();

        if ($request->ajax()) {
            return response()->json([
                'schedules' => $schedules
            ]);
        }

        return view('employee.schedule.index', compact('schedules'));
    }

    public function show($id)
    {
        $employee = auth()->user();
        $schedule = Schedule::where('id', $id)
                           ->where('employee_id', $employee->id)
                           ->firstOrFail();

        return response()->json($schedule);
    }
} 