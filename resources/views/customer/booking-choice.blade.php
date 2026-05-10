<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Booking Type | UB Sync</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .bg-maroon { background-color: #800000; }
        .text-maroon { color: #800000; }
        .border-maroon { border-color: #800000; }
        .hover-lift { transition: all 0.3s ease; }
        .hover-lift:hover { transform: translateY(-12px); box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-white to-slate-100 min-h-screen font-sans">
    <div class="max-w-7xl mx-auto px-4 py-12">
        <!-- Header -->
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-black text-slate-900 uppercase tracking-tighter mb-3">Choose Your Experience</h1>
            <p class="text-lg text-slate-600 font-medium">Select how you'd like to book with us today</p>
        </div>

        <!-- Choice Cards -->
        <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto mb-12">
            <!-- Advance Ordering Card -->
            <a href="{{ route('customer.book') }}?type=advance-order" class="block hover-lift">
                <div class="h-full rounded-3xl border-2 border-amber-200 bg-gradient-to-br from-amber-50 to-yellow-50 p-10 text-center shadow-lg flex flex-col justify-between">
                    <div>
                        <div class="w-24 h-24 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-utensils text-4xl text-amber-700"></i>
                        </div>
                        <h2 class="text-2xl font-black text-amber-900 uppercase tracking-wide mb-3">Advance Order</h2>
                        <p class="text-sm text-amber-800 mb-8 leading-relaxed font-medium mx-auto max-w-[22rem]">
                            Order your food in advance and have it ready when you arrive. Your table will be reserved and your meal prepared ahead of time.
                        </p>
                    </div>
                    <div class="space-y-3 text-left mx-auto max-w-[20rem]">
                        <div class="flex items-center gap-3 text-amber-800">
                            <i class="fas fa-check text-amber-600 mt-0.5"></i>
                            <span class="text-sm font-bold">Order food immediately</span>
                        </div>
                        <div class="flex items-center gap-3 text-amber-800">
                            <i class="fas fa-check text-amber-600 mt-0.5"></i>
                            <span class="text-sm font-bold">Food ready on arrival</span>
                        </div>
                        <div class="flex items-center gap-3 text-amber-800">
                            <i class="fas fa-check text-amber-600 mt-0.5"></i>
                            <span class="text-sm font-bold">Reserved table waiting</span>
                        </div>
                    </div>
                    <div class="mt-8 inline-flex items-center justify-center gap-2 text-amber-700 font-black uppercase tracking-wider text-sm">
                        Book Now <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </a>

            <!-- Table Reservation Card -->
            <a href="{{ route('customer.book') }}?type=reservation" class="block hover-lift">
                <div class="h-full rounded-3xl border-2 border-orange-200 bg-gradient-to-br from-orange-50 to-red-50 p-10 text-center shadow-lg flex flex-col justify-between">
                    <div>
                        <div class="w-24 h-24 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-calendar-check text-4xl text-orange-700"></i>
                        </div>
                        <h2 class="text-2xl font-black text-orange-900 uppercase tracking-wide mb-3">Reservation</h2>
                        <p class="text-sm text-orange-800 mb-8 leading-relaxed font-medium mx-auto max-w-[22rem]">
                            Reserve your table for a specific time. Your table will be held and ready for you when you arrive.
                        </p>
                    </div>
                    <div class="space-y-3 text-left mx-auto max-w-[20rem]">
                        <div class="flex items-center gap-3 text-orange-800">
                            <i class="fas fa-check text-orange-600 mt-0.5"></i>
                            <span class="text-sm font-bold">Table reserved on arrival</span>
                        </div>
                        <div class="flex items-center gap-3 text-orange-800">
                            <i class="fas fa-check text-orange-600 mt-0.5"></i>
                            <span class="text-sm font-bold">Schedule your visit time</span>
                        </div>
                        <div class="flex items-center gap-3 text-orange-800">
                            <i class="fas fa-check text-orange-600 mt-0.5"></i>
                            <span class="text-sm font-bold">Dedicated seating</span>
                        </div>
                    </div>
                    <div class="mt-8 inline-flex items-center justify-center gap-2 text-orange-700 font-black uppercase tracking-wider text-sm">
                        Book Now <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </a>
        </div>

        <!-- Footer Info -->
        <div class="text-center text-slate-500 text-sm font-medium">
            <p>Not sure? Both options provide excellent service. Choose what works best for you!</p>
        </div>
    </div>
</body>
</html>
