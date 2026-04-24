@extends('layouts.frontend')

@section('title', 'Informasi Publik - Desa Sindangmukti')

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
<main class="flex-grow pt-16 bg-gray-50" x-data="informasiPublikData()">

    <!-- SECTION 1: HEADER SECTION -->
    <section class="bg-gradient-to-br from-green-800 to-green-600 text-white py-16 md:py-20 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]">
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <span
                class="bg-green-700/50 text-green-100 text-sm font-semibold px-3 py-1 rounded-full border border-green-500/30 mb-4 inline-block">Layanan PPID</span>
            <h1 class="text-4xl md:text-5xl font-bold mb-4 tracking-tight">{{ $pageTitle }}</h1>
            <p class="text-lg text-green-100 max-w-2xl mx-auto leading-relaxed">
                {{ $pageSubtitle }}
            </p>
        </div>
    </section>

    <div class="px-4 py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">

        <!-- SECTION 2: KATEGORI INFORMASI (Informasi Berkala, Serta Merta, Setiap Saat) -->
        <section class="grid grid-cols-1 gap-6 mb-10 md:grid-cols-3">
            <!-- Kategori Berkala -->
            <article class="p-6 transition-all bg-white border border-gray-200 shadow-sm rounded-2xl hover:-translate-y-1 hover:shadow-md group">
                <div class="flex items-center justify-center mb-4 transition-colors bg-blue-50 text-blue-600 w-14 h-14 rounded-xl group-hover:bg-blue-600 group-hover:text-white">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="mb-2 text-xl font-bold text-gray-800">Informasi Berkala</h3>
                <p class="mb-4 text-sm text-gray-600">Informasi yang wajib diperbarui secara rutin (Ringkasan Program, Laporan Kinerja, APBDes).</p>
                <a href="#" class="text-sm font-semibold text-blue-600 hover:underline">Lihat Dokumen <svg class="inline-block w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg></a>
            </article>

            <!-- Kategori Serta Merta -->
            <article class="p-6 transition-all bg-white border border-gray-200 shadow-sm rounded-2xl hover:-translate-y-1 hover:shadow-md group">
                <div class="flex items-center justify-center mb-4 text-red-500 transition-colors bg-red-50 w-14 h-14 rounded-xl group-hover:bg-red-500 group-hover:text-white">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h3 class="mb-2 text-xl font-bold text-gray-800">Informasi Serta Merta</h3>
                <p class="mb-4 text-sm text-gray-600">Informasi yang dapat mengancam hajat hidup orang banyak dan ketertiban umum (Bencana, Wabah).</p>
                <a href="#" class="text-sm font-semibold text-red-500 hover:underline">Lihat Dokumen <svg class="inline-block w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg></a>
            </article>

            <!-- Kategori Setiap Saat -->
            <article class="p-6 transition-all bg-white border border-gray-200 shadow-sm rounded-2xl hover:-translate-y-1 hover:shadow-md group">
                <div class="flex items-center justify-center mb-4 transition-colors bg-emerald-50 text-emerald-600 w-14 h-14 rounded-xl group-hover:bg-emerald-600 group-hover:text-white">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 class="mb-2 text-xl font-bold text-gray-800">Informasi Setiap Saat</h3>
                <p class="mb-4 text-sm text-gray-600">Informasi yang tersedia dan dapat diakses kapan saja (Daftar Aset, Perjanjian Pihak Ketiga, Profil).</p>
                <a href="#" class="text-sm font-semibold text-emerald-600 hover:underline">Lihat Dokumen <svg class="inline-block w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg></a>
            </article>
        </section>

        <!-- SECTION 3: SEARCH & FILTER -->
        <section class="p-4 mb-8 bg-white border border-gray-200 shadow-sm md:p-6 rounded-2xl">
            <form class="flex flex-col items-end gap-4 md:flex-row">
                <!-- Search Bar -->
                <div class="w-full md:w-2/5">
                    <label class="block mb-1 text-sm font-medium text-gray-700">Cari Informasi Publik</label>
                    <div class="relative">
                        <input type="text" placeholder="Ketik kata kunci..." class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm focus:ring-[#2e7d32] focus:border-[#2e7d32]">
                        <svg class="absolute w-4 h-4 text-gray-400 transform -translate-y-1/2 left-3.5 top-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>

                <!-- Filter Sektor/Topik -->
                <div class="w-full md:w-1/5">
                    <label class="block mb-1 text-sm font-medium text-gray-700">Topik Informasi</label>
                    <select class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#2e7d32] focus:border-[#2e7d32] p-2.5">
                        <option value="">Semua Topik</option>
                        <option value="keuangan">Keuangan & Aset</option>
                        <option value="pemerintahan">Penyelenggaraan Pemerintahan</option>
                        <option value="pembangunan">Pembangunan Desa</option>
                        <option value="sosial">Bantuan Sosial (Rekap)</option>
                    </select>
                </div>

                <!-- Filter Tahun -->
                <div class="w-full md:w-1/5">
                    <label class="block mb-1 text-sm font-medium text-gray-700">Tahun Dokumen</label>
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
                        Tampilkan
                    </button>
                </div>
            </form>
        </section>

        <!-- SECTION 4: LIST INFORMASI & DOWNLOAD DATA -->
        <section class="mb-14">
            <div class="flex items-center justify-between pb-2 mb-6 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-800">Daftar Dokumen Publik</h2>
                <span class="text-sm font-medium text-gray-500">{{ count($daftarInformasi) }} Dokumen Tersedia</span>
            </div>

            <div class="flex flex-col gap-4">
                @foreach($daftarInformasi as $index => $info)
                <!-- Dokumen Item -->
                <article class="flex flex-col items-start gap-5 p-5 transition-all bg-white border border-gray-200 shadow-sm rounded-xl md:flex-row md:items-center hover:border-green-400 hover:shadow-md">
                    <div class="flex items-center justify-center w-12 h-12 border border-gray-200 rounded-lg shrink-0 bg-gray-100 text-{{ $info['format_warna'] }}-500">
                        @if($info['format_icon'] == 'fa-file-excel')
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        @else
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        @endif
                    </div>
                    <div class="flex-grow">
                        <div class="flex gap-2 mb-1">
                            <span class="bg-{{ $info['kategori_warna'] }}-100 text-{{ $info['kategori_warna'] }}-700 text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider">{{ $info['kategori'] }}</span>
                            <span class="bg-{{ $info['sektor_warna'] }}-100 text-{{ $info['sektor_warna'] }}-600 text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider">{{ $info['sektor'] }}</span>
                        </div>
                        <h3 class="mb-1 text-lg font-bold text-gray-800">{{ $info['judul'] }}</h3>
                        <p class="text-sm text-gray-600 line-clamp-2">{{ $info['ringkasan'] }}</p>
                        <p class="mt-2 text-xs text-gray-400">
                            <svg class="inline-block w-3 h-3 mr-1 pb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> 
                            Diperbarui: {{ $info['diperbarui'] }} • Ukuran: {{ $info['ukuran'] }}
                        </p>
                    </div>
                    <div class="flex flex-col w-full gap-2 pt-4 bg-white shrink-0 sm:flex-row md:w-auto md:pt-0">
                        <button @click="openModal({{ $index }})" class="flex items-center justify-center w-full gap-2 px-4 py-2 text-sm font-semibold text-gray-700 transition-colors bg-white border border-gray-300 rounded-lg sm:w-auto hover:bg-gray-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                            Detail
                        </button>
                        <a href="{{ $info['detail']['link_unduh'] }}" class="flex items-center justify-center w-full gap-2 px-4 py-2 text-sm font-semibold text-white transition-colors bg-green-600 rounded-lg shadow-sm sm:w-auto hover:bg-green-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg> 
                            Unduh
                        </a>
                    </div>
                </article>
                @endforeach
            </div>

            <!-- Pagination Minimalis -->
            <div class="flex justify-center mt-8">
                <nav class="inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                    <a href="#" class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50">Sebelumnya</a>
                    <a href="#" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-[#2e7d32] border border-gray-300 bg-green-50">1</a>
                    <a href="#" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50">2</a>
                    <a href="#" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50">3</a>
                    <a href="#" class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50">Selanjutnya</a>
                </nav>
            </div>
        </section>

        <!-- SECTION 5: PERMINTAAN INFORMASI (CTA Khusus / PPID) -->
        <section class="relative p-8 overflow-hidden text-white shadow-xl bg-gradient-to-br from-gray-900 to-[#1b5e20] rounded-3xl md:p-12">
            <!-- Elemen Dekoratif -->
            <div class="absolute top-0 right-0 w-64 h-64 -mt-16 -mr-16 rounded-full bg-green-500/10 blur-3xl"></div>

            <div class="relative z-10 flex flex-col items-center justify-between gap-8 lg:flex-row">
                <div class="lg:w-2/3">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="px-3 py-1 text-xs font-bold tracking-wider text-green-300 uppercase border rounded-full bg-green-600/30 border-green-500/50">Layanan PPID</span>
                    </div>
                    <h2 class="mb-4 text-3xl font-bold md:text-4xl">Tidak menemukan dokumen yang Anda cari?</h2>
                    <p class="mb-0 text-lg leading-relaxed text-gray-300">
                        Sebagai Warga Negara Indonesia, Anda berhak mengajukan permohonan informasi publik kepada Pejabat Pengelola Informasi dan Dokumentasi (PPID) Desa Sindangmukti sesuai dengan UU No. 14 Tahun 2008.
                    </p>
                </div>

                <div class="flex flex-col w-full gap-4 shrink-0 lg:w-auto sm:flex-row">
                    <a href="#" class="flex items-center justify-center gap-2 px-6 font-bold text-gray-900 transition-all bg-white shadow-lg py-3.5 rounded-xl hover:bg-gray-100">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg> 
                        Buat Permohonan
                    </a>
                    <a href="#" class="flex items-center justify-center gap-2 px-6 font-semibold text-white transparent transition-all border border-gray-500 py-3.5 rounded-xl hover:border-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg> 
                        SOP & Panduan
                    </a>
                </div>
            </div>
        </section>

    </div>

    <!-- SECTION 6: MODAL DETAIL INFORMASI (Alpine.js) -->
    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 overflow-y-auto transition-opacity bg-black/60 backdrop-blur-sm"
        @keydown.escape.window="closeModal()" style="display: none;">
        
        <div x-show="modalOpen" @click.away="closeModal()" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 -translate-y-10" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-10" class="relative flex flex-col w-full max-h-[90vh] bg-white shadow-2xl rounded-2xl max-w-3xl">

            <template x-if="activeInfo">
                <div class="flex flex-col h-full overflow-hidden">
                    <!-- Header Modal -->
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50 rounded-t-2xl shrink-0">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 text-blue-600 bg-blue-100 rounded-full">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <h2 class="text-xl font-bold text-gray-800">Detail Informasi</h2>
                        </div>
                        <button @click="closeModal()" class="flex items-center justify-center w-8 h-8 text-gray-400 transition-colors rounded-full hover:text-red-500 hover:bg-gray-200 shrink-0 focus:outline-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <!-- Body Modal -->
                    <div class="p-6 overflow-y-auto">
                        <div class="mb-6">
                            <h3 class="mb-2 text-2xl font-bold text-gray-800" x-text="activeInfo.judul"></h3>
                            <div class="flex flex-wrap gap-2 mb-4 text-xs font-semibold">
                                <span class="px-2.5 py-1 text-blue-700 bg-blue-50 border border-blue-100 rounded">Kategori: <span x-text="activeInfo.kategori"></span></span>
                                <span class="px-2.5 py-1 text-gray-700 bg-gray-100 border border-gray-200 rounded">Sektor: <span x-text="activeInfo.sektor"></span></span>
                            </div>
                        </div>

                        <div class="p-5 mb-6 space-y-3 border border-gray-200 bg-gray-50 rounded-xl">
                            <div class="flex justify-between pb-2 border-b border-gray-200">
                                <span class="text-sm font-medium text-gray-500">Penanggung Jawab (PPID)</span>
                                <span class="text-sm font-semibold text-gray-800" x-text="activeInfo.detail.penanggung_jawab"></span>
                            </div>
                            <div class="flex justify-between pb-2 border-b border-gray-200">
                                <span class="text-sm font-medium text-gray-500">Waktu Pembuatan</span>
                                <span class="text-sm font-semibold text-gray-800" x-text="activeInfo.detail.waktu_pembuatan"></span>
                            </div>
                            <div class="flex justify-between pb-2 border-b border-gray-200">
                                <span class="text-sm font-medium text-gray-500">Waktu Publikasi</span>
                                <span class="text-sm font-semibold text-gray-800" x-text="activeInfo.detail.waktu_publikasi"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-500">Format & Ukuran</span>
                                <span class="text-sm font-semibold text-gray-800" x-text="activeInfo.detail.format_ukuran"></span>
                            </div>
                        </div>

                        <div>
                            <h4 class="mb-2 font-bold text-gray-800">Ringkasan Isi Dokumen:</h4>
                            <p class="mb-4 text-sm leading-relaxed text-gray-600" x-text="activeInfo.detail.deskripsi"></p>
                        </div>

                        <!-- Peringatan Kewajiban Pengguna -->
                        <div class="p-4 text-sm border-l-4 rounded-r bg-amber-50 border-amber-500 text-amber-800">
                            <svg class="inline-block w-4 h-4 mr-1 pb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                            Dengan mengunduh dokumen ini, Anda setuju untuk menggunakan informasi sesuai dengan Undang-Undang Keterbukaan Informasi Publik dan tidak menyalahgunakannya.
                        </div>
                    </div>

                    <!-- Footer Modal Actions -->
                    <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-2xl shrink-0">
                        <button @click="closeModal()" class="px-5 py-2.5 text-sm font-semibold text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Tutup
                        </button>
                        <a :href="activeInfo.detail.link_unduh" class="px-5 py-2.5 text-sm font-semibold text-white bg-green-600 rounded-lg hover:bg-green-700 shadow-sm flex items-center gap-2 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg> 
                            Unduh File
                        </a>
                    </div>
                </div>
            </template>
        </div>
    </div>

</main>
@endsection

@push('scripts')
<script>
    function informasiPublikData() {
        return {
            daftarInformasi: @json($daftarInformasi),
            modalOpen: false,
            activeInfo: null,
            openModal(index) {
                this.activeInfo = this.daftarInformasi[index];
                this.modalOpen = true;
                document.body.style.overflow = 'hidden';
            },
            closeModal() {
                this.modalOpen = false;
                setTimeout(() => {
                    this.activeInfo = null;
                    document.body.style.overflow = 'auto';
                }, 300);
            }
        }
    }
</script>
@endpush
