<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Command Center | UB-SYNC</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/table-state.js') }}"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-image: url("{{ asset('img/backgroundmenu.png') }}"); background-size: cover; background-position: center; background-attachment: fixed; color: #334155; overflow-x: hidden; }
        .aws-header { background-color: #800000; height: 65px; display: flex; align-items: center; justify-content: space-between; padding: 0 25px; color: white; position: fixed; top: 0; width: 100%; z-index: 1000; }
        .gold-accent { background-color: #D4AF37; height: 4px; position: fixed; top: 65px; width: 100%; z-index: 999; }
        .aws-sidebar { width: 260px; background: white; border-right: 1px solid #eaeded; height: calc(100vh - 69px); position: fixed; top: 69px; left: 0; transition: all 0.3s ease; z-index: 1000; }
        .sidebar-collapsed { left: -260px; }
        .main-content { margin-left: 320px; margin-top: 69px; margin-right: 60px; padding: 30px; transition: all 0.3s ease; min-height: calc(100vh - 69px); background: transparent; }
        .main-content.content-wide { margin-left: 60px; margin-right: 60px; }
        
        @media (max-width: 768px) {
            .main-content { margin-left: 0 !important; padding: 15px; width: 100%; }
            .aws-sidebar { box-shadow: 10px 0 15px rgba(0,0,0,0.1); z-index: 1001; }
        }

        .clay-card { background: white; border: 1px solid #f1f5f9; border-radius: 16px; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); transition: all 0.3s ease; }
        .custom-scroll::-webkit-scrollbar { width: 4px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #3e0101; border-radius: 10px; }
        .table-card-occupied { background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); }
        .table-card-available { background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); }
        .modal-overlay { position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 1050; }
        [x-cloak] { display: none !important; }
    </style>
</head>

<body x-data="managerDashboard()" x-init="init()">

    <header class="aws-header">
        <div class="flex items-center gap-4">
            <button @click="sidebarOpen = !sidebarOpen" class="hover:bg-white/20 p-2 rounded transition cursor-pointer">
                <i class="fas fa-bars"></i>
            </button>

        </div>

        <div class="flex items-center gap-6 text-sm font-bold">
            <div class="relative" x-data="{ open: false }" @click.away="open = false">
                <button @click="open = !open" class="flex items-center gap-3 border-l border-white/20 pl-6 h-full hover:bg-white/5 p-2 rounded transition-all focus:outline-none">
                    <div class="hidden md:block text-right">
                        <span class="text-[10px] text-white/60 block leading-none uppercase tracking-widest font-black">Admin</span>
                        <p class="font-bold text-white uppercase text-sm tracking-tight">{{ Auth::user()->name ?? 'Manager Name' }}</p>
                    </div>
                    <div class="relative">
                        <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-xs">M</div>
                        <div class="absolute -bottom-0.5 -right-0.5 bg-emerald-500 w-2.5 h-2.5 rounded-full border-2 border-[#800000]"></div>
                    </div>
                </button>

                <div x-show="open" x-cloak class="absolute right-0 mt-3 w-56 bg-white rounded-xl shadow-xl py-2 z-[1100] border border-slate-200 overflow-hidden text-slate-800">
                    <div class="px-4 py-3 bg-slate-50 border-b border-slate-100 mb-1">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-0.5">Role: Manager</p>
                        <p class="text-xs font-bold text-slate-800 truncate">{{ Auth::user()->email ?? 'manager@ub.edu.ph' }}</p>
                    </div>
                    <div class="px-2">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left px-3 py-2.5 text-[11px] font-black text-red-600 hover:bg-red-50 rounded-lg uppercase tracking-widest flex items-center gap-3">
                                <i class="fa-solid fa-power-off text-sm"></i> Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="gold-accent"></div>

    <aside class="aws-sidebar shadow-sm" :class="!sidebarOpen ? 'sidebar-collapsed' : ''">
        <div class="p-4 space-y-2">
            <button @click="tab = 'analytics'" 
                class="w-full flex items-center gap-3 p-3 rounded-xl transition-all text-sm"
                :class="tab === 'analytics' ? 'bg-red-50 text-[#800000] font-black' : 'text-slate-500 hover:bg-slate-50 font-bold'">
                <i class="fas fa-chart-pie w-5"></i> Sales Analytics
            </button>

            <button @click="tab = 'tables'" 
                class="w-full flex items-center gap-3 p-3 rounded-xl transition-all text-sm"
                :class="tab === 'tables' ? 'bg-red-50 text-[#800000] font-black' : 'text-slate-500 hover:bg-slate-50 font-bold'">
                <i class="fas fa-door-open w-5"></i> Open Tables
            </button>

            <button @click="tab = 'inventory'" 
                class="w-full flex items-center gap-3 p-3 rounded-xl transition-all text-sm"
                :class="tab === 'inventory' ? 'bg-red-50 text-[#800000] font-black' : 'text-slate-500 hover:bg-slate-50 font-bold'">
                <i class="fas fa-box-open w-5"></i> Inventory
            </button>


            <button @click="tab = 'reservations'"
    class="w-full flex items-center gap-3 p-3 rounded-xl transition-all text-sm"
    :class="tab === 'reservations' ? 'bg-red-50 text-[#800000] font-black' : 'text-slate-500 hover:bg-slate-50 font-bold'">
    <i class="fas fa-calendar-check w-5"></i> Reservations
</button>

            <button @click="tab = 'productsales'"
                class="w-full flex items-center gap-3 p-3 rounded-xl transition-all text-sm"
                :class="tab === 'productsales' ? 'bg-red-50 text-[#800000] font-black' : 'text-slate-500 hover:bg-slate-50 font-bold'">
                <i class="fas fa-bag-shopping w-5"></i> Product Sales
            </button>

        </div>
    </aside>

    <main class="main-content" :class="!sidebarOpen ? 'content-wide' : ''">
        <div x-show="tab === 'analytics'" x-cloak>
            @include('manager.analytics')
        </div>

        <div x-show="tab === 'tables'" x-cloak>
            @include('manager.open-tables')
        </div>

        <div x-show="tab === 'inventory'" x-cloak>
            @include('manager.inventory')
        </div>

        <div x-show="tab === 'reservations'" x-cloak>
    @include('manager.reservation-booking')
</div>

        <div x-show="tab === 'productsales'" x-cloak>
            @include('manager.product-sales')
        </div>

    </main>

    <script>
        function managerDashboard() {
            return {
                sidebarOpen: true,
                tab: @json($activeTab ?? 'analytics'),
                selectedTable: null,
                showAddModal: false,
                showOrderModal: false,
                showReservedModal: false,
                showAdvanceOrderModal: false,
                showCompleteOrderModal: false,
                showSetupModal: false,
                editingProductId: null,
                isSavingProduct: false,
                deletingProductId: null,
                productFormError: '',
                reservations: [],
               voidOrderIndex: null,
               voidCodeInput: '',
               managerCode: '1234',
               salesDateFilter: 'today',
               currentReceiptOrderId: '',

                // Analytics Data
                salesSummary: { total: 0 },
               orderHistory: [],
                
                // Open Tables Data
             openTables: [],
                tableSyncError: '',
                isRefreshingDashboard: false,
                isClearingTable: false,
                
                // Inventory Data
                formData: { id: null, name: '', cost: 0, sellingPrice: 0, stock: 0, img: '', addOns: [], ingredients: [] },
                products: [],

                normalizeProducts(products) {
                    return products.map(product => ({
                        ...product,
                        price: product.price ?? product.sellingPrice ?? 0,
                        addOns: product.addOns || [],
                        ingredients: product.ingredients || []
                    }));
                },

                async loadProducts() {
                    const response = await fetch('{{ route('products.index') }}', {
                        headers: { 'Accept': 'application/json' }
                    });

                    if (!response.ok) {
                        throw new Error('Unable to load products.');
                    }

                    this.products = this.normalizeProducts(await response.json());
                },

                async saveProductToDatabase(product) {
                    const isEditing = product.id !== null && product.id !== undefined;
                    const url = isEditing
                        ? `{{ url('/api/products') }}/${product.id}`
                        : '{{ route('products.store') }}';
                    const response = await fetch(url, {
                        method: isEditing ? 'PUT' : 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(product)
                    });

                    const result = await response.json().catch(() => ({}));
                    if (!response.ok) {
                        const validationMessage = Object.values(result.errors || {}).flat()[0];
                        throw new Error(validationMessage || result.message || 'Unable to save product.');
                    }

                    return result;
                },

                resetForm() {
                    this.formData = { id: null, name: '', cost: 0, sellingPrice: 0, stock: 0, img: '', addOns: [], ingredients: [] };
                },

                formatCurrency(val) {
                    return '₱' + parseFloat(val || 0).toLocaleString(undefined, {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                },

                selectedDiscountSummary() {
                    const discountedItems = (this.selectedTable?.orders || []).filter(item => item.discountType);
                    const types = [...new Set(discountedItems.map(item => item.discountType))];

                    return {
                        hasDiscount: types.length > 0,
                        label: types.map(type => type === 'senior' ? 'Senior Citizen' : 'PWD').join(' / '),
                        amount: discountedItems.reduce((total, item) => total + Number(item.discountAmount || 0), 0)
                    };
                },

                selectedGrossSubtotal() {
                    return Number(this.selectedTable?.bill || 0) + this.selectedDiscountSummary().amount;
                },
              
     

      loadAnalytics() {
    const storedHistory = localStorage.getItem('ub_order_history');

    this.orderHistory = storedHistory
        ? JSON.parse(storedHistory)
        : [];

    this.salesSummary.total = this.orderHistory.reduce(
        (sum, order) => sum + Number(order.totalAmount),
        0
    );
},

async loadTablesFromStorage() {
                try {
                    const tables = await window.tableStateApi.load();
                    if (this.isClearingTable) {
                        return;
                    }

                    const reservations = JSON.parse(localStorage.getItem('ub_reservations') || '[]');

                    // Process each table
                    const updatedTables = tables.map(t => {
                        const tableOrders = t.orders || [];
                        const calculatedBill = Number(t.bill || 0);
                        const status = ['occupied', 'paid', 'reserved-advance', 'reserved-booking'].includes(t.status)
                            ? t.status
                            : (tableOrders.length > 0 ? 'occupied' : 'available');

                        const matchedReservation = reservations.find(r => r.table == t.id)
                            || reservations.find(r => r.status === 'pending' && ((status === 'reserved-advance' && r.type === 'advance-order') || (status === 'reserved-booking' && r.type === 'table-reservation')));

                        const adults = t.adults ?? (matchedReservation ? matchedReservation.adults || 0 : 0);
                        const children = t.children ?? (matchedReservation ? matchedReservation.children || 0 : 0);
                        const guests = (t.guests || adults + children);

                        return {
                            id: t.id,
                            tableNumber: t.id,
                            status: status,
                            isPaid: t.isPaid || false,
                            adults: adults,
                            children: children,
                            guests: guests,
                            duration: this.getDuration(t),
                            orders: tableOrders,
                            bill: calculatedBill
                        };
                    });
                    if (JSON.stringify(updatedTables) !== JSON.stringify(this.openTables)) {
                        this.openTables = updatedTables;
                    }
                    this.tableSyncError = '';
                } catch (error) {
                    this.tableSyncError = error.message;
                    console.error('Unable to synchronize the manager floorplan:', error);
                }
                },

                async loadReservationsFromStorage() {
                    try {
                        const response = await fetch('/bookings', {
                            headers: { 'Accept': 'application/json' }
                        });

                        if (response.ok) {
                            this.reservations = await response.json();
                            return;
                        }
                    } catch (error) {
                        console.warn('Unable to load database bookings:', error);
                    }

                    const stored = localStorage.getItem('ub_reservations');
                    this.reservations = stored ? JSON.parse(stored) : [];
                },




async updateReservationStatus(id, newStatus) {
    if(!confirm(`Are you sure you want to mark this reservation as ${newStatus}?`)) return;
    
    let index = this.reservations.findIndex(r => r.id === id);
    if (index === -1) {
        return;
    }

    const reservation = this.reservations[index];
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const statusResponse = await fetch(`/bookings/${encodeURIComponent(id)}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: newStatus })
        });

        const result = await statusResponse.json().catch(() => ({}));
        if (!statusResponse.ok) {
            await this.loadReservationsFromStorage();
            alert(result.message || 'This reservation has already been processed.');
            return;
        }

        reservation.status = newStatus;
        localStorage.setItem('ub_reservations', JSON.stringify(this.reservations));
        await this.loadReservationsFromStorage();

        if (newStatus !== 'confirmed') {
            return;
        }

        const emailResponse = await fetch('/reservation/confirm-email', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                id: reservation.id,
                name: reservation.name,
                email: reservation.email,
                date: reservation.date,
                time: reservation.time,
                type: reservation.type,
                table: reservation.table
            })
        });

        const emailResult = await emailResponse.json().catch(() => ({ success: false }));
        if (!emailResponse.ok || !emailResult.success) {
            alert(emailResult.message || 'Reservation confirmed, but the confirmation email could not be sent.');
            return;
        }

        alert('Reservation confirmed! The table-selection link was sent to the customer.');
    } catch (error) {
        console.warn('Email send failed:', error);
    }
},

async deleteReservation(id) {
    if(!confirm('Are you sure you want to delete this reservation record?')) return;

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const response = await fetch(`/bookings/${encodeURIComponent(id)}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
    });

    if (!response.ok) {
        alert('Unable to remove the reservation from the active list.');
        return;
    }

    this.reservations = this.reservations.filter(r => r.id !== id);
    localStorage.setItem('ub_reservations', JSON.stringify(this.reservations));
    await this.loadReservationsFromStorage();
},

async clearAllReservations() {
    if (!confirm('Remove all reservations from the active list? The database records will be kept.')) return;

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const response = await fetch('/bookings', {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
    });

    if (!response.ok) {
        alert('Unable to clear the active reservations.');
        return;
    }

    this.reservations = [];
    localStorage.setItem('ub_reservations', JSON.stringify(this.reservations));
    await this.loadReservationsFromStorage();
},

async cancelReservation(id) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const response = await fetch(`/bookings/${encodeURIComponent(id)}/status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ status: 'cancelled' })
    });

    if (!response.ok) {
        alert('Unable to cancel this reservation.');
        return;
    }

    this.reservations = this.reservations.filter(r => r.id !== id);
    localStorage.setItem('ub_reservations', JSON.stringify(this.reservations));
    await this.loadReservationsFromStorage();
},

formatTime(time) {
    if (!time) return '';
    const [hours, minutes] = time.split(':');
    let h = parseInt(hours);
    const ampm = h >= 12 ? 'PM' : 'AM';
    h = h % 12 || 12;
    return `${h}:${minutes} ${ampm}`;
},



getDuration(table) {
    if (!table.startTime) return '';

    let start = new Date(table.startTime);
    let now = new Date();

    let diff = Math.floor((now - start) / 60000);

    if (diff < 60) return diff + 'm';

    return Math.floor(diff / 60) + 'h ' + (diff % 60) + 'm';
},


handleTableClick(table) {
    if (table.status === 'available' && !table.isPaid) {
        return;
    }

    this.selectedTable = table;
    if (table.status === 'reserved-advance') {
        this.showAdvanceOrderModal = true;
        this.showOrderModal = false;
        this.showReservedModal = false;
    } else if (table.status === 'occupied' || table.status === 'paid' || table.isPaid || (table.orders && table.orders.length > 0)) {
        this.showOrderModal = true;
        this.showAdvanceOrderModal = false;
        this.showReservedModal = false;
    } else if (table.status === 'reserved-booking') {
        this.showReservedModal = true;
        this.showAdvanceOrderModal = false;
        this.showOrderModal = false;
    }
},


async clearTable(tableId) {
    if (this.isClearingTable) {
        return;
    }

    const tableIndex = this.openTables.findIndex(table => Number(table.id) === Number(tableId));
    if (tableIndex === -1) {
        alert('Unable to clear this table because it could not be found.');
        return;
    }

    const table = this.openTables[tableIndex];
    const clearedTable = {
        id: Number(table.id),
        status: 'available',
        isPaid: false,
        adults: 0,
        children: 0,
        guests: 0,
        bill: 0,
        startTime: null,
        orders: []
    };

    this.isClearingTable = true;
    this.openTables.splice(tableIndex, 1, {
        ...table,
        ...clearedTable,
        tableNumber: table.id,
        duration: ''
    });
    this.showOrderModal = false;
    this.showReservedModal = false;
    this.showAdvanceOrderModal = false;
    this.selectedTable = null;

    try {
        await window.tableStateApi.clear(clearedTable.id);

        const kitchenOrders = JSON.parse(localStorage.getItem('ub_kitchen_orders') || '[]');
        localStorage.setItem(
            'ub_kitchen_orders',
            JSON.stringify(kitchenOrders.filter(order => order.table != tableId))
        );
    } catch (error) {
        console.error('Unable to clear the paid table:', error);
        this.isClearingTable = false;
        alert(error.message || 'Unable to clear the paid table.');
        await this.loadTablesFromStorage();
    } finally {
        this.isClearingTable = false;
    }
},

               

                openVoidModal(index) {
                    this.voidOrderIndex = index;
                    this.voidCodeInput = ''; // I-clear ang input field
                    this.showVoidModal = true;
                },

                // 2. Kapag pinindot ang Cancel sa Security Check Modal
                cancelVoid() {
                    this.showVoidModal = false;
                    this.voidOrderIndex = null;
                    this.voidCodeInput = '';
                },

                // 3. Iche-check ang PIN bago burahin
                async confirmVoidOrder() {
                    if (this.voidCodeInput.trim() === '') {
                        alert('Please enter manager PIN.');
                        return;
                    }

                    if (this.voidCodeInput.trim() !== this.managerCode) {
                        alert('Invalid PIN. Void cancelled.');
                        return;
                    }

                    // Kung tama ang PIN, ituloy ang pagbura
                    await this.voidOrder(this.voidOrderIndex);
                    this.cancelVoid(); // Isara ang Security Modal pagkatapos
                },

                // 4. Ang mismong function na magbubura ng order sa database/localStorage
                async voidOrder(index) {
                    if (!this.selectedTable) return;

                        let tables = await window.tableStateApi.load();
                        let tableIndex = tables.findIndex(t => t.id == this.selectedTable.tableNumber);

                        if (tableIndex !== -1) {
                            const table = tables[tableIndex];
                            // Burahin yung order base sa index
                            table.orders.splice(index, 1);

                            table.bill = table.orders.reduce(
                                (sum, item) => sum + (item.price * item.qty) - Number(item.discountAmount || 0),
                                0
                            );

                            // Kung naubos na ang order ng table, gawin ulit 'available' ang table at isara ang Order Modal
                            if (table.orders.length === 0) {
                                table.status = 'available';
                                table.adults = 0;
                                table.children = 0;
                                table.guests = 0;
                                table.isPaid = false;
                                table.bill = 0;
                                table.startTime = null;
                                this.showOrderModal = false;
                            }

                            // I-save pabalik sa storage at i-refresh ang tables
                            await window.tableStateApi.save([table]);
                            await this.loadTablesFromStorage();
                        }
                },



                calculateMargin(cost, price) {
                    if(!cost || !price || cost == 0) return 0;
                    return Math.round(((price - cost) / cost) * 100);
                },

                selectTable(table) {
                    this.selectedTable = table;
                    console.log('Selected table:', table);
                },

                openAddProduct() {
                    this.productFormError = '';
                    this.editingProductId = null;
                    this.resetForm();
                    this.showAddModal = true;
                },

                editProduct(productId) {
                    const product = this.products.find(item => item.id === productId);
                    if (!product) {
                        this.productFormError = 'This product is no longer available. Refresh the inventory and try again.';
                        return;
                    }

                    this.productFormError = '';
                    this.showAddModal = false;
                    this.editingProductId = product.id;
                    this.formData = {
                        ...product,
                        addOns: (product.addOns || []).map(addOn => ({ ...addOn })),
                        ingredients: (product.ingredients || []).map(ingredient => ({ ...ingredient }))
                    };
                },

                async saveProduct() {
                    if (this.isSavingProduct) {
                        return;
                    }

                    this.productFormError = '';
                    const productToSave = {
                        ...this.formData,
                        name: this.formData.name.trim(),
                        ingredients: (this.formData.ingredients || []).filter(ingredient => ingredient.name?.trim()),
                        addOns: (this.formData.addOns || []).filter(addOn => addOn.name?.trim())
                    };

                    if (!productToSave.name) {
                        this.productFormError = 'Product name is required.';
                        return;
                    }

                    this.isSavingProduct = true;

                    try {
                        await this.saveProductToDatabase(productToSave);
                        await this.loadProducts();
                        this.closeModal();
                    } catch (error) {
                        this.productFormError = error.message || 'Unable to save product.';
                    } finally {
                        this.isSavingProduct = false;
                    }
                },

                async deleteProduct(productId) {
                    const product = this.products.find(item => item.id === productId);
                    if (!product || this.deletingProductId !== null) {
                        return;
                    }

                    if (confirm(`Delete ${product.name}?`)) {
                        this.deletingProductId = productId;

                        try {
                            const response = await fetch(`{{ url('/api/products') }}/${productId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json'
                                }
                            });

                            const result = await response.json().catch(() => ({}));
                            if (!response.ok) {
                                throw new Error(result.message || 'Unable to delete product.');
                            }

                            await this.loadProducts();
                        } catch (error) {
                            alert(error.message || 'Unable to delete product.');
                        } finally {
                            this.deletingProductId = null;
                        }
                    }
                },

                handleImageUpload(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (e) => { this.formData.img = e.target.result; };
                        reader.readAsDataURL(file);
                    }
                },

                closeModal() {
                    this.showAddModal = false;
                    this.editingProductId = null;
                    this.productFormError = '';
                    this.resetForm();
                },

                addAddOn() {
                    this.formData.addOns = this.formData.addOns || [];
                    this.formData.addOns.push({ name: '', price: 0 });
                },

                removeAddOn(index) {
                    this.formData.addOns.splice(index, 1);
                },

                addIngredient() {
                    this.formData.ingredients = this.formData.ingredients || [];
                    this.formData.ingredients.push({ name: '', stock: 0 });
                },

                removeIngredient(index) {
                    this.formData.ingredients.splice(index, 1);
                },

                calculateStockFromIngredients(product) {
                    return Number(product.stock || 0);
                },

                getLowStockIngredients(product) {
                    if (!product.ingredients) return [];
                    return product.ingredients.filter(ing => ing.stock <= 10);
                },

                resetForm() {
                    this.formData = { id: null, name: '', cost: 0, sellingPrice: 0, stock: 0, img: '', addOns: [], ingredients: [] };
                },

async init() {
            try {
await this.loadProducts();
            } catch (error) {
                alert(error.message);
            }
            this.loadAnalytics();
            await this.loadTablesFromStorage();
            this.loadReservationsFromStorage();

            window.addEventListener('storage', () => {
                this.loadAnalytics();
                this.loadTablesFromStorage();
                this.loadReservationsFromStorage();
            });

            setInterval(async () => {
                if (this.isRefreshingDashboard || this.isClearingTable || document.hidden) {
                    return;
                }

                this.isRefreshingDashboard = true;
                try {
                    this.loadAnalytics();
                    await this.loadTablesFromStorage();
                } finally {
                    this.isRefreshingDashboard = false;
                }
            }, 2000);

            setInterval(() => {
                if (!document.hidden) {
                    this.loadReservationsFromStorage();
                }
            }, 8000);
        },
        

                
                // Analytics Metrics
                get metrics() {
                    let totalOrders = this.orderHistory ? this.orderHistory.length : 0;
                    let avgOrder = totalOrders > 0 ? (this.salesSummary.total / totalOrders) : 0;
                    
                    let itemCounts = {};
                    this.orderHistory.forEach(order => {
                        if(order.items && Array.isArray(order.items)) {
                            order.items.forEach(item => {
                                itemCounts[item.name] = (itemCounts[item.name] || 0) + item.qty;
                            });
                        }
                    });
                    
                    let topItems = Object.keys(itemCounts).map(name => {
                        let product = this.products.find(p => p.name === name);
                        let imgPath = product && product.img ? (product.img.includes('data:') || product.img.includes('http') ? product.img : '/img/' + product.img) : '/img/placeholder.png';
                        return { name: name, qty: itemCounts[name], img: imgPath };
                    }).sort((a, b) => b.qty - a.qty).slice(0, 5);

                    return {
                        totalOrders,
                        avgOrder,
                        topItems
                    };
                },

                // Open Tables Metrics
                get occupiedTablesList() {
                    return this.openTables.filter(t => t.status === 'occupied');
                },

                get tablesMetrics() {
                    let occupiedTables = this.openTables.filter(t => t.status === 'occupied').length;
                    let availableTables = this.openTables.filter(t => t.status === 'available').length;
                    let totalTables = this.openTables.length;
                    let totalGuests = this.openTables.reduce((sum, t) => {
                        const guests = t.guests ?? ((t.adults || 0) + (t.children || 0));
                        return sum + guests;
                    }, 0);
                    let occupancyPercentage = totalTables > 0 ? Math.round((occupiedTables / totalTables) * 100) : 0;

                    return {
                        occupiedTables,
                        availableTables,
                        totalGuests,
                        occupancyPercentage
                    };
                },

                // Inventory Metrics
                get invMetrics() {
                    let totalStock = this.products.reduce((sum, p) => sum + parseInt(p.stock), 0);
                    let totalCostValue = this.products.reduce((sum, p) => sum + (p.stock * p.cost), 0);
                    let avgMargin = this.products.length > 0
                        ? Math.round(this.products.reduce((sum, p) => sum + this.calculateMargin(p.cost, p.sellingPrice), 0) / this.products.length)
                        : 0;
                    return { totalStock, totalCostValue, avgMargin };
                },

                // Product Sales Metrics
                get productSalesMetrics() {
                    let filtered = this.filteredProductSales;
                    let totalRevenue = filtered.reduce((sum, p) => sum + p.totalRevenue, 0);
                    let totalItemsSold = filtered.reduce((sum, p) => sum + p.qtySold, 0);

                    return { totalRevenue, totalItemsSold };
                },

                get filteredProductSales() {
                    let today = new Date();
                    let startDate, endDate;

                    if (this.salesDateFilter === 'today') {
                        startDate = new Date(today.getFullYear(), today.getMonth(), today.getDate());
                        endDate = new Date(today.getFullYear(), today.getMonth(), today.getDate(), 23, 59, 59);
                    } else {
                        let yesterday = new Date(today);
                        yesterday.setDate(yesterday.getDate() - 1);
                        startDate = new Date(yesterday.getFullYear(), yesterday.getMonth(), yesterday.getDate());
                        endDate = new Date(yesterday.getFullYear(), yesterday.getMonth(), yesterday.getDate(), 23, 59, 59);
                    }

                    let productSalesMap = {};
                    let totalRevenue = 0;

                    if (this.orderHistory && Array.isArray(this.orderHistory)) {
                        this.orderHistory.forEach(transaction => {
                            if (transaction.items && Array.isArray(transaction.items)) {
                                transaction.items.forEach(item => {
                                    if (!productSalesMap[item.name]) {
                                        let product = this.products.find(p => p.name === item.name);
                                        productSalesMap[item.name] = {
                                            id: product ? product.id : 0,
                                            name: item.name,
                                            qtySold: 0,
                                            unitCost: product ? product.cost : 0,
                                            totalRevenue: 0,
                                            imgPath: product && product.img ? (product.img.includes('data:') || product.img.includes('http') ? product.img : '/img/' + product.img) : '/img/placeholder.png'
                                        };
                                    }
                                    let itemTotal = item.price * item.qty;
                                    productSalesMap[item.name].qtySold += item.qty;
                                    productSalesMap[item.name].totalRevenue += itemTotal;
                                    totalRevenue += itemTotal;
                                });
                            }
                        });
                    }

                    return Object.values(productSalesMap).map(p => ({
                        ...p,
                        percentageOfSales: totalRevenue > 0 ? (p.totalRevenue / totalRevenue) * 100 : 0
                    })).sort((a, b) => b.totalRevenue - a.totalRevenue);
                },

                getCurrentDate() {
                    const now = new Date();
                    const month = String(now.getMonth() + 1).padStart(2, '0');
                    const day = String(now.getDate()).padStart(2, '0');
                    const year = now.getFullYear();
                    return `${month}/${day}/${year}`;
                },

                getCurrentTime() {
                    const now = new Date();
                    let hours = now.getHours();
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    const ampm = hours >= 12 ? 'PM' : 'AM';
                    hours = hours % 12;
                    hours = hours ? hours : 12;
                    const hoursStr = String(hours).padStart(2, '0');
                    return `${hoursStr}:${minutes} ${ampm}`;
                },

                printOrder(tableId) {
                    this.currentReceiptOrderId = 'ORD-' + Date.now();
                    this.showOrderModal = false;
                    this.showAdvanceOrderModal = false;
                    this.showCompleteOrderModal = true;
                },

                confirmPrint(tableId) {
                    let analyticsHistory = JSON.parse(localStorage.getItem('ub_order_history') || '[]');

                    if (this.selectedTable && this.selectedTable.orders && this.selectedTable.orders.length > 0) {
                        // Check if this transaction already exists to prevent duplicates
                        const transactionExists = analyticsHistory.some(t => t.orderId === this.currentReceiptOrderId);

                        if (!transactionExists) {
                            const transaction = {
                                orderId: this.currentReceiptOrderId,
                                timestamp: new Date().toLocaleTimeString(),
                                totalAmount: (this.selectedTable.bill || 0) * 1.05,
                                tableId: tableId,
                                items: this.selectedTable.orders.map(item => ({
                                    name: item.name,
                                    qty: item.qty,
                                    price: item.price,
                                    addonName: item.addonName
                                })),
                                status: 'completed'
                            };

                            analyticsHistory.unshift(transaction);
                            localStorage.setItem('ub_order_history', JSON.stringify(analyticsHistory));
                        }
                    }

                    alert('✓ Order ' + this.currentReceiptOrderId + ' printed successfully!');
                    this.showCompleteOrderModal = false;
                    this.clearTable(tableId);
                }
            }
        }
    </script>
</body>
</html>