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
<table style="width: 100%; border-collapse: collapse; table-layout: fixed; margin-bottom: 10px;">

    <tr>
        <td style="padding:3px 5px; margin-bottom: 25px;">
            <strong style="font-size: 13px; color: #000;">{{ strtoupper(auth()->user()->name) }} </strong>
        </td>
    </tr>
    <tr>
        <td style="padding:3px 5px;">&nbsp;</td>
    </tr>
    <tr>
        <td style="padding:3px 5px;">
            <span style="font-size: 12px; color: #000;">{{ $data->user->name }}</span>
        </td>
    </tr><br>
    <tr>
        <td>
            <table style="width: 100%; border-collapse:collapse; table-layout: fixed;">
                <tr>
                    <td style="padding:3px 5px;">
                        <strong style="font-size: 10px; color: #000;">ADDRESS: {{ $data->user->employeeProfile->address }}</strong>
                    </td>
                    <td style="padding:3px 5px;">
                        <strong style="font-size: 10px; color: #000;">SOCIAL SECURITY NUMBER: {{ $data->user->employeeProfile->pan_number ?? 'N/A' }}</strong>
                    </td>
                </tr>                
                <tr>
                    <td style="padding:3px 5px;">
                        <strong style="font-size: 10px; color: #000;">Pay Period: {{ date('F dS Y', strtotime($start_date))}} - {{ date('F dS Y', strtotime($end_date))}}</strong>
                    </td>
                    <td style="padding:3px 5px;">
                        <strong style="font-size: 10px; color: #000;">MEDICAL BENEFITS NUMBER: {{ $data->user->employeeProfile->ifsc_code ?? 'N/A' }}</strong>
                    </td>
                </tr>
                <!-- <tr>
                    <td style="padding:3px 5px;">{{ auth()->user()->name }}</td>
                    <td style="padding:3px 5px;">{{ $data->user->name }}</td>
                </tr>
                <tr>
                    <td style="padding:3px 5px;">{{ auth()->user()->phone_number }}</td>
                    <td style="padding:3px 5px;">Employee cell: {{ $data->user->employeeProfile->phone_number }}</td>
                </tr> -->
            </table>
        </td>
    </tr>
</table>

<?php
    $grossYTD = 0;
    $regHrsYTD = 0;
    $employeePayYTD = 0;
    $deductionsYTD = 0;
    $earningsYTD = 0;
    $totalTaxesYTD = 0;
    $paidTimeOffYTD = 0;
    $paidTimeOffBalaneYTD = 0;
    $unpaidTimeOffYTD = 0;
    $unpaidTimeOffBalaneYTD = 0;
    $reimbursementYTD = 0;//$data->reimbursement;
    $medicalYTD = 0;
    $securityYTD = 0;
    $edu_levyYTD = 0;
    $employerMedicalBenefitsYTD = 0;
    $employerssYTD = 0;
    $netPayYTD = 0;

    foreach($allApprovedData as $allApprovedDataRow) {
        if (count($allApprovedDataRow->additionalEarnings) > 0){
            foreach($allApprovedDataRow->additionalEarnings as $key => $val) {
                if($val->payhead->pay_type =='earnings') {
                    $earningsYTD += $val->amount;
                }

                if($val->payhead->pay_type =='deductions') {
                    $deductionsYTD += $val->amount;
                }

                if($val->payhead->pay_type =='nothing') {
                    $reimbursementYTD += $val->amount;
                }
            }
        }

        $regHrsYTD += $allApprovedDataRow->user->employeeProfile->pay_rate * $allApprovedDataRow->total_hours;

        $grossYTD += ($regHrsYTD + $allApprovedDataRow->overtime_hrs + $allApprovedDataRow->doubl_overtime_hrs + $allApprovedDataRow->holiday_pay + $earningsYTD + $allApprovedDataRow->paid_time_off);

        $employeePayYTD += $grossYTD - ($allApprovedDataRow->medical +$allApprovedDataRow->security + $allApprovedDataRow->edu_levy) + $earningsYTD - $deductionsYTD;

        // $totalEmployeePay += $employeePayYTD;
        $totalTaxesYTD += ($allApprovedDataRow->medical +$allApprovedDataRow->security + $allApprovedDataRow->edu_levy);

        $employerMedicalBenefitsYTD += $allApprovedDataRow->medical+$allApprovedDataRow->security+$allApprovedDataRow->edu_levy+$allApprovedDataRow->security_employer;
        $employerssYTD += $allApprovedDataRow->security_employer;

        $netPayYTD += $data->net_pay;

        $medicalYTD += $allApprovedDataRow->medical;
        $securityYTD += $allApprovedDataRow->security;
        $edu_levyYTD += $allApprovedDataRow->edu_levy;
    }
    // $totalDeductions += $deductions;
    // $totalAdditions += $earnings;
