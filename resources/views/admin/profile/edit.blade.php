@extends('layouts.app')

@section('content')
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">
            <i class="fa fa-braille" style="color:#1976d2"></i>
            My Profile
        </h3>
    </div>

    <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="javascript:void(0)">Home</a>
            </li>
            <li class="breadcrumb-item active">My Profile</li>
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
					<form class="form-horizontal" method="POST" action="{{ route('edit-my-profile.update', auth()->user()->id) }}">
					@csrf
					{{ method_field('PUT') }}
					<div class="card">
						<div class="card-header">
							<h3 class="card-title">Edit My Profile</h3>
						</div>								
						<div class="card-body">
							<div class="form-group">
								<label for="name" class="col-md-4 control-label">Name</label>
								<div class="col-md-6">
									<input id="name" type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ auth()->user()->name }}">

									@if ($errors->has('name'))
										<span class="text-danger">
											{{ $errors->first('name') }}
										</span>
									@endif
								</div>
							</div>
							<div class="form-group">
								<label for="email" class="col-md-4 control-label">Email</label>
								<div class="col-md-6">
									<input id="email" type="text" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ auth()->user()->email }}">

									@if ($errors->has('email'))
										<span class="text-danger">
											{{ $errors->first('email') }}
										</span>
									@endif
								</div>
							</div>
							<div class="form-group">
								<label for="name" class="col-md-4 control-label">Phone Number</label>
								<div class="col-md-6">
									<input id="phone_number" type="text" class="form-control {{ $errors->has('phone_number') ? ' is-invalid' : '' }}" name="phone_number" value="{{ auth()->user()->phone_number }}">

									@if ($errors->has('phone_number'))
										<span class="text-danger">
											{{ $errors->first('phone_number') }}
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
				<div class="col-sm-6">
					<div class="card">
						<div class="card-header">
							<h3 class="card-title">Change Password</h3>
						</div>								
						<div class="card-body">
							<div class="form-group">
								<label for="old_password" class="col-md-4 control-label">Old Password</label>
								<div class="col-md-6">
									<input id="old_password" type="old_password" class="form-control {{ $errors->has('old_password') ? ' is-invalid' : '' }}" name="old_password">

									@if ($errors->has('password'))
										<span class="text-danger">
											{{ $errors->first('password') }}
										</span>
									@endif
								</div>
							</div>
							<div class="form-group">
								<label for="password" class="col-md-4 control-label">Password</label>
								<div class="col-md-6">
									<input id="password" type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password">

									@if ($errors->has('password'))
										<span class="text-danger">
											{{ $errors->first('password') }}
										</span>
									@endif
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-6">
									<label for="password-confirm" >Confirm Password</label>
									<input id="password-confirm" type="password" class="form-control" name="password_confirmation">
								</div>
							</div>								
						</div>
						<div class="card-footer">
							<button type="submit" class="btn btn-primary">Submit</button>
						</div>					
					</div>
					</form>
				</div>
			</div>
		</div>
	</section>
@endsection