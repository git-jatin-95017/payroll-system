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
            <span style="font-size: 13px; color: #000;">{{ $data->user->name }}</span>
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

    $medical_less_60_amt = $data->medical_less_60 ?? $settings->medical_less_60;
    $medical_gre_60_amt = $data->medical_gre_60 ?? $settings->medical_gre_60;
    $social_security_amt = $data->social_security ?? $settings->social_security;
    $social_security_employer_amt = $data->social_security_employer ?? $settings->social_security_employer;
    $education_levy_amt = $data->education_levy ?? $settings->education_levy;
    $education_levy_amt_5 = $data->education_levy_amt_5 > 0 ? $data->education_levy_amt_5 : $settings->education_levy_amt_5;

    $totalEmployeePay =0; 
    $totalTaxes =0; 
    $totalDeductions =0; 
    $grossFinal =0;
    $nothingAdditionTonetPayTotal = 0 ; 

    $medical_benefits = $social_security = $education_lvey = $social_security_employer = 0;

    $gross =0;
    $employeePay =0;
    $deductions = 0;
    $earnings = 0;
    // $paidTimeOff = 0;
    // $reimbursement = $data->reimbursement;
    $nothingAdditionTonetPay = 0;

    if (count($data->additionalEarnings) > 0) {
        foreach($data->additionalEarnings as $key => $val) {
            if($val->payhead->pay_type =='earnings') {
                $earnings += $val->amount;
            }

            if($val->payhead->pay_type =='deductions') {
                $deductions += $val->amount;
            }

            if($val->payhead->pay_type =='nothing') {
                $nothingAdditionTonetPay += $val->amount;
            }
        }
    }

    // if (count($data->additionalPaids) > 0){
    //     foreach($data->additionalPaids as $key => $val) {
    //         $paidTimeOff += $val->amount;                                            
    //     }
    // }

    $regHrs = $data->user->employeeProfile->pay_rate * $data->total_hours;

    $gross = $data->gross + $data->paid_time_off;

    $pay_type = $data->user->employeeProfile->pay_type;
    $diff = date_diff(date_create($data->user->employeeProfile->dob), date_create(date("Y-m-d")));
    $dob = $diff->format('%y');
    $days = $data->total_hours;
   
    if ($pay_type == 'hourly' || $pay_type == 'weekly') {
        if ($dob <= 60) {
            $medical_benefits = ($gross * $medical_less_60_amt) / 100;
        } else if ($dob > 60 && $dob <=79 ) {
            $medical_benefits = ($gross * $medical_gre_60_amt) / 100;
        } else if ($dob > 70 ) {
            $medical_benefits = 0;
        }

        $social_security = ( $gross>1500 ? ((1500*$social_security_amt) / 100) : ($gross*$social_security_amt) / 100 );  
        $social_security_employer = ( $gross>1500 ? ((1500*$social_security_employer_amt) / 100) : ($gross*$social_security_employer_amt) / 100 );  
        $education_lvey = ($gross<=125?0:($gross>1154?( ((1154-125)*$education_levy_amt) / 100)+( (($gross-1154)*$education_levy_amt_5) / 100 ):( (($gross-125)*$education_levy_amt) /100)));
        $mbse_deductions = $medical_benefits + $social_security + $education_lvey;
        $net_pay = $gross - $mbse_deductions;
    } else if ($pay_type == 'bi-weekly') {
        //$medical_benefits = ($gross * 3.5) / 100;
        if ($dob <= 60) {
            $medical_benefits = ($gross * $medical_less_60_amt) / 100;
        } else if ($dob > 60 && $dob <=79 ) {
            $medical_benefits = ($gross * $medical_gre_60_amt) / 100;
        } else if ($dob > 70 ) {
            $medical_benefits = 0;
        }

        if ($days <= 7) {
            $social_security = ( $gross>3000 ? ((3000*$social_security_amt) / 100) : ($gross*$social_security_amt) / 100 ); 
            $social_security_employer = ( $gross>3000 ? ((3000*$social_security_employer_amt) / 100) : ($gross*$social_security_employer_amt) / 100 ); 
        } else {
            $social_security = ( $gross>3000 ? ((3000*$social_security_amt) / 100) : ($gross*$social_security_amt) / 100 ); 
            $social_security_employer = ( $gross>3000 ? ((3000*$social_security_employer_amt) / 100) : ($gross*$social_security_employer_amt) / 100 ); 
        }
        $education_lvey = ($gross<=250?0:($gross>2308?(((2308-250)*$education_levy_amt)/100)+((($gross-2308)*$education_levy_amt_5)/100):((($gross-250)*$education_levy_amt)/100)));
        $mbse_deductions = $medical_benefits + $social_security + $education_lvey;
        $net_pay = $gross - $mbse_deductions;
        if ($days <= 7) {
        } else {
            $net_pay = 2 * $net_pay;                
        }
    } else if ($pay_type == 'semi-monthly') {
        if ($dob <= 60) {
            $medical_benefits = ($gross * $medical_less_60_amt) / 100;
        } else if ($dob > 60 && $dob <=79 ) {
            $medical_benefits = ($gross * $medical_gre_60_amt) / 100;
        } else if ($dob > 70 ) {
            $medical_benefits = 0;
        }
        $social_security = ( $gross>3000 ? ((3000*$social_security_amt) / 100) : ($gross*$social_security_amt) / 100 ); 
        $social_security_employer = ( $gross>3000 ? ((3000*$social_security_employer_amt) / 100) : ($gross*$social_security_employer_amt) / 100 ); 
        $education_lvey = ($gross<=125?0:($gross>2500?(((2500-270.84)*$education_levy_amt)/100)+((($gross-2500)*$education_levy_amt_5)/100):((($gross-270.84)*$education_levy_amt)/100)));
        $mbse_deductions = $medical_benefits + $social_security + $education_lvey;
        $net_pay = $gross - $mbse_deductions;
    } else if ($pay_type == 'monthly') {
        if ($dob <= 60) {
            $medical_benefits = ($gross * $medical_less_60_amt) / 100;
        } else if ($dob > 60 && $dob <=79 ) {
            $medical_benefits = ($gross * $medical_gre_60_amt) / 100;
        } else if ($dob > 70 ) {
            $medical_benefits = 0;
        }
        $social_security = ( $gross>6500 ? ((6500*$social_security_amt) / 100) : ($gross*$social_security_amt) / 100 ); 
        $social_security_employer = ( $gross>6500 ? ((6500*$social_security_employer_amt) / 100) : ($gross*$social_security_employer_amt) / 100 ); 
        $education_lvey = ($gross<=125?0:($gross>5000?(((5000-541.67)*$education_levy_amt)/100)+((($gross-5000)*$education_levy_amt_5)/100):((($gross-541.67)*$education_levy_amt)/100)));
        $mbse_deductions = $medical_benefits + $social_security + $education_lvey;
        $net_pay = $gross - $mbse_deductions;
    }
    // $gross += ($regHrs + $data->overtime_hrs + $data->doubl_overtime_hrs + $data->holiday_pay + ($earnings + $nothingAdditionTonetPay) + $data->paid_time_off); commented

    $grossFinal += $gross;
    // $grossFinal += ($regHrs + $data->overtime_hrs + $data->doubl_overtime_hrs + $data->holiday_pay + ($earnings + $nothingAdditionTonetPay) + $data->paid_time_off);

    $employeePay = $gross- ($mbse_deductions) + ($nothingAdditionTonetPay) - $deductions;

    $totalEmployeePay += $employeePay;
    $totalTaxes += ($medical_benefits + $social_security + $education_lvey);
    $totalDeductions += $deductions;
    // $totalAdditions += $earnings;
    $nothingAdditionTonetPayTotal += $nothingAdditionTonetPay;
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
            <td style="padding:3px 5px; border-right: 1px solid #ddd;color: #000;">Regular Hours | {{ strtoupper($data->user->employeeProfile->pay_type) }}</td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;color: #000;">${{number_format($data->user->employeeProfile->pay_rate, 2)}}</td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;color: #000;">{{$data->total_hours}}</td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;color: #000;">${{number_format($regHrs, 2)}}</td>
        </tr>
        <tr>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: left;color: #000;">OT</td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;color: #000;"></td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;color: #000;"></td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;color: #000;">${{number_format($data->overtime_hrs, 2)}}</td>
        </tr>
        <tr>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: left;color: #000;">DT</td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;color: #000;"></td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;color: #000;"></td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;color: #000;">${{number_format($data->doubl_overtime_hrs, 2)}}</td>
        </tr>
        <tr>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: left;color: #000;">Holiday Pay</td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;color: #000;"></td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;color: #000;"></td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;color: #000;">${{number_format($data->holiday_pay, 2)}}</td>
        </tr>
        @php
            if (count($data->additionalPaids) > 0) {
                foreach($data->additionalPaids as $key => $val) {
                    

                    if ($data->user->employeeProfile->pay_type == 'hourly') {
                        $ptoff = $data->user->employeeProfile->pay_rate *  $val->amount;
                    }  else if ($data->user->employeeProfile->pay_type == 'weekly') {
                        $ptoff = $data->user->employeeProfile->pay_rate *  $val->amount;
                    }  else if ($data->user->employeeProfile->pay_type == 'bi-weekly') {
                        $ptoff = ((($data->user->employeeProfile->pay_rate * 26)/52)/40)*$val->amount;            
                    }  else if ($data->user->employeeProfile->pay_type == 'semi-monthly') {
                        $ptoff = ((($data->user->employeeProfile->pay_rate * 24)/52)/40)*$val->amount;
                    }  else if ($data->user->employeeProfile->pay_type == 'monthly') {
                        $ptoff = ((($data->user->employeeProfile->pay_rate * 12)/52)/40)*$val->amount;
                    } else {
                        $ptoff = 0;
                    }
        @endphp
        @if($val->amount > 0)
            <tr>    
                <td style="padding:3px 5px; border-right: 1px solid #ddd; width: 50%;color: #000;">{{ $val->leaveType->name ?? 'N/A'}}</td>
                <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;color: #000;">${{number_format($val->amount > 0 ? $ptoff/$val->amount : 0, 2)}}</td>
                <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;color: #000;">{{$val->amount}}</td>
                <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;  width: 50%;color: #000;">${{number_format(($val->amount > 0 ? $ptoff/$val->amount : 0) * $val->amount, 2)}}</td>
            </tr>
        @endif
        @php                                                     
                }
            }
        @endphp

        @php
            if (count($data->additionalEarnings) > 0) {
                foreach($data->additionalEarnings as $key => $val) {
                    if($val->payhead->pay_type =='earnings') {
            @endphp

                    @if($val->amount > 0)
                    <tr>
                        <!-- <td style="padding:3px 5px; border-right: 1px solid #ddd;">Deduction from Net Pay</td> -->
                        <td style="padding:3px 5px; border-right: 1px solid #ddd;color: #000;">{{ ucfirst($val->payhead->name)}}</td>
                        <td style="padding:3px 5px; border-right: 1px solid #ddd;color: #000;"></td>
                        <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;"></td>
                        <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;color: #000;">
                            ${{number_format($val->amount, 2)}}
                        </td>
                    </tr>
                    @endif
            @php
                    }
                }
            }
            @endphp
            
        <tr>
            <td style="padding:3px 5px; border-right: 1px solid #ddd;color: #000;">Gross Earnings</td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;"></td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;"></td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;color: #000;">${{number_format($gross, 2)}}</td>
        </tr>
    </tbody>
