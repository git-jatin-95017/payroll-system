@extends('layouts.app')

@section('content')
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">Manage Clients</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="#">Home</a></li>
						<li class="breadcrumb-item"><a href="#">Clients</a></li>
						<li class="breadcrumb-item active">Add New Client</li>
					</ol>
				</div>
			</div>
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
				<div class="col-sm-12">
					<div class="card card-primary">
						<div class="card-header">
							<h3 class="card-title">Add New Client</h3>
							<div class="card-tools">
								<div class="input-group input-group-sm">
									<a href="{{ route('client.create' )}}" class="btn btn-primary">Add New</a>
								</div>
							</div>
						</div>
						<form class="form-horizontal" method="POST" action="{{ route('client.store') }}">
							@csrf
							<div class="card-body">
								<div class="form-group">
									<label for="name" class="col-md-4 control-label">Name</label>
									<div class="col-md-6">
										<input id="name" type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name', '') }}">

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
										<input id="email" type="text" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email', '') }}">

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
										<input id="phone_number" type="text" class="form-control {{ $errors->has('phone_number') ? ' is-invalid' : '' }}" name="phone_number" value="{{ old('phone_number', '') }}">

										@if ($errors->has('phone_number'))
											<span class="text-danger">
												{{ $errors->first('phone_number') }}
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
									<label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>
									<div class="col-md-6">
										<input id="password-confirm" type="password" class="form-control" name="password_confirmation">
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
	</section>
@endsection