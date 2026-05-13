<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | UB Sync</title>
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
            padding: 1rem; /* Space para sa mobile screens */
        }

        /* Container adjustment for responsiveness */
        .login-box {
            background-color: #ffffff;
            border-radius: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            width: 100%;
            /* Mobile: Full width | Tablet/PC: Max 420px */
            max-width: 420px; 
            padding: 2.5rem 1.5rem; /* Default mobile padding */
        }

        /* Pag tablet pataas, lalakihan ang internal padding */
        @media (min-width: 640px) {
            .login-box {
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

    <div class="login-box" x-data="loginForm()">

        <div class="text-center mb-10">
           <img src="{{ asset('img/ublogo.png') }}" alt="UB Logo" class="w-48 sm:w-70 h-auto mx-auto mb-1">

            <p class="text-gray-500 text-sm font-medium">Please enter your details</p>
        </div>

        <form @submit.prevent="handleLogin" class="space-y-6">

            <div class="space-y-1.5">
                <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-wider ml-1">Email</label>
                <input type="email" x-model="email" id="email" required
                       class="custom-input" placeholder="Enter your email">
            </div>

            <div class="space-y-1.5">
                <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-wider ml-1">Password</label>
                <input :type="showPassword ? 'text' : 'password'" x-model="password" id="password" required
                       class="custom-input" placeholder="Enter your password">
            </div>

            <div class="flex flex-row items-center justify-between px-1">
                <div class="flex items-center cursor-pointer select-none" @click="showPassword = !showPassword">
                    <input type="checkbox" x-model="showPassword"
                           class="w-4 h-4 rounded border-gray-300 accent-[#800000] cursor-pointer">
                    <label class="ml-2 text-[12px] font-semibold text-gray-600 cursor-pointer">
                        Show password
                    </label>
                </div>

                <a href="{{ route('forgot-password') }}" class="text-[12px] font-semibold text-gray-400 hover:text-[#800000]">
                    Forgot Password?
                </a>
            </div>

            <div x-show="errorMessage" x-transition class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-[12px]">
                <span x-text="errorMessage"></span>
            </div>

            <button type="submit" :disabled="isLoading" class="w-full ub-maroon-btn font-bold py-4 rounded-xl uppercase tracking-widest text-[12px] mt-4 disabled:opacity-50 disabled:cursor-not-allowed">
                <span x-show="!isLoading">Log In</span>
                <span x-show="isLoading">Logging in...</span>
            </button>
        </form>
    </div>

    <script>
        function loginForm() {
            return {
                email: '',
                password: '',
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

                handleLogin() {
                    this.errorMessage = '';

                    if (!this.email || !this.password) {
                        this.errorMessage = 'Please enter both email and password';
                        return;
                    }

                    const user = this.users[this.email];

                    if (!user) {
                        this.errorMessage = 'Email not found';
                        return;
                    }

                    if (user.password !== this.password) {
                        this.errorMessage = 'Incorrect password';
                        return;
                    }

                    this.isLoading = true;

                    setTimeout(() => {
                        if (user.role === 'waiter') {
                            window.location.href = '/waiter';
                        } else if (user.role === 'manager') {
                            window.location.href = '/manager';
                        }
                    }, 500);
                }
            }
        }
    </script>
</body>
</html>