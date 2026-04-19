<!-- 1. MODAL: KELOLA PRODUK UMKM (CREATE/EDIT) -->
<div x-show="productModalOpen" class="fixed inset-0 z-[150] flex items-center justify-center p-4 sm:p-6" x-cloak
    x-init="
        $nextTick(() => {
            if(!window.productEditor) {
                window.productEditor = new Quill('#p_description_container', {
                    theme: 'snow',
                    placeholder: 'Tuliskan spesifikasi, keunggulan, dan detail lainnya mengenai produk ini...',
                    modules: {
                        toolbar: [
                            ['bold', 'italic', 'underline'],
                            [{ 'list': 'bullet' }]
                        ]
                    }
                });

                window.productEditor.on('text-change', function() {
                    document.getElementById('p_description_html').value = window.productEditor.root.innerHTML;
                });
            }
        });
    ">
    <div x-show="productModalOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="productModalOpen = false"></div>

    <div x-show="productModalOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative bg-white rounded-3xl shadow-2xl w-full max-w-5xl overflow-hidden flex flex-col max-h-[95vh]">

        <form id="productForm" action="{{ route('admin.umkm.storeProduct') }}" method="POST" class="flex-1 flex flex-col h-full overflow-hidden w-full m-0 p-0">
            @csrf
            <input type="hidden" name="id" :value="productId">
            <input type="hidden" name="description_html" id="p_description_html">

            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 shrink-0">
                <h3 class="font-extrabold text-lg text-gray-900" x-text="productId ? 'Edit Produk UMKM' : 'Tambah Produk Baru'"></h3>
                <button type="button" @click="productModalOpen = false" class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 transition-colors"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <div class="overflow-y-auto custom-scrollbar flex-1 flex flex-col md:flex-row">
                <!-- KIRI: Gambar, Status, & Info Penjual -->
                <div class="w-full md:w-80 p-6 border-b md:border-b-0 md:border-r border-gray-100 space-y-6 bg-gray-50/30">
                    <!-- Image Upload -->
                    <div>
                        <label class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-2 block">Foto Produk (Utama) <span class="text-gray-400 font-normal italic">(Akan Ditambahkan)</span></label>
                        <div class="w-full aspect-square bg-gray-100 rounded-2xl border-2 border-dashed border-gray-300 flex flex-col items-center justify-center text-gray-400">
                            <i class="fa-solid fa-image text-3xl mb-2"></i>
                            <span class="text-[10px] font-bold text-center">Foto Dummy</span>
                        </div>
                    </div>

                    <!-- Publish Status -->
                    <div class="bg-white border border-gray-200 p-4 rounded-xl shadow-sm">
                        <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wider mb-3">Status Visibilitas</h4>
                        <select name="status" x-model="publishStatus" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none font-semibold cursor-pointer">
                            <option value="aktif">Aktif (Tampil Publik)</option>
                            <option value="nonaktif">Nonaktif (Sembunyikan)</option>
                        </select>
                    </div>

                    <!-- Contact / Seller Info -->
                    <div>
                        <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Informasi Penjual / UMKM</h4>
                        <div class="space-y-3">
                            <input type="text" id="p_seller_name" name="seller_name" required placeholder="Nama Pemilik/Toko" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none transition-all">
                            <div class="relative">
                                <i class="fa-brands fa-whatsapp absolute left-4 top-1/2 -translate-y-1/2 text-green-500"></i>
                                <input type="text" id="p_seller_phone" name="seller_phone" required placeholder="No. WhatsApp (08...)" class="w-full bg-white border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none transition-all font-mono text-gray-600">
                            </div>
                            <p class="text-[10px] text-gray-500 leading-tight">Tombol "Beli via WA" di website akan mengarah ke nomor ini.</p>
                        </div>
                    </div>
                </div>

                <!-- KANAN: Detail Produk Form -->
                <div class="flex-1 p-6 space-y-5">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Nama Produk <span class="text-red-500">*</span></label>
                        <input type="text" name="name" x-model="productTitle" required placeholder="Misal: Keripik Singkong Madu Asli" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-base font-bold text-gray-900 focus:ring-2 focus:ring-green-500 outline-none transition-all placeholder:font-normal">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Kategori Produk <span class="text-red-500">*</span></label>
                            <select id="cat_select" name="category_id" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-500 outline-none cursor-pointer">
                                <option value="">Pilih Kategori...</option>
                                @foreach($categoriesList as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-gray-700 uppercase tracking-wider">Stok Produk <span class="text-gray-400 font-normal italic">(Opsional)</span></label>
                            <input type="number" id="p_stock" name="stock" placeholder="Kosongkan jika selalu ada" min="0" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-500 outline-none transition-all">
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Harga Jual (Rp) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-bold">Rp</span>
                            <input type="number" name="price" x-model="productPrice" required placeholder="0" min="0" class="w-full bg-white border-2 border-green-200 rounded-xl pl-12 pr-4 py-3 text-lg font-extrabold text-green-700 focus:border-green-500 focus:ring-2 focus:ring-green-500 outline-none transition-all shadow-sm">
                        </div>
                    </div>

                    <div class="space-y-1 h-full flex flex-col">
                        <label class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Deskripsi Produk <span class="text-red-500">*</span></label>
                        <div class="border border-gray-200 rounded-xl overflow-hidden flex flex-col flex-1 min-h-[200px]">
                            <div id="p_description_container" class="border-0 flex-1"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 shrink-0 flex justify-end gap-3 rounded-b-3xl z-10">
                <button type="button" @click="productModalOpen = false" class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-600 bg-white border border-gray-300 hover:bg-gray-100 transition-colors shadow-sm">Batal</button>
                <button type="submit" class="px-8 py-2.5 rounded-xl text-sm font-bold text-white bg-green-700 hover:bg-green-800 shadow-md transition-all flex items-center gap-2"><i class="fa-solid fa-save"></i> Simpan Produk</button>
            </div>
        </form>
    </div>
</div>

<!-- 2. MODAL: MANAJEMEN KATEGORI PRODUK -->
<div x-show="categoryModalOpen" class="fixed inset-0 z-[110] flex items-center justify-center p-4 sm:p-6" x-cloak>
    <div x-show="categoryModalOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="categoryModalOpen = false"></div>

    <div x-show="categoryModalOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden flex flex-col">
        
        <form id="categoryForm" action="{{ route('admin.umkm.storeCategory') }}" method="POST">
            @csrf
            <input type="hidden" name="id" :value="categoryId">

            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="font-extrabold text-lg text-gray-900" x-text="categoryId ? 'Edit Kategori' : 'Tambah Kategori Produk'"></h3>
                <button type="button" @click="categoryModalOpen = false" class="text-gray-400 hover:text-red-500 transition-colors"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <div class="p-6 space-y-5">
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700 uppercase">Nama Kategori <span class="text-red-500">*</span></label>
                    <input type="text" id="c_name" name="name" required placeholder="Misal: Fashion & Pakaian" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none">
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700 uppercase">Ikon Kategori (Opsional)</label>
                    <input type="text" id="c_icon" name="icon" placeholder="Class FontAwesome (cth: fa-solid fa-shirt)" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none font-mono">
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end gap-3 rounded-b-3xl">
                <button type="button" @click="categoryModalOpen = false" class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-600 bg-white border border-gray-200 hover:bg-gray-100 transition-colors">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-green-700 hover:bg-green-800 shadow-md transition-all flex items-center gap-2"><i class="fa-solid fa-save"></i> Simpan Kategori</button>
            </div>
        </form>
    </div>
</div>
