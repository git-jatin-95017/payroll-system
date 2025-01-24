@extends('layouts.new_layout')
@push('page_css')
	
@endpush
@section('content')
<div>
	<div class="page-heading d-flex justify-content-between align-items-center gap-3 mb-3">
		<div>
			<h3>List of clients</h3>
			<p class="mb-0">Track and manage your clients here</p>
		</div>
	</div>
	<div class="d-flex gap-3 align-items-center justify-content-between mb-4">
		<form method="GET" action="{{ route('client.index') }}" class="d-flex gap-3 align-items-center justify-content-between">
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
			<form action="{{ route('client.create') }}" method="GET" class="m-0 p-0">
				<button type="submit" class="d-flex justify-content-center gap-2 primary-add">
					<x-heroicon-o-plus width="16" />
					<span>Add Client</span>
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
						<!-- <th><input type="checkbox" class='checkall' id='checkall'>
							<input type="button" class="btn btn-sm btn-danger" id='delete_record' value='Delete' >
						</th> -->
						<th>Id</th>
						<th>Name</th>
						<th>Email</th>										
						<th>Created At</th>
						<th>Updated At</th>
						<th>Login As Client</th>
						<th>Action</th>																		
					</tr>
				</thead>
				<tbody>
					@forelse ($clients as $row)
					@php $id = $row->id  @endphp
						<tr>
							<td>{{$id}}</td>
							<td>{{ $row->name }}</td>
							<td>{{ $row->email }}</td>
							<td>{{ $row->created_at }}</td>
							<td>{{ $row->updated_at }}</td>
							<td>
								<form action=" {{route('login-as-client')}}" id="form-{{$id}}" method='post'>
									{{csrf_field()}}
									<input type='hidden' name='user_id' value="{{$id}}">
									<button type='submit' class='btn-primary btn'>
										Login
									</button>
								</form>
							</td>
							<td>
								<div class="dropdown">
									<button class="btn action-dropdown-toggle dropdown-toggle" type="button" id="dropdownMenuButton{$id}" data-bs-toggle="dropdown" aria-expanded="false">
										<x-bx-dots-horizontal-rounded class="w-20 h-20" />
									</button>
									<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{$id}">
										<li>
											<a href="{{ route('client.edit', $id) }}" class="dropdown-item">
												<x-bx-edit-alt class="w-16 h-16" /> Edit
											</a>
										</li>
										<li>
											<a href="javascript:void(0);" data-href="{{ route('client.destroy', $id) }}" class="dropdown-item delete" style="color:#dc3545;">
												<x-heroicon-o-trash class="w-16 h-16" /> Delete
											</a>
										</li>
									</ul>
								</div>
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="7" class="text-center">No client found.</td>
						</tr>
					@endforelse

					</tr>
				</tbody>
			</table>
		</div>
		{{ $clients->links('vendor.pagination.custom') }}
   </div>
</div> 
@endsection

@push('page_scripts')
	<!-- <script src="https://cdn.datatables.net/fixedheader/3.2.3/css/fixedHeader.dataTables.min.css"></script> -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
	<!-- <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>
	<script src="{{ asset('js/dataTables.responsive.min.js') }}"></script>
	<script src="{{ asset('js/dataTables.buttons.min.js') }}"></script>	 -->
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
					autoWidth: true,
					scrollX: true,
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
						{data:'action_button2', name: 'Action', orderable: false, searchable: false},
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
					  		location.reload();
							//$('#dataTableBuilder').DataTable().draw(true);		           
					  	}
					});
				  }
				});
			}
	</script>		
@endpush