<!-- 1. DRAWER: DETAIL PRODUK -->
<div x-show="detailDrawerOpen" class="fixed inset-0 z-[100] flex justify-end" x-cloak>
    <div x-show="detailDrawerOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="detailDrawerOpen = false"></div>

    <div x-show="detailDrawerOpen" x-transition:enter="transition ease-transform duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-transform duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="relative bg-white w-full max-w-md h-full shadow-2xl flex flex-col border-l border-gray-200">

        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 shrink-0">
            <div class="flex items-center gap-2">
                <template x-if="previewData.status === 'aktif'">
                    <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-0.5 rounded border border-green-200 uppercase tracking-widest"><i class="fa-solid fa-store mr-1"></i> Produk Aktif</span>
                </template>
                <template x-if="previewData.status === 'nonaktif'">
                    <span class="bg-gray-100 text-gray-500 text-[10px] font-bold px-2 py-0.5 rounded border border-gray-200 uppercase tracking-widest"><i class="fa-solid fa-store-slash mr-1"></i> Draft / Nonaktif</span>
                </template>
            </div>
            <div class="flex gap-2">
                <button @click="openProductForm(previewData.id); detailDrawerOpen = false" class="text-gray-400 hover:text-amber-500 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-amber-50 transition-colors" title="Edit"><i class="fa-solid fa-pen"></i></button>
                <button @click="detailDrawerOpen = false" class="text-gray-400 hover:text-gray-800 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-200 transition-colors"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
        </div>

        <div class="overflow-y-auto custom-scrollbar flex-1">
            <div class="w-full h-64 bg-gray-100 relative overflow-hidden border-b border-gray-200 flex items-center justify-center">
                <i class="fa-solid fa-image text-4xl text-gray-300"></i>
            </div>

            <div class="p-6 space-y-6">
                <div>
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded" x-text="previewData.category ? previewData.category.name : ''"></span>
                        <span class="text-[10px] text-gray-500 font-mono">Stok: <span x-text="previewData.stock !== null ? previewData.stock : 'Selalu Ada'"></span></span>
                    </div>
                    <h2 class="text-2xl font-extrabold text-gray-900 leading-tight mb-2" x-text="previewData.name"></h2>
                    <h3 class="text-xl font-black text-green-700" x-text="formatIDR(previewData.price)"></h3>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 border-b border-gray-100 pb-2">Deskripsi Produk</h4>
                    <div class="text-sm text-gray-600 leading-relaxed space-y-2 prose prose-sm" x-html="previewData.description_html">
                    </div>
                </div>

                <div class="bg-gray-50 border border-gray-200 p-4 rounded-2xl flex items-center gap-4">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-green-600 border border-gray-200 shadow-sm shrink-0">
                        <i class="fa-solid fa-shop text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Penjual / UMKM</p>
                        <p class="font-bold text-gray-900 leading-none mb-1" x-text="previewData.seller_name"></p>
                        <p class="text-[10px] font-mono text-gray-500" x-text="previewData.seller_phone"></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-5 border-t border-gray-100 bg-white shrink-0">
            <a :href="'https://wa.me/' + previewData.seller_phone" target="_blank" class="w-full flex items-center justify-center gap-2 bg-green-500 hover:bg-green-600 text-white px-4 py-3.5 rounded-xl font-bold text-sm transition-colors shadow-md shadow-green-200">
                <i class="fa-brands fa-whatsapp text-lg"></i> Coba Hubungi Penjual via WA
            </a>
        </div>
    </div>
</div>

