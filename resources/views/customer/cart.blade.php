<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Cart | Digital Ordering</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-white font-sans antialiased">
    <div x-data="customerCart()" x-init="loadCart()" class="min-h-screen pb-10" x-cloak>
        <div class="max-w-[1080px] mx-auto px-4 pt-6">
            <div class="mb-6 rounded-[2rem] bg-white p-5 shadow-lg border border-gray-200">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-3xl font-black uppercase tracking-tight text-gray-900">My Cart</h1>
                    </div>
                    <a href="{{ route('order.menu') }}" class="inline-flex items-center gap-2 rounded-full bg-[#800000] px-5 py-3 text-sm font-black uppercase text-white shadow-lg hover:bg-[#a00000] transition-all">
                        <i class="fas fa-arrow-left"></i> Back to Menu
                    </a>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-[1.6fr_0.9fr]">
                <div class="space-y-5">
                    <template x-if="cart.length === 0">
                        <div class="rounded-[2rem] border border-dashed border-gray-300 bg-white p-10 text-center text-gray-500">
                            <i class="fas fa-shopping-cart text-4xl text-gray-300"></i>
                            <p class="mt-4 text-xl font-black uppercase tracking-[0.25em]">Cart is empty</p>
                            <p class="mt-2 text-sm">Use the menu to add items to your order.</p>
                        </div>
                    </template>

                    <template x-for="(item, index) in cart" :key="index">
                        <div class="rounded-[2rem] bg-white p-5 shadow-sm border border-gray-200">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="h-28 w-28 overflow-hidden rounded-[1.5rem] bg-white p-3 border border-gray-100">
                                        <img :src="'/img/' + item.img" class="h-full w-full object-contain" x-on:error="$el.src='https://placehold.co/400x400/f8fafc/800000?text=No+Image'" />
                                    </div>
                                    <div>
                                        <p class="text-sm uppercase tracking-[0.35em] text-[#800000]" x-text="item.cat"></p>
                                        <h2 class="mt-2 text-xl font-black uppercase tracking-tight text-gray-900" x-text="item.name"></h2>
                                        <p class="mt-2 text-sm text-gray-600" x-text="formatCurrency(item.price)"></p>
                                    </div>
                                </div>
                                <div class="flex flex-col gap-3 sm:items-end">
                                    <div class="flex items-center gap-2 rounded-3xl border border-gray-200 bg-white px-3 py-2">
                                        <button type="button" @click="decrementQty(index)" class="h-10 w-10 rounded-2xl bg-gray-100 text-gray-600 hover:bg-gray-200">-</button>
                                        <span class="w-10 text-center text-sm font-black text-gray-900" x-text="item.qty"></span>
                                        <button type="button" @click="incrementQty(index)" class="h-10 w-10 rounded-2xl bg-gray-100 text-gray-600 hover:bg-gray-200">+</button>
                                    </div>
                                    <div class="flex flex-wrap gap-2 justify-end">
                                        <button @click="editItem(index)" class="inline-flex items-center gap-2 rounded-full border border-[#800000] px-4 py-3 text-sm font-black uppercase text-[#800000] hover:bg-[#fff4f4] transition-all"><i class="fas fa-pen"></i> Edit</button>
                                        <button @click="removeItem(index)" class="inline-flex items-center gap-2 rounded-full border border-red-200 bg-red-50 px-4 py-3 text-sm font-black uppercase text-red-600 hover:bg-red-100 transition-all"><i class="fas fa-trash"></i> Remove</button>
                                    </div>
                                </div>
                            </div>

                            <template x-if="item.addOns && item.addOns.length">
                                <div class="mt-4 rounded-3xl bg-white p-4 text-sm text-gray-700 border border-gray-100">
                                    <p class="font-black uppercase tracking-[0.25em] text-[#800000]">Add-ons</p>
                                    <div class="mt-3 space-y-2">
                                        <template x-for="addon in item.addOns" :key="addon.name">
                                            <p class="flex items-center justify-between text-sm text-gray-700"><span x-text="addon.name"></span><span x-text="formatCurrency(addon.price)"></span></p>
                                        </template>
                                    </div>
                                </div>
                            </template>

                            <div class="mt-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <p class="text-sm uppercase tracking-[0.3em] text-gray-500">Total</p>
                                <p class="text-xl font-black text-[#800000]" x-text="formatCurrency((item.price + (item.addOns || []).reduce((sum, addon) => sum + addon.price, 0)) * item.qty)"></p>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="space-y-5">
                    <div class="rounded-[2rem] bg-white p-5 shadow-lg border border-gray-200">
                        <h2 class="text-xl font-black uppercase tracking-tight text-gray-900">Order Summary</h2>
                        <div class="mt-5 space-y-4">
                            <div class="flex justify-between text-sm text-gray-500"><span>Table</span><span x-text="tableNumber ? 'TABLE ' + tableNumber : 'UNASSIGNED'"></span></div>
                            <div class="flex justify-between text-sm text-gray-500"><span>Items</span><span x-text="cart.length"></span></div>
                            <div class="flex justify-between text-sm text-gray-500"><span>Quantity</span><span x-text="cart.reduce((sum, item) => sum + item.qty, 0)"></span></div>
                            <div class="flex justify-between text-base font-black text-gray-900"><span>Total</span><span x-text="formatCurrency(cartTotal)"></span></div>
                        </div>
                        <button @click="gotoCheckout()" class="mt-6 w-full rounded-3xl bg-[#800000] py-4 text-sm font-black uppercase text-white shadow-lg hover:bg-[#a00000] transition" :disabled="cart.length === 0">Continue to Payment</button>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="showEdit" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4" x-cloak>
            <div class="w-full max-w-md rounded-[2rem] bg-white p-6 shadow-2xl">
                <div class="text-center mb-6 mt-2">
                    <h3 class="text-lg font-extrabold text-[#800000] tracking-wide leading-tight uppercase" x-text="'Customize ' + (selectedCartItem ? selectedCartItem.name : '')"></h3>
                    <p class="text-xs text-gray-500 mt-1">Select add-ons for this item</p>
                </div>

                <div class="space-y-3 max-h-60 overflow-y-auto mb-6 px-2 scrollbar-hide">
                    <template x-for="addon in (selectedCartItem ? selectedCartItem.addOns : [])" :key="addon.name">
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
                    <button @click="closeEdit()" class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-xl font-bold uppercase text-sm">Cancel</button>
                    <button @click="saveEdit()" class="flex-1 bg-[#800000] text-white py-3 rounded-xl font-bold uppercase text-sm transition-all">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function customerCart() {
            return {
                cart: [],
                tableNumber: null,
                showEdit: false,
                selectedCartItem: null,
                selectedCartItemIndex: null,
                selectedCartItemQty: 1,
                tempSelectedAddOns: [],

                loadCart() {
                    const savedCart = localStorage.getItem('customer_order_cart');
                    const savedTable = localStorage.getItem('customer_order_table');
                    if (savedCart) {
                        try {
                            this.cart = JSON.parse(savedCart) || [];
                        } catch (error) {
                            this.cart = [];
                        }
                    }
                    this.tableNumber = savedTable || null;
                },

                saveCart() {
                    localStorage.setItem('customer_order_cart', JSON.stringify(this.cart));
                    localStorage.setItem('customer_order_table', this.tableNumber || '');
                },

                get cartTotal() {
                    return this.cart.reduce((sum, item) => {
                        const addonsTotal = (item.addOns || []).reduce((a, addon) => a + addon.price, 0);
                        return sum + ((item.price + addonsTotal) * item.qty);
                    }, 0);
                },

                formatCurrency(value) {
                    return '₱' + parseFloat(value).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                },

                editItem(index) {
                    this.selectedCartItemIndex = index;
                    this.selectedCartItem = { ...this.cart[index] };
                    this.selectedCartItemQty = this.cart[index].qty;
                    this.tempSelectedAddOns = this.cart[index].addOns ? [...this.cart[index].addOns] : [];
                    this.showEdit = true;
                },

                closeEdit() {
                    this.showEdit = false;
                },

                decrementQty(index) {
                    if (this.cart[index].qty > 1) {
                        this.cart[index].qty -= 1;
                        this.saveCart();
                    }
                },

                incrementQty(index) {
                    this.cart[index].qty += 1;
                    this.saveCart();
                },

                toggleAddon(addon) {
                    const idx = this.tempSelectedAddOns.findIndex(a => a.name === addon.name);
                    if (idx !== -1) {
                        this.tempSelectedAddOns.splice(idx, 1);
                    } else {
                        this.tempSelectedAddOns.push(addon);
                    }
                },

                saveEdit() {
                    if (this.selectedCartItemIndex === null) {
                        return;
                    }
                    const item = this.cart[this.selectedCartItemIndex];
                    item.addOns = [...this.tempSelectedAddOns];
                    this.saveCart();
                    this.showEdit = false;
                },

                removeItem(index) {
                    this.cart.splice(index, 1);
                    this.saveCart();
                },

                gotoCheckout() {
                    if (this.cart.length === 0) {
                        return;
                    }
                    window.location.href = "{{ route('order.checkout') }}";
                }
            };
        }
    </script>
</body>
</html>
