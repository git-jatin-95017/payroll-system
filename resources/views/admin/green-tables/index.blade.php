@extends('layouts.app')

@section('content')
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">Run Script</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="#">Home</a></li>
						<li class="breadcrumb-item active">Run Script</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<section class="content">
		@error('flush_table')
			<div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
		@enderror
		<div class="container-fluid">
			@if(session('status'))
				<div class="alert alert-success">
					{{ session('status') }}
				</div>
			@endif
			<div class="row">				
				<div class="col-sm-6">
					<div class="card card-primary">
						<div class="card-header">
							<h3 class="card-title">Run Script</h3>
						</div>
						<div class="card-body text-center">
							<div id="heading-links" class="card-header">
								<a class="btn btn-lg btn-success" href="{{ route('run-script') }}">Run Script</a>
								<!-- <p class="font-light">Click to execute script</p> -->
							</div>
						</div>
						 <div class="card-footer">
							&nbsp;
						</div>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="card card-primary">
						<div class="card-header">
							<h3 class="card-title">Truncate / Delete - Green Tables Data</h3>
						</div>
						<form id="excel-csv-import-form" method="POST"  action="{{ route('flush-data') }}" accept-charset="utf-8" enctype="multipart/form-data">
							@csrf
							<div class="card-body">
								<p><strong>Calculations G&S</strong></p>
								<div class="form-check">
									  <label class="form-check-label">
										<input type="checkbox" class="form-check-input" name="flush_table[]" value="1"> G&S Cleaned Prices
									  </label>
								</div>
								<div class="form-check">
									  <label class="form-check-label">
										<input type="checkbox" class="form-check-input" name="flush_table[]" value="2"> G&S Component Item Prices Locations
									  </label>
								</div>
								<div class="form-check">
								  <label class="form-check-label">
									<input type="checkbox" class="form-check-input" name="flush_table[]" value="3"> G&S Component Item Prices Cities
								  </label>
								</div>
								<div class="form-check">
								  <label class="form-check-label">
									<input type="checkbox" class="form-check-input" name="flush_table[]" value="4"> G&S Component Item Prices Countries
								  </label>
								</div>
								<div class="form-check">
								  <label class="form-check-label">
									<input type="checkbox" class="form-check-input" name="flush_table[]" value="5"> G&S Component Item Prices Adjusted Cities
								  </label>
								</div>
								<div class="form-check">
								  <label class="form-check-label">
									<input type="checkbox" class="form-check-input" name="flush_table[]" value="6"> Gs Final Item Prices
								  </label>
								</div>
								<div class="form-check">
								  <label class="form-check-label">
									<input type="checkbox" class="form-check-input" name="flush_table[]" value="7"> GS Quantities
								  </label>
								</div>
								<div class="form-check">
								  <label class="form-check-label">
									<input type="checkbox" class="form-check-input" name="flush_table[]" value="8"> GS Item Budgets
								  </label>
								</div>
								<div class="form-check">
								  <label class="form-check-label">
									<input type="checkbox" class="form-check-input" name="flush_table[]" value="9"> GS City Budgets
								  </label>
								</div>
							</div>
							<hr>
							<div class="card-body">
								<p><strong>Calculations Housing</strong></p>
								<div class="form-check">
									  <label class="form-check-label">
										<input type="checkbox" class="form-check-input" name="flush_table[]" value="10"> Housing Final Prices
									  </label>
								</div>
								<div class="form-check">
									  <label class="form-check-label">
										<input type="checkbox" class="form-check-input" name="flush_table[]" value="11"> Housing Final Prices Countries
									  </label>
								</div>
								<div class="form-check">
								  <label class="form-check-label">
									<input type="checkbox" class="form-check-input" name="flush_table[]" value="12"> Housing_Final_Prices_Rental
								  </label>
								</div>
								<div class="form-check">
								  <label class="form-check-label">
									<input type="checkbox" class="form-check-input" name="flush_table[]" value="13">Housing_Home_Price_Indices Countries
								  </label>
								</div>
								<div class="form-check">
								  <label class="form-check-label">
									<input type="checkbox" class="form-check-input" name="flush_table[]" value="14">Housing_Rental_Price_Indices
								  </label>
								</div>
								<div class="form-check">
								  <label class="form-check-label">
									<input type="checkbox" class="form-check-input" name="flush_table[]" value="15">Housing_Property_Tax_Price_Indices
								  </label>
								</div>								
							</div>
							<div class="card-footer">
								<button type="submit" class="btn btn-primary">Truncate Tables</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>    
@endsection