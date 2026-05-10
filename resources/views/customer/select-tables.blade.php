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
                <p class="relative z-10 mt-2 text-sm text-white/80">Choose a table for your advance order</p>
            </div>
            <div class="p-6 md:p-8">
                <div class="flex flex-wrap gap-3 items-center justify-between">
                    <div>
                        <p class="text-sm uppercase tracking-[0.3em] text-slate-500 font-black">Status Legend</p>
                    </div>
                    <div class="flex flex-wrap gap-4 text-sm font-black">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-lg bg-[#ccfad8] border-2 border-[#4ade80]"></div>
                            <span class="text-emerald-700">Available</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-lg bg-[#fef3c7] border-2 border-[#f59e0b]"></div>
                            <span class="text-amber-700">Reserved</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-lg bg-[#ffdada] border-2 border-[#f87171]"></div>
                            <span class="text-red-700">Occupied</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tables Grid -->
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
            <template x-for="table in tables" :key="table.id">
                <div @click="selectTable(table)" 
                     class="w-full h-[170px] cursor-pointer transition-all hover:shadow-xl hover:-translate-y-1 flex flex-col items-center justify-center space-y-2 rounded-xl border-2 shadow-sm relative group"
                     :class="table.status === 'available' ? 'bg-[#ccfad8] border-[#4ade80]' : (table.status === 'reserved-advance' ? 'bg-[#fef3c7] border-[#f59e0b]' : 'bg-[#ffdada] border-[#f87171]')">
                    
                    <div class="text-4xl font-black text-[#1e293b] tracking-tight" x-text="table.id"></div>
                    
                    <p class="text-[11px] font-extrabold uppercase tracking-widest" 
                       :class="table.status === 'available' ? 'text-emerald-700' : (table.status === 'reserved-advance' ? 'text-amber-700' : 'text-[#cc0000]')" 
                       x-text="table.status.replace('-', ' ')"></p>
                    
                    <template x-if="table.status !== 'available'">
                        <div class="text-center pt-1 w-full">
                            <p class="text-sm font-bold text-[#1e293b]"><span x-text="table.adults + table.children"></span> guests</p>
                        </div>
                    </template>
                </div>
            </template>
        </div>

        <!-- Back Button -->
        <div class="mt-8 text-center">
            <a href="{{ route('order.booking-choice') }}" class="inline-flex items-center gap-2 rounded-full border border-slate-300 bg-white px-6 py-3 text-sm font-black uppercase tracking-[0.2em] text-slate-700 hover:bg-slate-50 transition-all shadow-sm">
                <i class="fas fa-arrow-left"></i> Go Back
            </a>
        </div>
    </div>

    <script>
        function tableSelection() {
            return {
                tables: [],

                init() {
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

                selectTable(table) {
                    if (table.status === 'occupied') {
                        alert('This table is currently occupied.');
                        return;
                    }

                    if (table.status === 'reserved-advance') {
                        alert('This table is already reserved for advance order.');
                        return;
                    }

                    window.location.href = `{{ route('order.menu', ':table') }}`.replace(':table', table.id);
                }
            }
        }
    </script>
</body>
</html>
