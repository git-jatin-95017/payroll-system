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
			@if(!empty($company->companyProfile->logo))
				<img id="tb-image" src="/files/{{$company->companyProfile->logo}}" width="225"  height="225" style="object-fit:contain !important;" alt="profile" />
			@else
				<img id="tb-image" src="{{ asset('img/no_img.jpg') }}"  alt="Uploaded Image" width="225"  height="225" style="object-fit:contain !important;" alt="profile" />
			@endif
		</div>
        <div>
            <div class="d-flex flex-column justify-content-between h-100">
                <div class="profile-name-container pt-5">
                    <h3>{{ ucwords(auth()->user()->name) }}</h3>
                    <p class="mt-4">{{ $company->companyProfile->designation ?? ''}}</p>
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
                <div class="d-flex gap-2 employee-info align-items-center mb-2">
                    <div>
                        <x-heroicon-o-map-pin class="w-20 h-20" />
                    </div>
                    <div>
                        <p class="mb-0">{{ $company->companyProfile->country }} ({{ ucwords($company->companyProfile->city) }})</p>
                    </div>
                </div>
                <div class="d-flex gap-2 employee-info align-items-center mb-3">
                    <div>
                        <x-heroicon-o-envelope class="w-20 h-20" />
                    </div>
                    <div>
                        <p class="mb-0">{{$company->email}}</p>
                    </div>
                </div>
                <ul class="mb-0 p-0 d-flex align-items-center gap-3 employee-social-media">
					<li>
                        <a href="{{ $company->companyProfile->fb_url }}" target="_blank">
                            <x-bxl-facebook-square class="w-24 h-24" />
                        </a>
                    </li>
                    <li>
                        <a href="{{ $company->companyProfile->linkden_url }}"  target="_blank">
                            <x-bxl-linkedin-square class="w-24 h-24" />
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="bg-white w-100 border-radius-15 p-4">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="company" role="tabpanel" aria-labelledby="company-tab">
                    <div class="max-w-md max-auto">
                        <div class="sub-text-heading pb-4">
                            <h3 class="mb-1">Company Information</h3>
                            <!-- <p>Type your information</p> -->
                        </div>
						<form class="form-horizontal" method="POST"
							action="{{ route('my-profile.update', auth()->user()->id) }}" enctype="multipart/form-data">
							@csrf
							{{ method_field('PUT') }}
								<input type="hidden" name="update_request" value="personal">

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
								</div>
								<div class="row">
									<div class="col-8 mb-3">
										<div class="form-group">
											<label class="db-label" for="name">Company Name</label>
											<input id="company_name" type="text"
												class="form-control db-custom-input {{ $errors->has('company_name') ? ' is-invalid' : '' }}"
												name="company_name"
												value="{{ !empty($company->companyProfile->company_name) ? $company->companyProfile->company_name : auth()->user()->name }}">
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
												name="country" value="{{ $company->companyProfile->country ?? NULL }}">
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
												name="state" value="{{ $company->companyProfile->city ?? NULL}}">
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
												name="city" value="{{ $company->companyProfile->city ?? NULL}}">
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
												rows="4">{{ $company->companyProfile->address ?? NULL}}</textarea>
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
												name="phone_number" value="{{ $company->companyProfile->phone_number ?? NULL}}">
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
											<input id="email" type="text"
												class="form-control db-custom-input {{ $errors->has('email') ? ' is-invalid' : '' }}"
												name="email" value="{{ $company->email }}">
											@if ($errors->has('email'))
											<span class="text-danger">
												{{ $errors->first('email') }}
											</span>
											@endif
										</div>
									</div>
									<div class="col-4 mb-3">
										<div class="form-group">
											<label class="db-label" for="name">Medical Benefits Registration No</label>
											<input id="medical_no" type="text"
												class="form-control db-custom-input {{ $errors->has('medical_no') ? ' is-invalid' : '' }}"
												name="medical_no" value="{{ $company->companyProfile->medical_no ?? NULL}}">
											@if ($errors->has('medical_no'))
											<span class="text-danger">
												{{ $errors->first('medical_no') }}
											</span>
											@endif
										</div>
									</div>
									<div class="col-4 mb-3">
										<div class="form-group">
											<label class="db-label" for="name">Social Security Registration No </label>
											<input id="ssr_no" type="text"
												class="form-control db-custom-input {{ $errors->has('ssr_no') ? ' is-invalid' : '' }}"
												name="ssr_no" value="{{ $company->companyProfile->ssr_no ?? NULL}}">
											@if ($errors->has('ssr_no'))
											<span class="text-danger">
												{{ $errors->first('ssr_no') }}
											</span>
											@endif
										</div>
									</div>
									<div class="col-4 mb-3">
										<div class="form-group">
											<label class="db-label" for="name">Education Levy ID No</label>
											<input id="levy_id_no" type="text"
												class="form-control db-custom-input {{ $errors->has('levy_id_no') ? ' is-invalid' : '' }}"
												name="levy_id_no" value="{{ $company->companyProfile->levy_id_no?? NULL }}">
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
											<input id="fb_url" type="text" class="form-control db-custom-input {{ $errors->has('fb_url') ? ' is-invalid' : '' }}" name="fb_url" value="{{ $company->companyProfile->fb_url }}"  >
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
											<input id="linkden_url" type="text" class="form-control db-custom-input {{ $errors->has('linkden_url') ? ' is-invalid' : '' }}" name="linkden_url" value="{{ $company->companyProfile->linkden_url }}" >
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
						</form>
                    </div>
                </div>
                <div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="payment-tab">
					<div class="max-w-md max-auto">
						<div class="sub-text-heading pb-4">
							<h3 class="mb-1">Payment Information</h3>
							<!-- <p>Type your employee details here</p> -->
						</div>
						<form class="form-horizontal" method="POST"
							action="{{ route('my-profile.update', auth()->user()->id) }}">
							@csrf
							{{ method_field('PUT') }}
							<input type="hidden" name="update_request" value="payment">
							<!-- <div class="row">
								<div class="col-8 mb-3">
									<input type="hidden" name="update_request" value="payment">
									<div class="form-group">
										<label class="db-label" for="name">Payment Method</label>
										<select
											class="form-control select-drop-down-arrow db-custom-input{{ $errors->has('payment_method') ? ' is-invalid' : '' }}"
											id="payment_method" name="payment_method" onchange="showDiv(this)">
											<option value="" selected disabled>Please Select</option>
											<option @if(!empty($company->paymentProfile->payment_method) &&
												$company->paymentProfile->payment_method == "check") selected @endif
												value="check">Cheque</option>
											<option @if(!empty($company->paymentProfile->payment_method) &&
												$company->paymentProfile->payment_method == "Direct Deposit")
												selected @endif svalue="deposit">Direct Deposit</option>
										</select>
										@if ($errors->has('payment_method'))
										<span class="text-danger">
											{{ $errors->first('payment_method') }}
										</span>
										@endif
									</div>
								</div>
							</div> -->
							<div class="row">
								<div class="col-12 mb-3">
									<div class="form-group">
										<label class="db-label mb-2">Payment Method</label>
										<div class="d-flex gap-5">
											<div>
												<div class="position-relative db-radio-btn">
													<input class="form-check-input"  type="radio" name="payment_method" id="payment_check" value="check" onchange="showDiv(this)" @if(!empty($company->paymentProfile->payment_method) && $company->paymentProfile->payment_method == "check") checked @endif>
													<label class="form-check-label" for="payment_check">
														<img src="{{ asset('img/bank-check.png') }}" class="mb-2" alt="">
														<h3>Cheque</h3>
													</label>
												</div>
											</div>
											<div>
												<div class="position-relative db-radio-btn">
													<input class="form-check-input" type="radio" name="payment_method" id="payment_deposit" value="deposit" onchange="showDiv(this)" @if(!empty($company->paymentProfile->payment_method) && $company->paymentProfile->payment_method == "deposit") checked @endif>
													<label class="form-check-label" for="payment_deposit">
														<img src="{{ asset('img/bank.png') }}" class="mb-2" alt="">
														<h3>Direct Deposit</h3>
													</label>
												</div>
											</div>
										</div>
										@if ($errors->has('payment_method'))
										<span class="text-danger">
											{{ $errors->first('payment_method') }}
										</span>
										@endif
									</div>
								</div>
								<div class="col-6 mb-3 @if(empty($company->paymentProfile->routing_number)) d-none @endif" id="routing_number_div">
									<label for="routing_number" class="db-label">Routing Number</label>
									<div class="form-group mb-0">
										<input id="routing_number" type="routing_number" class="form-control db-custom-input {{ $errors->has('routing_number') ? ' is-invalid' : '' }}" name="routing_number" value="{{ !empty($company->paymentProfile->routing_number) ? $company->paymentProfile->routing_number : '' }}" >
										@if ($errors->has('routing_number'))
											<span class="text-danger">
												{{ $errors->first('routing_number') }}
											</span>
										@endif
									</div>
								</div>
								<div class="col-6 mb-3 @if(empty($company->paymentProfile->routing_number)) d-none @endif" id="account_number_div">
									<label for="account_number" class="db-label">Account Number</label>
									<div class="form-group mb-0">
										<input id="account_number" type="account_number" class="form-control db-custom-input {{ $errors->has('account_number') ? ' is-invalid' : '' }}" value="{{ !empty($company->paymentProfile->account_number) ? $company->paymentProfile->account_number:'' }}" name="account_number" >
										@if ($errors->has('account_number'))
											<span class="text-danger">
												{{ $errors->first('account_number') }}
											</span>
										@endif
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-12 mb-3 @if(empty($company->paymentProfile->routing_number)) d-none @endif" id="account_type_div">
									<div class="row">
										<div class="col-md-6 @if(empty($company->paymentProfile->routing_number)) d-none @endif" id="bank_div">
											<label for="bank_name" class="db-label">Bank Name</label>
											<div class="form-group mb-0">
												<input id="bank_name" type="bank_name" class="form-control db-custom-input {{ $errors->has('bank_name') ? ' is-invalid' : '' }}" value="{{ !empty($company->paymentProfile->bank_name) ? $company->paymentProfile->bank_name:'' }}" name="bank_name" >

												@if ($errors->has('bank_name'))
													<span class="text-danger">
														{{ $errors->first('bank_name') }}
													</span>
												@endif
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="name" class="db-label" >Account Type</label>
												<select class="form-control select-drop-down-arrow  db-custom-input {{ $errors->has('account_type') ? ' is-invalid' : '' }}" id="account_type" name="account_type">
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
										</div>
									</div>
								</div>
							</div>


							<!-- <div class="row">
								<div class="col-6 mb-3">
									<div class="form-group
										@if(empty($company->paymentProfile->account_type)) d-none @endif" id="account_type_div">
										<label class="db-label" for="name">Account Type</label>
										<select
											class="form-control select-drop-down-arrow db-custom-input {{ $errors->has('account_type') ? ' is-invalid' : '' }}"
											id="account_type" name="account_type">
											<option value="" disabled>Please Select</option>
											<option @if(!empty($company->paymentProfile->account_type) &&
												$company->paymentProfile->account_type == "checking") selected
												@endif value="checking">Chequing</option>
											<option @if(!empty($company->paymentProfile->account_type) &&
												$company->paymentProfile->account_type == "saving") selected @endif
												value="saving">Saving</option>
										</select>
										@if ($errors->has('account_type'))
										<span class="text-danger">
											{{ $errors->first('account_type') }}
										</span>
										@endif
									</div>
								</div>
								<div class="col-6 mb-3">
									<div class="form-group
										@if(empty($company->paymentProfile->account_number)) d-none @endif" id="account_number_div">
										<label class="db-label" for="account_number">Account Number</label>
										<input id="account_number" type="account_number"
											class="form-control db-custom-input {{ $errors->has('account_number') ? ' is-invalid' : '' }}"
											name="account_number"
											value="{{ $company->paymentProfile->account_number ?? '' }}">
										@if ($errors->has('account_number'))
										<span class="text-danger">
											{{ $errors->first('account_number') }}
										</span>
										@endif
									</div>
								</div>

								<div class="col-6 mb-3">
									<div class="form-group">
										<label class="db-label" for="bank_name">Bank Name</label>
										<input id="bank_name" type="bank_name"
											class="form-control db-custom-input {{ $errors->has('bank_name') ? ' is-invalid' : '' }}"
											name="bank_name" value="{{ $company->paymentProfile->bank_name ?? '' }}">
										@if ($errors->has('bank_name'))
										<span class="text-danger">
											{{ $errors->first('bank_name') }}
										</span>
										@endif
									</div>
								</div>
								<div class="col-6 mb-3">
									<div class="form-group
										@if(empty($company->paymentProfile->routing_number)) d-none @endif" id="routing_number_div">
										<label class="db-label" for="routing_number">Routing Number</label>
										<input id="routing_number" type="routing_number"
											class="form-control db-custom-input {{ $errors->has('routing_number') ? ' is-invalid' : '' }}"
											name="routing_number"
											value="{{ $company->paymentProfile->routing_number?? '' }}">
										@if ($errors->has('routing_number'))
										<span class="text-danger">
											{{ $errors->first('routing_number') }}
										</span>
										@endif
									</div>
								</div>
								<div class="col-12 mb-3">
									<div class="form-group">
										<label class="db-label" for="bank_address">Bank Address</label>
										<textarea style="height: auto;" name="bank_address" id="bank_address"
											class="form-control db-custom-input {{ $errors->has('bank_address') ? ' is-invalid' : '' }}"
											rows="4">{!! $company->paymentProfile->bank_address ?? NULL !!}</textarea>

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
						</form>
					</div>
                </div>
                <div class="tab-pane fade" id="admin" role="tabpanel" aria-labelledby="admin-tab">
					<div class="max-w-md max-auto">
						<div class="sub-text-heading pb-4">
							<h3 class="mb-1">Administrators</h3>
							<!-- <p>Add your payment method here</p> -->
						</div>
						<form class="form-horizontal" method="POST"
							action="{{ route('my-profile.update', auth()->user()->id) }}">
							@csrf
							{{ method_field('PUT') }}
							<input type="hidden" name="update_request" value="changepwd">
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
						</form>

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
						<form class="form-horizontal" method="POST"
							action="{{ route('my-profile.update', auth()->user()->id) }}">
							@csrf
							{{ method_field('PUT') }}
							<input type="hidden" name="update_request" value="changepwdown">
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
@endsection

@push('page_scripts')
<script>
	$(document).ready(function () {
		var maxRows = 3;
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
	// $('[name="payment_method"]').trigger('click');

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
	// function showDiv(obj) {
	// 	console.log(obj.value);
	// 	if (obj.value == 'check') {
	// 		$('#routing_number_div').addClass('d-none');
	// 		//$('#account_number_div').addClass('d-none');
	// 		// $('#account_type_div').addClass('d-none');
	// 	}

	// 	if (obj.value == 'Direct Deposit') {
	// 		$('#routing_number_div').removeClass('d-none');
	// 		$('#account_number_div').removeClass('d-none');
	// 		$('#account_type_div').removeClass('d-none');
	// 	}
	// }
</script>
<script>

	// imgInp.onchange = evt => {
	// 	const [file] = imgInp.files
	// 	if (file) {
	// 		blah.src = URL.createObjectURL(file)
	// 	}
	// }
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