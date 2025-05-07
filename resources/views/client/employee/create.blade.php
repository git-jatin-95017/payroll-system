@extends('layouts.new_layout')
@section('content')
<section>
    <div class="page-heading d-flex justify-content-between align-items-center gap-3 mb-3">
		<div>
			<!-- <h3>Add People</h3> -->
			<!-- <p class="mb-0">Add new employee here</p> -->
		</div>
    </div>
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
    <div class="bg-cover-container d-flex gap-5 px-4 pb-3 mb-5">
        <div class="emp-proifle-picture">
            <img src="{{ asset('/img/no_img.jpg') }}" id="tb-image" width="225"  height="225" style="object-fit:contain !important;" alt="profile">
        </div>
        <div>
            <div class="d-flex flex-column justify-content-between h-100">
                <div class="profile-name-container pt-5">
                    <h3></h3>
                    <p></p>
                </div>
                <div>
                     <ul class="nav nav-tabs nav-pills db-custom-tabs gap-5 employee-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="company-tab" data-bs-toggle="tab" data-bs-target="#company"
                                type="button" role="tab" aria-controls="company" aria-selected="true">Personal</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment" type="button"
                                role="tab" aria-controls="payment" aria-selected="false">Employment</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="admin-tab" data-bs-toggle="tab" data-bs-target="#admin" type="button"
                                role="tab" aria-controls="admin" aria-selected="false">Payment</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button"
                                role="tab" aria-controls="password" aria-selected="false">Password</button>
                        </li>
                     </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex gap-4">
        <div class="employee-profile-left">
            <div class="bg-white p-4 border-radius-15">
               	<div class="d-flex gap-2 employee-info align-items-center mb-2">
                    <div>
                        <x-heroicon-o-map-pin class="w-20 h-20" />
                    </div>
                    <div>
                        <p class="mb-0">
                        </p>
                    </div>
               	</div>
               	<div class="d-flex gap-2 employee-info align-items-center mb-3">
                    <div>
                        <x-heroicon-o-envelope class="w-20 h-20" />
                    </div>
                    <div>
                        <p class="mb-0">
                        </p>
                    </div>
               	</div>
               	<ul class="mb-0 p-0 d-flex align-items-center gap-3 employee-social-media">
                    <li>
                        <a href="#">
                            <x-bxl-facebook-square class="w-24 h-24" />
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <x-bxl-linkedin-square class="w-24 h-24" />
                        </a>
                    </li>
                    <!-- <li>
                        <a href="#">
                            <svg width="22" height="22" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_353_4505)">
                                    <path d="M15.7508 0.960938H18.8175L12.1175 8.61927L20 19.0384H13.8283L8.995 12.7184L3.46333 19.0384H0.395L7.56167 10.8468L0 0.961771H6.32833L10.6975 6.73844L15.7508 0.960938ZM14.675 17.2034H16.3742L5.405 2.7001H3.58167L14.675 17.2034Z" fill="#454E97"/>
                                </g>
                                <defs>
                                <clipPath id="clip0_353_4505">
                                    <rect width="22" height="22" fill="white"/>
                                </clipPath>
                                </defs>
                            </svg>
                        </a>
                    </li> -->
               	</ul>
            </div>
        </div>
        <div class="bg-white w-100 border-radius-15 p-4">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="company" role="tabpanel" aria-labelledby="company-tab">
                    <div class="max-w-md max-auto">
                        <div class="sub-text-heading pb-4">
                            <h3 class="mb-1">Personal Information</h3>
                            <!-- <p>Type your information</p> -->
                        </div>
							<form class="form-horizontal" method="POST" action="{{ route('employee.store') }}" enctype="multipart/form-data">
							@csrf
							<div class="row">
								<div class="col-12 mb-3">
									<div class="tb-container d-flex gap-4 align-items-center">
										<label for="tb-file-upload">
											Upload Image
											<x-bx-upload class="w-20 g-20 ms-1"></x-bx-upload>
										</label>
										<input type="file" name="file" id="tb-file-upload" accept="image/*" onchange="fileUpload(event);" />
										<input type="hidden" name="base64_image" id="base64_image">
										
										<button type="button" class="btn btn-primary ms-3" data-bs-toggle="modal" data-bs-target="#faceCaptureModal">
											<i class="fas fa-camera me-2"></i>Capture Face
										</button>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-6 mb-3">
									<div class="form-group">
										<label class="db-label"  for="name">First Name</label>
										<input id="first_name" type="text" class="form-control db-custom-input {{ $errors->has('first_name') ? ' is-invalid' : '' }}" name="first_name" value="{{old('first_name')}}">
										@if ($errors->has('first_name'))
											<span class="text-danger">
												{{ $errors->first('first_name') }}
											</span>
										@endif
									</div>
								</div>
								<div class="col-6 mb-3">
									<div class="form-group">
										<label class="db-label"  for="name">Last Name</label>
										<input id="last_name" type="text" class="form-control db-custom-input {{ $errors->has('last_name') ? ' is-invalid' : '' }}" name="last_name"  value="{{old('last_name')}}">
										@if ($errors->has('last_name'))
											<span class="text-danger">
												{{ $errors->first('last_name') }}
											</span>
										@endif
									</div>
								</div>
								<div class="col-12 mb-3">
                                    <div class="form-check form-check-inline custom-radio-btn">
                                        <input class="form-check-input" type="radio" name="gender" id="inlineRadio1" value="Male" checked>
                                        <label class="form-check-label" for="inlineRadio1">Male</label>
                                    </div>
                                    <div class="form-check form-check-inline custom-radio-btn">
                                        <input class="form-check-input" type="radio" name="gender" id="inlineRadio2" value="Female">
                                        <label class="form-check-label" for="inlineRadio2">Female</label>
                                    </div>
									<div class="form-check form-check-inline custom-radio-btn">
                                        <input class="form-check-input" type="radio" name="gender" id="inlineRadio3" value="Other">
                                        <label class="form-check-label" for="inlineRadio3">Other</label>
                                    </div>

									@if ($errors->has('gender'))
										<span class="text-danger">
											{{ $errors->first('gender') }}
										</span>
									@endif
                                </div>
							</div>

							<div class="row">
								<div class="col-3 mb-3">
									<div class="form-group">
										<label class="db-label" for="name">Date of Birth</label>
										<input id="dob" type="date" class="form-control db-custom-input {{ $errors->has('dob') ? ' is-invalid' : '' }}" name="dob" value="{{old('dob')}}">
										@if ($errors->has('dob'))
											<span class="text-danger">
												{{ $errors->first('dob') }}
											</span>
										@endif
									</div>
								</div>
								<div class="col-3 mb-3">
									<div class="form-group">
										<label for="is_visible_calendar" class="db-label">Is Visible On Calendar?</label>
										<select class="form-control db-custom-input" id="is_visible_calendar" name="is_visible_calendar">
											<option value="0">No</option>
											<option value="1">Yes</option>
										</select>

										@if ($errors->has('is_visible_calendar'))
											<span class="text-danger">
												{{ $errors->first('is_visible_calendar') }}
											</span>
										@endif
									</div>
								</div>
								<div class="col-6 mb-3">
									<div class="form-group">
										<label class="db-label"  for="name">Marital Status</label>
										<select class="form-control db-custom-input" id="marital_status" name="marital_status">
											<option selected value disabled>Please Select</option>
											<option value="single" @if(old('marital_status') == 'single') selected @endif>Single</option>
											<option value="married" @if(old('marital_status') == 'married') selected @endif>Married</option>
											<option value="other" @if(old('marital_status') == 'other') selected @endif>Other</option>
										</select>
										@if ($errors->has('marital_status'))
											<span class="text-danger">
												{{ $errors->first('marital_status') }}
											</span>
										@endif
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-4 mb-3">
									<div class="form-group">
										<label class="db-label" for="name">Nationality</label>
										<input id="nationality" type="text" class="form-control db-custom-input {{ $errors->has('nationality') ? ' is-invalid' : '' }}" name="nationality" value="{{old('nationality')}}">
										@if ($errors->has('nationality'))
											<span class="text-danger">
												{{ $errors->first('nationality') }}
											</span>
										@endif
									</div>
								</div>
								<div class="col-4 mb-3">
									<div class="form-group">
										<label class="db-label" for="name">Country</label>
										<input id="country" type="text" class="form-control db-custom-input {{ $errors->has('country') ? ' is-invalid' : '' }}" name="country" value="{{old('country')}}" >
										@if ($errors->has('country'))
										<span class="text-danger">
											{{ $errors->first('country') }}
										</span>
										@endif
									</div>
								</div>
								<div class="col-4 mb-3">
									<div class="form-group">
										<label class="db-label" for="name">City</label>
										<input id="city" type="text" class="form-control db-custom-input {{ $errors->has('city') ? ' is-invalid' : '' }}" name="city" value="{{old('city')}}">
										@if ($errors->has('city'))
										<span class="text-danger">
											{{ $errors->first('city') }}
										</span>
										@endif
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-12 mb-3">
									<div class="form-group">
										<label class="db-label" for="name">Address</label>
										<textarea  style="height: auto;" name="address" id="address" class="form-control db-custom-input {{ $errors->has('address') ? ' is-invalid' : '' }}" rows="4">{{old('address')}}</textarea>
										@if ($errors->has('address'))
										<span class="text-danger">
											{{ $errors->first('address') }}
										</span>
										@endif
									</div>
								</div>
								<div class="col-6 mb-3">
									<div class="form-group">
										<label class="db-label" for="name">Phone Number</label>
										<input id="phone_number" type="text" class="form-control db-custom-input {{ $errors->has('phone_number') ? ' is-invalid' : '' }}" name="phone_number" value="{{old('phone_number')}}">
										@if ($errors->has('phone_number'))
										<span class="text-danger">
											{{ $errors->first('phone_number') }}
										</span>
										@endif
									</div>
								</div>
								<div class="col-6 mb-3">
									<div class="form-group">
										<label class="db-label" for="email">Email address</label>
										<input id="email" type="text" class="form-control db-custom-input  {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"  value="{{old('email')}}">

										@if ($errors->has('email'))
										<span class="text-danger">
											{{ $errors->first('email') }}
										</span>
										@endif
									</div>
								</div>
								<div class="col-6 mb-3">
									<div class="form-group">
										<label class="db-label" for="kiosk_code">Kiosk PIN (4 digits)</label>
										<input id="kiosk_code" type="text" maxlength="4" pattern="\d{4}" class="form-control db-custom-input {{ $errors->has('kiosk_code') ? ' is-invalid' : '' }}" name="kiosk_code" value="{{old('kiosk_code')}}">
										@if ($errors->has('kiosk_code'))
										<span class="text-danger">
											{{ $errors->first('kiosk_code') }}
										</span>
										@endif
									</div>
								</div>
								<div class="col-6 mb-3">
									<div class="form-group">
										<label class="db-label" for="name">Identity Document</label>
										<select class="form-control db-custom-input {{ $errors->has('identity_document') ? ' is-invalid' : '' }}" id="identity_document" name="identity_document">
											<option selected value="" disabled>Please Select</option>
											<option value="Voter Id"  @if(old('identity_document') == 'Voter Id') selected @endif>Voter Id</option>
											<option value="Aadhar Card"  @if(old('identity_document') == 'Aadhar Card') selected @endif>Aadhar Card</option>
											<option value="Driving License"  @if(old('identity_document') == 'Driving License') selected @endif>Driving License</option>
											<option value="Passport"  @if(old('identity_document') == 'Passport') selected @endif>Passport</option>
										</select>
										@if ($errors->has('identity_document'))
										<span class="text-danger">
											{{ $errors->first('identity_document') }}
										</span>
										@endif
									</div>
								</div>
								<div class="col-6 mb-3">
									<div class="form-group">
										<label class="db-label" for="name">Identity Number </label>
										<input id="identity_number" type="text" class="form-control db-custom-input {{ $errors->has('identity_number') ? ' is-invalid' : '' }}" name="identity_number" value="{{old('identity_number')}}">

										@if ($errors->has('identity_number'))
										<span class="text-danger">
											{{ $errors->first('identity_number') }}
										</span>
										@endif
									</div>
								</div>
								<div class="col-6 mb-3">
									<label for="name" class="db-label">Employee Status</label>
									<select class="form-control db-custom-input" id="status" name="status">
										<option selected value disabled>Please Select</option>
										<option value="1">Active</option>
										<option value="0">Inactive</option>
									</select>
									@if ($errors->has('status'))
										<span class="text-danger">
											{{ $errors->first('status') }}
										</span>
									@endif
								</div>
								<div class="col-6 mb-3">
									<label for="name" class="db-label">Profile Lock?</label>
									<select class="form-control db-custom-input" id="is_proifle_edit_access" name="is_proifle_edit_access">
										<option value="0">No</option>
										<option value="1">Yes</option>
									</select>
									@if ($errors->has('is_proifle_edit_access'))
										<span class="text-danger">
											{{ $errors->first('is_proifle_edit_access') }}
										</span>
									@endif
								</div>

								<div class="col-6 mb-3">
									<div class="form-group">
										<label class="db-label" for="name">Emergency Contact Name</label>
										<input id="em_name" type="text" class="form-control db-custom-input {{ $errors->has('em_name') ? ' is-invalid' : '' }}" name="em_name">
										@if ($errors->has('em_name'))
											<span class="text-danger">
												{{ $errors->first('em_name') }}
											</span>
										@endif
									</div>
								</div>

								<div class="col-6 mb-3">
									<div class="form-group">
										<label class="db-label" for="name">Emergency Contact Number</label>
										<input id="em_number" type="text" class="form-control db-custom-input {{ $errors->has('em_number') ? ' is-invalid' : '' }}" name="em_number" >
										@if ($errors->has('em_number'))
											<span class="text-danger">
												{{ $errors->first('em_number') }}
											</span>
										@endif
									</div>
								</div>

								<div class="col-6 mb-3">
									<div class="form-group">
										<label class="db-label" for="name">Facebook URL</label>
										<input id="fb_url" type="text" class="form-control db-custom-input {{ $errors->has('fb_url') ? ' is-invalid' : '' }}" name="fb_url"  >
										@if ($errors->has('fb_url'))
											<span class="text-danger">
												{{ $errors->first('fb_url') }}
											</span>
										@endif
									</div>
								</div>
								<div class="col-6 mb-3">
									<div class="form-group">
										<label class="db-label" for="name">Linkedin URL</label>
										<input id="linkden_url" type="text" class="form-control db-custom-input {{ $errors->has('linkden_url') ? ' is-invalid' : '' }}" name="linkden_url" >
										@if ($errors->has('linkden_url'))
											<span class="text-danger">
												{{ $errors->first('linkden_url') }}
											</span>
										@endif
									</div>
								</div>

								<div class="col-12 text-end">
									<button type="submit" class="btn btn-primary submit-btn">Submit</button>
								</div>
							</div>
                    </div>
                </div>
                <div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="payment-tab">
					<div class="max-w-md max-auto">
						<div class="sub-text-heading pb-4">
							<h3 class="mb-1">Employment Information</h3>
							<!-- <p>Type your employee details here</p> -->
						</div>
						<div class="row">
								<div class="col-4 mb-3">
									<div class="form-group">
										<label class="db-label"  for="name">Hire Date</label>
										<input id="doj" type="date" class="form-control db-custom-input {{ $errors->has('doj') ? ' is-invalid' : '' }}" name="doj" >
										@if ($errors->has('doj'))
											<span class="text-danger">
												{{ $errors->first('doj') }}
											</span>
										@endif
									</div>
								</div>
								<div class="col-4 mb-3">
									<div class="form-group">
										<label class="db-label"  for="name">Position</label>
										<input id="designation" type="text" class="form-control db-custom-input {{ $errors->has('designation') ? ' is-invalid' : '' }}" name="designation">
										@if ($errors->has('designation'))
											<span class="text-danger">
												{{ $errors->first('designation') }}
											</span>
										@endif
									</div>
								</div>
								<div class="col-4 mb-3">
									<div class="form-group">
										<label class="db-label"  for="name">Department</label>
										<input id="department" type="text" class="form-control db-custom-input {{ $errors->has('department') ? ' is-invalid' : '' }}" name="department">
										@if ($errors->has('department'))
											<span class="text-danger">
												{{ $errors->first('department') }}
											</span>
										@endif
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-4 mb-3">
									<div class="form-group">
										<label class="db-label"  for="name">Social Security Number</label>
										<input id="pan_number" type="text" class="form-control db-custom-input {{ $errors->has('pan_number') ? ' is-invalid' : '' }}" name="pan_number">
										@if ($errors->has('pan_number'))
											<span class="text-danger">
												{{ $errors->first('pan_number') }}
											</span>
										@endif
									</div>
								</div>
								<div class="col-4 mb-3">
									<div class="form-group">
										<label class="db-label"  for="name">Medical Benefits Number</label>
										<input id="ifsc_code" type="text" class="form-control db-custom-input {{ $errors->has('ifsc_code') ? ' is-invalid' : '' }}" name="ifsc_code">
										@if ($errors->has('ifsc_code'))
											<span class="text-danger">
												{{ $errors->first('ifsc_code') }}
											</span>
										@endif
									</div>
								</div>
								<div class="col-4 mb-3">
									<div class="form-group">
										<label class="db-label" for="name">Employee Type</label>
										<select class="form-control db-custom-input {{ $errors->has('emp_type') ? ' is-invalid' : '' }}" id="emp_type" name="emp_type">
											<option selected value disabled>Please Select</option>
											<option value="hourly">Hourly</option>
											<option value="part-time">Part Time</option>
											<option value="full-time">Full Time</option>
										</select>
										@if ($errors->has('emp_type'))
											<span class="text-danger">
												{{ $errors->first('emp_type') }}
											</span>
										@endif
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-4 mb-3">
									<div class="form-group">
										<label class="db-label" for="name">Pay Type</label>
										<select class="form-control db-custom-input {{ $errors->has('pay_type') ? ' is-invalid' : '' }}" id="pay_type" name="pay_type">
											<option selected value disabled>Please Select</option>
											<option value="hourly">Hourly</option>
											<option value="weekly">Weekly</option>
											<option value="bi-weekly">Bi-Weekly</option>
											<option value="semi-monthly">Semi Monthly</option>
											<option value="monthly">Monthly</option>
										</select>
										@if ($errors->has('pay_type'))
										<span class="text-danger">
											{{ $errors->first('pay_type') }}
										</span>
										@endif
									</div>
								</div>
								<div class="col-4 mb-3">
									<div class="form-group">
										<label class="db-label" for="name">Amount</label>
										<input id="pay_rate" type="number" class="form-control db-custom-input {{ $errors->has('pay_rate') ? ' is-invalid' : '' }}" name="pay_rate">
										@if ($errors->has('pay_rate'))
										<span class="text-danger">
											{{ $errors->first('pay_rate') }}
										</span>
										@endif
									</div>
								</div>
								<div class="col-4 mb-3">
									<div class="form-group">
										<label class="db-label" for="name">Manager Name</label>
										<input id="manager" type="text" class="form-control db-custom-input {{ $errors->has('manager') ? ' is-invalid' : '' }}" name="manager" >
										@if ($errors->has('manager'))
											<span class="text-danger">
												{{ $errors->first('manager') }}
											</span>
										@endif
									</div>
								</div>
								<div class="col-4 mb-3">
									<div class="form-group">
										<label class="db-label" for="name">Manager Position</label>
										<input id="manager_position" type="text" class="form-control db-custom-input {{ $errors->has('manager_position') ? ' is-invalid' : '' }}" name="manager_position" >
										@if ($errors->has('	'))
											<span class="text-danger">
												{{ $errors->first('manager_position') }}
											</span>
										@endif
									</div>
								</div>
								<div class="col-12 text-end">
									<button type="submit" class="btn btn-primary submit-btn">Submit</button>
								</div>
							</div>
					</div>
                </div>
                <div class="tab-pane fade" id="admin" role="tabpanel" aria-labelledby="admin-tab">
					<div class="max-w-md max-auto">
						<div class="sub-text-heading pb-4">
							<h3 class="mb-1">Payment Information</h3>
							<!-- <p>Add your payment method here</p> -->
						</div>
							<div class="row">
								<div class="col-12 mb-3">
									<div class="form-group">
										<label class="db-label" for="name">Payment Method</label>
										<div class="d-flex gap-5">
											<div>
												<div class="position-relative db-radio-btn">
													<input class="form-check-input"  type="radio" name="payment_method" id="payment_check" value="check" onchange="showDiv(this)" checked>
													<label class="form-check-label" for="payment_check">
														<img src="{{ asset('img/bank-check.png') }}" class="mb-2" alt="">
														<h3>Cheque</h3>
													</label>
												</div>
											</div>
											<div>
												<div class="position-relative db-radio-btn">
													<input class="form-check-input" type="radio" name="payment_method" id="payment_deposit" value="deposit" onchange="showDiv(this)">
													<label class="form-check-label" for="payment_deposit">
														<img src="{{ asset('img/bank.png') }}" class="mb-2" alt="">
														<h3>Direct Deposit</h3>
													</label>
												</div>
											</div>
										</div>

										<!-- <select class="form-control select-drop-down-arrow db-custom-input {{ $errors->has('payment_method') ? ' is-invalid' : '' }}" id="payment_method" name="payment_method"  onchange="showDiv(this)">
											<option value="" selected disabled>Please Select</option>
											<option value="check">Cheque</option>
											<option value="deposit">Direct Deposit</option>
										</select>
										@if ($errors->has('payment_method'))
										<span class="text-danger">
											{{ $errors->first('payment_method') }}
										</span>
										@endif -->
									</div>
								</div>
								<div class="col-6 @if(empty($employee->paymentProfile->routing_number)) d-none @endif" id="routing_number_div">
									<label for="routing_number" class="db-label">Routing Number</label>
									<div class="form-group mb-0">
										<input id="routing_number" type="routing_number" class="form-control db-custom-input {{ $errors->has('routing_number') ? ' is-invalid' : '' }}" name="routing_number">
										@if ($errors->has('routing_number'))
											<span class="text-danger">
												{{ $errors->first('routing_number') }}
											</span>
										@endif
									</div>
								</div>
								<div class="col-6 @if(empty($employee->paymentProfile->routing_number)) d-none @endif" id="account_number_div">
									<label for="account_number" class="db-label">Account Number</label>
									<div class="form-group mb-0">
										<input id="account_number" type="account_number" class="form-control db-custom-input {{ $errors->has('account_number') ? ' is-invalid' : '' }}" name="account_number">
										@if ($errors->has('account_number'))
											<span class="text-danger">
												{{ $errors->first('account_number') }}
											</span>
										@endif
									</div>
								</div>
							</div>

							<div class="row">
								
								<div class="row mb-3 @if(empty($employee->paymentProfile->routing_number)) d-none @endif" id="account_type_div">
									<div class="col-md-6 @if(empty($employee->paymentProfile->routing_number)) d-none @endif" id="bank_div">
										<label for="bank_name" class="db-label">Bank Name</label>
										<div class="form-group mb-0">
											<input id="bank_name" type="bank_name" class="form-control db-custom-input {{ $errors->has('bank_name') ? ' is-invalid' : '' }}"  name="bank_name">

											@if ($errors->has('bank_name'))
												<span class="text-danger">
													{{ $errors->first('bank_name') }}
												</span>
											@endif
										</div>
									</div>
									<div class="col-md-6">
										<label for="name" class="db-label">Account Type</label>
										<div class="form-group mb-0">	
											<select class="form-control select-drop-down-arrow db-custom-input {{ $errors->has('account_type') ? ' is-invalid' : '' }}" id="account_type" name="account_type">
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
									</div>
								</div>
							</div>
							<div class="row">
							<div class="col-md-12 text-end">
								<button type="submit" class="btn btn-primary submit-btn">Submit</button>
							</div>
						</div>
					</div>
                </div>
                <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
					<div class="max-w-md max-auto">
						<div class="sub-text-heading pb-4 d-flex justify-content-between">
							<div>
								<h3 class="mb-1">Password</h3>
								<!-- <p>Change password here</p> -->
							</div>
						</div>

							<div id="dynamicRowsContainer">
								<div class="row">
									<!-- <div class="col-6 mb-3">
										<div class="form-group">
											<label for="name" class="db-label">Current Password</label>
											<div class="col-md-12">
												<input id="old_password" type="password" class="form-control db-custom-input {{ $errors->has('old_password') ? ' is-invalid' : '' }}" name="old_password">
												@if ($errors->has('old_password'))
												<span class="text-danger">
													{{ $errors->first('old_password') }}
												</span>
												@endif
											</div>
										</div>
									</div> -->
									<div class="col-6 mb-3">
										<div class="form-group">
											<label for="email" class="db-label">New Password</label>
											<input id="password" type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password">
											@if ($errors->has('password'))
											<span class="text-danger">
												{{ $errors->first('password') }}
											</span>
											@endif
										</div>
									</div>
									<div class="col-6 mb-3">
										<div class="form-group">
											<label for="password" class="db-label">Confirm Password</label>
											<input id="password-confirm" type="password" class="form-control" name="password_confirmation">

											@if ($errors->has('password_confirmation'))
											<span class="text-danger">
												{{ $errors->first('password_confirmation') }}
											</span>
											@endif
										</div>
									</div>

									<div class="col-md-12 text-end">
										<button type="submit" class="btn btn-primary submit-btn">Submit</button>
									</div>
								</div>
							</div>
						</form>
					</div>
                </div>
            </div>
        </div>
    </div>
