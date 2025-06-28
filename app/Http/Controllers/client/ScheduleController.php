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
        if ($request->ajax() && $request->has('employee_id')) {
            // AJAX request for calendar events
            $start = $request->input('start_date');
            $end = $request->input('end_date');
            $employeeId = $request->input('employee_id');
            
            $query = Schedule::with('employee');
            
            if ($employeeId) {
                $query->where('employee_id', $employeeId);
            }
            
            if ($start && $end) {
                $query->whereBetween('start_date', [$start, $end]);
            }
            
            $schedules = $query->get();
            
            return response()->json(['schedules' => $schedules]);
        }
        
        // Regular view request
        $start = $request->input('start_date', now()->startOfMonth()->toDateString());
        $end = $request->input('end_date', now()->endOfMonth()->toDateString());
        $employees = User::with('employeeProfile')->get();
        $schedules = Schedule::whereBetween('start_date', [$start, $end])->get();
        
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
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
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
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
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
}
