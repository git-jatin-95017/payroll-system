@extends('layouts.new_layout')

@section('content')
<style>
	.tt-query,
	/* UPDATE: newer versions use tt-input instead of tt-query */
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

	.tt-query {
		/* UPDATE: newer versions use tt-input instead of tt-query */
		box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
	}

	.tt-hint {
		color: #999;
	}

	.tt-menu {
		/* UPDATE: newer versions use tt-menu instead of tt-dropdown-menu */
		width: 350px;
		margin-top: 12px;
		padding: 8px 0;
		background-color: #fff;
		border: 1px solid #ccc;
		border: 1px solid rgba(0, 0, 0, 0.2);
		border-radius: 8px;
		box-shadow: 0 5px 10px rgba(0, 0, 0, .2);
	}

	.tt-suggestion {
		padding: 3px 20px;
		font-size: 18px;
		line-height: 24px;
	}

	.tt-suggestion.tt-is-under-cursor {
		/* UPDATE: newer versions use .tt-suggestion.tt-cursor */
		color: #fff;
		background-color: #0097cf;

	}

	.tt-suggestion p {
		margin: 0;
	}

	.db-text-success {
		color: #33ba5d !important;
	}

	/* Calendar-like grid styles */
	.schedule-calendar-table th, .schedule-calendar-table td {
		/* text-align: center; */
		vertical-align: top;
		min-width: 120px;
		border: 1px solid #e0e0e0;
		background: #fff;
	}
	.schedule-calendar-table th.date-header {
		/* background: #f5f6fa; */
		color: #fff;
		background-color: #4f4bc3;
		font-weight: normal;
		font-size: 12px;
		border-bottom: 2px solid #4f4bc3;
	}
	.schedule-calendar-table td {
		position: relative;
		height: 70px;
	}
	.schedule-employee-cell {
		text-align: left;
		min-width: 180px;
		background: #f8f9fb;
		font-weight: 500;
		border-right: 2px solid #bdbdbd;
	}
	.schedule-avatar {
		width: 32px; height: 32px; border-radius: 50%; object-fit: cover; margin-right: 8px;
	}
	.schedule-event-chip {
		display: inline-block;
		background: #5e72e4;
		color: #fff;
		border-radius: 12px;
		padding: 2px 10px;
		font-size: 13px;
		margin-bottom: 2px;
		margin-right: 2px;
		cursor: pointer;
		white-space: nowrap;
	}
	.schedule-plus-btn {
		position: absolute;
		bottom: 4px;
		right: 4px;
		font-size: 18px;
		color: #5e72e4;
		background: #f5f6fa;
		border: none;
		border-radius: 50%;
		width: 28px; height: 28px;
		display: flex; align-items: center; justify-content: center;
		cursor: pointer;
		transition: background 0.2s;
	}
	.schedule-plus-btn:hover {
		background: #e0e0e0;
	}
</style>
<div class="page-heading d-flex justify-content-between align-items-center gap-3 mb-3">
	<div>
		<h3>Payroll</h3>
		<p class="mb-0">Track and manage your timesheet</p>
	</div>