?>

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
    $reimbursement = 0;//$data->reimbursement;

    if (count($data->additionalEarnings) > 0){
        foreach($data->additionalEarnings as $key => $val) {
            if($val->payhead->pay_type =='earnings') {
                $earnings += $val->amount;
            }

            if($val->payhead->pay_type =='deductions') {
                $deductions += $val->amount;
            }

            if($val->payhead->pay_type =='nothing') {
                $reimbursement += $val->amount;
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
    <thead style="border-bottom: 2px solid #58a8a4;">
        <tr>
            <th style="padding:3px 5px;  color: #000; font-size: 14px;" colspan="2" >Gross Earnings</th>
        </tr>
    </thead>
    <tr>
       <thead style="border-top: 2px solid #58a8a4;">
            <th style="padding:3px 5px; border-right: 1px solid #ddd; color: #000;">
                Description
            </th>
            <th style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;  color: #000;">Rate</th>
            <th style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;  color: #000;">Hours/Period</th>
            <th style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;  color: #000;">Amount</th>
            <!-- <th style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;  color: #000;">Year To Date </th> -->
       </thead>
    </tr>
    <tbody style="border-bottom: 1px solid #ddd;">
        <tr>
            <td style="padding:3px 5px; border-right: 1px solid #ddd;">Pay Type | {{ strtoupper($data->user->employeeProfile->pay_type) }}</td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($data->user->employeeProfile->pay_rate, 2)}}</td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">{{$data->total_hours}}</td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($regHrs, 2)}}</td>
            <!-- <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($regHrs+$regHrsYTD, 2)}}</td> -->
        </tr>
        @if($data->paid_time_off > 0)
        <tr>
            <td style="padding:3px 5px; border-right: 1px solid #ddd;">Paid Time Off</td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;"></td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;"></td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($data->paid_time_off, 2)}}</td>
            <!-- <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($gross+$grossYTD, 2)}}</td> -->
        </tr>
        @endif
        <tr>
            <td style="padding:3px 5px; border-right: 1px solid #ddd;">Gross Earnings</td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;"></td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;"></td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($gross, 2)}}</td>
            <!-- <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($gross+$grossYTD, 2)}}</td> -->
        </tr>
    </tbody>
</table>
<table style="width: 100%; border-collapse: collapse; table-layout: fixed; text-align: left; vertical-align: middle; margin-bottom: 25px;">
    <tbody>
        <td >
            <table style="width: 100%; border-collapse:collapse; table-layout: fixed;">
                <thead style="border-bottom: 2px solid #58a8a4;">
                    <tr>
                        <th style="padding:3px 5px;  color: #000; font-size: 14px;" colspan="2" >Statutory Deductions</th>
                    </tr>
                </thead>
                <thead style="border-bottom: 1px solid #ddd;">
                    <tr>
                        <th style="padding:3px 5px; border-right: 1px solid #ddd; color: #000;">Description </th>
                        <th style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right; color: #000;">Amount</th>
                        <!-- <th style="text-align: right; color: #000;">Year To Date </th> -->
                    </tr>
                    @if($data->medical > 0)
                    <tr>
                        <td style="padding:3px 5px; border-right: 1px solid #ddd; ">Medical benefits </td>
                        <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($data->medical, 2)}}</td>
                        <!-- <td style="padding:3px 5px; text-align: right;">${{number_format($data->medical +$medicalYTD, 2)}}</td> -->
                    </tr>
                    @endif

                    @if($data->security > 0)
                    <tr>
                        <td style="padding:3px 5px; border-right: 1px solid #ddd;">Social Social Security</td>
                        <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($data->security, 2)}}</td>
                        <!-- <td style="padding:3px 5px; text-align: right;">${{number_format($data->security + $securityYTD, 2)}}</td> -->
                    </tr>
                    @endif

                    @if($data->edu_levy > 0)
                    <tr>
                        <td style="padding:3px 5px; border-right: 1px solid #ddd;">Education Levy </td>
                        <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($data->edu_levy, 2)}}</td>
                        <!-- <td style="padding:3px 5px; text-align: right;">${{number_format($data->edu_levy + $edu_levyYTD, 2)}}</td> -->
                    </tr>
                    @endif
                </thead>
            </table>
        </td>
    </tbody>
