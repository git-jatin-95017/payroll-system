@extends('layouts.employee')
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
	<!-- <div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">Leaves</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="#">Home</a></li>
						<li class="breadcrumb-item active">Leaves</li>
					</ol>
				</div>
			</div>
		</div>
	</div> -->
	<div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor"><i class="mdi mdi-rocket" style="color:#1976d2"></i> My Leaves</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">My Leaves</li>
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
				<div class="col-lg-12">					
					<div class="card">
						<div class="card-header d-flex justify-content-between">
							<h3 class="card-title">My Leaves</h3>
							<div class="card-tools">
								<div class="input-group input-group-sm">
									<a href="{{ route('my-leaves.create' )}}" class="btn btn-primary">Apply For Leave</a>
								</div>
							</div>
						</div>					
						<div class="card-body">							
							<table class="table table-bordered table-hover wrap" id="dataTableBuilder">
								<thead>
									<tr>										
										<th>Id</th>
										<th>Leave</th>
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