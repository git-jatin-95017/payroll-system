@extends('layouts.new_layout')
@push('page_css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
@endpush
@section('content')
<div>
   <div class="page-heading d-flex justify-content-between align-items-center gap-3 mb-4">
		<div>
			<h3>Pay Heads</h3>
			<p class="mb-0">Track and manage pay heads here</p>
		</div>
		<div>
			<!-- <a href="{{ route('holidays.create' )}}" class="d-flex justify-content-center gap-2 primary-add ">
				<x-heroicon-o-plus width="16" />
				<span>Add Holiday</span>
			</a> -->
		</div>
   </div>
   @if (session('message'))
   <div>
      <div class="alert alert-success alert-dismissible">
         <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
         {{ session('message') }}
      </div>
   </div>
   @elseif (session('error'))
   <div class="col-md-12">
      <div class="alert alert-danger alert-dismissible">
         <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
         {{ session('error') }}
      </div>
   </div>
   @endif
   <div class="bg-white table-custom">
	   <table id="dataTableBuilder" class="table table-hover responsive nowrap" style="width:100%">
		 	<thead>
				<tr>
					<th>Id</th>
					<th>Head Name</th>										
					<th>Head Description</th>										
					<th>Head Type</th>										
					<th>Action</th>		
				</tr>
		 	</thead>
	   </table>
   </div>
</div>
@endsection
@push('page_css')
	<link rel="stylesheet" href="{{ asset('js/datepicker/datepicker3.css') }}">
@endpush

@push('page_scripts')
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
	<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
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

		  	var table = $('#dataTableBuilder').DataTable({
					processing: true,
					serverSide: true,
					autoWidth: false,
					// autoWidth: true,
					// scrollX: true,
					scrollY: "400px",
					ajax: {
						url: "{{route('pay-head.getData')}}",
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
							data:'description', 
							// orderable: true
						},
						{
							data:'pay_type', 
							// orderable: true
						},
						{
			                data: 'actions',
			                orderable : false,
			                searchable : false,
			                render: function(data, type, row, meta) {
			                	
			                	var id = row.id;
			                
			                	var action = `<div class="table-actions">`;
			                		//action += `<a data-href="/client/pay-head/${id}" class="btn btn-sm btn-info approve"><i class='fas fa-pen'></i></a>`;
			                		action += ` <a href="/client/pay-head/${id}/edit" class="btn btn-sm btn-primary"><i class='fas fa-pen'></i></a>`;
			                		action += ` <a data-href="/client/pay-head/${id}" class="btn btn-sm btn-danger delete" style="color:#fff;"><i class='fas fa-trash'></i></a>`;			
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