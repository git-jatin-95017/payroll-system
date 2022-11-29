<!-- need to remove -->
<li>
	<a href="{{ route('dashboard') }}" class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}">
		<i class="mdi mdi-gauge"></i>
		<span class="hide-menu">Dashboard</span>
	</a>
	@if(auth()->user()->role_id == 1)
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
		<li class=" <?php //echo $page_name == "attendance" ? 'active' : ''; ?>">
			<a href="{{ route('attendance.index') }}">
				<i class="fa fa-calendar nav-icon"></i> 
				<span class="hide-menu">Attendance</span>
			</a>
		</li>
	@endif

	@if(auth()->user()->role_id == 2)
		<li>
			<a href="#" class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">
				<i class="fa fa-building-o"></i>
				<span class="hide-menu">
				 	Departments
				</span>
			</a>
			<ul aria-expanded="false" class="collapse ">				
				<li>
					<a href="{{ route('department.index') }}">
						<span class="hide-menu">List Of Departments</span>
					</a>
				</li>
			</ul>
		</li>
		<li>
			<a href="#" class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">
				<i class="mdi mdi-file-outline"></i>
				<span class="hide-menu">
				 Leave Types
				</span>
			</a>
			<ul aria-expanded="false" class="collapse ">				
				<li>
					<a href="{{ route('leave-type.index') }}">
						<p>List Leave Types</p>
					</a>
				</li>
			</ul>
		</li>
		<li>
			<a href="#" class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">
				<i class="mdi mdi-account-multiple"></i>
				<span class="hide-menu">
				 Employees
				</span>
			</a>
			<ul aria-expanded="false" class="collapse ">
				<li>
					<a href="{{ route('employee.create') }}">
						<span class="hide-menu">Add New Employee</span>
					</a>
				</li>
				<li>
					<a href="{{ route('employee.index') }}">
						<span class="hide-menu">List Of Employees</span>
					</a>
				</li>
			</ul>
		</li>		
		<li>
			<a href="#" class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">
				<i class="mdi mdi-rocket"></i>
				<span class="hide-menu">
				 Holidays
				</span>
			</a>
			<ul aria-expanded="false" class="collapse ">
				<li class="nav-item">
					<a href="{{ route('holidays.create') }}">
						<span class="hide-menu">Add New Holiday</span>
					</a>
				</li>
				<li class="nav-item">
					<a href="{{ route('holidays.index') }}">
						<span class="hide-menu">List Of Holidays</span>
					</a>
				</li>
			</ul>
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
				<i class="mdi mdi-account-off"></i>
				<span class="hide-menu">
					Leaves
				</span>
			</a>		
		</li>
		<li>
			<a href="#" class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">
				<i class="mdi mdi-calendar"></i>
				<span class="hide-menu">
				 	Attendance
				</span>
			</a>
			<ul aria-expanded="false" class="collapse ">
				<li>
					<a href="#">
						<span class="hide-menu">List Of Attendance</span>
					</a>
				</li>
				<li>
					<a href="#">
						<span class="hide-menu">Add New Attendance</span>
					</a>
				</li>
			</ul>
		</li>
		<li>
			<a href="#" class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">
				<i class="mdi mdi-credit-card"></i>
				<span class="hide-menu">
				 	Salaries
				</span>
			</a>
			<ul aria-expanded="false" class="collapse ">
				<li>
					<a href="#">
						<span class="hide-menu">List</span>
					</a>
				</li>
			</ul>
		</li>
		<li>
			<a href="{{ route('payroll.create') }}">
				<i class="mdi mdi-receipt"></i>
				<span class="hide-menu">
				 	Payroll Sheet
				 </span>
			</a>
		</li>
	@endif

	@if(auth()->user()->role_id == 3)
		<li>
			<a href="{{ route('my-leaves.index') }}">
				<i class="nav-icon fas fa-book"></i>
				<span class="hide-menu">
					Leaves
				</span>
			</a>		
		</li>
		<li>
			<a href="#" class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">
				<i class="nav-icon fas fa-table"></i>
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
				<i class="mdi mdi-account-outline"></i>
				<span class="hide-menu">
					Profile
				</span>
			</a>		
		</li>
	@else
		<li>
			<a href="{{ route('edit-my-profile.edit', auth()->user()->id) }}">
				<i class="mdi mdi-account-outline"></i>
				<span class="hide-menu">
					Profile
				</span>
			</a>		
		</li>
	@endif
	<li>
		<a href="#"
		   onclick="event.preventDefault(); document.getElementById('logout-form-1').submit();">
			<i class="mdi mdi-lock-outline"></i>
			<span class="hide-menu">
				Logout
			</span>
		</a>
		<form id="logout-form-1" action="{{ route('logout') }}" method="POST" class="d-none">
			@csrf
		</form>		
	</li>
</li>
