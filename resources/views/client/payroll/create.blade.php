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
							<div class="d-flex align-items-center">
								<a class= "d-block mt-2 ts-prev-btn" href="{{ route('payroll.create', ['week' => $week-1]) }}">
									<svg width="24px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
										<g>
											<path fill="none" d="M0 0h24v24H0z"></path>
											<path d="M10.828 12l4.95 4.95-1.414 1.414L8 12l6.364-6.364 1.414 1.414z"></path>
										</g>
									</svg>
								</a>
								<h3 class="card-title mb-0 px-3 ts-header-date"> {{ $year }} - {{ $month }}</h3>
								<a class="d-block mt-2 ts-next-btn" href="{{ route('payroll.create', ['week' => $week+1]) }}">
									<svg width="24px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
										<g>
											<path fill="none" d="M0 0h24v24H0z"></path>
											<path d="M13.172 12l-4.95-4.95 1.414-1.414L16 12l-6.364 6.364-1.414-1.414z"></path>
										</g>
									</svg>
								</a>
							</div>
						</div>
						<form class="form-horizontal" method="POST" action="{{ route('employee.store') }}" enctype="multipart/form-data">
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
								      	<th scope="col" colspan="3"></th>
											<?php
											for ($i=1;$i<=13;$i++) {
											?>
													<th scope="col">{{ strtoupper(date("D", strtotime("+$i day", strtotime($first_date)))) }}</th>
											<?php
													// $two_week_days[] = date("d-m-Y", strtotime("+$i day", strtotime($first_date)));
												}
												?>
								    </tr>
								    <tr class="ts-day-row">
								      <th scope="col" colspan="1"></th>
								      <th scope="col">Start Date</th>
								      <!-- <th scope="col">Address</th> -->
								      <th scope="col">Pay/h</th>
									  <?php

									  for ($i=1;$i<=13;$i++) {
									  ?>
										  <th scope="col">{{ date("d", strtotime("+$i day", strtotime($first_date))) }}</th>
								  <?php
										  // $two_week_days[] = date("d-m-Y", strtotime("+$i day", strtotime($first_date)));
									  }
									?>
								    </tr>
								  </thead>
								  <tbody>
								  	@foreach($employees as $k => $v)
									    <tr class="ts-data-row">
									      {{-- <th scope="row">{{ $k+1 }}</th> --}}
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

									      <td>{{ !empty($v->employeeProfile) ? $v->employeeProfile->doj : ''}}</td>
									      <td>{{ !empty($v->employeeProfile) ? $v->employeeProfile->pay_rate : 0}}</td>
									      <?php
									      for ($i=1;$i<=13;$i++) {
									      	$dateToday = date("Y-m-d", strtotime("+$i day", strtotime($first_date)));
									      	$xcellData = NULL;
									      	$result = $tempDatesArr[$v->id];
									      	if (array_key_exists($dateToday, $result)) {

									      		$xcellData = $result[$dateToday];
									      	}
										?>
													<th scope="col">
														<input type="text" name="" class="form-control payroll_date_cell" placeholder="-"
														data-date="{{ $dateToday }}"
														data-empid="{{ $v->id }}"
														value="{{ $xcellData }}"
														>
													</th>
											<?php
												    // $two_week_days[] = date("d-m-Y", strtotime("+$i day", strtotime($first_date)));
												}
									      	?>
									    </tr>
									@endforeach
								  </tbody>
								</table>
							</div>
							{{-- <div class="card-footer">
								<!-- <button type="submit" class="btn btn-primary">Submit</button> -->
							</div> --}}
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection
@push('page_scripts')
<script>
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
@endpush