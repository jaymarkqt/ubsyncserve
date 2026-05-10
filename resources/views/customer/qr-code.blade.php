<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code | Table {{ $table }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        [x-cloak] { display: none !important; }
        .bg-maroon { background-color: #800000; }
        .text-maroon { color: #800000; }
    </style>
</head>
<body class="bg-gray-100 text-slate-900 font-sans antialiased">
    <div class="min-h-screen flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-md bg-white rounded-[2rem] border border-gray-200 shadow-2xl p-6 text-center">
            <p class="text-xs uppercase tracking-[0.35em] text-gray-400">QR Code Scan Test</p>
            <h1 class="mt-4 text-4xl font-black uppercase tracking-tight text-[#800000]">Table {{ $table }}</h1>
            <p class="mt-3 text-sm text-gray-500">Scan this QR or press <span class="font-black">TEST</span> to open the customer menu for this table.</p>

            <div class="mt-10 mb-8">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=320x320&data={{ urlencode(url('/order/choice')) }}" alt="QR Code" class="mx-auto rounded-[1.75rem] border border-gray-200 shadow-sm" />
            </div>

            <a href="{{ route('order.booking-choice') }}" class="inline-flex items-center justify-center w-full gap-3 rounded-3xl bg-[#800000] px-6 py-4 text-sm font-black uppercase text-white shadow-lg hover:bg-[#9a0000] transition-all">
                <i class="fas fa-sign-in-alt text-base"></i>
                Continue to Booking
            </a>
        </div>
    </div>
</body>
</html>
