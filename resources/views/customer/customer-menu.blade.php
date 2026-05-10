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
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        [x-cloak] { display: none !important; }
        .maroon-gradient { background: linear-gradient(135deg, #800000 0%, #a00000 100%); }
        .clay-card { box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(255, 255, 255, 0.05); }
    </style>
</head>
<body class="bg-white font-sans antialiased">
    <div x-data="digitalOrdering()" x-init="initOrder()" class="min-h-screen pb-28" x-cloak>
        <div class="max-w-[1080px] mx-auto px-4 pt-6">
            <div class="mb-6 rounded-[2rem] bg-white p-5 shadow-lg border border-gray-200">
                <div class="flex flex-col gap-4 sm:gap-6">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-black uppercase tracking-[0.3em] text-slate-500">Menu</p>
                        </div>
                        <div class="text-right">
                            <div class="inline-flex items-center gap-3 rounded-full border border-[#f1d6d6] bg-[#fff4f4] px-4 py-2 text-sm font-black uppercase text-[#800000] shadow-sm">
                                <i class="fas fa-table text-sm"></i>
                                <span x-text="tableNumber ? 'TABLE ' + tableNumber : 'UNASSIGNED'"></span>
                            </div>
                        </div>
                    </div>

                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-6 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                        <input type="text" x-model="searchQuery" placeholder="Search menu items..." class="w-full rounded-2xl border border-gray-200 bg-white py-5 pl-16 pr-6 text-base font-bold text-gray-700 outline-none shadow-sm focus:ring-4 focus:ring-[#800000]/10 focus:border-[#800000] transition-all" />
                    </div>

                    <div class="flex justify-start lg:justify-center flex-wrap gap-3 w-full mt-1 overflow-x-auto pb-4 scrollbar-hide px-1">
                        <template x-for="cat in categories" :key="cat">
                            <button type="button" @click="selectedCategory = cat" :class="selectedCategory === cat ? 'bg-[#800000] text-white shadow-md border-[#800000]' : 'bg-white text-gray-500 border-gray-200 hover:border-gray-300 hover:bg-gray-50'" class="px-6 py-3.5 rounded-2xl text-sm font-black uppercase border whitespace-nowrap transition-all tracking-wide" x-text="cat"></button>
                        </template>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 xl:grid-cols-3">
                <template x-for="product in filteredProducts" :key="product.id">
                    <div class="bg-white p-5 flex flex-col relative border border-gray-200 rounded-[2rem] shadow-sm hover:shadow-lg transition-all duration-300">
                        <div class="w-full h-44 mb-4 overflow-hidden rounded-[2rem] bg-[#fdfdfd] border border-gray-100 flex items-center justify-center">
                            <img :src="'/img/' + product.img" class="w-full h-full object-contain p-4" x-on:error="$el.src='https://placehold.co/400x400/f8fafc/800000?text=No+Image'" />
                        </div>

                        <div class="mb-4 flex-grow">
                            <h4 class="text-base font-black text-gray-800 uppercase tracking-tight leading-tight mb-1" x-text="product.name"></h4>

                            <div class="mb-2 flex flex-wrap gap-1 min-h-[20px]">
                                <template x-if="product.selectedAddOns && product.selectedAddOns.length > 0">
                                    <template x-for="addon in product.selectedAddOns" :key="addon.name">
                                        <span class="bg-orange-100 text-orange-700 text-[9px] font-black px-2 py-0.5 rounded-full uppercase border border-orange-200 animate__animated animate__fadeIn">
                                            + <span x-text="addon.name"></span>
                                        </span>
                                    </template>
                                </template>
                            </div>

                            <div class="space-y-3">
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-xl font-black text-[#800000]" x-text="formatCurrency(product.price)"></span>
                                    <div class="inline-flex items-center gap-2 rounded-2xl border border-gray-200 bg-slate-50 px-2 py-2">
                                        <button type="button" @click="product.qty = Math.max(1, product.qty - 1)" class="h-8 w-8 rounded-2xl bg-white text-[#800000] font-black transition hover:bg-[#f3d0d0]">-</button>
                                        <input type="number" x-model.number="product.qty" min="1" @change="product.qty = Math.max(1, product.qty || 1)" class="w-14 bg-transparent text-center text-sm font-black text-gray-700 outline-none" />
                                        <button type="button" @click="product.qty++" class="h-8 w-8 rounded-2xl bg-white text-[#800000] font-black transition hover:bg-[#f3d0d0]">+</button>
                                    </div>
                                </div>
                                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider">Quantity</div>
                            </div>
                        </div>

                        <div class="mt-auto space-y-3 pt-4 border-t border-gray-100">
                            <button type="button" @click="openCustomizeModal(product)" class="flex items-center justify-between w-full bg-gray-50 border border-gray-200 rounded-xl p-3 hover:bg-gray-100 transition-colors">
                                <span class="text-[11px] font-bold text-[#800000] uppercase tracking-wider">Customize Add-ons</span>
                                <i class="fas fa-plus text-[#800000] text-xs"></i>
                            </button>
                            <button type="button" @click="addToCart(product)" class="w-full py-4 bg-[#800000] text-white rounded-xl text-sm font-black uppercase shadow-md active:scale-[0.98] transition-transform">Add to Order</button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <a href="{{ route('order.cart') }}" class="fixed bottom-6 right-6 z-50 inline-flex items-center gap-3 rounded-full bg-[#800000] px-5 py-4 text-white shadow-2xl hover:bg-[#a00000] transition-all">
            <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-white text-[#800000] shadow-sm">
                <i class="fas fa-shopping-cart"></i>
            </span>
            <div class="text-left">
                <p class="text-[10px] uppercase tracking-[0.3em] text-white/80">View Cart</p>
                <p class="text-sm font-black" x-text="cart.length + ' item(s)'"></p>
            </div>
        </a>

        <div x-show="showCustomize" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4" x-cloak>
            <div class="w-full max-w-md rounded-[2rem] bg-white p-6 shadow-2xl">
                <div class="text-center mb-6 mt-2">
                    <h3 class="text-lg font-extrabold text-[#800000] tracking-wide leading-tight uppercase" x-text="'Customize ' + (selectedProduct ? selectedProduct.name : '')"></h3>
                    <p class="text-xs text-gray-500 mt-1">Select add-ons for this item</p>
                </div>

                <div class="space-y-3 max-h-60 overflow-y-auto mb-6 px-2 scrollbar-hide">
                    <template x-for="addon in (selectedProduct ? selectedProduct.addOns : [])" :key="addon.name">
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
                    <button @click="closeCustomize()" class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-xl font-bold uppercase text-sm">Cancel</button>
                    <button @click="confirmCustomize()" class="flex-1 bg-[#800000] text-white py-3 rounded-xl font-bold uppercase text-sm transition-all">Confirm</button>
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

                products: [
                    { id: 1, name: 'Burger Steak', price: 99, cat: 'Lunch', img: 'burgersteak.png', stock: 24, qty: 1, selectedAddOns: [], addOns: [{ name: 'Extra Rice', price: 20 }, { name: 'Cheese', price: 15 }] },
                    { id: 2, name: 'Tapa & Egg', price: 120, cat: 'Breakfast', img: 'tapa.png', stock: 18, qty: 1, selectedAddOns: [], addOns: [{ name: 'Extra Egg', price: 15 }, { name: 'Garlic Rice', price: 20 }] },
                    { id: 3, name: 'Fries', price: 65, cat: 'Snacks', img: 'fries.png', stock: 30, qty: 1, selectedAddOns: [], addOns: [{ name: 'Cheese Sauce', price: 10 }, { name: 'Bacon Bits', price: 15 }] },
                    { id: 4, name: 'Fried Chicken', price: 150, cat: 'Dinner', img: 'chicken.png', stock: 12, qty: 1, selectedAddOns: [], addOns: [{ name: 'Extra Gravy', price: 10 }, { name: 'Spicy Dip', price: 10 }] },
                    { id: 5, name: 'Ice Tea', price: 45, cat: 'Drinks', img: 'icetea.png', stock: 40, qty: 1, selectedAddOns: [], addOns: [{ name: 'Large Cup', price: 15 }] },
                    { id: 6, name: 'Pancit Canton', price: 115, cat: 'Lunch', img: 'pancit.png', stock: 16, qty: 1, selectedAddOns: [], addOns: [{ name: 'Extra Egg', price: 15 }] },
                    { id: 7, name: 'Latte', price: 95, cat: 'Drinks', img: 'latte.png', stock: 22, qty: 1, selectedAddOns: [], addOns: [{ name: 'Soy Milk', price: 20 }] }
                ],

                initOrder() {
                    const params = new URLSearchParams(window.location.search);
                    const tableParam = params.get('table');
                    if (tableParam) {
                        this.tableNumber = tableParam;
                        localStorage.setItem('customer_order_table', tableParam);
                    } else {
                        this.tableNumber = localStorage.getItem('customer_order_table') || null;
                    }

                    // Check if guest setup is complete
                    const savedAdults = localStorage.getItem('customer_guests_adults');
                    const savedChildren = localStorage.getItem('customer_guests_children');

                    if (!savedAdults || !savedChildren || (parseInt(savedAdults) === 0 && parseInt(savedChildren) === 0)) {
                        // Redirect to setup if not configured
                        const setupUrl = "{{ route('order.setup', ':table') }}".replace(':table', this.tableNumber || '1');
                        window.location.href = setupUrl;
                        return;
                    }

                    this.loadCart();
                    this.guestSetup.adults = parseInt(savedAdults) || 0;
                    this.guestSetup.children = parseInt(savedChildren) || 0;
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
