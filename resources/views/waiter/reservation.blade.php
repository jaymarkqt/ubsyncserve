<div class="space-y-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 text-left">
        <div>
            <h2 class="text-2xl md:text-3xl font-black text-slate-800 uppercase tracking-tighter">Reservation Hub</h2>
            <p class="flex text-sm text-slate-500 mt-2 font-medium uppercase tracking-wide">Active Bookings</p>
        </div>
        <div class="flex items-center gap-3">
            <button @click="clearAllReservations()" 
                    class="bg-red-50 text-red-600 text-[10px] font-black uppercase px-4 py-3 rounded-xl border border-red-100 hover:bg-red-600 hover:text-white transition-all shadow-sm">
                <i class="fas fa-trash-alt mr-2"></i> Clear All
            </button>
            <div class="bg-slate-800 text-white px-4 py-3 rounded-xl font-black text-[10px] uppercase tracking-wider shadow-md">
                Total: <span x-text="reservations.length"></span>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            <template x-for="res in reservations" :key="res.id">
                <div class="flex flex-col bg-white border border-slate-100 rounded-2xl shadow-sm transition-all overflow-hidden" 
                     :class="res.status === 'confirmed' ? 'border-t-4 border-t-green-500' : 'border-t-4 border-t-yellow-500'">
                    <div class="p-4 flex justify-between items-start gap-4">
                        <div class="flex-1 text-left">
                            <span class="text-lg font-black text-slate-800 leading-tight block break-words" x-text="res.name"></span>
                            <div class="mt-3 flex flex-wrap gap-2 text-[10px] uppercase tracking-[0.3em] font-black text-slate-500">
                                <span x-text="res.type ? res.type.replace('-', ' ') : 'Table Reservation'"></span>
                                <span x-show="res.table">| Table <span x-text="res.table"></span></span>
                            </div>
                            <div class="mt-2 text-[11px] text-slate-400">
                                Booked: <span x-text="res.createdAt ? new Date(res.createdAt).toLocaleString() : 'N/A'"></span>
                            </div>
                        </div>
                        <div class="shrink-0">
                            <span class="text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-tighter inline-block shadow-sm" 
                                  :class="res.status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'" 
                                  x-text="res.status === 'confirmed' ? 'CONFIRMED' : 'PENDING'">
                            </span>
                        </div>
                    </div>

                    <div class="px-4 pb-4 flex-1 space-y-2 text-left">
                        <div class="text-sm flex justify-between items-center bg-gray-50/70 p-2.5 rounded-xl overflow-hidden">
                            <span class="text-slate-500 font-medium italic shrink-0">Email:</span>
                            <span class="font-black text-slate-900 truncate ml-2 text-right" :title="res.email" x-text="res.email || 'N/A'"></span>
                        </div>
                        <div class="text-sm flex justify-between items-center bg-gray-50/70 p-2.5 rounded-xl">
                            <span class="text-slate-500 font-medium italic">Guests:</span>
                            <span class="font-black text-slate-900" x-text="res.guests + ' Pax'"></span>
                        </div>

                        <div class="mt-3 pt-3 border-t border-slate-50">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Appointment Schedule</p>
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-2">
                                    <i class="far fa-calendar-alt text-slate-400 text-xs"></i>
                                    <span class="text-sm font-black text-slate-700" x-text="res.date"></span>
                                </div>
                                <div class="flex items-center gap-2 bg-red-50 px-2 py-1 rounded-lg">
                                    <i class="far fa-clock text-red-400 text-xs"></i>
                                    <span class="text-sm font-black text-red-700" x-text="formatTime(res.time)"></span>
                                </div>
                            </div>
                        </div>

                        <template x-if="res.requests">
                            <div class="mt-2 p-3 bg-orange-50/50 rounded-xl border border-orange-100/30">
                                <p class="text-[9px] font-bold text-orange-600 uppercase mb-1">Customer Notes:</p>
                                <p class="text-xs text-orange-900 break-words leading-relaxed font-medium" x-text="res.requests"></p>
                            </div>
                        </template>
                    </div>

                    <div class="p-4 pt-0">
                        <div x-show="res.status !== 'confirmed'" class="flex gap-2">
                            <button @click="deleteReservation(res.id)" 
                                    class="flex-1 py-3 bg-gray-50 text-slate-400 hover:text-red-600 font-black text-[10px] uppercase rounded-xl transition-all">
                                Cancel
                            </button>
                            <button @click="confirmReservation(res.id)" 
                                    class="flex-[2] py-3 bg-green-600 text-white hover:bg-green-700 font-black text-[10px] uppercase rounded-xl shadow-md transition-all">
                                Confirm
                            </button>
                        </div>

                        <div x-show="res.status === 'confirmed'" class="flex gap-2">
                            <div class="flex-1 py-3 bg-slate-100 text-slate-400 font-black text-[10px] uppercase rounded-xl flex items-center justify-center gap-2">
                                <i class="fas fa-check-circle text-green-500"></i> Settled
                            </div>
                            <button @click="deleteReservation(res.id)" 
                                    class="px-4 py-3 bg-red-50 text-red-500 hover:bg-red-500 hover:text-white rounded-xl transition-all">
                                <i class="fas fa-trash-alt text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <div x-show="reservations.length === 0" class="py-12 text-center">
            <i class="fas fa-calendar-check text-slate-200 text-4xl mb-4"></i>
            <h3 class="text-lg font-black text-slate-700 uppercase">No Active Reservations</h3>
            <p class="text-slate-400 text-xs mt-1">Updates will appear here when customers book.</p>
        </div>
    </div>
</div>
