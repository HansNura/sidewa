{{--
    Halaman: Notifikasi Status (Pusat Notifikasi)
    Route: warga.notifikasi
    Guard: auth:warga
    Sumber Desain: Notifikasi-Status.html (Google Stitch)
--}}

@extends('layouts.warga')

@section('title', 'Notifikasi - Layanan Mandiri Desa Sindangmukti')
@section('meta_description', 'Pantau status permohonan surat, bantuan sosial, dan pengaduan Anda di sini.')

@php $pageTitle = 'Notifikasi & Status'; @endphp

@section('content')

    <div x-data="{
        activeTab: 'semua',
        markAllRead: false,
    }">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight mb-1">Notifikasi</h1>
                <p class="text-sm text-gray-500">Pantau status terkini dari semua layanan yang Anda ajukan.</p>
            </div>
            <div class="flex items-center gap-3 text-sm">
                @if($notifications->where('isNew', true)->count() > 0)
                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold">
                        {{ $notifications->where('isNew', true)->count() }} baru
                    </span>
                @endif
            </div>
        </div>

        {{-- Tab Bar --}}
        <div class="flex items-center gap-1 p-1 bg-gray-100 rounded-xl mb-6 overflow-x-auto w-fit">
            <button @click="activeTab = 'semua'"
                    class="px-4 py-2 rounded-lg text-sm font-bold transition-all whitespace-nowrap"
                    :class="activeTab === 'semua' ? 'bg-white text-green-700 shadow-sm' : 'text-gray-500 hover:text-gray-700'">
                <i class="fa-solid fa-bell mr-1"></i> Semua
            </button>
            <button @click="activeTab = 'surat'"
                    class="px-4 py-2 rounded-lg text-sm font-bold transition-all whitespace-nowrap"
                    :class="activeTab === 'surat' ? 'bg-white text-blue-700 shadow-sm' : 'text-gray-500 hover:text-gray-700'">
                <i class="fa-solid fa-file-lines mr-1"></i> Surat
            </button>
            <button @click="activeTab = 'bansos'"
                    class="px-4 py-2 rounded-lg text-sm font-bold transition-all whitespace-nowrap"
                    :class="activeTab === 'bansos' ? 'bg-white text-purple-700 shadow-sm' : 'text-gray-500 hover:text-gray-700'">
                <i class="fa-solid fa-hand-holding-heart mr-1"></i> Bantuan
            </button>
        </div>

        {{-- Notifications List --}}
        <section class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            @if($notifications->count() > 0)
                <div class="divide-y divide-gray-100">
                    @foreach($notifications as $notif)
                        <div class="px-5 py-4 hover:bg-gray-50 transition-colors flex items-start gap-4 group
                            @if($notif['isNew']) bg-green-50/30 border-l-4 border-l-green-400 @endif"
                            x-show="activeTab === 'semua'
                                || (activeTab === 'surat' && '{{ $notif['message'] }}'.toLowerCase().includes('surat') || '{{ $notif['message'] }}'.toLowerCase().includes('dokumen') || '{{ $notif['message'] }}'.toLowerCase().includes('berkas'))
                                || (activeTab === 'bansos' && '{{ $notif['message'] }}'.toLowerCase().includes('bantuan') || '{{ $notif['message'] }}'.toLowerCase().includes('bansos'))
                            ">

                            {{-- Icon --}}
                            <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 {{ $notif['iconBg'] }}">
                                <i class="{{ $notif['icon'] }}"></i>
                            </div>

                            {{-- Content --}}
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-800 leading-relaxed {{ $notif['isNew'] ? 'font-bold' : 'font-medium' }}">
                                    {{ $notif['message'] }}
                                </p>
                                <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                                    <i class="fa-regular fa-clock"></i>
                                    {{ $notif['time'] }}
                                </p>
                            </div>

                            {{-- New Badge --}}
                            @if($notif['isNew'])
                                <span class="w-2 h-2 bg-green-500 rounded-full shrink-0 mt-2 animate-pulse"></span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Empty State --}}
                <div class="py-16 text-center">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center text-gray-300 mx-auto mb-6 border border-gray-100">
                        <i class="fa-regular fa-bell-slash text-3xl"></i>
                    </div>
                    <h2 class="text-lg font-extrabold text-gray-800 mb-2">Belum Ada Notifikasi</h2>
                    <p class="text-sm text-gray-500 max-w-sm mx-auto leading-relaxed">
                        Notifikasi akan muncul ketika ada perubahan status pada permohonan surat, bantuan sosial, atau pengaduan Anda.
                    </p>
                    <div class="mt-6 flex flex-col sm:flex-row items-center justify-center gap-3">
                        <a href="{{ route('warga.surat.ajukan') }}"
                           class="text-sm font-bold text-green-600 hover:text-green-700 flex items-center gap-1.5">
                            <i class="fa-solid fa-plus"></i> Ajukan Surat
                        </a>
                        <span class="text-gray-300">•</span>
                        <a href="{{ route('warga.pengaduan') }}"
                           class="text-sm font-bold text-green-600 hover:text-green-700 flex items-center gap-1.5">
                            <i class="fa-solid fa-bullhorn"></i> Buat Pengaduan
                        </a>
                    </div>
                </div>
            @endif
        </section>

        {{-- Info Panel --}}
        <div class="mt-6 bg-blue-50 border border-blue-100 rounded-2xl p-5">
            <div class="flex items-start gap-3">
                <i class="fa-solid fa-circle-info text-blue-400 mt-0.5 shrink-0"></i>
                <div class="space-y-1">
                    <p class="text-sm font-bold text-blue-800">Tentang Notifikasi</p>
                    <p class="text-xs text-blue-700 leading-relaxed">
                        Notifikasi menampilkan 10 pembaruan terbaru dari seluruh layanan Anda.
                        Jika Anda ingin melihat detail lengkap, kunjungi halaman
                        <a href="{{ route('warga.surat.riwayat') }}" class="font-bold underline hover:text-blue-900">Riwayat Permohonan</a>
                        atau <a href="{{ route('warga.bansos') }}" class="font-bold underline hover:text-blue-900">Bantuan Sosial</a>.
                    </p>
                </div>
            </div>
        </div>

    </div>

@endsection
