@extends('layouts.new_layout')

@push('page_css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css" />
@endpush
@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1>Profile Details</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="#">Home</a></li>
					<li class="breadcrumb-item"><a href="#">Employees</a></li>
					<li class="breadcrumb-item active">Employee Profile Details</li>
				</ol>
			</div>
		</div>
	</div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-3">
				<!-- Profile Image -->
				<div class="card card-primary card-outline">
					<div class="card-body box-profile">
						<div class="text-center">
							<img style="object-fit: contain;width: 100px; height: 100px;"  class="profile-user-img img-fluid img-circle"
								src="{{ \File::exists(public_path('files/'.$employee->employeeProfile->file)) ? asset('files/'.$employee->employeeProfile->file) : asset('/img/user2-160x160.jpg') }}"
								alt="User profile picture">
						</div>

						<h3 class="profile-username text-center">
							{{ $employee->employeeProfile->first_name . ' '. $employee->employeeProfile->last_name }}</h3>

						<p class="text-muted text-center">{{ $employee->email }}</p>
						<ul class="list-group list-group-unbordered mb-3">
							<li class="list-group-item">
								<b>Emp Code</b> <a
									class="float-right">{{ $employee->user_code }}</a>
							</li>
							<li class="list-group-item">
								<b>Employee Name</b> <a
									class="float-right">{{ $employee->employeeProfile->first_name . ' '. $employee->employeeProfile->last_name }}</a>
							</li>					
							<li class="list-group-item">
								<b>Designation</b> <a
									class="float-right">{{ $employee->employeeProfile->designation ?? 'N/A' }}</a>
							</li>
							<li class="list-group-item">
								<b>Department</b> <a
									class="float-right">{{ $employee->employeeProfile->department ?? 'N/A' }}</a>
							</li>
							<li class="list-group-item">
								<b>Date Of Birth</b> <a
									class="float-right">{{ date('m/d/Y', strtotime($employee->employeeProfile->dob)) ?? 'N/A' }}</a>
							</li>
							<li class="list-group-item">
								<b>Date Of Joining</b> <a
									class="float-right">{{ date('m/d/Y', strtotime($employee->employeeProfile->doj)) ?? 'N/A' }}</a>
							</li>
							<li class="list-group-item">
								<b>Gender</b> <a
									class="float-right">{{ $employee->employeeProfile->gender ?? 'N/A' }}</a>
							</li>
							<li class="list-group-item">
								<b>Contact Details</b> <br>
								<span class="text-muted">Mobile <a class="float-right">{{ $employee->employeeProfile->mobile ?? 'N/A' }}</a></span><br>
								<span class="text-muted">Phone Number <a class="float-right">{{ $employee->employeeProfile->phone_number ?? 'N/A' }}</a></span>
							</li>
						</ul>
						
					</div>
					<!-- /.card-body -->
				</div>
				<!-- /.card -->
			</div>
			<div class="col-md-9">
				<!-- About Me Box -->
				<div class="card card-primary">
					<div class="card-header">
						<h3 class="card-title">Employee Details</h3>
						<div class="card-tools">
							<button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
								<i class="fas fa-minus"></i>
							</button>
							<button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
								<i class="fas fa-times"></i>
							</button>
						</div>
					</div>
					<!-- /.card-header -->
					<div class="card-body">
						<ul class="list-group list-group-unbordered mb-3">							
							<li class="list-group-item">
								<b>Marital Status</b> <a
									class="float-right">{{ $employee->employeeProfile->marital_status ?? 'N/A' }}</a>
							</li>
							<li class="list-group-item">
								<b>Nationality</b> <a
									class="float-right">{{ $employee->employeeProfile->nationality ?? 'N/A' }}</a>
							</li>
							<li class="list-group-item">
								<b>Blood Group</b> <a
									class="float-right">{{ $employee->employeeProfile->blood_group ?? 'N/A' }}</a>
							</li>
							<li class="list-group-item">
								<b>City</b> <a
									class="float-right">{{ $employee->employeeProfile->city ?? 'N/A' }}</a>
							</li>						
							<li class="list-group-item">
								<b><i class="fas fa-map-marker-alt mr-1"></i> Address</b> <a
									class="float-right">{{ $employee->employeeProfile->address ?? 'N/A' }}</a>
							</li>
							<li class="list-group-item">
								<b>State</b> <a
									class="float-right">{{ $employee->employeeProfile->state ?? 'N/A' }}</a>
							</li>
							<li class="list-group-item">
								<b>Country</b> <a
									class="float-right">{{ $employee->employeeProfile->country ?? 'N/A' }}</a>
							</li>
							<li class="list-group-item">
								<b>Identity Document</b> <a
									class="float-right">{{ $employee->employeeProfile->identity_document ?? 'N/A' }}</a>
							</li>
							<li class="list-group-item">
								<b>Identity Number</b> <a
									class="float-right">{{ $employee->employeeProfile->identity_number ?? 'N/A' }}</a>
							</li>
							<li class="list-group-item">
								<b>Employee Type</b> <a
									class="float-right">{{ $employee->employeeProfile->emp_type ?? 'N/A' }}</a>
							</li>
							<li class="list-group-item">
								<b>PAN Number</b> <a
									class="float-right">{{ $employee->employeeProfile->pan_number ?? 'N/A' }}</a>
							</li>							
						</ul>
						<strong></strong>

						<p class="text-muted"></p>

						<hr>						
					</div>
					<!-- /.card-body -->
				</div>
				<!-- /.card -->
				<div class="card card-primary">
					<div class="card-header">
						<h3 class="card-title">Bank Details</h3>
						<div class="card-tools">
							<button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
								<i class="fas fa-minus"></i>
							</button>
							<button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
								<i class="fas fa-times"></i>
							</button>
						</div>
					</div>
					<!-- /.card-header -->
					<div class="card-body">
						<li class="list-group-item">
							<b>Bank Name</b> <a
								class="float-right">{{ $employee->employeeProfile->bank_name ?? 'N/A' }}</a>
						</li>
						<li class="list-group-item">
							<b>Bank A/C No</b> <a
								class="float-right">{{ $employee->employeeProfile->bank_acc_number ?? 'N/A' }}</a>
						</li>	
						<li class="list-group-item">
							<b>IFSC Code</b> <a
								class="float-right">{{ $employee->employeeProfile->ifsc_code ?? 'N/A' }}</a>
						</li>
						<li class="list-group-item">
							<b>PF A/C No</b> <a
								class="float-right">{{ $employee->employeeProfile->pf_account_number ?? 'N/A' }}</a>
						</li>	
					</div>
				</div>
			</div>
			<!-- /.col -->
			
			<!-- /.col -->
		</div>
		<!-- /.row -->
	</div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection
@push('page_scripts')
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
@endpush