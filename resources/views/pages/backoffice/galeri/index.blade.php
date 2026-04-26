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
        },

        filePreviews: [],
        allFiles: [],
        uploadLightboxOpen: false,
        uploadLightboxIndex: null,

        handleFileChange(event) {
            const newFiles = Array.from(event.target.files);
            const input = document.getElementById('upload-input');
            const dt = new DataTransfer();

            // 1. Ambil file yang sudah ada di previews (jika ada)
            // Kita perlu menyimpan referensi File asli. 
            // Mari kita modifikasi struktur filePreviews untuk menyimpan objek File.
            
            // Re-sync DataTransfer dengan file lama + file baru
            if(!this.allFiles) this.allFiles = [];
            
            newFiles.forEach(file => {
                // Opsional: Cek duplikat berdasarkan nama & ukuran
                const isDuplicate = this.allFiles.some(f => f.name === file.name && f.size === file.size);
                if (!isDuplicate) {
                    this.allFiles.push(file);
                    
                    // Buat Preview untuk file baru ini saja
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.filePreviews.push({
                            url: e.target.result,
                            name: file.name,
                            type: file.type,
                            size: (file.size / 1024).toFixed(1) + ' KB'
                        });
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Update input.files agar berisi semua file di allFiles
            this.allFiles.forEach(file => dt.items.add(file));
            input.files = dt.files;
        },

        removeFile(index) {
            this.filePreviews.splice(index, 1);
            this.allFiles.splice(index, 1);
            
            // Sinkronisasi dengan input file
            const input = document.getElementById('upload-input');
            const dt = new DataTransfer();
            this.allFiles.forEach(file => dt.items.add(file));
            input.files = dt.files;
        },

        openUploadLightbox(index) {
            this.uploadLightboxIndex = index;
            this.uploadLightboxOpen = true;
        }
    }">

    <!-- TOP NAVBAR -->
    <header class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4 shrink-0">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Manajemen Konten Publik</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola aset visual, foto, dan video dokumentasi kegiatan desa.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <button @click="previewModalOpen = true" class="bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 shadow-sm rounded-xl px-4 py-2.5 text-sm font-bold transition-all flex items-center gap-2">
                <i class="fa-solid fa-desktop text-green-600"></i> <span class="hidden sm:inline">Preview Web</span>
            </button>
            <button @click="albumModalOpen = true" class="bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 shadow-sm rounded-xl px-4 py-2.5 text-sm font-bold transition-all flex items-center gap-2">
                <i class="fa-solid fa-folder-plus text-amber-500"></i> <span class="hidden sm:inline">Buat Album</span>
            </button>
            <button @click="uploadModalOpen = true; filePreviews = []; allFiles = []" class="bg-green-700 hover:bg-green-800 text-white shadow-md rounded-xl px-5 py-2.5 text-sm font-bold transition-all flex items-center gap-2">
                <i class="fa-solid fa-cloud-arrow-up"></i> Upload Media
            </button>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto pb-8 custom-scrollbar">
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

            <!-- SUMMARY CARDS -->
            <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                {{-- Total Media --}}
                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-all flex items-center gap-4 group relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-2 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity text-blue-600">
                        <i class="fa-solid fa-images text-7xl rotate-12"></i>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 text-xl shadow-inner group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-images"></i>
                    </div>
                    <div class="relative z-10">
                        <p class="text-[10px] font-bold text-blue-400 uppercase tracking-widest mb-0.5">Total Media</p>
                        <h3 class="text-2xl font-black text-blue-700 tracking-tight leading-none">{{ $totalMedia }}</h3>
                    </div>
                </article>

                {{-- Total Album --}}
                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-all flex items-center gap-4 group relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-2 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity text-amber-600">
                        <i class="fa-solid fa-folder-open text-7xl rotate-12"></i>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 text-xl shadow-inner group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-folder-open"></i>
                    </div>
                    <div class="relative z-10">
                        <p class="text-[10px] font-bold text-amber-400 uppercase tracking-widest mb-0.5">Total Album</p>
                        <h3 class="text-2xl font-black text-amber-700 tracking-tight leading-none">{{ $totalAlbum }}</h3>
                    </div>
                </article>

                {{-- Video Uploaded --}}
                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-all flex items-center gap-4 group relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-2 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity text-rose-600">
                        <i class="fa-solid fa-video text-7xl rotate-12"></i>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0 text-xl shadow-inner group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-video"></i>
                    </div>
                    <div class="relative z-10">
                        <p class="text-[10px] font-bold text-rose-400 uppercase tracking-widest mb-0.5">Video Uploaded</p>
                        <h3 class="text-2xl font-black text-rose-700 tracking-tight leading-none">{{ $totalVideo }}</h3>
                    </div>
                </article>

                {{-- Storage Terpakai --}}
                <article class="bg-gray-900 rounded-2xl p-5 border border-gray-800 shadow-xl hover:-translate-y-1 transition-all flex items-center gap-4 group relative overflow-hidden border-l-4 border-l-green-500">
                    <div class="absolute top-0 right-0 p-2 opacity-[0.05] group-hover:opacity-[0.1] transition-opacity text-green-400">
                        <i class="fa-solid fa-hard-drive text-7xl rotate-12"></i>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-gray-800 text-green-400 flex items-center justify-center shrink-0 text-xl shadow-inner group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-hard-drive"></i>
                    </div>
                    <div class="relative z-10">
                        <p class="text-[10px] font-bold text-green-400 uppercase tracking-widest mb-0.5">Storage Terpakai</p>
                        <h3 class="text-2xl font-black text-white tracking-tight leading-none">{{ $formattedStorageVal }} <span class="text-sm font-medium text-gray-400">{{ $formattedStorageUnit }}</span></h3>
                    </div>
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
            <div class="pt-2">
                @if($tab == 'media')
                    @include('pages.backoffice.galeri._tab_media')
                @else
                    @include('pages.backoffice.galeri._tab_album')
                @endif
            </div>

        </div>
    </main>

    <!-- COMPONENTS (MODALS & DRAWERS) -->
    @include('pages.backoffice.galeri._modals')
</div>
@endsection
