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
<body class="bg-gray-50 font-sans antialiased text-gray-900">
    <div x-data="customerCart()" x-init="loadCart()" class="min-h-screen pb-20 sm:pb-10" x-cloak>
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 sm:pt-10">
            
            <div class="mb-6 rounded-[2rem] bg-white p-5 shadow-sm border border-gray-200">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-3xl font-black uppercase tracking-tight text-gray-900">My Cart</h1>
                        <p class="text-sm text-gray-500 mt-1" x-text="cart.length + ' items in your order'"></p>
                    </div>
                    <a href="{{ route('order.menu') }}" class="w-fit inline-flex items-center gap-2 rounded-full bg-[#800000] px-5 py-3 text-sm font-black uppercase text-white shadow hover:bg-[#a00000] transition-all">
                        <i class="fas fa-arrow-left"></i> Back to Menu
                    </a>
                </div>
            </div>

            <div class="lg:grid lg:grid-cols-12 lg:items-start lg:gap-8">
                
                <div class="lg:col-span-8 space-y-4 sm:space-y-6">
                    
                    <template x-if="cart.length === 0">
                        <div class="rounded-3xl border border-gray-200 bg-white p-12 text-center text-gray-500 shadow-sm">
                            <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-gray-50 mb-4">
                                <i class="fas fa-shopping-cart text-3xl text-gray-400"></i>
                            </div>
                            <p class="text-xl font-black uppercase tracking-widest text-gray-700">Cart is empty</p>
                            <p class="mt-2 text-sm text-gray-500">Craving something? Use the menu to add items.</p>
                            <a href="{{ route('order.menu') }}" class="mt-6 inline-block rounded-full bg-[#800000] px-8 py-3 text-sm font-bold uppercase text-white shadow hover:bg-[#a00000] transition-colors">
                                Browse Menu
                            </a>
                        </div>
                    </template>

                    <template x-for="(item, index) in cart" :key="index">
                        <div class="rounded-3xl bg-white p-5 shadow-sm border border-gray-100 transition-all hover:shadow-md">
                            <div class="flex flex-col sm:flex-row gap-5">
                                
                                <div class="flex items-start gap-4 flex-1">
                                    <div class="h-24 w-24 sm:h-28 sm:w-28 shrink-0 overflow-hidden rounded-2xl bg-gray-50 p-2 border border-gray-100 flex items-center justify-center">
                                        <img :src="'/img/' + item.img" class="h-full w-full object-contain drop-shadow-sm" x-on:error="$el.src='https://placehold.co/400x400/f8fafc/800000?text=No+Image'" />
                                    </div>
                                    <div class="flex flex-col justify-center py-1">
                                        <span class="inline-block px-2 py-1 mb-2 rounded-md bg-red-50 text-[10px] font-black uppercase tracking-widest text-[#800000] w-fit" x-text="item.cat"></span>
                                        <h2 class="text-lg sm:text-xl font-black uppercase tracking-tight text-gray-900 leading-tight" x-text="item.name"></h2>
                                        <p class="mt-1 text-lg font-bold text-[#800000]" x-text="formatCurrency(item.price)"></p>
                                    </div>
                                </div>

                                <div class="flex flex-col sm:items-end justify-between gap-4 border-t border-gray-50 sm:border-t-0 pt-4 sm:pt-0">
                                    
                                    <div class="flex items-center justify-between sm:justify-end gap-4 w-full">
                                        <button @click="removeItem(index)" class="h-10 w-10 flex items-center justify-center rounded-full text-gray-400 text-red-500 transition-colors" aria-label="Remove item">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>

                                        <div class="inline-flex items-center rounded-xl overflow-hidden border border-gray-200 bg-white h-10">
                                            <button @click="decrementQty(index)" class="w-10 h-full flex items-center justify-center text-gray-500 hover:bg-gray-50 active:bg-gray-100 transition-colors border-r border-gray-200">
                                                <i class="fas fa-minus text-xs"></i>
                                            </button>
                                            <div class="w-12 h-full flex items-center justify-center bg-gray-50 font-black text-gray-800 text-sm">
                                                <span x-text="item.qty"></span>
                                            </div>
                                            <button @click="incrementQty(index)" class="w-10 h-full flex items-center justify-center text-gray-500 hover:bg-gray-50 active:bg-gray-100 transition-colors border-l border-gray-200">
                                                <i class="fas fa-plus text-xs"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <button @click="editItem(index)" class="w-full sm:w-auto rounded-xl border border-gray-200 bg-white hover:border-[#800000] hover:text-[#800000] px-4 py-2.5 text-xs font-bold uppercase tracking-wider text-black-600 transition-colors shadow-sm">
                                        <i class="fas fa-sliders-h mr-1.5"></i> Customize
                                    </button>
                                </div>
                            </div>

                            <template x-if="item.addOns && item.addOns.length">
                                <div class="mt-4 rounded-2xl bg-gray-50 p-4 border border-gray-100">
                                    <p class="text-xs font-black uppercase tracking-widest text-gray-500 mb-2">Selected Add-ons</p>
                                    <div class="space-y-1.5">
                                        <template x-for="addon in item.addOns" :key="addon.name">
                                            <div class="flex items-center justify-between text-sm text-gray-700">
                                                <span class="flex items-center gap-2">
                                                    <i class="fas fa-check text-[#800000] text-[10px]"></i>
                                                    <span x-text="addon.name"></span>
                                                </span>
                                                <span class="font-bold text-gray-900" x-text="'+' + formatCurrency(addon.price)"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                            
                           <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between items-center">
                                <span class="text-sm font-bold text-black-500 uppercase">Subtotal</span>
                                <span class="text-xl font-black text-gray-900" x-text="formatCurrency((item.price + (item.addOns || []).reduce((sum, addon) => sum + addon.price, 0)) * item.qty)"></span>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="lg:col-span-4 mt-8 lg:mt-0 lg:sticky lg:top-6">
                    <div class="rounded-3xl bg-white p-6 sm:p-8 shadow-lg border border-gray-100">
                        <h2 class="text-xl font-black uppercase tracking-tight text-gray-900 mb-6">Order Summary</h2>
                        
                       <div class="space-y-4 mb-6">
    <div class="flex justify-between items-center px-4 py-3.5 rounded-xl bg-gray-50 border border-gray-100">
        <span class="text-black-500 text-sm font-bold uppercase tracking-widest">Table</span>
        <span class="text-base font-black text-[#800000]" x-text="tableNumber ? '#' + tableNumber : 'N/A'"></span>
    </div>
    
    <div class="flex justify-between text-sm text-gray-600 px-4">
        <span>Total Items</span>
        <span class="font-bold text-black-900" x-text="cart.length"></span>
    </div>
    
    <div class="flex justify-between text-sm text-gray-600 px-4">
        <span>Total Quantity</span>
        <span class="font-bold text-black-900" x-text="cart.reduce((sum, item) => sum + item.qty, 0)"></span>
    </div>
