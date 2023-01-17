@extends('layouts.app')

@section('content')
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">
            <i class="fa fa-braille" style="color:#1976d2"></i>
            Payroll
        </h3>
    </div>
    <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="javascript:void(0)">Home</a>
            </li>
            <li class="breadcrumb-item active">Payroll</li>
			<li class="breadcrumb-item active">Create</li>
        </ol>
    </div>
</div>
<section class="content">
	<div class="container-fluid">
		<div class="px-3">
			<ul class="nav nav-tabs custom-ts-tabs mb-4" id="myTab" role="tablist">
				<li class="nav-item" role="presentation">
					<button class="nav-link active" id="home-tab" data-toggle="tab" data-target="#home" type="button" role="tab"
						aria-controls="home" aria-selected="true">
						TimeSheet
					</button>
				</li>
				<!-- <li class="nav-item" role="presentation">
					<button class="nav-link" id="profile-tab" data-toggle="tab" data-target="#profile" type="button"
						role="tab" aria-controls="profile" aria-selected="false">
						Approvals
					</button>
				</li> -->
			</ul>
		</div>
		<div class="tab-content" id="myTabContent">
			<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
				<div class="container-fluid">
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
					<div class="card" style="min-height: 400px">
						<div class="card-header">
							<div class="row">
								<div class="col-auto">
									<select name="week_search" class="form-control custom-ts-select" id="myFancyDropdown">
										<option value="1" @if($request->week_search ==1) selected @endif>Weekly Timesheet</option>
										<option value="2" @if($request->week_search ==2) selected @endif>Bi-Weekly Timesheet</option>
									</select>
								</div>
								<?php
									if($request->has('week_search') && $request->week_search == 1) {
										$weekday = 7;
									} else {
										$weekday = 13;
									}
								?>
								<div class="col-auto">
									<div class="d-flex align-items-center">
										<a class= "d-block mt-2 ts-prev-btn" href="{{ route('payroll.create', ['week' => $week-1, 'week_search'=> $request->week_search]) }}">
											<svg width="24px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
												<g>
													<path fill="none" d="M0 0h24v24H0z"></path>
													<path d="M10.828 12l4.95 4.95-1.414 1.414L8 12l6.364-6.364 1.414 1.414z"></path>
												</g>
											</svg>
										</a>
										<h3 class="card-title mb-0 px-3 ts-header-date"> {{ $year }} - {{ $month }}</h3>
										<a class="d-block mt-2 ts-next-btn" href="{{ route('payroll.create', ['week' => $week+1, 'week_search'=> $request->week_search]) }}">
											<svg width="24px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
												<g>
													<path fill="none" d="M0 0h24v24H0z"></path>
													<path d="M13.172 12l-4.95-4.95 1.414-1.414L16 12l-6.364 6.364-1.414-1.414z"></path>
												</g>
											</svg>
										</a>
									</div>
								</div>
							</div>
							</div>
						<form class="form-horizontal" method="GET" action="{{ route('payroll.create') }}" id="fom-timesheet">
							@csrf
							<?php
								// $default_week = date('W');
								// $week = $default_week; // get week
								$y = 2022; // get year
								$first_date =  date('d-m-Y', strtotime($y."W".$week));
								$two_week_days = [$first_date];
							?>
							<div class="card-body p-0">
								<table class="table table-bordered ts-custom-table border-0">
								<thead>
									<tr class="ts-date-row">
										<th></th>
										<th scope="col" colspan=""></th>
											<?php
											for ($i=1;$i<=$weekday;$i++) {
											?>
													<th scope="col">{{ strtoupper(date("D", strtotime("+$i day", strtotime($first_date)))) }}</th>
											<?php
													// $two_week_days[] = date("d-m-Y", strtotime("+$i day", strtotime($first_date)));
												}
											?>
										<th>Total</th>
									</tr>
									<tr class="ts-day-row">
										<th><input type="checkbox" id="select_all" style="opacity:99999999 !important;position: unset;left:0;" /></th>
										<th scope="col" colspan="">
											<p class="custom-search-ts">
												<svg class="w-64 h-64" width="20px" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
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

									for ($i=1;$i<=$weekday;$i++) {
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
										{{-- <th scope="row">{{ $k+1 }}</th> --}}
										<th>
											<div class="form-check mb-0">
												<input class="form-check-input checkbox" name="check[{{$v->id}}]" type="checkbox" value="1" id="flexCheckDefault{{$k}}">
												<label class="form-check-label" for="flexCheckDefault{{$k}}"></label>
											</div>
											<!-- <button class="approval_btn">Approval</button> -->
										</th>
										<td>
											<div class="d-flex">
												<div class="ts-img d-flex justify-content-center align-items-center">
													D
												</div>
												<div class="col-auto">
													<p class="ts-user-name mb-0">{{ $v->name }}</p>
													<p class="ts-designation mb-0">{{ !empty($v->employeeProfile) ? $v->employeeProfile->designation : ''}}</p>
												</div>
											</div>

										</td>

										<!-- <td>{{ !empty($v->employeeProfile) ? $v->employeeProfile->doj : ''}}</td> -->
										<!-- <td>{{ !empty($v->employeeProfile) ? $v->employeeProfile->pay_rate : 0}}</td> -->
										<?php
										$sum = 0;
										for ($i=1;$i<=$weekday;$i++) {
											$dateToday = date("Y-m-d", strtotime("+$i day", strtotime($first_date)));
											$xcellData = NULL;
											$result = $tempDatesArr[$v->id];
											$class = NULL;
											if (array_key_exists($dateToday, $result)) {

												$xcellData = $result[$dateToday]['hrs'];

												if (is_numeric($result[$dateToday]['hrs'])) {
													$sum += $result[$dateToday]['hrs'];
												}

												$class = $result[$dateToday]['approval_status'] == 1 ? 'bg-success' : null;
											}
										?>
													<th scope="col">
														<input type="text" name="dates[{{$v->id}}][{{ $dateToday }}]" class="form-control payroll_date_cell {{$class}}" placeholder="-"
														data-date="{{ $dateToday }}"
														data-empid="{{ $v->id }}"
														value="{{ $xcellData }}"
														>
													</th>
											<?php
													// $two_week_days[] = date("d-m-Y", strtotime("+$i day", strtotime($first_date)));
												}
											?>
											<td>{{ $sum }}</td>
										</tr>
									@endforeach
								</tbody>
								</table>
							</div>
							<div class="card-footer">
								<button type="submit" data-url="{{ route('payroll.store') }}" id="approve-button" class="btn btn-primary">Approve</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			</div>
			</div>
			<div class="tab-pane fade" id="profile" role="tabpanel"aria-labelledby="profile-tab">
				<h3 align="center">This Section is coming soon.</h3>
			</div>
		</div>
		</div>
</section>
@endsection
@push('page_scripts')
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
@endpush