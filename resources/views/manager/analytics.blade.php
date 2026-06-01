<div class="space-y-8" x-effect="() => $watch('orderHistory', () => {}, { deep: true })">
    <div class="mb-8">
        <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tighter leading-none">Performance Analytics</h1>
        <p class="text-xs text-slate-500 mt-2 font-bold uppercase tracking-[0.2em] flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            Live Business Growth Monitor
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <!-- Total Sales Card -->
        <div class="clay-card p-6 shadow-md hover:shadow-lg transition-all border border-slate-100">
            <div class="flex items-start gap-4">
                <div class="w-16 h-16 rounded-full bg-red-50 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-wallet text-2xl text-[#800000]"></i>
                </div>
                <div class="flex-1">
                    <p class="text-[11px] font-black text-slate-500 uppercase tracking-widest mb-1">Total Sales</p>
                    <p class="text-3xl font-black text-[#800000]" x-text="formatCurrency(salesSummary.total)"></p>
                    <p class="text-[10px] font-bold text-emerald-600 mt-1"><i class="fas fa-caret-up"></i> Incl. 5% VAT</p>
                </div>
            </div>
        </div>

        <!-- Orders Completed Card -->
        <div class="clay-card p-6 shadow-md hover:shadow-lg transition-all border border-slate-100">
            <div class="flex items-start gap-4">
                <div class="w-16 h-16 rounded-full bg-emerald-50 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-shopping-bag text-2xl text-emerald-600"></i>
                </div>
                <div class="flex-1">
                    <p class="text-[11px] font-black text-slate-500 uppercase tracking-widest mb-1">Orders Completed</p>
                    <p class="text-3xl font-black text-emerald-600" x-text="metrics.totalOrders"></p>
                    <p class="text-[10px] font-bold text-slate-400 mt-1">Total processed today</p>
                </div>
            </div>
        </div>

        <!-- Average Order Value Card -->
        <div class="clay-card p-6 shadow-md hover:shadow-lg transition-all border border-slate-100">
            <div class="flex items-start gap-4">
                <div class="w-16 h-16 rounded-full bg-blue-50 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-chart-line text-2xl text-blue-600"></i>
                </div>
                <div class="flex-1">
                    <p class="text-[11px] font-black text-slate-500 uppercase tracking-widest mb-1">Average Order Value</p>
                    <p class="text-3xl font-black text-blue-600" x-text="formatCurrency(metrics.avgOrder)"></p>
                    <p class="text-[10px] font-bold text-slate-400 mt-1">Avg. spend per table</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="clay-card overflow-hidden">
            <div class="p-5 border-b border-slate-200 flex justify-between items-center bg-slate-50/50">
                <h3 class="text-xs font-black text-slate-700 uppercase tracking-widest">Top Selling Products</h3>
                <i class="fas fa-crown text-amber-400"></i>
            </div>
            <div class="p-5 space-y-3">
                <template x-for="(item, index) in metrics.topItems" :key="index">
                    <div class="flex items-center justify-between p-4 bg-white border border-slate-100 rounded-2xl hover:bg-slate-50 hover:shadow-md transition-all">
                        <div class="flex items-center gap-4">
                            <span class="w-8 h-8 rounded-lg bg-slate-900 text-white text-xs flex items-center justify-center font-black" x-text="index + 1"></span>
                            <img :src="item.img" :alt="item.name" class="w-12 h-12 rounded-lg object-cover border border-slate-200">
                            <span class="text-sm font-black text-slate-700 uppercase tracking-tight" x-text="item.name"></span>
                        </div>
                        <span class="text-xs font-black text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-100" x-text="item.qty + ' Sold'"></span>
                    </div>
                </template>
            </div>
        </div>

        <div class="clay-card overflow-hidden">
            <div class="p-5 border-b border-slate-200 flex justify-between items-center bg-slate-50/50">
                <h3 class="text-xs font-black text-slate-700 uppercase tracking-widest">Recent Transactions</h3>
                <i class="fas fa-receipt text-slate-400"></i>
            </div>
            <div class="overflow-y-auto max-h-[420px] custom-scroll divide-y divide-slate-100">
                <template x-for="(history, index) in orderHistory.slice(0, 10)" :key="index">
                    <div class="flex justify-between items-center p-5 hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                                <i class="fas fa-check text-xs font-black"></i>
                            </div>
                            <div>
                                <p class="text-xs font-black text-slate-800 uppercase" x-text="history.orderId"></p>
                                <template x-if="history.bookingType === 'advance-order'">
                                    <span class="inline-block text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded bg-amber-100 text-amber-700 mt-1">
                                        <i class="fas fa-clock mr-1"></i> Advance Order
                                    </span>
                                </template>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-black text-[#800000]" x-text="formatCurrency(history.totalAmount)"></p>
                            <span class="text-[8px] font-black uppercase tracking-tighter px-2 py-0.5 rounded bg-slate-100 text-slate-500" x-text="'Table ' + history.tableId"></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>