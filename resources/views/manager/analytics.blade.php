<div class="space-y-8">
    <div class="mb-8">
        <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tighter leading-none">Performance Analytics</h1>
        <p class="text-xs text-slate-500 mt-2 font-bold uppercase tracking-[0.2em] flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            Live Business Growth Monitor
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <div class="clay-card border-t-4 border-t-[#800000] p-6 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:scale-110 transition-transform">
                <i class="fas fa-wallet text-5xl text-[#800000]"></i>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Sales</p>
            <p class="text-3xl font-black text-[#800000] mt-1" x-text="formatCurrency(salesSummary.total)"></p>
            <p class="text-[10px] font-bold text-emerald-600 mt-2"><i class="fas fa-caret-up"></i> Real-time update</p>
        </div>

        <div class="clay-card border-t-4 border-t-emerald-500 p-6 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:scale-110 transition-transform">
                <i class="fas fa-shopping-bag text-5xl text-emerald-600"></i>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Orders Completed</p>
            <p class="text-3xl font-black text-emerald-600 mt-1" x-text="metrics.totalOrders"></p>
            <p class="text-[10px] font-bold text-slate-400 mt-2">Total processed today</p>
        </div>

        <div class="clay-card border-t-4 border-t-blue-500 p-6 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:scale-110 transition-transform">
                <i class="fas fa-chart-line text-5xl text-blue-600"></i>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Average Order Value</p>
            <p class="text-3xl font-black text-blue-600 mt-1" x-text="formatCurrency(metrics.avgOrder)"></p>
            <p class="text-[10px] font-bold text-slate-400 mt-2">Avg. spend per table</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="clay-card overflow-hidden">
            <div class="p-5 border-b flex justify-between items-center bg-slate-50/50">
                <h3 class="text-xs font-black text-slate-700 uppercase tracking-widest">Top Selling Products</h3>
                <i class="fas fa-crown text-amber-400"></i>
            </div>
            <div class="p-5 space-y-3">
                <template x-for="(item, index) in metrics.topItems" :key="index">
                    <div class="flex items-center justify-between p-4 bg-white border border-slate-100 rounded-2xl hover:shadow-md transition-all">
                        <div class="flex items-center gap-4">
                            <span class="w-8 h-8 rounded-lg bg-slate-900 text-white text-xs flex items-center justify-center font-black" x-text="index + 1"></span>
                            <span class="text-sm font-black text-slate-700 uppercase tracking-tight" x-text="item.name"></span>
                        </div>
                        <span class="text-xs font-black text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-100" x-text="item.qty + ' Sold'"></span>
                    </div>
                </template>
            </div>
        </div>

        <div class="clay-card overflow-hidden">
            <div class="p-5 border-b flex justify-between items-center bg-slate-50/50">
                <h3 class="text-xs font-black text-slate-700 uppercase tracking-widest">Recent Transactions</h3>
                <i class="fas fa-receipt text-slate-400"></i>
            </div>
            <div class="overflow-y-auto max-h-[420px] custom-scroll divide-y divide-slate-50">
                <template x-for="(history, index) in orderHistory.slice(0, 10)" :key="index">
                    <div class="flex justify-between items-center p-5 hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500">
                                <i class="fas fa-check text-xs"></i>
                            </div>
                            <div>
                                <p class="text-xs font-black text-slate-800 uppercase" x-text="history.orderId"></p>
                                <p class="text-[9px] text-slate-400 font-bold uppercase" x-text="history.timestamp"></p>
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