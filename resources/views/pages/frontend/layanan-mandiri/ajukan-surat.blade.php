{{--
    Halaman: Ajukan Permohonan Surat (Multi-step Wizard)
    Route: warga.surat.ajukan
    Guard: auth:warga
    Sumber Desain: Permohonan-Surat.html (Google Stitch)
--}}

@extends('layouts.warga')

@section('title', 'Permohonan Surat Baru - Layanan Mandiri Desa Sindangmukti')
@section('meta_description', 'Ajukan permohonan surat keterangan desa secara mandiri.')

@php
    $pageTitle = 'Permohonan Surat';
    $warga = Auth::guard('warga')->user();
@endphp

@section('content')

    <div x-data="{
        step: {{ $errors->any() ? 2 : 1 }},
        searchQuery: '',
        selectedSurat: '{{ old('jenis_surat', '') }}',
        isAgree: false,
    
        suratList: [
            @foreach ($jenisSuratOptions as $key => $label)
            {
                id: '{{ $key }}',
                nama: '{{ $label }}',
                icon: '{{ match ($key) {
                    'domisili' => 'fa-house-user',
                    'sktm' => 'fa-hand-holding-dollar',
                    'pengantar_usaha' => 'fa-shop',
                    'kematian' => 'fa-bed',
                    'pengantar_nikah' => 'fa-ring',
                    'pindah' => 'fa-truck-moving',
                    default => 'fa-file-lines',
                } }}',
                desc: '{{ match ($key) {
                    'domisili' => 'Surat menerangkan tempat tinggal saat ini untuk pengurusan dokumen administrasi.',
                    'sktm' => 'Surat keterangan untuk pengajuan keringanan biaya pendidikan, kesehatan, atau bantuan sosial.',
                    'pengantar_usaha' => 'Surat keterangan kepemilikan usaha/UMKM di wilayah desa.',
                    'kematian' => 'Surat pengantar untuk pengurusan Akta Kematian anggota keluarga.',
                    'pengantar_nikah' => 'Berkas pengantar untuk pendaftaran pernikahan di KUA setempat.',
                    'pindah' => 'Surat keterangan pindah domisili ke wilayah lain.',
                    default => 'Surat keterangan desa.',
                } }}'
            }, @endforeach
        ],
    
        get filteredSuratList() {
            if (this.searchQuery === '') return this.suratList;
            return this.suratList.filter(s =>
                s.nama.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                s.desc.toLowerCase().includes(this.searchQuery.toLowerCase())
            );
        },
    
        get selectedSuratObj() {
            return this.suratList.find(s => s.id === this.selectedSurat) || null;
        },
    
        nextStep() {
            if (this.step < 3) {
                this.step++;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },
        prevStep() {
            if (this.step > 1) {
                this.step--;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        }
    }">

        {{-- Header Halaman & Stepper --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight mb-2">Permohonan Surat Baru</h1>
                <p class="text-sm text-gray-500">Ajukan berbagai keperluan surat pengantar/keterangan desa secara mandiri.
                </p>
            </div>

            {{-- Progress Stepper --}}
            <div class="flex items-center gap-3 sm:gap-4 shrink-0">
                <div class="flex flex-col items-center gap-1">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-colors"
                        :class="step >= 1 ? 'bg-green-600 border-green-600 text-white' :
                            'bg-white border-gray-300 text-gray-400'">
                        1</div>
                    <span class="text-[10px] font-semibold" :class="step >= 1 ? 'text-green-700' : 'text-gray-400'">Pilih
                        Surat</span>
                </div>
                <div class="w-8 sm:w-12 h-0.5 transition-colors mb-4" :class="step >= 2 ? 'bg-green-500' : 'bg-gray-200'">
                </div>
                <div class="flex flex-col items-center gap-1">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-colors"
                        :class="step >= 2 ? 'bg-green-600 border-green-600 text-white' :
                            'bg-white border-gray-300 text-gray-400'">
                        2</div>
                    <span class="text-[10px] font-semibold" :class="step >= 2 ? 'text-green-700' : 'text-gray-400'">Isi
                        Formulir</span>
                </div>
                <div class="w-8 sm:w-12 h-0.5 transition-colors mb-4" :class="step >= 3 ? 'bg-green-500' : 'bg-gray-200'">
                </div>
                <div class="flex flex-col items-center gap-1">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-colors"
                        :class="step >= 3 ? 'bg-green-600 border-green-600 text-white' :
                            'bg-white border-gray-300 text-gray-400'">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <span class="text-[10px] font-semibold"
                        :class="step >= 3 ? 'text-green-700' : 'text-gray-400'">Selesai</span>
                </div>
            </div>
        </div>

        {{-- Success Message --}}
        @if (session('success'))
            <div x-init="step = 3" x-show="step === 3" class="py-12">
                <div
                    class="max-w-md mx-auto bg-white rounded-3xl p-8 border border-gray-100 shadow-lg text-center relative overflow-hidden">
                    <div class="absolute inset-x-0 top-0 h-32 bg-gradient-to-b from-green-50 to-white -z-0"></div>
                    <div class="relative z-10">
                        <div
                            class="w-20 h-20 bg-green-100 text-green-500 rounded-full flex items-center justify-center text-4xl mx-auto mb-6 border-4 border-white shadow-sm">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <h2 class="text-2xl font-extrabold text-gray-900 mb-2">Permohonan Berhasil!</h2>
                        <p class="text-sm text-gray-500 leading-relaxed mb-6">{{ session('success') }}</p>
                        <div class="flex flex-col gap-3">
                            <a href="{{ route('warga.surat.riwayat') }}"
                                class="bg-green-700 hover:bg-green-800 text-white rounded-xl py-3 text-sm font-bold shadow-md transition-all text-center">
                                Lihat Riwayat Permohonan
                            </a>
                            <a href="{{ route('warga.surat.ajukan') }}"
                                class="text-sm font-bold text-gray-500 hover:text-gray-800 py-2">Ajukan Surat Lain</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Error Messages --}}
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 rounded-2xl px-5 py-4 mb-6">
                <p class="font-bold text-sm mb-1 flex items-center gap-2"><i class="fa-solid fa-triangle-exclamation"></i>
                    Mohon periksa kembali:</p>
                <ul class="text-sm list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ═══════════════════════════════════════════════════════
             STEP 1: PILIH KATEGORI SURAT
        ═══════════════════════════════════════════════════════ --}}
        <div x-show="step === 1 && !{{ session('success') ? 'true' : 'false' }}" x-transition.opacity.duration.300ms>

            {{-- Search Bar --}}
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 mb-6 flex items-center relative">
                <i class="fa-solid fa-magnifying-glass absolute left-8 text-gray-400"></i>
                <input type="text" x-model="searchQuery"
                    placeholder="Cari jenis surat yang Anda butuhkan... (Cth: Usaha, SKTM, dll)"
                    class="w-full bg-transparent pl-12 pr-4 py-2 outline-none text-sm text-gray-700">
            </div>

            {{-- Category Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                <template x-for="surat in filteredSuratList" :key="surat.id">
                    <div @click="selectedSurat = surat.id"
                        class="bg-white rounded-2xl p-6 border-2 shadow-sm transition-all duration-300 cursor-pointer group hover:-translate-y-1 hover:shadow-md"
                        :class="selectedSurat === surat.id ? 'border-green-500 bg-green-50/30' :
                            'border-gray-100 hover:border-green-200'">
                        <div class="w-12 h-12 rounded-xl mb-4 flex items-center justify-center text-xl transition-colors"
                            :class="selectedSurat === surat.id ? 'bg-green-500 text-white' :
                                'bg-gray-50 text-gray-500 group-hover:bg-green-100 group-hover:text-green-600'">
                            <i class="fa-solid" :class="surat.icon"></i>
                        </div>
                        <h3 class="font-bold text-gray-900 mb-2" x-text="surat.nama"></h3>
                        <p class="text-xs text-gray-500 leading-relaxed" x-text="surat.desc"></p>
                        <div class="mt-4 pt-4 border-t border-gray-50 flex justify-between items-center">
                            <span class="text-[10px] font-semibold text-gray-400 flex items-center gap-1">
                                <i class="fa-solid fa-clock"></i> 1-2 Hari Kerja
                            </span>
                            <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs transition-colors"
                                :class="selectedSurat === surat.id ? 'bg-green-500 text-white' :
                                    'bg-gray-100 text-gray-400 group-hover:bg-green-100 group-hover:text-green-600'">
                                <i class="fa-solid fa-arrow-right"></i>
                            </span>
                        </div>
                    </div>
                </template>

                {{-- Empty State --}}
                <div x-show="filteredSuratList.length === 0" class="col-span-full py-12 text-center" x-cloak>
                    <div
                        class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-300 mx-auto mb-3">
                        <i class="fa-solid fa-file-circle-question text-2xl"></i>
                    </div>
                    <p class="font-semibold text-gray-600">Surat tidak ditemukan</p>
                    <p class="text-xs text-gray-400 mt-1">Coba gunakan kata kunci lain.</p>
                </div>
            </div>

            {{-- Step 1 Actions --}}
            <div class="mt-8 flex justify-end">
                <button @click="nextStep()" :disabled="!selectedSurat"
                    class="bg-green-700 hover:bg-green-800 text-white px-6 py-3 rounded-xl font-bold text-sm shadow-md transition-all flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    Lanjutkan Isi Formulir <i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════
             STEP 2: FORMULIR + SUBMIT
        ═══════════════════════════════════════════════════════ --}}
        <div x-show="step === 2 && !{{ session('success') ? 'true' : 'false' }}" x-transition.opacity.duration.300ms
            x-cloak>

            {{-- Tombol Kembali --}}
            <button @click="prevStep()"
                class="text-sm font-semibold text-gray-500 hover:text-gray-800 mb-6 flex items-center gap-2 transition-colors">
                <i class="fa-solid fa-arrow-left"></i> Ganti Jenis Surat
            </button>

            <form action="{{ route('warga.surat.store') }}" method="POST" enctype="multipart/form-data"
                class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
                @csrf
                <input type="hidden" name="jenis_surat" :value="selectedSurat">

                {{-- KOLOM KIRI: FORM DATA --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Data Pemohon (Auto-filled) --}}
                    <section class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <header class="px-5 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                            <h3 class="font-bold text-gray-800 text-sm uppercase tracking-wide">Data Pemohon</h3>
                            <span
                                class="text-[10px] bg-blue-100 text-blue-700 font-bold px-2 py-0.5 rounded uppercase">Auto-filled</span>
                        </header>
                        <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1">Nama Lengkap</label>
                                <input type="text" value="{{ $warga->nama }}" readonly
                                    class="w-full bg-gray-50 border border-transparent rounded-lg px-3 py-2 text-sm text-gray-700 outline-none cursor-not-allowed">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1">NIK</label>
                                <input type="text" value="{{ $warga->formattedNik() }}" readonly
                                    class="w-full bg-gray-50 border border-transparent rounded-lg px-3 py-2 text-sm text-gray-700 outline-none cursor-not-allowed font-mono">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1">Nomor KK</label>
                                <input type="text" value="{{ $warga->no_kk }}" readonly
                                    class="w-full bg-gray-50 border border-transparent rounded-lg px-3 py-2 text-sm text-gray-700 outline-none cursor-not-allowed font-mono">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1">Tempat, Tgl Lahir</label>
                                <input type="text"
                                    value="{{ $warga->tempat_lahir }}, {{ $warga->tanggal_lahir?->format('d-m-Y') ?? '-' }}"
                                    readonly
                                    class="w-full bg-gray-50 border border-transparent rounded-lg px-3 py-2 text-sm text-gray-700 outline-none cursor-not-allowed">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-gray-500 mb-1">Alamat Sesuai KTP</label>
                                <input type="text" value="{{ $warga->alamatLengkap() }}" readonly
                                    class="w-full bg-gray-50 border border-transparent rounded-lg px-3 py-2 text-sm text-gray-700 outline-none cursor-not-allowed">
                            </div>
                        </div>
                    </section>

                    {{-- Detail Surat --}}
                    <section
                        class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden border-t-4 border-t-green-500">
                        <header class="px-5 py-4 border-b border-gray-100 bg-white">
                            <h3 class="font-bold text-gray-800 text-sm uppercase tracking-wide">Detail <span
                                    x-text="selectedSuratObj?.nama ?? ''"></span></h3>
                            <p class="text-xs text-gray-500 mt-1">Lengkapi form berikut sesuai kebutuhan surat Anda.</p>
                        </header>
                        <div class="p-5 space-y-5">

                            {{-- Dynamic: Nama Usaha (pengantar_usaha) --}}
                            <div x-show="selectedSurat === 'pengantar_usaha'" x-collapse x-cloak>
                                <label for="nama_usaha" class="block text-xs font-bold text-gray-700 mb-1">Nama Usaha/Toko
                                    <span class="text-red-500">*</span></label>
                                <input type="text" id="nama_usaha" name="nama_usaha" value="{{ old('nama_usaha') }}"
                                    placeholder="Contoh: Warung Berkah"
                                    class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none">
                                @error('nama_usaha')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Dynamic: Berlaku Hingga (domisili, sktm) --}}
                            <div x-show="['domisili', 'sktm'].includes(selectedSurat)" x-collapse x-cloak>
                                <label for="berlaku_hingga" class="block text-xs font-bold text-gray-700 mb-1">Berlaku
                                    Hingga (Opsional)</label>
                                <input type="date" id="berlaku_hingga" name="berlaku_hingga"
                                    value="{{ old('berlaku_hingga') }}" min="{{ date('Y-m-d') }}"
                                    class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none">
                            </div>

                            {{-- Tujuan / Keperluan (Selalu) --}}
                            <div class="border-t border-gray-100 pt-5 mt-2 space-y-4">
                                <div>
                                    <label for="keperluan" class="block text-xs font-bold text-gray-700 mb-1">Tujuan /
                                        Keperluan Surat <span class="text-red-500">*</span></label>
                                    <input type="text" id="keperluan" name="keperluan"
                                        value="{{ old('keperluan') }}" required
                                        placeholder="Contoh: Syarat pendaftaran sekolah, pengajuan kredit, dll"
                                        class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none">
                                    @error('keperluan')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="catatan" class="block text-xs font-bold text-gray-700 mb-1">Catatan
                                        Tambahan (Opsional)</label>
                                    <textarea id="catatan" name="catatan" rows="2"
                                        class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none placeholder-gray-300"
                                        placeholder="Pesan tambahan untuk petugas desa (jika ada)">{{ old('catatan') }}</textarea>
                                </div>
                            </div>

                            {{-- Prioritas --}}
                            <div class="border-t border-gray-100 pt-5">
                                <label class="block text-xs font-bold text-gray-700 mb-2">Prioritas Permohonan</label>
                                <div class="flex gap-3">
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="prioritas" value="normal"
                                            {{ old('prioritas', 'normal') === 'normal' ? 'checked' : '' }}
                                            class="sr-only peer">
                                        <div
                                            class="flex items-center gap-2 border-2 border-gray-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 rounded-xl p-3 transition-all">
                                            <i class="fa-solid fa-circle-dot text-blue-400 text-sm"></i>
                                            <div>
                                                <p class="text-sm font-bold text-gray-700">Normal</p>
                                                <p class="text-[10px] text-gray-500">1-2 hari kerja</p>
                                            </div>
                                        </div>
                                    </label>
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="prioritas" value="tinggi"
                                            {{ old('prioritas') === 'tinggi' ? 'checked' : '' }} class="sr-only peer">
                                        <div
                                            class="flex items-center gap-2 border-2 border-gray-200 peer-checked:border-red-500 peer-checked:bg-red-50 rounded-xl p-3 transition-all">
                                            <i class="fa-solid fa-circle-exclamation text-red-400 text-sm"></i>
                                            <div>
                                                <p class="text-sm font-bold text-gray-700">Mendesak</p>
                                                <p class="text-[10px] text-gray-500">Keperluan urgent</p>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- Upload Lampiran (GAP-08) --}}
                    <section class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <header class="px-5 py-4 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="font-bold text-gray-800 text-sm uppercase tracking-wide">Lampiran Pendukung</h3>
                            <p class="text-xs text-gray-500 mt-1">Upload dokumen pendukung (opsional). Maks. 5 file, ukuran
                                maks. 2MB per file.</p>
                        </header>
                        <div class="p-5">
                            <div x-data="{ files: [] }" class="space-y-3">
                                <label
                                    class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 hover:border-green-400 transition-all">
                                    <div class="flex flex-col items-center gap-1 text-gray-500">
                                        <i class="fa-solid fa-cloud-arrow-up text-xl text-gray-400"></i>
                                        <span class="text-xs font-semibold">Klik untuk upload atau drag & drop</span>
                                        <span class="text-[10px] text-gray-400">PDF, JPG, PNG — Maks. 2MB</span>
                                    </div>
                                    <input type="file" name="lampiran[]" multiple accept=".pdf,.jpg,.jpeg,.png"
                                        class="hidden" @change="files = Array.from($event.target.files)">
                                </label>

                                {{-- File list preview --}}
                                <template x-if="files.length > 0">
                                    <div class="space-y-2">
                                        <template x-for="(file, idx) in files" :key="idx">
                                            <div class="flex items-center gap-3 bg-gray-50 rounded-lg px-3 py-2 text-sm">
                                                <i class="fa-solid fa-file text-gray-400"></i>
                                                <span class="flex-1 truncate text-gray-700" x-text="file.name"></span>
                                                <span class="text-[10px] text-gray-400 shrink-0"
                                                    x-text="(file.size / 1024).toFixed(0) + ' KB'"></span>
                                            </div>
                                        </template>
                                    </div>
                                </template>

                                @error('lampiran')
                                    <p class="text-red-500 text-xs">{{ $message }}</p>
                                @enderror
                                @error('lampiran.*')
                                    <p class="text-red-500 text-xs">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </section>

                </div>

                {{-- KOLOM KANAN: SUBMIT --}}
                <div class="lg:col-span-1 space-y-6">

                    {{-- Ringkasan Pengajuan --}}
                    <section class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden sticky top-6">
                        <div class="p-5 bg-gray-50/50 border-b border-gray-100">
                            <h3 class="font-bold text-gray-800 text-sm">Ringkasan Pengajuan</h3>
                        </div>
                        <div class="p-5 space-y-4">
                            <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                                <span class="text-xs text-gray-500">Jenis Surat</span>
                                <span class="text-xs font-bold text-gray-900 text-right w-1/2 leading-tight"
                                    x-text="selectedSuratObj?.nama ?? '-'"></span>
                            </div>
                            <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                                <span class="text-xs text-gray-500">Pemohon</span>
                                <span class="text-xs font-bold text-gray-900">{{ $warga->nama }}</span>
                            </div>

                            {{-- Checkbox Persetujuan --}}
                            <label class="flex items-start gap-3 mt-4 cursor-pointer group">
                                <div class="relative flex items-center shrink-0 mt-0.5">
                                    <input type="checkbox" x-model="isAgree"
                                        class="peer h-4 w-4 cursor-pointer appearance-none rounded border border-gray-300 checked:border-green-600 checked:bg-green-600 transition-all">
                                    <i
                                        class="fa-solid fa-check absolute text-white text-[10px] left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 opacity-0 peer-checked:opacity-100 pointer-events-none"></i>
                                </div>
                                <span
                                    class="text-[10px] leading-relaxed text-gray-600 group-hover:text-gray-900 select-none">
                                    Saya menyatakan bahwa data dan dokumen yang saya kirimkan adalah benar dan sah sesuai
                                    hukum.
                                </span>
                            </label>

                            {{-- Submit Buttons --}}
                            <div class="pt-4 space-y-2">
                                <button type="submit" :disabled="!isAgree"
                                    class="w-full bg-green-700 hover:bg-green-800 text-white rounded-xl py-3 text-sm font-bold shadow-md transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-paper-plane"></i> Kirim Permohonan
                                </button>
                            </div>
                        </div>
                    </section>

                    {{-- Ketentuan --}}
                    <div class="bg-amber-50 border border-amber-100 rounded-2xl p-5">
                        <h4 class="text-sm font-bold text-amber-800 mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-circle-info text-amber-500"></i> Ketentuan
                        </h4>
                        <ul class="space-y-2 text-xs text-amber-700">
                            <li class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-amber-500 mt-0.5 shrink-0"></i>
                                Surat diproses dalam <strong>1–2 hari kerja</strong>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-amber-500 mt-0.5 shrink-0"></i>
                                Notifikasi dikirim via portal saat surat selesai
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-amber-500 mt-0.5 shrink-0"></i>
                                Dokumen dapat diunduh setelah disetujui Kades
                            </li>
                        </ul>
                    </div>
                </div>
            </form>
        </div>

    </div>

@endsection
