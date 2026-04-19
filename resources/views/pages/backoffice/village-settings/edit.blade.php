@extends('layouts.backoffice')

@section('title', 'Pengaturan Identitas Desa - Panel Administrasi')

@section('content')

{{-- Alpine.js State Manager --}}
<div x-data="{
        formData: {
            namaDesa: '{{ $settings->nama_desa }}',
            kecamatan: '{{ $settings->kecamatan }}',
            kabupaten: '{{ $settings->kabupaten }}',
            provinsi: '{{ $settings->provinsi }}',
            kodePos: '{{ $settings->kode_pos }}',
            email: '{{ $settings->email }}',
            telepon: '{{ $settings->telepon }}',
            website: '{{ $settings->website }}',
            alamat: '{{ $settings->alamat }}',
            namaKades: '{{ $settings->nama_kades }}',
            nipKades: '{{ $settings->nip_kades }}',
            jabatanKades: '{{ $settings->jabatan_kades }}',
            logoUrl: '{{ $settings->logoUrl() }}',
        },
        logoPreview: null,

        handleLogoPreview(event) {
            const file = event.target.files[0];
            if (file) {
                this.logoPreview = URL.createObjectURL(file);
            }
        },
     }"
     class="space-y-6">

    {{-- Flash Messages --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="bg-green-50 border border-green-200 text-green-800 rounded-2xl p-4 flex items-start gap-3 shadow-sm">
            <i class="fa-solid fa-circle-check text-green-600 mt-0.5"></i>
            <div class="flex-1">
                <p class="text-sm font-semibold">{{ session('success') }}</p>
            </div>
            <button @click="show = false" class="text-green-400 hover:text-green-600 cursor-pointer">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-2xl p-4 shadow-sm">
            <div class="flex items-center gap-2 mb-2">
                <i class="fa-solid fa-triangle-exclamation text-red-500"></i>
                <p class="text-sm font-bold">Terdapat kesalahan pada formulir:</p>
            </div>
            <ul class="list-disc list-inside text-sm space-y-1 ml-6">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Page Header --}}
    <section class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Pengaturan Identitas Desa</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola profil resmi desa, logo, dan pimpinan yang akan terintegrasi ke seluruh dokumen sistem.</p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <button type="submit" form="villageSettingsForm"
                class="bg-green-700 hover:bg-green-800 text-white shadow-md hover:shadow-lg rounded-xl px-6 py-2.5 text-sm font-bold transition-all flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-save"></i>
                <span>Simpan Perubahan</span>
            </button>
        </div>
    </section>

    {{-- Main Form --}}
    <form id="villageSettingsForm"
          action="{{ route('admin.village-settings.update') }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- LEFT COLUMN: Form Inputs --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Informasi Administratif --}}
                <section class="bg-white border border-gray-100 shadow-sm rounded-2xl p-6">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                        <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                            <i class="fa-solid fa-map-location-dot"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">Informasi Administratif</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Nama, alamat, dan wilayah pemerintahan.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        {{-- Nama Desa --}}
                        <div class="space-y-1 sm:col-span-2">
                            <label for="nama_desa" class="text-sm font-bold text-gray-700">Nama Desa <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium">Desa</span>
                                <input type="text" id="nama_desa" name="nama_desa"
                                       x-model="formData.namaDesa"
                                       value="{{ old('nama_desa', $settings->nama_desa) }}"
                                       class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-14 pr-4 py-2.5 text-sm font-semibold text-gray-900 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all"
                                       required>
                            </div>
                        </div>

                        {{-- Kecamatan --}}
                        <div class="space-y-1">
                            <label for="kecamatan" class="text-sm font-bold text-gray-700">Kecamatan <span class="text-red-500">*</span></label>
                            <input type="text" id="kecamatan" name="kecamatan"
                                   x-model="formData.kecamatan"
                                   value="{{ old('kecamatan', $settings->kecamatan) }}"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all"
                                   required>
                        </div>

                        {{-- Kabupaten --}}
                        <div class="space-y-1">
                            <label for="kabupaten" class="text-sm font-bold text-gray-700">Kabupaten / Kota <span class="text-red-500">*</span></label>
                            <input type="text" id="kabupaten" name="kabupaten"
                                   x-model="formData.kabupaten"
                                   value="{{ old('kabupaten', $settings->kabupaten) }}"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all"
                                   required>
                        </div>

                        {{-- Provinsi --}}
                        <div class="space-y-1">
                            <label for="provinsi" class="text-sm font-bold text-gray-700">Provinsi <span class="text-red-500">*</span></label>
                            <input type="text" id="provinsi" name="provinsi"
                                   x-model="formData.provinsi"
                                   value="{{ old('provinsi', $settings->provinsi) }}"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all"
                                   required>
                        </div>

                        {{-- Kode Pos --}}
                        <div class="space-y-1">
                            <label for="kode_pos" class="text-sm font-bold text-gray-700">Kode Pos <span class="text-red-500">*</span></label>
                            <input type="text" id="kode_pos" name="kode_pos"
                                   x-model="formData.kodePos"
                                   value="{{ old('kode_pos', $settings->kode_pos) }}"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all"
                                   required>
                        </div>

                        {{-- Alamat --}}
                        <div class="space-y-1 sm:col-span-2">
                            <label for="alamat" class="text-sm font-bold text-gray-700">Alamat Lengkap Kantor <span class="text-red-500">*</span></label>
                            <textarea id="alamat" name="alamat" rows="2"
                                      x-model="formData.alamat"
                                      class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all resize-none"
                                      required>{{ old('alamat', $settings->alamat) }}</textarea>
                        </div>
                    </div>
                </section>

                {{-- Kontak & Pejabat --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Kontak Resmi --}}
                    <section class="bg-white border border-gray-100 shadow-sm rounded-2xl p-6">
                        <div class="flex items-center gap-3 mb-5 border-b border-gray-50 pb-3">
                            <div class="w-8 h-8 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center">
                                <i class="fa-solid fa-address-book"></i>
                            </div>
                            <h3 class="font-bold text-gray-900">Kontak Resmi</h3>
                        </div>
                        <div class="space-y-4">
                            <div class="space-y-1">
                                <label for="email" class="text-xs font-bold text-gray-700">Email Instansi</label>
                                <div class="relative">
                                    <i class="fa-solid fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                    <input type="email" id="email" name="email"
                                           x-model="formData.email"
                                           value="{{ old('email', $settings->email) }}"
                                           class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all">
                                </div>
                            </div>
                            <div class="space-y-1">
                                <label for="telepon" class="text-xs font-bold text-gray-700">No. Telepon / HP</label>
                                <div class="relative">
                                    <i class="fa-solid fa-phone absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                    <input type="text" id="telepon" name="telepon"
                                           x-model="formData.telepon"
                                           value="{{ old('telepon', $settings->telepon) }}"
                                           class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all">
                                </div>
                            </div>
                            <div class="space-y-1">
                                <label for="website" class="text-xs font-bold text-gray-700">Website</label>
                                <div class="relative">
                                    <i class="fa-solid fa-globe absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                    <input type="text" id="website" name="website"
                                           x-model="formData.website"
                                           value="{{ old('website', $settings->website) }}"
                                           class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all">
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- Pejabat Utama --}}
                    <section class="bg-white border border-gray-100 shadow-sm rounded-2xl p-6">
                        <div class="flex items-center gap-3 mb-5 border-b border-gray-50 pb-3">
                            <div class="w-8 h-8 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center">
                                <i class="fa-solid fa-user-tie"></i>
                            </div>
                            <h3 class="font-bold text-gray-900">Pejabat Utama (Kades)</h3>
                        </div>
                        <div class="space-y-4">
                            <div class="space-y-1">
                                <label for="nama_kades" class="text-xs font-bold text-gray-700">Nama Lengkap (Termasuk Gelar)</label>
                                <input type="text" id="nama_kades" name="nama_kades"
                                       x-model="formData.namaKades"
                                       value="{{ old('nama_kades', $settings->nama_kades) }}"
                                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all font-semibold text-gray-900">
                            </div>
                            <div class="space-y-1">
                                <label for="nip_kades" class="text-xs font-bold text-gray-700">NIP / No. Identitas</label>
                                <input type="text" id="nip_kades" name="nip_kades"
                                       x-model="formData.nipKades"
                                       value="{{ old('nip_kades', $settings->nip_kades) }}"
                                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all">
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-bold text-gray-700">Jabatan Formal</label>
                                <input type="text" value="{{ $settings->jabatan_kades }}"
                                       class="w-full bg-gray-100 border border-gray-200 rounded-xl px-4 py-2 text-sm text-gray-500 cursor-not-allowed outline-none"
                                       readonly>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            {{-- RIGHT COLUMN: Media & Preview --}}
            <div class="lg:col-span-1 space-y-6">

                {{-- Logo Upload --}}
                <section class="bg-white border border-gray-100 shadow-sm rounded-2xl p-6">
                    <h3 class="font-bold text-gray-900 mb-1">Logo Instansi</h3>
                    <p class="text-xs text-gray-500 mb-4">Logo resmi yang akan muncul di kop surat.</p>

                    <label for="logo_input" class="cursor-pointer block">
                        <div class="flex items-center justify-center bg-gray-50 border border-gray-200 border-dashed rounded-2xl p-6 mb-4 relative group">
                            <img :src="logoPreview || formData.logoUrl"
                                 alt="Logo Desa"
                                 class="w-24 h-24 object-contain filter drop-shadow-md">
                            <div class="absolute inset-0 bg-gray-900/60 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="text-white text-xs font-bold flex items-center gap-1.5">
                                    <i class="fa-solid fa-camera"></i> Ganti Logo
                                </span>
                            </div>
                        </div>
                    </label>
                    <input type="file" id="logo_input" name="logo" accept="image/*" class="hidden"
                           @change="handleLogoPreview($event)">

                    <p class="text-[10px] text-gray-400 text-center">Format: PNG/JPG/SVG/WebP. Maks: 2MB.</p>

                    <hr class="border-gray-100 my-5">

                    {{-- Banner Upload --}}
                    <h3 class="font-bold text-gray-900 mb-1 text-sm">Banner Utama Website</h3>
                    <p class="text-xs text-gray-500 mb-3">Gambar sampul (<em>cover</em>) untuk halaman publik.</p>

                    <label for="banner_input" class="cursor-pointer block">
                        <div class="w-full h-24 bg-gray-100 rounded-xl border border-gray-200 border-dashed flex flex-col items-center justify-center text-gray-400 hover:bg-green-50 hover:text-green-600 hover:border-green-300 transition-colors group overflow-hidden">
                            @if ($settings->bannerUrl())
                                <img src="{{ $settings->bannerUrl() }}" alt="Banner" class="w-full h-full object-cover">
                            @else
                                <i class="fa-solid fa-cloud-arrow-up text-xl mb-1 group-hover:-translate-y-1 transition-transform"></i>
                                <span class="text-xs font-medium">Klik untuk upload foto</span>
                            @endif
                        </div>
                    </label>
                    <input type="file" id="banner_input" name="banner" accept="image/*" class="hidden">
                </section>

                {{-- Live Preview: Kop Surat --}}
                <section class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden flex flex-col">
                    <div class="bg-gray-800 p-3 flex justify-between items-center shrink-0">
                        <span class="text-white text-xs font-bold flex items-center gap-1.5">
                            <i class="fa-solid fa-eye text-green-400"></i> Live Preview: Kop Surat
                        </span>
                    </div>

                    <div class="bg-gray-100 p-4 sm:p-6 flex-1 flex items-center justify-center">
                        <div class="bg-white w-full max-w-[350px] shadow-lg rounded p-4 relative" style="font-family: 'Times New Roman', serif;">

                            {{-- Kop Header --}}
                            <div class="flex items-start gap-3 border-b-2 border-black pb-3 relative">
                                <div class="absolute bottom-[2px] left-0 w-full border-b border-black"></div>

                                <div class="w-12 h-14 shrink-0 flex items-center justify-center">
                                    <img :src="logoPreview || formData.logoUrl" alt="Logo" class="max-w-full max-h-full">
                                </div>

                                <div class="flex-1 text-center pr-2">
                                    <p class="text-[10px] font-bold uppercase leading-tight"
                                       x-text="'PEMERINTAH KABUPATEN ' + formData.kabupaten.toUpperCase()"></p>
                                    <p class="text-[10px] font-bold uppercase leading-tight"
                                       x-text="'KECAMATAN ' + formData.kecamatan.toUpperCase()"></p>
                                    <p class="text-sm font-bold uppercase leading-tight mt-0.5 tracking-wide"
                                       x-text="'DESA ' + formData.namaDesa.toUpperCase()"></p>
                                    <p class="text-[7px] leading-tight mt-1"
                                       x-text="formData.alamat + ', Kode Pos ' + formData.kodePos"></p>
                                    <p class="text-[7px] leading-tight"
                                       x-text="'Telp: ' + formData.telepon + ' | Email: ' + formData.email"></p>
                                </div>
                            </div>

                            {{-- Dummy Letter Body --}}
                            <div class="mt-4 text-center">
                                <p class="text-[9px] font-bold underline">SURAT KETERANGAN</p>
                                <p class="text-[7px]">Nomor: 470/___/Desa/2026</p>
                            </div>

                            <div class="mt-4 mb-8">
                                <div class="h-1.5 bg-gray-200 rounded w-full mb-1"></div>
                                <div class="h-1.5 bg-gray-200 rounded w-5/6 mb-1"></div>
                                <div class="h-1.5 bg-gray-200 rounded w-full mb-1"></div>
                                <div class="h-1.5 bg-gray-200 rounded w-4/6"></div>
                            </div>

                            {{-- Signature --}}
                            <div class="flex justify-end mt-6">
                                <div class="text-center w-1/2">
                                    <p class="text-[8px] mb-6"
                                       x-text="formData.namaDesa + ', {{ now()->translatedFormat('d F Y') }}'"></p>
                                    <p class="text-[9px] font-bold underline uppercase"
                                       x-text="formData.namaKades"></p>
                                    <p class="text-[7px]"
                                       x-text="'NIP. ' + formData.nipKades"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </form>

</div>

@endsection
