@extends('layouts.app')

@section('content')
<div class="row page-titles">
	<div class="col-md-5 align-self-center">
		<h3 class="text-themecolor">
			<i class="fa fa-braille" style="color:#1976d2"></i>
			Run Payroll
		</h3>
	</div>
	<div class="col-md-7 align-self-center">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="javascript:void(0)">Home</a>
			</li>
			<li class="breadcrumb-item active">Pending Payroll</li>
			<li class="breadcrumb-item active">/</li>
		</ol>
	</div>
</div>
<section class="content">
	<div class="container-fluid">		
		<div class="tab-content" id="myTabContent">
			<div class="container-fluid">
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
				<div class="col-sm-12">	
					<div class="card">			
						<div class="card-body">
							<table class="table custom-table-run">
								<thead>
									<tr>
									  <th scope="col">No.</th>								      
									  <th scope="col">Name</th>								      
									  <th scope="col">Payroll Period</th>								      
									  <th scope="col">Status</th>
									  <th scope="col">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php $i = 1; ?>
									@foreach($results as $k =>$result)
									<?php
										$arr = explode(' - ', $result[0]['date_range']);

										$startDate = date('Y-m-d', strtotime($arr[0]));

										$endDate = date('Y-m-d', strtotime($arr[1]));
									?>
									<tr class="row-tr-js tr-main">									      
										<td>{{ $i }}</td>
										<td>
											<input type="text" name="payroll_name" data-id="{{$result[0]['appoval_number']}}" class="payroll_name input-sm form-control" value="{{$result[0]['payroll_name']}}">
										</td>
										<td class="col-sm-3">
											<b>{{ $startDate}} - {{$endDate}}</b>
										</td>
										<td class="col-sm-3">
											Timecard Approved
										</td>
										<td class="col-sm-3">
											<a href="{{ route('list.step1', [
											'start_date' => $startDate, 
											'end_date' => $endDate, 
											'number' => $result[0]['appoval_number']]) }}">Click here to process</a>
										</td>
									</tr>			
									<?php $i++; ?>					
									@endforeach	    
								</tbody>
							</table>
						</div>				
					</div>
				</div>
			</div>
			</div>
		</div>
		</div>
</section>
@endsection
@push('page_scripts')
	<script>
		$(".payroll_name").blur(function() {
			if ($(this).val() != '' || $(this).val() != null) {
				$.ajax({
					url: "{{ route('save.name.payroll') }}",
					type: 'POST',
					data: {
						_token: "{{ csrf_token() }}", 
						key: $(this).data('id'), 
						name: $(this).val(), 
					},
					dataType: 'JSON',
					success: function (data) {
						// alert('Record Saved Successfully.');
					}
				});
			}
		});
	</script>
@endpush