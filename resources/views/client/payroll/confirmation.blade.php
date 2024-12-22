@extends('layouts.app')
@section('content')
@php  
    $TotalTaxes =0; 
@endphp
@php  
    $TotalPayroll = 0;
    $medical_benefits11T = $social_security11T = $education_lvey11T = $social_security11T_employer = [];
@endphp
@foreach($data as $row)
    <?php

        $medical_less_60_amt = $row->medical_less_60 ?? $settings->medical_less_60;
        $medical_gre_60_amt = $row->medical_gre_60 ?? $settings->medical_gre_60;
        $social_security_amt = $row->social_security ?? $settings->social_security;
        $social_security_employer_amt = $row->social_security_employer ?? $settings->social_security_employer;
        $education_levy_amt = $row->education_levy ?? $settings->education_levy;
        $education_levy_amt_5 = $row->education_levy_amt_5 > 0 ? $row->education_levy_amt_5 : $settings->education_levy_amt_5;

        $grossT =0;
        $employeePayT =0;
        $deductionsT = 0;
        $earningsT = 0;
        // $earningsT = 0;
        // $paidTimeOff = 0;
        // $reimbursement = $row->reimbursement;
        $nothingAdditionTonetPayT = 0;

        if (count($row->additionalEarnings) > 0) {
            foreach($row->additionalEarnings as $key => $val) {
                if($val->payhead->pay_type =='earnings') {
                    $earningsT += $val->amount;
                }

                if($val->payhead->pay_type =='deductions') {
                    $deductionsT += $val->amount;
                }

                if($val->payhead->pay_type =='nothing') {
                    $nothingAdditionTonetPayT += $val->amount;
                }
            }
        }

        // if (count($row->additionalPaids) > 0){
        //     foreach($row->additionalPaids as $key => $val) {
        //         $paidTimeOff += $val->amount;                                            
        //     }
        // }

        // $regHrs = $row->user->employeeProfile->pay_rate * $row->total_hours;

        $grossT = $row->gross + $row->paid_time_off;

        $pay_type = $row->user->employeeProfile->pay_type;
        $diff = date_diff(date_create($row->user->employeeProfile->dob), date_create(date("Y-m-d")));
        $dob = $diff->format('%y');
        $days = $row->total_hours;
       
        if ($pay_type == 'hourly' || $pay_type == 'weekly') {
            if ($dob <= 60) {
                $medical_benefitsT = ($grossT * $medical_less_60_amt) / 100;
            } else if ($dob > 60 && $dob <=79 ) {
                $medical_benefitsT = ($grossT * $medical_gre_60_amt) / 100;
            } else if ($dob > 70 ) {
                $medical_benefitsT = 0;
            }

            $social_securityT = ( $grossT>1500 ? ((1500*$social_security_amt) / 100) : ($grossT*$social_security_amt) / 100 );  
            $social_securityT_employer = ( $grossT>1500 ? ((1500*$social_security_employer_amt) / 100) : ($grossT*$social_security_employer_amt) / 100 );  
            $education_lveyT = ($grossT<=125?0: ($grossT>1154?( ((1154-125)*$education_levy_amt) / 100) + ( (($grossT-1154)*$education_levy_amt_5) / 100 ) : ( (($grossT-125)*$education_levy_amt) /100)));
            $mbse_deductions = $medical_benefitsT + $social_securityT + $education_lveyT;
            $net_pay = $grossT - $mbse_deductions;
        } else if ($pay_type == 'bi-weekly') {
            //$medical_benefitsT = ($grossT * 3.5) / 100;
            if ($dob <= 60) {
                $medical_benefitsT = ($grossT * $medical_less_60_amt) / 100;
            } else if ($dob > 60 && $dob <=79 ) {
                $medical_benefitsT = ($grossT * $medical_gre_60_amt) / 100;
            } else if ($dob > 70 ) {
                $medical_benefitsT = 0;
            }

            if ($days <= 7) {
                $social_securityT = ( $grossT>3000 ? ((3000*$social_security_amt) / 100) : ($grossT*$social_security_amt) / 100 ); 
                $social_securityT_employer = ( $grossT>3000 ? ((3000*$social_security_employer_amt) / 100) : ($grossT*$social_security_employer_amt) / 100 ); 
            } else {
                $social_securityT = ( $grossT>3000 ? ((3000*$social_security_amt) / 100) : ($grossT*$social_security_amt) / 100 ); 
                $social_securityT_employer = ( $grossT>3000 ? ((3000*$social_security_employer_amt) / 100) : ($grossT*$social_security_employer_amt) / 100 ); 
            }
            $education_lveyT = ($grossT<=250?0:($grossT>2308?(((2308-250)*$education_levy_amt)/100)+((($grossT-2308)*$education_levy_amt_5)/100):((($grossT-250)*$education_levy_amt)/100)));
            $mbse_deductions = $medical_benefitsT + $social_securityT + $education_lveyT;
            $net_pay = $grossT - $mbse_deductions;
            if ($days <= 7) {
            } else {
                $net_pay = 2 * $net_pay;                
            }
        } else if ($pay_type == 'semi-monthly') {
            if ($dob <= 60) {
                $medical_benefitsT = ($grossT * $medical_less_60_amt) / 100;
            } else if ($dob > 60 && $dob <=79 ) {
                $medical_benefitsT = ($grossT * $medical_gre_60_amt) / 100;
            } else if ($dob > 70 ) {
                $medical_benefitsT = 0;
            }
            $social_securityT = ( $grossT>3000 ? ((3000*$social_security_amt) / 100) : ($grossT*$social_security_amt) / 100 ); 
            $social_securityT_employer = ( $grossT>3000 ? ((3000*$social_security_employer_amt) / 100) : ($grossT*$social_security_employer_amt) / 100 ); 
            $education_lveyT = ($grossT<=125?0:($grossT>2500?(((2500-270.84)*$education_levy_amt)/100)+((($grossT-2500)*$education_levy_amt_5)/100):((($grossT-270.84)*$education_levy_amt)/100)));
            $mbse_deductions = $medical_benefitsT + $social_securityT + $education_lveyT;
            $net_pay = $grossT - $mbse_deductions;
        } else if ($pay_type == 'monthly') {
            if ($dob <= 60) {
                $medical_benefitsT = ($grossT * $medical_less_60_amt) / 100;
            } else if ($dob > 60 && $dob <=79 ) {
                $medical_benefitsT = ($grossT * $medical_gre_60_amt) / 100;
            } else if ($dob > 70 ) {
                $medical_benefitsT = 0;
            }
            $social_securityT = ( $grossT>6500 ? ((6500*$social_security_amt) / 100) : ($grossT*$social_security_amt) / 100 ); 
            $social_securityT_employer = ( $grossT>6500 ? ((6500*$social_security_employer_amt) / 100) : ($grossT*$social_security_employer_amt) / 100 ); 
            $education_lveyT = ($grossT<=125?0:($grossT>5000?(((5000-541.67)*$education_levy_amt)/100)+((($grossT-5000)*$education_levy_amt_5)/100):((($grossT-541.67)*$education_levy_amt)/100)));
            $mbse_deductions = $medical_benefitsT + $social_securityT + $education_lveyT;
            $net_pay = $grossT - $mbse_deductions;
        }
        // $grossT += ($regHrs + $row->overtime_hrs + $row->doubl_overtime_hrs + $row->holiday_pay + ($earningsT + $nothingAdditionTonetPayT) + $row->paid_time_off); commented

        // $grossTFinal += $grossT;
        // $grossTFinal += ($regHrs + $row->overtime_hrs + $row->doubl_overtime_hrs + $row->holiday_pay + ($earningsT + $nothingAdditionTonetPayT) + $row->paid_time_off);

        $employeePayT = $grossT- ($mbse_deductions) + ($nothingAdditionTonetPayT) - $deductionsT;

        // $totalEmployeePay += $employeePayT;
        // $totalTaxes += ($row->medical +$row->security + $row->edu_levy);
        // $totalDeductions += $deductionsT;
        // $totalAdditions += $earningsT;
        // $nothingAdditionTonetPayTTotal += $nothingAdditionTonetPayT;

        $TotalPayroll += $employeePayT + $mbse_deductions + $row->security_employer;

        array_push($medical_benefits11T, $medical_benefitsT);
        array_push($social_security11T, $social_securityT);
        array_push($education_lvey11T, $education_lveyT);
        array_push($social_security11T_employer, $social_securityT_employer);
    ?> 
