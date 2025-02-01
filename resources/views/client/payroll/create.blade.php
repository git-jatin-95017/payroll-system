@extends('layouts.new_layout')

@section('content')
<style>
	.tt-query, /* UPDATE: newer versions use tt-input instead of tt-query */
.tt-hint {
/*    width: 396px;*/
    height: 30px;
    padding: 8px 12px;
    font-size: 24px;
    line-height: 30px;
    border: 2px solid #ccc;
    border-radius: 8px;
    outline: none;
}

.tt-query { /* UPDATE: newer versions use tt-input instead of tt-query */
    box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
}

.tt-hint {
    color: #999;
}

.tt-menu { /* UPDATE: newer versions use tt-menu instead of tt-dropdown-menu */
    width: 350px;
    margin-top: 12px;
    padding: 8px 0;
    background-color: #fff;
    border: 1px solid #ccc;
    border: 1px solid rgba(0, 0, 0, 0.2);
    border-radius: 8px;
    box-shadow: 0 5px 10px rgba(0,0,0,.2);
}

.tt-suggestion {
    padding: 3px 20px;
    font-size: 18px;
    line-height: 24px;
}

.tt-suggestion.tt-is-under-cursor { /* UPDATE: newer versions use .tt-suggestion.tt-cursor */
    color: #fff;
    background-color: #0097cf;

}

.tt-suggestion p {
    margin: 0;
}

.db-text-success {
    color: #33ba5d !important;
 }
</style>
<div class="page-heading d-flex justify-content-between align-items-center gap-3 mb-3">
	<div>
		<h3>Payroll</h3>
		<p class="mb-0">Track and manage your timesheet</p>
	</div>
</div>
<ul class="nav nav-tabs nav-pills db-custom-tabs db-custom-tabs-theme gap-5 employee-tabs mb-4" id="myTab" role="tablist">
	<li class="nav-item" role="presentation">
		<button class="nav-link active" id="company-tab" data-bs-toggle="tab" data-bs-target="#company"
			type="button" role="tab" aria-controls="company" aria-selected="true">Personal</button>
	</li>
	<li class="nav-item" role="presentation">
		<button class="nav-link" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment" type="button"
			role="tab" aria-controls="payment" aria-selected="false">Approvals</button>
	</li>
</ul>

