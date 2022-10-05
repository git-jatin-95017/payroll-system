@extends('layouts.app')

@section('content')
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">Manage Employees</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="#">Home</a></li>
						<li class="breadcrumb-item"><a href="#">Employees</a></li>
						<li class="breadcrumb-item active">Add New Employee</li>
					</ol>
				</div>
			</div>
		</div>
	</section>
	<section class="content">
		<div class="container-fluid">
			<?php

			/*@if ($errors->any())
            <div class="alert alert-danger">
                <ul class="m-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            */
            ?>
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
							<h3 class="card-title">Add New Employee</h3>
						</div>
						<form class="form-horizontal" method="POST" action="{{ route('employee.store') }}" enctype="multipart/form-data">
							@csrf
							<div class="card-body">
								<div class="form-row mb-3">
									<div class="col-md-4">
										<label for="name">Employee ID number</label>
										<input id="emp_code" type="text" class="form-control {{ $errors->has('emp_code') ? ' is-invalid' : '' }}" name="emp_code" value="{{ old('emp_code', '') }}">

										@if ($errors->has('emp_code'))
											<span class="text-danger">
												{{ $errors->first('emp_code') }}
											</span>
										@endif
									</div>
									<div class="col-md-4">
										<label for="name">First Name</label>
										<input id="first_name" type="text" class="form-control {{ $errors->has('first_name') ? ' is-invalid' : '' }}" name="first_name" value="{{ old('first_name', '') }}">

										@if ($errors->has('first_name'))
											<span class="text-danger">
												{{ $errors->first('first_name') }}
											</span>
										@endif
									</div>
									<div class="col-md-4">
										<label for="name">Last Name</label>
										<input id="last_name" type="text" class="form-control {{ $errors->has('last_name') ? ' is-invalid' : '' }}" name="last_name" value="{{ old('last_name', '') }}">

										@if ($errors->has('last_name'))
											<span class="text-danger">
												{{ $errors->first('last_name') }}
											</span>
										@endif
									</div>							
								</div>
								<div class="form-row mb-3">
									<div class="col-md-4">
										<label for="name">Date of Birth</label>
										<input id="dob" type="date" class="form-control {{ $errors->has('dob') ? ' is-invalid' : '' }}" name="dob" value="{{ old('dob', '') }}">

										@if ($errors->has('dob'))
											<span class="text-danger">
												{{ $errors->first('dob') }}
											</span>
										@endif
									</div>
									<div class="col-md-4">
										<label for="name" >Gender</label>
										<select class="form-control" id="gender" name="gender">
				                            <option selected value disabled>Please make a choice</option>
				                            <option @if(old('gender') == "Male") selected @endif value="Male">Male</option>
				                            <option @if(old('gender') == "Female") selected @endif value="Female">Female</option>
				                            <option @if(old('gender') == "Other") selected @endif value="Other">Other</option>
				                        </select>

										@if ($errors->has('gender'))
											<span class="text-danger">
												{{ $errors->first('gender') }}
											</span>
										@endif
									</div>
									<div class="col-md-4">
										<label for="name">Marital Status</label>
										<select class="form-control" id="marital_status" name="marital_status">
				                            <option selected value disabled>Please make a choice</option>
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
								<div class="form-row mb-3">
									<div class="col-md-4">
										<label for="name">Nationality</label>
										<input id="nationality" type="text" class="form-control {{ $errors->has('nationality') ? ' is-invalid' : '' }}" name="nationality" value="{{ old('nationality', '') }}">

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
										<input id="city" type="text" class="form-control {{ $errors->has('city') ? ' is-invalid' : '' }}" name="city" value="{{ old('city', '') }}">

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
										<textarea name="address" id="address" class="form-control {{ $errors->has('address') ? ' is-invalid' : '' }}" rows="4">{{ old('address', '') }}</textarea>

										@if ($errors->has('address'))
											<span class="text-danger">
												{{ $errors->first('address') }}
											</span>
										@endif
									</div>
								</div>

								<div class="form-row mb-3">
									<!-- <div class="col-md-4">
										<label for="name" >State</label>
										<input id="state" type="text" class="form-control {{ $errors->has('state') ? ' is-invalid' : '' }}" name="state" value="{{ old('state', '') }}">

										@if ($errors->has('state'))
											<span class="text-danger">
												{{ $errors->first('state') }}
											</span>
										@endif
									</div>
 -->
									<div class="col-md-4">
										<label for="name" >Country</label>
										<input id="country" type="text" class="form-control {{ $errors->has('country') ? ' is-invalid' : '' }}" name="country" value="{{ old('country', '') }}">

										@if ($errors->has('country'))
											<span class="text-danger">
												{{ $errors->first('country') }}
											</span>
										@endif
									</div>

									<div class="col-md-4">
										<label for="email" >Email</label>
										<input id="email" type="text" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email', '') }}">

										@if ($errors->has('email'))
											<span class="text-danger">
												{{ $errors->first('email') }}
											</span>
										@endif
									</div>
								</div>
								<div class="form-row mb-3">
									<!-- <div class="col-md-4">
										<label for="name" >Mobile</label>
										<input id="mobile" type="text" class="form-control {{ $errors->has('mobile') ? ' is-invalid' : '' }}" name="mobile" value="{{ old('mobile', '') }}">

										@if ($errors->has('mobile'))
											<span class="text-danger">
												{{ $errors->first('mobile') }}
											</span>
										@endif
									</div> -->

									<div class="col-md-4">
										<label for="name" >Phone Number</label>
										<input id="phone_number" type="text" class="form-control {{ $errors->has('phone_number') ? ' is-invalid' : '' }}" name="phone_number" value="{{ old('phone_number', '') }}">

										@if ($errors->has('phone_number'))
											<span class="text-danger">
												{{ $errors->first('phone_number') }}
											</span>
										@endif
									</div>

									<div class="col-md-4">
										<label for="name" >Identity Document</label>
										<!-- <input id="identity_document" type="text" class="form-control {{ $errors->has('identity_document') ? ' is-invalid' : '' }}" name="identity_document" value="{{ old('identity_document', '') }}"> -->
										<select class="form-control {{ $errors->has('identity_document') ? ' is-invalid' : '' }}" id="identity_document" name="identity_document">
				                            <option selected value disabled>Please make a choice</option>
				                            <option @if(old('identity_document') == "Voter Id") selected @endif value="Voter Id">Voter Id</option>
				                            <option @if(old('identity_document') == "Aadhar Card") selected @endif value="Aadhar Card">Aadhar Card</option>
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
								<div class="form-row mb-3">
									<div class="col-md-4">
										<label for="name" >Identity Number</label>
										<input id="identity_number" type="text" class="form-control {{ $errors->has('identity_number') ? ' is-invalid' : '' }}" name="identity_number" value="{{ old('identity_number', '') }}">

										@if ($errors->has('identity_number'))
											<span class="text-danger">
												{{ $errors->first('identity_number') }}
											</span>
										@endif
									</div>

									<div class="col-md-4">
										<label for="name" >Employee Type</label>
										<!-- <input id="emp_type" type="text" class="form-control {{ $errors->has('emp_type') ? ' is-invalid' : '' }}" name="emp_type" value="{{ old('emp_type', '') }}"> -->
										<select class="form-control {{ $errors->has('emp_type') ? ' is-invalid' : '' }}" id="emp_type" name="emp_type">
				                            <option selected value disabled>Please make a choice</option>
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

									<div class="col-md-4">
										<label for="name" >Pay Type</label>
										<select class="form-control {{ $errors->has('pay_type') ? ' is-invalid' : '' }}" id="pay_type" name="pay_type">
				                            <option selected value disabled>Please make a choice</option>
				                            <option @if(old('pay_type') == "hourly") selected @endif value="hourly">Hourly</option>
				                            <option @if(old('pay_type') == "weekly") selected @endif value="weekly">Weekly</option>
				                            <option @if(old('pay_type') == "biweekly") selected @endif value="biweekly">Biweekly</option>
				                            <option @if(old('pay_type') == "semi-monthly") selected @endif value="semi-monthly">Semi Monthly</option>
				                           	<option @if(old('pay_type') == "monthly") selected @endif value="monthly">Monthly</option>
				                        </select>

										@if ($errors->has('pay_type'))
											<span class="text-danger">
												{{ $errors->first('pay_type') }}
											</span>
										@endif
									</div>
									<div class="col-md-4 mt-3">
										<label for="name" >Pay Rate</label>
										<input id="pay_rate" type="number" class="form-control {{ $errors->has('pay_rate') ? ' is-invalid' : '' }}" name="pay_rate" value="{{ old('pay_rate', '') }}">

										@if ($errors->has('pay_rate'))
											<span class="text-danger">
												{{ $errors->first('pay_rate') }}
											</span>
										@endif
									</div>
									<div class="col-md-4 mt-3">
										<label for="name" >Start Date</label>
										<input id="doj" type="date" class="form-control {{ $errors->has('doj') ? ' is-invalid' : '' }}" name="doj" value="{{ old('doj', '') }}">

										@if ($errors->has('doj'))
											<span class="text-danger">
												{{ $errors->first('doj') }}
											</span>
										@endif
									</div>
								</div>
								<div class="form-row mb-3">
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

									<div class="col-md-4">
										<label for="name" >Social Security Number</label>
										<input id="pan_number" type="text" class="form-control {{ $errors->has('pan_number') ? ' is-invalid' : '' }}" name="pan_number" value="{{ old('pan_number', '') }}">

										@if ($errors->has('pan_number'))
											<span class="text-danger">
												{{ $errors->first('pan_number') }}
											</span>
										@endif
									</div>
								</div>
								<div class="form-row mb-3">
									<div class="col-md-4">
										<label for="name" >Bank Name</label>
										<input id="bank_name" type="text" class="form-control {{ $errors->has('bank_name') ? ' is-invalid' : '' }}" name="bank_name" value="{{ old('bank_name', '') }}">

										@if ($errors->has('bank_name'))
											<span class="text-danger">
												{{ $errors->first('bank_name') }}
											</span>
										@endif
									</div>

									<div class="col-md-4">
										<label for="name" >Bank Account Number</label>
										<input id="bank_acc_number" type="text" class="form-control {{ $errors->has('bank_acc_number') ? ' is-invalid' : '' }}" name="bank_acc_number" value="{{ old('bank_acc_number', '') }}">

										@if ($errors->has('bank_acc_number'))
											<span class="text-danger">
												{{ $errors->first('bank_acc_number') }}
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
								</div>
								<div class="form-row mb-3">
									<div class="col-md-4">
										<label for="name" >PF A/C No</label>
										<input id="pf_account_number" type="text" class="form-control {{ $errors->has('pf_account_number') ? ' is-invalid' : '' }}" name="pf_account_number" value="{{ old('pf_account_number', '') }}">

										@if ($errors->has('pf_account_number'))
											<span class="text-danger">
												{{ $errors->first('pf_account_number') }}
											</span>
										@endif
									</div>

									<div class="col-md-4">
										<label for="name" >Upload Image</label>
										<input id="file" type="file" class="form-control {{ $errors->has('file') ? ' is-invalid' : '' }}" name="file" value="{{ old('file', '') }}">

										@if ($errors->has('file'))
											<span class="text-danger">
												{{ $errors->first('file') }}
											</span>
										@endif
									</div>
									<div class="col-md-4">
										<label for="name" >Employee Status</label>
										<select class="form-control" id="status" name="status">
				                            <option selected value disabled>Please make a choice</option>
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
								<hr>
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