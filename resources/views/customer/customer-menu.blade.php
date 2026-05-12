<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Menu | Digital Ordering</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        [x-cloak] { display: none !important; }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        body {
            background: #f8f8f8;
        }

        .glass-card {
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.6);
            box-shadow: 0 10px 40px rgba(0,0,0,0.06);
        }

        .menu-card {
            transition: all .3s ease;
        }

        .menu-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        }

        .maroon-gradient {
            background: linear-gradient(135deg, #6b0000 0%, #900000 100%);
        }
    </style>
</head>

<body class="font-sans antialiased text-slate-800">

<div x-data="digitalOrdering()" x-init="initOrder()" class="min-h-screen pb-32" x-cloak>

    <!-- CONTAINER -->
    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-10 2xl:px-12 pt-8">

        <!-- HEADER -->
        <div class="glass-card rounded-[2rem] p-6 sm:p-8 mb-8">

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

                <div>
                    <p class="text-xs font-black tracking-[0.35em] uppercase text-slate-400">
                        Digital Ordering
                    </p>
                    <h1 class="text-3xl sm:text-4xl font-black text-[#800000] tracking-tight mt-2">
                        Customer Menu
                    </h1>
                </div>

                <div class="inline-flex items-center gap-3 bg-[#fff5f5] rounded-3xl px-6 py-3 shadow-sm border border-[#f3d6d6]">
                    <i class="fas fa-table text-[#800000]"></i>
                    <span class="font-black text-[#800000] text-sm tracking-wide"
                          x-text="tableNumber ? 'TABLE ' + tableNumber : 'UNASSIGNED'">
                    </span>
                </div>
            </div>

            <!-- SEARCH -->
            <div class="mt-6 relative">
                <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input
                    x-model="searchQuery"
                    type="text"
                    placeholder="Search menu items..."
                    class="w-full rounded-2xl border border-slate-200 bg-white py-4 pl-14 pr-5 text-sm font-semibold outline-none focus:ring-4 focus:ring-[#800000]/10 focus:border-[#800000] transition"
                >
            </div>

            <!-- CATEGORIES -->
            <div class="flex gap-3 overflow-x-auto scrollbar-hide mt-6 pb-2">
                <template x-for="cat in categories" :key="cat">
                    <button
                        @click="selectedCategory = cat"
                        :class="selectedCategory === cat
                            ? 'bg-[#800000] text-white border-[#800000]'
                            : 'bg-white text-slate-500 border-slate-200 hover:border-slate-300'"
                        class="px-6 py-3 rounded-2xl border text-xs font-black uppercase tracking-wider whitespace-nowrap transition-all">
                        <span x-text="cat"></span>
                    </button>
                </template>
            </div>
        </div>

        <!-- PRODUCTS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 xl:gap-8">

            <template x-for="product in filteredProducts" :key="product.id">
                <div class="menu-card bg-white rounded-[2rem] p-6 shadow-xl flex flex-col border border-slate-200/70">

                    <!-- IMAGE -->
                    <div class="h-52 rounded-[1.7rem] overflow-hidden bg-slate-50 border border-slate-100 flex items-center justify-center mb-5">
                        <img
                            :src="'/img/' + product.img"
                            class="w-full h-full object-contain p-5"
                            x-on:error="$el.src='https://placehold.co/400x400/f8fafc/800000?text=No+Image'"
                        >
                    </div>

                    <!-- INFO -->
                    <div class="flex-1">
                        <h3 class="font-black text-lg uppercase tracking-tight text-slate-800"
                            x-text="product.name"></h3>

                        <!-- ADDONS -->
                        <div class="flex flex-wrap gap-2 mt-3 min-h-[24px]">
                            <template x-if="product.selectedAddOns && product.selectedAddOns.length > 0">
                                <template x-for="addon in product.selectedAddOns" :key="addon.name">
                                    <span class="text-[10px] px-2 py-1 rounded-full bg-orange-50 text-orange-700 border border-orange-200 font-black uppercase">
                                        + <span x-text="addon.name"></span>
                                    </span>
                                </template>
                            </template>
                        </div>

                        <!-- PRICE -->
                        <div class="mt-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <span class="text-2xl font-black text-[#800000]"
                                  x-text="formatCurrency(product.price)">
                            </span>

                            <div class="inline-flex items-stretch rounded-2xl overflow-hidden border border-slate-200 bg-white shadow-sm">
                                <button 
                                    @click="product.qty = Math.max(1, product.qty - 1)" 
                                    class="w-11 h-11 flex items-center justify-center text-slate-500 hover:bg-slate-50 transition border-r border-slate-200">
                                    <i class="fas fa-minus text-sm"></i>
                                </button>

                                <div class="w-14 h-11 flex items-center justify-center font-black text-slate-700">
                                    <span class="text-sm" x-text="product.qty"></span>
                                </div>

                                <button 
                                    @click="product.qty++" 
                                    class="w-11 h-11 flex items-center justify-center text-slate-500 hover:bg-slate-50 transition border-l border-slate-200">
                                    <i class="fas fa-plus text-sm"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- ACTIONS -->
                    <div class="space-y-3 mt-6 pt-5 border-t border-slate-100">

                        <button
                            @click="openCustomizeModal(product)"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 px-4 py-3 text-xs font-black uppercase tracking-wider text-[#800000] text-center">
                            CUSTOMIZE ADDS-ON
                        </button>

                        <button
                            @click="addToCart(product)"
                            class="w-full maroon-gradient text-white py-4 rounded-xl font-black uppercase tracking-wider shadow-lg active:scale-[0.98] transition">
                            Add to Order
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- FLOATING CART -->
    <a href="{{ route('order.cart') }}"
       class="fixed bottom-6 right-6 z-50 rounded-full maroon-gradient px-5 py-4 shadow-2xl flex items-center gap-4">

        <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center text-[#800000]">
            <i class="fas fa-shopping-cart"></i>
        </div>

        <div>
            <p class="text-[10px] uppercase tracking-[0.25em] text-white/70">
                View Cart
            </p>
            <p class="text-sm font-black text-white" x-text="cart.length + ' item(s)'"></p>
        </div>
    </a>

    <!-- CUSTOMIZE MODAL -->
    <div x-show="showCustomize"
         class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4"
         x-cloak>

        <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-md p-6">

            <div class="text-center mb-6">
                <h3 class="text-xl font-black text-[#800000] uppercase"
                    x-text="'Customize ' + (selectedProduct ? selectedProduct.name : '')">
                </h3>
                <p class="text-sm text-slate-400 mt-1">Select your add-ons</p>
            </div>

            <div class="space-y-3 max-h-72 overflow-y-auto scrollbar-hide">
                <template x-for="addon in (selectedProduct ? selectedProduct.addOns : [])" :key="addon.name">
                    <label class="flex items-center gap-3 border border-slate-200 rounded-xl p-4 hover:bg-slate-50 cursor-pointer">
                        <input
                            type="checkbox"
                            class="accent-[#800000] w-5 h-5"
                            :checked="tempSelectedAddOns.some(a => a.name === addon.name)"
                            @change="toggleAddon(addon)"
                        >

                        <span class="font-semibold text-sm" x-text="addon.name"></span>

                        <span class="ml-auto font-black text-[#800000]"
                              x-text="'+₱' + addon.price"></span>
                    </label>
                </template>
            </div>

            <div class="grid grid-cols-2 gap-3 mt-6">
                <button
                    @click="closeCustomize()"
                    class="py-3 rounded-xl bg-slate-200 font-black uppercase text-sm">
                    Cancel
                </button>

                <button
                    @click="confirmCustomize()"
                    class="py-3 rounded-xl maroon-gradient text-white font-black uppercase text-sm">
                    Confirm
                </button>
            </div>
        </div>
    </div>
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

                    // Load guest counts if available, otherwise fall back to table reservations
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
                    return '₱' + parseFloat(value).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                },

                incrementQty(product) {
                    product.qty = (product.qty || 1) + 1;
                },

                decrementQty(product) {
                    if (product.qty > 1) {
                        product.qty -= 1;
                    }
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
                    const clone = {
                        id: product.id,
                        name: product.name,
                        price: product.price,
                        img: product.img,
                        qty: product.qty || 1,
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
                },

                openCustomizeModal(product) {
                    this.selectedProduct = product;
                    this.selectedProductQty = product.qty || 1;
                    this.tempSelectedAddOns = product.selectedAddOns ? [...product.selectedAddOns] : [];
                    this.showCustomize = true;
                },

                toggleAddon(addon) {
                    const index = this.tempSelectedAddOns.findIndex(a => a.name === addon.name);
                    if (index !== -1) {
                        this.tempSelectedAddOns.splice(index, 1);
                    } else {
                        this.tempSelectedAddOns.push(addon);
                    }
                },

                decrementSelectedQty() {
                    if (this.selectedProductQty > 1) {
                        this.selectedProductQty -= 1;
                    }
                },

                incrementSelectedQty() {
                    this.selectedProductQty += 1;
                },

                confirmCustomize() {
                    if (!this.selectedProduct) {
                        return;
                    }
                    this.selectedProduct.selectedAddOns = [...this.tempSelectedAddOns];
                    this.selectedProduct.qty = this.selectedProductQty;
                    this.showCustomize = false;
                },

                closeCustomize() {
                    this.showCustomize = false;
                },

                gotoCart() {
                    window.location.href = "{{ route('order.cart') }}";
                },

                toast(message) {
                    const toast = document.createElement('div');
                    toast.textContent = message;
                    toast.className = 'fixed bottom-24 right-6 z-[999] rounded-full bg-[#800000] px-5 py-3 text-sm font-bold uppercase tracking-[0.2em] text-white shadow-2xl';
                    document.body.appendChild(toast);
                    setTimeout(() => toast.remove(), 2000);
                }
            };
        }
    </script>
</body>
</html>