</section>
@include('client.employee.face-capture')

<!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#faceCaptureModal">
    Capture Face
</button> -->
@endsection
@section('third_party_scripts')
<!-- FullCalendar JS -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://unpkg.com/tippy.js@6"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<!-- Add Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection

@push('page_scripts')
<script>
// Initialize Bootstrap components
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all modals
    var modals = document.querySelectorAll('.modal');
    modals.forEach(function(modal) {
        new bootstrap.Modal(modal);
    });

    // Initialize face capture functionality
    initializeFaceCapture();
});

// Face capture functionality
function initializeFaceCapture() {
    const faceCaptureModal = document.getElementById('faceCaptureModal');
    if (!faceCaptureModal) return;

    // Camera initialization
    let currentFacingMode = 'user';
    let videoElement;
    let stream = null;

    async function startCaptureVideo() {
        videoElement = document.getElementById('videoCapture');
        const startButton = document.getElementById('startCapture');
        const takePhotoButton = document.getElementById('takePhoto');
        const switchCameraButton = document.getElementById('switchCamera');
        const errorElement = document.getElementById('error-message');

        try {
            // Basic camera check
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                throw new Error('Camera access is not supported in your browser');
            }

            // Stop any existing stream
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }

            // Set up camera constraints
            const constraints = {
                video: {
                    facingMode: currentFacingMode,
                    width: { ideal: 1280 },
                    height: { ideal: 720 }
                },
                audio: false
            };

            // Request camera access
            stream = await navigator.mediaDevices.getUserMedia(constraints);
            videoElement.srcObject = stream;

            // Set video element dimensions based on stream
            const videoTrack = stream.getVideoTracks()[0];
            const settings = videoTrack.getSettings();
            const aspectRatio = settings.width / settings.height;
            
            // Set canvas dimensions to match video
            const canvas = document.getElementById('canvasCapture');
            canvas.width = settings.width;
            canvas.height = settings.height;

            // Update UI
            startButton.style.display = 'none';
            takePhotoButton.style.display = 'inline-block';
            errorElement.style.display = 'none';

            // Show switch camera button only if multiple cameras are available
            try {
                const devices = await navigator.mediaDevices.enumerateDevices();
                const videoDevices = devices.filter(device => device.kind === 'videoinput');
                if (videoDevices.length > 1) {
                    switchCameraButton.style.display = 'inline-block';
                    switchCameraButton.onclick = switchCamera;
                }
            } catch (err) {
                console.log('Could not detect multiple cameras');
            }

        } catch (error) {
            console.error('Camera error:', error);
            let errorMessage = 'Failed to access camera: ';
            
            if (error.name === 'NotAllowedError') {
                errorMessage += 'Please allow camera access in your browser settings';
            } else if (error.name === 'NotFoundError') {
                errorMessage += 'No camera found on your device';
            } else if (error.name === 'NotReadableError') {
                errorMessage += 'Camera is already in use by another application';
            } else {
                errorMessage += error.message;
            }
            
            errorElement.textContent = errorMessage;
            errorElement.style.display = 'block';
            startButton.style.display = 'inline-block';
        }
    }

    // Switch between front and back cameras
    async function switchCamera() {
        currentFacingMode = currentFacingMode === 'user' ? 'environment' : 'user';
        await startCaptureVideo();
    }

    // Take photo function
    function takePhoto() {
        const video = document.getElementById('videoCapture');
        const canvas = document.getElementById('canvasCapture');
        const context = canvas.getContext('2d');
        const capturedFacesDiv = document.getElementById('capturedFaces');

        // Draw video frame to canvas
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        
        // Convert to base64
        const imageData = canvas.toDataURL('image/jpeg', 0.8);
        
        // Create preview
        const previewDiv = document.createElement('div');
        previewDiv.className = 'col-6 col-md-4 position-relative';
        previewDiv.innerHTML = `
            <div class="text-center">
                <img src="${imageData}" class="img-fluid rounded" alt="Captured face">
                <button type="button" class="btn-remove-photo" onclick="this.closest('.col-6').remove()">&times;</button>
            </div>
        `;
        
        capturedFacesDiv.appendChild(previewDiv);
    }

    // Save captured faces
    function saveFaceData() {
        const capturedFacesDiv = document.getElementById('capturedFaces');
        const images = capturedFacesDiv.getElementsByTagName('img');
        
        if (images.length === 0) {
            const errorElement = document.getElementById('error-message');
            errorElement.textContent = 'Please capture at least one photo';
            errorElement.style.display = 'block';
            return;
        }
        
        // Create array of captured images
        const capturedData = Array.from(images).map(img => img.src);
        
        // Store in hidden input
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'face_data';
        input.value = JSON.stringify(capturedData);
        document.querySelector('form').appendChild(input);
        
        // Close modal and remove backdrop
        const modal = bootstrap.Modal.getInstance(faceCaptureModal);
        modal.hide();
        
        // Remove modal backdrop and reset body styles
        setTimeout(() => {
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('overflow');
            document.body.style.removeProperty('padding-right');
        }, 200);
    }

    // Attach event listeners when modal is shown
    faceCaptureModal.addEventListener('shown.bs.modal', function () {
        console.log('Face capture modal shown');
        const startButton = document.getElementById('startCapture');
        const takePhotoButton = document.getElementById('takePhoto');
        const saveFacesButton = document.getElementById('saveFaces');

        if (startButton) {
            startButton.onclick = function() {
                console.log('Start camera button clicked');
                startCaptureVideo();
            };
        }

        if (takePhotoButton) {
            takePhotoButton.onclick = takePhoto;
        }

        if (saveFacesButton) {
            saveFacesButton.onclick = saveFaceData;
        }
    });

    // Cleanup when modal is hidden
    faceCaptureModal.addEventListener('hidden.bs.modal', function () {
        // Stop camera
        const video = document.getElementById('videoCapture');
        if (video.srcObject) {
            video.srcObject.getTracks().forEach(track => track.stop());
            video.srcObject = null;
        }

        // Remove modal backdrop and reset body styles
        setTimeout(() => {
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('overflow');
            document.body.style.removeProperty('padding-right');
        }, 200);

        // Clear captured faces
        const capturedFacesDiv = document.getElementById('capturedFaces');
        if (capturedFacesDiv) {
            capturedFacesDiv.innerHTML = '';
        }

        // Reset buttons
        const startButton = document.getElementById('startCapture');
        const takePhotoButton = document.getElementById('takePhoto');
        if (startButton) startButton.style.display = 'block';
        if (takePhotoButton) takePhotoButton.style.display = 'none';
    });
}

