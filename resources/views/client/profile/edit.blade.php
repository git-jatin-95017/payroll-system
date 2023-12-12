@extends('layouts.app')

@section('content')

<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">
            <i class="mdi mdi-account-outline" style="color:#1976d2"></i>
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
				<div class="col-md-12">
					<div class="card">
						<div class="card-header p-2">
							<ul class="nav nav-pills">
								<li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Company Information</a></li>
								<li class="nav-item"><a class="nav-link" href="#payment-method" data-toggle="tab">Payment Method</a></li>
								<li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Administrators</a></li>
							</ul>
						</div>
						<div class="card-body">
							<div class="tab-content">
								<div class="tab-pane active" id="activity">
									<form class="form-horizontal" method="POST" action="{{ route('my-profile.update', auth()->user()->id) }}" enctype="multipart/form-data">
									@csrf
									{{ method_field('PUT') }}
										<input type="hidden" name="update_request" value="personal">
										<!-- <div class="form-row mb-3">
											<div class="col-md-4">
												<label for="name" >Upload Image</label>
												<input id="imgInp" type="file" class="form-control {{ $errors->has('file') ? ' is-invalid' : '' }}" name="file" value="{{ old('file', '') }}">

												@if ($errors->has('file'))
													<span class="text-danger">
														{{ $errors->first('file') }}
													</span>
												@endif

											</div>
											<div class="col-md-4">											
												@if(!empty($company->companyProfile->file))
												<img id="blah" src="/files/{{$company->companyProfile->file}}" class="img-thumbnail"
												style="object-fit: contain;width: 150px; height: 80px;" />
												@else
													<img id="blah" src="#" alt="image" class="img-thumbnail d-none" style="object-fit: contain;width: 150px; height: 80px;" />
												@endif								
											</div>													
										</div> -->
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
											<div class="col-md-4">
												@if(!empty($company->companyProfile->logo))
												<img src="/files/{{$company->companyProfile->logo}}" class="img-thumbnail"
												style="object-fit: contain;width: 150px; height: 80px;" />
												@endif
											</div>													
										</div>
										<hr>
										<div class="form-row mb-3">
											<div class="col-md-4">
												<label for="name">Company Name</label>
												<input id="company_name" type="text" class="form-control {{ $errors->has('company_name') ? ' is-invalid' : '' }}" name="company_name" value="{{ !empty($company->companyProfile->company_name) ? $company->companyProfile->company_name : auth()->user()->name }}" >

												@if ($errors->has('company_name'))
													<span class="text-danger">
														{{ $errors->first('company_name') }}
													</span>
												@endif
											</div>
											<div class="col-md-4">
												<label for="name" >Country</label>
												<input id="country" type="text" class="form-control {{ $errors->has('country') ? ' is-invalid' : '' }}" name="country" value="{{ $company->companyProfile->country ?? NULL }}" >

												@if ($errors->has('country'))
													<span class="text-danger">
														{{ $errors->first('country') }}
													</span>
												@endif
											</div>
											<div class="col-md-4">
												<label for="name" >City</label>
												<input id="city" type="text" class="form-control {{ $errors->has('city') ? ' is-invalid' : '' }}" name="city" value="{{ $company->companyProfile->city ?? NULL}}" >

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
												<textarea name="address" id="address" class="form-control {{ $errors->has('address') ? ' is-invalid' : '' }}" rows="4" >{{ $company->companyProfile->address ?? NULL}}</textarea>

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
												<input id="phone_number" type="text" class="form-control {{ $errors->has('phone_number') ? ' is-invalid' : '' }}" name="phone_number" value="{{ $company->companyProfile->phone_number ?? NULL}}" >

												@if ($errors->has('phone_number'))
													<span class="text-danger">
														{{ $errors->first('phone_number') }}
													</span>
												@endif
											</div>	
											<div class="col-md-4">
												<label for="email" >Email address</label>
												<input id="email" type="text" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $company->email }}" >

												@if ($errors->has('email'))
													<span class="text-danger">
														{{ $errors->first('email') }}
													</span>
												@endif
											</div>
											<div class="col-md-4">
												<label for="my_pwd" class="col-md-4 control-label">My Password</label>
												<input id="my_pwd" type="my_pwd" class="form-control {{ $errors->has('my_pwd') ? ' is-invalid' : '' }}" name="my_pwd">

												@if ($errors->has('my_pwd'))
													<span class="text-danger">
														{{ $errors->first('my_pwd') }}
													</span>
												@endif
											</div>
										</div>
										<div class="form-row mb-3">
											<div class="col-md-4">
												<label for="name" >Medical Benefits Registration Number</label>
												<input id="medical_no" type="text" class="form-control {{ $errors->has('medical_no') ? ' is-invalid' : '' }}" name="medical_no" value="{{ $company->companyProfile->medical_no ?? NULL}}" >

												@if ($errors->has('medical_no'))
													<span class="text-danger">
														{{ $errors->first('medical_no') }}
													</span>
												@endif
											</div>
											<div class="col-md-4">
												<label for="name" >Social Security Registration Number </label>
												<input id="ssr_no" type="text" class="form-control {{ $errors->has('ssr_no') ? ' is-invalid' : '' }}" name="ssr_no" value="{{ $company->companyProfile->ssr_no ?? NULL}}" >

												@if ($errors->has('ssr_no'))
													<span class="text-danger">
														{{ $errors->first('ssr_no') }}
													</span>
												@endif
											</div>
											<div class="col-md-4">
												<label for="name" >Education Levy ID Number</label>
												<input id="levy_id_no" type="text" class="form-control {{ $errors->has('levy_id_no') ? ' is-invalid' : '' }}" name="levy_id_no" value="{{ $company->companyProfile->levy_id_no?? NULL }}" >

												@if ($errors->has('levy_id_no'))
													<span class="text-danger">
														{{ $errors->first('levy_id_no') }}
													</span>
												@endif
											</div>
										</div>										
										<div class="form-group row ">
											<div class="col-12">
												<button type="submit" class="btn btn-primary">Submit</button>
											</div>
										</div>
									</form>
								</div>								
								<div class="tab-pane" id="payment-method">
									<form class="form-horizontal" method="POST" action="{{ route('my-profile.update', auth()->user()->id) }}">
									@csrf
									{{ method_field('PUT') }}
										<input type="hidden" name="update_request" value="payment">
										<div class="form-row mb-3">
											<div class="col-md-4">
												<label for="name" >Payment Method</label>
												<select class="form-control {{ $errors->has('payment_method') ? ' is-invalid' : '' }}" id="payment_method" name="payment_method" onchange="showDiv(this)">
													<option value="" selected disabled>Please Select</option>
													<option @if(!empty($company->paymentProfile->payment_method) && $company->paymentProfile->payment_method == "check") selected @endif value="check">Cheque</option>
													<option @if(!empty($company->paymentProfile->payment_method) && $company->paymentProfile->payment_method == "Direct Deposit") selected @endif svalue="deposit">Direct Deposit</option>
												</select>
												@if ($errors->has('payment_method'))
													<span class="text-danger">
														{{ $errors->first('payment_method') }}
													</span>
												@endif
											</div>
											<div class="col-md-4 
												@if(empty($company->paymentProfile->routing_number)) d-none @endif" 												
												id="routing_number_div"
											>
												<div class="col-md-12">
													<label for="routing_number">Routing Number</label>
													<input id="routing_number" type="routing_number" class="form-control {{ $errors->has('routing_number') ? ' is-invalid' : '' }}" name="routing_number" value="{{ $company->paymentProfile->routing_number?? '' }}" >
													@if ($errors->has('routing_number'))
														<span class="text-danger">
															{{ $errors->first('routing_number') }}
														</span>
													@endif
												</div>
											</div>
											<div class="col-md-4 
												@if(empty($company->paymentProfile->account_number)) d-none @endif" 
												id="account_number_div">
												<div class="col-md-12">
													<label for="account_number">Account Number</label>
													<input id="account_number" type="account_number" class="form-control {{ $errors->has('account_number') ? ' is-invalid' : '' }}" name="account_number" value="{{ $company->paymentProfile->account_number ?? '' }}" >
													@if ($errors->has('account_number'))
														<span class="text-danger">
															{{ $errors->first('account_number') }}
														</span>
													@endif
												</div>
											</div>
										</div>
										<div class="form-row mb-3
											@if(empty($company->paymentProfile->account_type)) d-none @endif" 
											id="account_type_div">
											<div class="col-md-4">
												<label for="name" >Account Type</label>
												<select class="form-control {{ $errors->has('account_type') ? ' is-invalid' : '' }}" id="account_type" name="account_type">
													<option value="" disabled>Please Select</option>
													<option @if(!empty($company->paymentProfile->account_type) && $company->paymentProfile->account_type == "checking") selected @endif value="checking">Chequing</option>
													<option @if(!empty($company->paymentProfile->account_type) && $company->paymentProfile->account_type == "saving") selected @endif value="saving">Saving</option>
												</select>
												@if ($errors->has('account_type'))
													<span class="text-danger">
														{{ $errors->first('account_type') }}
													</span>
												@endif
											</div>
											<div class="col-md-4">
												<div class="col-md-12">
													<label for="bank_name">Bank Name</label>
													<input id="bank_name" type="bank_name" class="form-control {{ $errors->has('bank_name') ? ' is-invalid' : '' }}" name="bank_name" value="{{ $company->paymentProfile->bank_name ?? '' }}" >
													@if ($errors->has('bank_name'))
														<span class="text-danger">
															{{ $errors->first('bank_name') }}
														</span>
													@endif
												</div>
											</div>
										</div>
										<div class="form-group row ">
											<div class="col-12">
												<button type="submit" class="btn btn-primary">Submit</button>
											</div>
										</div>
									</form>
								</div>
								<div class="tab-pane" id="settings">
									<form class="form-horizontal" method="POST" action="{{ route('my-profile.update', auth()->user()->id) }}">
										@csrf
										{{ method_field('PUT') }}
										<input type="hidden" name="update_request" value="changepwd">
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
									                <input type="text" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email[]" value="">

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
										<!-- <div class="form-group">
											<label for="old_password" class="col-md-4 control-label">Current Password</label>
											<div class="col-md-12">
												<input id="old_password" type="password" class="form-control {{ $errors->has('old_password') ? ' is-invalid' : '' }}" name="old_password">

												@if ($errors->has('old_password'))
													<span class="text-danger">
														{{ $errors->first('old_password') }}
													</span>
												@endif
											</div>
										</div>
										<div class="form-group">
											<label for="password" class="col-md-4 control-label">New Password</label>
											<div class="col-md-12">
												<input id="password" type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password">

												@if ($errors->has('password'))
													<span class="text-danger">
														{{ $errors->first('password') }}
													</span>
												@endif
											</div>
										</div>
										<div class="form-group">
											<div class="col-md-12">
												<label for="password-confirm" >Confirm Password</label>
												<input id="password-confirm" type="password" class="form-control" name="password_confirmation">
											</div>
										</div>	 -->
										<div class="form-group">
											<div class="col-md-12">
												<button type="button" id="addNewRow" class="btn btn-primary">Add New</button>
												<button type="submit" class="btn btn-primary">Submit</button>
											</div>
										</div>
									</form>		
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection

@push('page_scripts')
<script>
    $(document).ready(function () {
    	var maxRows = 3;
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
		console.log(obj.value);
		if (obj.value == 'check') {
			$('#routing_number_div').addClass('d-none');
			$('#account_number_div').addClass('d-none');
			$('#account_type_div').addClass('d-none');
		}

		if (obj.value == 'Direct Deposit') {
			$('#routing_number_div').removeClass('d-none');
			$('#account_number_div').removeClass('d-none');
			$('#account_type_div').removeClass('d-none');
		}
	}
</script>
<script>

	imgInp.onchange = evt => {
	  const [file] = imgInp.files
	  if (file) {
	    blah.src = URL.createObjectURL(file)
	  }
	}
</script>
@endpush