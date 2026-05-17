<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make a Reservation | UB Sync</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .bg-maroon { background-color: #800000; }
        .text-maroon { color: #800000; }
        .border-maroon { border-color: #800000; }
        .focus-maroon:focus { border-color: #800000; box-shadow: 0 0 0 3px rgba(128, 0, 0, 0.1); }
    </style>
</head>
<body class="bg-[url('{{ asset('img/backgroundlogo.png') }}')] bg-cover bg-center bg-no-repeat bg-fixed min-h-screen flex items-center justify-center px-3 py-6 sm:px-4 md:p-8 font-sans">

    <div class="max-w-lg w-full bg-white rounded-2xl sm:rounded-[2rem] shadow-lg sm:shadow-2xl overflow-hidden border border-gray-100">
        
        <div class="bg-maroon p-6 sm:p-8 text-center relative overflow-hidden">
            <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
            <h1 class="text-2xl sm:text-3xl font-black text-white uppercase tracking-widest relative z-10" id="formTitle">Make a Reservation</h1>
            <p class="text-white/80 text-xs sm:text-sm mt-2 relative z-10">Secure your dining experience with us.</p>
        </div>

        <form class="p-4 sm:p-6 md:p-8 space-y-4 sm:space-y-5" action="#" method="POST">
            <input type="hidden" name="type" value="table-reservation" id="bookingTypeInput">

            <!-- Booking Type Display -->
            <div class="rounded-2xl bg-slate-50 border-2 border-slate-100 p-4">
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 mb-2">Booking Type</p>
                <div class="flex items-center gap-3">
                    <i class="fas fa-bookmark text-maroon text-lg"></i>
                    <span class="text-lg font-black text-slate-800" id="bookingTypeLabel">Reservation</span>
                </div>
            </div>

            <!-- Full Name -->
            <div>
                <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-1.5 ml-1">Full Name</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                        <i class="fa-regular fa-user text-gray-400 text-sm"></i>
                    </div>
                    <input type="text" name="name" required class="w-full pl-10 sm:pl-11 pr-3 sm:pr-4 py-3 sm:py-3.5 rounded-xl border border-gray-200 outline-none focus-maroon transition-all bg-gray-50 focus:bg-white text-sm" placeholder="Your name">
                </div>
            </div>

            <!-- Email Address -->
            <div>
                <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-1.5 ml-1">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                        <i class="fa-regular fa-envelope text-gray-400 text-sm"></i>
                    </div>
                    <input type="email" name="email" required class="w-full pl-10 sm:pl-11 pr-3 sm:pr-4 py-3 sm:py-3.5 rounded-xl border border-gray-200 outline-none focus-maroon transition-all bg-gray-50 focus:bg-white text-sm" placeholder="your@email.com">
                </div>
            </div>

            <!-- Phone Number -->
            <div>
                <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-1.5 ml-1">Phone Number</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-phone text-gray-400 text-sm"></i>
                    </div>
                    <input type="tel" name="phone" required class="w-full pl-10 sm:pl-11 pr-3 sm:pr-4 py-3 sm:py-3.5 rounded-xl border border-gray-200 outline-none focus-maroon transition-all bg-gray-50 focus:bg-white text-sm" placeholder="+63 123 456 7890">
                </div>
            </div>

            <!-- Date and Time -->
            <div class="grid grid-cols-1 gap-3 sm:gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-1.5 ml-1">Date</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                            <i class="fa-regular fa-calendar text-gray-400 text-sm"></i>
                        </div>
                        <input type="date" name="date" required class="w-full pl-10 sm:pl-11 pr-3 sm:pr-4 py-3 sm:py-3.5 rounded-xl border border-gray-200 outline-none focus-maroon transition-all bg-gray-50 focus:bg-white text-gray-700 text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-1.5 ml-1">Time</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                            <i class="fa-regular fa-clock text-gray-400 text-sm"></i>
                        </div>
                        <input type="text" placeholder="--:-- --" onfocus="(this.type='time')" onblur="(this.value == '' ? this.type='text' : this.type='time')" name="time" required class="w-full pl-10 sm:pl-11 pr-3 sm:pr-4 py-3 sm:py-3.5 rounded-xl border border-gray-200 outline-none focus-maroon transition-all bg-gray-50 focus:bg-white text-gray-700 text-sm">
                    </div>
                </div>
            </div>

            <!-- Adults and Children -->
            <div class="grid grid-cols-1 gap-3 sm:gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-1.5 ml-1">Adults</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-user-group text-gray-400 text-sm"></i>
                        </div>
                        <input type="number" name="adults" min="0" value="0" id="adultsInput" required class="w-full pl-10 sm:pl-11 pr-3 sm:pr-4 py-3 sm:py-3.5 rounded-xl border border-gray-200 outline-none focus-maroon transition-all bg-gray-50 focus:bg-white text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-1.5 ml-1">Children</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-child text-gray-400 text-sm"></i>
                        </div>
                        <input type="number" name="children" min="0" value="0" id="childrenInput" required class="w-full pl-10 sm:pl-11 pr-3 sm:pr-4 py-3 sm:py-3.5 rounded-xl border border-gray-200 outline-none focus-maroon transition-all bg-gray-50 focus:bg-white text-sm">
                    </div>
                </div>
            </div>

            <!-- Total Pax -->
            <div>
                <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-1.5 ml-1">Total Pax</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-users text-gray-400"></i>
                    </div>
                    <input type="number" name="pax" id="paxInput" readonly class="w-full pl-11 pr-4 py-3.5 rounded-xl border border-gray-200 outline-none bg-gray-100 text-gray-600 font-bold">
                </div>
            </div>

            <!-- Special Requests -->
            <div>
                <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-1.5 ml-1">Special Requests (Optional)</label>
                <textarea name="requests" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none focus-maroon transition-all bg-gray-50 focus:bg-white resize-none" placeholder="Any special requirements or preferences?"></textarea>
            </div>

            <!-- Submit Button -->
            <button type="submit" id="submitBtn" disabled class="w-full mt-4 sm:mt-6 bg-maroon hover:bg-red-900 disabled:bg-gray-400 disabled:cursor-not-allowed text-white font-black py-3.5 sm:py-4 rounded-xl uppercase tracking-[0.2em] text-sm sm:text-base shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5 flex justify-center items-center gap-2 min-h-[44px] sm:min-h-[48px]">
                <i class="fas fa-check-circle"></i> Complete Reservation
            </button>

            <!-- Cancel Link -->
            <a href="{{ route('order.booking-choice') }}" class="block text-center text-slate-500 hover:text-slate-700 font-bold text-xs sm:text-sm uppercase tracking-wider transition-colors py-2">
                Go Back
            </a>
        </form>
    </div>

<script>
    function updatePaxCount() {
        const adults = parseInt(document.getElementById('adultsInput').value, 10) || 0;
        const children = parseInt(document.getElementById('childrenInput').value, 10) || 0;
        document.getElementById('paxInput').value = adults + children;
        validateForm();
    }

    function validateForm() {
        const name = document.querySelector('input[name="name"]').value.trim();
        const email = document.querySelector('input[name="email"]').value.trim();
        const phone = document.querySelector('input[name="phone"]').value.trim();
        const date = document.querySelector('input[name="date"]').value.trim();
        const time = document.querySelector('input[name="time"]').value.trim();
        const adults = parseInt(document.getElementById('adultsInput').value, 10) || 0;
        const children = parseInt(document.getElementById('childrenInput').value, 10) || 0;
        const totalGuests = adults + children;

        const isValid =
            name.length > 0 &&
            email.length > 0 && email.includes('@') &&
            phone.length > 0 &&
            date.length > 0 &&
            time.length > 0 &&
            totalGuests > 0;

        document.getElementById('submitBtn').disabled = !isValid;
    }

    function setBookingTypeFromQuery() {
        const params = new URLSearchParams(window.location.search);
        const type = params.get('type') || 'table-reservation';

        document.getElementById('bookingTypeInput').value = type;

        if (type === 'advance-order') {
            document.getElementById('formTitle').textContent = 'Advance Order';
            document.getElementById('bookingTypeLabel').textContent = 'Advance Order';
        } else if (type === 'table-reservation') {
            document.getElementById('formTitle').textContent = 'Table Reservation';
            document.getElementById('bookingTypeLabel').textContent = 'Table Reservation';
        } else {
            document.getElementById('formTitle').textContent = 'Make a Reservation';
            document.getElementById('bookingTypeLabel').textContent = 'Reservation';
        }
    }

    // Initialize
    updatePaxCount();
    setBookingTypeFromQuery();

    document.getElementById('adultsInput').addEventListener('input', updatePaxCount);
    document.getElementById('childrenInput').addEventListener('input', updatePaxCount);
    document.querySelector('input[name="name"]').addEventListener('input', validateForm);
    document.querySelector('input[name="email"]').addEventListener('input', validateForm);
    document.querySelector('input[name="phone"]').addEventListener('input', validateForm);
    document.querySelector('input[name="date"]').addEventListener('input', validateForm);
    document.querySelector('input[name="time"]').addEventListener('input', validateForm);

    document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const adults = parseInt(formData.get('adults'), 10) || 0;
        const children = parseInt(formData.get('children'), 10) || 0;
        const guests = adults + children;
        const type = formData.get('type') || 'table-reservation';

        const newReservation = {
            id: 'RES-' + Math.floor(Math.random() * 10000).toString().padStart(4, '0'),
            name: formData.get('name'),
            email: formData.get('email'),
            phone: formData.get('phone'),
            type: type,
            adults: adults,
            children: children,
            guests: guests,
            date: formData.get('date'),
            time: formData.get('time'),
            requests: formData.get('requests'),
            status: 'pending',
            createdAt: new Date().toISOString(),
            scheduledAt: formData.get('date') + ' ' + formData.get('time')
        };

        let existingReservations = JSON.parse(localStorage.getItem('ub_reservations')) || [];
        existingReservations.unshift(newReservation);
        localStorage.setItem('ub_reservations', JSON.stringify(existingReservations));

        if (type === 'advance-order') {
            let storedTables = JSON.parse(localStorage.getItem('ub_tables') || '[]');
            if (!Array.isArray(storedTables) || storedTables.length !== 15) {
                storedTables = Array.from({ length: 15 }, (_, idx) => ({
                    id: idx + 1,
                    status: 'available',
                    type: null,
                    adults: 0,
                    children: 0,
                    bill: 0,
                    orders: []
                }));
            }
            localStorage.setItem('ub_tables', JSON.stringify(storedTables));
        }

        localStorage.setItem('customer_booking_type', type);

        window.dispatchEvent(new Event('storage'));
        this.reset();
        updatePaxCount();
        alert('Reservation request submitted! Please wait for confirmation. Check your email for updates.');
    });
</script>
</body>
</html>