@extends('layouts.new_layout')
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
<style>
	body{
		background:#fff;
	}
</style>
<div class="bg-white w-100 border-radius-15 p-4">
    <div class="row">
        <div class="col-12">
            <div class="page-heading d-flex justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h3>Review and Submit</h3>
                    <p class="mb-0">Track and manage your payroll here</p>
                </div>
            </div>
        </div>
    </div>
	<div class="row mb-4">
		<div class="col-3">
			<div class="step-container on-step">
				<h2>1. Hours and Earnings</h2>
				<p class="bottom-line"></p>
			</div>
		</div>
		<div class="col-3">
			<div class="step-container on-step">
				<h2>2. Paid Time Off</h2>
				<p class="bottom-line"></p>
			</div>
		</div>
		<div class="col-3">
			<div class="step-container on-step">
				<h2>3. Review and Submit</h2>
				<p class="bottom-line"></p>
			</div>
		</div>
		<div class="col-3">
			<div class="step-container on-step">
				<h2>4. Submitted </h2>
				<p class="bottom-line"></p>
			</div>
		</div>
	</div>
    <div class="row">
        <div class="col-12">
            <div class="payroll-heading">
                <p>
                    Hooray! Your payroll is finished processing.
                </p>
            </div>
        </div>
        <div class="col-7 mb-4 pe-4">
            <div class="payroll-view-container h-100">
                <div class="row">
                    <div class="col-6 mb-4">
                        <div class="dabit-data">
                            <div class="d-flex gap-2 mb-2">
                                <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M13 6.5V19.5M9.75 16.4472L10.7022 17.1611C11.9708 18.1133 14.0281 18.1133 15.2978 17.1611C16.5674 16.2088 16.5674 14.6662 15.2978 13.7139C14.664 13.2373 13.832 13 13 13C12.2146 13 11.4292 12.7617 10.8301 12.2861C9.63192 11.3338 9.63192 9.79117 10.8301 8.83892C12.0282 7.88667 13.9718 7.88667 15.1699 8.83892L15.6195 9.19642M22.75 13C22.75 14.2804 22.4978 15.5482 22.0078 16.7312C21.5178 17.9141 20.7997 18.9889 19.8943 19.8943C18.9889 20.7997 17.9141 21.5178 16.7312 22.0078C15.5482 22.4978 14.2804 22.75 13 22.75C11.7196 22.75 10.4518 22.4978 9.26884 22.0078C8.08591 21.5178 7.01108 20.7997 6.10571 19.8943C5.20034 18.9889 4.48216 17.9141 3.99217 16.7312C3.50219 15.5482 3.25 14.2804 3.25 13C3.25 10.4141 4.27723 7.93419 6.10571 6.10571C7.93419 4.27723 10.4141 3.25 13 3.25C15.5859 3.25 18.0658 4.27723 19.8943 6.10571C21.7228 7.93419 22.75 10.4141 22.75 13Z" stroke="#5E5ADB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span>Total Payroll</span>
                            </div>
                            <h2>${{number_format($TotalPayroll, 2)}}</h2>
                        </div>
                    </div>
                    <div class="col-6 mb-4">
                        <div class="dabit-data">
                            <div class="d-flex gap-2 mb-2">
                                <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2.4375 20.3125C8.21774 20.3078 13.9728 21.0732 19.5509 22.5886C20.3385 22.8031 21.125 22.2181 21.125 21.4012V20.3125M4.0625 4.875V5.6875C4.0625 5.90299 3.9769 6.10965 3.82452 6.26202C3.67215 6.4144 3.46549 6.5 3.25 6.5H2.4375M2.4375 6.5V6.09375C2.4375 5.421 2.9835 4.875 3.65625 4.875H21.9375M2.4375 6.5V16.25M21.9375 4.875V5.6875C21.9375 6.136 22.3015 6.5 22.75 6.5H23.5625M21.9375 4.875H22.3438C23.0165 4.875 23.5625 5.421 23.5625 6.09375V16.6563C23.5625 17.329 23.0165 17.875 22.3438 17.875H21.9375M2.4375 16.25V16.6563C2.4375 16.9795 2.5659 17.2895 2.79446 17.518C3.02302 17.7466 3.33302 17.875 3.65625 17.875H4.0625M2.4375 16.25H3.25C3.46549 16.25 3.67215 16.3356 3.82452 16.488C3.9769 16.6403 4.0625 16.847 4.0625 17.0625V17.875M21.9375 17.875V17.0625C21.9375 16.847 22.0231 16.6403 22.1755 16.488C22.3278 16.3356 22.5345 16.25 22.75 16.25H23.5625M21.9375 17.875H4.0625M16.25 11.375C16.25 12.237 15.9076 13.0636 15.2981 13.6731C14.6886 14.2826 13.862 14.625 13 14.625C12.138 14.625 11.3114 14.2826 10.7019 13.6731C10.0924 13.0636 9.75 12.237 9.75 11.375C9.75 10.513 10.0924 9.6864 10.7019 9.0769C11.3114 8.46741 12.138 8.125 13 8.125C13.862 8.125 14.6886 8.46741 15.2981 9.0769C15.9076 9.6864 16.25 10.513 16.25 11.375ZM19.5 11.375H19.5087V11.3837H19.5V11.375ZM6.5 11.375H6.50867V11.3837H6.5V11.375Z" stroke="#5E5ADB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span>Total Taxes</span>
                            </div>
                            <h2>${{number_format($TotalTaxes, 2)}}</h2>
                        </div>
                    </div>
                    <div class="col-6 mb-4">
                        <div class="dabit-data">
                            <div class="d-flex gap-2 mb-2">
                                <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8.125 22.75L3.25 17.875M3.25 17.875L8.125 13M3.25 17.875H17.875M17.875 3.25L22.75 8.125M22.75 8.125L17.875 13M22.75 8.125H8.125" stroke="#5E5ADB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span>Direct deposits</span>
                            </div>
                            <h2>{{$directDeposits}}</h2>
                        </div>
                    </div>
                    <div class="col-6 mb-4">
                        <div class="dabit-data">
                            <div class="d-flex gap-2 mb-2">
                                <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2.4375 8.9375H23.5625M2.4375 9.75H23.5625M5.6875 15.4375H12.1875M5.6875 17.875H8.9375M4.875 21.125H21.125C21.7715 21.125 22.3915 20.8682 22.8486 20.4111C23.3057 19.954 23.5625 19.334 23.5625 18.6875V7.3125C23.5625 6.66603 23.3057 6.04605 22.8486 5.58893C22.3915 5.13181 21.7715 4.875 21.125 4.875H4.875C4.22853 4.875 3.60855 5.13181 3.15143 5.58893C2.69431 6.04605 2.4375 6.66603 2.4375 7.3125V18.6875C2.4375 19.334 2.69431 19.954 3.15143 20.4111C3.60855 20.8682 4.22853 21.125 4.875 21.125Z" stroke="#5E5ADB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span>Cheques</span>
                            </div>
                            <h2>{{$cheques}}</h2>
                        </div>
                    </div>
                    <div class="col-12">
                        <form action="{{ route('save.confirmation') }}" class="d-flex gap-3" method="POST">
                            @csrf
                            @foreach($ids as $k => $id)
                                <input type="hidden" name="ids[]" value="{{$id}}">
                            @endforeach
                            <button class="btn btn-primary save_continue" type="submit">Submit Payroll</button>
                            <a href="{{ route('store.Step2', [
                                'start_date' => Request::query('start_date'),
                                'end_date' => Request::query('end_date'),
                                'appoval_number'=> Request::query('appoval_number')
                            ]) }}" class="btn btn-primary  gap-2 px-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                            </svg>
                            Go Back
                            </a>
                            <a href="{{ route('download.pdf', [
                                'start_date' => Request::query('start_date'),
                                'end_date' => Request::query('end_date'),
                                'appoval_number'=> Request::query('appoval_number')
                            ]) }}" class="btn btn-primary  gap-2 px-3">
                            <svg class="w-64 h-64" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1"></path>
                                <path d="M4.603 12.087a.8.8 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.7 7.7 0 0 1 1.482-.645 20 20 0 0 0 1.062-2.227 7.3 7.3 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.187-.012.395-.047.614-.084.51-.27 1.134-.52 1.794a11 11 0 0 0 .98 1.686 5.8 5.8 0 0 1 1.334.05c.364.065.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.86.86 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.7 5.7 0 0 1-.911-.95 11.6 11.6 0 0 0-1.997.406 11.3 11.3 0 0 1-1.021 1.51c-.29.35-.608.655-.926.787a.8.8 0 0 1-.58.029m1.379-1.901q-.25.115-.459.238c-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361q.016.032.026.044l.035-.012c.137-.056.355-.235.635-.572a8 8 0 0 0 .45-.606m1.64-1.33a13 13 0 0 1 1.01-.193 12 12 0 0 1-.51-.858 21 21 0 0 1-.5 1.05zm2.446.45q.226.244.435.41c.24.19.407.253.498.256a.1.1 0 0 0 .07-.015.3.3 0 0 0 .094-.125.44.44 0 0 0 .059-.2.1.1 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a4 4 0 0 0-.612-.053zM8.078 5.8a7 7 0 0 0 .2-.828q.046-.282.038-.465a.6.6 0 0 0-.032-.198.5.5 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822q.036.167.09.346z"></path>
                              </svg>
                            Download PDF
                        </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-5 mb-4 ps-4">
            <div class="mychart pl-4f payroll-view-container">
                <canvas id="myChart"  height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div>
                <div class="accordion custom-accordion" id="accordionExample">
                    <div class="card-more mb-3">
                      <div class="card-head" id="headingOne">
                        <h2 class="mb-0 collapsed d-flex align-items-center gap-2" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13 6.5V19.5M9.75 16.4472L10.7022 17.1611C11.9708 18.1133 14.0281 18.1133 15.2978 17.1611C16.5674 16.2088 16.5674 14.6662 15.2978 13.7139C14.664 13.2373 13.832 13 13 13C12.2146 13 11.4292 12.7617 10.8301 12.2861C9.63192 11.3338 9.63192 9.79117 10.8301 8.83892C12.0282 7.88667 13.9718 7.88667 15.1699 8.83892L15.6195 9.19642M22.75 13C22.75 14.2804 22.4978 15.5482 22.0078 16.7312C21.5178 17.9141 20.7997 18.9889 19.8943 19.8943C18.9889 20.7997 17.9141 21.5178 16.7312 22.0078C15.5482 22.4978 14.2804 22.75 13 22.75C11.7196 22.75 10.4518 22.4978 9.26884 22.0078C8.08591 21.5178 7.01108 20.7997 6.10571 19.8943C5.20034 18.9889 4.48216 17.9141 3.99217 16.7312C3.50219 15.5482 3.25 14.2804 3.25 13C3.25 10.4141 4.27723 7.93419 6.10571 6.10571C7.93419 4.27723 10.4141 3.25 13 3.25C15.5859 3.25 18.0658 4.27723 19.8943 6.10571C21.7228 7.93419 22.75 10.4141 22.75 13Z" stroke="#5E5ADB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span>What gets taxed?</span>
                        </h2>
                      </div>
                      <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <div class="card-body">
                            <table class="table table-sm table-pay-data db-custom-table">
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
                                        <td>
                                            <strong>Medical benefits</strong>
                                        </td>
                                        <td>${{number_format(array_sum($medical_benefits11), 2,'.','')}}</td>
                                        <td>${{number_format(array_sum($medical_benefits11), 2,'.','')}}</td>
                                    </tr>
                                    <tr>
                                        <th>Social Security</th>
                                        <td>${{number_format(array_sum($social_security11), 2,'.','')}}</td>
                                        <td>${{number_format(array_sum($social_security_employer11), 2,'.','')}}</td>
                                    </tr>
                                    <tr>
                                        <th>Education levy</th>
                                        <td>${{number_format(array_sum($education_lvey11), 2,'.','')}}</td>
                                        <td>N/A</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight:bold !important;">Subtotal</td>
                                        <td>
                                            <span>${{number_format(array_sum($medical_benefits11)+array_sum($social_security11)+array_sum($education_lvey11), 2,'.','')}}</span>
                                        </td>
                                        <td>
                                            <span>${{number_format(array_sum($medical_benefits11)+array_sum($social_security_employer11), 2,'.','')}}</span>
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
                    <div class="card-more mb-3">
                      <div class="card-head" id="headingTwo">
                        <h2 class="mb-0 collapsed d-flex align-items-center gap-2" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.4375 20.3125C8.21774 20.3078 13.9728 21.0732 19.5509 22.5886C20.3385 22.8031 21.125 22.2181 21.125 21.4012V20.3125M4.0625 4.875V5.6875C4.0625 5.90299 3.9769 6.10965 3.82452 6.26202C3.67215 6.4144 3.46549 6.5 3.25 6.5H2.4375M2.4375 6.5V6.09375C2.4375 5.421 2.9835 4.875 3.65625 4.875H21.9375M2.4375 6.5V16.25M21.9375 4.875V5.6875C21.9375 6.136 22.3015 6.5 22.75 6.5H23.5625M21.9375 4.875H22.3438C23.0165 4.875 23.5625 5.421 23.5625 6.09375V16.6563C23.5625 17.329 23.0165 17.875 22.3438 17.875H21.9375M2.4375 16.25V16.6563C2.4375 16.9795 2.5659 17.2895 2.79446 17.518C3.02302 17.7466 3.33302 17.875 3.65625 17.875H4.0625M2.4375 16.25H3.25C3.46549 16.25 3.67215 16.3356 3.82452 16.488C3.9769 16.6403 4.0625 16.847 4.0625 17.0625V17.875M21.9375 17.875V17.0625C21.9375 16.847 22.0231 16.6403 22.1755 16.488C22.3278 16.3356 22.5345 16.25 22.75 16.25H23.5625M21.9375 17.875H4.0625M16.25 11.375C16.25 12.237 15.9076 13.0636 15.2981 13.6731C14.6886 14.2826 13.862 14.625 13 14.625C12.138 14.625 11.3114 14.2826 10.7019 13.6731C10.0924 13.0636 9.75 12.237 9.75 11.375C9.75 10.513 10.0924 9.6864 10.7019 9.0769C11.3114 8.46741 12.138 8.125 13 8.125C13.862 8.125 14.6886 8.46741 15.2981 9.0769C15.9076 9.6864 16.25 10.513 16.25 11.375ZM19.5 11.375H19.5087V11.3837H19.5V11.375ZM6.5 11.375H6.50867V11.3837H6.5V11.375Z" stroke="#5E5ADB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span>What do employees get?</span>
                        </h2>
                      </div>
                      <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-pay-data db-custom-table">
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
                                                <td>${{number_format($gross, 2,'.','')}}</td> <?php //$gross; commented?>
                                                <td>${{number_format($medical_benefits, 2,'.','')}}</td>
                                                <td>${{number_format($social_security, 2,'.','')}}</td>
                                                <td>${{number_format($education_lvey, 2,'.','')}}</td>
                                                <td>${{number_format($nothingAdditionTonetPay, 2,'.','')}}</td>
                                                <td>${{number_format($deductions, 2,'.','')}}</td>
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
                    </div>
                    <div class="card-more">
                      <div class="card-head" id="headingThree">
                        <h2 class="mb-0 collapsed d-flex align-items-center gap-2" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M19.481 20.2856C18.7241 19.2834 17.7447 18.4706 16.6202 17.9113C15.4956 17.352 14.2565 17.0615 13.0005 17.0627C11.7446 17.0615 10.5055 17.352 9.38093 17.9113C8.25636 18.4706 7.27701 19.2834 6.52004 20.2856M19.481 20.2856C20.9582 18.9717 21.9999 17.2399 22.4702 15.3197C22.9404 13.3995 22.8158 11.3818 22.113 9.53402C21.4101 7.68628 20.1622 6.09584 18.5346 4.97364C16.9071 3.85144 14.9769 3.25049 13 3.25049C11.0231 3.25049 9.09289 3.85144 7.46536 4.97364C5.83784 6.09584 4.5899 7.68628 3.88704 9.53402C3.18418 11.3818 3.05961 13.3995 3.52985 15.3197C4.00009 17.2399 5.04292 18.9717 6.52004 20.2856M19.481 20.2856C17.6979 21.8761 15.39 22.7536 13.0005 22.7502C10.6107 22.7538 8.30347 21.8764 6.52004 20.2856M16.2505 10.5627C16.2505 11.4246 15.9081 12.2513 15.2986 12.8608C14.6891 13.4703 13.8625 13.8127 13.0005 13.8127C12.1386 13.8127 11.3119 13.4703 10.7024 12.8608C10.093 12.2513 9.75054 11.4246 9.75054 10.5627C9.75054 9.70072 10.093 8.87407 10.7024 8.26457C11.3119 7.65508 12.1386 7.31267 13.0005 7.31267C13.8625 7.31267 14.6891 7.65508 15.2986 8.26457C15.9081 8.87407 16.2505 9.70072 16.2505 10.5627Z" stroke="#5E5ADB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span>What Employer pays?</span>
                        </h2>
                      </div>
                      <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                        <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-pay-data db-custom-table">
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
                                                ${{number_format($employeePay, 2,'.','')}}
                                            </td>
                                            <td>${{number_format($mbse_deductions, 2,'.','')}}</td>
                                            <td>${{number_format($row->security_employer+$medical_benefits, 2,'.','')}}</td>
                                            <td>${{number_format($employeePay + $mbse_deductions + $row->security_employer + $medical_benefits, 2,'.','')}}</td>
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

    const centerTextDoughnut = {
        id: 'centerTextDoughnut',
        afterDatasetsDraw(chart, args, pluginOptions) {
            const { ctx } = chart;
            ctx.font = 'bold 16px sans-serif'; // Font size 18px
            ctx.fillStyle = '#252525'; // Text color
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';

            const x = chart.getDatasetMeta(0).data[0].x;
            const y = chart.getDatasetMeta(0).data[0].y;

            // First line
            ctx.fillText("Total Pay:", x, y - 10); 

            // Second line
            ctx.fillText(`$${grossFinal}`, x, y + 15);
        }
    };


    // config
    const config = {
      type: 'doughnut',
      data,
      options: {
               responsive: true,
               cutout: '78%',
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
@endsection

