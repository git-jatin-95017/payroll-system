@php
   if(auth()->user()->role_id == 3) {
      $layoutDirectory = 'layouts.new_layout';
   } else {
      $layoutDirectory = 'layouts.new_layout';
   }
@endphp

@extends($layoutDirectory)

@section('content')
<div>
	<div class="page-heading d-flex justify-content-between align-items-center gap-3 mb-3">
		<div>
			<h3>Manage Holidays</h3>
		</div>
	</div>
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
	</div>
	<div class="bg-white white-container py-4 px-4 pt-4continer-h-full">
		<div class="row">            	
				<div class="col-sm-12">
					<div class="max-w-md max-auto">
						<div class="sub-text-heading pb-4">
							<h3 class="mb-1">Holiday Information</h3>
							<p>Type your holiday information here</p>
						</div>
						<form class="form-horizontal" method="POST" action="{{ route('holidays.store') }}">
							@csrf
							<div class="row">
								<div class="col-8 mb-3">
									<div class="form-group">
										<label for="title" class="db-label">Holiday Title</label>
										<!-- <div class="col-md-6"> -->
											<input id="title" type="text" class="form-control db-custom-input db-custom-input {{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" value="{{ old('title', '') }}">

											@if ($errors->has('title'))
												<span class="text-danger">
													{{ $errors->first('title') }}
												</span>
											@endif
										<!-- </div> -->
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-8 mb-3">
									<div class="form-group">
										<label for="description" class="db-label">Holiday Description</label>
										<!-- <div class="col-md-6"> -->
											<textarea name="description" id="description" class="form-control db-custom-input {{ $errors->has('description') ? ' is-invalid' : '' }}" rows="4">{{ old('description', '') }}</textarea>

											@if ($errors->has('description'))
												<span class="text-danger">
													{{ $errors->first('description') }}
												</span>
											@endif
										<!-- </div> -->
									</div>
								</div>
							</div>
   							
							<div class="row">
								<div class="col-8 mb-3">
									<div class="form-group">
										<label for="holiday_date" class="db-label">Holiday Date (DD/MM/YYY)</label>
										<!-- <div class="col-md-6"> -->
											<input id="holiday_date" type="date" class="form-control db-custom-input {{ $errors->has('holiday_date') ? ' is-invalid' : '' }}" name="holiday_date" value="{{ old('holiday_date', '') }}">

											@if ($errors->has('holiday_date'))
												<span class="text-danger">
													{{ $errors->first('holiday_date') }}
												</span>
											@endif
										<!-- </div> -->
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-8 mb-3">
									<div class="form-group">
										<!-- <div class="col-md-6"> -->
											<label for="name" class="db-label">Holiday Type</label>
											<select class="form-control db-custom-input" id="type" name="type">
												<option selected value disabled>Please make a choice</option>
												<!-- <option @if(old('type') == "1") selected @endif value="1">Compulsory Holiday</option> -->
												<!-- <option @if(old('type') == "2") selected @endif value="2">Restricted Holiday</option> -->
												<option @if(old('type') == "1") selected @endif value="1">Public Holiday</option>
												<option @if(old('type') == "2") selected @endif value="2">National Day</option>
												<option @if(old('type') == "3") selected @endif value="3">Voluntary</option>
											</select>

											@if ($errors->has('type'))
												<span class="text-danger">
													{{ $errors->first('type') }}
												</span>
											@endif
										<!-- </div> -->
									</div>
								</div>
							</div>
							<div class="card-footer">
								<button type="submit" class="btn btn-primary">Save Holiday</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection