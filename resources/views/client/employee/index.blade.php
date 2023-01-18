@extends('layouts.app')
@push('page_css')
	<style>
		thead input.top-filter {
	        width: 100%;
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
            Employees
        </h3>
    </div>

    <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="javascript:void(0)">Home</a>
            </li>
            <li class="breadcrumb-item active">List Of Employees</li>
        </ol>
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
						<div class="card-header d-flex justify-content-between">
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
							<table class="table table-bordered table-hover nowrap" style="width:100%" id="dataTableBuilder">
								<thead>
									<tr>
										<!-- <th><input type="checkbox" class='checkall' id='checkall'>
											<input type="button" class="btn btn-sm btn-danger" id='delete_record' value='Delete' >
										</th> -->
										<!-- <th>Id</th> -->
										<th>Image</th>
										<th>Name</th>
										<th>Contact</th>										
										<th>Social Security</th>										
										<th>Medical Benefit</th>										
										<th>Start Date</th>										
										<th>Position</th>										
										<th>Pay Rate</th>										
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
	<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>
	<script src="{{ asset('js/dataTables.responsive.min.js') }}"></script>
	<script src="{{ asset('js/dataTables.buttons.min.js') }}"></script>
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
                console.log(result.result,result.code);
                if ( result.code == 0 ) {
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
	            console.log(result.result,result.code);
	            if ( result.code == 0 ) {
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
						{data:'pan_number'},						
						{data:'ifsc_code'},						
						{data:'start_date'},						
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
			                	var action = `<div class="table-actions">`;

			                	action += "<a href='javascript:void(0);' data-toggle='modal' data-target='#ManageModal' title='Assign Pay Head' class='btn btn-sm btn-info' onclick='updateEmpCode("+row.id+")'><i class='mdi mdi-credit-card'></i></a>";

			                	action += " <a href='javascript:void(0);' data-toggle='modal' data-target='#LeavePolicyModal' title='Assign Leave Policy' class='btn btn-sm btn-success' onclick='updateLeaveAssign("+row.id+")'><i class='mdi mdi-file-outline'></i></a>";

			                	action += " <a href='javascript:void(0);' data-toggle='modal' data-target='#LocationModal' title='Assign Location' class='btn btn-sm btn-warning' onclick='updateLocationAssign("+row.id+")'><i class='mdi mdi-map-marker'></i></a>";

			                	action += " <a href=" + editRoute + " class='btn btn-sm btn-primary' title='Edit'><i class='fas fa-pen'></i></a>";

			                	action += " <a data-href=" + destrRoute + " class='btn btn-sm btn-danger delete' style='color:#fff;'  title='Delete'><i class='fas fa-trash'></i></a>";

			                	action += `</div>`;

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
	                    console.log(result.result,result.code);
	                    if ( result.code == 0 ) {
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