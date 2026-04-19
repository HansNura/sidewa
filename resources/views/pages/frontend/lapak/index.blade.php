@extends('layouts.frontend')

@section('title', 'Lapak Desa - Desa Sindangmukti')

@push('styles')
<style>
    /* Utility Clamp Text untuk Judul Produk */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .hide-scroll-bar::-webkit-scrollbar {
        display: none;
    }
    .hide-scroll-bar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
@endpush

@section('content')
<main class="flex-grow pt-16 bg-gray-50">

    <!-- SECTION 1: HEADER LAPAK DESA -->
    <section class="relative py-12 overflow-hidden text-white bg-gradient-to-br from-green-900 to-green-700 md:py-16">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        <div class="relative z-10 px-4 mx-auto text-center max-w-7xl sm:px-6 lg:px-8">
            <span class="bg-green-800/80 text-green-100 text-sm font-semibold px-4 py-1.5 rounded-full border border-green-500/50 mb-4 inline-flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg> 
                UMKM & Ekonomi Kreatif
            </span>
            <h1 class="mb-4 text-4xl font-bold tracking-tight md:text-5xl">{{ $pageTitle }}</h1>
            <p class="max-w-2xl mx-auto text-lg leading-relaxed text-green-100">
                {{ $pageSubtitle }}
            </p>
        </div>
    </section>

    <div class="px-4 py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">

        <!-- SECTION 2: BANNER PROMOSI (Highlight Event/Produk) -->
        <section class="mb-10">
            <div class="relative overflow-hidden shadow-md cursor-pointer bg-gradient-to-r from-amber-500 to-orange-500 rounded-2xl group">
                <!-- Ornamen -->
                <div class="absolute top-0 right-0 h-full transform origin-top-right transition-transform duration-700 w-64 bg-white/10 skew-x-12 group-hover:scale-110"></div>

                <div class="relative z-10 flex flex-col items-center justify-between px-8 py-8 md:p-10 md:flex-row">
                    <div class="mb-6 text-center text-white md:w-2/3 md:text-left md:mb-0">
                        <span class="inline-block px-3 py-1 mb-3 text-xs font-bold tracking-wider text-white uppercase rounded bg-white/20 backdrop-blur-sm">Promo Spesial Bulan Ini</span>
                        <h2 class="mb-2 text-2xl font-bold md:text-3xl">Festival Kerajinan Bambu Sindangmukti</h2>
                        <p class="text-sm opacity-90 text-amber-50 md:text-base">Dapatkan diskon hingga 20% untuk semua produk anyaman bambu asli buatan pengrajin lokal desa kami.</p>
                    </div>
                    <div class="shrink-0">
                        <button class="flex items-center gap-2 px-6 py-3 font-bold transition-transform transform bg-white rounded-xl shadow-lg text-amber-600 hover:bg-gray-50 hover:-translate-y-1">
                            Lihat Koleksi 
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION 3 & 4: SEARCH, FILTER & SORT OPTION -->
        <section class="sticky flex flex-col justify-between gap-4 p-4 mb-10 bg-white border border-gray-200 shadow-sm md:p-5 rounded-2xl lg:flex-row top-20 z-40">

            <!-- Kategori / Filter Tag -->
            <div class="flex gap-2 pb-2 overflow-x-auto md:pb-0 hide-scroll-bar flex-grow">
                <button class="px-5 py-2.5 bg-green-600 text-white text-sm font-semibold rounded-full shadow-sm shrink-0">
                    Semua
                </button>
                <button class="px-5 py-2.5 bg-gray-50 text-gray-600 border border-gray-200 text-sm font-medium rounded-full hover:bg-green-50 hover:text-green-600 hover:border-green-300 transition-colors shrink-0 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z"></path></svg>
                    Makanan
                </button>
                <button class="px-5 py-2.5 bg-gray-50 text-gray-600 border border-gray-200 text-sm font-medium rounded-full hover:bg-green-50 hover:text-green-600 hover:border-green-300 transition-colors shrink-0 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    Kerajinan
                </button>
                <button class="px-5 py-2.5 bg-gray-50 text-gray-600 border border-gray-200 text-sm font-medium rounded-full hover:bg-green-50 hover:text-green-600 hover:border-green-300 transition-colors shrink-0 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                    Kesehatan
                </button>
                <button class="px-5 py-2.5 bg-gray-50 text-gray-600 border border-gray-200 text-sm font-medium rounded-full hover:bg-green-50 hover:text-green-600 hover:border-green-300 transition-colors shrink-0 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Pertanian
                </button>
            </div>

            <div class="flex flex-col gap-3 shrink-0 sm:flex-row">
                <!-- Search Bar -->
                <div class="relative w-full sm:w-64">
                    <input type="text" placeholder="Cari produk..." class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
                    <svg class="absolute w-4 h-4 text-gray-400 transform -translate-y-1/2 left-3 top-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>

                <!-- Sort Option -->
                <select class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 p-2.5 font-medium outline-none cursor-pointer">
                    <option value="terbaru">Terbaru</option>
                    <option value="populer">Terpopuler</option>
                    <option value="harga_rendah">Harga: Rendah ke Tinggi</option>
                    <option value="harga_tinggi">Harga: Tinggi ke Rendah</option>
                </select>
            </div>
        </section>

        <!-- SECTION 5: GRID PRODUK UMKM -->
        <section class="mb-14">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">

                @foreach($daftarProduk as $produk)
                <article class="flex flex-col overflow-hidden transition-all bg-white border border-gray-200 shadow-sm rounded-2xl hover:shadow-lg hover:-translate-y-1 group">
                    <figure class="relative overflow-hidden bg-gray-100 aspect-square">
                        <img src="{{ $produk['gambar'] }}" alt="{{ $produk['nama'] }}" class="object-cover w-full h-full transition-transform duration-500 group-hover:scale-105">
                        <span class="absolute top-3 left-3 bg-white/90 backdrop-blur text-gray-800 text-xs font-bold px-2.5 py-1 rounded-md shadow-sm">
                            {{ $produk['kategori'] }}
                        </span>
                    </figure>
                    <div class="flex flex-col flex-grow p-4">
                        <a href="{{ route('lapak.detail', $produk['slug']) }}" class="block mb-1 font-bold leading-tight text-gray-800 transition-colors line-clamp-2 group-hover:text-green-600">
                            {{ $produk['nama'] }}
                        </a>
                        <div class="flex items-center gap-2 mb-3">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            <span class="text-xs font-medium text-gray-500">{{ $produk['toko'] }}</span>
                            @if($produk['terverifikasi'])
                                <svg class="w-3.5 h-3.5 text-blue-500" title="Terverifikasi Desa" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            @endif
                        </div>
                        <div class="mt-auto">
                            <p class="mb-4 text-lg font-black text-green-700">{{ $produk['harga'] }}</p>
                            <a href="{{ route('lapak.detail', $produk['slug']) }}" class="flex items-center justify-center w-full gap-2 py-2.5 text-sm font-bold text-green-700 transition-colors border border-green-200 bg-green-50 hover:bg-green-600 hover:text-white rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </article>
                @endforeach

            </div>
        </section>

        <!-- SECTION 6: PAGINATION / LOAD MORE -->
        <section class="flex justify-center mb-16">
            <button class="flex items-center gap-2 px-8 py-3 font-bold text-green-600 transition-all bg-white border-2 border-green-600 shadow-sm hover:bg-green-600 hover:text-white rounded-full">
                <svg class="w-5 h-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> 
                Muat Lebih Banyak
            </button>
        </section>

        <!-- SECTION 7: CTA UMKM (Ajakan Bergabung) -->
        <section class="relative overflow-hidden border border-green-800 shadow-xl bg-green-900 rounded-3xl">
            <div class="absolute w-64 h-64 bg-green-500 rounded-full opacity-20 -left-10 -bottom-10 blur-3xl"></div>
            <div class="absolute right-10 top-10 text-white/5">
                <svg class="w-32 h-32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>

            <div class="relative z-10 flex flex-col items-center justify-between gap-8 px-8 py-12 text-center md:flex-row md:px-14 md:py-16 md:text-left">
                <div class="md:w-2/3">
                    <h2 class="mb-4 text-3xl font-bold text-white md:text-4xl">Punya Usaha di Desa Sindangmukti?</h2>
                    <p class="mb-0 text-lg leading-relaxed text-green-100">
                        Ayo perluas jangkauan pasar Anda. Daftarkan produk UMKM, kerajinan, atau hasil tani Anda secara gratis di Lapak Desa dan temukan pembeli baru.
                    </p>
                </div>

                <div class="flex justify-center w-full shrink-0 md:justify-end md:w-1/3">
                    <a href="#" class="flex items-center justify-center w-full gap-2 px-8 py-4 font-bold text-white transition-transform transform shadow-lg bg-amber-500 hover:bg-amber-600 rounded-2xl hover:scale-105 md:w-auto">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg> 
                        Daftar Penjual
                    </a>
                </div>
            </div>
        </section>

    </div>
</main>
@endsection
