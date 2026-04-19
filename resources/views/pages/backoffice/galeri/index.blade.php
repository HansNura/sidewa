@extends('layouts.backoffice')

@section('title', 'Manajemen Galeri - Panel Administrasi')

@push('styles')
    <style>
        [x-cloak] { display: none !important; }
        .media-overlay {
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0) 50%);
        }
    </style>
@endpush

@section('content')
<div class="flex-1 flex flex-col h-full bg-[#F8FAFC]" x-data="{ 
        activeTab: '{{ $tab }}', 
        uploadModalOpen: false,
        detailDrawerOpen: false,
        albumModalOpen: false,
        previewModalOpen: false,
        previewDevice: 'desktop',
        
        // Data Media Terpilih
        selectedMedia: {
            id: null,
            url: '',
            title: '',
            album: '',
            date: '',
            size: '',
            type: '',
            uploader: ''
        },

        openDetail(media) {
            this.selectedMedia = media;
            this.detailDrawerOpen = true;
        }
    }">

    <header class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4 shrink-0 px-4 sm:px-6 pt-6">
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
        @if(session('error'))
            <div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm font-bold shadow-sm">
                <i class="fa-solid fa-circle-xmark mr-2"></i>{{ session('error') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm font-bold shadow-sm">
                <ul class="list-disc ml-5">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="max-w-7xl mx-auto space-y-6">

            <section class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Galeri & Media</h2>
                    <p class="text-sm text-gray-500 mt-1">Kelola aset visual, foto, dan video dokumentasi kegiatan desa.</p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <button @click="previewModalOpen = true" class="bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 shadow-sm rounded-xl px-4 py-2.5 text-sm font-bold transition-all flex items-center gap-2">
                        <i class="fa-solid fa-desktop text-green-600"></i> <span class="hidden sm:inline">Preview Web</span>
                    </button>
                    <button @click="albumModalOpen = true" class="bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 shadow-sm rounded-xl px-4 py-2.5 text-sm font-bold transition-all flex items-center gap-2">
                        <i class="fa-solid fa-folder-plus text-amber-500"></i> <span class="hidden sm:inline">Buat Album</span>
                    </button>
                    <button @click="uploadModalOpen = true" class="bg-green-700 hover:bg-green-800 text-white shadow-md rounded-xl px-5 py-2.5 text-sm font-bold transition-all flex items-center gap-2">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Upload Media
                    </button>
                </div>
            </section>

            <!-- SUMMARY CARDS -->
            <section class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between group hover:-translate-y-1 transition-transform">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Media</p>
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center"><i class="fa-solid fa-images text-xs"></i></div>
                    </div>
                    <h3 class="text-2xl font-extrabold text-gray-900">{{ $totalMedia }}</h3>
                </article>
                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between group hover:-translate-y-1 transition-transform">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Album</p>
                        <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center"><i class="fa-solid fa-folder-open text-xs"></i></div>
                    </div>
                    <h3 class="text-2xl font-extrabold text-gray-900">{{ $totalAlbum }}</h3>
                </article>
                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between group hover:-translate-y-1 transition-transform">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Video Uploaded</p>
                        <div class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center"><i class="fa-solid fa-video text-xs"></i></div>
                    </div>
                    <h3 class="text-2xl font-extrabold text-gray-900">{{ $totalVideo }}</h3>
                </article>
                <article class="bg-gray-900 rounded-2xl p-5 text-white shadow-xl flex flex-col justify-between border-l-4 border-l-green-500 hover:-translate-y-1 transition-transform">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-[10px] font-bold text-green-400 uppercase tracking-wider">Storage Terpakai</p>
                        <div class="w-8 h-8 rounded-lg bg-gray-800 text-green-400 flex items-center justify-center"><i class="fa-solid fa-hard-drive text-xs"></i></div>
                    </div>
                    <h3 class="text-2xl font-extrabold">{{ $formattedStorageVal }} <span class="text-sm font-medium text-gray-400">{{ $formattedStorageUnit }}</span></h3>
                </article>
            </section>

            <!-- TABS CONTROL -->
            <section class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-3 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex gap-2 p-1 bg-gray-100 rounded-xl w-full md:w-auto overflow-x-auto">
                    <form action="{{ route('admin.galeri.index') }}" method="GET" class="flex-1 md:flex-none">
                        <input type="hidden" name="tab" value="media">
                        <button type="submit" :class="activeTab === 'media' ? 'bg-white shadow-sm text-green-700 font-bold' : 'text-gray-500 hover:text-gray-700'" class="w-full px-6 py-2 text-sm rounded-lg transition-all whitespace-nowrap">Semua Media</button>
                    </form>
                    <form action="{{ route('admin.galeri.index') }}" method="GET" class="flex-1 md:flex-none">
                        <input type="hidden" name="tab" value="album">
                        <button type="submit" :class="activeTab === 'album' ? 'bg-white shadow-sm text-green-700 font-bold' : 'text-gray-500 hover:text-gray-700'" class="w-full px-6 py-2 text-sm rounded-lg transition-all whitespace-nowrap">Manajemen Album</button>
                    </form>
                </div>
            </section>

            <!-- TAB CONTENT -->
            @if($tab == 'media')
                @include('pages.backoffice.galeri._tab_media')
            @else
                @include('pages.backoffice.galeri._tab_album')
            @endif

        </div>
    </main>

    <!-- COMPONENTS (MODALS & DRAWERS) -->
    @include('pages.backoffice.galeri._modals')
</div>
@endsection
