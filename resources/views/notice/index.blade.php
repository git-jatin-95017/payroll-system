@php
   if(auth()->user()->role_id == 3) {
      $layoutDirectory = 'layouts.employee';
   } else {
      $layoutDirectory = 'layouts.new_layout';
   }
@endphp

@extends($layoutDirectory)

@push('page_css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
@endpush
@section('content')
<div>
   <div class="page-heading d-flex justify-content-between align-items-center gap-3 mb-4">
		<div>
			<h3>Notices</h3>
			<p class="mb-0">Track and manage your notices here</p>
		</div>
		<div>
			<a href="{{ route('notice.create' )}}" class="d-flex justify-content-center gap-2 primary-add ">
				<x-heroicon-o-plus width="16" />
				<span>Add Notice</span>
			</a>
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
				<th>Message</th>
				@if(auth()->user()->role_id == 2)
				<th>Action</th>			
				@endif	
		   </tr>
		 </thead>
	   </table>
   </div>
</div>
@endsection

@push('page_scripts')
	<!-- <script src="https://cdn.datatables.net/fixedheader/3.2.3/css/fixedHeader.dataTables.min.css"></script> -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
	<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
	<script type="text/javascript">
		//single record move to delete
		$(document).on('click','a.delete',function(){
		    var trashRecordUrl = $(this).data('href');
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
					// autoWidth: true,
					// scrollX: true,
					scrollY: "400px",
					ajax: {
						url: "{{ route('notice.index') }}",
					  	type: 'GET',
					  	data: function (d) {
					  		// d.start_date = $('#start_date').val();
					  		// d.end_date = $('#end_date').val();
					  	}
					},
					columns: [
			           	// {data: 'action', name: 'Action', orderable: false, searchable: false},
						{data:'id'},
						{data:'message'},
						@if(auth()->user()->role_id == 2)
						{data:'action_button', name: 'Action', orderable: false, searchable: false}
						@endif
			        ],
			        orderCellsTop: true,
        			// fixedHeader: true,
			       
			});
		});
		 
	   // Checkbox checked
		function checkcheckbox() {

		   // Total checkboxes
		   var length = $('.delete_check').length;

		   // Total checked checkboxes
		   var totalchecked = 0;
		   $('.delete_check').each(function(){
		      if($(this).is(':checked')){
		         totalchecked+=1;
		      }
		   });

		   // Checked unchecked checkbox
		   if(totalchecked == length){
		      $("#checkall").prop('checked', true);
		   }else{
		      $('#checkall').prop('checked', false);
		   }
		}
	</script>		
@endpush