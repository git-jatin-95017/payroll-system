<!-- need to remove -->
<li class="nav-item">
	<a href="{{ route('dashboard') }}" class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}">
		<i class="nav-icon fas fa-tachometer-alt"></i>
		<p>Dashboard</p>
	</a>
	
	@if(auth()->user()->role_id == 3)
		<li class="nav-item">
			<a href="{{ route('my-leaves.index') }}" class="nav-link">
				<i class="nav-icon fas fa-book"></i>
				<p>
					Leaves
				</p>
			</a>		
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
					<a href="{{ route('holidays.index') }}" class="nav-link">
						<i class="far fa-circle nav-icon"></i>
						<p>List Of Holidays</p>
					</a>
				</li>
			</ul>
		</li>
	@endif

	@if(auth()->user()->role_id == 3)
		<li class="nav-item">
			<a href="{{ route('emp-my-profile.edit', auth()->user()->id) }}" class="nav-link">
				<i class="nav-icon fas fa-edit"></i>
				<p>
					Profile
				</p>
			</a>		
		</li>
	@else
		<li class="nav-item">
			<a href="{{ route('edit-my-profile.edit', auth()->user()->id) }}" class="nav-link">
				<i class="nav-icon fas fa-edit"></i>
				<p>
					Profile
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
