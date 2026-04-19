@extends('layouts.backoffice')

@section('title', 'Manajemen Produk UMKM - Panel Administrasi')

@push('styles')
    <style>
        [x-cloak] { display: none !important; }
        .custom-checkbox {
            appearance: none; background-color: #fff; margin: 0;
            width: 1.15em; height: 1.15em; border: 2px solid #cbd5e1; border-radius: 0.25em; display: grid; place-content: center; cursor: pointer; transition: all 0.2s ease-in-out;
        }
        .custom-checkbox::before {
            content: ""; width: 0.65em; height: 0.65em; transform: scale(0); transition: 120ms transform ease-in-out; box-shadow: inset 1em 1em white; background-color: transform; transform-origin: center; clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%);
        }
        .custom-checkbox:checked { background-color: #16a34a; border-color: #16a34a; }
        .custom-checkbox:checked::before { transform: scale(1); }
        
        .ql-toolbar { border: none !important; border-bottom: 1px solid #e2e8f0 !important; background: #f8fafc; padding: 12px !important; }
        .ql-container { border: none !important; font-family: 'Inter', sans-serif; font-size: 0.875rem; }
        .ql-editor { padding: 16px !important; min-height: 200px; }
    </style>
@endpush

@section('content')
<div class="flex-1 flex flex-col h-full bg-[#F8FAFC]" x-data="{ 
        activeTab: '{{ $tab }}',
        productModalOpen: false,
        detailDrawerOpen: false,
        categoryModalOpen: false,
        previewModalOpen: false,
        selectAll: false,
        selectedRows: [],
        previewDevice: 'desktop',
        
        // Produk Form State
        productId: null,
        productTitle: '',
        productPrice: '',
        publishStatus: 'aktif',
        
        // Category Form State
        categoryId: null,
        
        // Detail Preview Data
        previewData: {},

        formatIDR(val) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(val);
        },

        async openProductForm(id = null) {
            this.productId = id;
            if(id) {
                const res = await fetch(`/konten/umkm/api/${id}`);
                const data = await res.json();
                
                this.productTitle = data.name;
                this.productPrice = data.price;
                this.publishStatus = data.status;
                
                document.getElementById('cat_select').value = data.category_id;
                document.getElementById('p_stock').value = data.stock || '';
                document.getElementById('p_seller_name').value = data.seller_name;
                document.getElementById('p_seller_phone').value = data.seller_phone;
                
                if(window.productEditor) {
                    window.productEditor.root.innerHTML = data.description_html || '';
                }
            } else {
                this.productTitle = '';
                this.productPrice = '';
                this.publishStatus = 'aktif';
                document.getElementById('productForm').reset();
                if(window.productEditor) window.productEditor.root.innerHTML = '';
            }
            this.productModalOpen = true;
        },

        async openDetail(id) {
            const res = await fetch(`/konten/umkm/api/${id}`);
            this.previewData = await res.json();
            this.detailDrawerOpen = true;
        },

        openCategoryForm(id, name, icon) {
            this.categoryId = id;
            if(id) {
                document.getElementById('c_name').value = name;
                document.getElementById('c_icon').value = icon || '';
            } else {
                document.getElementById('categoryForm').reset();
            }
            this.categoryModalOpen = true;
        }
    }">

    <header class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4 shrink-0 px-4 sm:px-6 pt-6 -z-10">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-semibold px-2.5 py-1 bg-amber-100 text-amber-700 rounded-md uppercase tracking-wider">Akses Publikasi</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Lapak Ekonomi Desa</h1>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto px-4 sm:px-6 lg:px-8 pb-8 custom-scrollbar">
        @if(session('success'))
            <div class="mb-4 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm font-bold shadow-sm">
                <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
            </div>
        @endif
        
        @if($errors->any())
            <div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm shadow-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <p><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="max-w-7xl mx-auto space-y-6">

            <section class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Manajemen Produk UMKM</h2>
                    <p class="text-sm text-gray-500 mt-1">Kelola katalog produk unggulan desa untuk ditampilkan di Lapak Desa Online.</p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <button @click="previewModalOpen = true" class="bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 shadow-sm rounded-xl px-4 py-2.5 text-sm font-bold transition-all flex items-center gap-2">
                        <i class="fa-solid fa-store text-blue-500"></i> <span class="hidden sm:inline">Preview Lapak Web</span>
                    </button>
                    <button @click="openProductForm()" class="bg-green-700 hover:bg-green-800 text-white shadow-md rounded-xl px-4 py-2.5 text-sm font-bold transition-all flex items-center gap-2">
                        <i class="fa-solid fa-circle-plus"></i> <span class="hidden sm:inline">Tambah Produk</span>
                    </button>
                </div>
            </section>

            <!-- CARDS -->
            <section class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between group hover:-translate-y-1 transition-transform">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Produk</p>
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center"><i class="fa-solid fa-box-open text-xs"></i></div>
                    </div>
                    <h3 class="text-2xl font-extrabold text-gray-900">{{ $totalProducts }}</h3>
                </article>

                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between group border-b-4 border-b-green-500 hover:-translate-y-1 transition-transform">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Produk Aktif (Live)</p>
                        <div class="w-8 h-8 rounded-lg bg-green-50 text-green-600 flex items-center justify-center"><i class="fa-solid fa-tags text-xs"></i></div>
                    </div>
                    <h3 class="text-2xl font-extrabold text-green-600">{{ $totalActive }}</h3>
                </article>

                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between group border-b-4 border-b-amber-500 hover:-translate-y-1 transition-transform">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Draft / Nonaktif</p>
                        <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center"><i class="fa-solid fa-store-slash text-xs"></i></div>
                    </div>
                    <h3 class="text-2xl font-extrabold text-amber-600">{{ $totalDraft }}</h3>
                </article>

                <article class="bg-gray-50 rounded-2xl p-5 border border-gray-200 flex flex-col justify-between group border-b-4 border-b-gray-400">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Total Kategori</p>
                        <div class="w-8 h-8 rounded-lg bg-gray-200 text-gray-600 flex items-center justify-center"><i class="fa-solid fa-list text-xs"></i></div>
                    </div>
                    <div class="flex items-end gap-2">
                        <h3 class="text-2xl font-extrabold text-gray-700">{{ $totalCategories }}</h3>
                        <span class="text-[10px] font-medium text-gray-500 mb-1">Kategori Utama</span>
                    </div>
                </article>
            </section>

            <!-- VIEW TOGGLE -->
            <section class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-3 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex gap-2 p-1 bg-gray-100 rounded-xl w-full md:w-auto overflow-x-auto">
                    <form action="{{ route('admin.umkm.index') }}" method="GET" class="flex-1 md:flex-none">
                        <input type="hidden" name="tab" value="produk">
                        <button type="submit" :class="activeTab === 'produk' ? 'bg-white shadow-sm text-green-700 font-bold' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-2 text-sm rounded-lg transition-all whitespace-nowrap w-full"><i class="fa-solid fa-boxes-stacked mr-1"></i> Daftar Produk</button>
                    </form>
                    <form action="{{ route('admin.umkm.index') }}" method="GET" class="flex-1 md:flex-none">
                        <input type="hidden" name="tab" value="kategori">
                        <button type="submit" :class="activeTab === 'kategori' ? 'bg-white shadow-sm text-green-700 font-bold' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-2 text-sm rounded-lg transition-all whitespace-nowrap w-full"><i class="fa-solid fa-tags mr-1"></i> Kategori Produk</button>
                    </form>
                </div>
            </section>

            <!-- TABS CONTENT -->
            @if($tab == 'produk')
                @include('pages.backoffice.umkm._tab_produk')
            @else
                @include('pages.backoffice.umkm._tab_kategori')
            @endif

        </div>
    </main>

    @include('pages.backoffice.umkm._form')
    @include('pages.backoffice.umkm._drawer')
</div>
@endsection
