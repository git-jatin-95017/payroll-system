@extends('layouts.employee')

@section('content')
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">My Leaves</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="#">Home</a></li>
						<li class="breadcrumb-item active">Leaves</li>
						<li class="breadcrumb-item active">Apply For Leaves</li>
					</ol>
				</div>
			</div>
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
					<div class="col-md-6">
						<div class="alert alert-success alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							{{ session('message') }}
						</div>
					</div>
				</div>
			@elseif (session('error'))
				<div class="row">
					<div class="col-md-6">
						<div class="alert alert-danger alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							{{ session('error') }}
						</div>
					</div>
				</div>
			@endif
			<div class="row">            	
				<div class="col-sm-6">
					<div class="card">
						<div class="card-header">
							<h3 class="card-title">Apply Leaves</h3>
						</div>
						<form class="form-horizontal" id="leaveapply" method="POST" action="{{ route('my-leaves.store') }}">
							@csrf
							<div class="card-body">								
								<div class="form-group">
									<label for="leave_subject" class="col-md-8 control-label">Subject</label>
									<div class="col-md-12">
										<input id="leave_subject" type="text" class="form-control {{ $errors->has('leave_subject') ? ' is-invalid' : '' }}" name="leave_subject" value="{{ old('leave_subject', '') }}">

										@if ($errors->has('leave_subject'))
											<span class="text-danger">
												{{ $errors->first('leave_subject') }}
											</span>
										@endif
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-12">
	                                	<label>Leave Type</label>
		                                <select class="form-control custom-select assignleave" name="typeid" id="leavetype">
		                                    <option value="">Select Here..</option>
		                                    <?php foreach($leavetypes as $value): ?>

		                                    <option value="<?php echo $value->id ?>"><?php echo $value->name ?></option>

		                                    <?php endforeach; ?>
		                                </select>
									</div>
	                            </div>
	                            <div class="form-group">
	                            	<div class="col-md-12">
		                                <span style="color:red" id="total"></span>
		                                <div class="span pull-right">
		                                    <button class="btn btn-info fetchLeaveTotal">Fetch Total Leave</button>
		                                </div>
	                                	<br>
	                                </div>
	                            </div>
	                            <div class="form-group">
	                            	<div class="col-md-12">
		                                <label class="control-label d-block w-100">Leave Duration</label>
		                                <div class="d-flex">
											<div class="mr-2">
												<input name="type" type="radio" id="radio_1" data-value="Half" class="duration" value="Half Day" checked="">
												<label for="radio_1">Hourly</label>
											</div>
											<div class="mr-2">
												<input name="type" type="radio" id="radio_2" data-value="Full" class="type" value="Full Day">
												<label for="radio_2">Full Day</label>
											</div>
											<div>
												<input name="type" type="radio" class="with-gap duration" id="radio_3" data-value="More" value="More than One day">
												<label for="radio_3">Above a Day</label>
											</div>
										</div>
		                            </div>
	                            </div>
	                            <div class="form-group">
	                            	<div class="col-md-12">
	                                	<label class="control-label" id="hourlyFix">Date</label>
	                                	<input type="date" name="startdate" class="form-control" id="recipient-name1" >
	                                </div>
	                            </div>
	                            <div class="form-group" id="enddate" style="display:none">
	                            	<div class="col-md-12">
	                                <label class="control-label">End Date</label>
	                                <input type="date" name="enddate" class="form-control" id="recipient-name1">
	                               	</div>
	                            </div>

	                            <div class="form-group" id="hourAmount">
	                            	<div class="col-md-12">
	                                <label>Length</label>
	                                <select id="hourAmountVal" class=" form-control custom-select" name="hourAmount" >
	                                    <option value="">Select Hour</option>
	                                    <option value="1">One hour</option>
	                                    <option value="2">Two hour</option>
	                                    <option value="3">Three hour</option>
	                                    <option value="4">Four hour</option>
	                                    <option value="5">Five hour</option>
	                                    <option value="6">Six hour</option>
	                                    <option value="7">Seven hour</option>
	                                    <option value="8">Eight hour</option>
	                                </select>
	                            	</div>
	                            </div>						

								<div class="form-group">
									<label for="leave_message" class="col-md-8 control-label">Reason</label>
									<div class="col-md-12">
										<textarea name="leave_message" id="leave_message" class="form-control {{ $errors->has('leave_message') ? ' is-invalid' : '' }}" rows="4">{{ old('leave_message', '') }}</textarea>

										@if ($errors->has('leave_message'))
											<span class="text-danger">
												{{ $errors->first('leave_message') }}
											</span>
										@endif
									</div>
								</div>					

							</div>
							<div class="card-footer">
								<button type="submit" class="btn btn-primary">Apply for Leave</button>
							</div>
						</form>
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