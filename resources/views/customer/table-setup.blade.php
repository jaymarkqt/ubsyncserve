<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table Setup | Digital Ordering</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        [x-cloak] { display: none !important; }
        .maroon-gradient { background: linear-gradient(135deg, #800000 0%, #a00000 100%); }
        .clay-card { box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(255, 255, 255, 0.05); }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4" x-data="tableSetup()" x-init="initSetup()" data-table="{{ $table }}">
    <div class="w-full max-w-sm">
        <div class="clay-card overflow-hidden bg-white rounded-3xl shadow-2xl">
            <div class="maroon-gradient p-8 text-white text-center">
                <h1 class="text-2xl font-black uppercase tracking-tighter">Table <span x-text="tableNumber"></span> Setup</h1>
                <p class="text-[10px] font-bold uppercase tracking-widest opacity-80 mt-1">Initialize Guest Session</p>
            </div>

            <div class="p-8 space-y-6">
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block text-center">Adults</label>
                        <input type="number" x-model.number="guestSetup.adults" min="0" class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl p-4 text-center font-black text-xl focus:border-[#800000] focus:bg-white outline-none transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block text-center">Children</label>
                        <input type="number" x-model.number="guestSetup.children" min="0" class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl p-4 text-center font-black text-xl focus:border-[#800000] focus:bg-white outline-none transition-all">
                    </div>
                </div>

                <div class="bg-slate-50 p-4 rounded-2xl flex justify-between items-center border border-slate-100">
                    <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Total Pax</span>
                    <span class="text-2xl font-black text-[#800000]" x-text="guestSetup.adults + guestSetup.children"></span>
                </div>

                <div class="flex flex-col gap-3 pt-2">
                    <button @click="startSession()" class="w-full py-4 bg-[#800000] text-white rounded-2xl font-black text-[11px] uppercase shadow-lg shadow-slate-900/10 hover:bg-[#660000] transition-all">
                        Confirm
                    </button>
                    <button @click="cancelSetup()" class="w-full py-3 text-slate-400 font-black text-[10px] uppercase hover:text-slate-600 transition-all">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function tableSetup() {
            return {
                tableNumber: null,
                guestSetup: { adults: 0, children: 0 },

                initSetup() {
                    if (!this.tableNumber) {
                        const app = document.querySelector('[x-data="tableSetup()"]');
                        this.tableNumber = app ? parseInt(app.dataset.table, 10) : null;
                    }
                    // Store table number in localStorage
                    localStorage.setItem('customer_order_table', this.tableNumber);

                    // Check if already set up
                    const savedAdults = localStorage.getItem('customer_guests_adults');
                    const savedChildren = localStorage.getItem('customer_guests_children');

                    if (savedAdults && savedChildren && (parseInt(savedAdults, 10) > 0 || parseInt(savedChildren, 10) > 0)) {
                        // Already set up, redirect to menu
                        window.location.href = "{{ route('order.menu') }}";
                    }
                },

                startSession() {
                    if (this.guestSetup.adults <= 0 && this.guestSetup.children <= 0) {
                        alert('Please enter a valid guest count. At least 1 guest is required.');
                        return;
                    }

                    // Save guest data
                    localStorage.setItem('customer_guests_adults', this.guestSetup.adults);
                    localStorage.setItem('customer_guests_children', this.guestSetup.children);

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

                    const index = storedTables.findIndex(t => t.id === this.tableNumber);
                    if (index !== -1) {
                        storedTables[index].status = 'reserved-advance';
                        storedTables[index].type = 'advance-order';
                        storedTables[index].adults = this.guestSetup.adults;
                        storedTables[index].children = this.guestSetup.children;
                        storedTables[index].reservedAt = new Date().toISOString();
                    }
                    localStorage.setItem('ub_tables', JSON.stringify(storedTables));

                    // Redirect to menu
                    window.location.href = "{{ route('order.menu') }}";
                },

                cancelSetup() {
                    // Redirect back to booking choice
                    window.location.href = "{{ route('order.booking-choice') }}";
                }
            };
        }
    </script>
</body>
</html>