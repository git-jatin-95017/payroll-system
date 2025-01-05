<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }}</title>
    <!-- Add CSRF Token Meta Tag -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    @yield('third_party_stylesheets')
    @stack('page_css')
    <!-- fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">
    <!-- style -->
    <link href=" {{ asset('css/siteCss/bootstrap.min.css') }}" rel="stylesheet">
    <link href=" {{ asset('css/siteCss/site.css') }}" rel="stylesheet">
    <link href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet">
    <style>
        body {
            background: #fff;
        }
    </style>
</head>

<body>
    <div id="logo">
        <span class="big-logo">
            <a href="">
                <img src="{{ asset('img/paywiz-logo.png') }}" alt="logo">
            </a>
        </span>
        <span class="small-logo">S!M</span>
    </div>
    <div id="left-menu" class="d-flex flex-column">
        @if(auth()->user()->role_id == 1)
            @include('layouts.admin_sidebar_new')
        @elseif(auth()->user()->role_id == 2)
            @include('layouts.client_sidebar_new')    
        @else
            @include('layouts.employee_sidebar_new')
        @endif
    </div>


    <!-- Content Wrapper. Contains page content -->
    <div id="main-content">
        <div id="header" class="px-4">
            <div class="d-flex justify-content-end align-items-center">
                <div>
                    <i id="toggle-left-menu" class="ion-android-menu"></i>
                </div>
                <div>
                    <ul class="d-flex m-0 p-0 gap-3 align-items-center top-right-bar">
                        <li>
                            <x-bxs-bell class="w-20 h-20" />
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
        <div id="page-container" class="p-4">
            @yield('content')
        </div>
    </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    @yield('third_party_scripts')

    <script>
        $('#toggle-left-menu').click(function () {
            if ($('#left-menu').hasClass('small-left-menu')) {
                $('#left-menu').removeClass('small-left-menu');
            } else {
                $('#left-menu').addClass('small-left-menu');
            }
            $('#logo').toggleClass('small-left-menu');
            $('#main-content').toggleClass('small-left-menu');
            $('#header .header-left').toggleClass('small-left-menu');

            $('#logo .big-logo').toggle('300');
            $('#logo .small-logo').toggle('300');
            $('#logo').toggleClass('p-0 pl-1');
        });
    </script>
    @stack('page_scripts')
</body>

</html>