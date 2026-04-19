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
        selectAll: false,
        selectedRows: [],
        
        // Editor State
        articleId: null,
        articleTitle: '',
        publishStatus: 'draft',
        
        // Modal Preview State
        previewDevice: 'desktop',
        
        // Fetch logic
        async openEditor(id = null) {
            this.articleId = id;
            if(id) {
                // Fetch existing article data (stub implementation, assuming quill initialization below hooks into this somehow)
                const res = await fetch(`/konten/berita/api/${id}`);
                const data = await res.json();
                this.articleTitle = data.judul;
                this.publishStatus = data.status;
                // Set form data and Quill HTML...
                document.getElementById('editor_title').value = data.judul;
                document.getElementById('editor_status').value = data.status;
                document.getElementById('editor_slug').value = data.slug;
                document.getElementById('editor_kategori_id').value = data.kategori_id;
                
                // Assuming we use a global quill instance
                window.contentEditor.root.innerHTML = data.konten_html;
            } else {
                this.articleTitle = '';
                this.publishStatus = 'draft';
                document.getElementById('editorForm').reset();
                if(window.contentEditor) window.contentEditor.root.innerHTML = '';
            }
            this.editorOpen = true;
        }
    }">

    <header class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4 shrink-0 px-4 sm:px-6 pt-6 -z-10">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-semibold px-2.5 py-1 bg-amber-100 text-amber-700 rounded-md uppercase tracking-wider">Akses Publikasi</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Manajemen Konten Publik</h1>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto px-4 sm:px-6 lg:px-8 pb-8 custom-scrollbar">
        @if(session('success'))
            <div class="mb-4 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm font-bold shadow-sm">
                <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
            </div>
        @endif

        <div class="max-w-7xl mx-auto space-y-6">

            <!-- HEADER ACTIONS -->
            <section class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Manajemen Berita & Artikel</h2>
                    <p class="text-sm text-gray-500 mt-1">Buat, kelola, dan publikasikan informasi terbaru untuk website publik.</p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <button class="bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 shadow-sm rounded-xl px-4 py-2.5 text-sm font-bold transition-all flex items-center gap-2">
                        <i class="fa-solid fa-arrow-up-right-from-square text-blue-500"></i> <span class="hidden sm:inline">Kunjungi Web Publik</span>
                    </button>
                    <button @click="openEditor()" class="bg-green-700 hover:bg-green-800 text-white shadow-md rounded-xl px-5 py-2.5 text-sm font-bold transition-all flex items-center gap-2">
                        <i class="fa-solid fa-pen-nib"></i> Tulis Artikel Baru
                    </button>
                </div>
            </section>

            <!-- SUMMARY CARDS -->
            <section class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between group">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Artikel</p>
                        <div class="w-8 h-8 rounded-lg bg-gray-50 text-gray-500 flex items-center justify-center"><i class="fa-solid fa-file-lines text-xs"></i></div>
                    </div>
                    <h3 class="text-2xl font-extrabold text-gray-900">{{ $totalArticles }}</h3>
                </article>

                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between group border-b-4 border-b-green-500">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Terpublikasi</p>
                        <div class="w-8 h-8 rounded-lg bg-green-50 text-green-600 flex items-center justify-center"><i class="fa-solid fa-globe text-xs"></i></div>
                    </div>
                    <h3 class="text-2xl font-extrabold text-green-600">{{ $totalPublished }}</h3>
                </article>

                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between group border-b-4 border-b-amber-500">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Draft & Terjadwal</p>
                        <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center"><i class="fa-solid fa-pen-ruler text-xs"></i></div>
                    </div>
                    <h3 class="text-2xl font-extrabold text-amber-600">{{ $totalDraftScheduled }}</h3>
                </article>

                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between group border-b-4 border-b-gray-400">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Diarsipkan (Private)</p>
                        <div class="w-8 h-8 rounded-lg bg-gray-100 text-gray-500 flex items-center justify-center"><i class="fa-solid fa-box-archive text-xs"></i></div>
                    </div>
                    <h3 class="text-2xl font-extrabold text-gray-600">{{ $totalArchived }}</h3>
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
            @if($tab == 'artikel')
                @include('pages.backoffice.artikel._tab_artikel')
            @else
                @include('pages.backoffice.artikel._tab_kategori')
            @endif

        </div>
    </main>

    <!-- COMPONENTS (MODALS & DRAWERS) -->
    @include('pages.backoffice.artikel._editor')
    @include('pages.backoffice.artikel._modals')
</div>
@endsection
