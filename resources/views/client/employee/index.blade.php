@extends('layouts.new_layout')
@push('page_css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
@endpush
@section('content')
<div>
    <div class="page-heading d-flex justify-content-between align-items-center gap-3 mb-3">
		<div>
			<h3>People</h3>
			<p class="mb-0">Track and manage your employees here</p>
		</div>
    </div>
    <div class="d-flex gap-3 align-items-center justify-content-between mb-4">
        <form method="GET" action="{{ route('employee.index') }}" class="d-flex gap-3 align-items-center justify-content-between mb-4">
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
            <form action="{{ route('employee.create') }}" method="GET" class="m-0 p-0">
                <button type="submit" class="d-flex justify-content-center gap-2 primary-add">
                    <x-heroicon-o-plus width="16" />
                    <span>Add Employee</span>
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
                        <th>Employee Photo</th>
                        <th>Employee Name</th>
                        <th>Contact Number</th>
                        <th>Social Security</th>
                        <th>Medical Benefit</th>
                        <th>Job Title</th>
                        <th>Start Date</th>
                        <th>Pay</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($employees as $employee)
                        <tr>
                            <td>
                                <div class="people-img">
                                    <?php
                                        if (!empty($employee->employeeProfile->file)) {
                                            $file = 'files/'. $employee->employeeProfile->file;
                                        } else {
                                            $file = '/img/user2-160x160.jpg';
                                        }

                                        $empId = $employee->id;
                                        
                                    ?>
                                    <img src="{{ asset($file) }}" alt="people" width='70' height='70'>
                                </div>
                            </td>
                            <td>{{ $employee->name }}</td>
                            <td>{{ $employee->phone_number }}</td>
                            <td>{{ $employee->pan_number }}</td>
                            <td>{{ $employee->ifsc_code }}</td>
                            <td>{{ $employee->designation }}</td>
                            <td>{{ $employee->start_date }}</td>
                            <td>${{ $employee->pay_rate }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn action-dropdown-toggle dropdown-toggle" type="button" id="dropdownMenuButton{$empId}" data-bs-toggle="dropdown" aria-expanded="false">
                                        <x-bx-dots-horizontal-rounded class="w-20 h-20" />
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{$empId}">
                                        <li>
                                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ManageModal" class="dropdown-item" onclick="updateEmpCode({$empId})">
                                                <x-bx-dollar-circle class="w-16 h-16" /> Assign Pay Head
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#LeavePolicyModal" class="dropdown-item" onclick="updateLeaveAssign({$empId})">
                                                <x-bx-user-check class="w-16 h-16" /> Assign Leave Policy
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#LocationModal" class="dropdown-item" onclick="updateLocationAssign({$empId})">
                                                <x-bx-map-alt class="w-16 h-16" /> Assign Location
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('employee.edit', $employee->id) }}" class="dropdown-item">
                                                <x-bx-edit-alt class="w-16 h-16" /> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" data-href="{{ route('employee.destroy', $employee->id) }}" class="dropdown-item delete" style="color:#dc3545;">
                                                <x-heroicon-o-trash class="w-16 h-16" /> Delete
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">No employees found</td>
                        </tr>
                    @endforelse
                    
                    </tr>
                </tbody>
            </table>
        </div>
        {{ $employees->links('vendor.pagination.custom') }}
   </div>
</div>
<div class="modal fade" id="ManageModal" tabindex="-1" role="dialog">
   <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title text-center">Add Payheads to Employee</h4>
         </div>
         <form method="post" role="form" data-toggle="validator" id="assign-payhead-form">
            @csrf
            <div class="modal-body">
               <div class="row">
                  <div class="col-sm-6">
                     <label for="all_payheads">List of Pay Heads</label>
                     <button type="button" id="selectHeads" class="btn btn-success btn-xs pull-right"><i class="fa fa-arrow-circle-right"></i></button>
                     <select class="form-control" id="all_payheads" name="all_payheads[]" multiple size="10">
                        @foreach($payheadList as $k => $v)
                        <option value="{{$v->id}}" class="{{$v->pay_type=='earnings'?'text-success':'text-danger'}}">{{$v->name}}</option>
                        @endforeach
                     </select>
                  </div>
                  <div class="col-sm-6">
                     <label for="selected_payheads">Selected Pay Heads</label>
                     <button type="button" id="removeHeads" class="btn btn-danger btn-xs pull-right"><i class="fa fa-arrow-circle-left"></i></button>
                     <select class="form-control" id="selected_payheads" name="selected_payheads[]" data-error="Pay Heads is required" multiple size="10" required></select>
                  </div>
                  <!-- <div class="col-sm-4">
                     <label for="selected_payamount">Enter Payhead Amount</label>
                     <div id="selected_payamount"></div>
                     </div> -->
               </div>
            </div>
            <div class="modal-footer">
               <input type="hidden" name="empcode" id="empcode" />
               <button type="submit" name="submit" class="btn btn-primary">Add Pay Heads to Employee</button>
            </div>
         </form>
      </div>
   </div>
