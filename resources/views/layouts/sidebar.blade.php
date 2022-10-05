<aside class="main-sidebar sidebar-dark-primary elevation-4">
	<a href="{{ route('dashboard') }}" class="brand-link">
		<img src="/img/logo-side.jpg"
			 alt="Logo" style="width: 235px; object-fit: unset;margin: 0;" 
			 class="brand-image elevation-3">
		<span class="brand-text font-weight-light">&nbsp;</span>
	</a>

	<div class="sidebar">
		<div class="user-panel mt-3 pb-3 mb-3 d-flex">
			<div class="image">
				<img src="/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
			</div>
			<div class="info">
				<a href="#" class="d-block">
					{{ ucwords(Auth::user()->name) }}
				</a>
			</div>
		</div>
		@if(auth()->user()->role_id == 3)
		<?php
			$attendanceData = App\Models\Attendance::where('emp_code', auth()->user()->user_code)->whereDate('attendance_date', date('Y-m-d'))->get();
			
			if (!$attendanceData->isEmpty()) {
				$attendanceROW = $attendanceData->count();
				if ( $attendanceROW == 0 ) {
					$action_name = 'Punch In';
				} else {
					$attendanceDATA = $attendanceData->first();
					if ($attendanceDATA->action_name == 'punchin' ) {
						$action_name = 'Punch Out';
					} else {
						$action_name = 'Punch In';
					}
				}
			} else {
				$attendanceROW = 0;
				$action_name = 'Punch In';
			} 
		?>

		<div class="form-inline">
			@if ($attendanceROW < 2)
			<form method="POST" class="employee sidebar-form" role="form" id="attendance-form">
				@csrf
				<div class="input-group">
					<input class="form-control form-control-sidebar" type="text" name="emp_desc" placeholder="Comment (if any)" aria-label="emp_desc">
					<div class="input-group-append">
						<button class="btn btn-sidebar bg-warning" id="action_btn">
							{{$action_name}}
						</button>
					</div>				
				</div>
			</form>
			@endif
		</div>
		@endif
		<nav class="mt-2">
			<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
				@include('layouts.menu')
			</ul>
		</nav>
	</div>

</aside>

@push('page_scripts')
	<script>
		/* Attendance Form Submit Script Start */
		if ( $('#attendance-form').length > 0 ) {
			$(document).on('submit', '#attendance-form', function(e) {
				e.preventDefault();
				
				var form = $(this);
				$.ajax({
					type     : "POST",
					dataType : "json",
					async    : true,
					cache    : false,
					url      : "{{ route('punch.store') }}",
					data     : form.serialize(),
					success  : function(data) {
						result = data.result;
						if ( result.code == 0 ) {
							form[0].reset();
							$('#action_btn').text(result.next);
							if ( result.complete == 2 ) {
								form.remove();
							}
							$.notify(result.result, "success");
						} else {
							$.notify(result.result, 'error');
						}
					}
				});
			});
		}
		/* End of Script */
	</script>
@endpush