<!-- TAB 1: DAFTAR PRODUK (TABLE VIEW) -->
<div x-show="activeTab === 'produk'" x-transition.opacity class="space-y-6">
    <!-- Filter -->
    <div class="flex flex-col md:flex-row gap-4 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
        <form action="{{ route('admin.umkm.index') }}" method="GET" class="flex-1 relative flex gap-4 w-full flex-col md:flex-row">
            <input type="hidden" name="tab" value="produk">
            
            <div class="flex-1 relative">
                <i class="fa-solid fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk, penjual..." class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none transition-all">
            </div>
            <select name="category" onchange="this.form.submit()" class="w-full md:w-48 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none cursor-pointer font-medium text-gray-700">
                <option value="">Semua Kategori</option>
                @foreach($categoriesList as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <select name="status" onchange="this.form.submit()" class="w-full md:w-48 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none cursor-pointer font-medium text-gray-700">
                <option value="">Semua Status</option>
                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif (Ditampilkan)</option>
                <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif (Draft)</option>
            </select>
            <button type="submit" class="hidden">Filter</button>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden">
        
        <div x-show="selectedRows.length > 0" x-collapse x-cloak class="bg-green-50 border-b border-green-100 px-5 py-3 flex items-center justify-between">
            <span class="text-sm font-bold text-green-800"><span x-text="selectedRows.length"></span> Produk Terpilih</span>
            <form action="{{ route('admin.umkm.destroyProduct') }}" method="POST" class="flex gap-2">
                @csrf
                <input type="hidden" name="selected_ids" :value="selectedRows.join(',')">
                <button type="submit" onclick="return confirm('Hapus permanen item terpilih?')" class="text-xs font-bold px-3 py-1.5 bg-red-600 border border-red-700 text-white rounded-lg hover:bg-red-700">Hapus</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-gray-50/80 text-gray-500 text-[10px] uppercase tracking-wider border-b border-gray-200">
                        <th class="p-4 w-12 text-center">
                            <input type="checkbox" class="custom-checkbox inline-block" x-model="selectAll" @change="selectedRows = selectAll ? [{{ isset($products) ? $products->pluck('id')->join(',') : '' }}] : []">
                        </th>
                        <th class="p-4 font-bold">Produk & Penjual</th>
                        <th class="p-4 font-bold text-center">Kategori</th>
                        <th class="p-4 font-bold text-right">Harga & Stok</th>
                        <th class="p-4 font-bold text-center">Status</th>
                        <th class="p-4 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    @if(isset($products) && count($products) > 0)
                        @foreach($products as $product)
                        <tr class="hover:bg-gray-50 transition-colors {{ $product->status == 'nonaktif' ? 'opacity-80' : '' }}">
                            <td class="p-4 text-center">
                                <input type="checkbox" class="custom-checkbox inline-block" value="{{ $product->id }}" x-model="selectedRows">
                            </td>
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-gray-100 text-gray-300 rounded-lg flex items-center justify-center border border-gray-200 border-dashed shrink-0">
                                        <i class="fa-solid fa-image text-xl"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900 leading-tight w-48 truncate" title="{{ $product->name }}">{{ $product->name }}</div>
                                        <div class="text-[10px] text-gray-500 mt-0.5"><i class="fa-solid fa-store mr-1 text-green-500"></i> {{ $product->seller_name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 text-center">
                                <span class="bg-amber-50 text-amber-700 text-[10px] font-bold px-2 py-1 rounded">{{ $product->category->name }}</span>
                            </td>
                            <td class="p-4 text-right">
                                <div class="font-bold text-gray-800">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                <div class="text-[10px] text-gray-500 mt-0.5">Sisa Stok: <span class="font-semibold text-gray-700">{{ $product->stock ?? 'Selalu Ada' }}</span></div>
                            </td>
                            <td class="p-4 text-center">
                                @if($product->status == 'aktif')
                                    <span class="bg-green-50 text-green-700 text-[9px] font-black px-2 py-0.5 rounded border border-green-200 uppercase tracking-widest block mb-1">Aktif</span>
                                    <span class="text-[9px] text-gray-400">Tampil di Web</span>
                                @else
                                    <span class="bg-gray-100 text-gray-500 text-[9px] font-black px-2 py-0.5 rounded border border-gray-200 uppercase tracking-widest block mb-1">Nonaktif</span>
                                    <span class="text-[9px] text-gray-400 italic">Disembunyikan</span>
                                @endif
                            </td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button @click="openDetail({{ $product->id }})" class="w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:text-green-700 hover:bg-green-50 flex items-center justify-center transition-colors border border-gray-200 shadow-sm" title="Preview Detail"><i class="fa-solid fa-eye text-xs"></i></button>
                                    <button @click="openProductForm({{ $product->id }})" class="w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:text-amber-600 hover:bg-amber-50 flex items-center justify-center transition-colors border border-gray-200 shadow-sm" title="Edit"><i class="fa-solid fa-pen text-xs"></i></button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="p-12 text-center text-gray-400">
                                <i class="fa-solid fa-box-open text-3xl mb-3 opacity-30"></i>
                                <p>Belum ada produk untuk ditampilkan.</p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        @if(isset($products) && $products->hasPages())
            <div class="p-4 border-t border-gray-100 bg-gray-50 flex justify-between items-center shrink-0">
                {{ $products->links('pagination::tailwind') }}
            </div>
        @endif
    </div>
</div>
