<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Attendance;

class CheckPunchin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $attendanceDate = date('Y-m-d');

        $isPunchInCount = Attendance::where('user_id', auth()->user()->id)
            ->whereDate('attendance_date', $attendanceDate)
            ->where('action_name', 'punchin')
            ->count();

        if ($isPunchInCount > 0) {
            return $next($request);
        }

        return redirect()->route('dashboard')->with('error','Please punch in first to proceed further!');   
    }
}