</div>
<div class="modal fade" id="LeavePolicyModal" tabindex="-1" role="dialog">
   <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title text-center">Add Leave Policy to Employee</h4>
         </div>
         <form method="post" role="form" data-toggle="validator" id="assign-leave-policy-form">
            @csrf
            <div class="modal-body">
               <div class="row">
                  <div class="col-sm-6">
                     <label for="all_leave_policies">List of Leave Policies</label>
                     <button type="button" id="selectHeadsLeavePolicy" class="btn btn-success btn-xs pull-right"><i class="fa fa-arrow-circle-right"></i></button>
                     <select class="form-control" id="all_leave_policies" name="all_leave_policies[]" multiple size="10">
                        @foreach($leavePolicies as $k => $v)
                        <option value="{{$v->id}}" class="">{{$v->name}}</option>
                        @endforeach
                     </select>
                  </div>
                  <div class="col-sm-6">
                     <label for="selected_leave_policies">Selected Leave Policy</label>
                     <button type="button" id="removeHeadsLeavePolicy" class="btn btn-danger btn-xs pull-right"><i class="fa fa-arrow-circle-left"></i></button>
                     <select class="form-control" id="selected_leave_policies" name="selected_leave_policies[]" data-error="Leave Policy is required" multiple size="10" required></select>
                  </div>
                  <!-- <div class="col-sm-4">
                     <label for="selected_payamount">Enter Payhead Amount</label>
                     <div id="selected_payamount"></div>
                     </div> -->
               </div>
            </div>
            <div class="modal-footer">
               <input type="hidden" name="empcodepolicy" id="empcodepolicy" />
               <button type="submit" name="submit" class="btn btn-primary">Add Leave Policy to Employee</button>
            </div>
         </form>
      </div>
   </div>
</div>
<div class="modal fade" id="LocationModal" tabindex="-1" role="dialog">
   <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title text-center">Add Location to Employee</h4>
         </div>
         <form method="post" role="form" data-toggle="validator" id="assign-location-form">
            @csrf
            <div class="modal-body">
               <div class="row">
                  <div class="col-sm-6">
                     <label for="all_locations">List of Locations</label>
                     <button type="button" id="selectHeadsLocation" class="btn btn-success btn-xs pull-right"><i class="fa fa-arrow-circle-right"></i></button>
                     <select class="form-control" id="all_locations" name="all_locations[]" multiple size="10">
                        @foreach($locations as $k => $v)
                        <option value="{{$v->id}}" class="">{{$v->dep_name}}</option>
                        @endforeach
                     </select>
                  </div>
                  <div class="col-sm-6">
                     <label for="selected_locations">Selected Locations</label>
                     <button type="button" id="removeHeadsLocation" class="btn btn-danger btn-xs pull-right"><i class="fa fa-arrow-circle-left"></i></button>
                     <select class="form-control" id="selected_locations" name="selected_locations[]" data-error="Location is required" multiple size="10" required></select>
                  </div>
                  <!-- <div class="col-sm-4">
                     <label for="selected_payamount">Enter Payhead Amount</label>
                     <div id="selected_payamount"></div>
                     </div> -->
               </div>
            </div>
            <div class="modal-footer">
               <input type="hidden" name="empcodelocation" id="empcodelocation" />
               <button type="submit" name="submit" class="btn btn-primary">Add Location to Employee</button>
            </div>
         </form>
      </div>
   </div>
