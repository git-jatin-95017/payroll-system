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
				<div class="alert alert-success alert-dismissible py-2 d-flex justify-content-between align-items-center px-3">
					<p class="mb-0">{{ session('message') }}</p>
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				</div>
			</div>
		</div>
	@elseif (session('error'))
		<div class="row">
			<div class="col-md-12">
				<div class="alert alert-danger alert-dismissible py-2 d-flex justify-content-between align-items-center px-3">
					<p class="mb-0">{{ session('error') }}</p>
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				</div>
			</div>
		</div>
	@endif
	<div class="bg-white p-4 border-radius-15">
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
						<td>
							<a href="javascript:void(0);" id="atag-{{$k}}" onblur="toggleInput(this, '{{$k}}');">
								<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="#888da8">
									<path d="M19.045 7.401c.378-.378.586-.88.586-1.414s-.208-1.036-.586-1.414l-1.586-1.586c-.378-.378-.88-.586-1.414-.586s-1.036.208-1.413.585L4 13.585V18h4.413L19.045 7.401zm-3-3 1.587 1.585-1.59 1.584-1.586-1.585 1.589-1.584zM6 16v-1.585l7.04-7.018 1.586 1.586L7.587 16H6zm-2 4h16v2H4z"></path>
								</svg>
							</a>
						</td>
						<td>
							<input type="text" name="payroll_name" data-id="{{$result[0]['appoval_number']}}" class="payroll_name input-sm form-control db-custom-input" value="{{$result[0]['payroll_name']}}" id="input-{{$k}}" onblur="saveData(this, '<?php echo $k; ?>')" readonly>
						</td>
						<td class="">
							{{ $startDate}} - {{$endDate}}
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