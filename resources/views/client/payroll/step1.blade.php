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
							<p>if you run payroll by <span>2.30(PST)</span> on <span>01/02/2023</span></p>
							<p>Your employees will paid on</p>
							<h3>05/02/2023</h3>
						</div>					
						<form class="form-horizontal" method="POST" action="{{ route('store.Step1') }}" id="fom-timesheet">
							@csrf
							<div class="card-body">
								<table class="table custom-table-run">
									<thead>
									    <tr>
									      <th scope="col">Employees (3)</th>
									      <th scope="col">Hours Worked (hrs.)</th>
									      <th scope="col">Additional Earnings</th>
									      <th scope="col">Gross Pay</th>
									    </tr>
									</thead>
									<tbody>
										@foreach($employees as $k =>$employee)
										<?php
											// $from = date('Y-m-01'); //date('m-01-Y');
											// $to = date('Y-m-t'); //date('m-t-Y');

											$timeCardData = \App\Models\PayrollSheet::whereBetween('payroll_date', [$from, $to])->where('approval_status', 1)->where('emp_id', $employee->id)->get();										

											$isDataExist = \App\Models\PayrollAmount::where('start_date', '>=', $from)->where('end_date', '<=', $to)->where('user_id', $employee->id)->first();

											if(!empty($isDataExist)) {
												$id = $isDataExist->id;
												$totalHours = $isDataExist->total_hours;
												$reimbursement = $isDataExist->reimbursement;
											} else {
												$id = NULL;
												$totalHours = collect($timeCardData)->sum('daily_hrs');
												$reimbursement = 0;
											}
										?>
									    <tr>									      
									      	<td class="col-sm-3">
												<table>
													<tr>
														<td>{{ $employee->name }}</td>
													</tr>
													<tr>
														<td>
															${{ $employee->employeeProfile->pay_rate }}
														</td>
													</tr>
													<tr>
														<td>/ Add Personal Note</td>
													</tr>
												</table>
									      	</td>
									      	<td class="col-sm-3">
									      		<table>
													<tr>
														<td>
															<input type="hidden" value="{{$id}}" name="input[{{$employee->id}}][id]">
									      					<input type="hidden" value="{{$from}}" name="input[{{$employee->id}}][start_date]">
									      					<input type="hidden" value="{{$to}}" name="input[{{$employee->id}}][end_date]">
									      					<input type="number" name="input[{{$employee->id}}][working_hrs]" min="0" value="{{ $totalHours }}" class="form-control fixed-input ">
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
															<?php
														if (!empty($isDataExist->additionalEarnings)) {
															foreach($isDataExist->additionalEarnings as $k=> $v) {
													?>									      				
															<p>
																<label>{{$v->payhead->name}}</label>									      	
																<input type="hidden" value="{{$id}}" name="input[{{$employee->id}}][earnings][{{$k }}][id]">
																<input type="hidden" value="{{$v->payhead->id}}" name="input[{{$employee->id}}][earnings][{{$k }}][payhead_id]">
																<input type="number" value="{{$v->amount}}" name="input[{{$employee->id}}][earnings][{{$k }}][amount]" min="0" class="form-control fixed-input">
															</p>
													<?php
															}
														} else {
													?>
															@foreach($employee->payheads as $key =>$value)
																<p>
																	<label>{{$value->payhead->name}} </label>										      	
																	<input type="hidden" value="{{$id}}" name="input[{{$employee->id}}][earnings][{{$key }}][id]">
																	<input type="hidden" value="{{$value->payhead_id}}" name="input[{{$employee->id}}][earnings][{{$key }}][payhead_id]">
																	<input type="number" name="input[{{$employee->id}}][earnings][{{$key }}][amount]" min="0" class="form-control fixed-input">
																</p>
															@endforeach
													<?php
														}
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
													<td>$3680.589</td>
												</tr>
												<tr>
													
													<td>
														<label>Reimbursement</label>
														<input type="number" name="input[{{$employee->id}}][reimbursement]" value="{{$reimbursement}}" min="0" class="form-control fixed-input">
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
										<span>29,525.65</span>
									</div>
								</div>
							</div>
							<div class="card-footer">
								<div class="d-flex justify-content-center">
									<button type="submit" id="save-button" class="btn btn-primary text-uppercase save_continue">Save & continue</button>
									<button type="reset" id="reset" class="btn btn-default text-uppercase ml-2 reset_btn">Reset</button>
								</div>
								<p>Save date & continue payroll latter</p>
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