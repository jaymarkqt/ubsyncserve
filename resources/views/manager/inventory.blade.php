<div class="space-y-8">

    <div class="mb-8 flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tighter leading-none">
                Inventory Management
            </h1>
            <p class="text-xs text-slate-500 mt-2 font-bold uppercase tracking-[0.2em] flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                Manage Products & Stock
            </p>
        </div>

        <button @click="showAddModal = true; editingIndex = null; resetForm();"
            class="flex items-center gap-2 px-6 py-3 bg-[#800000] text-white font-black rounded-xl hover:shadow-lg transition-all">
            <i class="fas fa-plus"></i> Add Product
        </button>
    </div>



    <div class="clay-card overflow-hidden">
        <div class="p-5 border-b bg-slate-50/50">
            <h3 class="text-xs font-black text-slate-700 uppercase tracking-widest">Product Inventory List</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b-2 border-slate-200 bg-slate-50/50 text-left">
                        <th class="px-4 py-4 font-black text-slate-700 uppercase text-xs">Product</th>
                        <th class="px-4 py-4 text-center font-black text-slate-700 uppercase text-xs">Stock</th>
                        <th class="px-4 py-4 text-right font-black text-slate-700 uppercase text-xs">Cost</th>
                        <th class="px-4 py-4 text-right font-black text-slate-700 uppercase text-xs">Selling Price</th>
                        <th class="px-4 py-4 text-right font-black text-slate-700 uppercase text-xs">Margin</th>
                        <th class="px-4 py-4 text-right font-black text-slate-700 uppercase text-xs">Total Cost</th>
                        <th class="px-4 py-4 text-center font-black text-slate-700 uppercase text-xs">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <template x-for="(product, index) in products" :key="index">
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-slate-200 overflow-hidden flex-shrink-0">
                                        <img :src="product.image" class="w-full h-full object-cover">
                                    </div>
                                    <span class="font-bold text-slate-800" x-text="product.name"></span>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-bold"
                                      :class="product.stock > 50 ? 'bg-emerald-100 text-emerald-700' : product.stock > 20 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700'"
                                      x-text="product.stock"></span>
                            </td>
                            <td class="px-4 py-4 text-right font-bold text-slate-700" x-text="formatCurrency(product.cost)"></td>
                            <td class="px-4 py-4 text-right font-bold text-[#800000]" x-text="formatCurrency(product.sellingPrice)"></td>
                            <td class="px-4 py-4 text-right font-bold text-emerald-600" x-text="calculateMargin(product.cost, product.sellingPrice) + '%'"></td>
                            <td class="px-4 py-4 text-right font-bold text-blue-600" x-text="formatCurrency(product.stock * product.cost)"></td>
                            <td class="px-4 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button @click="editProduct(index)" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button @click="deleteProduct(index)" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div x-show="showAddModal || editingIndex !== null" x-cloak class="modal-overlay" @click="closeModal()"></div>

<div x-show="showAddModal || editingIndex !== null" x-cloak 
     class="fixed inset-0 flex items-center justify-center z-[1051] p-4 pointer-events-none">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl pointer-events-auto overflow-hidden">
        <div class="p-6 border-b flex justify-between items-center bg-slate-50/50">
            <h2 class="text-xl font-black text-slate-800 uppercase" x-text="editingIndex !== null ? 'Edit Product' : 'Add New Product'"></h2>
            <button @click="closeModal()" class="text-slate-500 hover:text-slate-800">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="p-6 space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase mb-2">Product Name</label>
                    <input type="text" x-model="formData.name" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#800000] outline-none">
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase mb-2">Stock Quantity</label>
                    <input type="number" x-model="formData.stock" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#800000] outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase mb-2">Cost Price (₱)</label>
                    <input type="number" x-model="formData.cost" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#800000] outline-none">
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase mb-2">Selling Price (₱)</label>
                    <input type="number" x-model="formData.sellingPrice" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#800000] outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-black text-slate-700 uppercase mb-2">Product Image</label>
                <div @click="$refs.imageInput.click()" class="border-2 border-dashed border-slate-300 rounded-lg p-6 text-center cursor-pointer hover:border-[#800000]">
                    <input type="file" x-ref="imageInput" @change="handleImageUpload" accept="image/*" class="hidden">
                    <template x-if="formData.image">
                        <img :src="formData.image" class="w-24 h-24 object-cover rounded-lg mx-auto">
                    </template>
                    <template x-if="!formData.image">
                        <div class="text-slate-400">
                            <i class="fas fa-image text-3xl mb-2"></i>
                            <p class="text-xs font-bold">Click to upload</p>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <div class="p-6 border-t flex gap-3 justify-end bg-slate-50/50">
            <button @click="closeModal()" class="px-6 py-2 text-slate-600 font-bold">Cancel</button>
            <button @click="saveProduct()" class="px-6 py-2 bg-[#800000] text-white font-bold rounded-lg">Save Product</button>
        </div>
    </div>
</div>