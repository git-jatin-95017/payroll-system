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
</head>
<body style="font-family: Arial, Helvetica, sans-serif; font-size: 13px;">
<table style="width: 100%; border-collapse: collapse; table-layout: fixed; margin-bottom: 40px;">
    <tr>
        <td style="padding:3px 5px;" colspan="2">
            @if(auth()->user()->role_id == 2)
                @if(!empty(auth()->user()->logo))
                    <img style="width: 100px;" src="{{ public_path('files/' . auth()->user()->logo) }}" alt="">
                @else
                    <img style="width: 100px;" src="{{ public_path('img/logo-new.jpg') }}" alt="">
                @endif
            @else
                <img style="width: 100px;" src="{{ public_path('img/logo-new.jpg') }}" alt="">
            @endif
        </td>
    </tr>
    <tr>
        <td style="padding:3px 5px;">
            <h3 style="font-size: 38px; color: #000; font-weight: bold; margin-bottom: 5px; margin-top: 0;">Payslip</h3>
            <p style="margin-bottom: 0; font-size: 14px;">Pay period: {{ date('F dS Y', strtotime($start_date))}} - {{ date('F dS Y', strtotime($end_date))}}</p>
        </td>
        <td>
            <table style="width: 100%; border-collapse:collapse; table-layout: fixed;">
                <tr>
                    <td style="padding:3px 5px;">
                        <strong style="font-size: 14px; color: #000;">Company:</strong>
                    </td>
                    <td style="padding:3px 5px;">
                        <strong style="font-size: 14px; color: #000;">Employee:</strong>
                    </td>
                </tr>
                <tr>
                    <td style="padding:3px 5px;">{{ auth()->user()->name }}</td>
                    <td style="padding:3px 5px;">{{ $data->user->name }}</td>
                </tr>
                <tr>
                    <td style="padding:3px 5px;">{{ auth()->user()->phone_number }}</td>
                    <td style="padding:3px 5px;">Employee cell: {{ $data->user->employeeProfile->phone_number }}</td>
                </tr>
                <tr>
                    <td style="padding:3px 5px;">&nbsp;</td>
                    <td style="padding:3px 5px;">{{ $data->user->employeeProfile->address }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<?php
    $gross = 0;
    $employeePay = 0;
    $deductions = 0;
    $earnings = 0;
    $totalTaxes = 0;
    $paidTimeOff = 0;
    $paidTimeOffBalane = 0;
    $unpaidTimeOff = 0;
    $unpaidTimeOffBalane = 0;
    $reimbursement = $data->reimbursement;

    if (count($data->additionalEarnings) > 0){
        foreach($data->additionalEarnings as $key => $val) {
            if($val->payhead->pay_type =='earnings') {
                $earnings += $val->amount;
            }

            if($val->payhead->pay_type =='deductions') {
                $deductions += $val->amount;
            }
        }
    }

    if (count($data->additionalPaids) > 0){
        foreach($data->additionalPaids as $key => $val) {
            $paidTimeOff += $val->amount;                                            
            $paidTimeOffBalane += $val->leave_balance;                                            
        }
    }

    if (count($data->additionalUnpaids) > 0){
        foreach($data->additionalUnpaids as $key => $val) {
            $unpaidTimeOff += $val->amount;                                            
            $unpaidTimeOffBalane += $val->leave_balance;                                            
        }
    }

    $regHrs = $data->user->employeeProfile->pay_rate * $data->total_hours;

    $gross += ($regHrs + $data->overtime_hrs + $data->doubl_overtime_hrs + $data->holiday_pay + $earnings + $data->paid_time_off);

    $employeePay = $gross - ($data->medical +$data->security + $data->edu_levy) + $earnings - $deductions;

    // $totalEmployeePay += $employeePay;
    $totalTaxes += ($data->medical +$data->security + $data->edu_levy);
    // $totalDeductions += $deductions;
    // $totalAdditions += $earnings;
?>
<table style="width: 100%; border-collapse: collapse; table-layout: fixed; text-align: left; vertical-align: middle; margin-bottom: 25px;">
    <tr>
       <thead style="border-top: 2px solid #58a8a4;">
            <th style="padding:3px 5px; border-right: 1px solid #ddd; color: #000;">
                Description Rate
            </th>
            <th style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;  color: #000;">Rate</th>
            <th style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;  color: #000;">Hours</th>
            <th style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;  color: #000;">Current</th>
            <th style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;  color: #000;">Year To Date </th>
       </thead>
    </tr>
    <tbody style="border-bottom: 1px solid #ddd;">
        <tr>
            <td style="padding:3px 5px; border-right: 1px solid #ddd;">Regular Hours I Hourly </td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($data->user->employeeProfile->pay_rate, 2)}}</td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">{{$data->total_hours}}</td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($regHrs, 2)}}</td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($regHrs, 2)}}</td>
        </tr>
        <tr>
            <td style="padding:3px 5px; border-right: 1px solid #ddd;">Gross Earnings</td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;"></td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;"></td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($gross, 2)}}</td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($gross, 2)}}</td>
        </tr>
    </tbody>
