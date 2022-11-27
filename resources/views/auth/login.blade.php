<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.name') }}</title>

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css"
          integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog=="
          crossorigin="anonymous"/>
<!-- 
    <link href="{{ asset('css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/icheck-bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/adminlte.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet"> -->
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
</head>
<body>
<!-- <div class="login-box">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a href="/" class="h2">
                <img src="{{URL::asset('img/logo-color.svg')}}" alt="logo">
            </a>
        </div>
        <div class="card-body login-card-body">
            @include('layouts.flash-admin-message')
            <p class="login-box-msg">Sign in to start your session</p>

            <form method="post" action="{{ url('/login') }}">
                @csrf

                <div class="input-group mb-3">
                    <input type="email"
                           name="email"
                           value="{{ old('email') }}"
                           placeholder="Email"
                           class="form-control @error('email') is-invalid @enderror">
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                    </div>
                    @error('email')
                    <span class="error invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-group mb-3">
                    <input type="password"
                           name="password"
                           placeholder="Password"
                           class="form-control @error('password') is-invalid @enderror">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    @error('password')
                    <span class="error invalid-feedback">{{ $message }}</span>
                    @enderror

                </div>

                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember">
                            <label for="remember">Remember Me</label>
                        </div>
                    </div>

                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                    </div>

                </div>
            </form>

            <p class="mb-1">
                <a href="{{ route('password.request') }}">I forgot my password</a>
            </p>
            <p class="mb-0">
            </p>
        </div>
    </div>

</div> -->



<section id="wrapper" class="login-register login-sidebar" style="background-image: url(./img/hrbbg.jpg);">
         <div class="login-box card">
             <div class="card-body loginpage">  
                @include('layouts.flash-admin-message')                                      
                 <form class="form-horizontal form-material" method="post" id="loginform" action="{{ url('/login') }}">
                    @csrf
                     <a href="javascript:void(0)" class="text-center db">
                        <br/><img src="{{URL::asset('/img/logo-color.svg')}}" width="250px" alt="Home" /></a>
                     <div class="form-group m-t-40">
                         <div class="col-xs-12">
                             <input class="form-control" name="email" value="" type="text" required placeholder="Email">
                         </div>
                         @error('email')
                            <span class="error invalid-feedback">{{ $message }}</span>
                         @enderror
                     </div>
                     <div class="form-group">
                         <div class="col-xs-12">
                             <input class="form-control" name="password" value="" type="password" required placeholder="Password">
                         </div>
                         @error('password')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                     </div>
                  <div class="form-check">
                      <input type="checkbox" name="remember" class="form-check-input" id="remember-me">
                      <label class="form-check-label" for="remember-me">Remember Me</label>
                  </div>                     
                     <div class="form-group text-center m-t-20">
                         <div class="col-xs-12">
                             <button class="btn btn-success btn-login btn-block text-uppercase waves-effect waves-light" type="submit">Log In</button>
                         </div>
                     </div>
                 </form>
                 <p class="mb-1 text-center forget-password">
                    <a href="{{ route('password.request') }}">I forgot my password</a>
                </p>
             </div>
         </div>
     </section>





<!-- <script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/adminlte.min.js') }}"></script> -->


<script src="{{asset('js/payroljs/jquery.min.js') }}"></script>
 <script src="{{asset('js/payroljs/popper.min.js') }}"></script>
 <script src="{{asset('js/payroljs/bootstrap.min.js') }}"></script>
 <script src="{{asset('js/payroljs/jquery.slimscroll.js') }}"></script>
 <script src="{{asset('js/payroljs/waves.js') }}"></script>
 <script src="{{asset('js/payroljs/sidebarmenu.js') }}"></script>
 <script src="{{asset('js/payroljs/sticky-kit.min.js') }}"></script>
 <script src="{{asset('js/payroljs/custom.min.js') }}"></script>

</body>
</html>
