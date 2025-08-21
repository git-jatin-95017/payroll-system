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
			<h3>Manage Notices</h3>
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
							<h3 class="mb-1">Notices Information</h3>
							<p>Type your notices information here</p>
						</div>
						<form class="form-horizontal" method="POST" action="{{ route('notice.update', $notice->id) }}">
							@csrf
							{{ method_field('PUT') }}
							<div class="row">
								<div class="col-8 mb-3">
									<div class="form-group">
										<label for="message" class="db-label">Message</label>
										<!-- <div class="col-md-6"> -->
											<textarea name="message" id="message" class="form-control db-custom-input {{ $errors->has('message') ? ' is-invalid' : '' }}" rows="4">{{ $notice->message }}</textarea>

											@if ($errors->has('message'))
												<span class="text-danger">
													{{ $errors->first('message') }}
												</span>
											@endif
										<!-- </div> -->
									</div>
								</div>
							</div>

							<div class="card-footer">
								<button type="submit" class="btn btn-primary">Save</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection