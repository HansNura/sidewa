@extends('layouts.backoffice')

@section('title', 'Manajemen Produk Hukum (JDIH) - Panel Administrasi')

@push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Merriweather:wght@300;400;700&display=swap');
        [x-cloak] { display: none !important; }
        .custom-checkbox {
            appearance: none; background-color: #fff; margin: 0; font: inherit; color: currentColor;
            width: 1.15em; height: 1.15em; border: 2px solid #cbd5e1; border-radius: 0.25em; display: grid; place-content: center; cursor: pointer; transition: all 0.2s ease-in-out;
        }
        .custom-checkbox::before {
            content: ""; width: 0.65em; height: 0.65em; transform: scale(0); transition: 120ms transform ease-in-out; box-shadow: inset 1em 1em white; background-color: transform; transform-origin: center; clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%);
        }
        .custom-checkbox:checked { background-color: #16a34a; border-color: #16a34a; }
        .custom-checkbox:checked::before { transform: scale(1); }
        .font-serif { font-family: 'Merriweather', serif; }
    </style>
@endpush

@section('content')
<div class="flex-1 flex flex-col h-full bg-[#F8FAFC]" x-data="{ 
        activeTab: '{{ $tab }}',
        docModalOpen: false,
        detailDrawerOpen: false,
        categoryModalOpen: false,
        previewModalOpen: false,
        selectAll: false,
        selectedRows: [],
        previewDevice: 'desktop',
        
        // Doc Form State
        docId: null,
        docTitle: '',
        docStatus: 'berlaku',
        
        // Category Form State
        categoryId: null,
        
        // Detail Preview Data
        previewData: {},

        formatDate(dateString) {
            if(!dateString) return '';
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('id-ID', options);
        },
        
        formatBytes(bytes, decimals = 2) {
            if (!+bytes) return '0 Bytes';
            const k = 1024, dm = decimals < 0 ? 0 : decimals, sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'], i = Math.floor(Math.log(bytes) / Math.log(k));
            return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`;
        },

        async openDocForm(id = null) {
            this.docId = id;
            if(id) {
                try {
                    const res = await fetch(`/konten/jdih/api/${id}`);
                    if(!res.ok) throw new Error('Network response was not ok');
                    const data = await res.json();
                    
                    this.docTitle = data.title;
                    this.docStatus = data.status;
                    
                    document.getElementById('cat_select').value = data.category_id;
                    document.getElementById('d_docnum').value = data.document_number;
                    document.getElementById('d_date').value = data.established_date ? data.established_date.substring(0, 10) : '';
                    document.getElementById('d_desc').value = data.description || '';
                    
                    // Trigger Alpine reactivity to update mockup
                    this.previewData = data; 
                } catch(e) {
                    console.error('Error fetching doc:', e);
                }
            } else {
                this.docTitle = '';
                this.docStatus = 'berlaku';
                this.previewData = {};
                document.getElementById('docForm').reset();
            }
            this.docModalOpen = true;
        },

        async openDetail(id) {
            const res = await fetch(`/konten/jdih/api/${id}`);
            this.previewData = await res.json();
            this.detailDrawerOpen = true;
        },

        openCategoryForm(id, name, desc) {
            this.categoryId = id;
            if(id) {
                document.getElementById('c_name').value = name;
                document.getElementById('c_desc').value = desc || '';
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
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Dokumentasi Hukum</h1>
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
                    <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Manajemen Produk Hukum</h2>
                    <p class="text-sm text-gray-500 mt-1">Kelola regulasi, Perdes, dan SK sebagai Jaringan Dokumentasi dan Informasi Hukum (JDIH) Desa.</p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <button @click="previewModalOpen = true" class="bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 shadow-sm rounded-xl px-4 py-2.5 text-sm font-bold transition-all flex items-center gap-2">
                        <i class="fa-solid fa-scale-balanced text-primary-600"></i> <span class="hidden sm:inline">Preview Portal JDIH</span>
                    </button>
                    <button @click="openDocForm()" class="bg-primary-700 hover:bg-primary-800 text-white shadow-md rounded-xl px-5 py-2.5 text-sm font-bold transition-all flex items-center gap-2">
                        <i class="fa-solid fa-file-circle-plus"></i> Tambah Dokumen
                    </button>
                </div>
            </section>

            <!-- CARDS -->
            <section class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between group hover:-translate-y-1 transition-transform">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Dokumen</p>
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center"><i class="fa-solid fa-folder-open text-xs"></i></div>
                    </div>
                    <h3 class="text-2xl font-extrabold text-gray-900">{{ $totalDocs }}</h3>
                </article>

                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between group border-b-4 border-b-primary-500 hover:-translate-y-1 transition-transform">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Peraturan Desa (Perdes)</p>
                        <div class="w-8 h-8 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center"><i class="fa-solid fa-gavel text-xs"></i></div>
                    </div>
                    <h3 class="text-2xl font-extrabold text-primary-700">{{ $countPerdes }}</h3>
                </article>

                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between group border-b-4 border-b-amber-500 hover:-translate-y-1 transition-transform">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">SK Kades</p>
                        <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center"><i class="fa-solid fa-file-signature text-xs"></i></div>
                    </div>
                    <h3 class="text-2xl font-extrabold text-amber-600">{{ $countSk }}</h3>
                </article>

                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between group border-b-4 border-b-red-500 hover:-translate-y-1 transition-transform">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Status: Tidak Berlaku</p>
                        <div class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center"><i class="fa-solid fa-box-archive text-xs"></i></div>
                    </div>
                    <h3 class="text-2xl font-extrabold text-red-600">{{ $totalDicabut }}</h3>
                </article>
            </section>

            <!-- VIEW TOGGLE -->
            <section class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-3 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex gap-2 p-1 bg-gray-100 rounded-xl w-full md:w-auto overflow-x-auto">
                    <form action="{{ route('admin.jdih.index') }}" method="GET" class="flex-1 md:flex-none">
                        <input type="hidden" name="tab" value="dokumen">
                        <button type="submit" :class="activeTab === 'dokumen' ? 'bg-white shadow-sm text-primary-700 font-bold' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-2 text-sm rounded-lg transition-all whitespace-nowrap w-full"><i class="fa-solid fa-file-pdf mr-1"></i> Daftar Dokumen Hukum</button>
                    </form>
                    <form action="{{ route('admin.jdih.index') }}" method="GET" class="flex-1 md:flex-none">
                        <input type="hidden" name="tab" value="kategori">
                        <button type="submit" :class="activeTab === 'kategori' ? 'bg-white shadow-sm text-primary-700 font-bold' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-2 text-sm rounded-lg transition-all whitespace-nowrap w-full"><i class="fa-solid fa-tags mr-1"></i> Kategori Produk Hukum</button>
                    </form>
                </div>
            </section>

            <!-- TABS CONTENT -->
            @if($tab == 'dokumen')
                @include('pages.backoffice.jdih._tab_dokumen')
            @else
                @include('pages.backoffice.jdih._tab_kategori')
            @endif

        </div>
    </main>

    @include('pages.backoffice.jdih._form')
    @include('pages.backoffice.jdih._drawer')
</div>
@endsection
