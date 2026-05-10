<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waiter Menu - POS System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        [x-cloak] { display: none !important; }
        .bg-maroon { background-color: #800000; }
        .text-maroon { color: #800000; }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased">

<div x-data="posSystem()" x-init="initStore()" class="max-w-[1600px] mx-auto p-4 lg:p-6" x-cloak>
    
    <div class="flex justify-between items-center mb-6 bg-white p-4 rounded-2xl shadow-sm border border-gray-200">
        <div class="flex items-center gap-4">
            <button @click="handleBackNavigation()" class="w-10 h-10 flex items-center justify-center bg-gray-100 rounded-xl text-gray-600 hover:bg-red-50 hover:text-[#800000] transition-all">
                <i class="fa-solid fa-arrow-left"></i>
            </button>
            <h1 class="text-xl font-black text-gray-800 uppercase tracking-tight">Menu Selection</h1>
        </div>
        <div class="flex items-center gap-3">
            <div class="text-right">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Active Table</p>
                <p class="text-sm font-black text-[#800000]" x-text="tableNumber ? 'TABLE ' + tableNumber : 'WALK-IN'"></p>
            </div>
            <div class="w-10 h-10 rounded-full bg-[#800000] flex items-center justify-center text-white">
                <i class="fa-solid fa-utensils text-sm"></i>
            </div>
        </div>
    </div>

    <div class="pos-interface grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">
        
        <div class="lg:col-span-7 xl:col-span-8 flex flex-col">
            <div class="mb-6 flex flex-col items-center w-full">
                <div class="relative w-full">
                    <i class="fa-solid fa-magnifying-glass absolute left-6 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                    <input type="text" x-model="searchQuery" placeholder="Search menu items..." 
                           class="w-full pl-16 pr-6 py-5 bg-white border border-gray-200 rounded-2xl text-base font-bold outline-none shadow-sm focus:ring-4 focus:ring-red-900/10 focus:border-red-800 transition-all">
                </div>

              <div class="flex justify-start lg:justify-center gap-3 w-full mt-5 overflow-x-auto pb-4 scrollbar-hide px-1">
    <template x-for="cat in ['All', 'Breakfast', 'Lunch', 'Snacks', 'Dinner', 'Drinks']">
        <button x-on:click="selectedCategory = cat" 
                :class="selectedCategory === cat ? 'bg-[#800000] text-white shadow-md border-[#800000]' : 'bg-white text-gray-500 border-gray-200 hover:border-gray-300 hover:bg-gray-50'"
                class="px-6 py-3.5 rounded-2xl text-sm font-black uppercase border whitespace-nowrap transition-all tracking-wide" 
                x-text="cat"></button>
    </template>
</div>
            </div>

            <div class="grid grid-cols-2 xl:grid-cols-3 gap-6">
               <template x-for="p in filteredProducts" :key="p.id">
    <div class="bg-white p-5 flex flex-col relative border border-gray-200 rounded-[2rem] shadow-sm hover:shadow-lg transition-all duration-300">
        <div class="w-full h-44 mb-4 overflow-hidden rounded-[2rem] bg-[#fdfdfd] border border-gray-100 flex items-center justify-center">
            <img :src="'/img/' + p.img" class="w-full h-full object-contain p-4" x-on:error="$el.src='https://placehold.co/400x400/f8fafc/800000?text=No+Image'">
        </div>
        
        <div class="mb-4 flex-grow">
            <h4 class="text-base font-black text-gray-800 uppercase tracking-tight leading-tight mb-1" x-text="p.name"></h4>
            
            <div class="mb-2 flex flex-wrap gap-1 min-h-[20px]">
                <template x-if="p.selectedAddOns && p.selectedAddOns.length > 0">
                    <template x-for="addon in p.selectedAddOns" :key="addon.name">
                        <span class="bg-orange-100 text-orange-700 text-[9px] font-black px-2 py-0.5 rounded-full uppercase border border-orange-200 animate__animated animate__fadeIn">
                            + <span x-text="addon.name"></span>
                        </span>
                    </template>
                </template>
            </div>

            <div class="flex flex-col gap-3">
                <div class="flex justify-between items-center">
                    <span class="text-xl font-black text-[#800000]" x-text="'₱' + p.price.toFixed(2)"></span>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider" x-text="'Stock: ' + p.stock"></span>
                </div>
                <div class="inline-flex items-center gap-2 rounded-2xl border border-gray-200 bg-slate-50 px-2 py-2">
                    <button type="button" @click="p.qty = Math.max(1, p.qty - 1)" class="h-8 w-8 rounded-2xl bg-white text-[#800000] font-black transition hover:bg-[#f3d0d0]">-</button>
                    <input type="number" x-model.number="p.qty" min="1" class="w-14 bg-transparent text-center text-sm font-black text-gray-700 outline-none" />
                    <button type="button" @click="p.qty = (p.qty || 1) + 1" class="h-8 w-8 rounded-2xl bg-white text-[#800000] font-black transition hover:bg-[#f3d0d0]">+</button>
                </div>
                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider">Quantity</div>
            </div>
        </div>

        <div class="mt-auto space-y-3 pt-4 border-t border-gray-100">
            <button @click="openCustomizeModal(p)" class="flex items-center justify-between w-full bg-gray-50 border border-gray-200 rounded-xl p-3 hover:bg-gray-100 transition-colors">
                <span class="text-[11px] font-bold text-[#800000] uppercase tracking-wider">Customize Add-ons</span>
                <i class="fas fa-plus text-[#800000] text-xs"></i>
            </button>
            
            <button x-on:click.prevent="addToCart(p)" 
                    class="w-full py-4 bg-[#800000] text-white rounded-xl text-sm font-black uppercase shadow-md active:scale-[0.98]">
                Add to Order
            </button>
        </div>
    </div>
</template>
            </div>
        </div>

        <div class="lg:col-span-5 xl:col-span-4 sticky top-4">
            <div class="bg-white p-5 border-t-[8px] border-[#800000] shadow-xl flex flex-col h-[calc(100vh-60px)] rounded-[2rem] overflow-hidden">
                
                <div class="space-y-3 mb-4 overflow-y-auto flex-1 min-h-[150px] scrollbar-hide pr-1 relative">
                    <div x-show="cart.length === 0" class="absolute inset-0 flex flex-col items-center justify-center text-center p-6 bg-white">
                        <i class="fa-solid fa-cart-shopping text-4xl text-gray-100 mb-4"></i>
                        <p class="text-sm font-bold text-gray-300 uppercase tracking-widest">Cart is empty</p>
                    </div>

                    <template x-for="(item, index) in cart" :key="index">
                        <div class="flex justify-between items-center bg-white p-3.5 rounded-xl border border-gray-200 shadow-sm animate__animated animate__fadeInRight animate__faster">
                            <div class="flex items-center gap-3 w-full">
                                <div class="w-8 h-8 rounded-lg bg-red-50 text-[#800000] flex items-center justify-center text-sm font-black shrink-0" x-text="item.qty + 'x'"></div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-black uppercase text-gray-800 truncate" x-text="item.name"></p>
                                    
                                 <p x-show="item.addonName" class="text-[10px] text-orange-600 font-bold uppercase truncate" x-text="'+ ' + item.addonName"></p>
                                    <p class="text-xs text-gray-500 font-bold" x-text="formatCurrency(item.price * item.qty)"></p>
                                </div>
                            </div>
                            <button x-on:click="removeFromCart(index)" class="w-10 h-10 shrink-0 flex items-center justify-center text-gray-400 hover:text-red-500 bg-gray-50 rounded-lg transition-colors ml-2">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </template>
                </div>
                
                <div class="space-y-4 pt-3 border-t border-gray-100 shrink-0 bg-white">
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                        <div class="flex justify-between items-end pt-3">
                            <span class="text-xs font-black text-gray-800 uppercase tracking-widest">Total Due</span>
                            <span class="text-3xl font-black text-[#800000]" x-text="formatCurrency(cartTotal)"></span>
                        </div>
                    </div>
                    
                    <button type="button" 
                            @click="completeOrder"
                            :disabled="cart.length === 0"
                            class="w-full py-4 bg-[#800000] text-white rounded-xl font-black text-sm uppercase shadow-lg disabled:opacity-50 flex justify-center items-center gap-2 transition-all">
                        Complete Order <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div x-show="showModal" class="fixed inset-0 z-[200] flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="closeCustomizeModal()"></div>
        <div class="bg-white rounded-[2rem] p-6 max-w-md w-full shadow-2xl relative z-10 animate__animated animate__zoomIn animate__faster">
            <div class="text-center mb-6 mt-2">
                <h3 class="text-lg font-extrabold text-[#800000] tracking-wide leading-tight uppercase" x-text="'Customize ' + (selectedFood ? selectedFood.name : '')"></h3>
                <p class="text-xs text-gray-500 mt-1">Select add-ons for this item</p>
            </div>

            <div class="space-y-3 max-h-60 overflow-y-auto mb-6 px-2 scrollbar-hide">
                <template x-for="addon in (selectedFood ? selectedFood.addOns : [])" :key="addon.name">
                    <label class="flex items-center bg-gray-50 rounded-lg p-3 hover:bg-gray-100 transition-colors cursor-pointer border border-gray-100">
                        <input type="checkbox" class="accent-[#800000] mr-3 w-5 h-5"
                               :checked="tempSelectedAddOns.some(a => a.name === addon.name)"
                               @change="toggleAddon(addon)">
                        <span class="text-sm font-medium text-gray-800" x-text="addon.name"></span>
                        <span class="text-sm text-[#800000] font-black ml-auto" x-text="'+₱' + addon.price"></span>
                    </label>
                </template>
            </div>

            <div class="flex gap-3">
                <button @click="closeCustomizeModal()" class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-xl font-bold uppercase text-sm">Cancel</button>
                <button @click="confirmAddOns()" class="flex-1 bg-[#800000] text-white py-3 rounded-xl font-bold uppercase text-sm transition-all">Confirm</button>
            </div>
        </div>
    </div>
</div>

<script>
  function posSystem() {
    return {
        searchQuery: '',
        selectedCategory: 'All',
        tableNumber: null,
        cart: [],
        adults: 0,
        children: 0,
        showModal: false,
        selectedFood: null,
        tempSelectedAddOns: [],
        
    products: [],

      defaultProducts() {
            return [
                { id: 1, name: 'Burger Steak', price: 99, cat: 'Lunch', img: 'burgersteak.png', stock: 24, qty: 1, addOns: [{ name: 'Extra Rice', price: 20 }, { name: 'Cheese', price: 15 }] },
                { id: 2, name: 'Tapa & Egg', price: 120, cat: 'Breakfast', img: 'tapa.png', stock: 18, qty: 1, addOns: [{ name: 'Extra Egg', price: 15 }, { name: 'Garlic Rice', price: 20 }] },
                { id: 3, name: 'Fries', price: 65, cat: 'Snacks', img: 'fries.png', stock: 30, qty: 1, addOns: [{ name: 'Cheese Sauce', price: 10 }, { name: 'Bacon Bits', price: 15 }] },
                { id: 4, name: 'Fried Chicken', price: 150, cat: 'Dinner', img: 'chicken.png', stock: 12, qty: 1, addOns: [{ name: 'Extra Gravy', price: 10 }, { name: 'Spicy Dip', price: 10 }] },
                { id: 5, name: 'Ice Tea', price: 45, cat: 'Drinks', img: 'icetea.png', stock: 40, qty: 1, addOns: [{ name: 'Large Cup', price: 15 }] }
            ];
        },

        loadProducts() {
            const savedProducts = localStorage.getItem('product_catalog');
            if (savedProducts) {
                try {
                    const parsed = JSON.parse(savedProducts);
                    this.products = parsed.map(product => ({
                        ...product,
                        price: product.price ?? product.sellingPrice ?? 0,
                        qty: product.qty || 1,
                        selectedAddOns: product.selectedAddOns || []
                    }));
                } catch (error) {
                    this.products = this.defaultProducts();
                    localStorage.setItem('product_catalog', JSON.stringify(this.products));
                }
            } else {
                this.products = this.defaultProducts();
                localStorage.setItem('product_catalog', JSON.stringify(this.products));
            }
        },

      initStore() {
            // --- REFRESH DETECTOR ---
            const navEntries = performance.getEntriesByType("navigation");
            if (navEntries.length > 0 && navEntries[0].type === "reload") {
                localStorage.removeItem('ub_tables');
                localStorage.removeItem('ub_order_history');
            }
            // ------------------------
            this.loadProducts();
            window.addEventListener('storage', () => this.loadProducts());

           const params = new URLSearchParams(window.location.search);
            this.tableNumber = params.get('table');
            this.adults = parseInt(params.get('adults')) || 0;
            this.children = parseInt(params.get('children')) || 0;
        },


        handleBackNavigation() {
            if (this.cart.length === 0 && this.tableNumber) {
                let tables = JSON.parse(localStorage.getItem('ub_tables') || '[]');
                let idx = tables.findIndex(t => t.id == this.tableNumber);
                if (idx !== -1) { tables[idx].status = 'vacant'; localStorage.setItem('ub_tables', JSON.stringify(tables)); }
            }
            window.location.href = "{{ route('waiter.dashboard') }}";
        },

        get filteredProducts() {
            return this.products.filter(p => {
                const matchesSearch = p.name.toLowerCase().includes(this.searchQuery.toLowerCase());
                const matchesCat = this.selectedCategory === 'All' || p.cat === this.selectedCategory;
                return matchesSearch && matchesCat;
            });
        },
        
        get cartTotal() {
            return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
        },

        formatCurrency(val) {
            return '₱' + parseFloat(val).toLocaleString(undefined, {minimumFractionDigits: 2});
        },

       openCustomizeModal(product) {
    this.selectedFood = product;
    // Load existing selections if any
    this.tempSelectedAddOns = product.selectedAddOns ? [...product.selectedAddOns] : [];
    this.showModal = true;
},

        toggleAddon(addon) {
            let exists = this.tempSelectedAddOns.findIndex(a => a.name === addon.name);
            if (exists !== -1) this.tempSelectedAddOns.splice(exists, 1);
            else this.tempSelectedAddOns.push(addon);
        },

       confirmAddOns() {
    if (this.selectedFood) {
        // I-save ang pinili sa mismong product object para mabasa ng UI
        this.selectedFood.selectedAddOns = [...this.tempSelectedAddOns];
    }
    this.showModal = false;
},


        closeCustomizeModal() { this.showModal = false; },

        addToCart(product) {
            const qty = Math.max(1, product.qty || 1);
            const selectedAddOns = product.selectedAddOns || [];
            const addOnPrice = selectedAddOns.reduce((sum, a) => sum + a.price, 0);
            const finalUnitPrice = product.price + addOnPrice;
            const addonNameStr = selectedAddOns.length > 0 ? selectedAddOns.map(a => a.name).join(', ') : '';
            const cartId = product.id + (addonNameStr ? '-' + addonNameStr : '');

            let foundIndex = this.cart.findIndex(i => i.cartId === cartId);
            if (foundIndex > -1) {
                this.cart[foundIndex].qty += qty;
            } else {
                this.cart.push({
                    ...product,
                    cartId,
                    qty,
                    addonName: addonNameStr,
                    price: finalUnitPrice
                });
            }
            product.selectedAddOns = [];
            product.qty = 1;
        },

        removeFromCart(index) { this.cart.splice(index, 1); },

       // Sa menu.blade.php, hanapin ang completeOrder() at palitan ng ganito:
completeOrder() {
    if (!this.tableNumber || this.cart.length === 0) return;

    let tables = JSON.parse(localStorage.getItem('ub_tables') || '[]');
    let analyticsHistory = JSON.parse(localStorage.getItem('ub_order_history') || '[]');

    let idx = tables.findIndex(t => t.id == this.tableNumber);

    const totalAmount = this.cart.reduce(
        (sum, item) => sum + (item.price * item.qty), 0
    );

    // save sa waiter tables
    if (idx !== -1) {
        tables[idx].status = 'occupied';
                tables[idx].adults = this.adults;        // <-- IDAGDAG ITO
                tables[idx].children = this.children;    // <-- IDAGDAG ITO
                tables[idx].orders = [...(tables[idx].orders || []), ...this.cart];
                tables[idx].bill = totalAmount;
            
    }

    localStorage.setItem('ub_tables', JSON.stringify(tables));

    // save analytics transaction
    const transaction = {
        orderId: 'ORD-' + Date.now(),
        timestamp: new Date().toLocaleTimeString(),
        totalAmount: totalAmount,
        tableId: this.tableNumber,
        items: this.cart.map(item => ({
            name: item.name,
            qty: item.qty
        }))
    };

    analyticsHistory.unshift(transaction);

    localStorage.setItem(
        'ub_order_history',
        JSON.stringify(analyticsHistory)
    );

    // redirect
    window.location.href = "{{ route('waiter.dashboard') }}";
},
    }
}


</script>
</body>
</html>