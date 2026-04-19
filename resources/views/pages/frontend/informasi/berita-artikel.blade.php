@extends('layouts.frontend')

@section('title', 'Berita & Artikel - Desa Sindangmukti')

@push('styles')
<style>
    /* Utility class membatasi baris teks (Trunkasi) apabila class Tailwind bawaan belum ter-generate sempurna */
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;  
        overflow: hidden;
    }
</style>
@endpush

@section('content')
<main class="flex-grow pt-16 bg-gray-50">

    <!-- SECTION 1: HEADER SECTION -->
    <section class="relative py-12 overflow-hidden text-white bg-gradient-to-br from-[#2e7d32] to-[#1b5e20] md:py-16">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        <div class="relative z-10 px-4 mx-auto text-center max-w-7xl sm:px-6 lg:px-8">
            <span class="inline-block px-4 py-1 mb-4 text-sm font-semibold border rounded-full bg-green-700/50 text-green-100 border-green-500/30">
                <svg class="inline-block w-4 h-4 mr-1 pb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg> 
                Informasi Publik
            </span>
            <h1 class="mb-4 text-4xl font-bold tracking-tight md:text-5xl">{{ $pageTitle }}</h1>
            <p class="max-w-2xl mx-auto text-lg leading-relaxed text-green-100">
                {{ $pageSubtitle }}
            </p>
        </div>
    </section>

    <div class="px-4 py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">

        <!-- SECTION 2: SEARCH BAR & KATEGORI BERITA -->
        <section class="flex flex-col items-center justify-between gap-4 p-4 mb-10 bg-white border border-gray-200 shadow-sm rounded-2xl md:p-5 md:flex-row">
            
            <!-- Kategori Berita (Filter Pills) -->
            <div class="flex flex-wrap w-full gap-2 md:w-auto">
                <button class="px-4 py-2 text-sm font-semibold text-white transition-colors bg-[#2e7d32] rounded-full shadow-sm hover:bg-[#1b5e20]">
                    Semua
                </button>
                <button class="px-4 py-2 text-sm font-medium text-gray-600 transition-colors border border-gray-200 rounded-full bg-gray-50 hover:bg-gray-100 hover:text-[#2e7d32]">
                    Pemerintahan
                </button>
                <button class="px-4 py-2 text-sm font-medium text-gray-600 transition-colors border border-gray-200 rounded-full bg-gray-50 hover:bg-gray-100 hover:text-[#2e7d32]">
                    Pembangunan
                </button>
                <button class="px-4 py-2 text-sm font-medium text-gray-600 transition-colors border border-gray-200 rounded-full bg-gray-50 hover:bg-gray-100 hover:text-[#2e7d32]">
                    Edukasi
                </button>
            </div>

            <!-- Search Bar -->
            <div class="relative w-full md:w-72 lg:w-96">
                <input type="text" placeholder="Cari artikel..." class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-[#2e7d32] focus:border-[#2e7d32] transition-all">
                <svg class="absolute w-4 h-4 text-gray-400 transform -translate-y-1/2 left-4 top-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </section>

        <!-- SECTION 3: FEATURED NEWS (Sorotan Utama) -->
        <section class="mb-12">
            <div class="flex items-center gap-2 mb-6">
                <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path></svg>
                <h2 class="text-2xl font-bold text-gray-800">Sorotan Utama</h2>
            </div>

            @if($sorotanUtama)
            <article class="flex flex-col overflow-hidden transition-all bg-white border border-gray-200 shadow-md cursor-pointer rounded-2xl md:flex-row group hover:shadow-lg">
                <!-- Image Wrapper -->
                <figure class="relative w-full overflow-hidden h-64 md:h-auto md:w-1/2 lg:w-3/5">
                    <img src="{{ $sorotanUtama['gambar'] }}" alt="{{ $sorotanUtama['judul'] }}" class="object-cover w-full h-full transition-transform duration-700 transform group-hover:scale-105">
                    <div class="absolute top-4 left-4 bg-red-600 text-white text-xs font-bold px-3 py-1.5 rounded-md shadow-md uppercase tracking-wide">
                        Headline
                    </div>
                </figure>
                <!-- Content Wrapper -->
                <div class="flex flex-col justify-center w-full p-6 md:w-1/2 lg:w-2/5 md:p-8">
                    <div class="flex items-center gap-3 mb-3 text-xs font-medium text-gray-500">
                        <span class="flex items-center">
                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> 
                            {{ $sorotanUtama['tanggal'] }}
                        </span>
                        <span class="text-gray-300">|</span>
                        <span class="flex items-center text-[#2e7d32]">
                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg> 
                            {{ $sorotanUtama['kategori'] }}
                        </span>
                    </div>
                    <h3 class="mb-4 text-2xl font-bold text-gray-800 transition-colors lg:text-3xl group-hover:text-[#2e7d32]">
                        {{ $sorotanUtama['judul'] }}
                    </h3>
                    <p class="mb-6 leading-relaxed text-gray-600 line-clamp-3">
                        {{ $sorotanUtama['ringkasan'] }}
                    </p>
                    <div class="flex items-center justify-between mt-auto">
                        <div class="flex items-center gap-2">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($sorotanUtama['penulis']) }}&background=0D8ABC&color=fff" alt="Admin" class="w-8 h-8 rounded-full">
                            <span class="text-sm font-semibold text-gray-700">{{ $sorotanUtama['penulis'] }}</span>
                        </div>
                        <a href="#" class="inline-flex items-center text-[#2e7d32] font-semibold text-sm hover:text-[#1b5e20] transition-colors group-hover:translate-x-1 duration-300">
                            Baca Selengkapnya 
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                </div>
            </article>
            @endif
        </section>

        <!-- SECTION 4: LIST BERITA (Grid Card Artikel) -->
        <section class="mb-14">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Artikel Terbaru</h2>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 md:gap-8">
                @foreach($daftarArtikel as $artikel)
                <!-- Card Artikel -->
                <article class="flex flex-col h-full overflow-hidden transition-all bg-white border border-gray-200 shadow-sm rounded-xl hover:-translate-y-1 hover:shadow-md group">
                    <figure class="relative h-48 overflow-hidden">
                        <img src="{{ $artikel['gambar'] }}" alt="{{ $artikel['judul'] }}" class="object-cover w-full h-full transition-transform duration-500 transform group-hover:scale-105">
                        <span class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm text-green-700 text-xs font-bold px-2.5 py-1 rounded-md shadow-sm">
                            {{ $artikel['kategori'] }}
                        </span>
                    </figure>
                    <div class="flex flex-col flex-grow p-5">
                        <div class="flex items-center gap-3 mb-2 text-xs font-medium text-gray-500">
                            <span class="flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                                {{ $artikel['tanggal'] }}
                            </span>
                        </div>
                        <h3 class="mb-2 text-lg font-bold leading-tight text-gray-800 transition-colors group-hover:text-[#2e7d32]">
                            {{ $artikel['judul'] }}
                        </h3>
                        <p class="flex-grow mb-4 text-sm text-gray-600 line-clamp-3">
                            {{ $artikel['ringkasan'] }}
                        </p>
                        <a href="#" class="block w-full py-2 mt-auto text-sm font-semibold text-center text-green-600 transition-colors border border-gray-200 rounded-lg bg-gray-50 hover:bg-[#2e7d32] hover:text-white">
                            Baca Artikel
                        </a>
                    </div>
                </article>
                @endforeach
            </div>
        </section>

        <!-- SECTION 5: PAGINATION -->
        <section class="flex items-center justify-center gap-2 mt-12">
            <!-- Tombol Sebelumnya -->
            <button class="flex items-center justify-center w-10 h-10 text-gray-400 border border-gray-200 rounded-lg cursor-not-allowed bg-gray-50" disabled aria-label="Halaman sebelumnya">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            
            <!-- Nomor Halaman -->
            <button class="w-10 h-10 flex items-center justify-center rounded-lg bg-[#2e7d32] text-white font-semibold shadow-sm hover:bg-[#1b5e20] transition-colors" aria-current="page">
                1
            </button>
            <button class="flex items-center justify-center w-10 h-10 font-semibold text-gray-600 transition-colors bg-white border border-gray-200 rounded-lg hover:bg-gray-50 hover:text-green-600 hover:border-green-300">
                2
            </button>
            <button class="flex items-center justify-center w-10 h-10 font-semibold text-gray-600 transition-colors bg-white border border-gray-200 rounded-lg hover:bg-gray-50 hover:text-green-600 hover:border-green-300">
                3
            </button>
            
            <span class="px-2 text-gray-400">...</span>
            
            <button class="flex items-center justify-center w-10 h-10 font-semibold text-gray-600 transition-colors bg-white border border-gray-200 rounded-lg hover:bg-gray-50 hover:text-green-600 hover:border-green-300">
                8
            </button>

            <!-- Tombol Selanjutnya -->
            <button class="flex items-center justify-center w-10 h-10 text-gray-600 transition-colors bg-white border border-gray-200 rounded-lg hover:bg-gray-50 hover:text-green-600 hover:border-green-300" aria-label="Halaman selanjutnya">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
        </section>

    </div>
</main>
@endsection