</table>
<table style="width: 100%; border-collapse: collapse; table-layout: fixed; text-align: left; vertical-align: middle; margin-bottom: 25px;">
    <tbody>
        <td>
            <table style="width: 100%; border-collapse:collapse; table-layout: fixed;">
                <thead style="border-bottom: 2px solid #58a8a4;">
                    <tr>
                        <th style="padding:3px 5px; color: #000; font-size: 14px;" colspan="2">Employee Additions/Deductions</th>
                    </tr>
                </thead>
                <thead style="border-bottom: 1px solid #ddd;">
                    <tr>
                        <!-- <th style="padding:3px 5px; border-right: 1px solid #ddd; color: #000;">Description</th> -->
                        <th style="padding:3px 5px; border-right: 1px solid #ddd; color: #000;">Description</th>
                        <th style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right; color: #000;">Amount</th>
                        <!-- <th style="padding:3px 5px; text-align: right; color: #000;">Year To Date </th> -->
                    </tr>

                    @php
                    $totalAe = 0;
                    if (count($data->additionalEarnings) > 0) {
                        foreach($data->additionalEarnings as $key => $val) {
                            if($val->payhead->pay_type !='earnings') {
                                $totalAe += $val->amount;
                    @endphp

                            @if($val->amount > 0)
                            <tr>
                                <!-- <td style="padding:3px 5px; border-right: 1px solid #ddd;">Deduction from Net Pay</td> -->
                                <td style="padding:3px 5px; border-right: 1px solid #ddd;">{{ $val->payhead->name}}</td>
                                <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($val->amount, 2)}}</td>
                                <!-- <td style="padding:3px 5px; text-align: right;">${{number_format($val->amount+$deductionsYTD+$reimbursementYTD, 2)}}</td> -->
                            </tr>
                            @endif
                    @php
                            }
                        }
                    }
                    @endphp
                    <?php
                    /*
                    <tr>
                    
                        <td style="padding:3px 5px; border-right: 1px solid #ddd; color: #000;">Total</td>
                        <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right; color: #000;">${{$totalAe}}</td>
                    </tr>
                    */
                    ?>
                </thead>
            </table>
        </td>
        <!-- <td style="padding-left: 10px;">
            <table style="width: 100%; border-collapse:collapse; table-layout: fixed;">
                <thead style="border-bottom: 2px solid #58a8a4;">
                    <tr>
                        <th style="padding:3px 5px; color: #000; font-size: 14px;" colspan="3">Employer Taxes</th>
                    </tr>
                </thead>
                <tbody style="border-bottom: 1px solid #ddd;">
                    <tr>
                        <th style="padding:3px 5px; border-right: 1px solid #ddd; color: #000;">Company Tax </th>
                        <th style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right; color: #000;">Amount</th>
                        <th style="padding:3px 5px; text-align: right; color: #000;">Year To Date </th>
                    </tr>
                    <tr>
                        <td style="padding:3px 5px; border-right: 1px solid #ddd;">Medical benefits </td>
                        <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($data->medical+$data->security+$data->edu_levy+$data->security_employer, 2)}}</td>
                        <td style="padding:3px 5px; text-align: right;">${{number_format($data->medical+$data->security+$data->edu_levy+$data->security_employer+$employerMedicalBenefitsYTD, 2)}}</td>
                    </tr>
                    <tr>
                        <td style="padding:3px 5px; border-right: 1px solid #ddd;">Social Social Security</td>
                        <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($data->security_employer, 2)}}</td>
                        <td style="padding:3px 5px; text-align: right;">${{number_format($data->security_employer+$employerssYTD, 2)}}</td>
                    </tr>
                    <tr>
                        <td style="padding:3px 5px; border-right: 1px solid #ddd;">Education Levy </td>
                        <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">N/A </td>
                        <td style="padding:3px 5px; text-align: right;">N/A </td>
                    </tr>
                </tbody>
            </table>
        </td> -->
    </tbody>
