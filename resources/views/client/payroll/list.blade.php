@extends('layouts.new_layout')

@section('content')
<div>
    <div class="page-heading d-flex justify-content-between align-items-center gap-3 mb-3">
		<div>
			<h3>Run Payroll</h3>
			<p class="mb-0">Track and manage your Pending payroll here</p>
		</div>
    </div>
    <div class="d-flex gap-3 align-items-center justify-content-between mb-4">
   </div>
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
	<div class="bg-white p-4">
        <div class="table-responsive">
            <table class="table db-custom-table">
                <thead>
					<tr>
						<th scope="col">No.</th>	
						<th scope="col"></th>								      
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
						<td><small><a href="javascript:void(0);" id="atag-{{$k}}" onblur="toggleInput(this, '{{$k}}');">Edit</a></small></td>
						<td>
							<input type="text" name="payroll_name" data-id="{{$result[0]['appoval_number']}}" class="payroll_name input-sm form-control" value="{{$result[0]['payroll_name']}}" id="input-{{$k}}" onblur="saveData(this, '<?php echo $k; ?>')" readonly>
						</td>
						<td class="">
							<b>{{ $startDate}} - {{$endDate}}</b>
						</td>
						<td class="">
							@if($isGreen) <span class="badge bg-success">Approved</span> @else Timecard Approved @endif
						</td>
						<td>
							<div class="dropdown">
								<button class="btn action-dropdown-toggle dropdown-toggle" type="button" id="dropdownMenuButton{$k}" data-bs-toggle="dropdown" aria-expanded="false">
									<x-bx-dots-horizontal-rounded class="w-20 h-20" />
								</button>
								<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{$k}">
									@if($isGreen)
										<li>
											<a href="{{ route('payroll.confirmation', [
												'start_date' => $startDate, 
												'end_date' => $endDate, 
												'appoval_number' => $result[0]['appoval_number'],
												'is_green' => true
											]) }}" class="dropdown-item">
											<x-bx-user-check class="w-16 h-16" /> Submitted
											</a>
										</li>
									@else
										<li>
											<a href="{{ route('list.step1', [
												'start_date' => $startDate, 
												'end_date' => $endDate, 
												'number' => $result[0]['appoval_number']]) }}" class="dropdown-item"> 
												<x-bx-edit-alt class="w-16 h-16" /> Process
											</a>
										</li>
									@endif
									<li>
										<a href="{{ route('delete.payroll',  ['appoval_number' => $result[0]['appoval_number'] ]) }}" class="dropdown-item" onclick="return confirm('Are you sure? Once you delete the payroll then you have to approve the timesheet hours again for the same date range and employees.')" title="Delete" style="color:#dc3545;"><x-heroicon-o-trash class="w-16 h-16" /> Delete</a>
									</li>
								</ul>
							</div>
						</td>
					</tr>			
					<?php $i++; ?>					
					@endforeach	    
				</tbody>
            </table>
        </div>
   </div>
</div>
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