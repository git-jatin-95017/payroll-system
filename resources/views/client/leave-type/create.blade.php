@extends('layouts.app')
@push('page_css')
	<style>
		thead input.top-filter {
			width: 100%;
		}

		table.dataTable tbody td {
			word-break: break-word;
			vertical-align: top;
		}
	</style>
	<link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap4.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/responsive.bootstrap4.min.css') }}">
@endpush
@section('content')
<div class="row page-titles">
	<div class="col-md-5 align-self-center">
		<h3 class="text-themecolor">
			<i class="fa fa-braille" style="color:#1976d2"></i>
			Leave Types
		</h3>
	</div>

	<div class="col-md-7 align-self-center">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="javascript:void(0)">Home</a>
			</li>
			<li class="breadcrumb-item active">Leave Policies</li>
		</ol>
	</div>
</div>
<section class="content">
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
		<div class="row">            	
			<div class="col-sm-6">
				<div class="card">
					<div class="card-header">
						<h3 class="card-title">Leave Policies</h3>
					</div>
					<form class="form-horizontal" method="POST" action="{{ route('leave-type.store') }}">
						@csrf
						<div class="card-body">								
							<div class="form-group">
								<label for="name" class="col-md-8 control-label">Leave Type</label>
								<div class="col-md-12">
									<input id="name" type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name', '') }}">

									@if ($errors->has('name'))
										<span class="text-danger">
											{{ $errors->first('name') }}
										</span>
									@endif
								</div>
							</div>								
												
							<div class="form-group">
								<label for="no_of_day" class="col-md-8 control-label">Number Of Days</label>
								<div class="col-md-12">
									<input id="no_of_day" type="number" min="0" class="form-control {{ $errors->has('no_of_day') ? ' is-invalid' : '' }}" name="no_of_day" value="{{ old('no_of_day', '') }}">

									@if ($errors->has('no_of_day'))
										<span class="text-danger">
											{{ $errors->first('no_of_day') }}
										</span>
									@endif
								</div>
							</div>

							<div class="form-group">
								<label for="start_days" class="col-md-8 control-label">Start (after hire date)</label>
								<div class="col-md-12">
									<input id="start_days" type="number" min="0" class="form-control {{ $errors->has('start_days') ? ' is-invalid' : '' }}" name="start_days" value="{{ old('start_days', '') }}">

									@if ($errors->has('start_days'))
										<span class="text-danger">
											{{ $errors->first('start_days') }}
										</span>
									@endif
								</div>
							</div>

							<div class="form-group">
								<label for="carry_over_amount" class="col-md-8 control-label">Carryover Amount</label>
								<div class="col-md-12">
									<input id="carry_over_amount" type="number" min="0" class="form-control {{ $errors->has('carry_over_amount') ? ' is-invalid' : '' }}" name="carry_over_amount" value="{{ old('carry_over_amount', '') }}">

									@if ($errors->has('carry_over_amount'))
										<span class="text-danger">
											{{ $errors->first('carry_over_amount') }}
										</span>
									@endif
								</div>
							</div>

							<div class="form-group">
								<label for="date_after" class="col-md-8 control-label">Carryover Date</label>
								<div class="col-md-12">
									<?php $year = date('Y')+1; ?>
									<!-- <select class="form-control" name="date_after">
										<option value="">Please Select</option>
										<option value="{{ date('Y-12-31') }}">{{ date('31/12/Y') }}</option>
										<option value="{{$year}}-{{ date('-01-01') }}">{{ date('01/01/') }}{{$year}}</option>
									</select> -->
									<input id="date_after" type="text" readonly class="form-control {{ $errors->has('date_after') ? ' is-invalid' : '' }}" name="date_after" value="{{ date('Y-m-31') }}" readnly>

									@if ($errors->has('date_after'))
										<span class="text-danger">
											{{ $errors->first('date_after') }}
										</span>
									@endif
								</div>
							</div>


							<div class="form-group">
								<label for="status" class="col-md-8 control-label">Status</label>
								<div class="col-md-12">
									<select class="form-control" name="status">
										<option value="">Please Select</option>
										<option value="1">Active</option>
										<option value="0">Inactive</option>
									</select>

									@if ($errors->has('status'))
										<span class="text-danger">
											{{ $errors->first('status') }}
										</span>
									@endif
								</div>
							</div>									
						</div>
						<div class="card-footer">
							<button type="submit" class="btn btn-primary">Save</button>
							<a href="{{ route('leave-type.index' )}}" class="btn btn-info">Back</a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>		
</section>    
@endsection