</table>
<table style="width: 100%; border-collapse: collapse; table-layout: fixed; text-align: left; vertical-align: middle; margin-bottom: 25px;">
    <tbody>
        <td style="padding-right: 10px;">
            <table style="width: 100%; border-collapse:collapse; table-layout: fixed;">
                <thead style="border-bottom: 2px solid #58a8a4;">
                    <tr>
                        <th style="padding:3px 5px;  color: #000; font-size: 14px;" colspan="3" >Employee Taxes Withheld</th>
                    </tr>
                </thead>
                <thead style="border-bottom: 1px solid #ddd;">
                    <tr>
                        <th style="padding:3px 5px; border-right: 1px solid #ddd; color: #000;">Employee Tax </th>
                        <th style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right; color: #000;">Current</th>
                        <th style="text-align: right; color: #000;">Year To Date </th>
                    </tr>
                    <tr>
                        <td style="padding:3px 5px; border-right: 1px solid #ddd; ">Medical benefits </td>
                        <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($data->medical, 2)}}</td>
                        <td style="padding:3px 5px; text-align: right;">${{number_format($data->medical, 2)}}</td>
                    </tr>
                    <tr>
                        <td style="padding:3px 5px; border-right: 1px solid #ddd;">Social Social Security</td>
                        <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($data->security, 2)}}</td>
                        <td style="padding:3px 5px; text-align: right;">${{number_format($data->security, 2)}}</td>
                    </tr>
                    <tr>
                        <td style="padding:3px 5px; border-right: 1px solid #ddd;">Education Levy </td>
                        <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($data->edu_levy, 2)}}</td>
                        <td style="padding:3px 5px; text-align: right;">${{number_format($data->edu_levy, 2)}}</td>
                    </tr>
                </thead>
            </table>
        </td>
        <td style="padding-left: 10px;">
            <table style="width: 100%; border-collapse:collapse; table-layout: fixed;">
                <thead style="border-bottom: 2px solid #58a8a4;">
                    <tr>
                        <th style="padding:3px 5px; color: #000; font-size: 14px;" colspan="3">Employee Taxes Withheld</th>
                    </tr>
                </thead>
                <tbody style="border-bottom: 1px solid #ddd;">
                    <tr>
                        <th style="padding:3px 5px; border-right: 1px solid #ddd; color: #000;">Employee Tax </th>
                        <th style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right; color: #000;">Current</th>
                        <th style="padding:3px 5px; text-align: right; color: #000;">Year To Date </th>
                    </tr>
                    <tr>
                        <td style="padding:3px 5px; border-right: 1px solid #ddd;">Medical benefits </td>
                        <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($data->medical+$data->security+$data->edu_levy+$data->security_employer, 2)}}</td>
                        <td style="padding:3px 5px; text-align: right;">${{number_format($data->medical+$data->security+$data->edu_levy+$data->security_employer, 2)}}</td>
                    </tr>
                    <tr>
                        <td style="padding:3px 5px; border-right: 1px solid #ddd;">Social Social Security</td>
                        <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">$90.78</td>
                        <td style="padding:3px 5px; text-align: right;">${{number_format($data->security_employer, 2)}}</td>
                    </tr>
                    <tr>
                        <td style="padding:3px 5px; border-right: 1px solid #ddd;">Education Levy </td>
                        <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">N/A </td>
                        <td style="padding:3px 5px; text-align: right;">N/A </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tbody>
