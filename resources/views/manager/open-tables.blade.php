<div x-data="managerDashboard()" class="space-y-8">

    <div>
        <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tighter leading-none">
            Open Tables Management
        </h1>
        <p class="text-xs text-slate-500 mt-2 font-bold uppercase tracking-[0.2em] flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
            Real-time Table Occupancy Status
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">



    <div class="clay-card border-t-4 border-t-emerald-500 p-6 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:scale-110 transition-transform">
                <i class="fas fa-door-open text-5xl text-emerald-600"></i>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Available Tables</p>
            <p class="text-3xl font-black text-emerald-600 mt-1" x-text="tablesMetrics.availableTables"></p>
            <p class="text-[10px] font-bold text-slate-400 mt-2">Ready for seating</p>
        </div>


        
        <div class="clay-card border-t-4 border-t-red-800 p-6 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:scale-110 transition-transform">
                <i class="fas fa-utensils text-5xl text-red-800"></i>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Occupied Tables</p>
            <p class="text-3xl font-black text-red-800 mt-1" x-text="tablesMetrics.occupiedTables"></p>
            <p class="text-[10px] font-bold text-slate-400 mt-2">Currently in use</p>
        </div>

        
        <div class="clay-card border-t-4 border-t-blue-500 p-6 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:scale-110 transition-transform">
                <i class="fas fa-users text-5xl text-blue-600"></i>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Guests</p>
            <p class="text-3xl font-black text-slate-800 mt-1" x-text="tablesMetrics.totalGuests"></p>
            <p class="text-[10px] font-bold text-slate-400 mt-2">In restaurant</p>
        </div>
    </div>

   <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
  <template x-for="(table, index) in openTables" :key="index">
    <div @click="table.status !== 'available' ? handleTableClick(table) : null"
         class="w-full max-w-[170px] h-[170px] mx-auto transition-all flex flex-col items-center justify-center space-y-2 rounded-xl border-2 shadow-sm relative group"
         :class="{
            'bg-[#ccfad8] border-[#4ade80] cursor-not-allowed opacity-80': table.status === 'available',
            'bg-[#fef3c7] border-[#f59e0b] cursor-pointer hover:shadow-xl hover:-translate-y-1': table.status === 'reserved-advance',
            'bg-[#ffedd5] border-[#fb923c] cursor-pointer hover:shadow-xl hover:-translate-y-1': table.status === 'reserved-booking',
            'bg-[#ffdada] border-[#f87171] cursor-pointer hover:shadow-xl hover:-translate-y-1': table.status === 'occupied'
         }">

        <div class="text-4xl font-black text-[#1e293b] tracking-tight"
             x-text="table.tableNumber"></div>

        <p class="text-[11px] font-extrabold uppercase tracking-widest"
           :class="table.status === 'available' ? 'text-emerald-700' : (table.status === 'reserved-advance' ? 'text-amber-700' : (table.status === 'reserved-booking' ? 'text-orange-700' : 'text-[#cc0000]'))"
           x-text="table.status.replace('-', ' ')"></p>

        <template x-if="table.status !== 'available'">
            <div class="text-center pt-1 w-full">
                <p class="text-sm font-bold text-[#1e293b]">
                    <span x-text="table.guests"></span> guests
                </p>
                <template x-if="table.status === 'occupied'">
                    <p class="text-[10px] text-[#cc0000] font-bold mt-1 tracking-widest"
                       x-text="table.duration"></p>
                </template>
            </div>
        </template>
    </div>
</template>
</div>

    <template x-teleport="body">
        <div x-show="showOrderModal" 
             x-cloak 
             class="fixed inset-0 z-[1200] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
             
            <div class="clay-card w-full max-w-md overflow-hidden bg-white rounded-3xl shadow-2xl">
                
                <div class="bg-[#800000] p-6 text-white flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-black uppercase tracking-tighter">
                            Table <span x-text="selectedTable?.tableNumber || selectedTable?.id"></span> Bill
                        </h3>
                        <p class="text-[10px] font-bold uppercase tracking-widest opacity-80">
                            Current Active Session
                        </p>
                    </div>
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
                                    
                                    <button @click="openVoidModal(index)" class="w-8 h-8 bg-white text-red-500 rounded-full border border-red-100 hover:bg-red-500 hover:text-white transition-all shadow-sm">
                                        <i class="fas fa-trash text-[10px]"></i>
                                    </button>
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
                        <div class="flex justify-between items-center px-1">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Running Total</span>
                            <span class="text-3xl font-black text-[#800000] tracking-tighter" x-text="formatCurrency(selectedTable?.bill || 0)"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <button @click="clearTable(selectedTable?.tableNumber || selectedTable?.id)" class="py-4 bg-emerald-600 text-white rounded-2xl font-black text-[11px] uppercase shadow-lg shadow-emerald-900/10 hover:bg-emerald-700 transition-all flex items-center justify-center gap-2">
                            <i class="fas fa-check-circle text-xs"></i> Finish and Clear 
                        </button>
                        
                        <button @click="showOrderModal = false" class="py-4 bg-[#800000] text-white rounded-2xl font-black text-[11px] uppercase shadow-lg shadow-red-900/10 hover:bg-red-900 transition-all flex items-center justify-center gap-2">
                            <i class="fas fa-times text-xs"></i> Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>

  <template x-teleport="body">
       <div x-show="showVoidModal" x-cloak class="fixed inset-0 z-[1300] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
             
            <div class="clay-card w-full max-w-sm overflow-hidden bg-white rounded-3xl shadow-2xl">
                <div class="bg-red-600 p-8 text-white text-center">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shield-alt text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-black uppercase tracking-tighter">Security Check</h3>
                    <p class="text-[10px] font-bold uppercase tracking-widest opacity-80 mt-1">Manager Pin Required</p>
                </div>
                <div class="p-8 space-y-6">
                    <input type="password" x-model="voidCodeInput" class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl p-5 text-center font-black text-2xl tracking-[0.5em] focus:border-red-600 focus:bg-white outline-none transition-all" placeholder="****">
                    <div class="flex gap-3">
                        <button @click="cancelVoid()" class="flex-1 py-4 bg-slate-100 text-slate-500 font-black text-[11px] uppercase rounded-2xl hover:bg-slate-200 transition-all">Cancel</button>
                        <button @click="confirmVoidOrder()" class="flex-1 py-4 bg-red-600 text-white font-black text-[11px] uppercase rounded-2xl shadow-lg shadow-red-900/20 hover:bg-red-700 transition-all">Confirm</button>
                    </div>
                </div>
            </div>
            
        </div>
    </template>

    </div>

    
</div>