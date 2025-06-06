<div>
	<div class="sb-menu-h pb-3">
		<span>Menu</span>
	</div>
	<ul class="list-unstyled main-navigation">
		<li class="{{ Request::routeIs('client.dashboard') ? 'active' : '' }}">
			<a href="{{ route('client.dashboard') }}">
				<x-bxs-home-alt-2 class="w-20 h-20" />
				<span>Dashboard</span>
			</a>
		</li>
		<li class="{{ Request::routeIs('department.index', 'department.create', 'department.edit') ? 'active' : '' }}">
			<a href="{{ route('department.index') }}">
				<x-heroicon-s-users class="w-20 h-20" />
				<span>Locations</span>
			</a>
		</li>
		<li class="{{ Request::routeIs('employee.index', 'employee.create', 'employee.edit') ? 'active' : '' }}">
			<a href="{{ route('employee.index') }}">
				<x-heroicon-s-users class="w-20 h-20" />
				<span>People</span>
			</a>
		</li>
		<li class="{{ Request::routeIs('holidays.index', 'holidays.create', 'holidays.edit') ? 'active' : '' }}">
			<a href="{{ route('holidays.index') }}">
				<x-bxs-plane-take-off class="w-20 h-20"/>
				<span>Holidays</span>
			</a>
		</li>
		<li class="{{ request()->is('client/leaves*') || request()->is('client/edit-leave/*') ? 'active' : '' }}">
			<a href="{{ route('leaves.index') }}">
				<x-bxs-file-doc class="w-20 h-20"/>
				<span>Leave</span>
			</a>
		</li>
		<li class="{{ request()->is('client/attendance*') ? 'active' : '' }}">
			<a href="{{ route('attendance.index') }}">
				<x-bxs-time class="w-20 h-20"/>
				<span>Attendance</span>
			</a>
		</li>
		<li class="{{ Request::routeIs('payroll.create', 'payroll.create', 'payroll.edit') ? 'active' : '' }}">
			<a href="{{ route('payroll.create', ['week_search' => 2]) }}">
				<x-bxs-time class="w-20 h-20"/>
				<span>Time</span>
			</a>
		</li>
		<li class="{{ Request::routeIs('list.payroll') ? 'active' : '' }}">
			<a href="{{ route('list.payroll') }}">
				<x-bxs-dollar-circle class="w-20 h-20"/>
				<span>Payroll </span>
			</a>
		</li>
		<li class="{{ Request::routeIs('payroll.reports.*') ? 'active' : '' }}">
			<a href="{{ route('payroll.reports.employee-earnings') }}">
				<x-bxs-file class="w-20 h-20"/>
				<span>Reports</span>
			</a>
			<ul class="list-unstyled sub-menu">
				<!--<li><a href="{{ route('payroll.reports.employee-earnings') }}"><x-bxs-plane-take-off class="w-20 h-20"/><span>Employee Earnings</span></a></li>
				<li><a href="{{ route('payroll.reports.employer-payments') }}"><x-bxs-user class="w-20 h-20"/><span>Employer Payments</span></a></li>
				 <li><a href="{{ route('payroll.reports.taxes') }}">Taxes</a></li> -->
				<!-- <li><a href="{{ route('payroll.reports.summary') }}">Summary</a></li> -->
			</ul>
		</li>
		<li class="{{ Request::routeIs('notice.index', 'notice.create', 'notice.edit') ? 'active' : '' }}">
			<a href="{{ route('notice.index') }}">
				<x-bxs-plane-take-off class="w-20 h-20"/>
				<span>Notices</span>
			</a>
		</li>
		<li class="has-sub {{ Request::routeIs('my-profile.edit', 'pay-head.index', 'pay-head.create', 'pay-head.edit', 'leave-type.index', 'leave-type.create', 'leave-type.edit') ? 'active' : '' }}">
			<a href="#">
				<x-bxs-user class="w-20 h-20"/>
				<span>Company</span>
			</a>
			<ul class="list-unstyled">
				<li><a href="{{ route('my-profile.edit', auth()->user()->id) }}">Profile</a></li>
				<li><a href="{{ route('pay-head.index') }}">Pay Labels</a></li>
				<li><a href="{{ route('leave-type.index') }}">Leave Polices</a></li>
			</ul>
		</li>
		
	</ul>
</div>
<div class="bottom-nav auto">
	<ul>
		<li>
			<a href="/logout">
				<x-bxs-user class="w-20 h-20"/>
				<span>Logout</span>
			</a>
		</li>
	</ul>
</div>

<!-- <div>
	<div class="sb-menu-h pt-5 pb-4">
		<span>Menu</span>
	</div>
	<ul>
		<li class="active">
			<a href="{{ route('client.dashboard') }}">
				<x-bxs-home-alt-2 class="w-20 h-20" />
				<span>Dashboard</span>
			</a>
		</li>
        <li>
			<a href="{{ route('department.index') }}">
				<x-heroicon-s-users class="w-20 h-20" />
				<span>Locations</span>
			</a>
		</li>
        <li>
			<a href="{{ route('leave-type.index') }}">
				<x-bxs-file-doc class="w-20 h-20"/>
				<span>Leave Policies</span>
			</a>
		</li>
        <li>
			<a href="{{ route('pay-head.index') }}">
				<x-bxs-plane-take-off class="w-20 h-20"/>
				<span>Pay Heads</span>
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
			<a href="{{ route('leaves.index') }}">
				<x-bxs-file-doc class="w-20 h-20"/>
				<span>Leaves</span>
			</a>
		</li>
		<li>
			<a href="{{ route('attendance.index') }}">
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
				<span>Salaries</span>
			</a>
			<ul>
				<li><a href="{{ route('payroll.create', ['week_search' => 2]) }}">Time Card</a></li>
				<li><a href="{{ route('list.payroll') }}">Run Payroll</a></li>
			</ul>
		</li>
        <li>
			<a href="{{ route('my-profile.edit', auth()->user()->id) }}">
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
</div> -->