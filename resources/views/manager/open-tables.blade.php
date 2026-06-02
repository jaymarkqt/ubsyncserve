<div class="space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-4xl font-black text-slate-800 uppercase tracking-tighter leading-none">Open Tables</h1>
            <p class="text-xs text-slate-700 mt-2 font-bold uppercase tracking-[0.2em] flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                Real-time Table Occupancy Status
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <!-- Available Tables Card -->
        <div class="clay-card p-6 shadow-md hover:shadow-lg transition-all border border-slate-100">
            <div class="flex items-start gap-4">
                <div class="w-16 h-16 rounded-full bg-emerald-50 flex items-center justify-center flex-shrink-0">
                    <img src="/img/tables.png" alt="table" class="w-20 h-20 object-contain">
                </div>
                <div class="flex-1">
                    <p class="text-[11px] font-black text-slate-500 uppercase tracking-widest mb-1">Available Tables</p>
                    <p class="text-3xl font-black text-emerald-600" x-text="tablesMetrics.availableTables"></p>
                    <p class="text-[10px] font-bold text-slate-400 mt-1">Ready for seating</p>
                </div>
            </div>
        </div>

        <!-- Active Tables Card -->
        <div class="clay-card p-6 shadow-md hover:shadow-lg transition-all border border-slate-100">
            <div class="flex items-start gap-4">
                <div class="w-16 h-16 rounded-full bg-red-50 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-utensils text-2xl text-red-600"></i>
                </div>
                <div class="flex-1">
                    <p class="text-[11px] font-black text-slate-500 uppercase tracking-widest mb-1">Active Tables</p>
                    <p class="text-3xl font-black text-red-600" x-text="tablesMetrics.occupiedTables"></p>
                    <p class="text-[10px] font-bold text-slate-400 mt-1">Currently in use</p>
                </div>
            </div>
        </div>

        <!-- Total Guests Card -->
        <div class="clay-card p-6 shadow-md hover:shadow-lg transition-all border border-slate-100">
            <div class="flex items-start gap-4">
                <div class="w-16 h-16 rounded-full bg-blue-50 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-users text-2xl text-blue-600"></i>
                </div>
                <div class="flex-1">
                    <p class="text-[11px] font-black text-slate-500 uppercase tracking-widest mb-1">Total Guests</p>
                    <p class="text-3xl font-black text-slate-800" x-text="tablesMetrics.totalGuests"></p>
                    <p class="text-[10px] font-bold text-slate-400 mt-1">In restaurant</p>
                </div>
            </div>
        </div>
    </div>

   <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
  <template x-for="(table, index) in openTables" :key="index">
    <div @click="table.status !== 'available' ? handleTableClick(table) : null"
          class="clay-card p-6 flex flex-col items-center justify-center gap-4 transition-all hover:shadow-lg hover:scale-105 border-2"
          :class="{
            'bg-emerald-50 border-emerald-300 cursor-not-allowed': table.status === 'available' && !table.isPaid,
            'bg-blue-50 border-blue-300 hover:border-blue-500 cursor-pointer': table.isPaid === true,
            'bg-orange-50 border-orange-300 hover:border-orange-500 cursor-pointer': table.status === 'reserved-advance' && table.isPaid !== true,
            'bg-amber-50 border-amber-300 hover:border-amber-500 cursor-pointer': table.status === 'reserved-booking' && table.isPaid !== true,
            'bg-red-50 border-red-300 hover:border-red-500 cursor-pointer': table.status === 'occupied' && table.isPaid !== true
          }">

        <!-- Table Icon -->
        <div class="w-14 h-14 rounded-full flex items-center justify-center"
             :class="{
               'bg-emerald-200 text-emerald-700': table.status === 'available' && !table.isPaid,
               'bg-blue-200 text-blue-700': table.isPaid === true,
               'bg-orange-200 text-orange-700': table.status === 'reserved-advance' && table.isPaid !== true,
               'bg-amber-200 text-amber-700': table.status === 'reserved-booking' && table.isPaid !== true,
               'bg-red-200 text-red-700': table.status === 'occupied' && table.isPaid !== true
             }">
            <img src="/img/tables.png" alt="table" class="w-16 h-16 object-contain">
        </div>

        <!-- Table Number -->
        <div class="text-3xl font-black text-slate-800" x-text="table.tableNumber"></div>

        <!-- Status Label -->
        <p class="text-[10px] font-black uppercase tracking-widest text-center"
           :class="{
             'text-emerald-700': table.status === 'available' && !table.isPaid,
             'text-blue-700': table.isPaid === true,
             'text-orange-700': table.status === 'reserved-advance' && table.isPaid !== true,
             'text-amber-700': table.status === 'reserved-booking' && table.isPaid !== true,
             'text-red-700': table.status === 'occupied' && table.isPaid !== true
           }"
           x-text="table.status === 'available' ? 'AVAILABLE' : (table.isPaid === true && table.status === 'reserved-advance' ? 'PAID' : (table.status === 'reserved-advance' ? 'ADVANCE' : (table.status === 'reserved-booking' ? 'RESERVED' : (table.isPaid === true ? 'PAID' : 'ACTIVE'))))"></p>

        <!-- Guest Count (if occupied) -->
        <template x-if="table.status !== 'available'">
            <p class="text-xs font-bold text-slate-700 bg-white/50 px-3 py-1 rounded-full">
                <span x-text="table.guests ?? ((table.adults || 0) + (table.children || 0))"></span> guests
            </p>
        </template>
    </div>
