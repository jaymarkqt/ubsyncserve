<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | Digital Ordering</title>
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
    <div x-data="checkoutPage()" x-init="loadOrder()" class="min-h-screen pb-10" x-cloak>
        <div class="max-w-[1080px] mx-auto px-4 pt-6">
            <div class="mb-6 rounded-[2rem] bg-white p-5 shadow-lg border border-gray-200">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-3xl font-black uppercase tracking-tight text-gray-900">Payment</h1>
                    </div>
                    <a href="{{ route('order.cart') }}" class="inline-flex items-center gap-2 rounded-full bg-[#800000] px-5 py-3 text-sm font-black uppercase text-white shadow-lg hover:bg-[#a00000] transition-all">
                        <i class="fas fa-arrow-left"></i> Back to Cart
                    </a>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-[2rem] bg-white p-5 shadow-lg border border-gray-200">
                    <h2 class="text-xl font-black uppercase tracking-tight text-gray-900">Order Summary</h2>
                    <template x-if="cart.length === 0">
                        <p class="mt-4 text-sm text-gray-500">Your cart is empty. Go back to menu and add items first.</p>
                    </template>
                    <template x-for="item in cart" :key="item.id">
                            <div class="mt-5 flex flex-col gap-4 rounded-[2rem] border border-gray-100 bg-white p-4 sm:flex-row sm:items-center">
                            <div class="h-28 w-28 overflow-hidden rounded-[1.75rem] bg-white p-3">
                                <img :src="'/img/' + item.img" class="h-full w-full object-contain" x-on:error="$el.src='https://placehold.co/400x400/f8fafc/800000?text=No+Image'" />
                            </div>
                            <div class="flex-1">
                                <p class="text-xs uppercase tracking-[0.35em] text-[#800000]" x-text="item.cat"></p>
                                <h3 class="mt-2 text-lg font-black uppercase text-gray-900" x-text="item.name"></h3>
                                <p class="mt-2 text-sm text-gray-600" x-text="item.qty + ' x ' + formatCurrency(item.price)"></p>
                                <template x-if="item.addOns && item.addOns.length">
                                    <div class="mt-3 space-y-2 rounded-3xl bg-white p-3 text-sm text-gray-700">
                                        <template x-for="addon in item.addOns" :key="addon.name">
                                            <p class="flex justify-between"><span x-text="addon.name"></span><span x-text="formatCurrency(addon.price)"></span></p>
                                        </template>
                                    </div>
                                </template>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-black text-[#800000]" x-text="formatCurrency((item.price + (item.addOns || []).reduce((sum, addon) => sum + addon.price, 0)) * item.qty)"></p>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="rounded-[2rem] bg-white p-5 shadow-lg border border-gray-200">
                    <h2 class="text-xl font-black uppercase tracking-tight text-gray-900">Order Details</h2>
                    <div class="mt-5 space-y-4">
                        <div class="flex justify-between text-sm text-gray-500"><span>Table</span><span x-text="tableNumber ? 'TABLE ' + tableNumber : 'UNASSIGNED'"></span></div>
                        <div class="flex justify-between text-sm text-gray-500"><span>Items</span><span x-text="cart.length"></span></div>
                        <div class="flex justify-between text-sm text-gray-500"><span>Quantity</span><span x-text="cart.reduce((sum, item) => sum + item.qty, 0)"></span></div>
                        <div class="flex justify-between text-base font-black text-gray-900"><span>Total</span><span x-text="formatCurrency(cartTotal)"></span></div>
                    </div>
                </div>

                <div class="rounded-[2rem] bg-white p-5 shadow-lg border border-gray-200">
                    <h2 class="text-xl font-black uppercase tracking-tight text-gray-900">Payment Method</h2>
                    <div class="mt-5 grid gap-4 sm:grid-cols-2">
                        <label class="cursor-pointer block rounded-[1.75rem] border border-gray-200 p-4 transition-all" :class="paymentMethod === 'credit' ? 'border-[#800000] bg-[#fff4f4]' : ''">
                            <input type="radio" name="payment" value="credit" class="hidden" x-model="paymentMethod" />
                            <div class="flex items-center gap-3">
                                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#800000] text-white"><i class="fas fa-credit-card"></i></span>
                                <div>
                                    <p class="font-black uppercase text-gray-900">Credit Card</p>
                                    <p class="text-sm text-gray-500">Pay with card details.</p>
                                </div>
                            </div>
                        </label>
                        <label class="cursor-pointer block rounded-[1.75rem] border border-gray-200 p-4 transition-all" :class="paymentMethod === 'gcash' ? 'border-[#800000] bg-[#fff4f4]' : ''">
                            <input type="radio" name="payment" value="gcash" class="hidden" x-model="paymentMethod" />
                            <div class="flex items-center gap-3">
                                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#800000] text-white"><i class="fas fa-mobile-screen-button"></i></span>
                                <div>
                                    <p class="font-black uppercase text-gray-900">GCash</p>
                                    <p class="text-sm text-gray-500">Pay through GCash.</p>
                                </div>
                            </div>
                        </label>
                    </div>

                    <div class="mt-6 space-y-4">
                        <template x-if="paymentMethod === 'credit'">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs uppercase tracking-[0.3em] text-gray-500">Cardholder Name</label>
                                    <input type="text" x-model="payment.credit.cardName" class="mt-2 w-full rounded-3xl border border-gray-200 bg-[#fcf6f6] p-4 outline-none focus:ring-4 focus:ring-[#f9e4e4]" placeholder="Juan dela Cruz" />
                                </div>
                                <div>
                                    <label class="block text-xs uppercase tracking-[0.3em] text-gray-500">Card Number</label>
                                    <input type="text" x-model="payment.credit.cardNumber" class="mt-2 w-full rounded-3xl border border-gray-200 bg-[#fcf6f6] p-4 outline-none focus:ring-4 focus:ring-[#f9e4e4]" placeholder="1234 5678 9012 3456" />
                                </div>
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <label class="block text-xs uppercase tracking-[0.3em] text-gray-500">Expiry</label>
                                        <input type="text" x-model="payment.credit.expiry" class="mt-2 w-full rounded-3xl border border-gray-200 bg-[#fcf6f6] p-4 outline-none focus:ring-4 focus:ring-[#f9e4e4]" placeholder="MM/YY" />
                                    </div>
                                    <div>
                                        <label class="block text-xs uppercase tracking-[0.3em] text-gray-500">CVV</label>
                                        <input type="text" x-model="payment.credit.cvv" class="mt-2 w-full rounded-3xl border border-gray-200 bg-[#fcf6f6] p-4 outline-none focus:ring-4 focus:ring-[#f9e4e4]" placeholder="123" />
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template x-if="paymentMethod === 'gcash'">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs uppercase tracking-[0.3em] text-gray-500">GCash Mobile Number</label>
                                    <input type="text" x-model="payment.gcash.mobile" class="mt-2 w-full rounded-3xl border border-gray-200 bg-[#fcf6f6] p-4 outline-none focus:ring-4 focus:ring-[#f9e4e4]" placeholder="09XX XXX XXXX" />
                                </div>
                                <div>
                                    <label class="block text-xs uppercase tracking-[0.3em] text-gray-500">Reference Number</label>
                                    <input type="text" x-model="payment.gcash.reference" class="mt-2 w-full rounded-3xl border border-gray-200 bg-[#fcf6f6] p-4 outline-none focus:ring-4 focus:ring-[#f9e4e4]" placeholder="Transaction ID" />
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <button @click="placeOrder()" class="w-full rounded-3xl bg-[#800000] py-4 text-sm font-black uppercase text-white shadow-lg hover:bg-[#a00000] transition">Place Order</button>
            </div>
        </div>

        <div x-show="orderComplete" class="fixed inset-0 z-[999] flex items-center justify-center bg-black/50 p-4" x-cloak>
            <div class="w-full max-w-lg rounded-[2rem] bg-white p-6 shadow-2xl text-center">
                <div class="mb-5 inline-flex h-20 w-20 items-center justify-center rounded-full bg-green-100 text-green-700 mx-auto">
                    <i class="fas fa-check text-3xl"></i>
                </div>
                <h2 class="text-2xl font-black text-gray-900">Order Confirmed!</h2>
                <p class="mt-3 text-gray-500">Your payment has been received. The kitchen will begin preparing your order.</p>
                <div class="mt-6 rounded-[1.75rem] bg-[#f8f3f3] p-5 text-left">
                    <p class="text-sm uppercase tracking-[0.3em] text-gray-400">Summary</p>
                    <p class="mt-3 text-sm text-gray-700"><span class="font-black">Table:</span> <span x-text="tableNumber ? 'TABLE ' + tableNumber : 'UNASSIGNED'"></span></p>
                    <p class="mt-2 text-sm text-gray-700"><span class="font-black">Payment:</span> <span x-text="paymentMethod === 'credit' ? 'Credit Card' : 'GCash'"></span></p>
                    <p class="mt-2 text-sm text-gray-700"><span class="font-black">Total Paid:</span> <span x-text="formatCurrency(cartTotal)"></span></p>
                </div>
                <button @click="clearOrder()" class="mt-6 rounded-3xl bg-[#800000] px-8 py-4 text-sm font-bold uppercase text-white">Close</button>
            </div>
        </div>
    </div>

    <script>
        function checkoutPage() {
            return {
                cart: [],
                tableNumber: null,
                paymentMethod: 'credit',
                payment: {
                    credit: { cardName: '', cardNumber: '', expiry: '', cvv: '' },
                    gcash: { mobile: '', reference: '' }
                },
                orderComplete: false,

                loadOrder() {
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

                get cartTotal() {
                    return this.cart.reduce((sum, item) => {
                        const addOnsTotal = (item.addOns || []).reduce((a, addon) => a + addon.price, 0);
                        return sum + ((item.price + addOnsTotal) * item.qty);
                    }, 0);
                },

                formatCurrency(value) {
                    return '₱' + parseFloat(value).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                },

                placeOrder() {
                    if (this.cart.length === 0 || !this.tableNumber) {
                        return;
                    }

                    const tableId = Number(this.tableNumber);
                    if (Number.isNaN(tableId) || tableId <= 0) {
                        return;
                    }

                    const savedAdults = parseInt(localStorage.getItem('customer_guests_adults')) || 0;
                    const savedChildren = parseInt(localStorage.getItem('customer_guests_children')) || 0;

                    const orderItems = this.cart.map(item => {
                        const addOnTotal = (item.addOns || []).reduce((sum, addon) => sum + addon.price, 0);
                        const addonName = (item.addOns || []).map(addon => addon.name).join(', ');
                        return {
                            name: item.name,
                            qty: item.qty,
                            price: item.price + addOnTotal,
                            addonName: addonName || 'default'
                        };
                    });

                    let tables = JSON.parse(localStorage.getItem('ub_tables') || '[]');
                    let tableIndex = tables.findIndex(t => t.id === tableId);

                    if (tableIndex === -1) {
                        tables.push({
                            id: tableId,
                            status: 'occupied',
                            adults: savedAdults,
                            children: savedChildren,
                            bill: orderItems.reduce((sum, item) => sum + item.price * item.qty, 0),
                            orders: orderItems,
                            startTime: new Date().toISOString()
                        });
                    } else {
                        const table = tables[tableIndex];
                        table.status = 'occupied';
                        table.adults = savedAdults;
                        table.children = savedChildren;
                        table.startTime = table.startTime || new Date().toISOString();
                        table.orders = Array.isArray(table.orders) ? table.orders.concat(orderItems) : orderItems;
                        table.bill = (table.orders || []).reduce((sum, item) => sum + item.price * item.qty, 0);
                    }

                    localStorage.setItem('ub_tables', JSON.stringify(tables));

                    const catalog = JSON.parse(localStorage.getItem('product_catalog') || '[]');
                    this.cart.forEach(item => {
                        const product = catalog.find(p => p.id === item.id);
                        if (product) {
                            product.stock = Math.max(0, (product.stock || 0) - item.qty);
                        }
                    });
                    localStorage.setItem('product_catalog', JSON.stringify(catalog));
                    window.dispatchEvent(new Event('storage'));

                    const analyticsHistory = JSON.parse(localStorage.getItem('ub_order_history') || '[]');
                    const totalAmount = this.cartTotal;
                    const transaction = {
                        orderId: 'ORD-' + Date.now(),
                        timestamp: new Date().toLocaleString(),
                        totalAmount,
                        tableId,
                        paymentMethod: this.paymentMethod,
                        items: this.cart.map(item => ({ name: item.name, qty: item.qty }))
                    };
                    analyticsHistory.unshift(transaction);
                    localStorage.setItem('ub_order_history', JSON.stringify(analyticsHistory));

                    this.orderComplete = true;
                },

                clearOrder() {
                    localStorage.removeItem('customer_order_cart');
                    localStorage.removeItem('customer_order_table');
                    localStorage.removeItem('customer_guests_adults');
                    localStorage.removeItem('customer_guests_children');
                    window.location.href = "{{ route('order.qrcodes') }}";
                }
            };
        }
    </script>
</body>
</html>
