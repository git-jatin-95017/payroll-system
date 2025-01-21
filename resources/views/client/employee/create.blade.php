@extends('layouts.new_layout')

@section('content')
<div class="page-heading d-flex justify-content-between align-items-center gap-3 mb-3">
	<div>
		<h3>Add People</h3>
		<p class="mb-0">Add new employee here</p>
	</div>
</div>
<section class="content">
	<div class="container-fluid">
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
				<form class="form-horizontal" method="POST" action="{{ route('employee.store') }}" enctype="multipart/form-data">
					@csrf
					<div class="card">
						<div class="card-header p-2">
						</div>
							<div class="card-body">
								<div class="tab-content">
									<div class="tab-pane" id="emp-details">
										<div class="form-row mb-3">
											<div class="col-md-4">
												<label for="name" >Start Date</label>
												<input id="doj" type="date" class="form-control {{ $errors->has('doj') ? ' is-invalid' : '' }}" name="doj" value="{{ old('doj', '') }}">
												@if ($errors->has('doj'))
													<span class="text-danger">
														{{ $errors->first('doj') }}
													</span>
												@endif
											</div>
											<div class="col-md-4">
												<label for="name" >Position</label>
												<input id="designation" type="text" class="form-control {{ $errors->has('designation') ? ' is-invalid' : '' }}" name="designation" value="{{ old('designation', '') }}">
												@if ($errors->has('designation'))
													<span class="text-danger">
														{{ $errors->first('designation') }}
													</span>
												@endif
											</div>
											<div class="col-md-4">
												<label for="name" >Department</label>
												<input id="department" type="text" class="form-control {{ $errors->has('department') ? ' is-invalid' : '' }}" name="department" value="{{ old('department', '') }}">
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
												<input id="pan_number" type="text" class="form-control {{ $errors->has('pan_number') ? ' is-invalid' : '' }}" name="pan_number" value="{{ old('pan_number', '') }}">
												@if ($errors->has('pan_number'))
													<span class="text-danger">
														{{ $errors->first('pan_number') }}
													</span>
												@endif
											</div>
											<div class="col-md-4">
												<label for="name" >Medical Benefits Number</label>
												<input id="ifsc_code" type="text" class="form-control {{ $errors->has('ifsc_code') ? ' is-invalid' : '' }}" name="ifsc_code" value="{{ old('ifsc_code', '') }}">

												@if ($errors->has('ifsc_code'))
													<span class="text-danger">
														{{ $errors->first('ifsc_code') }}
													</span>
												@endif
											</div>

											<div class="col-md-4">
												<label for="name" >Employee Type</label>
												<select class="form-control {{ $errors->has('emp_type') ? ' is-invalid' : '' }}" id="emp_type" name="emp_type">
													<option selected value disabled>Please Select</option>
													<option @if(old('emp_type') == "hourly") selected @endif value="hourly">Hourly</option>
													<option @if(old('emp_type') == "part-time") selected @endif value="part-time">Part Time</option>
													<option @if(old('emp_type') == "full-time") selected @endif value="full-time">Full Time</option>
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
												<select class="form-control {{ $errors->has('pay_type') ? ' is-invalid' : '' }}" id="pay_type" name="pay_type">
													<option selected value disabled>Please Select</option>
													<option @if(old('pay_type') == "hourly") selected @endif value="hourly">Hourly</option>
													<option @if(old('pay_type') == "weekly") selected @endif value="weekly">Weekly</option>
													<option @if(old('pay_type') == "bi-weekly") selected @endif value="bi-weekly">Bi-Weekly</option>
													<option @if(old('pay_type') == "semi-monthly") selected @endif value="semi-monthly">Semi Monthly</option>
													<option @if(old('pay_type') == "monthly") selected @endif value="monthly">Monthly</option>
												</select>
												@if ($errors->has('pay_type'))
													<span class="text-danger">
														{{ $errors->first('pay_type') }}
													</span>
												@endif
											</div>
											<div class="col-md-4">
												<label for="name" >Amount</label>
												<input id="pay_rate" type="number" class="form-control {{ $errors->has('pay_rate') ? ' is-invalid' : '' }}" name="pay_rate" value="{{ old('pay_rate', '') }}">

												@if ($errors->has('pay_rate'))
													<span class="text-danger">
														{{ $errors->first('pay_rate') }}
													</span>
												@endif
											</div>
											<!-- <div class="col-md-4">
												<label for="name" >Pay Rate</label>
												<input id="rate" type="number" class="form-control {{ $errors->has('rate') ? ' is-invalid' : '' }}" name="rate" value="{{ old('rate', '') }}">
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
													<option value="check">Cheque</option>
													<option value="deposit">Direct Deposit</option>
												</select>
												@if ($errors->has('payment_method'))
													<span class="text-danger">
														{{ $errors->first('payment_method') }}
													</span>
												@endif
											</div>
											<div class="col-md-4 d-none" id="routing_number_div">
												<label for="routing_number">Routing Number</label>
												<div class="form-group mb-0">
													<input id="routing_number" type="routing_number" class="form-control {{ $errors->has('routing_number') ? ' is-invalid' : '' }}" name="routing_number">
													@if ($errors->has('routing_number'))
														<span class="text-danger">
															{{ $errors->first('routing_number') }}
														</span>
													@endif
												</div>
											</div>
											<div class="col-md-4 d-none" id="account_number_div">
												<label for="account_number">Account Number</label>
												<div class="form-group mb-0">
													<input id="account_number" type="account_number" class="form-control {{ $errors->has('account_number') ? ' is-invalid' : '' }}" name="account_number">
													@if ($errors->has('account_number'))
														<span class="text-danger">
															{{ $errors->first('account_number') }}
														</span>
													@endif
												</div>
											</div>

										</div>
										<div class="form-row mb-3 d-none" id="account_type_div">
											<div class="col-md-4">
												<label for="name" >Account Type</label>
												<select class="form-control {{ $errors->has('account_type') ? ' is-invalid' : '' }}" id="account_type" name="account_type">
													<option value="" disabled>Please Select</option>
													<option value="checking">Chequing</option>
													<option value="saving">Saving</option>
												</select>
												@if ($errors->has('account_type'))
													<span class="text-danger">
														{{ $errors->first('account_type') }}
													</span>
												@endif
											</div>
											<div class="col-md-4">
												<label for="bank_name">Bank Name</label>
												<input id="bank_name" type="bank_name" class="form-control {{ $errors->has('bank_name') ? ' is-invalid' : '' }}" name="bank_name" >
												@if ($errors->has('bank_name'))
													<span class="text-danger">
														{{ $errors->first('bank_name') }}
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
					<!-- <div class="card-footer">
						<button type="submit" class="btn btn-primary">Submit</button>
						<a href="{{ route('employee.index' )}}" class="btn btn-info">Back</a>
					</div> -->
				</form>
			</div>
		</div>
	</div>
