<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waiter Command Center | UB-SYNC</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #334155; overflow-x: hidden; }
        .aws-header { background-color: #800000; height: 65px; display: flex; align-items: center; justify-content: space-between; padding: 0 25px; color: white; position: fixed; top: 0; width: 100%; z-index: 1000; }
        .gold-accent { background-color: #D4AF37; height: 4px; position: fixed; top: 65px; width: 100%; z-index: 999; }
        .aws-sidebar { width: 260px; background: white; border-right: 1px solid #eaeded; height: calc(100vh - 69px); position: fixed; top: 69px; left: 0; transition: all 0.3s ease; z-index: 1000; }
        .sidebar-collapsed { left: -260px; }
        .main-content { margin-left: 260px; margin-top: 69px; padding: 30px; transition: all 0.3s ease; min-height: calc(100vh - 69px); }
        .content-wide { margin-left: 0; width: 100%; }
        
        @media (max-width: 768px) {
            .main-content { margin-left: 0 !important; padding: 15px; width: 100%; }
            .aws-sidebar { box-shadow: 10px 0 15px rgba(0,0,0,0.1); z-index: 1001; }
        }

        .clay-card { background: white; border: 1px solid #f1f5f9; border-radius: 16px; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); transition: all 0.3s ease; }
        .maroon-gradient { background: linear-gradient(135deg, #800000 0%, #a52a2a 100%); }
        .custom-scroll::-webkit-scrollbar { width: 4px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #800000; border-radius: 10px; }
        [x-cloak] { display: none !important; }
    </style>
</head>

<body x-data="waiterSystem()" x-init="init()">

    <header class="aws-header">
        <div class="flex items-center gap-4">
            <button @click="sidebarOpen = !sidebarOpen" class="hover:bg-white/20 p-2 rounded transition cursor-pointer">
                <i class="fas fa-bars"></i> 
            </button>
           
        </div>

        <div class="flex items-center gap-6 text-sm font-bold">
            <div class="relative" x-data="{ open: false }" @click.away="open = false">
                <button @click="open = !open" class="flex items-center gap-3 border-l border-white/20 pl-6 h-full hover:bg-white/5 p-2 rounded transition-all focus:outline-none">
                    <div class="hidden md:block text-right">
                        <span class="text-[10px] text-white/60 block leading-none uppercase tracking-widest font-black">Staff</span>
                        <p class="font-bold text-white uppercase text-sm tracking-tight">{{ Auth::user()->name ?? 'Guest User' }}</p>
                    </div>
                    <div class="relative">
                        <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-xs">W</div>
                        <div class="absolute -bottom-0.5 -right-0.5 bg-emerald-500 w-2.5 h-2.5 rounded-full border-2 border-[#800000]"></div>
                    </div>
                </button>

                <div x-show="open" x-cloak class="absolute right-0 mt-3 w-56 bg-white rounded-xl shadow-xl py-2 z-[1100] border border-slate-200 overflow-hidden text-slate-800">
                    <div class="px-4 py-3 bg-slate-50 border-b border-slate-100 mb-1">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-0.5">Role: Waiter</p>
                        <p class="text-xs font-bold text-slate-800 truncate">{{ Auth::user()->email ?? 'waiter@ub.edu.ph' }}</p>
                    </div>
                    <div class="px-2">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left px-3 py-2.5 text-[11px] font-black text-red-600 hover:bg-red-50 rounded-lg uppercase tracking-widest flex items-center gap-3">
                                <i class="fa-solid fa-power-off text-sm"></i> Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="gold-accent"></div>

    <aside class="aws-sidebar shadow-sm" :class="!sidebarOpen ? 'sidebar-collapsed' : ''">
        <div class="p-4 space-y-2">
            
            <button @click="switchTab('home')" 
                :class="tab === 'home' ? 'bg-red-50 text-[#800000] font-black' : 'text-slate-500 hover:bg-slate-50 font-bold'" 
                class="w-full flex items-center gap-3 p-3 rounded-xl transition-all text-sm">
                <i class="fa-solid fa-table-cells-large w-5"></i> Floor Plan
            </button>
            <button @click="switchTab('reservations')" 
                :class="tab === 'reservations' ? 'bg-red-50 text-[#800000] font-black' : 'text-slate-500 hover:bg-slate-50 font-bold'" 
                class="w-full flex items-center gap-3 p-3 rounded-xl transition-all text-sm">
                <i class="fas fa-calendar-check w-5"></i> Reservations
            </button>
        </div>
    </aside>

    <main class="main-content" :class="!sidebarOpen ? 'content-wide' : ''">
        <div x-show="tab === 'home'" x-cloak class="space-y-8">
            <div>
                <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tighter leading-none">Floor Plan</h1>
                <p class="text-xs text-slate-500 mt-2 font-bold uppercase tracking-[0.2em] flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Live Table Status Monitor
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="clay-card border-t-4 border-t-emerald-500 p-6 shadow-sm relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-20 group-hover:scale-110 transition-transform">
                        <i class="fas fa-door-open text-5xl text-emerald-600"></i>
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Available Tables</p>
                    <p class="text-3xl font-black text-emerald-600 mt-1" x-text="tables.filter(t => t.status === 'available').length"></p>
                    <p class="text-[10px] font-bold text-slate-400 mt-2">Ready for seating</p>
                </div>

                <div class="clay-card border-t-4 border-t-red-800 p-6 shadow-sm relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-20 group-hover:scale-110 transition-transform">
                        <i class="fas fa-utensils text-5xl text-red-800"></i>
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Active Tables</p>
                    <p class="text-3xl font-black text-red-800 mt-1" x-text="tables.filter(t => t.status === 'occupied').length"></p>
                    <p class="text-[10px] font-bold text-slate-400 mt-2">Current active guests</p>
                </div>

                <div class="clay-card border-t-4 border-t-blue-500 p-6 shadow-sm relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-20 group-hover:scale-110 transition-transform">
                        <i class="fas fa-clock text-5xl text-blue-600"></i>
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Reservations</p>
                    <p class="text-3xl font-black text-slate-800 mt-1" x-text="reservations.filter(r => r.status === 'confirmed').length"></p>
                    <p class="text-[10px] font-bold text-slate-400 mt-2">For today's schedule</p>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
    <template x-for="table in tables" :key="table.id">
        <div @click="selectTable(table)" 
             class="w-full max-w-[220px] sm:max-w-[240px] min-h-[170px] sm:min-h-[190px] lg:min-h-[210px] transition-all hover:shadow-xl hover:-translate-y-1 flex flex-col items-center justify-center space-y-2 rounded-[1.25rem] border-2 shadow-sm relative group"
             :class="table.status === 'available' ? 'bg-[#ccfad8] border-[#4ade80]' : (table.isPaid === true ? 'bg-[#bfdbfe] border-[#3b82f6]' : (table.status === 'reserved-advance' ? 'bg-[#fed7aa] border-[#ea580c]' : (table.status === 'reserved-booking' ? 'bg-[#ffedd5] border-[#fb923c]' : 'bg-[#ffdada] border-[#f87171]')))">
            <div class="text-4xl font-black text-[#1e293b] tracking-tight" x-text="table.id"></div>
            
            <p class="text-[11px] font-extrabold uppercase tracking-widest" 
               :class="table.status === 'available' ? 'text-emerald-700' : (table.isPaid === true ? 'text-blue-700' : (table.status === 'reserved-advance' ? 'text-orange-700' : (table.status === 'reserved-booking' ? 'text-amber-700' : 'text-[#cc0000]')))"
               x-text="table.status === 'available' ? 'available' : (table.isPaid === true && table.status === 'reserved-advance' ? 'advance order (paid)' : (table.status === 'reserved-advance' ? 'advance order' : (table.status === 'reserved-booking' ? 'table reservation' : (table.isPaid === true ? 'paid' : 'occupied'))))"></p>
            
            <template x-if="table.status !== 'available'">
                <div class="text-center pt-1 w-full">
                    <p class="text-sm font-bold text-[#1e293b]"><span x-text="table.guests ?? ((table.adults || 0) + (table.children || 0))"></span> guests</p>
                </div>
            </template>
        </div>
    </template>
</div>
            
        </div>

        <div x-show="tab === 'reservations'" x-cloak class="animate__animated animate__fadeIn">
            @include('waiter.reservation')
        </div>
    </main>

    <div x-show="showSetupModal" x-cloak class="fixed inset-0 z-[1200] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div class="clay-card w-full max-w-sm overflow-hidden bg-white rounded-3xl shadow-2xl">
            <div class="maroon-gradient p-8 text-white text-center">
                <h3 class="text-2xl font-black uppercase tracking-tighter">Table <span x-text="selectedTable?.id"></span> Setup</h3>
                <p class="text-[10px] font-bold uppercase tracking-widest opacity-80 mt-1">Initialize Guest Session</p>
            </div>
            
            <div class="p-8 space-y-6">
                <div class="grid grid-cols-2 gap-4">
                   <div class="space-y-2">
    <label class="text-[10px] font-black uppercase text-black tracking-widest block text-center">Adults</label>
    <input type="number" x-model.number="guestSetup.adults" min="0" class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl p-4 text-center font-black text-xl focus:border-[#800000] focus:bg-white outline-none transition-all">
</div>
                    <div class="space-y-2 text-center">
                        <label class="text-[10px] font-black uppercase text-black tracking-widest block text-center">Children</label>
                        <input type="number" x-model.number="guestSetup.children" min="0" class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl p-4 text-center font-black text-xl focus:border-[#800000] focus:bg-white outline-none transition-all">
                    </div>
                </div>

                <div class="bg-slate-50 p-4 rounded-2xl flex justify-between items-center border border-slate-100">
                    <span class="text-xs font-black text-black uppercase tracking-widest">Total Pax</span>
                    <span class="text-2xl font-black text-[#800000]" x-text="guestSetup.adults + guestSetup.children"></span>
                </div>

                <div class="flex flex-col gap-3 pt-2">
                    <button @click="startSession()" class="w-full py-4 bg-[#800000] text-white rounded-2xl font-black text-[11px] uppercase shadow-lg shadow-red-900/20 hover:bg-red-900 transition-all">
                        Open Table & Order Menu
                    </button>
                    <button @click="showSetupModal = false" class="w-full py-3 text-black font-black text-[10px] uppercase  transition-all">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div x-show="showOrderModal" x-cloak class="fixed inset-0 z-[1200] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div class="clay-card w-full max-w-md overflow-hidden bg-white rounded-3xl shadow-2xl">
            <div class="maroon-gradient p-6 text-white flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-black uppercase tracking-tighter">Table <span x-text="selectedTable?.id"></span> Bill</h3>
                    <p class="text-[10px] font-bold uppercase tracking-widest opacity-80">Current Active Session</p>
                </div>
                <button @click="showOrderModal = false" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 transition-all">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="p-6">
                <div class="space-y-3 max-h-64 overflow-y-auto custom-scroll mb-6 pr-2">
                    <template x-for="(item, index) in (selectedTable?.orders || [])" :key="index">
                        <div class="flex justify-between items-center p-4 bg-slate-50 rounded-2xl border border-slate-100 group">
                            <div class="flex items-center gap-4">
                                <span class="w-8 h-8 flex items-center justify-center bg-red-100 text-[#800000] text-xs font-black rounded-lg" x-text="item.qty + 'x'"></span>
                                <div>
                                    <p class="text-[11px] font-black text-slate-800 uppercase tracking-tight" x-text="item.name"></p>
                                    <template x-if="item.addonName && item.addonName.toLowerCase() !== 'default'">
                                        <p class="text-[9px] text-orange-600 font-bold uppercase tracking-widest" x-text="item.addonName"></p>
                                    </template>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <p class="text-sm font-black text-slate-700" x-text="formatCurrency(item.price * item.qty)"></p>
                            </div>
                        </div>
                    </template>

                    <template x-if="!selectedTable?.orders || selectedTable?.orders.length === 0">
                        <div class="text-center py-10">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-receipt text-slate-200 text-2xl"></i>
                            </div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">No orders yet</p>
                        </div>
                    </template>
                </div>

                <div class="border-t border-dashed border-slate-200 pt-5 mb-6">
                    <div class="flex justify-between items-center px-1 pb-3 border-b border-slate-200 mb-3">
                        <span class="text-[12px] font-bold text-black uppercase tracking-[0.2em]">Subtotal</span>
                        <span class="text-xl font-black text-slate-700 tracking-tighter" x-text="formatCurrency(selectedTable?.bill || 0)"></span>
                    </div>
                    <div class="flex justify-between items-center px-1 pb-3">
                        <span class="text-[12px] font-bold text-black uppercase tracking-[0.2em]">VAT (5%)</span>
                        <span class="text-sm font-bold text-slate-700 tracking-tighter" x-text="formatCurrency((selectedTable?.bill || 0) * 0.05)"></span>
                    </div>
                    <div class="flex justify-between items-center px-1 pt-3 border-t border-slate-200">
                        <span class="text-3xl font-black text-[#800000] tracking-tighter" x-text="formatCurrency((selectedTable?.bill || 0) * 1.05)"></span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <button x-show="selectedTable?.isPaid === true" class="col-span-2 py-4 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-2xl font-black text-[11px] uppercase shadow-lg shadow-blue-900/10 flex items-center justify-center gap-2">
                        <i class="fas fa-check-circle text-xs"></i>
                        <span>PAID</span>
                    </button>
                    <button x-show="selectedTable?.isPaid !== true" @click="printOrder(selectedTable.id)" class="py-4 bg-emerald-600 text-white rounded-2xl font-black text-[11px] uppercase shadow-lg shadow-emerald-900/10 hover:bg-emerald-700 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-print text-xs"></i> Print
                    </button>
                    <button x-show="selectedTable?.isPaid !== true" @click="window.location.href = '{{ route('waiter.menu') }}?table=' + selectedTable.id + '&adults=' + (selectedTable.adults || 0) + '&children=' + (selectedTable.children || 0)"
    class="py-4 bg-[#800000] text-white rounded-2xl font-black text-[11px] uppercase shadow-lg shadow-red-900/10 hover:bg-red-900 transition-all flex items-center justify-center gap-2">
    <i class="fas fa-plus text-xs"></i> Add Order
</button>

                </div>
            </div>
        </div>
    </div>

    <div x-show="showReservedModal" x-cloak class="fixed inset-0 z-[1200] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div class="clay-card w-full max-w-sm overflow-hidden bg-white rounded-3xl shadow-2xl">
            <div class="bg-amber-500 p-8 text-white text-center">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-calendar-check text-xl"></i>
                </div>
                <h3 class="text-2xl font-black uppercase tracking-tighter">Table <span x-text="selectedTable?.id"></span></h3>
                <p class="text-[10px] font-bold uppercase tracking-widest opacity-80 mt-1" x-text="selectedTable?.status === 'reserved-advance' ? 'Advance Order Reserved' : 'Table Reservation'"></p>
            </div>
            <div class="p-8 space-y-6">
                <div class="text-center">
                    <p class="text-sm font-bold text-slate-600 mb-4">
                        <span x-text="selectedTable?.guests ?? ((selectedTable?.adults || 0) + (selectedTable?.children || 0))"></span> guests reserved
                    </p>
                    <template x-if="selectedTable?.status === 'reserved-advance' && selectedTable?.orders && selectedTable?.orders.length > 0">
                        <div class="space-y-2">
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Advance Orders</p>
                            <template x-for="order in selectedTable.orders" :key="order.id">
                                <div class="flex justify-between items-center bg-slate-50 p-3 rounded-lg">
                                    <span class="text-sm font-bold" x-text="order.name"></span>
                                    <span class="text-sm text-[#800000] font-black" x-text="'₱' + order.price.toFixed(2)"></span>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <button @click="clearTable(selectedTable?.tableNumber || selectedTable?.id)" class="py-4 bg-emerald-600 text-white rounded-2xl font-black text-[11px] uppercase shadow-lg shadow-emerald-900/10 hover:bg-emerald-700 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-check-circle text-xs"></i> Finish and Clear
                    </button>
                    <button @click="showReservedModal = false" class="py-4 bg-[#800000] text-white rounded-2xl font-black text-[11px] uppercase shadow-lg shadow-red-900/10 hover:bg-red-900 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-times text-xs"></i> Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div x-show="showAdvanceOrderModal" x-cloak class="fixed inset-0 z-[1200] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div class="clay-card w-full max-w-md overflow-hidden bg-white rounded-3xl shadow-2xl">
            <div class="maroon-gradient p-6 text-white flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-black uppercase tracking-tighter">Table <span x-text="selectedTable?.id"></span> - Advance Order</h3>
                    <p class="text-[10px] font-bold uppercase tracking-widest opacity-80">Pre-ordered Items</p>
                </div>
                <button @click="showAdvanceOrderModal = false; advanceOrderSentToKitchen = false" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 transition-all">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="p-6">
                <div class="space-y-3 max-h-64 overflow-y-auto custom-scroll mb-6 pr-2">
                    <template x-for="(item, index) in (selectedTable?.orders || [])" :key="index">
                        <div class="flex justify-between items-center p-4 bg-slate-50 rounded-2xl border border-slate-100 group">
                            <div class="flex items-center gap-4">
                                <span class="w-8 h-8 flex items-center justify-center bg-orange-100 text-orange-700 text-xs font-black rounded-lg" x-text="item.qty + 'x'"></span>
                                <div>
                                    <p class="text-[11px] font-black text-slate-800 uppercase tracking-tight" x-text="item.name"></p>
                                    <template x-if="item.addonName && item.addonName.toLowerCase() !== 'default'">
                                        <p class="text-[9px] text-orange-600 font-bold uppercase tracking-widest" x-text="item.addonName"></p>
                                    </template>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <p class="text-sm font-black text-slate-700" x-text="formatCurrency(item.price * item.qty)"></p>
                            </div>
                        </div>
                    </template>

                    <template x-if="!selectedTable?.orders || selectedTable?.orders.length === 0">
                        <div class="text-center py-10">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-receipt text-slate-200 text-2xl"></i>
                            </div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">No advance orders</p>
                        </div>
                    </template>
                </div>

                <div class="border-t border-dashed border-slate-200 pt-5 mb-6">
                    <div class="flex justify-between items-center px-1 pb-3 border-b border-slate-200 mb-3">
                        <span class="text-[12px] font-bold text-black uppercase tracking-[0.2em]">Subtotal</span>
                        <span class="text-xl font-black text-slate-700 tracking-tighter" x-text="formatCurrency(selectedTable?.bill || 0)"></span>
                    </div>
                    <div class="flex justify-between items-center px-1 pb-3">
                        <span class="text-[12px] font-bold text-black uppercase tracking-[0.2em]">VAT (5%)</span>
                        <span class="text-sm font-bold text-slate-700 tracking-tighter" x-text="formatCurrency((selectedTable?.bill || 0) * 0.05)"></span>
                    </div>
                    <div class="flex justify-between items-center px-1 pt-3 border-t border-slate-200">
                        <span class="text-3xl font-black text-[#800000] tracking-tighter" x-text="formatCurrency((selectedTable?.bill || 0) * 1.05)"></span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <button x-show="selectedTable?.isPaid === true" class="col-span-2 py-4 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-2xl font-black text-[11px] uppercase shadow-lg shadow-blue-900/10 flex items-center justify-center gap-2">
                        <i class="fas fa-check-circle text-xs"></i>
                        <span>PAID</span>
                    </button>
                    <button x-show="selectedTable?.isPaid !== true && !advanceOrderSentToKitchen" @click="showAdvanceOrderModal = false; showAdvanceOrderSummaryModal = true" class="col-span-2 py-4 bg-[#800000] text-white rounded-2xl font-black text-[11px] uppercase shadow-lg shadow-red-900/10 hover:bg-red-900 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-eye text-xs"></i> View Orders
                    </button>
                    <button x-show="selectedTable?.isPaid !== true && advanceOrderSentToKitchen" @click="printOrder(selectedTable.id)" class="py-4 bg-emerald-600 text-white rounded-2xl font-black text-[11px] uppercase shadow-lg shadow-emerald-900/10 hover:bg-emerald-700 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-print text-xs"></i> Print
                    </button>
                    <button x-show="selectedTable?.isPaid !== true && advanceOrderSentToKitchen" @click="window.location.href = '{{ route('waiter.menu') }}?table=' + selectedTable.id + '&adults=' + (selectedTable.adults || 0) + '&children=' + (selectedTable.children || 0)" class="py-4 bg-[#800000] text-white rounded-2xl font-black text-[11px] uppercase shadow-lg shadow-red-900/10 hover:bg-red-900 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-plus text-xs"></i> Add Order
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div x-show="showCompleteOrderModal" x-cloak class="fixed inset-0 z-[1400] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
            <div class="p-8 font-mono text-sm leading-relaxed border-b-2 border-black max-h-[70vh] overflow-y-auto">
                <div class="text-center mb-6 pb-4 border-b-2 border-dashed border-black">
                    <p class="text-sm font-black text-black">UNIVERSITY OF BATANGAS</p>
                    <p class="text-xs text-black">Hilltop Rd. Batangas City, 4250 Batangas</p>
                </div>

                <div class="border-4 border-black rounded-lg p-4 mb-6 text-center">
                    <p class="text-2xl font-black tracking-widest text-black" x-text="'TABLE ' + (selectedTable?.id || 'WALK-IN')"></p>
                </div>

                <div class="mb-6 pb-4 border-b-2 border-dashed border-black">
                    <div class="flex justify-between text-xs mb-2">
                        <span class="text-black font-semibold">ORDER ID:</span>
                        <span class="text-black font-semibold" x-text="currentReceiptOrderId"></span>
                    </div>
                    <div class="flex justify-between text-xs mb-2">
                        <span class="text-black font-semibold">DATE:</span>
                        <span class="text-black font-semibold" x-text="getCurrentDate()"></span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-black font-semibold">TIME:</span>
                        <span class="text-black font-semibold" x-text="getCurrentTime()"></span>
                    </div>
                </div>

                <div class="mb-6 pb-4 border-b-2 border-dashed border-black">
                    <p class="text-xs font-black mb-3 text-black">QTY ITEM/S</p>
                    <template x-for="item in (selectedTable?.orders || [])" :key="item.name">
                        <div class="mb-2 text-xs">
                            <div class="flex justify-between text-black">
                                <span><span x-text="item.qty"></span>x <span x-text="item.name.toUpperCase()"></span></span>
                                <span x-text="formatCurrency(item.price * item.qty)"></span>
                            </div>
                            <p x-show="item.addonName && item.addonName.toLowerCase() !== 'default'" class="text-[10px] text-black ml-4" x-text="'+ ' + item.addonName"></p>
                        </div>
                    </template>
                </div>

                <div class="mb-6 pb-4 border-b-2 border-dashed border-black">
                    <div class="flex justify-between font-black text-sm text-black mb-2">
                        <span>SUBTOTAL:</span>
                        <span x-text="formatCurrency(selectedTable?.bill || 0)"></span>
                    </div>
                    <div class="flex justify-between font-bold text-xs text-black mb-2">
                        <span>VAT (5%):</span>
                        <span x-text="formatCurrency((selectedTable?.bill || 0) * 0.05)"></span>
                    </div>
                    <div class="flex justify-between font-black text-sm text-black pt-2 border-t-2 border-dashed border-black">
                        <span>TOTAL:</span>
                        <span x-text="formatCurrency((selectedTable?.bill || 0) * 1.05)"></span>
                    </div>
                </div>

                <div class="text-center text-xs">
                    <p class="font-bold mb-1 text-black">THANK YOU!</p>
                    <p class="text-black">Please come again.</p>
                </div>
            </div>

            <div class="bg-slate-50 p-4 flex gap-3 border-t border-slate-200">
                <button @click="confirmPrint(selectedTable?.id)" class="w-full py-3 maroon-gradient text-white font-semibold rounded-lg text-sm shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-check"></i> Confirm Print
                </button>
            </div>
        </div>
    </div>

    <!-- Advance Order Kitchen Ticket Modal -->
    <div x-show="showAdvanceKitchenTicketModal" x-cloak class="fixed inset-0 z-[1400] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-sm overflow-hidden">
            <!-- Simple Header -->
            <div class="p-4 text-center border-b border-slate-200">
                <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mb-1">Advance Order</p>
                <p class="text-lg font-bold text-slate-900">ORDER #<span x-text="currentReceiptOrderId"></span></p>
                <p class="text-xs text-slate-500 mt-1">TABLE <span x-text="selectedTable?.id || 'WALK-IN'"></span> • <span x-text="getCurrentTime()"></span></p>
            </div>

            <!-- Items -->
            <div class="p-6 max-h-[60vh] overflow-y-auto">
                <template x-for="(item, index) in (selectedTable?.orders || [])" :key="index">
                    <div class="mb-5 pb-4 border-b border-slate-100 last:border-b-0 last:pb-0 last:mb-0">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <p class="text-sm font-bold text-slate-900 uppercase" x-text="item.name"></p>
                                <p x-show="item.addonName && item.addonName.toLowerCase() !== 'default'" class="text-xs text-slate-600 mt-1" x-text="'+ ' + item.addonName"></p>
                            </div>
                            <p class="text-2xl font-black text-slate-900" x-text="item.qty"></p>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Action Buttons -->
            <div class="bg-slate-50 p-4 flex gap-3 border-t border-slate-200">
                <button @click="showAdvanceKitchenTicketModal = false; showAdvanceOrderModal = true" class="flex-1 py-2 bg-white border border-slate-200 text-slate-600 font-semibold rounded-lg text-xs hover:bg-slate-100 transition-all">Cancel</button>
                <button @click="finalizeAdvanceOrder(selectedTable?.id)" class="flex-1 py-2 maroon-gradient text-white font-semibold rounded-lg text-xs shadow-md hover:shadow-lg transition-all">
                    Send to Kitchen
                </button>
            </div>
        </div>
    </div>

    <!-- Advance Order Summary Modal -->
    <div x-show="showAdvanceOrderSummaryModal" x-cloak class="fixed inset-0 z-[1400] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-sm overflow-hidden">
            <div class="p-6 text-center border-b border-slate-200">
                <p class="text-lg font-bold text-slate-900">Order Summary</p>
                <p class="text-xs text-slate-500 mt-1">Table <span x-text="selectedTable?.id"></span></p>
            </div>

            <div class="p-6 space-y-4 max-h-[50vh] overflow-y-auto">
                <!-- Items List -->
                <div class="space-y-3 pb-4 border-b border-slate-200">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Items</p>
                    <template x-for="item in (selectedTable?.orders || [])" :key="item.id">
                        <div class="flex justify-between items-start text-sm">
                            <div class="flex-1">
                                <p class="font-semibold text-slate-900" x-text="item.qty + 'x ' + item.name"></p>
                                <p x-show="item.addonName && item.addonName.toLowerCase() !== 'default'" class="text-xs text-slate-600 mt-0.5" x-text="'+ ' + item.addonName"></p>
                            </div>
                            <p class="font-bold text-slate-900 ml-2" x-text="formatCurrency(item.price * item.qty)"></p>
                        </div>
                    </template>
                </div>

                <!-- Subtotal -->
                <div class="flex justify-between items-center">
                    <span class="text-sm font-semibold text-slate-600">Subtotal:</span>
                    <span class="text-lg font-bold text-slate-900" x-text="formatCurrency(selectedTable?.bill || 0)"></span>
                </div>

                <!-- VAT 5% -->
                <div class="flex justify-between items-center">
                    <span class="text-sm font-semibold text-slate-600">VAT (5%):</span>
                    <span class="text-lg font-bold text-slate-900" x-text="formatCurrency((selectedTable?.bill || 0) * 0.05)"></span>
                </div>

                <!-- Total -->
                <div class="border-t border-slate-200 pt-4 flex justify-between items-center">
                    <span class="text-sm font-bold text-slate-900 uppercase">Total:</span>
                    <span class="text-2xl font-black text-[#800000]" x-text="formatCurrency((selectedTable?.bill || 0) + ((selectedTable?.bill || 0) * 0.05))"></span>
                </div>
            </div>

            <div class="bg-slate-50 p-4 flex gap-3 border-t border-slate-200">
                <button @click="showAdvanceOrderSummaryModal = false; showAdvanceOrderModal = true" class="flex-1 py-2 bg-white border border-slate-200 text-slate-600 font-semibold rounded-lg text-xs hover:bg-slate-100 transition-all">Cancel</button>
                <button @click="currentReceiptOrderId = 'ORD-' + Date.now(); showAdvanceOrderSummaryModal = false; showAdvanceKitchenTicketModal = true" class="flex-1 py-2 maroon-gradient text-white font-semibold rounded-lg text-xs shadow-md hover:shadow-lg transition-all">
                    Send Order
                </button>
            </div>
        </div>
    </div>

<script>
function waiterSystem() {
    return {
        tab: 'home',
        sidebarOpen: window.innerWidth >= 768,
        showSetupModal: false,
        selectedTable: null,
        showOrderModal: false,
        showReservedModal: false,
        showAdvanceOrderModal: false,
        showAdvanceKitchenTicketModal: false,
        showAdvanceOrderSummaryModal: false,
        showCompleteOrderModal: false,
        advanceOrderSentToKitchen: false,
        currentReceiptOrderId: '',

        // Data Arrays
        tables: [],
        reservations: [],
        guestSetup: { adults: 0, children: 0 },
        salesSummary: { total: 0 },

        init() {
            this.loadTables();
            this.loadReservations();

            // Makinig sa kahit anong pagbabago sa localStorage
            window.addEventListener('storage', (event) => {
                if (event.key === 'ub_reservations' || event.key === null) {
                    this.loadReservations();
                }
                if (event.key === 'ub_tables' || event.key === null) {
                    this.loadTables();
                }
            });

            setInterval(() => {
                this.loadTables();
                this.loadReservations();
            }, 2000);
        },

        // --- TABLE MANAGEMENT FUNCTIONS ---
        loadTables() {
            const stored = localStorage.getItem('ub_tables');
            if (stored) {
                let parsedTables = JSON.parse(stored);
                const reservations = JSON.parse(localStorage.getItem('ub_reservations') || '[]');

                // AUTOMATIC STATUS CHECKER:
                // Preserve reserved states and only set occupied when there are real orders.
                parsedTables = parsedTables.map(t => {
                    const status = (t.status === 'reserved-advance' || t.status === 'reserved-booking')
                        ? t.status
                        : ((t.orders && t.orders.length > 0) ? 'occupied' : 'available');

                    const matchedReservation = reservations.find(r => r.table == t.id)
                        || reservations.find(r => r.status === 'pending' && ((t.status === 'reserved-advance' && r.type === 'advance-order') || (t.status === 'reserved-booking' && r.type === 'table-reservation')));

                    const adults = t.adults ?? (matchedReservation ? matchedReservation.adults || 0 : 0);
                    const children = t.children ?? (matchedReservation ? matchedReservation.children || 0 : 0);
                    const guests = (t.guests || adults + children);

                    return {
                        ...t,
                        status: status,
                        isPaid: t.isPaid || false,
                        adults: adults,
                        children: children,
                        guests: guests,
                        bill: t.bill || 0,
                        orders: t.orders || []
                    };
                });

                this.tables = parsedTables;
                this.saveTables();
            } else {
                // Initial Load: 15 Tables, Lahat Available (Green)
                this.tables = Array.from({ length: 15 }, (_, i) => ({
                    id: i + 1,
                    status: 'available',
                    adults: 0,
                    children: 0,
                    bill: 0,
                    orders: []
                }));
                this.saveTables();
            }
        },

        selectTable(table) {
            this.selectedTable = table;

            if (table.status === 'reserved-advance') {
                this.showAdvanceOrderModal = true;
            } else if (table.status === 'occupied' || table.status === 'paid' || table.isPaid || (table.orders && table.orders.length > 0)) {
                this.showOrderModal = true;
            } else if (table.status === 'reserved-booking') {
                this.showReservedModal = true;
            } else {
                // GINAWANG 0 ANG DEFAULT DITO DIN
                this.guestSetup = { adults: 0, children: 0 };
                this.showSetupModal = true;
            }
        },

        clearTable(tableId) {
            const index = this.tables.findIndex(t => t.id === tableId);
            if (index !== -1) {
                this.tables[index].status = 'available';
                this.tables[index].adults = 0;
                this.tables[index].children = 0;
                this.tables[index].bill = 0;
                this.tables[index].orders = [];

                this.saveTables();
                this.showOrderModal = false;
                this.showReservedModal = false;
                this.showAdvanceOrderModal = false;
                this.selectedTable = null;

                let kOrders = JSON.parse(localStorage.getItem('ub_kitchen_orders') || '[]');
                let filteredK = kOrders.filter(ko => ko.table != tableId);
                localStorage.setItem('ub_kitchen_orders', JSON.stringify(filteredK));
            }
        },
startSession() {
            // VALIDATION: Check if both Adults and Children are 0
            if (this.guestSetup.adults <= 0 && this.guestSetup.children <= 0) {
                alert('Please enter a valid guest count. At least 1 guest is required to open a table.');
                return; // Stop the function from proceeding
            }

            if (this.selectedTable) {
                this.showSetupModal = false;
                
                const url = "{{ route('waiter.menu') }}?table=" + this.selectedTable.id + 
                            "&adults=" + this.guestSetup.adults + 
                            "&children=" + this.guestSetup.children;
                            
                window.location.href = url;
            }
        },
        

        saveTables() {
            localStorage.setItem('ub_tables', JSON.stringify(this.tables));
        },

        recalculateBill(table) {
            table.bill = (table.orders || []).reduce((sum, item) => sum + (item.price * item.qty), 0);
        },

        // --- RESERVATION FUNCTIONS ---
     loadReservations() {
    const stored = localStorage.getItem('ub_reservations');
    if (!stored) {
        this.reservations = [];
        return;
    }
    
    let rawData = JSON.parse(stored);

    this.reservations = rawData.map(res => {
        let s = res.status ? res.status.toLowerCase() : 'pending';
        return {
            ...res,
            status: s,
            createdAt: res.createdAt || res.created_at || null,
            table: res.table || null,
            type: res.type || 'table-reservation'
        };
    });
},

        async confirmReservation(resId) {
            const index = this.reservations.findIndex(r => r.id === resId);
            if (index === -1) {
                return;
            }

            this.reservations[index].status = 'confirmed';
            this.updateReservationStorage();

            const reservation = this.reservations[index];
            
            // For advance order, send link to select-tables for table selection
            // For table reservation, send link to select-tables for reservation confirmation
            const selectTablesUrl = '{{ route("order.select-tables") }}?type=' + reservation.type + '&resId=' + reservation.id;
            
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const response = await fetch('{{ route('reservation.confirm.email') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        id: reservation.id,
                        name: reservation.name,
                        email: reservation.email,
                        date: reservation.date,
                        time: reservation.time,
                        type: reservation.type,
                        table: reservation.table,
                        selectTablesUrl: selectTablesUrl
                    })
                });

                const result = await response.json().catch(() => ({ success: false }));

                if (!response.ok || !result.success) {
                    console.warn('Email API warning:', result);
                    alert('Reservation confirmed, but email sending failed. Check server logs.');
                    window.dispatchEvent(new Event('storage'));
                    return;
                }
            } catch (error) {
                console.warn('Email send failed:', error);
                alert('Reservation confirmed, but email sending failed. Please try again later.');
                window.dispatchEvent(new Event('storage'));
                return;
            }

            alert('Reservation confirmed! Email with the select-tables link has been sent to the customer.');
            window.dispatchEvent(new Event('storage'));
        },


        deleteReservation(resId) {
            this.reservations = this.reservations.filter(r => r.id !== resId);
            this.updateReservationStorage(); 
            window.dispatchEvent(new Event('storage'));
        },

        clearAllReservations() {
            this.reservations = [];
            this.updateReservationStorage();
            window.dispatchEvent(new Event('storage'));
        },

        updateReservationStorage() {
            localStorage.setItem('ub_reservations', JSON.stringify(this.reservations));
            this.reservations = [...this.reservations]; 
            window.dispatchEvent(new Event('storage'));
        },

        // --- UTILITIES ---
        formatTime(time) {
            if (!time) return '';
            const [hours, minutes] = time.split(':');
            let h = parseInt(hours);
            const ampm = h >= 12 ? 'PM' : 'AM';
            h = h % 12 || 12;   
            return `${h}:${minutes} ${ampm}`;
        },

        formatCurrency(num) {
            return '₱' + parseFloat(num || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
        },

        switchTab(target) {
            this.tab = target;
            if (window.innerWidth < 768) this.sidebarOpen = false;
        },

        getImageUrl(img) {
            if (!img || img === 'Default.png') return 'https://placehold.co/150x150/eeeeee/800000?text=No+Image';
            return img.startsWith('http') ? img : '/img/' + img;
        },

        getCurrentDate() {
            const now = new Date();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const year = now.getFullYear();
            return `${month}/${day}/${year}`;
        },

        getCurrentTime() {
            const now = new Date();
            let hours = now.getHours();
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12;
            const hoursStr = String(hours).padStart(2, '0');
            return `${hoursStr}:${minutes} ${ampm}`;
        },

        printOrder(tableId) {
            this.currentReceiptOrderId = 'ORD-' + Date.now();
            this.showOrderModal = false;
            this.showAdvanceOrderModal = false;
            this.showCompleteOrderModal = true;
        },

        finalizeAdvanceOrder(tableId) {
            let kitchenOrders = JSON.parse(localStorage.getItem('ub_kitchen_orders') || '[]');

            const transaction = {
                orderId: this.currentReceiptOrderId,
                timestamp: new Date().toLocaleTimeString(),
                totalAmount: (this.selectedTable.bill || 0) * 1.05,
                tableId: tableId,
                items: this.selectedTable.orders.map(item => ({
                    name: item.name,
                    qty: item.qty,
                    price: item.price,
                    addonName: item.addonName
                })),
                status: 'pending'
            };

            kitchenOrders.push(transaction);
            localStorage.setItem('ub_kitchen_orders', JSON.stringify(kitchenOrders));

            this.advanceOrderSentToKitchen = true;
            this.showAdvanceKitchenTicketModal = false;
            this.showAdvanceOrderModal = true;
            alert('Order successfully sent to stations!');
        },

        confirmPrint(tableId) {
            let tables = JSON.parse(localStorage.getItem('ub_tables') || '[]');
            let analyticsHistory = JSON.parse(localStorage.getItem('ub_order_history') || '[]');

            if (this.selectedTable && this.selectedTable.orders && this.selectedTable.orders.length > 0) {
                const transaction = {
                    orderId: this.currentReceiptOrderId,
                    timestamp: new Date().toLocaleTimeString(),
                    totalAmount: (this.selectedTable.bill || 0) * 1.05,
                    tableId: tableId,
                    items: this.selectedTable.orders.map(item => ({
                        name: item.name,
                        qty: item.qty,
                        price: item.price,
                        addonName: item.addonName
                    })),
                    status: 'completed'
                };

                analyticsHistory.unshift(transaction);
                localStorage.setItem('ub_order_history', JSON.stringify(analyticsHistory));

                let tableIndex = tables.findIndex(t => t.id === tableId);
                if (tableIndex !== -1) {
                    // Preserve advance order status
                    if (tables[tableIndex].status !== 'reserved-advance') {
                        tables[tableIndex].status = 'paid';
                    }
                    tables[tableIndex].isPaid = true;
                    localStorage.setItem('ub_tables', JSON.stringify(tables));

                    // Update selectedTable immediately
                    this.selectedTable.isPaid = true;
                }
            }

            alert('✓ Order ' + this.currentReceiptOrderId + ' printed successfully!');
            this.showCompleteOrderModal = false;
            this.loadTables();
        }
    }
}
</script>

</body>
</html>