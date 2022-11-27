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
	<div class="content-header">
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
				<div class="col-sm-4">
					<div class="card card-primary">
						<div class="card-header">
							<h3 class="card-title">Apply for Leave</h3>
						</div>
						<form class="form-horizontal" method="POST" action="{{ route('my-leaves.store') }}">
							@csrf
							<div class="card-body">
								<div class="form-group">
									<label for="leave_dates" class="col-md-8 control-label">Date (MM/DD/YYYY)</label>
									<div class="col-md-12">
										<input id="leave_dates" type="text" class="form-control multidatepicker {{ $errors->has('leave_dates') ? ' is-invalid' : '' }}" name="leave_dates" value="{{ old('leave_dates', '') }}">
										<small class="text-muted">You can select multiple dates separated by comma.</small>
										@if ($errors->has('leave_dates'))
											<span class="text-danger">
												{{ $errors->first('leave_dates') }}
											</span>
										@endif
									</div>
								</div>

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
									<label for="leave_message" class="col-md-8 control-label">Leave Message</label>
									<div class="col-md-12">
										<textarea name="leave_message" id="leave_message" class="form-control {{ $errors->has('leave_message') ? ' is-invalid' : '' }}" rows="4">{{ old('leave_message', '') }}</textarea>

										@if ($errors->has('leave_message'))
											<span class="text-danger">
												{{ $errors->first('leave_message') }}
											</span>
										@endif
									</div>
								</div>					

								<div class="form-group">
									<div class="col-md-12">
										<label for="name" >Leave Type</label>
										<select class="form-control" id="leave_type" name="leave_type">
				                            <option selected value disabled>Please make a choice</option>
				                            <option @if(old('leave_type') == "Casual Leave") selected @endif value="Casual Leave">Casual Leave</option>
				                            <option @if(old('leave_type') == "Earned Leave") selected @endif value="Earned Leave">Privileged / Earned Leave</option>
				                            <option @if(old('leave_type') == "Sick Leave") selected @endif value="Sick Leave">Medical / Sick Leave</option>
				                            <option @if(old('leave_type') == "Maternity Leave") selected @endif value="Maternity Leave">Maternity Leave</option>
				                            <option @if(old('leave_type') == "Leave Without Pay") selected @endif value="Leave Without Pay">Leave Without Pay</option>
				                        </select>

										@if ($errors->has('leave_type'))
											<span class="text-danger">
												{{ $errors->first('leave_type') }}
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
				<div class="col-8">					
					<div class="card card-primary">
						<div class="card-header">
							<h3 class="card-title">My Leaves</h3>
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
										<th>ID</th>
										<th>SUBJECT</th>
										<th>DATES</th>
										<th>MESSAGE</th>										
										<th>TYPE</th>										
										<th>STATUS</th>								
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
						{
							data:'leave_subject', 
							// orderable: true
						},
						{data:'leave_dates'},						
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