</template>
</div>

   <!-- ================= ORDER / BILL MODAL ================= -->
<template x-teleport="body">
    <div x-show="showOrderModal" x-cloak 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[1200] flex items-center justify-center bg-slate-900/40 backdrop-blur-md p-4">
        
        <div x-show="showOrderModal"
            x-transition:enter="transition ease-out duration-300 delay-75"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         
            class="clay-card w-full max-w-md overflow-hidden bg-white rounded-[2rem] shadow-2xl border border-white/20">
            
            <!-- Header with Gradient & Close Button -->
         <div class="p-6 text-white flex justify-between items-center shadow-md relative z-10 bg-[#800000]">
    <div>
        <h3 class="text-2xl font-black uppercase tracking-tight">Table <span x-text="selectedTable?.tableNumber || selectedTable?.id"></span> Bill</h3>
        <p class="text-xs font-semibold uppercase tracking-widest text-red-200 mt-1">Current Active Session</p>
    </div>
</div>
            
            <!-- Body -->
            <div class="p-6 bg-white">
                <!-- Orders List -->
                <div class="space-y-3 max-h-[50vh] overflow-y-auto custom-scroll mb-6 pr-2">
                    <template x-for="(item, index) in (selectedTable?.orders || [])" :key="index">
                        <div class="flex justify-between items-center p-4 bg-white rounded-2xl border border-slate-100 shadow-sm transition-all group">
                            <div class="flex items-center gap-4">
                                <span class="w-10 h-10 flex items-center justify-center bg-red-50 text-[#800000] text-sm font-black rounded-xl border border-red-100" x-text="item.qty + 'x'"></span>
                                <div>
                                    <p class="text-sm font-black text-slate-800 tracking-tight" x-text="item.name"></p>
                                    <template x-if="item.addonName && item.addonName.toLowerCase() !== 'default'">
                                        <p class="text-[10px] text-orange-600 font-bold uppercase tracking-widest mt-0.5" x-text="item.addonName"></p>
                                    </template>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <p class="text-base font-black text-slate-700" x-text="formatCurrency(item.price * item.qty)"></p>
                            </div>
                        </div>
                    </template>

                    <template x-if="!selectedTable?.orders || selectedTable?.orders.length === 0">
                        <div class="text-center py-12 px-4 rounded-2xl bg-slate-50 border border-dashed border-slate-200">
                            <div class="w-20 h-20 bg-white shadow-sm rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                                <i class="fas fa-receipt text-slate-300 text-3xl"></i>
                            </div>
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest">No orders yet</p>
                        </div>
                    </template>
                </div>

                <!-- Bill Breakdowns -->
                <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100 mb-6">
                    <div class="flex justify-between items-center pb-3 border-b border-dashed border-slate-300 mb-3">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-[0.2em]">Subtotal</span>
                        <span class="text-lg font-black text-slate-700 tracking-tight" x-text="formatCurrency(selectedTable?.bill || 0)"></span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-dashed border-slate-300 mb-3">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-[0.2em]">VAT (5%)</span>
                        <span class="text-sm font-bold text-slate-600 tracking-tight" x-text="formatCurrency((selectedTable?.bill || 0) * 0.05)"></span>
                    </div>
                    <div class="flex justify-between items-center pt-2">
                        <span class="text-xl font-black text-slate-800 uppercase tracking-wider">Total</span>
                        <span class="text-3xl font-black text-[#800000] tracking-tight" x-text="formatCurrency((selectedTable?.bill || 0) * 1.05)"></span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="grid grid-cols-2 gap-4">
                    <template x-if="selectedTable?.status === 'paid' || selectedTable?.isPaid">
                        <button @click="clearTable(selectedTable?.tableNumber || selectedTable?.id)"
                            class="py-4 bg-emerald-600 text-white rounded-2xl font-black text-xs uppercase tracking-wider shadow-lg shadow-emerald-600/30 active:scale-95 transition-all flex items-center justify-center gap-2">
                            <i class="fas fa-check-circle text-sm"></i> PAID
                        </button>
                    </template>
                    <template x-if="selectedTable?.status !== 'paid' && !selectedTable?.isPaid">
                        <button class="py-4 bg-blue-600 text-white rounded-2xl font-black text-xs uppercase tracking-wider shadow-sm cursor-not-allowed flex items-center justify-center gap-2 opacity-60">
                            <i class="fas fa-clock text-sm"></i> Pending
                        </button>
                    </template>

                    <button @click="showOrderModal = false"
        class="py-4 bg-slate-800 text-white rounded-2xl font-black text-[11px] uppercase tracking-wider shadow-sm active:scale-95 transition-all flex items-center justify-center gap-2">
    Close
