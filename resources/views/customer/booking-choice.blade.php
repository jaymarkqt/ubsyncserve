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
    <div class="max-w-7xl mx-auto px-3 sm:px-4 py-8 sm:py-12 md:py-16">
        <!-- Header -->
        <div class="text-center mb-10 sm:mb-14 md:mb-16">
            <h1 class="text-2xl sm:text-4xl md:text-5xl font-black text-slate-900 uppercase tracking-tighter mb-2 sm:mb-3">ADVANCE ORDER AT TABLE RESERVATION</h1>
            <p class="text-sm sm:text-base md:text-lg text-slate-600 font-medium px-2">Select the booking option you want, then continue to the booking form.</p>
        </div>

        <!-- Choice Cards -->
        <div class="grid md:grid-cols-2 gap-5 sm:gap-6 md:gap-8 max-w-4xl mx-auto mb-8 sm:mb-12">
            <!-- Advance Ordering Card -->
            <a href="{{ route('customer.book') }}?type=advance-order" class="block hover-lift group">
                <div class="h-full rounded-2xl sm:rounded-3xl border-2 border-amber-200 bg-gradient-to-br from-amber-50 to-yellow-50 p-6 sm:p-8 md:p-10 text-center shadow-md sm:shadow-lg group-hover:shadow-xl group-hover:border-amber-300 transition-all flex flex-col justify-between">
                    <div>
                        <div class="w-20 h-20 sm:w-24 sm:h-24 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6 group-hover:scale-110 transition-transform">
                            <i class="fas fa-utensils text-3xl sm:text-4xl text-amber-700"></i>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-black text-amber-900 uppercase tracking-wide mb-2 sm:mb-3">Advance Order</h2>
                        <p class="text-xs sm:text-sm text-amber-800 mb-6 sm:mb-8 leading-relaxed font-medium mx-auto max-w-xs">
                            Order your food in advance and have it ready when you arrive. Your table will be reserved and your meal prepared ahead of time.
                        </p>
                    </div>
                    <div class="space-y-2 sm:space-y-3 text-left mx-auto max-w-xs">
                        <div class="flex items-center gap-2 sm:gap-3 text-amber-800 text-sm">
                            <i class="fas fa-check text-amber-600 mt-0.5 flex-shrink-0"></i>
                            <span class="font-bold">Order food immediately</span>
                        </div>
                        <div class="flex items-center gap-2 sm:gap-3 text-amber-800 text-sm">
                            <i class="fas fa-check text-amber-600 mt-0.5 flex-shrink-0"></i>
                            <span class="font-bold">Food ready on arrival</span>
                        </div>
                        <div class="flex items-center gap-2 sm:gap-3 text-amber-800 text-sm">
                            <i class="fas fa-check text-amber-600 mt-0.5 flex-shrink-0"></i>
                            <span class="font-bold">Reserved table waiting</span>
                        </div>
                    </div>
                    <div class="mt-6 sm:mt-8 inline-flex items-center justify-center gap-2 text-amber-700 font-black uppercase tracking-wider text-xs sm:text-sm">
                        Book Now <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </div>
            </a>

            <!-- Table Reservation Card -->
            <a href="{{ route('customer.book') }}?type=table-reservation" class="block hover-lift group">
                <div class="h-full rounded-2xl sm:rounded-3xl border-2 border-orange-200 bg-gradient-to-br from-orange-50 to-red-50 p-6 sm:p-8 md:p-10 text-center shadow-md sm:shadow-lg group-hover:shadow-xl group-hover:border-orange-300 transition-all flex flex-col justify-between">
                    <div>
                        <div class="w-20 h-20 sm:w-24 sm:h-24 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6 group-hover:scale-110 transition-transform">
                            <i class="fas fa-calendar-check text-3xl sm:text-4xl text-orange-700"></i>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-black text-orange-900 uppercase tracking-wide mb-2 sm:mb-3">Table Reservation</h2>
                        <p class="text-xs sm:text-sm text-orange-800 mb-6 sm:mb-8 leading-relaxed font-medium mx-auto max-w-xs">
                            Reserve your table for a specific time. Your table will be held and ready for you when you arrive.
                        </p>
                    </div>
                    <div class="space-y-2 sm:space-y-3 text-left mx-auto max-w-xs">
                        <div class="flex items-center gap-2 sm:gap-3 text-orange-800 text-sm">
                            <i class="fas fa-check text-orange-600 mt-0.5 flex-shrink-0"></i>
                            <span class="font-bold">Table reserved on arrival</span>
                        </div>
                        <div class="flex items-center gap-2 sm:gap-3 text-orange-800 text-sm">
                            <i class="fas fa-check text-orange-600 mt-0.5 flex-shrink-0"></i>
                            <span class="font-bold">Schedule your visit time</span>
                        </div>
                        <div class="flex items-center gap-2 sm:gap-3 text-orange-800 text-sm">
                            <i class="fas fa-check text-orange-600 mt-0.5 flex-shrink-0"></i>
                            <span class="font-bold">Dedicated seating</span>
                        </div>
                    </div>
                    <div class="mt-6 sm:mt-8 inline-flex items-center justify-center gap-2 text-orange-700 font-black uppercase tracking-wider text-xs sm:text-sm">
                        Book Now <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>
</body>
</html>
