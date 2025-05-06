<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }}</title>
    <!-- Add CSRF Token Meta Tag -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    @yield('third_party_stylesheets')
    @stack('page_css')
    <!-- fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">
    <!-- style -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/siteCss/daterangepicker.css') }}" />
    <link href=" {{ asset('css/siteCss/bootstrap.min.css') }}" rel="stylesheet">
    <link href=" {{ asset('css/siteCss/site.css') }}" rel="stylesheet">
    <style>
        body {
            background: #F6F6F4;
        }
    </style>
    @stack('styles')
</head>

<body>
    <div id="left-menu">
        <div class="sidebar-logo">
            <button id="toggle-left-menu">
                <x-heroicon-m-bars-3 />
            </button>
            <a href="">
                @if(!empty(auth()->user()->companyProfile->logo))
                <img src="/files/{{auth()->user()->companyProfile->logo}}" style="widht:150px; height:31px;" alt="logo">
                @else
                <img src="{{ asset('img/paywiz-logo.png') }}" alt="logo">
                @endif
            </a>
            <span class="small-logo">Pay</span>
        </div>
        <div class="d-flex flex-column sidebar-nav">
            @if(auth()->user()->role_id == 1)
            @include('layouts.admin_sidebar_new')
            @elseif(auth()->user()->role_id == 2)
            @include('layouts.client_sidebar_new')
            @else
            @include('layouts.employee_sidebar_new')
            @endif
        </div>
    </div>

    <!-- Content Wrapper. Contains page content -->
    <div id="main-content">
        <div id="header" class="px-4">
            <div class="d-flex justify-content-end align-items-center">
                <div>
                    <ul class="d-flex m-0 p-0 gap-3 align-items-center top-right-bar">
                        <li>
                            <!-- <x-bxs-bell class="w-20 h-20" /> -->
                        </li>
                        <li>
                            <x-bxs-help-circle class="w-20 h-20" />
                        </li>
                        <li>
                            <div class="user-top">
                                <x-bxs-user-circle />
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div id="page-container" class="p-4 h-100">
            @yield('content')
        </div>
    </div>

    <!-- Modal container -->
    <div id="modal-container">
        @stack('modals')
    </div>

    <script src="{{asset('js/sitejs/libs/jquery.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="{{asset('js/sitejs/datepicker/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/sitejs/datepicker/daterangepicker.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/sitejs/main.js')}}"></script>
    @yield('third_party_scripts')
    @stack('page_scripts')
    @stack('scripts')
</body>

</html>