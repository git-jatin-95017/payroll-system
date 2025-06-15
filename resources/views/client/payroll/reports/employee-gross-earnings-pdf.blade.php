<!DOCTYPE html>
<html>
<head>
    <title>Employee Gross Earnings Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-end {
            text-align: right;
        }
        .fw-bold {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2>Employee Gross Earnings Report</h2>
    
    <table>
        <thead>
            <tr>
                <th>Employee</th>
                <th>Pay Period</th>
                <th>Pay Type</th>
                <th>Rate</th>
                <th>Hours/Period</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalHours = 0;
                $totalAmount = 0;
            @endphp
            @foreach($earnings as $earning)
                <tr>
                    <td>{{ $earning->user->name ?? '' }}</td>
                    <td>{{ date('M d, Y', strtotime($earning->start_date)) }} - {{ date('M d, Y', strtotime($earning->end_date)) }}</td>
                    <td>{{ $earning->user->employeeProfile->pay_type ?? '-' }}</td>
                    <td class="text-end">{{ number_format($earning->rate ?? 0, 2) }}</td>
                    <td class="text-end">{{ number_format($earning->hours ?? 0, 2) }}</td>
                    <td class="text-end">${{ number_format($earning->gross ?? 0, 2) }}</td>
                </tr>
                @php
                    $totalHours += $earning->hours ?? 0;
                    $totalAmount += $earning->gross ?? 0;
                @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr class="fw-bold">
                <td colspan="4" class="text-end">Subtotals:</td>
                <td class="text-end">{{ number_format($totalHours, 2) }}</td>
                <td class="text-end">{{ number_format($totalAmount, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html> 