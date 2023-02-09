@extends('layouts.app')
@push('page_css')
	<style>
		thead input.top-filter {
	        width: 100%;
	    }
	</style>
	<link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap4.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/responsive.bootstrap4.min.css') }}">
@endpush
@section('content')
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">Attendance</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="#">Home</a></li>
						<li class="breadcrumb-item active">Attendance</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
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
					<div class="card">
						<div class="card-header">
							<h3 class="card-title">Employee Attendance</h3>							
						</div>
						
						<div class="card-body">
							<table class="table table-bordered table-hover nowrap" id="dataTableBuilder">
								<thead>
									<tr>
										<th>Date</th>
										<!-- <th>Emp Code</th> -->
										<th>Name</th>
										<th>Punch-In</th>
										<th>Punch-In Message</th>
										<th>Punch-Out</th>
										<th>Punch-Out Message</th>
										<th>Work Hours</th>
									</tr>
								</thead>
							</table>
						</div>
					  </div>
				</div>
			</div>
		</div>
	</section>    
@endsection

@push('page_scripts')
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
	<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>
	<script src="{{ asset('js/dataTables.responsive.min.js') }}"></script>
	<script src="{{ asset('js/dataTables.buttons.min.js') }}"></script>	

	<script type="text/javascript">
		$(document).ready( function () {
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});

		  	var table = $('#dataTableBuilder').DataTable({
					processing: true,
					serverSide: true,
					// autoWidth: true,
					// scrollX: true,
					scrollY: "400px",
					ajax: {
						url: "{{route('attendance.getData')}}",
					  	type: 'GET',
					  	data: function (d) {
					  		//d.start_date = $('#start_date').val();
					  		//d.end_date = $('#end_date').val();
					  	}
					},	
					//"order": [0, 'desc'],	
					columns: [
			           	// {data: 'action', name: 'Action', orderable: false, searchable: false},
						{data:'attendance_date'},						
						// {data:'user_code'},
						{data:'name'},						
						{data:'punchin'},						
						{data:'punchin_message'},						
						{data:'punchout'},						
						{data:'punchout_message'},						
						{data:'work_hrs'},
			        ],
					"columnDefs": [{
		                "targets": 0,
		                "className": "dt-center"
		            }, {
		                "targets": 1,
		                "className": "dt-center"
		            }],			
			        orderCellsTop: true
        			// fixedHeader: true,			       
			  	});
		   });
	</script>		
@endpush