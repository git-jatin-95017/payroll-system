@extends('layouts.new_layout')

@section('content')
<style>
	body{
		background:#fff;
	}
	#main-content {
    height: calc(100vh - 70px);
	min-height:100%;
}
</style>

<div class="bg-white w-100 border-radius-15 p-4 h-100">
	<div class="row">
		<div class="col-12">
			<div class="page-heading d-flex justify-content-between align-items-center gap-3 mb-3">
				<div>
					<h3>Paid Time Off</h3>
					<p class="mb-0">Track and manage your payroll here</p>
				</div>
			</div>
		</div>
	</div>
	<div class="row mb-4">
		<div class="text-end payroll-date-section mb-3">
			<p class="mb-0">
				<span class="mb-0">
					Pay Period:
				</span>
				{{ date('F dS Y', strtotime($from))}} to {{ date('F dS Y', strtotime($to))}}
			</p>
		</div>
	</div>
	<div class="row mb-5">
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
			<div class="step-container">
				<h2>3. Review and Submit</h2>
				<p class="bottom-line"></p>
			</div>
		</div>
		<div class="col-3">
			<div class="step-container">
				<h2>4. Submitted </h2>
				<p class="bottom-line"></p>
			</div>
		</div>
	</div>
	<div class="tab-content" id="myTabContent">
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
				<div>
					<form class="form-horizontal" method="POST" action="{{ route('store.Step2', ['start_date' => $from, 'end_date' => $to, 'appoval_number'=> $appoval_number]) }}" id="fom-timesheet">
						@csrf
						<div class="custom-table-run">
							<table class="table">
								<thead>
									<tr>
									  <th class="col-4" scope="col">Employees</th>
									  <th class="col-4" scope="col">Paid Time Off</th>
									  <th class="col-4" scope="col">Unpaid Time Off</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$total = 0;
									?>
									@foreach($employees as $k =>$employee)
									<?php
										// $from = date('Y-m-01'); //date('m-01-Y');
										// $to = date('Y-m-t'); //date('m-t-Y');
	
										$timeCardData = \App\Models\PayrollSheet::whereBetween('payroll_date', [$from, $to])->where('approval_status', 1)->where('appoval_number', $appoval_number)->where('emp_id', $employee->id)->get();
	
										if ($timeCardData->count() == 0) {
											continue;
										}
	
										$isDataExist = \App\Models\PayrollAmount::where('start_date', '>=', $from)->where('end_date', '<=', $to)->where('user_id', $employee->id)->first();
	
										$empLeavesPaid = \App\Models\EmpLeavePolicy::where('emp_leave_policies.user_id', $employee->id)
										->join('leave_types', function($join) {
											$join->on('leave_types.id', '=', 'emp_leave_policies.leave_type_id');
										})->where('status', 1)->get();
	
										$empLeavesUnPaid = \App\Models\EmpLeavePolicy::where('emp_leave_policies.user_id', $employee->id)
										->join('leave_types', function($join) {
											$join->on('leave_types.id', '=', 'emp_leave_policies.leave_type_id');
										})->where('status', 0)->get();
	
										$id = $isDataExist->id;
										$sick_hrs = $isDataExist->sick_hrs;
										$vacation_hrs = $isDataExist->vacation_hrs;
	
	
										$salary = 0;
										if (!empty($isDataExist)) {
											$salary = $isDataExist->gross;
											$total += $isDataExist->gross;
										}
									?>
	
									<input type="hidden" value="{{$id}}" name="input[{{$employee->id}}][id]">
									<tr class="row-tr-js">
										  <td class="col-sm-4">
											<table class="w-100">
												<tr>
													<td class="employee-name">
	
													<div class="ts-img">
														<div class="d-flex gap-3 justify-content-center align-items-center ">
															<div>
																@if(!empty($employee->employeeProfile->file))
																	<img src="/files/{{$employee->employeeProfile->file}}"
																	style="width: 40px; height: 40px; border-radius: 100em;" />
																@else
																	<img src='/img/user2-160x160.jpg' style="width: 40px; height: 40px; border-radius: 100em;">
																@endif	
															</div>
															<div class="col pe-4">
																<p class="mb-0 d-flex justify-content-between">
																	<span class="payroll-emp-name">{{ $employee->name }}</span> 
																	<span class="badge-payroll">{{ strtoupper($employee->employeeProfile->pay_type) }}</span>
																</p>
																<p class="mb-0">${{ $employee->employeeProfile->pay_rate }}</p>
															</div>	
														</div>
													</div>
													</td>
												</tr>
											</table>
										  </td>
										 <td class="col-sm-4">
											<table>
												<tr>
													<td>
														<?php
															//Last leave balance
															// $isLastPayroll = \App\Models\PayrollAmount::where('user_id',  $employee->id)
															//     ->where(function ($query) use ($from, $to) {
															//         // Exclude records where both start_date and end_date are within the specified date range
															//         $query->whereNotBetween('start_date', [$from, $to])
															//               ->whereNotBetween('end_date', [$from, $to]);
															//     })
															//     ->orderBy('id', 'DESC')
															//     ->first();
														?>
														@foreach($empLeavesPaid as $key =>$value)
															<?php
																$employeeID = $employee->id;
	
																$leaveID = $value->leave->id;
	
																$carryOverAmount = $value->leave->carry_over_amount * 8 ?? 0;
	
																$year = date('Y');
	
																$daysTaken = \App\Models\AssignLeave::where('emp_id', $employeeID)->where('type_id', $leaveID)->where('dateyear', $year)->first();
	
																// $daysTaken = $this->getEmpAssignLeaveType($employeeID, $leaveID, $year);
	
																$leavetypes = \App\Models\LeaveType::findOrFail($leaveID);
	
																if (empty($daysTaken->hour)) {
																	$daysTakenval = '0';
																} else {
																	$daysTakenval = $daysTaken->hour / 8;
																}
	
																if ($leaveID =='5') {
																	// $earnTaken = $this->leave_model->emEarnselectByLeave($employeeID);
																	$totalday = 0;//'Earned Balance: '.($earnTaken->hour / 8).' Days';
																} else {
																	//$totalday   = $leavetypes->leave_day . '/' . ($daysTaken/8);
																	$totalday = (float)$leavetypes->leave_day - (float)$daysTakenval;
																}
	
																/*
																$pastLeaveBalancer = 0;
																if (!empty($isLastPayroll)) {
																	$paidLdata = $isLastPayroll->additionalPaids()->select('amount')->where('user_id', $employeeID)->where('leave_type_id', $value->leave_type_id)->first();
	
																	$pastLeaveBalancer = (!empty($paidLdata->amount) ? $paidLdata->amount: 0);
																}
																*/
															?>
	
																<?php
																$amountPaidOff = 0;
																if (!empty($isDataExist->additionalPaids)) {
																	// $collection = collect($isDataExist->additionalPaids);
																	// dd($collection);
																	$arrTemp = $isDataExist->additionalPaids()->select('amount')->where('user_id', $employeeID)->where('leave_type_id', $value->leave_type_id)->first();
	
																	$amountPaidOff = !empty($arrTemp->amount) ? $arrTemp->amount: 0;
																}
	
																if (date('m-d') == '08-29') {
																	// $amountPaidOff  += 	$leavetypes->carry_over_amount;
																}
	
																/*
																if (empty($amountPaidOff)) {
																	if ($pastLeaveBalancer > 0) {
																		$amountPaidOff = $pastLeaveBalancer;
																	}
																}
																*/
	
																// Get balance from DB (this should be from the last CONFIRMED payroll)
																$runningBalance = \App\Models\LeaveBalance::where('user_id', $employeeID)
																	->where('leave_type_id', $value->leave_type_id)
																	->where('leave_year', date('Y', strtotime($isDataExist->start_date)))
																	->first();
																
																// Debug: Log what we're getting from DB
																\Log::info("Debug Leave Balance for Paid", [
																	'employee_id' => $employeeID,
																	'leave_type_id' => $value->leave_type_id,
																	'db_balance' => $runningBalance ? $runningBalance->balance : null,
																	'db_amount' => $runningBalance ? $runningBalance->amount : null,
																	'payroll_id' => $isDataExist->id,
																	'payroll_status' => $isDataExist->status,
																	'amountPaidOff' => $amountPaidOff
																]);
															?>
															<div class="toggle-container">
																<label class="cursor-pointer" data-bs-toggle="collapse" href="#bonus{{$employee->id}}{{$key}}" aria-expanded="false" aria-controls="bonus{{$employee->id}}{{$key}}">
																	<svg width="20px" class="align-middle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#007bff" aria-hidden="true">
																		<path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 9a.75.75 0 00-1.5 0v2.25H9a.75.75 0 000 1.5h2.25V15a.75.75 0 001.5 0v-2.25H15a.75.75 0 000-1.5h-2.25V9z" clip-rule="evenodd"></path>
																	</svg>
																	{{$value->leave->name}}
																</label>
																<div class="collapse" id="bonus{{$employee->id}}{{$key}}">
																	<input type="hidden" value="{{$value->leave_type_id}}" name="input[{{$employee->id}}][earnings][{{$key }}][leave_type_id]">
																	@php
																		// Use the running balance that was already fetched
																		$dbBalance = $runningBalance;
																		
																		// Calculate Hours Allowed
																		$baseAllowance = ($value->leave->leave_day ?? 0) * 8;
																		$totalAllowance = $baseAllowance + $carryOverAmount;
																		
																		// Determine starting balance:
																		// Get balance from DB (from last confirmed payroll)
																		// If this payroll has already been edited, ADD back the amount entered to get the starting balance
																		if ($dbBalance && $dbBalance->amount > 0) {
																			// Balance exists in DB
																			// If we have AdditionalPaid records, this payroll has been edited
																			// Add back the amount to get the original starting balance
																			if ($amountPaidOff > 0) {
																				// This payroll was previously edited: add back to get starting balance
																				$startingBalance = $dbBalance->balance + $amountPaidOff;
																			} else {
																				// No edits yet in this payroll: DB balance is the starting balance
																				$startingBalance = $dbBalance->balance;
																			}
																		} else {
																			// First payroll or no leave taken yet: use Hours Allowed
																			$startingBalance = $totalAllowance;
																		}
																		
																		// Ensure starting balance doesn't exceed total allowance
																		$startingBalance = min($startingBalance, $totalAllowance);
																	@endphp
																	<input type="hidden" id="paid-leave-balnce-{{$employee->id}}-{{$value->leave->id}}" name="input[{{$employee->id}}][earnings][{{$key }}][leave_balance]" value="{{ $dbBalance ? $dbBalance->balance : (($value->leave->leave_day * 8) + $carryOverAmount) }}">
																	<input type="hidden" id="paid-time-off-{{$employee->id}}" value="0" name="input[{{$employee->id}}][paid_time_off]">
																	<input type="text" name="input[{{$employee->id}}][earnings][{{$key }}][amount]" min="0" class="form-control db-custom-input fixed-input leave-hrs" data-leavetype="{{ $value->leave->id}}-{{$employee->id}}" data-starting-balance="{{ $startingBalance }}" data-db-balance="{{ $dbBalance ? $dbBalance->balance : $totalAllowance }}" data-max-available="{{ ($dbBalance && $dbBalance->amount > 0) ? $dbBalance->balance : $totalAllowance }}" value="{{ $amountPaidOff }}" onchange="calculateOff(this, '<?php echo $employee->id; ?>', '<?php echo $employee->employeeProfile->pay_type; ?>', '<?php echo $k; ?>', '<?php echo $employee->employeeProfile->pay_rate; ?>', '<?php echo $salary; ?>', '<?php echo $value->leave->leave_day??0; ?>', '<?php echo $value->leave->id; ?>', '<?php echo $startingBalance; ?>', '<?php echo $carryOverAmount; ?>')" onblur="handleLeaveInputBlur(this)" min=0>
																	<div class="ms-2 mt-2">
																		<p class="mb-0 d-none">Hours Allowed | <b>{{ $startingBalance }}</b>hrs</p>
																		<p class="mb-0">Leave Balance | <b class="leave-balance-all" id="balance-{{$employee->id}}-{{$value->leave->id}}" data-amount="{{ $dbBalance ? $dbBalance->amount : 0 }}" data-starting-balance="{{ $startingBalance }}">
																			@php
																				// Display running balance: DB balance minus any new entry
																				// When editing, subtract current entry from DB balance to show net effect
																				// When new payroll, DB balance is already net, so subtract current entry
																				$displayBalance = $startingBalance - $amountPaidOff;
																				echo number_format($displayBalance, 2);
																			@endphp
																		</b>hrs</p>
																		<?php
																			if (!empty($employee->employeeProfile->doj)) {
																				$todayDate = date('Y-m-d');
																				$joiningDate = $employee->employeeProfile->doj;
																				$startDaysAfter = $value->leave->start_days??0;
	
																				$modifiedDate = date('Y-m-d', strtotime($joiningDate. " + {$startDaysAfter} days"));
	
																				if ($todayDate > $modifiedDate) {
																					$statusTitle = 'Eligible';
																				} else {
																					$statusTitle = 'Ineligible';
																				}
																			} else {
																				$statusTitle = 'Ineligible';
																			}
																		?>
																		<p class=" mb-1">
																			<small>Status:</small> 
																			<strong><small class="@if($statusTitle == 'Eligible') text-success @else text-danger @endif	">{{$statusTitle}}</small></strong>
																		</p>
																	</div>
																</div>
															</div>
														@endforeach
														<div class="paid-time-off">
															<small class="badge badge-info ps-0">Paid Time Off</small>
															<small class="total" id="payoff-{{$employee->id}}">$0</small>
														</div>
													</td>
												</tr>
											</table>
										  </td>
										  <td class="col-sm-4">
											<table>
												<tr>
													<td>
														@foreach($empLeavesUnPaid as $key =>$value)
															<?php
																$employeeID = $employee->id;
	
																$leaveID = $value->leave->id;
	
																$carryOverAmount = $value->leave->carry_over_amount * 8 ?? 0;
	
																$year = date('Y');
	
																$daysTaken = \App\Models\AssignLeave::where('emp_id', $employeeID)->where('type_id', $leaveID)->where('dateyear', $year)->first();
	
																// $daysTaken = $this->getEmpAssignLeaveType($employeeID, $leaveID, $year);
	
																$leavetypes = \App\Models\LeaveType::findOrFail($leaveID);
	
																if (empty($daysTaken->hour)) {
																	$daysTakenval = '0';
																} else {
																	$daysTakenval = $daysTaken->hour / 8;
																}
	
																if ($leaveID =='5') {
																	// $earnTaken = $this->leave_model->emEarnselectByLeave($employeeID);
																	$totaldayunpaid = 0;//'Earned Balance: '.($earnTaken->hour / 8).' Days';
																} else {
																	//$totaldayunpaid   = $leavetypes->leave_day . '/' . ($daysTaken/8);
																	$totaldayunpaid = (float)$leavetypes->leave_day - (float)$daysTakenval;
																}
	
																$amountUnPaidOff = 0;
																if (!empty($isDataExist->additionalUnpaids)) {
																	// $collection = collect($isDataExist->additionalUnpaids);
																	// dd($collection);
																	$arrTemp = $isDataExist->additionalUnpaids()->select('amount')->where('user_id', $employeeID)->where('leave_type_id', $leaveID)->first();
	
																	$amountUnPaidOff = !empty($arrTemp->amount) ? $arrTemp->amount: 0;
																}
	
																$runningBalance = \App\Models\LeaveBalance::where('user_id', $employeeID)
																	->where('leave_type_id', $value->leave_type_id)
																	->where('leave_year', date('Y', strtotime($isDataExist->start_date)))
																	->first();
															?>
															<div class="toggle-container">
																<label class="cursor-pointer" data-bs-toggle="collapse" href="#unpaid{{$employee->id}}{{$key}}" aria-expanded="false" aria-controls="unpaid{{$employee->id}}{{$key}}">
																	<svg width="20px" class="align-middle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#007bff" aria-hidden="true">
																		<path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 9a.75.75 0 00-1.5 0v2.25H9a.75.75 0 000 1.5h2.25V15a.75.75 0 001.5 0v-2.25H15a.75.75 0 000-1.5h-2.25V9z" clip-rule="evenodd"></path>
																	</svg>
																	{{$value->leave->name}}
																</label>
																<div class="collapse" id="unpaid{{$employee->id}}{{$key}}">
																	<input type="hidden" value="{{$value->leave_type_id}}" name="input[{{$employee->id}}][earnings_unpaid][{{$key }}][leave_type_id_unpaid]">
																	@php
																		// Get the database balance for this employee and leave type
																		$dbBalance = \App\Models\LeaveBalance::where('user_id', $employee->id)
																			->where('leave_type_id', $value->leave_type_id)
																			->where('leave_year', date('Y', strtotime($isDataExist->start_date)))
																			->first();
																		
																		// Calculate Hours Allowed
																		$baseAllowanceUnpaid = ($value->leave->leave_day ?? 0) * 8;
																		$totalAllowanceUnpaid = $baseAllowanceUnpaid + $carryOverAmount;
																		
																		// Determine starting balance for unpaid leave:
																		// Get balance from DB (from last confirmed payroll)
																		// If this payroll has already been edited, ADD back the amount entered to get the starting balance
																		if ($dbBalance && $dbBalance->amount > 0) {
																			// Balance exists in DB
																			// If we have AdditionalUnPaid records, this payroll has been edited
																			// Add back the amount to get the original starting balance
																			if ($amountUnPaidOff > 0) {
																				// This payroll was previously edited: add back to get starting balance
																				$startingBalanceUnpaid = $dbBalance->balance + $amountUnPaidOff;
																			} else {
																				// No edits yet in this payroll: DB balance is the starting balance
																				$startingBalanceUnpaid = $dbBalance->balance;
																			}
																		} else {
																			// First payroll or no leave taken yet: use Hours Allowed
																			$startingBalanceUnpaid = $totalAllowanceUnpaid;
																		}
																		
																		// Ensure starting balance doesn't exceed total allowance
																		$startingBalanceUnpaid = min($startingBalanceUnpaid, $totalAllowanceUnpaid);
																	@endphp
																	<input type="hidden" id="unpaid-leave-balnce-{{$employee->id}}-{{$value->leave->id}}" name="input[{{$employee->id}}][earnings_unpaid][{{$key }}][leave_balance_unpaid]" value="{{ $dbBalance ? $dbBalance->balance : (($value->leave->leave_day * 8) + $carryOverAmount) }}">
	
																	<input min=0 type="number" name="input[{{$employee->id}}][earnings_unpaid][{{$key }}][amount_unpaid]" min="0" class="db-custom-input form-control fixed-input leave-hrs-unpaid" data-leavetype="{{ $value->leave->id}}-{{$employee->id}}" data-starting-balance="{{ $startingBalanceUnpaid }}" data-db-balance="{{ $dbBalance ? $dbBalance->balance : $totalAllowanceUnpaid }}" data-max-available="{{ ($dbBalance && $dbBalance->amount > 0) ? $dbBalance->balance : $totalAllowanceUnpaid }}" value="{{ $amountUnPaidOff }}"
																	onchange="calculateUnpaidOff(this, '<?php echo $employee->id; ?>', '<?php echo $employee->employeeProfile->pay_type; ?>', '<?php echo $k; ?>', '<?php echo $employee->employeeProfile->pay_rate; ?>', '<?php echo $salary; ?>', '<?php echo $value->leave->leave_day??0; ?>', '<?php echo $value->leave->id; ?>', '<?php echo $startingBalanceUnpaid; ?>', '<?php echo $carryOverAmount; ?>')"
																	onblur="handleLeaveInputBlur(this)"
																	>
																	<div class="ms-2 mt-2">
																		<p class="mb-0">
																			Hours Allowed | <b>{{ $startingBalanceUnpaid }}</b>
																		</p>
																		<p class="mb-0">
																			Leave Balance | <b class="leave-balance-all-unpaids" id="balanceunpaid-{{$employee->id}}-{{$value->leave->id}}" data-amount="{{ $dbBalance ? $dbBalance->amount : 0 }}" data-starting-balance="{{ $startingBalanceUnpaid }}">
																				@php
																					// Display running balance: DB balance minus any new entry
																					// When editing, subtract current entry from DB balance to show net effect
																					// When new payroll, DB balance is already net, so subtract current entry
																					$displayBalanceUnpaid = $startingBalanceUnpaid - $amountUnPaidOff;
																					echo number_format($displayBalanceUnpaid, 2);
																				@endphp
																			</b>hrs</p>
																	</div>
																</div>
															</div>
														@endforeach
													</td>
												</tr>
												</tr>
											</table>
										  </td>
									</tr>
									@endforeach
								</tbody>
							</table>
							<div class="confirm-container">
								<div class="text-center">
									<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"  width="60" height="60">
										<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
									</svg>
									<h3>Confirm your amounts</h3>
									<p class="text-center">To ensure accuracy, please review your payroll numbers above and make sure they’re 100% correct</p>
									<b class="total_amount_confirm">${{number_format($total, 2)}}</b>
								</div>
							</div>
						</div>
						<div class="card-footer">
							<div class="d-flex justify-content-between">
								<div>
									<a href="{{ route('store.Step1', [
										'start_date' => Request::query('start_date'),
										'end_date' => Request::query('end_date'),
										'number'=> Request::query('appoval_number')
									]) }}" class="back-btn-payroll reset_btn">
									<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16">
											<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
										</svg>
										Back
									</a>
								</div>
								<button type="submit" id="save-button" class="btn btn-primary  save_continue">Submit</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@push('page_scripts')