</div>
<ul class="nav nav-tabs nav-pills db-custom-tabs db-custom-tabs-theme gap-5 employee-tabs mb-4" id="myTab"
	role="tablist">
	<li class="nav-item" role="presentation">
		<button class="nav-link active" id="company-tab" data-bs-toggle="tab" data-bs-target="#company" type="button"
			role="tab" aria-controls="company" aria-selected="true">Time Sheet</button>
	</li>
	<li class="nav-item" role="presentation">
		<button class="nav-link" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment" type="button"
			role="tab" aria-controls="payment" aria-selected="false">Schedule</button>
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
						<div
							class="alert alert-success alert-dismissible py-2 d-flex justify-content-between align-items-center px-3">
							<p class="mb-0">{{ session('message') }}</p>
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						</div>
					</div>
				</div>
				@elseif (session('error'))
				<div class="row">
					<div class="col-md-12">
						<div
							class="alert alert-danger alert-dismissible py-2 d-flex justify-content-between align-items-center px-3">
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
								<form class="" method="POST" action="{{ route('payroll.create.post') }}"
									id="filter-timesheet">
									@csrf
									<div class="row">
										<div class="col daterange-container-main">
											<div class="form-group">
												<p class="mb-0 position-relative daterange-container">
													<input type="text" name="daterange" id="daterange"
														class="form-control db-custom-input"
														value="{{date('m/d/Y', strtotime($request->start_date)).' - '.date('m/d/Y', strtotime($request->end_date))}}">
													<svg xmlns="http://www.w3.org/2000/svg" fill="none"
														viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
														class="size-6">
														<path stroke-linecap="round" stroke-linejoin="round"
															d="M6.75 2.994v2.25m10.5-2.25v2.25m-14.252 13.5V7.491a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v11.251m-18 0a2.25 2.25 0 0 0 2.25 2.25h13.5a2.25 2.25 0 0 0 2.25-2.25m-18 0v-7.5a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v7.5m-6.75-6h2.25m-9 2.25h4.5m.002-2.25h.005v.006H12v-.006Zm-.001 4.5h.006v.006h-.006v-.005Zm-2.25.001h.005v.006H9.75v-.006Zm-2.25 0h.005v.005h-.006v-.005Zm6.75-2.247h.005v.005h-.005v-.005Zm0 2.247h.006v.006h-.006v-.006Zm2.25-2.248h.006V15H16.5v-.005Z" />
													</svg>
												</p>
											</div>
										</div>
										<div class="col ps-0">
											<div class="form-group">
												<button type="submit" id="submit-button"
													class="btn btn-primary btn-search">Search</button>
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
							<form class="form-horizontal" method="POST" action="{{ route('payroll.create') }}"
								id="fom-timesheet">
								@csrf
								<input type="hidden" name="daterangehidden" id="daterange-hidden" class="form-control"
									value="{{date('Y-m-d', strtotime($request->start_date)).' - '.date('Y-m-d', strtotime($request->end_date))}}">
								<?php
								$y = date('Y', strtotime($request->start_date));
								$first_date = $request->start_date;
							?>
								<div class="p-0">
									<div class="table-responsive">
										<table class="table ts-custom-table">
											<thead>
												<tr class="ts-date-row">
													<th scope="col">
														<div class="form- mb-0">
															<input style="width: 16px; height: 16px;" type="checkbox"
																id="select_all" class="form-check-input mt-0" />
															
														</div>
													</th>
													<th scope="col">
														
														{{ date('m/d/Y', strtotime($request->start_date)).' - '.date('m/d/Y', strtotime($request->end_date)) }}
													</th>
												
													<?php
													for ($i=0;$i<=$weekday;$i++) {
													?>
													<th scope="col">{{ strtoupper(date("D", strtotime("+$i day",
														strtotime($first_date)))) }}</th>
													<?php
														}
													?>
													<th>Total</th>
												</tr>
												<tr class="ts-day-row">
													<th scope="col">
														<label class="form-check-label d-block db-label mt-1" style="font-size: 11px; color: #fff;" for="select_all">All</label>
													</th>
													<th scope="col">
														<p class="db-table-search position-relative mb-0">
															<svg width="20px" xmlns="http://www.w3.org/2000/svg"
																fill="currentColor" viewBox="0 0 24 24">
																<path fill-rule="evenodd"
																	d="M14.53 15.59a8.25 8.25 0 111.06-1.06l5.69 5.69a.75.75 0 11-1.06 1.06l-5.69-5.69zM2.5 9.25a6.75 6.75 0 1111.74 4.547.746.746 0 00-.443.442A6.75 6.75 0 012.5 9.25z">
																</path>
															</svg>
															<input type="text" name="search" onkeypress="handle(event)"
																value="{{$request->search}}" placeholder="search">
															<input type="hidden" name="week_search"
																value="{{$request->week_search ??1}}">
														</p>
													</th>
													<?php

											for ($i=0;$i<=$weekday;$i++) {
											?>
													<th scope="col">{{ date("d", strtotime("+$i day",
														strtotime($first_date))) }}</th>
													<?php
											}
											?>
													<th id="th-total-div"></th>
												</tr>
											</thead>
											<tbody>
												@foreach($employees as $k => $v)
												<tr class="ts-data-row">
													{{-- <td scope="row">{{ $k+1 }}</td> --}}
													<td>
														<div class="form-check mb-0">
															<input class="form-check-input checkbox"
																name="check[{{$v->id}}]" type="checkbox" value="1"
																id="flexCheckDefault{{$k}}">
															<label class="form-check-label"
																for="flexCheckDefault{{$k}}"></label>
														</div>
														<!-- <button class="approval_btn">Approval</button> -->
													</td>
													<td>
														<div class="d-flex">
															<div
																class="ts-img d-flex justify-content-center align-items-center">
																@if(!empty($v->employeeProfile->file))
																<img src="/files/{{$v->employeeProfile->file}}"
																	style="width: 40px; height: 40px; border-radius: 100em;" />
																@else
																<img src='/img/user2-160x160.jpg'
																	style="width: 40px; height: 40px; border-radius: 100em;">
																@endif
															</div>
															<div class="col-auto ps-2">
																<p class="ts-user-name mb-0">{{ $v->name }}</p>
																<p class="ts-designation mb-0">{{
																	!empty($v->employeeProfile) ?
																	$v->employeeProfile->designation : ''}}</p>
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
															<input type="text"
																name="dates[{{$v->id}}][{{ $dateToday }}]"
																class="form-control typeahead payroll_date_cell {{$class}}"
																placeholder="-" data-date="{{ $dateToday }}"
																data-empid="{{ $v->id }}" value="{{ $xcellData ?? 0 }}"
																data-inputid="payroll_input_{{$v->id}}"
																data-id="{{$v->id}}"
																style="font-size: 12px !important;">
														</div>
													</td>
													<?php
															// $two_week_days[] = date("d-m-Y", strtotime("+$i day", strtotime($first_date)));
														}
													?>
													<td class="total" @if($result[$dateToday]['approval_status'] == 1) style="color:#33ba5d !important;"
														@endif>{{ $sum }}</td>
												</tr>
												@endforeach
											</tbody>
										</table>
									</div>
								</div>
								<div class="text-end">
									<button type="submit" data-url="{{ route('payroll.store') }}" id="approve-button"
										class="btn btn-primary submit-btn">Approve</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="payment-tab">
			<div class="schedule-grid-container">
				<form id="schedule-date-range-form" class="form-horizontal">
					<div class="row">
						<div class="col daterange-container-main">
							<div class="form-group">
								<p class="mb-0 position-relative daterange-container">
									<input type="text" name="daterange" id="schedule-daterange" class="form-control db-custom-input" value="{{date('m/d/Y', strtotime($request->start_date)).' - '.date('m/d/Y', strtotime($request->end_date))}}">
									<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
										<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 2.994v2.25m10.5-2.25v2.25m-14.252 13.5V7.491a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v11.251m-18 0a2.25 2.25 0 0 0 2.25 2.25h13.5a2.25 2.25 0 0 0 2.25-2.25m-18 0v-7.5a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v7.5m-6.75-6h2.25m-9 2.25h4.5m.002-2.25h.005v.006H12v-.006Zm-.001 4.5h.006v.006h-.006v-.005Zm-2.25.001h.005v.006H9.75v-.006Zm-2.25 0h.005v.005h-.006v-.005Zm6.75-2.247h.005v.005h-.005v-.005Zm0 2.247h.006v.006h-.006v-.006Zm2.25-2.248h.006V15H16.5v-.005Z"></path>
									</svg>
								</p>
							</div>
						</div>
						
						<div class="col ps-0">
							<div class="form-group">
								<button type="submit" id="submit-button" class="btn btn-primary btn-search">Search</button>
							</div>
						</div>
						<div class="d-flex col justify-content-end mb-2">
							<button id="publish-schedule-btn" class="btn btn-success" type="button">
								<span id="publish-btn-text">Publish</span>
							</button>
						</div>							
					</div>
					<!-- <input type="text" id="schedule-daterange" class="form-control" style="max-width: 250px;" readonly /> -->
					<!-- <button type="submit" class="btn btn-primary">Go</button> -->
				</form>
				<div class="table-responsive" style="max-height: 500px; overflow: auto;">
					<table class="table table-bordered align-middle" id="schedule-grid-table">
						<thead>
							<tr>
								<th>Employee</th>
								<!-- Dates will be injected here by JS -->
							</tr>
						</thead>
						<tbody>
							<!-- Rows will be injected here by JS -->
						</tbody>
					</table>
				</div>
			</div>
			<!-- Schedule Modal -->
			<div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="scheduleModalLabel">Job</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<form id="scheduleForm">
							<div class="modal-body">
								<input type="hidden" id="schedule_id" name="schedule_id">
								<input type="hidden" id="employee_id" name="employee_id">
								<input type="hidden" id="schedule_date" name="schedule_date">
								<div class="mb-3">
									<label for="title" class="form-label">Title</label>
									<input type="text" class="form-control" id="title" name="title" value="">
								</div>
								<div class="mb-3">
									<label for="start_datetime" class="form-label">Start</label>
									<input type="datetime-local" class="form-control" id="start_datetime" name="start_datetime" required>
								</div>
								<div class="mb-3">
									<label for="end_datetime" class="form-label">End</label>
									<input type="datetime-local" class="form-control" id="end_datetime" name="end_datetime" required>
								</div>
								<div class="mb-3">
									<label for="description" class="form-label">Notes</label>
									<textarea class="form-control" id="description" name="description"></textarea>
								</div>
							</div>
							<div class="modal-footer">
								<button type="submit" class="btn btn-success">Save</button>
								<button type="button" class="btn btn-danger d-none" id="deleteScheduleBtn">Delete</button>
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
							</div>
						</form>
					</div>
				</div>
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
	var mm = today.getMonth() + 1; //January is 0!
	var yyyy = today.getFullYear();
	if (dd < 10) { dd = '0' + dd }
	if (mm < 10) { mm = '0' + mm }
	var today1 = mm + '/' + dd + '/' + yyyy;

	$('#daterange').daterangepicker({

		maxDate: today1
	});
	$("#approve-button").click(function (e) {
		e.preventDefault();

		var form = $("#fom-timesheet");

		form.prop("method", 'POST');
		form.prop("action", $(this).data("url"));
		form.submit();
	});
