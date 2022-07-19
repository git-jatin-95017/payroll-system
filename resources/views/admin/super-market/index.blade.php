@extends('layouts.app')

@section('content')
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">Super Market</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="#">Home</a></li>
						<li class="breadcrumb-item active">Super Market</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					@if(session('status'))
						<div class="alert alert-success">
							{{ session('status') }}
						</div>
					@endif
					<div class="card">
						<div class="card-header">
							<h3 class="card-title">Super market Data</h3>
						</div>
						<div class="card-body">
							{!! $html->table(['class' => 'table table-bordered table-hover nowrap'], true) !!}
						</div>
					  </div>
				</div>
			</div>
		</div>
	</section>    
@endsection

@push('page_scripts')
	{!! $html->scripts() !!}
@endpush
