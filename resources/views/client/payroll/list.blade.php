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

										$empIds = collect($result)->pluck('emp_id');

										$isGreen = \App\Models\PayrollAmount::where('start_date', '>=', $startDate)->where('end_date', '<=', $endDate)->where('status', 1)->whereIn('user_id', $empIds)->count();
									?>
									<tr class="row-tr-js tr-main">									      
										<td>{{ $i }}</td>
										<td>
											<a href="javascript:void(0);" id="atag-{{$k}}" onblur="toggleInput(this, '{{$k}}');">Edit</a>
											<input type="text" name="payroll_name" data-id="{{$result[0]['appoval_number']}}" class="payroll_name input-sm form-control" value="{{$result[0]['payroll_name']}}" id="input-{{$k}}" onblur="saveData(this, '<?php echo $k; ?>')" readonly>
										</td>
										<td class="col-sm-3">
											<b>{{ $startDate}} - {{$endDate}}</b>
										</td>
										<td class="col-sm-3">
											@if($isGreen) <span class="badge badge-sm badge-success">Timecard Approved</span> @else Timecard Approved @endif
										</td>
										<td class="col-sm-3">
											@if(!$isGreen)
											<a href="{{ route('list.step1', [
											'start_date' => $startDate, 
											'end_date' => $endDate, 
											'number' => $result[0]['appoval_number']]) }}">Click here to process</a>
											@endif

											<a href="{{ route('delete.payroll',  ['appoval_number' => $result[0]['appoval_number'] ]) }}"class="btn btn-sm btn-danger" onclick="return confirm('Are you sure? Once you delete the payroll then you have to approve the timesheet hours again for the same date range and employees.')" style="color:#fff;" title="Delete"><i class='fa fa-trash'></i></a>
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
		function toggleInput(obj, index) {
			console.log(obj);
			$('#input-'+index).attr('readonly', false);
		}

		function saveData(obj, index) {
			if ($(obj).val() != '' || $(obj).val() != null) {
				$.ajax({
					url: "{{ route('save.name.payroll') }}",
					type: 'POST',
					data: {
						_token: "{{ csrf_token() }}", 
						key: $(obj).data('id'), 
						name: $(obj).val(), 
					},
					dataType: 'JSON',
					success: function (data) {
						// alert('Record Saved Successfully.');
						$('#input-'+index).attr('readonly', true);
					}
				});
			}
		}
	</script>
@endpush