</script>

<script>
	function handle(e) {
		if (e.keyCode == 13) {
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

	$(document).ready(function () {
		$(".payroll_date_cell").blur(function () {
			if ($(this).val() != '' || $(this).val() != null) {
				$.ajax({
					url: "{{ route('payroll.store') }}",
					type: 'POST',
					data: { _token: "{{ csrf_token() }}", emp_id: $(this).data('empid'), payroll_date: $(this).data('date'), daily_hrs: $(this).val() },
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
	$(document).ready(function () {
		$('#select_all').on('click', function () {
			if (this.checked) {
				$('.checkbox').each(function () {
					this.checked = true;
				});
			} else {
				$('.checkbox').each(function () {
					this.checked = false;
				});
			}
		});

		$('.checkbox').on('click', function () {
			if ($('.checkbox:checked').length == $('.checkbox').length) {
				$('#select_all').prop('checked', true);
			} else {
				$('#select_all').prop('checked', false);
			}
		});
	});
</script>
<script>
	$(document).ready(function () {
		calc_final_total();
		$(".payroll_date_cell").on('blur', function () {
			var that = $(this);

			calc_total(that);
			calc_final_total();
		});

		function calc_total(obj) {
			var focusedRow = obj.closest('tr');

			console.log(focusedRow);

			var sum = 0;
			focusedRow.find(".payroll_date_cell").each(function () {
				if ($.isNumeric(this.value)) {
					sum += parseFloat(this.value);
				}
			});

			console.log(sum);

			focusedRow.find('td.total').html(sum);
		}

		function calc_final_total() {
			var total = 0;
			$(".total").each(function () {
				var value = $(this).html().trim(); // Get and trim the content
				console.log(value);
				
				if ($.isNumeric(value)) {
					total += parseFloat(value);
				}
			});

			$('#th-total-div').html(total);
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
			url: route,
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
				console.log(process(data), 2222);
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

	}).on('typeahead:selected', function (event, selection) {
		// the second argument has the info you want
		console.log(selection.short_name);
		let res = selection.short_name;
		// clearing the selection requires a typeahead method
		// $(this).typeahead('setQuery', '');

		$.ajax({
			url: "{{ route('payroll.store') }}",
			type: 'POST',
			data: { _token: "{{ csrf_token() }}", emp_id: $(this).data('empid'), payroll_date: $(this).data('date'), daily_hrs: res },
			dataType: 'JSON',
			success: function (data) {
				// alert('Record Saved Successfully.');
			}
		});
	});
</script>

<script>
	$(document).ready(function() {
		// --- Date Range Picker for Schedule Grid ---
		let startDate = moment().startOf('week');
		let endDate = moment().endOf('week');
		$('#schedule-daterange').daterangepicker({
			startDate: startDate,
			endDate: endDate,
			locale: { format: 'MM/DD/YYYY' }
		});

		// Initial load
		loadScheduleGrid();

		// On date range change
		$('#schedule-date-range-form').on('submit', function(e) {
			e.preventDefault();
			const drp = $('#schedule-daterange').data('daterangepicker');
			startDate = drp.startDate;
			endDate = drp.endDate;
			loadScheduleGrid();
		});

		function pastelColor(seed) {
			// Generate a pastel color based on a string seed
			let hash = 0;
			for (let i = 0; i < seed.length; i++) hash = seed.charCodeAt(i) + ((hash << 5) - hash);
			const h = Math.abs(hash) % 360;
			return `hsl(${h}, 70%, 85%)`;
		}

		function showToast(msg, type = 'success') {
			const toast = $(`<div class='toast align-items-center text-bg-${type === 'success' ? 'success' : 'danger'} border-0 show' role='alert' aria-live='assertive' aria-atomic='true' style='position:fixed;top:20px;right:20px;z-index:9999;'><div class='d-flex'><div class='toast-body'>${msg}</div><button type='button' class='btn-close btn-close-white me-2 m-auto' data-bs-dismiss='toast' aria-label='Close'></button></div></div>`);
			$('body').append(toast);
			setTimeout(() => toast.fadeOut(400,()=>toast.remove()), 2500);
		}

		function loadScheduleGrid() {
			$.ajax({
				url: '/client/schedule',
				method: 'GET',
				data: {
					start_datetime: startDate.format('YYYY-MM-DD 00:00:00'),
					end_datetime: endDate.format('YYYY-MM-DD 23:59:59'),
					search: $('#schedule-employee-search').val()
				},
				success: function(response) {
					renderScheduleGrid(response.employees, response.schedules);
				},
				error: function() {
					$('#schedule-grid-table tbody').html('<tr><td colspan="100%" class="text-danger">Error loading schedules.</td></tr>');
				}
			});
		}

		function renderScheduleGrid(employees, schedules) {
			// Build date columns
			let dateColumns = [];
			let dayNames = [];
			let current = moment(startDate);
			while (current <= endDate) {
				dateColumns.push(current.format('YYYY-MM-DD'));
				dayNames.push(current.format('ddd'));
				current.add(1, 'days');
			}
			// Render table header
			let searchValue = $('#schedule-employee-search').val() || '';
			let thead = `<tr><th class="schedule-employee-cell" style="background-color: #4f4bc3;border-bottom: 2px solid #4f4bc3;">
				<p class="db-table-search position-relative mb-0">
					<svg width="20px" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
						<path fill-rule="evenodd" d="M14.53 15.59a8.25 8.25 0 111.06-1.06l5.69 5.69a.75.75 0 11-1.06 1.06l-5.69-5.69zM2.5 9.25a6.75 6.75 0 1111.74 4.547.746.746 0 00-.443.442A6.75 6.75 0 012.5 9.25z">
						</path>
					</svg>
					<input type="text" id="schedule-employee-search" class="input-sm form-control" placeholder="Search ..." value="${searchValue}">
				</p>
			</th>`;
			dateColumns.forEach((date, idx) => {
				const d = moment(date);
				thead += `<th class="date-header"><span style='font-weight:bold;font-size:12px;'>${d.format('ddd')}</span><br><span style='font-size:12px;'>${d.format('DD MMM')}</span></th>`;
			});
			thead += '</tr>';
			$('#schedule-grid-table thead').html(thead);
			// Render table body
			let tbody = '';
			employees.forEach(emp => {
				tbody += `<tr><td class="schedule-employee-cell">`;
				// if (emp.avatar) {
				// 	tbody += `<img src="/files/${emp.avatar}" class="schedule-avatar" alt="avatar">`;
				// } else {
				// 	tbody += `<span class="schedule-avatar" style="background:#e0e0e0;display:inline-block;"></span>`;
				// }
				const img = emp.avatar ? `/files/${emp.avatar}` : '/img/user2-160x160.jpg';
				tbody += `<div class="d-flex">`
				tbody += `<div class="ts-img d-flex justify-content-center align-items-center">`
				tbody += `<img src="${img}" style="width: 40px; height: 40px; border-radius: 100em;" alt="avatar">`;
				tbody += `</div>`
				tbody += `<div class="col-auto ps-2">`
				tbody += `<p class="ts-user-name mb-0">${emp.name}</p><p class="ts-designation mb-0">${emp.designation || ''}</p>`;
				tbody += `</div>`
				tbody += `</div>`
				tbody += `</td>`
				dateColumns.forEach(date => {
					// Find schedules for this employee/date
					const cellSchedules = schedules.filter(s => s.employee_id === emp.id && s.start_datetime.startsWith(date));
					tbody += '<td style="position:relative">';
					if (cellSchedules.length > 0) {
						cellSchedules.forEach(sch => {
							const color = pastelColor(sch.title+sch.id);
							// Format time in 12-hour format with AM/PM
							function format12hr(timeStr) {
								if (!timeStr) return '';
								const [h, m] = timeStr.split(':');
								let hour = parseInt(h);
								const min = m;
								const ampm = hour >= 12 ? 'pm' : 'am';
								hour = hour % 12;
								if (hour === 0) hour = 12;
								return `${hour}:${min}${ampm}`;
							}
							const startTime = format12hr(sch.start_datetime.substr(11,5));
							const endTime = format12hr(sch.end_datetime.substr(11,5));
							tbody += `<div class="schedule-event-chip edit-schedule-btn" data-schedule='${JSON.stringify(sch)}' style="background:${color}" title="${sch.title}\n${startTime} - ${endTime}\n${sch.description||''}"> <span style='font-size:11px;'>${startTime}-${endTime}</span></div>`;
						});
					}
					tbody += `<button class="schedule-plus-btn add-schedule-btn" data-empid="${emp.id}" data-date="${date}" title="Add schedule">+</button>`;
					tbody += '</td>';
				});
				tbody += '</tr>';
			});
			$('#schedule-grid-table').addClass('schedule-calendar-table');
			$('#schedule-grid-table tbody').html(tbody);
		}
		
		// Modal open for add
		$(document).on('click', '.add-schedule-btn', function() {
			$('#scheduleForm')[0].reset();
			$('#schedule_id').val('');
			$('#employee_id').val($(this).data('empid'));
			$('#schedule_date').val($(this).data('date'));
			$('#start_datetime').val($(this).data('date') + 'T09:00');
			$('#end_datetime').val($(this).data('date') + 'T18:00');
			$('#deleteScheduleBtn').addClass('d-none');
			$('#scheduleModalLabel').text('Job');
			$('#scheduleModal').modal('show');
		});
		// Modal open for edit
		$(document).on('click', '.edit-schedule-btn', function() {
			const sch = $(this).data('schedule');
			$('#scheduleForm')[0].reset();
			$('#schedule_id').val(sch.id);
			$('#employee_id').val(sch.employee_id);
			$('#schedule_date').val(sch.start_datetime.substr(0,10));
			$('#title').val(sch.title);
			$('#start_datetime').val(sch.start_datetime.replace(' ', 'T'));
			$('#end_datetime').val(sch.end_datetime.replace(' ', 'T'));
			$('#description').val(sch.description);
			$('#deleteScheduleBtn').removeClass('d-none');
			$('#scheduleModalLabel').text('Job');
			$('#scheduleModal').modal('show');
		});
		// AJAX for save
		$('#scheduleForm').on('submit', function(e) {
			e.preventDefault();
			const id = $('#schedule_id').val();
			const url = id ? `/client/schedule/${id}` : '/client/schedule';
			const method = id ? 'PUT' : 'POST';
			const data = $(this).serialize();
			$.ajax({
				url: url,
				method: method,
				data: data,
				success: function() {
					$('#scheduleModal').modal('hide');
					showToast('Schedule saved!','success');
					loadScheduleGrid();
				},
				error: function(xhr) {
					let msg = 'Error saving schedule';
					if (xhr.responseJSON && xhr.responseJSON.errors) {
						msg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
					} else if (xhr.responseJSON && xhr.responseJSON.message) {
						msg = xhr.responseJSON.message;
					}
					showToast(msg, 'danger');
				}
			});
		});
		// AJAX for delete
		$('#deleteScheduleBtn').on('click', function() {
			if (!confirm('Delete this schedule?')) return;
			const id = $('#schedule_id').val();
			$.ajax({
				url: `/client/schedule/${id}`,
				method: 'DELETE',
				data: { _token: $('meta[name="csrf-token"]').attr('content') },
				success: function() {
					$('#scheduleModal').modal('hide');
					showToast('Schedule deleted!','success');
					loadScheduleGrid();
				},
				error: function() {
					showToast('Error deleting schedule','danger');
				}
			});
		});

		// Add at the top of your $(document).ready(function() { ... });
		let isPublished = false; // This should be set based on backend response

		function updatePublishButton() {
			if (isPublished) {
				$('#publish-schedule-btn').prop('disabled', true);
				$('#publish-btn-text').text('Published');
			} else {
				$('#publish-schedule-btn').prop('disabled', false);
				$('#publish-btn-text').text('Publish');
			}
		}

		// Call this after loading the grid, and after publishing
		function checkPublishedStatus() {
			$.ajax({
				url: '/client/schedule/published-status',
				method: 'GET',
				data: {
					start_datetime: startDate.format('YYYY-MM-DD 00:00:00'),
					end_datetime: endDate.format('YYYY-MM-DD 23:59:59'),
				},
				success: function(response) {
					isPublished = response.published;
					// updatePublishButton();
				}
			});
		}

		$('#publish-schedule-btn').on('click', function() {
			if (!confirm('Are you sure you want to publish the schedule for this period?')) return;
			$.ajax({
				url: '/client/schedule/publish',
				method: 'POST',
				data: {
					start_datetime: startDate.format('YYYY-MM-DD 00:00:00'),
					end_datetime: endDate.format('YYYY-MM-DD 23:59:59'),
				},
				success: function() {
					isPublished = true;
					// updatePublishButton();
					showToast('Schedule published!','success');
				},
				error: function() {
					showToast('Error publishing schedule','danger');
				}
			});
		});

		// Employee search functionality with event delegation
		let searchTimeout;
		$(document).on('input', '#schedule-employee-search', function() {
			clearTimeout(searchTimeout);
			searchTimeout = setTimeout(function() {
				loadScheduleGrid();
			}, 300); // 300ms debounce
		});
	});
</script>

<script>
// Ensure CSRF token is sent with all AJAX requests
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
</script>

@endpush