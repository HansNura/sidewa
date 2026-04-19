{{--
    Dashboard Layanan Mandiri Warga — Desa Sindangmukti
    Protected route: auth:warga guard
--}}

@extends('layouts.frontend')

@section('title', 'Dashboard Layanan Mandiri - Desa Sindangmukti')

@section('content')

<section class="py-10 md:py-16 mt-16 bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- ═══════════════════════════════════════════════════
             HEADER: Welcome Banner
             ═══════════════════════════════════════════════════ --}}
        <div class="bg-gradient-to-r from-green-700 to-green-600 rounded-3xl p-8 mb-8 text-white relative overflow-hidden">
            {{-- Pattern overlay --}}
            <div class="absolute inset-0 opacity-10"
                 style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;40&quot; height=&quot;40&quot; viewBox=&quot;0 0 40 40&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.3&quot; fill-rule=&quot;evenodd&quot;%3E%3Cpath d=&quot;M0 20L20 0h20v20L20 40H0z&quot;/%3E%3C/g%3E%3C/svg%3E');">
            </div>
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center text-2xl font-bold backdrop-blur-sm border border-white/20">
                        {{ $warga->initials() }}
                    </div>
                    <div>
                        <p class="text-green-100 text-sm mb-1">Selamat datang di Layanan Mandiri,</p>
                        <h1 class="text-2xl font-bold">{{ $warga->nama }}</h1>
                        <p class="text-green-200 text-sm mt-0.5">
                            <i class="fa-regular fa-id-card mr-1"></i> NIK: {{ $warga->formattedNik() }}
                        </p>
                    </div>
                </div>

                {{-- Logout Button --}}
                <form action="{{ route('warga.logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/15 hover:bg-white/25
                               border border-white/20 rounded-xl text-sm font-semibold text-white
                               transition-all cursor-pointer backdrop-blur-sm">
                        <i class="fa-solid fa-right-from-bracket"></i> Keluar
                    </button>
                </form>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════
             SECTION: Data Diri Singkat
             ═══════════════════════════════════════════════════ --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 mb-8">
            <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-user-circle text-green-600"></i> Data Diri
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                <div>
                    <p class="text-gray-500 text-xs uppercase font-semibold mb-0.5">Nama Lengkap</p>
                    <p class="text-gray-800 font-medium">{{ $warga->nama }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase font-semibold mb-0.5">NIK</p>
                    <p class="text-gray-800 font-medium font-mono">{{ $warga->formattedNik() }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase font-semibold mb-0.5">No. KK</p>
                    <p class="text-gray-800 font-medium font-mono">{{ $warga->no_kk ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase font-semibold mb-0.5">Jenis Kelamin</p>
                    <p class="text-gray-800 font-medium">
                        {{ $warga->jenis_kelamin === 'L' ? 'Laki-laki' : ($warga->jenis_kelamin === 'P' ? 'Perempuan' : '-') }}
                    </p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase font-semibold mb-0.5">Tempat/Tanggal Lahir</p>
                    <p class="text-gray-800 font-medium">
                        {{ $warga->tempat_lahir ?? '-' }}{{ $warga->tanggal_lahir ? ', ' . $warga->tanggal_lahir->translatedFormat('d F Y') : '' }}
                    </p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase font-semibold mb-0.5">Alamat</p>
                    <p class="text-gray-800 font-medium">{{ $warga->alamatLengkap() }}</p>
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════
             SECTION: Menu Layanan
             ═══════════════════════════════════════════════════ --}}
        <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-grip text-green-600"></i> Menu Layanan
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
            @foreach ($layananItems as $item)
                @php
                    $colorMap = [
                        'green'  => ['bg' => 'bg-green-50', 'border' => 'border-green-100', 'icon' => 'text-green-600', 'iconBg' => 'bg-green-100'],
                        'blue'   => ['bg' => 'bg-blue-50',  'border' => 'border-blue-100',  'icon' => 'text-blue-600',  'iconBg' => 'bg-blue-100'],
                        'amber'  => ['bg' => 'bg-amber-50', 'border' => 'border-amber-100', 'icon' => 'text-amber-600', 'iconBg' => 'bg-amber-100'],
                        'purple' => ['bg' => 'bg-purple-50','border' => 'border-purple-100','icon' => 'text-purple-600','iconBg' => 'bg-purple-100'],
                    ];
                    $c = $colorMap[$item['color']] ?? $colorMap['green'];
                @endphp
                <a href="{{ $item['route'] }}"
                   class="{{ $c['bg'] }} {{ $c['border'] }} border rounded-2xl p-5 flex items-start gap-4
                          hover:shadow-md transition-all group">
                    <div class="{{ $c['iconBg'] }} {{ $c['icon'] }} w-12 h-12 rounded-xl flex items-center justify-center shrink-0
                                group-hover:scale-110 transition-transform">
                        <i class="{{ $item['icon'] }} text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 mb-0.5 group-hover:text-green-700 transition-colors">
                            {{ $item['title'] }}
                        </h3>
                        <p class="text-sm text-gray-500">{{ $item['description'] }}</p>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- ═══════════════════════════════════════════════════
             SECTION: Info Placeholder
             ═══════════════════════════════════════════════════ --}}
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 text-center">
            <i class="fa-solid fa-triangle-exclamation text-amber-500 text-lg mb-1"></i>
            <p class="text-sm text-amber-700 font-medium">
                Dashboard ini masih dalam tahap pengembangan.
            </p>
            <p class="text-xs text-amber-600 mt-1">
                Fitur cetak surat, cek bansos, dan riwayat pengajuan akan segera tersedia.
            </p>
        </div>

        {{-- Last Login Info --}}
        @if ($warga->last_login_at)
            <div class="mt-6 text-center text-xs text-gray-400">
                <i class="fa-regular fa-clock mr-1"></i>
                Login terakhir: {{ $warga->last_login_at->translatedFormat('d F Y, H:i') }} WIB
            </div>
        @endif

    </div>
</section>

@endsection
