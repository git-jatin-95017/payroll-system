@php
   if(auth()->user()->role_id == 3) {
      $layoutDirectory = 'layouts.employee';
   } else {
      $layoutDirectory = 'layouts.new_layout';
   }
@endphp

@extends($layoutDirectory)

@section('content')
	<div class="row page-titles">
	    <div class="col-md-5 align-self-center">
	        <h3 class="text-themecolor">
	            <i class="fa fa-braille" style="color:#1976d2"></i>
	            Manage Notices
	        </h3>
	    </div>

	    <div class="col-md-7 align-self-center">
	        <ol class="breadcrumb">
	            <li class="breadcrumb-item">
	                <a href="javascript:void(0)">Home</a>
	            </li>
	            <li class="breadcrumb-item active">Notices</li>
	            <li class="breadcrumb-item active">Modify</li>
	        </ol>
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
					<div class="card">
						<div class="card-header">
							<h3 class="card-title">Modify Notice</h3>
						</div>
						<form class="form-horizontal" method="POST" action="{{ route('notice.update', $notice->id) }}">
							@csrf
							{{ method_field('PUT') }}
							<div class="card-body">
								<div class="form-group">
									<label for="message" class="col-md-4 control-label">Message</label>
									<div class="col-md-6">
										<textarea name="message" id="message" class="form-control {{ $errors->has('message') ? ' is-invalid' : '' }}" rows="4">{{ $notice->message }}</textarea>

										@if ($errors->has('message'))
											<span class="text-danger">
												{{ $errors->first('message') }}
											</span>
										@endif
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