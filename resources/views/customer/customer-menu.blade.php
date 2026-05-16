<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Menu | Digital Ordering</title>

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
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.7);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.04);
        }

        .maroon-gradient {
            background: linear-gradient(135deg, #800000 0%, #5a0000 100%);
        }

        .menu-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .menu-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(128, 0, 0, 0.08);
            border-color: rgba(128, 0, 0, 0.1);
        }
    </style>
</head>

<body class="antialiased text-slate-800">

<div x-data="digitalOrdering()" x-init="initOrder()" class="min-h-screen pb-40 sm:pb-32" x-cloak>

    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-10 2xl:px-12 pt-6 sm:pt-8">

        <div class="glass-card rounded-[1.5rem] sm:rounded-[2rem] p-5 sm:p-8 mb-6 sm:mb-8 relative">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 sm:gap-5">
                <div>
                    <p class="text-[10px] sm:text-xs font-black tracking-[0.35em] uppercase text-slate-400 mb-1">
                        Digital Ordering
                    </p>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight">
                        Our <span class="text-[#800000]">Menu</span>
                    </h1>
                </div>

                <div class="inline-flex items-center gap-3 bg-white rounded-2xl px-4 sm:px-5 py-3 shadow-sm border border-slate-100 self-start md:self-auto">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-rose-50 flex items-center justify-center">
                        <i class="fas fa-utensils text-[#800000] text-sm sm:text-base"></i>
                    </div>
                    <div>
                        <p class="text-[9px] sm:text-[10px] uppercase font-bold text-slate-400 tracking-wider">Current Table</p>
                        <p class="font-black text-slate-800 text-xs sm:text-sm tracking-wide" x-text="tableNumber ? 'Table ' + tableNumber : 'Unassigned'"></p>
                    </div>
                </div>
            </div>

            <div class="mt-6 sm:mt-8 flex flex-col xl:flex-row gap-4 sm:gap-6 items-center">
                
                <div class="w-full xl:w-1/3 relative group">
                    <i class="fas fa-search absolute left-4 sm:left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-[#800000] transition-colors text-sm sm:text-base"></i>
                    <input
                        x-model="searchQuery"
                        type="text"
                        placeholder="What are you craving?"
                        class="w-full rounded-xl sm:rounded-2xl border-2 border-slate-100 bg-white/50 py-3 sm:py-3.5 pl-10 sm:pl-12 pr-5 text-sm font-semibold outline-none focus:border-[#800000] focus:bg-white transition-all shadow-sm placeholder:text-slate-400"
                    >
                    <button x-show="searchQuery" @click="searchQuery = ''" class="absolute right-4 sm:right-5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                        <i class="fas fa-times text-sm sm:text-base"></i>
                    </button>
                </div>

                <div class="w-full xl:w-2/3 flex gap-2 sm:gap-3 overflow-x-auto scrollbar-hide pb-2 mask-linear">
                    <template x-for="cat in categories" :key="cat">
                        <button
                            @click="selectedCategory = cat"
                            :class="selectedCategory === cat
                                ? 'bg-[#800000] text-white shadow-md shadow-maroon/20 border-transparent'
                                : 'bg-white text-slate-600 border-slate-200 hover:border-[#800000]/30 hover:bg-rose-50/50'"
                            class="px-5 sm:px-6 py-2.5 sm:py-3 rounded-xl sm:rounded-2xl border text-xs sm:text-sm font-bold tracking-wide whitespace-nowrap transition-all duration-200 flex-shrink-0">
                            <span x-text="cat"></span>
                        </button>
                    </template>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-2 sm:gap-4 md:gap-5 lg:gap-6 xl:gap-8">
            <template x-for="product in filteredProducts" :key="product.id">
                <div class="menu-card bg-white p-2 sm:p-4 md:p-5 flex flex-col border border-slate-100 rounded-lg sm:rounded-xl md:rounded-[1.5rem] lg:rounded-[2rem] shadow-sm relative group">

                    <div class="w-full h-28 sm:h-36 md:h-40 lg:h-48 mb-2 sm:mb-3 md:mb-4 lg:mb-5 overflow-hidden rounded-lg sm:rounded-xl md:rounded-[1.25rem] lg:rounded-[1.5rem] bg-gradient-to-br from-slate-50 to-slate-100 flex items-center justify-center p-2 sm:p-3 md:p-4 relative">


                        <img :src="'/img/' + product.img" class="w-full h-full object-contain drop-shadow-md group-hover:scale-105 transition-transform duration-500" x-on:error="$el.src='https://placehold.co/400x400/f8fafc/800000?text=No+Image'">
                    </div>

                    <div class="flex-grow flex flex-col">
                        <div class="mb-1 sm:mb-2 flex justify-between items-start gap-1">
                            <h4 class="text-xs sm:text-sm md:text-base lg:text-lg font-bold text-slate-900 leading-tight line-clamp-2" x-text="product.name"></h4>
                            <span class="text-sm sm:text-base md:text-lg lg:text-xl font-black text-[#800000] whitespace-nowrap flex-shrink-0 text-right" x-text="formatCurrency(product.price)"></span>
                        </div>

                        <div class="mb-1.5 sm:mb-2 md:mb-3 flex flex-wrap gap-1 min-h-[16px]">
                            <template x-for="addon in product.selectedAddOns" :key="addon.name">
                                <span class="bg-rose-50 text-[#800000] text-[7px] sm:text-[8px] md:text-[9px] font-bold px-1 sm:px-1.5 py-0.5 rounded-sm sm:rounded-md border border-rose-100 flex items-center gap-0.5">
                                    <i class="fas fa-plus text-[5px] sm:text-[6px]"></i> <span x-text="addon.name"></span>
                                </span>
                            </template>
                        </div>

                        <div class="mt-auto space-y-1 sm:space-y-1.5 md:space-y-2">
                            <div class="flex items-center gap-1 sm:gap-1.5 md:gap-2">
                                <div class="flex items-center rounded-lg sm:rounded-xl border-2 border-slate-100 bg-slate-50 h-8 sm:h-9 md:h-10 lg:h-11 w-20 sm:w-24 md:w-28 overflow-hidden flex-shrink-0">
                                    <button
                                        @click="if (product.stock > 0 && product.qty > 1) product.qty = product.qty - 1"
                                        :disabled="product.stock === 0"
                                        :class="['w-6 sm:w-8 md:w-10 h-full flex items-center justify-center transition-colors text-[8px] sm:text-[9px] md:text-xs', product.stock === 0 ? 'text-gray-300 cursor-not-allowed' : 'text-slate-500 hover:bg-slate-200']">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <div class="flex-1 h-full flex items-center justify-center font-black text-slate-800 text-xs sm:text-sm bg-white">
                                        <span x-text="product.stock === 0 ? 0 : product.qty"></span>
                                    </div>
                                    <button
                                        @click="if (product.stock > product.qty) product.qty++"
                                        :disabled="product.stock === 0 || product.qty >= product.stock"
                                        :class="['w-6 sm:w-8 md:w-10 h-full flex items-center justify-center transition-colors text-[8px] sm:text-[9px] md:text-xs', product.stock === 0 ? 'text-gray-300 cursor-not-allowed' : 'text-slate-500 hover:bg-slate-200']">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>

                                <button @click="openCustomizeModal(product)"
                                        :disabled="product.stock === 0"
                                        :class="['flex-1 rounded-lg sm:rounded-xl border-2 border-slate-100 bg-white h-8 sm:h-9 md:h-10 lg:h-11 text-[7px] sm:text-[8px] md:text-[9px] lg:text-[10px] font-bold uppercase tracking-widest text-center transition-all px-0.5 sm:px-1', product.stock === 0 ? 'text-slate-300 border-slate-200 cursor-not-allowed' : 'text-slate-500 hover:border-[#800000] hover:text-[#800000]']">
                                    <i class="fas fa-sliders-h mr-0.5 sm:mr-1"></i><span class="hidden sm:inline">Add-ons</span><span class="sm:hidden">+</span>
                                </button>
                            </div>

                            <button @click.prevent="addToCart(product)"
                                    :disabled="product.stock === 0"
                                    :class="['w-full rounded-lg sm:rounded-xl md:rounded-[1rem] text-white h-8 sm:h-9 md:h-10 lg:h-11 text-[8px] sm:text-[9px] md:text-[10px] lg:text-[11px] font-black uppercase tracking-widest text-center shadow-lg transition-all active:scale-[0.98] flex items-center justify-center gap-1', product.stock === 0 ? 'bg-gray-300 cursor-not-allowed shadow-none' : 'maroon-gradient shadow-maroon/20 hover:shadow-xl']">
                                <i class="fas fa-shopping-bag text-[7px] sm:text-[8px] md:text-xs"></i> <span class="hidden sm:inline">Add</span>
                            </button>
                        </div>
                    </div>

                    <div x-show="product.stock === 0" class="absolute inset-0 bg-black/60 rounded-lg sm:rounded-xl md:rounded-[1.5rem] flex items-center justify-center">
                        <div class="text-center">
                            <div class="text-[8px] sm:text-[9px] font-black uppercase tracking-[0.45em] text-red-100 mb-1">Out of Stock</div>
                            <div class="inline-flex items-center justify-center bg-red-600 text-white text-[7px] sm:text-[8px] md:text-[9px] font-black uppercase rounded-full px-2 sm:px-3 py-1 tracking-[0.2em] shadow-xl">
                                SOLD OUT
                            </div>
            </template>

            <div x-show="filteredProducts.length === 0" class="col-span-full py-8 sm:py-10 md:py-12 text-center" x-cloak>
                <div class="w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 mx-auto bg-slate-200 rounded-full flex items-center justify-center mb-3 sm:mb-4 text-slate-400">
                    <i class="fas fa-hamburger text-xl sm:text-2xl md:text-3xl"></i>
                </div>
                <h3 class="text-base sm:text-lg md:text-xl font-bold text-slate-700">No items found</h3>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Try adjusting your search or category filter.</p>
            </div>
        </div>
    </div>

    <div x-show="cart.length > 0" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-10"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="fixed bottom-4 right-4 sm:bottom-6 sm:right-6 z-50">
        <a href="{{ route('order.cart') }}"
           class="group rounded-2xl maroon-gradient px-4 sm:px-6 py-3 sm:py-4 shadow-2xl flex items-center gap-3 sm:gap-4 hover:-translate-y-1 transition-all duration-300 border border-white/20">
            
            <div class="relative w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white border border-white/30 group-hover:scale-110 transition-transform">
                <i class="fas fa-shopping-cart text-sm sm:text-base"></i>
                <span class="absolute -top-2 -right-2 bg-white text-[#800000] text-[9px] sm:text-[10px] font-black w-5 h-5 sm:w-6 sm:h-6 rounded-full flex items-center justify-center shadow-md" x-text="cart.length"></span>
            </div>

            <div class="pr-1 sm:pr-2">
                <p class="text-[9px] sm:text-[10px] uppercase font-bold tracking-[0.2em] text-white/80 mb-0 sm:mb-0.5">
                    View Order
                </p>
                <p class="text-xs sm:text-sm font-black text-white" x-text="formatCurrency(cartTotal)"></p>
            </div>
            
            <i class="fas fa-chevron-right text-white/50 group-hover:text-white transition-colors ml-1 sm:ml-2 text-xs sm:text-sm"></i>
        </a>
    </div>

    <div x-show="showCustomize"
         class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-sm flex items-end sm:items-center justify-center p-0 sm:p-4"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak>

        <div class="bg-white rounded-t-[2rem] sm:rounded-[2rem] shadow-2xl w-full max-w-md overflow-hidden max-h-[90vh] flex flex-col"
            
             x-transition:enter="transition ease-out duration-300 delay-100"
             x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-8 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-8 sm:scale-95">

            <div class="bg-slate-50 p-5 sm:p-6 border-b border-slate-100 relative shrink-0">
                <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto mb-4 sm:hidden"></div>
                
               
                <h3 class="text-lg sm:text-xl font-black text-slate-900 pr-8" x-text="selectedProduct ? selectedProduct.name : ''"></h3>
                <p class="text-xs sm:text-sm font-semibold text-[#800000] mt-1">Customize your order</p>
            </div>

            <div class="p-5 sm:p-6 overflow-y-auto scrollbar-hide">
                <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Available Add-ons</p>
                
                <div class="space-y-2.5 sm:space-y-3">
                    <template x-show="selectedProduct && selectedProduct.addOns.length === 0">
                        <p class="text-xs sm:text-sm text-slate-500 italic">No add-ons available for this item.</p>
                    </template>
                    
                    <template x-for="addon in (selectedProduct ? selectedProduct.addOns : [])" :key="addon.name">
                        <label class="flex items-center gap-3 sm:gap-4 border-2 border-slate-100 rounded-xl sm:rounded-2xl p-3 sm:p-4 cursor-pointer transition-all hover:border-rose-200 hover:bg-rose-50/30"
                               :class="tempSelectedAddOns.some(a => a.name === addon.name) ? 'border-[#800000] bg-rose-50/50' : ''">
                            
                            <div class="relative flex items-center justify-center">
                                <input type="checkbox"
                                    class="peer appearance-none w-5 h-5 sm:w-6 sm:h-6 border-2 border-slate-300 rounded-md sm:rounded-lg checked:border-[#800000] checked:bg-[#800000] transition-colors cursor-pointer"
                                    :checked="tempSelectedAddOns.some(a => a.name === addon.name)"
                                    @change="toggleAddon(addon)">
                                <i class="fas fa-check absolute text-white text-[9px] sm:text-[10px] opacity-0 peer-checked:opacity-100 pointer-events-none"></i>
                            </div>

                            <span class="font-bold text-slate-700 text-sm sm:text-base" x-text="addon.name"></span>
                            <span class="ml-auto font-black text-[#800000] bg-white px-2 sm:px-3 py-1 rounded-lg border border-slate-100 shadow-sm text-xs sm:text-sm" x-text="'+' + formatCurrency(addon.price)"></span>
                        </label>
                    </template>
                </div>
            </div>

            <div class="p-4 sm:p-6 border-t border-slate-100 bg-slate-50 flex gap-2 sm:gap-3 shrink-0 pb-6 sm:pb-6">
                <button @click="closeCustomize()" class="flex-1 py-3 sm:py-4 rounded-xl bg-white border-2 border-slate-200 text-slate-600 font-bold uppercase text-[10px] sm:text-xs tracking-wider hover:bg-slate-100 transition-colors">
                    Cancel
                </button>
                <button @click="confirmCustomize()" class="flex-1 py-3 sm:py-4 rounded-xl maroon-gradient text-white font-black uppercase text-[10px] sm:text-xs tracking-wider shadow-lg shadow-maroon/20 hover:shadow-xl transition-all">
                    Save Changes
                </button>
            </div>
        </div>
    </div>
    
    <div id="toast-container" class="fixed top-4 sm:top-6 left-1/2 -translate-x-1/2 z-[999] flex flex-col gap-2 w-[90%] max-w-sm pointer-events-none"></div>