</button>

                </div>
            </div>
        </div>
    </div>
</template>

<!-- ================= RESERVED MODAL ================= -->
<template x-teleport="body">
    <div x-show="showReservedModal" x-cloak 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[1200] flex items-center justify-center bg-slate-900/40 backdrop-blur-md p-4">
        
        <div x-show="showReservedModal"
            x-transition:enter="transition ease-out duration-300 delay-75"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
          
            class="clay-card w-full max-w-sm overflow-hidden bg-white rounded-[2rem] shadow-2xl border border-white/20">
            
            <div class="bg-gradient-to-br from-amber-500 to-orange-600 p-8 text-white text-center shadow-inner relative">
                <div class="absolute inset-0 bg-white/5 pattern-dots"></div>
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mx-auto mb-4 border border-white/30 shadow-lg relative z-10">
                    <i class="fas fa-calendar-check text-2xl"></i>
                </div>
                <h3 class="text-3xl font-black uppercase tracking-tight relative z-10">Table <span x-text="selectedTable?.tableNumber || selectedTable?.id"></span></h3>
                <p class="text-xs font-bold uppercase tracking-[0.2em] opacity-90 mt-2 relative z-10">Table Reservation</p>
            </div>
            
            <div class="p-8 space-y-6 bg-white">
                <div class="text-center">
                    <div class="inline-block px-4 py-2 bg-amber-50 rounded-full border border-amber-100 mb-2">
                        <p class="text-sm font-bold text-amber-800">
                            <i class="fas fa-users mr-2 opacity-70"></i>
                            <span x-text="selectedTable?.guests ?? ((selectedTable?.adults || 0) + (selectedTable?.children || 0))"></span> guests reserved
                        </p>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4 pt-2">
                    <button @click="clearTable(selectedTable?.tableNumber || selectedTable?.id)"
                        class="py-4 bg-emerald-600 text-white rounded-2xl font-black text-[11px] uppercase tracking-wider shadow-lg shadow-emerald-600/30 active:scale-95 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-check-circle text-sm"></i> Finish & Clear
                    </button>

                     <button @click="showReservedModal = false"
        class="py-4 bg-slate-800 text-white rounded-2xl font-black text-[11px] uppercase tracking-wider shadow-sm active:scale-95 transition-all flex items-center justify-center gap-2">
    Close