<!-- 2. MODAL: PREVIEW LAPAK DESA (PUBLIK) -->
<div x-show="previewModalOpen" class="fixed inset-0 z-[200] flex items-center justify-center p-4 sm:p-6" x-cloak>
    <div x-show="previewModalOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/80 backdrop-blur-sm" @click="previewModalOpen = false"></div>

    <div x-show="previewModalOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" class="relative bg-gray-100 rounded-3xl shadow-2xl w-full max-w-5xl overflow-hidden flex flex-col h-[90vh]">

        <div class="px-4 py-3 bg-white border-b border-gray-200 flex justify-between items-center shrink-0 shadow-sm z-10">
            <div class="flex gap-1.5 w-16">
                <div class="w-3 h-3 rounded-full bg-red-400"></div>
                <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                <div class="w-3 h-3 rounded-full bg-green-400"></div>
            </div>

            <div class="flex-1 max-w-md mx-auto bg-gray-100 rounded-lg px-3 py-1.5 flex items-center gap-2 text-xs text-gray-500 font-mono text-center justify-center">
                <i class="fa-solid fa-lock text-gray-400"></i> desa-sindangmukti.id/lapak
            </div>

            <div class="flex items-center gap-2 justify-end w-32">
                <div class="bg-gray-100 rounded-lg flex p-1">
                    <button @click="previewDevice = 'desktop'" :class="previewDevice === 'desktop' ? 'bg-white shadow-sm text-gray-800' : 'text-gray-400'" class="w-7 h-7 rounded flex items-center justify-center transition-all"><i class="fa-solid fa-desktop text-xs"></i></button>
                    <button @click="previewDevice = 'mobile'" :class="previewDevice === 'mobile' ? 'bg-white shadow-sm text-gray-800' : 'text-gray-400'" class="w-7 h-7 rounded flex items-center justify-center transition-all"><i class="fa-solid fa-mobile-screen text-xs"></i></button>
                </div>
                <div class="w-px h-5 bg-gray-300 mx-1"></div>
                <button @click="previewModalOpen = false" class="text-gray-400 hover:text-red-500 transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
        </div>

        <div class="flex-1 bg-gray-200 overflow-y-auto custom-scrollbar flex justify-center p-4 sm:p-8 transition-all duration-500">
            <div class="bg-white shadow-lg overflow-hidden transition-all duration-500 ease-in-out border border-gray-200 flex flex-col" :class="previewDevice === 'desktop' ? 'w-full max-w-4xl rounded-t-xl min-h-full' : 'w-[375px] h-[812px] rounded-3xl ring-8 ring-gray-800 shrink-0'">

                <!-- Web Header / Nav Fake -->
                <div class="bg-white border-b border-gray-100 px-6 py-4 flex justify-between items-center shrink-0">
                    <div class="font-black text-green-700 text-lg tracking-widest">DESAKU</div>
                    <div class="hidden sm:flex gap-6 text-xs font-bold text-gray-600">
                        <span>Beranda</span>
                        <span class="text-green-600">Lapak Desa</span>
                        <span>Profil</span>
                    </div>
                    <div class="sm:hidden text-gray-400"><i class="fa-solid fa-bars text-xl"></i></div>
                </div>

                <!-- Web Content Fake (Lapak UMKM) -->
                <div class="flex-1 overflow-y-auto custom-scrollbar">
                    <div class="bg-green-50 p-8 text-center border-b border-green-100">
                        <h1 class="text-2xl font-extrabold text-green-900 mb-2">Lapak Desa</h1>
                        <p class="text-xs text-green-700">Dukung UMKM lokal dengan membeli produk asli buatan warga kami.</p>
                    </div>

                    <div class="p-6">
                        <div class="flex gap-2 overflow-x-auto pb-4 mb-2 custom-scrollbar">
                            <span class="bg-green-600 text-white px-4 py-1.5 rounded-full text-xs font-bold whitespace-nowrap cursor-pointer">Semua Produk</span>
                            @if(isset($categoriesList))
                                @foreach($categoriesList as $cat)
                                    <span class="bg-white border border-gray-200 text-gray-600 px-4 py-1.5 rounded-full text-xs font-bold whitespace-nowrap cursor-pointer hover:bg-gray-50">{{ $cat->name }}</span>
                                @endforeach
                            @endif
                        </div>

                        <div class="grid gap-4" :class="previewDevice === 'desktop' ? 'grid-cols-3' : 'grid-cols-2'">
                            @if(isset($products))
                                @foreach($products->take(6) as $sim)
                                    <div class="border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition-shadow bg-white flex flex-col">
                                        <div class="aspect-square bg-gray-100 flex items-center justify-center text-gray-300 text-3xl">
                                            <i class="fa-solid fa-image"></i>
                                        </div>
                                        <div class="p-4 flex flex-col flex-1">
                                            <span class="text-[9px] font-bold text-amber-600 uppercase mb-1">{{ $sim->category->name }}</span>
                                            <h3 class="text-sm font-bold text-gray-900 leading-tight mb-2 line-clamp-2" title="{{ $sim->name }}">{{ \Illuminate\Support\Str::limit($sim->name, 40) }}</h3>
                                            <p class="text-sm font-black text-green-700 mt-auto">Rp {{ number_format($sim->price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <div class="bg-gray-900 p-4 text-center shrink-0">
                    <p class="text-[9px] text-gray-500 uppercase tracking-widest">&copy; {{ date('Y') }} SIDesa</p>
                </div>
            </div>
        </div>

        <div class="p-4 border-t border-gray-200 bg-white shrink-0 flex justify-between items-center text-xs text-gray-500">
            <span>Simulator Katalog Publik Lapak Desa</span>
        </div>
    </div>
</div>
