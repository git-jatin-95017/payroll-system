<aside class="main-sidebar sidebar-dark-primary elevation-4">
	<a href="{{ route('dashboard') }}" class="brand-link">
		<img src="/img/logo-side.jpg"
			 alt="Logo" style="width: 235px; object-fit: unset;margin: 0;" 
			 class="brand-image elevation-3">
		<span class="brand-text font-weight-light">&nbsp;</span>
	</a>

	<div class="sidebar">
		<nav class="mt-2">
			<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
				@include('layouts.menu')
			</ul>
		</nav>
	</div>

</aside>
