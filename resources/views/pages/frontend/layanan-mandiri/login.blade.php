{{--
    Login Layanan Mandiri Warga — Desa Sindangmukti
    Auth terpisah: menggunakan guard 'warga' (NIK + PIN)
--}}

@extends('layouts.frontend')

@section('title', 'Login Layanan Mandiri - Desa Sindangmukti')

@push('styles')
    <style>
        /* Custom input focus ring untuk warna hijau desa */
        .input-focus-ring:focus {
            box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.2);
        }

        /* Menghilangkan panah atas-bawah pada input number */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
@endpush

@section('content')

    <section class="flex-grow flex items-center py-10 md:py-16 mt-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center">

                {{-- ══════════════════════════════════════════════════════════
                 KOLOM KIRI: FORM LOGIN WARGA
                 ══════════════════════════════════════════════════════════ --}}
                <section class="order-2 lg:order-1">
                    <article class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">

                        {{-- Header Form --}}
                        <div class="px-8 pt-8 pb-6 text-center border-b border-gray-100">
                            <div
                                class="w-16 h-16 bg-green-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-green-600 border border-green-100 shadow-sm">
                                <i class="fa-solid fa-fingerprint text-3xl"></i>
                            </div>
                            <h1 class="text-2xl font-bold text-gray-900">Login Layanan Mandiri</h1>
                            <p class="text-sm text-gray-500 mt-2">
                                Gunakan NIK dan PIN yang telah didaftarkan di Kantor Desa.
                            </p>
                        </div>

                        {{-- Session Status --}}
                        @if (session('status'))
                            <div
                                class="mx-8 mt-6 text-sm text-green-600 bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-center">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{-- Validation Errors --}}
                        @if ($errors->any())
                            <div
                                class="mx-8 mt-6 text-sm text-red-600 bg-red-50 border border-red-200 rounded-xl px-4 py-3">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Body Form --}}
                        <form action="{{ route('layanan.mandiri.authenticate') }}" method="POST" class="p-8"
                            id="wargaLoginForm" x-data="{ showPin: false }">
                            @csrf

                            {{-- Input NIK --}}
                            <div class="mb-5">
                                <label for="nik" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nomor Induk Kependudukan (NIK)
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fa-regular fa-id-card text-gray-400"></i>
                                    </div>
                                    <input type="text" id="nik" name="nik" inputmode="numeric" maxlength="16"
                                        pattern="[0-9]{16}" value="{{ old('nik') }}"
                                        class="input-focus-ring w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl
                                           focus:border-green-500 block p-3.5 pl-11 transition-all outline-none font-medium tracking-wider"
                                        placeholder="Masukkan 16 digit NIK" required autofocus
                                        x-on:input="$el.value = $el.value.replace(/[^0-9]/g, '')" />
                                </div>
                            </div>

                            {{-- Input PIN --}}
                            <div class="mb-6">
                                <label for="pin" class="block text-sm font-semibold text-gray-700 mb-2">
                                    PIN Keamanan
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fa-solid fa-lock text-gray-400"></i>
                                    </div>
                                    <input :type="showPin ? 'text' : 'password'" id="pin" name="pin"
                                        inputmode="numeric" maxlength="6"
                                        class="input-focus-ring w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl
                                           focus:border-green-500 block p-3.5 pl-11 pr-11 transition-all outline-none font-bold tracking-[0.25em]"
                                        placeholder="••••••" required
                                        x-on:input="$el.value = $el.value.replace(/[^0-9]/g, '')" />
                                    {{-- Toggle Show PIN --}}
                                    <button type="button" @click="showPin = !showPin"
                                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400
                                           hover:text-green-600 transition-colors focus:outline-none cursor-pointer"
                                        :aria-label="showPin ? 'Sembunyikan PIN' : 'Tampilkan PIN'">
                                        <i :class="showPin ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- Bantuan Login (Lupa PIN) --}}
                            <div class="flex items-center justify-end mb-8">
                                <a href="#bantuan"
                                    class="text-sm font-semibold text-green-600 hover:text-green-800 hover:underline transition-colors flex items-center gap-1">
                                    <i class="fa-regular fa-circle-question"></i> Lupa PIN?
                                </a>
                            </div>

                            {{-- CTA Login Button --}}
                            <button type="submit" id="wargaLoginBtn"
                                class="w-full text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none
                                   focus:ring-green-300 font-bold rounded-xl text-sm px-5 py-4 text-center shadow-lg
                                   hover:shadow-xl transition-all flex items-center justify-center gap-2 group cursor-pointer">
                                Masuk Layanan
                                <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </button>
                        </form>

                        {{-- Footer Card (Informasi Registrasi) --}}
                        <div class="bg-gray-50 px-8 py-5 border-t border-gray-100 text-center">
                            <p class="text-sm text-gray-600">
                                Belum memiliki akun layanan? <br class="sm:hidden">
                                <a href="{{ route('layanan') }}" class="font-bold text-green-700 hover:underline ml-1">
                                    Aktivasi di Kantor Desa
                                </a>
                            </p>
                        </div>
                    </article>
                </section>

                {{-- ══════════════════════════════════════════════════════════
                 KOLOM KANAN: INFORMASI KEAMANAN & EDUKASI
                 ══════════════════════════════════════════════════════════ --}}
                <section class="order-1 lg:order-2 space-y-6">

                    {{-- Hero Text --}}
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">
                            Portal Layanan Mandiri Desa Sindangmukti
                        </h2>
                        <p class="text-gray-600 leading-relaxed mb-6">
                            Layanan Mandiri memungkinkan warga untuk mengurus administrasi desa, mencetak surat keterangan,
                            dan mengakses data kependudukan secara <em>online</em> dari rumah 24/7.
                        </p>
                    </div>

                    {{-- Informasi Keamanan Data --}}
                    <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6 flex gap-4 items-start shadow-sm">
                        <div
                            class="bg-blue-100 text-blue-600 w-12 h-12 rounded-full flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-shield-halved text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-blue-900 mb-1">Keamanan Data Terjamin</h3>
                            <p class="text-sm text-blue-800/80 leading-relaxed">
                                Koneksi diamankan dengan enkripsi SSL. Kami tidak pernah meminta PIN Anda melalui
                                telepon atau pesan WhatsApp. Jaga kerahasiaan PIN Anda.
                            </p>
                        </div>
                    </div>

                    {{-- List Manfaat Layanan --}}
                    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                        <h3 class="font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100">
                            Fasilitas Layanan Mandiri:
                        </h3>
                        <ul class="space-y-3">
                            @php
                                $fasilitas = [
                                    'Cetak Surat Keterangan (Domisili, Usaha, SKTM)',
                                    'Cek Status Bantuan Sosial Terpadu',
                                    'Update Data Profil Keluarga',
                                    'Lacak Riwayat Pengajuan Surat',
                                ];
                            @endphp
                            @foreach ($fasilitas as $item)
                                <li class="flex items-center gap-3 text-sm text-gray-600">
                                    <div
                                        class="w-6 h-6 rounded-full bg-green-100 text-green-600 flex items-center justify-center shrink-0">
                                        <i class="fa-solid fa-check text-[10px]"></i>
                                    </div>
                                    {{ $item }}
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Bantuan Login --}}
                    <div id="bantuan"
                        class="bg-amber-50 border border-amber-100 rounded-2xl p-6 flex gap-4 items-start shadow-sm mt-4">
                        <div
                            class="bg-amber-100 text-amber-600 w-12 h-12 rounded-full flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-headset text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-amber-900 mb-1">Butuh Bantuan Login?</h3>
                            <p class="text-sm text-amber-800/80 leading-relaxed mb-3">
                                Jika Anda lupa PIN atau mengalami kendala saat login, silakan hubungi Operator Desa via
                                WhatsApp atau datang langsung ke Kantor Desa membawa e-KTP.
                            </p>
                            <a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer"
                                class="inline-flex items-center text-sm font-bold text-amber-700 hover:text-amber-900 hover:underline transition-colors">
                                <i class="fa-brands fa-whatsapp mr-1.5 text-lg"></i> Hubungi Operator CS
                            </a>
                        </div>
                    </div>

                </section>

            </div>
        </div>
    </section>

@endsection
