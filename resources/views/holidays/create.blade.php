@extends('layouts.app')

@section('content')
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">
            <i class="fa fa-braille" style="color:#1976d2"></i>
            Manage Holidays
        </h3>
    </div>

    <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="javascript:void(0)">Home</a>
            </li>
            <li class="breadcrumb-item active">Holidays</li>
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
				<div class="col-sm-12">
					<div class="card">
						<div class="card-header">
							<h3 class="card-title">Add New</h3>
						</div>
						<form class="form-horizontal" method="POST" action="{{ route('holidays.store') }}">
							@csrf
							<div class="card-body">
								<div class="form-group">
									<label for="title" class="col-md-4 control-label">Holiday Title</label>
									<div class="col-md-6">
										<input id="title" type="text" class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" value="{{ old('title', '') }}">

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
										<textarea name="description" id="description" class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}" rows="4">{{ old('description', '') }}</textarea>

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
										<input id="holiday_date" type="date" class="form-control {{ $errors->has('holiday_date') ? ' is-invalid' : '' }}" name="holiday_date" value="{{ old('holiday_date', '') }}">

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
	</section>
@endsection