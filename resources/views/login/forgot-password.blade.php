<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | UB Sync</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body {
            background-color: #f9fafb;
            font-family: 'Inter', sans-serif;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 1rem;
        }

        .forgot-box {
            background-color: #ffffff;
            border-radius: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            width: 100%;
            max-width: 420px;
            padding: 2.5rem 1.5rem;
        }

        @media (min-width: 640px) {
            .forgot-box {
                padding: 3.5rem 3rem;
            }
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
        }

        .custom-input:focus {
            outline: none;
            border-color: #800000;
        }

        .ub-maroon-btn {
            background-color: #800000;
            color: #ffffff;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <div class="forgot-box" x-data="forgotPasswordForm()">

        <div class="text-center mb-10">
           <img src="{{ asset('img/ublogo.png') }}" alt="UB Logo" class="w-48 sm:w-70 h-auto mx-auto mb-1">
            <p class="text-gray-500 text-sm font-medium">Reset Your Password</p>
        </div>

        <!-- Step 1: Email Verification -->
        <form x-show="step === 1" @submit.prevent="verifyEmail" class="space-y-6">
            <div class="space-y-1.5">
                <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-wider ml-1">Enter Your Email</label>
                <input type="email" x-model="email" required
                       class="custom-input" placeholder="Enter registered email">
            </div>

            <div x-show="errorMessage" x-transition class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-[12px]">
                <span x-text="errorMessage"></span>
            </div>

            <button type="submit" :disabled="isLoading" class="w-full ub-maroon-btn font-bold py-4 rounded-xl uppercase tracking-widest text-[12px] disabled:opacity-50 disabled:cursor-not-allowed">
                <span x-show="!isLoading">Continue</span>
                <span x-show="isLoading">Checking...</span>
            </button>

            <div class="text-center">
                <a href="{{ route('login') }}" class="text-[12px] font-semibold text-[#800000] hover:text-gray-700">
                    Back to Login
                </a>
            </div>
        </form>

        <!-- Step 2: Password Reset -->
        <form x-show="step === 2" @submit.prevent="resetPassword" class="space-y-6">
            <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg text-[12px]">
                Resetting password for: <strong x-text="email"></strong>
            </div>

            <div class="space-y-1.5">
                <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-wider ml-1">New Password</label>
                <input :type="showPassword ? 'text' : 'password'" x-model="newPassword" required
                       class="custom-input" placeholder="Enter new password">
            </div>

            <div class="space-y-1.5">
                <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-wider ml-1">Confirm Password</label>
                <input :type="showPassword ? 'text' : 'password'" x-model="confirmPassword" required
                       class="custom-input" placeholder="Confirm new password">
            </div>

            <div class="flex items-center cursor-pointer select-none" @click="showPassword = !showPassword">
                <input type="checkbox" x-model="showPassword"
                       class="w-4 h-4 rounded border-gray-300 accent-[#800000] cursor-pointer">
                <label class="ml-2 text-[12px] font-semibold text-gray-600 cursor-pointer">
                    Show password
                </label>
            </div>

            <div x-show="errorMessage" x-transition class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-[12px]">
                <span x-text="errorMessage"></span>
            </div>

            <button type="submit" :disabled="isLoading" class="w-full ub-maroon-btn font-bold py-4 rounded-xl uppercase tracking-widest text-[12px] disabled:opacity-50 disabled:cursor-not-allowed">
                <span x-show="!isLoading">Reset Password</span>
                <span x-show="isLoading">Updating...</span>
            </button>

            <button type="button" @click="step = 1; errorMessage = ''" class="w-full text-[12px] font-semibold text-gray-600 hover:text-gray-800 py-2">
                Back
            </button>
        </form>

        <!-- Step 3: Success -->
        <div x-show="step === 3" x-transition class="space-y-6 text-center">
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-4 rounded-lg">
                <p class="text-[14px] font-bold mb-2">✓ Password Reset Successful!</p>
                <p class="text-[12px]">Your password has been updated successfully.</p>
            </div>

            <p class="text-[12px] text-gray-600">
                You can now login with your new password.
            </p>

            <a href="{{ route('login') }}" class="w-full ub-maroon-btn font-bold py-4 rounded-xl uppercase tracking-widest text-[12px] block text-center">
                Back to Login
            </a>
        </div>

    </div>

    <script>
        function forgotPasswordForm() {
            return {
                step: 1,
                email: '',
                newPassword: '',
                confirmPassword: '',
                showPassword: false,
                errorMessage: '',
                isLoading: false,

                users: {
                    'waiter123@gmail.com': { password: 'admin123', role: 'waiter' },
                    'manager123@gmail.com': { password: 'admin123', role: 'manager' }
                },

                init() {
                    this.loadStoredUsers();
                },

                loadStoredUsers() {
                    const stored = localStorage.getItem('updatedUsers');
                    if (stored) {
                        this.users = JSON.parse(stored);
                    }
                },

                saveUsers() {
                    localStorage.setItem('updatedUsers', JSON.stringify(this.users));
                },

                verifyEmail() {
                    this.errorMessage = '';

                    if (!this.email) {
                        this.errorMessage = 'Please enter your email';
                        return;
                    }

                    const user = this.users[this.email];

                    if (!user) {
                        this.errorMessage = 'Email not found in our system';
                        return;
                    }

                    this.isLoading = true;

                    setTimeout(() => {
                        this.isLoading = false;
                        this.step = 2;
                    }, 500);
                },

                resetPassword() {
                    this.errorMessage = '';

                    if (!this.newPassword || !this.confirmPassword) {
                        this.errorMessage = 'Please fill in all password fields';
                        return;
                    }

                    if (this.newPassword.length < 4) {
                        this.errorMessage = 'Password must be at least 4 characters';
                        return;
                    }

                    if (this.newPassword !== this.confirmPassword) {
                        this.errorMessage = 'Passwords do not match';
                        return;
                    }

                    this.isLoading = true;

                    setTimeout(() => {
                        this.users[this.email].password = this.newPassword;
                        this.saveUsers();

                        this.isLoading = false;
                        this.step = 3;
                    }, 500);
                }
            }
        }
    </script>

</body>
</html>
