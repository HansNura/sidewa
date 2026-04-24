{{--
    Halaman: Layanan Pengaduan Warga
    Route: warga.pengaduan
    Guard: auth:warga
    Sumber Desain: Layanan-Pengaduan.html (Google Stitch)
--}}

@extends('layouts.warga')

@section('title', 'Pengaduan Warga - Layanan Mandiri Desa Sindangmukti')
@section('meta_description', 'Sampaikan pengaduan, keluhan, atau aspirasi Anda kepada pemerintah desa.')

@php
    $pageTitle = 'Layanan Pengaduan';
    $warga = Auth::guard('warga')->user();
@endphp

@section('content')

    <div x-data="{
        successModal: {{ session('success') ? 'true' : 'false' }},
        kategori: '{{ old('kategori', '') }}',
        prioritas: '{{ old('prioritas', 'sedang') }}',

        kategoriList: [
            { id: 'infrastruktur', label: 'Infrastruktur', icon: 'fa-road', color: 'amber' },
            { id: 'kebersihan', label: 'Kebersihan & Lingkungan', icon: 'fa-leaf', color: 'green' },
            { id: 'keamanan', label: 'Keamanan', icon: 'fa-shield-halved', color: 'red' },
            { id: 'administrasi', label: 'Administrasi', icon: 'fa-file-lines', color: 'blue' },
            { id: 'sosial', label: 'Sosial & Kemasyarakatan', icon: 'fa-people-group', color: 'purple' },
            { id: 'lainnya', label: 'Lainnya', icon: 'fa-ellipsis', color: 'gray' },
        ]
    }">

        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight mb-1">Layanan Pengaduan</h1>
            <p class="text-sm text-gray-500">Sampaikan pengaduan, keluhan, atau aspirasi Anda. Kami akan menindaklanjuti segera.</p>
        </div>

        {{-- Error Messages --}}
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 rounded-2xl px-5 py-4 mb-6">
                <p class="font-bold text-sm mb-1 flex items-center gap-2"><i class="fa-solid fa-triangle-exclamation"></i> Mohon periksa kembali:</p>
                <ul class="text-sm list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Success Modal --}}
        <div x-show="successModal"
             x-transition.opacity.duration.300ms
             class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
             x-cloak>
            <div @click.outside="successModal = false"
                 x-show="successModal" x-transition.scale.origin.center
                 class="bg-white rounded-3xl p-8 max-w-sm w-full shadow-2xl text-center relative overflow-hidden">
                <div class="absolute inset-x-0 top-0 h-24 bg-gradient-to-b from-green-50 to-transparent"></div>
                <div class="relative z-10">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                        <i class="fa-solid fa-paper-plane text-green-500 text-3xl"></i>
                    </div>
                    <h2 class="text-xl font-extrabold text-gray-900 mb-2">Pengaduan Terkirim!</h2>
                    <p class="text-sm text-gray-500 leading-relaxed mb-6">{{ session('success') }}</p>
                    <button @click="successModal = false"
                            class="w-full bg-green-700 hover:bg-green-800 text-white py-3 rounded-xl font-bold text-sm shadow-md transition-all">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- KOLOM KIRI: FORM PENGADUAN (2 kolom) --}}
            <div class="lg:col-span-2">
                <form action="{{ route('warga.pengaduan.store') }}" method="POST"
                      class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    @csrf

                    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                        <h2 class="font-bold text-gray-800 text-base flex items-center gap-2">
                            <i class="fa-solid fa-bullhorn text-red-500"></i> Formulir Pengaduan
                        </h2>
                        <p class="text-xs text-gray-500 mt-1">Isi form berikut dengan lengkap dan jelas agar pengaduan dapat ditindaklanjuti.</p>
                    </div>

                    <div class="p-6 space-y-6">

                        {{-- Kategori Pengaduan --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-3">Kategori Pengaduan <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                <template x-for="kat in kategoriList" :key="kat.id">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="kategori" :value="kat.id" x-model="kategori" class="sr-only peer">
                                        <div class="flex items-center gap-2 p-3 rounded-xl border-2 border-gray-200 peer-checked:border-green-500 peer-checked:bg-green-50 transition-all hover:border-gray-300">
                                            <i class="fa-solid text-sm" :class="kat.icon + ' text-' + kat.color + '-500'"></i>
                                            <span class="text-xs font-bold text-gray-700" x-text="kat.label"></span>
                                        </div>
                                    </label>
                                </template>
                            </div>
                            @error('kategori')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Prioritas --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-3">Tingkat Urgensi <span class="text-red-500">*</span></label>
                            <div class="flex gap-3">
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="prioritas" value="rendah" x-model="prioritas" class="sr-only peer">
                                    <div class="text-center p-3 rounded-xl border-2 border-gray-200 peer-checked:border-green-500 peer-checked:bg-green-50 transition-all">
                                        <i class="fa-solid fa-circle text-green-400 text-xs mb-1"></i>
                                        <p class="text-xs font-bold text-gray-700">Rendah</p>
                                    </div>
                                </label>
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="prioritas" value="sedang" x-model="prioritas" class="sr-only peer">
                                    <div class="text-center p-3 rounded-xl border-2 border-gray-200 peer-checked:border-amber-500 peer-checked:bg-amber-50 transition-all">
                                        <i class="fa-solid fa-circle text-amber-400 text-xs mb-1"></i>
                                        <p class="text-xs font-bold text-gray-700">Sedang</p>
                                    </div>
                                </label>
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="prioritas" value="tinggi" x-model="prioritas" class="sr-only peer">
                                    <div class="text-center p-3 rounded-xl border-2 border-gray-200 peer-checked:border-red-500 peer-checked:bg-red-50 transition-all">
                                        <i class="fa-solid fa-circle text-red-400 text-xs mb-1"></i>
                                        <p class="text-xs font-bold text-gray-700">Tinggi</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Judul Pengaduan --}}
                        <div>
                            <label for="judul" class="block text-xs font-bold text-gray-700 mb-1">
                                Judul Pengaduan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="judul" name="judul" required
                                   value="{{ old('judul') }}"
                                   placeholder="Contoh: Jalan rusak di RT 03/RW 02"
                                   class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all">
                            @error('judul')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Lokasi --}}
                        <div>
                            <label for="lokasi" class="block text-xs font-bold text-gray-700 mb-1">
                                Lokasi Kejadian <span class="text-gray-400 font-normal">(opsional)</span>
                            </label>
                            <div class="relative">
                                <i class="fa-solid fa-location-dot absolute left-3 top-3 text-gray-400"></i>
                                <input type="text" id="lokasi" name="lokasi"
                                       value="{{ old('lokasi') }}"
                                       placeholder="Contoh: Jl. Desa RT 01/RW 03, dekat pos ronda"
                                       class="w-full bg-white border border-gray-300 rounded-xl pl-9 pr-4 py-3 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all">
                            </div>
                        </div>

                        {{-- Isi Pengaduan --}}
                        <div>
                            <label for="isi" class="block text-xs font-bold text-gray-700 mb-1">
                                Isi Pengaduan <span class="text-red-500">*</span>
                            </label>
                            <textarea id="isi" name="isi" rows="5" required
                                      placeholder="Jelaskan permasalahan secara detail agar bisa ditindaklanjuti dengan baik..."
                                      class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all resize-none">{{ old('isi') }}</textarea>
                            @error('isi')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Submit Footer --}}
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <p class="text-xs text-gray-500 text-center sm:text-left">
                            <i class="fa-solid fa-circle-info text-blue-400 mr-1"></i>
                            Pengaduan akan diteruskan ke perangkat desa.
                        </p>
                        <button type="submit"
                                class="inline-flex items-center gap-2 bg-green-700 hover:bg-green-800 text-white font-bold text-sm px-6 py-2.5 rounded-xl transition-colors shadow-md hover:shadow-lg cursor-pointer w-full sm:w-auto justify-center">
                            <i class="fa-solid fa-paper-plane"></i>
                            Kirim Pengaduan
                        </button>
                    </div>
                </form>
            </div>

            {{-- KOLOM KANAN: INFO & GUIDELINES (1 kolom) --}}
            <div class="lg:col-span-1 space-y-6">

                {{-- Data Pelapor --}}
                <section class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 bg-green-700 text-white">
                        <h3 class="font-bold text-sm flex items-center gap-2">
                            <i class="fa-solid fa-user-shield"></i> Identitas Pelapor
                        </h3>
                    </div>
                    <div class="p-5 space-y-3">
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase tracking-wider font-bold">Nama</p>
                            <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $warga->nama }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase tracking-wider font-bold">NIK</p>
                            <p class="text-sm font-semibold text-gray-700 mt-0.5 font-mono">{{ $warga->formattedNik() }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase tracking-wider font-bold">Alamat</p>
                            <p class="text-sm text-gray-700 mt-0.5">{{ $warga->alamatLengkap() }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-3">
                            <p class="text-[10px] text-gray-400 flex items-center gap-1.5">
                                <i class="fa-solid fa-shield-halved text-green-500"></i>
                                Identitas pelapor bersifat <strong class="text-gray-600">rahasia</strong> dan dilindungi.
                            </p>
                        </div>
                    </div>
                </section>

                {{-- Panduan Pengaduan --}}
                <div class="bg-amber-50 border border-amber-100 rounded-2xl p-5">
                    <h4 class="text-sm font-bold text-amber-800 mb-3 flex items-center gap-2">
                        <i class="fa-solid fa-lightbulb text-amber-500"></i> Panduan Pengaduan
                    </h4>
                    <ul class="space-y-2 text-xs text-amber-700">
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-check text-amber-500 mt-0.5 shrink-0"></i>
                            Jelaskan <strong>masalah secara detail</strong> agar mudah ditindaklanjuti
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-check text-amber-500 mt-0.5 shrink-0"></i>
                            Cantumkan <strong>lokasi kejadian</strong> sespesifik mungkin
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-check text-amber-500 mt-0.5 shrink-0"></i>
                            Pengaduan diproses <strong>maksimal 3 hari kerja</strong>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-check text-amber-500 mt-0.5 shrink-0"></i>
                            Status tindak lanjut dapat dipantau melalui <strong>halaman notifikasi</strong>
                        </li>
                    </ul>
                </div>

                {{-- Kontak Darurat --}}
                <div class="bg-red-50 border border-red-100 rounded-2xl p-5">
                    <h4 class="text-sm font-bold text-red-800 mb-2 flex items-center gap-2">
                        <i class="fa-solid fa-phone-volume text-red-500"></i> Hotline Darurat
                    </h4>
                    <p class="text-xs text-red-700 leading-relaxed mb-3">
                        Untuk kejadian darurat/mendesak yang membutuhkan penanganan segera:
                    </p>
                    <div class="flex items-center gap-3 bg-white rounded-xl p-3 border border-red-100">
                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center text-red-500 shrink-0">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-red-800">(0263) 123-456</p>
                            <p class="text-[10px] text-red-500">24 Jam / 7 Hari</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