</table>
<table style="width: 100%; border-collapse: collapse; table-layout: fixed; text-align: left; vertical-align: middle; margin-bottom: 25px;">
    <tbody>
        <td >
            <table style="width: 100%; border-collapse:collapse; table-layout: fixed;">
                <thead style="border-bottom: 2px solid #58a8a4;">
                    <tr>
                        <th style="padding:3px 5px;  color: #000; font-size: 14px;" colspan="2">Statutory Deductions</th>
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
                        <td style="padding:3px 5px; border-right: 1px solid #ddd; color: #000;">Medical Benefits </td>
                        <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;color: #000;">${{number_format($medical_benefits, 2)}}</td>
                    </tr>
                    @endif

                    @if($data->security > 0)
                    <tr>
                        <td style="padding:3px 5px; border-right: 1px solid #ddd;color: #000;">Social Social Security</td>
                        <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;color: #000;">${{number_format($social_security, 2)}}</td>
                    </tr>
                    @endif

                    @if($data->edu_levy > 0)
                    <tr>
                        <td style="padding:3px 5px; border-right: 1px solid #ddd;color: #000;">Education Levy </td>
                        <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;color: #000;">${{number_format($education_lvey, 2)}}</td>
                    </tr>
                    @endif

                    <tr>
                        <!-- <td style="padding:3px 5px; border-right: 1px solid #ddd;">Deduction from Net Pay</td> -->
                        <td style="padding:3px 5px; border-right: 1px solid #ddd;color: #000;"><strong>Total</strong></td>
                        <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;color: #000;">${{number_format($medical_benefits + $social_security + $education_lvey, 2)}}</td>
                    </tr>
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
                        <th style="padding:3px 5px; border-right: 1px solid #ddd; color: #000;">Type</th>
                        <th style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right; color: #000;">Amount</th>
                        <!-- <th style="padding:3px 5px; text-align: right; color: #000;">Year To Date </th> -->
                    </tr>

                    @php
                    $totalAe = 0;
                    if (count($data->additionalEarnings) > 0) {
                        foreach($data->additionalEarnings as $key => $val) {
                            if($val->payhead->pay_type !='earnings') {
                                
                                if($val->payhead->pay_type =='deductions') {
                                    $totalAe -= $val->amount;
                                } else {$totalAe += $val->amount;}
                    @endphp

                            @if($val->amount > 0)
                            <tr>
                                <!-- <td style="padding:3px 5px; border-right: 1px solid #ddd;">Deduction from Net Pay</td> -->
                                <td style="padding:3px 5px; border-right: 1px solid #ddd;color: #000;">{{ $val->payhead->name}}</td>
                                <td style="padding:3px 5px; border-right: 1px solid #ddd;color: #000;">
                                    @if( $val->payhead->pay_type == 'nothing') 
                                        Addition
                                    @else 
                                        {{ ucfirst($val->payhead->pay_type)}}
                                    @endif
                                </td>
                                <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;color: #000;">
                                    @if($val->payhead->pay_type == 'deductions') - @endif${{number_format($val->amount, 2)}}
                                </td>
                            </tr>
                            @endif
                    @php
                            }
                        }
                    }
                    @endphp
                    <tr>
                    
                        <td style="padding:3px 5px; border-right: 1px solid #ddd; color: #000;"><strong>Total</strong></td>
                        <td style="padding:3px 5px; border-right: 1px solid #ddd; color: #000;"></td>
                        <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right; color: #000;">${{number_format($totalAe, 2)}}</td>
                    </tr>
                </thead>
            </table>
        </td>
        
    </tbody>
