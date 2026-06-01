<div class="space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-4xl font-black text-slate-800 uppercase tracking-tighter leading-none">Reservation Management</h1>
            <p class="text-xs text-slate-500 mt-2 font-bold uppercase tracking-[0.2em] flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                Real-time Booking & Schedule Status
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <template x-for="res in reservations" :key="res.id">
            <div class="clay-card p-6 border border-slate-100 shadow-sm hover:shadow-lg transition-all">
                <div class="flex flex-col sm:flex-row sm:justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-black text-slate-900 uppercase tracking-tight" x-text="res.name"></h2>
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mt-2" x-text="res.type ? res.type.replace('-', ' ') : 'Reservation'"></p>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center gap-2 px-3 py-2 rounded-full text-[10px] font-black uppercase tracking-[0.2em]"
                              :class="res.status === 'confirmed' ? 'bg-emerald-100 text-emerald-700' : 'bg-yellow-100 text-yellow-700'"
                              x-text="res.status === 'confirmed' ? 'CONFIRMED' : 'PENDING'"></span>
                    </div>
                </div>

                <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="bg-slate-50 rounded-3xl p-4 border border-slate-100">
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400">Contact</p>
                        <p class="text-sm font-bold text-slate-800 mt-2" x-text="res.email"></p>
                        <p class="text-sm font-bold text-slate-800 mt-1" x-text="res.phone"></p>
                    </div>
                    <div class="bg-slate-50 rounded-3xl p-4 border border-slate-100">
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400">Guests</p>
                        <p class="text-2xl font-black text-[#800000] mt-2" x-text="res.guests + ' pax'"></p>
                    </div>
                </div>

                <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-slate-50 rounded-3xl p-4 border border-slate-100">
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400">Date</p>
                        <p class="text-sm font-bold text-slate-800 mt-2" x-text="res.date"></p>
                    </div>
                    <div class="bg-slate-50 rounded-3xl p-4 border border-slate-100">
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400">Time</p>
                        <p class="text-sm font-bold text-[#800000] mt-2" x-text="formatTime(res.time)"></p>
                    </div>
                </div>

                <template x-if="res.requests">
                    <div class="mt-5 bg-orange-50 rounded-3xl p-4 border border-orange-100">
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-orange-600">Customer Notes</p>
                        <p class="text-xs text-orange-900 mt-2 leading-relaxed" x-text="res.requests"></p>
                    </div>
                </template>

                <div class="mt-6 flex flex-wrap gap-3">
                    <template x-if="!res.status || res.status === 'pending'">
                        <button @click="updateReservationStatus(res.id, 'confirmed')"
                                class="flex-1 min-w-[130px] py-3 bg-[#800000] text-white rounded-2xl font-black uppercase text-xs tracking-[0.2em] hover:bg-[#660000] transition-all">
                            Confirm
                        </button>
                    </template>
                    <button @click="deleteReservation(res.id)"
                            class="flex-1 min-w-[130px] py-3 bg-red-50 text-red-600 rounded-2xl font-black uppercase text-xs tracking-[0.2em] hover:bg-red-600 hover:text-white transition-all">
                        Delete
                    </button>
                </div>
            </div>
        </template>

        <div x-show="reservations.length === 0" x-cloak class="col-span-full py-12 text-center bg-white border border-slate-200 rounded-3xl shadow-sm">
            <i class="fas fa-calendar-check text-slate-200 text-5xl mb-4"></i>
            <h3 class="text-lg font-black text-slate-700 uppercase">No reservations available</h3>
            <p class="text-slate-400 text-xs mt-2">Bookings created by the waiter will appear here.</p>
        </div>
    </div>
</div>