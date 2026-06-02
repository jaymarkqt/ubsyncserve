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
        .text-gold { color: #d4af37; }
        .border-gold { border-color: #e5c158; }
        .focus-maroon:focus { border-color: #800000; box-shadow: 0 0 0 2px rgba(128, 0, 0, 0.1); }
        .header-curve {
            border-bottom-left-radius: 50% 15%;
            border-bottom-right-radius: 50% 15%;
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center px-4 py-8 font-sans">

    <div class="max-w-[420px] w-full bg-white rounded-3xl shadow-2xl relative">
        
        <div class="bg-gradient-to-b from-[#6b0000] to-[#990000] pt-10 pb-12 text-center relative header-curve rounded-t-3xl shadow-md z-10 border-b-[3px] border-[#d4af37]">
            <div class="absolute inset-0 opacity-5 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] rounded-t-3xl header-curve"></div>
            
            <div class="flex items-center justify-center gap-3 mb-3 relative z-10">
                <div class="w-12 h-[1px] bg-gradient-to-r from-transparent to-[#d4af37]"></div>
                <div class="w-2 h-2 rotate-45 border border-[#d4af37]"></div>
                <div class="w-12 h-[1px] bg-gradient-to-l from-transparent to-[#d4af37]"></div>
            </div>
            
            <h1 class="text-2xl font-serif font-bold text-white uppercase tracking-widest relative z-10 shadow-sm" id="formTitle">Advance Order</h1>
            
            <p class="text-white/90 text-[11px] font-medium tracking-wide mt-2 relative z-10">Secure your dining experience with us.</p>
            
            <div class="absolute -bottom-7 left-1/2 transform -translate-x-1/2 w-14 h-14 bg-white rounded-full border shadow flex items-center justify-center z-20" style="border-color: #f0e6d2;">
                <div class="w-11 h-11 rounded-full border border-yellow-600/30 flex items-center justify-center bg-[#fffdf8]">
                    <i class="fa-solid fa-bell-concierge text-[#d4af37] text-lg"></i>
                </div>
            </div>
        </div>

        <form class="px-6 pt-12 pb-6 space-y-4" action="#" method="POST">
            <input type="hidden" name="type" value="table-reservation" id="bookingTypeInput">

            <div class="rounded-xl bg-[#fafafa] border border-gray-100 p-3.5 shadow-sm">
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-[#800000] mb-1">Booking Type</p>
                <div class="flex items-center gap-2.5">
                    <i class="fas fa-bookmark text-[#800000] text-sm"></i>
                    <span class="text-[15px] font-bold text-gray-800" id="bookingTypeLabel">Advance Order</span>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1">Full Name</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="fa-regular fa-user text-[#800000]/60 text-sm"></i>
                    </div>
                    <input type="text" name="name" required class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-200 outline-none focus-maroon transition-all bg-white text-sm" placeholder="Your name">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="fa-regular fa-envelope text-[#800000]/60 text-sm"></i>
                    </div>
                    <input type="email" name="email" required class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-200 outline-none focus-maroon transition-all bg-white text-sm" placeholder="your@email.com">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1">Phone Number</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="fa-solid fa-phone text-[#800000]/60 text-sm"></i>
                    </div>
                    <input type="tel" name="phone" required class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-200 outline-none focus-maroon transition-all bg-white text-sm" placeholder="+63 123 456 7890">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1">Date</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fa-regular fa-calendar text-[#800000]/60 text-sm"></i>
                        </div>
                        <input type="date" name="date" required class="w-full pl-10 pr-3 py-2.5 rounded-lg border border-gray-200 outline-none focus-maroon transition-all bg-white text-gray-600 text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1">Time</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fa-regular fa-clock text-[#800000]/60 text-sm"></i>
                        </div>
                        <input type="text" placeholder="--:-- --" onfocus="(this.type='time')" onblur="(this.value == '' ? this.type='text' : this.type='time')" name="time" required class="w-full pl-10 pr-3 py-2.5 rounded-lg border border-gray-200 outline-none focus-maroon transition-all bg-white text-gray-600 text-sm">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-chevron-down text-gray-400 text-[10px]"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1">Adults</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fa-solid fa-user-group text-[#800000]/60 text-sm"></i>
                        </div>
                        <input type="number" name="adults" min="0" value="0" id="adultsInput" required class="w-full pl-10 pr-3 py-2.5 rounded-lg border border-gray-200 outline-none focus-maroon transition-all bg-white text-sm appearance-none">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-chevron-down text-gray-400 text-[10px]"></i>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1">Children</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fa-solid fa-child text-[#800000]/60 text-sm"></i>
                        </div>
                        <input type="number" name="children" min="0" value="0" id="childrenInput" required class="w-full pl-10 pr-3 py-2.5 rounded-lg border border-gray-200 outline-none focus-maroon transition-all bg-white text-sm appearance-none">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-chevron-down text-gray-400 text-[10px]"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1">Total Pax</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="fa-solid fa-users text-[#800000]/60 text-sm"></i>
                    </div>
                    <input type="number" name="pax" id="paxInput" readonly class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-200 outline-none bg-gray-50 text-gray-700 font-bold text-sm">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1">Special Requests (Optional)</label>
                <div class="relative">
                    <div class="absolute top-3 left-0 pl-3.5 flex items-start pointer-events-none">
                        <i class="fa-solid fa-pen-to-square text-[#800000]/60 text-sm"></i>
                    </div>
                    <textarea name="requests" rows="2" class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-200 outline-none focus-maroon transition-all bg-white resize-none text-sm" placeholder="Any special requirements or preferences?"></textarea>
                </div>
            </div>

            <button type="submit" id="submitBtn" disabled class="w-full mt-2 bg-[#6b0000] hover:bg-[#4d0000] disabled:bg-gray-300 disabled:text-gray-500 disabled:cursor-not-allowed text-white font-black py-3 rounded-xl uppercase tracking-widest text-[11px] sm:text-xs transition-all shadow-md flex justify-center items-center gap-2">
                <i class="fas fa-check-circle text-[#d4af37] text-sm disabled:text-gray-500"></i> COMPLETE RESERVATION
            </button>

            <a href="{{ route('order.booking-choice') }}" class="block text-center text-[#800000] hover:text-[#4d0000] font-bold text-[11px] uppercase tracking-widest transition-colors pt-3">
                <i class="fa-solid fa-chevron-left mr-1"></i> GO BACK
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