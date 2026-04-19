@extends('layouts.frontend')

@section('title', 'JDIH & Produk Hukum Desa - Desa Sindangmukti')

@section('content')
<main class="flex-grow pt-16 bg-gray-50" x-data="hukumData()">

    <!-- SECTION 1: HEADER SECTION -->
    <section class="relative py-12 overflow-hidden text-white bg-gradient-to-br from-[#2e7d32] to-[#1b5e20] md:py-16">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]">
        </div>
        <div class="relative z-10 px-4 mx-auto text-center max-w-7xl sm:px-6 lg:px-8">
            <span class="inline-block px-4 py-1 mb-4 text-sm font-semibold border rounded-full bg-green-700/50 text-green-100 border-green-500/30">
                <svg class="inline-block w-4 h-4 mr-1 pb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg> 
                Informasi Publik
            </span>
            <h1 class="mb-4 text-4xl font-bold tracking-tight md:text-5xl">{{ $pageTitle }}</h1>
            <p class="max-w-2xl mx-auto text-lg leading-relaxed text-green-100">
                {{ $pageSubtitle }}
            </p>
        </div>
    </section>

    <div class="px-4 py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">

        <!-- SECTION 2: KATEGORI HUKUM (Statistik Cepat) -->
        <section class="grid grid-cols-2 gap-4 mb-10 md:grid-cols-4">
            <!-- Card Kategori 1 -->
            <div class="flex items-center gap-4 p-5 transition-colors bg-white border border-gray-200 cursor-pointer rounded-2xl shadow-sm hover:border-green-500 group">
                <div class="flex items-center justify-center w-12 h-12 transition-colors border rounded-full shrink-0 border-blue-200 bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $statistikHukum['perdes'] }}</p>
                    <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Perdes</p>
                </div>
            </div>
            <!-- Card Kategori 2 -->
            <div class="flex items-center gap-4 p-5 transition-colors bg-white border border-gray-200 cursor-pointer rounded-2xl shadow-sm hover:border-emerald-500 group">
                <div class="flex items-center justify-center w-12 h-12 transition-colors border rounded-full shrink-0 border-emerald-200 bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $statistikHukum['sk_kades'] }}</p>
                    <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase">SK Kades</p>
                </div>
            </div>
            <!-- Card Kategori 3 -->
            <div class="flex items-center gap-4 p-5 transition-colors bg-white border border-gray-200 cursor-pointer rounded-2xl shadow-sm hover:border-amber-500 group">
                <div class="flex items-center justify-center w-12 h-12 transition-colors border border-amber-200 rounded-full shrink-0 bg-amber-50 text-amber-600 group-hover:bg-amber-600 group-hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $statistikHukum['perkades'] }}</p>
                    <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Perkades</p>
                </div>
            </div>
            <!-- Card Kategori 4 -->
            <div class="flex items-center gap-4 p-5 transition-colors bg-white border border-gray-200 cursor-pointer rounded-2xl shadow-sm hover:border-purple-500 group">
                <div class="flex items-center justify-center w-12 h-12 transition-colors border border-purple-200 rounded-full shrink-0 bg-purple-50 text-purple-600 group-hover:bg-purple-600 group-hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M36 18L24 6 12 18M24 6v24"></path></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $statistikHukum['kep_bpd'] }}</p>
                    <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Kep. BPD</p>
                </div>
            </div>
        </section>

        <!-- SECTION 3: SEARCH & FILTER -->
        <section class="p-4 mb-8 bg-white border border-gray-200 shadow-sm md:p-6 rounded-2xl">
            <form class="flex flex-col items-end gap-4 md:flex-row">

                <!-- Search Bar -->
                <div class="w-full md:w-2/5">
                    <label class="block mb-1 text-sm font-medium text-gray-700">Cari Dokumen</label>
                    <div class="relative">
                        <input type="text" placeholder="Masukkan judul, nomor, atau tentang..." class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm focus:ring-[#2e7d32] focus:border-[#2e7d32]">
                        <svg class="absolute w-4 h-4 text-gray-400 transform -translate-y-1/2 left-3.5 top-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>

                <!-- Filter Kategori -->
                <div class="w-full md:w-1/5">
                    <label class="block mb-1 text-sm font-medium text-gray-700">Jenis Aturan</label>
                    <select class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#2e7d32] focus:border-[#2e7d32] p-2.5">
                        <option value="">Semua Jenis</option>
                        <option value="perdes">Peraturan Desa (Perdes)</option>
                        <option value="sk">SK Kepala Desa</option>
                        <option value="perkades">Peraturan Kepala Desa</option>
                    </select>
                </div>

                <!-- Filter Tahun -->
                <div class="w-full md:w-1/5">
                    <label class="block mb-1 text-sm font-medium text-gray-700">Tahun</label>
                    <select class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#2e7d32] focus:border-[#2e7d32] p-2.5">
                        <option value="">Semua Tahun</option>
                        <option value="2024">2024</option>
                        <option value="2023">2023</option>
                        <option value="2022">2022</option>
                    </select>
                </div>

                <!-- Tombol Cari -->
                <div class="w-full md:w-1/5">
                    <button type="button" class="w-full text-white bg-[#2e7d32] hover:bg-[#1b5e20] font-semibold rounded-lg text-sm px-5 py-2.5 transition-colors flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg> 
                        Terapkan
                    </button>
                </div>
            </form>
        </section>

        <!-- SECTION 4: LIST DOKUMEN HUKUM -->
        <section class="mb-12">
            <div class="flex items-center justify-between pb-2 mb-6 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-800">Daftar Produk Hukum</h2>
                <span class="text-sm text-gray-500">Menampilkan 1-10 dari 199 dokumen</span>
            </div>

            <div class="flex flex-col gap-4">
                @foreach($daftarHukum as $index => $hukum)
                <!-- Item Dokumen -->
                <!-- Jika status dicabut, berikan background abu-abu, jika tidak putih/group hover -->
                <article class="{{ $hukum['status'] == 'Berlaku' ? 'bg-white hover:border-green-400' : 'bg-gray-50' }} rounded-xl shadow-sm border border-gray-200 p-5 flex flex-col md:flex-row gap-5 items-start md:items-center hover:-translate-y-1 hover:shadow-md transition-all group">
                    <!-- Format Icon -->
                    <div class="bg-{{ $hukum['tipe_warna'] }}-50 text-{{ $hukum['tipe_warna'] }}-500 w-14 h-14 rounded-lg flex items-center justify-center shrink-0 border border-{{ $hukum['tipe_warna'] }}-100">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    </div>

                    <!-- Metadata Utama -->
                    <div class="flex-grow">
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="bg-{{ $hukum['kategori_warna'] }}-100 text-{{ $hukum['kategori_warna'] }}-700 text-xs font-bold px-2.5 py-0.5 rounded border border-{{ $hukum['kategori_warna'] }}-200">
                                {{ $hukum['kategori'] }}
                            </span>
                            <span class="bg-{{ $hukum['status_warna'] }}-100 text-{{ $hukum['status_warna'] }}-700 text-xs font-bold px-2.5 py-0.5 rounded border border-{{ $hukum['status_warna'] }}-200 flex items-center">
                                @if($hukum['status'] == 'Berlaku')
                                <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                @else
                                <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-12.728 12.728M5.636 5.636l12.728 12.728"></path></svg>
                                @endif
                                {{ $hukum['status'] }}
                            </span>
                        </div>
                        <h3 class="font-bold text-lg {{ $hukum['status'] == 'Berlaku' ? 'text-gray-800 group-hover:text-green-700' : 'text-gray-600 line-through' }} mb-1 transition-colors">
                            {{ $hukum['judul'] }}
                        </h3>
                        <p class="text-sm font-medium {{ $hukum['status'] == 'Berlaku' ? 'text-gray-600' : 'text-gray-500' }}">
                            Tentang: {{ $hukum['tentang'] }}
                        </p>
                        @if(isset($hukum['keterangan_status']))
                        <p class="mt-1 text-xs italic text-red-600">{{ $hukum['keterangan_status'] }}</p>
                        @endif
                        <div class="flex gap-4 mt-3 text-xs {{ $hukum['status'] == 'Berlaku' ? 'text-gray-500' : 'text-gray-400' }} font-medium">
                            <span class="flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> 
                                Ditetapkan: {{ $hukum['ditetapkan'] }}
                            </span>
                            @if($hukum['unduhan'] > 0)
                            <span class="flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg> 
                                {{ $hukum['unduhan'] }} Unduhan
                            </span>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-row w-full gap-2 pt-4 border-t shrink-0 md:w-auto md:flex-col md:border-t-0 md:border-l border-gray-100 md:pt-0 md:pl-5">
                        @if($hukum['status'] == 'Berlaku')
                            <button @click="openModal({{ $index }})" class="flex items-center justify-center flex-1 gap-2 px-4 py-2 text-sm font-semibold text-green-700 transition-colors border border-gray-200 rounded-lg text-center bg-gray-50 hover:bg-green-50 hover:border-green-300 md:flex-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg> 
                                Detail
                            </button>
                            <a href="{{ $hukum['detail']['link_unduh'] ?? '#' }}" class="flex items-center justify-center flex-1 gap-2 px-4 py-2 text-sm font-semibold text-white transition-colors bg-green-600 rounded-lg shadow-sm text-center hover:bg-green-700 md:flex-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg> 
                                Unduh
                            </a>
                        @else
                            <button @click="openModal({{ $index }})" class="flex items-center justify-center flex-1 gap-2 px-4 py-2 text-sm font-semibold text-gray-700 transition-colors border border-gray-300 rounded-lg text-center bg-gray-100 hover:bg-gray-200 md:flex-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg> 
                                Detail
                            </button>
                            <a href="#" class="flex items-center justify-center flex-1 gap-2 px-4 py-2 text-sm font-semibold text-white bg-gray-400 rounded-lg shadow-sm cursor-not-allowed text-center md:flex-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg> 
                                Arsip
                            </a>
                        @endif
                    </div>
                </article>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-center gap-2 mt-10">
                <button class="flex items-center justify-center w-10 h-10 text-gray-400 border border-gray-200 rounded-lg cursor-not-allowed bg-gray-50" disabled>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <button class="flex items-center justify-center w-10 h-10 font-semibold text-white bg-[#2e7d32] rounded-lg shadow-sm">1</button>
                <button class="flex items-center justify-center w-10 h-10 font-semibold text-gray-600 transition-colors bg-white border border-gray-200 rounded-lg hover:bg-gray-50 hover:text-[#2e7d32]">2</button>
                <button class="flex items-center justify-center w-10 h-10 font-semibold text-gray-600 transition-colors bg-white border border-gray-200 rounded-lg hover:bg-gray-50 hover:text-[#2e7d32]">3</button>
                <span class="px-2 text-gray-400">...</span>
                <button class="flex items-center justify-center w-10 h-10 text-gray-600 transition-colors bg-white border border-gray-200 rounded-lg hover:bg-gray-50 hover:text-[#2e7d32]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>
        </section>

    </div>

    <!-- SECTION 5: MODAL DETAIL DOKUMEN & PREVIEW (Alpine.js View) -->
    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 overflow-y-auto transition-opacity bg-black/60 backdrop-blur-sm"
        @keydown.escape.window="closeModal()" style="display: none;">
        
        <div x-show="modalOpen" @click.away="closeModal()" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 -translate-y-10" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-10" class="relative flex flex-col w-full max-h-[90vh] overflow-hidden bg-white shadow-2xl rounded-2xl max-w-4xl">

            <template x-if="activeHukum">
                <div class="flex flex-col h-full">
                    <!-- Header Modal -->
                    <div class="flex items-start justify-between px-6 py-4 border-b border-gray-200 rounded-t-2xl bg-gray-50 shrink-0">
                        <div>
                            <h2 class="pr-8 text-xl font-bold text-gray-800">Detail Produk Hukum</h2>
                            <p class="mt-1 text-sm text-gray-500">Metadata Jaringan Dokumentasi dan Informasi Hukum (JDIH)</p>
                        </div>
                        <button @click="closeModal()" class="flex items-center justify-center w-8 h-8 text-gray-400 transition-colors rounded-full hover:text-red-500 hover:bg-gray-200 focus:outline-none shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <!-- Body Modal (Scrollable Content) -->
                    <div class="flex flex-col flex-grow p-6 overflow-y-auto md:flex-row gap-8">
                        <!-- Kolom Kiri: Metadata Detail -->
                        <div class="w-full space-y-6 md:w-1/2">
                            <div>
                                <h3 class="mb-1 font-bold text-gray-800">Judul Peraturan</h3>
                                <p class="font-medium text-gray-700" x-text="activeHukum.judul + ' tentang ' + activeHukum.tentang"></p>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-3 border border-gray-100 rounded-lg bg-gray-50">
                                    <p class="mb-1 text-xs font-semibold tracking-wide text-gray-500 uppercase">Tipe / Jenis</p>
                                    <p class="text-sm font-bold text-gray-800" x-text="activeHukum.kategori"></p>
                                </div>
                                <div class="p-3 border border-gray-100 rounded-lg bg-gray-50">
                                    <p class="mb-1 text-xs font-semibold tracking-wide text-gray-500 uppercase">Nomor</p>
                                    <p class="text-sm font-bold text-gray-800" x-text="activeHukum.detail.nomor"></p>
                                </div>
                                <div class="p-3 border border-gray-100 rounded-lg bg-gray-50">
                                    <p class="mb-1 text-xs font-semibold tracking-wide text-gray-500 uppercase">Tahun</p>
                                    <p class="text-sm font-bold text-gray-800" x-text="activeHukum.detail.tahun"></p>
                                </div>
                                <div class="p-3 border border-gray-100 rounded-lg bg-gray-50">
                                    <p class="mb-1 text-xs font-semibold tracking-wide text-gray-500 uppercase">Status Keberlakuan</p>
                                    <p class="text-sm font-bold" :class="activeHukum.status == 'Berlaku' ? 'text-green-600' : 'text-red-600'">
                                        <svg x-show="activeHukum.status == 'Berlaku'" class="inline-block w-4 h-4 mr-1 pb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        <svg x-show="activeHukum.status != 'Berlaku'" class="inline-block w-4 h-4 mr-1 pb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-12.728 12.728M5.636 5.636l12.728 12.728"></path></svg>
                                        <span x-text="activeHukum.status"></span>
                                    </p>
                                </div>
                            </div>

                            <div class="pt-2 border-t border-gray-100 space-y-3">
                                <div class="flex justify-between pb-2 border-b border-gray-100">
                                    <span class="text-sm text-gray-500">Tanggal Ditetapkan</span>
                                    <span class="text-sm font-medium text-gray-800" x-text="activeHukum.ditetapkan"></span>
                                </div>
                                <div class="flex justify-between pb-2 border-b border-gray-100">
                                    <span class="text-sm text-gray-500">Tanggal Diundangkan</span>
                                    <span class="text-sm font-medium text-gray-800" x-text="activeHukum.detail.diundangkan"></span>
                                </div>
                                <div class="flex justify-between pb-2 border-b border-gray-100">
                                    <span class="text-sm text-gray-500">Pemrakarsa</span>
                                    <span class="text-sm font-medium text-gray-800" x-text="activeHukum.detail.pemrakarsa"></span>
                                </div>
                                <div class="flex justify-between pb-2">
                                    <span class="text-sm text-gray-500">Penandatangan</span>
                                    <span class="text-sm font-medium text-gray-800" x-text="activeHukum.detail.penandatangan"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Kanan: File Preview & Aksi (Download Section) -->
                        <div class="flex flex-col w-full md:w-1/2">
                            <h3 class="pb-2 mb-3 font-bold text-gray-800 border-b border-gray-200">Dokumen Elektronik</h3>

                            <!-- Simulasi Placeholder PDF Viewer -->
                            <div class="bg-gray-100 flex-grow rounded-xl border-2 border-dashed border-gray-300 flex flex-col items-center justify-center p-6 text-center mb-4 min-h-[250px]">
                                <svg class="w-12 h-12 mb-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                <p class="font-bold text-gray-700" x-text="activeHukum.detail.file_nama"></p>
                                <p class="mt-1 text-xs text-gray-500">
                                    Ukuran File: <span x-text="activeHukum.detail.file_ukuran"></span> | 
                                    Tipe: <span x-text="activeHukum.detail.file_tipe"></span>
                                </p>
                                <span class="px-3 py-1 mt-4 text-xs text-gray-600 bg-white border border-gray-300 rounded-full shadow-sm">
                                    <svg class="inline-block w-4 h-4 mr-1 pb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg> 
                                    Pratinjau Dokumen
                                </span>
                            </div>

                            <!-- Tombol Download -->
                            <a :href="activeHukum.detail.link_unduh" class="flex items-center justify-center w-full gap-2 px-4 py-3 font-bold text-white transition-all bg-green-600 shadow-md rounded-xl hover:bg-green-700 group">
                                <svg class="text-lg transition-transform w-[18px] h-[18px] group-hover:-translate-y-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg> 
                                Unduh Dokumen Resmi
                            </a>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

</main>
@endsection

@push('scripts')
<script>
    function hukumData() {
        return {
            daftarHukum: @json($daftarHukum),
            modalOpen: false,
            activeHukum: null,
            openModal(index) {
                this.activeHukum = this.daftarHukum[index];
                this.modalOpen = true;
                document.body.style.overflow = 'hidden';
            },
            closeModal() {
                this.modalOpen = false;
                setTimeout(() => {
                    this.activeHukum = null;
                    document.body.style.overflow = 'auto';
                }, 300);
            }
        }
    }
</script>
@endpush
