@extends('layouts.new_layout')
@push('page_css')
<style>
   thead input.top-filter {
   width: 100%;
   }
   .table {
	border-spacing: 0 0.85rem !important;
  }
  
  .table .dropdown {
	display: inline-block;
  }
  
  .table td,
  .table th {
	vertical-align: middle;
	margin-bottom: 10px;
	border: none;
  }
  
  .table thead tr,
  .table thead th {
	border: none;
	font-size: 12px;
	text-transform: uppercase;
	background: transparent;
	color: #64748B;
  }
  
  .table td {
	background: #f7f9fc !important;
  }
  
  .table td:first-child {
	border-top-left-radius: 10px;
	border-bottom-left-radius: 10px;
  }
  
  .table td:last-child {
	border-top-right-radius: 10px;
	border-bottom-right-radius: 10px;
  }
  
  .avatar {
	width: 2.75rem;
	height: 2.75rem;
	line-height: 3rem;
	border-radius: 50%;
	display: inline-block;
	background: transparent;
	position: relative;
	text-align: center;
	color: #868e96;
	font-weight: 700;
	vertical-align: bottom;
	font-size: 1rem;
	-webkit-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
  }
  
  .avatar-sm {
	width: 2.5rem;
	height: 2.5rem;
	font-size: 0.83333rem;
	line-height: 1.5;
  }
  
  .avatar-img {
	width: 100%;
	height: 100%;
	-o-object-fit: cover;
	object-fit: cover;
  }
  
  .avatar-blue {
	background-color: #c8d9f1;
	color: #467fcf;
  }
  
  table.dataTable.dtr-inline.collapsed
	> tbody
	> tr[role="row"]
	> td:first-child:before,
  table.dataTable.dtr-inline.collapsed
	> tbody
	> tr[role="row"]
	> th:first-child:before {
	top: 28px;
	left: 14px;
	border: none;
	box-shadow: none;
  }
  
  table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > td:first-child,
  table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > th:first-child {
	padding-left: 48px;
  }
  
  table.dataTable > tbody > tr.child ul.dtr-details {
	width: 100%;
  }
  
  table.dataTable > tbody > tr.child span.dtr-title {
	min-width: 50%;
  }
  
  table.dataTable.dtr-inline.collapsed > tbody > tr > td.child,
  table.dataTable.dtr-inline.collapsed > tbody > tr > th.child,
  table.dataTable.dtr-inline.collapsed > tbody > tr > td.dataTables_empty {
	padding: 0.75rem 1rem 0.125rem;
  }
  
  div.dataTables_wrapper div.dataTables_length label,
  div.dataTables_wrapper div.dataTables_filter label {
	margin-bottom: 0;
  }
  
  @media (max-width: 767px) {
	div.dataTables_wrapper div.dataTables_paginate ul.pagination {
	  -ms-flex-pack: center !important;
	  justify-content: center !important;
	  margin-top: 1rem;
	}
  }
  
  .btn-icon {
	background: #fff;
  }
  .btn-icon .bx {
	font-size: 20px;
  }
  
  .btn .bx {
	vertical-align: middle;
	font-size: 20px;
  }
  
  .dropdown-menu {
	padding: 0.25rem 0;
  }
  
  .dropdown-item {
	padding: 0.5rem 1rem;
  }

  
  .table a:hover,
  .table a:focus {
	text-decoration: none;
  }
  
  table.dataTable {
	margin-top: 12px !important;
	border-collapse: separate !important;
  }
  
  .icon > .bx {
	display: block;
	min-width: 1.5em;
	min-height: 1.5em;
	text-align: center;
	font-size: 1.0625rem;
  }

  
  .avatar-blue {
		background-color: #c8d9f1;
		color: #467fcf;
	  }
  
	  .avatar-pink {
		background-color: #fcd3e1;
		color: #f66d9b;
	  }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
@endpush
@section('content')
<div>
   <div class="page-heading d-flex justify-content-between align-items-center gap-3 mb-4">
		<div>
			<h3>People</h3>
			<p class="mb-0">Track and manage your employees here</p>
		</div>
		<div>
			<button class="d-flex justify-content-center gap-2 primary-add ">
				<x-heroicon-o-plus width="16" />
				<span>Add Employee</span>
			</button>
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
   <div class="bg-white table-custom">
	   <table id="example" class="table table-hover responsive nowrap" style="width:100%">
		 <thead>
		   <tr>
			 <th>NAME</th>
			 <th>Social Security</th>
			 <th>Medical Benefit</th>
			 <th>Position</th>
			 <th>Start Date</th>
			 <th>Start Date</th>
			 <th>Actions</th>
		   </tr>
		 </thead>
		 <tbody>
		   <tr>
			 <td>
				 <div class="d-flex align-items-center">
				   	<div class="avatar avatar-pink mr-3">JR</div>
					<div class="ms-3">
						<p class="font-weight-bold mb-0">Julie Richards</p>
						<p class="text-muted mb-0">julie_89@example.com</p>
					</div>
				 </div>
			 </td>
			 <td> (937) 874 6878</td>
			 <td>Investment Banker</td>
			 <td>13/01/1989</td>
			 <td>13/01/1989</td>
			 <td>
			   <div class="badge badge-success badge-success-alt">Enabled</div>
			 </td>
			 <td>
			   <div class="btn-group">
				   <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;"><path d="M12 10c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm6 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zM6 10c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"></path></svg>
				   </button>
				   <ul class="dropdown-menu dropdown-menu-end">
					 <li><button class="dropdown-item" type="button">Action</button></li>
					 <li><button class="dropdown-item" type="button">Another action</button></li>
					 <li><button class="dropdown-item" type="button">Something else here</button></li>
				   </ul>
				 </div>
			 </td>
		   </tr>
		 </tbody>
	   </table>
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
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
<script src="{{ asset('js/dataTables.buttons.min.js') }}"></script>
<script>
	$(document).ready(function() {
		$("#example").DataTable({
		  aaSorting: [],
		  responsive: true,
	  
		  columnDefs: [
			{
			  responsivePriority: 1,
			  targets: 0
			},
			{
			  responsivePriority: 2,
			  targets: -1
			}
		  ]
		});
	  
		$(".dataTables_filter input")
		  .attr("placeholder", "Search here...")
		  .css({
			width: "300px",
			display: "inline-block"
		  });
	  
		$('[data-toggle="tooltip"]').tooltip();
	  });
	  
</script>
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
   							var avatar = `<img src='/files/${row.file}' class='table-user-thumb'>`;
   						} else {
   							var avatar = "<img src='/img/user2-160x160.jpg' width='65' height='65' class='table-user-thumb'>";
   						}
   	                	return avatar;
   	                }
   				},
   				{
   					//data:'name', 
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