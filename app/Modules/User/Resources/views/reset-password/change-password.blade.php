<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Change Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .info-box {
            background-color: #dbeafe;
            border-left: 4px solid #3b82f6;
            padding: 16px;
            margin: 24px 0;
            border-radius: 0 8px 8px 0;
        }

        .info-title {
            color: #1e40af;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 16px;
        }


        .info-list {
            color: #1d4ed8;
            font-size: 14px;
            margin: 0;
            padding-left: 0;
            list-style: none;
        }
    </style>
</head>

<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">
            Change Your Password
        </h2>

        <form class="space-y-5" action="{{ route('otp-reset-password.update-password', ['otp' => $otp->id]) }}"
            method="POST">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                <input type="password" name="password" placeholder="New password"
                    class="w-full border px-4 py-2 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" />
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                <input type="password" name="password_confirmation" placeholder="Re-enter new password"
                    class="w-full border px-4 py-2 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded-xl hover:bg-blue-700 transition font-semibold">
                Update Password
            </button>

            @if ($errors->any())
                <ul>
                    @foreach ($errors->all() as $error)
                        <div
                            class="bg-red-200 px-6 py-4 mx-2 my-4 rounded-md text-lg flex items-center mx-auto max-w-lg">
                            <svg viewBox="0 0 24 24" class="text-red-600 w-5 h-5 sm:w-5 sm:h-5 mr-3">
                                <path fill="currentColor"
                                    d="M11.983,0a12.206,12.206,0,0,0-8.51,3.653A11.8,11.8,0,0,0,0,12.207,11.779,11.779,0,0,0,11.8,24h.214A12.111,12.111,0,0,0,24,11.791h0A11.766,11.766,0,0,0,11.983,0ZM10.5,16.542a1.476,1.476,0,0,1,1.449-1.53h.027a1.527,1.527,0,0,1,1.523,1.47,1.475,1.475,0,0,1-1.449,1.53h-.027A1.529,1.529,0,0,1,10.5,16.542ZM11,12.5v-6a1,1,0,0,1,2,0v6a1,1,0,1,1-2,0Z">
                                </path>
                            </svg>
                            <span class="text-red-800"> {{ $error }} </span>
                        </div>
                    @endforeach
                </ul>
                <div class="info-box">
                    <div class="info-title">
                        <i class="fas fa-shield-alt" style="margin-right: 8px;"></i>
                        Password Security Tips:
                    </div>
                    <ul class="info-list">
                        <li>• Use a combination of uppercase, lowercase, numbers, and symbols</li>
                        <li>• Make it at least 8 characters long</li>
                        <li>• Avoid using personal information or common words</li>
                        <li>• Don't reuse previous passwords</li>
                    </ul>
                </div>
            @endif
        </form>

        <p class="text-sm text-gray-400 text-center mt-6">
            Make sure your new password is strong and secure.
        </p>
    </div>
</body>

</html>
