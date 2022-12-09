@extends('layouts.app')
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
			Leave Policies
		</h3>
	</div>

	<div class="col-md-7 align-self-center">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="javascript:void(0)">Home</a>
			</li>
			<li class="breadcrumb-item active">Leave Policies</li>
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
			<div class="col-12">					
				<div class="card">
					<div class="card-header  d-flex justify-content-between">
						<h3 class="card-title">List Of Leave Policies</h3>
						<div class="card-tools">
							<div class="input-group input-group-sm">
								<a href="{{ route('leave-type.create' )}}" class="btn btn-primary">Add New</a>
							</div>
						</div>
					</div>					
					<div class="card-body">							
						<table class="table table-bordered table-hover wrap" id="dataTableBuilder">
							<thead>
								<tr>										
									<th>ID</th>
									<th>LEAVE TYPE</th>										
									<th>NO. OF DAYS</th>										
									<th>ACTION</th>								
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
@push('page_css')
	<link rel="stylesheet" href="{{ asset('js/datepicker/datepicker3.css') }}">
@endpush

@push('page_scripts')
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
	<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>
	<script src="{{ asset('js/dataTables.responsive.min.js') }}"></script>
	<script src="{{ asset('js/dataTables.buttons.min.js') }}"></script>
	<script src="{{ asset('js/datepicker/bootstrap-datepicker.js') }}"></script>
	<script type="text/javascript">
		//single record move to delete
		$(document).on('click','a.delete',function(){
			var trashRecordUrl = $(this).data('href');
			console.log(trashRecordUrl);
			moveToDelete(trashRecordUrl);
		});

		// move to Delete single record by just pass the url of 
		function moveToDelete(trashRecordUrl) {
		  Swal.fire({
			  text: "You Want to Delete?",
			  showCancelButton: true,
			  confirmButtonText: '<i class="ik trash-2 ik-trash-2"></i> Permanent Delete!',
			  cancelButtonText: 'Not Now!',
			  reverseButtons: true,
			  showCloseButton : true,
			  allowOutsideClick:false,
			}).then((result)=>{
			  var action = 'delete';
			  if(result.value == true){
				$.ajax({
					url: trashRecordUrl,
					type: 'DELETE',
					data: {
						_token: "{{ csrf_token() }}",
					},
					dataType:'JSON',
					success:(result)=>{
						$('#dataTableBuilder').DataTable().draw(true);		           
					}
				});
			  }
			});
		}

		$(document).ready( function () {
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});

			// setTimeout(function() {
				// $('#dataTableBuilder thead tr')
			 //        .clone(true)
			 //        .addClass('filters')
			 //        .appendTo('#dataTableBuilder thead');
			// }, 1000);

			var table = $('#dataTableBuilder').DataTable({
					processing: true,
					serverSide: true,
					autoWidth: false,
					// autoWidth: true,
					// scrollX: true,
					scrollY: "400px",
					ajax: {
						url: "{{route('leave-type.getData')}}",
						type: 'GET',
						data: function (d) {
							d.start_date = $('#start_date').val();
							d.end_date = $('#end_date').val();
						}
					},
					columns: [
						{data:'id'},						
						{
							data:'name', 
							// orderable: true
						},
						{
							data:'leave_day', 
							// orderable: true
						},
						{
							data: 'actions',
							orderable : false,
							searchable : false,
							render: function(data, type, row, meta) {
								
								var id = row.id;
							
								var action = `<div class="table-actions">`;
									//action += `<a data-href="/client/leave-type/${id}" class="btn btn-sm btn-info approve"><i class='fas fa-pen'></i></a>`;
									action += ` <a data-href="/client/leave-type/${id}" class="btn btn-sm text-danger delete"><i class='fas fa-trash'></i></a>`;			
									action += `</div>`;
								return action;
							}
						}
					],
					orderCellsTop: true,
					// fixedHeader: true,
				   
				});
		   });		  	

	</script>		
@endpush