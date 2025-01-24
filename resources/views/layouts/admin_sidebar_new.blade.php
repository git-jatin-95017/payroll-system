<div>
	<div class="sb-menu-h pb-3">
		<span>Admin Menu</span>
	</div>
	<ul class="list-unstyled main-navigation">
		<li class="active">
			<a href="{{ route('admin.dashboard') }}">
				<x-bxs-home-alt-2 class="w-20 h-20" />
				<span>Dashboard</span>
			</a>
		</li>
		<li>
			<a href="{{ route('settings.create', auth()->user()->id) }}">
				<x-heroicon-s-users class="w-20 h-20" />
				<span>Settings</span>
			</a>
		</li>
		<li>
			<a href="{{ route('edit-my-profile.edit', auth()->user()->id) }}">
				<x-bxs-file-doc class="w-20 h-20"/>
				<span>Profile</span>
			</a>
		</li>
		<li class="has-sub">
			<a href="#">
				<x-bxs-user class="w-20 h-20"/>
				<span>Clients</span>
			</a>
			<ul class="list-unstyled">
				<li><a href="{{ route('client.create') }}">Add New Client</a></li>
				<li><a href="{{ route('client.index') }}">List Of Client</a></li>
			</ul>
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