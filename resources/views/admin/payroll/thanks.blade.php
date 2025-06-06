@extends('layouts.app')

@section('content')
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">
            <i class="fa fa-braille" style="color:#1976d2"></i>
            Run Payroll
        </h3>
    </div>
    <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="javascript:void(0)">Home</a>
            </li>
            <li class="breadcrumb-item active">Run Payroll</li>
			<li class="breadcrumb-item active">/</li>
        </ol>
    </div>
</div>
<section class="content">
	<div class="container-fluid">		
		<div class="tab-content" id="myTabContent">
			<div class="container-fluid">
			@if (session('message'))
				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-success alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							{{ session('message') }}
						</div>
					</div>
				</div>
			@elseif (session('error'))
				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-danger alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							{{ session('error') }}
						</div>
					</div>
				</div>
			@endif
			<div class="row">
				<div class="col-sm-12 mt-4 pt-4">	
					<div class="card">						
						<div class="confirm-container">
							<div class="text-center">
								<svg xmlns="http://www.w3.org/2000/svg" width="32" viewBox="0 0 48 48"><g data-name="Layer 1 copy">
									<rect x="7.859" y="10.844" fill="#569f7d" transform="rotate(-10.943 26.58 21.563)"/><rect width="37.447" height="21.434" x="4.372" y="15.125" fill="#60b18b" transform="rotate(-5.938 23.096 25.837)"/><rect width="37.447" height="21.434" x="1" y="19.081" fill="#8ed8b5"/><path fill="#b1dfbc" d="M35,34.478A3.007,3.007,0,0,0,32,37.485H7.453a3.007,3.007,0,0,0-3.008-3.007V25.119a3.008,3.008,0,0,0,3.008-3.008H32A3.007,3.007,0,0,0,35,25.119Z"/><circle cx="19.724" cy="29.798" r="4.512" fill="#8ed8b5"/><polygon fill="#60b18b" points="41.346 9.771 45.046 28.907 47 28.529 42.931 7.485 6.165 14.594 6.534 16.502 41.346 9.771"/><polygon fill="#8ed8b5" points="39.025 15.532 41.024 34.752 42.827 34.565 40.61 13.246 3.364 17.12 3.582 19.219 39.025 15.532"/><polygon fill="#b1dfbc" points="1 19.081 1 21.111 36.4 21.111 36.4 40.515 38.447 40.515 38.447 19.081 1 19.081"/><path fill="#8ed8b5" d="M5.268 24.99a2.987 2.987 0 0 1-.823.129v1.214A3 3 0 0 0 5.268 24.99zM30.748 37.485H32a2.981 2.981 0 0 1 .136-.849A2.988 2.988 0 0 0 30.748 37.485z"/><path fill="#c1ecd0" d="M32,22.111H7.453a2.989,2.989,0,0,1-.8,2.03h23.3a3.007,3.007,0,0,0,3.008,3.007v8.145A2.988,2.988,0,0,1,35,34.478V25.119A3.007,3.007,0,0,1,32,22.111Z"/><path fill="#b1dfbc" d="M19.724,25.286a4.5,4.5,0,0,0-4.019,2.5,4.456,4.456,0,0,1,1.971-.472,4.512,4.512,0,0,1,4.512,4.512,4.459,4.459,0,0,1-.493,2.01,4.5,4.5,0,0,0-1.971-8.552Z"/></g><g data-name="Layer 1 copy 2"><path fill="#1c1c1b" d="M38.447,41.515H1a1,1,0,0,1-1-1V19.081a1,1,0,0,1,1-1H38.447a1,1,0,0,1,1,1V40.515A1,1,0,0,1,38.447,41.515ZM2,39.515H37.447V20.081H2Z"/><path fill="#1c1c1b" d="M32,38.485H7.453a1,1,0,0,1-1-1,2.01,2.01,0,0,0-2.008-2.007,1,1,0,0,1-1-1V25.119a1,1,0,0,1,1-1,2.011,2.011,0,0,0,2.008-2.008,1,1,0,0,1,1-1H32a1,1,0,0,1,1,1A2.01,2.01,0,0,0,35,24.119a1,1,0,0,1,1,1v9.359a1,1,0,0,1-1,1,2.009,2.009,0,0,0-2.007,2.007A1,1,0,0,1,32,38.485Zm-23.669-2h22.8A4.025,4.025,0,0,1,34,33.6V25.992a4.023,4.023,0,0,1-2.881-2.881H8.326a4.021,4.021,0,0,1-2.881,2.881V33.6A4.023,4.023,0,0,1,8.326,36.485Z"/><path fill="#1c1c1b" d="M19.724,35.31A5.512,5.512,0,1,1,25.235,29.8,5.519,5.519,0,0,1,19.724,35.31Zm0-9.023A3.512,3.512,0,1,0,23.235,29.8,3.515,3.515,0,0,0,19.724,26.287Z"/><path fill="#1c1c1b" d="M40.819,35.788a1,1,0,0,1-.1-1.995l1.154-.121-2.03-19.327L7.65,17.725a1,1,0,0,1-.209-1.99l33.184-3.484a1,1,0,0,1,1.1.89l2.239,21.317a1,1,0,0,1-.89,1.1l-2.149.226C40.889,35.786,40.854,35.788,40.819,35.788Z"/><path fill="#1c1c1b" d="M45.367,29.848a1,1,0,0,1-.188-1.982l.651-.125L42.168,8.655,10.827,14.667A1,1,0,1,1,10.45,12.7L42.774,6.5a1,1,0,0,1,1.17.793l4.038,21.051a1,1,0,0,1-.794,1.17l-1.632.313A1.024,1.024,0,0,1,45.367,29.848Z"/></g>
								</svg>
								<h3>Calculating Payroll</h3>
								<p class="text-center font-bold">Relax while we compute your payment. No need for a calculator, you’re in good hands.</p>
								<!-- <p class="text-center font-bold">Feel free to put away your calculator. you won't need it anymore.</p> -->
							</div>
						</div>						
					</div>
				</div>
			</div>
			</div>
		</div>
		</div>
</section>
@endsection