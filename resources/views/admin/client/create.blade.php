@extends('layouts.app')

@section('content')
<div class="row page-titles">
	<div class="col-md-5 align-self-center">
		<h3 class="text-themecolor">
			<i class="fa fa-braille" style="color:#1976d2"></i>
			Manage Clients
		</h3>
	</div>

	<div class="col-md-7 align-self-center">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="javascript:void(0)">Home</a>
			</li>
			<li class="breadcrumb-item"><a href="#">Clients</a></li>
			<li class="breadcrumb-item active">Add New Client</li>
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
				<div class="col-sm-12">
					<form class="form-horizontal" method="POST" action="{{ route('client.store') }}" enctype="multipart/form-data">
						@csrf
					<div class="card">
						<div class="card-header p-2">
							<ul class="nav nav-pills">
								<li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Company Information</a></li>
								<li class="nav-item"><a class="nav-link" href="#payment-method" data-toggle="tab">Bank Details</a></li>
								<li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Administrators</a></li>
							</ul>
						</div>									
							<div class="card-body">
								<div class="tab-content">
									<div class="tab-pane active" id="activity">
										<div class="form-row mb-3">
											<div class="col-md-4">
												<label for="name" >Upload Logo</label>
												<input id="file2" type="file" class="form-control {{ $errors->has('logo') ? ' is-invalid' : '' }}" name="logo" value="{{ old('logo', '') }}" >

												@if ($errors->has('logo'))
													<span class="text-danger">
														{{ $errors->first('logo') }}
													</span>
												@endif
											</div>												
										</div>
										<hr>	
										<div class="form-row mb-3">
											<div class="col-md-4">
												<label for="name">Company Name</label>
												<input id="company_name" type="text" class="form-control {{ $errors->has('company_name') ? ' is-invalid' : '' }}" name="company_name">

												@if ($errors->has('company_name'))
													<span class="text-danger">
														{{ $errors->first('company_name') }}
													</span>
												@endif
											</div>
											<div class="col-md-4">
												<label for="name" >Country</label>
												<input id="country" type="text" class="form-control {{ $errors->has('country') ? ' is-invalid' : '' }}" name="country" >

												@if ($errors->has('country'))
													<span class="text-danger">
														{{ $errors->first('country') }}
													</span>
												@endif
											</div>
											<div class="col-md-4">
												<label for="name" >City</label>
												<input id="city" type="text" class="form-control {{ $errors->has('city') ? ' is-invalid' : '' }}" name="city" >

												@if ($errors->has('city'))
													<span class="text-danger">
														{{ $errors->first('city') }}
													</span>
												@endif
											</div>					
										</div>
										<div class="form-row mb-3">
											<div class="col-md-12">
												<label for="name" >Address</label>
												<textarea name="address" id="address" class="form-control {{ $errors->has('address') ? ' is-invalid' : '' }}" rows="4" ></textarea>

												@if ($errors->has('address'))
													<span class="text-danger">
														{{ $errors->first('address') }}
													</span>
												@endif
											</div>
										</div>
										<div class="form-row mb-3">
											<div class="col-md-4">
												<label for="name" >Phone Number</label>
												<input id="phone_number" type="text" class="form-control {{ $errors->has('phone_number') ? ' is-invalid' : '' }}" name="phone_number" >

												@if ($errors->has('phone_number'))
													<span class="text-danger">
														{{ $errors->first('phone_number') }}
													</span>
												@endif
											</div>	
											<div class="col-md-4">
												<label for="email_address" >Email address</label>
												<input id="email_address" type="text" class="form-control {{ $errors->has('email_address') ? ' is-invalid' : '' }}" name="email_address">

												@if ($errors->has('email_address'))
													<span class="text-danger">
														{{ $errors->first('email_address') }}
													</span>
												@endif
											</div>
										</div>										
										<div class="form-row mb-3">
											<div class="col-md-4">
												<label for="name" >Medical Benefits Registration Number</label>
												<input id="medical_no" type="text" class="form-control {{ $errors->has('medical_no') ? ' is-invalid' : '' }}" name="medical_no"  >

												@if ($errors->has('medical_no'))
													<span class="text-danger">
														{{ $errors->first('medical_no') }}
													</span>
												@endif
											</div>
											<div class="col-md-4">
												<label for="name" >Social Security Registration Number </label>
												<input id="ssr_no" type="text" class="form-control {{ $errors->has('ssr_no') ? ' is-invalid' : '' }}" name="ssr_no">

												@if ($errors->has('ssr_no'))
													<span class="text-danger">
														{{ $errors->first('ssr_no') }}
													</span>
												@endif
											</div>
											<div class="col-md-4">
												<label for="name" >Education Levy ID Number</label>
												<input id="levy_id_no" type="text" class="form-control {{ $errors->has('levy_id_no') ? ' is-invalid' : '' }}" name="levy_id_no" >

												@if ($errors->has('levy_id_no'))
													<span class="text-danger">
														{{ $errors->first('levy_id_no') }}
													</span>
												@endif
											</div>
										</div>
										<hr>
										<div class="form-row mb-3">
											<div class="col-md-4">
												<label for="passwordc" >Client Login Password</label>
												<input id="passwordc" type="passwordc" class="form-control {{ $errors->has('passwordc') ? ' is-invalid' : '' }}" name="passwordc">

												@if ($errors->has('passwordc'))
													<span class="text-danger">
														{{ $errors->first('passwordc') }}
													</span>
												@endif
											</div>
											<!-- <div class="col-md-4">
												<label for="password-confirm" >Confirm Password</label>
												<input id="password-confirm" type="password" class="form-control" name="password_confirmation">
											</div> -->
										</div>
									</div>
									
									<div class="tab-pane" id="payment-method">
										<div class="form-row mb-3">
											<div class="col-md-4">
												<label for="bank_name" class="control-label">Bank Name</label>
												<div class="form-group mb-0">
													<input id="bank_name" type="bank_name" class="form-control {{ $errors->has('bank_name') ? ' is-invalid' : '' }}" name="bank_name" >

													@if ($errors->has('bank_name'))
														<span class="text-danger">
															{{ $errors->first('bank_name') }}
														</span>
													@endif
												</div>
											</div>
											<div class="col-md-4">
												<label for="bank_address" class="control-label">Bank Address</label>
												<div class="form-group mb-0">
													<input id="bank_address" type="bank_address" class="form-control {{ $errors->has('bank_address') ? ' is-invalid' : '' }}" name="bank_address" >

													@if ($errors->has('bank_address'))
														<span class="text-danger">
															{{ $errors->first('bank_address') }}
														</span>
													@endif
												</div>
											</div>
										</div>
										<div class="form-row mb-3">	
											<div class="col-md-4" id="account_number_div">
												<label for="account_number" class="control-label">Account Number</label>
												<div class="form-group mb-0">
													<input id="account_number" type="account_number" class="form-control {{ $errors->has('account_number') ? ' is-invalid' : '' }}" name="account_number" >

													@if ($errors->has('account_number'))
														<span class="text-danger">
															{{ $errors->first('account_number') }}
														</span>
													@endif
												</div>
											</div>							
											<div class="col-md-4" id="routing_number_div">
												<label for="routing_number" class="control-label">Routing Number</label>
												<div class="form-group mb-0">
													<input id="routing_number" type="routing_number" class="form-control {{ $errors->has('routing_number') ? ' is-invalid' : '' }}" name="routing_number">

													@if ($errors->has('routing_number'))
														<span class="text-danger">
															{{ $errors->first('routing_number') }}
														</span>
													@endif
												</div>
											</div>																			
										</div>
									</div>

									<div class="tab-pane" id="settings">
										<div id="dynamicRowsContainer">
									        <div class="form-row mb-3">
									            <div class="col-md-3">
									                <label for="name" class="col-md-4 control-label">Name</label>
									                <div class="col-md-12">
									                    <input id="name" type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" name="name[]">

									                    @if ($errors->has('name'))
									                        <span class="text-danger">
									                            {{ $errors->first('name') }}
									                        </span>
									                    @endif
									                </div>
									            </div>
									            <div class="col-md-3">
									                <label for="email">Email address</label>
									                <input type="text" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email[]">

									                @if ($errors->has('email'))
									                    <span class="text-danger">
									                        {{ $errors->first('email') }}
									                    </span>
									                @endif
									            </div>
									            <div class="col-md-3">
									                <label for="password">New Password</label>
									                <input type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password[]">

									                    @if ($errors->has('password'))
									                        <span class="text-danger">
									                            {{ $errors->first('password') }}
									                        </span>
									                    @endif
									            </div>
									            <div class="col-md-3">
									                <label for="password-confirm">Confirm Password</label>
									                <input type="password" class="form-control" name="password_confirmation[]">
									            </div>
									        </div>
									    </div>
										<div class="form-group">
											<div class="col-md-12">
												<button type="button" id="addNewRow" class="btn btn-primary">Add New</button>
											</div>
										</div>	
									</div>
								</div>								
							</div>							
					</div>
					<div class="card-footer">
						<button type="submit" class="btn btn-primary">Submit</button>
						<a href="{{ route('client.index' )}}" class="btn btn-info">Back</a>
					</div>
					</form>
				</div>
			</div>
		</div>
	</section>
