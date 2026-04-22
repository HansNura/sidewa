@extends('layouts.frontend')

@section('title', $pageTitle . ' - Desa Sindangmukti')

@push('styles')
    <style>
        /* Custom scrollbar untuk tabel overflow */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
    <main class="flex-grow bg-gray-50 pt-16">

        <!-- SECTION 1: HEADER TRANSPARANSI -->
        <section class="bg-gradient-to-br from-green-800 to-green-600 text-white py-16 md:py-20 relative overflow-hidden">
            <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]">
            </div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
                <span
                    class="bg-green-700/50 text-green-100 text-sm font-semibold px-3 py-1 rounded-full border border-green-500/30 mb-4 inline-block">Data
                    Terbuka Publik</span>
                <h1 class="text-4xl md:text-5xl font-bold mb-4 tracking-tight">{{ $pageTitle }}</h1>
                <p class="text-lg text-green-100 max-w-2xl mx-auto leading-relaxed">
                    {{ $pageSubtitle }}
                </p>
            </div>
        </section>

        <!-- Modal Alpine Handler setup -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ imageModalOpen: false }">

            <!-- SECTION 2: FILTER TAHUN ANGGARAN (Simulasi State Aktif: 2024) -->
            <section
                class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-gray-200 pb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Laporan Anggaran: <span class="text-green-600">Tahun
                            {{ $tahunBerjalan }}</span></h2>
                    <p class="text-sm text-gray-500">Berikut adalah rincian realisasi keuangan desa untuk tahun berjalan.
                    </p>
                </div>
                <div
                    class="flex items-center bg-white border border-gray-300 rounded-lg shadow-sm px-4 py-2 hover:border-green-500 transition-colors">
                    <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <!-- Filter ini akan mereload data berdasarkan query param -->
                    <select id="tahunAnggaran"
                        class="bg-transparent border-none focus:ring-0 text-gray-800 font-bold cursor-pointer outline-none w-32"
                        onchange="window.location.href='?tahun='+this.value">
                        @foreach($availableYears as $year)
                            <option value="{{ $year }}" {{ $tahunBerjalan == $year ? 'selected' : '' }}>Tahun {{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </section>

            <!-- SECTION 3: RINGKASAN APBDES (HIGHLIGHT ANGKA) -->
            <section class="mb-12">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Card Pendapatan -->
                    <article
                        class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 hover:-translate-y-1 hover:shadow-lg transition-all">
                        <div class="flex justify-between items-start mb-2">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Pendapatan</p>
                            <i class="fa-solid fa-arrow-down text-blue-500 bg-blue-50 p-2 rounded-full"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Rp
                            {{ number_format($apbdesRingkasan['pendapatan_realisasi'] / 1000000, 1, ',', '.') }} Jt</h3>
                        <div class="w-full bg-gray-200 rounded-full h-2 mb-1">
                            @php $pendapatanPct = $apbdesRingkasan['pendapatan_target'] > 0 ? ($apbdesRingkasan['pendapatan_realisasi'] / $apbdesRingkasan['pendapatan_target']) * 100 : 0; @endphp
                            <div class="bg-blue-500 h-2 rounded-full" style="width: {{ min(100, $pendapatanPct) }}%"></div>
                        </div>
                        <p class="text-xs text-blue-600 font-medium">Realisasi {{ number_format($pendapatanPct, 0) }}% dari Anggaran</p>
                    </article>

                    <!-- Card Belanja -->
                    <article
                        class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-500 hover:-translate-y-1 hover:shadow-lg transition-all">
                        <div class="flex justify-between items-start mb-2">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Belanja</p>
                            <i class="fa-solid fa-cart-shopping text-red-500 bg-red-50 p-2 rounded-full"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Rp
                            {{ number_format($apbdesRingkasan['belanja_realisasi'] / 1000000, 1, ',', '.') }} Jt</h3>
                        <div class="w-full bg-gray-200 rounded-full h-2 mb-1">
                            @php $belanjaPct = $apbdesRingkasan['belanja_target'] > 0 ? ($apbdesRingkasan['belanja_realisasi'] / $apbdesRingkasan['belanja_target']) * 100 : 0; @endphp
                            <div class="bg-red-500 h-2 rounded-full" style="width: {{ min(100, $belanjaPct) }}%"></div>
                        </div>
                        <p class="text-xs text-red-600 font-medium">Realisasi {{ number_format($belanjaPct, 0) }}% dari Rp
                            {{ number_format($apbdesRingkasan['belanja_target'] / 1000000, 0, ',', '.') }} Jt</p>
                    </article>

                    <!-- Card Pembiayaan Netto -->
                    <article
                        class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500 hover:-translate-y-1 hover:shadow-lg transition-all">
                        <div class="flex justify-between items-start mb-2">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Pembiayaan Netto</p>
                            <i class="fa-solid fa-building-columns text-purple-500 bg-purple-50 p-2 rounded-full"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mt-2 mb-1">Rp
                            {{ number_format($apbdesRingkasan['pembiayaan_netto'], 0, ',', '.') }}</h3>
                        <p class="text-xs text-gray-500">Penerimaan - Pengeluaran</p>
                    </article>

                    <!-- Card Surplus / Defisit (SiLPA) -->
                    <article
                        class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500 hover:-translate-y-1 hover:shadow-lg transition-all">
                        <div class="flex justify-between items-start mb-2">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">SiLPA / Surplus</p>
                            <i class="fa-solid fa-piggy-bank text-green-500 bg-green-50 p-2 rounded-full"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-green-600 mt-2 mb-1">Rp
                            {{ number_format($apbdesRingkasan['silpa'], 0, ',', '.') }}</h3>
                        <p class="text-xs text-gray-500">Sisa Lebih Perhitungan Anggaran</p>
                    </article>
                </div>
            </section>

        <!-- SECTION 4: GRAFIK APBDES (VISUALISASI KEUANGAN) -->
        <section class="mb-14">
            <div class="flex items-center gap-3 mb-6">
                <div class="bg-green-100 p-2 rounded text-green-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Visualisasi Keuangan {{ $tahunBerjalan }}</h2>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Chart Anggaran vs Realisasi (Bar Chart) -->
                <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-700 mb-4 text-center">Perbandingan Anggaran vs Realisasi</h3>
                    <div class="relative h-72 w-full">
                        <canvas id="anggaranRealisasiChart"></canvas>
                    </div>
                </div>
                <!-- Chart Proporsi Belanja (Doughnut Chart) -->
                <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-700 mb-4 text-center">Proporsi Bidang Belanja Desa</h3>
                    <div class="relative h-72 w-full flex justify-center">
                        <canvas id="belanjaDoughnutChart"></canvas>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION 5: DETAIL TABEL (PENDAPATAN, BELANJA, PEMBIAYAAN) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-14">
            <!-- Tabel Pendapatan -->
            <section>
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-200 pb-2">Rincian Pendapatan</h3>
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Uraian
                                </th>
                                <th scope="col"
                                    class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Anggaran
                                    (Rp)</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($rincianPendapatan as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-5 py-3 text-sm text-gray-700">{{ $item['uraian'] }}</td>
                                    <td class="px-5 py-3 text-sm font-medium text-gray-900 text-right">
                                        {{ number_format($item['anggaran'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-blue-50">
                            <tr>
                                <td class="px-5 py-4 text-sm font-bold text-gray-800">JUMLAH PENDAPATAN</td>
                                <td class="px-5 py-4 text-sm font-bold text-blue-700 text-right">
                                    {{ number_format($apbdesRingkasan['pendapatan_target'], 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </section>

            <!-- Tabel Belanja -->
            <section>
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-200 pb-2">Rincian Belanja</h3>
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Bidang
                                    Uraian</th>
                                <th scope="col"
                                    class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Anggaran
                                    (Rp)</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($rincianBelanja as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-5 py-3 text-sm text-gray-700">{{ $item['bidang'] }}</td>
                                    <td class="px-5 py-3 text-sm font-medium text-gray-900 text-right">
                                        {{ number_format($item['anggaran'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-red-50">
                            <tr>
                                <td class="px-5 py-4 text-sm font-bold text-gray-800">JUMLAH BELANJA</td>
                                <td class="px-5 py-4 text-sm font-bold text-red-700 text-right">
                                    {{ number_format($apbdesRingkasan['belanja_target'], 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </section>

            <!-- Breakdown Pembiayaan Desa -->
            <section class="lg:col-span-2">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-200 pb-2">Breakdown Pembiayaan Desa</h3>
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Uraian Pembiayaan</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Anggaran (Rp)</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @if(count($rincianPembiayaan['penerimaan']) > 0)
                                <tr class="bg-gray-50/80">
                                    <td colspan="2" class="px-6 py-2 text-xs font-bold text-gray-500">
                                        <i class="fa-solid fa-plus-circle text-green-500 mr-2"></i>Penerimaan Pembiayaan
                                    </td>
                                </tr>
                                @foreach($rincianPembiayaan['penerimaan'] as $item)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-3 text-sm pl-12 text-gray-700">{{ $item['uraian'] }}</td>
                                        <td class="px-6 py-3 text-sm font-medium text-gray-900 text-right">{{ number_format($item['anggaran'], 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            @endif
                            @if(count($rincianPembiayaan['pengeluaran']) > 0)
                                <tr class="bg-gray-50/80">
                                    <td colspan="2" class="px-6 py-2 text-xs font-bold text-gray-500 border-t border-gray-200">
                                        <i class="fa-solid fa-minus-circle text-red-500 mr-2"></i>Pengeluaran Pembiayaan
                                    </td>
                                </tr>
                                @foreach($rincianPembiayaan['pengeluaran'] as $item)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-3 text-sm pl-12 text-gray-700">{{ $item['uraian'] }}</td>
                                        <td class="px-6 py-3 text-sm font-medium text-gray-900 text-right">{{ number_format($item['anggaran'], 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            @endif
                            @if(count($rincianPembiayaan['penerimaan']) === 0 && count($rincianPembiayaan['pengeluaran']) === 0)
                                <tr>
                                    <td colspan="2" class="px-6 py-4 text-sm text-gray-500 text-center italic">Belum ada data pembiayaan untuk tahun ini.</td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot class="bg-purple-50 border-t-2 border-purple-200">
                            <tr>
                                <td class="px-6 py-4 text-sm font-bold text-gray-800">PEMBIAYAAN NETTO</td>
                                <td class="px-6 py-4 text-sm font-bold text-purple-700 text-right">{{ number_format($apbdesRingkasan['pembiayaan_netto'], 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </section>
        </div>

        <!-- SECTION 6: INFOGRAFIS APBDES (VISUAL UPLOAD) -->
        <section class="mb-14">
            <div class="flex items-center gap-3 mb-6 border-b border-gray-200 pb-3">
                <div class="bg-amber-100 p-2 rounded text-amber-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Visual Infografis APBDes</h2>
            </div>

            <!-- Poster Image -->
            <div class="relative max-w-4xl mx-auto rounded-3xl overflow-hidden shadow-2xl z-10
                            transform group-hover:-translate-y-2 transition-all duration-500 ease-out group-hover:shadow-green-900/30"
                @click="imageModalOpen = true">
                <img src="{{ $posterCurrent?->gambar_baliho_url ?? 'https://placehold.co/1200x800/E8F5E9/2E7D32?text=Baliho+Belum+Diunggah' }}"
                    alt="Visual Infografis APBDes TA {{ $tahunBerjalan }}" class="w-full object-cover cursor-pointer">
                <div
                    class="absolute inset-0 bg-gradient-to-t from-gray-900/80 via-transparent to-transparent
                                flex items-end p-6 sm:p-8 pointer-events-none">
                    <div class="text-left w-full flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
                        <div>
                            <h3 class="text-white font-bold text-xl sm:text-2xl">Banner Infografis APBDes
                                {{ $tahunBerjalan }}</h3>
                            <p class="text-gray-300 text-sm mt-1">Baliho Publikasi APBDes {{ $tahunBerjalan }} <br>
                                Terpasang di depan Kantor Kepala Desa Sindangmukti</p>
                        </div>
                        <button
                            class="bg-white/20 hover:bg-white/30 backdrop-blur-md text-white px-5 py-2.5 rounded-full text-sm font-bold transition-all shadow border border-white/30 flex items-center gap-2 pointer-events-auto cursor-pointer"
                            @click="imageModalOpen = true">
                            <i class="fa-solid fa-expand"></i> Klik untuk Perbesar
                        </button>
                    </div>
                </div>
            </div>

            <!-- MODAL INFOGRAFIS (Terkait Section 6) -->
            <div x-show="imageModalOpen" style="display: none"
                class="fixed inset-0 z-[100] bg-black/90 backdrop-blur-sm overflow-y-auto flex items-center justify-center p-4 md:p-10 transition-opacity"
                x-transition.opacity>
                <div class="relative max-w-5xl w-full" @click.away="imageModalOpen = false">
                    <button @click="imageModalOpen = false"
                        class="absolute -top-10 right-0 text-white hover:text-red-400 text-3xl font-bold transition-colors">
                        &times;
                    </button>
                    <img src="https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&q=80&w=1600"
                        alt="Infografis Full" class="w-full h-auto rounded-lg shadow-2xl border border-gray-700">
                    <p class="text-center text-gray-300 mt-4 text-sm font-medium">Infografis APBDes
                        {{ request('tahun', 2024) }} (Resolusi Penuh)</p>
                </div>
            </div>
        </section>

        <!-- SECTION 7 & 8: DOKUMEN RESMI & CTA LENGKAP -->
        <section class="mb-16">
            <div class="bg-gray-50 rounded-2xl p-6 md:p-8 border border-gray-200 shadow-sm">
                <div class="flex flex-col md:flex-row items-center justify-between mb-8 gap-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800 mb-2">Repository Dokumen Resmi</h2>
                        <p class="text-gray-600 text-sm">Unduh dokumen legalitas, peraturan desa, dan laporan keuangan
                            rincian.</p>
                    </div>
                    <a href="#arsip-lengkap"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg shadow-md transition-all flex items-center shrink-0">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg> Unduh Semua (.ZIP)
                    </a>
                </div>
                <!-- List File Download -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div
                        class="bg-white p-4 rounded-xl border border-gray-200 flex items-center justify-between hover:border-green-400 hover:shadow-md transition-all">
                        <div class="flex items-center gap-4">
                            <div class="bg-red-100 p-3 rounded-lg text-red-500">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 text-sm">Perdes APBDes TA {{ $tahunBerjalan }}</h4>
                                <p class="text-xs text-gray-500 mt-1">PDF Dokumen Resmi</p>
                            </div>
                        </div>
                        <a href="{{ $posterCurrent?->perdes_dokumen_url ?? '#' }}"
                            class="text-green-600 bg-green-50 hover:bg-green-600 hover:text-white p-2 md:px-4 md:py-2 rounded-lg text-sm font-semibold transition-colors border border-green-200 flex items-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg> <span class="hidden md:inline ml-1">Unduh</span>
                        </a>
                    </div>
                    <div
                        class="bg-white p-4 rounded-xl border border-gray-200 flex items-center justify-between hover:border-green-400 hover:shadow-md transition-all">
                        <div class="flex items-center gap-4">
                            <div class="bg-green-100 p-3 rounded-lg text-green-600">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 text-sm">RAB Rincian Belanja Pembangunan</h4>
                                <p class="text-xs text-gray-500 mt-1">XLSX Lampiran Detail</p>
                            </div>
                        </div>
                        <a href="{{ $posterCurrent?->rab_dokumen_url ?? '#' }}"
                            class="text-green-600 bg-green-50 hover:bg-green-600 hover:text-white p-2 md:px-4 md:py-2 rounded-lg text-sm font-semibold transition-colors border border-green-200 flex items-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg> <span class="hidden md:inline ml-1">Unduh</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- ========================================== -->
        <!-- SECTION 10: ARSIP & RIWAYAT TRANSPARANSI   -->
        <!-- ========================================== -->
        <section class="mb-16 pt-10 border-t border-gray-200" id="arsip-transparansi">
            <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg> Arsip & Riwayat Transparansi
                    </h2>
                    <p class="text-gray-500 mt-2 text-sm max-w-2xl">
                        Telusuri rekam jejak historis pengelolaan keuangan desa untuk melihat perkembangan dan
                        perbandingan alokasi dana dari tahun ke tahun.
                    </p>
                </div>
                <div class="shrink-0">
                    <!-- Fitur Pencarian Tahun -->
                    <div class="relative">
                        <input type="text" placeholder="Cari Tahun..."
                            class="w-48 pl-10 pr-4 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:ring-green-500 focus:border-green-500">
                        <svg class="w-4 h-4 absolute left-3.5 top-1/2 transform -translate-y-1/2 text-gray-400"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Grid Card Riwayat Tahun -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                @foreach ($arsipTransparansi as $arsip)
                    <article
                        class="{{ request('tahun') == $arsip['tahun'] ? 'bg-green-50 border-green-300 shadow-md ring-1 ring-green-300' : 'bg-white border-gray-200 shadow-sm hover:shadow-md hover:border-green-300' }} border rounded-2xl p-6 transition-all flex flex-col h-full relative group">
                        <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-4">
                            <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-yellow-500 transition-colors"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z">
                                    </path>
                                </svg> Tahun {{ $arsip['tahun'] }}
                            </h3>
                            <span
                                class="bg-green-100 text-green-700 text-[11px] font-bold px-2.5 py-1 rounded-full border border-green-200 tracking-wider uppercase">{{ $arsip['status'] }}</span>
                        </div>

                        <div class="space-y-3 mb-6 flex-grow">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Pendapatan</span>
                                <span class="text-sm font-bold text-gray-800">Rp
                                    {{ number_format($arsip['pendapatan'] / 1000000, 0, ',', '.') }} Jt</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Belanja</span>
                                <span class="text-sm font-bold text-gray-800">Rp
                                    {{ number_format($arsip['belanja'] / 1000000, 0, ',', '.') }} Jt</span>
                            </div>
                            <div
                                class="flex justify-between items-center bg-gray-50 p-2.5 rounded-lg mt-3 border border-gray-100">
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">SiLPA</span>
                                <span class="text-sm font-black text-green-600">Rp
                                    {{ number_format($arsip['silpa'] / 1000000, 0, ',', '.') }} Jt</span>
                            </div>
                        </div>

                        <a href="?tahun={{ $arsip['tahun'] }}"
                            class="w-full bg-gray-50 hover:bg-green-600 hover:text-white text-green-700 font-semibold py-2.5 px-4 rounded-xl text-center transition-colors border border-gray-200 hover:border-green-600 flex items-center justify-center gap-2">
                            Lihat Detail <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </a>
                    </article>
                @endforeach

            </div>

            <!-- Load More / Pagination untuk Riwayat -->
            <div class="mt-8 flex justify-center">
                <button
                    class="bg-white border-2 border-green-600 text-green-600 hover:bg-green-600 hover:text-white font-bold py-2.5 px-6 rounded-full shadow-sm transition-all flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                        </path>
                    </svg> Muat Tahun Sebelumnya
                </button>
            </div>
        </section>
        <!-- ========================================== -->

        <!-- SECTION 11: INFORMASI TAMBAHAN (EDUKASI TRANSPARANSI) -->
        <section class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center"><svg class="w-6 h-6 text-green-600 mr-2"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                    </path>
                </svg>Edukasi Transparansi</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-blue-50/50 p-5 rounded-xl border border-blue-100">
                    <h3 class="font-bold text-blue-800 mb-2">Apa itu APBDes?</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Anggaran Pendapatan dan Belanja Desa (APBDes) adalah rencana keuangan tahunan Pemerintahan Desa
                        yang dibahas dan disetujui bersama oleh Pemerintah Desa dan Badan Permusyawaratan Desa (BPD).
                    </p>
                </div>
                <div class="bg-green-50/50 p-5 rounded-xl border border-green-100">
                    <h3 class="font-bold text-green-800 mb-2">Tujuan Transparansi</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Memberikan ruang bagi masyarakat untuk mengetahui, mengawasi, dan berpartisipasi dalam setiap
                        tahap pembangunan desa agar bebas dari praktik korupsi dan tepat sasaran.
                    </p>
                </div>
                <div class="bg-amber-50/50 p-5 rounded-xl border border-amber-100">
                    <h3 class="font-bold text-amber-800 mb-2">Dasar Hukum</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Berdasarkan UU No. 14 Tahun 2014 tentang Desa dan Permendagri No. 20 Tahun 2018 tentang
                        Pengelolaan Keuangan Desa yang mewajibkan publikasi informasi ke warga.
                    </p>
                </div>
            </div>
        </section>

        </div>

        <!-- 12. CTA BAWAH: AJAKAN EDUKASI & PARTISIPASI -->
        <section class="bg-green-900 border-t border-green-800 py-16 text-white mt-10">
            <div class="max-w-4xl mx-auto px-4 text-center">
                <div
                    class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-800 text-green-300 mb-6 border border-green-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                        </path>
                    </svg>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold mb-4">Temukan Kejanggalan atau Punya Saran?</h2>
                <p class="text-green-100/80 mb-8 leading-relaxed max-w-2xl mx-auto text-sm md:text-base">
                    Pembangunan desa adalah milik bersama. Jika Anda memiliki pertanyaan seputar alokasi dana atau ingin
                    melaporkan potensi penyalahgunaan, gunakan saluran komunikasi resmi kami secara aman dan rahasia.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('layanan.pengaduan') }}"
                        class="bg-white text-green-900 font-bold py-3 px-8 rounded-lg shadow-md hover:bg-gray-100 transition-colors flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z">
                            </path>
                        </svg> Lapor via Web Desa
                    </a>
                    <a href="#"
                        class="bg-green-800 hover:bg-green-700 text-white border border-green-600 font-medium py-3 px-8 rounded-lg shadow-sm transition-colors flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2 text-green-400" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M12.0006 2.05203C6.46337 2.05203 1.95465 6.55018 1.95465 12.0838C1.95465 13.8427 2.40938 15.5451 3.25997 17.0673L2.09115 21.4338L6.61109 20.2526C8.0645 21.0503 9.7118 21.4883 11.3934 21.4883C11.3963 21.4883 11.3992 21.4883 12.0006 21.4883C17.535 21.4883 22.0466 16.99 22.0466 11.4565C22.0437 8.78441 21.0001 6.27364 19.1054 4.38289C17.2098 2.49021 14.6932 1.44855 12.0006 1.44855V2.05203ZM12.0006 19.8055C10.4907 19.8055 9.02055 19.4005 7.74737 18.6438L7.44754 18.4655L4.76727 19.1678L5.49129 16.536L5.2971 16.2238C4.47141 14.9224 4.03265 13.3857 4.03265 11.8211C4.03265 7.42418 7.61869 3.84196 12.0209 3.84196C14.1565 3.84486 16.1472 4.6766 17.6534 6.18522C19.1576 7.69383 19.9855 9.68832 19.9855 11.8288C19.9855 16.2248 16.4024 19.8055 12.0006 19.8055Z"
                                clip-rule="evenodd"></path>
                        </svg> Hubungi Admin
                    </a>
                </div>
            </div>
        </section>
    </main>
@endsection

@push('scripts')
    <script>
        // --- Chart.js Initialization ---
        document.addEventListener("DOMContentLoaded", function() {

            // Data PHP ke Variable JS
            const ringkasan = @json($apbdesRingkasan);
            const rincianBelanja = @json($rincianBelanja);

            // 1. Bar Chart: Perbandingan Anggaran vs Realisasi
            const ctxBar = document.getElementById('anggaranRealisasiChart').getContext('2d');
            new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: ['Pendapatan', 'Belanja', 'Pembiayaan'],
                    datasets: [{
                            label: 'Anggaran (Target)',
                            data: [
                                ringkasan.pendapatan_target / 1000000,
                                ringkasan.belanja_target / 1000000,
                                ringkasan.silpa / 1000000 // Simulasi pembiayaan
                            ],
                            backgroundColor: '#94a3b8',
                            borderRadius: 4
                        },
                        {
                            label: 'Realisasi Aktual',
                            data: [
                                ringkasan.pendapatan_realisasi / 1000000,
                                ringkasan.belanja_realisasi / 1000000,
                                50 // Simulasi realisasi silpa/pembiayaan
                            ],
                            backgroundColor: '#10b981',
                            borderRadius: 4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': Rp ' + context.raw.toFixed(1) +
                                        ' Juta';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f1f5f9'
                            },
                            ticks: {
                                callback: function(value) {
                                    return value + ' Jt';
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // 2. Doughnut Chart: Proporsi Belanja
            const bidangBelanjaLabels = rincianBelanja.map(item => item.bidang);
            const bidangBelanjaData = rincianBelanja.map(item => item.anggaran / 1000000);

            const ctxDoughnut = document.getElementById('belanjaDoughnutChart').getContext('2d');
            new Chart(ctxDoughnut, {
                type: 'doughnut',
                data: {
                    labels: bidangBelanjaLabels,
                    datasets: [{
                        data: bidangBelanjaData,
                        backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ef4444',
                            '#14b8a6'
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff',
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            position: 'right'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return ' Rp ' + (context.raw || 0).toFixed(1) + ' Juta';
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
