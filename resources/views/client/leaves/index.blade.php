@extends('layouts.new_layout')
@push('page_css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
@endpush
@section('content')
<div>
   <div class="page-heading d-flex justify-content-between align-items-center gap-3 mb-4">
		<div>
			<h3>Leaves</h3>
			<p class="mb-0">Track and manage leaves here</p>
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
					<th>Employee</th>
					<th>Subject</th>
					<th>Start Date</th>
					<th>End Date</th>
					<th>Message</th>
					<th>Leave</th>
					<th>Type</th>
					<th>Status</th>
					<th>Action</th>	
				</tr>
		 	</thead>
	   </table>
   </div>
</div>
@endsection

@push('page_scripts')
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
	<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
	<script type="text/javascript">
		//single record move to delete
		$(document).on('click','a.approve',function() {
			id = $(this).data('href');
		    employeeId = $(this).attr('data-employeeId');
	        lid = id;
	        lvalue = $(this).attr('data-value');
	        duration = $(this).attr('data-duration');
	        type = $(this).attr('data-type');

		    var approveUrl = '/client/leaves/' + $(this).data('href');
		    approveLeave(approveUrl,employeeId, lid,lvalue,duration,type,id);
		});
		$(document).on('click','a.reject',function(){
		    var rejectUrl = '/client/leaves/' + $(this).data('href');
		    rejectLeave(rejectUrl);
		});

		// move to Delete single record by just pass the url of
		function approveLeave(approveUrl,employeeId, lid,lvalue,duration,type,id) {
		  Swal.fire({
		      text: "You Want to Approve?",
		      showCancelButton: true,
		      confirmButtonText: 'Approve Leave!',
		      cancelButtonText: 'Not Now!',
		      reverseButtons: true,
		      showCloseButton : true,
		      allowOutsideClick:false,
		    }).then((result)=>{
		      var action = 'delete';
		      if(result.value == true){
		        $.ajax({
		          	url: approveUrl,
		          	type: 'PUT',
		          	data: {
	                	_token: "{{ csrf_token() }}",
	                	action:'approve',
	                	employeeId: employeeId,
		              	lid: lid,
		              	lvalue: lvalue,
		              	duration: duration,
		              	type: type
	             	},
		          	dataType:'JSON',
		          	success:(result)=>{
		            	$('#dataTableBuilder').DataTable().draw(true);
		          	}
		        });
		      }
		    });
		}

		function rejectLeave(rejectUrl) {
		  Swal.fire({
		      text: "You Want to Reject?",
		      showCancelButton: true,
		      confirmButtonText: 'Reject Leave!',
		      cancelButtonText: 'Not Now!',
		      reverseButtons: true,
		      showCloseButton : true,
		      allowOutsideClick:false,
		    }).then((result)=>{
		      var action = 'delete';
		      if(result.value == true){
		        $.ajax({
		          	url: rejectUrl,
		          	type: 'PUT',
		          	data: {
	                	_token: "{{ csrf_token() }}",
	                	action:'reject'
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
						url: "{{route('leaves.getData')}}",
					  	type: 'GET',
					  	data: function (d) {
					  		d.start_date = $('#start_date').val();
					  		d.end_date = $('#end_date').val();
					  	}
					},
					columns: [
			           	// {data: 'action', name: 'Action', orderable: false, searchable: false},
						{data:'id'},
						{data:'name'},
						{
							data:'leave_subject',
							// orderable: true
						},
						{data:'start_date'},
						{data:'end_date'},
						{data:'leave_message'},
						{data:'leave_name'},
						{data:'leave_type'},
						{
			                data: 'leave_status',
			                // orderable : false,
			                // searchable : false,
			                render: function(data, type, row, meta) {
			                	var html = `<span class="badge badge-warning">Pending</span>`;

			                	if (row.leave_status == 'pending') {
			                		html = `<span class="badge badge-warning text-white">Pending</span>`;
			                	}

			                	if (row.leave_status == 'approved') {
			                		html = `<span class="badge btn-success text-white">Approved</span>`;
			                	}

			                	if (row.leave_status == 'rejected') {
			                		html = `<span class="badge btn-danger text-white">Rejected</span>`;
			                	}

			                	return html;
			                }
			            },
			            {
			                data: 'actions',
			                orderable : false,
			                searchable : false,
			                render: function(data, type, row, meta) {
			                	var id = row.id;
			                	var userid = row.user_id;
			                	var duration = row.leave_duration;
			                	var typeid = row.type_id;

			                	var action = `<div class="table-actions">`;
			                	if (row.leave_status == 'pending') {
			                		action += ` <a data-href="${id}" data-employeeId="${userid}" data-value="Approve" data-duration="${duration}" data-type="${typeid}" class="btn btn-sm btn-success approve mt-1" style="color:#fff;" title="Approve"><i class='fas fa-check'></i></a>`;
			                		action += ` <a data-href="${id}" class="btn btn-sm btn-danger reject mt-1" style="color:#fff;" title="Reject"><i class='fas fa-close'></i></a>`;
			                	}
			                	action += `&nbsp;<a href="/client/edit-leave/${id}/${userid}" class="btn btn-sm btn-primary mt-1" title="Edit" ><i class='fas fa-pen'></i></a>`;
			                	action += `</div>`;
			                	return action;
			                }
			            }
			        ],
			        orderCellsTop: true,
        			// fixedHeader: true,

			  	});
		   });

		  	$('#btnFiterSubmitSearch').click(function(){
				$('#dataTableBuilder').DataTable().draw(true);
		  	});

		  	// Check all
		   $('#checkall').click(function(){
		      if($(this).is(':checked')){
		         $('.delete_check').prop('checked', true);
		      }else{
		         $('.delete_check').prop('checked', false);
		      }
		   });

		   // Checkbox checked
			function checkcheckbox(){

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