</table>
<?php /*<table style="width: 100%; border-collapse:collapse; table-layout: fixed; text-align: left; margin-bottom: 25px;">
    <thead style="border-bottom: 2px solid #58a8a4;">
        <tr>
            <th style="padding:3px 5px; color: #000; font-size: 14px;" colspan="3">Employee Pre-Tax Additions </th>
        </tr>
    </thead>
    <thead style="border-bottom: 1px solid #ddd;">
        <tr>
            <th style="padding:3px 5px; border-right: 1px solid #ddd; color: #000;">Description</th>
            <th style="padding:3px 5px; border-right: 1px solid #ddd; color: #000;">Type</th>
            <th style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right; color: #000;">Amount</th>
            <!-- <th style="padding:3px 5px; text-align: right; color: #000;">Year To Date </th> -->
        </tr>

        @php
        if (count($data->additionalEarnings) > 0) {
            foreach($data->additionalEarnings as $key => $val) {
                if($val->payhead->pay_type =='earnings') {
        @endphp
                <tr>
                    <td style="padding:3px 5px; border-right: 1px solid #ddd;">Addition to Gross Pay</td> <?php //$val->payhead->pay_type ?>
                    <td style="padding:3px 5px; border-right: 1px solid #ddd;">{{ $val->payhead->name}}</td>
                    <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($val->amount, 2)}}</td>
                    <!-- <td style="padding:3px 5px; text-align: right;">${{number_format($val->amount + $earningsYTD, 2)}}</td> -->
                </tr>
        @php
                }
            }
        }
        @endphp
    </thead>
</table>*/ ?>
<table style="width: 100%; border-collapse:collapse; table-layout: fixed; text-align: left; margin-bottom: 25px;">
    <thead style="border-bottom: 2px solid #58a8a4;">
        <tr>
            <th style="padding:3px 5px; color: #000; font-size: 14px;" colspan="2">Summary </th>
        </tr>
    </thead>
    <thead style="border-bottom: 1px solid #ddd;">
        <tr>
            <th style="padding:3px 5px; border-right: 1px solid #ddd; color: #000;">Description</th>
            <th style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right; color: #000;">Amount</th>
            <!-- <th style="padding:3px 5px;  border-right: 1px solid #ddd; text-align: right; color: #000;">Year To Date</th> -->
        </tr>
        <tr>
            <td style="padding:3px 5px; border-right: 1px solid #ddd;">Gross Earnings</td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($gross, 2)}}</td>
            <!-- <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($gross + $grossYTD, 2)}}</td> -->
        </tr>
        <!-- tr>
            <td style="padding:3px 5px; border-right: 1px solid #ddd;">Pre-Tax Additions/Contributions</td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($earnings, 2)}}</td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($earnings + $earningsYTD, 2)}}</td>
        </tr> -->
        @if($totalTaxes > 0)
        <tr>
            <td style="padding:3px 5px; border-right: 1px solid #ddd;">Taxes</td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($totalTaxes, 2)}}</td>
            <!-- <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($totalTaxes + $totalTaxesYTD, 2)}}</td> -->
        </tr>
        @endif

        @if($deductions > 0)
        <tr>
            <td style="padding:3px 5px; border-right: 1px solid #ddd;">Employee additions/deductions </td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($deductions, 2)}}</td>
            <!-- <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($deductions+$deductionsYTD, 2)}}</td> -->
        </tr>
        @endif
        <tr>
            <td style="padding:3px 5px; border-right: 1px solid #ddd;">Net Pay </td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($data->net_pay, 2)}}</td>
            <!-- <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($data->net_pay +$netPayYTD, 2)}}</td> -->
        </tr>
        <!-- <tr>
            <td style="padding:3px 5px; border-right: 1px solid #ddd;">Reimbursements</td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($reimbursement, 2)}}</td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;">${{number_format($reimbursement+$reimbursementYTD, 2)}}</td>
        </tr> -->
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
                        <th style="padding:3px 5px; color: #000; font-size: 14px;" colspan="3">Direct Deposit</th>
                    </tr>
                </thead>
                <tbody style="border-bottom: 1px solid #ddd;">
                    <tr>
                        <th  style="padding:3px 5px; border-right: 1px solid #ddd; color: #000; width: 50%;">Description</th>
                        <th style="padding:3px 5px;  text-align: right; color: #000; width: 50%;">Title</th>
                    </tr>
                    <tr>    
                        <td style="padding:3px 5px; border-right: 1px solid #ddd; width: 50%;">Bank Name</td>
                        <td style="padding:3px 5px; text-align: right;  width: 50%;">
                            @if(!empty($data->user->paymentProfile->bank_name))
                                {{$data->user->paymentProfile->bank_name}}
                            @endif
                        </td>
                    </tr>
                    <tr>    
                        <td style="padding:3px 5px; border-right: 1px solid #ddd; width: 50%;">Routing Number </td>
                        <td style="padding:3px 5px; text-align: right;  width: 50%;">
                            @if(!empty($data->user->paymentProfile->routing_number))
                                {{$data->user->paymentProfile->routing_number}}
                            @endif
                        </td>
                    </tr>
                    <tr>    
                        <td style="padding:3px 5px; border-right: 1px solid #ddd; width: 50%;">Account Number</td>
                        <td style="padding:3px 5px; text-align: right;  width: 50%;">
                            @if(!empty($data->user->paymentProfile->account_number))
                                {{$data->user->paymentProfile->account_number}}
                            @endif
                        </td>
                    </tr>
                    <tr>    
                        <td style="padding:3px 5px; border-right: 1px solid #ddd; width: 50%;">Account Type</td>
                        <td style="padding:3px 5px; text-align: right;  width: 50%;">
                            @if(!empty($data->user->paymentProfile->account_type) && $data->user->paymentProfile->account_type == "checking")
                                Chequing
                            @endif

                            @if(!empty($data->user->paymentProfile->payment_method) && $data->user->paymentProfile->payment_method == "saving")
                                Saving
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tbody>
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

                    @if($paidTimeOff > 0)
                    <tr>    
                        <td style="padding:3px 5px; border-right: 1px solid #ddd; width: 50%;">Hours used this period</td>
                        <td style="padding:3px 5px; text-align: right;  width: 50%;">{{$paidTimeOff}}</td>
                    </tr>
                    @endif

                    @if($paidTimeOffBalane > 0)
                    <tr>    
                        <td style="padding:3px 5px; border-right: 1px solid #ddd; width: 50%;">Remaining Paid Time off Balance</td>
                        <td style="padding:3px 5px; text-align: right;  width: 50%;">{{$paidTimeOffBalane}}</td>
                    </tr>
                    @endif
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
                    @if($unpaidTimeOff > 0)
                    <tr>    
                        <td style="padding:3px 5px; border-right: 1px solid #ddd; width: 50%;">Hours used this period</td>
                        <td style="padding:3px 5px; text-align: right;  width: 50%;">{{$unpaidTimeOff}}</td>
                    </tr>
                    @endif

                    @if($unpaidTimeOffBalane > 0)
                    <tr>    
                        <td style="padding:3px 5px; border-right: 1px solid #ddd; width: 50%;">Remaining Paid Time off Balance</td>
                        <td style="padding:3px 5px; text-align: right;  width: 50%;">{{$unpaidTimeOffBalane}}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </td>
    </tbody>
</table>
</body>
</html>