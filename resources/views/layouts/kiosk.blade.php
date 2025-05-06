<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Kiosk - Time Clock</title>

    <!-- Bootstrap CSS -->
    <link href=" {{ asset('css/kioskCss/bootstrap.min.css') }}" rel="stylesheet">
    <link href=" {{ asset('css/kioskCss/customKiosk.css') }}" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    @stack('styles')
</head>

<body class="{{ Request::is('kiosk') ? 'first-step' : '' }}">
    <div class="d-flex flex-column justify-content-between h-100">
        <!-- Include header except for the first step -->
        @if(!Request::is('kiosk'))
        @include('components.kiosk-header')
        @endif
        <div class="kiosk-container h-100">
            @yield('content')
        </div>
        <!-- Include footer for all pages -->
        @include('components.kiosk-footer')
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="{{ asset('css/kioskJS/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>

</html>