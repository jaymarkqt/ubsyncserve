<div class="space-y-8">
    <div class="mb-8">
        <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tighter leading-none">Product Sales</h1>
        <p class="text-xs text-slate-500 mt-2 font-bold uppercase tracking-[0.2em] flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
            Detailed Sales Performance
        </p>
    </div>

    <!-- Date Filter -->
    <div class="mb-6 flex gap-3">
        <button @click="salesDateFilter = 'today'"
            :class="salesDateFilter === 'today' ? 'bg-[#800000] text-white' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50'"
            class="px-4 py-2 rounded-lg font-bold text-xs uppercase tracking-widest transition-all">
            Today
        </button>
        <button @click="salesDateFilter = 'yesterday'"
            :class="salesDateFilter === 'yesterday' ? 'bg-[#800000] text-white' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50'"
            class="px-4 py-2 rounded-lg font-bold text-xs uppercase tracking-widest transition-all">
            Yesterday
        </button>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Total Revenue Card -->
        <div class="clay-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-black text-slate-500 uppercase tracking-widest mb-1">Total Revenue Income</p>
                    <h3 class="text-3xl font-black text-[#800000]" x-text="formatCurrency(productSalesMetrics.totalRevenue)"></h3>
                    <p class="text-xs text-slate-400 mt-2">from all transactions</p>
                </div>
                <div class="w-14 h-14 rounded-full bg-red-50 flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-2xl text-[#800000]"></i>
                </div>
            </div>
        </div>

        <!-- Total Products Sold Card -->
        <div class="clay-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-black text-slate-500 uppercase tracking-widest mb-1">Total Products Sold</p>
                    <h3 class="text-3xl font-black text-[#800000]" x-text="productSalesMetrics.totalItemsSold + ' units'"></h3>
                    <p class="text-xs text-slate-400 mt-2">products ordered today</p>
                </div>
                <div class="w-14 h-14 rounded-full bg-red-50 flex items-center justify-center">
                    <i class="fas fa-box-open text-2xl text-[#800000]"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="clay-card overflow-hidden">
        <div class="p-5 border-b flex justify-between items-center bg-slate-50/50">
            <h3 class="text-xs font-black text-slate-700 uppercase tracking-widest">Product Sales Breakdown</h3>
            <i class="fas fa-box text-slate-400"></i>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-slate-600 uppercase tracking-wider">Product</th>
                        <th class="px-6 py-4 text-center text-[10px] font-black text-slate-600 uppercase tracking-wider">Qty Sold</th>
                        <th class="px-6 py-4 text-right text-[10px] font-black text-slate-600 uppercase tracking-wider">Unit Cost</th>
                        <th class="px-6 py-4 text-right text-[10px] font-black text-slate-600 uppercase tracking-wider">Total Revenue</th>
                        <th class="px-6 py-4 text-center text-[10px] font-black text-slate-600 uppercase tracking-wider">% of Sales</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <template x-for="product in filteredProductSales" :key="product.id">
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <img :src="product.imgPath" alt="" class="w-12 h-12 rounded-lg object-cover bg-slate-100 border border-slate-200">
                                    <div>
                                        <p class="text-sm font-black text-slate-800 uppercase" x-text="product.name"></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span class="text-sm font-bold text-slate-700" x-text="product.qtySold"></span>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <span class="text-sm font-bold text-slate-700" x-text="formatCurrency(product.unitCost)"></span>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <span class="text-sm font-black text-[#800000]" x-text="formatCurrency(product.totalRevenue)"></span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center justify-center gap-2">
                                    <div class="w-full bg-slate-100 rounded-full h-2 max-w-xs">
                                        <div class="bg-[#800000] h-2 rounded-full transition-all" :style="`width: ${product.percentageOfSales}%`"></div>
                                    </div>
                                    <span class="text-xs font-black text-slate-700 min-w-fit" x-text="product.percentageOfSales.toFixed(1) + '%'"></span>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template x-if="filteredProductSales.length === 0">
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-400 font-bold">
                                No sales data available
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>