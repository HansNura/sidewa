@extends('layouts.backoffice')

@section('title', 'Manajemen Halaman Publik - Panel Administrasi')

@push('styles')
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

        /* Tree view connector lines */
        .tree-connector {
            position: absolute; left: 1rem; top: 0; bottom: 0; width: 2px;
            background-color: #e2e8f0; z-index: 0;
        }
        .tree-item-line {
            position: absolute; left: 1rem; top: 50%; width: 1rem; height: 2px;
            background-color: #e2e8f0; z-index: 0;
        }

        /* Quill override for builder */
        .ql-toolbar { border: none !important; border-bottom: 1px solid #e2e8f0 !important; background: #f8fafc; padding: 12px !important; }
        .ql-container { border: none !important; font-family: 'Merriweather', serif; font-size: 1.125rem; }
        .ql-editor { padding: 24px !important; min-height: 400px; }
    </style>
@endpush

@section('content')
<div class="flex-1 flex flex-col h-full bg-[#F8FAFC]" x-data="{ 
        activeTab: '{{ $tab }}', 
        editorOpen: false,
        detailDrawerOpen: false,
        previewModalOpen: false,
        selectAll: false,
        selectedRows: [],
        
        // Form & Editor State
        pageId: null,
        pageTitle: '',
        pageSlug: '',
        publishStatus: 'publish',
        previewDevice: 'desktop',
        pageType: 'custom',
        
        generateSlug() {
            if(this.pageType !== 'system') {
                this.pageSlug = this.pageTitle.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');
            }
        },

        async openEditor(id = null) {
            this.pageId = id;
            if(id) {
                const res = await fetch(`/konten/halaman/api/${id}`);
                const data = await res.json();
                
                this.pageTitle = data.title;
                this.pageSlug = data.slug;
                this.publishStatus = data.status;
                this.pageType = data.type;
                
                document.getElementById('editor_title').value = data.title;
                document.getElementById('editor_slug').value = data.slug;
                document.getElementById('editor_status').value = data.status;
                document.getElementById('editor_parent_id').value = data.parent_id || '';
                document.getElementById('editor_meta_title').value = data.meta_title || '';
                document.getElementById('editor_meta_description').value = data.meta_description || '';
                
                if(window.pageEditor) window.pageEditor.root.innerHTML = data.content_html || '';
            } else {
                this.pageTitle = '';
                this.pageSlug = '';
                this.publishStatus = 'publish';
                this.pageType = 'custom';
                document.getElementById('pageEditorForm').reset();
                if(window.pageEditor) window.pageEditor.root.innerHTML = '';
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
                    <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Halaman Publik</h2>
                    <p class="text-sm text-gray-500 mt-1">Kelola halaman statis profil desa, visi misi, layanan, dan struktur navigasi website.</p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <form action="{{ route('admin.halaman.index') }}" method="GET" class="inline-block">
                        <input type="hidden" name="tab" value="structure">
                        <button type="submit" class="bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 shadow-sm rounded-xl px-4 py-2.5 text-sm font-bold transition-all flex items-center gap-2">
                            <i class="fa-solid fa-sitemap text-blue-500"></i> <span class="hidden sm:inline">Atur Struktur Navigasi</span>
                        </button>
                    </form>
                    <button @click="openEditor()" class="bg-green-700 hover:bg-green-800 text-white shadow-md rounded-xl px-5 py-2.5 text-sm font-bold transition-all flex items-center gap-2">
                        <i class="fa-solid fa-file-circle-plus"></i> Buat Halaman Baru
                    </button>
                </div>
            </section>

            <!-- PAGE SUMMARY CARDS -->
            <section class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between group hover:-translate-y-1 transition-transform">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Halaman</p>
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center"><i class="fa-regular fa-file-lines text-xs"></i></div>
                    </div>
                    <h3 class="text-2xl font-extrabold text-gray-900">{{ $totalPages }}</h3>
                </article>

                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between group border-b-4 border-b-green-500 hover:-translate-y-1 transition-transform">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Terpublikasi (Live)</p>
                        <div class="w-8 h-8 rounded-lg bg-green-50 text-green-600 flex items-center justify-center"><i class="fa-solid fa-globe text-xs"></i></div>
                    </div>
                    <h3 class="text-2xl font-extrabold text-green-600">{{ $totalPublished }}</h3>
                </article>

                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between group border-b-4 border-b-amber-500 hover:-translate-y-1 transition-transform">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Draft Disimpan</p>
                        <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center"><i class="fa-solid fa-pen-ruler text-xs"></i></div>
                    </div>
                    <h3 class="text-2xl font-extrabold text-amber-600">{{ $totalDraft }}</h3>
                </article>

                <article class="bg-gray-50 rounded-2xl p-5 border border-gray-200 flex flex-col justify-between group border-b-4 border-b-gray-400">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Halaman Sistem (Terkunci)</p>
                        <div class="w-8 h-8 rounded-lg bg-gray-200 text-gray-600 flex items-center justify-center"><i class="fa-solid fa-lock text-xs"></i></div>
                    </div>
                    <div class="flex items-end gap-2">
                        <h3 class="text-2xl font-extrabold text-gray-700">{{ $totalSystem }}</h3>
                        <span class="text-[10px] font-medium text-gray-500 mb-1">Beranda, Profil, dll</span>
                    </div>
                </article>
            </section>

            <!-- TABS CONTROL -->
            <section class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-3 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex gap-2 p-1 bg-gray-100 rounded-xl w-full md:w-auto overflow-x-auto">
                    <form action="{{ route('admin.halaman.index') }}" method="GET" class="flex-1 md:flex-none">
                        <input type="hidden" name="tab" value="list">
                        <button type="submit" :class="activeTab === 'list' ? 'bg-white shadow-sm text-green-700 font-bold' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-2 text-sm rounded-lg transition-all whitespace-nowrap w-full"><i class="fa-solid fa-list mr-1"></i> Daftar Halaman</button>
                    </form>
                    <form action="{{ route('admin.halaman.index') }}" method="GET" class="flex-1 md:flex-none">
                        <input type="hidden" name="tab" value="structure">
                        <button type="submit" :class="activeTab === 'structure' ? 'bg-white shadow-sm text-green-700 font-bold' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-2 text-sm rounded-lg transition-all whitespace-nowrap w-full"><i class="fa-solid fa-sitemap mr-1"></i> Struktur Menu Navigasi</button>
                    </form>
                </div>
            </section>

            <!-- TAB CONTENT -->
            @if($tab == 'list')
                @include('pages.backoffice.halaman._tab_list')
            @else
                @include('pages.backoffice.halaman._tab_navigasi')
            @endif

        </div>
    </main>

    @include('pages.backoffice.halaman._editor')
</div>
@endsection
