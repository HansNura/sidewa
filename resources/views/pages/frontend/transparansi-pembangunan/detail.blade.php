@extends('layouts.frontend')

@section('title', $pageTitle . ' - Desa Sindangmukti')

@push('styles')
    <style>
        [x-cloak] { display: none !important; }
    </style>
@endpush

@section('content')
    <main class="flex-grow bg-gray-50 pt-16">

        <!-- SECTION 1: HEADER DETAIL PROYEK -->
        <section class="bg-gradient-to-br from-green-800 to-green-600 text-white py-12 md:py-16 relative overflow-hidden">
            <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <!-- Breadcrumb -->
                <a href="{{ route('transparansi.pembangunan') }}" class="inline-flex items-center gap-2 text-green-200 hover:text-white mb-6 transition-colors font-medium text-sm group">
                    <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg> Kembali ke Daftar Proyek
                </a>
                <div class="flex flex-wrap gap-2 mb-3">
                    @if($proyek->status == 'berjalan')
                        <span class="px-3 py-1 bg-amber-400/20 text-amber-200 text-xs font-bold rounded-full border border-amber-400/30">Status: Berjalan</span>
                    @elseif($proyek->status == 'selesai')
                        <span class="px-3 py-1 bg-green-400/20 text-green-200 text-xs font-bold rounded-full border border-green-400/30">Status: Selesai</span>
                    @elseif($proyek->status == 'terlambat')
                        <span class="px-3 py-1 bg-red-400/20 text-red-200 text-xs font-bold rounded-full border border-red-400/30">Status: Terlambat</span>
                    @else
                        <span class="px-3 py-1 bg-white/10 text-green-200 text-xs font-bold rounded-full border border-white/20">Status: Perencanaan</span>
                    @endif
                    <span class="px-3 py-1 bg-white/10 text-green-200 text-xs font-medium rounded-full border border-white/20">{{ $proyek->kategori }}</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold mb-2 tracking-tight leading-tight">{{ $proyek->nama_proyek }}</h1>
                @if($proyek->lokasi_dusun)
                    <p class="text-green-100 flex items-center gap-2 text-sm">
                        <i class="fa-solid fa-map-pin"></i> {{ $proyek->lokasi_dusun }}{{ $proyek->rt_rw ? ', ' . $proyek->rt_rw : '' }}, Desa Sindangmukti
                    </p>
                @endif
            </div>
        </section>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            <!-- SECTION 2: PROGRESS FISIK HIGHLIGHT -->
            <section class="mb-10 -mt-16 relative z-20">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 md:p-8">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                        <div class="flex items-center gap-6 flex-grow">
                            {{-- Circular Progress Visualization --}}
                            @php
                                $progressColor = match($proyek->status) {
                                    'selesai' => 'text-green-600',
                                    'terlambat' => 'text-red-600',
                                    default => 'text-green-600',
                                };
                                $barColor = match($proyek->status) {
                                    'selesai' => 'bg-green-500',
                                    'terlambat' => 'bg-red-500',
                                    default => 'bg-green-500',
                                };
                                $barBg = match($proyek->status) {
                                    'terlambat' => 'bg-red-100',
                                    default => 'bg-gray-200',
                                };
                            @endphp
                            <div class="text-center shrink-0">
                                <p class="text-5xl md:text-6xl font-bold {{ $progressColor }}">{{ $proyek->progres_fisik }}%</p>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mt-1">Progres Fisik</p>
                            </div>
                            <div class="flex-grow w-full">
                                <div class="flex justify-between mb-2">
                                    <span class="text-sm font-semibold text-gray-700">Realisasi Fisik Lapangan</span>
                                    @if($proyek->status == 'terlambat')
                                        <span class="text-xs font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded border border-red-100 flex items-center gap-1"><i class="fa-solid fa-triangle-exclamation text-[10px]"></i> Melewati Target</span>
                                    @elseif($proyek->status == 'selesai')
                                        <span class="text-xs font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded border border-green-100 flex items-center gap-1"><i class="fa-solid fa-circle-check text-[10px]"></i> Selesai</span>
                                    @endif
                                </div>
                                <div class="w-full {{ $barBg }} rounded-full h-4 overflow-hidden">
                                    <div class="{{ $barColor }} h-4 rounded-full transition-all" style="width: {{ $proyek->progres_fisik }}%"></div>
                                </div>
                                <div class="flex justify-between mt-2">
                                    <span class="text-xs text-gray-500">0%</span>
                                    <span class="text-xs text-gray-500">100%</span>
                                </div>
                            </div>
                        </div>
                        <div class="shrink-0 grid grid-cols-2 gap-4 w-full md:w-auto">
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 text-center">
                                <p class="text-xs text-gray-500 font-medium mb-1">Mulai</p>
                                <p class="text-sm font-bold text-gray-800">{{ $proyek->tanggal_mulai ? $proyek->tanggal_mulai->format('d M Y') : '-' }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 text-center">
                                <p class="text-xs text-gray-500 font-medium mb-1">Target</p>
                                <p class="text-sm font-bold text-gray-800">{{ $proyek->target_selesai ? $proyek->target_selesai->format('d M Y') : '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- GRID LAYOUT DETAIL -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- KOLOM KIRI (Info Utama & Dokumentasi) -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- INFORMASI UMUM PROYEK -->
                    <section class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center gap-3 mb-5 border-b border-gray-200 pb-4">
                            <div class="bg-green-100 p-2 rounded text-green-700">
                                <i class="fa-solid fa-info-circle"></i>
                            </div>
                            <h2 class="text-lg font-bold text-gray-800">Informasi Umum Proyek</h2>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-x-4 gap-y-5">
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Tanggal Mulai</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $proyek->tanggal_mulai ? $proyek->tanggal_mulai->format('d M Y') : '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Target Selesai</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $proyek->target_selesai ? $proyek->target_selesai->format('d M Y') : '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Kategori</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $proyek->kategori }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Lokasi</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $proyek->lokasi_dusun ?? '-' }}</p>
                            </div>
                        </div>
                        @if($proyek->deskripsi)
                            <div class="mt-5 pt-5 border-t border-gray-100">
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Deskripsi Proyek</p>
                                <p class="text-sm text-gray-600 leading-relaxed">{{ $proyek->deskripsi }}</p>
                            </div>
                        @endif
                    </section>

                    <!-- DOKUMENTASI LAPANGAN -->
                    <section class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-5 border-b border-gray-200 pb-4">
                            <div class="flex items-center gap-3">
                                <div class="bg-amber-100 p-2 rounded text-amber-600">
                                    <i class="fa-solid fa-camera"></i>
                                </div>
                                <h2 class="text-lg font-bold text-gray-800">Dokumentasi Lapangan</h2>
                            </div>
                            <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded">{{ $proyek->fotos->count() }} Foto</span>
                        </div>
                        @if($proyek->fotos->count() > 0)
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($proyek->fotos as $foto)
                                    <div class="group relative aspect-video bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                                        <img src="{{ $foto->foto_url }}" alt="{{ $foto->keterangan }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                                        <div class="absolute bottom-0 w-full bg-gradient-to-t from-black/70 to-transparent p-2 text-white text-xs">
                                            Tahap {{ $foto->progres_saat_foto }}%
                                            @if($foto->keterangan) — {{ $foto->keterangan }} @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-gray-50 rounded-xl p-8 text-center border border-dashed border-gray-200">
                                <i class="fa-solid fa-camera text-3xl text-gray-300 mb-3"></i>
                                <p class="text-sm text-gray-500 font-medium">Belum ada dokumentasi foto untuk proyek ini.</p>
                            </div>
                        @endif
                    </section>
                </div>

                <!-- KOLOM KANAN (Anggaran & Histori) -->
                <div class="space-y-8">
                    
                    <!-- LINKED ANGGARAN (Integrasi APBDes) -->
                    <section class="bg-white rounded-xl shadow-sm border border-green-200/50 overflow-hidden relative">
                        <div class="absolute top-0 right-0 p-3 opacity-[0.05] text-green-500">
                            <i class="fa-solid fa-wallet text-[5rem]"></i>
                        </div>
                        <div class="p-6 relative z-10">
                            <div class="flex items-center gap-3 mb-5 border-b border-gray-200 pb-4">
                                <div class="bg-green-100 p-2 rounded text-green-700">
                                    <i class="fa-solid fa-database"></i>
                                </div>
                                <h2 class="text-lg font-bold text-gray-800">Data Anggaran</h2>
                            </div>
                            
                            @if($proyek->apbdes_id && $proyek->apbdes)
                                <div class="space-y-4">
                                    <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                                        <span class="text-sm text-gray-500">Sumber Dana</span>
                                        <span class="text-xs font-bold text-gray-800 bg-gray-100 px-2 py-1 rounded">{{ $proyek->apbdes->tipe_anggaran ?? 'APBDes' }} {{ $proyek->apbdes->tahun ?? '' }}</span>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Pagu Anggaran</p>
                                        <p class="text-xl font-bold text-gray-800">Rp {{ number_format($proyek->apbdes->pagu_anggaran, 0, ',', '.') }}</p>
                                    </div>
                                    <div>
                                        <div class="flex justify-between mb-1">
                                            <p class="text-xs text-gray-500">Serapan Estimasi</p>
                                            <p class="text-sm font-bold text-green-600">{{ $proyek->progres_fisik }}%</p>
                                        </div>
                                        <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                                            <div class="bg-green-500 h-2 rounded-full" style="width: {{ $proyek->progres_fisik }}%"></div>
                                        </div>
                                        <p class="text-xs text-gray-400 mt-1 text-right">Serapan Keuangan: {{ $proyek->progres_fisik }}%</p>
                                    </div>
                                    @php
                                        $serapan = ($proyek->apbdes->pagu_anggaran * $proyek->progres_fisik) / 100;
                                        $sisa = $proyek->apbdes->pagu_anggaran - $serapan;
                                    @endphp
                                    <div class="bg-green-50 rounded-lg p-3 border border-green-100 flex justify-between items-center mt-2">
                                        <span class="text-sm font-medium text-green-800">Sisa Anggaran</span>
                                        <span class="text-lg font-bold text-green-700">Rp {{ number_format($sisa, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            @else
                                <div class="bg-gray-50 rounded-xl p-6 text-center border border-dashed border-gray-200">
                                    <i class="fa-solid fa-link-slash text-2xl text-gray-300 mb-2"></i>
                                    <p class="text-sm text-gray-500 font-medium">Belum terhubung ke APBDes</p>
                                    <p class="text-xs text-gray-400 mt-1">Data anggaran akan muncul setelah operator menautkan ke item APBDes.</p>
                                </div>
                            @endif
                        </div>
                    </section>

                    <!-- TIMELINE KEGIATAN (HISTORI) -->
                    <section class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center gap-3 mb-5 border-b border-gray-200 pb-4">
                            <div class="bg-blue-100 p-2 rounded text-blue-700">
                                <i class="fa-solid fa-clock-rotate-left"></i>
                            </div>
                            <h2 class="text-lg font-bold text-gray-800">Timeline Kegiatan</h2>
                        </div>
                        
                        @if($proyek->historis->count() > 0)
                            <div class="relative border-l-2 border-gray-200 ml-3 space-y-6">
                                @foreach($proyek->historis as $index => $h)
                                    <div class="relative pl-6">
                                        <div class="absolute -left-2 top-0 w-4 h-4 rounded-full {{ $index == 0 ? 'bg-green-500' : 'bg-gray-300' }} ring-4 ring-white"></div>
                                        <p class="text-xs {{ $index == 0 ? 'text-green-600' : 'text-gray-500' }} font-bold mb-1">
                                            {{ $h->tanggal ? $h->tanggal->format('d M Y') : '-' }}
                                        </p>
                                        <h4 class="text-sm font-bold text-gray-800">{{ $h->judul_update }}</h4>
                                        @if($h->deskripsi)
                                            <p class="text-sm text-gray-500 mt-1 leading-relaxed">{{ $h->deskripsi }}</p>
                                        @endif
                                        <div class="flex items-center gap-3 mt-2">
                                            <p class="text-xs text-gray-400 flex items-center gap-1">
                                                <i class="fa-solid fa-user text-[8px]"></i> {{ $h->oleh_siapa }}
                                            </p>
                                            @if($h->progres_dicapai)
                                                <span class="text-xs font-bold bg-green-50 text-green-700 px-2 py-0.5 rounded border border-green-100">{{ $h->progres_dicapai }}%</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-gray-50 rounded-xl p-6 text-center border border-dashed border-gray-200">
                                <i class="fa-solid fa-clock text-2xl text-gray-300 mb-2"></i>
                                <p class="text-sm text-gray-500 font-medium">Belum ada catatan kegiatan.</p>
                            </div>
                        @endif
                    </section>
                </div>
            </div>

        </div>

        <!-- CTA BAWAH -->
        <section class="bg-green-900 border-t border-green-800 py-16 text-white mt-10">
            <div class="max-w-4xl mx-auto px-4 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-800 text-green-300 mb-6 border border-green-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold mb-4">Ada Pertanyaan Tentang Proyek Ini?</h2>
                <p class="text-green-100/80 mb-8 leading-relaxed max-w-2xl mx-auto text-sm md:text-base">
                    Jika Anda menemukan ketidaksesuaian data atau ingin memberikan masukan terkait proyek pembangunan desa, gunakan saluran komunikasi resmi kami.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('layanan.pengaduan') }}" class="bg-white text-green-900 font-bold py-3 px-8 rounded-lg shadow-md hover:bg-gray-100 transition-colors flex items-center justify-center">
                        <i class="fa-solid fa-bullhorn mr-2"></i> Lapor via Web Desa
                    </a>
                    <a href="{{ route('transparansi.pembangunan') }}" class="bg-green-800 hover:bg-green-700 text-white border border-green-600 font-medium py-3 px-8 rounded-lg shadow-sm transition-colors flex items-center justify-center">
                        <i class="fa-solid fa-arrow-left text-green-400 mr-2"></i> Kembali ke Daftar Proyek
                    </a>
                </div>
            </div>
        </section>
    </main>
@endsection
