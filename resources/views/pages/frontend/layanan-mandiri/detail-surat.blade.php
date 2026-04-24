{{--
    Halaman: Detail Permohonan Surat (Timeline & Dokumen)
    Route: warga.surat.detail
    Guard: auth:warga
    Sumber Desain: Detail-Permohonan.html (Google Stitch)
--}}

@extends('layouts.warga')

@section('title', 'Detail Permohonan ' . $surat->nomor_tiket . ' - Layanan Mandiri Desa Sindangmukti')
@section('meta_description', 'Detail dan status permohonan surat ' . $surat->jenisLabel())

@php
    $pageTitle = 'Detail Permohonan';
    $badge = $surat->statusBadge();
    $slaInfo = $surat->slaInfo();
    $warga = Auth::guard('warga')->user();

    // Build timeline steps
    $timelineSteps = [
        [
            'title' => 'Diajukan',
            'desc' => 'Permohonan diterima oleh sistem',
            'icon' => 'fa-paper-plane',
            'date' => $surat->tanggal_pengajuan?->format('d M Y, H:i'),
            'active' => in_array($surat->status, ['pengajuan', 'verifikasi', 'menunggu_tte', 'selesai', 'ditolak']),
            'current' => $surat->status === 'pengajuan',
        ],
        [
            'title' => 'Verifikasi Berkas',
            'desc' => 'Berkas sedang diperiksa petugas desa',
            'icon' => 'fa-list-check',
            'date' => $surat->status !== 'pengajuan' ? $surat->updated_at->format('d M Y, H:i') : null,
            'active' => in_array($surat->status, ['verifikasi', 'menunggu_tte', 'selesai']),
            'current' => $surat->status === 'verifikasi',
        ],
        [
            'title' => 'Tanda Tangan Kades',
            'desc' => 'Menunggu tanda tangan elektronik (TTE) Kepala Desa',
            'icon' => 'fa-signature',
            'date' => null,
            'active' => in_array($surat->status, ['menunggu_tte', 'selesai']),
            'current' => $surat->status === 'menunggu_tte',
        ],
        [
            'title' => $surat->status === 'ditolak' ? 'Ditolak' : 'Selesai',
            'desc' => $surat->status === 'ditolak'
                ? 'Permohonan ditolak.' . ($surat->alasan_tolak ? ' Alasan: ' . $surat->alasan_tolak : '')
                : 'Dokumen surat siap diunduh/dicetak.',
            'icon' => $surat->status === 'ditolak' ? 'fa-xmark' : 'fa-check-double',
            'date' => $surat->tanggal_selesai?->format('d M Y, H:i'),
            'active' => in_array($surat->status, ['selesai', 'ditolak']),
            'current' => in_array($surat->status, ['selesai', 'ditolak']),
        ],
    ];
@endphp

@section('content')

    {{-- Back Navigation --}}
    <div class="mb-6">
        <a href="{{ route('warga.surat.riwayat') }}"
           class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-gray-800 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Riwayat
        </a>
    </div>

    {{-- Header --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl {{ $badge['bg'] }} flex items-center justify-center text-2xl {{ $badge['text'] }} border {{ $badge['border'] }}">
                <i class="fa-solid {{ $badge['icon'] }}"></i>
            </div>
            <div>
                <h1 class="text-xl font-extrabold text-gray-900 tracking-tight mb-0.5">{{ $surat->jenisLabel() }}</h1>
                <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500">
                    <span class="bg-green-50 text-green-700 font-mono font-bold px-2 py-0.5 rounded">{{ $surat->nomor_tiket }}</span>
                    <span><i class="fa-regular fa-calendar mr-1"></i> {{ $surat->tanggal_pengajuan?->format('d M Y') ?? '-' }}</span>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-xs font-bold px-3 py-1.5 rounded-xl uppercase tracking-wide border {{ $badge['bg'] }} {{ $badge['text'] }} {{ $badge['border'] }}">
                <i class="fa-solid {{ $badge['icon'] }} mr-1"></i> {{ $badge['label'] }}
            </span>
            @if(!in_array($surat->status, ['selesai', 'ditolak', 'draft']))
                <span class="text-xs font-bold px-3 py-1.5 rounded-xl {{ $slaInfo['overdue'] ? 'bg-red-100 text-red-700 border border-red-200' : 'bg-blue-100 text-blue-700 border border-blue-200' }}">
                    <i class="fa-solid {{ $slaInfo['overdue'] ? 'fa-clock' : 'fa-hourglass-half' }} mr-1"></i>
                    {{ $slaInfo['label'] }}
                </span>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

        {{-- Kolom Kiri: Timeline (3 kolom) --}}
        <div class="lg:col-span-3 space-y-6">

            {{-- Progress Timeline --}}
            <section class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <header class="px-5 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h2 class="font-bold text-gray-800 text-sm">Status Permohonan</h2>
                </header>
                <div class="p-6">
                    <div class="relative pl-10 space-y-8">
                        @foreach($timelineSteps as $i => $timeline)
                            <div class="relative">
                                {{-- Vertical Line --}}
                                @if(!$loop->last)
                                    <div class="absolute left-[-26px] top-8 w-0.5 h-full {{ $timeline['active'] ? 'bg-green-200' : 'bg-gray-200' }}"></div>
                                @endif
                                {{-- Circle --}}
                                <div class="absolute left-[-34px] top-1 w-8 h-8 rounded-full flex items-center justify-center text-sm border-2 z-10
                                    @if($timeline['current'])
                                        {{ $surat->status === 'ditolak' ? 'bg-red-100 border-red-400 text-red-500' : 'bg-green-100 border-green-500 text-green-600 animate-pulse' }}
                                    @elseif($timeline['active'])
                                        bg-green-500 border-green-500 text-white
                                    @else
                                        bg-white border-gray-200 text-gray-300
                                    @endif
                                ">
                                    <i class="fa-solid {{ $timeline['icon'] }}"></i>
                                </div>
                                {{-- Content --}}
                                <div>
                                    <h3 class="font-bold text-sm {{ $timeline['active'] ? 'text-gray-900' : 'text-gray-400' }}">{{ $timeline['title'] }}</h3>
                                    <p class="text-xs {{ $timeline['active'] ? 'text-gray-500' : 'text-gray-300' }} mt-0.5 leading-relaxed">{{ $timeline['desc'] }}</p>
                                    @if($timeline['date'] && $timeline['active'])
                                        <span class="text-[10px] text-gray-400 mt-1 inline-block">
                                            <i class="fa-regular fa-clock mr-1"></i> {{ $timeline['date'] }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            {{-- Detail Permohonan --}}
            <section class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <header class="px-5 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h2 class="font-bold text-gray-800 text-sm">Detail Permohonan</h2>
                </header>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Jenis Surat</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $surat->jenisLabel() }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Prioritas</p>
                        @php $priBadge = $surat->prioritasBadge(); @endphp
                        <span class="text-xs font-bold px-2.5 py-1 rounded-lg border {{ $priBadge['bg'] }} {{ $priBadge['text'] }} {{ $priBadge['border'] }}">
                            {{ $priBadge['label'] }}
                        </span>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Keperluan</p>
                        <p class="text-sm text-gray-700">{{ $surat->keperluan ?? '-' }}</p>
                    </div>
                    @if($surat->catatan)
                        <div class="sm:col-span-2">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Catatan</p>
                            <p class="text-sm text-gray-700">{{ $surat->catatan }}</p>
                        </div>
                    @endif
                    @if($surat->nama_usaha)
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Nama Usaha</p>
                            <p class="text-sm text-gray-800">{{ $surat->nama_usaha }}</p>
                        </div>
                    @endif
                    @if($surat->berlaku_hingga)
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Berlaku Hingga</p>
                            <p class="text-sm text-gray-800">{{ \Carbon\Carbon::parse($surat->berlaku_hingga)->format('d M Y') }}</p>
                        </div>
                    @endif
                    @if($surat->alasan_tolak)
                        <div class="sm:col-span-2 bg-red-50 border border-red-100 rounded-xl p-4">
                            <p class="text-[10px] font-bold text-red-500 uppercase tracking-wider mb-1">Alasan Penolakan</p>
                            <p class="text-sm text-red-800">{{ $surat->alasan_tolak }}</p>
                        </div>
                    @endif
                </div>
            </section>
        </div>

        {{-- Kolom Kanan: Info Pemohon & Aksi (2 kolom) --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Data Pemohon --}}
            <section class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 bg-green-700 text-white">
                    <h3 class="font-bold text-sm flex items-center gap-2">
                        <i class="fa-solid fa-user-circle"></i> Data Pemohon
                    </h3>
                </div>
                <div class="p-5 space-y-3">
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase tracking-wider font-bold">Nama Lengkap</p>
                        <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $surat->penduduk->nama ?? $warga->nama }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase tracking-wider font-bold">NIK</p>
                        <p class="text-sm font-semibold text-gray-700 mt-0.5 font-mono">{{ $warga->formattedNik() }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase tracking-wider font-bold">Alamat</p>
                        <p class="text-sm text-gray-700 mt-0.5">{{ $warga->alamatLengkap() }}</p>
                    </div>
                </div>
            </section>

            {{-- Dokumen Download (jika selesai) --}}
            @if($surat->status === 'selesai')
                <section class="bg-white rounded-2xl border border-green-100 shadow-sm overflow-hidden border-l-4 border-l-green-500">
                    <div class="p-5">
                        <h3 class="font-bold text-gray-800 text-sm mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-file-arrow-down text-green-500"></i> Dokumen Tersedia
                        </h3>
                        <div class="bg-green-50 rounded-xl p-4 flex items-center gap-4 border border-green-100 mb-3">
                            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center text-green-600 shadow-sm shrink-0">
                                <i class="fa-solid fa-file-pdf text-lg"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-800 truncate">{{ $surat->jenisShort() }}.pdf</p>
                                <p class="text-[10px] text-gray-500">Selesai {{ $surat->tanggal_selesai?->format('d M Y') }}</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 flex items-start gap-1.5">
                            <i class="fa-solid fa-circle-info mt-0.5 shrink-0"></i>
                            Dokumen dapat dicetak atau diunduh dalam format PDF digital.
                        </p>
                    </div>
                </section>
            @endif

            {{-- Help Box --}}
            <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5">
                <h4 class="text-sm font-bold text-blue-800 mb-2 flex items-center gap-2">
                    <i class="fa-solid fa-headset text-blue-500"></i> Butuh Bantuan?
                </h4>
                <p class="text-xs text-blue-700 leading-relaxed mb-3">
                    Jika Anda memiliki pertanyaan tentang status permohonan ini, silakan hubungi Kantor Desa melalui:
                </p>
                <ul class="text-xs text-blue-700 space-y-1.5">
                    <li class="flex items-center gap-2">
                        <i class="fa-solid fa-phone text-blue-400 shrink-0 w-3"></i>
                        (0263) 123-456
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fa-solid fa-building text-blue-400 shrink-0 w-3"></i>
                        Kantor Desa: 08.00 – 15.00 WIB (Senin-Jumat)
                    </li>
                </ul>
            </div>
        </div>
    </div>

@endsection
