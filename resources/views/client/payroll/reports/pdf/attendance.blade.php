<!DOCTYPE html>
<html>
<head>
    <title>Attendance Report</title>
    <style>
          body{
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13px;
            -webkit-print-color-adjust: exact;
            color: #6e6e6e;
            }
        @page {
            margin:10px 5px;
            size: letter; /*or width then height 150mm 50mm*/
        }
        @page wide {
         size: a4 landscape;
        }
    </style>
    <style>
        body {
            font-family: sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .filters {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Attendance Report</h2>
    </div>

    <div class="filters">
        @if(request()->filled('start_date'))
            <p><strong>Start Date:</strong> {{ date('M d, Y', strtotime(request('start_date'))) }}</p>
        @endif
        @if(request()->filled('end_date'))
            <p><strong>End Date:</strong> {{ date('M d, Y', strtotime(request('end_date'))) }}</p>
        @endif
        @if(request()->filled('department_id'))
            <p><strong>Department:</strong> {{ request('department_id') }}</p>
        @endif
        @if(request()->filled('employee_id') && isset($employees) && !$employees->isEmpty())
            @php
                $selectedEmployee = $employees->firstWhere('id', request('employee_id'));
            @endphp
            @if($selectedEmployee)
                <p><strong>Employee:</strong> {{ $selectedEmployee->name }}</p>
            @endif
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Employee</th>
                <th>Date</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th>Duration</th>
                <th>Note</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $attendance)
            <tr>
                <td>{{ $attendance->user->name }}</td>
                <td>{{ date('M d, Y', strtotime($attendance->checked_in_at)) }}</td>
                <td>{{ $attendance->checked_in_at ? date('h:i A', strtotime($attendance->checked_in_at)) : '-' }}</td>
                <td>{{ $attendance->checked_out_at ? date('h:i A', strtotime($attendance->checked_out_at)) : '-' }}</td>
                <td>
                    @if($attendance->checked_in_at && $attendance->checked_out_at)
                        {{ \Carbon\Carbon::parse($attendance->checked_in_at)->diffInHours(\Carbon\Carbon::parse($attendance->checked_out_at)) }} hours
                    @else
                        -
                    @endif
                </td>
                <td>{{ $attendance->note ?? '-' }}</td>
                <td>Completed</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 