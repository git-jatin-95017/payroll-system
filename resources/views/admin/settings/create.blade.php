@php
   if(auth()->user()->role_id == 3) {
      $layoutDirectory = 'layouts.employee';
   } else {
      $layoutDirectory = 'layouts.app';
   }
@endphp

@extends($layoutDirectory)

@section('content')
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">
            <i class="fa fa-braille" style="color:#1976d2"></i>
            Settings
        </h3>
    </div>

    <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="javascript:void(0)">Home</a>
            </li>
            <li class="breadcrumb-item active">Settings</li>
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
					<form class="form-horizontal" method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
					@csrf
					<div class="card">
						<div class="card-header">
							<h3 class="card-title">Edit Settings</h3>
						</div>								
						<div class="card-body">
							<div class="form-group">
								<label for="medical_less_60" class="col-sm-8 control-label">Medical Benefits (DOB <= 60 )</label>
								<div class="col-md-6">
									<input id="medical_less_60" type="text" class="form-control {{ $errors->has('medical_less_60') ? ' is-invalid' : '' }}" name="medical_less_60" value="{{ $settings->medical_less_60 }}">

									@if ($errors->has('medical_less_60'))
										<span class="text-danger">
											{{ $errors->first('medical_less_60') }}
										</span>
									@endif
								</div>
							</div>
							<div class="form-group">
								<label for="medical_gre_60" class="col-sm-8 control-label">Medical Benefits (DOB > 60 && DOB <=79 )</label>
								<div class="col-md-6">
									<input id="medical_gre_60" type="text" class="form-control {{ $errors->has('medical_gre_60') ? ' is-invalid' : '' }}" name="medical_gre_60" value="{{ $settings->medical_gre_60 }}">

									@if ($errors->has('medical_gre_60'))
										<span class="text-danger">
											{{ $errors->first('medical_gre_60') }}
										</span>
									@endif
								</div>
							</div>

							<div class="form-group">
								<label for="social_security" class="col-sm-8 control-label">Social Security</label>
								<div class="col-md-6">
									<input id="social_security" type="text" class="form-control {{ $errors->has('social_security') ? ' is-invalid' : '' }}" name="social_security" value="{{ $settings->social_security }}">

									@if ($errors->has('social_security'))
										<span class="text-danger">
											{{ $errors->first('social_security') }}
										</span>
									@endif
								</div>
							</div>
							<div class="form-group">
								<label for="social_security_employer" class="col-sm-8 control-label">Social Security (Employer)</label>
								<div class="col-md-6">
									<input id="social_security_employer" type="text" class="form-control {{ $errors->has('social_security_employer') ? ' is-invalid' : '' }}" name="social_security_employer" value="{{ $settings->social_security_employer }}">

									@if ($errors->has('social_security_employer'))
										<span class="text-danger">
											{{ $errors->first('social_security_employer') }}
										</span>
									@endif
								</div>
							</div>
							<div class="form-group">
								<label for="education_levy" class="col-sm-8 control-label">Education Levy</label>
								<div class="col-md-6">
									<input id="education_levy" type="text" class="form-control {{ $errors->has('education_levy') ? ' is-invalid' : '' }}" name="education_levy" value="{{ $settings->education_levy }}">

									@if ($errors->has('education_levy'))
										<span class="text-danger">
											{{ $errors->first('education_levy') }}
										</span>
									@endif
								</div>
							</div>
						</div>
						<div class="card-footer">
							<button type="submit" class="btn btn-primary">Submit</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection