<div class="space-y-8">

    <style>
        .maroon-gradient { background: linear-gradient(135deg, #800000 0%, #a52a2a 100%); }
    </style>

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
    <div @click.stop="handleTableClick(table)"
          class="w-full max-w-[220px] sm:max-w-[240px] min-h-[170px] sm:min-h-[190px] lg:min-h-[210px] transition-all flex flex-col items-center justify-center space-y-2 rounded-[1.25rem] border-2 shadow-sm relative group"
          :class="{
            'bg-[#ccfad8] border-[#4ade80] cursor-not-allowed opacity-80': table.status === 'available',
            'bg-[#fed7aa] border-[#ea580c] cursor-pointer hover:shadow-xl hover:-translate-y-1': table.status === 'reserved-advance',
            'bg-[#fef08a] border-[#fcd34d] cursor-pointer hover:shadow-xl hover:-translate-y-1': table.status === 'reserved-booking',
            'bg-[#ffdada] border-[#f87171] cursor-pointer hover:shadow-xl hover:-translate-y-1': table.status === 'occupied'
          }">

        <div class="text-4xl font-black text-[#1e293b] tracking-tight"
             x-text="table.tableNumber"></div>

        <p class="text-[11px] font-extrabold uppercase tracking-widest"
           :class="table.status === 'available' ? 'text-emerald-700' : (table.status === 'reserved-advance' ? 'text-orange-700' : (table.status === 'reserved-booking' ? 'text-yellow-700' : 'text-[#cc0000]'))"
           x-text="table.status === 'reserved-advance' ? 'advance order' : (table.status === 'reserved-booking' ? 'table reservation' : (table.status === 'available' ? 'available' : 'occupied'))"></p>

        <template x-if="table.status !== 'available'">
            <div class="text-center pt-1 w-full">
                <p class="text-sm font-bold text-[#1e293b]"><span x-text="table.guests ?? ((table.adults || 0) + (table.children || 0))"></span> guests</p>
            </div>
        </template>
    </div>
</template>
</div>

</div>

