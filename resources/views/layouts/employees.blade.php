<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>{{ config('app.name') }}</title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

	<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ asset('css/style-new.css') }}" rel="stylesheet">

	{{-- cutom css --}}

	@yield('third_party_stylesheets')

	@stack('page_css')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
	<!-- Main Header -->
	<nav class="main-header navbar navbar-expand navbar-white navbar-light">
		<!-- Left navbar links -->
		<ul class="navbar-nav">
			<li class="nav-item">
				<a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
			</li>
		</ul>

		<ul class="navbar-nav ml-auto">
			<li class="nav-item dropdown user-menu">
				<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
					<img src="/img/logo3.png"
						 class="user-image img-circle elevation-2" alt="User Image">
					<span class="d-none d-md-inline">Welcome {{ ucwords(Auth::user()->name) }}</span>
				</a>
				<ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
					<!-- User image -->
					<li class="user-header bg-primary">
						<img src="/img/logo3.jpg"
							 class="img-circle elevation-2" style="object-fit: contain;"
							 alt="User Image">
						<p>
							{{ ucwords(Auth::user()->name) }}
							<small>Member since {{ Auth::user()->created_at->format('M. Y') }}</small>
						</p>
					</li>
					<!-- Menu Footer-->
					<li class="user-footer">
						@if(Auth::user()->role_id == 1)
						<a href="{{ route('edit-my-profile.edit', Auth::user()->id) }}" class="btn btn-default btn-flat">Profile</a>
						@endif
						<a href="#" class="btn btn-default btn-flat float-right"
						   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
							Sign out
						</a>
						<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
							@csrf
						</form>
					</li>
				</ul>
			</li>

		</ul>
	</nav>

	<!-- Left side column. contains the logo and sidebar -->
@include('layouts.sidebar_employees')

<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		@yield('content')
	</div>

	<!-- Main Footer -->
	<footer class="main-footer">
		<div class="float-right d-none d-sm-block">
			<!-- <b>Version</b> 3.0.5 -->
		</div>
		<strong>Copyright &copy; 2022 <a href="/">Payroll Management</a>.</strong> All rights
		reserved.
	</footer>
</div>

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<!-- <script src="{{ asset('js/responsive.bootstrap4.min.js') }}"></script> -->
<!-- <script src="{{ asset('js/buttons.bootstrap4.min.js') }}"></script> -->
<script src="{{ asset('js/adminlte.min.js') }}"></script>
<script src="{{ asset('js/notify.min.js') }}"></script>

@yield('third_party_scripts')

@stack('page_scripts')
</body>
</html>