// Payment method toggle function
function showDiv(obj) {
    if ($(obj).val() == 'check') {
        $('#routing_number_div').addClass('d-none');
        $('#account_number_div').addClass('d-none');
        $('#account_type_div').addClass('d-none');
        $('#bank_div').addClass('d-none');
    }

    if ($(obj).val() == 'deposit') {
        $('#routing_number_div').removeClass('d-none');
        $('#account_number_div').removeClass('d-none');
        $('#account_type_div').removeClass('d-none');
        $('#bank_div').removeClass('d-none');
    }
}

// File upload handling
const fileUpload = (event) => {
    const files = event.target.files;
    const filesLength = files.length;

    const imagePreviewElement = document.querySelector("#tb-image");

    if (filesLength > 0) {
        const imageSrc = URL.createObjectURL(files[0]);
        imagePreviewElement.src = imageSrc;
        imagePreviewElement.style.display = "block";
    } else {
        imagePreviewElement.style.display = "none";
    }

    convertImageToBase64(event);
};

function convertImageToBase64(event) {
    const file = event.target.files[0];
    const reader = new FileReader();
    reader.onload = function(event) {
        const base64 = event.target.result;
        document.getElementById('base64_image').value = base64;
    };
    reader.readAsDataURL(file);
}
</script>
@endpush