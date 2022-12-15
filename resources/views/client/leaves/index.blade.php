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
            Leaves
        </h3>
    </div>

    <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="javascript:void(0)">Home</a>
            </li>
            <li class="breadcrumb-item active">Leaves</li>
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
						<div class="card-header">
							<h3 class="card-title">Leaves</h3>
							<div class="card-tools">
								<div class="input-group input-group-sm">
									<!-- <a href="{{ route('my-leaves.create' )}}" class="btn btn-primary">Add New</a> -->
								</div>
							</div>
						</div>					
						<div class="card-body">							
							<table class="table table-bordered table-hover wrap" id="dataTableBuilder">
								<thead>
									<tr>										
										<th>Id</th>
										<th>Emp Code</th>
										<th>Subject</th>
										<th>Start Date</th>
										<th>End Date</th>
										<th>Message</th>										
										<th>Type</th>										
										<th>Status</th>								
										<th>Action</th>								
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
						{data:'user_code'},						
						{
							data:'leave_subject', 
							// orderable: true
						},
						{data:'start_date'},						
						{data:'end_date'},						
						{data:'leave_message'},						
						{data:'leave_type'},
						{
			                data: 'leave_status',
			                // orderable : false,
			                // searchable : false,
			                render: function(data, type, row, meta) {	
			                	var html = `<span class="badge badge-warning">Pending</span>`;

			                	if (row.leave_status == 'pending') {
			                		html = `<span class="badge badge-warning">Pending</span>`;
			                	}	

			                	if (row.leave_status == 'approved') {
			                		html = `<span class="badge badge-success text-white">Approved</span>`;
			                	}	

			                	if (row.leave_status == 'rejected') {
			                		html = `<span class="badge badge-danger text-white">Rejected</span>`;
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
			                		action += `<a data-href="${id}" data-employeeId="${userid}" data-value="Approve" data-duration="${duration}" data-type="${typeid}" class="btn btn-sm btn-info approve">Approve</a>`;
			                		action += ` <a data-href="${id}" class="btn btn-sm btn-primary reject">Reject</a>`;
			                	}
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