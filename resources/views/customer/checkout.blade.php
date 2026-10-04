<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Checkout | Digital Ordering</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/table-state.js') }}"></script>
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
        <div class="w-full px-4 sm:px-6 lg:px-8 pt-8">
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
    <p class="inline-block rounded-md bg-[#fff4f4] px-2.5 py-1 text-[10px] font-black uppercase tracking-widest text-[#800000]" x-text="item.cat"></p>
    <h3 class="mt-2 text-lg font-black uppercase text-gray-900 leading-tight" x-text="item.name"></h3>
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
                            <div class="flex justify-between text-sm pt-2 font-bold text-gray-600 pb-2 border-b border-gray-100">
                                <span>Subtotal</span>
                                <span x-text="formatCurrency(cartTotal)"></span>
                            </div>
                            <div class="flex justify-between text-sm pt-2 font-bold text-gray-600 pb-2">
                                <span>VAT (5%)</span>
                                <span x-text="formatCurrency(cartTotal * 0.05)"></span>
                            </div>
                            <div class="flex justify-between text-xl pt-2 font-black text-gray-900">
                                <span>Total</span>
                                <span class="text-[#800000]" x-text="formatCurrency(cartTotal * 1.05)"></span>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[2rem] bg-white p-6 shadow-sm border border-gray-200">
                        <h2 class="text-xl font-black uppercase tracking-tight text-gray-900">Payment Method</h2>
                        <div class="mt-5 rounded-[1.5rem] border-2 border-[#800000] bg-[#fff4f4]">
                            <div class="flex items-center gap-4 p-4">
                                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-500 text-white shadow-sm"><i class="fas fa-mobile-screen-button"></i></span>
                                <div>
                                    <p class="font-black uppercase text-gray-900">GCash</p>
                                    <p class="text-xs text-gray-500">GCash payment will be confirmed by staff.</p>
                                </div>
                                <div class="ml-auto text-[#800000]">
                                    <i class="fas fa-check-circle text-xl"></i>
                                </div>
                            </div>
                        </div>
                        <p class="mt-3 text-xs text-gray-500">Online GCash processing is not configured yet. Do not send payment until staff provides instructions.</p>
                    </div>

                    <p x-show="orderError" x-text="orderError" class="rounded-xl bg-red-50 px-4 py-3 text-sm font-bold text-red-700" role="alert"></p>
                    <button @click="placeOrder()" :disabled="isSubmittingOrder || cart.length === 0" class="w-full rounded-full bg-[#800000] py-4 text-base font-black uppercase tracking-wide text-white shadow-lg hover:bg-[#a00000] transition active:scale-[0.98] disabled:opacity-50 disabled:pointer-events-none">
                        <span x-text="isSubmittingOrder ? 'Submitting...' : 'Place Order'"></span>
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
                <p class="mt-2 text-sm text-gray-500">Your order has been submitted. GCash payment is pending staff confirmation; online payment processing is not configured yet.</p>
                
                <div class="mt-6 rounded-[1.5rem] bg-gray-50 p-5 text-left border border-gray-100">
                   <p class="text-[11px] font-black uppercase tracking-widest text-gray-900 mb-4 border-b border-gray-200 pb-3">Receipt Summary</p>
                    <div class="space-y-2">
                        <div class="flex justify-between"><span class="text-sm text-gray-500">Table:</span> <span class="text-sm font-black text-gray-900" x-text="tableNumber ? 'TABLE ' + tableNumber : 'UNASSIGNED'"></span></div>
                        <div class="flex justify-between"><span class="text-sm text-gray-500">Method:</span> <span class="text-sm font-black text-gray-900">GCash (pending confirmation)</span></div>
                        <div class="flex justify-between pb-2 border-b border-gray-200"><span class="text-sm text-gray-500">Subtotal:</span> <span class="text-sm font-black text-gray-900" x-text="formatCurrency(cartTotal)"></span></div>
                        <div class="flex justify-between pb-2"><span class="text-sm text-gray-500">VAT (5%):</span> <span class="text-sm font-black text-gray-900" x-text="formatCurrency(cartTotal * 0.05)"></span></div>
                        <div class="flex justify-between pt-2 border-t border-gray-200 mt-2"><span class="text-base font-bold text-gray-900">Order Total:</span> <span class="text-base font-black text-[#800000]" x-text="formatCurrency(cartTotal * 1.05)"></span></div>
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
                paymentMethod: 'gcash',
                bookingType: 'dine-in',
                orderComplete: false,
                isSubmittingOrder: false,
                orderError: '',
                serverSubtotal: null,

                async loadOrder() {
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
                        const storedTables = await window.tableStateApi.load();
                        const tableData = storedTables.find(t => t.id === Number(this.tableNumber));
                        if (tableData) {
                            adults = Number.isInteger(tableData.adults) ? tableData.adults : adults;
                            children = Number.isInteger(tableData.children) ? tableData.children : children;
                        }
                    }

                    this.guestCount = adults + children;
                },

                get cartTotal() {
                    if (this.serverSubtotal !== null) {
                        return this.serverSubtotal;
                    }

                    return this.cart.reduce((sum, item) => {
                        const addOnsTotal = (item.addOns || []).reduce((a, addon) => a + addon.price, 0);
                        return sum + ((item.price + addOnsTotal) * item.qty);
                    }, 0);
                },

                formatCurrency(value) {
                    return '₱' + parseFloat(value).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                },

                async placeOrder() {
                    if (this.isSubmittingOrder) {
                        return;
                    }
                    if (this.cart.length === 0 || !this.tableNumber) {
                        alert("Please make sure you have items in your cart and a table is assigned.");
                        return;
                    }

                    const tableId = Number(this.tableNumber);
                    if (Number.isNaN(tableId) || tableId <= 0) {
                        return;
                    }

                    this.isSubmittingOrder = true;
                    this.orderError = '';
                    try {
                        let adults = parseInt(localStorage.getItem('customer_guests_adults'), 10);
                        let children = parseInt(localStorage.getItem('customer_guests_children'), 10);

                        adults = Number.isInteger(adults) ? adults : 0;
                        children = Number.isInteger(children) ? children : 0;

                        if (adults === 0 && children === 0) {
                            const storedTables = await window.tableStateApi.load();
                            const tableData = storedTables.find(t => t.id === tableId);
                            if (tableData) {
                                adults = Number.isInteger(tableData.adults) ? tableData.adults : adults;
                                children = Number.isInteger(tableData.children) ? tableData.children : children;
                            }
                        }

                        const orderItems = this.cart.map(item => {
                            const addonName = (item.addOns || []).map(addon => addon.name).join(', ');
                            return {
                                name: item.name,
                                qty: item.qty,
                                price: item.price + (item.addOns || []).reduce((sum, addon) => sum + addon.price, 0),
                                addonName: addonName || 'default'
                            };
                        });

                        const response = await fetch('{{ route('orders.complete') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                table_number: tableId,
                                adults,
                                children,
                                items: this.cart.map(item => ({
                                    product_id: item.id,
                                    quantity: item.qty,
                                    add_ons: (item.addOns || []).map(addon => ({ name: addon.name }))
                                }))
                            })
                        });
                        const result = await response.json().catch(() => ({}));
                        if (!response.ok) {
                            const validationMessage = Object.values(result.errors || {}).flat()[0];
                            throw new Error(validationMessage || result.message || 'Unable to submit your order.');
                        }

                        this.serverSubtotal = Number(result.total_amount);
                        const subtotal = this.cartTotal;
                        this.orderComplete = true;

                        try {
                            const analyticsHistory = JSON.parse(localStorage.getItem('ub_order_history') || '[]');
                            analyticsHistory.unshift({
                                orderId: result.order_number,
                                timestamp: new Date().toISOString(),
                                subtotal,
                                vat: subtotal * 0.05,
                                totalAmount: subtotal * 1.05,
                                tableId,
                                paymentMethod: 'gcash',
                                bookingType: this.bookingType,
                                items: orderItems,
                                status: 'pending'
                            });
                            localStorage.setItem('ub_order_history', JSON.stringify(analyticsHistory));
                            window.dispatchEvent(new Event('storage'));
                        } catch (error) {
                            console.error('Unable to save the local checkout analytics entry:', error);
                        }
                    } catch (error) {
                        this.orderError = error.message || 'Unable to submit your order. Please try again.';
                    } finally {
                        this.isSubmittingOrder = false;
                    }
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