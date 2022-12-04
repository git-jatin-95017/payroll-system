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
            Pay Head
        </h3>
    </div>

    <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="javascript:void(0)">Home</a>
            </li>
            <li class="breadcrumb-item active">Pay Head</li>
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
				<div class="col-sm-4">
					<div class="card">
						<div class="card-header">
							<h3 class="card-title">Pay Head</h3>
						</div>
						<form class="form-horizontal" method="POST" action="{{ route('pay-head.store') }}">
							@csrf
							<div class="card-body">								
								<div class="form-group">
									<label for="name" class="col-md-8 control-label">Pay Head Name</label>
									<div class="col-md-12">
										<input id="name" type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name', '') }}">

										@if ($errors->has('name'))
											<span class="text-danger">
												{{ $errors->first('name') }}
											</span>
										@endif
									</div>
								</div>
								<div class="form-group">
									<label for="description" class="col-md-8 control-label">Pay Head Descrition</label>
									<div class="col-md-12">
										<input id="description" type="text" class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" value="{{ old('description', '') }}">

										@if ($errors->has('description'))
											<span class="text-danger">
												{{ $errors->first('description') }}
											</span>
										@endif
									</div>
								</div>
								<div class="form-group">
									<label for="pay_type" class="col-md-8 control-label">Pay Head Descrition</label>
									<div class="col-md-12">
										<select class="form-control" id="pay_type" name="pay_type">
											<option @if(old('pay_type', '') == "earnings") selected @endif value="earnings">Earnings</option>
											<option @if(old('pay_type', '') == "deductions") selected @endif value="deductions">Deductions</option>
										</select>
										@if ($errors->has('pay_type'))
											<span class="text-danger">
												{{ $errors->first('pay_type') }}
											</span>
										@endif
									</div>
								</div>								
							</div>
							<div class="card-footer">
								<button type="submit" class="btn btn-primary">Save</button>
							</div>
						</form>
					</div>
				</div>		
				<div class="col-8">					
					<div class="card">
						<div class="card-header">
							<h3 class="card-title">Pay Head</h3>
							<div class="card-tools">
								<div class="input-group input-group-sm">
									<!-- <a href="{{ route('pay-head.create' )}}" class="btn btn-primary">Add New</a> -->
								</div>
							</div>
						</div>					
						<div class="card-body">							
							<table class="table table-bordered table-hover wrap" id="dataTableBuilder">
								<thead>
									<tr>										
										<th>ID</th>
										<th>Head Name</th>										
										<th>Head Description</th>										
										<th>Head Type</th>										
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
	<script type="text/javascript">
		//single record move to delete
		$(document).on('click','a.delete',function(){
		    var trashRecordUrl = $(this).data('href');
		    console.log(trashRecordUrl);
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

		  	var table = $('#dataTableBuilder').DataTable({
					processing: true,
					serverSide: true,
					autoWidth: false,
					// autoWidth: true,
					// scrollX: true,
					scrollY: "400px",
					ajax: {
						url: "{{route('pay-head.getData')}}",
					  	type: 'GET',
					  	data: function (d) {
					  		d.start_date = $('#start_date').val();
					  		d.end_date = $('#end_date').val();
					  	}
					},
					columns: [
						{data:'id'},						
						{
							data:'name', 
							// orderable: true
						},
						{
							data:'description', 
							// orderable: true
						},
						{
							data:'pay_type', 
							// orderable: true
						},
						{
			                data: 'actions',
			                orderable : false,
			                searchable : false,
			                render: function(data, type, row, meta) {
			                	
			                	var id = row.id;
			                
			                	var action = `<div class="table-actions">`;
			                		//action += `<a data-href="/client/pay-head/${id}" class="btn btn-sm btn-info approve"><i class='fas fa-pen'></i></a>`;
			                		action += ` <a data-href="/client/pay-head/${id}" class="btn btn-sm btn-primary delete"><i class='fas fa-trash'></i></a>`;			
			                		action += `</div>`;
			                	return action;
			                }
			            }
			        ],
			        orderCellsTop: true,
        			// fixedHeader: true,
			       
			  	});
		   });		  	

	</script>		
@endpush