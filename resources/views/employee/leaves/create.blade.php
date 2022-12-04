@extends('layouts.employee')

@section('content')
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">Manage Leaves</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="#">Home</a></li>
						<li class="breadcrumb-item active">Leaves</li>
						<li class="breadcrumb-item active">Add New</li>
					</ol>
				</div>
			</div>
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
					<div class="col-md-6">
						<div class="alert alert-success alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							{{ session('message') }}
						</div>
					</div>
				</div>
			@elseif (session('error'))
				<div class="row">
					<div class="col-md-6">
						<div class="alert alert-danger alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							{{ session('error') }}
						</div>
					</div>
				</div>
			@endif
			<div class="row">            	
				<div class="col-sm-6">
					<div class="card">
						<div class="card-header">
							<h3 class="card-title">Add New</h3>
						</div>
						<form class="form-horizontal" method="POST" action="{{ route('my-leaves.store') }}">
							@csrf
							<div class="card-body">
								<div class="form-group">
									<label for="leave_subject" class="col-md-4 control-label">Subject</label>
									<div class="col-md-6">
										<input id="leave_subject" type="text" class="form-control {{ $errors->has('leave_subject') ? ' is-invalid' : '' }}" name="leave_subject" value="{{ old('leave_subject', '') }}">

										@if ($errors->has('leave_subject'))
											<span class="text-danger">
												{{ $errors->first('leave_subject') }}
											</span>
										@endif
									</div>
								</div>

								<div class="form-group">
									<label for="leave_dates" class="col-md-4 control-label">Date (DD/MM/YYY)</label>
									<div class="col-md-6">
										<input id="leave_dates" type="text" class="form-control multidatepicker {{ $errors->has('leave_dates') ? ' is-invalid' : '' }}" name="leave_dates" value="{{ old('leave_dates', '') }}">
										<small class="text-muted">You can select multiple dates separated by comma.</small>
										@if ($errors->has('leave_dates'))
											<span class="text-danger">
												{{ $errors->first('leave_dates') }}
											</span>
										@endif
									</div>
								</div>

								<div class="form-group">
									<label for="leave_message" class="col-md-4 control-label">Leave Message</label>
									<div class="col-md-6">
										<textarea name="leave_message" id="leave_message" class="form-control {{ $errors->has('leave_message') ? ' is-invalid' : '' }}" rows="4">{{ old('leave_message', '') }}</textarea>

										@if ($errors->has('leave_message'))
											<span class="text-danger">
												{{ $errors->first('leave_message') }}
											</span>
										@endif
									</div>
								</div>					

								<div class="form-group">
									<div class="col-md-6">
										<label for="name" >Leave Type</label>
										<select class="form-control" id="leave_type" name="leave_type">
				                            <option selected value disabled>Please make a choice</option>
				                            <option @if(old('leave_type') == "Casual Leave") selected @endif value="Casual Leave">Casual Leave</option>
				                            <option @if(old('leave_type') == "Earned Leave") selected @endif value="Earned Leave">Privileged / Earned Leave</option>
				                            <option @if(old('leave_type') == "Sick Leave") selected @endif value="Sick Leave">Medical / Sick Leave</option>
				                            <option @if(old('leave_type') == "Maternity Leave") selected @endif value="Maternity Leave">Maternity Leave</option>
				                            <option @if(old('leave_type') == "Leave Without Pay") selected @endif value="Leave Without Pay">Leave Without Pay</option>
				                        </select>

										@if ($errors->has('leave_type'))
											<span class="text-danger">
												{{ $errors->first('leave_type') }}
											</span>
										@endif
									</div>
								</div>
							</div>
							<div class="card-footer">
								<button type="submit" class="btn btn-primary">Apply for Leave</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection
@push('page_css')
	<link rel="stylesheet" href="{{ asset('js/datepicker/datepicker3.css') }}">
@endpush

@push('page_scripts')
	<script src="{{ asset('js/datepicker/bootstrap-datepicker.js') }}"></script>
	<script>
		 if ( $('.multidatepicker').length > 0 ) {
		        $('.multidatepicker').datepicker({
		            format: 'mm/dd/yyyy',
		            startDate : new Date(),
		            multidate: true,
		            autoclose: true
		        });
		    }
	</script>
@endpush