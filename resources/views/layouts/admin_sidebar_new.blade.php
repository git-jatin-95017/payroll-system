<div>
	<div class="sb-menu-h pb-3">
		<span>Admin Menu</span>
	</div>
	<ul class="list-unstyled main-navigation">
		<li class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
			<a href="{{ route('admin.dashboard') }}">
				<x-bxs-home-alt-2 class="w-20 h-20" />
				<span>Dashboard</span>
			</a>
		</li>
		<li class="{{ request()->is('admin/settings*') ? 'active' : '' }}">
			<a href="{{ route('settings.create', auth()->user()->id) }}">
				<x-bxs-wrench class="w-20 h-20" />
				<span>Calculations</span>
			</a>
		</li>
		<li class="{{ request()->is('edit-my-profile*') ? 'active' : '' }}">
			<a href="{{ route('edit-my-profile.edit', auth()->user()->id) }}">
				<x-bxs-file-doc class="w-20 h-20"/>
				<span>Profile</span>
			</a>
		</li>
		<li class="has-sub {{ Request::routeIs('my-profile.edit', 'client.index', 'client.create', 'client.edit') ? 'active' : '' }}">
			<a href="#">
				<x-bxs-user class="w-20 h-20"/>
				<span>Clients</span>
			</a>
			<ul class="list-unstyled">
				<li><a href="{{ route('client.create') }}">Add New Client</a></li>
				<li><a href="{{ route('client.index') }}">List Of Client</a></li>
			</ul>
		</li>
		<li class="has-sub {{ Request::routeIs('admin.permissions.*') ? 'active' : '' }}">
			<a href="#">
				<x-bxs-shield class="w-20 h-20"/>
				<span>Permissions</span>
			</a>
			<ul class="list-unstyled">
				<li><a href="{{ route('admin.permissions.index') }}">Manage Roles</a></li>
				<li><a href="{{ route('admin.permissions.create-role') }}">Create Role</a></li>
				<li><a href="{{ route('admin.permissions.create-permission') }}">Create Permission</a></li>
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