{{--
    Dashboard Layanan Mandiri Warga — Desa Sindangmukti
    Protected route: auth:warga guard
    Layout: layouts.warga (dedicated sidebar + topbar)
--}}

@extends('layouts.warga')

@section('title', 'Dashboard Warga - Desa Sindangmukti')
@section('meta_description', 'Dashboard layanan mandiri warga Desa Sindangmukti.')

@php $pageTitle = 'Dashboard Warga'; @endphp

@section('content')

    {{-- ═══════════════════════════════════════
         1. WELCOME BANNER
    ═══════════════════════════════════════ --}}
    <section class="relative bg-gradient-to-r from-green-700 to-green-900 rounded-3xl p-6 sm:p-8 shadow-lg overflow-hidden flex flex-col md:flex-row items-center gap-6">
        {{-- Decorative Blobs --}}
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-yellow-400/20 rounded-full blur-2xl pointer-events-none"></div>

        {{-- Avatar --}}
        <div class="relative shrink-0 z-10">
            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full border-4 border-white/20 shadow-md bg-white/20 backdrop-blur-sm flex items-center justify-center text-3xl sm:text-4xl font-bold text-white select-none">
                {{ $warga->initials() }}
            </div>
            <div class="absolute bottom-0 right-0 bg-green-400 border-2 border-green-800 w-5 h-5 rounded-full" title="Akun Aktif"></div>
        </div>

        {{-- Greeting & Info --}}
        <div class="flex-1 text-center md:text-left z-10 text-white">
            <p class="text-green-100 text-sm font-medium mb-1" x-text="getGreeting() + ','">Selamat Datang,</p>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight mb-2">{{ $warga->nama }}</h1>
            <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 text-sm text-green-50">
                <span class="flex items-center gap-1.5">
                    <i class="fa-regular fa-id-card opacity-70"></i>
                    {{ $warga->formattedNik() }}
                </span>
                <span class="hidden sm:inline opacity-50">|</span>
                <span class="flex items-center gap-1.5">
                    <i class="fa-solid fa-location-dot opacity-70"></i>
                    {{ $warga->alamatLengkap() }}
                </span>
            </div>
        </div>

        {{-- Date & Status --}}
        <div class="flex flex-col items-center md:items-end gap-3 z-10 w-full md:w-auto mt-4 md:mt-0 border-t md:border-t-0 md:border-l border-white/20 pt-4 md:pt-0 md:pl-6">
            <p class="text-sm font-medium text-green-100 flex items-center gap-2">
                <i class="fa-regular fa-calendar"></i>
                <span x-text="getCurrentDate()"></span>
            </p>
            <span class="inline-flex items-center gap-1.5 bg-white text-green-800 text-xs font-bold px-3 py-1.5 rounded-lg shadow-sm">
                <i class="fa-solid fa-circle-check text-green-500"></i> Akun Terverifikasi
            </span>
            @if($warga->last_login_at)
                <p class="text-[10px] text-green-200/80">
                    <i class="fa-regular fa-clock mr-1"></i>
                    Login terakhir: {{ $warga->last_login_at->translatedFormat('d M Y, H:i') }} WIB
                </p>
            @endif
        </div>
    </section>

    {{-- ═══════════════════════════════════════
         2. QUICK ACTION CARDS
    ═══════════════════════════════════════ --}}
    <section>
        <h2 class="text-lg font-bold text-gray-800 mb-4 px-1">Layanan Cepat</h2>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">
            @php
                $colorMap = [
                    'blue'    => ['bg' => 'bg-blue-50',    'text' => 'text-blue-600',    'hover' => 'group-hover:bg-blue-100'],
                    'emerald' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'hover' => 'group-hover:bg-emerald-100'],
                    'amber'   => ['bg' => 'bg-amber-50',   'text' => 'text-amber-600',   'hover' => 'group-hover:bg-amber-100'],
                    'red'     => ['bg' => 'bg-red-50',     'text' => 'text-red-600',     'hover' => 'group-hover:bg-red-100'],
                ];
            @endphp
            @foreach($quickActions as $action)
                @php $c = $colorMap[$action['color']] ?? $colorMap['blue']; @endphp
                <a href="{{ $action['route'] }}"
                   class="group bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-green-200 transition-all duration-300 flex flex-col items-center justify-center text-center transform hover:-translate-y-1">
                    <div class="w-12 h-12 {{ $c['bg'] }} {{ $c['text'] }} rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform {{ $c['hover'] }}">
                        <i class="{{ $action['icon'] }} text-xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 text-sm mb-1">{{ $action['title'] }}</h3>
                    <p class="text-[10px] text-gray-500">{{ $action['description'] }}</p>
                </a>
            @endforeach
        </div>
    </section>

    {{-- ═══════════════════════════════════════
         TWO-COLUMN GRID
    ═══════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">

        {{-- KIRI: Status & Notifikasi (2 kolom) --}}
        <div class="lg:col-span-2 space-y-6 sm:space-y-8">

            {{-- 3. STATUS PERMOHONAN SURAT --}}
            <section class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
                <header class="px-5 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-gray-800 text-base">Status Permohonan Surat</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Pantau progres 5 pengajuan terakhir Anda.</p>
                    </div>
                    <a href="{{ route('warga.surat.riwayat') }}"
                       class="text-xs font-semibold text-green-600 hover:text-green-800 whitespace-nowrap">
                        Lihat Semua &rarr;
                    </a>
                </header>
                <div class="flex-1 divide-y divide-gray-50">
                    @forelse($suratPermohonan as $surat)
                        @php $badge = $surat->statusBadge(); @endphp
                        <article class="p-5 hover:bg-gray-50/80 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-4 {{ $surat->status == 'ditolak' ? 'opacity-80' : '' }}">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl {{ $badge['bg'] }} {{ $badge['text'] }} flex items-center justify-center shrink-0 border {{ $badge['border'] }}">
                                    @if(in_array($surat->status, ['pengajuan', 'verifikasi']))
                                        <i class="fa-solid fa-spinner animate-spin"></i>
                                    @elseif($surat->status === 'selesai')
                                        <i class="fa-solid fa-check-double"></i>
                                    @elseif($surat->status === 'ditolak')
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                    @elseif($surat->status === 'menunggu_tte')
                                        <i class="fa-solid fa-signature"></i>
                                    @else
                                        <i class="fa-solid fa-{{ $badge['icon'] }}"></i>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800 text-sm">{{ $surat->jenisLabel() }}</h4>
                                    <p class="text-xs text-gray-500 mt-1">
                                        @if($surat->status === 'ditolak')
                                            <span class="text-red-500 font-medium">
                                                Catatan: {{ $surat->alasan_tolak ?? 'Silakan ajukan ulang.' }}
                                            </span>
                                        @else
                                            Diajukan: {{ $surat->tanggal_pengajuan?->translatedFormat('d M Y, H:i') }} WIB
                                        @endif
                                    </p>
                                    <p class="text-[10px] text-gray-400 mt-0.5">No. Tiket: {{ $surat->nomor_tiket }}</p>
                                </div>
                            </div>
                            <div class="flex flex-row sm:flex-col items-center sm:items-end justify-between sm:justify-center mt-2 sm:mt-0 gap-2">
                                <span class="{{ $badge['bg'] }} {{ $badge['text'] }} text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase tracking-wide border {{ $badge['border'] }}">
                                    {{ $badge['label'] }}
                                </span>
                                @if($surat->status === 'ditolak')
                                    <a href="{{ route('warga.surat.ajukan') }}"
                                       class="text-[10px] font-semibold bg-green-50 text-green-700 hover:bg-green-100 px-2.5 py-1 rounded-lg transition-colors">
                                        <i class="fa-solid fa-rotate-right mr-1"></i>Ajukan Ulang
                                    </a>
                                @endif
                            </div>
                        </article>
                    @empty
                        <div class="p-10 text-center">
                            <i class="fa-solid fa-inbox text-gray-200 text-4xl mb-3 block"></i>
                            <p class="text-sm text-gray-500 mb-3">Belum ada permohonan surat yang diajukan.</p>
                            <a href="{{ route('warga.surat.ajukan') }}"
                               class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-green-600 hover:bg-green-700 px-4 py-2 rounded-xl transition-colors">
                                <i class="fa-solid fa-plus"></i> Ajukan Sekarang
                            </a>
                        </div>
                    @endforelse
                </div>
            </section>

            {{-- 4. NOTIFIKASI TERBARU --}}
            <section class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
                <header class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800 text-base flex items-center gap-2">
                        Pemberitahuan
                        @php $newCount = $notifications->where('isNew', true)->count(); @endphp
                        @if($newCount > 0)
                            <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full leading-none">{{ $newCount }} Baru</span>
                        @endif
                    </h3>
                    <a href="{{ route('warga.notifikasi') }}" class="text-xs font-semibold text-gray-500 hover:text-gray-800 hover:underline">
                        Lihat Semua
                    </a>
                </header>
                <div class="divide-y divide-gray-50 max-h-[320px] overflow-y-auto custom-scrollbar">
                    @forelse($notifications as $notif)
                        <a href="#" class="block p-4 {{ $notif['isNew'] ? 'bg-blue-50/30' : '' }} hover:bg-gray-50 transition-colors">
                            <div class="flex gap-4 items-start relative">
                                @if($notif['isNew'])
                                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-1.5 bg-green-500 rounded-full"></div>
                                @endif
                                <div class="w-8 h-8 rounded-full {{ $notif['iconBg'] }} flex items-center justify-center shrink-0 ml-4">
                                    <i class="{{ $notif['icon'] }} text-xs"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-900 {{ $notif['isNew'] ? 'font-semibold' : 'font-medium' }} leading-snug">
                                        {{ $notif['message'] }}
                                    </p>
                                    <p class="text-[10px] text-gray-400 mt-1">
                                        <i class="fa-regular fa-clock mr-1"></i> {{ $notif['time'] }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="p-8 text-center">
                            <i class="fa-regular fa-bell-slash text-gray-200 text-3xl mb-2 block"></i>
                            <p class="text-sm text-gray-400">Belum ada pemberitahuan.</p>
                        </div>
                    @endforelse
                </div>
            </section>
        </div>

        {{-- KANAN: Info Desa (1 kolom) --}}
        <div class="lg:col-span-1">
            <section class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden lg:sticky lg:top-6">
                <header class="px-5 py-4 border-b border-gray-100 bg-green-700 text-white">
                    <h3 class="font-bold text-base flex items-center gap-2">
                        <i class="fa-regular fa-newspaper"></i> Info Desa
                    </h3>
                </header>
                <div class="p-4 space-y-4">
                    @forelse($berita as $news)
                        <article class="group flex gap-3 cursor-pointer">
                            @if($news->cover_image)
                                <img src="{{ asset('storage/' . $news->cover_image) }}"
                                     alt="{{ $news->judul }}"
                                     class="w-16 h-16 rounded-xl object-cover shadow-sm group-hover:opacity-80 transition-opacity shrink-0">
                            @else
                                <div class="w-16 h-16 rounded-xl bg-green-50 text-green-300 flex items-center justify-center shadow-sm shrink-0">
                                    <i class="fa-solid fa-image text-xl"></i>
                                </div>
                            @endif
                            <div class="flex flex-col justify-between py-0.5 flex-1 min-w-0">
                                <h4 class="text-sm font-bold text-gray-800 leading-tight group-hover:text-green-600 transition-colors line-clamp-2">
                                    {{ $news->judul }}
                                </h4>
                                <p class="text-[10px] text-gray-500 mt-1">
                                    <i class="fa-regular fa-calendar mr-1"></i>
                                    {{ $news->published_at?->translatedFormat('d M Y') }}
                                </p>
                            </div>
                        </article>
                    @empty
                        <div class="text-center py-6 text-sm text-gray-400">
                            <i class="fa-solid fa-newspaper text-gray-200 text-2xl mb-2 block"></i>
                            Belum ada berita terbaru.
                        </div>
                    @endforelse
                </div>
                <div class="px-4 pb-4">
                    <a href="{{ route('informasi.berita-artikel') }}"
                       class="block w-full text-center py-2 text-xs font-semibold text-green-700 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                        Baca Berita Lainnya
                    </a>
                </div>
            </section>
        </div>
    </div>

@endsection