<div class="bg-white w-100 border-radius-15 p-4">
	<div class="tab-content" id="myTabContent">
		<div class="tab-pane fade show active" id="company" role="tabpanel" aria-labelledby="company-tab">
			<div>
				<?php

				/*@if ($errors->any())
				<div class="alert alert-danger">
					<ul class="m-0">
						@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
				@endif
				*/
					?>
		@if (session('message'))
			<div class="row">
				<div class="col-md-12">
					<div class="alert alert-success alert-dismissible py-2 d-flex justify-content-between align-items-center px-3">
						<p class="mb-0">{{ session('message') }}</p>
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					</div>
				</div>
			</div>
		@elseif (session('error'))
			<div class="row">
				<div class="col-md-12">
					<div class="alert alert-danger alert-dismissible py-2 d-flex justify-content-between align-items-center px-3">
						<p class="mb-0">{{ session('error') }}</p>
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					</div>
				</div>
			</div>
		@endif
		<div class="row">
			<div class="col-sm-12">
				<div class="time-sheet-container" style="min-height: 400px">
					<div>
						<form class="" method="GET" action="{{ route('payroll.create') }}" id="filter-timesheet">
							<div class="row">
								<div class="col-xl-3 col-4">
									<div class="form-group">
										<p class="mb-0 position-relative daterange-container">
											<input type="text" name="daterange" id="daterange" class="form-control db-custom-input" value="{{date('m/d/Y', strtotime($request->start_date)).' - '.date('m/d/Y', strtotime($request->end_date))}}">
											<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
												<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 2.994v2.25m10.5-2.25v2.25m-14.252 13.5V7.491a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v11.251m-18 0a2.25 2.25 0 0 0 2.25 2.25h13.5a2.25 2.25 0 0 0 2.25-2.25m-18 0v-7.5a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v7.5m-6.75-6h2.25m-9 2.25h4.5m.002-2.25h.005v.006H12v-.006Zm-.001 4.5h.006v.006h-.006v-.005Zm-2.25.001h.005v.006H9.75v-.006Zm-2.25 0h.005v.005h-.006v-.005Zm6.75-2.247h.005v.005h-.005v-.005Zm0 2.247h.006v.006h-.006v-.006Zm2.25-2.248h.006V15H16.5v-.005Z" />
											</svg>
										</p>
									</div>
								</div>
								<div class="col ps-0">
									<div class="form-group">
										<button type="submit" id="submit-button" class="btn btn-primary btn-search">Search</button>
									</div>
								</div>
									<?php
										$fdate = $request->start_date;
										$tdate = $request->end_date;
										$startDate = new \DateTime($fdate);
										$endDate = new \DateTime($tdate);
										$diff = $endDate->diff($startDate);
										$weekday = $diff->format('%a');
										$week = floor($diff->days / 7);
									?>
							</div>
						</form>
					</div>
					<form class="form-horizontal" method="GET" action="{{ route('payroll.create') }}" id="fom-timesheet">
						@csrf
						<input type="hidden" name="daterangehidden" id="daterange-hidden" class="form-control" value="{{date('Y-m-d', strtotime($request->start_date)).' - '.date('Y-m-d', strtotime($request->end_date))}}">
							<?php
								$y = date('Y', strtotime($request->start_date));
								$first_date = $request->start_date;
							?>
							<div class="p-0">
								<div class="table-responsive">
									<table class="table  ts-custom-table border-0 responsive">
										<thead>
											<tr class="ts-date-row">
												<th></th>
												<th scope="col" colspan=""></th>
													<?php
													for ($i=0;$i<=$weekday;$i++) {
													?>
														<th scope="col">{{ strtoupper(date("D", strtotime("+$i day", strtotime($first_date)))) }}</th>
													<?php
														}
													?>
												<th>Total</th>
											</tr>
											<tr class="ts-day-row">
												<th>
													<div class="form- mb-0">
														<input type="checkbox" id="select_all" class="form-check-input" />
														<label class="form-check-label d-block db-label"  style="font-size: 11px;" for="select_all">All</label>
													</div>
												</th>
												<th scope="col" colspan="">
													<p class="db-table-search position-relative mb-0">
														<svg width="20px" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
															<path fill-rule="evenodd" d="M14.53 15.59a8.25 8.25 0 111.06-1.06l5.69 5.69a.75.75 0 11-1.06 1.06l-5.69-5.69zM2.5 9.25a6.75 6.75 0 1111.74 4.547.746.746 0 00-.443.442A6.75 6.75 0 012.5 9.25z"></path>
														</svg>
														<!-- <form action="#" onsubmit="handle"> -->
															<!-- <input type="text" name="txt" /> -->
															<input type="text" name="search"  onkeypress="handle(event)" value="{{$request->search}}" placeholder="search">
															<input type="hidden" name="week_search" value="{{$request->week_search ??1}}">
														<!-- </form> -->
													</p>
												</th>
											<!-- <th scope="col">Start Date</th>
											<th scope="col">Address</th>
											<th scope="col">Pay/h</th> -->
											<?php

											for ($i=0;$i<=$weekday;$i++) {
											?>
												<th scope="col">{{ date("d", strtotime("+$i day", strtotime($first_date))) }}</th>
										<?php
												// $two_week_days[] = date("d-m-Y", strtotime("+$i day", strtotime($first_date)));
											}
											?>
											<th>-</th>
											</tr>
										</thead>
										<tbody>
											@foreach($employees as $k => $v)
												<tr class="ts-data-row">
												{{-- <td scope="row">{{ $k+1 }}</td> --}}
												<td>
													<div class="form-check mb-0">
														<input class="form-check-input checkbox" name="check[{{$v->id}}]" type="checkbox" value="1" id="flexCheckDefault{{$k}}">
														<label class="form-check-label" for="flexCheckDefault{{$k}}"></label>
													</div>
													<!-- <button class="approval_btn">Approval</button> -->
												</td>
												<td>
													<div class="d-flex">
														<div class="ts-img d-flex justify-content-center align-items-center">
															@if(!empty($v->employeeProfile->file))
																<img src="/files/{{$v->employeeProfile->file}}"
																style="width: 40px; height: 40px; border-radius: 100em;" />
															@else
																<img src='/img/user2-160x160.jpg' style="width: 40px; height: 40px; border-radius: 100em;">
															@endif
														</div>
														<div class="col-auto ps-2">
															<p class="ts-user-name mb-0">{{ $v->name }}</p>
															<p class="ts-designation mb-0">{{ !empty($v->employeeProfile) ? $v->employeeProfile->designation : ''}}</p>
														</div>
													</div>

												</td>


												<?php
												$sum = 0;
												for ($i=0;$i<=$weekday;$i++) {
													$dateToday = date("Y-m-d", strtotime("+$i day", strtotime($first_date)));
													$xcellData = NULL;
													$result = $tempDatesArr[$v->id];
													$class = NULL;
													if (array_key_exists($dateToday, $result)) {

														$xcellData = $result[$dateToday]['hrs'];

														if (is_numeric($result[$dateToday]['hrs'])) {
															$sum += $result[$dateToday]['hrs'];
														}

														$class = $result[$dateToday]['approval_status'] == 1 ? 'db-text-success' : null;
													}
												?>
															<td scope="col">
																<div id="the-basics">
																<input type="text" name="dates[{{$v->id}}][{{ $dateToday }}]" class="form-control typeahead payroll_date_cell {{$class}}" placeholder="-"
																data-date="{{ $dateToday }}"
																data-empid="{{ $v->id }}"
																value="{{ $xcellData ?? 0 }}"
																data-inputid="payroll_input_{{$v->id}}"
																data-id="{{$v->id}}" style="font-size: 12px !important;"
																></div>
														</td>
													<?php
															// $two_week_days[] = date("d-m-Y", strtotime("+$i day", strtotime($first_date)));
														}
													?>
													<td class="total" @if($sum > 0) style="color:#33ba5d !important;" @endif>{{ $sum }}</td>
												</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</div>
						<div class="text-end">
							<button type="submit" data-url="{{ route('payroll.store') }}" id="approve-button" class="btn btn-primary submit-btn">Approve</button>
						</div>
					</form>
				</div>
			</div>
		</div>
			</div>
		</div>
		<div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="payment-tab">
			<div class="sub-text-heading pb-4">
				<h3 class="mb-1">This Section is coming soon</h3>
			</div>
		</div>
	</div>
</div>
@endsection
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@push('page_scripts')
<!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script> -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/corejs-typeahead/1.2.1/bloodhound.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/corejs-typeahead/1.2.1/typeahead.jquery.min.js"></script>
<script>
	var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!
    var yyyy = today.getFullYear();
    if(dd<10){ dd='0'+dd }
    if(mm<10){ mm='0'+mm }
    var today1 = mm+'/'+dd+'/'+yyyy;

	$('#daterange').daterangepicker({

             maxDate:today1
    });
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