<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reset Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">
            Reset Your Password
        </h2>

        <p class="text-gray-600 mb-4 text-center">
            Enter your username to begin the password reset process.
        </p>

        <form class="space-y-4" action="{{ route('otp-reset-password.grab-user') }}" method="POST">
            @csrf
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input id="username" name="username" type="text" required
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="your.username" />
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded-xl font-medium hover:bg-blue-700 transition">
                Continue
            </button>
        </form>

        <p class="text-sm text-gray-400 text-center mt-6">
            We’ll send a reset link to your registered contact method.
        </p>
    </div>
</body>

</html>
