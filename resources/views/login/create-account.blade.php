<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="view-transition" content="same-origin">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Account | UB Sync</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
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
            max-width: 480px;
            padding: 1.25rem 1.5rem;
            animation: fadeIn 0.4s ease-out forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (min-width: 640px) {
            .login-box { padding: 1rem 3rem; }
        }

        .custom-input {
            display: block;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            padding: 0.55rem 1rem;
            width: 100%;
            font-weight: 500;
            font-size: 1rem;
            background-color: #ffffff;
            color: #1a1a1a;
            box-sizing: border-box;
            transition: all 0.3s ease;
        }

        .account-field {
            min-width: 0;
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
    <div class="login-box" x-data="createAccountForm()" x-cloak>
        <div class="text-center mb-8">
            <img src="{{ asset('img/ublogo.png') }}" alt="UB Logo" class="w-48 sm:w-70 h-auto mx-auto mb-1">
            <p class="text-black-500 text-sm font-medium" x-text="mode === 'register' ? 'Create your account' : 'Verify your email'"></p>
        </div>

        <template x-if="mode === 'register'">
        <form @submit.prevent="sendCode" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div x-show="errorMessage" x-transition
                 class="sm:col-span-2 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-[12px]">
                <span x-text="errorMessage"></span>
            </div>

            <div x-show="successMessage" x-transition
                 class="sm:col-span-2 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-[12px]">
                <span x-text="successMessage"></span>
            </div>

            <div class="account-field space-y-1.5">
                <label for="first-name" class="block text-[11px] font-bold text-black-700 uppercase tracking-wider ml-1">First Name</label>
                <input type="text" id="first-name" x-model="firstName" required class="custom-input" placeholder="Enter your first name">
            </div>

            <div class="account-field space-y-1.5">
                <label for="last-name" class="block text-[11px] font-bold text-black-700 uppercase tracking-wider ml-1">Last Name</label>
                <input type="text" id="last-name" x-model="lastName" required class="custom-input" placeholder="Enter your last name">
            </div>

            <div class="account-field space-y-1.5">
                <label for="email" class="block text-[11px] font-bold text-black-700 uppercase tracking-wider ml-1">Email</label>
                <input type="email" id="email" x-model="email" required class="custom-input" placeholder="Enter your email">
            </div>

            <div class="account-field space-y-1.5">
                <label for="role" class="block text-[11px] font-bold text-black-700 uppercase tracking-wider ml-1">Account Type</label>
                <select id="role" x-model="role" required class="custom-input">
                    <option value="waiter">Waiter</option>
                    <option value="manager">Manager</option>
                </select>
            </div>

            <div class="account-field space-y-1.5">
                <label for="password" class="block text-[11px] font-bold text-black-700 uppercase tracking-wider ml-1">Password</label>
                <input type="password" id="password" x-model="password" required minlength="4" class="custom-input" placeholder="Enter your password">
            </div>

            <div class="account-field space-y-1.5">
                <label for="confirm-password" class="block text-[11px] font-bold text-black-700 uppercase tracking-wider ml-1">Confirm Password</label>
                <input type="password" id="confirm-password" x-model="confirmPassword" required minlength="4" class="custom-input" placeholder="Confirm your password">
            </div>

            <button type="submit" :disabled="isLoading" class="sm:col-span-2 w-full ub-maroon-btn font-bold py-2.5 rounded-xl uppercase tracking-widest text-[12px] mt-1 disabled:opacity-50 disabled:cursor-not-allowed">
                <span x-show="!isLoading">Send Verification Code</span>
                <span x-show="isLoading">Sending code...</span>
            </button>

            <a href="{{ route('login') }}" class="sm:col-span-2 block w-full text-center text-[12px] font-semibold text-gray-600 hover:text-[#800000] py-1 transition-colors duration-300">
                Back to Login
            </a>
        </form>
        </template>

        <template x-if="mode === 'verify'">
            <form @submit.prevent="verifyCode" class="space-y-4">
                <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg text-[12px]">
                    We sent a 6-digit verification code to <strong x-text="email"></strong>.
                </div>

                <div x-show="errorMessage" x-transition class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-[12px]">
                    <span x-text="errorMessage"></span>
                </div>

                <div class="space-y-1.5">
                    <label for="verification-code" class="block text-[11px] font-bold text-black-700 uppercase tracking-wider ml-1">Verification Code</label>
                    <input type="text" id="verification-code" x-model="code" required inputmode="numeric" maxlength="6" pattern="[0-9]{6}" class="custom-input text-center tracking-[0.5em]" placeholder="000000">
                </div>

                <p class="text-center text-[12px] font-semibold text-gray-500">
                    Code expires in <span class="text-[#800000]" x-text="secondsRemaining + ' seconds'"></span>
                </p>

                <button type="submit" :disabled="isLoading || secondsRemaining === 0" class="w-full ub-maroon-btn font-bold py-2.5 rounded-xl uppercase tracking-widest text-[12px] disabled:opacity-50 disabled:cursor-not-allowed">
                    <span x-show="!isLoading">Verify Code</span>
                    <span x-show="isLoading">Verifying...</span>
                </button>

                <button type="button" @click="mode = 'register'" class="w-full text-[12px] font-semibold text-gray-600 hover:text-[#800000] py-1 transition-colors duration-300">
                    Back to registration
                </button>
            </form>
        </template>

        <div x-show="successMessage" x-transition class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-[12px] mt-4">
            <span x-text="successMessage"></span>
        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        function createAccountForm() {
            return {
                mode: 'register',
                firstName: '',
                lastName: '',
                email: '',
                role: 'waiter',
                password: '',
                confirmPassword: '',
                code: '',
                secondsRemaining: 0,
                timer: null,
                errorMessage: '',
                successMessage: '',
                isLoading: false,

                async sendCode() {
                    this.errorMessage = '';
                    this.successMessage = '';
                    this.isLoading = true;

                    try {
                        const response = await fetch("{{ route('create-account.send-code') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                first_name: this.firstName,
                                last_name: this.lastName,
                                email: this.email,
                                role: this.role,
                                password: this.password,
                                password_confirmation: this.confirmPassword
                            })
                        });

                        const data = await response.json();
                        if (!response.ok) {
                            throw new Error(data.message || Object.values(data.errors || {}).flat()[0] || 'Unable to send verification code.');
                        }

                        this.mode = 'verify';
                        this.code = '';
                        this.secondsRemaining = 60;
                        this.startTimer();
                    } catch (error) {
                        this.errorMessage = error.message;
                    } finally {
                        this.isLoading = false;
                    }
                },

                startTimer() {
                    clearInterval(this.timer);
                    this.timer = setInterval(() => {
                        this.secondsRemaining--;
                        if (this.secondsRemaining <= 0) {
                            clearInterval(this.timer);
                            this.secondsRemaining = 0;
                        }
                    }, 1000);
                },

                async verifyCode() {
                    this.errorMessage = '';
                    this.isLoading = true;

                    try {
                        const response = await fetch("{{ route('create-account.verify-code') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ email: this.email, code: this.code })
                        });

                        const data = await response.json();
                        if (!response.ok) {
                            throw new Error(data.message || Object.values(data.errors || {}).flat()[0] || 'Unable to verify code.');
                        }

                        clearInterval(this.timer);
                        this.successMessage = 'Account created successfully. You can now log in.';
                        setTimeout(() => {
                            window.location.href = "{{ route('login') }}";
                        }, 1500);
                    } catch (error) {
                        this.errorMessage = error.message;
                    } finally {
                        this.isLoading = false;
                    }
                }
            };
        }
    </script>
</body>
</html>
