<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Table | UB Sync</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .bg-maroon { background-color: #800000; }
        .text-maroon { color: #800000; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen font-sans" x-data="tableSelection()" x-init="init()" x-cloak>
    <div class="max-w-7xl mx-auto p-6">
        <!-- Header -->
        <div class="mb-8 rounded-[2rem] bg-white shadow-2xl border border-gray-200 overflow-hidden">
            <div class="bg-maroon p-8 text-center text-white relative overflow-hidden">
                <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
                <h1 class="text-3xl font-black uppercase tracking-widest relative z-10">Select Your Table</h1>
                <p class="relative z-10 mt-2 text-sm text-white/80" x-text="currentReservation ? 'Complete your reservation for ' + currentReservation.name : (bookingType === 'advance-order' ? 'Choose a table for your advance order' : 'Choose a table for your reservation')"></p>
            </div>
            <div class="p-6 md:p-8">
                <div class="flex flex-wrap gap-4 text-sm font-black">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-lg bg-[#ccfad8] border-2 border-[#4ade80]"></div>
                        <span class="text-emerald-700">Available</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-lg bg-[#fed7aa] border-2 border-[#ea580c]"></div>
                        <span class="text-orange-700">Advance Order</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-lg bg-[#fef3c7] border-2 border-[#f59e0b]"></div>
                        <span class="text-amber-700">Reservation</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-lg bg-[#ffdada] border-2 border-[#f87171]"></div>
                        <span class="text-red-700">Occupied</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tables Grid -->
        <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
            <template x-for="table in tables" :key="table.id">
                <div @click="table.status === 'available' ? selectTable(table) : null" 
                     class="w-full min-h-[170px] transition-all flex flex-col items-center justify-center space-y-2 rounded-xl border-2 shadow-sm relative group"
                         :class="getTableClass(table) + (table.status !== 'available' ? ' cursor-not-allowed opacity-80' : ' cursor-pointer hover:shadow-xl hover:-translate-y-1')">
                        
                        <div class="text-4xl font-black text-[#1e293b] tracking-tight" x-text="table.id"></div>
                        
                        <p class="text-[11px] font-extrabold uppercase tracking-widest" 
                           :class="getTableTextClass(table)" 
                           x-text="getTableStatusText(table)"></p>
                        
                        <template x-if="table.status === 'reserved-advance' || table.status === 'reserved-booking'">
                            <div class="text-center pt-1 w-full">
                                <p class="text-sm font-bold text-[#1e293b]"><span x-text="table.adults + table.children"></span> guests</p>
                            </div>
                        </template>
                    </div>
                </template>
            </div>


        <!-- Reservation Confirm Modal -->
        <div x-show="showConfirmModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4" x-cloak>
            <div class="w-full max-w-md rounded-[2rem] bg-white p-6 shadow-2xl">
                <div class="text-center mb-6 mt-2">
                    <h3 class="text-lg font-extrabold text-[#800000] tracking-wide leading-tight uppercase">Confirm Reservation</h3>
                    <p class="text-xs text-gray-500 mt-1">Reserve Table <span x-text="selectedTable ? selectedTable.id : ''"></span>?</p>
                </div>

                <div class="flex gap-3">
                    <button @click="cancelReservation()" class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-xl font-bold uppercase text-sm">Cancel</button>
                    <button @click="confirmReservation()" class="flex-1 bg-[#800000] text-white py-3 rounded-xl font-bold uppercase text-sm transition-all">Confirm</button>
                </div>
            </div>
        </div>

        <!-- Table Details Modal -->
        <div x-show="showDetailsModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4" x-cloak>
            <div class="w-full max-w-md rounded-[2rem] bg-white p-6 shadow-2xl">
                <div class="text-center mb-6 mt-2">
                    <h3 class="text-lg font-extrabold text-[#800000] tracking-wide leading-tight uppercase">Table <span x-text="selectedTable ? selectedTable.id : ''"></span></h3>
                    <p class="text-xs text-gray-500 mt-1" x-text="getDetailsText()"></p>
                </div>

                <template x-if="selectedTable && selectedTable.status === 'reserved-advance' && selectedTable.orders && selectedTable.orders.length > 0">
                    <div class="space-y-2 mb-6 max-h-40 overflow-y-auto">
                        <template x-for="order in selectedTable.orders" :key="order.id">
                            <div class="flex justify-between items-center bg-gray-50 p-3 rounded-lg">
                                <span class="text-sm font-bold" x-text="order.name"></span>
                                <span class="text-sm text-[#800000] font-black" x-text="'₱' + order.price.toFixed(2)"></span>
                            </div>
                        </template>
                    </div>
                </template>

                <div class="flex gap-3">
                    <button @click="closeDetailsModal()" class="flex-1 bg-[#800000] text-white py-3 rounded-xl font-bold uppercase text-sm transition-all">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function tableSelection() {
            return {
                tables: [],
                bookingType: 'advance-order',
                showConfirmModal: false,
                showDetailsModal: false,
                selectedTable: null,
                currentReservation: null,

                init() {
                    const params = new URLSearchParams(window.location.search);
                    this.bookingType = params.get('type') || 'advance-order';
                    const resId = params.get('resId');
                    if (resId) {
                        // Load reservation details
                        const reservations = JSON.parse(localStorage.getItem('ub_reservations') || '[]');
                        const reservation = reservations.find(r => r.id === resId);
                        if (reservation) {
                            this.currentReservation = reservation;
                        }
                    }
                    this.loadTables();
                },

                loadTables() {
                    const stored = localStorage.getItem('ub_tables');
                    if (stored) {
                        let parsed = JSON.parse(stored);
                        this.tables = parsed.map(t => ({
                            id: t.id,
                            status: t.status || (t.orders && t.orders.length > 0 ? 'occupied' : 'available'),
                            adults: t.adults || 0,
                            children: t.children || 0,
                            orders: t.orders || []
                        }));
                    } else {
                        this.tables = Array.from({ length: 15 }, (_, idx) => ({
                            id: idx + 1,
                            status: 'available',
                            adults: 0,
                            children: 0,
                            orders: []
                        }));
                    }
                },

                getTableClass(table) {
                    if (table.status === 'available') return 'bg-[#ccfad8] border-[#4ade80]';
                    if (table.status === 'reserved-advance') return 'bg-[#fed7aa] border-[#ea580c]';
                    if (table.status === 'reserved-booking') return 'bg-[#fef3c7] border-[#f59e0b]';
                    return 'bg-[#ffdada] border-[#f87171]';
                },

                getTableTextClass(table) {
                    if (table.status === 'available') return 'text-emerald-700';
                    if (table.status === 'reserved-advance') return 'text-orange-700';
                    if (table.status === 'reserved-booking') return 'text-amber-700';
                    return 'text-[#cc0000]';
                },

                getTableStatusText(table) {
                    if (table.status === 'available') return 'available';
                    if (table.status === 'reserved-advance') return 'TABLE ADVANCE ORDER';
                    if (table.status === 'reserved-booking') return 'TABLE RESERVATION';
                    return table.status.replace('-', ' ');
                },

                selectTable(table) {
                    if (table.status === 'occupied') {
                        alert('This table is currently occupied.');
                        return;
                    }

                    if (table.status === 'reserved-advance' || table.status === 'reserved-booking') {
                        this.selectedTable = table;
                        this.showDetailsModal = true;
                        return;
                    }

                    if (table.status === 'available') {
                        if (this.bookingType === 'advance-order') {
                            // Mark table as reserved-advance before redirecting
                            let storedTables = JSON.parse(localStorage.getItem('ub_tables') || '[]');
                            let tableIndex = storedTables.findIndex(t => t.id === table.id);
                            if (tableIndex !== -1) {
                                storedTables[tableIndex].status = 'reserved-advance';
                                // Get guest count from current reservation or latest pending
                                if (this.currentReservation) {
                                    storedTables[tableIndex].adults = this.currentReservation.adults;
                                    storedTables[tableIndex].children = this.currentReservation.children;
                                } else {
                                    let reservations = JSON.parse(localStorage.getItem('ub_reservations') || '[]');
                                    let latestReservation = reservations.find(r => r.status === 'pending' && r.type === 'advance-order');
                                    if (latestReservation) {
                                        storedTables[tableIndex].adults = latestReservation.adults;
                                        storedTables[tableIndex].children = latestReservation.children;
                                    }
                                }
                                localStorage.setItem('ub_tables', JSON.stringify(storedTables));
                                window.dispatchEvent(new Event('storage'));
                            }
                            // For advance order, redirect to menu with table parameter
                            window.location.href = `{{ route('order.menu') }}?table=` + table.id;
                        } else {
                            // For reservation, show confirm modal
                            this.selectedTable = table;
                            this.showConfirmModal = true;
                        }
                    }
                },

                confirmReservation() {
                    if (this.selectedTable) {
                        // Mark table as reserved-booking
                        let storedTables = JSON.parse(localStorage.getItem('ub_tables') || '[]');
                        let tableIndex = storedTables.findIndex(t => t.id === this.selectedTable.id);
                        if (tableIndex !== -1) {
                            storedTables[tableIndex].status = 'reserved-booking';
                            // Get guest count from current reservation or latest pending
                            if (this.currentReservation) {
                                storedTables[tableIndex].adults = this.currentReservation.adults;
                                storedTables[tableIndex].children = this.currentReservation.children;
                                this.currentReservation.status = 'confirmed';
                                this.currentReservation.table = this.selectedTable.id;
                                let reservations = JSON.parse(localStorage.getItem('ub_reservations') || '[]');
                                let resIndex = reservations.findIndex(r => r.id === this.currentReservation.id);
                                if (resIndex !== -1) {
                                    reservations[resIndex] = this.currentReservation;
                                    localStorage.setItem('ub_reservations', JSON.stringify(reservations));
                                }
                            } else {
                                let reservations = JSON.parse(localStorage.getItem('ub_reservations') || '[]');
                                let latestReservation = reservations.find(r => r.status === 'pending');
                                if (latestReservation) {
                                    storedTables[tableIndex].adults = latestReservation.adults;
                                    storedTables[tableIndex].children = latestReservation.children;
                                    latestReservation.status = 'confirmed';
                                    localStorage.setItem('ub_reservations', JSON.stringify(reservations));
                                }
                            }
                        }
                        localStorage.setItem('ub_tables', JSON.stringify(storedTables));
                        this.loadTables();
                        window.dispatchEvent(new Event('storage'));
                    }
                    this.showConfirmModal = false;
                    this.selectedTable = null;
                },

                cancelReservation() {
                    this.showConfirmModal = false;
                    this.selectedTable = null;
                },

                getDetailsText() {
                    if (!this.selectedTable) return '';
                    if (this.selectedTable.status === 'reserved-advance') {
                        return 'Advance order placed';
                    }
                    if (this.selectedTable.status === 'reserved-booking') {
                        return 'Table reserved';
                    }
                    return '';
                },

                closeDetailsModal() {
                    this.showDetailsModal = false;
                    this.selectedTable = null;
                }
            }
        }
    </script>
</body>
</html>