</div>

                        <div class="border-t border-gray-200 pt-4 mb-6 flex justify-between items-end">
                            <span class="text-base font-black uppercase text-gray-900">Total Amount</span>
                            <span class="text-3xl font-black text-[#800000]" x-text="formatCurrency(cartTotal)"></span>
                        </div>
                        
                        <button @click="gotoCheckout()" class="w-full rounded-2xl bg-[#800000] py-4 px-6 text-sm font-black uppercase tracking-widest text-white shadow-xl shadow-[#800000]/20 hover:bg-[#a00000] hover:-translate-y-0.5 active:translate-y-0 transition-all disabled:opacity-50 disabled:pointer-events-none" :disabled="cart.length === 0">
                            Proceed to Payment
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="showEdit" style="display: none;" class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4">
          <div x-show="showEdit" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
            
            <div x-show="showEdit" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-8 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-8 sm:scale-95"
                 class="relative w-full max-w-md bg-white rounded-t-[2rem] sm:rounded-[2rem] p-6 sm:p-8 shadow-2xl flex flex-col max-h-[85vh]">
                
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-black text-gray-900 tracking-tight uppercase" x-text="'Customize'"></h3>
                        <p class="text-sm text-[#800000] font-bold mt-0.5" x-text="selectedCartItem ? selectedCartItem.name : ''"></p>
                    </div>
                    
                </div>

                <div class="flex-1 overflow-y-auto mb-6 pr-1 scrollbar-hide space-y-3">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3" x-show="selectedCartItem && selectedCartItem.addOns.length">Available Add-ons</p>
                    
                    <template x-for="addon in (selectedCartItem ? selectedCartItem.addOns : [])" :key="addon.name">
                        <label class="flex items-center bg-white rounded-xl p-4 cursor-pointer border-2 transition-all"
                               :class="tempSelectedAddOns.some(a => a.name === addon.name) ? 'border-[#800000] bg-red-50/30' : 'border-gray-100 hover:border-gray-300'">
                            
                            <div class="relative flex items-center justify-center w-6 h-6 mr-4 rounded-md border-2 transition-colors"
                                 :class="tempSelectedAddOns.some(a => a.name === addon.name) ? 'border-[#800000] bg-[#800000]' : 'border-gray-300'">
                                <input type="checkbox" class="opacity-0 absolute inset-0 cursor-pointer"
                                       :checked="tempSelectedAddOns.some(a => a.name === addon.name)"
                                       @change="toggleAddon(addon)">
                                <i class="fas fa-check text-white text-xs" x-show="tempSelectedAddOns.some(a => a.name === addon.name)"></i>
                            </div>
                            
                            <span class="text-sm font-bold text-gray-800" x-text="addon.name"></span>
                            <span class="text-sm font-black text-[#800000] ml-auto" x-text="'+' + formatCurrency(addon.price)"></span>
                        </label>
                    </template>
                </div>

                <div class="flex gap-3 mt-auto pt-4 border-t border-gray-100">
                    <button @click="closeEdit()" class="w-1/3 bg-gray-100 text-gray-700 py-3.5 rounded-xl font-black uppercase text-xs tracking-wider hover:bg-gray-200 transition-colors">Cancel</button>
                    <button @click="saveEdit()" class="w-2/3 bg-[#800000] text-white py-3.5 rounded-xl font-black uppercase text-xs tracking-wider shadow-lg hover:bg-[#a00000] transition-colors">Save Changes</button>
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
                    setTimeout(() => {
                        this.selectedCartItem = null;
                        this.tempSelectedAddOns = [];
                    }, 300); // Wait for transition
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
                    if (this.selectedCartItemIndex === null) return;
                    
                    const item = this.cart[this.selectedCartItemIndex];
                    item.addOns = [...this.tempSelectedAddOns];
                    this.saveCart();
                    this.closeEdit();
                },

                removeItem(index) {
                   
                        this.cart.splice(index, 1);
                        this.saveCart();
                    
                },

                gotoCheckout() {
                    if (this.cart.length === 0) return;
                    window.location.href = "{{ route('order.checkout') }}";
                }
            };
        }
    </script>
</body>
</html>