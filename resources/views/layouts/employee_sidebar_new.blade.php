<div>
	<div class="sb-menu-h">
		<span>Employee Menu</span>
	</div>
	<ul>
		<li class="{{ Request::routeIs('dashboard') ? 'active' : '' }}">
			<a href="{{ route('dashboard') }}">
				<x-bxs-home-alt-2 class="w-20 h-20" />
				<span>Dashboard</span>
			</a>
		</li>
		<li class="{{ request()->is('employee/my-leaves*') ? 'active' : '' }}">
			<a href="{{ route('my-leaves.index') }}">
				<x-bxs-file-doc class="w-20 h-20"/>
				<span>Leaves</span>
			</a>
		</li>
		<li class="{{ request()->is('employee/my-schedules*') ? 'active' : '' }}">
			<a href="{{ route('my-schedules') }}">
				<x-bxs-calendar class="w-20 h-20"/>
				<span>Schedules</span>
			</a>
		</li>
		<li class="{{ request()->is('employee/holidays*') ? 'active' : '' }}">
			<a href="{{ route('holidays.index') }}">
				<x-bxs-plane-take-off class="w-20 h-20"/>
				<span>Holidays</span>
			</a>
		</li>
		<li class="{{ request()->is('employee/my-payslip*') ? 'active' : '' }}">
			<a href="{{ route('my.payslip') }}">
				<x-heroicon-s-users class="w-20 h-20" />
				<span>Payslips</span>
			</a>
		</li>
		<li class="{{ request()->is('client/notice*') ? 'active' : '' }}">
			<a href="{{ route('notice.index') }}">
				<x-bxs-plane-take-off class="w-20 h-20"/>
				<span>Notices</span>
			</a>
		</li>
		<li class="{{ request()->is('employee/emp-my-profile*') ? 'active' : '' }}">
			<a href="{{ route('emp-my-profile.edit', auth()->user()->id) }}">
				<x-bxs-file-doc class="w-20 h-20"/>
				<span>Profile</span>
			</a>
		</li>
	</ul>
</div>
<div class="bottom-nav mt-auto">
	<ul>
		<li>
			<a href="/logout">
				<x-bxs-user class="w-20 h-20"/>
				<span>Logout</span>
			</a>
		</li>
	</ul>
</div>