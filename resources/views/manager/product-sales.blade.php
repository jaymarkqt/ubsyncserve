<div class="space-y-8" x-data="productSalesDashboard()" x-init="init()">
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tighter leading-none">
                Product Sales
            </h1>
            <p class="text-xs text-slate-500 mt-2 font-bold uppercase tracking-[0.2em] flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Complete Transaction History
            </p>
        </div>

        <div class="flex gap-2 flex-wrap">
            <button @click="filterType = 'all'"
                :class="filterType === 'all' ? 'bg-[#800000] text-white shadow-md' : 'bg-white border border-slate-200 text-slate-600 hover:border-slate-300'"
                class="px-4 py-2 rounded-lg font-bold text-xs uppercase tracking-wide transition-all">
                All Transactions
            </button>
            <button @click="filterType = 'products'"
                :class="filterType === 'products' ? 'bg-[#800000] text-white shadow-md' : 'bg-white border border-slate-200 text-slate-600 hover:border-slate-300'"
                class="px-4 py-2 rounded-lg font-bold text-xs uppercase tracking-wide transition-all">
                By Product
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="clay-card p-5 border-l-4 border-l-[#800000]">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Sales</p>
            <p class="text-2xl font-black text-[#800000] mt-2" x-text="formatCurrency(totalRevenue)"></p>
            <p class="text-[10px] text-slate-500 mt-2" x-text="'Transactions: ' + allTransactions.length"></p>
        </div>
        <div class="clay-card p-5 border-l-4 border-l-emerald-500">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Items Sold</p>
            <p class="text-2xl font-black text-emerald-600 mt-2" x-text="totalItemsSold"></p>
            <p class="text-[10px] text-slate-500 mt-2">Units across all products</p>
        </div>
        <div class="clay-card p-5 border-l-4 border-l-blue-500">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Unique Products</p>
            <p class="text-2xl font-black text-blue-600 mt-2" x-text="uniqueProducts.length"></p>
            <p class="text-[10px] text-slate-500 mt-2">Different items sold</p>
        </div>
    </div>

    <!-- All Transactions View -->
    <div x-show="filterType === 'all'" class="clay-card overflow-hidden">
        <div class="p-5 border-b bg-slate-50/50 flex justify-between items-center">
            <h3 class="text-xs font-black text-slate-700 uppercase tracking-widest">
                <i class="fas fa-list mr-2"></i>All Transactions
            </h3>
            <div class="text-xs font-bold text-slate-500 uppercase">
                <span class="text-slate-800" x-text="allTransactions.length + ' transactions'"></span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b-2 border-slate-200 bg-slate-50/50 text-left">
                        <th class="px-4 py-3 font-black text-slate-700 uppercase text-xs">Order ID</th>
                        <th class="px-4 py-3 font-black text-slate-700 uppercase text-xs">Product</th>
                        <th class="px-4 py-3 text-center font-black text-slate-700 uppercase text-xs">Qty</th>
                        <th class="px-4 py-3 text-right font-black text-slate-700 uppercase text-xs">Unit Price</th>
                        <th class="px-4 py-3 text-right font-black text-slate-700 uppercase text-xs">Total</th>
                        <th class="px-4 py-3 font-black text-slate-700 uppercase text-xs">Table</th>
                        <th class="px-4 py-3 font-black text-slate-700 uppercase text-xs">Time</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <template x-for="(item, index) in allTransactions" :key="index">
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-3 font-mono text-xs font-bold text-slate-600" x-text="item.orderId"></td>
                            <td class="px-4 py-3 font-bold text-slate-800" x-text="item.name"></td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center justify-center px-2 py-1 rounded-md text-xs font-bold bg-blue-100 text-blue-700"
                                      x-text="item.qty"></span>
                            </td>
                            <td class="px-4 py-3 text-right font-bold text-slate-700" x-text="formatCurrency(item.price)"></td>
                            <td class="px-4 py-3 text-right font-black text-[#800000]" x-text="formatCurrency(item.qty * item.price)"></td>
                            <td class="px-4 py-3 text-sm font-bold text-slate-600" x-text="'T' + item.tableId"></td>
                            <td class="px-4 py-3 text-xs text-slate-500" x-text="formatTime(item.timestamp)"></td>
                        </tr>
                    </template>

                    <tr x-show="allTransactions.length === 0">
                        <td colspan="7" class="px-4 py-8 text-center text-slate-500 font-bold uppercase text-xs tracking-widest">
                            No transactions found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Product Sales Aggregation -->
    <div x-show="filterType === 'products'" class="clay-card overflow-hidden">
        <div class="p-5 border-b bg-slate-50/50 flex justify-between items-center">
            <h3 class="text-xs font-black text-slate-700 uppercase tracking-widest">
                <i class="fas fa-box mr-2"></i>Sales by Product
            </h3>

            <div class="text-xs font-bold text-slate-500 uppercase">
                Total Revenue: <span class="text-slate-800 text-sm ml-1" x-text="formatCurrency(totalRevenue)"></span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b-2 border-slate-200 bg-slate-50/50 text-left">
                        <th class="px-4 py-3 font-black text-slate-700 uppercase text-xs">Product</th>
                        <th class="px-4 py-3 text-center font-black text-slate-700 uppercase text-xs">Qty Sold</th>
                        <th class="px-4 py-3 text-right font-black text-slate-700 uppercase text-xs">Unit Price</th>
                        <th class="px-4 py-3 text-right font-black text-slate-700 uppercase text-xs">Total Revenue</th>
                        <th class="px-4 py-3 text-center font-black text-slate-700 uppercase text-xs">% of Sales</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <template x-for="(item, index) in productSalesData" :key="index">
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-3">
                                <span class="font-bold text-slate-800" x-text="item.name"></span>
                            </td>

                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700"
                                      x-text="item.qtySold"></span>
                            </td>

                            <td class="px-4 py-3 text-right font-bold text-slate-700" x-text="formatCurrency(item.unitPrice)"></td>

                            <td class="px-4 py-3 text-right font-black text-[#800000]" x-text="formatCurrency(item.totalRevenue)"></td>

                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3 justify-center">
                                    <span class="font-bold text-slate-700 text-xs" x-text="item.percentage + '%'"></span>
                                    <div class="w-16 h-2 bg-slate-200 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full"
                                             :style="`width: ${item.percentage}%; background: linear-gradient(90deg, #800000 0%, #d32f2f 100%);`"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </template>

                    <tr x-show="productSalesData.length === 0">
                        <td colspan="5" class="px-4 py-8 text-center text-slate-500 font-bold uppercase text-xs tracking-widest">
                            No product sales found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function productSalesDashboard() {
    return {
        filterType: 'all',
        allTransactions: [],
        totalRevenue: 0,
        totalItemsSold: 0,
        uniqueProducts: [],
        productSalesData: [],

        init() {
            this.loadTransactions();
            window.addEventListener('storage', () => this.loadTransactions());
            setInterval(() => this.loadTransactions(), 3000);
        },

        loadTransactions() {
            const orderHistory = JSON.parse(localStorage.getItem('ub_order_history') || '[]');
            const products = JSON.parse(localStorage.getItem('product_catalog') || '[]');

            this.allTransactions = [];
            const productMap = {};

            orderHistory.forEach(order => {
                if (order.items && Array.isArray(order.items)) {
                    order.items.forEach(item => {
                        this.allTransactions.push({
                            orderId: order.orderId || 'N/A',
                            name: item.name,
                            qty: item.qty || 1,
                            price: item.price || 0,
                            tableId: order.tableId || 'N/A',
                            timestamp: order.timestamp || new Date().toLocaleTimeString()
                        });

                        if (!productMap[item.name]) {
                            productMap[item.name] = {
                                name: item.name,
                                qtySold: 0,
                                totalRevenue: 0,
                                unitPrice: item.price || 0
                            };
                        }
                        productMap[item.name].qtySold += item.qty || 1;
                        productMap[item.name].totalRevenue += (item.qty || 1) * (item.price || 0);
                    });
                }
            });

            this.totalRevenue = this.allTransactions.reduce((sum, t) => sum + (t.qty * t.price), 0);
            this.totalItemsSold = this.allTransactions.reduce((sum, t) => sum + t.qty, 0);
            this.uniqueProducts = Object.values(productMap);

            this.productSalesData = this.uniqueProducts.map(product => ({
                ...product,
                percentage: this.totalRevenue > 0 ? Math.round((product.totalRevenue / this.totalRevenue) * 100) : 0
            })).sort((a, b) => b.totalRevenue - a.totalRevenue);

            this.allTransactions.sort((a, b) => new Date(b.timestamp) - new Date(a.timestamp));
        },

        formatCurrency(val) {
            return '₱' + parseFloat(val || 0).toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        },

        formatTime(timestamp) {
            if (!timestamp) return '';
            try {
                const date = new Date(timestamp);
                return date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
            } catch {
                return timestamp;
            }
        }
    }
}
</script>