</section>
<div class="bg-white white-container py-4 continer-h-full">
	<ul class="nav nav-tabs nav-pills px-4 db-custom-tabs gap-4" id="myTab" role="tablist">
		<li class="nav-item" role="presentation">
			<button class="nav-link active" id="company-tab" data-bs-toggle="tab" data-bs-target="#company"
				type="button" role="tab" aria-controls="company" aria-selected="true">Personal Information</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment" type="button"
				role="tab" aria-controls="payment" aria-selected="false">Employment Details</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link" id="admin-tab" data-bs-toggle="tab" data-bs-target="#admin" type="button"
				role="tab" aria-controls="admin" aria-selected="false">Payment Method</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button"
				role="tab" aria-controls="password" aria-selected="false">Password</button>
		</li>
	</ul>
	<div class="tab-content px-4 pt-4" id="myTabContent">
		<div class="tab-pane fade show active" id="company" role="tabpanel" aria-labelledby="company-tab">
			<div class="max-w-md max-auto">
				<div class="sub-text-heading pb-4">
					<h3 class="mb-1">Company Information</h3>
					<p>Type your client information here</p>
				</div>
				<form class="form-horizontal" method="POST" action="{{ route('employee.store') }}" enctype="multipart/form-data">
					@csrf
					<div class="row">
						<div class="col-12 mb-3">
							<div class="tb-container d-flex gap-4 align-items-center">
								<div class="tb-img-view">
									<!-- Default SVG Avatar -->
									@if(!empty($company->companyProfile->logo))
										<img id="tb-image" src="/files/{{$company->companyProfile->logo}}" style="object-fit: contain;"/>
									@else
										<img id="tb-image" src="" style="display:none;" alt="Uploaded Image" />
									@endif

									<svg id="tb-avatar" class="w-64 h-64" xmlns="http://www.w3.org/2000/svg"
										viewBox="0 0 32 32" fill="currentColor">
										<path id="_inner-path_" data-name="<inner-path>" class="cls-1"
											d="M8.0071,24.93A4.9958,4.9958,0,0,1,13,20h6a4.9959,4.9959,0,0,1,4.9929,4.93,11.94,11.94,0,0,1-15.9858,0ZM20.5,12.5A4.5,4.5,0,1,1,16,8,4.5,4.5,0,0,1,20.5,12.5Z"
											style="fill: none"></path>
										<path
											d="M26.7489,24.93A13.9893,13.9893,0,1,0,2,16a13.899,13.899,0,0,0,3.2511,8.93l-.02.0166c.07.0845.15.1567.2222.2392.09.1036.1864.2.28.3008.28.3033.5674.5952.87.87.0915.0831.1864.1612.28.2417.32.2759.6484.5372.99.7813.0441.0312.0832.0693.1276.1006v-.0127a13.9011,13.9011,0,0,0,16,0V27.48c.0444-.0313.0835-.0694.1276-.1006.3412-.2441.67-.5054.99-.7813.0936-.08.1885-.1586.28-.2417.3025-.2749.59-.5668.87-.87.0933-.1006.1894-.1972.28-.3008.0719-.0825.1522-.1547.2222-.2392ZM16,8a4.5,4.5,0,1,1-4.5,4.5A4.5,4.5,0,0,1,16,8ZM8.0071,24.93A4.9957,4.9957,0,0,1,13,20h6a4.9958,4.9958,0,0,1,4.9929,4.93,11.94,11.94,0,0,1-15.9858,0Z">
										</path>
										<rect id="_Transparent_Rectangle_" data-name="<Transparent Rectangle>"
											class="cls-1" width="32" height="32" style="fill: none"></rect>
									</svg>
								</div>
								<label for="tb-file-upload">
									Upload Image
									<x-bx-upload class="w-20 g-20 ms-1"></x-bx-upload>
								</label>
								<input type="file" name="logo" id="tb-file-upload" accept="image/*"
									onchange="fileUpload(event);" />
							</div>
						</div>
						<div class="col-6 mb-3">
							<div class="form-group">
								<label class="db-label" for="name">First Name</label>
								<input id="first_name" type="text" placeholder="Type here" class="form-control db-custom-input {{ $errors->has('first_name') ? ' is-invalid' : '' }}" name="first_name" value="{{ old('first_name', '') }}">
								@if ($errors->has('first_name'))
									<span class="text-danger">
										{{ $errors->first('first_name') }}
									</span>
								@endif
							</div>
						</div>
						<div class="col-6 mb-3">
							<div class="form-group">
								<label class="db-label" for="name">Last Name</label>
								<input id="last_name" type="text" placeholder="Type here" class="form-control db-custom-input {{ $errors->has('last_name') ? ' is-invalid' : '' }}" name="last_name" value="{{ old('last_name', '') }}">
								@if ($errors->has('last_name'))
									<span class="text-danger">
										{{ $errors->first('last_name') }}
									</span>
								@endif
							</div>
						</div>
						<div class="col-6 mb-3">
							<div class="form-group">
								<label class="db-label" for="name" >Phone Number</label>
								<input id="phone_number" type="text" class="form-control db-custom-input {{ $errors->has('phone_number') ? ' is-invalid' : '' }}" name="phone_number" value="{{ old('phone_number', '') }}">
								@if ($errors->has('phone_number'))
									<span class="text-danger">
										{{ $errors->first('phone_number') }}
									</span>
								@endif
							</div>
						</div>
						<div class="col-6 mb-3">
							<div class="form-group">
								<label class="db-label" for="email" >Email</label>
								<input id="email" type="text" class="form-control db-custom-input {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email', '') }}">
								@if ($errors->has('email'))
									<span class="text-danger">
										{{ $errors->first('email') }}
									</span>
								@endif
							</div>
						</div>
						<div class="col-12 mb-3">
							<div class="form-check form-check-inline custom-radio-btn">
								<input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
								<label class="form-check-label" for="inlineRadio1">Male</label>
							</div>
							<div class="form-check form-check-inline custom-radio-btn">
								<input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
								<label class="form-check-label" for="inlineRadio2">Female</label>
							</div>
						</div>
						<div class="col-6 mb-3">
							<div class="form-group">
								<label for="name">Date of Birth</label>
								<input id="dob" type="date" class="form-control db-custom-input {{ $errors->has('dob') ? ' is-invalid' : '' }}" name="dob" value="{{ old('dob', '') }}">
								@if ($errors->has('dob'))
									<span class="text-danger">
										{{ $errors->first('dob') }}
									</span>
								@endif
							</div>
						</div>
						<div class="col-6 mb-3">
							<div class="form-group">
								<label class="db-label" for="name">Marital Status</label>
								<select class="form-control db-custom-input select-drop-down-arrow" id="marital_status" name="marital_status">
									<option selected value disabled>Please Select</option>
									<option @if(old('marital_status') == "single") selected @endif value="single">Single</option>
									<option @if(old('marital_status') == "married") selected @endif value="married">Married</option>
									<option @if(old('marital_status') == "other") selected @endif value="other">Other</option>
								</select>
								@if ($errors->has('marital_status'))
									<span class="text-danger">
										{{ $errors->first('marital_status') }}
									</span>
								@endif
							</div>
						</div>
						<div class="col-md-4 mb-3">
							<div class="form-group">
								<label class="db-label" for="name">Nationality</label>
								<input id="nationality" type="text" placeholder="Type here" class="form-control db-custom-input {{ $errors->has('nationality') ? ' is-invalid' : '' }}" name="nationality" value="{{ old('nationality', '') }}">
								@if ($errors->has('nationality'))
									<span class="text-danger">
										{{ $errors->first('nationality') }}
									</span>
								@endif
							</div>
						</div>
						<div class="col-md-4 mb-3">
							<div class="form-group">
								<label class="db-label" for="name" >City</label>
								<input id="city" type="text" placeholder="Type here" class="form-control db-custom-input {{ $errors->has('city') ? ' is-invalid' : '' }}" name="city" value="{{ old('city', '') }}">
								@if ($errors->has('city'))
									<span class="text-danger">
										{{ $errors->first('city') }}
									</span>
								@endif
							</div>
						</div>
						<div class="col-md-4 mb-3">
							<div class="form-group">
								<label class="db-label" for="name">Country</label>
								<input id="country" type="text" placeholder="Type here" class="form-control db-custom-input {{ $errors->has('country') ? ' is-invalid' : '' }}" name="country" value="{{ old('country', '') }}">
								@if ($errors->has('country'))
									<span class="text-danger">
										{{ $errors->first('country') }}
									</span>
								@endif
							</div>
						</div>
						<div class="col-md-12 mb-3">
							<div class="form-group">
								<label class="db-label" for="name" >Address</label>
								<textarea style="height: auto;" placeholder="Type here" name="address" id="address" class="form-control db-custom-input {{ $errors->has('address') ? ' is-invalid' : '' }}" rows="4">{{ old('address', '') }}</textarea>
								@if ($errors->has('address'))
									<span class="text-danger">
										{{ $errors->first('address') }}
									</span>
								@endif
							</div>
						</div>
						<!--<div class="col-6 mb-3">
							<div class="form-group">
								<label class="db-label" for="name">Employee Status</label>
								<select class="form-control db-custom-input select-drop-down-arrow" id="status" name="status">
									<option selected value disabled>Please Select</option>
									<option @if(old('status') == "1") selected @endif value="1">Active</option>
									<option @if(old('status') == "0") selected @endif value="0">Inactive</option>
								</select>
								@if ($errors->has('status'))
									<span class="text-danger">
										{{ $errors->first('status') }}
									</span>
								@endif
							</div>
						</div>
						<div class="col-6 mb-3">
							<div class="form-group">
								<label class="db-label" for="name">Profile Lock?</label>
								<select class="form-control db-custom-input select-drop-down-arrow" id="is_proifle_edit_access" name="is_proifle_edit_access">
									<option @if(old('is_proifle_edit_access') == "0") selected @endif value="0">No</option>
									<option @if(old('is_proifle_edit_access') == "1") selected @endif value="1">Yes</option>
								</select>
								@if ($errors->has('is_proifle_edit_access'))
									<span class="text-danger">
										{{ $errors->first('is_proifle_edit_access') }}
									</span>
								@endif
							</div>
						</div> -->
						<div class="col-6">
							<div class="form-group">
								<label for="name" class="db-label">Identity Document</label>
								<select class="form-control db-custom-input select-drop-down-arrow {{ $errors->has('identity_document') ? ' is-invalid' : '' }}" id="identity_document" name="identity_document">
									<option selected value disabled>Please Select</option>
									<option @if(old('identity_document') == "Voter Id") selected @endif value="Voter Id">Voter Id</option>
									<option @if(old('identity_document') == "Driving License") selected @endif value="Driving License">Driving License</option>
									<option @if(old('identity_document') == "Passport") selected @endif value="Passport">Passport</option>
								</select>
								@if ($errors->has('identity_document'))
									<span class="text-danger">
										{{ $errors->first('identity_document') }}
									</span>
								@endif
							</div>
						</div>
						<div class="col-md-6 mb-3">
							<div class="form-group">
								<label for="name" class="db-label">Identity Number</label>
								<input id="identity_number" type="text" class="form-control db-custom-input select-drop-down-arrow {{ $errors->has('identity_number') ? ' is-invalid' : '' }}" name="identity_number" value="{{ old('identity_number', '') }}">
								@if ($errors->has('identity_number'))
									<span class="text-danger">
										{{ $errors->first('identity_number') }}
									</span>
								@endif
							</div>
						</div>
						<div class="col-12 mb-3">
							<label class="db-label d-block" for="name" >Employee Status</label>
							<div class="form-check form-check-inline custom-radio-btn">
								<input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
								<label class="form-check-label" for="inlineRadio1">Active</label>
							</div>
							<div class="form-check form-check-inline custom-radio-btn">
								<input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
								<label class="form-check-label" for="inlineRadio2">Inactive</label>
							</div>
						</div>
						<div class="col-12 mb-3">
							<label class="db-label d-block" for="name" >Profile Lock?</label>
							<div class="form-check form-check-inline custom-radio-btn">
								<input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
								<label class="form-check-label" for="inlineRadio1">Yes</label>
							</div>
							<div class="form-check form-check-inline custom-radio-btn">
								<input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
								<label class="form-check-label" for="inlineRadio2">No</label>
							</div>
						</div>
						<div class="col-12 text-end ">
							<div class="border-top bdr-color pt-3">
								<button type="submit" class="btn btn-primary submit-btn">Submit</button>
							</div>
						</div>
					</div>
				</form>
				</div>
			</div>
			<div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="payment-tab">
				<div class="max-w-md max-auto">
					<div class="sub-text-heading pb-4">
						<h3 class="mb-1">Payment Method</h3>
						<p>Add your payment method here</p>
					</div>

				</div>
			</div>
			<div class="tab-pane fade" id="admin" role="tabpanel" aria-labelledby="admin-tab">
				<div class="max-w-md max-auto">
					<div class="sub-text-heading pb-4 d-flex justify-content-between">
						<div>
							<h3 class="mb-1">Administrators</h3>
							<p>Change administrators password here</p>
						</div>
					</div>

				</div>
			</div>
			<div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
				<div class="max-w-md max-auto">
					<div class="sub-text-heading pb-4">
						<h3 class="mb-1">Change Password</h3>
						<p>Change password here</p>
					</div>

				</div>
			</div>
	</div>
</div>
@endsection
@push('page_scripts')
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
<script>
	const fileUpload = (event) => {
		const files = event.target.files;
		const filesLength = files.length;
		const imagePreviewElement = document.querySelector("#tb-image");
		const avatarElement = document.querySelector("#tb-avatar");
		if (filesLength > 0) {
			const imageSrc = URL.createObjectURL(files[0]);
			// Show uploaded image
			imagePreviewElement.src = imageSrc;
			imagePreviewElement.style.display = "block";
			// Hide the default SVG avatar
			avatarElement.style.display = "none";
		} else {
			// Show the default SVG avatar and hide the image preview
			imagePreviewElement.style.display = "none";
			avatarElement.style.display = "block";
		}
	};
</script>
@endpush