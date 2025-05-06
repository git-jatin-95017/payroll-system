@extends('layouts.new_layout')
@section('content')
<section>
@include('client.employee.face-capture')

    <div class="page-heading d-flex justify-content-between align-items-center gap-3 mb-3">
		<div>
			<!-- <h3>Edit Employee Profile</h3> -->
			<!-- <p class="mb-0">Edit employee profile here</p> -->
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
			@if(!empty($employee->employeeProfile->file))
				<img id="tb-image" src="/files/{{$employee->employeeProfile->file}}" width="225"  height="225" style="object-fit:contain !important;" alt="profile" />
			@else
				<img id="tb-image" src="{{asset('img/no_img.jpg')}}" width="225"  height="225" style="object-fit:contain !important;" alt="profile" />
			@endif
		</div>
        <div>
            <div class="d-flex flex-column justify-content-between h-100">
                <div class="profile-name-container pt-5">
                    <h3>{{ $employee->employeeProfile->first_name ?? ''}} {{ $employee->employeeProfile->last_name ?? ''}}</h3>
                    <p>{{ $employee->employeeProfile->designation ?? ''}}</p>
                </div>
                <div>
                    <ul class="nav nav-tabs nav-pills db-custom-tabs gap-5 employee-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="company-tab" data-bs-toggle="tab" data-bs-target="#company"
                                type="button" role="tab" aria-controls="company" aria-selected="true">Personal								</button>
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
		<div class="bg-white sub-text-heading py-4 px-3 border-radius-15">
				<h3 class="mb-1">Personal</h3>
				<div class="d-flex gap-2 employee-info align-items-center mb-3">
                    <div>
						<x-bx-phone class="w-20 h-20" />
                    </div>
                    <div>
                        <p class="mb-0">{{ $employee->phone_number }}</p>
                    </div>
                </div>
				<div class="d-flex gap-2 employee-info align-items-center mb-3">
                    <div>
						<x-bx-user class="w-20 h-20"  style="color: #d76060 !important;" />
                    </div>
                    <div>
                        <p class="mb-0">{{ $employee->employeeProfile->em_name }}</p>
                    </div>
                </div>
				<div class="d-flex gap-2 employee-info align-items-center mb-3">
                    <div>
						<x-bx-phone class="w-20 h-20"  style="color: #d76060 !important;" />
                    </div>
                    <div>
                        <p class="mb-0">{{ $employee->employeeProfile->em_number }}</p>
                    </div>
                </div>
				<div class="d-flex gap-2 employee-info align-items-center mb-3">
                    <div>
						<x-bx-envelope class="w-20 h-20" />
                    </div>
                    <div>
                        <p class="mb-0">{{ $employee->email }}</p>
                    </div>
                </div>
				<div class="d-flex gap-2 employee-info align-items-center mb-2">
                    <div>
                        <x-bx-map class="w-20 h-20" />
                    </div>
                    <div>
                        <p class="mb-0">
							{{$employee->employeeProfile->address}},
							{{$employee->employeeProfile->city}},
							{{$employee->employeeProfile->country}},
						</p>
                    </div>
                </div>
				<ul class="mb-2 p-0 d-flex align-items-center gap-3 employee-social-media">
                    <li>
                        <a href="{{$employee->employeeProfile->fb_url}}" target="_blank">
                            <x-bxl-facebook-square class="w-24 h-24" />
                        </a>
                    </li>
                    <li>
                        <a href="{{$employee->employeeProfile->linkden_url}}"  target="_blank">
                            <x-bxl-linkedin-square class="w-24 h-24" />
                        </a>
                    </li>
                </ul>

				<h3 class="mb-2 mt-4">Employment</h3>
				

				<div class="d-flex gap-2 employee-info align-items-center mb-2">
                    <div>
						<x-bx-briefcase-alt class="w-20 h-20" />
                    </div>
                    <div>
                        <p class="mb-0">{{$employee->employeeProfile->designation}}</p>
                    </div>
                </div>

				<div class="d-flex gap-2 employee-info align-items-center mb-2">
                    <div>
                        <x-bx-building class="w-20 h-20" />
                    </div>
                    <div>
                        <p class="mb-0">{{$employee->employeeProfile->department}}</p>
                    </div>
                </div>

				<div class="d-flex gap-2 employee-info align-items-center mb-2">
                    <div>
                        <x-bx-dock-left class="w-20 h-20" />
                    </div>
                    <div>
                        <p class="mb-0">{{ ucwords($employee->employeeProfile->emp_type) }}</p>
                    </div>
                </div>

				<h3 class="mb-2 mt-4">Hire date</h3>
				<div class="d-flex gap-2 employee-info align-items-center mb-2">
                    <div>
                        <x-bx-calendar class="w-20 h-20" />
                    </div>
                    <div>
                        <p class="mb-0">{{ $employee->employeeProfile->doj }}</p>
                    </div>
                </div>

				<h3 class="mb-2 mt-4">Manager</h3>
				<div class="d-flex gap-2 employee-info align-items-center mb-2">
                    <div>
                        <x-bx-user class="w-20 h-20" />
                    </div>
                    <div>
                        <p class="mb-0">{{ ucwords($employee->employeeProfile->manager) }}</p>
                    </div>
                </div>
				<div class="d-flex gap-2 employee-info align-items-center mb-2">
                    <div>
                        <x-bx-user-pin class="w-20 h-20" />
                    </div>
                    <div>
                        <p class="mb-0">{{ ucwords($employee->employeeProfile->manager_position) }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white w-100 border-radius-15 p-4">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="company" role="tabpanel" aria-labelledby="company-tab">
                    <div class="max-w-md max-auto">
                        <div class="sub-text-heading pb-4">
                            <h3 class="mb-1">Edit Employee Information</h3>
                        </div>
                        <form class="form-horizontal" method="POST" action="{{ route('employee.update', $employee->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <div class="tb-container d-flex gap-4 align-items-center">
                                        <label for="tb-file-upload">
                                            Upload Image
                                            <x-bx-upload class="w-20 g-20 ms-1"></x-bx-upload>
                                        </label>
                                        <input type="file" name="file" id="tb-file-upload" accept="image/*" onchange="fileUpload(event);" />
                                        <input type="hidden" name="base64_image" id="base64_image">
                                        <input type="hidden" name="face_data" value="{{ $employee->employeeProfile->face_data ?? '' }}">
                                        
                                        <button type="button" class="btn btn-primary ms-3" data-bs-toggle="modal" data-bs-target="#faceCaptureModal">
                                            <i class="fas fa-camera me-2"></i>Update Face Data
                                        </button>
                                    </div>
                                    @if($employee->employeeProfile->file)
                                    <div class="mt-2">
                                        <img src="{{ asset('files/'.$employee->employeeProfile->file) }}" alt="Current Photo" class="img-thumbnail" style="max-width: 200px;">
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <div class="form-group">
                                        <label class="db-label" for="name">First Name</label>
                                        <input id="first_name" type="text" class="form-control db-custom-input {{ $errors->has('first_name') ? ' is-invalid' : '' }}" name="first_name" value="{{ $employee->employeeProfile->first_name }}" {{$disabled}}>
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
                                        <input id="last_name" type="text" class="form-control db-custom-input {{ $errors->has('last_name') ? ' is-invalid' : '' }}" name="last_name" value="{{ $employee->employeeProfile->last_name }}" {{$disabled}}>
                                        @if ($errors->has('last_name'))
                                            <span class="text-danger">
                                                {{ $errors->first('last_name') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="form-check form-check-inline custom-radio-btn">
                                        <input class="form-check-input" type="radio" name="gender" id="inlineRadio1" value="Male" @if($employee->employeeProfile->gender == "Male") checked @endif>
                                        <label class="form-check-label" for="inlineRadio1">Male</label>
                                    </div>
                                    <div class="form-check form-check-inline custom-radio-btn">
                                        <input class="form-check-input" type="radio" name="gender" id="inlineRadio2" value="Female" @if($employee->employeeProfile->gender == "Female") checked @endif>
                                        <label class="form-check-label" for="inlineRadio2">Female</label>
                                    </div>
                                    <div class="form-check form-check-inline custom-radio-btn">
                                        <input class="form-check-input" type="radio" name="gender" id="inlineRadio3" value="Other" @if($employee->employeeProfile->gender == "Other") checked @endif>
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
                                        <input id="dob" type="date" class="form-control db-custom-input {{ $errors->has('dob') ? ' is-invalid' : '' }}" name="dob" value="{{ $employee->employeeProfile->dob }}" {{$disabled}}>
                                        @if ($errors->has('dob'))
                                            <span class="text-danger">
                                                {{ $errors->first('dob') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-3 mb-3">
                                    <div class="form-group">
                                        <!-- <div class="col-md-6"> -->
                                            <label for="is_visible_calendar" class="db-label">Is Visible On Calendar?</label>
                                            <select class="form-control db-custom-input" id="is_visible_calendar" name="is_visible_calendar">
                                                <option @if($employee->employeeProfile->is_visible_calendar == "0") selected @endif value="0">No</option>
                                                <option @if($employee->employeeProfile->is_visible_calendar == "1") selected @endif value="1">Yes</option>
                                            </select>

                                            @if ($errors->has('is_visible_calendar'))
                                                <span class="text-danger">
                                                    {{ $errors->first('is_visible_calendar') }}
                                                </span>
                                            @endif
                                        <!-- </div> -->
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="form-group">
                                        <label class="db-label"  for="name">Marital Status</label>
                                        <select class="form-control db-custom-input select-drop-down-arrow" id="marital_status" name="marital_status" @if($disabledDrop) style="pointer-events: none;" @endif>
                                            <option selected value disabled>Please Select</option>
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
                            </div>

                            <div class="row">
                                <div class="col-4 mb-3">
                                    <div class="form-group">
                                        <label class="db-label" for="name">Nationality</label>
                                        <input id="nationality" type="text" class="form-control db-custom-input {{ $errors->has('nationality') ? ' is-invalid' : '' }}" name="nationality" value="{{ $employee->employeeProfile->nationality }}" {{$disabled}}>
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
                                        <input id="country" type="text" class="form-control db-custom-input {{ $errors->has('country') ? ' is-invalid' : '' }}" name="country" value="{{ $employee->employeeProfile->country }}" {{$disabled}}>
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
                                        <input id="city" type="text" class="form-control db-custom-input {{ $errors->has('city') ? ' is-invalid' : '' }}" name="city" value="{{ $employee->employeeProfile->city }}" {{$disabled}}>
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
                                        <textarea  style="height: auto;" name="address" id="address" class="form-control db-custom-input {{ $errors->has('address') ? ' is-invalid' : '' }}" rows="4" {{$disabled}}>{{ $employee->employeeProfile->address }}</textarea>
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
                                        <input id="phone_number" type="text" class="form-control db-custom-input {{ $errors->has('phone_number') ? ' is-invalid' : '' }}" name="phone_number" value="{{ $employee->employeeProfile->phone_number }}" {{$disabled}}>
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
                                        <input id="email" type="text" class="form-control db-custom-input  {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $employee->email }}" {{$disabled}}>

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
                                        <input id="kiosk_code" type="text" maxlength="4" pattern="\d{4}" class="form-control db-custom-input {{ $errors->has('kiosk_code') ? ' is-invalid' : '' }}" name="kiosk_code" value="{{ $employee->kiosk_code }}" {{$disabled}}>
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
                                        <select class="form-control db-custom-input select-drop-down-arrow {{ $errors->has('identity_document') ? ' is-invalid' : '' }}" id="identity_document" name="identity_document" @if($disabledDrop) style="pointer-events: none;" @endif>
                                            <option selected value disabled>Please Select</option>
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
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="form-group">
                                        <label class="db-label" for="name">Identity Number </label>
                                        <input id="identity_number" type="text" class="form-control db-custom-input {{ $errors->has('identity_number') ? ' is-invalid' : '' }}" name="identity_number" value="{{ $employee->employeeProfile->identity_number }}" {{$disabled}}>

                                        @if ($errors->has('identity_number'))
                                        <span class="text-danger">
                                            {{ $errors->first('identity_number') }}
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-6 mb-3">
                                        <label for="name" class="db-label">Employee Status</label>
                                        <select class="form-control db-custom-input select-drop-down-arrow" id="status" name="status">
                                            <option selected value disabled>Please Select</option>
                                            <option value="1" @if($employee->status == "1") selected @endif >Active</option>
                                            <option value="0" @if($employee->status == "0") selected @endif>Inactive</option>
                                        </select>
                                        @if ($errors->has('status'))
                                            <span class="text-danger">
                                                {{ $errors->first('status') }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label for="name" class="db-label">Profile Lock?</label>
                                        <select class="form-control db-custom-input select-drop-down-arrow" id="is_proifle_edit_access" name="is_proifle_edit_access">
                                            <option value="0" @if($employee->is_proifle_edit_access == "0") selected @endif >No</option>
                                            <option value="1" @if($employee->is_proifle_edit_access == "1") selected @endif >Yes</option>
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
                                            <input id="em_name" type="text" class="form-control db-custom-input {{ $errors->has('em_name') ? ' is-invalid' : '' }}" name="em_name" value="{{ $employee->employeeProfile->em_name }}" {{$disabled}}>
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
                                            <input id="em_number" type="text" class="form-control db-custom-input {{ $errors->has('em_number') ? ' is-invalid' : '' }}" name="em_number" value="{{ $employee->employeeProfile->em_number }}" {{$disabled}}>
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
                                            <input id="fb_url" type="text" class="form-control db-custom-input {{ $errors->has('fb_url') ? ' is-invalid' : '' }}" name="fb_url" value="{{ $employee->employeeProfile->fb_url }}"  >
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
                                            <input id="linkden_url" type="text" class="form-control db-custom-input {{ $errors->has('linkden_url') ? ' is-invalid' : '' }}" name="linkden_url" value="{{ $employee->employeeProfile->linkden_url }}" {{$disabled}}>
                                            @if ($errors->has('linkden_url'))
                                                <span class="text-danger">
                                                    {{ $errors->first('linkden_url') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    

                                    <!-- <div class="col-6 mb-3">
                                        <div class="form-group">
                                            <label class="db-label" for="name">Hire Date</label>
                                            <input id="hire_date" type="date" class="form-control db-custom-input {{ $errors->has('hire_date') ? ' is-invalid' : '' }}" name="hire_date" value="{{ $employee->employeeProfile->hire_date }}" {{$disabled}}>
                                            @if ($errors->has('hire_date'))
                                                <span class="text-danger">
                                                    {{ $errors->first('hire_date') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div> -->

                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn btn-primary submit-btn">Update</button>
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
							<div class="col-12  mb-3">
								<div class="row">
									<div class="col-5">
										<div class="form-group">
											<label class="db-label"  for="name">Hire Date</label>
											<input id="doj" type="date" class="form-control db-custom-input {{ $errors->has('doj') ? ' is-invalid' : '' }}" name="doj" value="{{ $employee->employeeProfile->doj }}" {{$disabled}}>
											@if ($errors->has('doj'))
												<span class="text-danger">
													{{ $errors->first('doj') }}
												</span>
											@endif
										</div>
									</div>
								</div>
							</div>
							<div class="col-6 mb-3">
								<div class="form-group">
									<label class="db-label"  for="name">Position</label>
									<input id="designation" type="text" class="form-control db-custom-input {{ $errors->has('designation') ? ' is-invalid' : '' }}" name="designation" value="{{ $employee->employeeProfile->designation }}" {{$disabled}}>
									@if ($errors->has('designation'))
										<span class="text-danger">
											{{ $errors->first('designation') }}
										</span>
									@endif
								</div>
							</div>
							<div class="col-6 mb-3">
								<div class="form-group">
									<label class="db-label"  for="name">Department</label>
									<input id="department" type="text" class="form-control db-custom-input {{ $errors->has('department') ? ' is-invalid' : '' }}" name="department" value="{{ $employee->employeeProfile->department }}" {{$disabled}}>
									@if ($errors->has('department'))
										<span class="text-danger">
											{{ $errors->first('department') }}
										</span>
									@endif
								</div>
							</div>
							<div class="col-6 mb-3">
								<div class="form-group">
									<label class="db-label"  for="name">Social Security Number</label>
									<input id="pan_number" type="text" class="form-control db-custom-input {{ $errors->has('pan_number') ? ' is-invalid' : '' }}" name="pan_number" value="{{ $employee->employeeProfile->pan_number }}" {{$disabled}}>
									@if ($errors->has('pan_number'))
										<span class="text-danger">
											{{ $errors->first('pan_number') }}
										</span>
									@endif
								</div>
							</div>
							<div class="col-6 mb-3">
								<div class="form-group">
									<label class="db-label"  for="name">Medical Benefits Number</label>
									<input id="ifsc_code" type="text" class="form-control db-custom-input {{ $errors->has('ifsc_code') ? ' is-invalid' : '' }}" name="ifsc_code" value="{{ $employee->employeeProfile->ifsc_code }}" {{$disabled}}>
									@if ($errors->has('ifsc_code'))
										<span class="text-danger">
											{{ $errors->first('ifsc_code') }}
										</span>
									@endif
								</div>
							</div>
							<div class="col-6 mb-3">
								<div class="form-group">
									<label class="db-label"  for="name">Employee Type</label>
									<select class="form-control db-custom-input select-drop-down-arrow  {{ $errors->has('emp_type') ? ' is-invalid' : '' }}" id="emp_type" name="emp_type" @if($disabledDrop) style="pointer-events: none;" @endif>
										<option selected value disabled>Please Select</option>
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
							<div class="col-6 mb-3">
								<div class="form-group">
									<label class="db-label" for="name">Pay Type</label>
									<select class="form-control db-custom-input select-drop-down-arrow  {{ $errors->has('pay_type') ? ' is-invalid' : '' }}" id="pay_type" name="pay_type" @if($disabledDrop) style="pointer-events: none;" @endif>
										<option selected value disabled>Please Select</option>
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
							</div>
							<div class="col-6 mb-3">
								<div class="form-group">
									<label class="db-label" for="name">Amount</label>
									<input id="pay_rate" type="number" class="form-control db-custom-input {{ $errors->has('pay_rate') ? ' is-invalid' : '' }}" name="pay_rate" value="{{ $employee->employeeProfile->pay_rate }}" {{$disabled}}>
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
									<input id="manager" type="text" class="form-control db-custom-input {{ $errors->has('manager') ? ' is-invalid' : '' }}" name="manager" value="{{ $employee->employeeProfile->manager }}"  >
									@if ($errors->has('manager'))
										<span class="text-danger">
											{{ $errors->first('manager') }}
										</span>
									@endif
								</div>
							</div>
							<div class="col-6 mb-3">
								<div class="form-group">
									<label class="db-label" for="name">Manager Position</label>
									<input id="manager_position" type="text" class="form-control db-custom-input {{ $errors->has('manager_position') ? ' is-invalid' : '' }}" name="manager_position" value="{{ $employee->employeeProfile->manager_position }}" {{$disabled}}>
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
								<!-- <div class="row">
									<div class="col-4 ">
										<div class="form-group">
											<label class="db-label" for="name">Payment Method</label>
											<select class="form-control select-drop-down-arrow db-custom-input {{ $errors->has('payment_method') ? ' is-invalid' : '' }}" id="payment_method" name="payment_method" @if($disabledDrop) style="pointer-events: none;" @endif onchange="showDiv(this)">
												<option value="" selected disabled>Please Select</option>
												<option @if(!empty($employee->paymentProfile->payment_method) && $employee->paymentProfile->payment_method == "check") selected @endif value="check">Cheque</option>
												<option @if(!empty($employee->paymentProfile->payment_method) && $employee->paymentProfile->payment_method == "deposit") selected @endif value="deposit">Direct Deposit</option>
											</select>
											@if ($errors->has('payment_method'))
											<span class="text-danger">
												{{ $errors->first('payment_method') }}
											</span>
											@endif
										</div>
									</div>
								</div> -->
								<div class="form-group">
										<label class="db-label mb-2">Payment Method</label>
										<div class="d-flex gap-5">
											<div>
												<div class="position-relative db-radio-btn">
													<input class="form-check-input"  type="radio" name="payment_method" id="payment_check" value="check" onchange="showDiv(this)" @if(!empty($employee->paymentProfile->payment_method) && $employee->paymentProfile->payment_method == "check") checked @endif>
													<label class="form-check-label" for="payment_check">
														<img src="{{ asset('img/bank-check.png') }}" class="mb-2" alt="">
														<h3>Cheque</h3>
													</label>
												</div>
											</div>
											<div>
												<div class="position-relative db-radio-btn">
													<input class="form-check-input" type="radio" name="payment_method" id="payment_deposit" value="deposit" onchange="showDiv(this)" @if(!empty($employee->paymentProfile->payment_method) && $employee->paymentProfile->payment_method == "deposit") checked @endif>
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
								<div class="col-6 mb-3 @if(empty($employee->paymentProfile->routing_number)) d-none @endif" id="routing_number_div">
									<label for="routing_number" class="db-label">Routing Number</label>
									<div class="form-group mb-0">
										<input id="routing_number" type="routing_number" class="form-control db-custom-input {{ $errors->has('routing_number') ? ' is-invalid' : '' }}" name="routing_number" value="{{ !empty($employee->paymentProfile->routing_number) ? $employee->paymentProfile->routing_number : '' }}" {{$disabled}}>
										@if ($errors->has('routing_number'))
											<span class="text-danger">
												{{ $errors->first('routing_number') }}
											</span>
										@endif
									</div>
								</div>
								<div class="col-6 mb-3 @if(empty($employee->paymentProfile->routing_number)) d-none @endif" id="account_number_div">
									<label for="account_number" class="db-label">Account Number</label>
									<div class="form-group mb-0">
										<input id="account_number" type="account_number" class="form-control db-custom-input {{ $errors->has('account_number') ? ' is-invalid' : '' }}" value="{{ !empty($employee->paymentProfile->account_number) ? $employee->paymentProfile->account_number:'' }}" name="account_number" {{$disabled}}>
										@if ($errors->has('account_number'))
											<span class="text-danger">
												{{ $errors->first('account_number') }}
											</span>
										@endif
									</div>
								</div>
						</div>
						<div class="row">
							<div class="col-12 mb-3 @if(empty($employee->paymentProfile->routing_number)) d-none @endif" id="account_type_div">
								<div class="row">
									<div class="col-md-6 @if(empty($employee->paymentProfile->routing_number)) d-none @endif" id="bank_div">
										<label for="bank_name" class="db-label">Bank Name</label>
										<div class="form-group mb-0">
											<input id="bank_name" type="bank_name" class="form-control db-custom-input {{ $errors->has('bank_name') ? ' is-invalid' : '' }}" value="{{ !empty($employee->paymentProfile->bank_name) ? $employee->paymentProfile->bank_name:'' }}" name="bank_name" {{$disabled}}>

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
											<select class="form-control select-drop-down-arrow  db-custom-input {{ $errors->has('account_type') ? ' is-invalid' : '' }}" id="account_type" name="account_type" @if($disabledDrop) style="pointer-events: none;" @endif>
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
											<input id="password" type="password" class="form-control db-custom-input {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password">
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
											<input id="password-confirm" type="password" class="form-control db-custom-input" name="password_confirmation">

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

@push('modals')
    @include('client.employee.face-capture')
@endpush

@section('third_party_scripts')
<!-- FullCalendar JS -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://unpkg.com/tippy.js@6"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection


@push('page_scripts')
<script>
	$('[name="payment_method"]').trigger('click');
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