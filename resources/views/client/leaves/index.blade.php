@extends('layouts.new_layout')
@push('page_css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
@endpush
@section('content')
<div>
    <div class="page-heading d-flex justify-content-between align-items-center gap-3 mb-3">
		<div>
			<h3>Leaves</h3>
			<p class="mb-0">Track and manage your leaves here</p>
		</div>
    </div>
    <div class="d-flex gap-3 align-items-center justify-content-between mb-4">
        <form method="GET" action="{{ route('leaves.index') }}" class="d-flex gap-3 align-items-center justify-content-between mb-4">
            <div class="search-container">
                <div class="d-flex align-items-center gap-3">
                    <p class="mb-0 position-relative search-input-container">
                        <x-heroicon-o-magnifying-glass class="search-icon" />
                        <input type="search" class="form-control" name="search" placeholder="Type here" value="{{request()->search ?? ''}}">
                    </p>
                    <button type="submit" class="btn search-btn">
                        <x-bx-filter class="w-20 h-20"/>
                        Search
                    </button>
                </div>
            </div>
        </form>
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
   <div class="bg-white p-4">
        <div class="table-responsive">
            <table class="table db-custom-table">
                <thead>
                    <tr>
						<!-- <th>Id</th> -->
						<th>Employee</th>
						<th>Subject</th>
						<th>Start Date</th>
						<th>End Date</th>
						<th>Message</th>
						<th>Leave</th>
						<th>Duration</th>
						<th>Status</th>
						<th>Action</th>	
                    </tr>
                </thead>
                <tbody>
                    @forelse ($leaves as $row)
                        <tr>
                            <!-- <td>{{ $row->id }}
							</td> -->
                            <td>{{ $row->name }}</td>
                            <td>{{ $row->leave_subject }}</td>
                            <td>{{ date('m/d/Y', strtotime($row->start_date)) }}</td>
							<td>{{ !empty($row->end_date) ? date('m/d/Y', strtotime($row->end_date)) : date('m/d/Y', strtotime($row->start_date)) }}</td>
							<td>{{ $row->leave_message }}</td>
							<td>{{ $row->leave_name }}</td>
							<td>{{ $row->leave_type }}</td>
                            <td>
								<?php 
									$type = '<span class="badge bg-warning">Pending</span>';
									if ($row->leave_status == 'pending') {
										$type = '<span class="badge bg-warning">Pending</span>';
									} elseif ($row->leave_status == 'approved') {
										$type = '<span class="badge bg-success">Approved</span>';
									} elseif ($row->leave_status == 'rejected') {
										$type = '<span class="badge bg-danger">Rejected</span>';
									}
								?>
								{!! $type !!}
							</td>
                            <td>
								<?php 
									$id = $row->id;
									$userid = $row->user_id;
									$duration = $row->leave_duration;
									$typeid = $row->type_id;
								?>
									@if ($row->leave_status == 'pending') 
									@endif

									<div class="dropdown">
										<button class="btn action-dropdown-toggle dropdown-toggle" type="button" id="dropdownMenuButton{$id}" data-bs-toggle="dropdown" aria-expanded="false">
											<x-bx-dots-horizontal-rounded class="w-20 h-20" />
										</button>
										<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{$id}">
											@if ($row->leave_status == 'pending') 
												<li>
													<a data-href="{{$id}}" data-employeeid="{{$userid}}" data-value="Approve" data-duration="{{$duration}}" data-type="{{$typeid}}" class="dropdown-item approve" title="Approve">
														<x-bx-user-check class="w-16 h-16" /> Approve
													</a>
												</li>
												<li>
													<a data-href="{{$id}}" class="dropdown-item reject mt-1" title="Reject">
														<x-bx-map-alt class="w-16 h-16" /> Reject
													</a>
												</li>
											@endif
											<li>
												<a href="/client/edit-leave/{{$id}}/{{$userid}}" class="dropdown-item" title="Edit" >
													<x-bx-edit-alt class="w-16 h-16" /> Edit
												</a>
											</li>
										</ul>
									</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">No record found</td>
                        </tr>
                    @endforelse
                    
                    </tr>
                </tbody>
            </table>
        </div>
        {{ $leaves->links('vendor.pagination.custom') }}
   </div>
</div>
@endsection

@push('page_scripts')
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
	<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script> -->
	<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
	<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
	<script type="text/javascript">
		//single record move to delete
		$(document).on('click','a.approve',function() {
			id = $(this).data('href');
		    employeeId = $(this).attr('data-employeeid');
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
						location.reload();
		            	// $('#dataTableBuilder').DataTable().draw(true);
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
						location.reload();
		            	// $('#dataTableBuilder').DataTable().draw(true);
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