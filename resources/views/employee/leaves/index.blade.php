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
<div>
    <div class="page-heading d-flex justify-content-between align-items-center gap-3 mb-3">
		<div>
			<h3>Leaves</h3>
			<p class="mb-0">Track and manage your leaves here</p>
		</div>
    </div>
    <div class="d-flex gap-3 align-items-center justify-content-between mb-4">
        <form method="GET" action="{{ route('my-leaves.index') }}" class="d-flex gap-3 align-items-center justify-content-between mb-4">
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
        <div>
            <form action="{{ route('my-leaves.create') }}" method="GET" class="m-0 p-0">
                <button type="submit" class="d-flex justify-content-center gap-2 primary-add">
                    <x-heroicon-o-plus width="16" />
                    <span>Apply For Leave</span>
                </button>
            </form>
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
   <div class="bg-white p-4">
        <div class="table-responsive">
            <table class="table db-custom-table">
                <thead>
                    <tr>
						<!-- <th>Id</th> -->
						<th>Leave</th>
						<th>Subject</th>
						<th>Start Date</th>
						<th>End Date</th>
						<th>Message</th>										
						<th>Duration</th>										
						<th>Status</th>								
						<th>Action</th>		
                    </tr>
                </thead>
                <tbody>
                    @forelse ($myLeaves as $row)
					@php $id = $row->id @endphp 
                        <tr>
							<!-- <td>{{ $id }}</td> -->
							<td>{{ $row->name }}</td>
                            <td>{!! $row->leave_subject  !!}</td>
							<td>{{ date('m/d/Y', strtotime($row->start_date)) }}</td>
							<td>{{ !empty($row->end_date) ? date('m/d/Y', strtotime($row->end_date)) : date('m/d/Y', strtotime($row->start_date)) }}</td>
							<td>{{ $row->leave_message }}</td>
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
								<div class="dropdown">
                                    <button class="btn action-dropdown-toggle dropdown-toggle" type="button" id="dropdownMenuButton{$id}" data-bs-toggle="dropdown" aria-expanded="false">
                                        <x-bx-dots-horizontal-rounded class="w-20 h-20" />
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{$id}">
										<a href="/employee/my-leaves/{{$id}}/edit" class="dropdown-item" title="Edit" >
											<x-bx-edit-alt class="w-16 h-16" /> Edit
										</a>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No record found</td>
                        </tr>
                    @endforelse
                    </tr>
                </tbody>
            </table>
        </div>
        {{ $myLeaves->links('vendor.pagination.custom') }}
   </div>
</div>  
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
	<script>
        $(document).ready(function () {
            $('#leaveapply input').on('change', function(e) {
                e.preventDefault(e);

                // Get the record's ID via attribute  
                var duration = $('input[name=type]:checked', '#leaveapply').attr('data-value');

                if(duration =='Half'){
                    $('#enddate').hide();
                    $('#hourlyFix').text('Date');
                    $('#hourAmount').show();
                }
                else if(duration =='Full'){
                    $('#enddate').hide();  
                    $('#hourAmount').hide();  
                    $('#hourlyFix').text('Start date');  
                }
                else if(duration =='More'){
                    $('#enddate').show();
                    $('#hourAmount').hide();
                }
            });
        }); 
    </script>
    <script>
	    $(document).ready(function () {
	        $('.fetchLeaveTotal').on('click', function (e) {
	            e.preventDefault();
	            
	            var selectedEmployeeID = "{{ auth()->user()->id }}"; //$('.selectedEmployeeID').val();
	            var leaveTypeID = $('#leavetype').val();
	            
	            if (leaveTypeID == '' || leaveTypeID == null) {
	            	alert('Please select leave type first.');
	            	return false;
	            }
	            // console.log(selectedEmployeeID, leaveTypeID);

	            $.ajax({
	                url: "{{ route('my-leaves.create') }}", //'LeaveAssign?leaveID=' + leaveTypeID + '&employeeID=' +selectedEmployeeID,
	                method: 'GET',
	                dataType:'JSON',
	                data: {
	                	leaveID: leaveTypeID,
	                	employeeID: selectedEmployeeID,
	                }
	            }).done(function (response) {
	                //console.log(response);
	                $("#total").html(response.totalday);
	            });
	        });
	    });
	</script>
    <script type="text/javascript">
        $('#duration').on('input', function() {
            var day = parseInt($('#duration').val());
            console.log('gfhgf');
            var hour = 8;
            $('.totalhour').val((day * hour ? day * hour : 0).toFixed(2));
        });
    </script>
	<script>
		if ( $('.multidatepicker').length > 0 ) {
	        $('.multidatepicker').datepicker({
	            format: 'mm/dd/yyyy',
	            startDate : new Date(),
	            multidate: true,
	            autoclose: true
	        });
	    }
	</script>
	<script>
		$('#delete-all').on('click', function (e) { 
			e.preventDefault();		     
			// Confirm alert
			var confirmdelete = confirm("Do you really want to delete all records?");

			if (confirmdelete == true) {
				$.ajax({
				   url: $(this).data('remote'),
				   type: 'POST',
				   data: {is_delete_request_all:true, "_token": "{{ csrf_token() }}"},
				   success: function(response) {
				   	Swal.fire(
				      'Success!',
				      'Records deleted successfully!',
				      'success'
				    )
				    
				    // tabelD.ajax.reload();
				    $('#dataTableBuilder').DataTable().draw(true);
				   }
				});
			} 
		});
	</script>

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
						url: "{{route('my.leaves.getData')}}",
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
						{data:'leave_type'},
						{
			                data: 'leave_status',
			                // orderable : false,
			                // searchable : false,
			                render: function(data, type, row, meta) {	
			                	var html = `<span class="badge badge-warning text-white">Pending</span>`;

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
			                
			                	var action = ``;
			                		action += `<a href="/employee/my-leaves/${id}/edit" class="btn btn-sm btn-primary" title="Edit" style="color:#fff;"><i class='fas fa-pen'></i></a>`;
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