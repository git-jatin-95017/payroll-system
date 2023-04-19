@extends('layouts.app')

@section('content')
<div class="row page-titles">
	<div class="col-md-5 align-self-center">
		<h3 class="text-themecolor">
			<i class="fa fa-braille" style="color:#1976d2"></i>
			Employees
		</h3>
	</div>

	<div class="col-md-7 align-self-center">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="javascript:void(0)">Home</a>
			</li>
			<li class="breadcrumb-item"><a href="#">Employee</a></li>
			<li class="breadcrumb-item active">Modify Employee</li>
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
					<form class="form-horizontal" method="POST" action="{{ route('employee.update', $employee->id) }}" enctype="multipart/form-data">
						@csrf
						{{ method_field('PUT') }}
					<div class="card">
						<div class="card-header p-2">
							<ul class="nav nav-pills">
								<li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Personal Information</a></li>
								<li class="nav-item"><a class="nav-link" href="#emp-details" data-toggle="tab">Employment Details</a></li>
								<li class="nav-item"><a class="nav-link" href="#payment-method" data-toggle="tab">Payment Method</a></li>
								<li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Password</a></li>
							</ul>
						</div>									
							<div class="card-body">
								<div class="tab-content">
									<div class="tab-pane active" id="activity">
										<div class="form-row mb-3">
											<div class="col-md-4">
												<label for="name" >Upload Image</label>
												<input id="file" type="file" class="form-control {{ $errors->has('file') ? ' is-invalid' : '' }}" name="file" value="{{ old('file', '') }}" @if($employee->is_proifle_edit_access == "1") disabled="disabled" @endif>

												@if ($errors->has('file'))
													<span class="text-danger">
														{{ $errors->first('file') }}
													</span>
												@endif

											</div>
											<div class="col-md-4">
												@if(!empty($employee->employeeProfile->file))
												<img src="/files/{{$employee->employeeProfile->file}}" class="img-thumbnail"
												style="object-fit: contain;width: 200px; height: 150px;" />
												@endif								
											</div>
											
										</div>
										<!-- <div class="form-row mb-3">
											<div class="col-md-4">
												<label for="name" >Upload Logo</label>
												<input id="file2" type="file" class="form-control {{ $errors->has('logo') ? ' is-invalid' : '' }}" name="logo" value="{{ old('logo', '') }}" @if($employee->is_proifle_edit_access == "1") disabled="disabled" @endif>

												@if ($errors->has('logo'))
													<span class="text-danger">
														{{ $errors->first('logo') }}
													</span>
												@endif

											</div>
											<div class="col-md-4">
												@if(!empty($employee->employeeProfile->logo))
												<img src="/files/{{$employee->employeeProfile->logo}}" class="img-thumbnail"
												style="object-fit: contain;width: 150px; height: 80px;" />
												@endif
											</div>													
										</div> -->
										<div class="form-row mb-3">
											<!-- <div class="col-md-4">
												<label for="name">Employee ID number</label>
												<input id="emp_code" type="text" class="form-control {{ $errors->has('emp_code') ? ' is-invalid' : '' }}" name="emp_code" value="{{ $employee->user_code }}" {{$disabled}}>

												@if ($errors->has('emp_code'))
													<span class="text-danger">
														{{ $errors->first('emp_code') }}
													</span>
												@endif
											</div> -->
											<div class="col-md-4">
												<label for="name">First Name</label>
												<input id="first_name" type="text" class="form-control {{ $errors->has('first_name') ? ' is-invalid' : '' }}" name="first_name"  value="{{ $employee->employeeProfile->first_name }}" {{$disabled}}>

												@if ($errors->has('first_name'))
													<span class="text-danger">
														{{ $errors->first('first_name') }}
													</span>
												@endif
											</div>
											<div class="col-md-4">
												<label for="name">Last Name</label>
												<input id="last_name" type="text" class="form-control {{ $errors->has('last_name') ? ' is-invalid' : '' }}" name="last_name" value="{{ $employee->employeeProfile->last_name }}" {{$disabled}}>

												@if ($errors->has('last_name'))
													<span class="text-danger">
														{{ $errors->first('last_name') }}
													</span>
												@endif
											</div>
											<div class="col-md-4">
												<label for="name" >Phone Number</label>
												<input id="phone_number" type="text" class="form-control {{ $errors->has('phone_number') ? ' is-invalid' : '' }}" name="phone_number" value="{{ $employee->employeeProfile->phone_number }}" {{$disabled}}>

												@if ($errors->has('phone_number'))
													<span class="text-danger">
														{{ $errors->first('phone_number') }}
													</span>
												@endif
											</div>								
										</div>
										<div class="form-row mb-3">
											<div class="col-md-4">
												<label for="name">Date of Birth</label>
												<input id="dob" type="date" class="form-control {{ $errors->has('dob') ? ' is-invalid' : '' }}" name="dob"  value="{{ $employee->employeeProfile->dob }}" {{$disabled}}>

												@if ($errors->has('dob'))
													<span class="text-danger">
														{{ $errors->first('dob') }}
													</span>
												@endif
											</div>
											<div class="col-md-4">
												<label for="name" >Gender</label>
												<select class="form-control" id="gender" name="gender" @if($disabledDrop) style="pointer-events: none;" @endif>
													<option @if($employee->employeeProfile->gender == "Male") selected @endif value="Male">Male</option>
						                            <option @if($employee->employeeProfile->gender == "Female") selected @endif value="Female">Female</option>
						                            <option @if($employee->employeeProfile->gender == "Other") selected @endif value="Other">Other</option>
						                        </select>
												@if ($errors->has('gender'))
													<span class="text-danger">
														{{ $errors->first('gender') }}
													</span>
												@endif
											</div>
											<div class="col-md-4">
												<label for="name">Marital Status</label>
												<select class="form-control" id="marital_status" name="marital_status" @if($disabledDrop) style="pointer-events: none;" @endif>
						                            <option selected value disabled>Please make a choice</option>
						                            <option @if($employee->employeeProfile->marital_status == "single") selected @endif value="single">Single</option>
						                            <option @if($employee->employeeProfile->marital_status == "married") selected @endif value="married">Married</option>
						                            <option @if($employee->employeeProfile->marital_status == "other") selected @endif value="other">Other</option>
						                        </select>
												@if ($errors->has('marital_status'))
													<span class="text-danger">
														{{ $errors->first('marital_status') }}
													</span>
												@endif
											</div>								
										</div>
										<div class="form-row mb-3">
											<div class="col-md-4">
												<label for="name">Nationality</label>
												<input id="nationality" type="text" class="form-control {{ $errors->has('nationality') ? ' is-invalid' : '' }}" name="nationality" value="{{ $employee->employeeProfile->nationality }}" {{$disabled}} >

												@if ($errors->has('nationality'))
													<span class="text-danger">
														{{ $errors->first('nationality') }}
													</span>
												@endif
											</div>
											<!-- <div class="col-md-4">
												<label for="name">Blood Group</label>
												<input id="blood_group" type="text" class="form-control {{ $errors->has('blood_group') ? ' is-invalid' : '' }}" name="blood_group" value="{{ old('blood_group', '') }}">

												@if ($errors->has('blood_group'))
													<span class="text-danger">
														{{ $errors->first('blood_group') }}
													</span>
												@endif
											</div> -->
											<div class="col-md-4">
												<label for="name" >City</label>
												<input id="city" type="text" class="form-control {{ $errors->has('city') ? ' is-invalid' : '' }}" name="city" value="{{ $employee->employeeProfile->city }}" {{$disabled}}>

												@if ($errors->has('city'))
													<span class="text-danger">
														{{ $errors->first('city') }}
													</span>
												@endif
											</div>
											<div class="col-md-4">
												<label for="name">Country</label>
												<input id="country" type="text" class="form-control {{ $errors->has('country') ? ' is-invalid' : '' }}" name="country" value="{{ $employee->employeeProfile->country }}" {{$disabled}}>

												@if ($errors->has('country'))
													<span class="text-danger">
														{{ $errors->first('country') }}
													</span>
												@endif
											</div>
										</div>										
										<div class="form-row mb-3">
											<div class="col-md-12">
												<label for="name" >Address</label>
												<textarea name="address" id="address" class="form-control {{ $errors->has('address') ? ' is-invalid' : '' }}" rows="4" {{$disabled}}>{{ $employee->employeeProfile->address }}</textarea>

												@if ($errors->has('address'))
													<span class="text-danger">
														{{ $errors->first('address') }}
													</span>
												@endif
											</div>
										</div>
										<div class="form-row mb-3">											
											<div class="col-md-4">
												<label for="email" >Email</label>
												<input id="email" type="text" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $employee->email }}" {{$disabled}}>

												@if ($errors->has('email'))
													<span class="text-danger">
														{{ $errors->first('email') }}
													</span>
												@endif
											</div>										
											<div class="col-md-4">
												<label for="name" >Identity Document</label>
												<!-- <input id="identity_document" type="text" class="form-control {{ $errors->has('identity_document') ? ' is-invalid' : '' }}" name="identity_document" value="{{ old('identity_document', '') }}"> -->
												<select class="form-control {{ $errors->has('identity_document') ? ' is-invalid' : '' }}" id="identity_document" name="identity_document" @if($disabledDrop) style="pointer-events: none;" @endif>
						                            <option selected value disabled>Please make a choice</option>
						                            <option @if($employee->employeeProfile->identity_document == "Voter Id") selected @endif value="Voter Id">Voter Id</option>
						                            <option @if($employee->employeeProfile->identity_document == "Aadhar Card") selected @endif value="Aadhar Card">Aadhar Card</option>
						                            <option @if($employee->employeeProfile->identity_document == "Driving License") selected @endif value="Driving License">Driving License</option>
						                            <option @if($employee->employeeProfile->identity_document == "Passport") selected @endif value="Passport">Passport</option>
						                        </select>

												@if ($errors->has('identity_document'))
													<span class="text-danger">
														{{ $errors->first('identity_document') }}
													</span>
												@endif
											</div>
											<div class="col-md-4">
												<label for="name" >Identity Number</label>
												<input id="identity_number" type="text" class="form-control {{ $errors->has('identity_number') ? ' is-invalid' : '' }}" name="identity_number" value="{{ $employee->employeeProfile->identity_number }}" {{$disabled}}>
												@if ($errors->has('identity_number'))
													<span class="text-danger">
														{{ $errors->first('identity_number') }}
													</span>
												@endif
											</div>										
										</div>
										<!-- <div class="form-row mb-3">
												<div class="col-md-4">
													<label for="name" >Bank Name</label>
													<input id="bank_name" type="text" class="form-control {{ $errors->has('bank_name') ? ' is-invalid' : '' }}" name="bank_name" value="{{ $employee->employeeProfile->bank_name }}" {{$disabled}}>

													@if ($errors->has('bank_name'))
														<span class="text-danger">
															{{ $errors->first('bank_name') }}
														</span>
													@endif
												</div>
												<div class="col-md-4">
													<label for="name" >Bank Account Number</label>
													<input id="bank_acc_number" type="text" class="form-control {{ $errors->has('bank_acc_number') ? ' is-invalid' : '' }}" name="bank_acc_number" value="{{ $employee->employeeProfile->bank_acc_number }}" {{$disabled}}>

													@if ($errors->has('bank_acc_number'))
														<span class="text-danger">
															{{ $errors->first('bank_acc_number') }}
														</span>
													@endif
												</div>									
												<div class="col-md-4">
													<label for="name" >PF A/C No</label>
													<input id="pf_account_number" type="text" class="form-control {{ $errors->has('pf_account_number') ? ' is-invalid' : '' }}" name="pf_account_number" value="{{ $employee->employeeProfile->pf_account_number }}" {{$disabled}}>
													@if ($errors->has('pf_account_number'))
														<span class="text-danger">
															{{ $errors->first('pf_account_number') }}
														</span>
													@endif
												</div>									
											</div> -->
											<div class="form-row mb-3">
												<div class="col-md-4">
													<label for="name">Employee Status</label>
													<select class="form-control" id="status" name="status">
														<option selected value disabled>Please Select</option>
														<option @if($employee->is_proifle_edit_access == "1") selected @endif value="1">Active</option>
														<option @if($employee->is_proifle_edit_access == "0") selected @endif value="0">Inactive</option>
													</select>
													@if ($errors->has('status'))
														<span class="text-danger">
															{{ $errors->first('status') }}
														</span>
													@endif
												</div>
												<div class="col-md-4">
													<label for="name">Profile Lock?</label>
													<select class="form-control" id="is_proifle_edit_access" name="is_proifle_edit_access">
							                            <option @if($employee->is_proifle_edit_access == "0") selected @endif value="0">No</option>
							                            <option @if($employee->is_proifle_edit_access == "1") selected @endif value="1">Yes</option>
							                        </select>
													@if ($errors->has('is_proifle_edit_access'))
														<span class="text-danger">
															{{ $errors->first('is_proifle_edit_access') }}
														</span>
													@endif
												</div>
											</div>
									</div>
									<div class="tab-pane" id="emp-details">
										<div class="form-row mb-3">
											<div class="col-md-4">
												<label for="name" >Start Date</label>
												<input id="doj" type="date" class="form-control {{ $errors->has('doj') ? ' is-invalid' : '' }}" name="doj" value="{{ $employee->employeeProfile->doj }}" {{$disabled}}>
												@if ($errors->has('doj'))
													<span class="text-danger">
														{{ $errors->first('doj') }}
													</span>
												@endif
											</div>
											<div class="col-md-4">
												<label for="name" >Position</label>
												<input id="designation" type="text" class="form-control {{ $errors->has('designation') ? ' is-invalid' : '' }}" name="designation" value="{{ $employee->employeeProfile->designation }}" {{$disabled}}>
												@if ($errors->has('designation'))
													<span class="text-danger">
														{{ $errors->first('designation') }}
													</span>
												@endif
											</div>
											<div class="col-md-4">
												<label for="name" >Department</label>
												<input id="department" type="text" class="form-control {{ $errors->has('department') ? ' is-invalid' : '' }}" name="department" value="{{ $employee->employeeProfile->department }}" {{$disabled}}>
												@if ($errors->has('department'))
													<span class="text-danger">
														{{ $errors->first('department') }}
													</span>
												@endif
											</div>
										</div>
										<div class="form-row mb-3">
											<div class="col-md-4">
												<label for="name" >Social Security Number</label>
												<input id="pan_number" type="text" class="form-control {{ $errors->has('pan_number') ? ' is-invalid' : '' }}" name="pan_number" value="{{ $employee->employeeProfile->pan_number }}" {{$disabled}}s>
												@if ($errors->has('pan_number'))
													<span class="text-danger">
														{{ $errors->first('pan_number') }}
													</span>
												@endif
											</div>
											<div class="col-md-4">
												<label for="name" >Medical Benefits Number</label>
												<input id="ifsc_code" type="text" class="form-control {{ $errors->has('ifsc_code') ? ' is-invalid' : '' }}" name="ifsc_code" value="{{ $employee->employeeProfile->ifsc_code }}" {{$disabled}}>

												@if ($errors->has('ifsc_code'))
													<span class="text-danger">
														{{ $errors->first('ifsc_code') }}
													</span>
												@endif
											</div>										

											<div class="col-md-4">
												<label for="name" >Employee Type</label>
												<select class="form-control {{ $errors->has('emp_type') ? ' is-invalid' : '' }}" id="emp_type" name="emp_type" @if($disabledDrop) style="pointer-events: none;" @endif>
						                            <option selected value disabled>Please make a choice</option>
						                            <option @if($employee->employeeProfile->emp_type == "hourly") selected @endif value="hourly">Hourly</option>
						                            <option @if($employee->employeeProfile->emp_type == "part-time") selected @endif value="part-time">Part Time</option>
						                           	<option @if($employee->employeeProfile->emp_type == "full-time") selected @endif value="full-time">Full Time</option>
						                        </select>
												@if ($errors->has('emp_type'))
													<span class="text-danger">
														{{ $errors->first('emp_type') }}
													</span>
												@endif
											</div>
										</div>
										<div class="form-row mb-3">
											<div class="col-md-4">
												<label for="name" >Pay Type</label>
												<select class="form-control {{ $errors->has('pay_type') ? ' is-invalid' : '' }}" id="pay_type" name="pay_type" >
						                            <option selected value disabled>Please make a choice</option>
						                            <option @if($employee->employeeProfile->pay_type == "hourly") selected @endif value="hourly">Hourly</option>
						                            <option @if($employee->employeeProfile->pay_type == "weekly") selected @endif value="weekly">Weekly</option>
						                            <option @if($employee->employeeProfile->pay_type == "bi-weekly") selected @endif value="bi-weekly">Bi-Weekly</option>
						                            <option @if($employee->employeeProfile->pay_type == "semi-monthly") selected @endif value="semi-monthly">Semi Monthly</option>
						                           	<option @if($employee->employeeProfile->pay_type == "monthly") selected @endif value="monthly">Monthly</option>
						                        </select>
												@if ($errors->has('pay_type'))
													<span class="text-danger">
														{{ $errors->first('pay_type') }}
													</span>
												@endif
											</div>
											<div class="col-md-4">
												<label for="name" >Amount</label>
												<input id="pay_rate" type="number" class="form-control {{ $errors->has('pay_rate') ? ' is-invalid' : '' }}" name="pay_rate" value="{{ $employee->employeeProfile->pay_rate }}" {{$disabled}}>

												@if ($errors->has('pay_rate'))
													<span class="text-danger">
														{{ $errors->first('pay_rate') }}
													</span>
												@endif
											</div>	
											<!-- <div class="col-md-4">
												<label for="name" >Pay Rate</label>
												<input id="rate" type="number" class="form-control {{ $errors->has('rate') ? ' is-invalid' : '' }}" name="rate" value="{{ $employee->employeeProfile->rate }}" {{$disabled}}>
												@if ($errors->has('rate'))
													<span class="text-danger">
														{{ $errors->first('rate') }}
													</span>
												@endif
											</div>	 -->										
										</div>
									</div>
									<div class="tab-pane" id="payment-method">
										<div class="form-row mb-3">
											<div class="col-md-4">
												<label for="name" >Payment Method</label>
												<select class="form-control {{ $errors->has('payment_method') ? ' is-invalid' : '' }}" id="payment_method" name="payment_method" onchange="showDiv(this)">
													<option value="" selected disabled>Please Select</option>
													<option @if(!empty($employee->paymentProfile->payment_method) && $employee->paymentProfile->payment_method == "check") selected @endif value="check">Cheque</option>
													<option @if(!empty($employee->paymentProfile->payment_method) && $employee->paymentProfile->payment_method == "Direct Deposit") selected @endif svalue="deposit">Direct Deposit</option>
												</select>
												@if ($errors->has('payment_method'))
													<span class="text-danger">
														{{ $errors->first('payment_method') }}
													</span>
												@endif
											</div>
											<div class="col-md-4 
												@if(empty($employee->paymentProfile->routing_number)) d-none @endif" 												
												id="routing_number_div"
											>
												<label for="routing_number">Routing Number</label>
												<div class="col-md-12">
													<input id="routing_number" type="routing_number" class="form-control {{ $errors->has('routing_number') ? ' is-invalid' : '' }}" name="routing_number" value="{{ $employee->paymentProfile->routing_number?? '' }}" {{$disabled}}>
													@if ($errors->has('routing_number'))
														<span class="text-danger">
															{{ $errors->first('routing_number') }}
														</span>
													@endif
												</div>
											</div>
											<div class="col-md-4 
												@if(empty($employee->paymentProfile->account_number)) d-none @endif" 
												id="account_number_div">
												<label for="account_number">Account Number</label>
												<div class="col-md-12">
													<input id="account_number" type="account_number" class="form-control {{ $errors->has('account_number') ? ' is-invalid' : '' }}" name="account_number" value="{{ $employee->paymentProfile->account_number ?? '' }}" {{$disabled}}>
													@if ($errors->has('account_number'))
														<span class="text-danger">
															{{ $errors->first('account_number') }}
														</span>
													@endif
												</div>
											</div>
										</div>
										<div class="form-row mb-3
											@if(empty($employee->paymentProfile->account_type)) d-none @endif" 
											id="account_type_div">
											<div class="col-md-4">
												<label for="name" >Account Type</label>
												<select class="form-control {{ $errors->has('account_type') ? ' is-invalid' : '' }}" id="account_type" name="account_type">
													<option value="" disabled>Please Select</option>
													<option @if(!empty($employee->paymentProfile->account_type) && $employee->paymentProfile->account_type == "checking") selected @endif value="checking">Chequing</option>
													<option @if(!empty($employee->paymentProfile->account_type) && $employee->paymentProfile->account_type == "saving") selected @endif value="saving">Saving</option>
												</select>
												@if ($errors->has('account_type'))
													<span class="text-danger">
														{{ $errors->first('account_type') }}
													</span>
												@endif
											</div>
										</div>
									</div>
									<div class="tab-pane" id="settings">
										<div class="form-row mb-3">
											<div class="col-md-4">
												<label for="password" >Password</label>
												<input id="password" type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password">

												@if ($errors->has('password'))
													<span class="text-danger">
														{{ $errors->first('password') }}
													</span>
												@endif
											</div>
											<div class="col-md-4">
												<label for="password-confirm" >Confirm Password</label>
												<input id="password-confirm" type="password" class="form-control" name="password_confirmation">
											</div>
										</div>
									</div>
								</div>								
							</div>							
					</div>
					<div class="card-footer">
						<button type="submit" class="btn btn-primary">Submit</button>
						<a href="{{ route('employee.index' )}}" class="btn btn-info">Back</a>
					</div>
					</form>
				</div>
			</div>
		</div>
	</section>
@endsection
@push('page_scripts')
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
@endpush