</table>
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
        </tr>
        @if($grossFinal > 0)
        <tr>
            <td style="padding:3px 5px; border-right: 1px solid #ddd;color: #000;">Gross Earnings</td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;color: #000;">${{number_format($grossFinal, 2)}}</td>
        </tr>
        @endif
        @if($totalTaxes > 0)
        <tr>
            <td style="padding:3px 5px; border-right: 1px solid #ddd;color: #000;">Statutory deductions</td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;color: #000;">${{number_format($totalTaxes, 2)}}</td>
        </tr>
        @endif

        @if($deductions > 0)
        <tr>
            <td style="padding:3px 5px; border-right: 1px solid #ddd;color: #000;">Employee additions/deductions </td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;color: #000;">${{number_format($deductions, 2)}}</td>
        </tr>
        @endif
        <tr>
            <td style="padding:3px 5px; border-right: 1px solid #ddd;color: #000;">Net Pay </td>
            <td style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right;color: #000;">${{number_format($grossFinal-$deductions-$totalTaxes, 2)}}</td>
        </tr>
        <tr><td colspan="2"></td></tr>
        <tr><td colspan="2"></td></tr>
        
    </thead>
</table>
<table style="width: 100%; border-collapse: collapse; table-layout: fixed; text-align: left; vertical-align: middle; margin-bottom: 25px;">
    <tbody>
        <tr>
            <td style="padding:3px 5px;" rowspan="2"><strong style="font-size: 14px; color: #000;">Note: {{ $data->notes }}</strong></td>
        </tr>
    </tbody>
</table>
</body>
</html>