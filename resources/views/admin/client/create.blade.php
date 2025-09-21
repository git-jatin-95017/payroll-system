@extends('layouts.new_layout')

@section('content')
<section>
    <div class="page-heading d-flex justify-content-between align-items-center gap-3 mb-3">
		<div>
			<!-- <h3>Profile</h3> -->
			<!-- <p class="mb-0">Track and manage profile here</p> -->
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
			
				<img id="tb-image" src="{{ asset('img/no_img.jpg') }}"  alt="Uploaded Image" width="225"  height="225" style="object-fit:contain !important;" alt="profile" />
		</div>
        <div>
            <div class="d-flex flex-column justify-content-between h-100">
                <div class="profile-name-container pt-5">
                    <h3>Company</h3>
                    <!-- <p class="mt-4"></p> -->
                </div>
                <div>
                    <ul class="nav nav-tabs nav-pills db-custom-tabs gap-5 employee-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="company-tab" data-bs-toggle="tab" data-bs-target="#company"
                                type="button" role="tab" aria-controls="company" aria-selected="true">Company</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment" type="button"
                                role="tab" aria-controls="payment" aria-selected="false">Payment</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="admin-tab" data-bs-toggle="tab" data-bs-target="#admin" type="button"
                                role="tab" aria-controls="admin" aria-selected="false">Administrators</button>
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
                    <li>
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
                    </li>
                </ul>
            </div>
        </div>
        <div class="bg-white w-100 border-radius-15 p-4">
			<form class="form-horizontal" method="POST" action="{{ route('client.store') }}" enctype="multipart/form-data">
			@csrf
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="company" role="tabpanel" aria-labelledby="company-tab">
                    <div class="max-w-md max-auto">
                        <div class="sub-text-heading pb-4">
                            <h3 class="mb-1">Company Information</h3>
                            <!-- <p>Type your information</p> -->
                        </div>
						<div class="row">
							<div class="col-12 mb-3">
								<div class="tb-container d-flex gap-4 align-items-center">
									
									<label for="tb-file-upload">
										Upload Image
										<x-bx-upload class="w-20 g-20 ms-1"></x-bx-upload>
									</label>
									<input type="file" name="logo" id="tb-file-upload" accept="image/*"
										onchange="fileUpload(event);" />
								</div>

							</div>
						</div>
						<div class="row">
							<div class="col-8 mb-3">
								<div class="form-group">
									<label class="db-label" for="name">Company Name</label>
									<input id="company_name" type="text"
										class="form-control db-custom-input {{ $errors->has('company_name') ? ' is-invalid' : '' }}"
										name="company_name">
									@if ($errors->has('company_name'))
									<span class="text-danger">
										{{ $errors->first('company_name') }}
									</span>
									@endif
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-4 mb-3">
								<div class="form-group">
									<label class="db-label" for="name">Country</label>
									<input id="country" type="text"
										class="form-control db-custom-input {{ $errors->has('country') ? ' is-invalid' : '' }}"
										name="country" value="">
									@if ($errors->has('country'))
									<span class="text-danger">
										{{ $errors->first('country') }}
									</span>
									@endif
								</div>
							</div>
							<div class="col-4 mb-3">
								<div class="form-group">
									<label class="db-label" for="name">State</label>
									<input id="city" type="text"
										class="form-control db-custom-input {{ $errors->has('city') ? ' is-invalid' : '' }}"
										name="state" value="">
									@if ($errors->has('city'))
									<span class="text-danger">
										{{ $errors->first('city') }}
									</span>
									@endif
								</div>
							</div>
							<div class="col-4 mb-3">
								<div class="form-group">
									<label class="db-label" for="name">City</label>
									<input id="city" type="text"
										class="form-control db-custom-input {{ $errors->has('city') ? ' is-invalid' : '' }}"
										name="city" value="">
									@if ($errors->has('city'))
									<span class="text-danger">
										{{ $errors->first('city') }}
									</span>
									@endif
								</div>
							</div>
							<div class="col-12 mb-3">
								<div class="form-group">
									<label class="db-label" for="name">Address</label>
									<textarea name="address" id="address" style="height: auto;"
										class="form-control db-custom-input {{ $errors->has('address') ? ' is-invalid' : '' }}"
										rows="4"></textarea>
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
									<input id="phone_number" type="text"
										class="form-control db-custom-input {{ $errors->has('phone_number') ? ' is-invalid' : '' }}"
										name="phone_number" value="">
									@if ($errors->has('phone_number'))
									<span class="text-danger">
										{{ $errors->first('phone_number') }}
									</span>
									@endif
								</div>
							</div>
							<div class="col-6 mb-3">
								<div class="form-group">
									<label class="db-label" for="email">Company Email address</label>
									<input id="email" type="text"
										class="form-control db-custom-input {{ $errors->has('email') ? ' is-invalid' : '' }}"
										name="email_address" value="">
									@if ($errors->has('email_address'))
									<span class="text-danger">
										{{ $errors->first('email_address') }}
									</span>
									@endif
								</div>
							</div>
							<!-- <div class="col-6 mb-3">
								<div class="form-group">
									<label class="db-label" for="email">Company Password</label>
									<input id="passwordc" type="passwordc" class="db-custom-input form-control {{ $errors->has('passwordc') ? ' is-invalid' : '' }}" name="passwordc">

									@if ($errors->has('passwordc'))
									<span class="text-danger">
										{{ $errors->first('passwordc') }}
									</span>
									@endif
								</div>
							</div> -->
							<div class="col-4 mb-3">
								<div class="form-group">
									<label class="db-label" for="name">Medical Benefits Registration No.</label>
									<input id="medical_no" type="text"
										class="form-control db-custom-input {{ $errors->has('medical_no') ? ' is-invalid' : '' }}"
										name="medical_no" value="">
									@if ($errors->has('medical_no'))
									<span class="text-danger">
										{{ $errors->first('medical_no') }}
									</span>
									@endif
								</div>
							</div>
							<div class="col-4 mb-3">
								<div class="form-group">
									<label class="db-label" for="name">Social Security Registration No. </label>
									<input id="ssr_no" type="text"
										class="form-control db-custom-input {{ $errors->has('ssr_no') ? ' is-invalid' : '' }}"
										name="ssr_no" value="">
									@if ($errors->has('ssr_no'))
									<span class="text-danger">
										{{ $errors->first('ssr_no') }}
									</span>
									@endif
								</div>
							</div>
							<div class="col-4 mb-3">
								<div class="form-group">
									<label class="db-label" for="name">Education Levy ID No.</label>
									<input id="levy_id_no" type="text"
										class="form-control db-custom-input {{ $errors->has('levy_id_no') ? ' is-invalid' : '' }}"
										name="levy_id_no" value="">
									@if ($errors->has('levy_id_no'))
									<span class="text-danger">
										{{ $errors->first('levy_id_no') }}
									</span>
									@endif
								</div>
							</div>
							<div class="col-6 mb-3">
										<div class="form-group">
											<label class="db-label" for="name">Facebook URL</label>
											<input id="fb_url" type="text" class="form-control db-custom-input {{ $errors->has('fb_url') ? ' is-invalid' : '' }}" name="fb_url" value="{{ $company->companyProfile->fb_url?? NULL }}"  >
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
											<input id="linkden_url" type="text" class="form-control db-custom-input {{ $errors->has('linkden_url') ? ' is-invalid' : '' }}" name="linkden_url" value="{{ $company->companyProfile->linkden_url ?? NULL }}" >
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
							<h3 class="mb-1">Payment Information</h3>
							<!-- <p>Type your employee details here</p> -->
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
						<!-- <div class="row">
							<div class="col-8 mb-3">
								<div class="form-group">
									<label class="db-label" for="name">Payment Method</label>
									<select
										class="form-control select-drop-down-arrow db-custom-input{{ $errors->has('payment_method') ? ' is-invalid' : '' }}"
										id="payment_method" name="payment_method" onchange="showDiv(this)">
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
							</div>
						</div> -->
						<!-- <div class="row">
							<div class="col-4 mb-3">
								<div class="form-group"
									 id="routing_number_div">
									<label class="db-label" for="routing_number">Routing Number</label>
									<input id="routing_number" type="routing_number"
										class="form-control db-custom-input {{ $errors->has('routing_number') ? ' is-invalid' : '' }}"
										name="routing_number"
										value="">
									@if ($errors->has('routing_number'))
									<span class="text-danger">
										{{ $errors->first('routing_number') }}
									</span>
									@endif
								</div>
							</div>
							<div class="col-4 mb-3">
								<div class="form-group" id="account_number_div">
									<label class="db-label" for="account_number">Account Number</label>
									<input id="account_number" type="account_number"
										class="form-control db-custom-input {{ $errors->has('account_number') ? ' is-invalid' : '' }}"
										name="account_number"
										value="">
									@if ($errors->has('account_number'))
									<span class="text-danger">
										{{ $errors->first('account_number') }}
									</span>
									@endif
								</div>
							</div>
						</div> -->
						<!-- <div class="row">
							<div class="col-4 mb-3">
								<div class="form-group" id="account_type_div">
									<label class="db-label" for="name">Account Type</label>
									<select
										class="form-control select-drop-down-arrow db-custom-input {{ $errors->has('account_type') ? ' is-invalid' : '' }}"
										id="account_type" name="account_type">
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
						</div> -->
						<div class="row">
							<!-- <div class="col-6 mb-3">
								<div class="form-group">
									<label class="db-label" for="bank_name">Bank Name</label>
									<input id="bank_name" type="bank_name"
										class="form-control db-custom-input {{ $errors->has('bank_name') ? ' is-invalid' : '' }}"
										name="bank_name" value="">
									@if ($errors->has('bank_name'))
									<span class="text-danger">
										{{ $errors->first('bank_name') }}
									</span>
									@endif
								</div>
							</div>
							<div class="col-12 mb-3">
								<div class="form-group">
									<label class="db-label" for="bank_address">Bank Address</label>
									<textarea style="height: auto;" name="bank_address" id="bank_address"
										class="form-control db-custom-input {{ $errors->has('bank_address') ? ' is-invalid' : '' }}"
										rows="4"></textarea>

									@if ($errors->has('bank_address'))
									<span class="text-danger">
										{{ $errors->first('bank_address') }}
									</span>
									@endif
								</div>
							</div> -->
							<div class="col-12 text-end">
								<button type="submit" class="btn btn-primary submit-btn">Submit</button>
							</div>
						</div>
					</div>
                </div>
                <div class="tab-pane fade" id="admin" role="tabpanel" aria-labelledby="admin-tab">
					<div class="max-w-md max-auto">
						<div class="sub-text-heading pb-4">
							<h3 class="mb-1">Administrators</h3>
							<p class="mb-0">Add administrators for this company</p>
						</div>
						
						<!-- Add New Administrator Section -->
						<div class="border-top pt-4">
							<h5 class="mb-3 text-success">
								<i class="fas fa-plus-circle"></i> Add New Administrator
							</h5>
							<p class="text-muted mb-3">Fill in the details below to add new administrators to this company.</p>
							<div id="dynamicRowsContainer">
								<div class="row">
									<div class="col-6 mb-3">
										<div class="form-group">
											<label for="name" class="db-label">Admin Name</label>
											<div class="col-md-12">
												<input id="name" type="text"
													class="form-control db-custom-input {{ $errors->has('name') ? ' is-invalid' : '' }}"
													name="name[]">
												@if ($errors->has('name'))
												<span class="text-danger">
												{{ $errors->first('name') }}
												</span>
												@endif
											</div>
										</div>
									</div>
									<div class="col-6 mb-3">
										<div class="form-group">
											<label for="email" class="db-label">Admin Email address</label>
											<input type="text"
												class="form-control db-custom-input {{ $errors->has('email') ? ' is-invalid' : '' }}"
												name="email[]" value="">
											@if ($errors->has('email'))
											<span class="text-danger">
											{{ $errors->first('email') }}
											</span>
											@endif
										</div>
									</div>
									<div class="col-6 mb-3">
										<div class="form-group">
											<label for="password" class="db-label">Admin Login Password</label>
											<input type="password"
												class="form-control db-custom-input {{ $errors->has('password') ? ' is-invalid' : '' }}"
												name="password[]">
											@if ($errors->has('password'))
											<span class="text-danger">
											{{ $errors->first('password') }}
											</span>
											@endif
										</div>
									</div>
									<div class="col-6 mb-3">
										<div class="form-group">
											<label for="password-confirm" class="db-label">Confirm Password</label>
											<input type="password" class="form-control db-custom-input"
												name="password_confirmation[]">
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12 text-end">
								<button type="button" id="addNewRow" class="btn btn-primary submit-btn">
									Add New
								</button>
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
					</div>
                </div>

            </div>
			</form>
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
	            var newRow = '<div class="row">' +
					'   <div class="col-6 mb-3">' +
					'       <label for="name" class="db-label">Admin Name</label>' +
					'       <div class="col-md-12">' +
					'           <input type="text" class="form-control db-custom-input" name="name[]">' +
					'       </div>' +
					'   </div>' +
					'   <div class="col-6 mb-3">' +
					'       <label for="email" class="db-label">Admin Email address</label>' +
					'       <input type="text" class="form-control db-custom-input" name="email[]">' +
					'   </div>' +
					'   <div class="col-6 mb-3">' +
					'       <label for="password" class="db-label">Admin Login Password</label>' +
					'       <input type="password" class="form-control db-custom-input" name="password[]">' +
					'   </div>' +
					'   <div class="col-6 mb-3">' +
					'       <label for="password-confirm" class="db-label">Confirm Password</label>' +
					'       <input type="password" class="form-control db-custom-input" name="password_confirmation[]">' +
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
	// function showDiv(obj) {
	// 	if ($(obj).val() == 'check') {
	// 		$('#routing_number_div').addClass('d-none');
	// 		$('#account_number_div').addClass('d-none');
	// 		$('#account_type_div').addClass('d-none');
	// 	}

	// 	if ($(obj).val() == 'deposit') {
	// 		$('#routing_number_div').removeClass('d-none');
	// 		$('#account_number_div').removeClass('d-none');
	// 		$('#account_type_div').removeClass('d-none');
	// 	}
	// }

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