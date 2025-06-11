<div class="relative font-inter antialiased">
    <main class="relative min-h-screen flex flex-col justify-center bg-slate-50 overflow-hidden">
        <div class="w-full max-w-6xl mx-auto px-4 md:px-6 py-24">
            <div class="flex justify-center">

                @if (Otp::checkIfSmsCanBeSend($otp) || Otp::checkIfEmailCanBeSend($otp))
                    <div class="max-w-md mx-auto text-center bg-white px-4 sm:px-8 py-10 rounded-xl shadow">
                        <header class="mb-8">
                            <h1 class="text-2xl font-bold mb-1">OTP Verification</h1>
                            <p class="text-[15px] text-slate-500">Enter the
                                {{ config('otp.defaults.length') }}-digit
                                verification code that was sent to
                                your
                            <ul>
                                @if (Otp::checkIfEmailCanBeSend($otp))
                                    <li><b>Email : </b> {{ maskEmail($user->getOtpEmail()) }}</li>
                                @endif
                                @if (Otp::checkIfSmsCanBeSend($otp))
                                    <li><b>Phone : </b> {{ maskPhone($user->getOtpPhone()) }}</li>
                                @endif
                            </ul>
                            </p>
                        </header>
                        <form id="otp-form" method="POST"
                            action="{{ route('otp-reset-password.otp.verify', ['otp' => $otp->id]) }}">
                            @csrf
                            <div class="flex items-center justify-center gap-3">
                                @for ($i = 0; $i < config('otp.defaults.length'); $i++)
                                    <input type="text" id="otp-input-{{ $i }}"
                                        class="w-14 h-14 text-center text-2xl font-extrabold text-slate-900 bg-slate-100 border border-transparent hover:border-slate-200 appearance-none rounded p-4 outline-none focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                                        maxlength="1" />
                                @endfor
                            </div>
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
                            <div class="max-w-[260px] mx-auto mt-4">
                                <button type="submit"
                                    class="cursor-pointer w-full inline-flex justify-center whitespace-nowrap rounded-lg bg-indigo-500 px-3.5 py-2.5 text-sm font-medium text-white shadow-sm shadow-indigo-950/10 hover:bg-indigo-600 focus:outline-none focus:ring focus:ring-indigo-300 focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300 transition-colors duration-150">Verify
                                    Account</button>
                            </div>
                        </form>
                        <div class="text-sm text-slate-500 mt-4">Didn't receive code? <a
                                class="font-medium text-indigo-500 hover:text-indigo-600"
                                href="{{ route('otp-reset-password.otp.regenerate', ['otp' => $otp->id]) }}">Resend</a>
                        </div>
                        <div class="text-sm text-slate-500">Choose <a
                                class="font-medium text-indigo-500 hover:text-indigo-600"
                                href="{{ route('otp-reset-password.otp.chooseDifferentMode', ['otp' => $otp->id]) }}">different
                                mode </a> ?
                        </div>
                    </div>
                @else
                    <div class="bg-red-200 px-6 py-4 mx-2 my-4 rounded-md text-lg flex items-center mx-auto max-w-lg">
                        <svg viewBox="0 0 24 24" class="text-red-600 w-5 h-5 sm:w-5 sm:h-5 mr-3">
                            <path fill="currentColor"
                                d="M11.983,0a12.206,12.206,0,0,0-8.51,3.653A11.8,11.8,0,0,0,0,12.207,11.779,11.779,0,0,0,11.8,24h.214A12.111,12.111,0,0,0,24,11.791h0A11.766,11.766,0,0,0,11.983,0ZM10.5,16.542a1.476,1.476,0,0,1,1.449-1.53h.027a1.527,1.527,0,0,1,1.523,1.47,1.475,1.475,0,0,1-1.449,1.53h-.027A1.529,1.529,0,0,1,10.5,16.542ZM11,12.5v-6a1,1,0,0,1,2,0v6a1,1,0,1,1-2,0Z">
                            </path>
                        </svg>
                        <span class="text-red-800"> No email or phone no found. Please contact your administrator to
                            update your necessary contact.</span>
                    </div>
                @endif

            </div>
        </div>
    </main>
</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('otp-form');
        const inputs = [...form.querySelectorAll('input[type=text]')];
        const submit = form.querySelector('button[type=submit]');
        const hiddenField = document.createElement('input');
        hiddenField.type = 'hidden';
        hiddenField.name = 'code';
        form.appendChild(hiddenField);

        const handleKeyDown = (e) => {
            if (
                !/^[0-9]{1}$/.test(e.key) &&
                e.key !== 'Backspace' &&
                e.key !== 'Delete' &&
                e.key !== 'Tab' &&
                !e.metaKey
            ) {
                e.preventDefault();
            }

            if (e.key === 'Delete' || e.key === 'Backspace') {
                const index = inputs.indexOf(e.target);
                if (index > 0) {
                    inputs[index - 1].value = '';
                    inputs[index - 1].focus();
                }
            }
        };

        const handleInput = (e) => {
            const {
                target
            } = e;
            const index = inputs.indexOf(target);
            if (target.value) {
                if (index < inputs.length - 1) {
                    inputs[index + 1].focus();
                } else {
                    submit.focus();
                }
            }
            updateHiddenField();
        };

        const handleFocus = (e) => {
            e.target.select();
        };

        const handlePaste = async (e) => {
            e.preventDefault(); // Prevent default paste behavior
            console.log('paste');
            try {
                const text = await navigator.clipboard.readText(); // Get text from clipboard
                console.log("Pasted Text:", text);

                // Validate input: only numbers of the correct length
                if (!new RegExp(`^[0-9]{${inputs.length}}$`).test(text)) {
                    return;
                }

                // Split text into individual digits and fill the inputs
                const digits = text.split('');
                inputs.forEach((input, index) => input.value = digits[index]);

                updateHiddenField();
                submit.focus();
            } catch (error) {
                console.error("Failed to read clipboard data:", error);
            }
        };

        const updateHiddenField = () => {
            hiddenField.value = inputs.map(input => input.value).join('');
        };

        inputs.forEach((input) => {
            input.addEventListener('input', handleInput);
            input.addEventListener('keydown', handleKeyDown);
            input.addEventListener('focus', handleFocus);
            input.addEventListener('paste', handlePaste);
            input.addEventListener('keydown', (e) => {
                // Windows
                if (e.ctrlKey && e.key === 'v') {
                    handlePaste(e);
                }
                // Mac
                if (e.metaKey && e.key === 'v') {
                    handlePaste(e);
                }
            });
        });

        const setFocusToTextBox = () => {
            document.getElementById("otp-input-0").focus();
        }
    });
</script>
