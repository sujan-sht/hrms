<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title ?? 'OTP Password Reset' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    @if (count($otp->channels ?? []) == 0)
        @include('user::reset-password.otp.layouts.choose')
    @else
        @include('user::reset-password.otp.layouts.form')
    @endif
</body>
