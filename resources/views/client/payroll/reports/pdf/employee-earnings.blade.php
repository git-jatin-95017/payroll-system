<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>index</title>
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
<body style="font-family: Arial, Helvetica, sans-serif; font-size: 13px;">
<table style="width: 100%; border-collapse: collapse; table-layout: fixed; margin-bottom: 10px;">

    <tr>
        <td style="padding:3px 5px; margin-bottom: 25px;">
            <strong style="font-size: 13px; color: #000;">Employee Earnings Report </strong>
        </td>
    </tr>
    <tr>
        <td style="padding:3px 5px;">&nbsp;</td>
    </tr>
   
</table>

<table style="width: 100%; border-collapse: collapse; table-layout: fixed; text-align: left; vertical-align: middle; margin-bottom: 25px;">
    <tr>
       <thead style="border-top: 2px solid #58a8a4;">
            <th style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;  color: #000;">Employee</th>
            <th style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;  color: #000;">Pay Period</th>
            <th style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;  color: #000;">Gross Pay</th>
            <th style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;  color: #000;">Medical Benefits </th> 
            <th style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;  color: #000;">Social Security </th> 
            <th style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;  color: #000;">Education Levy </th> 
            <th style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;  color: #000;">Additions </th> 
            <th style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;  color: #000;">Deductions </th> 
            <th style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;  color: #000;">Employee Pay </th> 
       </thead>
    </tr>
    <tbody style="border-bottom: 1px solid #ddd;">
       
        @php
			$grosspay =0;
			$medicalbenefits =0;
			$socialsecurity =0;
			$educationlevy =0;
			$additions = 0;
			$deductions = 0;
			$totalemppay = 0;
            if (count($payrolls) > 0) {
                foreach($payrolls as $payroll) {
                    
					$grosspay += $payroll->gross;
					$medicalbenefits += $payroll->medical;
					$socialsecurity += $payroll->security;
					$educationlevy += $payroll->edu_levy;
					$add =  number_format($payroll->additionalEarnings->where('payhead.pay_type', 'nothing')->sum('amount'), 2);
					$ded =  number_format($payroll->additionalEarnings->where('payhead.pay_type', 'deductions')->sum('amount'), 2);
					$additions += $add;
					$deductions += $ded;
                    $totalemppay += $payroll->employee_pay; 
        @endphp
            <tr>    
                                    <td>{{ $payroll->user->name }}</td>
                                    <td>{{ date('M d, Y', strtotime($payroll->start_date)) }} - {{ date('M d, Y', strtotime($payroll->end_date)) }}</td>
                                    <td>${{ number_format($payroll->gross, 2) }}</td>
                                    <td>${{ number_format($payroll->medical, 2) }}</td>
                                    <td>${{ number_format($payroll->security, 2) }}</td>
                                    <td>${{ number_format($payroll->edu_levy, 2) }}</td>
                                    <td>${{ number_format($payroll->additionalEarnings->where('payhead.pay_type', 'nothing')->sum('amount'), 2) }}</td>
                                    <td>${{ number_format($payroll->additionalEarnings->where('payhead.pay_type', 'deductions')->sum('amount'), 2) }}</td>
									<td>${{ number_format($payroll->employee_pay, 2) }}</td>
            </tr>
        @php                                                     
                }
		@endphp
		<tr>
		<td colspan="2" align="center"><strong>Total</strong></td>
		<td><strong>${{ number_format($grosspay, 2) }}</strong></td>
		<td><strong>${{ number_format($medicalbenefits, 2) }}</strong></td>
		<td><strong>${{ number_format($socialsecurity, 2) }}</strong></td>
		<td><strong>${{ number_format($educationlevy, 2) }}</strong></td>
		<td><strong>${{ number_format($additions) }}</strong></td>
		<td><strong>${{ number_format($deductions, 2) }}</strong></td>
		<td><strong>${{ number_format($totalemppay, 2) }}</strong></td>
		</tr>
		@php
            }
        @endphp

        
    </tbody>
</table>

</body>
</html>