</table>
<table style="width: 100%; border-collapse:collapse; table-layout: fixed; text-align: left; margin-bottom: 25px;">
    <thead style="border-bottom: 2px solid #58a8a4;">
        <tr>
            <th style="padding:3px 5px; color: #000; font-size: 14px;" colspan="3">Employee Pre-Tax Deductions </th>
        </tr>
    </thead>
    <thead style="border-bottom: 1px solid #ddd;">
        <tr>
            <th style="padding:3px 5px; border-right: 1px solid #ddd; color: #000;">Description</th>
            <th style="padding:3px 5px; border-right: 1px solid #ddd; color: #000;">Type</th>
            <th style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right; color: #000;">Current</th>
            <th style="padding:3px 5px; text-align: right; color: #000;">Year To Date </th>
        </tr>

        @php
        if (count($data->additionalEarnings) > 0) {
            foreach($data->additionalEarnings as $key => $val) {
                if($val->payhead->pay_type =='earnings') {
        @endphp
                <tr>
                    <td style="padding:3px 5px; border-right: 1px solid #ddd;">{{ ucfirst($val->payhead->pay_type) }}</td>
                    <td style="padding:3px 5px; border-right: 1px solid #ddd;">{{ $val->payhead->name}}</td>
                    <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($val->amount, 2)}}</td>
                    <td style="padding:3px 5px; text-align: right;">${{number_format($val->amount, 2)}}</td>
                </tr>
        @php
                }
            }
        }
        @endphp
    </thead>
</table>

<table style="width: 100%; border-collapse:collapse; table-layout: fixed; text-align: left; margin-bottom: 25px;">
    <thead style="border-bottom: 2px solid #58a8a4;">
        <tr>
            <th style="padding:3px 5px; color: #000; font-size: 14px;" colspan="3">Employer Post-Tax Deductions </th>
        </tr>
    </thead>
    <thead style="border-bottom: 1px solid #ddd;">
        <tr>
            <th style="padding:3px 5px; border-right: 1px solid #ddd; color: #000;">Description</th>
            <th style="padding:3px 5px; border-right: 1px solid #ddd; color: #000;">Type</th>
            <th style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right; color: #000;">Current</th>
            <th style="padding:3px 5px; text-align: right; color: #000;">Year To Date </th>
        </tr>

        @php
        if (count($data->additionalEarnings) > 0) {
            foreach($data->additionalEarnings as $key => $val) {
                if($val->payhead->pay_type =='deductions') {
        @endphp
                <tr>
                    <td style="padding:3px 5px; border-right: 1px solid #ddd;">{{ ucfirst($val->payhead->pay_type)}}</td>
                    <td style="padding:3px 5px; border-right: 1px solid #ddd;">{{ $val->payhead->name}}</td>
                    <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($val->amount, 2)}}</td>
                    <td style="padding:3px 5px; text-align: right;">${{number_format($val->amount, 2)}}</td>
                </tr>
        @php
                }
            }
        }
        @endphp
    </thead>
</table>

<table style="width: 100%; border-collapse:collapse; table-layout: fixed; text-align: left; margin-bottom: 25px;">
    <thead style="border-bottom: 2px solid #58a8a4;">
        <tr>
            <th style="padding:3px 5px; color: #000; font-size: 14px;" colspan="3">Summary </th>
        </tr>
    </thead>
    <thead style="border-bottom: 1px solid #ddd;">
        <tr>
            <th style="padding:3px 5px; border-right: 1px solid #ddd; color: #000;">Description</th>
            <th style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right; color: #000;">Current</th>
            <th style="padding:3px 5px;  border-right: 1px solid #ddd; text-align: right; color: #000;">Year To Date</th>
        </tr>
        <tr>
            <td style="padding:3px 5px; border-right: 1px solid #ddd;">Gross Earnings</td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($gross, 2)}}</td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($gross, 2)}}</td>
        </tr>
        <tr>
            <td style="padding:3px 5px; border-right: 1px solid #ddd;">Pre-Tax Deductions/Contr but ons</td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($earnings, 2)}}</td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($earnings, 2)}}</td>
        </tr>
        <tr>
            <td style="padding:3px 5px; border-right: 1px solid #ddd;">Taxes</td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($totalTaxes, 2)}}</td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($totalTaxes, 2)}}</td>
        </tr>
        <tr>
            <td style="padding:3px 5px; border-right: 1px solid #ddd;">Post-Tax Deductions/Contr but ons </td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($deductions, 2)}}</td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($deductions, 2)}}</td>
        </tr>
        <tr>
            <td style="padding:3px 5px; border-right: 1px solid #ddd;">Net Pay </td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($data->net_pay, 2)}}</td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($data->net_pay, 2)}}</td>
        </tr>
        <tr>
            <td style="padding:3px 5px; border-right: 1px solid #ddd;">Reimbursements</td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($data->reimbursement, 2)}}</td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($data->reimbursement, 2)}}</td>
        </tr>
        <!-- <tr>
            <td style="padding:3px 5px; border-right: 1px solid #ddd;">Check Amount </td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($totalTaxes, 2)}}</td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($totalTaxes, 2)}}</td>
        </tr> -->
    </thead>
