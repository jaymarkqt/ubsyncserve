<div class="space-y-8">

    <div>
        <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tighter leading-none">
            Reservation Management
        </h1>
        <p class="text-xs text-slate-500 mt-2 font-bold uppercase tracking-[0.2em] flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
            Real-time Booking & Schedule Status
        </p>
    </div>

    <div class="clay-card overflow-hidden">
        <div class="p-5 border-b bg-slate-50/50">
            <h3 class="text-xs font-black text-slate-700 uppercase tracking-widest">Booking List</h3>
        </div>
        
        <div class="overflow-x-auto">
         <table class="w-full text-sm">
    <thead>
        <tr class="border-b-2 border-slate-200 bg-slate-50/50 text-left">
            <th class="px-4 py-4 font-black text-slate-700 uppercase text-xs">Customer Info</th>
            <th class="px-4 py-4 font-black text-slate-700 uppercase text-xs">Schedule</th>
            <th class="px-4 py-4 font-black text-slate-700 uppercase text-xs">Time</th>
            <th class="px-4 py-4 text-center font-black text-slate-700 uppercase text-xs">Guests</th>
            <th class="px-4 py-4 text-center font-black text-slate-700 uppercase text-xs">Status</th>
            <th class="px-4 py-4 text-center font-black text-slate-700 uppercase text-xs">Actions</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-slate-100">
        <tr x-show="reservations.length === 0" x-cloak>
            <td colspan="6" class="px-4 py-8 text-center text-slate-400">
                <i class="fas fa-calendar-times text-3xl mb-2 opacity-50"></i>
                <p class="font-bold text-sm">No reservations found.</p>
                <p class="text-xs">Bookings created by the waiter will appear here.</p>
            </td>
        </tr>

        <template x-for="res in reservations" :key="res.id">
            <tr class="hover:bg-slate-50 transition-colors">
                <td class="px-4 py-4">
                    <div class="font-bold text-slate-800" x-text="res.name"></div>
                    <div class="text-xs text-slate-500 truncate max-w-[150px]" x-text="res.email" :title="res.email"></div>
                    <div class="text-xs text-slate-500 truncate max-w-[150px]" x-text="res.phone" :title="res.phone"></div>
                    <div class="mt-2 text-[10px] text-slate-400 uppercase tracking-[0.2em] font-black">
                        <span x-text="res.type ? res.type.replace('-', ' ') : 'Reservation'"></span>
                  
                    </div>
                    <div class="mt-1 text-[10px] text-black font-medium" x-text="res.createdAt ? 'Booked: ' + new Date(res.createdAt).toLocaleDateString() + ', ' + new Date(res.createdAt).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' }) : ''"></div>
                </td>
                
                <td class="px-4 py-4">
                    <div class="font-bold text-slate-700" x-text="res.date"></div>
                </td>

                <td class="px-4 py-4">
                    <div class="font-bold text-[#800000]" x-text="formatTime(res.time)"></div>
                </td>

                <td class="px-4 py-4 text-center">
                    <span class="bg-slate-200 text-slate-700 px-3 py-1 rounded-full text-xs font-bold" x-text="res.guests + ' Pax'"></span>
                </td>

             <td class="px-4 py-4 text-center">
    <span x-show="!res.status || res.status === 'pending'" class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700">Pending</span>
    
    <span x-show="res.status === 'confirmed'" class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">Confirmed</span>
</td>

<td class="px-4 py-4 text-center">
    <div class="flex items-center justify-center gap-2">
        <template x-if="!res.status || res.status === 'pending'">
            <button @click="updateReservationStatus(res.id, 'confirmed')" class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Confirm Booking">
                <i class="fas fa-check"></i>
            </button>
        </template>

        <button @click="deleteReservation(res.id)" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Remove Record">
            <i class="fas fa-trash"></i>
        </button>
    </div>
</td>

            </tr>
        </template>
    </tbody>
</table>
        </div>
    </div>
</div>