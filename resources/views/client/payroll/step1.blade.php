@extends('layouts.app')

@section('content')
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
            <li class="breadcrumb-item active">Run Payroll</li>
			<li class="breadcrumb-item active">/</li>
        </ol>
    </div>
</div>
<section class="content">
	<div class="container-fluid">		
		<div class="tab-content" id="myTabContent">
			<div class="container-fluid">
			@if (session('message'))
				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-success alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							{{ session('message') }}
						</div>
					</div>
				</div>
			@elseif (session('error'))
				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-danger alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							{{ session('error') }}
						</div>
					</div>
				</div>
			@endif
			<div class="row">
				<div class="col-sm-12">	
					<div class="card">	
						<div class="payroll-top pt-4 text-center">
							<h4>Payroll is being processed from {{ date('F dS Y', strtotime($from))}} to {{ date('F dS Y', strtotime($to))}}</h4>
						</div>					
						<form class="form-horizontal" method="POST" action="{{ route('store.Step1', ['start_date' => $from, 'end_date' => $to, 'appoval_number'=> $appoval_number]) }}" id="fom-timesheet">
							@csrf
							<div class="card-body">
								<table class="table custom-table-run">
									<thead>
									    <tr>
									      <th scope="col">Employees</th>
									      <th scope="col">Hours Worked</th>
									      <th scope="col">Additional Earnings</th>
									      <th scope="col">Gross Pay</th>
									    </tr>
									</thead>
									<tbody>
										@foreach($employees as $k =>$employee)
										<?php
											// $from = date('Y-m-01'); //date('m-01-Y');
											// $to = date('Y-m-t'); //date('m-t-Y');

											$timeCardData = \App\Models\PayrollSheet::whereBetween('payroll_date', [$from, $to])->where('approval_status', 1)->where('appoval_number', $appoval_number)->whereNotNull('payroll_date')->where('emp_id', $employee->id)->get();

											if ($timeCardData->count() == 0) {
												continue;
											}
											// foreach($timeCardData as $k => $v) {
											// 	$timeCardData[$k]['daily_hrs'] = (float) $v['daily_hrs'];
											// }

											$isDataExist = \App\Models\PayrollAmount::where('start_date', '>=', $from)->where('end_date', '<=', $to)->where('user_id', $employee->id)->first();

											if(!empty($isDataExist)) {
												$id = $isDataExist->id;
												$totalHours = collect($timeCardData)->sum(function ($row) {
												    return (float) $row->daily_hrs;
												}); //$isDataExist->total_hours;
												$totalDays = collect($timeCardData)->count('daily_hrs');
												$reimbursement = $isDataExist->reimbursement;
												$overtimeHours = $isDataExist->overtime_hrs;
												$dovertimeHours = $isDataExist->doubl_overtime_hrs;
												$holidayPayHrs = !empty($isDataExist->holiday_pay) ? $isDataExist->holiday_pay : 0;
												$gross = $isDataExist->gross;
												$medical = $isDataExist->medical;
												$security = $isDataExist->security;
												$securityEmployer = $isDataExist->security_employer;
												$net_pay = $isDataExist->net_pay;
												$otCalc = $isDataExist->overtime_calc;
												$dotCalc = $isDataExist->doubl_overtime_calc;
												$edu_levy = $isDataExist->edu_levy;

												$medical_less_60_amt = $isDataExist->medical_less_60_amt ?? $settings->medical_less_60;
												$medical_gre_60_amt = $isDataExist->medical_gre_60_amt ?? $settings->medical_gre_60;
												$social_security_amt = $isDataExist->social_security_amt ?? $settings->social_security;
												$social_security_employer_amt = $isDataExist->social_security_employer_amt ?? $settings->social_security_employer;
												$education_levy_amt = $isDataExist->education_levy_amt ?? $settings->education_levy;
											} else {
												$id = NULL;
												$totalHours = collect($timeCardData)->sum(function ($row) {
												    return (float) $row->daily_hrs;
												});
												$totalDays = collect($timeCardData)->count('daily_hrs');
												$reimbursement = 0;
												$overtimeHours = 0;
												$dovertimeHours = 0;
												$holidayPayHrs = 0;
												$otCalc = 0;
												$dotCalc = 0;
												$gross = 0;
												$medical = 0;
												$security = 0;
												$securityEmployer = 0;
												$net_pay = 0;
												$edu_levy = 0;

												$medical_less_60_amt = $settings->medical_less_60;
												$medical_gre_60_amt = $settings->medical_gre_60;
												$social_security_amt = $settings->social_security;
												$social_security_employer_amt = $settings->social_security_employer;
												$education_levy_amt = $settings->education_levy;
											}


											// $from = new \DateTime($employee->employeeProfile->dob);
											// $to   = new \DateTime('today');
											// dd($from->diff($to)->y);
											$diff = date_diff(date_create($employee->employeeProfile->dob), date_create(date("Y-m-d")));

											$dob = $diff->format('%y');
										?>
									    <tr class="row-tr-js tr-main">									      
									      	<td class="col-sm-3">
												<table>
													<tr>
														<td class="employee-name">
															<div class="d-flex">
																<div class="ts-img d-flex justify-content-center align-items-center">
																	@if(!empty($employee->employeeProfile->file))
																		<img src="/files/{{$employee->employeeProfile->file}}"
																		style="width: 40px; height: 40px; border-radius: 100em;" />
																	@else
																		<img src='/img/user2-160x160.jpg' style="width: 40px; height: 40px; border-radius: 100em;">
																	@endif		
																</div>
															<div class="col-auto">
															{{ $employee->name }} <span class="badge badge-primary">{{ strtoupper($employee->employeeProfile->pay_type) }}</span>
															</div>
															</div>
														</td>

													</tr>
													<tr>
														<td>
															${{ $employee->employeeProfile->pay_rate }}
														</td>
													</tr>
													<tr>
														<!-- <td>/ Add Personal Note</td> -->
													</tr>
												</table>
									      	</td>
									      	<td class="col-sm-3">
									      		<table>
													<tr>
														<td>
															<p>
																<button class="btn-none"  data-toggle="collapse" href="#collapseExample{{$k}}" role="button" aria-expanded="false" aria-controls="collapseExample{{$k}}">
																	<svg width="20px" class="align-middle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#007bff" aria-hidden="true">
																		<path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 9a.75.75 0 00-1.5 0v2.25H9a.75.75 0 000 1.5h2.25V15a.75.75 0 001.5 0v-2.25H15a.75.75 0 000-1.5h-2.25V9z" clip-rule="evenodd"></path>																	
																	</svg> Regular hours
																</button>
																<p class="collapse" id="collapseExample{{$k}}">
																	<input type="hidden" value="{{$id}}" name="input[{{$employee->id}}][id]">
																	  <input type="hidden" value="{{$from}}" name="input[{{$employee->id}}][start_date]">
																	  <input type="hidden" value="{{$to}}" name="input[{{$employee->id}}][end_date]">
																	 <input type="number" name="input[{{$employee->id}}][working_hrs]" min="0" value="{{ $totalHours }}" class="form-control fixed-input working_hrs" onchange="calculateGross(this, '<?php echo $employee->id; ?>', '<?php echo $employee->employeeProfile->pay_type; ?>', 'working_hrs', '<?php echo $k; ?>', '<?php echo $employee->employeeProfile->pay_rate; ?>', '<?php echo $totalDays; ?>' , '<?php echo $dob; ?>')">
																</p>
															</p>

															<p>
																<button class="btn-none"  data-toggle="collapse" href="#overtime{{$k}}" role="button" aria-expanded="false" aria-controls="overtime{{$k}}">
																	<svg width="20px" class="align-middle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#007bff" aria-hidden="true">
																		<path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 9a.75.75 0 00-1.5 0v2.25H9a.75.75 0 000 1.5h2.25V15a.75.75 0 001.5 0v-2.25H15a.75.75 0 000-1.5h-2.25V9z" clip-rule="evenodd"></path>
																	</svg> OT
																</button>
																<p class="collapse" id="overtime{{$k}}">
																	<input type="number" name="input[{{$employee->id}}][overtime_hrs]" min="0" value="{{ $overtimeHours }}" class="form-control fixed-input overtime_hrs" onchange="calculateGross(this, '<?php echo $employee->id; ?>', '<?php echo $employee->employeeProfile->pay_type; ?>', 'overtime_hrs', '<?php echo $k; ?>', '<?php echo $employee->employeeProfile->pay_rate; ?>', '<?php echo $totalDays; ?>', '<?php echo $dob; ?>')">
																</p>
															</p>
															<p>
																<button class="btn-none"  data-toggle="collapse" href="#doubleovertime{{$k}}" role="button" aria-expanded="false" aria-controls="doubleovertime{{$k}}">
																	<svg width="20px" class="align-middle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#007bff" aria-hidden="true">
																		<path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 9a.75.75 0 00-1.5 0v2.25H9a.75.75 0 000 1.5h2.25V15a.75.75 0 001.5 0v-2.25H15a.75.75 0 000-1.5h-2.25V9z" clip-rule="evenodd"></path>
																	</svg> DT
																</button>
																<p class="collapse" id="doubleovertime{{$k}}">
																	 <input type="number" name="input[{{$employee->id}}][double_overtime_hrs]" min="0" value="{{ $dovertimeHours }}" class="form-control fixed-input double_overtime_hrs" onchange="calculateGross(this, '<?php echo $employee->id; ?>', '<?php echo $employee->employeeProfile->pay_type; ?>', 'double_overtime_hrs', '<?php echo $k; ?>', '<?php echo $employee->employeeProfile->pay_rate; ?>', '<?php echo $totalDays; ?>', '<?php echo $dob; ?>')">
																</p>
															</p>

															<p>
																<button class="btn-none"  data-toggle="collapse" href="#holiday_pay{{$k}}" role="button" aria-expanded="false" aria-controls="holiday_pay{{$k}}">
																	<svg width="20px" class="align-middle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#007bff" aria-hidden="true">
																		<path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 9a.75.75 0 00-1.5 0v2.25H9a.75.75 0 000 1.5h2.25V15a.75.75 0 001.5 0v-2.25H15a.75.75 0 000-1.5h-2.25V9z" clip-rule="evenodd"></path>
																	</svg> Holiday pay
																</button>
																<p class="collapse" id="holiday_pay{{$k}}">
																	<input type="number" name="input[{{$employee->id}}][holiday_pay]" min="0" value="{{ $holidayPayHrs }}" class="form-control fixed-input holiday_pay" onchange="calculateGross(this, '<?php echo $employee->id; ?>', '<?php echo $employee->employeeProfile->pay_type; ?>', 'holiday_pay', '<?php echo $k; ?>', '<?php echo $employee->employeeProfile->pay_rate; ?>', '<?php echo $totalDays; ?>', '<?php echo $dob; ?>')">
																</p>
															</p>
														</td>
													</tr>
													<tr>
														<td></td>
													</tr>
													<tr>
														<td></td>
													</tr>
												</table>
									      	</td>
									      	<td class="col-sm-3" data-earn="{{$isDataExist->additionalEarnings ?? null}}">
												<table>
													<tr>
														<td>
															@foreach($employee->payheads as $key =>$value)
																<?php
																	$amountPayhead = 0;
																	if (!empty($isDataExist->additionalEarnings)) {
																		// $collection = collect($isDataExist->additionalEarnings);
																		// dd($collection);
																		$arrTemp = $isDataExist->additionalEarnings()->select('amount')->where('user_id', $employee->id)->where('payhead_id', $value->payhead->id)->first();

																		$amountPayhead = !empty($arrTemp->amount) ? $arrTemp->amount: 0;
																	}
																?>
																<p>
																	<label class="cursor-pointer" data-toggle="collapse" href="#bonus{{$employee->id}}{{$key}}" role="button" aria-expanded="false" aria-controls="bonus{{$employee->id}}{{$key}}">
																		<svg width="20px" class="align-middle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#007bff" aria-hidden="true">
																			<path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 9a.75.75 0 00-1.5 0v2.25H9a.75.75 0 000 1.5h2.25V15a.75.75 0 001.5 0v-2.25H15a.75.75 0 000-1.5h-2.25V9z" clip-rule="evenodd"></path>
																		</svg>
																		{{$value->payhead->name}} 
																	</label>
																	<p class="collapse" id="bonus{{$employee->id}}{{$key}}">
																		<input type="hidden" value="{{$value->payhead_id}}" name="input[{{$employee->id}}][earnings][{{$key }}][payhead_id]">
																		<input type="number" name="input[{{$employee->id}}][earnings][{{$key }}][amount]" min="0" value="{{$amountPayhead}}"  data-payheadtype="{{$value->payhead->pay_type}}" class="form-control fixed-input additional-hrs" data-payhead="{{ $value->payhead->id}}-{{$employee->id}}" onchange="calculateGross(this, '<?php echo $employee->id; ?>', '<?php echo $employee->employeeProfile->pay_type; ?>', 'additional', '<?php echo $k; ?>', '<?php echo $employee->employeeProfile->pay_rate; ?>', '<?php echo $totalDays; ?>', '<?php echo $dob; ?>')">
																	</p>										      	
																</p>
															@endforeach
													<?php
														// }
													?>
														</td>
													</tr>
													<tr>
														<td></td>
													</tr>
													<tr>
														<td></td>
													</tr>
												</table>
									      	
									      	</td>
									      <td class="col-sm-3">
											<table>
												<tr>
													<td>
														<div data-maindiv="reg_hrs_div"><small class="badge badge-info">Regular hours</small>: <span class="reg_hrs">0.00</span><br></div>
														<div data-maindiv="overtime-div"><small class="badge badge-info">OT</small>: $<span class="overtime">0.00</span><br></div>
														<div data-maindiv="double-overtime-div"><small class="badge badge-info">DT</small>: $<span class="double-overtime">0.00</span><br></div>
														<div data-maindiv="holiday-pay-span-div"><small class="badge badge-info">Holiday Pay</small>: $<span class="holiday-pay-span">0.00</span><br></div>
														<div data-maindiv="additonal-earn-span-div">
															@foreach($employee->payheads as $key =>$value)
																<div id="{{ $value->payhead->id}}-{{$employee->id}}" class="d-none">
																	<small class="badge badge-info">{{ $value->payhead->name}}</small>: $<span data-payheadlast="{{ $value->payhead->id}}-{{$employee->id}}">0.00</span>
																</div>
															@endforeach													
																<small class="badge badge-info d-none">Total Additional Earnings</small><span class="additonal-earn-span d-none">0.00</span>
														</div>
														<div data-maindiv="medical-div" class="d-none"><small class="badge badge-info">Medical</small>: $<span class="medical">0.00</span><br></div>
														<div data-maindiv="social-security-div" class="d-none"><small class="badge badge-info">Security</small>: $<span class="social-security">0.00</span><br></div>
														<div data-maindiv="edu-levy-div" class="d-none"><small class="badge badge-info">Education Levy</small>: $<span class="edu-levy">0.00</span><br></div>
														<div class="d-none" data-maindiv="net-pay-div"><small class="badge badge-info">Net Pay</small>: $<span class="net-pay">0.00</span><br></div>
														<div data-maindiv="total-div"><small class="badge badge-info">Gross Pay</small>: $<span class="total">0.00</span></div>


														<input type="hidden" class="total-hidden" name="input[{{$employee->id}}][gross]" value="{{ $gross }}">
														<input type="hidden" class="overtime-hidden" name="input[{{$employee->id}}][overtime_calc]" value="{{ $otCalc }}">
														<input type="hidden" class="double-overtime-hidden" name="input[{{$employee->id}}][doubl_overtime_calc]" value="{{ $dotCalc }}">
														<input type="hidden" class="holiday-pay-hidden" name="input[{{$employee->id}}][holiday_pay]" value="{{ $dotCalc }}">
														<input type="hidden" class="medical-hidden" name="input[{{$employee->id}}][medical]" value="{{ $medical }}">
														<input type="hidden" class="social-security-hidden" name="input[{{$employee->id}}][security]" value="{{ $security }}">
														<input type="hidden" class="social-security-employer-hidden" name="input[{{$employee->id}}][security_employer]" value="{{ $securityEmployer }}">
														<input type="hidden" class="net-pay-hidden" name="input[{{$employee->id}}][net_pay]" value="{{ $net_pay }}">
														<input type="hidden" class="edu-levy-hidden" name="input[{{$employee->id}}][edu_levy]" value="{{ $edu_levy }}">

														<input type="hidden" class="medical_less_60_amthidden" name="input[{{$employee->id}}][medical_less_60_amt]" value="{{ $medical_less_60_amt }}">	
												    	<input type="hidden" class="medical_gre_60_amthidden" name="input[{{$employee->id}}][medical_gre_60_amt]" value="{{ $medical_gre_60_amt }}">	
												    	<input type="hidden" class="social_security_amthidden" name="input[{{$employee->id}}][social_security_amt]" value="{{ $social_security_amt }}">	
												    	<input type="hidden" class="social_security_employer_amthidden" name="input[{{$employee->id}}][social_security_employer_amt]" value="{{ $social_security_employer_amt }}">	
												    	<input type="hidden" class="education_levy_amthidden" name="input[{{$employee->id}}][education_levy_amt]" value="{{ $education_levy_amt }}">	
													</td>
												</tr>
												<tr>
													<td>
														<!-- <label class="cursor-pointer" data-toggle="collapse" href="#g-pay{{$employee->id}}{{$k}}" role="button" aria-expanded="false" aria-controls="g-pay{{$employee->id}}{{$k}}">
															<svg width="20px" class="align-middle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#007bff" aria-hidden="true">
																<path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 9a.75.75 0 00-1.5 0v2.25H9a.75.75 0 000 1.5h2.25V15a.75.75 0 001.5 0v-2.25H15a.75.75 0 000-1.5h-2.25V9z" clip-rule="evenodd"></path>
															</svg>	
															Reimbursement
														</label> -->
														<p class="collapse" id="g-pay{{$employee->id}}{{$k}}">
															<input type="hidden" name="input[{{$employee->id}}][reimbursement]" value="{{$reimbursement}}" min="0" class="form-control fixed-input reimbursement_hrs" onchange="calculateGross(this, '<?php echo $employee->id; ?>', '<?php echo $employee->employeeProfile->pay_type; ?>', 'reimbursement', '<?php echo $k; ?>', '<?php echo $employee->employeeProfile->pay_rate; ?>', '<?php echo $totalDays; ?>', '<?php echo $dob; ?>')">
														</p>
													</td>
												</tr>
												<tr>
													<td></td>
												</tr>
											</table>
									      </td>
									    </tr>

									    @endforeach	    
									</tbody>
								</table>
								<div class="confirm-container">
									<div class="text-center">
										<svg xmlns="http://www.w3.org/2000/svg" width="32" viewBox="0 0 48 48"><g data-name="Layer 1 copy">
											<rect x="7.859" y="10.844" fill="#569f7d" transform="rotate(-10.943 26.58 21.563)"/><rect width="37.447" height="21.434" x="4.372" y="15.125" fill="#60b18b" transform="rotate(-5.938 23.096 25.837)"/><rect width="37.447" height="21.434" x="1" y="19.081" fill="#8ed8b5"/><path fill="#b1dfbc" d="M35,34.478A3.007,3.007,0,0,0,32,37.485H7.453a3.007,3.007,0,0,0-3.008-3.007V25.119a3.008,3.008,0,0,0,3.008-3.008H32A3.007,3.007,0,0,0,35,25.119Z"/><circle cx="19.724" cy="29.798" r="4.512" fill="#8ed8b5"/><polygon fill="#60b18b" points="41.346 9.771 45.046 28.907 47 28.529 42.931 7.485 6.165 14.594 6.534 16.502 41.346 9.771"/><polygon fill="#8ed8b5" points="39.025 15.532 41.024 34.752 42.827 34.565 40.61 13.246 3.364 17.12 3.582 19.219 39.025 15.532"/><polygon fill="#b1dfbc" points="1 19.081 1 21.111 36.4 21.111 36.4 40.515 38.447 40.515 38.447 19.081 1 19.081"/><path fill="#8ed8b5" d="M5.268 24.99a2.987 2.987 0 0 1-.823.129v1.214A3 3 0 0 0 5.268 24.99zM30.748 37.485H32a2.981 2.981 0 0 1 .136-.849A2.988 2.988 0 0 0 30.748 37.485z"/><path fill="#c1ecd0" d="M32,22.111H7.453a2.989,2.989,0,0,1-.8,2.03h23.3a3.007,3.007,0,0,0,3.008,3.007v8.145A2.988,2.988,0,0,1,35,34.478V25.119A3.007,3.007,0,0,1,32,22.111Z"/><path fill="#b1dfbc" d="M19.724,25.286a4.5,4.5,0,0,0-4.019,2.5,4.456,4.456,0,0,1,1.971-.472,4.512,4.512,0,0,1,4.512,4.512,4.459,4.459,0,0,1-.493,2.01,4.5,4.5,0,0,0-1.971-8.552Z"/></g><g data-name="Layer 1 copy 2"><path fill="#1c1c1b" d="M38.447,41.515H1a1,1,0,0,1-1-1V19.081a1,1,0,0,1,1-1H38.447a1,1,0,0,1,1,1V40.515A1,1,0,0,1,38.447,41.515ZM2,39.515H37.447V20.081H2Z"/><path fill="#1c1c1b" d="M32,38.485H7.453a1,1,0,0,1-1-1,2.01,2.01,0,0,0-2.008-2.007,1,1,0,0,1-1-1V25.119a1,1,0,0,1,1-1,2.011,2.011,0,0,0,2.008-2.008,1,1,0,0,1,1-1H32a1,1,0,0,1,1,1A2.01,2.01,0,0,0,35,24.119a1,1,0,0,1,1,1v9.359a1,1,0,0,1-1,1,2.009,2.009,0,0,0-2.007,2.007A1,1,0,0,1,32,38.485Zm-23.669-2h22.8A4.025,4.025,0,0,1,34,33.6V25.992a4.023,4.023,0,0,1-2.881-2.881H8.326a4.021,4.021,0,0,1-2.881,2.881V33.6A4.023,4.023,0,0,1,8.326,36.485Z"/><path fill="#1c1c1b" d="M19.724,35.31A5.512,5.512,0,1,1,25.235,29.8,5.519,5.519,0,0,1,19.724,35.31Zm0-9.023A3.512,3.512,0,1,0,23.235,29.8,3.515,3.515,0,0,0,19.724,26.287Z"/><path fill="#1c1c1b" d="M40.819,35.788a1,1,0,0,1-.1-1.995l1.154-.121-2.03-19.327L7.65,17.725a1,1,0,0,1-.209-1.99l33.184-3.484a1,1,0,0,1,1.1.89l2.239,21.317a1,1,0,0,1-.89,1.1l-2.149.226C40.889,35.786,40.854,35.788,40.819,35.788Z"/><path fill="#1c1c1b" d="M45.367,29.848a1,1,0,0,1-.188-1.982l.651-.125L42.168,8.655,10.827,14.667A1,1,0,1,1,10.45,12.7L42.774,6.5a1,1,0,0,1,1.17.793l4.038,21.051a1,1,0,0,1-.794,1.17l-1.632.313A1.024,1.024,0,0,1,45.367,29.848Z"/></g>
										</svg>
										<h3>Confirm your amounts</h3>
										<p class="text-center">To ensure accuracy, please review your payroll numbers above and make sure they’re 100% correct</p>
										<b class="total_amount_confirm">$0.00</b>
									</div>
								</div>
							</div>
							<div class="card-footer">
								<div class="d-flex justify-content-center">
									<button type="submit" id="save-button" class="btn btn-primary text-uppercase save_continue">Save & continue</button>
									<!-- <button type="reset" id="reset" class="btn btn-default text-uppercase ml-2 reset_btn">Reset</button> -->
								</div>
							</div>
						</form>
						
					</div>
				</div>
			</div>
			</div>
		</div>
		</div>
