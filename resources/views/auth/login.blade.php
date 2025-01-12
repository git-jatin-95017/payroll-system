<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.name') }}</title>

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap Core CSS -->
    <link href="{{ asset('css/payrollCss/bootstrap.min.css') }}" rel="stylesheet">
    <!-- morris CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/2.0.46/css/materialdesignicons.min.css" rel="stylesheet">
    <link href=" {{ asset('css/payrollCss/morris.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href=" {{ asset('css/siteCss/bootstrap.min.css') }}" rel="stylesheet">
    <link href=" {{ asset('css/siteCss/site.css') }}" rel="stylesheet">
    <link
    href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
    rel="stylesheet">
    <style>
    body {
      background-color: #f9f9f9;
      font-family: "Inter", serif;
    }

    .login-container {
      min-height: 100vh;
    }

    .login-box {
        box-shadow: 0 2px 3px rgba(0, 0, 0, 0.1);
        max-width: 450px;
        border-radius: 6px;
        border: 1px solid #BABABE;
        padding: 36px;
    }

    .btn-login {
        background-color: #A66FCE;
        color: #fff;
        border-radius: 0.5rem;
        padding: 0.6rem 1rem;
        height: 48px;
        font-weight: 500;
    }

    .btn-login:hover {
      background-color: #5a53c9;
    }

    .btn-outline-signup {
      border: 1px solid #A66FCE;
      color: #A66FCE;
      border-radius: 0.5rem;
      padding: 0.6rem 1rem;
    }

    .btn-outline-signup:hover {
      background-color: #A66FCE;
      color: #fff;
    }
    .login-input-container input {
        background-color: #F1F3F6;
        height: 48px;
        border-color: transparent;
        border-radius: 8px;
        font-size: 16px;
    }
    .login-input-container input:focus{
        box-shadow: none;
        outline: none;
        border-color: #a9a9a9;
    }
    .login-input-container input::placeholder{
        color: #555555 !important;
    }
    .login-input-container .login-icon-container {
        border: transparent;
        border-radius: 8px !important;
        background: #A66FCE;
        color: #fff;
        width: 48px;
        justify-content: center;
    }
    .login-input-container label{
        font-size: 16px;
        font-weight: 500;
        color: #252525;
    }
    .login-header h3{
        color: #252525;
        font-size: 22px;
        font-weight: 600;
    }
    a.forget-password {
        color: #1E2772;
        font-size: 14px;
        font-weight: 500;
    }
    .or-line {
        position: relative;
    }
    .or-line:before {
        content: "";
        position: absolute;
        height: 1px;
        background: #C2C2C2;
        left: 0;
        right: 0;
        top: 12px;
    }
    .or-line span {
        background-color: #ffffff;
        position: relative;
        z-index: 10;
        padding: 0 21px;
        display: inline-block;
    }
    </style>
</head>
<body>
<div class="container-fluid login-container d-flex justify-content-center align-items-center ">
    <div class="row w-100">
      <div class="col-md-6 d-none d-md-flex align-items-center justify-content-center">
        <img src="{{ asset('img/login-img.svg') }}" alt="login images">
      </div>
      <div class="col-md-6">
        <div class="login-box mx-auto bg-white">
            @include('layouts.flash-admin-message')
          <div class="login-header">
            <div class="mb-4">
                <img src="{{ asset('img/big-logo.svg') }}" alt="PayWiz Logo">
                <h3 class="mt-4">Sign in</h3>
            </div>
          </div>
          <form method="post" id="loginform" action="{{ url('/login') }}">
          @csrf
            <div class="mb-3 login-input-container">
              <label for="email" class="form-label">Email Address</label>
              <div class="input-group">
                  <input type="email" class="form-control" name="email" value="" required placeholder="Email">
                  <span class="input-group-text login-icon-container">
                      <x-bx-envelope class="w-20 h-20" />
                  </span>
                  @error('email')
                  <span class="error invalid-feedback">{{ $message }}</span>
                @enderror
              </div>
            </div>
            <div class="mb-3 login-input-container">
              <label for="password" class="form-label">Password</label>
              <div class="input-group">
                  <input type="password" class="form-control" name="password" value="" required placeholder="Password">
                  <span class="input-group-text login-icon-container">
                        <x-bx-lock class="w-20 h-20" />
                  </span>
                  @error('password')
                    <span class="error invalid-feedback">{{ $message }}</span>
                @enderror
              </div>
              <div class="text-end mt-2">
                <a href="{{ route('password.request') }}" class="forget-password">Forgot Password?</a>
              </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-login w-100 mb-3" type="submit">Sign in</button>
                <div class="text-center text-muted mb-3 or-line">
                    <span>OR</span>
                </div>
                <button type="button" class="btn btn-outline-signup w-100">Signup Now</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
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
