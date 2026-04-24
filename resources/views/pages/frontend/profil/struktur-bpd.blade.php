@extends('layouts.frontend')

@section('title', 'BPD & Lembaga Desa - Desa Sindangmukti')

@section('content')
<div class="bg-gray-50 text-gray-800 antialiased pt-16" x-data="{ tab: 'pemerintahan', openAccordion: null }">

    <!-- HEADER HALAMAN: GRADIENT SaaS STYLE -->
    <section class="bg-gradient-to-br from-green-800 to-green-600 text-white py-16 md:py-20 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]">
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <span
                class="bg-green-700/50 text-green-100 text-sm font-semibold px-3 py-1 rounded-full border border-green-500/30 mb-4 inline-block">Kelembagaan Desa</span>
            <h1 class="text-4xl md:text-5xl font-bold mb-4 tracking-tight">BPD & Lembaga Desa Sindangmukti</h1>
            <p class="text-lg text-green-100 max-w-2xl mx-auto leading-relaxed">
                Struktur tata kelola dan lembaga kemasyarakatan yang bersinergi dalam membangun Desa Sindangmukti.
            </p>
        </div>
    </section>

    <!-- NAVIGASI TAB -->
    <section class="py-12">
        <div class="px-6 mx-auto max-w-7xl lg:px-8">
            <div class="flex flex-wrap justify-center gap-4 mb-12">
                <button @click="tab = 'pemerintahan'"
                    :class="{ 'bg-green-600 text-white shadow-lg shadow-green-600/30': tab === 'pemerintahan', 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200': tab !== 'pemerintahan' }"
                    class="flex items-center gap-2 px-6 py-3 rounded-2xl text-base font-bold transition-all duration-300">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M15.75 1.5a.75.75 0 0 0-.75.75v4.5a.75.75 0 0 0 1.5 0v-4.5a.75.75 0 0 0-.75-.75Z" clip-rule="evenodd" />
                        <path fill-rule="evenodd" d="M12.75 4.5a.75.75 0 0 1 .75-.75h.75a.75.75 0 0 1 0 1.5h-.75a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
                        <path fill-rule="evenodd" d="M.75 4.5A.75.75 0 0 1 1.5 3.75H12a.75.75 0 0 1 0 1.5H1.5A.75.75 0 0 1 .75 4.5Z" clip-rule="evenodd" />
                        <path fill-rule="evenodd" d="M4.25 7.5a.75.75 0 0 0 0 1.5h14.5a.75.75 0 0 0 0-1.5H4.25Z" clip-rule="evenodd" />
                        <path fill-rule="evenodd" d="M6.25 10.5a.75.75 0 0 0 0 1.5h12.5a.75.75 0 0 0 0-1.5H6.25Z" clip-rule="evenodd" />
                        <path fill-rule="evenodd" d="M.75 13.5A.75.75 0 0 1 1.5 12.75h17a.75.75 0 0 1 0 1.5h-17a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
                        <path fill-rule="evenodd" d="M4.25 16.5a.75.75 0 0 0 0 1.5h14.5a.75.75 0 0 0 0-1.5H4.25Z" clip-rule="evenodd" />
                    </svg>
                    Struktur SOTK
                </button>
                <button @click="tab = 'bpd'"
                    :class="{ 'bg-green-600 text-white shadow-lg shadow-green-600/30': tab === 'bpd', 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200': tab !== 'bpd' }"
                    class="flex items-center gap-2 px-6 py-3 rounded-2xl text-base font-bold transition-all duration-300">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-5.5-2.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0ZM10 12a5.99 5.99 0 0 0-4.793 2.39A6.483 6.483 0 0 0 10 16.5a6.483 6.483 0 0 0 4.793-2.11A5.99 5.99 0 0 0 10 12Z" clip-rule="evenodd" />
                    </svg>
                    Badan Permusyawaratan (BPD)
                </button>
            </div>

            <!-- KONTEN TAB -->
            <div>
                <!-- TAB 1: Struktur Pemerintahan (SOTK) -->
                <div x-show="tab === 'pemerintahan'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="max-w-4xl mx-auto grid gap-6">
                        @foreach($strukturSOTK as $item)
                        <div class="group p-8 bg-white border border-slate-100 shadow-sm rounded-[2rem] hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] hover:border-green-100 transition-all duration-300">
                            <h3 class="text-xl font-black text-green-700 mb-3 tracking-tight">{{ $item['jabatan'] }}</h3>
                            <p class="text-slate-600 leading-relaxed mb-6">{{ $item['deskripsi'] }}</p>
                            @if(count($item['fungsi']) > 0)
                            <div class="space-y-3">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Tugas & Fungsi Utama</p>
                                <ul class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach($item['fungsi'] as $fungsi)
                                    <li class="flex items-start gap-3 text-sm text-slate-600">
                                        <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                        {{ $fungsi }}
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- TAB 2: BPD -->
                <div x-show="tab === 'bpd'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="space-y-16">
                        <!-- Deskripsi BPD -->
                        <div class="max-w-3xl mx-auto text-center">
                            <h2 class="text-3xl font-black text-slate-900 mb-6 tracking-tight">Badan Permusyawaratan Desa (BPD)</h2>
                            <p class="text-lg leading-relaxed text-slate-600">{{ $bpdDeskripsi }}</p>
                        </div>

                        <!-- Fungsi & Tugas Accordion -->
                        <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Accordion Fungsi -->
                            <div class="bg-white border border-slate-100 rounded-[2rem] shadow-sm overflow-hidden transition-all duration-300">
                                <button @click="openAccordion === 'fungsi' ? openAccordion = null : openAccordion = 'fungsi'"
                                    class="flex items-center justify-between w-full p-8 text-left transition hover:bg-green-50/50">
                                    <span class="text-lg font-black text-slate-900 tracking-tight">Fungsi BPD</span>
                                    <div class="w-8 h-8 rounded-full bg-white border border-slate-100 flex items-center justify-center transition-transform duration-300" :class="{ 'rotate-180 bg-green-600 border-green-600 text-white': openAccordion === 'fungsi' }">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </button>
                                <div x-show="openAccordion === 'fungsi'" x-transition class="p-8 pt-0 text-slate-600">
                                    <ul class="space-y-3">
                                        @foreach($bpdFungsi as $fungsi)
                                        <li class="flex items-start gap-3 text-sm">
                                            <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                            {{ $fungsi }}
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <!-- Accordion Tugas -->
                            <div class="bg-white border border-slate-100 rounded-[2rem] shadow-sm overflow-hidden transition-all duration-300">
                                <button @click="openAccordion === 'tugas' ? openAccordion = null : openAccordion = 'tugas'"
                                    class="flex items-center justify-between w-full p-8 text-left transition hover:bg-green-50/50">
                                    <span class="text-lg font-black text-slate-900 tracking-tight">Tugas BPD</span>
                                    <div class="w-8 h-8 rounded-full bg-white border border-slate-100 flex items-center justify-center transition-transform duration-300" :class="{ 'rotate-180 bg-green-600 border-green-600 text-white': openAccordion === 'tugas' }">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </button>
                                <div x-show="openAccordion === 'tugas'" x-transition class="p-8 pt-0 text-slate-600">
                                    <ul class="space-y-3">
                                        @foreach($bpdTugas as $tugas)
                                        <li class="flex items-start gap-3 text-sm">
                                            <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                            {{ $tugas }}
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
    
                        <!-- Daftar Anggota BPD -->
                        <div class="space-y-8">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-12 h-1 bg-green-600 rounded-full"></div>
                                <h3 class="text-2xl font-black text-slate-900 tracking-tight">Anggota BPD (2019–2025)</h3>
                            </div>
                            
                            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                                @foreach($bpdMembers as $person)
                                <div class="group p-6 text-center bg-white border border-slate-100 rounded-[2rem] shadow-sm hover:shadow-[0_15px_40px_rgba(0,0,0,0.08)] hover:-translate-y-2 transition-all duration-500">
                                    <div class="relative w-28 h-28 mx-auto mb-6">
                                        <div class="absolute inset-0 bg-green-100 rounded-full scale-0 group-hover:scale-110 transition-transform duration-500"></div>
                                        <img src="{{ $person['photo'] }}" alt="{{ $person['name'] }}"
                                            class="relative z-10 object-cover w-28 h-28 mx-auto rounded-full border-4 border-white shadow-md" />
                                    </div>
                                    <h4 class="text-lg font-black text-slate-900 mb-1 group-hover:text-green-700 transition-colors">{{ $person['name'] }}</h4>
                                    <p class="text-sm font-bold text-green-600 mb-3 uppercase tracking-wider">{{ $person['position'] }}</p>
                                    <div class="flex items-center justify-center gap-1.5 text-xs text-slate-400">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        </svg>
                                        {{ $person['address'] }}
                                    </div>
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
    <section class="py-24 bg-white border-t border-slate-100">
        <div class="px-6 mx-auto max-w-7xl lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl font-black text-slate-900 tracking-tight mb-4">Lembaga Kemasyarakatan Desa</h2>
                <div class="h-1.5 w-20 bg-green-600 rounded-full mx-auto"></div>
            </div>
            
            <div class="max-w-4xl mx-auto space-y-4">
                @foreach($lembagaDesa as $index => $lembaga)
                <div class="bg-white border border-slate-100 rounded-[1.5rem] shadow-sm overflow-hidden transition-all duration-300">
                    <button @click="openAccordion === 'lembaga{{ $index }}' ? openAccordion = null : openAccordion = 'lembaga{{ $index }}'"
                        class="flex items-center justify-between w-full p-6 text-left transition hover:bg-green-50/50">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-green-50 text-green-600 rounded-xl flex items-center justify-center font-bold shadow-sm">
                                {{ $index + 1 }}
                            </div>
                            <span class="text-lg font-bold text-slate-900">{{ $lembaga['nama'] }}</span>
                        </div>
                        <div class="w-8 h-8 rounded-full bg-white border border-slate-100 flex items-center justify-center transition-transform duration-300 shadow-sm" :class="{ 'rotate-180 bg-green-600 border-green-600 text-white': openAccordion === 'lembaga{{ $index }}' }">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </button>
                    <div x-show="openAccordion === 'lembaga{{ $index }}'" x-transition class="p-8 pt-2 text-slate-600 bg-white border-t border-slate-50">
                        <p class="mb-6 leading-relaxed">{{ $lembaga['deskripsi'] }}</p>
                        <div class="space-y-4">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Program Utama</p>
                            <ul class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($lembaga['program'] as $program)
                                <li class="flex items-center gap-3 text-sm font-medium text-slate-700 bg-slate-50 p-3 rounded-xl border border-slate-100">
                                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                    {{ $program }}
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
</div>
@endsection
