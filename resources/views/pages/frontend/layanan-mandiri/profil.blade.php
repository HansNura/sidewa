{{--
    Halaman: Profil Warga
    Route: warga.profil
    Guard: auth:warga
--}}

@extends('layouts.warga')

@section('title', 'Profil Warga - Layanan Mandiri Desa Sindangmukti')

@php $pageTitle = 'Profil Saya'; @endphp

@section('content')

    <div>
        <h1 class="text-xl font-extrabold text-gray-900">Profil Warga</h1>
        <p class="text-sm text-gray-500 mt-1">Informasi data kependudukan Anda yang terdaftar di Desa Sindangmukti.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- KARTU PROFIL --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden text-center">
                <div class="bg-gradient-to-br from-green-700 to-green-900 px-6 pt-8 pb-14 relative">
                    <div class="absolute -bottom-12 left-1/2 -translate-x-1/2">
                        <div class="w-24 h-24 rounded-full bg-white/20 backdrop-blur-sm border-4 border-white shadow-lg flex items-center justify-center text-3xl font-bold text-white">
                            {{ $warga->initials() }}
                        </div>
                    </div>
                </div>
                <div class="pt-14 pb-6 px-6">
                    <h2 class="text-lg font-extrabold text-gray-900">{{ $warga->nama }}</h2>
                    <p class="text-sm text-gray-500 mt-0.5 font-mono">{{ $warga->formattedNik() }}</p>
                    <span class="inline-flex items-center gap-1.5 mt-3 bg-green-50 text-green-700 text-xs font-bold px-3 py-1.5 rounded-full border border-green-100">
                        <i class="fa-solid fa-circle-check text-green-500 text-[10px]"></i>
                        {{ $warga->is_active ? 'Akun Aktif & Terverifikasi' : 'Akun Nonaktif' }}
                    </span>
                </div>
                <div class="border-t border-gray-100 px-6 py-4 space-y-3 bg-gray-50/50">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Login Terakhir</span>
                        <span class="font-medium text-gray-700 text-xs">{{ $warga->last_login_at?->translatedFormat('d M Y, H:i') ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- DATA LENGKAP --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Data Pribadi --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-user text-green-600"></i> Data Pribadi
                    </h3>
                </div>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-5">
                    @php
                        $dataFields = [
                            ['label' => 'Nama Lengkap',       'value' => $warga->nama],
                            ['label' => 'NIK',                'value' => $warga->formattedNik(), 'mono' => true],
                            ['label' => 'No. Kartu Keluarga', 'value' => $warga->no_kk ?? '-', 'mono' => true],
                            ['label' => 'Tempat Lahir',       'value' => $warga->tempat_lahir ?? '-'],
                            ['label' => 'Tanggal Lahir',      'value' => $warga->tanggal_lahir?->translatedFormat('d F Y') . ' (' . $warga->umur() . ' tahun)'],
                            ['label' => 'Jenis Kelamin',      'value' => $warga->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'],
                            ['label' => 'Agama',              'value' => $warga->agama ?? '-'],
                            ['label' => 'Pekerjaan',          'value' => $warga->pekerjaan ?? '-'],
                            ['label' => 'Status Perkawinan',  'value' => $warga->status_perkawinan ?? '-'],
                            ['label' => 'Pendidikan Terakhir','value' => $warga->pendidikan_terakhir ?? '-'],
                        ];
                    @endphp
                    @foreach($dataFields as $field)
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase tracking-wider font-bold">{{ $field['label'] }}</p>
                            <p class="text-sm font-semibold text-gray-800 mt-0.5 {{ ($field['mono'] ?? false) ? 'font-mono' : '' }}">
                                {{ $field['value'] ?? '-' }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Alamat --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-location-dot text-green-600"></i> Alamat Domisili
                    </h3>
                </div>
                <div class="p-5 grid grid-cols-2 sm:grid-cols-4 gap-5">
                    <div class="col-span-2">
                        <p class="text-[10px] text-gray-400 uppercase tracking-wider font-bold">Alamat Lengkap</p>
                        <p class="text-sm font-semibold text-gray-800 mt-0.5">{{ $warga->alamat ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase tracking-wider font-bold">RT / RW</p>
                        <p class="text-sm font-semibold text-gray-800 mt-0.5">
                            {{ $warga->rt ? sprintf('%02d', $warga->rt) : '-' }} /
                            {{ $warga->rw ? sprintf('%02d', $warga->rw) : '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase tracking-wider font-bold">Dusun</p>
                        <p class="text-sm font-semibold text-gray-800 mt-0.5">{{ $warga->dusun ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Info Keamanan PIN --}}
            <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5 flex items-start gap-4">
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-blue-800">Ganti PIN Akun</h4>
                    <p class="text-xs text-blue-700/80 mt-0.5 leading-relaxed">
                        Untuk mengubah PIN akun layanan Anda, silakan datang langsung ke Kantor Desa Sindangmukti
                        dengan membawa e-KTP yang masih berlaku.
                    </p>
                </div>
            </div>

        </div>
    </div>

@endsection