@endsection
@push('page_scripts')
<script>
    $(document).ready(function () {
    	var maxRows = 10;
        var rowNum = 1;
        $("#addNewRow").on("click", function () {
        	 if (rowNum < maxRows) {
                rowNum++;
	            var newRow = '<div class="form-row mb-3">' +
	                '   <div class="col-md-3">' +
	                '       <label for="name" class="col-md-4 control-label">Name</label>' +
	                '       <div class="col-md-12">' +
	                '           <input type="text" class="form-control" name="name[]">' +
	                '       </div>' +
	                '   </div>' +
	                '   <div class="col-md-3">' +
	                '       <label for="email">Email address</label>' +
	                '       <input type="text" class="form-control" name="email[]">' +
	                '   </div>' +
	                '   <div class="col-md-3">' +
	                '       <label for="password">New Password</label>' +
	                '       <input type="password" class="form-control" name="password[]">' +
	                '   </div>' +
	                '   <div class="col-md-3">' +
	                '       <label for="password-confirm">Confirm Password</label>' +
	                '       <input type="password" class="form-control" name="password_confirmation[]">' +
	                '   </div>' +
	                '</div>';
            
            	$("#dynamicRowsContainer").append(newRow);
            } else {
                alert('You cannot add more than ' + maxRows + ' admins.');
            }
        });
    });
</script>
<script>
	function showDiv(obj) {
		if ($(obj).val() == 'check') {
			$('#routing_number_div').addClass('d-none');
			$('#account_number_div').addClass('d-none');
			$('#account_type_div').addClass('d-none');
		}

		if ($(obj).val() == 'deposit') {
			$('#routing_number_div').removeClass('d-none');
			$('#account_number_div').removeClass('d-none');
			$('#account_type_div').removeClass('d-none');
		}
	}
</script>
@endpush