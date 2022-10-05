<!-- need to remove -->
<li class="nav-item">
	<a href="{{ route('dashboard') }}" class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}">
		<i class="nav-icon fas fa-tachometer-alt"></i>
		<p>Dashboard</p>
	</a>
	@if(auth()->user()->role_id == 1)
		<li class="nav-item">
			<a href="#" class="nav-link">
				<i class="nav-icon fas fa-user"></i>
				<p>
				 Clients
					<i class="right fas fa-angle-left"></i>
				</p>
			</a>
			<ul class="nav nav-treeview">
				<li class="nav-item">
					<a href="{{ route('client.create') }}" class="nav-link">
						<i class="far fa-circle nav-icon"></i>
						<p>Add New Client</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="{{ route('client.index') }}" class="nav-link">
						<i class="far fa-circle nav-icon"></i>
						<p>List Of Client</p>
					</a>
				</li>
			</ul>
		</li>
		<li class="nav-item <?php //echo $page_name == "attendance" ? 'active' : ''; ?>">
			<a href="{{ route('attendance.index') }}" class="nav-link">
				<i class="fa fa-calendar nav-icon"></i> <span>Attendance</span>
			</a>
		</li>
	@endif

	@if(auth()->user()->role_id == 2)
		<li class="nav-item">
			<a href="#" class="nav-link">
				<i class="nav-icon fas fa-user"></i>
				<p>
				 Employees
					<i class="right fas fa-angle-left"></i>
				</p>
			</a>
			<ul class="nav nav-treeview">
				<li class="nav-item">
					<a href="{{ route('employee.create') }}" class="nav-link">
						<i class="far fa-circle nav-icon"></i>
						<p>Add New Employee</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="{{ route('employee.index') }}" class="nav-link">
						<i class="far fa-circle nav-icon"></i>
						<p>List Of Employees</p>
					</a>
				</li>
			</ul>
		</li>		
		<li class="nav-item">
			<a href="#" class="nav-link">
				<i class="nav-icon fas fa-table"></i>
				<p>
				 Holidays
					<i class="right fas fa-angle-left"></i>
				</p>
			</a>
			<ul class="nav nav-treeview">
				<li class="nav-item">
					<a href="{{ route('holidays.create') }}" class="nav-link">
						<i class="far fa-circle nav-icon"></i>
						<p>Add New Holiday</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="{{ route('holidays.index') }}" class="nav-link">
						<i class="far fa-circle nav-icon"></i>
						<p>List Of Holidays</p>
					</a>
				</li>
			</ul>
		</li>
		
		<!-- <li class="nav-item">
			<a href="#" class="nav-link">
				<i class="nav-icon fas fa-home"></i>
				<p>
					Overtime
					<i class="right fas fa-angle-left"></i>
				</p>
			</a>		
		</li> -->
		<li class="nav-item">
			<a href="{{ route('leaves.index') }}" class="nav-link">
				<i class="nav-icon fas fa-book"></i>
				<p>
					Leaves
				</p>
			</a>		
		</li>
		<li class="nav-item">
			<a href="#" class="nav-link">
				<i class="nav-icon far fa-calendar-alt"></i>
				<p>
				 Attendance
					<i class="right fas fa-angle-left"></i>
				</p>
			</a>
			<ul class="nav nav-treeview">
				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="far fa-circle nav-icon"></i>
						<p>List Of Attendance</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="far fa-circle nav-icon"></i>
						<p>Add New Attendance</p>
					</a>
				</li>
			</ul>
		</li>
		<li class="nav-item">
			<a href="#" class="nav-link">
				<i class="nav-icon fas fa-file"></i>
				<p>
				 Salaries
					<i class="right fas fa-angle-left"></i>
				</p>
			</a>
			<ul class="nav nav-treeview">
				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="far fa-circle nav-icon"></i>
						<p>List</p>
					</a>
				</li>
			</ul>
		</li>
	@endif

	@if(auth()->user()->role_id == 3)
		<li class="nav-item">
			<a href="{{ route('my-leaves.index') }}" class="nav-link">
				<i class="nav-icon fas fa-book"></i>
				<p>
					Leaves
				</p>
			</a>		
		</li>
	@endif

	@if(auth()->user()->role_id == 3)
		<li class="nav-item">
			<a href="{{ route('emp-my-profile.edit', auth()->user()->id) }}" class="nav-link">
				<i class="nav-icon fas fa-edit"></i>
				<p>
					Edit Profile
				</p>
			</a>		
		</li>
	@else
		<li class="nav-item">
			<a href="{{ route('edit-my-profile.edit', auth()->user()->id) }}" class="nav-link">
				<i class="nav-icon fas fa-edit"></i>
				<p>
					Edit Profile
				</p>
			</a>		
		</li>
	@endif
	<li class="nav-item">
		<a href="#" class="nav-link"
		   onclick="event.preventDefault(); document.getElementById('logout-form-1').submit();">
			<i class="nav-icon fas fa-sign-out-alt"></i>
			<p>
				Logout
			</p>
		</a>
		<form id="logout-form-1" action="{{ route('logout') }}" method="POST" class="d-none">
			@csrf
		</form>		
	</li>
</li>
