<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>{{ config('app.name') }}</title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css"
		  integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog=="
		  crossorigin="anonymous"/>
		  <link rel="preconnect" href="https://fonts.googleapis.com">
			<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
			<link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:ital,wght@0,200;0,300;0,400;0,600;1,200;1,300;1,400;1,600&display=swap" rel="stylesheet">

	<!-- <link href="{{ asset('css/all.min.css') }}" rel="stylesheet"> -->
	  <!-- icheck bootstrap -->
	<!-- <link href="{{ asset('css/icheck-bootstrap.min.css') }}" rel="stylesheet"> -->
	<!-- Theme style -->
	<!-- <link href="{{ asset('css/adminlte.min.css') }}" rel="stylesheet"> -->

	<!-- Bootstrap Core CSS -->
    <link href="{{ asset('css/payrollCss/bootstrap.min.css') }}" rel="stylesheet">
    <!-- morris CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/2.0.46/css/materialdesignicons.min.css" rel="stylesheet">
    <link href=" {{ asset('css/payrollCss/morris.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
	<link href=" {{ asset('css/payrollCss/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">
    <link href=" {{ asset('css/payrollCss/style.css') }}" rel="stylesheet">
	<link href=" {{ asset('css/payrollCss/print.css') }}" rel="stylesheet">

    <!-- You can change the theme colors from here -->
    <!-- <link href="css/colors/blue.css" id="theme" rel="stylesheet"> -->
	<link href=" {{ asset('css/payrollCss/select2.min.css') }}" rel="stylesheet">
	<link href=" {{ asset('css/payrollCss/switchery.min.css') }}" rel="stylesheet">
	<link href=" {{ asset('css/payrollCss/bootstrap-select.min.css') }}" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />
	<link href=" {{ asset('css/payrollCss/jquery-clockpicker.min.css') }}" rel="stylesheet">
	<link href=" {{ asset('css/payrollCss/bootstrap-timepicker.min.css') }}" rel="stylesheet">
	{{-- cutom css --}}
	<!-- <link href="{{ asset('css/custom.css') }}" rel="stylesheet"> -->
	@yield('third_party_stylesheets')

	@stack('page_css')
</head>

<body class="fix-header fix-sidebar card-no-border">
<div class="main-wrapper">
	<!-- <nav class="main-header navbar navbar-expand navbar-white navbar-light">
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
					<li class="user-header bg-primary">
						<img src="/img/logo3.jpg"
							 class="img-circle elevation-2" style="object-fit: contain;"
							 alt="User Image">
						<p>
							{{ ucwords(Auth::user()->name) }}
							<small>Member since {{ Auth::user()->created_at->format('M. Y') }}</small>
						</p>
					</li>
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
	</nav> -->
	<header class="topbar">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <div class="navbar-header pt-4">
                    <a class="navbar-brand" href="#">
                    	@if(!empty(Auth::user()->employeeProfile->logo))
							<img src="/files/{{Auth::user()->employeeProfile->logo}}"/>
						@else
							<img src="{{URL::asset('/img/logo-color.svg')}}" alt="">
						@endif
                    </a>
                </div>
                <div class="navbar-collapse bg-dark-blue">
                    <ul class="navbar-nav mr-auto mt-md-0">
                        <!-- This is  -->
                        <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> </li>
                        <li class="nav-item m-l-10">
                            <a class="nav-link sidebartoggler hidden-sm-down text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted text-muted waves-effect waves-dark" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="mdi mdi-bell"></i>
                                <div class="notify">
								<span class="heartbit">
								</span> <span class="point"></span>
							</div>
                            </a>
                            <div class="dropdown-menu mailbox scale-up-left">
                                <ul>
                                    <li>
                                        <div class="drop-title">Notifications</div>
                                    </li>
                                    <li>
                                        <div class="message-center">
                                            <!-- Message -->
                                            <a href="#">
                                                <div class="btn btn-danger btn-circle"><i class="fa fa-link"></i></div>
                                                <div class="mail-contnet">
                                                    <h5>Rakesh</h5> <span class="mail-desc">date</span> </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <a class="nav-link text-center" href="javascript:void(0);">
											<strong>Check all notifications</strong>
											<i class="fa fa-angle-right"></i>
										</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                    <ul class="navbar-nav my-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            	@if(!empty(Auth::user()->employeeProfile->file))
									<img src="/files/{{Auth::user()->employeeProfile->file}}" alt="Genit" class="profile-pic" style="height:40px;width:40px;border-radius:50px" />
								@endif
                            <div class="dropdown-menu dropdown-menu-right scale-up">
                                <ul class="dropdown-user">
                                    <li>
                                        <div class="dw-user-box">
                                            <div class="u-img">
                                            	@if(!empty(Auth::user()->employeeProfile->file))
												<img src="/files/{{Auth::user()->employeeProfile->file}}" class="img-thumbnail" />
												@endif
                                            </div>
                                            <div class="u-text">
                                                <h4>Welcome {{ ucwords(Auth::user()->name) }}</h4>
                                                <p class="text-muted">{{ ucwords(Auth::user()->name) }}</p>
                                        </div>
                                    </li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="{{ route('emp-my-profile.edit', auth()->user()->id) }}"><i class="mdi mdi-account"></i> My Profile</a></li>
                                    <!-- <li><a href="#"><i class="ti-settings"></i> Account Setting</a></li> -->
                                    <li>
									@if(Auth::user()->role_id == 1)
										<a href="{{ route('edit-my-profile.edit', Auth::user()->id) }}" >Profile</a>
										@endif
										<a href="#"
										onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
										<i class="mdi mdi-power"></i> Logout
										</a>
										<form id="logout-form" action="{{ route('logout') }}" method="GET" class="d-none">
											@csrf
										</form>
									</li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
	<!-- Left side column. contains the logo and sidebar -->
	@include('layouts.sidebar_employee')

