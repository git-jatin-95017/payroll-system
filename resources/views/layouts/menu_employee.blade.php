<!-- need to remove -->
<li>
	<a href="{{ route('dashboard') }}" class=" {{ Request::is('dashboard') ? '' : '' }}">
		<i class="mdi mdi-gauge"></i>
		<span class="hide-menu">Dashboard</span>
	</a>
	
	@if(auth()->user()->role_id == 3)
		<li>
			<a href="{{ route('my-leaves.index') }}">
				<i class="mdi mdi-rocket"></i>
				<span class="hide-menu">
					My Leaves
				</span>
			</a>		
		</li>
		<li> 
        	<a href="{{ route('holidays.index') }}"><i class="mdi mdi-account-off"></i> Holidays </a>            
    	</li>

	@endif

	@if(auth()->user()->role_id == 3)
		<li>
			<a href="{{ route('emp-my-profile.edit', auth()->user()->id) }}">
				<i class="mdi mdi-account-multiple"></i>
				<span class="hide-menu">
					Profile
				</span>
			</a>		
		</li>
	@else
		<li>
			<a href="{{ route('edit-my-profile.edit', auth()->user()->id) }}">
				<i class="mdi mdi-account-multiple"></i>
				<span class="hide-menu">
					Profile
				</span>
			</a>		
		</li>
	@endif
	<li>
		<a href="#"
		   onclick="event.preventDefault(); document.getElementById('logout-form-1').submit();">
			<i class="mdi mdi-power"></i>
			<span class="hide-menu">
				Logout
			</span>
		</a>
		<form id="logout-form-1" action="{{ route('logout') }}" method="POST" class="d-none">
			@csrf
		</form>		
	</li>
</li>
