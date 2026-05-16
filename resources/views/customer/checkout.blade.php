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
<body class="bg-gray-50 font-sans antialiased">
    <div x-data="checkoutPage()" x-init="loadOrder()" class="min-h-screen pb-10" x-cloak>
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
            <div class="mb-6 rounded-[2rem] bg-white p-5 shadow-sm border border-gray-200">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-3xl font-black uppercase tracking-tight text-gray-900">Payment</h1>
                    </div>
                    <a href="{{ route('order.cart') }}" class="w-fit inline-flex items-center gap-2 rounded-full bg-[#800000] px-5 py-3 text-sm font-black uppercase text-white shadow hover:bg-[#a00000] transition-all">
    <i class="fas fa-arrow-left"></i> Back to Cart
</a>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-12">
                <div class="lg:col-span-7 xl:col-span-8 space-y-6">
                    <div class="rounded-[2rem] bg-white p-6 shadow-sm border border-gray-200">
                        <h2 class="text-xl font-black uppercase tracking-tight text-gray-900 mb-2">Order Summary</h2>
                        
                        <template x-if="cart.length === 0">
                            <div class="py-8 text-center rounded-2xl bg-gray-50 border-2 border-dashed border-gray-200 mt-4">
                                <p class="text-sm text-gray-500">Your cart is empty. Go back to menu and add items first.</p>
                            </div>
                        </template>

                        <div class="space-y-4 mt-4">
                            <template x-for="item in cart" :key="item.id">
                                <div class="flex flex-col sm:flex-row gap-5 rounded-[1.5rem] border border-gray-100 bg-white p-4 shadow-sm items-start sm:items-center">
                                    
                                    <div class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-2xl bg-gray-50 p-2">
                                        <img :src="'/img/' + item.img" class="h-full w-full object-contain" x-on:error="$el.src='https://placehold.co/400x400/f8fafc/800000?text=No+Image'" />
                                    </div>

                                    <div class="flex flex-1 flex-col w-full">
                                        <div class="flex justify-between items-start w-full">
                                            <div class="pr-4">
                                                <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-[#800000]" x-text="item.cat"></p>
                                                <h3 class="mt-1 text-lg font-black uppercase text-gray-900 leading-tight" x-text="item.name"></h3>
                                                <p class="mt-1 text-sm font-medium text-gray-500" x-text="item.qty + ' x ' + formatCurrency(item.price)"></p>
                                            </div>
                                            <div class="text-right flex-shrink-0">
                                                <p class="text-lg font-black text-[#800000]" x-text="formatCurrency((item.price + (item.addOns || []).reduce((sum, addon) => sum + addon.price, 0)) * item.qty)"></p>
                                            </div>
                                        </div>

                                        <template x-if="item.addOns && item.addOns.length">
                                            <div class="mt-3 rounded-xl bg-gray-50 p-3">
                                                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">Add-ons</p>
                                                <div class="space-y-1.5">
                                                    <template x-for="addon in item.addOns" :key="addon.name">
                                                        <div class="flex justify-between text-sm text-gray-600">
                                                            <span class="font-medium" x-text="'+ ' + addon.name"></span>
                                                            <span x-text="formatCurrency(addon.price * item.qty)"></span>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-5 xl:col-span-4 space-y-6">
                    <div class="rounded-[2rem] bg-white p-6 shadow-sm border border-gray-200">
                        <h2 class="text-xl font-black uppercase tracking-tight text-gray-900">Order Details</h2>
                        <div class="mt-5 space-y-3">
                            <div class="flex justify-between text-sm text-gray-500 pb-3 border-b border-gray-100">
                                <span>Table</span>
                                <span class="font-bold text-gray-900" x-text="tableNumber ? 'TABLE ' + tableNumber : 'UNASSIGNED'"></span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-500 pb-3 border-b border-gray-100">
                                <span>Total Items</span>
                                <span class="font-bold text-gray-900" x-text="cart.length"></span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-500 pb-3 border-b border-gray-100">
                                <span>Total Quantity</span>
                                <span class="font-bold text-gray-900" x-text="cart.reduce((sum, item) => sum + item.qty, 0)"></span>
                            </div>
                            <div class="flex justify-between text-xl pt-2 font-black text-gray-900">
                                <span>Total</span>
                                <span class="text-[#800000]" x-text="formatCurrency(cartTotal)"></span>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[2rem] bg-white p-6 shadow-sm border border-gray-200">
                        <h2 class="text-xl font-black uppercase tracking-tight text-gray-900">Payment Method</h2>
                        <div class="mt-5 space-y-3">
                            <label class="cursor-pointer block rounded-[1.5rem] border-2 transition-all" :class="paymentMethod === 'credit' ? 'border-[#800000] bg-[#fff4f4]' : 'border-gray-100 bg-white hover:border-gray-200'">
                                <input type="radio" name="payment" value="credit" class="hidden" x-model="paymentMethod" />
                                <div class="flex items-center gap-4 p-4">
                                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#800000] text-white shadow-sm"><i class="fas fa-credit-card"></i></span>
                                    <div>
                                        <p class="font-black uppercase text-gray-900">Credit Card</p>
                                        <p class="text-xs text-gray-500">Pay via credit/debit card</p>
                                    </div>
                                    <div class="ml-auto text-[#800000]" x-show="paymentMethod === 'credit'">
                                        <i class="fas fa-check-circle text-xl"></i>
                                    </div>
                                </div>
                            </label>

                            <label class="cursor-pointer block rounded-[1.5rem] border-2 transition-all" :class="paymentMethod === 'gcash' ? 'border-[#800000] bg-[#fff4f4]' : 'border-gray-100 bg-white hover:border-gray-200'">
                                <input type="radio" name="payment" value="gcash" class="hidden" x-model="paymentMethod" />
                                <div class="flex items-center gap-4 p-4">
                                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-500 text-white shadow-sm"><i class="fas fa-mobile-screen-button"></i></span>
                                    <div>
                                        <p class="font-black uppercase text-gray-900">GCash</p>
                                        <p class="text-xs text-gray-500">Pay using e-wallet</p>
                                    </div>
                                    <div class="ml-auto text-[#800000]" x-show="paymentMethod === 'gcash'">
                                        <i class="fas fa-check-circle text-xl"></i>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <button @click="placeOrder()" class="w-full rounded-full bg-[#800000] py-4 text-base font-black uppercase tracking-wide text-white shadow-lg hover:bg-[#a00000] transition active:scale-[0.98]">
                        Place Order
                    </button>
                </div>
            </div>
        </div>

        <div x-show="orderComplete" class="fixed inset-0 z-[999] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4" x-cloak>
            <div class="w-full max-w-md rounded-[2rem] bg-white p-8 shadow-2xl text-center transform transition-all">
                <div class="mb-5 inline-flex h-24 w-24 items-center justify-center rounded-full bg-green-100 text-green-600 mx-auto border-4 border-white shadow-sm">
                    <i class="fas fa-check text-4xl"></i>
                </div>
                <h2 class="text-2xl font-black uppercase text-gray-900">Order Confirmed!</h2>
                <p class="mt-2 text-sm text-gray-500">Your payment has been received. The kitchen will begin preparing your order.</p>
                
                <div class="mt-6 rounded-[1.5rem] bg-gray-50 p-5 text-left border border-gray-100">
                    <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-gray-400 mb-3 border-b border-gray-200 pb-2">Receipt Summary</p>
                    <div class="space-y-2">
                        <div class="flex justify-between"><span class="text-sm text-gray-500">Table:</span> <span class="text-sm font-black text-gray-900" x-text="tableNumber ? 'TABLE ' + tableNumber : 'UNASSIGNED'"></span></div>
                        <div class="flex justify-between"><span class="text-sm text-gray-500">Method:</span> <span class="text-sm font-black text-gray-900" x-text="paymentMethod === 'credit' ? 'Credit Card' : 'GCash'"></span></div>
                        <div class="flex justify-between pt-2 border-t border-gray-200 mt-2"><span class="text-base font-bold text-gray-900">Total Paid:</span> <span class="text-base font-black text-[#800000]" x-text="formatCurrency(cartTotal)"></span></div>
                    </div>
                </div>
                
                <button @click="clearOrder()" class="mt-8 w-full rounded-full bg-[#800000] px-8 py-4 text-sm font-black uppercase tracking-wide text-white shadow-md hover:bg-[#a00000] transition active:scale-95">
                    Close & Return
                </button>
            </div>
        </div>
    </div>

    <script>
        function checkoutPage() {
            return {
                cart: [],
                tableNumber: null,
                guestCount: 0,
                paymentMethod: 'credit',
                bookingType: 'dine-in',
                orderComplete: false,

                loadOrder() {
                    const savedCart = localStorage.getItem('customer_order_cart');
                    const savedTable = localStorage.getItem('customer_order_table');
                    const savedBookingType = localStorage.getItem('customer_booking_type') || 'dine-in';

                    if (savedCart) {
                        try {
                            this.cart = JSON.parse(savedCart) || [];
                        } catch (error) {
                            this.cart = [];
                        }
                    }
                    this.tableNumber = savedTable || null;
                    this.bookingType = savedBookingType;

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

                    this.guestCount = adults + children;
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
                        alert("Please make sure you have items in your cart and a table is assigned.");
                        return;
                    }

                    const tableId = Number(this.tableNumber);
                    if (Number.isNaN(tableId) || tableId <= 0) {
                        return;
                    }

                    let adults = parseInt(localStorage.getItem('customer_guests_adults'), 10);
                    let children = parseInt(localStorage.getItem('customer_guests_children'), 10);

                    adults = Number.isInteger(adults) ? adults : 0;
                    children = Number.isInteger(children) ? children : 0;

                    if (adults === 0 && children === 0) {
                        const storedTables = JSON.parse(localStorage.getItem('ub_tables') || '[]');
                        const tableData = storedTables.find(t => t.id === tableId);
                        if (tableData) {
                            adults = Number.isInteger(tableData.adults) ? tableData.adults : adults;
                            children = Number.isInteger(tableData.children) ? tableData.children : children;
                        }
                    }

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
                    const incomingBill = orderItems.reduce((sum, item) => sum + item.price * item.qty, 0);

                    if (tableIndex === -1) {
                        tables.push({
                            id: tableId,
                            status: 'occupied',
                            adults: adults,
                            children: children,
                            bill: incomingBill,
                            orders: orderItems,
                            startTime: new Date().toISOString()
                        });
                    } else {
                        const table = tables[tableIndex];
                        const isReserved = table.status === 'reserved-advance' || table.status === 'reserved-booking';

                        table.status = isReserved ? table.status : 'occupied';
                        table.adults = adults;
                        table.children = children;
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
                        timestamp: new Date().toISOString(),
                        totalAmount,
                        tableId,
                        paymentMethod: this.paymentMethod,
                        bookingType: this.bookingType,
                        items: this.cart.map(item => ({ name: item.name, qty: item.qty, price: item.price }))
                    };
                    analyticsHistory.unshift(transaction);
                    try {
                        localStorage.setItem('ub_order_history', JSON.stringify(analyticsHistory));
                        console.log('✅ Checkout transaction saved:', transaction);
                        window.dispatchEvent(new Event('storage'));
                    } catch (error) {
                        console.error('❌ Failed to save checkout transaction:', error);
                    }

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