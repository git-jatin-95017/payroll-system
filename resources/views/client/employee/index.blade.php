@extends('layouts.app')
@push('page_css')
	<style>
		thead input.top-filter {
	        width: 100%;
	    }
	</style>
@endpush
@section('content')
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">Manage Employees</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="#">Home</a></li>
						<li class="breadcrumb-item active">List Of Employees</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
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
					<div class="card">
						<div class="card-header">
							<h3 class="card-title">List of employees</h3>
							<div class="card-tools">
								<div class="input-group input-group-sm">
									<a href="{{ route('employee.create' )}}" class="btn btn-primary">Add New</a>
								</div>
							</div>
						</div>
						
						<div class="card-body">
							<!-- <div class="row">
							    <div class="col">
							      <input type="date" name="start_date" id="start_date" class="form-control datepicker-autoclose" placeholder="Please select start date">
							    </div>
							    <div class="col">
							      <input type="date" name="end_date" id="end_date" class="form-control datepicker-autoclose" placeholder="Please select end date">
							    </div>
							    <div class="col">
							      <button type="text" id="btnFiterSubmitSearch" class="btn btn-info">Submit</button>
							    </div>
							   	
							</div> -->
							<!-- <br> -->
							<table class="table table-bordered table-hover nowrap" id="dataTableBuilder">
								<thead>
									<tr>
										<!-- <th><input type="checkbox" class='checkall' id='checkall'>
											<input type="button" class="btn btn-sm btn-danger" id='delete_record' value='Delete' >
										</th> -->
										<th>ID</th>
										<th>IMAGE</th>
										<th>NAME</th>
										<th>EMAIL</th>										
										<th>CONTACT</th>										
										<th>IDENTITY</th>										
										<th>DOB</th>										
										<th>JOINING</th>										
										<th>BLOOD</th>										
										<th>EMP TYPE</th>										
										<th>CREATED AT</th>										
										<th>ACTION</th>										
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
	<!-- <script src="https://cdn.datatables.net/fixedheader/3.2.3/css/fixedHeader.dataTables.min.css"></script> -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

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
					// autoWidth: true,
					// scrollX: true,
					scrollY: "400px",
					ajax: {
						url: "{{ route('employee.index') }}",
					  	type: 'GET',
					  	data: function (d) {
					  		d.start_date = $('#start_date').val();
					  		d.end_date = $('#end_date').val();
					  	}
					},
					columns: [
			           	// {data: 'action', name: 'Action', orderable: false, searchable: false},
						{data:'id', name: 'ID'},
						{data:'avatar', name: 'IMAGE', orderable: false, searchable: false},
						{data:'name', name: 'NAME'},
						{data:'email', name: 'EMAIL'},						
						{data:'mobile', name: 'CONTACT'},						
						{data:'identity_document', name: 'IDENTITY'},						
						{data:'dob', name: 'DOB'},						
						{data:'doj', name: 'JOINING'},						
						{data:'blood_group', name: 'BLOOD'},						
						{data:'emp_type', name: 'EMP TYPE'},						
						{data:'created_at_formatted', name: 'CREATED AT'},
						// {data:'updated_at_formatted', name: 'updated_at'},
						{data:'action_button', name: 'ACTION', orderable: false, searchable: false},
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