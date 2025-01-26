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
			<h3>Profile</h3>
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
							<h3 class="mb-1">Profile</h3>
							<!-- <p>Enter your information here</p> -->
						</div>
						<form class="form-horizontal" method="POST" action="{{ route('edit-my-profile.update', auth()->user()->id) }}" enctype="multipart/form-data">
						@csrf
						{{ method_field('PUT') }}
							<div class="row">
								<div class="col-8 mb-3">
									<div class="form-group">
										<label for="title" class="db-label">Name</label>
										<input id="name" type="text" class="form-control db-custom-input {{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ auth()->user()->name }}">
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
										<label for="description" class="db-label">Medical Benefits (DOB > 60 && DOB <=79 )</label>
										<!-- <div class="col-md-6"> -->
										<input id="medical_gre_60" type="text" class="form-control db-custom-input {{ $errors->has('medical_gre_60') ? ' is-invalid' : '' }}" name="medical_gre_60" value="{{ auth()->user()->name }}">

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
										<label for="social_security" class="db-label">Email</label>
										<input id="email" type="text" class="form-control  db-custom-input {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"  value="{{ auth()->user()->email }}">

											@if ($errors->has('email'))
												<span class="text-danger">
													{{ $errors->first('email') }}
												</span>
											@endif
									</div>
								</div>
							</div>
   							
							<div class="row">
								<div class="col-8 mb-3">
									<div class="form-group">
										<label for="social_security" class="db-label">Phone Number</label>
										<input id="phone_number" type="text" class="form-control db-custom-input {{ $errors->has('phone_number') ? ' is-invalid' : '' }}" name="phone_number" value="{{ auth()->user()->phone_number }}">

											@if ($errors->has('phone_number'))
												<span class="text-danger">
													{{ $errors->first('phone_number') }}
												</span>
											@endif
									</div>
								</div>
							</div>


							<div class="row">
								<div class="col-8 mb-3">
									<div class="form-group">
										<label for="social_security" class="db-label">Old Password</label>
										<input id="old_password" type="old_password" class="form-control db-custom-input {{ $errors->has('old_password') ? ' is-invalid' : '' }}" name="old_password">

											@if ($errors->has('old_password'))
												<span class="text-danger">
													{{ $errors->first('old_password') }}
												</span>
											@endif
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-8 mb-3">
									<div class="form-group">
										<label for="social_security" class="db-label">Password</label>
										<input id="password" type="password" class="form-control db-custom-input  {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password">

											@if ($errors->has('password'))
												<span class="text-danger">
													{{ $errors->first('password') }}
												</span>
											@endif
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-8 mb-3">
									<div class="form-group">
										<label for="social_security" class="db-label">Confirm Password</label>
										<input id="password-confirm" type="password" class="form-control db-custom-input " name="password_confirmation">

											@if ($errors->has('password'))
												<span class="text-danger">
													{{ $errors->first('password') }}
												</span>
											@endif
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