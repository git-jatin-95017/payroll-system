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
							<h3>Payroll is being processed from {{ date('F dS Y', strtotime($from))}} to {{ date('F dS Y', strtotime($to))}}</h3>
						</div>							
						<form class="form-horizontal" method="POST" action="{{ route('store.Step2') }}" id="fom-timesheet">
							@csrf
							<div class="card-body">
								<table class="table custom-table-run">
									<thead>
									    <tr>
									      <th class="col-4" scope="col">Employees ({{$employees->count()}})</th>
									      <th class="col-4" scope="col">Paid Time Off</th>
									      <th class="col-4" scope="col">Unpaid Time Off</th>									      								
									    </tr>
									</thead>
									<tbody>
										@foreach($employees as $k =>$employee)
										<?php
											// $from = date('Y-m-01'); //date('m-01-Y');
											// $to = date('Y-m-t'); //date('m-t-Y');

											$timeCardData = \App\Models\PayrollSheet::whereBetween('payroll_date', [$from, $to])->where('approval_status', 1)->where('emp_id', $employee->id)->get();										

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
											}
										?>
									    <tr class="row-tr-js">									      
									      	<td class="col-sm-4">
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
														<td>${{ $employee->employeeProfile->pay_rate }}</td>
													</tr>
												</table>
									      	</td>
									     	<td class="col-sm-4">
												<table>
													<tr>
														<td>
															@foreach($empLeavesPaid as $key =>$value)
																<?php
																	$employeeID = $employee->id;

														            $leaveID = $value->leave->id;

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
																?>
																<p>
																	<label class="cursor-pointer" data-toggle="collapse" href="#bonus{{$employee->id}}{{$key}}" role="button" aria-expanded="false" aria-controls="bonus{{$employee->id}}{{$key}}">
																		{{$value->leave->name}} 
																		<svg width="20px" class="align-middle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#007bff" aria-hidden="true">
																			<path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 9a.75.75 0 00-1.5 0v2.25H9a.75.75 0 000 1.5h2.25V15a.75.75 0 001.5 0v-2.25H15a.75.75 0 000-1.5h-2.25V9z" clip-rule="evenodd"></path>
																		</svg>
																	</label>
																	<p class="collapse" id="bonus{{$employee->id}}{{$key}}">
																		<input type="hidden" value="{{$value->leave_type_id}}" name="input[{{$employee->id}}][earnings][{{$key }}][leave_type_id]">
																		<input type="number" name="input[{{$employee->id}}][earnings][{{$key }}][amount]" min="0" class="form-control fixed-input leave-hrs" data-leavetype="{{ $value->leave->id}}-{{$employee->id}}" onchange="calculateOff(this, '<?php echo $employee->id; ?>', '<?php echo $employee->employeeProfile->pay_type; ?>', '<?php echo $k; ?>', '<?php echo $employee->employeeProfile->pay_rate; ?>', '<?php echo $salary; ?>', '<?php echo $value->leave->leave_day??0; ?>', '<?php echo $value->leave->id; ?>', '<?php echo $totalday; ?>')">
																		<br>
																		Hours Allowed | <b>{{ !empty($value->leave->leave_day) ? ($value->leave->leave_day * 8 ) : 0}}</b><br>
																		Leave Balance | <b class="leave-balance-all" id="balance-{{$employee->id}}-{{$value->leave->id}}">{{$totalday}}</b><br><br>
																	</p>
																</p>
															@endforeach
															<br>
															<small class="badge badge-info">Paid Time Off:</small>
															$<small class="total" id="payoff-{{$employee->id}}">0</small>														
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
																?>
																<p>
																	<label class="cursor-pointer" data-toggle="collapse" href="#unpaid{{$employee->id}}{{$key}}" role="button" aria-expanded="false" aria-controls="unpaid{{$employee->id}}{{$key}}">
																		{{$value->leave->name}} 
																		<svg width="20px" class="align-middle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#007bff" aria-hidden="true">
																			<path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 9a.75.75 0 00-1.5 0v2.25H9a.75.75 0 000 1.5h2.25V15a.75.75 0 001.5 0v-2.25H15a.75.75 0 000-1.5h-2.25V9z" clip-rule="evenodd"></path>
																		</svg>
																	</label>
																	<p class="collapse" id="unpaid{{$employee->id}}{{$key}}">
																		<input type="hidden" value="{{$value->leave_type_id}}" name="input[{{$employee->id}}][earnings][{{$key }}][leave_type_id_unpaid]">
																		<input type="number" name="input[{{$employee->id}}][earnings][{{$key }}][amount_unpaid]" min="0" class="form-control fixed-input">
																		<br>
																		Hours Allowed | <b>{{ !empty($value->leave->leave_day) ? ($value->leave->leave_day * 8 ) : 0}}</b><br>
																		Leave Balance | <b>{{$totaldayunpaid}}</b><br><br>																
																	</p>
																</p>
															@endforeach
															<!-- <p>
																<label class="cursor-pointer">
																	Total Leave Balance
																</label>
																<p>
																	<input type="number" name="input[{{$employee->id}}][earnings][{{$key }}][amount]" min="0" class="form-control fixed-input hrs" id="last-row-{{$employee->id}}">
																</p>										      	
															</p> -->
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
										<svg xmlns="http://www.w3.org/2000/svg" width="32" viewBox="0 0 48 48"><g data-name="Layer 1 copy">
											<rect x="7.859" y="10.844" fill="#569f7d" transform="rotate(-10.943 26.58 21.563)"/><rect width="37.447" height="21.434" x="4.372" y="15.125" fill="#60b18b" transform="rotate(-5.938 23.096 25.837)"/><rect width="37.447" height="21.434" x="1" y="19.081" fill="#8ed8b5"/><path fill="#b1dfbc" d="M35,34.478A3.007,3.007,0,0,0,32,37.485H7.453a3.007,3.007,0,0,0-3.008-3.007V25.119a3.008,3.008,0,0,0,3.008-3.008H32A3.007,3.007,0,0,0,35,25.119Z"/><circle cx="19.724" cy="29.798" r="4.512" fill="#8ed8b5"/><polygon fill="#60b18b" points="41.346 9.771 45.046 28.907 47 28.529 42.931 7.485 6.165 14.594 6.534 16.502 41.346 9.771"/><polygon fill="#8ed8b5" points="39.025 15.532 41.024 34.752 42.827 34.565 40.61 13.246 3.364 17.12 3.582 19.219 39.025 15.532"/><polygon fill="#b1dfbc" points="1 19.081 1 21.111 36.4 21.111 36.4 40.515 38.447 40.515 38.447 19.081 1 19.081"/><path fill="#8ed8b5" d="M5.268 24.99a2.987 2.987 0 0 1-.823.129v1.214A3 3 0 0 0 5.268 24.99zM30.748 37.485H32a2.981 2.981 0 0 1 .136-.849A2.988 2.988 0 0 0 30.748 37.485z"/><path fill="#c1ecd0" d="M32,22.111H7.453a2.989,2.989,0,0,1-.8,2.03h23.3a3.007,3.007,0,0,0,3.008,3.007v8.145A2.988,2.988,0,0,1,35,34.478V25.119A3.007,3.007,0,0,1,32,22.111Z"/><path fill="#b1dfbc" d="M19.724,25.286a4.5,4.5,0,0,0-4.019,2.5,4.456,4.456,0,0,1,1.971-.472,4.512,4.512,0,0,1,4.512,4.512,4.459,4.459,0,0,1-.493,2.01,4.5,4.5,0,0,0-1.971-8.552Z"/></g><g data-name="Layer 1 copy 2"><path fill="#1c1c1b" d="M38.447,41.515H1a1,1,0,0,1-1-1V19.081a1,1,0,0,1,1-1H38.447a1,1,0,0,1,1,1V40.515A1,1,0,0,1,38.447,41.515ZM2,39.515H37.447V20.081H2Z"/><path fill="#1c1c1b" d="M32,38.485H7.453a1,1,0,0,1-1-1,2.01,2.01,0,0,0-2.008-2.007,1,1,0,0,1-1-1V25.119a1,1,0,0,1,1-1,2.011,2.011,0,0,0,2.008-2.008,1,1,0,0,1,1-1H32a1,1,0,0,1,1,1A2.01,2.01,0,0,0,35,24.119a1,1,0,0,1,1,1v9.359a1,1,0,0,1-1,1,2.009,2.009,0,0,0-2.007,2.007A1,1,0,0,1,32,38.485Zm-23.669-2h22.8A4.025,4.025,0,0,1,34,33.6V25.992a4.023,4.023,0,0,1-2.881-2.881H8.326a4.021,4.021,0,0,1-2.881,2.881V33.6A4.023,4.023,0,0,1,8.326,36.485Z"/><path fill="#1c1c1b" d="M19.724,35.31A5.512,5.512,0,1,1,25.235,29.8,5.519,5.519,0,0,1,19.724,35.31Zm0-9.023A3.512,3.512,0,1,0,23.235,29.8,3.515,3.515,0,0,0,19.724,26.287Z"/><path fill="#1c1c1b" d="M40.819,35.788a1,1,0,0,1-.1-1.995l1.154-.121-2.03-19.327L7.65,17.725a1,1,0,0,1-.209-1.99l33.184-3.484a1,1,0,0,1,1.1.89l2.239,21.317a1,1,0,0,1-.89,1.1l-2.149.226C40.889,35.786,40.854,35.788,40.819,35.788Z"/><path fill="#1c1c1b" d="M45.367,29.848a1,1,0,0,1-.188-1.982l.651-.125L42.168,8.655,10.827,14.667A1,1,0,1,1,10.45,12.7L42.774,6.5a1,1,0,0,1,1.17.793l4.038,21.051a1,1,0,0,1-.794,1.17l-1.632.313A1.024,1.024,0,0,1,45.367,29.848Z"/></g>
										</svg>
										<h3>Confirm your amounts</h3>
										<p class="text-center">To ensure accuracy, please review your payroll numbers above and make sure they’re 100% correct</p>
										$<span class="total_amount_confirm">0.00</span>
									</div>
								</div>
							</div>
							<div class="card-footer">
								<div class="d-flex justify-content-center">
									<button type="submit" id="save-button" class="btn btn-primary text-uppercase save_continue">Submit</button>
									<a href="{{ route('store.Step1') }}" class="btn btn-info text-uppercase ml-2 reset_btn">Back</a>
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

	function calculateOff(obj, emp_id, pay_type, row_key, rate_per_hour, salary, leave_day_terms, leave_id, leave_balance) {
		// console.log(obj.value, emp_id, pay_type, row_key, rate_per_hour, salary);
		
		let initial_enter_val = obj.value;

		let final_balance = leave_balance - initial_enter_val;

		var focusedRow = $(obj).closest('.row-tr-js');

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
			paid_time_off = rate_per_hour *  hrs_inputted;
		}  else if (pay_type == 'bi-weekly') {
			paid_time_off = (((rate_per_hour * 26)/52)/40)*hrs_inputted;			
		}  else if (pay_type == 'semi-monthly') {
			paid_time_off = (((rate_per_hour * 24)/52)/40)*hrs_inputted;
		}  else if (pay_type == 'monthly') {
			paid_time_off = (((rate_per_hour * 12)/52)/40)*hrs_inputted;
			// paid_time_off = leave_balance - paid_time_off;
		}

		focusedRow.find(`[id="payoff-${emp_id}"]`).html(paid_time_off.toFixed(3));
		focusedRow.find(`[id="balance-${emp_id}-${leave_id}"]`).html(final_balance);

		total_balance = 0;
		focusedRow.find(".leave-balance-all").each(function() {	 
		console.log(paid_time_off); 		
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

	  	$(document).find('.total_amount_confirm').html(total_confimr_amt);	  
	}

  $("#approve-button").click(function(e) {
    e.preventDefault();

    var form = $("#fom-timesheet");

    form.prop("method", 'POST');
    form.prop("action", $(this).data("url"));
    form.submit();
  });
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