<div>
	<div class="sb-menu-h">
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
			<a href="{{ route('my-leaves.index') }}">
				<x-bxs-file-doc class="w-20 h-20"/>
				<span>My Leaves</span>
			</a>
		</li>
		<li>
			<a href="{{ route('holidays.index') }}">
				<x-bxs-plane-take-off class="w-20 h-20"/>
				<span>Holidays</span>
			</a>
		</li>
		<li>
			<a href="{{ route('my.payslip') }}">
				<x-heroicon-s-users class="w-20 h-20" />
				<span>Pay Slip</span>
			</a>
		</li>
		<li>
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