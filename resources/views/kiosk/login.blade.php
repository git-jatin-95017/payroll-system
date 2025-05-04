<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.name') }} - Kiosk</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
            background-color: white;
        }

        .logo {
            margin-bottom: 48px;
        }

        .logo img {
            max-width: 300px;
            height: auto;
        }

        .form-container {
            width: 100%;
            max-width: 400px;
            padding: 0 20px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-size: 16px;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            height: 48px;
            padding: 8px 16px;
            font-size: 16px;
            border: 1px solid #E0E0E0;
            border-radius: 8px;
            box-sizing: border-box;
        }

        .form-control:focus {
            outline: none;
            border-color: #A66FCE;
        }

        .form-control::placeholder {
            color: #757575;
        }

        .step-indicator {
            position: fixed;
            bottom: 24px;
            left: 0;
            right: 0;
            text-align: center;
            color: #666;
            font-size: 14px;
        }

        @media (max-width: 480px) {
            .logo img {
                max-width: 240px;
            }
        }
    </style>
</head>
<body>
    <div class="logo">
        <img src="{{ asset('img/big-logo.svg') }}" alt="PayWiz Kiosk">
    </div>

    <div class="form-container">
        <form method="POST" action="{{ route('kiosk.process-login') }}">
            @csrf
            <div class="form-group">
                <label for="company_name" class="form-label">Company Name</label>
                <input 
                    type="text" 
                    class="form-control @error('company_name') is-invalid @enderror" 
                    id="company_name" 
                    name="company_name" 
                    placeholder="Enter Company Name" 
                    required 
                    value="{{ old('company_name') }}"
                >
                @error('company_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </form>
    </div>

    <!-- <div class="step-indicator">
        Step 1 of 6
    </div> -->
</body>
</html> 