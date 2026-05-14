<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Access | UB Sync</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        [x-cloak] { display: none !important; }
        .bg-maroon { background-color: #800000; }
        .text-maroon { color: #800000; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased">
    <div class="min-h-screen flex items-center justify-center px-4 py-10">
        
        <!-- Main Card -->
        <div class="w-full max-w-md bg-white rounded-[2rem] border border-slate-100 shadow-[0_10px_40px_-10px_rgba(0,0,0,0.08)] p-8 sm:p-10 text-center">
            
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-400">QR Code Scan Test</p>
            <h1 class="mt-4 text-4xl font-black uppercase tracking-tight text-[#800000]">UB Sync</h1>
          
            <!-- QR Code Area (No Hover Effect) -->
            <div class="mt-10 mb-8">
                <div class="inline-block p-4 bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.06)] border border-slate-100">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=320x320&data={{ urlencode(url('/order/choice')) }}" 
                         alt="QR Code" 
                         class="w-56 h-56 mx-auto rounded-xl" />
                </div>
            </div>

            <!-- Enhanced Button (Nanatili ang interactive feel dito para sa UX) -->
            <a href="{{ route('order.booking-choice') }}" class="group inline-flex items-center justify-center w-full gap-3 rounded-3xl bg-[#800000] px-6 py-4 text-sm font-black uppercase tracking-wide text-white shadow-[0_8px_20px_-6px_rgba(128,0,0,0.4)] hover:bg-[#9a0000] hover:shadow-[0_12px_25px_-6px_rgba(128,0,0,0.5)] hover:-translate-y-0.5 active:translate-y-0 active:scale-[0.98] transition-all duration-300">
                <i class="fas fa-sign-in-alt text-base group-hover:translate-x-1 transition-transform duration-300"></i>
                Continue to Booking
            </a>
            
        </div>
        
    </div>
</body>
</html>