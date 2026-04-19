@extends('layouts.frontend')

@section('title', 'BPD & Lembaga Desa - Desa Sindangmukti')

@section('content')
<div class="pt-24 bg-gray-50 text-gray-800 antialiased" x-data="{ tab: 'pemerintahan', openAccordion: null }">

    <!-- HEADER HALAMAN (Breadcrumb) -->
    <section class="py-12 bg-white border-b border-gray-200">
        <div class="px-6 mx-auto max-w-7xl">
            <nav class="flex mb-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 text-sm text-gray-500 md:space-x-2">
                    <li class="inline-flex items-center">
                        <a href="{{ url('/') }}" class="inline-flex items-center transition-colors hover:text-[#2e7d32]">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            Beranda
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 md:ml-2">Profil Desa</span>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 font-medium text-gray-800 md:ml-2">BPD & Lembaga</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-3xl font-bold md:text-4xl text-[#2e7d32]">
                BPD & Lembaga Desa
            </h1>
            <p class="mt-2 text-lg text-gray-600">
                Struktur kelembagaan yang berperan dalam pembangunan dan
                pelayanan masyarakat Desa Sindangmukti.
            </p>
        </div>
    </section>

    <!-- NAVIGASI TAB -->
    <section class="py-16">
        <div class="px-6 mx-auto max-w-7xl lg:px-8">
            <div class="flex justify-center mb-10 border-b border-gray-200">
                <button @click="tab = 'pemerintahan'"
                    :class="{ 'border-[#2e7d32] text-[#2e7d32]': tab === 'pemerintahan', 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': tab !== 'pemerintahan' }"
                    class="flex items-center gap-2 px-1 py-4 -mb-px text-lg font-medium transition-colors duration-200 border-b-2">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M15.75 1.5a.75.75 0 0 0-.75.75v4.5a.75.75 0 0 0 1.5 0v-4.5a.75.75 0 0 0-.75-.75Z" clip-rule="evenodd" />
                        <path fill-rule="evenodd" d="M12.75 4.5a.75.75 0 0 1 .75-.75h.75a.75.75 0 0 1 0 1.5h-.75a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
                        <path fill-rule="evenodd" d="M.75 4.5A.75.75 0 0 1 1.5 3.75H12a.75.75 0 0 1 0 1.5H1.5A.75.75 0 0 1 .75 4.5Z" clip-rule="evenodd" />
                        <path fill-rule="evenodd" d="M4.25 7.5a.75.75 0 0 0 0 1.5h14.5a.75.75 0 0 0 0-1.5H4.25Z" clip-rule="evenodd" />
                        <path fill-rule="evenodd" d="M6.25 10.5a.75.75 0 0 0 0 1.5h12.5a.75.75 0 0 0 0-1.5H6.25Z" clip-rule="evenodd" />
                        <path fill-rule="evenodd" d="M.75 13.5A.75.75 0 0 1 1.5 12.75h17a.75.75 0 0 1 0 1.5h-17a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
                        <path fill-rule="evenodd" d="M4.25 16.5a.75.75 0 0 0 0 1.5h14.5a.75.75 0 0 0 0-1.5H4.25Z" clip-rule="evenodd" />
                    </svg>
                    Struktur Pemerintahan (SOTK)
                </button>
                <button @click="tab = 'bpd'"
                    :class="{ 'border-[#2e7d32] text-[#2e7d32]': tab === 'bpd', 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': tab !== 'bpd' }"
                    class="flex items-center gap-2 px-1 py-4 ml-8 -mb-px text-lg font-medium transition-colors duration-200 border-b-2">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-5.5-2.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0ZM10 12a5.99 5.99 0 0 0-4.793 2.39A6.483 6.483 0 0 0 10 16.5a6.483 6.483 0 0 0 4.793-2.11A5.99 5.99 0 0 0 10 12Z" clip-rule="evenodd" />
                    </svg>
                    Badan Permusyawaratan (BPD)
                </button>
            </div>

            <!-- KONTEN TAB -->
            <div>
                <!-- TAB 1: Struktur Pemerintahan (SOTK) -->
                <div x-show="tab === 'pemerintahan'" x-cloak>
                    <div class="max-w-4xl p-6 mx-auto space-y-8 bg-white border border-gray-100 shadow-lg rounded-xl">
                        @foreach($strukturSOTK as $item)
                        <div class="pb-4 border-b border-gray-100 last:border-none">
                            <h3 class="text-lg font-semibold text-[#2e7d32]">{{ $item['jabatan'] }}</h3>
                            <p class="mt-1 text-sm text-gray-600">{{ $item['deskripsi'] }}</p>
                            @if(count($item['fungsi']) > 0)
                            <ul class="pl-5 mt-2 space-y-1 text-sm text-gray-700 list-disc">
                                @foreach($item['fungsi'] as $fungsi)
                                <li>{{ $fungsi }}</li>
                                @endforeach
                            </ul>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- TAB 2: BPD -->
                <div x-show="tab === 'bpd'" x-cloak>
                    <div class="space-y-12">
                        <!-- Deskripsi BPD -->
                        <div>
                            <h2 class="mb-4 text-2xl font-bold text-[#2e7d32]">
                                Badan Permusyawaratan Desa (BPD)
                            </h2>
                            <p class="leading-relaxed text-gray-700">{{ $bpdDeskripsi }}</p>
                        </div>

                        <!-- Fungsi & Tugas Accordion -->
                        <div class="space-y-3">
                            <!-- Accordion Fungsi -->
                            <div class="border border-gray-200 rounded-lg shadow-sm">
                                <button @click="openAccordion === 'fungsi' ? openAccordion = null : openAccordion = 'fungsi'"
                                    class="flex items-center justify-between w-full p-4 text-left transition bg-gray-50 hover:bg-green-50">
                                    <span class="font-semibold text-gray-800">Fungsi BPD</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-transform"
                                        :class="{ 'rotate-180': openAccordion === 'fungsi' }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="openAccordion === 'fungsi'" class="p-5 text-gray-700 bg-white" style="display: none;">
                                    <ul class="pl-5 space-y-2 list-disc">
                                        @foreach($bpdFungsi as $fungsi)
                                        <li>{{ $fungsi }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <!-- Accordion Tugas -->
                            <div class="border border-gray-200 rounded-lg shadow-sm">
                                <button @click="openAccordion === 'tugas' ? openAccordion = null : openAccordion = 'tugas'"
                                    class="flex items-center justify-between w-full p-4 text-left transition bg-gray-50 hover:bg-green-50">
                                    <span class="font-semibold text-gray-800">Tugas BPD</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-transform"
                                        :class="{ 'rotate-180': openAccordion === 'tugas' }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="openAccordion === 'tugas'" class="p-5 text-gray-700 bg-white" style="display: none;">
                                    <ul class="pl-5 space-y-2 list-disc">
                                        @foreach($bpdTugas as $tugas)
                                        <li>{{ $tugas }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Daftar Anggota BPD -->
                        <div class="space-y-6">
                            <h3 class="text-2xl font-bold text-[#2e7d32]">
                                Anggota BPD (2019–2025)
                            </h3>
                            <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                                @foreach($bpdMembers as $person)
                                <div class="p-5 text-center transition bg-white border border-gray-100 rounded-lg shadow-md hover:shadow-lg">
                                    <img src="{{ $person['photo'] }}" alt="{{ $person['name'] }}"
                                        class="object-cover w-24 h-24 mx-auto mb-3 rounded-full" />
                                    <h4 class="font-semibold text-gray-800">{{ $person['name'] }}</h4>
                                    <p class="text-sm font-medium text-[#2e7d32]">{{ $person['position'] }}</p>
                                    <p class="mt-1 text-xs text-gray-500">{{ $person['address'] }}</p>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- LEMBAGA LAIN -->
    <section class="px-6 py-20 mx-auto bg-white border-t border-gray-100 max-w-7xl lg:px-8">
        <h2 class="mb-8 text-2xl font-bold text-center text-[#2e7d32]">
            Lembaga Kemasyarakatan Desa
        </h2>
        <div class="max-w-4xl mx-auto space-y-4">
            @foreach($lembagaDesa as $index => $lembaga)
            <div class="border border-gray-200 rounded-lg shadow-sm">
                <button @click="openAccordion === 'lembaga{{ $index }}' ? openAccordion = null : openAccordion = 'lembaga{{ $index }}'"
                    class="flex items-center justify-between w-full p-4 text-left rounded-t-lg bg-gray-50 hover:bg-green-50">
                    <span class="font-semibold text-gray-800">{{ $lembaga['nama'] }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-transform"
                        :class="{ 'rotate-180': openAccordion === 'lembaga{{ $index }}' }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="openAccordion === 'lembaga{{ $index }}'" class="p-5 text-gray-700 bg-white rounded-b-lg" style="display: none;">
                    <p class="mb-3 text-sm">{{ $lembaga['deskripsi'] }}</p>
                    <ul class="pl-5 space-y-1 text-sm list-disc">
                        @foreach($lembaga['program'] as $program)
                        <li>{{ $program }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endforeach
        </div>
    </section>
</div>
@endsection
