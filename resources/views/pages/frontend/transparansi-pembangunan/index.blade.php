@extends('layouts.frontend')

@section('title', $pageTitle . ' - Desa Sindangmukti')

@push('styles')
    <style>
        [x-cloak] { display: none !important; }
    </style>
@endpush

@section('content')
    <main class="flex-grow bg-gray-50 pt-16">

        <!-- SECTION 1: HEADER TRANSPARANSI PEMBANGUNAN -->
        <section class="bg-gradient-to-br from-green-800 to-green-600 text-white py-16 md:py-20 relative overflow-hidden">
            <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
                <span class="bg-green-700/50 text-green-100 text-sm font-semibold px-3 py-1 rounded-full border border-green-500/30 mb-4 inline-block">
                    <i class="fa-solid fa-helmet-safety mr-1"></i> Data Terbuka Publik
                </span>
                <h1 class="text-4xl md:text-5xl font-bold mb-4 tracking-tight">{{ $pageTitle }}</h1>
                <p class="text-lg text-green-100 max-w-2xl mx-auto leading-relaxed">{{ $pageSubtitle }}</p>
            </div>
        </section>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            <!-- SECTION 2: RINGKASAN STATISTIK PEMBANGUNAN -->
            <section class="mb-12">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    {{-- Total Proyek --}}
                    <article class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 hover:-translate-y-1 hover:shadow-lg transition-all">
                        <div class="flex justify-between items-start mb-2">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Proyek</p>
                            <i class="fa-solid fa-diagram-project text-blue-500 bg-blue-50 p-2 rounded-full"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-1">{{ $totalProyek }}</h3>
                        @if($tahun)
                            <p class="text-xs text-blue-600 font-medium">Tahun Anggaran {{ $tahun }}</p>
                        @else
                            <p class="text-xs text-gray-500">Seluruh periode</p>
                        @endif
                    </article>

                    {{-- Total Anggaran --}}
                    <article class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500 hover:-translate-y-1 hover:shadow-lg transition-all">
                        <div class="flex justify-between items-start mb-2">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Anggaran</p>
                            <i class="fa-solid fa-coins text-green-500 bg-green-50 p-2 rounded-full"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-1">Rp {{ number_format($totalAnggaran / 1000000, 1, ',', '.') }} Jt</h3>
                        <p class="text-xs text-green-600 font-medium">Pagu anggaran teralokasi</p>
                    </article>

                    {{-- Sedang Berjalan --}}
                    <article class="bg-white rounded-xl shadow-md p-6 border-l-4 border-amber-500 hover:-translate-y-1 hover:shadow-lg transition-all">
                        <div class="flex justify-between items-start mb-2">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Sedang Berjalan</p>
                            <i class="fa-solid fa-person-digging text-amber-500 bg-amber-50 p-2 rounded-full"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-1">{{ $proyekBerjalan }}</h3>
                        <p class="text-xs text-amber-600 font-medium">Proyek aktif di lapangan</p>
                    </article>

                    {{-- Telah Selesai --}}
                    <article class="bg-white rounded-xl shadow-md p-6 border-l-4 border-indigo-500 hover:-translate-y-1 hover:shadow-lg transition-all">
                        <div class="flex justify-between items-start mb-2">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Telah Selesai</p>
                            <i class="fa-solid fa-circle-check text-indigo-500 bg-indigo-50 p-2 rounded-full"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-1">{{ $proyekSelesai }}</h3>
                        <p class="text-xs text-indigo-600 font-medium">Proyek terselesaikan</p>
                    </article>
                </div>
            </section>

            <!-- SECTION 3: FILTER & PENCARIAN -->
            <section class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-gray-200 pb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Daftar Proyek Pembangunan</h2>
                    <p class="text-sm text-gray-500">Klik kartu proyek untuk melihat detail lengkap, dokumentasi, dan timeline.</p>
                </div>
            </section>

            <form action="{{ route('transparansi.pembangunan') }}" method="GET" id="filterForm">
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm mb-8 flex flex-col md:flex-row gap-3 items-center justify-between">
                    <div class="flex flex-wrap gap-3 w-full md:w-auto">
                        <select name="tahun" onchange="document.getElementById('filterForm').submit()"
                            class="bg-gray-50 border border-gray-200 text-sm rounded-lg px-3 py-2.5 outline-none focus:border-green-500 focus:ring-2 focus:ring-green-500/20 w-full md:w-auto cursor-pointer">
                            <option value="">Semua Tahun</option>
                            @foreach($availableYears as $yr)
                                <option value="{{ $yr }}" {{ $tahun == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                            @endforeach
                        </select>
                        <select name="kategori" onchange="document.getElementById('filterForm').submit()"
                            class="bg-gray-50 border border-gray-200 text-sm rounded-lg px-3 py-2.5 outline-none focus:border-green-500 focus:ring-2 focus:ring-green-500/20 w-full md:w-auto cursor-pointer">
                            <option value="">Semua Kategori</option>
                            @foreach($availableKategori as $kat)
                                <option value="{{ $kat }}" {{ $kategori == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                            @endforeach
                        </select>
                        <select name="status" onchange="document.getElementById('filterForm').submit()"
                            class="bg-gray-50 border border-gray-200 text-sm rounded-lg px-3 py-2.5 outline-none focus:border-green-500 focus:ring-2 focus:ring-green-500/20 w-full md:w-auto cursor-pointer">
                            <option value="">Semua Status</option>
                            <option value="perencanaan" {{ $status == 'perencanaan' ? 'selected' : '' }}>Perencanaan</option>
                            <option value="berjalan" {{ $status == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                            <option value="selesai" {{ $status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="terlambat" {{ $status == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                        </select>
                    </div>
                    <div class="w-full md:w-auto">
                        <div class="relative">
                            <svg class="w-4 h-4 absolute left-3 top-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama proyek..."
                                class="bg-gray-50 border border-gray-200 text-sm rounded-lg pl-9 pr-3 py-2.5 outline-none focus:border-green-500 focus:ring-2 focus:ring-green-500/20 w-full">
                        </div>
                    </div>
                </div>
            </form>

            <!-- SECTION 4: CARD GRID PROYEK -->
            @if($proyeks->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
                    @foreach($proyeks as $p)
                        <a href="{{ route('transparansi.pembangunan.detail', $p->id) }}"
                           class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all cursor-pointer group flex flex-col">
                            
                            {{-- Card Header --}}
                            <div class="p-5 border-b border-gray-100 flex-grow">
                                <div class="flex justify-between items-start mb-3">
                                    @if($p->status == 'berjalan')
                                        <span class="px-2.5 py-1 bg-amber-100 text-amber-800 text-xs font-bold rounded-full">Berjalan</span>
                                    @elseif($p->status == 'selesai')
                                        <span class="px-2.5 py-1 bg-indigo-100 text-indigo-800 text-xs font-bold rounded-full">Selesai</span>
                                    @elseif($p->status == 'terlambat')
                                        <span class="px-2.5 py-1 bg-red-100 text-red-800 text-xs font-bold rounded-full">Terlambat</span>
                                    @else
                                        <span class="px-2.5 py-1 bg-gray-200 text-gray-700 text-xs font-bold rounded-full">Perencanaan</span>
                                    @endif
                                    <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded-md">{{ $p->kategori }}</span>
                                </div>
                                <h3 class="text-lg font-bold text-gray-800 group-hover:text-green-600 transition-colors leading-snug line-clamp-2">{{ $p->nama_proyek }}</h3>
                                @if($p->lokasi_dusun)
                                    <p class="text-sm text-gray-500 mt-2 flex items-center gap-1.5">
                                        <i class="fa-solid fa-map-pin text-xs"></i> {{ $p->lokasi_dusun }}{{ $p->rt_rw ? ', ' . $p->rt_rw : '' }}
                                    </p>
                                @endif
                            </div>

                            {{-- Card Footer: Budget & Progress --}}
                            <div class="p-5 bg-gray-50 flex-grow flex flex-col justify-end rounded-b-xl">
                                <div class="flex justify-between items-end mb-2">
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Anggaran</p>
                                        <p class="text-sm font-bold text-gray-800">
                                            @if($p->apbdes_id && $p->apbdes)
                                                Rp {{ number_format($p->apbdes->pagu_anggaran, 0, ',', '.') }}
                                            @else
                                                <span class="text-gray-400">Belum terhubung</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-500 mb-1">Progress</p>
                                        @php
                                            $pColor = match($p->status) {
                                                'selesai' => 'text-indigo-600',
                                                'terlambat' => 'text-red-600',
                                                default => 'text-green-600',
                                            };
                                        @endphp
                                        <p class="text-sm font-bold {{ $pColor }}">{{ $p->progres_fisik }}%</p>
                                    </div>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2 mt-1 overflow-hidden">
                                    @php
                                        $barColor = match($p->status) {
                                            'selesai' => 'bg-indigo-500',
                                            'terlambat' => 'bg-red-500',
                                            'berjalan' => 'bg-green-500',
                                            default => 'bg-gray-300',
                                        };
                                    @endphp
                                    <div class="{{ $barColor }} h-2 rounded-full transition-all" style="width: {{ $p->progres_fisik }}%"></div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($proyeks->hasPages())
                    <div class="flex justify-center mb-10">
                        {{ $proyeks->links() }}
                    </div>
                @endif
            @else
                {{-- Empty State --}}
                <div class="bg-white rounded-2xl border border-gray-200 p-16 text-center mb-10 shadow-sm">
                    <div class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4 text-gray-300 text-4xl">
                        <i class="fa-solid fa-folder-open"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-700 mb-2">Belum Ada Data Proyek</h3>
                    <p class="text-sm text-gray-500 max-w-md mx-auto">Tidak ditemukan data proyek pembangunan untuk filter yang dipilih. Coba ubah kriteria pencarian Anda.</p>
                </div>
            @endif

            <!-- SECTION 5: EDUKASI TRANSPARANSI -->
            <section class="mb-8">
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg> Tentang Transparansi Pembangunan
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-blue-50/50 p-5 rounded-xl border border-blue-100">
                        <h3 class="font-bold text-blue-800 mb-2">Apa itu Transparansi Proyek?</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">Penyajian data proyek pembangunan fisik desa secara terbuka, mulai dari perencanaan, pelaksanaan, hingga selesai. Masyarakat dapat memantau progres secara real-time.</p>
                    </div>
                    <div class="bg-green-50/50 p-5 rounded-xl border border-green-100">
                        <h3 class="font-bold text-green-800 mb-2">Bagaimana Alur Proyek?</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">Setiap proyek melalui tahap Perencanaan → Berjalan → Selesai. Progres fisik diperbarui berkala oleh petugas dan tersedia untuk dilihat oleh seluruh warga.</p>
                    </div>
                    <div class="bg-amber-50/50 p-5 rounded-xl border border-amber-100">
                        <h3 class="font-bold text-amber-800 mb-2">Dasar Hukum</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">Berdasarkan UU No. 14 Tahun 2014 tentang Desa dan Permendagri No. 20 Tahun 2018 yang mewajibkan keterbukaan informasi pengelolaan anggaran dan pembangunan desa.</p>
                    </div>
                </div>
            </section>

        </div>

        <!-- CTA BAWAH: AJAKAN PARTISIPASI -->
        <section class="bg-green-900 border-t border-green-800 py-16 text-white mt-10">
            <div class="max-w-4xl mx-auto px-4 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-800 text-green-300 mb-6 border border-green-600">
                    <i class="fa-solid fa-helmet-safety text-2xl"></i>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold mb-4">Temukan Kejanggalan atau Punya Saran?</h2>
                <p class="text-green-100/80 mb-8 leading-relaxed max-w-2xl mx-auto text-sm md:text-base">
                    Pembangunan desa adalah milik bersama. Jika Anda memiliki pertanyaan seputar proyek atau ingin melaporkan potensi penyalahgunaan, gunakan saluran komunikasi resmi kami.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('layanan.pengaduan') }}" class="bg-white text-green-900 font-bold py-3 px-8 rounded-lg shadow-md hover:bg-gray-100 transition-colors flex items-center justify-center">
                        <i class="fa-solid fa-bullhorn mr-2"></i> Lapor via Web Desa
                    </a>
                    <a href="{{ route('transparansi') }}" class="bg-green-800 hover:bg-green-700 text-white border border-green-600 font-medium py-3 px-8 rounded-lg shadow-sm transition-colors flex items-center justify-center">
                        <i class="fa-solid fa-coins text-green-400 mr-2"></i> Lihat Transparansi APBDes
                    </a>
                </div>
            </div>
        </section>
    </main>
@endsection
