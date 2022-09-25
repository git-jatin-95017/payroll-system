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
					<h1 class="m-0">Clients</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="#">Home</a></li>
						<li class="breadcrumb-item active">List of clients</li>
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
							<h3 class="card-title">List of clients</h3>
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
										<th>Id</th>
										<th>Name</th>
										<th>Email</th>										
										<th>Created At</th>
										<th>Updated At</th>
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
						url: "{{ route('client.index') }}",
					  	type: 'GET',
					  	data: function (d) {
					  		d.start_date = $('#start_date').val();
					  		d.end_date = $('#end_date').val();
					  	}
					},
					columns: [
			           	// {data: 'action', name: 'Action', orderable: false, searchable: false},
						{data:'id', name: 'id'},
						{data:'name', name: 'Name'},
						{data:'email', name: 'Email'},						
						{data:'created_at_formatted', name: 'created_at'},
						{data:'updated_at_formatted', name: 'updated_at'},
						{data:'action_button', name: 'Action', orderable: false, searchable: false},
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