<script src="{{asset('js/payroljs/jquery.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/corejs-typeahead/1.2.1/bloodhound.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/corejs-typeahead/1.2.1/typeahead.jquery.min.js"></script>
<script>
	var totalConfimrAmtStep1 = @json($total);

	const formatter = new Intl.NumberFormat('en-US', {
		style: 'currency',
		currency: 'USD',
	});

	// ✅ Add the showToast function
	function showToast(type = 'success', msg = '') {
		let bgClass, textClass;
		if (type === 'success') {
			bgClass = 'bg-success';
			textClass = 'text-white';
		} else if (type === 'warning') {
			bgClass = 'bg-warning';
			textClass = 'text-dark';
		} else {
			bgClass = 'bg-danger';
			textClass = 'text-white';
		}
		
		const toast = $(`<div class='toast align-items-center ${bgClass} ${textClass} border-0 show' role='alert' aria-live='assertive' aria-atomic='true' style='position:fixed;top:20px;right:20px;z-index:9999;min-width:350px;max-width:500px;'><div class='d-flex'><div class='toast-body' style='flex:1;word-wrap:break-word;'>${msg}</div><button type='button' class='btn-close me-2 m-auto' data-bs-dismiss='toast' aria-label='Close'></button></div></div>`);
		$('body').append(toast);
		setTimeout(() => toast.fadeOut(400,()=>toast.remove()), 4000);
	}

	let initializationCompleted = false;
	
	$(document).ready(function() {
		console.log('Document ready - initializing leave balance system...');
		
		// Initialize immediately (don't wait for setTimeout to avoid timing issues)
		if (!initializationCompleted) {
			// Initialize previous data for cumulative tracking
			initializePreviousData();
			initializationCompleted = true;
		}
		
		// Add event listeners for debugging
		$('.leave-hrs').on('input', function() {
			console.log('Paid leave input changed:', $(this).val(), 'for element:', this);
		});
		
		$('.leave-hrs-unpaid').on('input', function() {
			console.log('Unpaid leave input changed:', $(this).val(), 'for element:', this);
		});
		
		// Don't trigger change events on page load - this causes double calculation
		// $('.leave-hrs').each(function() { $(this).trigger('change');})
		// $('.leave-hrs-unpaid').each(function() { $(this).trigger('change');})
		
		// Initialize hidden inputs with correct balance values
		initializeHiddenInputs();
	});
	
	// Function to initialize hidden inputs with correct balance values
	function initializeHiddenInputs() {
		console.log('Initializing hidden inputs with correct balance values...');
		
		// For paid leave
		$('.leave-hrs').each(function() {
			let leaveTypeData = $(this).attr('data-leavetype');
			let [leaveId, empId] = leaveTypeData.split('-');
			
			if (empId && leaveId) {
				let balanceSelector = `#balance-${empId}-${leaveId}`;
				let balanceElement = $(balanceSelector);
				let hiddenInputSelector = `#paid-leave-balnce-${empId}-${leaveId}`;
				let hiddenInput = $(hiddenInputSelector);
				
				if (balanceElement.length > 0 && hiddenInput.length > 0) {
					let currentBalance = parseFloat(balanceElement.html()) || 0;
					hiddenInput.val(currentBalance);
					console.log(`✅ Initialized hidden input: ${hiddenInputSelector} = ${currentBalance}`);
				}
			}
		});
		
		// For unpaid leave
		$('.leave-hrs-unpaid').each(function() {
			let leaveTypeData = $(this).attr('data-leavetype');
			let [leaveId, empId] = leaveTypeData.split('-');
			
			if (empId && leaveId) {
				let balanceSelector = `#balanceunpaid-${empId}-${leaveId}`;
				let balanceElement = $(balanceSelector);
				let hiddenInputSelector = `#unpaid-leave-balnce-${empId}-${leaveId}`;
				let hiddenInput = $(hiddenInputSelector);
				
				if (balanceElement.length > 0 && hiddenInput.length > 0) {
					let currentBalance = parseFloat(balanceElement.html()) || 0;
					hiddenInput.val(currentBalance);
					console.log(`✅ Initialized unpaid hidden input: ${hiddenInputSelector} = ${currentBalance}`);
				}
			}
		});
	}
	
	// Function to initialize previous data for cumulative leave tracking
	function initializePreviousData() {
		console.log('Initializing leave balance data...');
		
		// For paid leave
		$('.leave-hrs').each(function() {
			let currentValue = parseFloat($(this).val()) || 0; // This could be 0 or existing value from DB
			let leaveTypeData = $(this).attr('data-leavetype');
			let [leaveId, empId] = leaveTypeData.split('-');
			
			console.log('Debug - leaveTypeData:', leaveTypeData, 'leaveId:', leaveId, 'empId:', empId);
			
			if (empId && leaveId) {
				// Note: balance ID format is balance-empId-leaveId (different from data-leavetype)
				let balanceSelector = `#balance-${empId}-${leaveId}`;
				let balanceElement = $(balanceSelector);
				
				console.log('Debug - balanceElement found:', balanceElement.length > 0, 'selector:', balanceSelector);
				console.log('Debug - balanceElement HTML:', balanceElement.html());
				
				if (balanceElement.length > 0) {
					// Check if already initialized to avoid overwriting
					if (!balanceElement.data('has-previous-data')) {
						// ✅ Get starting balance from input's data attribute (this is the DB balance when payroll was opened)
						let inputElement = $(this);
						let startingBalance = parseFloat(inputElement.attr('data-starting-balance')) || 0;
						
						// ✅ CRITICAL: Use the balance that's ALREADY displayed in the HTML (server-calculated)
						// Don't recalculate it, as the server already did it correctly
						let displayBalance = parseFloat(balanceElement.html()) || 0;
						
						// Store values for JavaScript calculations
						// KEY: Store the balance at the START of this payroll (before any edits)
						balanceElement.data('payroll-start-balance', startingBalance);
						balanceElement.data('previous-balance', displayBalance);
						balanceElement.data('previous-input', currentValue);
						balanceElement.data('original-input', currentValue);
						balanceElement.data('has-previous-data', true);
						balanceElement.data('current-running-balance', displayBalance);
						
						// Store the starting balance on the input element for reset purposes
						$(this).data('payroll-start-balance', startingBalance);
						
						console.log(`✅ Initialized: Emp ${empId}, Leave ${leaveId}, Payroll Start Balance: ${startingBalance}, Display Balance: ${displayBalance}, Input: ${currentValue}`);
					} else {
						console.log(`⏭️ Already initialized: Emp ${empId}, Leave ${leaveId}`);
					}
				} else {
					console.warn(`❌ Balance element not found for selector: ${balanceSelector}`);
				}
			}
		});
		
		// For unpaid leave
		$('.leave-hrs-unpaid').each(function() {
			let currentValue = parseFloat($(this).val()) || 0; // This could be 0 or existing value from DB
			let leaveTypeData = $(this).attr('data-leavetype');
			let [leaveId, empId] = leaveTypeData.split('-');
			
			if (empId && leaveId) {
				let balanceSelector = `#balanceunpaid-${empId}-${leaveId}`;
				let balanceElement = $(balanceSelector);
				
				if (balanceElement.length > 0) {
					// Check if already initialized to avoid overwriting
					if (!balanceElement.data('has-previous-data')) {
						// ✅ Get starting balance from input's data attribute (this is the DB balance when payroll was opened)
						let inputElement = $(this);
						let startingBalance = parseFloat(inputElement.attr('data-starting-balance')) || 0;
						
						// ✅ CRITICAL: Use the balance that's ALREADY displayed in the HTML (server-calculated)
						// Don't recalculate it, as the server already did it correctly
						let displayBalance = parseFloat(balanceElement.html()) || 0;
						
						// Store values for JavaScript calculations
						// KEY: Store the balance at the START of this payroll (before any edits)
						balanceElement.data('payroll-start-balance', startingBalance);
						balanceElement.data('previous-balance', displayBalance);
						balanceElement.data('previous-input', currentValue);
						balanceElement.data('original-input', currentValue);
						balanceElement.data('has-previous-data', true);
						balanceElement.data('current-running-balance', displayBalance);
						
						// Store the starting balance on the input element for reset purposes
						$(this).data('payroll-start-balance', startingBalance);
						
						console.log(`✅ Unpaid Initialized: Emp ${empId}, Leave ${leaveId}, Payroll Start Balance: ${startingBalance}, Display Balance: ${displayBalance}, Input: ${currentValue}`);
					} else {
						console.log(`⏭️ Already initialized (unpaid): Emp ${empId}, Leave ${leaveId}`);
					}
				} else {
					console.warn(`❌ Unpaid balance element not found for selector: ${balanceSelector}`);
				}
			}
		});
	}

	function calculateOff(obj, emp_id, pay_type, row_key, rate_per_hour, salary, leave_day_terms, leave_id, initial_balance, carry_over_amount) {
		console.log('calculateOff called with:', obj.value, 'emp_id:', emp_id, 'leave_id:', leave_id);

		let current_enter_val = parseFloat(obj.value) || 0;
		
		// Debug: Log the current state
		console.log(`📝 Current Input Value: ${current_enter_val}`);
		
		// Get the focused row first
		var focusedRow = $(obj).closest('.row-tr-js');
		
		// Get the balance element for this specific leave type
		let balanceSelector = `#balance-${emp_id}-${leave_id}`;
		let balanceElement = focusedRow.find(balanceSelector);
		
		console.log('Debug - balanceSelector:', balanceSelector);
		console.log('Debug - balanceElement found:', balanceElement.length > 0);
		console.log('Debug - balanceElement HTML before:', balanceElement.html());
		
		// Safety check - if element not found, use initial balance
		if (balanceElement.length === 0) {
			console.warn(`❌ Balance element not found for emp_id: ${emp_id}, leave_id: ${leave_id}`);
			return;
		}
		
		// ✅ CRITICAL: Get the balance at the START of this payroll (before any edits)
		// This is what was loaded when the payroll was opened
		let payrollStartBalance = $(obj).data('payroll-start-balance') || parseFloat($(obj).attr('data-starting-balance')) || parseFloat(initial_balance);
		
		console.log(`🔍 Debug - payrollStartBalance from data: ${$(obj).data('payroll-start-balance')}`);
		console.log(`🔍 Debug - data-starting-balance attr: ${$(obj).attr('data-starting-balance')}`);
		console.log(`🔍 Debug - initial_balance param: ${initial_balance}`);
		console.log(`📊 Final payrollStartBalance: ${payrollStartBalance}`);
		
		// ✅ Get max available balance (for validation - what's actually available to use)
		let maxAvailable = parseFloat($(obj).attr('data-max-available')) || payrollStartBalance;
		
		// Validate against max available (prevent taking more than what's actually available)
		if (current_enter_val > maxAvailable) {
			// Clear the input and show warning
			obj.value = '';
			showToast('warning', `Cannot take ${current_enter_val}hrs! Available balance is only ${maxAvailable.toFixed(2)}hrs.`);
			console.log('🚫 Input blocked: Exceeds available balance');
			return;
		}
		
		// ✅ Calculate balance: Payroll Start Balance - current input
		// This ensures we always calculate from the balance at the start of this payroll
		let newBalance = payrollStartBalance - current_enter_val;
		
		// Prevent negative balance
		newBalance = Math.max(0, newBalance);
		
		// Update the balance display
		balanceElement.html(newBalance.toFixed(2));
		
		console.log(`✅ NEW Calculation: Payroll Start Balance: ${payrollStartBalance}, Input: ${current_enter_val}, New Balance: ${newBalance}`);
		console.log('Debug - balanceElement HTML after:', balanceElement.html());
		
		// Update the hidden input for form submission
		focusedRow.find(`[id="paid-leave-balnce-${emp_id}-${leave_id}"]`).val(newBalance);

		let entered_leave_hrs = 0;

		focusedRow.find(".leave-hrs").each(function() {
	  		if ($.isNumeric(this.value)) {
	  			entered_leave_hrs += parseFloat(this.value);
	  		}
	  	});

		let hrs_inputted = entered_leave_hrs;

		let paid_time_off = 0;

		if (pay_type == 'hourly') {
			paid_time_off = rate_per_hour *  hrs_inputted;
		}  else if (pay_type == 'weekly') {
			paid_time_off = (rate_per_hour / 40) *  hrs_inputted;
		}  else if (pay_type == 'bi-weekly') {
			paid_time_off = (((rate_per_hour * 26)/52)/40)*hrs_inputted;
		}  else if (pay_type == 'semi-monthly') {
			paid_time_off = (((rate_per_hour * 24)/52)/40)*hrs_inputted;
		}  else if (pay_type == 'monthly') {
			paid_time_off = (((rate_per_hour * 12)/52)/40)*hrs_inputted;
			// paid_time_off = leave_balance - paid_time_off;
		}

		focusedRow.find(`[id="payoff-${emp_id}"]`).html(paid_time_off.toFixed(2));
		focusedRow.find(`[id="paid-time-off-${emp_id}"]`).val(paid_time_off.toFixed(2));

		// Calculate total balance for all leave types
		let total_balance = 0;
		focusedRow.find(".leave-balance-all").each(function() {
	  		if ($.isNumeric($(this).html())) {
	  			total_balance += parseFloat($(this).html());
	  		}
	  	});

		focusedRow.find(`[id ="last-row-${emp_id}"]`).val(total_balance);

		var total_confimr_amt =0;
	  	$(document).find(".total").each(function(index, value){
	  		// console.log($(value).text(), 33333);
	  		if ($.isNumeric($(value).text())) {
	  			total_confimr_amt += parseFloat($(value).text());
	  		}
	  	});

	  			$(document).find('.total_amount_confirm').html('$' + (total_confimr_amt + Number(totalConfimrAmtStep1)).toFixed(2));
	}

	// Function to handle when leave input goes blank
	function handleLeaveInputBlur(obj) {
		let currentValue = parseFloat(obj.value) || 0;
		let leaveTypeData = $(obj).attr('data-leavetype');
		let [leaveId, empId] = leaveTypeData.split('-');
		
		console.log('handleLeaveInputBlur called:', currentValue, 'leaveId:', leaveId, 'empId:', empId);
		
		if (empId && leaveId) {
			// Check if this is a paid or unpaid leave input
			let isUnpaid = $(obj).hasClass('leave-hrs-unpaid');
			let balanceSelector = isUnpaid ? `#balanceunpaid-${empId}-${leaveId}` : `#balance-${empId}-${leaveId}`;
			let balanceElement = $(balanceSelector);
			
			if (balanceElement.length > 0) {
				// If input is blank (0) or empty string, restore to the payroll start balance
				if (currentValue === 0 || obj.value === '') {
					// ✅ CRITICAL: Restore to the balance at the START of this payroll (before any edits)
					// This is the balance that was loaded when the payroll was first opened
					let restoreValue = parseFloat($(obj).data('payroll-start-balance')) || parseFloat($(obj).attr('data-starting-balance')) || 0;
					balanceElement.html(restoreValue.toFixed(2));
					
					// Update the hidden input for form submission
					let focusedRow = $(obj).closest('.row-tr-js');
					if (isUnpaid) {
						focusedRow.find(`[id="unpaid-leave-balnce-${empId}-${leaveId}"]`).val(restoreValue);
					} else {
						focusedRow.find(`[id="paid-leave-balnce-${empId}-${leaveId}"]`).val(restoreValue);
					}
					
					// Update the stored balance data
					balanceElement.data('previous-balance', restoreValue);
					
					console.log(`✅ Input cleared - restored to payroll start balance: ${restoreValue} for ${isUnpaid ? 'unpaid' : 'paid'} leave`);
				}
			}
		}
	}

	// Function to calculate unpaid time off
	function calculateUnpaidOff(obj, emp_id, pay_type, row_key, rate_per_hour, salary, leave_day_terms, leave_id, initial_balance, carry_ovr_amnt) {
		console.log('calculateUnpaidOff called with:', obj.value, 'emp_id:', emp_id, 'leave_id:', leave_id);

		let current_enter_val = parseFloat(obj.value) || 0;
		
		// Get the focused row first
		var focusedRow = $(obj).closest('.row-tr-js');

		// Get the balance element for this specific leave type
		let balanceSelector = `#balanceunpaid-${emp_id}-${leave_id}`;
		let balanceElement = focusedRow.find(balanceSelector);
		
		console.log('Debug - unpaid balanceSelector:', balanceSelector);
		console.log('Debug - unpaid balanceElement found:', balanceElement.length > 0);
		console.log('Debug - unpaid balanceElement HTML before:', balanceElement.html());
		
		// Safety check - if element not found, use initial balance
		if (balanceElement.length === 0) {
			console.warn(`❌ Unpaid balance element not found for emp_id: ${emp_id}, leave_id: ${leave_id}`);
			return;
		}
		
		// ✅ CRITICAL: Get the balance at the START of this payroll (before any edits)
		// This is what was loaded when the payroll was opened
		let payrollStartBalance = $(obj).data('payroll-start-balance') || parseFloat($(obj).attr('data-starting-balance')) || parseFloat(initial_balance);
		
		// ✅ Get max available balance (for validation - what's actually available to use)
		let maxAvailable = parseFloat($(obj).attr('data-max-available')) || payrollStartBalance;
		
		// Validate against max available (prevent taking more than what's actually available)
		if (current_enter_val > maxAvailable) {
			// Clear the input and show warning
			obj.value = '';
			showToast('warning', `Cannot take ${current_enter_val}hrs! Available balance is only ${maxAvailable.toFixed(2)}hrs.`);
			console.log('🚫 Unpaid input blocked: Exceeds available balance');
			return;
		}
		
		// ✅ Calculate balance: Payroll Start Balance - current input
		// This ensures we always calculate from the balance at the start of this payroll
		let newBalance = payrollStartBalance - current_enter_val;
		
		// Prevent negative balance
		newBalance = Math.max(0, newBalance);
		
		// Update the balance display
		balanceElement.html(newBalance.toFixed(2));
		
		console.log(`✅ NEW Unpaid Calculation: Payroll Start Balance: ${payrollStartBalance}, Input: ${current_enter_val}, New Balance: ${newBalance}`);
		console.log('Debug - unpaid balanceElement HTML after:', balanceElement.html());
		
		// Update the hidden input for form submission
		focusedRow.find(`[id="unpaid-leave-balnce-${emp_id}-${leave_id}"]`).val(newBalance);
	}