</div>
@endsection
@push('page_scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
<!-- <script src="{{ asset('js/dataTables.buttons.min.js') }}"></script> -->
<script>
	function updateLocationAssign(empId) {
        $(document).find('#empcodelocation').val(empId);
        $.ajax({
            type     : "GET",
            dataType : "json",
            async    : true,
            cache    : false,
            url      : "{{ route('assigned.locations') }}",
            data     : 'emp_code=' + empId,
            success  : function(result) {
                $('#selected_locations').html('');
                $('#all_locations').html('');
                console.log(result.result,result.code);
                if ( result.code == 0 ) {
                	for ( var j in result.alllocations ) {
	                    $('#all_locations').append($("<option></option>")
	                        .attr({
	                            "value": result.alllocations[j].id,
	                            // "selected": "selected"
	                        })
	                        .text(
	                            result.alllocations[j].dep_name
	                        )
	                    );
	                }

                    for ( var i in result.result ) {
                        $('#selected_locations').append($("<option></option>")
                            .attr({
                                "value": result.result[i].department_id,
                                "selected": "selected"
                            })
                            .text(
                                result.result[i].dep_name
                            )
                        );
                        /*s
                        $('#selected_payamount').append($("<input />")
                            .attr({
                                "type": "text",
                                "name": "pay_amounts[" + result.result[i].payhead_id + "]",
                                "id": "pay_amounts_" + result.result[i].payhead_id,
                                "placeholder": result.result[i].name,
                                "value": result.result[i].default_salary
                            })
                            .addClass('form-control')
                        );
                        */
                    }
                }
            }
        });
    }
    /* Assign Payhead to Employee Form Submit Script Start */
    if ( $('#assign-location-form').length > 0 ) {
        $('#assign-location-form').on('submit', function(e) {
            e.preventDefault();

            var form = $(this);
            $.ajax({
                type     : "POST",
                dataType : "json",
                async    : true,
                cache    : false,
                url      : "{{ route('assign.locations') }}",
                data     : form.serialize(),
                success  : function(result) {
                    if ( result.code == 0 ) {
                        $('#LocationModal').modal('hide');
                        $.notify({
                            icon: 'glyphicon glyphicon-ok-circle',
                            message: result.result,
                        },{
                            allow_dismiss: false,
                            type: "success",
                            placement: {
                                from: "top",
                                align: "right"
                            },
                            z_index: 9999,
                        });                        
                    } else {
                        $.notify({
                            icon: 'glyphicon glyphicon-remove-circle',
                            message: result.result,
                        },{
                            allow_dismiss: false,
                            type: "danger",
                            placement: {
                                from: "top",
                                align: "right"
                            },
                            z_index: 9999,
                        });
                    }
                }
            });
        });
    }
    /* End of Script */

        function moveItemsLocations(origin, dest) {
            $(origin).find(':selected').appendTo(dest);
        }
         /* Add Payhead To Employee Script Start */
        $(document).on('click', '#selectHeadsLocation', function() {
            $('#all_locations').find(':selected').each(function() {
                var val = $(this).val();
                var name = $(this).text();
                $('#selected_locations').append($("<input />")
                    .attr({
                        "type": "text",
                        "name": "pay_amounts[" + val + "]",
                        "id": "pay_amounts_" + val,
                        "placeholder": name
                    })
                    .addClass('form-control')
                );
            });
            moveItemsLocations('#all_locations', '#selected_locations');
        });

        /* Manage Modal Close Script Start */
            if ( $('#LocationModal').length > 0 ) {
                $('#LocationModal').on('hidden.bs.modal', function () {
                    $("#empcodelocation").val('');
                    $('#selected_locations').html('');
                });
            }
        /* End of Script */
        $(document).on('click', '#removeHeadsLocation', function() {
            $('#selected_locations').find(':selected').each(function() {
                var val = $(this).val();
                $('#pay_amounts_' + val).remove();
            });
            moveItemsLocations('#selected_locations', '#all_locations');
        });
        /* End of Script */     
</script>
	<script>
	function updateLeaveAssign(empId) {
	    $(document).find('#empcodepolicy').val(empId);
	    $.ajax({
	        type     : "GET",
	        dataType : "json",
	        async    : true,
	        cache    : false,
	        url      : "{{ route('assigned.leave.policies') }}",
	        data     : 'emp_code=' + empId,
	        success  : function(result) {
	            $('#selected_leave_policies').html('');
	            $('#all_leave_policies').html('');
	            
	            console.log(result.result,result.code, result.leavePolicies);
	            
	            if ( result.code == 0 ) {
	            	for ( var j in result.leavePolicies ) {
	                    $('#all_leave_policies').append($("<option></option>")
	                        .attr({
	                            "value": result.leavePolicies[j].id,
	                            // "selected": "selected"
	                        })
	                        .text(
	                            result.leavePolicies[j].name
	                        )
	                    );
	                }
	                for ( var i in result.result ) {
	                    $('#selected_leave_policies').append($("<option></option>")
	                        .attr({
	                            "value": result.result[i].leave_type_id,
	                            "selected": "selected"
	                        })
	                        .text(
	                            result.result[i].name
	                        )
	                    );
	                    /*s
	                    $('#selected_payamount').append($("<input />")
	                        .attr({
	                            "type": "text",
	                            "name": "pay_amounts[" + result.result[i].payhead_id + "]",
	                            "id": "pay_amounts_" + result.result[i].payhead_id,
	                            "placeholder": result.result[i].name,
	                            "value": result.result[i].default_salary
	                        })
	                        .addClass('form-control')
	                    );
	                    */
	                }
	            }
	        }
	    });
	}
	/* Assign Payhead to Employee Form Submit Script Start */
    if ( $('#assign-leave-policy-form').length > 0 ) {
        $('#assign-leave-policy-form').on('submit', function(e) {
            e.preventDefault();

            var form = $(this);
            $.ajax({
                type     : "POST",
                dataType : "json",
                async    : true,
                cache    : false,
                url      : "{{ route('assign.leave.policies') }}",
                data     : form.serialize(),
                success  : function(result) {
                    if ( result.code == 0 ) {
                    	$('#LeavePolicyModal').modal('hide');
                        $.notify({
                            icon: 'glyphicon glyphicon-ok-circle',
                            message: result.result,
                        },{
                            allow_dismiss: false,
                            type: "success",
                            placement: {
                                from: "top",
                                align: "right"
                            },
                            z_index: 9999,
                        });                        
                    } else {
                        $.notify({
                            icon: 'glyphicon glyphicon-remove-circle',
                            message: result.result,
                        },{
                            allow_dismiss: false,
                            type: "danger",
                            placement: {
                                from: "top",
                                align: "right"
                            },
                            z_index: 9999,
                        });
                    }
                }
            });
        });
    }
    /* End of Script */

		function moveItemsLeavePolicy(origin, dest) {
		    $(origin).find(':selected').appendTo(dest);
		}

		/* Add Payhead To Employee Script Start */
        $(document).on('click', '#selectHeadsLeavePolicy', function() {
            $('#all_leave_policies').find(':selected').each(function() {
                var val = $(this).val();
                var name = $(this).text();
                $('#selected_payamount').append($("<input />")
                    .attr({
                        "type": "text",
                        "name": "pay_amounts[" + val + "]",
                        "id": "pay_amounts_" + val,
                        "placeholder": name
                    })
                    .addClass('form-control')
                );
            });
            moveItemsLeavePolicy('#all_leave_policies', '#selected_leave_policies');
        });

        /* Manage Modal Close Script Start */
		    if ( $('#LeavePolicyModal').length > 0 ) {
		        $('#LeavePolicyModal').on('hidden.bs.modal', function () {
		            $("#empcodepolicy").val('');
		            $('#selected_leave_policies').html('');
		        });
		    }
		/* End of Script */
        $(document).on('click', '#removeHeadsLeavePolicy', function() {
            $('#selected_leave_policies').find(':selected').each(function() {
                var val = $(this).val();
                $('#pay_amounts_' + val).remove();
            });
            moveItemsLeavePolicy('#selected_leave_policies', '#all_leave_policies');
        });
        /* End of Script */		
</script>

	<script>
	/* Assign Payhead to Employee Form Submit Script Start */
    if ( $('#assign-payhead-form').length > 0 ) {
        $('#assign-payhead-form').on('submit', function(e) {
            e.preventDefault();

            var form = $(this);
            $.ajax({
                type     : "POST",
                dataType : "json",
                async    : true,
                cache    : false,
                url      : "{{ route('assign.payhead') }}",
                data     : form.serialize(),
                success  : function(result) {
                    if ( result.code == 0 ) {
                    	$('#ManageModal').modal('hide');
                        $.notify({
                            icon: 'glyphicon glyphicon-ok-circle',
                            message: result.result,
                        },{
                            allow_dismiss: false,
                            type: "success",
                            placement: {
                                from: "top",
                                align: "right"
                            },
                            z_index: 9999,
                        });                        
                    } else {
                        $.notify({
                            icon: 'glyphicon glyphicon-remove-circle',
                            message: result.result,
                        },{
                            allow_dismiss: false,
                            type: "danger",
                            placement: {
                                from: "top",
                                align: "right"
                            },
                            z_index: 9999,
                        });
                    }
                }
            });
        });
    }
    /* End of Script */

		function moveItems(origin, dest) {
		    $(origin).find(':selected').appendTo(dest);
		}
		 /* Add Payhead To Employee Script Start */
        $(document).on('click', '#selectHeads', function() {
            $('#all_payheads').find(':selected').each(function() {
                var val = $(this).val();
                var name = $(this).text();
                $('#selected_payamount').append($("<input />")
                    .attr({
                        "type": "text",
                        "name": "pay_amounts[" + val + "]",
                        "id": "pay_amounts_" + val,
                        "placeholder": name
                    })
                    .addClass('form-control')
                );
            });
            moveItems('#all_payheads', '#selected_payheads');
        });

        /* Manage Modal Close Script Start */
		    if ( $('#ManageModal').length > 0 ) {
		        $('#ManageModal').on('hidden.bs.modal', function () {
		            $("#empcode").val('');
		            $('#selected_payheads').html('');
		        });
		    }
		/* End of Script */
        $(document).on('click', '#removeHeads', function() {
            $('#selected_payheads').find(':selected').each(function() {
                var val = $(this).val();
                $('#pay_amounts_' + val).remove();
            });
            moveItems('#selected_payheads', '#all_payheads');
        });
        /* End of Script */
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
		          	success:(result)=> {
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

			// setTimeout(function() {
				// $('#dataTableBuilder thead tr')
			 //        .clone(true)
			 //        .addClass('filters')
			 //        .appendTo('#dataTableBuilder thead');
			// }, 1000);

		  	var table = $('#dataTableBuilder').DataTable({
					processing: true,
					serverSide: true,
					autoWidth: true,
					scrollX: true,
					scrollY: "400px",
					ajax: {
						url: "{{route('employee.getData')}}",
					  	type: 'GET',
					  	data: function (d) {
					  		d.start_date = $('#start_date').val();
					  		d.end_date = $('#end_date').val();
					  	}
					},
					columns: [
			           	// {data: 'action', name: 'Action', orderable: false, searchable: false},
						// {data:'user_code'},
						{
							data:'file', 
							orderable: false, 
							searchable: false,
							render: function(data, type, row, meta) {
			                	if(row.file) {
									var avatar = `<img src='/files/${row.file}' width='65' height='65' class='table-user-thumb'>`;
								} else {
									var avatar = "<img src='/img/user2-160x160.jpg' width='65' height='65' class='table-user-thumb'>";
								}
			                	return avatar;
			                }
						},
						{
							data:'name', 
							// orderable: true
						},
						{data:'phone_number'},						
						{
                            data: 'pan_number',
                            render: function (data, type, row, meta) {
                                return `<span class="text-primary">${data}</span>`;
                            }
                        },						
						{data:'ifsc_code'},
                        {
                            data: 'start_date',
                            render: function (data, type, row, meta) {
                                return `
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1.75 12C1.75 13.2426 2.75736 14.25 4 14.25H10.2485C10.5513 14.25 10.8438 14.1401 11.0717 13.9407L13.8231 11.5332C14.0944 11.2958 14.25 10.9529 14.25 10.5925V4C14.25 2.75736 13.2426 1.75 12 1.75H4C2.75736 1.75 1.75 2.75736 1.75 4V12Z" stroke="#5E5ADB" stroke-width="1.5"/>
                                    <path d="M5.25 6.5H10.75" stroke="#5E5ADB" stroke-width="1.5" stroke-linecap="round"/>
                                    <path d="M5.25 9.5H8.75" stroke="#5E5ADB" stroke-width="1.5" stroke-linecap="round"/>
                                    </svg><span class="ms-2">${data}</span>`;
                                }
                        },						
						{data:'designation'},						
						{data:'pay_rate'},
						{
			                data: 'actions',
			                orderable : false,
			                searchable : false,
			                render: function(data, type, row, meta) {
			                	var viewRoute = '{{ route("employee.show", ":id") }}';
			                	viewRoute = viewRoute.replace(':id', row.id);
			                	var editRoute = '{{ route("employee.edit", ":id") }}';
			                	editRoute = editRoute.replace(':id', row.id);
			                	var destrRoute = '{{ route("employee.destroy", ":id") }}';
			                	destrRoute = destrRoute.replace(':id', row.id);
			                	var action = `
                                    <div class="dropdown">
                                        <button class="btn action-dropdown-toggle dropdown-toggle" type="button" id="dropdownMenuButton${row.id}" data-bs-toggle="dropdown" aria-expanded="false">
                                            <x-bx-dots-horizontal-rounded class="w-20 h-20" />
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton${row.id}">
                                            <li>
                                                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ManageModal" class="dropdown-item" onclick="updateEmpCode(${row.id})">
                                                    <x-bx-dollar-circle class="w-16 h-16" /> Assign Pay Head
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#LeavePolicyModal" class="dropdown-item" onclick="updateLeaveAssign(${row.id})">
                                                    <x-bx-user-check class="w-16 h-16" /> Assign Leave Policy
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#LocationModal" class="dropdown-item" onclick="updateLocationAssign(${row.id})">
                                                    <x-bx-map-alt class="w-16 h-16" /> Assign Location
                                                </a>
                                            </li>
                                            <li>
                                                <a href="${editRoute}" class="dropdown-item">
                                                    <x-bx-edit-alt class="w-16 h-16" /> Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a data-href="${destrRoute}" class="dropdown-item delete" style="color:#dc3545;">
                                                     <x-heroicon-o-trash class="w-16 h-16" /> Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>`;
			                	return action;
			                }
			            }
			        ],
			        orderCellsTop: true,
        			// fixedHeader: true,
			       
			  	});
		   	});
		 	
		 	function jsUcfirst(string) {
			    return string.charAt(0).toUpperCase() + string.slice(1);
			}

		 	function updateEmpCode(empId) {
		 		$(document).find('#empcode').val(empId);
		 		$.ajax({
	                type     : "GET",
	                dataType : "json",
	                async    : true,
	                cache    : false,
	                url      : "{{ route('assigned.payhead') }}",
	                data     : 'emp_code=' + empId,
	                success  : function(result) {
	                    $('#selected_payheads').html('');
	                    $('#all_payheads').html('');
	                    console.log(result.result,result.code);
	                    if ( result.code == 0 ) {
	                    	for ( var j in result.payheads ) {
			                    $('#all_payheads').append($("<option></option>")
			                        .attr({
			                            "value": result.payheads[j].id,
			                            // "selected": "selected"
			                        })
			                        .text(
			                            result.payheads[j].name
			                        )
			                    );
			                }
	                        for ( var i in result.result ) {
	                            $('#selected_payheads').append($("<option></option>")
	                                .attr({
	                                    "value": result.result[i].payhead_id,
	                                    "selected": "selected"
	                                })
	                                .text(
	                                    result.result[i].name + ' (' + jsUcfirst(result.result[i].pay_type) + ')'
	                                )
	                                .addClass((result.result[i].pay_type=='earnings'?'text-success':'text-danger'))
	                            );
	                            /*s
	                            $('#selected_payamount').append($("<input />")
	                                .attr({
	                                    "type": "text",
	                                    "name": "pay_amounts[" + result.result[i].payhead_id + "]",
	                                    "id": "pay_amounts_" + result.result[i].payhead_id,
	                                    "placeholder": result.result[i].name,
	                                    "value": result.result[i].default_salary
	                                })
	                                .addClass('form-control')
	                            );
	                            */
	                        }
	                    }
	                }
	            });
		 	}

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