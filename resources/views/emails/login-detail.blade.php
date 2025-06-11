<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Detail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 0;
            margin: 0;
        }

        .container-sec {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 20px;
            margin-top: 30px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
        }

        .otp-code {
            font-size: 24px;
            font-weight: bold;
            background-color: #f8f9fa;
            padding: 15px;
            text-align: center;
            border-radius: 8px;
            border: 1px dashed #F40009;
            color: #F40009;
        }

        .btn-verify {
            display: flex;
            justify-content: center;
            padding: 10px 20px;
            color: #ffffff;
            background-color: #F40009;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }

        .footer-text {
            color: #6c757d;
            font-size: 14px;
            text-align: center;
            margin-top: 20px;
        }

        .footer-text a {
            color: #F40009;
            text-decoration: none;
        }

        .otp-lock {
            color: #333;
            font-size: 80px;
        }

        .welcome-section {
            background: #144fa9db;
            padding: 30px;
            border-radius: 4px;
            color: #fff;
            font-size: 20px;
            margin: 20px 0px;
            text-align: center;
        }

        .welcome-text {
            font-family: monospace;
        }

        .app-name {
            font-size: 30px;
            font-weight: 800;
            margin: 7px 0px;
        }

        .verify-text {
            margin-top: 25px;
            font-size: 25px;
            letter-spacing: 3px;
        }

        i.fas.fa-envelope-open {
            font-size: 35px !important;
            color: #ffffff;
        }
    </style>
</head>

<body>
    <div class="container-sec">
        <div class="text-center">
            <div><i class="fas fa-lock otp-lock"></i></div>
            <div class="welcome-section">
                <div class="app-name">
                    {{ env('APP_NAME') }}
                </div>
                <div class="welcome-text">
                    Your Account Is Now Activated
                </div>

                <div class="verify-text">
                    Please Verify Your Login
                </div>
                <div class="email-icon">
                    <i class="fas fa-envelope-open"></i>
                </div>

            </div>
            <h2>Hello, {{ $user->full_name }}</h2>
            <p>Your Login Credential For username: <b>{{ $user->username }}</b></p>
            <div class="otp-code">{{ $password }}</div>
            <p class="mt-4">Please use this credential to <a href="{{ route('login') }}">login</a></p>
            <a href="{{ route('login') }}" target="_blank" class="btn-verify">Login</a>
        </div>
        <div class="footer-text">
            <p>Thank you,<br>The {{ env('APP_NAME') }} Team</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
