<!-- need to remove -->
<li class="nav-item">
	<a href="{{ route('dashboard') }}" class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}">
		<i class="nav-icon fas fa-tachometer-alt"></i>
		<p>Dashboard</p>
	</a>
	<li class="nav-item">
		<a href="#" class="nav-link">
			<i class="nav-icon fas fa-home"></i>
			<p>
			Location Codes
				<i class="right fas fa-angle-left"></i>
			</p>
		</a>
		<ul class="nav nav-treeview">
			<li class="nav-item">
				<a href="{{ route('location-codes.index') }}" class="nav-link">
					<i class="far fa-circle nav-icon"></i>
					<p>List</p>
				</a>
			</li>
				<li class="nav-item">
				<a href="{{ route('location-codes.create') }}" class="nav-link">
					<i class="far fa-circle nav-icon"></i>
					<p>Add</p>
				</a>
			</li>
		</ul>
	</li>

	<li class="nav-item">
		<a href="#" class="nav-link">
			<i class="nav-icon fas fa-home"></i>
			<p>
			Housing Codes
				<i class="right fas fa-angle-left"></i>
			</p>
		</a>
		<ul class="nav nav-treeview">
			<li class="nav-item">
				<a href="{{ route('housing-code.index') }}" class="nav-link">
					<i class="far fa-circle nav-icon"></i>
					<p>List</p>
				</a>
			</li>
				<li class="nav-item">
				<a href="{{ route('housing-code.create') }}" class="nav-link">
					<i class="far fa-circle nav-icon"></i>
					<p>Add</p>
				</a>
			</li>
		</ul>
	</li>

	<li class="nav-item">
		<a href="#" class="nav-link">
			<i class="nav-icon fas fa-home"></i>
			<p>
			Housing Data
				<i class="right fas fa-angle-left"></i>
			</p>
		</a>
		<ul class="nav nav-treeview">
			<li class="nav-item">
				<a href="{{ route('housing.index') }}" class="nav-link">
					<i class="far fa-circle nav-icon"></i>
					<p>List</p>
				</a>
			</li>
				<li class="nav-item">
				<a href="{{ route('housing.create') }}" class="nav-link">
					<i class="far fa-circle nav-icon"></i>
					<p>Add</p>
				</a>
			</li>
		</ul>
	</li>

	<li class="nav-item">
		<a href="#" class="nav-link">
			<i class="nav-icon fas fa-home"></i>
			<p>
			National Data
				<i class="right fas fa-angle-left"></i>
			</p>
		</a>
		<ul class="nav nav-treeview">
			<li class="nav-item">
				<a href="{{ route('national-data.index') }}" class="nav-link">
					<i class="far fa-circle nav-icon"></i>
					<p>List</p>
				</a>
			</li>
				<li class="nav-item">
				<a href="{{ route('national-data.create') }}" class="nav-link">
					<i class="far fa-circle nav-icon"></i>
					<p>Add</p>
				</a>
			</li>
		</ul>
	</li>

	<li class="nav-item">
		<a href="#" class="nav-link">
			<i class="nav-icon fas fa-home"></i>
			<p>
			Expenditure Data
				<i class="right fas fa-angle-left"></i>
			</p>
		</a>
		<ul class="nav nav-treeview">
			<li class="nav-item">
				<a href="{{ route('expenditure.index') }}" class="nav-link">
					<i class="far fa-circle nav-icon"></i>
					<p>List</p>
				</a>
			</li>
				<li class="nav-item">
				<a href="{{ route('expenditure.create') }}" class="nav-link">
					<i class="far fa-circle nav-icon"></i>
					<p>Add</p>
				</a>
			</li>
		</ul>
	</li>


	<li class="nav-item">
		<a href="#" class="nav-link">
			<i class="nav-icon fas fa-home"></i>
			<p>
			GS Codes Data
				<i class="right fas fa-angle-left"></i>
			</p>
		</a>
		<ul class="nav nav-treeview">
			<li class="nav-item">
				<a href="{{ route('gs-code.index') }}" class="nav-link">
					<i class="far fa-circle nav-icon"></i>
					<p>List</p>
				</a>
			</li>
				<li class="nav-item">
				<a href="{{ route('gs-code.create') }}" class="nav-link">
					<i class="far fa-circle nav-icon"></i>
					<p>Add</p>
				</a>
			</li>
		</ul>
	</li>

	<li class="nav-item">
		<a href="#" class="nav-link">
			<i class="nav-icon fas fa-home"></i>
			<p>
			GS Quantities
				<i class="right fas fa-angle-left"></i>
			</p>
		</a>
		<ul class="nav nav-treeview">
			<li class="nav-item">
				<a href="{{ route('gs-quantity.index') }}" class="nav-link">
					<i class="far fa-circle nav-icon"></i>
					<p>List</p>
				</a>
			</li>
				<li class="nav-item">
				<a href="{{ route('gs-quantity.create') }}" class="nav-link">
					<i class="far fa-circle nav-icon"></i>
					<p>Add</p>
				</a>
			</li>
		</ul>
	</li>

	<li class="nav-item">
		<a href="#" class="nav-link">
			<i class="nav-icon fas fa-home"></i>
			<p>
			Location Price Data
				<i class="right fas fa-angle-left"></i>
			</p>
		</a>
		<ul class="nav nav-treeview">
			<li class="nav-item">
				<a href="{{ route('location-price.index') }}" class="nav-link">
					<i class="far fa-circle nav-icon"></i>
					<p>List</p>
				</a>
			</li>
				<li class="nav-item">
				<a href="{{ route('location-price.create') }}" class="nav-link">
					<i class="far fa-circle nav-icon"></i>
					<p>Add</p>
				</a>
			</li>
		</ul>
	</li>

	<li class="nav-item">
		<a href="#" class="nav-link">
			<i class="nav-icon fas fa-home"></i>
			<p>
			Property Tax
				<i class="right fas fa-angle-left"></i>
			</p>
		</a>
		<ul class="nav nav-treeview">
			<li class="nav-item">
				<a href="{{ route('property-tax.index') }}" class="nav-link">
					<i class="far fa-circle nav-icon"></i>
					<p>List</p>
				</a>
			</li>
				<li class="nav-item">
				<a href="{{ route('property-tax.create') }}" class="nav-link">
					<i class="far fa-circle nav-icon"></i>
					<p>Add</p>
				</a>
			</li>
		</ul>
	</li>

	<li class="nav-item">
		<a href="#" class="nav-link">
			<i class="nav-icon fas fa-home"></i>
			<p>
			Sale Tax
				<i class="right fas fa-angle-left"></i>
			</p>
		</a>
		<ul class="nav nav-treeview">
			<li class="nav-item">
				<a href="{{ route('sale-tax.index') }}" class="nav-link">
					<i class="far fa-circle nav-icon"></i>
					<p>List</p>
				</a>
			</li>
				<li class="nav-item">
				<a href="{{ route('sale-tax.create') }}" class="nav-link">
					<i class="far fa-circle nav-icon"></i>
					<p>Add</p>
				</a>
			</li>
		</ul>
	</li>

	<li class="nav-item">
		<a href="#" class="nav-link">
			<i class="nav-icon fas fa-home"></i>
			<p>
			Super Market
				<i class="right fas fa-angle-left"></i>
			</p>
		</a>
		<ul class="nav nav-treeview">
			<li class="nav-item">
				<a href="{{ route('super-market.index') }}" class="nav-link">
					<i class="far fa-circle nav-icon"></i>
					<p>List</p>
				</a>
			</li>
				<li class="nav-item">
				<a href="{{ route('super-market.create') }}" class="nav-link">
					<i class="far fa-circle nav-icon"></i>
					<p>Add</p>
				</a>
			</li>
		</ul>
	</li>
</li>
