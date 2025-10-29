<!DOCTYPE html>
<html>
<head>
    <title>Leave Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .fw-bold { font-weight: bold; }
        .text-end { text-align: right; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 4px; color: #fff; font-size: 11px; }
        .bg-success { background-color: #28a745; }
        .bg-warning { background-color: #ffc107; color: #212529; }
        .bg-danger { background-color: #dc3545; }
    </style>
</head>
<body>
    <h2>Leave Report</h2>
    <table>
        <thead>
            <tr>
                <th>Employee</th>
                <th>Pay Period</th>
                <th>Requests</th>
                <th>Status</th>
                <th>Paid/Unpaid</th>
                <th>Leave Policy</th>
                <th>Running Balance Used</th>
                <th>Running Balance Remaining</th>
            </tr>
        </thead>
        <tbody>
            @foreach($leaveRequests as $request)
                @if(($request->total_used ?? 0) > 0)
                <tr>
                    <td>{{ $request->user->name ?? '' }}</td>
                    <td>{{ $request->pay_period ?? '' }}</td>
                    <td>{{ $request->requests ?? '' }}</td>
                    <td>
                        <span class="badge bg-{{ $request->leave_status == 'approved' ? 'success' : ($request->leave_status == 'pending' ? 'warning' : 'danger') }}">
                            {{ ucfirst($request->leave_status ?? '') }}
                        </span>
                    </td>
                    <td>{{ $request->leave_type ?? '' }}</td>
                    <td>{{ $request->leaveType->name ?? '' }}</td>
                    <td>{{ $request->total_used ?? '' }} hrs</td>
                    <td>{{ $request->leave_balance ?? '' }} hrs</td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</body>
</html> 