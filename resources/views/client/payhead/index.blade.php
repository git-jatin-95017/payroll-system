@extends('layouts.new_layout')
@push('page_css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
@endpush
@section('content')
<div>
    <div class="page-heading d-flex justify-content-between align-items-center gap-3 mb-3">
		<div>
			<h3>Pay Heads</h3>
			<p class="mb-0">Track and manage your pay heads here</p>
		</div>
    </div>
    <div class="d-flex gap-3 align-items-center justify-content-between mb-4">
        <form method="GET" action="{{ route('pay-head.index') }}" class="d-flex gap-3 align-items-center justify-content-between mb-4">
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

		@if(auth()->user()->role_id == 2)
        <div>
            <form action="{{ route('pay-head.create') }}" method="GET" class="m-0 p-0">
                <button type="submit" class="d-flex justify-content-center gap-2 primary-add">
                    <x-heroicon-o-plus width="16" />
                    <span>Add Pay Head</span>
                </button>
            </form>
        </div>
		@endif

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
						<th>Head Name</th>										
						<th>Head Description</th>										
						<th>Head Type</th>										
						<th>Action</th>	
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payheads as $row)
						@php $id = $row->id @endphp
                        <tr>
                            <!-- <td>{{ $row->id }} -->
							</td>
                            <td>{!! $row->name  !!}</td>
							<td>{!! $row->description  !!}</td>
							<td>
								@if ($row->pay_type == 'nothing')
									{{'Addition to Net Pay'}}
								@elseif ($row->pay_type == 'deductions')
									{{'Deduction to Net Pay'}}
								@elseif ($row->pay_type == 'earnings') 
									{{'Addition to Gross Pay'}}
								@endif
							</td>
                            <td>
								<div class="dropdown">
                                    <button class="btn action-dropdown-toggle dropdown-toggle" type="button" id="dropdownMenuButton{$id}" data-bs-toggle="dropdown" aria-expanded="false">
                                        <x-bx-dots-horizontal-rounded class="w-20 h-20" />
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{$id}">
                                        <li>
                                            <a href="/client/pay-head/{{$id}}/edit" class="dropdown-item">
                                                <x-bx-edit-alt class="w-16 h-16" /> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" data-href="/client/pay-head/{{$id}}" class="dropdown-item delete" style="color:#dc3545;">
                                                <x-heroicon-o-trash class="w-16 h-16" /> Delete
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">No record found</td>
                        </tr>
                    @endforelse
                    </tr>
                </tbody>
            </table>
        </div>
        {{ $payheads->links('vendor.pagination.custom') }}
   </div>
</div>
@endsection
@push('page_css')
	<link rel="stylesheet" href="{{ asset('js/datepicker/datepicker3.css') }}">
@endpush

@push('page_scripts')
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
	<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script> -->
	<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
	<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
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
			                		action += ` <a href="/client/pay-head/${id}/edit" class="btn btn-sm btn-primary"><i class='fas fa-pen'></i></a>`;
			                		action += ` <a data-href="/client/pay-head/${id}" class="btn btn-sm btn-danger delete" style="color:#fff;"><i class='fas fa-trash'></i></a>`;			
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