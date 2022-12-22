@extends('layouts.app')

@section('content')
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">
            <i class="fa fa-braille" style="color:#1976d2"></i>
            Manage Locations
        </h3>
    </div>

    <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="javascript:void(0)">Home</a>
            </li>
            <li class="breadcrumb-item active">Locations</li>
            <li class="breadcrumb-item active">Add New</li>
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
				<div class="col-sm-6">
					<div class="card">
						<div class="card-header">
							<h3 class="card-title">Location</h3>
						</div>
						<form class="form-horizontal" method="POST" action="{{ route('department.update', $department->id) }}">
							@csrf
							{{ method_field('PUT') }}
							<div class="card-body">								
								<div class="form-group">
									<label for="dep_name" class="col-md-8 control-label">Location Name</label>
									<div class="col-md-12">
										<input id="dep_name" type="text" class="form-control {{ $errors->has('dep_name') ? ' is-invalid' : '' }}" name="dep_name" value="{{ $department->dep_name }}">

										@if ($errors->has('dep_name'))
											<span class="text-danger">
												{{ $errors->first('dep_name') }}
											</span>
										@endif
									</div>
								</div>								
							</div>
							<div class="card-footer">
								<button type="submit" class="btn btn-primary">Save</button>
								<a href="{{ route('department.index' )}}" class="btn btn-info">Back</a>
							</div>
						</form>
					</div>
				</div>				
			</div>
		</div>
	</section>
@endsection