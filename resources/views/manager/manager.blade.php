<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Command Center | UB-SYNC</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #334155; overflow-x: hidden; }
        .aws-header { background-color: #800000; height: 65px; display: flex; align-items: center; justify-content: space-between; padding: 0 25px; color: white; position: fixed; top: 0; width: 100%; z-index: 1000; }
        .gold-accent { background-color: #D4AF37; height: 4px; position: fixed; top: 65px; width: 100%; z-index: 999; }
        .aws-sidebar { width: 260px; background: white; border-right: 1px solid #eaeded; height: calc(100vh - 69px); position: fixed; top: 69px; left: 0; transition: all 0.3s ease; z-index: 1000; }
        .sidebar-collapsed { left: -260px; }
        .main-content { margin-left: 260px; margin-top: 69px; padding: 30px; transition: all 0.3s ease; min-height: calc(100vh - 69px); }
        .content-wide { margin-left: 0; width: 100%; }
        
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
                tab: 'analytics', // Dito nag-uumpisa sa Analytics by default
                selectedTable: null,
                showAddModal: false,
                showOrderModal: false,
                showReservedModal: false,
                showAdvanceOrderModal: false,
                showSetupModal: false,
                editingIndex: null,
                reservations: [],
               voidOrderIndex: null,
               voidCodeInput: '',
               managerCode: '1234',
               salesDateFilter: 'today',

                // Analytics Data
                salesSummary: { total: 0 },
               orderHistory: [],
                
                // Open Tables Data
             openTables: [],
                
                // Inventory Data
                formData: { id: null, name: '', stock: 0, cost: 0, sellingPrice: 0, img: '', addOns: [] },
                products: [],

                defaultProducts() {
                    return [
                        { id: 1, name: 'Burger Steak', stock: 24, cost: 59, sellingPrice: 99, price: 99, cat: 'Lunch', img: 'burgersteak.png', addOns: [{ name: 'Extra Rice', price: 20 }, { name: 'Cheese', price: 15 }] },
                        { id: 2, name: 'Tapa & Egg', stock: 18, cost: 75, sellingPrice: 120, price: 120, cat: 'Breakfast', img: 'tapa.png', addOns: [{ name: 'Extra Egg', price: 15 }, { name: 'Garlic Rice', price: 20 }] },
                        { id: 3, name: 'Fries', stock: 30, cost: 28, sellingPrice: 65, price: 65, cat: 'Snacks', img: 'fries.png', addOns: [{ name: 'Cheese Sauce', price: 10 }, { name: 'Bacon Bits', price: 15 }] },
                        { id: 4, name: 'Fried Chicken', stock: 12, cost: 95, sellingPrice: 150, price: 150, cat: 'Dinner', img: 'chicken.png', addOns: [{ name: 'Extra Gravy', price: 10 }, { name: 'Spicy Dip', price: 10 }] },
                        { id: 5, name: 'Ice Tea', stock: 40, cost: 20, sellingPrice: 45, price: 45, cat: 'Drinks', img: 'icetea.png', addOns: [{ name: 'Large Cup', price: 15 }] },
                    ];
                },

                normalizeProducts(products) {
                    return products.map(product => ({
                        ...product,
                        price: product.price ?? product.sellingPrice ?? 0,
                        addOns: product.addOns || []
                    }));
                },

                loadProducts() {
                    const savedProducts = localStorage.getItem('product_catalog');
                    if (savedProducts) {
                        try {
                            this.products = JSON.parse(savedProducts);
                        } catch (error) {
                            this.products = this.defaultProducts();
                            this.saveProducts();
                        }
                    } else {
                        this.products = this.defaultProducts();
                        this.saveProducts();
                    }
                },

                saveProducts() {
                    const normalized = this.normalizeProducts(this.products);
                    this.products = normalized;
                    localStorage.setItem('product_catalog', JSON.stringify(normalized));
                    window.dispatchEvent(new Event('storage'));
                },

                resetForm() {
                    this.formData = { id: null, name: '', stock: 0, cost: 0, sellingPrice: 0, img: '', addOns: [] };
                },

                formatCurrency(val) {
                    return '₱' + parseFloat(val || 0).toLocaleString(undefined, {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
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

loadTablesFromStorage() {
                    const stored = localStorage.getItem('ub_tables');
                    const reservations = JSON.parse(localStorage.getItem('ub_reservations') || '[]');
                    let tables = [];

                    if (!stored) {
                        // Initialize default tables and persist them.
                        tables = Array.from({ length: 15 }, (_, i) => ({
                            id: i + 1,
                            status: 'available',
                            adults: 0,
                            children: 0,
                            bill: 0,
                            orders: []
                        }));
                        localStorage.setItem('ub_tables', JSON.stringify(tables));
                    } else {
                        tables = JSON.parse(stored);
                    }

                    // Process each table
                    this.openTables = tables.map(t => {
                        const tableOrders = t.orders || [];
                        let calculatedBill = tableOrders.reduce((sum, item) => sum + (item.price * item.qty), 0);
                        let status = t.status;
                        if (!status) {
                            status = tableOrders.length > 0 ? 'occupied' : 'available';
                        }

                        const matchedReservation = reservations.find(r => r.table == t.id)
                            || reservations.find(r => r.status === 'pending' && ((status === 'reserved-advance' && r.type === 'advance-order') || (status === 'reserved-booking' && r.type === 'table-reservation')));

                        const adults = t.adults ?? (matchedReservation ? matchedReservation.adults || 0 : 0);
                        const children = t.children ?? (matchedReservation ? matchedReservation.children || 0 : 0);
                        const guests = (t.guests || adults + children);

                        return {
                            id: t.id,
                            tableNumber: t.id,
                            status: status,
                            adults: adults,
                            children: children,
                            guests: guests,
                            duration: this.getDuration(t),
                            orders: tableOrders,
                            bill: calculatedBill
                        };
                    });
                },

                loadReservationsFromStorage() {
                    const stored = localStorage.getItem('ub_reservations');
                    this.reservations = stored ? JSON.parse(stored) : [];
                },




async updateReservationStatus(id, newStatus) {
    if(!confirm(`Are you sure you want to mark this reservation as ${newStatus}?`)) return;
    
    let index = this.reservations.findIndex(r => r.id === id);
    if (index === -1) {
        return;
    }

    this.reservations[index].status = newStatus;
    localStorage.setItem('ub_reservations', JSON.stringify(this.reservations));
    this.loadReservationsFromStorage();

    const reservation = this.reservations[index];
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        await fetch('{{ route('reservation.confirm.email') }}', {
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
    } catch (error) {
        console.warn('Email send failed:', error);
    }
},

deleteReservation(id) {
    if(!confirm('Are you sure you want to delete this reservation record?')) return;

    this.reservations = this.reservations.filter(r => r.id !== id);
    localStorage.setItem('ub_reservations', JSON.stringify(this.reservations));
    this.loadReservationsFromStorage();
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
    if (table.status === 'available') {
        return;
    }

    this.selectedTable = table;
    if (table.status === 'reserved-advance') {
        this.showAdvanceOrderModal = true;
        this.showOrderModal = false;
        this.showReservedModal = false;
    } else if (table.status === 'occupied' || (table.orders && table.orders.length > 0)) {
        this.showOrderModal = true;
        this.showAdvanceOrderModal = false;
        this.showReservedModal = false;
    } else if (table.status === 'reserved-booking') {
        this.showReservedModal = true;
        this.showAdvanceOrderModal = false;
        this.showOrderModal = false;
    }
},


clearTable(tableId) {
                    let stored = localStorage.getItem('ub_tables');
                    if (stored) {
                        let tables = JSON.parse(stored);
                        let index = tables.findIndex(t => t.id == tableId);

                        if (index !== -1) {
                            tables[index].status = 'available';
                            tables[index].adults = 0;
                            tables[index].children = 0;
                            tables[index].bill = 0;
                            tables[index].orders = [];

                            localStorage.setItem('ub_tables', JSON.stringify(tables));

                            // Clear kitchen orders same as waiter
                            let kOrders = JSON.parse(localStorage.getItem('ub_kitchen_orders') || '[]');
                            let filteredK = kOrders.filter(ko => ko.table != tableId);
                            localStorage.setItem('ub_kitchen_orders', JSON.stringify(filteredK));

                            this.loadTablesFromStorage();
                            this.showOrderModal = false;
                            this.showReservedModal = false;
                            this.showAdvanceOrderModal = false;
                            this.selectedTable = null;
                        }
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
                confirmVoidOrder() {
                    if (this.voidCodeInput.trim() === '') {
                        alert('Please enter manager PIN.');
                        return;
                    }

                    if (this.voidCodeInput.trim() !== this.managerCode) {
                        alert('Invalid PIN. Void cancelled.');
                        return;
                    }

                    // Kung tama ang PIN, ituloy ang pagbura
                    this.voidOrder(this.voidOrderIndex);
                    this.cancelVoid(); // Isara ang Security Modal pagkatapos
                },

                // 4. Ang mismong function na magbubura ng order sa database/localStorage
                voidOrder(index) {
                    if (!this.selectedTable) return;

                    let stored = localStorage.getItem('ub_tables');
                    if (stored) {
                        let tables = JSON.parse(stored);
                        let tableIndex = tables.findIndex(t => t.id == this.selectedTable.tableNumber);

                        if (tableIndex !== -1) {
                            // Burahin yung order base sa index
                            tables[tableIndex].orders.splice(index, 1);
                            
                            // I-compute ulit ang Running Total Bill
                            tables[tableIndex].bill = tables[tableIndex].orders.reduce((sum, item) => sum + (item.price * item.qty), 0);

                            // Kung naubos na ang order ng table, gawin ulit 'available' ang table at isara ang Order Modal
                            if (tables[tableIndex].orders.length === 0) {
                                tables[tableIndex].status = 'available';
                                tables[tableIndex].adults = 0;
                                tables[tableIndex].children = 0;
                                this.showOrderModal = false;
                            }

                            // I-save pabalik sa storage at i-refresh ang tables
                            localStorage.setItem('ub_tables', JSON.stringify(tables));
                            this.loadTablesFromStorage();
                        }
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

                editProduct(index) {
                    this.editingIndex = index;
                    this.formData = { ...this.products[index] };
                },

                saveProduct() {
                    if (!this.formData.name) return alert('Name is required');

                    if (this.editingIndex !== null) {
                        this.products[this.editingIndex] = { ...this.formData };
                    } else {
                        const nextId = this.products.reduce((max, product) => Math.max(max, product.id), 0) + 1;
                        this.products.push({ ...this.formData, id: nextId });
                    }

                    this.saveProducts();
                    this.closeModal();
                },

                deleteProduct(index) {
                    if (confirm('Delete this product?')) {
                        this.products.splice(index, 1);
                        this.saveProducts();
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
                    this.editingIndex = null;
                    this.resetForm();
                },

                addAddOn() {
                    this.formData.addOns = this.formData.addOns || [];
                    this.formData.addOns.push({ name: '', price: 0 });
                },

                removeAddOn(index) {
                    this.formData.addOns.splice(index, 1);
                },

                resetForm() {
                    this.formData = { id: null, name: '', stock: 0, cost: 0, sellingPrice: 0, img: '', addOns: [] };
                },

init() {
            localStorage.removeItem('ub_order_history');
            localStorage.removeItem('ub_tables');
            this.loadProducts();
            this.loadAnalytics();
            this.loadTablesFromStorage();
            this.loadReservationsFromStorage();

            window.addEventListener('storage', () => {
                this.loadProducts();
                this.loadAnalytics();
                this.loadTablesFromStorage();
                this.loadReservationsFromStorage();
            });

            setInterval(() => {
                this.loadAnalytics();
                this.loadTablesFromStorage();
                this.loadReservationsFromStorage();
            }, 2000);
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
                    let allOrders = [];

                    // Get orders from tables only (to avoid double counting)
                    if (this.openTables && Array.isArray(this.openTables)) {
                        this.openTables.forEach(table => {
                            if (table.orders && Array.isArray(table.orders)) {
                                table.orders.forEach(order => {
                                    allOrders.push({
                                        items: [order],
                                        timestamp: order.timestamp || new Date().toISOString()
                                    });
                                });
                            }
                        });
                    }

                    allOrders.forEach(order => {
                        let orderDate = new Date(order.timestamp);
                        if (orderDate >= startDate && orderDate <= endDate) {
                            if (order.items && Array.isArray(order.items)) {
                                order.items.forEach(item => {
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
                        }
                    });

                    return Object.values(productSalesMap).map(p => ({
                        ...p,
                        percentageOfSales: totalRevenue > 0 ? (p.totalRevenue / totalRevenue) * 100 : 0
                    })).sort((a, b) => b.totalRevenue - a.totalRevenue);
                }
            }
        }
    </script>
</body>
</html>