</script>

<script>
	function handle(e){
        if(e.keyCode == 13) {
            e.preventDefault(); // Ensure it is only this code that runs
            $('#fom-timesheet').submit();
            // alert("Enter was pressed was presses");
        }
    }

	let elmSelect = document.getElementById('myFancyDropdown');

	if (!!elmSelect) {
	    elmSelect.addEventListener('change', e => {
	        let choice = e.target.value;
	        if (!choice) return;

	        let url = new URL(window.location.href);
	        url.searchParams.set('week_search', choice);
	        // console.log(url);
	        window.location.href = url; // reloads the page
	    });
	}

    $(document).ready(function() {
        $(".payroll_date_cell").blur(function() {
        	if ($(this).val() != '' || $(this).val() != null) {
	            $.ajax({
	                url: "{{ route('payroll.store') }}",
	                type: 'POST',
	                data: {_token: "{{ csrf_token() }}", emp_id: $(this).data('empid'), payroll_date: $(this).data('date'), daily_hrs: $(this).val() },
	                dataType: 'JSON',
	                success: function (data) {
	                    // alert('Record Saved Successfully.');
	                }
	            });
        	}
        });
   });
</script>
<script type="text/javascript">
$(document).ready(function(){
    $('#select_all').on('click',function(){
        if(this.checked){
            $('.checkbox').each(function(){
                this.checked = true;
            });
        }else{
             $('.checkbox').each(function(){
                this.checked = false;
            });
        }
    });

    $('.checkbox').on('click',function(){
        if($('.checkbox:checked').length == $('.checkbox').length){
            $('#select_all').prop('checked',true);
        }else{
            $('#select_all').prop('checked',false);
        }
    });
});
</script>
<script>
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
<script type="text/javascript">
	var route = "{{ route('search.autocomplete') }}";

	var states = new Bloodhound({
		datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		// sufficient: 5,
		prefetch: {
	        url:route,
	        transform: function (data) {          // we modify the prefetch response
	            var newData = [];                 // here to match the response format
	            data.forEach(function (item) {    // of the remote endpoint
	                newData.push({
	                	'name': item
	                });
	            });
	            return newData;
	        }
	    },
		remote: {
			url: route + '?codes=%QUERY',
			wildcard: '%QUERY' // %QUERY will be replace by users input in
		},
	});

	states.initialize();

	$('#the-basics .typeahead').typeahead({
		hint: true,
		highlight: true,
		minLength: 1,
		source: function (term, process) {

			return $.get(route, {
				term: term
			}, function (data) {
				console.log(process(data),2222);
				return process(data);
			});
		},
	}, {
		name: 'states',
		display: 'short_name',
		source: states.ttAdapter(),
		// limit: 5,
		templates: {
			// pending: function (query) {
			// 	return '<div>Loading...</div>';
			// },
			// empty: [
			// 	''
			// ].join('\n'),
			header: '<h3 class="league-name">Select Leaves</h3>',
			suggestion: function (data) {
				return `<div class="man-section">
					<p>${data.full_name}</p>
				</div>`;
			}
		}

	}).on('typeahead:selected', function(event, selection) {
	  	// the second argument has the info you want
	  	console.log(selection.short_name);
	  	let res = selection.short_name;
	  	// clearing the selection requires a typeahead method
	  	// $(this).typeahead('setQuery', '');

        $.ajax({
            url: "{{ route('payroll.store') }}",
            type: 'POST',
            data: {_token: "{{ csrf_token() }}", emp_id: $(this).data('empid'), payroll_date: $(this).data('date'), daily_hrs: res },
            dataType: 'JSON',
            success: function (data) {
                // alert('Record Saved Successfully.');
            }
        });
	});
</script>

@endpush