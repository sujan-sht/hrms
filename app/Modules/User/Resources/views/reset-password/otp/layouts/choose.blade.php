<div class="bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">
            Two-Factor Authentication
        </h2>

        <p class="text-gray-600 mb-4 text-center">
            Choose how you'd like to receive your verification code:
        </p>

        <div class="space-y-4">
            @if (
                (!is_null($user->getOtpEmail()) && config('otp.enable.mail', false)) ||
                    (!is_null($user->getOtpPhone()) && config('otp.enable.sms', false)))
                <form action="{{ route('otp-reset-password.otp.chooseMode', ['otp' => $otp->id]) }}" method="POST">
                    @csrf
                    @if (!is_null($user->getOtpPhone()) && config('otp.enable.sms', false))
                        @if (Otp::sparrowCredits() > 0)
                            <button type="submit" name="mode" value="sms"
                                class="w-full flex items-center justify-between p-4 mt-2 border rounded-xl hover:bg-gray-100 transition">
                                <div class="flex items-center space-x-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path d="M3 5h18M3 12h18M3 19h18" />
                                    </svg>
                                    <span>Send code to Phone</span>
                                </div>
                                <span class="text-sm text-gray-500">{{ maskPhone($user->getOtpPhone()) }}</span>
                            </button>
                        @else
                            <div
                                class="bg-red-200 px-6 py-4 mx-2 my-4 rounded-md text-lg flex items-center mx-auto max-w-lg">
                                <svg viewBox="0 0 24 24" class="text-red-600 w-5 h-5 sm:w-5 sm:h-5 mr-3">
                                    <path fill="currentColor"
                                        d="M11.983,0a12.206,12.206,0,0,0-8.51,3.653A11.8,11.8,0,0,0,0,12.207,11.779,11.779,0,0,0,11.8,24h.214A12.111,12.111,0,0,0,24,11.791h0A11.766,11.766,0,0,0,11.983,0ZM10.5,16.542a1.476,1.476,0,0,1,1.449-1.53h.027a1.527,1.527,0,0,1,1.523,1.47,1.475,1.475,0,0,1-1.449,1.53h-.027A1.529,1.529,0,0,1,10.5,16.542ZM11,12.5v-6a1,1,0,0,1,2,0v6a1,1,0,1,1-2,0Z">
                                    </path>
                                </svg>
                                <span class="text-red-800"> SMS otp cannot be send because system does not have enough
                                    credit. Please Contact IT.</span>
                            </div>
                        @endif
                    @endif
                    @if (!is_null($user->getOtpEmail()) && config('otp.enable.mail', false))
                        <button type="submit" name="mode" value="mail"
                            class="w-full flex items-center justify-between p-4 mt-2 border rounded-xl hover:bg-gray-100 transition">
                            <div class="flex items-center space-x-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M16 12H8m8 0l-8 8m8-8l-8-8" />
                                </svg>
                                <span>Send code to Email</span>
                            </div>
                            <span class="text-sm text-gray-500">{{ maskEmail($user->getOtpEmail()) }}</span>
                        </button>
                    @endif
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
                    @endif
                </form>
            @else
                <div class="bg-red-200 px-6 py-4 mx-2 my-4 rounded-md text-lg flex items-center mx-auto max-w-lg">
                    <svg viewBox="0 0 24 24" class="text-red-600 w-5 h-5 sm:w-5 sm:h-5 mr-3">
                        <path fill="currentColor"
                            d="M11.983,0a12.206,12.206,0,0,0-8.51,3.653A11.8,11.8,0,0,0,0,12.207,11.779,11.779,0,0,0,11.8,24h.214A12.111,12.111,0,0,0,24,11.791h0A11.766,11.766,0,0,0,11.983,0ZM10.5,16.542a1.476,1.476,0,0,1,1.449-1.53h.027a1.527,1.527,0,0,1,1.523,1.47,1.475,1.475,0,0,1-1.449,1.53h-.027A1.529,1.529,0,0,1,10.5,16.542ZM11,12.5v-6a1,1,0,0,1,2,0v6a1,1,0,1,1-2,0Z">
                        </path>
                    </svg>
                    <span class="text-red-800"> You don't have sufficient information to enable OTP. Please contact your
                        administrator.</span>
                </div>
            @endif
        </div>

        <p class="text-sm text-gray-400 text-center mt-6">We'll send a one-time code to your selected method.</p>
    </div>
</div>
