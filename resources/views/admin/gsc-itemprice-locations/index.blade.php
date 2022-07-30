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
					<h1 class="m-0">ERD - GS ITem Price Locations</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="#">Home</a></li>
						<li class="breadcrumb-item active">GS ITem Price Locations</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					@if(session('status'))
						<div class="alert alert-success">
							{{ session('status') }}
						</div>
					@endif
					<div class="card">
						<div class="card-header">
							<h3 class="card-title">ERD - GS ITem Price Locations</h3>
						</div>
						<div class="card-body">
							<div class="row">
							    <div class="col">
							      <input type="date" name="start_date" id="start_date" class="form-control datepicker-autoclose" placeholder="Please select start date">
							    </div>
							    <div class="col">
							      <input type="date" name="end_date" id="end_date" class="form-control datepicker-autoclose" placeholder="Please select end date">
							    </div>
							    <div class="col">
							      <button type="text" id="btnFiterSubmitSearch" class="btn btn-info">Submit</button>
							    </div>
							</div>
							<br>							
							<table class="table table-bordered table-hover nowrap" id="dataTableBuilder">
								<thead>
									<tr>
										<th>Check All <input type="checkbox" class='checkall' id='checkall'>
											<input type="button" class="btn btn-sm btn-danger" id='delete_record' value='Delete' >
										</th>
										<th>Id</th>
										<th>Location Codes</th>
										<th>Item Codes</th>
										<th>Price Level</th>
										<th>Price</th>
										<th>Currency</th>										
										<th>Price Date</th>
										<th>Created At</th>
										<th>Updated At</th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<th>Action</th>
										<th>Id</th>
										<th>Location Codes</th>
										<th>Item Codes</th>
										<th>Price Level</th>
										<th>Price</th>
										<th>Currency</th>										
										<th>Price Date</th>
										<th>Created At</th>
										<th>Updated At</th>
									</tr>
								</tfoot>
							</table>
						</div>
					  </div>
				</div>
			</div>
		</div>
	</section>    
@endsection

@push('page_scripts')
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

	<script>
		$('#dataTableBuilder').on('click', '.btn-delete[data-remote]', function (e) { 
			e.preventDefault();		     
			var url = $(this).data('remote');
			// confirm then
			if (confirm('Are you sure you want to delete this?')) {
				$.ajax({
					url: url,
					type: 'DELETE',
					dataType: 'json',
					data: {method: '_DELETE', submit: true, "_token": "{{ csrf_token() }}"}
				}).always(function (data) {
					$('#dataTableBuilder').DataTable().draw(true);
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
				$('#dataTableBuilder thead tr')
			        .clone(true)
			        .addClass('filters')
			        .appendTo('#dataTableBuilder thead');
			// }, 1000);

		  	var table = $('#dataTableBuilder').DataTable({
					processing: true,
					serverSide: true,
					// autoWidth: true,
					// scrollX: true,
					scrollY: "400px",
					ajax: {
						url: "{{ route('gsc-itemprice-locations.index') }}",
					  	type: 'GET',
					  	data: function (d) {
					  		d.start_date = $('#start_date').val();
					  		d.end_date = $('#end_date').val();
					  	}
					},
					columns: [
			           	{data: 'action', name: 'Action', orderable: false, searchable: false},
						{data:'id', name: 'id'},
						{data:'location_codes', name: 'location_codes'},
						{data:'item_codes', name: 'item_codes'},
						{data:'price_level', name: 'price_level'},
						{data:'price', name: 'price'},
						{data:'currency', name: 'currency'},						
						{data:'price_date', name: 'price_date'},							
						{data:'created_at', name: 'created_at'},
						{data:'updated_at', name: 'updated_at'},
			        ],
			        orderCellsTop: true,
        			// fixedHeader: true,
			        initComplete: function () {
			            var api = this.api();			 
			            api
			                .columns()
			                .eq(0)
			                .each(function (colIdx) {
			                    // Set the header cell to contain the input element
			                    var cell = $('.filters th').eq(
			                        $(api.column(colIdx).header()).index()
			                    );
			                    var title = $(cell).text();

			                    var hiddenClass = "";
			                  	if (title.trim() == 'Check All' || title == 'Id') {
			                  		hiddenClass = "d-none";
			                  	}
 			                    $(cell).html('<input class="top-filter '+hiddenClass+'" type="text" placeholder="' + title + '" />');
			 
			                    // On every keypress in this input
			                    $(
			                        'input',
			                        $('.filters th').eq($(api.column(colIdx).header()).index())
			                    )
			                        .off('keyup change')
			                        .on('change', function (e) {
			                            // Get the search value
			                            $(this).attr('title', $(this).val());
			                            var regexr = '({search})'; //$(this).parents('th').find('select').val();
			 
			                            var cursorPosition = this.selectionStart;
			                            // Search the column for that value
			                            api
			                                .column(colIdx)
			                                .search(
			                                    this.value
			                                )
			                                .draw();
			                        })
			                        .on('keyup', function (e) {
			                            e.stopPropagation();
			 
			                            $(this).trigger('change');
			                            // $(this)
			                            //     .focus()[0]
			                            //     .setSelectionRange(cursorPosition, cursorPosition);
			                        });
			                });
			        },
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

		    // Delete record
		   $('#delete_record').click(function(){

		      var delete_ids = [];
		      // Read all checked checkboxes
		      $("input:checkbox[class=delete_check]:checked").each(function () {
		         delete_ids.push($(this).val());
		      });

		      // Check checkbox checked or not
		      if(delete_ids.length > 0){

		         // Confirm alert
		         var confirmdelete = confirm("Do you really want to Delete records?");

		         if (confirmdelete == true) {
		            $.ajax({
		               url: "{{ route('gsc-itemprice-locations.multi-delete') }}",
		               type: 'POST',
		               data: {is_delete_request:true, ids: delete_ids, "_token": "{{ csrf_token() }}"},
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
