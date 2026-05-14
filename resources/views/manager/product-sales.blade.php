<div class="space-y-8">
    <div class="mb-8">
        <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tighter leading-none">Product Sales</h1>
        <p class="text-xs text-slate-500 mt-2 font-bold uppercase tracking-[0.2em] flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
            Detailed Sales Performance
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
        <div class="clay-card border-t-4 border-t-[#800000] p-6 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:scale-110 transition-transform">
                <i class="fas fa-coins text-5xl text-[#800000]"></i>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Revenue</p>
            <p class="text-3xl font-black text-[#800000] mt-1" x-text="formatCurrency(productSalesMetrics.totalRevenue)"></p>
            <p class="text-[10px] font-bold text-emerald-600 mt-2"><i class="fas fa-caret-up"></i> Real-time update</p>
        </div>

        <div class="clay-card border-t-4 border-t-emerald-500 p-6 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:scale-110 transition-transform">
                <i class="fas fa-box text-5xl text-emerald-600"></i>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Products Sold</p>
            <p class="text-3xl font-black text-emerald-600 mt-1" x-text="productSalesMetrics.totalItemsSold"></p>
            <p class="text-[10px] font-bold text-slate-400 mt-2">Units sold today</p>
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