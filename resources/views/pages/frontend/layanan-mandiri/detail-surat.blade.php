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
            'desc' => in_array($surat->status, ['menunggu_tte', 'selesai'])
                ? 'Berkas telah diverifikasi oleh petugas desa'
                : 'Berkas sedang diperiksa petugas desa',
            'icon' => 'fa-list-check',
            'date' => $surat->status !== 'pengajuan' ? $surat->updated_at->format('d M Y, H:i') : null,
            'active' => in_array($surat->status, ['verifikasi', 'menunggu_tte', 'selesai']),
            'current' => $surat->status === 'verifikasi',
        ],
        [
            'title' => 'Tanda Tangan Kades',
            'desc' => $surat->status === 'selesai'
                ? 'Telah ditandatangani secara elektronik (TTE) oleh Kepala Desa'
                : 'Menunggu tanda tangan elektronik (TTE) Kepala Desa',
            'icon' => 'fa-signature',
            'date' => in_array($surat->status, ['menunggu_tte', 'selesai']) 
                ? ($surat->status === 'selesai' ? $surat->tanggal_selesai?->format('d M Y, H:i') : $surat->updated_at->format('d M Y, H:i'))
                : null,
            'active' => in_array($surat->status, ['menunggu_tte', 'selesai']),
            'current' => $surat->status === 'menunggu_tte',
        ],
        [
            'title' => $surat->status === 'ditolak' ? 'Ditolak' : 'Selesai',
            'desc' =>
                $surat->status === 'ditolak'
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

    {{-- Session Alerts --}}
    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl px-5 py-4 mb-6 flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-green-500 text-xl"></i>
            <p class="text-sm font-semibold">{{ session('success') }}</p>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl px-5 py-4 mb-6 flex items-center gap-3">
            <i class="fa-solid fa-circle-xmark text-red-500 text-xl"></i>
            <p class="text-sm font-semibold">{{ session('error') }}</p>
        </div>
    @endif

    {{-- Header --}}
    <div
        class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div class="flex items-center gap-4">
            <div
                class="w-14 h-14 rounded-2xl {{ $badge['bg'] }} flex items-center justify-center text-2xl {{ $badge['text'] }} border {{ $badge['border'] }}">
                <i class="fa-solid {{ $badge['icon'] }}"></i>
            </div>
            <div>
                <h1 class="text-xl font-extrabold text-gray-900 tracking-tight mb-0.5">{{ $surat->jenisLabel() }}</h1>
                <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500">
                    <span
                        class="bg-green-50 text-green-700 font-mono font-bold px-2 py-0.5 rounded">{{ $surat->nomor_tiket }}</span>
                    <span><i class="fa-regular fa-calendar mr-1"></i>
                        {{ $surat->tanggal_pengajuan?->format('d M Y') ?? '-' }}</span>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <span
                class="text-xs font-bold px-3 py-1.5 rounded-xl uppercase tracking-wide border {{ $badge['bg'] }} {{ $badge['text'] }} {{ $badge['border'] }}">
                <i class="fa-solid {{ $badge['icon'] }} mr-1"></i> {{ $badge['label'] }}
            </span>
            @if (!in_array($surat->status, ['selesai', 'ditolak', 'draft']))
                <span
                    class="text-xs font-bold px-3 py-1.5 rounded-xl {{ $slaInfo['overdue'] ? 'bg-red-100 text-red-700 border border-red-200' : 'bg-blue-100 text-blue-700 border border-blue-200' }}">
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
            <section
                class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 hover:shadow-md transition-shadow duration-300">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-10">
                    <div>
                        <h3 class="text-xl font-extrabold text-slate-800 tracking-tight">Lacak Permohonan</h3>
                        <p class="text-xs text-slate-500 mt-1 font-medium">Status real-time berkas permohonan Anda</p>
                    </div>
                    <span
                        class="px-3 py-1.5 rounded-xl text-[10px] font-bold uppercase tracking-widest border {{ $badge['bg'] }} {{ $badge['text'] }} {{ $badge['border'] }}">
                        {{ $badge['label'] }}
                    </span>
                </div>

                <div class="relative space-y-0">
                    @foreach ($timelineSteps as $i => $timeline)
                        @php
                            $isActive = $timeline['active'];
                            $isCurrent = $timeline['current'];
                            $isLast = $loop->last;
                            $isDitolak = $surat->status === 'ditolak';

                            // Color Logic
                            $color = $isDitolak && ($isCurrent || ($isLast && $isActive)) ? 'red' : 'emerald';
                        @endphp

                        <div class="relative flex items-start group">
                            {{-- Vertical Line Segment --}}
                            @if (!$isLast)
                                <div @class([
                                    'absolute left-[13px] top-[28px] bottom-0 w-0.5 transition-all duration-500',
                                    'bg-' . $color . '-500' => $isActive && $timelineSteps[$i + 1]['active'],
                                    'bg-slate-100' => !($isActive && $timelineSteps[$i + 1]['active']),
                                ])></div>
                            @endif

                            {{-- Circle Icon --}}
                            <div @class([
                                'relative flex items-center justify-center w-7 h-7 rounded-full ring-4 ring-white z-10 transition-all duration-300 group-hover:scale-110',
                                'bg-' . $color . '-500 shadow-lg shadow-' . $color . '-200' => $isActive && !$isCurrent,
                                'bg-white border-2 border-' . $color . '-500' => $isCurrent,
                                'bg-slate-50 border border-slate-200' => !$isActive,
                            ])>
                                @if ($isActive && !$isCurrent)
                                    @if ($isDitolak && $isLast)
                                        <i class="fa-solid fa-xmark text-white text-[10px]"></i>
                                    @else
                                        <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @endif
                                @elseif ($isCurrent)
                                    <div class="w-2 h-2 rounded-full bg-{{ $color }}-500 animate-pulse"></div>
                                @else
                                    <div class="w-1.5 h-1.5 rounded-full bg-slate-300"></div>
                                @endif
                            </div>

                            {{-- Content --}}
                            <div class="ml-8 flex flex-col pb-10">
                                <h4 @class([
                                    'text-sm font-bold transition-colors',
                                    'text-slate-900 group-hover:text-' . $color . '-600' => $isActive && !$isCurrent,
                                    'text-' . $color . '-600' => $isCurrent,
                                    'text-slate-400' => !$isActive,
                                ])>
                                    {{ $timeline['title'] }}
                                </h4>

                                @if ($isCurrent)
                                    <div
                                        class="mt-3 p-4 rounded-2xl bg-{{ $color }}-50/50 border border-{{ $color }}-100/50">
                                        <p class="text-xs text-{{ $color }}-800 font-semibold leading-relaxed">
                                            {{ $timeline['desc'] }}
                                        </p>
                                    </div>
                                @else
                                    <p @class([
                                        'text-xs mt-1.5 leading-relaxed font-medium',
                                        'text-slate-500' => $isActive,
                                        'text-slate-400' => !$isActive,
                                    ])>
                                        {{ $timeline['desc'] }}
                                    </p>
                                @endif

                                @if ($timeline['date'] && $isActive)
                                    <div class="mt-3">
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2 py-1 rounded-lg bg-slate-50 border border-slate-100 text-[10px] font-bold text-slate-500">
                                            <i class="fa-regular fa-clock text-slate-400"></i>
                                            {{ $timeline['date'] }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- Detail Permohonan --}}
            <section
                class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden transition-shadow duration-300 hover:shadow-md">
                <header class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h2 class="font-bold text-slate-800 text-sm">Detail Permohonan</h2>
                </header>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-6">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Jenis Surat</p>
                        <p class="text-sm font-bold text-slate-800">{{ $surat->jenisLabel() }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Prioritas</p>
                        @php $priBadge = $surat->prioritasBadge(); @endphp
                        <span
                            class="inline-flex items-center text-[10px] font-bold px-2.5 py-1 rounded-lg border {{ $priBadge['bg'] }} {{ $priBadge['text'] }} {{ $priBadge['border'] }}">
                            {{ $priBadge['label'] }}
                        </span>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Keperluan</p>
                        <p class="text-sm text-slate-700 leading-relaxed">{{ $surat->keperluan ?? '-' }}</p>
                    </div>
                    @if ($surat->catatan)
                        <div class="sm:col-span-2">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Catatan</p>
                            <p class="text-sm text-slate-700 leading-relaxed">{{ $surat->catatan }}</p>
                        </div>
                    @endif
                    @if ($surat->nama_usaha)
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Nama Usaha</p>
                            <p class="text-sm font-bold text-slate-800">{{ $surat->nama_usaha }}</p>
                        </div>
                    @endif
                    @if ($surat->berlaku_hingga)
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Berlaku Hingga</p>
                            <p class="text-sm font-bold text-slate-800">{{ $surat->formatBerlakuHingga() }}</p>
                        </div>
                    @endif
                    @if ($surat->alasan_tolak)
                        <div class="sm:col-span-2 bg-red-50/50 border border-red-100 rounded-2xl p-5">
                            <p class="text-[10px] font-bold text-red-500 uppercase tracking-widest mb-2">Alasan Penolakan</p>
                            <p class="text-sm text-red-800 leading-relaxed font-medium">{{ $surat->alasan_tolak }}</p>
                        </div>
                    @endif
                </div>
            </section>
        </div>

        {{-- Kolom Kanan: Info Pemohon & Aksi (2 kolom) --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Data Pemohon --}}
            <section
                class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden transition-shadow duration-300 hover:shadow-md">
                <div class="px-6 py-4 border-b border-emerald-600 bg-emerald-700 text-white">
                    <h3 class="font-bold text-sm flex items-center gap-2">
                        <i class="fa-solid fa-user-circle"></i> Data Pemohon
                    </h3>
                </div>
                <div class="p-6 space-y-5">
                    <div>
                        <p class="text-[10px] text-slate-400 uppercase tracking-widest font-bold mb-1">Nama Lengkap</p>
                        <p class="text-sm font-bold text-slate-800">{{ $surat->penduduk->nama ?? $warga->nama }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 uppercase tracking-widest font-bold mb-1">NIK</p>
                        <p class="text-sm font-bold text-slate-700 font-mono">{{ $warga->formattedNik() }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 uppercase tracking-widest font-bold mb-1">Alamat</p>
                        <p class="text-sm text-slate-600 leading-relaxed">{{ $warga->alamatLengkap() }}</p>
                    </div>
                </div>
            </section>
            </section>

            {{-- Dokumen Download (jika selesai) --}}
            @if ($surat->status === 'selesai')
                <section
                    class="bg-white rounded-2xl border border-emerald-100 shadow-sm overflow-hidden border-l-4 border-l-emerald-500 hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <h3 class="font-bold text-slate-800 text-sm mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-file-arrow-down text-emerald-500"></i> Dokumen Tersedia
                        </h3>
                        <div class="bg-emerald-50/50 rounded-xl p-4 flex items-center gap-4 border border-emerald-100 mb-4">
                            <div
                                class="w-10 h-10 bg-white rounded-lg flex items-center justify-center text-emerald-600 shadow-sm shrink-0">
                                <i class="fa-solid fa-file-pdf text-lg"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-slate-800 truncate">{{ $surat->jenisShort() }}.pdf</p>
                                <p class="text-[10px] text-slate-500 font-medium">Selesai
                                    {{ $surat->tanggal_selesai?->format('d M Y') }}</p>
                            </div>
                        </div>

                        {{-- Download Button --}}
                        <a href="{{ route('warga.surat.download', $surat->id) }}"
                            class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-3 rounded-xl text-sm font-bold shadow-lg shadow-emerald-100 transition-all flex items-center justify-center gap-2 mb-4 group">
                            <i class="fa-solid fa-download group-hover:animate-bounce"></i> Download PDF Surat
                        </a>

                        <p class="text-[11px] text-slate-400 flex items-start gap-1.5 leading-relaxed">
                            <i class="fa-solid fa-circle-info mt-0.5 shrink-0 text-emerald-400"></i>
                            Dokumen ini telah ditandatangani secara elektronik dan merupakan dokumen resmi yang sah.
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
