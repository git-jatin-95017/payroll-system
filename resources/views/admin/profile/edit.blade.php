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
@endpush