</div>

<script>
    function digitalOrdering() {
        return {
            searchQuery: '',
            selectedCategory: 'All',
            categories: ['All', 'Breakfast', 'Lunch', 'Snacks', 'Dinner', 'Drinks'],
            tableNumber: null,
            cart: [],
            showCustomize: false,
            selectedProduct: null,
            selectedProductQty: 1,
            tempSelectedAddOns: [],
            guestSetup: { adults: 0, children: 0 },
            guestCount: 0,
            products: [],

            defaultProducts() {
                return [
                    { id: 1, name: 'Burger Steak', price: 99, cat: 'Lunch', img: 'burgersteak.png', stock: 24, qty: 1, selectedAddOns: [], addOns: [{ name: 'Extra Rice', price: 20 }, { name: 'Cheese', price: 15 }] },
                    { id: 2, name: 'Tapa & Egg', price: 120, cat: 'Breakfast', img: 'tapa.png', stock: 18, qty: 1, selectedAddOns: [], addOns: [{ name: 'Extra Egg', price: 15 }, { name: 'Garlic Rice', price: 20 }] },
                    { id: 3, name: 'Fries', price: 65, cat: 'Snacks', img: 'fries.png', stock: 30, qty: 1, selectedAddOns: [], addOns: [{ name: 'Cheese Sauce', price: 10 }, { name: 'Bacon Bits', price: 15 }] },
                    { id: 4, name: 'Fried Chicken', price: 150, cat: 'Dinner', img: 'chicken.png', stock: 12, qty: 1, selectedAddOns: [], addOns: [{ name: 'Extra Gravy', price: 10 }, { name: 'Spicy Dip', price: 10 }] },
                    { id: 5, name: 'Ice Tea', price: 45, cat: 'Drinks', img: 'icetea.png', stock: 40, qty: 1, selectedAddOns: [], addOns: [{ name: 'Large Cup', price: 15 }] },
                    { id: 6, name: 'Pancit Canton', price: 115, cat: 'Lunch', img: 'pancit.png', stock: 16, qty: 1, selectedAddOns: [], addOns: [{ name: 'Extra Egg', price: 15 }] },
                    { id: 7, name: 'Latte', price: 95, cat: 'Drinks', img: 'latte.png', stock: 22, qty: 1, selectedAddOns: [], addOns: [{ name: 'Soy Milk', price: 20 }] }
                ];
            },

            loadProducts() {
                const saved = localStorage.getItem('product_catalog');
                if (saved) {
                    try {
                        const parsed = JSON.parse(saved);
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

            initOrder() {
                this.loadProducts();
                const params = new URLSearchParams(window.location.search);
                const tableParam = params.get('table');
                
                if (tableParam) {
                    this.tableNumber = tableParam;
                    localStorage.setItem('customer_order_table', tableParam);
                } else {
                    this.tableNumber = localStorage.getItem('customer_order_table') || null;
                }

                const savedAdults = parseInt(localStorage.getItem('customer_guests_adults'), 10);
                const savedChildren = parseInt(localStorage.getItem('customer_guests_children'), 10);

                let adults = Number.isInteger(savedAdults) ? savedAdults : 0;
                let children = Number.isInteger(savedChildren) ? savedChildren : 0;

                if (adults === 0 && children === 0 && this.tableNumber) {
                    const storedTables = JSON.parse(localStorage.getItem('ub_tables') || '[]');
                    const tableData = storedTables.find(t => t.id === Number(this.tableNumber));
                    if (tableData) {
                        adults = Number.isInteger(tableData.adults) ? tableData.adults : adults;
                        children = Number.isInteger(tableData.children) ? tableData.children : children;
                    }
                }

                this.guestSetup.adults = adults;
                this.guestSetup.children = children;
                this.guestCount = adults + children;
                localStorage.setItem('customer_guests_adults', adults);
                localStorage.setItem('customer_guests_children', children);

                this.loadCart();
            },

            get filteredProducts() {
                return this.products.filter(product => {
                    const matchesSearch = product.name.toLowerCase().includes(this.searchQuery.toLowerCase());
                    const matchesCategory = this.selectedCategory === 'All' || product.cat === this.selectedCategory;
                    return matchesSearch && matchesCategory;
                });
            },

            get cartTotal() {
                return this.cart.reduce((sum, item) => {
                    const addOnsTotal = (item.addOns || []).reduce((a, addon) => a + addon.price, 0);
                    return sum + ((item.price + addOnsTotal) * item.qty);
                }, 0);
            },

            formatCurrency(value) {
                return '₱' + parseFloat(value).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            },

            saveCart() {
                localStorage.setItem('customer_order_cart', JSON.stringify(this.cart));
                localStorage.setItem('customer_order_table', this.tableNumber || '');
            },

            loadCart() {
                const savedCart = localStorage.getItem('customer_order_cart');
                if (savedCart) {
                    try {
                        this.cart = JSON.parse(savedCart) || [];
                    } catch (error) {
                        this.cart = [];
                    }
                }
            },

            addToCart(product) {
                if (product.stock === 0 || !product.qty) return;
                const clone = {
                    id: product.id,
                    name: product.name,
                    price: product.price,
                    img: product.img,
                    qty: Math.min(Math.max(1, product.qty || 1), product.stock),
                    cat: product.cat,
                    addOns: product.selectedAddOns ? [...product.selectedAddOns] : []
                };
                
                const existingIndex = this.cart.findIndex(item => item.id === clone.id && JSON.stringify(item.addOns) === JSON.stringify(clone.addOns));
                
                if (existingIndex !== -1) {
                    this.cart[existingIndex].qty += clone.qty;
                } else {
                    this.cart.push(clone);
                }
                
                this.saveCart();
                this.toast(`Added ${clone.qty}x ${clone.name}`);
                product.qty = 1;
            },

            openCustomizeModal(product) {
                this.selectedProduct = product;
                this.tempSelectedAddOns = product.selectedAddOns ? [...product.selectedAddOns] : [];
                this.showCustomize = true;
                document.body.style.overflow = 'hidden';
            },

            toggleAddon(addon) {
                const index = this.tempSelectedAddOns.findIndex(a => a.name === addon.name);
                if (index !== -1) {
                    this.tempSelectedAddOns.splice(index, 1);
                } else {
                    this.tempSelectedAddOns.push(addon);
                }
            },

            confirmCustomize() {
                if (!this.selectedProduct) return;
                this.selectedProduct.selectedAddOns = [...this.tempSelectedAddOns];
                this.closeCustomize();
                this.toast('Add-ons updated');
            },

            closeCustomize() {
                this.showCustomize = false;
                document.body.style.overflow = '';
            },

            toast(message) {
                const container = document.getElementById('toast-container');
                const toast = document.createElement('div');
                toast.className = 'bg-slate-900 text-white px-4 sm:px-6 py-3 rounded-xl sm:rounded-2xl shadow-xl flex items-center gap-3 transform transition-all duration-300 translate-y-[-100%] opacity-0 pointer-events-auto w-full';
                
                toast.innerHTML = `
                    <div class="w-5 h-5 sm:w-6 sm:h-6 bg-green-500 rounded-full flex items-center justify-center shrink-0">
                        <i class="fas fa-check text-[10px] sm:text-xs text-white"></i>
                    </div>
                    <span class="text-xs sm:text-sm font-bold truncate">${message}</span>
                `;
                
                container.appendChild(toast);
                
                requestAnimationFrame(() => {
                    toast.classList.remove('translate-y-[-100%]', 'opacity-0');
                    toast.classList.add('translate-y-0', 'opacity-100');
                });

                setTimeout(() => {
                    toast.classList.remove('translate-y-0', 'opacity-100');
                    toast.classList.add('translate-y-[-100%]', 'opacity-0');
                    setTimeout(() => toast.remove(), 300);
                }, 2500);
            }
        };
    }
</script>
</body>
</html>