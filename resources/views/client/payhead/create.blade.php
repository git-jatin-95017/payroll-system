@extends('layouts.new_layout')
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

<div>
	<div class="page-heading d-flex justify-content-between align-items-center gap-3 mb-3">
		<div>
			<h3>Manage Pay Labels</h3>
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
		<div class="row">            	
				<div class="col-sm-12">
					<div class="max-w-md max-auto">
						<div class="sub-text-heading pb-4">
							<h3 class="mb-1">Pay Label</h3>
							<!-- <p>Add your pay label information here</p> -->
						</div>
						<form class="form-horizontal" method="POST" action="{{ route('pay-head.store') }}">
							@csrf
							<div class="row">
								<div class="col-8 mb-3">
									<div class="form-group">
										<label for="title" class="db-label">Pay Label Name</label>
										<input id="name" type="text" class="form-control  db-custom-input {{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name', '') }}">
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
										<label for="description" class="db-label">Pay Label Type</label>
										<select class="form-control db-custom-input" id="pay_type" name="pay_type">
											<option @if(old('pay_type', '') == "earnings") selected @endif value="earnings">Addition to Gross Pay</option>
											<option @if(old('pay_type', '') == "deductions") selected @endif value="deductions">Deduction from Net Pay</option>
											<option @if(old('pay_type', '') == "nothing") selected @endif value="nothing">Addition to Net Pay</option>
										</select>
										@if ($errors->has('pay_type'))
											<span class="text-danger">
												{{ $errors->first('pay_type') }}
											</span>
										@endif
									</div>
								</div>
							</div>
   							
							<div class="row">
								<div class="col-8 mb-3">
									<div class="form-group">
										<label for="holiday_date" class="db-label">Pay Label Description</label>
										<input id="description" type="text" class="form-control db-custom-input {{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" value="{{ old('description', '') }}">

										@if ($errors->has('description'))
											<span class="text-danger">
												{{ $errors->first('description') }}
											</span>
										@endif
									</div>
								</div>
							</div>
							<div class="card-footer">
								<button type="submit" class="btn btn-primary">Save</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>   
@endsection