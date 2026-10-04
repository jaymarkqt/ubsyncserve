<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="view-transition" content="same-origin">
    <title>Login | UB Sync</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }

        html, body {
            background-color: #e5e7eb;
            margin: 0;
            padding: 0;
            height: 100%;
        }

        body {
            background-image: url("{{ asset('img/backgroundlogo.png') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 1rem;
        }

        .login-box {
            background-color: #ffffff;
            border-radius: 2rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
            width: 100%;
            max-width: 420px;
            padding: 2.5rem 1.5rem;
            animation: fadeIn 0.4s ease-out forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (min-width: 640px) {
            .login-box { padding: 3.5rem 3rem; }
        }

        .custom-input {
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            padding: 0.85rem 1rem;
            width: 100%;
            font-weight: 500;
            font-size: 1rem;
            background-color: #ffffff;
            color: #1a1a1a;
            box-sizing: border-box;
            transition: all 0.3s ease;
        }

        .custom-input:focus {
            outline: none;
            border-color: #800000;
            box-shadow: 0 0 0 3px rgba(128, 0, 0, 0.1);
        }

        .ub-maroon-btn {
            background-color: #800000;
            color: #ffffff;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .ub-maroon-btn:hover:not(:disabled) {
            background-color: #660000;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(128, 0, 0, 0.2);
        }

        .ub-maroon-btn:active:not(:disabled) {
            transform: translateY(0);
        }
    </style>
</head>
<body>

    <div class="login-box" x-data="authForm()" x-cloak>

        <!-- Login Form -->
        <template x-if="mode === 'login'">
            <div>
                <div class="text-center mb-10">
                    <img src="{{ asset('img/ublogo.png') }}" alt="UB Logo" class="w-48 sm:w-70 h-auto mx-auto mb-1">
                    <p class="text-black-500 text-sm font-medium">Please enter your details</p>
                </div>

                <form @submit.prevent="handleLogin" class="space-y-6">

                    <div x-show="errorMessage" x-transition
                         class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-[12px] mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <span x-text="errorMessage"></span>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-[11px] font-bold text-black-700 uppercase tracking-wider ml-1">Email</label>
                        <input type="email" x-model="email" id="email" required
                               class="custom-input" placeholder="Enter your email">
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-[11px] font-bold text-black-700 uppercase tracking-wider ml-1">Password</label>
                        <input :type="showPassword ? 'text' : 'password'" x-model="password" id="password" required
                               class="custom-input" placeholder="Enter your password">
                    </div>

                    <div class="flex flex-row items-center justify-between px-1">
                        <div class="flex items-center cursor-pointer select-none" @click="showPassword = !showPassword">
                            <input type="checkbox" x-model="showPassword"
                                   class="w-4 h-4 rounded border-gray-300 accent-[#800000] cursor-pointer transition-all duration-300">
                            <label class="ml-2 text-[12px] font-semibold text-black-600 cursor-pointer">
                                Show password
                            </label>
                        </div>

                        <button type="button" @click="switchToForgot()" class="text-[12px] font-semibold text-black-400 hover:text-[#800000] transition-colors duration-300">
                            Forgot Password?
                        </button>
                    </div>

                    <button type="submit" :disabled="isLoading" class="w-full ub-maroon-btn font-bold py-4 rounded-xl uppercase tracking-widest text-[12px] mt-4 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!isLoading">Log In</span>
                        <span x-show="isLoading">Logging in...</span>
                    </button>
                </form>

                <div class="text-center mt-6">
                    <p class="text-[12px] font-semibold text-gray-500 mb-3">Don't have an account?</p>
                    <a href="{{ route('create-account') }}" class="block w-full border border-[#800000] text-[#800000] hover:bg-[#800000] hover:text-white font-bold py-3 rounded-xl uppercase tracking-widest text-[11px] transition-colors duration-300">
                        Create Account
                    </a>
                </div>
            </div>
        </template>

        <!-- Forgot Password - Step 1 -->
        <template x-if="mode === 'forgotStep1'">
            <div>
                <div class="text-center mb-10">
                    <img src="{{ asset('img/ublogo.png') }}" alt="UB Logo" class="w-48 sm:w-70 h-auto mx-auto mb-1">
                    <p class="text-black-500 text-sm font-medium">Reset Your Password</p>
                </div>

                <form @submit.prevent="verifyEmail" class="space-y-6">

                    <div x-show="errorMessage" x-transition
                         class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-[12px] mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <span x-text="errorMessage"></span>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-[11px] font-bold text-black-700 uppercase tracking-wider ml-1">Enter Your Email</label>
                        <input type="email" x-model="email" required
                               class="custom-input" placeholder="Enter registered email">
                    </div>

                    <button type="submit" :disabled="isLoading" class="w-full ub-maroon-btn font-bold py-4 rounded-xl uppercase tracking-widest text-[12px] disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!isLoading">Send Verification Code</span>
                        <span x-show="isLoading">Sending code...</span>
                    </button>

                    <div class="text-center">
                        <button type="button" @click="switchToLogin()" class="text-[12px] font-semibold text-[#800000] hover:text-gray-700 transition-colors duration-300">
                            Back to Login
                        </button>
                    </div>
                </form>
            </div>
        </template>

        <!-- Forgot Password - Verify OTP -->
        <template x-if="mode === 'forgotStep2'">
            <div>
                <div class="text-center mb-10">
                    <img src="{{ asset('img/ublogo.png') }}" alt="UB Logo" class="w-48 sm:w-70 h-auto mx-auto mb-1">
                    <p class="text-black-500 text-sm font-medium">Verify Your Email</p>
                </div>

                <form @submit.prevent="verifyResetCode" class="space-y-6">
                    <div x-show="errorMessage" x-transition
                         class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-[12px] mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <span x-text="errorMessage"></span>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg text-[12px]">
                        <span x-text="successMessage"></span>
                        Enter the 6-digit code sent to <strong x-text="email"></strong>.
                    </div>

                    <div class="space-y-1.5">
                        <label for="password-reset-code" class="block text-[11px] font-bold text-black-700 uppercase tracking-wider ml-1">6-Digit Verification Code</label>
                        <input type="text" id="password-reset-code" x-model="verificationCode" required
                               inputmode="numeric" maxlength="6" pattern="[0-9]{6}"
                               class="custom-input text-center tracking-[0.5em]" placeholder="000000">
                    </div>

                    <p class="text-center text-[12px] font-semibold text-gray-500">
                        <span x-show="otpSecondsRemaining > 0">Code expires in <span class="text-[#800000]" x-text="formatOtpTime()"></span>.</span>
                        <span x-show="otpSecondsRemaining === 0" class="text-red-600">The verification code has expired.</span>
                    </p>

                    <button type="submit" :disabled="isLoading || otpSecondsRemaining === 0" class="w-full ub-maroon-btn font-bold py-4 rounded-xl uppercase tracking-widest text-[12px] disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!isLoading">Verify Code</span>
                        <span x-show="isLoading">Verifying...</span>
                    </button>

                    <button x-show="otpSecondsRemaining === 0" type="button" @click="verifyEmail()" :disabled="isLoading"
                            class="w-full text-[12px] font-semibold text-[#800000] hover:text-gray-800 py-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        Send a new code
                    </button>

                    <button type="button" @click="switchToForgotStep1()" class="w-full text-[12px] font-semibold text-black-600 hover:text-gray-800 py-2 transition-colors duration-300">
                        Back
                    </button>
                </form>
            </div>
        </template>

        <!-- Forgot Password - Set New Password -->
        <template x-if="mode === 'forgotStep3'">
            <div>
                <div class="text-center mb-10">
                    <img src="{{ asset('img/ublogo.png') }}" alt="UB Logo" class="w-48 sm:w-70 h-auto mx-auto mb-1">
                    <p class="text-black-500 text-sm font-medium">Choose a New Password</p>
                </div>

                <form @submit.prevent="resetPassword" class="space-y-6">
                    <div x-show="errorMessage" x-transition
                         class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-[12px]">
                        <span x-text="errorMessage"></span>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg text-[12px]">
                        Resetting password for: <strong x-text="email"></strong>
                    </div>

                    <div class="space-y-1.5">
                        <label for="new-password" class="block text-[11px] font-bold text-black-700 uppercase tracking-wider ml-1">New Password</label>
                        <input :type="showPassword ? 'text' : 'password'" x-model="newPassword" id="new-password"
                               required minlength="4" class="custom-input" placeholder="Enter new password">
                    </div>

                    <div class="space-y-1.5">
                        <label for="confirm-new-password" class="block text-[11px] font-bold text-black-700 uppercase tracking-wider ml-1">Confirm Password</label>
                        <input :type="showPassword ? 'text' : 'password'" x-model="confirmPassword" id="confirm-new-password"
                               required minlength="4" class="custom-input" placeholder="Confirm new password">
                    </div>

                    <div class="flex items-center cursor-pointer select-none" @click="showPassword = !showPassword">
                        <input type="checkbox" x-model="showPassword"
                               class="w-4 h-4 rounded border-gray-300 accent-[#800000] cursor-pointer transition-all duration-300">
                        <label class="ml-2 text-[12px] font-semibold text-black-600 cursor-pointer">
                            Show password
                        </label>
                    </div>

                    <button type="submit" :disabled="isLoading" class="w-full ub-maroon-btn font-bold py-4 rounded-xl uppercase tracking-widest text-[12px] disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!isLoading">Reset Password</span>
                        <span x-show="isLoading">Updating...</span>
                    </button>
                </form>
            </div>
        </template>

        <!-- Forgot Password - Success -->
        <template x-if="mode === 'forgotSuccess'">
            <div class="space-y-6 text-center">
                <div class="text-center mb-10">
                    <img src="{{ asset('img/ublogo.png') }}" alt="UB Logo" class="w-48 sm:w-70 h-auto mx-auto mb-1">
                </div>
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-4 rounded-lg">
                    <p class="text-[14px] font-bold mb-2">✓ Password Reset Successful!</p>
                    <p class="text-[12px]">Your password has been updated successfully.</p>
                </div>
                <button type="button" @click="switchToLogin()" class="w-full ub-maroon-btn font-bold py-4 rounded-xl uppercase tracking-widest text-[12px]">
                    Back to Login
                </button>
            </div>
        </template>

    </div>

    <script>
        function authForm() {
            return {
                mode: 'login',
                email: '',
                password: '',
                verificationCode: '',
                resetToken: '',
                otpSecondsRemaining: 0,
                otpTimer: null,
                newPassword: '',
                confirmPassword: '',
                showPassword: false,
                errorMessage: '',
                successMessage: '',
                isLoading: false,

                showError(message) {
                    this.errorMessage = message;
                    setTimeout(() => {
                        this.errorMessage = '';
                    }, 2000);
                },

                formatOtpTime() {
                    const minutes = Math.floor(this.otpSecondsRemaining / 60);
                    const seconds = this.otpSecondsRemaining % 60;

                    return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                },

                startOtpTimer() {
                    clearInterval(this.otpTimer);
                    this.otpSecondsRemaining = 60;
                    this.otpTimer = setInterval(() => {
                        if (this.otpSecondsRemaining > 0) {
                            this.otpSecondsRemaining--;
                        }

                        if (this.otpSecondsRemaining === 0) {
                            clearInterval(this.otpTimer);
                        }
                    }, 1000);
                },

                switchToLogin() {
                    clearInterval(this.otpTimer);
                    this.mode = 'login';
                    this.email = '';
                    this.password = '';
                    this.verificationCode = '';
                    this.resetToken = '';
                    this.otpSecondsRemaining = 0;
                    this.newPassword = '';
                    this.confirmPassword = '';
                    this.showPassword = false;
                    this.errorMessage = '';
                    this.successMessage = '';
                    this.isLoading = false;
                },

                switchToForgot() {
                    clearInterval(this.otpTimer);
                    this.mode = 'forgotStep1';
                    this.email = '';
                    this.password = '';
                    this.verificationCode = '';
                    this.resetToken = '';
                    this.otpSecondsRemaining = 0;
                    this.newPassword = '';
                    this.confirmPassword = '';
                    this.showPassword = false;
                    this.errorMessage = '';
                    this.successMessage = '';
                    this.isLoading = false;
                },

                switchToForgotStep1() {
                    clearInterval(this.otpTimer);
                    this.mode = 'forgotStep1';
                    this.verificationCode = '';
                    this.resetToken = '';
                    this.otpSecondsRemaining = 0;
                    this.newPassword = '';
                    this.confirmPassword = '';
                    this.errorMessage = '';
                    this.successMessage = '';
                },

                async handleLogin() {
                    this.errorMessage = '';

                    if (!this.email || !this.password) {
                        this.showError('Please enter both email and password');
                        return;
                    }

                    this.isLoading = true;

                    try {
                        const response = await fetch("{{ route('login.post') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                email: this.email,
                                password: this.password
                            })
                        });

                        const data = await response.json();
                        if (!response.ok) {
                            throw new Error(data.message || Object.values(data.errors || {}).flat()[0] || 'Invalid credentials.');
                        }

                        window.location.href = data.redirect;
                    } catch (error) {
                        this.showError(error.message);
                    } finally {
                        this.isLoading = false;
                    }
                },

                async verifyEmail() {
                    this.errorMessage = '';
                    this.successMessage = '';

                    if (!this.email) {
                        this.showError('Please enter your email');
                        return;
                    }

                    this.isLoading = true;

                    try {
                        const response = await fetch("{{ route('password-reset.send-code') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ email: this.email })
                        });

                        const data = await response.json();
                        if (!response.ok) {
                            throw new Error(data.message || Object.values(data.errors || {}).flat()[0] || 'Unable to send verification code.');
                        }

                        this.successMessage = data.message;
                        this.verificationCode = '';
                        this.mode = 'forgotStep2';
                        this.startOtpTimer();
                    } catch (error) {
                        this.showError(error.message);
                    } finally {
                        this.isLoading = false;
                    }
                },

                async verifyResetCode() {
                    this.errorMessage = '';

                    if (!/^\d{6}$/.test(this.verificationCode)) {
                        this.showError('Enter the 6-digit verification code');
                        return;
                    }

                    this.isLoading = true;

                    try {
                        const response = await fetch("{{ route('password-reset.verify-code') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ email: this.email, code: this.verificationCode })
                        });

                        const data = await response.json();
                        if (!response.ok) {
                            throw new Error(data.message || Object.values(data.errors || {}).flat()[0] || 'Unable to verify code.');
                        }

                        this.resetToken = data.reset_token;
                        this.mode = 'forgotStep3';
                    } catch (error) {
                        this.showError(error.message);
                    } finally {
                        this.isLoading = false;
                    }
                },

                async resetPassword() {
                    this.errorMessage = '';

                    if (!this.newPassword || !this.confirmPassword) {
                        this.showError('Please fill in all password fields');
                        return;
                    }

                    if (this.newPassword.length < 4) {
                        this.showError('Password must be at least 4 characters');
                        return;
                    }

                    if (this.newPassword !== this.confirmPassword) {
                        this.showError('Passwords do not match');
                        return;
                    }

                    this.isLoading = true;

                    try {
                        const response = await fetch("{{ route('password-reset.reset') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                email: this.email,
                                reset_token: this.resetToken,
                                password: this.newPassword,
                                password_confirmation: this.confirmPassword
                            })
                        });

                        const data = await response.json();
                        if (!response.ok) {
                            throw new Error(data.message || Object.values(data.errors || {}).flat()[0] || 'Unable to reset password.');
                        }

                        this.successMessage = data.message;
                        this.mode = 'forgotSuccess';
                    } catch (error) {
                        this.showError(error.message);
                    } finally {
                        this.isLoading = false;
                    }
                }
            }
        }
    </script>
</body>
</html>
