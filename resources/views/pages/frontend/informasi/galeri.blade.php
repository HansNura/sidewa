@extends('layouts.frontend')

@section('title', 'Galeri Desa - Desa Sindangmukti')

@push('styles')
    <style>
        /* Utility Clamp Text */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
@endpush

@section('content')
    <main class="flex-grow pt-16 bg-gray-50" x-data="galeriData()">

        <!-- SECTION 1: HEADER SECTION -->
        <section class="bg-gradient-to-br from-green-800 to-green-600 text-white py-16 md:py-20 relative overflow-hidden">
            <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]">
            </div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
                <span
                    class="bg-green-700/50 text-green-100 text-sm font-semibold px-3 py-1 rounded-full border border-green-500/30 mb-4 inline-block">Galeri
                    Visual</span>
                <h1 class="text-4xl md:text-5xl font-bold mb-4 tracking-tight">{{ $pageTitle }}</h1>
                <p class="text-lg text-green-100 max-w-2xl mx-auto leading-relaxed">
                    {{ $pageSubtitle }}
                </p>
            </div>
        </section>

        <div class="px-4 py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">

            <!-- SECTION 2: FILTER GALERI -->
            <section class="flex flex-wrap justify-center gap-3 pb-8 mb-10 border-b border-gray-200">
                @foreach ($daftarKategori as $index => $kategori)
                    <button
                        class="px-5 py-2.5 text-sm {{ $index == 0 ? 'bg-green-600 text-white font-semibold shadow-sm hover:bg-green-700' : 'bg-white text-gray-600 border border-gray-300 font-medium hover:bg-green-50 hover:text-green-600 hover:border-green-300' }} rounded-full transition-colors">
                        {{ $kategori }}
                    </button>
                @endforeach
            </section>

            <!-- SECTION 3: GRID FOTO -->
            <section class="mb-16">
                <!-- Grid Container -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 md:gap-8">
                    @foreach ($daftarFoto as $index => $foto)
                        <!-- Foto Card -->
                        <figure @click="openLightbox({{ $index }})"
                            class="relative group cursor-pointer overflow-hidden rounded-2xl shadow-sm border border-gray-200 bg-gray-100 aspect-[4/3]">
                            <!-- Gambar -->
                            <img src="{{ $foto['gambar_url'] }}" alt="{{ $foto['judul'] }}"
                                class="object-cover w-full h-full transition-transform duration-700 transform group-hover:scale-110">
                            <!-- Overlay Hitam Gradien -->
                            <div
                                class="absolute inset-0 flex flex-col justify-end p-5 transition-opacity duration-300 opacity-0 bg-gradient-to-t from-gray-900/90 via-gray-900/20 to-transparent group-hover:opacity-100">
                                <span
                                    class="bg-{{ $foto['kategori_warna'] }}-500 text-white text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider w-max mb-2">
                                    {{ $foto['kategori'] }}
                                </span>
                                <h3 class="mb-1 text-lg font-bold leading-tight text-white">{{ $foto['judul'] }}</h3>
                                <p class="text-xs text-gray-300">
                                    <svg class="inline-block w-3 h-3 mr-1 pb-0.5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    {{ $foto['tanggal'] }}
                                </p>
                            </div>
                            <!-- Ikon Zoom Tengah -->
                            <div
                                class="absolute inset-0 z-10 flex items-center justify-center transition-opacity duration-300 opacity-0 pointer-events-none group-hover:opacity-100">
                                <div
                                    class="flex items-center justify-center w-12 h-12 text-white rounded-full bg-white/20 backdrop-blur-sm">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                        </figure>
                    @endforeach
                </div>
            </section>

            <!-- SECTION 4: PAGINATION GALERI -->
            <section class="flex items-center justify-center gap-2 pb-12 mb-16 border-b border-gray-200">
                <button
                    class="flex items-center justify-center w-10 h-10 text-gray-400 border border-gray-200 rounded-lg cursor-not-allowed bg-gray-50"
                    disabled>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <button
                    class="flex items-center justify-center w-10 h-10 font-semibold text-white bg-[#2e7d32] rounded-lg shadow-sm">1</button>
                <button
                    class="flex items-center justify-center w-10 h-10 font-medium text-gray-600 transition-colors bg-white border border-gray-200 rounded-lg hover:bg-gray-50 hover:text-[#2e7d32]">2</button>
                <button
                    class="flex items-center justify-center w-10 h-10 font-medium text-gray-600 transition-colors bg-white border border-gray-200 rounded-lg hover:bg-gray-50 hover:text-[#2e7d32]">3</button>
                <span class="px-2 text-gray-400">...</span>
                <button
                    class="flex items-center justify-center w-10 h-10 text-gray-600 transition-colors bg-white border border-gray-200 rounded-lg hover:bg-gray-50 hover:text-[#2e7d32]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </section>

            <!-- SECTION 5: VIDEO SECTION (Dokumentasi Video) -->
            <section class="mb-10">
                <div class="flex items-center gap-3 mb-8">
                    <div class="flex items-center justify-center w-12 h-12 text-red-600 bg-red-100 rounded-xl shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Dokumentasi Video</h2>
                        <p class="text-sm text-gray-500">Kumpulan liputan dan video profil desa kami.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                    @foreach ($daftarVideo as $video)
                        <!-- Video Card -->
                        <div
                            class="overflow-hidden transition-shadow bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md group">
                            <!-- Custom Video Thumbnail/Player Wrapper -->
                            <div class="relative overflow-hidden cursor-pointer aspect-video bg-gray-900">
                                <img src="{{ $video['thumbnail'] }}" alt="Thumbnail {{ $video['judul'] }}"
                                    class="object-cover w-full h-full transition-opacity duration-300 opacity-70 group-hover:opacity-50">
                                <!-- Play Button -->
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div
                                        class="flex items-center justify-center w-16 h-16 text-white transition-all bg-red-600 rounded-full shadow-lg group-hover:scale-110 group-hover:bg-red-700">
                                        <svg class="w-6 h-6 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div
                                    class="absolute px-2 py-1 text-xs font-bold text-white rounded bottom-3 right-3 bg-black/80">
                                    {{ $video['durasi'] }}
                                </div>
                            </div>
                            <div class="p-5">
                                <h3
                                    class="mb-2 text-lg font-bold text-gray-800 transition-colors group-hover:text-green-600">
                                    {{ $video['judul'] }}</h3>
                                <p class="text-sm text-gray-600 line-clamp-2">{{ $video['ringkasan'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

        </div>

        <!-- SECTION 6: LIGHTBOX PREVIEW MODAL (Alpine.js) -->
        <!-- Modal full screen untuk melihat gambar galeri lebih detail -->
        <div x-show="lightboxOpen" x-cloak
            class="fixed inset-0 z-[100] flex flex-col items-center justify-center p-4 transition-opacity bg-black/95"
            style="display: none;" @keydown.escape.window="closeLightbox()" @keydown.left.window="prevImage()"
            @keydown.right.window="nextImage()">

            <template x-if="activeImage !== null">
                <div class="relative flex items-center justify-center w-full h-full">
                    <!-- Action Top Bar -->
                    <div
                        class="absolute top-0 left-0 right-0 z-20 flex items-center justify-between p-4 md:p-6 bg-gradient-to-b from-black/70 to-transparent">
                        <p class="text-lg font-semibold text-white md:text-xl drop-shadow-md"
                            x-text="daftarFoto[activeImage].judul"></p>
                        <button @click="closeLightbox()"
                            class="flex items-center justify-center w-10 h-10 text-white transition-all rounded-full hover:text-red-500 bg-black/40 hover:bg-black/60 focus:outline-none shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Tombol Navigasi Kiri Kanan -->
                    <button @click.stop="prevImage()"
                        class="absolute z-20 hidden transition-all -translate-y-1/2 left-4 md:left-8 top-1/2 text-white/50 hover:text-white hover:scale-110 sm:block">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                    </button>
                    <button @click.stop="nextImage()"
                        class="absolute z-20 hidden transition-all -translate-y-1/2 right-4 md:right-8 top-1/2 text-white/50 hover:text-white hover:scale-110 sm:block">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </button>

                    <!-- Overlay Background untuk Klik Luar -->
                    <div class="absolute inset-0 z-0" @click="closeLightbox()"></div>

                    <!-- Main Image Container -->
                    <div
                        class="relative z-10 flex items-center justify-center max-w-5xl w-full h-full max-h-[80vh] mt-8 pointer-events-none">
                        <img :src="daftarFoto[activeImage].gambar_besar" :alt="daftarFoto[activeImage].judul"
                            x-show="lightboxOpen" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-95 -translate-y-10"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            class="object-contain max-w-full max-h-full shadow-2xl rounded-sm pointer-events-auto">
                    </div>
                </div>
            </template>
        </div>

    </main>
@endsection

@push('scripts')
    <script>
        function galeriData() {
            return {
                daftarFoto: @json($daftarFoto),
                lightboxOpen: false,
                activeImage: null,
                openLightbox(index) {
                    this.activeImage = index;
                    this.lightboxOpen = true;
                    document.body.style.overflow = 'hidden';
                },
                closeLightbox() {
                    this.lightboxOpen = false;
                    setTimeout(() => {
                        this.activeImage = null;
                        document.body.style.overflow = 'auto';
                    }, 300);
                },
                prevImage() {
                    if (this.activeImage > 0) {
                        this.activeImage--;
                    } else {
                        this.activeImage = this.daftarFoto.length - 1; // loop ke akhir
                    }
                },
                nextImage() {
                    if (this.activeImage < this.daftarFoto.length - 1) {
                        this.activeImage++;
                    } else {
                        this.activeImage = 0; // loop ke awal
                    }
                }
            }
        }
    </script>
@endpush
