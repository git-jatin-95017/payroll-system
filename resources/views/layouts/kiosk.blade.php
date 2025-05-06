<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Kiosk - Time Clock</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        html {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        
        body {
            min-height: 100%;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            display: flex;
            flex-direction: column;
            position: relative;
        }
        
        .kiosk-container {
            flex: 1 0 auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            margin-bottom: 80px; /* Height of footer */
            margin-top: 80px; /* Height of header */
        }

        /* First step (company entry) shouldn't have top margin */
        .first-step .kiosk-container {
            margin-top: 0;
        }

        .kiosk-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background-color: white;
            z-index: 1030;
            height: 70px;
            display: flex;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .kiosk-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #6f42c1;
            z-index: 1030;
            height: 70px;
            display: flex;
            align-items: center;
        }
        
        @media (max-width: 768px) {
            .kiosk-container {
                padding: 15px;
            }
            
            .btn-lg {
                padding: 0.75rem 1.5rem;
                font-size: 1.1rem;
            }
            
            h1 {
                font-size: 1.8rem;
            }
            
            .display-4 {
                font-size: 2rem;
            }
        }
        
        @media (max-width: 576px) {
            .kiosk-container {
                padding: 10px;
            }
            
            .btn-lg {
                padding: 0.5rem 1rem;
                font-size: 1rem;
            }
            
            h1 {
                font-size: 1.5rem;
            }
            
            .display-4 {
                font-size: 1.8rem;
            }
        }
        
        /* iOS specific styles */
        @supports (-webkit-touch-callout: none) {
            input, textarea, button {
                font-size: 16px !important; /* Prevents zoom on focus */
            }
            
            .form-control {
                -webkit-appearance: none;
                border-radius: 4px;
            }
        }
        
        /* Touch-friendly buttons */
        .btn {
            touch-action: manipulation;
            -webkit-tap-highlight-color: transparent;
        }
        
        /* Prevent text selection */
        .no-select {
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        
        /* Smooth scrolling */
        .smooth-scroll {
            -webkit-overflow-scrolling: touch;
            scroll-behavior: smooth;
        }
        
        /* Better tap targets for mobile */
        .form-control, .btn {
            min-height: 44px;
        }
        
        /* Improved contrast for accessibility */
        .text-muted {
            color: #6c757d !important;
        }
        
        .badge {
            font-weight: 500;
        }
    </style>
    
    @stack('styles')
</head>
<body class="{{ Request::is('kiosk') ? 'first-step' : '' }}">
    <!-- Include header except for the first step -->
    @if(!Request::is('kiosk'))
        @include('components.kiosk-header')
    @endif

    <div class="kiosk-container">
        @yield('content')
    </div>

    <!-- Include footer for all pages -->
    @include('components.kiosk-footer')
    
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html> 