<!DOCTYPE html>
<html>
<head>
    <title>Statutory Deductions Report</title>
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
    <h2>Statutory Deductions Report</h2>
    
    <table>
        <thead>
            <tr>
                <th>Employee</th>
                <th>Pay Period</th>
                <th>Employee Medical Benefits</th>
                <th>Employee Social Security</th>
                <th>Employee Education Levy</th>
                <th>Employer Medical Benefits</th>
                <th>Employer Social Security</th>
                <th>Employer Education Levy</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalEmployeeMedical = 0;
                $totalEmployeeSecurity = 0;
                $totalEmployeeEduLevy = 0;
                $totalEmployerMedical = 0;
                $totalEmployerSecurity = 0;
                $totalEmployerEduLevy = 0;
                $totalOverall = 0;
            @endphp
            @foreach($earnings as $earning)
                <tr>
                    <td>{{ $earning->user->name }}</td>
                    <td>{{ date('M d, Y', strtotime($earning->start_date)) }} - {{ date('M d, Y', strtotime($earning->end_date)) }}</td>
                    <td class="text-end">${{ number_format($earning->medical, 2) }}</td>
                    <td class="text-end">${{ number_format($earning->security, 2) }}</td>
                    <td class="text-end">${{ number_format($earning->edu_levy, 2) }}</td>
                    <td class="text-end">${{ number_format($earning->medical, 2) }}</td>
                    <td class="text-end">${{ number_format($earning->security, 2) }}</td>
                    <td class="text-end">${{ number_format($earning->edu_levy, 2) }}</td>
                    <td class="text-end">${{ number_format($earning->medical * 2 + $earning->security * 2 + $earning->edu_levy * 2, 2) }}</td>
                </tr>
                @php
                    $totalEmployeeMedical += $earning->medical;
                    $totalEmployeeSecurity += $earning->security;
                    $totalEmployeeEduLevy += $earning->edu_levy;
                    $totalEmployerMedical += $earning->medical;
                    $totalEmployerSecurity += $earning->security;
                    $totalEmployerEduLevy += $earning->edu_levy;
                    $totalOverall += ($earning->medical * 2 + $earning->security * 2 + $earning->edu_levy * 2);
                @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr class="fw-bold">
                <td colspan="2" class="text-end">Subtotals:</td>
                <td class="text-end">${{ number_format($totalEmployeeMedical, 2) }}</td>
                <td class="text-end">${{ number_format($totalEmployeeSecurity, 2) }}</td>
                <td class="text-end">${{ number_format($totalEmployeeEduLevy, 2) }}</td>
                <td class="text-end">${{ number_format($totalEmployerMedical, 2) }}</td>
                <td class="text-end">${{ number_format($totalEmployerSecurity, 2) }}</td>
                <td class="text-end">${{ number_format($totalEmployerEduLevy, 2) }}</td>
                <td class="text-end">${{ number_format($totalOverall, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html> 