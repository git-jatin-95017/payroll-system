@extends('layouts.new_layout')

@section('content')
<div>
	<div class="page-heading d-flex justify-content-between align-items-center gap-3 mb-3">
		<div>
			<h3>Leaves</h3>
		</div>
	</div>
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
	</div>
	<div class="bg-white white-container py-4 px-4 pt-4continer-h-full">
		<div class="row">            	
				<div class="col-sm-12">
					<div class="max-w-md max-auto">
						<div class="sub-text-heading pb-4">
							<h3 class="mb-1">Apply For Leaves</h3>
							<p>Enter your leave information here</p>
						</div>
						<form class="form-horizontal" id="leaveapply" method="POST" action="{{ route('my-leaves.store') }}">
							@csrf
							<div class="row">
								<div class="col-8 mb-3">
									<div class="form-group">
										<label for="leave_subject" class="db-label">Subject</label>
										<input id="leave_subject" type="text" class="form-control db-custom-input {{ $errors->has('leave_subject') ? ' is-invalid' : '' }}" name="leave_subject" value="{{ old('leave_subject', '') }}">

										@if ($errors->has('leave_subject'))
											<span class="text-danger">
												{{ $errors->first('leave_subject') }}
											</span>
										@endif
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-8 mb-3">
									<div class="form-group">
										<label for="leave_subject" class="db-label">Leave Type</label>
										<select class="form-control select-drop-down-arrow db-custom-input custom-select assignleave" name="typeid" id="leavetype">
		                                    <option value="">Select Here..</option>
		                                    <?php foreach($leavetypes as $value): ?>

		                                    <option value="<?php echo $value->id ?>"><?php echo $value->name ?></option>

		                                    <?php endforeach; ?>
		                                </select>
										@if ($errors->has('typeid'))
											<span class="text-danger">
												{{ $errors->first('typeid') }}
											</span>
										@endif
									</div>
								</div>
								<div class="col-4 mt-4">
									<div class="span pull-right">
										<button class="btn btn-primary fetchLeaveTotal">Fetch Total Leave</button>
									</div>
									<span style="color:red" id="total"></span>
									
								</div>
							</div>

							<div class="row">
								<div class="col-8 mb-3">
									<div class="form-group">
										<label for="leave_subject" class="db-label">Leave Duration</label>
										<div class="form-check form-check-inline custom-radio-btn">
											<input name="type" type="radio" id="radio_1" data-value="Hourly" class="form-check-input duration" value="Hourly" checked>
											<label class="form-check-label" for="inlineRadio1">Hourly</label>
										</div>
										<div class="form-check form-check-inline custom-radio-btn">
											<input name="type" type="radio" id="radio_2" data-value="Full" class="form-check-input type" value="Full Day">
											<label class="form-check-label" for="inlineRadio2">Full Day</label>
										</div>
										<div class="form-check form-check-inline custom-radio-btn">
											<input name="type" type="radio" class="form-check-input with-gap duration" id="radio_3" data-value="More" value="More than One day">
											<label class="form-check-label" for="inlineRadio3">More than one day</label>
										</div>
										@if ($errors->has('type'))
											<span class="text-danger">
												{{ $errors->first('type') }}
											</span>
										@endif
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-8 mb-3">
									<div class="form-group">
										<label for="startdate" class="db-label" id="hourlyFix">Date</label>
	                                	<input type="date" name="startdate" class="form-control db-custom-input" id="recipient-name1">

										@if ($errors->has('startdate'))
											<span class="text-danger">
												{{ $errors->first('startdate') }}
											</span>
										@endif
									</div>
								</div>
							</div>

							<div class="row" id="enddate">
								<div class="col-8 mb-3">
									<div class="form-group">
										<label for="enddate" class="db-label">End Date</label>
										<input type="date" name="enddate" class="form-control db-custom-input" id="recipient-name2">

										@if ($errors->has('enddate'))
											<span class="text-danger">
												{{ $errors->first('enddate') }}
											</span>
										@endif
									</div>
								</div>
							</div>

							<div class="row" id="hourAmount">
								<div class="col-8 mb-3">
									<div class="form-group">
										<label for="hourAmount" class="db-label">Duration</label>
										<input id="hourAmount" type="text" id="hourAmountVal" class="form-control db-custom-input {{ $errors->has('hourAmount') ? ' is-invalid' : '' }}" name="hourAmount">

										@if ($errors->has('hourAmount'))
											<span class="text-danger">
												{{ $errors->first('hourAmount') }}
											</span>
										@endif
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-8 mb-3">
									<div class="form-group">
										<label for="leave_message" class="db-label">Reason</label>
										<textarea name="leave_message" id="leave_message" class="form-control db-custom-input {{ $errors->has('leave_message') ? ' is-invalid' : '' }}" rows="4"></textarea>
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

                if(duration =='Hourly'){
                    $('#enddate').hide();
                    $('#hourlyFix').text('Date');
                    $('#hourAmount').show();
                }
                else if(duration =='Full'){
                    $('#enddate').show();  
                    $('#hourAmount').hide();  
                    $('#hourlyFix').text('Start date');  
                }
                else if(duration =='More'){
                    $('#enddate').show();
                    $('#hourAmount').hide();
                }
            });

			$('#leaveapply input').trigger('change');
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