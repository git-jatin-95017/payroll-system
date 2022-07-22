@extends('layouts.app')

@section('content')
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">National Prices</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="#">Home</a></li>
						<li class="breadcrumb-item active">National Prices</li>
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
							<h3 class="card-title">National Prices</h3>
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
	<script>
		$('#dataTableBuilder').on('click', '.btn-delete[data-remote]', function (e) { 
		    e.preventDefault();		     
		    var url = $(this).data('remote');
		    // confirm then
		    if (confirm('Are you sure you want to delete this?')) {
		        $.ajax({
		            url: url,
		            type: 'DELETE',
		            dataType: 'json',
		            data: {method: '_DELETE', submit: true, "_token": "{{ csrf_token() }}",}
		        }).always(function (data) {
		            $('#dataTableBuilder').DataTable().draw(true);
		        });
		    }
		});
	</script>
@endpush
