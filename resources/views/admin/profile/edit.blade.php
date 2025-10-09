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
        <div>
            <div class="d-flex flex-column justify-content-between h-100">
                <div class="profile-name-container pt-5">
                    <h3>{{ ucwords(auth()->user()->name) }}</h3>
                    <!-- <p class="mt-4">Admin</p> -->
                </div>
                <div>
                    <ul class="nav nav-tabs nav-pills db-custom-tabs gap-5 employee-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="company-tab" data-bs-toggle="tab" data-bs-target="#company"
                                type="button" role="tab" aria-controls="company" aria-selected="true">Profile</button>
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
        
        <div class="bg-white w-100 border-radius-15 p-4">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="company" role="tabpanel" aria-labelledby="company-tab">
                    <div class="max-w-md max-auto">
                        <div class="sub-text-heading pb-4">
                            <h3 class="mb-1">Profile Information</h3>
                            <!-- <p>Type your information</p> -->
                        </div>
						<form class="form-horizontal" method="POST" action="{{ route('edit-my-profile.update', auth()->user()->id) }}" enctype="multipart/form-data">
							@csrf
							{{ method_field('PUT') }}
								<input type="hidden" name="update_request" value="personal">

								<div class="row">
									<div class="col-8 mb-3">
										<div class="form-group">
											<label for="title" class="db-label">Name</label>
											<input id="name" type="text" class="form-control db-custom-input {{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ auth()->user()->name }}">
											@if ($errors->has('name'))
												<span class="text-danger">
													{{ $errors->first('name') }}
												</span>
											@endif
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-8 mb-3">
										<div class="form-group">
											<label for="description" class="db-label">Medical Benefits (DOB > 60 && DOB <=79 )</label>
											<!-- <div class="col-md-6"> -->
											<input id="medical_gre_60" type="text" class="form-control db-custom-input {{ $errors->has('medical_gre_60') ? ' is-invalid' : '' }}" name="medical_gre_60" value="{{ auth()->user()->name }}">

												@if ($errors->has('medical_gre_60'))
													<span class="text-danger">
														{{ $errors->first('medical_gre_60') }}
													</span>
												@endif
											<!-- </div> -->
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-8 mb-3">
										<div class="form-group">
											<label for="social_security" class="db-label">Email</label>
											<input id="email" type="text" class="form-control  db-custom-input {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"  value="{{ auth()->user()->email }}">

												@if ($errors->has('email'))
													<span class="text-danger">
														{{ $errors->first('email') }}
													</span>
												@endif
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-8 mb-3">
										<div class="form-group">
											<label for="social_security" class="db-label">Phone Number</label>
											<input id="phone_number" type="text" class="form-control db-custom-input {{ $errors->has('phone_number') ? ' is-invalid' : '' }}" name="phone_number" value="{{ auth()->user()->phone_number }}">

												@if ($errors->has('phone_number'))
													<span class="text-danger">
														{{ $errors->first('phone_number') }}
													</span>
												@endif
										</div>
									</div>
								</div>
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
							action="{{ route('edit-my-profile.update', auth()->user()->id) }}">
							@csrf
							{{ method_field('PUT') }}
							<input type="hidden" name="update_request" value="adminsadd">
							
							<!-- Existing Administrators as Pre-filled Form Fields -->
							@if($existingAdmins && $existingAdmins->count() > 0)
							<div class="mb-4">
								<h5 class="mb-3 text-primary">
									<i class="fas fa-users"></i> Existing Administrators
								</h5>
								<p class="text-muted mb-3">Edit the information for existing administrators below. Changes will be saved when you submit the form.</p>
								@foreach($existingAdmins as $index => $admin)
								<div class="row mb-3 border-bottom pb-3 bg-light p-3 rounded">
									<div class="col-5">
										<div class="form-group">
											<label class="db-label">Admin Name</label>
											<input type="text" class="form-control db-custom-input {{ $errors->has('existing_name.' . $index) ? ' is-invalid' : '' }}" 
												name="existing_name[]" value="{{ $admin->name }}">
											@if ($errors->has('existing_name.' . $index))
											<span class="text-danger">
											{{ $errors->first('existing_name.' . $index) }}
											</span>
											@endif
											<input type="hidden" name="existing_admin_id[]" value="{{ $admin->id }}">
										</div>
									</div>
									<div class="col-5">
										<div class="form-group">
											<label class="db-label">Admin Email</label>
											<input type="text" class="form-control db-custom-input {{ $errors->has('existing_email.' . $index) ? ' is-invalid' : '' }}" 
												name="existing_email[]" value="{{ $admin->email }}">
											@if ($errors->has('existing_email.' . $index))
											<span class="text-danger">
											{{ $errors->first('existing_email.' . $index) }}
											</span>
											@endif
										</div>
									</div>
									<div class="col-2 mt-4">
										<div class="btn-group" role="group">
											<button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
												<i class="fas fa-cog"></i> Actions
											</button>
											<ul class="dropdown-menu">
												<li><a class="dropdown-item change-password-btn" href="#" data-admin-id="{{ $admin->id }}" data-admin-name="{{ $admin->name }}">
													<i class="fas fa-key"></i> Change Password
												</a></li>
												<li><a class="dropdown-item permission-btn" href="#" data-admin-id="{{ $admin->id }}" data-admin-name="{{ $admin->name }}">
													<i class="fas fa-shield-alt"></i> Permission
												</a></li>
												<li><hr class="dropdown-divider"></li>
												<li><a class="dropdown-item text-danger delete-admin" href="#" data-admin-id="{{ $admin->id }}" data-admin-name="{{ $admin->name }}">
													<i class="fas fa-trash"></i> Delete
												</a></li>
											</ul>
										</div>
									</div>
								</div>
								@endforeach
							</div>
							@endif
							
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
							action="{{ route('edit-my-profile.update', auth()->user()->id) }}">
							@csrf
							{{ method_field('PUT') }}
							<input type="hidden" name="update_request" value="changepwdown">
							<div class="row">
								<div class="col-8 mb-3">
									<div class="form-group">
										<label for="social_security" class="db-label">Old Password</label>
										<input id="old_password" type="old_password" class="form-control db-custom-input {{ $errors->has('old_password') ? ' is-invalid' : '' }}" name="old_password">

											@if ($errors->has('old_password'))
												<span class="text-danger">
													{{ $errors->first('old_password') }}
												</span>
											@endif
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-8 mb-3">
									<div class="form-group">
										<label for="social_security" class="db-label">Password</label>
										<input id="password" type="password" class="form-control db-custom-input  {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password">

											@if ($errors->has('password'))
												<span class="text-danger">
													{{ $errors->first('password') }}
												</span>
											@endif
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-8 mb-3">
									<div class="form-group">
										<label for="social_security" class="db-label">Confirm Password</label>
										<input id="password-confirm" type="password" class="form-control db-custom-input " name="password_confirmation">

											@if ($errors->has('password'))
												<span class="text-danger">
													{{ $errors->first('password') }}
												</span>
											@endif
									</div>
								</div>
							</div>	

							<div class="col-md-12 text-end">
								<button type="submit" class="btn btn-primary submit-btn">Submit</button>
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
			// if (rowNum < maxRows) {
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
			// } else {
				// alert('You cannot add more than ' + maxRows + ' admins.');
			// }
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

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form id="changePasswordForm">
				<div class="modal-body">
					<input type="hidden" id="password_admin_id" name="admin_id">
					<div class="mb-3">
						<label for="admin_name_display" class="form-label">Administrator</label>
						<input type="text" class="form-control" id="admin_name_display" readonly>
					</div>
					<div class="mb-3">
						<label for="new_password" class="form-label">New Password</label>
						<input type="password" class="form-control" id="new_password" name="new_password" required>
					</div>
					<div class="mb-3">
						<label for="confirm_password" class="form-label">Confirm Password</label>
						<input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-primary">Change Password</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Permission Modal -->
