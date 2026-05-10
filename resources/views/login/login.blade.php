<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Get Started | UB Sync</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@900&family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #ffffff;
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }

        /* Box Adjustment: Ginawang 2.5rem ang horizontal padding para safe lahat sa loob */
        .login-box {
            background-color: #ffffff;
            border-radius: 2.5rem;
            box-shadow: 0 15px 45px rgba(0, 0, 0, 0.06);
            border: 1px solid #f0f0f0;
            width: 90%;
            max-width: 380px;
            padding: 3rem 2.5rem; 
            box-sizing: border-box;
        }

        .get-started-header {
            font-family: 'Montserrat', sans-serif;
            font-weight: 900;
            font-size: 1.1rem; 
            text-transform: uppercase;
            letter-spacing: -0.5px;
            color: #1a1a1a;
            line-height: 1;
            margin: 0;
        }

        .ub-maroon { background-color: #800000; }

        /* Input Adjustment: box-sizing border-box para hindi lumampas kahit may padding */
        .custom-input {
            border: 2px solid #f3f4f6;
            color: #000000;
            font-weight: 600;
            transition: all 0.2s ease;
            width: 100%;
            box-sizing: border-box; 
        }

        .custom-input:focus {
            border-color: #800000;
            outline: none;
        }

        .fade-out { opacity: 0; transition: opacity 0.5s ease-out; }
    </style>
</head>
<body>

    <div class="login-box animate__animated animate__fadeIn">
        
        <div class="text-center mb-6">
            <img src="{{ asset('img/ublogo.png') }}" alt="UB Logo" 
                 class="w-40 h-auto mx-auto mb-3"> 
            
            <h2 class="get-started-header">
                GET <span class="text-[#800000]">STARTED</span>
            </h2>
        </div>

        <div class="empty:hidden mb-4">
            @if (session('success'))
                <div id="alert-success" class="bg-green-50 text-green-700 p-2 rounded-xl text-[10px] font-bold border border-green-100 text-center">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div id="alert-error-login" class="bg-red-50 text-red-600 p-2 rounded-xl text-[10px] font-bold border border-red-100 text-center animate__animated animate__shakeX">
                    Invalid email or password.
                </div>
            @endif
        </div>

        <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
    @csrf
    
    <div class="space-y-1">
        <label class="block text-[10px] font-black text-black uppercase tracking-widest ml-1">Email</label>
        <input type="email" name="email" id="email" required 
            class="custom-input px-4 py-3 rounded-2xl text-sm"
            placeholder="">
    </div>

    <div class="space-y-1">
        <label class="block text-[10px] font-black text-black uppercase tracking-widest ml-1">Password</label>
        <input type="password" name="password" id="password" required 
            class="custom-input px-4 py-3 rounded-2xl text-sm"
            placeholder="">
    
        <div class="flex items-center gap-2 pt-2 ml-1 w-full box-border">
            <input type="checkbox" id="show-pass-check" onclick="togglePassword()" 
                   class="w-4 h-4 rounded border-gray-300 text-[#800000] accent-[#800000] cursor-pointer flex-shrink-0">
            
            <label class="text-[11px] font-bold text-black uppercase select-none whitespace-nowrap cursor-pointer" for="show-pass-check">
                Show Password
            </label>
        </div>
    </div>

    <button type="submit" class="w-full ub-maroon text-white font-black py-4 rounded-2xl uppercase tracking-widest text-[11px] shadow-md hover:brightness-110 transition-all active:scale-95 mt-2">
        Login
    </button>
</form>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = ['alert-success', 'alert-error-login'];
            alerts.forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    setTimeout(() => {
                        el.classList.add('fade-out');
                        setTimeout(() => el.remove(), 500);
                    }, 3000);
                }
            });
        });

        function togglePassword() {
            const passInput = document.getElementById('password');
            const checkBox = document.getElementById('show-pass-check');
            passInput.type = checkBox.checked ? "text" : "password";
        }
    </script>
</body>
</html>