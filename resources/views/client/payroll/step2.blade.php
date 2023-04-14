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
													<tr >
														<td class="employee-name">{{ $employee->name }} <span class="badge badge-primary">{{ strtoupper($employee->employeeProfile->pay_type) }}</td>
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
																		Days Allowed | <b>{{$value->leave->leave_day??0}}</b><br>
																		Leave Balance | <b class="leave-balance-all" id="balance-{{$employee->id}}-{{$value->leave->id}}">{{$totalday}}</b><br><br>
																	</p>
																</p>
															@endforeach
															<br>
															<small class="badge badge-info">Paid Time Off:</small>
															$<small id="payoff-{{$employee->id}}">0</small>														
														</td>
													</tr>											
												</table>
									      	</td>									      
									      	<td class="col-sm-4">
												<table>
													<tr>
														<td>
															@foreach($empLeavesUnPaid as $key =>$value)
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
		}  else if (pay_type == 'biweekly') {
			paid_time_off = (((rate_per_hour * 26)/52)/40)*hrs_inputted;			
		}  else if (pay_type == 'semi-monthly') {
			paid_time_off = (((rate_per_hour * 24)/52)/40)*hrs_inputted;
		}  else if (pay_type == 'monthly') {
			paid_time_off = (((rate_per_hour * 12)/52)/40)*hrs_inputted;
			paid_time_off = leave_balance - paid_time_off;
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