<div class="modal fade" id="permissionModal" tabindex="-1" aria-labelledby="permissionModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="permissionModalLabel">Manage Permissions</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form id="permissionForm">
				<div class="modal-body">
					<input type="hidden" id="permission_admin_id" name="admin_id">
					<div class="mb-3">
						<label for="permission_admin_name" class="form-label">Administrator</label>
						<input type="text" class="form-control" id="permission_admin_name" readonly>
					</div>
					<div class="row">
						<div class="col-md-6">
							<h6>COMING SOON</h6>
							<!-- <div class="form-check">
								<input class="form-check-input" type="checkbox" id="dashboard_view" name="permissions[]" value="dashboard_view">
								<label class="form-check-label" for="dashboard_view">View Dashboard</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="dashboard_edit" name="permissions[]" value="dashboard_edit">
								<label class="form-check-label" for="dashboard_edit">Edit Dashboard</label>
							</div> -->
						</div>
						<!-- <div class="col-md-6">
							<h6>Employee Management</h6>
							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="employee_view" name="permissions[]" value="employee_view">
								<label class="form-check-label" for="employee_view">View Employees</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="employee_create" name="permissions[]" value="employee_create">
								<label class="form-check-label" for="employee_create">Create Employees</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="employee_edit" name="permissions[]" value="employee_edit">
								<label class="form-check-label" for="employee_edit">Edit Employees</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="employee_delete" name="permissions[]" value="employee_delete">
								<label class="form-check-label" for="employee_delete">Delete Employees</label>
							</div>
						</div> -->
					</div>
					<!-- <div class="row mt-3">
						<div class="col-md-6">
							<h6>Payroll Management</h6>
							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="payroll_view" name="permissions[]" value="payroll_view">
								<label class="form-check-label" for="payroll_view">View Payroll</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="payroll_create" name="permissions[]" value="payroll_create">
								<label class="form-check-label" for="payroll_create">Create Payroll</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="payroll_edit" name="permissions[]" value="payroll_edit">
								<label class="form-check-label" for="payroll_edit">Edit Payroll</label>
							</div>
						</div>
						<div class="col-md-6">
							<h6>Reports</h6>
							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="reports_view" name="permissions[]" value="reports_view">
								<label class="form-check-label" for="reports_view">View Reports</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="reports_export" name="permissions[]" value="reports_export">
								<label class="form-check-label" for="reports_export">Export Reports</label>
							</div>
						</div>
					</div> -->
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-primary">Save Permissions</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
// Change Password functionality
document.addEventListener('DOMContentLoaded', function() {
	// Handle change password button clicks
	document.querySelectorAll('.change-password-btn').forEach(function(button) {
		button.addEventListener('click', function(e) {
			e.preventDefault();
			const adminId = this.getAttribute('data-admin-id');
			const adminName = this.getAttribute('data-admin-name');
			
			document.getElementById('password_admin_id').value = adminId;
			document.getElementById('admin_name_display').value = adminName;
			
			const modal = new bootstrap.Modal(document.getElementById('changePasswordModal'));
			modal.show();
		});
	});
	
	// Handle permission button clicks
	document.querySelectorAll('.permission-btn').forEach(function(button) {
		button.addEventListener('click', function(e) {
			e.preventDefault();
			const adminId = this.getAttribute('data-admin-id');
			const adminName = this.getAttribute('data-admin-name');
			
			document.getElementById('permission_admin_id').value = adminId;
			document.getElementById('permission_admin_name').value = adminName;
			
			const modal = new bootstrap.Modal(document.getElementById('permissionModal'));
			modal.show();
		});
	});
	
	// Handle change password form submission
	document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
		e.preventDefault();
		
		const newPassword = document.getElementById('new_password').value;
		const confirmPassword = document.getElementById('confirm_password').value;
		
		if (newPassword !== confirmPassword) {
			alert('Passwords do not match!');
			return;
		}
		
		// if (newPassword.length < 8) {
		// 	alert('Password must be at least 8 characters long!');
		// 	return;
		// }
		
		const formData = new FormData(this);
		
		fetch('{{ route("set-user-pwd") }}', {
			method: 'POST',
			body: formData,
			headers: {
				'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
			}
		})
		.then(response => response.json())
		.then(data => {
			if (data.success) {
				alert('Password changed successfully!');
				const modal = bootstrap.Modal.getInstance(document.getElementById('changePasswordModal'));
				modal.hide();
				document.getElementById('changePasswordForm').reset();
			} else {
				alert('Error: ' + (data.message || 'Failed to change password'));
			}
		})
		.catch(error => {
			console.error('Error:', error);
			alert('An error occurred while changing the password');
		});
	});
	
	// Handle permission form submission (disabled for now)
	document.getElementById('permissionForm').addEventListener('submit', function(e) {
		e.preventDefault();
		alert('Permission management will be implemented later.');
	});
	
	// Handle delete admin button click
	$(document).on('click', '.delete-admin', function() {
		var adminId = $(this).data('admin-id');
		var adminName = $(this).data('admin-name');
		
		if (confirm('Are you sure you want to delete administrator "' + adminName + '"?')) {
			$.ajax({
				url: '{{ route("edit-my-profile.delete-admin", ":admin_id") }}'.replace(':admin_id', adminId),
				type: 'DELETE',
				data: {
					_token: '{{ csrf_token() }}'
				},
				success: function(response) {
					if (response.success) {
						alert('Administrator deleted successfully!');
						location.reload(); // Reload the page to show updated list
					} else {
						alert('Error: ' + (response.message || 'Failed to delete administrator'));
					}
				},
				error: function(xhr, status, error) {
					console.error('Error:', error);
					alert('An error occurred while deleting the administrator');
				}
			});
		}
	});
});
</script>
@endpush