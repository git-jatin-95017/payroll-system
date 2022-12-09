@php
   if(auth()->user()->role_id == 3) {
      $layoutDirectory = 'layouts.employee';
   } else {
      $layoutDirectory = 'layouts.app';
   }
@endphp

@extends($layoutDirectory)

@section('content')
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">Manage Holidays</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="#">Home</a></li>
						<li class="breadcrumb-item"><a href="#">Holidays</a></li>
						<li class="breadcrumb-item active">Modify</li>
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
							<h3 class="card-title">Modify Holiday</h3>
						</div>
						<form class="form-horizontal" method="POST" action="{{ route('holidays.update', $holiday->id) }}">
							@csrf
							{{ method_field('PUT') }}
							<div class="card-body">
								<div class="form-group">
									<label for="title" class="col-md-4 control-label">Holiday Title</label>
									<div class="col-md-6">
										<input id="title" type="text" class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" value="{{ $holiday->title }}">

										@if ($errors->has('title'))
											<span class="text-danger">
												{{ $errors->first('title') }}
											</span>
										@endif
									</div>
								</div>

								<div class="form-group">
									<label for="description" class="col-md-4 control-label">Holiday Description</label>
									<div class="col-md-6">
										<textarea name="description" id="description" class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}" rows="4">{{ $holiday->description }}</textarea>

										@if ($errors->has('description'))
											<span class="text-danger">
												{{ $errors->first('description') }}
											</span>
										@endif
									</div>
								</div>

								<div class="form-group">
									<label for="holiday_date" class="col-md-4 control-label">Holiday Date (DD/MM/YYY)</label>
									<div class="col-md-6">
										<input id="holiday_date" type="date" class="form-control {{ $errors->has('holiday_date') ? ' is-invalid' : '' }}" name="holiday_date" value="{{ $holiday->holiday_date }}">

										@if ($errors->has('holiday_date'))
											<span class="text-danger">
												{{ $errors->first('holiday_date') }}
											</span>
										@endif
									</div>
								</div>

								<div class="form-group">
									<div class="col-md-6">
										<label for="name" >Holiday Type</label>
										<select class="form-control" id="type" name="type">
				                            <option selected value disabled>Please make a choice</option>				                            
				                            <option @if($holiday->type == "1") selected @endif value="1">Public Holiday</option>
				                            <option @if($holiday->type == "2") selected @endif value="2">National Day</option>
				                            <option @if($holiday->type == "3") selected @endif value="3">Voluntary</option>				                          
				                        </select>
										@if ($errors->has('type'))
											<span class="text-danger">
												{{ $errors->first('type') }}
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