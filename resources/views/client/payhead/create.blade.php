@extends('layouts.new_layout')
@push('page_css')
	<style>
		thead input.top-filter {
	        width: 100%;
	    }

	    table.dataTable tbody td {
			word-break: break-word;
		  	vertical-align: top;
		}
	</style>
	<link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap4.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/responsive.bootstrap4.min.css') }}">
@endpush
@section('content')


<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">
            <i class="fa fa-braille" style="color:#1976d2"></i>
            Pay Head
        </h3>
    </div>

    <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="javascript:void(0)">Home</a>
            </li>
            <li class="breadcrumb-item active">Pay Head</li>
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
				<div class="col-sm-7">
					<div class="card">
						<div class="card-header">
							<h3 class="card-title">Pay Head</h3>
						</div>
						<form class="form-horizontal" method="POST" action="{{ route('pay-head.store') }}">
							@csrf
							<div class="card-body">								
								<div class="form-group">
									<label for="name" class="col-md-8 control-label">Pay Head Name</label>
									<div class="col-md-12">
										<input id="name" type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name', '') }}">

										@if ($errors->has('name'))
											<span class="text-danger">
												{{ $errors->first('name') }}
											</span>
										@endif
									</div>
								</div>							
								<div class="form-group">
									<label for="pay_type" class="col-md-8 control-label">Pay Head Type</label>
									<div class="col-md-12">
										<select class="form-control" id="pay_type" name="pay_type">
											<option @if(old('pay_type', '') == "earnings") selected @endif value="earnings">Addition to Gross Pay</option>
											<option @if(old('pay_type', '') == "deductions") selected @endif value="deductions">Deduction from Net Pay</option>
											<option @if(old('pay_type', '') == "nothing") selected @endif value="nothing">Addition to Net Pay</option>
										</select>
										@if ($errors->has('pay_type'))
											<span class="text-danger">
												{{ $errors->first('pay_type') }}
											</span>
										@endif
									</div>
								</div>
								<div class="form-group">
									<label for="description" class="col-md-8 control-label">Pay Head Description</label>
									<div class="col-md-12">
										<input id="description" type="text" class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" value="{{ old('description', '') }}">

										@if ($errors->has('description'))
											<span class="text-danger">
												{{ $errors->first('description') }}
											</span>
										@endif
									</div>
								</div>								
							</div>
							<div class="card-footer">
								<button type="submit" class="btn btn-primary">Save</button>
								<a href="{{ route('pay-head.index' )}}" class="btn btn-info">Back</a>
							</div>
						</form>
					</div>
				</div>						
			</div>
		</div>		
	</section>    
@endsection