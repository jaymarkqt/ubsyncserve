<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UB-SYNCSERVE | Waiter POS Terminal</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f5f7;
            background-image: radial-gradient(#e5e7eb 1px, transparent 1px);
            background-size: 20px 20px;
        }

        [x-cloak] { display: none !important; }

        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }

        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.7);
        }

        .maroon-gradient {
            background: linear-gradient(135deg, #800000 0%, #5a0000 100%);
        }

        .menu-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .menu-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(128, 0, 0, 0.08);
        }
    </style>
</head>

<body class="antialiased text-slate-800">

<div x-data="posSystem()" x-init="initStore()" class="w-full px-4 py-6 lg:px-8 lg:py-8 min-h-screen" x-cloak>
    
    <div class="glass-card rounded-[2rem] p-6 mb-8 flex flex-col md:flex-row justify-between items-center gap-6 shadow-sm">
        <div class="flex items-center gap-6">
            <button @click="handleBackNavigation()" class="w-12 h-12 flex items-center justify-center bg-white rounded-2xl text-slate-400 hover:text-[#800000] hover:shadow-lg transition-all border border-slate-100">
                <i class="fa-solid fa-arrow-left"></i>
            </button>
            <div>
               
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Order <span class="text-[#800000]">Terminal</span></h1>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <div class="text-right hidden sm:block">
                <p class="text-[10px] font-bold text-black-400 uppercase tracking-widest">Active Table</p>
                <p class="text-sm font-black text-[#800000] tracking-wide" x-text="tableNumber ? 'TABLE ' + tableNumber : 'WALK-IN'"></p>
            </div>
            <div class="w-12 h-12 rounded-2xl maroon-gradient flex items-center justify-center text-white shadow-lg shadow-maroon/20">
                <i class="fa-solid fa-utensils"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <div class="lg:col-span-7 xl:col-span-8">
            
            <div class="flex flex-col xl:flex-row gap-4 mb-8">
                <div class="relative flex-1 group">
                    <i class="fa-solid fa-magnifying-glass absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-[#800000] transition-colors"></i>
                    <input type="text" x-model="searchQuery" placeholder="Search menu items..." 
                           class="w-full pl-14 pr-6 py-4 bg-white border-2 border-slate-100 rounded-2xl font-semibold outline-none focus:border-[#800000] transition-all shadow-sm">
                </div>

                <div class="flex gap-2 overflow-x-auto scrollbar-hide pb-1">
                    <template x-for="cat in categories" :key="cat">
                        <button @click="selectedCategory = cat" 
                                :class="selectedCategory === cat ? 'bg-[#800000] text-white shadow-md shadow-maroon/20' : 'bg-white text-slate-500 border-2 border-slate-100 hover:bg-rose-50'"
                                class="px-6 py-3 rounded-2xl text-xs font-bold uppercase tracking-widest whitespace-nowrap transition-all" 
                                x-text="cat"></button>
                    </template>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                <template x-for="p in filteredProducts" :key="p.id">
                    <div class="menu-card bg-white p-5 flex flex-col border border-slate-100 rounded-[2rem] shadow-sm relative group">
                        
                        <div class="w-full h-44 mb-5 overflow-hidden rounded-[1.5rem] bg-slate-50 flex items-center justify-center p-4 relative">
                            <img :src="'/img/' + p.img" 
     :class="p.stock <= 0 ? 'opacity-90' : ''"
     class="w-full h-full object-contain drop-shadow-md group-hover:scale-105 transition-transform duration-500" 
     x-on:error="$el.src='https://placehold.co/400x400/f8fafc/800000?text=No+Image'">
                            
                            <div x-show="p.stock <= 0" class="absolute inset-0 z-10 flex items-center justify-center pointer-events-none">
                                <div class="absolute w-[150%] py-2.5 bg-gradient-to-r from-[#991b1b] via-[#dc2626] to-[#991b1b] transform -rotate-[10deg] shadow-xl border-y border-white/30 flex items-center justify-center">
                                    <div class="flex items-center gap-3">
                                        <span class="w-10 h-0.5 bg-white/90"></span>
                                        <span class="text-white text-3xl tracking-wide drop-shadow-md" style="font-family: 'Impact', 'Arial Black', sans-serif;">SOLD OUT</span>
                                        <span class="w-10 h-0.5 bg-white/90"></span>
                                    </div>
                                </div>
                            </div>

                            <div x-show="p.stock > 0" class="absolute top-3 right-3 bg-white/90 backdrop-blur-md px-3 py-1 rounded-full shadow-sm z-20">
                                <span class="text-[10px] font-bold text-slate-400 uppercase">Stock: </span>
                                <span class="text-[10px] font-black text-slate-700" x-text="p.stock"></span>
                            </div>
                        </div>
                        
                        <div class="flex-grow">
                            <div class="flex justify-between items-start gap-2 mb-2">
                                <h4 class="text-base font-bold text-slate-900 leading-tight uppercase" x-text="p.name"></h4>
                                <span class="text-lg font-black text-[#800000]" x-text="formatCurrency(p.price)"></span>
                            </div>

                            <div class="mb-4 flex flex-wrap gap-1.5 min-h-[20px]">
                                <template x-for="addon in p.selectedAddOns" :key="addon.name">
                                    <span class="bg-rose-50 text-[#800000] text-[9px] font-bold px-2 py-1 rounded-lg border border-rose-100">
                                        + <span x-text="addon.name"></span>
                                    </span>
                                </template>
                            </div>

                            <div class="space-y-3">
                                <div class="flex items-center gap-2">
                                    <div class="flex items-center rounded-xl border-2 border-slate-100 bg-slate-50 h-11 w-28 overflow-hidden"
                                         :class="p.stock <= 0 ? 'opacity-50 pointer-events-none' : ''">
                                        <button @click="if(p.qty > 1) p.qty--" class="w-10 h-full flex items-center justify-center text-slate-500 hover:bg-slate-200 transition-colors">
                                            <i class="fas fa-minus text-[10px]"></i>
                                        </button>
                                        <div class="flex-1 h-full flex items-center justify-center font-black text-slate-800 text-sm bg-white" x-text="p.qty"></div>
                                        <button @click="if(p.qty < p.stock) p.qty++" class="w-10 h-full flex items-center justify-center text-slate-500 hover:bg-slate-200 transition-colors">
                                            <i class="fas fa-plus text-[10px]"></i>
                                        </button>
                                    </div>

                                    <button @click="openCustomizeModal(p)" 
                                            :disabled="p.stock <= 0"
                                            class="flex-1 rounded-xl border-2 border-slate-100 bg-white h-11 text-[10px] font-bold uppercase tracking-widest text-black-500 transition-all"
                                            :class="p.stock <= 0 ? 'opacity-50 cursor-not-allowed' : 'hover:border-[#800000] hover:text-[#800000]'">
                                        Add-ons
                                    </button>
                                </div>
                                
                                <button @click.prevent="addToCart(p)" 
                                        :disabled="p.stock <= 0"
                                        class="w-full py-4 rounded-xl text-[11px] font-black uppercase tracking-[0.1em] transition-all flex items-center justify-center gap-2"
                                        :class="p.stock <= 0 ? 'bg-[#b8bcc6] text-white shadow-inner cursor-not-allowed' : 'bg-[#800000] text-white shadow-lg shadow-maroon/20 active:scale-95 hover:bg-[#600000]'">
                                    <i x-show="p.stock <= 0" class="fa-solid fa-lock text-[11px]"></i>
                                    <span x-text="p.stock <= 0 ? 'SOLD OUT' : 'Add to Order'"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <div class="lg:col-span-5 xl:col-span-4 sticky top-8">
           <div class="bg-white rounded-[2.5rem] p-6 shadow-2xl shadow-red-900/10 border-2 border-[#800000]/50 h-[calc(100vh-120px)] flex flex-col">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Order Details</h2>
                    <span class="bg-rose-50 text-[#800000] px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest" x-text="cart.length + ' Items'"></span>
                </div>

                <div class="flex-1 overflow-y-auto scrollbar-hide space-y-4 pr-1">
                    <div x-show="cart.length === 0" class="h-full flex flex-col items-center justify-center text-slate-300 opacity-60">
                        <i class="fa-solid fa-cart-shopping text-5xl mb-4"></i>
                        <p class="font-bold uppercase text-[10px] tracking-widest">No items selected</p>
                    </div>

                  <template x-for="(item, index) in cart" :key="index">
    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm relative group">
        <div class="flex justify-between gap-4">
            <div class="flex gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#0f172a] flex items-center justify-center text-white font-bold text-sm shrink-0 shadow-inner" x-text="item.qty + 'x'"></div>
                
                <div>
                    <p class="text-sm font-bold text-slate-900 leading-tight uppercase" x-text="item.name"></p>
                    <p x-show="item.addonName" class="text-[10px] text-[#800000] font-bold mt-0.5" x-text="'+ ' + item.addonName"></p>
                    <p class="text-xs font-black text-black-400 mt-1" x-text="formatCurrency(item.price * item.qty)"></p>
                </div>
            </div>
            <button @click="openVoidModal(index)" class="text-red-500 transition-colors">
                <i class="fa-solid fa-trash-can text-sm"></i>
            </button>
        </div>
    </div>
