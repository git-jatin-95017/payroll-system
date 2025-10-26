<!DOCTYPE html>
<html>
<head>
    <title>Employer Payments Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        .total-row {
            font-weight: bold;
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Employer Payments Report</h2>
        <p>Generated on: {{ date('Y-m-d H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Employee</th>
                <th>Pay Period</th>
                <th>Employee Pay</th>
                <th>Employee Taxes</th>
                <th>Employer Taxes</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalEmployeePay = 0;
                $totalEmployeeTaxes = 0;
                $totalEmployerTaxes = 0;
                $totalSubtotal = 0;
            @endphp

            @foreach($calculatedData as $item)
                @php
                    $payroll = $item['row'];
                    $amounts = $item['amounts'];
                    
                    // Use calculated values from trait for consistency
                    $employeePay = $amounts['employee_pay'];
                    $employeeTaxes = $amounts['medical_benefits'] + $amounts['social_security'] + $amounts['education_levy'];
                    $employerTaxes = $amounts['medical_benefits'] + $amounts['social_security_employer'];
                    $subtotal = $employeePay + $employeeTaxes + $employerTaxes;

                    $totalEmployeePay += $employeePay;
                    $totalEmployeeTaxes += $employeeTaxes;
                    $totalEmployerTaxes += $employerTaxes;
                    $totalSubtotal += $subtotal;
                @endphp
                <tr>
                    <td>{{ $payroll->user->name }}</td>
                    <td>{{ date('M d, Y', strtotime($payroll->start_date)) }} - {{ date('M d, Y', strtotime($payroll->end_date)) }}</td>
                    <td>${{ number_format($employeePay, 2) }}</td>
                    <td>${{ number_format($employeeTaxes, 2) }}</td>
                    <td>${{ number_format($employerTaxes, 2) }}</td>
                    <td>${{ number_format($subtotal, 2) }}</td>
                </tr>
            @endforeach

            <tr class="total-row">
                <td colspan="2">Total</td>
                <td>${{ number_format($totalEmployeePay, 2) }}</td>
                <td>${{ number_format($totalEmployeeTaxes, 2) }}</td>
                <td>${{ number_format($totalEmployerTaxes, 2) }}</td>
                <td>${{ number_format($totalSubtotal, 2) }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html> 