</button>

                </div>
            </div>
        </div>
    </div>
</template>

<!-- ================= ADVANCE ORDER MODAL ================= -->
<template x-teleport="body">
    <div x-show="showAdvanceOrderModal" x-cloak 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[1200] flex items-center justify-center bg-slate-900/40 backdrop-blur-md p-4">
        
        <div x-show="showAdvanceOrderModal"
            x-transition:enter="transition ease-out duration-300 delay-75"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
         
            class="clay-card w-full max-w-md overflow-hidden bg-white rounded-[2rem] shadow-2xl border border-white/20">
            
            <!-- Header with Orange/Red Gradient -->
            <div class="bg-gradient-to-r from-orange-600 to-red-700 p-6 text-white flex justify-between items-center shadow-md relative z-10">
                <div>
                    <h3 class="text-2xl font-black uppercase tracking-tight">Table <span x-text="selectedTable?.tableNumber || selectedTable?.id"></span> Bill</h3>
                    <p class="text-xs font-semibold uppercase tracking-widest text-orange-200 mt-1">Advance Pre-orders</p>
                </div>
            </div>

            <!-- Body -->
            <div class="p-6 bg-white">
                <!-- Orders List -->
                <div class="space-y-3 max-h-[50vh] overflow-y-auto custom-scroll mb-6 pr-2">
                    <template x-for="(item, index) in (selectedTable?.orders || [])" :key="index">
                        <div class="flex justify-between items-center p-4 bg-white rounded-2xl border border-slate-100 shadow-sm transition-all group">
                            <div class="flex items-center gap-4">
                                <span class="w-10 h-10 flex items-center justify-center bg-orange-50 text-orange-600 text-sm font-black rounded-xl border border-orange-100" x-text="item.qty + 'x'"></span>
                                <div>
                                    <p class="text-sm font-black text-slate-800 tracking-tight" x-text="item.name"></p>
                                    <template x-if="item.addonName && item.addonName.toLowerCase() !== 'default'">
                                        <p class="text-[10px] text-orange-500 font-bold uppercase tracking-widest mt-0.5" x-text="item.addonName"></p>
                                    </template>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <p class="text-base font-black text-slate-700" x-text="formatCurrency(item.price * item.qty)"></p>
                            </div>
                        </div>
                    </template>

                    <template x-if="!selectedTable?.orders || selectedTable?.orders.length === 0">
                        <div class="text-center py-12 px-4 rounded-2xl bg-slate-50 border border-dashed border-slate-200">
                            <div class="w-20 h-20 bg-white shadow-sm rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                                <i class="fas fa-receipt text-slate-300 text-3xl"></i>
                            </div>
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest">No advance orders</p>
                        </div>
                    </template>
                </div>

                <!-- Bill Breakdowns -->
                <div class="bg-orange-50/50 rounded-2xl p-5 border border-orange-100 mb-6">
                    <div class="flex justify-between items-center pb-3 border-b border-dashed border-orange-200 mb-3">
                        <span class="text-xs font-bold text-orange-800 uppercase tracking-[0.2em]">Subtotal</span>
                        <span class="text-lg font-black text-slate-700 tracking-tight" x-text="formatCurrency(selectedTable?.bill || 0)"></span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-dashed border-orange-200 mb-3">
                        <span class="text-xs font-bold text-orange-800 uppercase tracking-[0.2em]">VAT (5%)</span>
                        <span class="text-sm font-bold text-slate-600 tracking-tight" x-text="formatCurrency((selectedTable?.bill || 0) * 0.05)"></span>
                    </div>
                    <div class="flex justify-between items-center pt-2">
                        <span class="text-xl font-black text-orange-950 uppercase tracking-wider">Total</span>
                        <span class="text-3xl font-black text-[#800000] tracking-tight" x-text="formatCurrency((selectedTable?.bill || 0) * 1.05)"></span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="grid grid-cols-2 gap-4">

                    <template x-if="selectedTable?.status === 'paid' || selectedTable?.isPaid">
                        <button @click="clearTable(selectedTable?.tableNumber || selectedTable?.id)"
                            class="py-4 bg-emerald-600 text-white rounded-2xl font-black text-xs uppercase tracking-wider shadow-lg shadow-emerald-600\30 active:scale-95 transition-all flex items-center justify-center gap-2">
                            <i class="fas fa-check-circle text-sm"></i> PAID
                        </button>
                    </template>
                    <template x-if="selectedTable?.status !== 'paid' && !selectedTable?.isPaid">
                    </template>

                    <template x-if="selectedTable?.status !== 'paid' && !selectedTable?.isPaid">
                        <button class="py-4 bg-blue-600 text-white rounded-2xl font-black text-[11px] uppercase tracking-wider shadow-sm cursor-not-allowed flex items-center justify-center gap-2 opacity-60">
                            <i class="fas fa-clock text-sm"></i> Pending
                        </button>
                    </template>

                    <button @click="showAdvanceOrderModal = false"
                        class="py-4 bg-slate-800 text-white rounded-2xl font-black text-[11px] uppercase tracking-wider shadow-sm active:scale-95 transition-all flex items-center justify-center gap-2">
                        Close
                    </button>

                </div>
            </div>
        </div>
    </div>
