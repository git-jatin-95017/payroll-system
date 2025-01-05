<div>
	<div class="sb-menu-h">
		<span>Menu</span>
	</div>
	<ul>
		@if(auth()->user()->role_id == 2)
		<li class="active">
			<a href="{{ route('client.dashboard') }}">
				<x-bxs-home-alt-2 class="w-20 h-20" />
				<span>Dashboard</span>
			</a>
		</li>
		<li>
			<a href="{{ route('employee.index') }}">
				<x-heroicon-s-users class="w-20 h-20" />
				<span>People</span>
			</a>
		</li>
		<li>
			<a href="{{ route('holidays.index') }}">
				<x-bxs-plane-take-off class="w-20 h-20"/>
				<span>Holidays</span>
			</a>
		</li>
		<li>
			<a href="{{ route('leave-type.index') }}">
				<x-bxs-file-doc class="w-20 h-20"/>
				<span>Leave</span>
			</a>
		</li>
		<li>
			<a href="#">
				<x-bxs-time class="w-20 h-20"/>
				<span>Time</span>
			</a>
		</li>
		<li>
			<a href="#">
				<x-bxs-dollar-circle class="w-20 h-20"/>
				<span>Payroll </span>
			</a>
		</li>
		<li class="has-sub">
			<a href="#">
				<x-bxs-user class="w-20 h-20"/>
				<span>Company</span>
			</a>
			<ul>
				<li><a href="#">Profile</a></li>
				<li><a href="#">Pay Labels</a></li>
				<li><a href="#">Leave Polices</a></li>
			</ul>
		</li>
		@endif
		
	</ul>
</div>
<div class="bottom-nav mt-auto">
	<ul>
		<li>
			<a href="#">
				<x-bxs-user class="w-20 h-20"/>
				<span>Logout</span>
			</a>
		</li>
	</ul>
</div>