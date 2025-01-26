@php
   if(auth()->user()->role_id == 3) {
      $layoutDirectory = 'layouts.new_layout';
   } else {
      $layoutDirectory = 'layouts.new_layout';
   }
@endphp

@extends($layoutDirectory)

@section('content')
<div>
	<div class="page-heading d-flex justify-content-between align-items-center gap-3 mb-3">
		<div>
			<h3>Calculations</h3>
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
							<h3 class="mb-1">Calculations</h3>
							<!-- <p>Enter your information here</p> -->
						</div>
						<form class="form-horizontal" method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
							@csrf
							<div class="row">
								<div class="col-8 mb-3">
									<div class="form-group">
										<label for="title" class="db-label">Medical Benefits (DOB <= 60 )</label>
										<!-- <div class="col-md-6"> -->
											<input id="medical_less_60" type="text" class="form-control db-custom-input {{ $errors->has('medical_less_60') ? ' is-invalid' : '' }}" name="medical_less_60" value="{{ $settings->medical_less_60 }}">

											@if ($errors->has('medical_less_60'))
												<span class="text-danger">
													{{ $errors->first('medical_less_60') }}
												</span>
											@endif
										<!-- </div> -->
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-8 mb-3">
									<div class="form-group">
										<label for="description" class="db-label">Medical Benefits (DOB > 60 && DOB <=79 )</label>
										<!-- <div class="col-md-6"> -->
										<input id="medical_gre_60" type="text" class="form-control db-custom-input {{ $errors->has('medical_gre_60') ? ' is-invalid' : '' }}" name="medical_gre_60" value="{{ $settings->medical_gre_60 }}">

											@if ($errors->has('medical_gre_60'))
												<span class="text-danger">
													{{ $errors->first('medical_gre_60') }}
												</span>
											@endif
										<!-- </div> -->
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-8 mb-3">
									<div class="form-group">
										<label for="social_security" class="db-label">Social Security (Employee)</label>
										<!-- <div class="col-md-6"> -->
										<input id="social_security" type="text" class="form-control db-custom-input {{ $errors->has('social_security') ? ' is-invalid' : '' }}" name="social_security" value="{{ $settings->social_security }}">

											@if ($errors->has('social_security'))
												<span class="text-danger">
													{{ $errors->first('social_security') }}
												</span>
											@endif
										<!-- </div> -->
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-8 mb-3">
									<div class="form-group">
										<label for="social_security_employer" class="db-label">Social Security (Employer)</label>
										<!-- <div class="col-md-6"> -->
										<input id="social_security_employer" type="text" class="form-control db-custom-input {{ $errors->has('social_security_employer') ? ' is-invalid' : '' }}" name="social_security_employer" value="{{ $settings->social_security_employer }}">

											@if ($errors->has('social_security_employer'))
												<span class="text-danger">
													{{ $errors->first('social_security_employer') }}
												</span>
											@endif
										<!-- </div> -->
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-8 mb-3">
									<div class="form-group">
										<label for="education_levy" class="db-label">Education Levy</label>
											<!-- <div class="col-md-6"> -->
											<input id="education_levy" type="text" class="form-control db-custom-input {{ $errors->has('education_levy') ? ' is-invalid' : '' }}" name="education_levy" value="{{ $settings->education_levy }}">

											@if ($errors->has('education_levy'))
												<span class="text-danger">
													{{ $errors->first('education_levy') }}
												</span>
											@endif
										<!-- </div> -->
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-8 mb-3">
									<div class="form-group">
										<label for="education_levy_amt_5" class="db-label">Education Levy</label>
										<!-- <div class="col-md-6"> -->
											<input id="education_levy_amt_5" type="text" class="form-control db-custom-input {{ $errors->has('education_levy_amt_5') ? ' is-invalid' : '' }}" name="education_levy_amt_5" value="{{ $settings->education_levy_amt_5 }}">

											@if ($errors->has('education_levy_amt_5'))
												<span class="text-danger">
													{{ $errors->first('education_levy_amt_5') }}
												</span>
											@endif
										<!-- </div> -->
									</div>
								</div>
							</div>
   							
							<div class="card-footer">
								<button type="submit" class="btn btn-primary">Submit</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection