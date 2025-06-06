@if(auth()->user()->role_id == 1)
		<li>
			<a href="{{ route('admin.dashboard') }}" class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}">
				<i class="mdi mdi-speedometer"></i>
				<span class="hide-menu">Dashboard</span>
			</a>
		</li>
		<li>
			<a href="#" href="#" class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">
				<i class="nav-icon fas fa-user"></i>
				<span class="hide-menu">
					Clients
				</span>
			</a>
			<ul aria-expanded="false" class="collapse ">
				<li>
					<a href="{{ route('client.create') }}">
						<span class="hide-menu">Add New Client</span>
					</a>
				</li>
				<li>
					<a href="{{ route('client.index') }}">
						<span class="hide-menu">List Of Client</span>
					</a>
				</li>
			</ul>
		</li>
		<li>
			<a href="{{ route('settings.create', auth()->user()->id) }}">
				<i class="mdi mdi-settings"></i>
				<span class="hide-menu">
					Settings
				</span>
			</a>
		</li>
	@endif

	@if(auth()->user()->role_id == 2)
		<li>
			<a href="{{ route('client.dashboard') }}" class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}">
				<i class="mdi mdi-speedometer"></i>
				<span class="hide-menu">Dashboard</span>
			</a>
		</li>
		<li>
			<a href="{{ route('department.index') }}">
				<i class="mdi mdi-map-marker"></i>
				<span class="hide-menu">Locations</span>
			</a>
		</li>
		<li>
			<a href="{{ route('leave-type.index') }}">
				<i class="mdi mdi-file-outline"></i>
				<span class="hide-menu">Leave Policies</span>
			</a>
		</li>
		<li>
			<a href="{{ route('pay-head.index') }}">
				<i class="mdi mdi-cash-usd"></i>
				<span class="hide-menu">Pay Heads</span>
			</a>
		</li>
		<li>
			<a href="{{ route('employee.index') }}">
				<i class="mdi mdi-account-multiple"></i>
				<span class="hide-menu">Employees</span>
			</a>
		</li>
		<li>
			<a href="{{ route('holidays.index') }}">
				<i class="mdi mdi-airplane-takeoff"></i>
				<span class="hide-menu">Holidays</span>
			</a>
		</li>

		<!-- <li class="nav-item">
			<a href="#">
				<i class="nav-icon fas fa-home"></i>
				<p>
					Overtime
					<i class="right fas fa-angle-left"></i>
				</p>
			</a>
		</li> -->
		<li>
			<a href="{{ route('leaves.index') }}">
				<i class="mdi mdi-calendar"></i>
				<span class="hide-menu">
					Leaves
				</span>
			</a>
		</li>
		<li class="<?php //echo $page_name == "attendance" ? 'active' : ''; ?>">
			<a href="{{ route('attendance.index') }}">
				<i class="mdi mdi-fingerprint"></i>
				<span class="hide-menu">Attendance</span>
			</a>
		</li>
		<!-- <li>
			<a href="{{ route('report.index') }}">
				<i class="mdi mdi-calendar"></i>
				<span class="hide-menu">
					Reports
				</span>
			</a>
		</li> -->
		<li>
			<a href="{{ route('list.step1') }}" class="has-arrow waves-effect waves-dark" aria-expanded="false">
				<i class="mdi mdi-cash"></i>
				<span class="hide-menu">
					Salaries
				</span>
			</a>
			<ul aria-expanded="false" class="collapse ">
				<li>
					<a href="{{ route('payroll.create', ['week_search' => 2]) }}">
						<span class="hide-menu">
							Time Card
						 </span>
					</a>
				</li>
				<li>
					<a href="{{ route('list.payroll') }}">
						<span class="hide-menu"> Run Payroll</span>
					</a>
				</li>
			</ul>
		</li>
	@endif

	@if(auth()->user()->role_id == 3)
		<li>
			<a href="{{ route('my-leaves.index') }}">
				<i class="mdi mdi-calendar"></i>
				<span class="hide-menu">
					Leaves
				</span>
			</a>
		</li>
		<li>
			<a href="#" class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">
				<i class="mdi mdi-airplane-takeoff"></i>
				<span class="hide-menu">
					Holidays
				</span>
			</a>
			<ul aria-expanded="false" class="collapse">
				<li>
					<a href="{{ route('holidays.create') }}">
						<span class="hide-menu">Add New Holiday</span>
					</a>
				</li>
				<li>
					<a href="{{ route('holidays.index') }}">
						<span class="hide-menu">List Of Holidays</span>
					</a>
				</li>
			</ul>
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
	@elseif(auth()->user()->role_id == 2)
		<li>
			<a href="{{ route('my-profile.edit', auth()->user()->id) }}">
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
