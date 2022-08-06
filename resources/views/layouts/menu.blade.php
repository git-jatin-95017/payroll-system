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
			Housing Prices
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
			National Prices
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
			Expenditure
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
			GS Codes
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
			Local Prices
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
			Sales Tax
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
			Supermarket Prices
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

	<li class="nav-item">
		<a href="#" class="nav-link">
			<i class="nav-icon fas fa-home"></i>
			<p>
				GS & Calculations
				<i class="right fas fa-angle-left"></i>
			</p>
		</a>
		<ul class="nav nav-treeview">			

			<li class="nav-item">
				<a href="{{ route('gs-cleaned-prices.index') }}" class="nav-link">
					<i class="far fa-circle nav-icon"></i>
					<p>
						GS Cleaned Prices
					</p>
				</a>		
			</li>
			<li class="nav-item">
				<a href="{{ route('gsc-itemprice-locations.index') }}" class="nav-link">
					<i class="far fa-circle nav-icon"></i>
					<p>
						GS Component Item Prices Locations
					</p>
				</a>		
			</li>
			<li class="nav-item">
				<a href="{{ route('gsc-itemprice-cities.index') }}" class="nav-link">
					<i class="far fa-circle nav-icon"></i>
					<p>
						GS Component Item Prices Cities
					</p>
				</a>		
			</li><li class="nav-item">
				<a href="{{ route('gsc-itemprice-countries.index') }}" class="nav-link">
					<i class="far fa-circle nav-icon"></i>
					<p>
						GS Component Item Prices Countries
					</p>
				</a>		
			</li>
			<li class="nav-item">
				<a href="{{ route('gsc-itemprice-adjusted-cities.index') }}" class="nav-link">
					<i class="far fa-circle nav-icon"></i>
					<p>
						GS Component Item Prices Adjusted Cities
					</p>
				</a>		
			</li>		
			<li class="nav-item">
				<a href="{{ route('gs-final-item-prices.index') }}" class="nav-link">
					<i class="far fa-circle nav-icon"></i>
					<p>
						GS FinalItem Prices
					</p>
				</a>		
			</li><li class="nav-item">
				<a href="{{ route('gs-item-budgets.index') }}" class="nav-link">
					<i class="far fa-circle nav-icon"></i>
					<p>
						GS Item Budgets
					</p>
				</a>		
			</li><li class="nav-item">
				<a href="{{ route('gs-city-budgets.index') }}" class="nav-link">
					<i class="far fa-circle nav-icon"></i>
					<p>
						GS City Budgets
					</p>
				</a>		
			</li>
						
			<li class="nav-item">
				<a href="{{ route('gs-raw-prices.index') }}" class="nav-link">
					<i class="far fa-circle nav-icon"></i>
					<p>
						GS Raw Prices
					</p>
				</a>		
			</li>			
		</ul>
	</li>

	<li class="nav-item">
		<a href="#" class="nav-link">
			<i class="nav-icon fas fa-home"></i>
			<p>
				Housing & Calculations
				<i class="right fas fa-angle-left"></i>
			</p>
		</a>
		<ul class="nav nav-treeview">
			<li class="nav-item">
				<a href="{{ route('housing-final-prices.index') }}" class="nav-link">
					<i class="far fa-circle nav-icon"></i>
					<p>
						Housing Final Prices
					</p>
				</a>		
			</li>
			<li class="nav-item">
				<a href="{{ route('housing-final-prices-country.index') }}" class="nav-link">
					<i class="far fa-circle nav-icon"></i>
					<p>
						Housing Final Prices Countries
					</p>
				</a>		
			</li>
			<li class="nav-item">
				<a href="{{ route('housing-final-rental-prices.index') }}" class="nav-link">
					<i class="far fa-circle nav-icon"></i>
					<p>
						Housing Final Rental Prices
					</p>
				</a>		
			</li>

			<li class="nav-item">
				<a href="{{ route('housing-home-indices-prices.index') }}" class="nav-link">
					<i class="far fa-circle nav-icon"></i>
					<p>
						Housing Home Indices Prices
					</p>
				</a>		
			</li>

			<li class="nav-item">
				<a href="{{ route('housing-rental-indices-prices.index') }}" class="nav-link">
					<i class="far fa-circle nav-icon"></i>
					<p>
						Housing Rental Indices Prices
					</p>
				</a>		
			</li>

			<li class="nav-item">
				<a href="{{ route('housing-prop-tax-ind-prices.index') }}" class="nav-link">
					<i class="far fa-circle nav-icon"></i>
					<p>
						Housing Property Tax Indices Prices
					</p>
				</a>		
			</li>
		</ul>
	</li>
	<a href="{{ route('exchange-rates.index') }}" class="nav-link">
		<i class="far fa-circle nav-icon"></i>
		<p>
			Exchange Rates 
		</p>
	</a>
	<a href="{{ route('run-script-view') }}" class="nav-link {{ Request::is('run-script-view') ? 'active' : '' }}">
		<i class="nav-icon fas fa-circle"></i>
		<p>Run SCRIPT</p>
	</a>
</li>
