<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Table | UB Sync</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .bg-maroon { background-color: #800000; }
        .text-maroon { color: #800000; }
        .border-maroon { border-color: #800000; }
        .focus-maroon:focus { border-color: #800000; box-shadow: 0 0 0 1px #800000; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4 md:p-8 font-sans">

    <div class="max-w-lg w-full bg-white rounded-[2rem] shadow-2xl overflow-hidden border border-gray-100">
        
        <div class="bg-maroon p-8 text-center relative overflow-hidden">
            <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
            <h1 class="text-3xl font-black text-white uppercase tracking-widest relative z-10">Table Reservation</h1>
            <p class="text-white/80 text-sm mt-2 relative z-10">Secure your dining experience with us.</p>
        </div>

        <form class="p-6 sm:p-8 space-y-5" action="#" method="POST">
            <div>
                <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-1.5 ml-1">Full Name</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fa-regular fa-user text-gray-400"></i>
                    </div>
                    <input type="text" name="name" required class="w-full pl-11 pr-4 py-3.5 rounded-xl border border-gray-200 outline-none focus-maroon transition-all bg-gray-50 focus:bg-white" placeholder="">
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-1.5 ml-1">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fa-regular fa-envelope text-gray-400"></i>
                    </div>
                    <input type="email" name="email" required class="w-full pl-11 pr-4 py-3.5 rounded-xl border border-gray-200 outline-none focus-maroon transition-all bg-gray-50 focus:bg-white" placeholder="">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-1.5 ml-1">Date</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-regular fa-calendar text-gray-400"></i>
                        </div>
                        <input type="date" name="date" required class="w-full pl-11 pr-4 py-3.5 rounded-xl border border-gray-200 outline-none focus-maroon transition-all bg-gray-50 focus:bg-white text-gray-700">
                    </div>
                </div>
                
                <div>
                    <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-1.5 ml-1">Time</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-regular fa-clock text-gray-400"></i>
                        </div>
                        <input type="text" placeholder="Select Time" onfocus="(this.type='time')" onblur="(this.value == '' ? this.type='text' : this.type='time')" name="time" required class="w-full pl-11 pr-4 py-3.5 rounded-xl border border-gray-200 outline-none focus-maroon transition-all bg-gray-50 focus:bg-white text-gray-700">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-1.5 ml-1">Number of Guests (Pax)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-users text-gray-400"></i>
                    </div>
                    <input type="number" name="pax" min="1" max="20" required class="w-full pl-11 pr-4 py-3.5 rounded-xl border border-gray-200 outline-none focus-maroon transition-all bg-gray-50 focus:bg-white" placeholder="">
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-1.5 ml-1">Special Requests (Optional)</label>
                <textarea name="requests" rows="2" class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none focus-maroon transition-all bg-gray-50 focus:bg-white" placeholder=""></textarea>
            </div>

            <button type="submit" class="w-full mt-2 bg-maroon hover:bg-red-900 text-white font-black py-4 rounded-xl uppercase tracking-[0.2em] shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5 flex justify-center items-center gap-2">
                Book Table
            </button>
        </form>
    </div>

<script>
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault(); // Pipigilan mag-refresh ang page

            // 1. Kunin lahat ng nilagay ng customer sa form
            const formData = new FormData(this);
            
            // 2. Gawa tayo ng bagong object para sa reservation
            const newReservation = {
                id: 'RES-' + Math.floor(Math.random() * 10000).toString().padStart(4, '0'),
                name: formData.get('name'),
                email: formData.get('email'), // Updated to get the email value
                date: formData.get('date'),
                time: formData.get('time'),
                guests: formData.get('pax'),
                requests: formData.get('requests'),
               status: 'pending'
            };

            // 3. Kunin ang mga lumang reservations sa localStorage, tapos idagdag itong bago
            let existingReservations = JSON.parse(localStorage.getItem('ub_reservations')) || [];
            existingReservations.unshift(newReservation);

            // 4. I-save pabalik sa localStorage
            localStorage.setItem('ub_reservations', JSON.stringify(existingReservations));

            // 5. I-trigger ang event para malaman ng ibang tabs
            window.dispatchEvent(new Event('storage'));

            // 6. I-clear ang form at mag-alert sa customer
            this.reset();
            alert('Table booked successfully! Please wait for the cashier to confirm.');
        });
    </script>
</body>
</html>