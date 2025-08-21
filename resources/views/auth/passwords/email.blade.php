<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ config('app.name') }}</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <!-- Styles -->
  <link href="{{ asset('css/payrollCss/bootstrap.min.css') }}" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/2.0.46/css/materialdesignicons.min.css" rel="stylesheet">
  <link href="{{ asset('css/siteCss/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/siteCss/site.css') }}" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    body {
      background-color: #f9f9f9;
      font-family: 'Inter', sans-serif;
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

    .login-input-container input {
      background-color: #F1F3F6;
      height: 48px;
      border-color: transparent;
      border-radius: 8px;
      font-size: 16px;
    }

    .login-input-container input:focus {
      box-shadow: none;
      outline: none;
      border-color: #a9a9a9;
    }

    .login-input-container input::placeholder {
      color: #555555 !important;
    }

    .login-input-container .login-icon-container {
      background: #A66FCE;
      color: #fff;
      width: 48px;
      justify-content: center;
      display: flex;
      align-items: center;
      border-radius: 8px !important;
    }

    .login-input-container label {
      font-size: 16px;
      font-weight: 500;
      color: #252525;
    }

    .login-header h3 {
      color: #252525;
      font-size: 22px;
      font-weight: 600;
    }
  </style>
</head>
<body>
  <div class="container-fluid login-container d-flex justify-content-center align-items-center">
    <div class="row w-100">
      <div class="col-md-6 d-none d-md-flex align-items-center justify-content-center">
        <img src="{{ asset('img/login-img.svg') }}" alt="Forgot password image">
      </div>
      <div class="col-md-6">
        <div class="login-box mx-auto bg-white">
          <div class="login-header mb-4">
            <img src="{{ asset('img/big-logo.svg') }}" alt="PayWiz Logo">
            <h3 class="mt-4">Forgot Password</h3>
            <p class="mt-2">Enter your email address and we’ll send you a link to reset your password.</p>
          </div>

          @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
          @endif

          <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-3 login-input-container">
              <label for="email" class="form-label">Email Address</label>
              <div class="input-group">
                <input 
                  type="email" 
                  name="email" 
                  id="email"
                  class="form-control @error('email') is-invalid @enderror" 
                  placeholder="Enter your email" 
                  required 
                  value="{{ old('email') }}"
                >
                <span class="input-group-text login-icon-container">
                  <x-bx-envelope class="w-20 h-20" />
                </span>
              </div>
              @error('email')
                <span class="invalid-feedback d-block">{{ $message }}</span>
              @enderror
            </div>

            <div class="mt-4">
              <button type="submit" class="btn btn-login w-100 mb-3">Send Password Reset Link</button>
              <div class="text-center">
                <a href="{{ route('login') }}" class="forget-password">Back to Login</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="{{ asset('js/payroljs/jquery.min.js') }}"></script>
  <script src="{{ asset('js/payroljs/bootstrap.min.js') }}"></script>
</body>
</html>