</template>
                </div>
                
                <div class="mt-6 pt-6 border-t border-slate-100">
                    <div class="bg-slate-50 rounded-2xl p-4 mb-4">
                        <div class="flex justify-between items-center">
                           <span class="text-base font-black text-black uppercase tracking-widest">Total Bill</span>
                            <span class="text-3xl font-black text-[#800000]" x-text="formatCurrency(cartTotal)"></span>
                        </div>
                    </div>
                    
                    <button @click="completeOrder()" :disabled="cart.length === 0"
                            class="w-full py-5 maroon-gradient text-white rounded-[1.5rem] font-black text-sm uppercase tracking-[0.2em] shadow-xl shadow-maroon/20 hover:shadow-2xl transition-all disabled:opacity-50">
                        Complete Order <i class="fa-solid fa-check-circle ml-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div x-show="showModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm" x-cloak>
        <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md overflow-hidden">
            <div class="bg-slate-50 p-6 border-b border-slate-100">
                <h3 class="text-xl font-black text-slate-900" x-text="selectedFood ? 'Customize ' + selectedFood.name : ''"></h3>
                <p class="text-xs font-bold text-[#800000] mt-1 uppercase tracking-widest">Select Extra Add-ons</p>
            </div>

            <div class="p-6 max-h-[60vh] overflow-y-auto scrollbar-hide space-y-3">
                <template x-for="addon in (selectedFood ? selectedFood.addOns : [])" :key="addon.name">
                    <label class="flex items-center gap-4 border-2 border-slate-100 rounded-2xl p-4 cursor-pointer hover:bg-rose-50 transition-all"
                           :class="tempSelectedAddOns.some(a => a.name === addon.name) ? 'border-[#800000] bg-rose-50/50' : ''">
                        <input type="checkbox" class="accent-[#800000] w-5 h-5 rounded-lg"
                               :checked="tempSelectedAddOns.some(a => a.name === addon.name)"
                               @change="toggleAddon(addon)">
                        <span class="font-bold text-slate-700" x-text="addon.name"></span>
                        <span class="ml-auto font-black text-[#800000] bg-white px-3 py-1 rounded-lg border border-slate-100 shadow-sm text-xs" x-text="'+ ' + formatCurrency(addon.price)"></span>
                    </label>
                </template>
            </div>

            <div class="p-6 border-t border-slate-100 flex gap-3">
                <button @click="closeCustomizeModal()" class="flex-1 py-4 bg-white border-2 border-slate-100 text-slate-500 font-bold rounded-2xl uppercase text-xs tracking-widest">Cancel</button>
                <button @click="confirmAddOns()" class="flex-1 py-4 maroon-gradient text-white font-black rounded-2xl uppercase text-xs tracking-widest">Apply</button>
            </div>
        </div>
    </div>

    <div x-show="showVoidModal" x-cloak class="fixed inset-0 z-[1300] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div class="clay-card w-full max-w-sm overflow-hidden bg-white rounded-3xl shadow-2xl">
            <div class="bg-red-600 p-8 text-white text-center">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shield-alt text-xl"></i>
                </div>
                <h3 class="text-2xl font-black uppercase tracking-tighter">Security Check</h3>
                <p class="text-[10px] font-bold uppercase tracking-widest opacity-80 mt-1">Manager Pin Required</p>
            </div>
            <div class="p-8 space-y-6">
                <input type="password" x-model="voidCodeInput" class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl p-5 text-center font-black text-2xl tracking-[0.5em] focus:border-red-600 focus:bg-white outline-none transition-all" placeholder="****">
                <div class="flex gap-3">
                    <button @click="cancelVoid()" class="flex-1 py-4 bg-slate-100 text-slate-500 font-black text-[11px] uppercase rounded-2xl hover:bg-slate-200 transition-all">Cancel</button>
                    <button @click="confirmVoidOrder()" class="flex-1 py-4 bg-red-600 text-white font-black text-[11px] uppercase rounded-2xl shadow-lg shadow-red-900/20 hover:bg-red-700 transition-all">Confirm</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function posSystem() {
        return {
            searchQuery: '',
            selectedCategory: 'All',
            categories: ['All', 'Breakfast', 'Lunch', 'Snacks', 'Dinner', 'Drinks'],
            tableNumber: null,
            adults: 0,
            children: 0,
            cart: [],
            showModal: false,
            selectedFood: null,
            tempSelectedAddOns: [],
            products: [],
            showVoidModal: false,
            voidOrderIndex: null,
            voidCodeInput: '',
            managerCode: '1234',

            initStore() {
                this.loadProducts();
                const params = new URLSearchParams(window.location.search);
                this.tableNumber = params.get('table');
                this.adults = parseInt(params.get('adults')) || 0;
                this.children = parseInt(params.get('children')) || 0;
            },

            loadProducts() {
                const saved = localStorage.getItem('product_catalog');
                if (saved) {
                    this.products = JSON.parse(saved).map(p => ({
                        ...p,
                        qty: 1,
                        selectedAddOns: []
                    }));
                }
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
                return '₱' + parseFloat(val).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
            },

            openCustomizeModal(product) {
                this.selectedFood = product;
                this.tempSelectedAddOns = product.selectedAddOns ? [...product.selectedAddOns] : [];
                this.showModal = true;
            },

            toggleAddon(addon) {
                const idx = this.tempSelectedAddOns.findIndex(a => a.name === addon.name);
                if (idx !== -1) this.tempSelectedAddOns.splice(idx, 1);
                else this.tempSelectedAddOns.push(addon);
            },

            confirmAddOns() {
                if (this.selectedFood) this.selectedFood.selectedAddOns = [...this.tempSelectedAddOns];
                this.showModal = false;
            },

            closeCustomizeModal() { this.showModal = false; },

            addToCart(product) {
                if (product.stock <= 0) return;
                
                const addOnPrice = (product.selectedAddOns || []).reduce((sum, a) => sum + a.price, 0);
                const addonNameStr = (product.selectedAddOns || []).map(a => a.name).join(', ');
                const cartId = product.id + '-' + addonNameStr;

                const existing = this.cart.find(i => i.cartId === cartId);
                if (existing) {
                    existing.qty += product.qty;
                } else {
                    this.cart.push({
                        ...product,
                        cartId,
                        price: product.price + addOnPrice,
                        addonName: addonNameStr,
                        qty: product.qty
                    });
                }
                
                // RESET product state without notification
                product.selectedAddOns = [];
                product.qty = 1;
            },

            removeFromCart(index) { this.cart.splice(index, 1); },

            openVoidModal(index) {
                this.voidOrderIndex = index;
                this.voidCodeInput = '';
                this.showVoidModal = true;
            },

            cancelVoid() {
                this.showVoidModal = false;
                this.voidOrderIndex = null;
                this.voidCodeInput = '';
            },

            confirmVoidOrder() {
                if (this.voidCodeInput.trim() === '') {
                    alert('Please enter manager code.');
                    return;
                }

                if (this.voidCodeInput.trim() !== this.managerCode) {
                    alert('Invalid manager code. Void cancelled.');
                    return;
                }

                this.removeFromCart(this.voidOrderIndex);
                this.cancelVoid();
            },

            handleBackNavigation() {
                window.location.href = "{{ route('waiter.dashboard') }}";
            },

            completeOrder() {
                let tables = JSON.parse(localStorage.getItem('ub_tables') || '[]');
                let products = JSON.parse(localStorage.getItem('product_catalog') || '[]');
                let analyticsHistory = JSON.parse(localStorage.getItem('ub_order_history') || '[]');

                let idx = tables.findIndex(t => t.id == this.tableNumber);

                this.cart.forEach(item => {
                    let pIdx = products.findIndex(p => p.id === item.id);
                    if (pIdx !== -1) products[pIdx].stock -= item.qty;
                });
                localStorage.setItem('product_catalog', JSON.stringify(products));

                if (idx !== -1) {
                    tables[idx].status = 'occupied';
                    tables[idx].adults = this.adults;
                    tables[idx].children = this.children;
                    tables[idx].orders = [...(tables[idx].orders || []), ...this.cart];
                    tables[idx].bill = (tables[idx].bill || 0) + this.cartTotal;
                }
                localStorage.setItem('ub_tables', JSON.stringify(tables));

                const transaction = {
                    orderId: 'ORD-' + Date.now(),
                    timestamp: new Date().toLocaleTimeString(),
                    totalAmount: this.cartTotal,
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

                alert('Order successfully sent to kitchen!');
                window.location.href = "{{ route('waiter.dashboard') }}";
            }
        }
    }
</script>
</body>
</html>