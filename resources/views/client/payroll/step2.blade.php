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

											$id = $isDataExist->id;
											$sick_hrs = $isDataExist->sick_hrs;
											$vacation_hrs = $isDataExist->vacation_hrs;
											

											$salary = 0;
											if (!empty($isDataExist)) {
												$salary = $isDataExist->gross;
											}
										?>
									    <tr>									      
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
															<button class="btn-none"  data-toggle="collapse" href="#eraning{{$k}}" role="button" aria-expanded="false" aria-controls="eraning{{$k}}">
																<svg width="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#007bff" aria-hidden="true">
																	<path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 9a.75.75 0 00-1.5 0v2.25H9a.75.75 0 000 1.5h2.25V15a.75.75 0 001.5 0v-2.25H15a.75.75 0 000-1.5h-2.25V9z" clip-rule="evenodd"></path>
																</svg>
															</button>
															<div class="collapse" id="eraning{{$k}}">
																<input type="hidden" value="{{$id}}" name="input[{{$employee->id}}][id]">
																  <input type="hidden" value="{{$from}}" name="input[{{$employee->id}}][start_date]">
																  <input type="hidden" value="{{$to}}" name="input[{{$employee->id}}][end_date]">							      	
																Vacation Hours
																<div class="input-group group-left">
																	<input type="number" name="input[{{$employee->id}}][vacation_hrs]" min="0" value="{{ $vacation_hrs }}" class="form-control fixed-input" onchange="calculateOff(this, '<?php echo $employee->id; ?>', '<?php echo $employee->employeeProfile->pay_type; ?>', '<?php echo $k; ?>', '<?php echo $employee->employeeProfile->pay_rate; ?>', '<?php echo $salary; ?>')">
																</div>
																<hr>
																Paid sick day:<input type="number" name="input[{{$employee->id}}][paid_sick_days]" min="0" class="form-control input-sm" value="0" readonly>
																Hourly Vacation:<input type="number" name="input[{{$employee->id}}][hourly_vacation]" min="0" class="form-control input-sm" value="0" readonly>
																Salaried Vacation Monthly:
																<input type="number" name="input[{{$employee->id}}][vac_monthly]" min="0" class="form-control input-sm" value="0" readonly>
																Salaried Vacation Semi-Monthly:
																<input type="number" name="input[{{$employee->id}}][vac_semi_monthly]" min="0" class="form-control input-sm" value="0" readonly>
																Salaried Vacation Bi-weekly:
																<input type="number" name="input[{{$employee->id}}][vac_biweekly]" min="0" class="form-control input-sm" value="0" readonly>
																Weekly vacation:
																<input type="number" name="input[{{$employee->id}}][vac_weekly]" min="0" class="form-control input-sm" value="0" readonly>
															</div>
														</td>
													</tr>											
												</table>
									      	</td>									      
									      	<td class="col-sm-4">
												<table>
													<tr>
														<td>
															<button class="btn-none"  data-toggle="collapse" href="#emp{{$k}}" role="button" aria-expanded="false" aria-controls="emp{{$k}}">
																<svg width="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#007bff" aria-hidden="true">
																	<path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 9a.75.75 0 00-1.5 0v2.25H9a.75.75 0 000 1.5h2.25V15a.75.75 0 001.5 0v-2.25H15a.75.75 0 000-1.5h-2.25V9z" clip-rule="evenodd"></path>
																</svg>
															</button>
															<div class="collapse" id="emp{{$k}}">
																	Unpaid Sick day:
																<div class="input-group group-left">																	
																	<input type="number" name="input[{{$employee->id}}][sick_hrs]" min="0" value="{{ $sick_hrs }}" class="form-control fixed-input	">
																</div><br>
																	Unpaid Vacation:
																<div class="input-group group-left">																	
																	<input type="number" name="input[{{$employee->id}}][sick_hrs]" min="0" value="{{ $sick_hrs }}" class="form-control fixed-input">
																</div>
															</div>															
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

	function calculateOff(obj, emp_id, pay_type, row_key, rate_per_hour, salary) {
		console.log(obj.value, emp_id, pay_type, row_key, rate_per_hour, salary);
		let pack_sick_days  = rate_per_hour *  obj.value;
		let hourly_vacation  = 0;
		let vac_monthly = 0;
		let vac_semi_monthly  = 0;
		let vac_biweekly = 0;
		let vac_weekly = 0;

		$(`[name="input[${emp_id}][paid_sick_days]"]`).val(pack_sick_days);

		if (pay_type == 'hourly') {
			hourly_vacation  = rate_per_hour *  obj.value
			vac_monthly = salary * 40 *  obj.value;			
			vac_semi_monthly = salary * 40 *  obj.value;	
			vac_biweekly = salary * 40 *  obj.value;
			vac_weekly = 0;
			
		}  else if (pay_type == 'weekly') {
			hourly_vacation = 0;
			vac_monthly = salary * 52 *  obj.value;
			vac_semi_monthly = salary * 52 *  obj.value;	
			vac_biweekly = salary * 52 *  obj.value;;
			vac_weekly = salary * 40 *  obj.value;
		}  else if (pay_type == 'biweekly') {
			hourly_vacation = 0;
			vac_monthly  = salary * 52 *  obj.value;
			vac_semi_monthly  = 0;
			vac_biweekly = 0;
			vac_weekly = 0;
		}  else if (pay_type == 'semi-monthly') {
			hourly_vacation = 0;
			vac_monthly  = salary * 40 *  obj.value;
			vac_semi_monthly = salary * 52 *  obj.value;
			vac_biweekly = 0;
			vac_weekly = 0;
		}  else if (pay_type == 'monthly') {
			hourly_vacation = 0;
			vac_monthly  = salary * 12 *  obj.value;
			vac_semi_monthly  =  salary * 24 *  obj.value;
			vac_biweekly = salary * 26 *  obj.value;;
			vac_weekly = 0;
		}

		$(`[name="input[${emp_id}][hourly_vacation]"]`).val(hourly_vacation);
		$(`[name="input[${emp_id}][vac_monthly]"]`).val(vac_monthly);
		$(`[name="input[${emp_id}][vac_semi_monthly]"]`).val(vac_semi_monthly);
		$(`[name="input[${emp_id}][vac_biweekly]"]`).val(vac_biweekly);
		$(`[name="input[${emp_id}][vac_weekly]"]`).val(vac_weekly);
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