<!-- need to remove -->
<li>
	<a href="{{ route('dashboard') }}" class=" {{ Request::is('dashboard') ? '' : '' }}">
		<i class="mdi mdi-speedometer"></i>
		<span class="hide-menu">Dashboard</span>
	</a>

	@if(auth()->user()->role_id == 3)
		<li>
			<a href="{{ route('my-leaves.index') }}">
				<i class="mdi mdi-calendar"></i>
				<span class="hide-menu">
					My Leaves
				</span>
			</a>
		</li>
		<li>
        	<a href="{{ route('holidays.index') }}">
				<i class="mdi mdi-airplane-takeoff"></i> 
				<span class="hide-menu">
					Holidays 
				</span>
			</a>
    	</li>

    	<li>
			<a href="{{ route('my.payslip') }}">
				<i class="mdi mdi-account"></i>
				<span class="hide-menu">
					Pay Slip
				</span>
			</a>
		</li>

	@endif

	@if(auth()->user()->role_id == 3)
		<li>
			<a href="{{ route('emp-my-profile.edit', auth()->user()->id) }}">
				<i class="mdi mdi-account"></i>
				<span class="hide-menu">
					Profile
				</span>
			</a>
		</li>
	@else
		<li>
			<a href="{{ route('edit-my-profile.edit', auth()->user()->id) }}">
				<i class="mdi mdi-account"></i>
				<span class="hide-menu">
					Profile
				</span>
			</a>
		</li>
	@endif
	<li>
		<a href="/logout">
			<i class="mdi mdi-power"></i>
			<span class="hide-menu">
				Logout
			</span>
		</a>		
	</li>
</li>
