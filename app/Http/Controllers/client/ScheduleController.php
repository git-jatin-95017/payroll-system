<?php

namespace App\Http\Controllers\client;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\User;
use App\Http\Controllers\Controller;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $start = $request->input('start_datetime', now()->startOfWeek()->toDateTimeString());
            $end = $request->input('end_datetime', now()->endOfWeek()->toDateTimeString());
            $search = $request->input('search');
            
            // Get all employees for the logged-in client
            $employeesQuery = User::with('employeeProfile')->where('users.role_id', 3)->where('users.created_by', auth()->user()->id);
            
            $employeesQuery->leftJoin('emp_departments', function($join) {
                $join->on('users.id', '=', 'emp_departments.user_id');
            })->leftJoin('departments', function($join) {
                $join->on('departments.id', '=', 'emp_departments.department_id');
            });
            
            if ($search) {
                $employeesQuery->where(function($q) use ($search) {
                    $q->where('users.name', 'like', '%' . $search . '%')
                      ->orWhere('departments.dep_name', 'like', '%' . $search . '%')
                      ->orWhereHas('employeeProfile', function($subQ) use ($search) {
                          $subQ->where('department', 'LIKE', '%' . $search . '%')
                               ->orWhere('designation', 'LIKE', '%' . $search . '%')
                               ->orWhere('manager_position', 'LIKE', '%' . $search . '%');
                      });
                });
            }
            
            $employees = $employeesQuery->get()
                ->map(function($emp) {
                    return [
                        'id' => $emp->id,
                        'name' => $emp->name,
                        'avatar' => $emp->employeeProfile->file ?? null,
                        'designation' => $emp->employeeProfile->designation ?? '',
                    ];
                });
            
            // Get all schedules for those employees in the date range
            $schedules = Schedule::whereIn('employee_id', $employees->pluck('id'))
                ->whereBetween('start_datetime', [$start, $end])
                ->get();
                
            return response()->json([
                'employees' => $employees,
                'schedules' => $schedules
            ]);
        }
        
        // Regular view request
        $start = $request->input('start_datetime', now()->startOfMonth()->toDateTimeString());
        $end = $request->input('end_datetime', now()->endOfMonth()->toDateTimeString());
        
        // Get only employees created by the logged-in user
        $employees = User::with('employeeProfile')
                        ->where('created_by', auth()->user()->id)
                        ->get();
        
        $schedules = Schedule::whereBetween('start_datetime', [$start, $end])->get();
        
        // Check if this is an AJAX request for tab content
        if ($request->ajax()) {
            return view('client.payroll.schedule_content', compact('employees', 'schedules', 'start', 'end'));
        }
        
        return view('client.payroll.schedule', compact('employees', 'schedules', 'start', 'end'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:users,id',
            'title' => 'nullable|string|max:255',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after_or_equal:start_datetime',
            'description' => 'nullable|string',
        ]);
        $schedule = Schedule::create($data);
        return response()->json(['success' => true, 'schedule' => $schedule]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $schedule = Schedule::with('employee')->findOrFail($id);
        return response()->json($schedule);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:users,id',
            'title' => 'nullable|string|max:255',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after_or_equal:start_datetime',
            'description' => 'nullable|string',
        ]);
        
        $schedule = Schedule::findOrFail($id);
        $schedule->update($data);
        
        return response()->json(['success' => true, 'schedule' => $schedule]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();
        return response()->json(['success' => true]);
    }

    /**
     * Check if all schedules in the given range are published
     */
    public function publishedStatus(Request $request)
    {
        $start = $request->input('start_datetime');
        $end = $request->input('end_datetime');
        $employeeIds = User::where('created_by', auth()->user()->id)->pluck('id');
        $count = Schedule::whereIn('employee_id', $employeeIds)
            ->whereBetween('start_datetime', [$start, $end])
            ->count();
        $publishedCount = Schedule::whereIn('employee_id', $employeeIds)
            ->whereBetween('start_datetime', [$start, $end])
            ->where('published', true)
            ->count();
        $published = ($count > 0 && $count === $publishedCount);
        return response()->json(['published' => $published]);
    }

    /**
     * Publish all schedules in the given range
     */
    public function publish(Request $request)
    {
        $start = $request->input('start_datetime');
        $end = $request->input('end_datetime');
        $employeeIds = User::where('created_by', auth()->user()->id)->pluck('id');
        $now = now();
        Schedule::whereIn('employee_id', $employeeIds)
            ->whereBetween('start_datetime', [$start, $end])
            ->update(['published' => true, 'published_at' => $now]);
        return response()->json(['success' => true]);
    }
}