@endforeach 
<?php
    $TotalTaxes = array_sum($medical_benefits11T)+array_sum($social_security11T)+array_sum($education_lvey11T)+array_sum($medical_benefits11T)+array_sum($social_security11T_employer);
?>
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">
            <i class="fa fa-braille" style="color:#1976d2"></i>
            Run Payroll
        </h3>
    </div>
    <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="javascript:void(0)">Home</a>
            </li>

            <li class="breadcrumb-item active">Confirm Payroll</li>
			<li class="breadcrumb-item active">/</li>
        </ol>
    </div>
</div>
<section class="content">
	<div class="container-fluid p-4">
        <div class="bg-white p-4">
            <div class="row">
                <div class="col-12">
                    <div class="payroll-heading">
                        <h3 class="text-themecolor">Review Submit</h3>
                    </div>
                </div>
            </div>		
            <div class="row">
                <div class="col">
                    <div id="progress-bar" class="mb-4">
                        <h2 class="off-screen">Donation progress indicator</h2>
                        <ol id="progress-steps">
                            <li class="progress-step" style="width: 25%;">
                                <span class="count highlight-index"></span>
                                <span class="description" style="left: 9px !important;font-weight: bold;">1. Hours and Earnings</span>
                            </li>
                            <li class="progress-step" style="width: 25%;">
                                <span class="count highlight-index"></span>
                                <span class="description" style="left: 11px !important;font-weight: bold;">2. Paid Time Off</span>
                            </li>
                            <li class="progress-step" style="width: 25%;">
                                <span class="count highlight-index"></span>
                                <span class="description" style="left: 11px !important;font-weight: bold;">3. Review and Submit</span>
                            </li>
                        
                            <li class="progress-step" style="width: 25%;">
                            <span class="count @if(!empty(request()->is_green)) highlight-index @endif"></span>
                            <span class="description" style="left: 11px !important;font-weight: bold;"> 4. Submitted</span>
                            </li>
                        </ol>
                    </div>
                    <div class="payroll-heading my-5">
                        <h4 class="mb-1 text-themecolor">Review and submit</h4>
                        <p>
                            Hooray! Your payroll is finished processing.
                        </p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="dabit-data">
                            <span>Total Payroll</span>
                            <p>${{number_format($TotalPayroll, 2)}}</p>
                        </div>
                        <div class="dabit-data">
                            <span>Total Taxes</span>
                            <p>${{number_format($TotalTaxes, 2)}}</p>
                        </div>
                        <div class="dabit-data">
                            <span>Direct deposits</span>
                            <p>{{$directDeposits}}</p>
                        </div>
                        <div class="dabit-data">
                            <span>Cheques</span>
                            <p>{{$cheques}}</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <form action="{{ route('save.confirmation') }}" method="POST">
                            @csrf
                            @foreach($ids as $k => $id)
                                <input type="hidden" name="ids[]" value="{{$id}}">
                            @endforeach
                            <button class="btn btn-primary mr-2" type="submit">Submit Payroll</button>
                            <a href="{{ route('store.Step2', [
                                'start_date' => Request::query('start_date'),
                                'end_date' => Request::query('end_date'),
                                'appoval_number'=> Request::query('appoval_number')
                            ]) }}" class="btn btn-primary mr-2">Go Back</a>
                            <a href="{{ route('download.pdf', [
                                'start_date' => Request::query('start_date'),
                                'end_date' => Request::query('end_date'),
                                'appoval_number'=> Request::query('appoval_number')
                            ]) }}" class="btn btn-primary mr-2">Download PDF</a>
                        </form>                       
                    </div>
                    
                </div>
             
                <div class="mychart pl-4f">
                    <canvas id="myChart" width="600" height="600"></canvas>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div>
                        <div class="accordion custom-accordion" id="accordionExample">
                            <div class="card">
                              <div class="card-head" id="headingOne">
                                <h2 class="mb-0 collapsed" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    What gets taxed?
                                </h2>
                              </div>
                          
                              <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                <div class="card-body">
                                    <table class="table table-sm table-pay-data">
                                        <thead>
                                          <tr>
                                            <th scope="col">Tax description</th>
                                            <th scope="col">By Employees</th>
                                            <th scope="col">By Employer</th>
                                          </tr>
                                        </thead>
                                        @php  
                                            $medical_benefits11 = $social_security11 = $education_lvey11 = $social_security_employer11 = [];
                                        @endphp

                                        @foreach($data as $row)
                                            <?php

                                                $medical_less_60_amt = $row->medical_less_60 ?? $settings->medical_less_60;
                                                $medical_gre_60_amt = $row->medical_gre_60 ?? $settings->medical_gre_60;
                                                $social_security_amt = $row->social_security ?? $settings->social_security;
                                                $social_security_employer_amt = $row->social_security_employer ?? $settings->social_security_employer;
                                                $education_levy_amt = $row->education_levy ?? $settings->education_levy;
                                                $education_levy_amt_5 = $row->education_levy_amt_5 > 0 ? $row->education_levy_amt_5 : $settings->education_levy_amt_5;

                                                $gross1 =0;
                                                $gross1 = $row->gross + $row->paid_time_off;

                                                $pay_type = $row->user->employeeProfile->pay_type;
                                                $diff = date_diff(date_create($row->user->employeeProfile->dob), date_create(date("Y-m-d")));
                                                $dob = $diff->format('%y');
                                                $days = $row->total_hours;
                                               
                                                if ($pay_type == 'hourly' || $pay_type == 'weekly') {
                                                    if ($dob <= 60) {
                                                        $medical_benefits1 = ($gross1 * $medical_less_60_amt) / 100;
                                                    } else if ($dob > 60 && $dob <=79 ) {
                                                        $medical_benefits1 = ($gross1 * $medical_gre_60_amt) / 100;
                                                    } else if ($dob > 70 ) {
                                                        $medical_benefits1 = 0;
                                                    }

                                                    $social_security1 = ( $gross1>1500 ? ((1500*$social_security_amt) / 100) : ($gross1*$social_security_amt) / 100 );  
                                                    $social_security_employer1 = ( $gross1>1500 ? ((1500*$social_security_employer_amt) / 100) : ($gross1*$social_security_employer_amt) / 100 );  
                                                    $education_lvey1 = ($gross1<=125?0:($gross1>1154?( ((1154-125)*$education_levy_amt) / 100)+( (($gross1-1154)*$education_levy_amt_5) / 100 ):( (($gross1-125)*$education_levy_amt) /100)));
                                                    // $mbse_deductions = $medical_benefits1 + $social_security1 + $education_lvey1;
                                                    // $net_pay = $gross1 - $mbse_deductions;
                                                } else if ($pay_type == 'bi-weekly') {
                                                    //$medical_benefits1 = ($gross1 * 3.5) / 100;
                                                    if ($dob <= 60) {
                                                        $medical_benefits1 = ($gross1 * $medical_less_60_amt) / 100;
                                                    } else if ($dob > 60 && $dob <=79 ) {
                                                        $medical_benefits1 = ($gross1 * $medical_gre_60_amt) / 100;
                                                    } else if ($dob > 70 ) {
                                                        $medical_benefits1 = 0;
                                                    }

                                                    if ($days <= 7) {
                                                        $social_security1 = ( $gross1>3000 ? ((3000*$social_security_amt) / 100) : ($gross1*$social_security_amt) / 100 ); 
                                                        $social_security_employer1 = ( $gross1>3000 ? ((3000*$social_security_employer_amt) / 100) : ($gross1*$social_security_employer_amt) / 100 ); 
                                                    } else {
                                                        $social_security1 = ( $gross1>3000 ? ((3000*$social_security_amt) / 100) : ($gross1*$social_security_amt) / 100 ); 
                                                        $social_security_employer1 = ( $gross1>3000 ? ((3000*$social_security_employer_amt) / 100) : ($gross1*$social_security_employer_amt) / 100 ); 
                                                    }
                                                    $education_lvey1 = ($gross1<=250?0:($gross1>2308?(((2308-250)*$education_levy_amt)/100)+((($gross1-2308)*$education_levy_amt_5)/100):((($gross1-250)*$education_levy_amt)/100)));
                                                    // $mbse_deductions = $medical_benefits1 + $social_security1 + $education_lvey1;
                                                    
                                                } else if ($pay_type == 'semi-monthly') {
                                                    if ($dob <= 60) {
                                                        $medical_benefits1 = ($gross1 * $medical_less_60_amt) / 100;
                                                    } else if ($dob > 60 && $dob <=79 ) {
                                                        $medical_benefits1 = ($gross1 * $medical_gre_60_amt) / 100;
                                                    } else if ($dob > 70 ) {
                                                        $medical_benefits1 = 0;
                                                    }
                                                    $social_security1 = ( $gross1>3000 ? ((3000*$social_security_amt) / 100) : ($gross1*$social_security_amt) / 100 ); 
                                                    $social_security_employer1 = ( $gross1>3000 ? ((3000*$social_security_employer_amt) / 100) : ($gross1*$social_security_employer_amt) / 100 ); 
                                                    $education_lvey1 = ($gross1<=125?0:($gross1>2500?(((2500-270.84)*$education_levy_amt)/100)+((($gross1-2500)*$education_levy_amt_5)/100):((($gross1-270.84)*$education_levy_amt)/100)));
                                                    // $mbse_deductions = $medical_benefits1 + $social_security1 + $education_lvey1;
                                                } else if ($pay_type == 'monthly') {
                                                    if ($dob <= 60) {
                                                        $medical_benefits1 = ($gross1 * $medical_less_60_amt) / 100;
                                                    } else if ($dob > 60 && $dob <=79 ) {
                                                        $medical_benefits1 = ($gross1 * $medical_gre_60_amt) / 100;
                                                    } else if ($dob > 70 ) {
                                                        $medical_benefits1 = 0;
                                                    }
                                                    $social_security1 = ( $gross1>6500 ? ((6500*$social_security_amt) / 100) : ($gross1*$social_security_amt) / 100 ); 
                                                    $social_security_employer1 = ( $gross1>6500 ? ((6500*$social_security_employer_amt) / 100) : ($gross1*$social_security_employer_amt) / 100 ); 
                                                    $education_lvey1 = ($gross1<=125?0:($gross1>5000?(((5000-541.67)*$education_levy_amt)/100)+((($gross1-5000)*$education_levy_amt_5)/100):((($gross1-541.67)*$education_levy_amt)/100)));
                                                    // $mbse_deductions = $medical_benefits1 + $social_security1 + $education_lvey1;
                                                }


                                                array_push($medical_benefits11, $medical_benefits1);
                                                array_push($social_security11, $social_security1);
                                                array_push($education_lvey11, $education_lvey1);
                                                array_push($social_security_employer11, $social_security_employer1);
                                                // $medical_benefits1 += $medical_benefits1;
                                                // $social_security1 += $social_security1;
                                                // $education_lvey1 += $education_lvey1;
                                                // $social_security_employer1 += $social_security_employer1;
                                            ?>
                                            @endforeach
                                        <tbody>
                                            <tr>
                                                <th>Medical benefits</th>
                                                <td>${{number_format(array_sum($medical_benefits11), 2)}}</td>
                                                <td>${{number_format(array_sum($medical_benefits11), 2)}}</td>
                                            </tr>
                                            <tr>
                                                <th>Social Security</th>
                                                <td>${{number_format(array_sum($social_security11), 2)}}</td>
                                                <td>${{number_format(array_sum($social_security_employer11), 2)}}</td>
                                            </tr>
                                            <tr>
                                                <th>Education levy</th>
                                                <td>${{number_format(array_sum($education_lvey11), 2)}}</td>
                                                <td>N/A</td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight:bold !important;">Subtotal</td>
                                                <td>
                                                    <span>${{number_format(array_sum($medical_benefits11)+array_sum($social_security11)+array_sum($education_lvey11), 2)}}</span>
                                                </td>
                                                <td>
                                                    <span>${{number_format(array_sum($medical_benefits11)+array_sum($social_security_employer11), 2)}}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight:bold !important;"></td>
                                                <td> </td>
                                                <td>
                                                    <span style="color: #000 !important;font-weight: 700 !important;">${{number_format(array_sum($medical_benefits11)+array_sum($social_security11)+array_sum($education_lvey11)+array_sum($medical_benefits11)+array_sum($social_security_employer11), 2)}}</span><br>
                                                    <small style="color: #000 !important;font-weight: 600 !important;">Total Taxes</small>
                                                    <?php //$securityEmployerTotal ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                      </table>
                                </div>
                              </div>
                            </div>
                            <div class="card">
                              <div class="card-head" id="headingTwo">
                                <h2 class="mb-0 collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    What do employees get?
                                </h2>
                              </div>
                              <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                <div class="card-body">
                                    <table class="table table-sm table-pay-data">
                                        <thead>
                                          <tr>
                                            <th scope="col">Employee</th>
                                            <th scope="col">Gross Pay</th>
                                            <!-- <th scope="col">Regular hours</th> -->
                                            <!-- <th scope="col">OT</th> -->
                                            <!-- <th scope="col">Dbl OT</th> -->
                                            <!-- <th scope="col">Holiday pay</th> -->
                                            <th scope="col">Medical benefits</th>
                                            <th scope="col">Social Security</th>
                                            <th scope="col">Education levy</th>
                                            <th scope="col">Addition to net pay</th>
                                            <th scope="col">Deductions</th>
                                            <!-- <th scope="col">Paid time off</th> -->
                                            <th scope="col">Employee Pay</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                            @php  
                                                $totalEmployeePay =0; 
                                                $totalTaxes =0; 
                                                $totalDeductions =0; 
                                                $grossFinal =0;
                                                $nothingAdditionTonetPayTotal = 0 ; 

                                                $medical_benefits = $social_security = $education_lvey = $social_security_employer = 0;
                                            @endphp
                                            @foreach($data as $row)
                                                <?php
                                                    $medical_less_60_amt = $row->medical_less_60 ?? $settings->medical_less_60;
                                                    $medical_gre_60_amt = $row->medical_gre_60 ?? $settings->medical_gre_60;
                                                    $social_security_amt = $row->social_security ?? $settings->social_security;
                                                    $social_security_employer_amt = $row->social_security_employer ?? $settings->social_security_employer;
                                                    $education_levy_amt = $row->education_levy ?? $settings->education_levy;
                                                    $education_levy_amt_5 = $row->education_levy_amt_5 > 0 ? $row->education_levy_amt_5 : $settings->education_levy_amt_5;

                                                    $gross =0;
                                                    $employeePay =0;
                                                    $deductions = 0;
                                                    $earnings = 0;
                                                    // $paidTimeOff = 0;
                                                    // $reimbursement = $row->reimbursement;
                                                    $nothingAdditionTonetPay = 0;

                                                    if (count($row->additionalEarnings) > 0) {
                                                        foreach($row->additionalEarnings as $key => $val) {
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

                                                    // if (count($row->additionalPaids) > 0){
                                                    //     foreach($row->additionalPaids as $key => $val) {
                                                    //         $paidTimeOff += $val->amount;                                            
                                                    //     }
                                                    // }

                                                    // $regHrs = $row->user->employeeProfile->pay_rate * $row->total_hours;

                                                    $gross = $row->gross + $row->paid_time_off;

                                                    $pay_type = $row->user->employeeProfile->pay_type;
                                                    $diff = date_diff(date_create($row->user->employeeProfile->dob), date_create(date("Y-m-d")));
                                                    $dob = $diff->format('%y');
                                                    $days = $row->total_hours;
                                                   
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
                                                    // $gross += ($regHrs + $row->overtime_hrs + $row->doubl_overtime_hrs + $row->holiday_pay + ($earnings + $nothingAdditionTonetPay) + $row->paid_time_off); commented

                                                    $grossFinal += $gross;
                                                    // $grossFinal += ($regHrs + $row->overtime_hrs + $row->doubl_overtime_hrs + $row->holiday_pay + ($earnings + $nothingAdditionTonetPay) + $row->paid_time_off);

                                                    $employeePay = $gross- ($mbse_deductions) + ($nothingAdditionTonetPay) - $deductions;

                                                    $totalEmployeePay += $employeePay;
                                                    // $totalTaxes += ($row->medical +$row->security + $row->edu_levy);
                                                    $totalDeductions += $deductions;
                                                    // $totalAdditions += $earnings;
                                                    $nothingAdditionTonetPayTotal += $nothingAdditionTonetPay;
                                                ?>
                                                <tr>
                                                    <td>{{ucfirst($row->user->employeeProfile->first_name)}} {{ucfirst($row->user->employeeProfile->last_name)}}</td>
                                                    <td>${{number_format($gross, 2)}}</td> <?php //$gross; commented?>
                                                    <td>${{number_format($medical_benefits, 2)}}</td>
                                                    <td>${{number_format($social_security, 2)}}</td>
                                                    <td>${{number_format($education_lvey, 2)}}</td>
                                                    <td>${{number_format($nothingAdditionTonetPay, 2)}}</td>
                                                    <td>${{number_format($deductions, 2)}}</td>
                                                    <?php
                                                    /*
                                                    <td>{{$row->total_hours}}</td>
                                                    <td>{{$row->overtime_hrs}}</td>
                                                    <td>{{$row->doubl_overtime_hrs}}</td>
                                                    <td>${{number_format($row->holiday_pay, 2)}}</td>
                                                    */
                                                    ?>                                                
                                                    <td>${{number_format($employeePay, 2)}}</td>                                                    
                                                </tr>                   
                                            @endforeach     
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>                                                    
                                                    <td>
                                                        <span style="color: #000 !important;font-weight: 700 !important;">${{number_format($totalEmployeePay, 2)}}</span><br>
                                                        <small style="color: #000 !important;font-weight: 600 !important;">Total Employee Pay</small>
                                                    </td>
                                                </tr>          
                                        </tbody>
                                      </table>
                                </div>
                              </div>
                            </div>
                            <div class="card">
                              <div class="card-head" id="headingThree">
                                <h2 class="mb-0 collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                   What Employer pays?
                                </h2>
                              </div>
                              <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                                <div class="card-body">
                                    <table class="table table-sm table-pay-data">
                                         <thead>
                                          <tr>
                                            <th scope="col">Employee</th>
                                            <th scope="col">Employee Pay</th>
                                            <th scope="col">Employee Taxes</th>
                                            <th scope="col">Employer taxes</th>
                                            <th scope="col">Subtotal</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                            @php  
                                                $subtotal = 0;
                                                $medical_benefits = $social_security = $education_lvey = $social_security_employer = 0;
                                            @endphp
                                            @foreach($data as $row)
                                                <?php

                                                    $medical_less_60_amt = $row->medical_less_60 ?? $settings->medical_less_60;
                                                    $medical_gre_60_amt = $row->medical_gre_60 ?? $settings->medical_gre_60;
                                                    $social_security_amt = $row->social_security ?? $settings->social_security;
                                                    $social_security_employer_amt = $row->social_security_employer ?? $settings->social_security_employer;
                                                    $education_levy_amt = $row->education_levy ?? $settings->education_levy;
                                                    $education_levy_amt_5 = $row->education_levy_amt_5 > 0 ? $row->education_levy_amt_5 : $settings->education_levy_amt_5;

                                                    $gross =0;
                                                    $employeePay =0;
                                                    $deductions = 0;
                                                    // $earnings = 0;
                                                    // $paidTimeOff = 0;
                                                    // $reimbursement = $row->reimbursement;
                                                    $nothingAdditionTonetPay = 0;

                                                    if (count($row->additionalEarnings) > 0) {
                                                        foreach($row->additionalEarnings as $key => $val) {
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

                                                    // if (count($row->additionalPaids) > 0){
                                                    //     foreach($row->additionalPaids as $key => $val) {
                                                    //         $paidTimeOff += $val->amount;                                            
                                                    //     }
                                                    // }

                                                    // $regHrs = $row->user->employeeProfile->pay_rate * $row->total_hours;

                                                    $gross = $row->gross + $row->paid_time_off;

                                                    $pay_type = $row->user->employeeProfile->pay_type;
                                                    $diff = date_diff(date_create($row->user->employeeProfile->dob), date_create(date("Y-m-d")));
                                                    $dob = $diff->format('%y');
                                                    $days = $row->total_hours;
                                                   
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
                                                    // $gross += ($regHrs + $row->overtime_hrs + $row->doubl_overtime_hrs + $row->holiday_pay + ($earnings + $nothingAdditionTonetPay) + $row->paid_time_off); commented

                                                    // $grossFinal += $gross;
                                                    // $grossFinal += ($regHrs + $row->overtime_hrs + $row->doubl_overtime_hrs + $row->holiday_pay + ($earnings + $nothingAdditionTonetPay) + $row->paid_time_off);

                                                    $employeePay = $gross- ($mbse_deductions) + ($nothingAdditionTonetPay) - $deductions;

                                                    // $totalEmployeePay += $employeePay;
                                                    // $totalTaxes += ($row->medical +$row->security + $row->edu_levy);
                                                    // $totalDeductions += $deductions;
                                                    // $totalAdditions += $earnings;
                                                    // $nothingAdditionTonetPayTotal += $nothingAdditionTonetPay;

                                                    $subtotal += $employeePay + $mbse_deductions + $row->security_employer + $medical_benefits;
                                                ?>
                                                <tr>
                                                    <td>{{ucfirst($row->user->employeeProfile->first_name)}} {{ucfirst($row->user->employeeProfile->last_name)}}</td>
                                                    <td>

                                                        ${{number_format($employeePay, 2)}}
                                                    </td>
                                                    <td>${{number_format($mbse_deductions, 2)}}</td>
                                                    <td>${{number_format($row->security_employer+$medical_benefits, 2)}}</td>
                                                    <td>${{number_format($employeePay + $mbse_deductions + $row->security_employer + $medical_benefits, 2)}}</td>
                                                </tr>  
                                            @endforeach       
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>                                                    
                                                    <td>
                                                        <span style="color: #000 !important;font-weight: 700 !important;">${{number_format($subtotal, 2)}}</span><br>
                                                        <small style="color: #000 !important;font-weight: 600 !important;">Total Payroll</small>
                                                    </td>
                                                </tr>               
                                        </tbody>
                                      </table>
                                </div>
                              </div>
                            </div>
                          </div>
                    </div>
                </div>
            </div>
        </div>
	</div>
</section>

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.umd.min.js"></script>
<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>


<script>
    var dataFinal = @json($dataGraph);
    var grossFinal = @json(number_format($grossFinal, 2));
    var totalEmployeePay = @json($TotalPayroll);
    var totalTaxes = @json($TotalTaxes);
    var totalDeductions = @json($totalDeductions);
    var totalAdditions = @json($nothingAdditionTonetPayTotal);
    // setup 
    const data = {
      labels: ['Employee Pay', 'Taxes', 'Deductions', 'Additions'],
      datasets: [{
        // label: 'Weekly Sales',
        data: [totalEmployeePay, totalTaxes, totalDeductions, totalAdditions],
        backgroundColor: [
            "#418f26",
			"#d7541b",
			"#f29314",
            "#4a2fec",
        ],
        borderColor: [
            "#418f26",
			"#d7541b",
			"#f29314",
            "#4a2fec",
        ],
        borderWidth: 0
      }]
    };

    const centerTextDoughnut ={
        id: 'centerTextDoughnut',
          afterDatasetsDraw(chart, args, pluginOptions){
          const{ctx} = chart;
          ctx.font = 'bold 12px sans-serif'
          const text= `Total Gross Pay: $${grossFinal}`;
          ctx.textAlign = 'center';
          ctx.textBaseline = 'Middle';
          const textWidth = ctx.measureText(text).width;
          const x = chart.getDatasetMeta(0).data[0].x
          const y = chart.getDatasetMeta(0).data[0].y
          ctx.fillText(text, x, y);
        }
    }
    // config 
    const config = {
      type: 'doughnut',
      data,
      options: {
               responsive: true,
               cutout: '85%',
               plugins: {
                  legend: {
                     display: true,
                     position: 'left',
                     align: 'center',
                     labels: {
                        color: 'black',
                        font: {
                           weight: 'normal'
                        },
                     }
                  }
               }
            },
            plugins: [centerTextDoughnut],
    };

    // render init block
    const myChart = new Chart(
      document.getElementById('myChart'),
      config
    );

    // Instantly assign Chart.js version
    const chartVersion = document.getElementById('chartVersion');
    chartVersion.innerText = Chart.version;
    </script>
<style>
    .mychart {
    width: 350px;
}
</style>
@endsection