<!-- Content Wrapper. Contains page content -->
	<div class="page-wrapper">
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
<!-- Bootstrap Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">Session Timeout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>You have been inactive for a while. Do you want to stay logged in?</p>
            </div>
            <div class="modal-footer">
                <button type="button" id="logoutCancel" class="btn btn-secondary" data-bs-dismiss="modal">Stay Logged In</button>
                <button type="button" id="logoutConfirm" class="btn btn-danger">Log Out</button>
            </div>
        </div>
    </div>
</div>
<!-- <script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script> -->
<!-- <script src="{{ asset('js/responsive.bootstrap4.min.js') }}"></script> -->
<!-- <script src="{{ asset('js/buttons.bootstrap4.min.js') }}"></script> -->
<!-- <script src="{{ asset('js/adminlte.min.js') }}"></script>
<script src="{{ asset('js/notify.min.js') }}"></script> -->

<script src="{{asset('js/payroljs/jquery.min.js') }}"></script>
 <script src="{{asset('js/payroljs/popper.min.js') }}"></script>
 <script src="{{asset('js/payroljs/bootstrap.min.js') }}"></script>
 <script src="{{asset('js/payroljs/jquery.slimscroll.js') }}"></script>
 <script src="{{asset('js/payroljs/waves.js') }}"></script>
 <script src="{{asset('js/payroljs/sidebarmenu.js') }}"></script>
 <script src="{{asset('js/payroljs/sticky-kit.min.js') }}"></script>
 <script src="{{asset('js/payroljs/custom.min.js') }}"></script>


@yield('third_party_scripts')

@stack('page_scripts')

<script>
    let logoutTimer, modalTimer;

    // Reset the timers when user is active
    function resetTimers() {
        clearTimeout(logoutTimer);
        clearTimeout(modalTimer);
        logoutTimer = setTimeout(showLogoutModal, 5 * 60 * 1000); // 1 minute for testing
    }

    // Show the logout modal
    function showLogoutModal() {
        $('#logoutModal').modal('show'); // Show the Bootstrap modal using jQuery

        modalTimer = setTimeout(() => {
            logoutUser();
        }, 30 * 1000); // Auto logout after 30 seconds
    }

    // Stay logged in
    function stayLoggedIn() {
        $('#logoutModal').modal('hide'); // Hide the modal
        resetTimers();
    }

    // Logout the user
    function logoutUser() {
        window.location.href = '/logout'; // Ensure /logout is a GET route
    }

    // Attach event listeners
    $(document).on('click keypress mousemove scroll touchstart', resetTimers);
    $('#logoutCancel').on('click', stayLoggedIn);
    $('#logoutConfirm').on('click', logoutUser);

    // Initialize the timer
    resetTimers();
</script>

</body>
</html>