</table>

<table style="width: 100%; border-collapse: collapse; table-layout: fixed; text-align: left; vertical-align: middle; margin-bottom: 25px;">
    <tbody>
        <td style="padding-right: 10px;">
            <table style="width: 100%; border-collapse:collapse; table-layout: auto;">
                <thead style="border-bottom: 2px solid #58a8a4;">
                    <tr>
                        <th style="padding:3px 5px; color: #000; font-size: 14px;" colspan="3">Paid Time Off Policy </th>
                    </tr>
                </thead>
                <tbody style="border-bottom: 1px solid #ddd;">
                    <tr>
                        <th  style="padding:3px 5px; border-right: 1px solid #ddd; color: #000; width: 50%;">Description</th>
                        <th style="padding:3px 5px;  text-align: right; color: #000; width: 50%;">Hours </th>
                    </tr>
                    <?php
                    /*
                    @php
                        if (count($data->additionalPaids) > 0){
                            foreach($data->additionalPaids as $key => $val) {
                    @endphp
                                <tr>    
                                    <td style="padding:3px 5px; border-right: 1px solid #ddd; width: 50%;">{{ $val->leaveTypes->name}}</td>
                                    <td style="padding:3px 5px; text-align: right;  width: 50%;">{{$val->amount}}</td>
                                </tr>
                    @php                                         
                            }
                        }
                    @endphp
                    */

                    ?>

                    <tr>    
                        <td style="padding:3px 5px; border-right: 1px solid #ddd; width: 50%;">Hours used this period</td>
                        <td style="padding:3px 5px; text-align: right;  width: 50%;">{{$paidTimeOff}}</td>
                    </tr>
                    <tr>    
                        <td style="padding:3px 5px; border-right: 1px solid #ddd; width: 50%;">Remaining Paid Time off Balance</td>
                        <td style="padding:3px 5px; text-align: right;  width: 50%;">{{$paidTimeOffBalane}}</td>
                    </tr>
                </tbody>
            </table>
        </td>
        <td style="padding-left: 10px;">
            <table style="width: 100%; border-collapse:collapse; table-layout: auto;">
                <thead style="border-bottom: 2px solid #58a8a4;">
                    <tr>
                        <th style="padding:3px 5px; color: #000; font-size: 14px;" colspan="3">Unpaid Time off Policy  </th>
                    </tr>
                </thead>
                <tbody style="border-bottom: 1px solid #ddd;">
                    <tr>
                        <th style="padding:3px 5px; border-right: 1px solid #ddd; color: #000; width: 50%;">Description</th>
                        <th style="padding:3px 5px;  text-align: right; color: #000;  width: 50%;">Hours</th>
                    </tr>
                    <tr>    
                        <td style="padding:3px 5px; border-right: 1px solid #ddd; width: 50%;">Hours used this period</td>
                        <td style="padding:3px 5px; text-align: right;  width: 50%;">{{$unpaidTimeOff}}</td>
                    </tr>
                    <tr>    
                        <td style="padding:3px 5px; border-right: 1px solid #ddd; width: 50%;">Remaining Paid Time off Balance</td>
                        <td style="padding:3px 5px; text-align: right;  width: 50%;">{{$unpaidTimeOffBalane}}</td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tbody>
</table>
</body>
</html>