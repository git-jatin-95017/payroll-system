@extends('layouts.new_layout')

@push('page_css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
@endpush

@section('content')
<div>
	<div class="page-heading d-flex justify-content-between align-items-center gap-3 mb-3">
		<div>
			<h3>Leave Types</h3>
		</div>
	</div>
	<div class="container-fluid">
		@if ($errors->any())
		<div class="alert alert-danger">
			<ul class="m-0">
				@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
		@endif
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
	</div>

	

	<div class="bg-white white-container py-4 px-4 pt-4continer-h-full">
		<ul class="nav nav-tabs nav-pills db-custom-tabs db-custom-tabs-theme gap-5 employee-tabs mb-4" id="myTab"
		role="tablist">
			<li class="nav-item" role="presentation">
				<button class="nav-link active" id="company-tab" data-bs-toggle="tab" data-bs-target="#company" type="button"
					role="tab" aria-controls="company" aria-selected="true">Leave Type</button>
			</li>
			<li class="nav-item" role="presentation">
				<button class="nav-link" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment" type="button"
					role="tab" aria-controls="payment" aria-selected="false">Carryover</button>
			</li>
		</ul>

		<form class="form-horizontal" method="POST" action="{{ route('leave-type.update', $leaveType->id) }}">
		@csrf
		{{ method_field('PUT') }}
		<div class="tab-content" id="myTabContent">
			<div class="tab-pane fade show active" id="company" role="tabpanel" aria-labelledby="company-tab">
				<div class="row">            	
					<div class="col-sm-12">
						<div class="max-w-md max-auto">
							<div class="sub-text-heading pb-4">
								<h3 class="mb-1">Leave Policies</h3>
								<p>Edit your policy information here</p>
							</div>
							<div class="row">
								<div class="col-8 mb-3">
									<div class="form-group">
										<label for="title" class="db-label">Leave Type</label>
										<input id="name" type="text" class="form-control db-custom-input {{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ $leaveType->name }}"">
										@if ($errors->has('name'))
											<span class="text-danger">
												{{ $errors->first('name') }}
											</span>
										@endif
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-8 mb-3">
									<div class="form-group">
										<label for="description" class="db-label">Number Of Days</label>
											<input id="no_of_day" type="number" min="0" class="form-control db-custom-input {{ $errors->has('no_of_day') ? ' is-invalid' : '' }}" name="no_of_day" value="{{ $leaveType->leave_day }}">
											@if ($errors->has('no_of_day'))
												<span class="text-danger">
													{{ $errors->first('no_of_day') }}
												</span>
											@endif
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="col-8 mb-3">
									<div class="form-group">
										<label for="start_days" class="db-label">Start (after hire date)</label>
											<input id="start_days" type="number" min="0" class="form-control db-custom-input {{ $errors->has('start_days') ? ' is-invalid' : '' }}" name="start_days" value="{{ $leaveType->start_days }}">
											@if ($errors->has('start_days'))
												<span class="text-danger">
													{{ $errors->first('start_days') }}
												</span>
											@endif
									</div>
								</div>
							</div>

							<!-- 
							<div class="row">
								<div class="col-8 mb-3">
									<div class="form-group">
										<label for="carry_over_amount" class="db-label">Carryover Amount</label>
											<input id="carry_over_amount" type="number" min="0" class="form-control db-custom-input {{ $errors->has('carry_over_amount') ? ' is-invalid' : '' }}" name="carry_over_amount" value="{{ $leaveType->carry_over_amount }}">
											@if ($errors->has('carry_over_amount'))
												<span class="text-danger">
													{{ $errors->first('carry_over_amount') }}
												</span>
											@endif
									</div>
								</div>
							</div> 
							-->

							<div class="row">
								<div class="col-8 mb-3">
									<div class="form-group">
										<label for="date_after" class="db-label">Carryover Date</label>
											<input id="date_after" type="text" readonly class="form-control db-custom-input {{ $errors->has('date_after') ? ' is-invalid' : '' }}" name="date_after" value="{{ date('12-31') }}" readnly>
											@if ($errors->has('date_after'))
												<span class="text-danger">
													{{ $errors->first('date_after') }}
												</span>
											@endif
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-8 mb-3">
									<div class="form-group">
										<!-- <div class="col-md-6"> -->
											<label for="name" class="db-label">Status</label>
												<select class="form-control db-custom-input" name="status">
													<option value="">Please Select</option>
													<option value="1" @if($leaveType->status == 1) selected @endif>Paid</option>
													<option value="0" @if($leaveType->status == 0) selected @endif>Unpaid</option>
												</select>

											@if ($errors->has('status'))
												<span class="text-danger">
													{{ $errors->first('status') }}
												</span>
											@endif
										<!-- </div> -->
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-8 mb-3">
									<div class="form-group">
										<!-- <div class="col-md-6"> -->
											<label for="is_visible_calendar" class="db-label">Visible On Calendar?</label>
											<select class="form-control db-custom-input" id="is_visible_calendar" name="is_visible_calendar">
												<option @if($leaveType->is_visible_calendar == "0") selected @endif value="0">No</option>
												<option @if($leaveType->is_visible_calendar == "1") selected @endif value="1">Yes</option>
											</select>

											@if ($errors->has('is_visible_calendar'))
												<span class="text-danger">
													{{ $errors->first('is_visible_calendar') }}
												</span>
											@endif
										<!-- </div> -->
									</div>
								</div>
							</div>
							<div class="card-footer">
								<button type="submit" class="btn btn-primary">Save</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			</div>
			<div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="payment-tab">
				<div class="row">            	
					<div class="col-sm-12">
						<table class="w-full border-collapse border border-gray-300">
							<thead>
								<tr class="bg-gray-200">
									<th class="border p-2">Select</th>
									<th class="border p-2">Employee Name</th>
									<th class="border p-2">Manual Balance</th>
								</tr>
							</thead>
							<tbody>
								@foreach($employees as $employee)
								@php
								$leaveBalance = \App\Models\LeaveBalance::where('user_id', $employee->id)
									->where('leave_type_id', $leaveType->id)
									->where('leave_year', date('Y'))
									->first();
								@endphp
									<tr>
										<td class="border p-2 text-center">
											<input type="checkbox" name="users[{{ $employee->id }}][selected]" value="1">
										</td>
										<td class="border p-2">{{ $employee->name }}</td>
								
										<td class="border p-2">
											<input value="{{ $leaveBalance->balance ?? 0 }}" type="number" name="users[{{ $employee->id }}][balance]" step="0.01" class="border p-2 w-full" placeholder="Enter Balance">
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
						<div class="card-footer">
							<button type="submit" class="btn mt-4 btn-primary">Save</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>

	</div>
</div>     
@endsection