</template>


    <!-- Receipt Modal -->
    <template x-teleport="body">
        <div x-show="showCompleteOrderModal" x-cloak class="fixed inset-0 z-[1400] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
                <div class="p-8 font-mono text-sm leading-relaxed border-b-2 border-black max-h-[70vh] overflow-y-auto">
                    <!-- Restaurant Info -->
                    <div class="text-center mb-6 pb-4 border-b-2 border-dashed border-black">
                        <p class="text-sm font-black text-black">UNIVERSITY OF BATANGAS</p>
                        <p class="text-xs text-black">Hilltop Rd. Batangas City, 4250 Batangas</p>
                    </div>

                    <!-- Table Number -->
                    <div class="border-4 border-black rounded-lg p-4 mb-6 text-center">
                        <p class="text-2xl font-black tracking-widest text-black" x-text="'TABLE ' + (selectedTable?.tableNumber || selectedTable?.id || 'WALK-IN')"></p>
                    </div>

                    <!-- Order Info -->
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

                    <!-- Items Section -->
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

                    <!-- Total -->
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

                    <!-- Footer Message -->
                    <div class="text-center text-xs">
                        <p class="font-bold mb-1 text-black">THANK YOU!</p>
                        <p class="text-black">Please come again.</p>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="bg-slate-50 p-4 flex gap-3 border-t border-slate-200">
                    <button @click="confirmPrint(selectedTable?.tableNumber || selectedTable?.id)" class="w-full py-3 maroon-gradient text-white font-semibold rounded-lg text-sm shadow-md transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-check"></i> Confirm Print
                    </button>
                </div>
            </div>
        </div>
    </template>

    </div>