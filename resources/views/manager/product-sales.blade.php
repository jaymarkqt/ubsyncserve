<div class="space-y-8" x-effect="() => $watch('orderHistory', () => {}, { deep: true })">
    <div class="mb-8">
        <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tighter leading-none">Product Sales</h1>
        <p class="text-xs text-slate-500 mt-2 font-bold uppercase tracking-[0.2em] flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
            Detailed Sales Performance
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
        <!-- Total Products Sold Card -->
        <div class="clay-card p-6 shadow-md hover:shadow-lg transition-all border border-slate-100">
            <div class="flex items-start gap-4">
                <div class="w-16 h-16 rounded-full bg-blue-50 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-box text-2xl text-blue-600"></i>
                </div>
                <div class="flex-1">
                    <p class="text-[11px] font-black text-slate-500 uppercase tracking-widest mb-1">Total Products Sold</p>
                    <p class="text-3xl font-black text-blue-600" x-text="productSalesMetrics.totalItemsSold"></p>
                    <p class="text-[10px] font-bold text-slate-400 mt-1">Total quantity</p>
                </div>
            </div>
        </div>

        <!-- Total Revenue Card -->
        <div class="clay-card p-6 shadow-md hover:shadow-lg transition-all border border-slate-100">
            <div class="flex items-start gap-4">
                <div class="w-16 h-16 rounded-full bg-red-50 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-coins text-2xl text-[#800000]"></i>
                </div>
                <div class="flex-1">
                    <p class="text-[11px] font-black text-slate-500 uppercase tracking-widest mb-1">Total Revenue</p>
                    <p class="text-3xl font-black text-[#800000]" x-text="formatCurrency(productSalesMetrics.totalRevenue + (productSalesMetrics.totalRevenue * 0.05))"></p>
                    <p class="text-[10px] font-bold text-emerald-600 mt-1"><i class="fas fa-caret-up"></i> Incl. 5% VAT</p>
                </div>
            </div>
        </div>
    </div>

    <div class="clay-card overflow-hidden">
        <div class="p-5 border-b border-slate-200 flex justify-between items-center bg-slate-50/50">
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
                        <th class="px-6 py-4 text-right text-[10px] font-black text-slate-600 uppercase tracking-wider">Subtotal</th>
                        <th class="px-6 py-4 text-center text-[10px] font-black text-slate-600 uppercase tracking-wider">% of Sales</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <template x-for="product in filteredProductSales" :key="product.id">
                        <tr class="hover:bg-slate-50/50 transition-colors">
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