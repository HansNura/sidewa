@extends('layouts.backoffice')

@section('title', 'Manajemen Berita & Artikel - Panel Administrasi')

@push('styles')
    <!-- Quill Snow CSS diload global via app.js -->
    <style>
        [x-cloak] { display: none !important; }
        
        /* Custom Checkbox */
        .custom-checkbox {
            appearance: none; background-color: #fff; margin: 0;
            width: 1.15em; height: 1.15em; border: 2px solid #cbd5e1;
            border-radius: 0.25em; display: grid; place-content: center;
            cursor: pointer; transition: all 0.2s ease-in-out;
        }
        .custom-checkbox::before {
            content: ""; width: 0.65em; height: 0.65em;
            transform: scale(0); transition: 120ms transform ease-in-out;
            box-shadow: inset 1em 1em white;
            background-color: transform; transform-origin: center;
            clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%);
        }
        .custom-checkbox:checked { background-color: #16a34a; border-color: #16a34a; }
        .custom-checkbox:checked::before { transform: scale(1); }
        
        /* Quill custom override for fullscreen Editor */
        .ql-toolbar.ql-snow { border: none !important; border-bottom: 1px solid #e2e8f0 !important; background: #f8fafc; padding: 12px 24px !important; }
        .ql-container.ql-snow { border: none !important; font-family: 'Merriweather', serif; font-size: 1.125rem; }
        .ql-editor { padding: 24px !important; min-height: 500px; }
        .ql-editor p { margin-bottom: 1rem; line-height: 1.8; color: #1e293b; }
        .ql-editor h2 { font-weight: 700; margin-bottom: 1.5rem; margin-top: 2rem; }
    </style>
@endpush

@section('content')
<div class="flex-1 flex flex-col h-full bg-[#F8FAFC]" x-data="{ 
        activeTab: '{{ $tab }}', 
        editorOpen: false,
        detailDrawerOpen: false,
        previewModalOpen: false,
        catModalOpen: false,
        tagModalOpen: false,
        selectAll: false,
        selectedRows: [],
        
        // Editor State
        articleId: null,
        articleTitle: '',
        articleSlug: '',
        publishStatus: 'draft',
        coverPreview: '',
        
        // Modal Preview State
        previewDevice: 'desktop',
        
        // Fetch logic
        async openEditor(id = null) {
            this.articleId = id;
            if(id) {
                const res = await fetch(`/admin/konten/berita/api/${id}`);
                const data = await res.json();
                this.articleTitle = data.judul;
                this.articleSlug = data.slug;
                this.publishStatus = data.status;
                this.coverPreview = data.cover_image ? `/storage/${data.cover_image}` : '';
                
                document.getElementById('editor_title').value = data.judul;
                document.getElementById('editor_status').value = data.status;
                document.getElementById('editor_slug').value = data.slug;
                document.getElementById('editor_kategori_id').value = data.kategori_id;
                
                let tagsString = data.tags ? data.tags.map(t => t.nama_tag).join(',') : '';
                window.dispatchEvent(new CustomEvent('open-editor-tags', { detail: tagsString }));
                
                window.contentEditor.root.innerHTML = data.konten_html;
            } else {
                this.articleTitle = '';
                this.articleSlug = '';
                this.publishStatus = 'draft';
                this.coverPreview = '';
                document.getElementById('editorForm').reset();
                window.dispatchEvent(new CustomEvent('open-editor-tags', { detail: '' }));
                if(window.contentEditor) window.contentEditor.root.innerHTML = '';
            }
            this.editorOpen = true;
        }
    }">

    <!-- TOP NAVBAR -->
    <header class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4 shrink-0">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Manajemen Berita & Artikel</h1>
            <p class="text-sm text-gray-500 mt-1">Buat, kelola, dan publikasikan informasi terbaru untuk website publik.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <button class="bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 shadow-sm rounded-xl px-4 py-2.5 text-sm font-bold transition-all flex items-center gap-2">
                <i class="fa-solid fa-arrow-up-right-from-square text-blue-500"></i> <span class="hidden sm:inline">Kunjungi Web</span>
            </button>
            <button @click="openEditor()" class="bg-green-700 hover:bg-green-800 text-white shadow-md rounded-xl px-5 py-2.5 text-sm font-bold transition-all flex items-center gap-2">
                <i class="fa-solid fa-pen-nib"></i> Tulis Artikel Baru
            </button>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto pb-8 custom-scrollbar">
        @if(session('success'))
            <div class="mb-4 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm font-bold shadow-sm">
                <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm font-bold shadow-sm">
                <i class="fa-solid fa-triangle-exclamation mr-2"></i>Terdapat kesalahan:
                <ul class="list-disc pl-5 mt-1 text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="max-w-7xl mx-auto space-y-6">

            <!-- SUMMARY CARDS -->
            <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                {{-- Total Artikel --}}
                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-all flex items-center gap-4 group relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-2 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity">
                        <i class="fa-solid fa-file-lines text-7xl rotate-12"></i>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-600 flex items-center justify-center shrink-0 text-xl shadow-inner group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-file-lines"></i>
                    </div>
                    <div class="relative z-10">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Total Artikel</p>
                        <h3 class="text-2xl font-black text-gray-900 tracking-tight leading-none">{{ $totalArticles }}</h3>
                    </div>
                </article>

                {{-- Terpublikasi --}}
                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-all flex items-center gap-4 group relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-2 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity text-emerald-600">
                        <i class="fa-solid fa-globe text-7xl rotate-12"></i>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 text-xl shadow-inner group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-globe"></i>
                    </div>
                    <div class="relative z-10">
                        <p class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest mb-0.5">Terpublikasi</p>
                        <h3 class="text-2xl font-black text-emerald-700 tracking-tight leading-none">{{ $totalPublished }}</h3>
                    </div>
                </article>

                {{-- Draft & Terjadwal --}}
                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-all flex items-center gap-4 group relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-2 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity text-amber-600">
                        <i class="fa-solid fa-pen-ruler text-7xl rotate-12"></i>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 text-xl shadow-inner group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-pen-ruler"></i>
                    </div>
                    <div class="relative z-10">
                        <p class="text-[10px] font-bold text-amber-400 uppercase tracking-widest mb-0.5">Draft & Terjadwal</p>
                        <h3 class="text-2xl font-black text-amber-700 tracking-tight leading-none">{{ $totalDraftScheduled }}</h3>
                    </div>
                </article>

                {{-- Diarsipkan --}}
                <article class="bg-gray-900 rounded-2xl p-5 border border-gray-800 shadow-xl hover:-translate-y-1 transition-all flex items-center gap-4 group relative overflow-hidden border-l-4 border-l-green-500">
                    <div class="absolute top-0 right-0 p-2 opacity-[0.05] group-hover:opacity-[0.1] transition-opacity text-green-400">
                        <i class="fa-solid fa-box-archive text-7xl rotate-12"></i>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-gray-800 text-green-400 flex items-center justify-center shrink-0 text-xl shadow-inner group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-box-archive"></i>
                    </div>
                    <div class="relative z-10">
                        <p class="text-[10px] font-bold text-green-400 uppercase tracking-widest mb-0.5">Diarsipkan</p>
                        <h3 class="text-2xl font-black text-white tracking-tight leading-none">{{ $totalArchived }}</h3>
                    </div>
                </article>
            </section>

            <!-- TABS CONTROL -->
            <section class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-3 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex gap-2 p-1 bg-gray-100 rounded-xl overflow-x-auto w-full md:w-auto">
                    <form action="{{ route('admin.artikel.index') }}" method="GET" class="flex-1 md:flex-none">
                        <input type="hidden" name="tab" value="artikel">
                        <button type="submit" :class="activeTab === 'artikel' ? 'bg-white shadow-sm text-green-700 font-bold' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-2 text-sm rounded-lg transition-all whitespace-nowrap w-full">Daftar Artikel</button>
                    </form>
                    <form action="{{ route('admin.artikel.index') }}" method="GET" class="flex-1 md:flex-none">
                        <input type="hidden" name="tab" value="kategori">
                        <button type="submit" :class="activeTab === 'kategori' ? 'bg-white shadow-sm text-green-700 font-bold' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-2 text-sm rounded-lg transition-all whitespace-nowrap w-full">Manajemen Kategori & Tag</button>
                    </form>
                </div>
            </section>

            <!-- TAB CONTENT -->
            <div class="pt-2">
                @if($tab == 'artikel')
                    @include('pages.backoffice.artikel._tab_artikel')
                @else
                    @include('pages.backoffice.artikel._tab_kategori')
                @endif
            </div>

        </div>
    </main>

    <!-- COMPONENTS (MODALS & DRAWERS) -->
    @include('pages.backoffice.artikel._editor')
    @include('pages.backoffice.artikel._modals')
</div>
@endsection
