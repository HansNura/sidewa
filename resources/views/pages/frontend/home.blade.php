@extends('layouts.frontend')

@section('title', 'Beranda - Desa Sindangmukti')

@section('content')

    <!-- Hero Section -->
    <section class="relative h-[100vh] flex items-center justify-center text-center text-white"
        style="
            background-image: url('{{ asset('assets/img/hero/hero-desa.jpg') }}');
            background-size: cover;
            background-position: center;
        ">
        <!-- Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>

        <!-- Konten Hero -->
        <div class="relative z-10 px-4 md:px-8">
            <!-- Tagline kecil -->
            <div
                class="inline-flex items-center justify-center max-w-full gap-1.5 sm:gap-2 px-3 sm:px-4 py-1.5 sm:py-2 mx-auto mb-6 transition border rounded-full border-gray-300/40 hover:border-gray-200/70 bg-white/10 backdrop-blur-sm">
                <span class="text-[10px] sm:text-sm text-gray-100 truncate">Selamat datang di laman resmi</span>
                <span class="text-[10px] sm:text-sm font-semibold text-yellow-400 shrink-0">Desa Sindangmukti</span>
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                    class="ml-0.5 sm:ml-1 shrink-0 w-3 h-3 sm:w-4 sm:h-4">
                    <path d="M5 12h14M13 6l6 6-6 6" stroke="#facc15" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </div>

            <!-- Judul -->
            <h1 class="text-4xl md:text-6xl font-extrabold text-yellow-300 drop-shadow-[0_4px_8px_rgba(0,0,0,0.8)]">
                Selamat Datang di Desa Sindangmukti
            </h1>

            <!-- Deskripsi -->
            <p
                class="max-w-2xl mx-auto mt-6 text-base md:text-lg leading-relaxed text-gray-100 drop-shadow-[0_2px_4px_rgba(0,0,0,0.7)]">
                Desa Sindangmukti terletak di kaki Gunung Sawal, Kecamatan
                Panumbangan, Kabupaten Ciamis. Dikenal dengan keindahan
                alamnya dan sejarah panjang para pemimpin desa yang
                telah berjasa membangun masyarakat yang sejahtera dan
                berdaya.
            </p>

            <!-- Tombol Aksi -->
            <div class="flex items-center justify-center gap-4 mt-8">
                <a href="{{ url('profil') }}"
                    class="px-8 py-3 font-semibold text-gray-900 transition-all duration-200 bg-yellow-400 rounded-full shadow-md hover:bg-yellow-300 hover:-translate-y-1">
                    Jelajahi Desa
                </a>
                <a href="{{ url('profil#sejarah') }}"
                    class="px-8 py-3 font-medium text-white transition-all duration-200 border border-gray-200 rounded-full hover:bg-white/10 backdrop-blur-sm hover:-translate-y-1">
                    Lihat Profil Desa
                </a>
            </div>
        </div>
        <!-- Scroll Down Indicator -->
        <div class="absolute flex flex-col items-center text-gray-200 bottom-6 animate-bounce">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
            <span class="mt-1 text-xs">Scroll</span>
        </div>
    </section>

    <!-- SECTION: JELAJAHI DESA (BENTO GRID) -->
    <section id="jelajahi" class="py-24 bg-slate-50 border-t border-slate-100">
        <div class="px-6 mx-auto max-w-7xl">
            <!-- HEADER -->
            <div class="mb-12 text-center">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold uppercase tracking-wider mb-4">
                    Eksplorasi
                </div>
                <h2 class="text-3xl md:text-4xl font-black text-slate-800 mb-4 leading-tight">
                    Jelajahi <span class="text-emerald-600 italic">Sindangmukti</span>
                </h2>
                <p class="max-w-2xl mx-auto text-slate-600 text-lg leading-relaxed">
                    Temukan informasi seputar profil desa, layanan publik, potensi daerah, dan berita terbaru dalam satu
                    atap digital.
                </p>
            </div>

            <!-- CONTAINER UTAMA BENTO GRID: 12 Kolom -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">

                <!-- KOLOM KIRI (Hero + 2 Kartu Kecil) -->
                <div class="lg:col-span-8 flex flex-col gap-6">

                    <!-- HERO CARD: LAYANAN DESA -->
                    <a href="{{ route('layanan') }}"
                        class="relative flex-grow flex flex-col justify-center bg-gradient-to-br from-emerald-600 to-teal-800 rounded-[2.5rem] p-8 sm:p-10 shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group overflow-hidden border border-emerald-500/30">
                        <!-- Background Ornamen -->
                        <div
                            class="absolute right-0 top-0 w-64 h-64 bg-white/10 rounded-full blur-3xl transform translate-x-1/2 -translate-y-1/2 group-hover:scale-110 transition-transform duration-700">
                        </div>
                        <svg class="absolute right-4 top-1/2 -translate-y-1/2 w-48 h-48 text-white/5 group-hover:text-white/10 group-hover:scale-110 transition-all duration-500"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>

                        <div class="relative z-10 w-full sm:w-3/4">
                            <span
                                class="inline-block px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-[10px] font-bold text-emerald-50 tracking-widest uppercase mb-4 border border-white/20">Layanan
                                Unggulan</span>
                            <h3 class="text-3xl font-black text-white mb-3">Layanan Mandiri</h3>
                            <p class="text-emerald-50 text-sm mb-8 leading-relaxed">Ajukan surat keterangan atau layanan
                                administrasi kependudukan secara online dengan cepat dan transparan tanpa harus antre di
                                balai desa.</p>
                            <div
                                class="inline-flex items-center gap-2 bg-white text-emerald-700 px-6 py-3 rounded-full text-sm font-bold shadow-sm group-hover:bg-emerald-50 transition-colors">
                                Akses Layanan <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </div>
                        </div>
                    </a>

                    <!-- GRID BAWAH (Berita & Potensi) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- KARTU BERITA -->
                        <a href="{{ route('informasi.berita-artikel') }}"
                            class="bg-white rounded-[2rem] p-8 border border-slate-200 shadow-sm hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] hover:border-amber-200 hover:-translate-y-1 transition-all duration-300 group flex flex-col justify-between min-h-[200px]">
                            <div>
                                <div class="flex justify-between items-start mb-5">
                                    <div
                                        class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center group-hover:bg-amber-500 group-hover:text-white transition-colors duration-300">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H14" />
                                        </svg>
                                    </div>
                                    <svg class="w-5 h-5 text-slate-300 group-hover:text-amber-500 transform group-hover:translate-x-1 group-hover:-translate-y-1 transition-all duration-300"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </div>
                                <h3
                                    class="text-xl font-bold text-slate-900 mb-2 group-hover:text-amber-600 transition-colors">
                                    Berita & Kegiatan</h3>
                            </div>
                            <p class="text-slate-500 text-sm leading-relaxed">Baca berita terkini dan dokumentasi kegiatan
                                resmi desa.</p>
                        </a>

                        <!-- KARTU POTENSI -->
                        <a href="{{ route('lapak') }}"
                            class="bg-white rounded-[2rem] p-8 border border-slate-200 shadow-sm hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] hover:border-blue-200 hover:-translate-y-1 transition-all duration-300 group flex flex-col justify-between min-h-[200px]">
                            <div>
                                <div class="flex justify-between items-start mb-5">
                                    <div
                                        class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                        </svg>
                                    </div>
                                    <svg class="w-5 h-5 text-slate-300 group-hover:text-blue-500 transform group-hover:translate-x-1 group-hover:-translate-y-1 transition-all duration-300"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </div>
                                <h3
                                    class="text-xl font-bold text-slate-900 mb-2 group-hover:text-blue-600 transition-colors">
                                    Lapak Desa</h3>
                            </div>
                            <p class="text-slate-500 text-sm leading-relaxed">Dukung UMKM lokal dengan mengeksplorasi
                                produk unggulan warga.</p>
                        </a>
                    </div>

                </div>

                <!-- KOLOM KANAN (Profil Desa - Tall Card) -->
                <div class="lg:col-span-4 flex">
                    <a href="{{ route('profil.identitas') }}"
                        class="w-full bg-white rounded-[2.5rem] p-8 sm:p-10 border border-slate-200 shadow-sm hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] hover:border-emerald-200 hover:-translate-y-1 transition-all duration-300 group flex flex-col">
                        <div class="flex-grow">
                            <div
                                class="w-16 h-16 rounded-3xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-8 border border-emerald-100 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300 shadow-sm">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <h3
                                class="text-2xl font-bold text-slate-900 mb-4 group-hover:text-emerald-700 transition-colors">
                                Profil Desa</h3>
                            <p class="text-slate-500 text-base leading-relaxed">Kenali sejarah panjang, visi dan misi,
                                serta struktur lembaga dan pemerintahan Desa Sindangmukti secara mendalam untuk mewujudkan
                                transparansi informasi publik.</p>
                        </div>

                        <div
                            class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-between group-hover:text-emerald-600 transition-colors">
                            <span class="font-bold text-sm">Lihat Selengkapnya</span>
                            <div
                                class="w-10 h-10 rounded-full border border-slate-200 flex items-center justify-center group-hover:border-emerald-600 group-hover:bg-emerald-50 transition-colors">
                                <svg class="w-4 h-4 transform group-hover:rotate-45 transition-transform duration-300"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                </svg>
                            </div>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </section>

    <!-- Sambutan Kepala Desa (Editorial Offset Layout) -->
    <section id="sambutan" class="py-24 bg-white">
        <div class="max-w-6xl px-6 mx-auto">
            <div class="flex flex-col md:flex-row items-center gap-12 lg:gap-20">

                <!-- Area Gambar (Offset Design) -->
                <div class="w-full md:w-1/2 relative px-4 sm:px-8">
                    <!-- Blok Warna Latar Belakang Offset -->
                    <div
                        class="absolute inset-y-0 left-0 w-4/5 bg-slate-100 rounded-3xl transform -translate-x-2 sm:-translate-x-6 translate-y-6 sm:translate-y-8 z-0">
                    </div>
                    <!-- Gambar Utama -->
                    <div
                        class="relative z-10 rounded-3xl overflow-hidden shadow-[0_20px_50px_-12px_rgba(0,0,0,0.1)] group bg-emerald-50">
                        <img src="{{ asset('assets/img/people.png') }}" alt="Kepala Desa {{ $village->nama_desa }}"
                            class="w-full aspect-square md:aspect-[4/5] object-cover object-top group-hover:scale-105 transition-transform duration-700 ease-in-out">
                        <!-- Overlay Gradasi Halus -->
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        </div>
                    </div>
                </div>

                <!-- Area Teks -->
                <div class="w-full md:w-1/2 px-4 sm:px-0">
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-50 rounded-lg text-xs font-bold text-emerald-700 tracking-widest uppercase mb-6 border border-emerald-100">
                        Pesan {{ $village->jabatan_kades ?? 'Kepala Desa' }}
                    </div>

                    <h3 class="text-3xl sm:text-4xl font-black text-slate-900 mb-6 tracking-tight leading-tight">
                        Transparansi & Pelayanan <br><span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-600">Berbasis
                            Digital</span>
                    </h3>

                    <div class="space-y-5 text-slate-500 text-base leading-relaxed mb-10">
                        <p>
                            Assalamu'alaikum warahmatullahi wabarakatuh. Puji syukur kita panjatkan ke hadirat Allah SWT,
                            karena atas rahmat dan karunia-Nya website resmi <strong>Desa
                                {{ $village->nama_desa }}</strong> ini dapat terwujud sebagai sarana informasi, komunikasi,
                            dan transparansi bagi seluruh masyarakat.
                        </p>
                        <p>
                            Melalui media ini, kami berharap masyarakat dapat lebih mudah mengakses berbagai informasi
                            mengenai profil desa, potensi wilayah, serta pelayanan administrasi secara digital. Mari
                            bersama-sama kita wujudkan desa yang sejahtera, mandiri, dan berdaya saing.
                        </p>
                    </div>

                    <!-- Tanda Tangan / Profil Info -->
                    <div class="flex items-center gap-5">
                        <div class="w-1 h-12 bg-emerald-500 rounded-full"></div>
                        <div>
                            <p class="font-bold text-slate-900 text-xl tracking-tight">
                                {{ $village->nama_kades ?? 'Kepala Desa' }}</p>
                            <p class="text-slate-500 text-sm font-medium">{{ $village->jabatan_kades ?? 'Kepala Desa' }}
                                {{ $village->nama_desa }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Peta Desa Section (Immersive Floating Glassmorphism) -->
    <section class="max-w-7xl mx-auto py-24 px-6">
        <div class="mb-12 text-center">
            <div
                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold uppercase tracking-wider mb-4">
                Lokasi & Wilayah
            </div>
            <h2 class="text-3xl md:text-4xl font-black text-slate-800 mb-4 leading-tight">
                Peta Interaktif <span class="text-emerald-600 italic">Desa</span>
            </h2>
            <p class="max-w-2xl mx-auto text-slate-600 text-lg leading-relaxed">
                Jelajahi batas administratif dan letak geografis wilayah kami secara langsung.
            </p>
        </div>

        <div class="relative w-full rounded-[2.5rem] overflow-hidden border border-slate-200 shadow-xl group">

            <!-- Elemen Peta (Leaflet Target) -->
            <div id="map" class="relative z-0 w-full h-[500px] sm:h-[600px] bg-slate-200 z-[1]">
                <!-- Leaflet map will be dynamically injected here -->
            </div>

            <!-- Gradient Overlay untuk Text Readability -->
            <div
                class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-slate-900/40 to-transparent z-[10] pointer-events-none">
            </div>

            <!-- Floating Info Card (Glassmorphism) -->
            <div
                class="absolute bottom-6 sm:bottom-10 left-6 sm:left-10 z-[20] max-w-md w-[calc(100%-3rem)] sm:w-auto bg-white/80 backdrop-blur-xl rounded-3xl p-6 sm:p-8 shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-white/60 transform group-hover:-translate-y-2 transition-transform duration-500">
                <h3 class="text-2xl font-bold text-slate-900 mb-2">Peta {{ $village->nama_desa ?? 'Desa' }}</h3>
                <p class="text-slate-600 text-sm leading-relaxed mb-6">
                    Lihat lokasi dan batas wilayah desa secara langsung melalui peta interaktif. Gunakan fitur zoom dan
                    geser untuk menjelajah.
                </p>

                <div class="flex items-center gap-3 bg-white/60 p-3 rounded-2xl border border-white/50">
                    <div
                        class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-sm">Desa {{ $village->nama_desa ?? 'Sindangmukti' }}</h4>
                        <p class="text-xs text-slate-500 font-medium">Kecamatan
                            {{ $village->kecamatan ?? 'Panumbangan' }}, Kab. {{ $village->kabupaten ?? 'Ciamis' }}</p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- STRUKTUR ORGANISASI BPD (Hierarki Modern) -->
    <section id="bpd" class="max-w-6xl mx-auto py-24 px-6">
        <div class="mb-12 text-center">
            <div
                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold uppercase tracking-wider mb-4">
                Organisasi
            </div>
            <h2 class="text-3xl md:text-4xl font-black text-slate-800 mb-4 leading-tight">
                Badan Permusyawaratan <span class="text-emerald-600 italic">Desa</span>
            </h2>
            <p class="max-w-2xl mx-auto text-slate-600 text-lg leading-relaxed">
                Struktur lembaga yang menjadi mitra strategis pemerintah desa dalam menyalurkan aspirasi masyarakat.
            </p>
        </div>

        <div
            class="bg-white rounded-[2.5rem] p-6 sm:p-10 lg:p-14 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100">

            <!-- Header section -->
            <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-12 border-b border-slate-100 pb-8">
                <div class="text-center md:text-left">
                    <h3 class="text-2xl font-bold text-slate-900">Struktur BPD</h3>
                    <p class="text-slate-500 mt-1">Daftar Anggota BPD Desa {{ $village->nama_desa ?? 'Sindangmukti' }}</p>
                </div>
                <div
                    class="inline-flex items-center gap-2 bg-emerald-50 text-emerald-700 px-4 py-2 rounded-xl text-sm font-bold border border-emerald-100 shadow-sm">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Masa Bakti: 2019 – 2025
                </div>
            </div>

            <div class="flex flex-col gap-8">

                <!-- LEVEL 1: KETUA (Full Width Card) -->
                @php $ketua = $bpdMembers[0]; @endphp
                <div class="w-full">
                    <div
                        class="bg-gradient-to-br from-white to-slate-50 rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col sm:flex-row items-center gap-6 sm:gap-10 relative overflow-hidden group">
                        <div class="absolute left-0 top-0 w-2 h-full bg-emerald-500"></div>

                        <!-- Foto Ketua -->
                        <div class="shrink-0 relative">
                            <div
                                class="w-32 h-32 sm:w-40 sm:h-40 rounded-full p-1.5 bg-white shadow-md border border-slate-100 group-hover:scale-105 transition-transform duration-500">
                                <img src="{{ asset('assets/img/people.png') }}" alt="{{ $ketua['nama'] }}"
                                    class="w-full h-full rounded-full object-cover object-top">
                            </div>
                            <div
                                class="absolute -bottom-2 right-1/2 translate-x-1/2 sm:translate-x-0 sm:right-0 bg-emerald-600 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-sm border border-white">
                                {{ $ketua['jabatan'] }}
                            </div>
                        </div>

                        <!-- Info Ketua -->
                        <div class="text-center sm:text-left flex-1">
                            <h4 class="text-2xl sm:text-3xl font-black text-slate-900 mb-4">{{ $ketua['nama'] }}</h4>

                            <div class="flex flex-col sm:flex-row gap-4 sm:gap-8 justify-center sm:justify-start">
                                <div
                                    class="flex items-center gap-3 bg-white px-4 py-2.5 rounded-xl border border-slate-100 shadow-sm">
                                    <div
                                        class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                    </div>
                                    <div class="text-left">
                                        <p
                                            class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">
                                            Kontak</p>
                                        <p class="text-sm font-semibold text-slate-800 leading-none">
                                            {{ $ketua['kontak'] }}</p>
                                    </div>
                                </div>

                                <div
                                    class="flex items-center gap-3 bg-white px-4 py-2.5 rounded-xl border border-slate-100 shadow-sm">
                                    <div
                                        class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <div class="text-left">
                                        <p
                                            class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">
                                            Alamat</p>
                                        <p class="text-sm font-semibold text-slate-800 leading-none">
                                            {{ $ketua['alamat'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- LEVEL 2: WAKIL & SEKRETARIS (2 Cols) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @php
                        $wakil = $bpdMembers[1] ?? null;
                        $sekretaris = $bpdMembers[2] ?? null;
                    @endphp

                    @if ($wakil)
                        <!-- Wakil -->
                        <div
                            class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm hover:shadow-md hover:border-slate-300 transition-all duration-300 flex items-center gap-5 group">
                            <div class="shrink-0 relative">
                                <div
                                    class="w-20 h-20 rounded-full p-1 bg-white shadow-sm border border-slate-100 group-hover:scale-105 transition-transform">
                                    <img src="{{ asset('assets/img/people.png') }}" alt="{{ $wakil['nama'] }}"
                                        class="w-full h-full rounded-full object-cover">
                                </div>
                            </div>
                            <div>
                                <span
                                    class="inline-block px-2.5 py-0.5 bg-slate-100 text-slate-600 rounded text-[10px] font-bold uppercase tracking-widest mb-2 border border-slate-200">{{ $wakil['jabatan'] }}</span>
                                <h4 class="text-lg font-bold text-slate-900 leading-tight">{{ $wakil['nama'] }}</h4>
                            </div>
                        </div>
                    @endif

                    @if ($sekretaris)
                        <!-- Sekretaris -->
                        <div
                            class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm hover:shadow-md hover:border-slate-300 transition-all duration-300 flex items-center gap-5 group">
                            <div class="shrink-0 relative">
                                <div
                                    class="w-20 h-20 rounded-full p-1 bg-white shadow-sm border border-slate-100 group-hover:scale-105 transition-transform">
                                    <img src="{{ asset('assets/img/people.png') }}" alt="{{ $sekretaris['nama'] }}"
                                        class="w-full h-full rounded-full object-cover">
                                </div>
                            </div>
                            <div>
                                <span
                                    class="inline-block px-2.5 py-0.5 bg-slate-100 text-slate-600 rounded text-[10px] font-bold uppercase tracking-widest mb-2 border border-slate-200">{{ $sekretaris['jabatan'] }}</span>
                                <h4 class="text-lg font-bold text-slate-900 leading-tight">{{ $sekretaris['nama'] }}</h4>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- LEVEL 3: ANGGOTA (Grid) -->
                @if (count($bpdMembers) > 3)
                    <div class="bg-slate-50 rounded-[2rem] p-6 sm:p-8 border border-slate-100">
                        <div class="flex items-center gap-4 mb-6">
                            <h4 class="text-sm font-bold text-slate-500 uppercase tracking-widest">Anggota BPD</h4>
                            <div class="flex-grow h-px bg-slate-200"></div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                            @for ($i = 3; $i < count($bpdMembers); $i++)
                                @php $anggota = $bpdMembers[$i]; @endphp
                                <div
                                    class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm text-center hover:-translate-y-1 hover:border-slate-300 transition-all duration-300">
                                    <img src="{{ asset('assets/img/people.png') }}" alt="{{ $anggota['nama'] }}"
                                        class="w-16 h-16 rounded-full mx-auto mb-3 object-cover shadow-sm">
                                    <h5 class="text-sm font-bold text-slate-900 mb-0.5">{{ $anggota['nama'] }}</h5>
                                    <p class="text-[10px] text-slate-500 uppercase">{{ $anggota['jabatan'] }}</p>
                                </div>
                            @endfor
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </section>

    <!-- ADMINISTRASI PENDUDUK (Mobile Optimized) -->
    <section id="penduduk" class="w-full bg-slate-50 py-16 sm:py-24">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="mb-12 text-center">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold uppercase tracking-wider mb-4">
                    Data Desa
                </div>
                <h2 class="text-3xl md:text-4xl font-black text-slate-800 mb-4 leading-tight">
                    Administrasi <span class="text-emerald-600 italic">Penduduk</span>
                </h2>
                <p class="max-w-2xl mx-auto text-slate-600 text-lg leading-relaxed">
                    Berikut adalah ringkasan demografi Desa {{ $village->nama_desa ?? 'Sindangmukti' }} yang mencakup
                    jumlah Kepala Keluarga, sebaran gender, dan distribusi pekerjaan.
                </p>
            </div>

            <!-- Grid Kartu Statistik (Compact Mobile) -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 sm:gap-8 mb-10 sm:mb-16">
                <!-- Kartu: Kepala Keluarga -->
                <div
                    class="bg-white p-4 sm:p-8 rounded-2xl sm:rounded-[2rem] shadow-[0_4px_24px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_32px_rgb(0,0,0,0.08)] transition-all duration-300 group border border-slate-100 flex flex-col justify-between">
                    <div class="flex flex-col sm:flex-row justify-between items-start mb-2 sm:mb-4 gap-2">
                        <div
                            class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center group-hover:bg-emerald-50 group-hover:text-emerald-500 transition-colors shrink-0 order-first sm:order-last">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h3
                            class="text-3xl sm:text-5xl font-black text-slate-800 group-hover:text-emerald-600 transition-colors">
                            {{ number_format($totalKK) }}</h3>
                    </div>
                    <p class="text-[10px] sm:text-sm font-bold text-slate-500 uppercase tracking-wide">Kep. Keluarga</p>
                </div>

                <!-- Kartu: Total Penduduk -->
                <div
                    class="bg-white p-4 sm:p-8 rounded-2xl sm:rounded-[2rem] shadow-[0_4px_24px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_32px_rgb(0,0,0,0.08)] transition-all duration-300 group border border-slate-100 flex flex-col justify-between">
                    <div class="flex flex-col sm:flex-row justify-between items-start mb-2 sm:mb-4 gap-2">
                        <div
                            class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center group-hover:bg-emerald-50 group-hover:text-emerald-500 transition-colors shrink-0 order-first sm:order-last">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h3
                            class="text-3xl sm:text-5xl font-black text-slate-800 group-hover:text-emerald-600 transition-colors">
                            {{ number_format($pendudukStats['total']) }}</h3>
                    </div>
                    <p class="text-[10px] sm:text-sm font-bold text-slate-500 uppercase tracking-wide">Total Penduduk</p>
                </div>

                <!-- Kartu: Jumlah Wilayah -->
                <div
                    class="col-span-2 md:col-span-1 bg-white p-4 sm:p-8 rounded-2xl sm:rounded-[2rem] shadow-[0_4px_24px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_32px_rgb(0,0,0,0.08)] transition-all duration-300 group border border-slate-100 flex flex-row sm:flex-col justify-between items-center sm:items-start">
                    <div
                        class="flex flex-row sm:flex-row justify-between items-center sm:items-start w-full gap-3 sm:gap-2 sm:mb-4">
                        <h3
                            class="text-3xl sm:text-5xl font-black text-slate-800 group-hover:text-emerald-600 transition-colors">
                            {{ $totalRtRw }}</h3>
                        <div
                            class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center group-hover:bg-emerald-50 group-hover:text-emerald-500 transition-colors shrink-0">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                            </svg>
                        </div>
                    </div>
                    <p
                        class="text-[10px] sm:text-sm font-bold text-slate-500 uppercase tracking-wide flex-1 sm:flex-none text-left mt-0 sm:mt-0">
                        Jumlah Wilayah</p>
                </div>
            </div>

            <!-- Grid Grafik -->
            <div class="grid gap-4 sm:gap-8 lg:gap-12 md:grid-cols-2">
                <!-- Grafik Gender -->
                <div
                    class="bg-white p-5 sm:p-8 rounded-3xl sm:rounded-[2rem] shadow-[0_4px_24px_rgb(0,0,0,0.04)] border border-slate-100 flex flex-col justify-center">
                    <h4 class="mb-4 sm:mb-6 font-bold text-slate-800 text-sm sm:text-xl text-center">Perbandingan Gender
                    </h4>
                    <div class="relative w-full flex items-center justify-center h-[200px] sm:h-[300px]">
                        <canvas id="genderChart" class="max-h-[200px] sm:max-h-[300px]"></canvas>
                    </div>
                </div>

                <!-- Grafik Pekerjaan -->
                <div
                    class="bg-white p-5 sm:p-8 rounded-3xl sm:rounded-[2rem] shadow-[0_4px_24px_rgb(0,0,0,0.04)] border border-slate-100 flex flex-col justify-center">
                    <h4 class="mb-4 sm:mb-6 font-bold text-slate-800 text-sm sm:text-xl text-center">Distribusi Pekerjaan
                    </h4>
                    <div class="relative w-full flex items-center justify-center h-[200px] sm:h-[300px]">
                        <canvas id="pekerjaanChart" class="max-h-[200px] sm:max-h-[300px]"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="apbdes" class="bg-[#F9FAFB] py-24">
        <div class="max-w-6xl px-6 mx-auto">
            <div class="mb-16 text-center">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold uppercase tracking-wider mb-4">
                    Keuangan Desa
                </div>
                <h2 class="text-3xl md:text-4xl font-black text-slate-800 mb-4 leading-tight">
                    Transparansi APBDes <span class="text-emerald-600 italic">{{ $tahunApbdes }}</span>
                </h2>
                <p class="max-w-2xl mx-auto text-slate-600 text-lg leading-relaxed">
                    Data Anggaran Pendapatan dan Belanja Desa. Informasi ini disajikan secara terbuka untuk mendukung
                    transparansi dan akuntabilitas keuangan.


                <div class="grid grid-cols-1 gap-8 mb-16 md:grid-cols-3">
                    <div
                        class="p-6 transition-all duration-300 ease-in-out transform bg-white border border-gray-100 shadow-lg rounded-xl hover:shadow-xl hover:-translate-y-1">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h4 class="mb-2 text-lg font-semibold text-green-700">
                                    Pendapatan
                                </h4>
                                <p class="text-sm text-gray-600">
                                    Anggaran: Rp {{ number_format($apbdesSummary['pendapatan'], 0, ',', '.') }}
                                </p>
                                <p class="mb-4 text-sm text-gray-600">
                                    Realisasi: Rp {{ number_format($apbdesSummary['pendapatan_realisasi'], 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="p-3 bg-green-100 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-700">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                                </svg>
                            </div>
                        </div>
                        <div class="h-3 overflow-hidden bg-gray-200 rounded-full">
                            <div class="h-3 bg-[#2E7D32] transition-all duration-700"
                                style="width: {{ min($pendapatanPersen, 100) }}%"></div>
                        </div>
                        <p class="mt-1 text-xs text-right text-gray-500">
                            {{ $pendapatanPersen }}% terealisasi
                        </p>
                    </div>

                    <div
                        class="p-6 transition-all duration-300 ease-in-out transform bg-white border border-gray-100 shadow-lg rounded-xl hover:shadow-xl hover:-translate-y-1">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h4 class="mb-2 text-lg font-semibold text-red-700">
                                    Belanja
                                </h4>
                                <p class="text-sm text-gray-600">
                                    Anggaran: Rp {{ number_format($apbdesSummary['belanja'], 0, ',', '.') }}
                                </p>
                                <p class="mb-4 text-sm text-gray-600">
                                    Realisasi: Rp {{ number_format($apbdesSummary['belanja_realisasi'], 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="p-3 bg-red-100 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-700">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 6 9 12.75l4.306-4.306a11.95 11.95 0 0 1 5.814 5.518l2.74 1.22m0 0-5.94 2.281m5.94-2.28-2.28-5.941" />
                                </svg>
                            </div>
                        </div>
                        <div class="h-3 overflow-hidden bg-gray-200 rounded-full">
                            <div class="h-3 bg-red-600 transition-all duration-700"
                                style="width: {{ min($belanjaPersen, 100) }}%"></div>
                        </div>
                        <p class="mt-1 text-xs text-right text-gray-500">
                            {{ $belanjaPersen }}% terealisasi
                        </p>
                    </div>

                    <div
                        class="p-6 transition-all duration-300 ease-in-out transform bg-white border border-gray-100 shadow-lg rounded-xl hover:shadow-xl hover:-translate-y-1">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h4 class="mb-2 text-lg font-semibold text-blue-700">
                                    Pembiayaan
                                </h4>
                                <p class="text-sm text-gray-600">
                                    Anggaran: Rp {{ number_format($apbdesSummary['pembiayaan'], 0, ',', '.') }}
                                </p>
                                <p class="mb-4 text-sm text-gray-600">
                                    Realisasi: Rp {{ number_format($apbdesSummary['pembiayaan_realisasi'], 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="p-3 bg-blue-100 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-700">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z" />
                                </svg>
                            </div>
                        </div>
                        <div class="h-3 overflow-hidden bg-gray-200 rounded-full">
                            <div class="h-3 bg-[#0288D1]" style="width: 75%"></div>
                        </div>
                        <p class="mt-1 text-xs text-right text-gray-500">
                            Surplus/Defisit
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-12 mb-20 lg:grid-cols-2">
                    <div class="p-6 bg-white border border-gray-100 shadow-xl rounded-2xl">
                        <h4 class="mb-4 text-lg font-semibold text-gray-800">
                            Pendapatan Desa (Anggaran vs Realisasi)
                        </h4>
                        <canvas id="incomeChart" height="220"></canvas>
                    </div>
                    <div class="p-6 bg-white border border-gray-100 shadow-xl rounded-2xl">
                        <h4 class="mb-4 text-lg font-semibold text-gray-800">
                            Belanja Desa (Anggaran vs Realisasi)
                        </h4>
                        <canvas id="expenseChart" height="220"></canvas>
                    </div>
                </div>

                <div class="text-center">
                    <a id="apbdes-pdf-link" href="#" target="_blank"
                        class="inline-flex items-center gap-3 bg-[#2E7D32] text-white px-8 py-3 rounded-full font-medium transform transition-all duration-300 ease-in-out hover:bg-green-700 hover:-translate-y-1 hover:shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        <span id="apbdes-pdf-text">Lihat Dokumen Lengkap (PDF)</span>
                    </a>
                </div>
            </div>
    </section>



    <!-- SECTION: BERITA DESA (PREVIEW) -->
    <section id="berita" class="py-24 bg-white border-t border-slate-100">
        <div class="px-6 mx-auto max-w-7xl">
            <!-- HEADER -->
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
                <div class="max-w-2xl">
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold uppercase tracking-wider mb-4">
                        <span class="relative flex h-2 w-2">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        Warta Desa
                    </div>
                    <h2 class="text-3xl md:text-4xl font-black text-slate-800 mb-4 leading-tight">
                        Berita & Artikel <span class="text-emerald-600 italic">Terbaru</span>
                    </h2>
                    <p class="text-slate-600 text-lg leading-relaxed">
                        Ikuti perkembangan terkini, kebijakan, dan cerita inspiratif langsung dari Desa Sindangmukti.
                    </p>
                </div>
                <div>
                    <a href="{{ route('informasi.berita-artikel') }}"
                        class="group inline-flex items-center gap-2 bg-white text-emerald-700 font-bold px-6 py-3 rounded-2xl shadow-sm border border-emerald-100 hover:bg-emerald-600 hover:text-white hover:border-emerald-600 transition-all duration-300">
                        Lihat Semua Berita
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="w-5 h-5 group-hover:translate-x-1 transition-transform">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- GRID BERITA (Limit to 3) -->
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                @foreach (collect($articles)->take(3) as $artikel)
                    <x-frontend.berita-item :artikel="$artikel" />
                @endforeach
            </div>

            @if (empty($articles) || count($articles) == 0)
                <div class="text-center py-16 bg-slate-50 rounded-[2rem] border border-slate-100">
                    <i class="fa-regular fa-newspaper text-5xl text-slate-300 mb-4"></i>
                    <h3 class="text-lg font-bold text-slate-700 mb-2">Belum Ada Artikel</h3>
                    <p class="text-sm text-slate-500">Artikel terbaru belum tersedia saat ini.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- SECTION: LAPAK DESA (PREVIEW) -->
    <section id="lapak" class="py-24 bg-slate-50/50">
        <div class="px-6 mx-auto max-w-7xl">
            <!-- HEADER -->
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
                <div class="max-w-2xl">
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold uppercase tracking-wider mb-4">
                        <span class="relative flex h-2 w-2">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        Ekonomi Kreatif
                    </div>
                    <h2 class="text-3xl md:text-4xl font-black text-slate-800 mb-4 leading-tight">
                        Lapak Desa <span class="text-emerald-600 italic">Sindangmukti</span>
                    </h2>
                    <p class="text-slate-600 text-lg leading-relaxed">
                        Temukan beragam produk, jasa, dan potensi unggulan masyarakat Desa Sindangmukti langsung dari tangan
                        pertama.
                    </p>
                </div>
                <div>
                    <a href="{{ route('lapak') }}"
                        class="group inline-flex items-center gap-2 bg-white text-emerald-700 font-bold px-6 py-3 rounded-2xl shadow-sm border border-emerald-100 hover:bg-emerald-600 hover:text-white hover:border-emerald-600 transition-all duration-300">
                        Jelajahi Lapak
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="w-5 h-5 group-hover:translate-x-1 transition-transform">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- GRID PRODUK (Limit to 8) -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach (collect($products)->take(8) as $produk)
                    <x-frontend.lapak-item :produk="$produk" />
                @endforeach
            </div>

            <!-- BOTTOM CTA -->
            <div class="mt-16 text-center">
                <a href="{{ route('lapak') }}"
                    class="inline-flex items-center gap-3 bg-emerald-600 text-white font-bold px-10 py-4 rounded-3xl shadow-[0_10px_40px_rgba(5,150,105,0.2)] hover:bg-emerald-700 hover:-translate-y-1 transition-all duration-300">
                    Lihat Selengkapnya
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                        stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                    </svg>
                </a>
                <p class="mt-6 text-slate-500 text-sm font-medium">
                    Mendukung Produk Lokal, Memajukan Desa.
                </p>
            </div>
        </div>
    </section>


    <section id="galeri" class="overflow-hidden py-24 bg-slate-50 border-t border-slate-100" x-data="{
        tab: 'all',
        categories: [],
        lightboxOpen: false,
        activeImage: null,
        albums: {{ Js::from($albums) }},
    
        init() {
            const allCategories = this.albums.map(a => a.category);
            const uniqueCategories = [...new Set(allCategories)];
    
            const labelMap = {
                'pembangunan': 'Pembangunan',
                'sosial': 'Kegiatan Sosial',
                'lainnya': 'Lainnya'
            };
    
            this.categories = [
                { key: 'all', label: 'Semua Album' },
                ...uniqueCategories.map(key => ({
                    key: key,
                    label: labelMap[key] || key.charAt(0).toUpperCase() + key.slice(1)
                }))
            ];
        },
    
        get filteredAlbums() {
            return this.tab === 'all' ? this.albums : this.albums.filter(a => a.category === this.tab);
        },
    
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
                this.activeImage = this.filteredAlbums.length - 1;
            }
        },
    
        nextImage() {
            if (this.activeImage < this.filteredAlbums.length - 1) {
                this.activeImage++;
            } else {
                this.activeImage = 0;
            }
        }
    }">
        <div class="px-6 mx-auto max-w-7xl">
            <!-- HEADER -->
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
                <div class="max-w-2xl">
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold uppercase tracking-wider mb-4">
                        <span class="relative flex h-2 w-2">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        Dokumentasi
                    </div>
                    <h2 class="text-3xl md:text-4xl font-black text-slate-800 mb-4 leading-tight">
                        Galeri <span class="text-emerald-600 italic">Desa</span>
                    </h2>
                    <p class="text-slate-600 text-lg leading-relaxed">
                        Kumpulan foto dokumentasi kegiatan, pembangunan, dan momen penting masyarakat Desa Sindangmukti.
                    </p>
                </div>
                <div>
                    <a href="{{ route('informasi.galeri') }}"
                        class="group inline-flex items-center gap-2 bg-white text-emerald-700 font-bold px-6 py-3 rounded-2xl shadow-sm border border-emerald-100 hover:bg-emerald-600 hover:text-white hover:border-emerald-600 transition-all duration-300">
                        Lihat Semua Galeri
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="w-5 h-5 group-hover:translate-x-1 transition-transform">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- FILTER CATEGORIES -->
            <div class="flex flex-wrap justify-center gap-3 mb-10 pb-4 border-b border-slate-200/60">
                <template x-for="btn in categories" :key="btn.key">
                    <button @click="tab = btn.key"
                        :class="tab === btn.key ? 'bg-emerald-600 text-white font-semibold shadow-sm' :
                            'bg-white border-slate-200 text-slate-600 font-medium hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-300'"
                        class="px-5 py-2.5 text-sm transition-colors border rounded-full" x-text="btn.label"></button>
                </template>
            </div>

            <!-- GRID GALERI -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                <template x-for="(album, index) in filteredAlbums" :key="album.id">
                    <figure @click="openLightbox(index)"
                        class="relative group cursor-pointer overflow-hidden rounded-[2rem] shadow-sm border border-slate-100 bg-slate-100 aspect-[4/3] hover:shadow-xl transition-all duration-300">
                        <!-- Gambar -->
                        <img :src="album.img" :alt="album.title" loading="lazy"
                            class="object-cover w-full h-full transition-transform duration-700 transform group-hover:scale-110" />

                        <!-- Overlay Hitam Gradien -->
                        <div
                            class="absolute inset-0 flex flex-col justify-end p-5 transition-opacity duration-300 opacity-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent group-hover:opacity-100">
                            <span
                                class="bg-emerald-500 text-white text-[10px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wider w-max mb-2"
                                x-text="categories.find(c => c.key === album.category)?.label || album.category">
                            </span>
                            <h3 class="mb-1 text-lg font-bold leading-tight text-white" x-text="album.title"></h3>
                        </div>

                        <!-- Ikon Zoom Tengah -->
                        <div
                            class="absolute inset-0 z-10 flex items-center justify-center transition-opacity duration-300 opacity-0 pointer-events-none group-hover:opacity-100">
                            <div
                                class="flex items-center justify-center w-12 h-12 text-white rounded-full bg-white/20 backdrop-blur-sm shadow-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </figure>
                </template>
            </div>
        </div>

        <!-- LIGHTBOX PREVIEW MODAL -->
        <div x-show="lightboxOpen" x-cloak
            class="fixed inset-0 z-[100] flex flex-col items-center justify-center p-4 transition-opacity bg-black/95"
            style="display: none;" @keydown.escape.window="closeLightbox()" @keydown.left.window="prevImage()"
            @keydown.right.window="nextImage()">

            <template x-if="activeImage !== null && filteredAlbums[activeImage]">
                <div class="relative flex items-center justify-center w-full h-full">
                    <!-- Action Top Bar -->
                    <div
                        class="absolute top-0 left-0 right-0 z-20 flex items-center justify-between p-4 md:p-6 bg-gradient-to-b from-black/70 to-transparent">
                        <p class="text-lg font-semibold text-white md:text-xl drop-shadow-md"
                            x-text="filteredAlbums[activeImage].title"></p>
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
                        <img :src="filteredAlbums[activeImage].img" :alt="filteredAlbums[activeImage].title"
                            x-show="lightboxOpen" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-95 -translate-y-10"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            class="object-contain max-w-full max-h-full shadow-2xl rounded-sm pointer-events-auto">
                    </div>
                </div>
            </template>
        </div>
    </section>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof Chart !== 'undefined') {
                // ── Gender Pie Chart ────────────────
                const ctxGender = document.getElementById('genderChart');
                if (ctxGender) {
                    new Chart(ctxGender, {
                        type: 'pie',
                        data: {
                            labels: {!! json_encode($genderData['labels']) !!},
                            datasets: [{
                                data: {!! json_encode($genderData['data']) !!},
                                backgroundColor: ['#0288D1', '#FBC02D']
                            }]
                        },
                        options: {
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }
                    });
                }

                // ── Pekerjaan Bar Chart ────────────────
                const ctxPekerjaan = document.getElementById('pekerjaanChart');
                if (ctxPekerjaan) {
                    new Chart(ctxPekerjaan, {
                        type: 'bar',
                        data: {
                            labels: {!! json_encode($pekerjaanChart['labels']) !!},
                            datasets: [{
                                label: 'Jumlah Penduduk',
                                data: {!! json_encode($pekerjaanChart['data']) !!},
                                backgroundColor: '#2E7D32'
                            }]
                        },
                        options: {
                            indexAxis: 'y',
                            plugins: {
                                legend: {
                                    display: false
                                }
                            }
                        }
                    });
                }

                // ── APBDes Income Chart ────────────────
                const ctxIncome = document.getElementById('incomeChart');
                if (ctxIncome) {
                    new Chart(ctxIncome, {
                        type: 'bar',
                        data: {
                            labels: {!! json_encode($incomeChartData['labels']) !!},
                            datasets: [{
                                    label: 'Anggaran',
                                    data: {!! json_encode($incomeChartData['anggaran']) !!},
                                    backgroundColor: '#2E7D32'
                                },
                                {
                                    label: 'Realisasi',
                                    data: {!! json_encode($incomeChartData['realisasi']) !!},
                                    backgroundColor: '#81C784'
                                }
                            ]
                        },
                        options: {
                            indexAxis: 'y',
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                }
                            },
                            scales: {
                                x: {
                                    ticks: {
                                        callback: v => 'Rp ' + (v / 1000000).toFixed(0) + ' Jt'
                                    }
                                }
                            }
                        }
                    });
                }

                // ── APBDes Expense Chart ────────────────
                const ctxExpense = document.getElementById('expenseChart');
                if (ctxExpense) {
                    new Chart(ctxExpense, {
                        type: 'bar',
                        data: {
                            labels: {!! json_encode($expenseChartData['labels']) !!},
                            datasets: [{
                                    label: 'Anggaran',
                                    data: {!! json_encode($expenseChartData['anggaran']) !!},
                                    backgroundColor: '#D32F2F'
                                },
                                {
                                    label: 'Realisasi',
                                    data: {!! json_encode($expenseChartData['realisasi']) !!},
                                    backgroundColor: '#EF9A9A'
                                }
                            ]
                        },
                        options: {
                            indexAxis: 'y',
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                }
                            },
                            scales: {
                                x: {
                                    ticks: {
                                        callback: v => 'Rp ' + (v / 1000000).toFixed(0) + ' Jt'
                                    }
                                }
                            }
                        }
                    });
                }
            }
        });

        document.addEventListener("DOMContentLoaded", function() {
            // Koordinat tengah Peta
            const desaSindangmukti = [-7.145544225495087, 108.21370969734988];

            // Inisialisasi peta
            const map = L.map("map").setView(desaSindangmukti, 14);

            // Menggunakan layer satelit Esri World Imagery
            L.tileLayer(
                "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}", {
                    attribution: "Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community",
                    maxZoom: 18,
                }
            ).addTo(map);

            // --- 1. PENANDA (MARKER) UTAMA ---
            const marker = L.marker(desaSindangmukti).addTo(map);
            marker
                .bindPopup("<b>Desa Sindangmukti</b><br>Kec. Panumbangan, Kab. Ciamis")
                .openPopup(); // Langsung buka popup ini

            // --- 2. TAMBAHAN MARKER TEMPAT PENTING ---

            const coordsBalaiDesa = [-7.1464646138911885, 108.21314313084818];
            const markerBalaiDesa = L.marker(coordsBalaiDesa).addTo(map);
            markerBalaiDesa.bindPopup("<b>Kantor Desa Sindangmukti</b>");

            const coordsGedungDakwah = [-7.147966238341924, 108.21101821600854];
            const markerMasjid = L.marker(coordsGedungDakwah).addTo(map);
            markerMasjid.bindPopup("<b>Gedung Dakwah</b>");

            const coordsSekolah = [-7.146636192955685, 108.21239501257453];
            const markerSekolah = L.marker(coordsSekolah).addTo(map);
            markerSekolah.bindPopup("<b>SDN 1 Sindangmukti</b>");

            // --- 3. BATAS WILAYAH (GEOJSON) ---

            // Nama file GeoJSON menggunakan Laravel asset helper
            const urlGeoJSON = "{{ asset('assets/js/map.geojson') }}";

            // Fungsi untuk memberi gaya pada batas wilayah
            function styleBatas(feature) {
                return {
                    color: "#2f7f33", // Warna garis batas (hijau tua)
                    weight: 3, // Ketebalan garis
                    opacity: 0.8, // Opasitas garis
                    fillColor: "#2f7f33", // Warna isian di dalam batas
                    fillOpacity: 0.1, // Opasitas isian (10%)
                };
            }

            // Memuat file GeoJSON dan menambahkannya ke peta
            fetch(urlGeoJSON)
                .then((response) => response.json())
                .then((data) => {
                    L.geoJSON(data, {
                        style: styleBatas, // Terapkan gaya yang sudah dibuat
                    }).addTo(map);
                })
                .catch((err) => {
                    console.error("Error: Gagal memuat file GeoJSON.", err);
                });
        });
    </script>
@endpush