</section>
@endsection
@push('page_scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/corejs-typeahead/1.2.1/bloodhound.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/corejs-typeahead/1.2.1/typeahead.jquery.min.js"></script>

<script>

	const formatter = new Intl.NumberFormat('en-US', {
		style: 'currency',
		currency: 'USD',
	});

	$(document).ready(function() {
		$('.working_hrs').each(function() { $(this).trigger('change');})
	});

	function calculateGross(obj, emp_id, pay_type, field_name, row_key, rate_per_hour, days, dob) {
		var regular_hrs = 1;
		var overtime_hrs = 0;
		var double_overtime_hrs = 0;
		var reimbursement_hrs = 0;
		var gross = medical_benefits = amount = social_security = education_lvey = social_security_employer = 0;
		var focusedRow = $(obj).closest('.row-tr-js');
		var total_deductions = 0;
		var net_pay = 0;
		var holiday_pay = 0;
		var final_gross= 0;

		var rate_per_hour = parseFloat(rate_per_hour);
		regular_hrs = parseFloat(focusedRow.find(".working_hrs").val());
		overtime_hrs_val = parseFloat(focusedRow.find(".overtime_hrs").val());
		holiday_pay_val = parseFloat(focusedRow.find(".holiday_pay").val());
		double_overtime_hrs_val = parseFloat(focusedRow.find(".double_overtime_hrs").val());
		reimbursement_hrs = parseFloat(focusedRow.find(".reimbursement_hrs").val());

		var medical_less_60_amt = parseFloat(focusedRow.find(".medical_less_60_amthidden").val());
		var medical_gre_60_amt  = parseFloat(focusedRow.find(".medical_gre_60_amthidden").val());
		var social_security_amt  = parseFloat(focusedRow.find(".social_security_amthidden").val());
		var social_security_employer_amt  = parseFloat(focusedRow.find(".social_security_employer_amthidden").val());
		var education_levy_amt  = parseFloat(focusedRow.find(".education_levy_amthidden").val());
		console.log(medical_less_60_amt,medical_gre_60_amt,social_security_amt,social_security_employer_amt,education_levy_amt);
		//Caluclate Additonal Hours
		var additionalHrsEarnings = 0;
		var additionalHrsDeductions = 0;
	  	focusedRow.find(".additional-hrs").each(function() {	  		
	  		if ($.isNumeric(this.value) && $(this).attr('data-payheadtype') == 'earnings') {
	  			additionalHrsEarnings += parseFloat(this.value);

	  			if (this.hasAttribute('data-payhead')) {
			  		let temp_attr = $(this).attr('data-payhead');

			  		// console.log(focusedRow.find(`[data-payheadlast="${temp_attr}"]`), 77777);
			  		if ($.isNumeric(this.value) && this.value > 0) {
				  		focusedRow.find(`[id="${temp_attr}"]`).removeClass('d-none');
				  		focusedRow.find(`[data-payheadlast="${temp_attr}"]`).html(this.value);
				  	}  else {
				  		focusedRow.find(`[id="${temp_attr}"]`).addClass('d-none');	
				  	}

			  	}
	  		}
	  	});

	  	focusedRow.find(".additional-hrs").each(function(){
	  		if ($.isNumeric(this.value) && $(this).attr('data-payheadtype') == 'deductions') {
	  			additionalHrsDeductions += parseFloat(this.value);
			  	if (this.hasAttribute('data-payhead')) {
			  		let temp_attr = $(this).attr('data-payhead');

			  		// console.log(focusedRow.find(`[data-payheadlast="${temp_attr}"]`), 77777);
			  		if ($.isNumeric(this.value) && this.value > 0) {
				  		focusedRow.find(`[id="${temp_attr}"]`).removeClass('d-none');
				  		focusedRow.find(`[data-payheadlast="${temp_attr}"]`).html(this.value);
				  	}  else {
				  		// focusedRow.find(`[id="${temp_attr}"]`).addClass('d-none');	
				  	}
			  	}
	  		}
	  	});


	  	// console.log($(obj).attr('payhead'), obj.hasAttribute('data-payhead'), 22222222);

	  	gross = amount = rate_per_hour * regular_hrs; //Gross

	  	overtime_hrs = (overtime_hrs_val * 1.5 ) * rate_per_hour;

	  	holiday_pay = (holiday_pay_val * 1.5 ) * rate_per_hour;

	  	double_overtime_hrs = (double_overtime_hrs_val * 2) * rate_per_hour;

	  	if (pay_type == 'hourly' || pay_type == 'weekly') {

	  		if (dob <= 60) {
	  			medical_benefits = (gross * medical_less_60_amt) / 100;
	  		} else if (dob > 60 && dob <=79 ) {
				medical_benefits = (gross * medical_gre_60_amt) / 100;
	  		} else if (dob > 70 ) {
	  			medical_benefits = 0;
	  		}

	  		social_security = ( gross>1500 ? ((1500*social_security_amt) / 100) : (gross*social_security_amt) / 100 );  
	  		social_security_employer = ( gross>1500 ? ((1500*social_security_employer_amt) / 100) : (gross*social_security_employer_amt) / 100 );  
	  		education_lvey = (gross<=125?0:(gross>1154?( ((1154-125)*education_levy_amt) / 100)+( ((gross-1154)*5) / 100 ):( ((gross-125)*education_levy_amt) /100)));
	  		total_deductions = medical_benefits + social_security + education_lvey;
	  		net_pay = gross - total_deductions;
	  	} else if (pay_type == 'bi-weekly') {
	  		//medical_benefits = (gross * 3.5) / 100;
  			if (dob <= 60) {
	  			medical_benefits = (gross * medical_less_60_amt) / 100;
	  		} else if (dob > 60 && dob <=79 ) {
				medical_benefits = (gross * medical_gre_60_amt) / 100;
	  		} else if (dob > 70 ) {
	  			medical_benefits = 0;
	  		}

	  		if (days <= 7) {
	  			social_security = ( gross>3000 ? ((3000*social_security_amt) / 100) : (gross*social_security_amt) / 100 ); 
	  			social_security_employer = ( gross>3000 ? ((3000*social_security_employer_amt) / 100) : (gross*social_security_employer_amt) / 100 ); 
	  		} else {
	  			social_security = ( gross>3000 ? ((3000*social_security_amt) / 100) : (gross*social_security_amt) / 100 ); 
	  			social_security_employer = ( gross>3000 ? ((3000*social_security_employer_amt) / 100) : (gross*social_security_employer_amt) / 100 ); 
	  		}
	  		education_lvey = (gross<=250?0:(gross>2308?(((2308-250)*education_levy_amt)/100)+(((gross-2308)*5)/100):(((gross-250)*education_levy_amt)/100)));
	  		total_deductions = medical_benefits + social_security + education_lvey;
	  		net_pay = gross - total_deductions;
	  		if (days <= 7) {
	  		} else {
	  			net_pay = 2 * net_pay;	  			
	  		}
	  	} else if (pay_type == 'semi-monthly') {
	  		if (dob <= 60) {
	  			medical_benefits = (gross * medical_less_60_amt) / 100;
	  		} else if (dob > 60 && dob <=79 ) {
				medical_benefits = (gross * medical_gre_60_amt) / 100;
	  		} else if (dob > 70 ) {
	  			medical_benefits = 0;
	  		}
	  		social_security = ( gross>3000 ? ((3000*social_security_amt) / 100) : (gross*social_security_amt) / 100 ); 
	  		social_security_employer = ( gross>3000 ? ((3000*social_security_employer_amt) / 100) : (gross*social_security_employer_amt) / 100 ); 
	  		education_lvey = (gross<=125?0:(gross>2500?(((2500-270.84)*education_levy_amt)/100)+(((gross-2500)*5)/100):(((gross-270.84)*education_levy_amt)/100)));
	  		total_deductions = medical_benefits + social_security + education_lvey;
	  		net_pay = gross - total_deductions;
	  	} else if (pay_type == 'monthly') {
	  		if (dob <= 60) {
	  			medical_benefits = (gross * medical_less_60_amt) / 100;
	  		} else if (dob > 60 && dob <=79 ) {
				medical_benefits = (gross * medical_gre_60_amt) / 100;
	  		} else if (dob > 70 ) {
	  			medical_benefits = 0;
	  		}
	  		social_security = ( gross>6500 ? ((6500*social_security_amt) / 100) : (gross*social_security_amt) / 100 ); 
	  		social_security_employer = ( gross>6500 ? ((6500*social_security_employer_amt) / 100) : (gross*social_security_employer_amt) / 100 ); 
	  		education_lvey = (gross<=125?0:(gross>5000?(((5000-541.67)*education_levy_amt)/100)+(((gross-5000)*5)/100):(((gross-541.67)*education_levy_amt)/100)));
	  		total_deductions = medical_benefits + social_security + education_lvey;
	  		net_pay = gross - total_deductions;
	  	}

	  	gross += (overtime_hrs + double_overtime_hrs + additionalHrsEarnings + holiday_pay);

	  	net_pay += reimbursement_hrs;

	  	net_pay -= additionalHrsDeductions;

	  	final_gross += (rate_per_hour * regular_hrs) + (overtime_hrs + double_overtime_hrs + additionalHrsEarnings + holiday_pay);

	  	if (additionalHrsEarnings > 0) {
	  		focusedRow.find('[data-maindiv="additonal-earn-span-div"]').removeClass('d-none');
	  	}  else {
	  		// focusedRow.find('[data-maindiv="additonal-earn-span-div"]').addClass('d-none');	
	  	}

	  	if (regular_hrs > 0) {
	  		focusedRow.find('[data-maindiv="reg_hrs_div"]').removeClass('d-none');
	  	}  else {
	  		focusedRow.find('[data-maindiv="reg_hrs_div"]').addClass('d-none');	
	  	}

	  	if (holiday_pay > 0) {	  	
	  		focusedRow.find('[data-maindiv="holiday-pay-span-div"]').removeClass('d-none');
	  	} else {
	  		focusedRow.find('[data-maindiv="holiday-pay-span-div"]').addClass('d-none');	
	  	}

	  	if (overtime_hrs > 0) {	  	
	  		focusedRow.find('[data-maindiv="overtime-div"]').removeClass('d-none');
	  	} else {
	  		focusedRow.find('[data-maindiv="overtime-div"]').addClass('d-none');	
	  	}

	  	if (double_overtime_hrs > 0) {
	  		focusedRow.find('[data-maindiv="double-overtime-div"]').removeClass('d-none');
	  	} else {
	  		focusedRow.find('[data-maindiv="double-overtime-div"]').addClass('d-none');	
	  	}

	  	if (medical_benefits > 0) {
	  		// focusedRow.find('.medical').html(medical_benefits);	
	  		// focusedRow.find('[data-maindiv="medical-div"]').removeClass('d-none');
	  	} else {
	  		// focusedRow.find('[data-maindiv="medical-div"]').addClass('d-none');	
	  	}

	  	if (social_security > 0) {
	  		// focusedRow.find('.social-security').html(social_security);
	  		// focusedRow.find('[data-maindiv="social-security-div"]').removeClass('d-none');
	  	} else {
	  		// focusedRow.find('[data-maindiv="social-security-div"]').addClass('d-none');	
	  	}

	  	if (education_lvey > 0) {
	  		// focusedRow.find('.edu-levy').html(education_lvey.toFixed(2));	
	  		// focusedRow.find('[data-maindiv="edu-levy-div"]').removeClass('d-none');
	  	} else {
	  		// focusedRow.find('[data-maindiv="edu-levy-div"]').addClass('d-none');	
	  	}

	  	if (net_pay) {	  	
	  		focusedRow.find('[data-maindiv="net-pay-div"]').addClass('d-none'); //Changed removeClasss
	  	} else {
	  		focusedRow.find('[data-maindiv="net-pay-div"]').addClass('d-none');	
	  	}

	  	if (regular_hrs > 0 || holiday_pay > 0 
	  			|| overtime_hrs > 0 || double_overtime_hrs > 0 || net_pay > 0
	  				|| medical_benefits > 0 || social_security > 0 || education_lvey > 0 || additionalHrsEarnings > 0) {
	  		focusedRow.find('[data-maindiv="total-div"]').removeClass('d-none');	
	  	} else {
	  		focusedRow.find('[data-maindiv="total-div"]').addClass('d-none');	
	  	}

	  	focusedRow.find('.reg_hrs').html(regular_hrs * rate_per_hour); // changed
		focusedRow.find('.holiday-pay-span').html(holiday_pay);	
		focusedRow.find('.additonal-earn-span').html(additionalHrsEarnings);	
		focusedRow.find('.overtime').html(overtime_hrs);
		focusedRow.find('.double-overtime').html(double_overtime_hrs);
		focusedRow.find('.medical').html(medical_benefits);
		focusedRow.find('.social-security').html(social_security);
		focusedRow.find('.edu-levy').html(education_lvey.toFixed(2));
		focusedRow.find('.net-pay').html(net_pay);	
		focusedRow.find('.total').html(final_gross);

	  	focusedRow.find('.total-hidden').val(final_gross);	 // gross
	  	focusedRow.find('.overtime-hidden').val(overtime_hrs);	
	  	focusedRow.find('.double-overtime-hidden').val(double_overtime_hrs);	
	  	focusedRow.find('.holiday-pay-hidden').val(holiday_pay);	
	  	focusedRow.find('.medical-hidden').val(medical_benefits);	
	  	focusedRow.find('.social-security-hidden').val(social_security);	
	  	focusedRow.find('.social-security-employer-hidden').val(social_security_employer);	
	  	focusedRow.find('.net-pay-hidden').val(net_pay);	
	  	focusedRow.find('.edu-levy-hidden').val(education_lvey.toFixed(2));	

	  	// console.log(rate_per_hour, pay_type, regular_hrs, overtime_hrs, double_overtime_hrs, additionalHrsEarnings, reimbursement_hrs);

	  	var total_confimr_amt =0;
	  	$(document).find(".total").each(function(index, value){
	  		// console.log($(value).text(), 33333);
	  		if ($.isNumeric($(value).text())) {
	  			total_confimr_amt += parseFloat($(value).text());
	  		}
	  	});

	  	$(document).find('.total_amount_confirm').html(formatter.format(total_confimr_amt));	  
	}

	$(document).ready(function() {	
		$(".payroll_date_cell").on('blur', function(){
		  	var that = $(this);

		  	calc_total(that);
		});

		function calc_total(obj) {
			var focusedRow = obj.closest('tr');
			
			console.log(focusedRow);
		  	
		  	var sum = 0;
		  	focusedRow.find(".payroll_date_cell").each(function(){
		  		if ($.isNumeric(this.value)) {
		  			sum += parseFloat(this.value);
		  		}
		  	});
		  	
		  	console.log(sum);

		  	focusedRow.find('td.total').html(sum);	 
		}		

	});
</script>

@endpush