<aside class = "left-sidebar" > <div class="scroll-sidebar">
    <div class="user-profile my-4">
        <div class="profile-img">
            <img src="{{asset('img/user-new.svg') }}"></div>
            <div class="profile-text">
                <h5>{{ ucwords(Auth::user()->name) }}</h5>
                <a
                    href="#"
                    class="dropdown-toggle u-dropdown"
                    role="button"
                    aria-haspopup="true"
                    aria-expanded="true">
                    <i class="mdi mdi-settings"></i>
                </a>
                <a href="#" class="" data-toggle="tooltip" title="Logout">
                    <i class="mdi mdi-power"></i>
                </a>
            </div>
        </div>

        <div class="sidebar custom-sidebar">

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
		<div class="px-3">
			@if ($attendanceROW < 2)
			<form method="POST" class="employee sidebar-form" role="form" id="attendance-form">
				@csrf
				<div class="from-group text-center">
					<input class="form-control form-control-sidebar" type="text" name="emp_desc" placeholder="Comment (if any)" aria-label="emp_desc">
					<div class="input-group-append mt-2">
						<button class="btn btn-sidebar bg-primary w-100 text-white" id="action_btn">
							{{$action_name}}
						</button>
					</div>
				</div>
			</form>
			@endif
		</div>
		@endif
		<nav class="sidebar-nav">
			<ul id="sidebarnav">
			<li class="nav-devider"></li>
				@include('layouts.menu_employee')
			</ul>
		</nav>
	</div>
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