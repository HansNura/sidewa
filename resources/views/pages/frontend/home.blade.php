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
                class="flex items-center gap-2 px-4 py-2 mx-auto mb-6 transition border rounded-full border-gray-300/40 hover:border-gray-200/70 w-max bg-white/10 backdrop-blur-sm">
                <span class="text-sm text-gray-100">Selamat datang di laman resmi</span>
                <span class="font-semibold text-yellow-400">Desa Sindangmukti</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                    class="ml-1">
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

    <!-- Jelajahi Desa Section -->
    <section id="jelajahi" class="py-20 bg-[#F9FAFB]">
        <div class="px-6 mx-auto text-center max-w-7xl">
            <!-- Judul -->
            <h2 class="text-3xl md:text-4xl font-extrabold text-[#2E7D32]">
                Jelajahi Desa Sindangmukti
            </h2>
            <p class="max-w-2xl mx-auto mt-3 text-gray-600">
                Temukan informasi seputar profil desa, layanan publik,
                potensi daerah, dan berita terbaru dari Desa Sindangmukti.
            </p>

            <!-- Grid Navigasi -->
            <div class="grid grid-cols-1 gap-8 mt-12 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Kartu 1: Profil Desa -->
                <div x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false"
                    class="p-6 transition duration-300 transform bg-white border border-gray-100 shadow-sm rounded-2xl hover:-translate-y-2 hover:shadow-lg">
                    <div class="flex justify-center mb-4">
                        <div class="bg-[#2E7D32]/10 p-3 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-[#2E7D32]" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 2l9 4.9v10.2L12 22l-9-4.9V6.9L12 2z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="mb-2 text-xl font-semibold text-gray-800">
                        Profil Desa
                    </h3>
                    <p class="mb-4 text-gray-600">
                        Kenali sejarah, visi misi, dan struktur
                        pemerintahan Desa Sindangmukti.
                    </p>
                    <a href="{{ url('profil') }}" class="inline-block text-[#2E7D32] font-medium hover:text-[#25632a]">
                        Lihat Profil &rarr;
                    </a>
                </div>

                <!-- Kartu 2: Layanan -->
                <div x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false"
                    class="p-6 transition duration-300 transform bg-white border border-gray-100 shadow-sm rounded-2xl hover:-translate-y-2 hover:shadow-lg">
                    <div class="flex justify-center mb-4">
                        <div class="bg-[#0288D1]/10 p-3 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-[#0288D1]" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m2 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="mb-2 text-xl font-semibold text-gray-800">
                        Layanan Desa
                    </h3>
                    <p class="mb-4 text-gray-600">
                        Ajukan surat atau layanan administrasi secara
                        online dengan mudah.
                    </p>
                    <a href="{{ url('layanan') }}" class="inline-block text-[#0288D1] font-medium hover:text-[#0273B8]">
                        Akses Layanan &rarr;
                    </a>
                </div>

                <!-- Kartu 3: Berita Desa -->
                <div x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false"
                    class="p-6 transition duration-300 transform bg-white border border-gray-100 shadow-sm rounded-2xl hover:-translate-y-2 hover:shadow-lg">
                    <div class="flex justify-center mb-4">
                        <div class="bg-[#FBC02D]/10 p-3 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-[#FBC02D]" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21H5a2 2 0 01-2-2V7a2 2 0 012-2h4l2-2h6a2 2 0 012 2v14a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="mb-2 text-xl font-semibold text-gray-800">
                        Berita & Kegiatan
                    </h3>
                    <p class="mb-4 text-gray-600">
                        Baca berita terkini dan dokumentasi kegiatan
                        desa dalam satu laman.
                    </p>
                    <a href="{{ url('galeri') }}" class="inline-block text-[#FBC02D] font-medium hover:text-[#D6A513]">
                        Lihat Berita &rarr;
                    </a>
                </div>

                <!-- Kartu 4: Potensi Desa -->
                <div x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false"
                    class="p-6 transition duration-300 transform bg-white border border-gray-100 shadow-sm rounded-2xl hover:-translate-y-2 hover:shadow-lg">
                    <div class="flex justify-center mb-4">
                        <div class="bg-[#2E7D32]/10 p-3 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-[#2E7D32]" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 20l9-5-9-5-9 5 9 5z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="mb-2 text-xl font-semibold text-gray-800">
                        Potensi Desa
                    </h3>
                    <p class="mb-4 text-gray-600">
                        Eksplorasi potensi ekonomi, wisata, dan sumber
                        daya alam Desa Sindangmukti.
                    </p>
                    <a href="{{ url('lapak') }}" class="inline-block text-[#2E7D32] font-medium hover:text-[#25632a]">
                        Jelajahi Potensi &rarr;
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Sambutan Kepala Desa -->
    <section id="sambutan" class="py-24 bg-white">
        <div class="grid items-center max-w-6xl gap-12 px-6 mx-auto md:grid-cols-2">
            <!-- FOTO KEPALA DESA -->
            <div class="relative">
                <img src="{{ asset('assets/img/people.png') }}" alt="Kepala Desa {{ $village->nama_desa }}"
                    class="object-cover w-full" />
                <div
                    class="absolute -bottom-4 -right-4 bg-[#2E7D32] text-white px-4 py-2 rounded-tl-lg rounded-br-2xl shadow-md text-sm">
                    {{ $village->jabatan_kades ?? 'Kepala Desa' }} {{ $village->nama_desa }}
                </div>
            </div>

            <!-- TEKS SAMBUTAN -->
            <div>
                <h2 class="text-3xl md:text-4xl font-extrabold text-[#2E7D32] mb-4">
                    Sambutan {{ $village->jabatan_kades ?? 'Kepala Desa' }}
                </h2>
                <p class="mb-6 leading-relaxed text-gray-700">
                    Assalamu'alaikum warahmatullahi wabarakatuh. Puji
                    syukur kita panjatkan ke hadirat Allah SWT, karena
                    atas rahmat dan karunia-Nya website resmi
                    <strong>Desa {{ $village->nama_desa }}</strong> ini dapat terwujud
                    sebagai sarana informasi, komunikasi, dan
                    transparansi bagi seluruh masyarakat.
                </p>
                <p class="mb-6 leading-relaxed text-gray-700">
                    Melalui media ini, kami berharap masyarakat dapat
                    lebih mudah mengakses berbagai informasi mengenai
                    profil desa, potensi wilayah, serta pelayanan
                    administrasi secara digital. Mari bersama-sama kita
                    wujudkan desa yang
                    <span class="font-semibold text-[#2E7D32]">sejahtera, mandiri, dan berdaya saing</span>.
                </p>

                <!-- Tanda tangan -->
                <div class="mt-8">
                    <p class="text-lg font-semibold text-gray-800">
                        {{ $village->nama_kades ?? 'Kepala Desa' }}
                    </p>
                    <p class="text-sm text-gray-500">
                        {{ $village->jabatan_kades ?? 'Kepala Desa' }} {{ $village->nama_desa }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Peta Desa Section -->
    <section class="bg-[#F9FAFB] py-24">
        <div class="max-w-6xl px-6 mx-auto text-center">
            <h2 class="text-3xl md:text-4xl font-extrabold text-[#2E7D32] mb-4">
                Peta Desa Sindangmukti
            </h2>
            <p class="max-w-2xl mx-auto mb-10 text-gray-700">
                Lihat lokasi dan batas wilayah Desa Sindangmukti secara
                langsung melalui peta interaktif di bawah ini. Gunakan
                fitur zoom dan geser untuk menjelajahi wilayah desa
                kami.
            </p>

            <div id="map"
                class="relative z-10 w-full h-[500px] rounded-2xl shadow-md overflow-hidden border border-gray-200">
            </div>
        </div>
    </section>

    <!-- STRUKTUR ORGANISASI BPD -->
    <section id="bpd" class="bg-white py-24">
        <div class="max-w-6xl px-6 mx-auto text-center">
            <h2 class="text-3xl md:text-4xl font-extrabold text-[#2E7D32] mb-4">
                Struktur Organisasi BPD Desa Sindangmukti
            </h2>
            <p class="max-w-3xl mx-auto mb-12 text-gray-700">
                <strong>Badan Permusyawaratan Desa (BPD)</strong>
                merupakan lembaga pemerintahan desa yang memiliki peran
                penting dalam membahas dan menyepakati peraturan desa,
                menampung aspirasi masyarakat, serta mengawasi kinerja
                kepala desa dalam mewujudkan tata kelola pemerintahan
                yang partisipatif dan transparan.
            </p>

            <!-- GRID STRUKTUR -->
            <div class="grid gap-8 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4" x-data="{ selected: null }">
                @foreach ($bpdMembers as $index => $anggota)
                    <x-frontend.bpd-card :anggota="$anggota" :index="$index" />
                @endforeach
            </div>

            <!-- Keterangan Bawah -->
            <p class="mt-10 text-sm text-gray-500">
                Masa Bakti Anggota BPD:
                <strong>2019 – 2025</strong>
            </p>
        </div>
    </section>

    <!-- ADMINISTRASI PENDUDUK -->
    <section id="penduduk" class="py-24 bg-[#F9FAFB]">
        <div class="max-w-6xl px-6 mx-auto text-center">
            <h2 class="text-3xl md:text-4xl font-extrabold text-[#2E7D32] mb-4">
                Administrasi Penduduk
            </h2>
            <p class="max-w-2xl mx-auto mb-12 text-gray-700">
                Berikut adalah data penduduk Desa Sindangmukti yang
                mencakup jumlah Kepala Keluarga (KK), perbandingan
                gender, dan distribusi pekerjaan masyarakat.
            </p>

            <!-- Statistik Ringkas -->
            <div class="grid gap-8 mb-16 sm:grid-cols-2 md:grid-cols-3">
                <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-6">
                    <h3 class="text-2xl font-bold text-[#2E7D32]">
                        {{ number_format($totalKK) }}
                    </h3>
                    <p class="text-gray-600">Jumlah Kepala Keluarga</p>
                </div>
                <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-6">
                    <h3 class="text-2xl font-bold text-[#2E7D32]">
                        {{ number_format($pendudukStats['total']) }}
                    </h3>
                    <p class="text-gray-600">Total Penduduk</p>
                </div>
                <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-6">
                    <h3 class="text-2xl font-bold text-[#2E7D32]">
                        {{ $totalRtRw }}
                    </h3>
                    <p class="text-gray-600">Jumlah Wilayah</p>
                </div>
            </div>

            <!-- GRAFIK -->
            <div class="grid gap-12 md:grid-cols-2">
                <!-- Grafik Gender -->
                <div class="bg-white border border-gray-100 p-6 rounded-2xl shadow-md">
                    <h4 class="mb-4 font-semibold text-gray-700">
                        Perbandingan Gender
                    </h4>
                    <canvas id="genderChart" height="200"></canvas>
                </div>

                <!-- Grafik Pekerjaan -->
                <div class="bg-white border border-gray-100 p-6 rounded-2xl shadow-md">
                    <h4 class="mb-4 font-semibold text-gray-700">
                        Distribusi Pekerjaan
                    </h4>
                    <canvas id="pekerjaanChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </section>

    <section id="apbdes" class="bg-[#F9FAFB] py-24">
        <div class="max-w-6xl px-6 mx-auto">
            <div class="mb-16 text-center">
                <h2 class="text-3xl md:text-4xl font-extrabold text-[#2E7D32] mb-3">
                    Transparansi APBDes Tahun
                    <span>{{ $tahunApbdes }}</span>
                </h2>
                <p class="max-w-2xl mx-auto text-gray-700">
                    Data Anggaran Pendapatan dan Belanja Desa Sindangmukti.
                    Informasi ini bertujuan untuk mendukung transparansi
                    dan akuntabilitas keuangan desa.
                </p>
            </div>

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
                        <div class="h-3 bg-[#2E7D32] transition-all duration-700" style="width: {{ min($pendapatanPersen, 100) }}%"></div>
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
                        <div class="h-3 bg-red-600 transition-all duration-700" style="width: {{ min($belanjaPersen, 100) }}%"></div>
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



    <section id="berita" class="py-24 bg-[#F9FAFB]">
        <div class="px-6 mx-auto max-w-7xl" x-data="{
            berita: {{ Js::from($articles) }},
            currentPage: 1,
            itemsPerPage: 3,
            get totalPages() {
                return Math.ceil(this.berita.length / this.itemsPerPage)
            },
            get paginatedBerita() {
                const start = (this.currentPage - 1) * this.itemsPerPage;
                const end = start + this.itemsPerPage;
                return this.berita.slice(start, end);
            },
            nextPage() {
                if (this.currentPage < this.totalPages) this.currentPage++;
            },
            prevPage() {
                if (this.currentPage > 1) this.currentPage--;
            },
            goToPage(page) {
                this.currentPage = page;
            }
        }">
            <div class="mb-12 text-center">
                <h2 class="text-3xl md:text-4xl font-extrabold text-[#2E7D32] mb-3">
                    Berita & Artikel Desa
                </h2>
                <p class="max-w-2xl mx-auto text-gray-600">
                    Update informasi terkini seputar kegiatan,
                    pembangunan, dan kebijakan Desa Sindangmukti.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-10 md:grid-cols-2 lg:grid-cols-3">
                <template x-for="item in paginatedBerita" :key="item.id">
                    <div
                        class="flex flex-col overflow-hidden transition bg-white shadow-md group rounded-2xl hover:shadow-lg">
                        <div class="relative">
                            <a :href="item.url">
                                <img :src="item.imgSrc" :alt="item.judul"
                                    class="object-cover w-full transition duration-500 aspect-video group-hover:scale-105" />
                            </a>
                            <div class="absolute top-0 left-0 bg-[#2E7D32]/80 text-white text-sm px-3 py-1 rounded-br-lg"
                                x-text="item.tanggal"></div>
                        </div>
                        <div class="flex flex-col flex-grow p-6">
                            <h4 class="font-semibold text-xl text-gray-800 mb-3 group-hover:text-[#2E7D32] transition">
                                <a :href="item.url" x-text="item.judul"></a>
                            </h4>

                            <div class="flex items-center justify-between mb-4 text-sm text-gray-600">
                                <span class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                    </svg>
                                    <span x-text="item.admin"></span>
                                </span>
                                <span class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                    <span x-text="item.views + ' dibuka'"></span>
                                </span>
                            </div>

                            <a :href="item.url"
                                class="inline-block mt-auto text-[#2E7D32] font-medium hover:underline">
                                Buka Halaman →
                            </a>
                        </div>
                    </div>
                </template>
            </div>

            <div class="flex items-center justify-center gap-2 mt-12">
                <button @click="prevPage()" :disabled="currentPage === 1"
                    class="px-4 py-2 text-sm text-gray-700 border rounded-md hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed">
                    «
                </button>

                <template x-for="page in totalPages" :key="page">
                    <button @click="goToPage(page)" class="px-4 py-2 text-sm border rounded-md"
                        :class="{
                            'bg-[#2E7D32] text-white': currentPage === page,
                            'hover:bg-gray-100': currentPage !== page
                        }"
                        x-text="page"></button>
                </template>

                <button @click="nextPage()" :disabled="currentPage === totalPages"
                    class="px-4 py-2 text-sm text-gray-700 border rounded-md hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed">
                    ▸
                </button>
            </div>
        </div>
    </section>

    <section id="lapak" class="py-24 bg-white">
        <div class="px-6 mx-auto max-w-7xl" x-data="{
            search: '',
            category: 'Semua',
            categories: ['Semua', ...{{ Js::from($productCategories) }}],
            produkList: {{ Js::from($products) }},

            get filteredProduk() {
                return this.produkList.filter(p =>
                    (this.category === 'Semua' || p.kategori === this.category) &&
                    (this.search === '' || p.nama.toLowerCase().includes(this.search.toLowerCase()))
                );
            },

            formatHarga(angka) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(angka);
            }
        }">
            <!-- JUDUL -->
            <div class="mb-12 text-center">
                <h2 class="text-3xl md:text-4xl font-extrabold text-[#2E7D32] mb-3">
                    Lapak Desa Sindangmukti
                </h2>
                <p class="max-w-2xl mx-auto text-gray-600">
                    Temukan beragam produk, jasa, dan potensi unggulan
                    masyarakat Desa Sindangmukti.
                </p>
            </div>

            <!-- FILTER BAR -->
            <div class="flex flex-col items-center justify-between gap-4 mb-10 md:flex-row">
                <div class="flex items-center w-full gap-3 md:w-auto">
                    <label for="kategori" class="font-medium text-gray-700 whitespace-nowrap">Kategori:</label>
                    <select id="kategori" x-model="category"
                        class="border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#2E7D32] w-full">
                        <template x-for="cat in categories" :key="cat">
                            <option :value="cat" x-text="cat"></option>
                        </template>
                    </select>
                </div>
                <!-- Diperbaiki: Typo pada class list -->
                <div class="relative w-full md:w-auto lg:w-1/3">
                    <input type="text" placeholder="Cari produk..." x-model="search"
                        class="border rounded-lg w-full px-4 py-2 pl-10 focus:outline-none focus:ring-2 focus:ring-[#2E7D32]" />
                    <span class="absolute text-gray-400 -translate-y-1/2 left-3 top-1/2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                            <path fill-rule="evenodd"
                                d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z"
                                clip-rule="evenodd" />
                        </svg>
                    </span>
                </div>
            </div>

            <!-- GRID PRODUK -->
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                <template x-for="produk in filteredProduk" :key="produk.id">
                    <div
                        class="flex flex-col overflow-hidden transition bg-white border shadow-md rounded-2xl hover:shadow-lg">
                        <img :src="produk.foto" :alt="produk.nama" class="object-cover w-full h-52" />
                        <div class="flex flex-col flex-1 p-5">
                            <h4 class="mb-1 text-lg font-semibold text-gray-800" x-text="produk.nama"></h4>
                            <p class="text-[#2E7D32] font-bold mb-2" x-text="formatHarga(produk.harga)"></p>
                            <p class="mb-1 text-sm text-gray-600">
                                <strong>Kategori:</strong>
                                <span x-text="produk.kategori"></span>
                            </p>
                            <p class="mb-3 text-sm text-gray-600">
                                <strong>Pelapak:</strong>
                                <span x-text="produk.pelapak"></span>
                            </p>
                            <div class="flex gap-3 mt-auto">
                                <!-- PERBAIKAN #9: Ikon ditambahkan ke tombol -->
                                <button
                                    class="flex-1 bg-[#2E7D32] text-white py-2 px-3 rounded-lg text-sm hover:bg-[#256928] transition flex items-center justify-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                                        class="w-4 h-4">
                                        <path
                                            d="M2.5 3A1.5 1.5 0 0 0 1 4.5V5a.5.5 0 0 0 .5.5H2a.5.5 0 0 0 .484-.375A2.484 2.484 0 0 1 4.312 3H2.5zM14 4.5A1.5 1.5 0 0 0 12.5 3h-8.188a2.484 2.484 0 0 1 1.826 1.5H13.5a.5.5 0 0 0 .5-.5V4.5zM1.5 6a.5.5 0 0 0-.5.5v2A1.5 1.5 0 0 0 2.5 10H3a.5.5 0 0 0 .484-.375A2.484 2.484 0 0 1 5.312 8H1.5V6zM14.5 6H5.312a2.484 2.484 0 0 1 1.826 1.5H14.5a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zM3.484 10.625A.5.5 0 0 0 3 11h1.312a2.484 2.484 0 0 1 1.826 1.5H1.5v-1a.5.5 0 0 0-.5-.5A1.5 1.5 0 0 0 0 12.5v2A1.5 1.5 0 0 0 1.5 16h13A1.5 1.5 0 0 0 16 14.5v-2A1.5 1.5 0 0 0 14.5 11H6.688a2.484 2.484 0 0 1-1.826-1.5H3.484z" />
                                    </svg>
                                    Beli
                                </button>
                                <button
                                    class="flex-1 border border-[#2E7D32] text-[#2E7D32] py-2 px-3 rounded-lg text-sm hover:bg-[#2E7D32]/10 transition flex items-center justify-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                                        class="w-4 h-4">
                                        <path fill-rule="evenodd"
                                            d="M8.5 3a.5.5 0 0 0-1 0v.518a3.5 3.5 0 0 0-2.35 1.14l-.367-.368a.5.5 0 0 0-.708.708l.368.367A3.5 3.5 0 0 0 3.518 7.5H3a.5.5 0 0 0 0 1h.518a3.5 3.5 0 0 0 1.14 2.35l-.368.367a.5.5 0 1 0 .708.708l.367-.368A3.5 3.5 0 0 0 7.5 12.482V13a.5.5 0 0 0 1 0v-.518a3.5 3.5 0 0 0 2.35-1.14l.367.368a.5.5 0 0 0 .708-.708l-.368-.367A3.5 3.5 0 0 0 12.482 8.5H13a.5.5 0 0 0 0-1h-.518a3.5 3.5 0 0 0-1.14-2.35l.368-.367a.5.5 0 1 0-.708-.708l-.367.368A3.5 3.5 0 0 0 8.5 3.518V3zM8 5.5A2.5 2.5 0 1 0 8 10.5 2.5 2.5 0 0 0 8 5.5z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Detail
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </section>

    <section id="galeri" class="overflow-hidden py-24 bg-[#F9FAFB]" x-data="{
        tab: 'all',
        categories: [],
        modalOpen: false,
        modalImage: null,
        albums: {{ Js::from($albums) }},
    
        // IMPROVEMENT 1: Membuat kategori filter dinamis
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
    
        // IMPROVEMENT 2: Fungsi untuk membuka modal lightbox
        openModal(album) {
            this.modalImage = album;
            this.modalOpen = true;
        },
    
        closeModal() {
            this.modalOpen = false;
            // Menunggu transisi selesai sebelum menghapus gambar
            setTimeout(() => { this.modalImage = null }, 300);
        }
    }" x-init="init()">
        <div class="px-6 mx-auto max-w-7xl">
            <!-- JUDUL -->
            <div class="mb-12 text-center">
                <h2 class="text-3xl md:text-4xl font-extrabold text-[#2E7D32] mb-3">
                    Galeri Kegiatan & Pembangunan
                </h2>
                <p class="max-w-2xl mx-auto text-gray-600">
                    Dokumentasi kegiatan, pembangunan, dan momen penting
                    Desa Sindangmukti dari tahun ke tahun.
                </p>
            </div>

            <div class="flex flex-wrap justify-center gap-3 mb-10">
                <template x-for="btn in categories" :key="btn.key">
                    <button @click="tab = btn.key"
                        :class="tab === btn.key ? 'bg-[#2E7D32] text-white' : 'bg-white border text-gray-700 hover:bg-[#E8F5E9]'"
                        class="px-5 py-2 font-medium transition border rounded-full" x-text="btn.label"></button>
                </template>
            </div>

            <!-- GRID GALERI -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                <template x-for="album in filteredAlbums" :key="album.id">
                    <div @click="openModal(album)"
                        class="relative overflow-hidden transition-all duration-300 shadow-lg cursor-pointer group rounded-2xl hover:shadow-xl">
                        <img :src="album.img" :alt="album.title"
                            class="object-cover w-full transition-transform duration-500 aspect-square group-hover:scale-105" />
                        <!-- Caption yang slide-up -->
                        <div
                            class="absolute bottom-0 left-0 w-full p-4 text-white transition-transform duration-300 ease-in-out translate-y-full bg-gradient-to-t from-black/80 to-transparent group-hover:translate-y-0">
                            <h4 class="text-lg font-semibold" x-text="album.title"></h4>
                            <span class="text-sm opacity-80"
                                x-text="categories.find(c => c.key === album.category)?.label || album.category"></span>
                        </div>
                        <div
                            class="absolute p-2 text-white transition-opacity duration-300 rounded-full opacity-0 top-3 right-3 bg-white/20 group-hover:opacity-100">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607zM10.5 7.5v6m3-3h-6" />
                            </svg>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <div x-show="modalOpen" @click="closeModal()" @keydown.escape.window="closeModal()" x-cloak
            x-transition.opacity.duration.300ms
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
            <div @click.stop class="relative max-w-3xl max-h-[90vh] w-full bg-white rounded-xl shadow-2xl overflow-hidden">
                <img :src="modalImage?.img" :alt="modalImage?.title"
                    class="w-full h-auto object-contain max-h-[calc(90vh-4rem)]" />
                <h4 class="p-4 pt-3 text-lg font-semibold text-center text-gray-800" x-text="modalImage?.title">
                </h4>
                <!-- Tombol Close Modal -->
                <button @click="closeModal()"
                    class="absolute p-2 text-white transition-colors rounded-full top-3 right-3 bg-gray-800/50 hover:bg-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
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
                                legend: { position: 'bottom' }
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
                            plugins: { legend: { display: false } }
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
                            datasets: [
                                { label: 'Anggaran', data: {!! json_encode($incomeChartData['anggaran']) !!}, backgroundColor: '#2E7D32' },
                                { label: 'Realisasi', data: {!! json_encode($incomeChartData['realisasi']) !!}, backgroundColor: '#81C784' }
                            ]
                        },
                        options: {
                            indexAxis: 'y',
                            plugins: { legend: { position: 'bottom' } },
                            scales: {
                                x: { ticks: { callback: v => 'Rp ' + (v/1000000).toFixed(0) + ' Jt' } }
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
                            datasets: [
                                { label: 'Anggaran', data: {!! json_encode($expenseChartData['anggaran']) !!}, backgroundColor: '#D32F2F' },
                                { label: 'Realisasi', data: {!! json_encode($expenseChartData['realisasi']) !!}, backgroundColor: '#EF9A9A' }
                            ]
                        },
                        options: {
                            indexAxis: 'y',
                            plugins: { legend: { position: 'bottom' } },
                            scales: {
                                x: { ticks: { callback: v => 'Rp ' + (v/1000000).toFixed(0) + ' Jt' } }
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
