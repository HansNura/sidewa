@extends('layouts.frontend')

@section('title', 'Pengumuman & Agenda - Desa Sindangmukti')

@push('styles')
<style>
    /* Utility class membatasi baris teks (Trunkasi) */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush

@section('content')
<main class="flex-grow pt-16 bg-gray-50" x-data="agendaData()">

    <!-- SECTION 1: HEADER SECTION -->
    <section class="relative py-12 overflow-hidden text-white bg-gradient-to-br from-[#2e7d32] to-[#1b5e20] md:py-16">
        <!-- Pattern Latar Belakang -->
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]">
        </div>
        <div class="relative z-10 px-4 mx-auto text-center max-w-7xl sm:px-6 lg:px-8">
            <span class="inline-block px-4 py-1 mb-4 text-sm font-semibold border rounded-full bg-green-700/50 text-green-100 border-green-500/30">
                <svg class="inline-block w-4 h-4 mr-1 pb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg> 
                Informasi Publik
            </span>
            <h1 class="mb-4 text-4xl font-bold tracking-tight md:text-5xl">{{ $pageTitle }}</h1>
            <p class="max-w-2xl mx-auto text-lg leading-relaxed text-green-100">
                {{ $pageSubtitle }}
            </p>
        </div>
    </section>

    <div class="px-4 py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">

        <!-- SECTION 2: PENGUMUMAN TERBARU (Highlight Penting) -->
        <section class="mb-12">
            @if($pengumumanPenting)
            <div class="relative flex flex-col items-start gap-6 p-6 overflow-hidden transition-all border-l-4 border-amber-500 cursor-pointer bg-amber-50 rounded-r-2xl shadow-sm md:flex-row md:items-center group hover:shadow-md"
                {{-- onclick="openDetailModal('pengumuman')" --}}>
                <!-- Efek blink untuk menandakan "Penting/Live" -->
                <div class="absolute flex w-3 h-3 top-4 right-4">
                    <span class="absolute inline-flex w-full h-full rounded-full opacity-75 animate-ping bg-amber-400"></span>
                    <span class="relative inline-flex w-3 h-3 rounded-full bg-amber-500"></span>
                </div>

                <div class="flex items-center justify-center shrink-0 w-14 h-14 rounded-full bg-amber-100 text-amber-600">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                </div>

                <div class="flex-grow">
                    <div class="flex items-center gap-3 mb-2 text-xs font-bold tracking-wide uppercase text-amber-700">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1 pb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg> 
                            Pengumuman Penting
                        </span>
                        <span class="text-amber-300">|</span>
                        <span>Diterbitkan: {{ $pengumumanPenting['tanggal'] }}</span>
                    </div>
                    <h2 class="mb-2 text-xl font-bold text-gray-800 transition-colors md:text-2xl group-hover:text-amber-700">
                        {{ $pengumumanPenting['judul'] }}
                    </h2>
                    <p class="text-gray-600 line-clamp-2">
                        {{ $pengumumanPenting['ringkasan'] }}
                    </p>
                </div>

                <div class="shrink-0 md:pl-4 md:border-l border-amber-200">
                    <button class="flex items-center px-5 py-2 font-semibold text-white transition-colors rounded-lg whitespace-nowrap bg-amber-500 hover:bg-amber-600">
                        Lihat Detail 
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>
            </div>
            @endif
        </section>

        <!-- LAYOUT GRID: KOLOM KIRI (KALENDER & FILTER) | KOLOM KANAN (LIST AGENDA) -->
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12 lg:gap-10">

            <!-- KOLOM KIRI (Sidebar) -->
            <aside class="flex flex-col gap-6 lg:col-span-4">

                <!-- SECTION 6: FILTER WAKTU -->
                <section class="p-6 bg-white border border-gray-200 shadow-sm rounded-2xl">
                    <h3 class="flex items-center mb-4 text-lg font-bold text-gray-800">
                        <svg class="w-5 h-5 mr-2 text-[#2e7d32]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        Filter Agenda
                    </h3>
                    <form class="flex flex-col gap-4">
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700">Bulan</label>
                            <select class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#2e7d32] focus:border-[#2e7d32] block p-2.5">
                                <option value="10" selected>Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700">Tahun</label>
                            <select class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#2e7d32] focus:border-[#2e7d32] block p-2.5">
                                <option value="2024" selected>2024</option>
                                <option value="2025">2025</option>
                            </select>
                        </div>
                        <button type="button" class="w-full text-white bg-[#2e7d32] hover:bg-[#1b5e20] focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mt-2 transition-colors">
                            Terapkan Filter
                        </button>
                    </form>
                </section>

                <!-- SECTION 3: KALENDER AGENDA (Visual Component) -->
                <section class="p-6 bg-white border border-gray-200 shadow-sm rounded-2xl">
                    <div class="flex items-center justify-between mb-4">
                        <button class="text-gray-400 transition-colors hover:text-green-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </button>
                        <h3 class="font-bold text-gray-800">Oktober 2024</h3>
                        <button class="text-gray-400 transition-colors hover:text-green-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    </div>

                    <!-- Nama Hari -->
                    <div class="grid grid-cols-7 gap-1 mb-2 text-xs font-semibold text-center text-gray-500">
                        <div>M</div>
                        <div>S</div>
                        <div>S</div>
                        <div>R</div>
                        <div>K</div>
                        <div>J</div>
                        <div>S</div>
                    </div>

                    <!-- Tanggal (Simulasi Statis) -->
                    <div class="grid grid-cols-7 gap-1 text-sm font-medium text-center text-gray-700">
                        <!-- Tanggal bulan sebelumnya -->
                        <div class="py-1.5 text-gray-300">29</div>
                        <div class="py-1.5 text-gray-300">30</div>
                        <!-- Tanggal bulan berjalan -->
                        <div class="py-1.5 hover:bg-gray-100 rounded-md cursor-pointer transition-colors">1</div>
                        <div class="py-1.5 hover:bg-gray-100 rounded-md cursor-pointer transition-colors">2</div>
                        <div class="py-1.5 hover:bg-gray-100 rounded-md cursor-pointer transition-colors">3</div>
                        <div class="py-1.5 hover:bg-gray-100 rounded-md cursor-pointer transition-colors">4</div>
                        <div class="py-1.5 hover:bg-gray-100 rounded-md cursor-pointer transition-colors">5</div>
                        <div class="py-1.5 hover:bg-gray-100 rounded-md cursor-pointer transition-colors">6</div>
                        <div class="py-1.5 hover:bg-gray-100 rounded-md cursor-pointer transition-colors">7</div>
                        <div class="py-1.5 hover:bg-gray-100 rounded-md cursor-pointer transition-colors">8</div>
                        <div class="py-1.5 hover:bg-gray-100 rounded-md cursor-pointer transition-colors">9</div>
                        <!-- Tanggal dengan Agenda (Ditandai) -->
                        <div class="py-1.5 bg-green-100 text-green-700 font-bold rounded-md cursor-pointer ring-1 ring-green-400 relative group">
                            10
                            <span class="absolute w-1.5 h-1.5 bg-green-600 rounded-full bottom-0.5 left-1/2 transform -translate-x-1/2"></span>
                        </div>
                        <div class="py-1.5 hover:bg-gray-100 rounded-md cursor-pointer transition-colors">11</div>
                        <div class="py-1.5 hover:bg-gray-100 rounded-md cursor-pointer transition-colors">12</div>
                        <!-- Tanggal Hari Ini -->
                        <div class="py-1.5 bg-[#2e7d32] text-white shadow-md font-bold rounded-md cursor-pointer">13</div>
                        <div class="py-1.5 hover:bg-gray-100 rounded-md cursor-pointer transition-colors">14</div>
                        <!-- Tanggal dengan Agenda (Ditandai) -->
                        <div class="py-1.5 bg-amber-100 text-amber-700 font-bold rounded-md cursor-pointer ring-1 ring-amber-400 relative">
                            15
                            <span class="absolute w-1.5 h-1.5 bg-amber-500 rounded-full bottom-0.5 left-1/2 transform -translate-x-1/2"></span>
                        </div>
                        <div class="py-1.5 hover:bg-gray-100 rounded-md cursor-pointer transition-colors">16</div>
                        <div class="py-1.5 hover:bg-gray-100 rounded-md cursor-pointer transition-colors">17</div>
                        <div class="py-1.5 hover:bg-gray-100 rounded-md cursor-pointer transition-colors">18</div>
                        <div class="py-1.5 hover:bg-gray-100 rounded-md cursor-pointer transition-colors">19</div>
                        <div class="py-1.5 hover:bg-gray-100 rounded-md cursor-pointer transition-colors">20</div>
                        <div class="py-1.5 hover:bg-gray-100 rounded-md cursor-pointer transition-colors">21</div>
                        <div class="py-1.5 hover:bg-gray-100 rounded-md cursor-pointer transition-colors">22</div>
                        <div class="py-1.5 hover:bg-gray-100 rounded-md cursor-pointer transition-colors">23</div>
                        <div class="py-1.5 hover:bg-gray-100 rounded-md cursor-pointer transition-colors">24</div>
                        <!-- Tanggal dengan Agenda -->
                        <div class="py-1.5 bg-green-100 text-green-700 font-bold rounded-md cursor-pointer ring-1 ring-green-400 relative">
                            25
                            <span class="absolute w-1.5 h-1.5 bg-green-600 rounded-full bottom-0.5 left-1/2 transform -translate-x-1/2"></span>
                        </div>
                        <div class="py-1.5 hover:bg-gray-100 rounded-md cursor-pointer transition-colors">26</div>
                        <div class="py-1.5 hover:bg-gray-100 rounded-md cursor-pointer transition-colors">27</div>
                        <!-- Tanggal Pengumuman -->
                        <div class="py-1.5 bg-red-100 text-red-700 font-bold rounded-md cursor-pointer ring-1 ring-red-400 relative">
                            28
                            <span class="absolute w-1.5 h-1.5 bg-red-600 rounded-full bottom-0.5 left-1/2 transform -translate-x-1/2"></span>
                        </div>
                        <div class="py-1.5 hover:bg-gray-100 rounded-md cursor-pointer transition-colors">29</div>
                        <div class="py-1.5 hover:bg-gray-100 rounded-md cursor-pointer transition-colors">30</div>
                        <div class="py-1.5 hover:bg-gray-100 rounded-md cursor-pointer transition-colors">31</div>
                    </div>

                    <!-- Keterangan Kalender -->
                    <div class="flex flex-wrap gap-3 pt-4 mt-4 text-xs text-gray-500 border-t border-gray-100">
                        <span class="flex items-center"><span class="w-2.5 h-2.5 bg-[#2e7d32] rounded-full mr-1.5"></span> Hari Ini</span>
                        <span class="flex items-center"><span class="w-2.5 h-2.5 bg-amber-400 rounded-full mr-1.5"></span> Agenda</span>
                        <span class="flex items-center"><span class="w-2.5 h-2.5 bg-red-500 rounded-full mr-1.5"></span> Pengumuman</span>
                    </div>
                </section>
            </aside>

            <!-- KOLOM KANAN (List Agenda) -->
            <article class="lg:col-span-8">
                <div class="flex items-center justify-between pb-2 mb-6 border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-800">Daftar Kegiatan Bulan Ini</h2>
                    <span class="px-3 py-1 text-sm font-semibold text-gray-600 bg-gray-100 rounded-full">{{ count($daftarAgenda) }} Agenda</span>
                </div>

                <!-- SECTION 4: LIST AGENDA -->
                <div class="flex flex-col gap-5">
                    @foreach($daftarAgenda as $index => $agenda)
                    <!-- Card Agenda -->
                    <div class="flex flex-col gap-5 p-5 transition-all bg-white border border-gray-200 shadow-sm sm:flex-row rounded-xl hover:-translate-y-1 hover:shadow-md group">
                        <!-- Kotak Tanggal -->
                        @php
                            $colorFromTheme = "green";
                            if($agenda['tema_warna'] == 'amber') $colorFromTheme = "amber";
                            if($agenda['tema_warna'] == 'blue') $colorFromTheme = "blue";

                            $bgGradientMap = [
                                'green' => 'from-green-50 to-white',
                                'amber' => 'from-amber-50 to-white',
                                'blue' => 'from-blue-50 to-white',
                            ];
                            $borderMap = [
                                'green' => 'border-green-100',
                                'amber' => 'border-amber-100',
                                'blue' => 'border-blue-100',
                            ];
                            $textMap = [
                                'green' => 'text-green-700',
                                'amber' => 'text-amber-700',
                                'blue' => 'text-blue-700',
                            ];
                        @endphp
                        <div class="flex flex-col items-center justify-center w-full shadow-inner sm:w-24 h-20 sm:h-24 shrink-0 bg-gradient-to-b {{ $bgGradientMap[$colorFromTheme] }} border {{ $borderMap[$colorFromTheme] }} rounded-lg {{ $textMap[$colorFromTheme] }}">
                            <span class="mb-1 text-sm font-bold tracking-wider uppercase">{{ $agenda['bulan'] }}</span>
                            <span class="text-3xl font-black leading-none transition-transform group-hover:scale-110">{{ $agenda['hari_tanggal'] }}</span>
                        </div>

                        <!-- Informasi Agenda -->
                        <div class="flex flex-col justify-center flex-grow">
                            <h3 class="mb-2 text-lg font-bold text-gray-800 transition-colors md:text-xl group-hover:text-{{ $colorFromTheme }}-600">
                                {{ $agenda['judul'] }}
                            </h3>
                            <div class="flex flex-wrap items-center gap-4 mb-3 text-sm text-gray-600">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-{{ $colorFromTheme }}-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                                    {{ $agenda['waktu'] }}
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> 
                                    {{ $agenda['lokasi'] }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 line-clamp-2">
                                {{ $agenda['ringkasan'] }}
                            </p>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="flex items-center sm:border-l sm:border-gray-100 sm:pl-5 shrink-0">
                            @if($agenda['detail'])
                            <button @click="openModal({{ $index }})" class="w-full px-4 py-2 text-sm font-semibold text-green-600 transition-colors border border-green-200 rounded-lg sm:w-auto bg-gray-50 hover:bg-green-600 hover:text-white hover:border-green-600">
                                Detail Acara
                            </button>
                            @else
                            <button class="w-full px-4 py-2 text-sm font-semibold text-green-600 transition-colors border border-green-200 rounded-lg sm:w-auto bg-gray-50 hover:bg-green-600 hover:text-white hover:border-green-600">
                                Detail Acara
                            </button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination (Opsional jika agenda banyak) -->
                <div class="mt-8 text-center">
                    <button class="pb-1 font-semibold text-green-600 transition-colors border-b border-transparent hover:text-green-800 hover:border-green-600">
                        Muat Lebih Banyak Agenda 
                        <svg class="inline-block w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                    </button>
                </div>

            </article>
        </div>
    </div>

    <!-- SECTION 5: DETAIL AGENDA (MODAL VIEW) Alpine.js -->
    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 overflow-y-auto transition-opacity bg-black/60 backdrop-blur-sm"
        @keydown.escape.window="closeModal()" style="display: none;">
        
        <div x-show="modalOpen" @click.away="closeModal()" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95" class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full overflow-hidden relative">

            <template x-if="activeAgenda && activeAgenda.detail">
                <div>
                    <!-- Tombol Close (Silang) -->
                    <button @click="closeModal()" class="absolute z-10 flex items-center justify-center w-8 h-8 text-white transition-colors rounded-full top-4 right-4 bg-black/20 hover:bg-black/40 focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>

                    <!-- Banner / Cover Image Modal -->
                    <div class="relative h-48 bg-green-700">
                        <img :src="activeAgenda.detail.gambar" alt="Cover Agenda" class="object-cover w-full h-full opacity-60 mix-blend-multiply">
                        <div class="absolute left-6 right-6 bottom-4">
                            <span class="inline-block px-3 py-1 mb-2 text-xs font-bold tracking-wider text-white uppercase bg-green-500 rounded-md shadow-sm">Agenda Desa</span>
                            <h2 class="text-2xl font-bold text-white drop-shadow-md" x-text="activeAgenda.judul"></h2>
                        </div>
                    </div>

                    <!-- Konten Modal -->
                    <div class="p-6 md:p-8">
                        <!-- Info Grid (Waktu, Tempat, Kontak) -->
                        <div class="grid grid-cols-1 gap-4 p-4 mb-6 border border-gray-100 sm:grid-cols-2 bg-gray-50 rounded-xl">
                            <div class="flex gap-3">
                                <svg class="w-5 h-5 mt-1 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <div>
                                    <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Tanggal Pelaksanaan</p>
                                    <p class="text-sm font-bold text-gray-800" x-text="activeAgenda.detail.tanggal_full"></p>
                                    <p class="text-sm text-gray-600">Pukul <span x-text="activeAgenda.waktu"></span></p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <svg class="w-5 h-5 mt-1 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <div>
                                    <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Lokasi Acara</p>
                                    <p class="text-sm font-bold text-gray-800" x-text="activeAgenda.lokasi"></p>
                                    <a :href="activeAgenda.detail.peta_link" class="text-xs text-blue-600 hover:underline">Lihat di Peta</a>
                                </div>
                            </div>
                            <div class="flex gap-3 sm:col-span-2">
                                <svg class="w-5 h-5 mt-1 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                <div>
                                    <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Koordinator / Narahubung</p>
                                    <p class="text-sm font-bold text-gray-800" x-text="activeAgenda.detail.kontak"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Deskripsi Lengkap -->
                        <h3 class="pb-2 mb-2 font-bold text-gray-800 border-b border-gray-200">Deskripsi Kegiatan</h3>
                        <div class="mb-8 space-y-3 text-sm leading-relaxed text-gray-600">
                            <p x-text="activeAgenda.detail.deskripsi"></p>
                            
                            <!-- Poin Agenda (Jika ada) -->
                            <template x-if="activeAgenda.detail.poin && activeAgenda.detail.poin.length > 0">
                                <div>
                                    <p>Adapun layanan yang akan diberikan meliputi:</p>
                                    <ul class="pl-5 space-y-1 list-disc">
                                        <template x-for="poin in activeAgenda.detail.poin">
                                            <li x-text="poin"></li>
                                        </template>
                                    </ul>
                                </div>
                            </template>

                            <template x-if="activeAgenda.detail.catatan">
                                <p class="p-2 font-medium border rounded text-amber-700 bg-amber-50 border-amber-100">
                                    <svg class="inline-block w-4 h-4 mr-1 pb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                                    <span x-text="activeAgenda.detail.catatan"></span>
                                </p>
                            </template>
                        </div>

                        <!-- Action Button Bawah -->
                        <div class="flex justify-end gap-3 pt-5 border-t border-gray-100">
                            <button class="px-4 py-2 text-sm font-semibold text-gray-600 transition-colors bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                                <svg class="inline-block w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg> 
                                Bagikan
                            </button>
                            <button class="px-6 py-2 text-sm font-semibold text-white transition-colors bg-green-600 rounded-lg shadow-md hover:bg-green-700">
                                Simpan ke Kalender
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    function agendaData() {
        return {
            daftarAgenda: @json($daftarAgenda),
            modalOpen: false,
            activeAgenda: null,
            openModal(index) {
                this.activeAgenda = this.daftarAgenda[index];
                this.modalOpen = true;
                document.body.style.overflow = 'hidden';
            },
            closeModal() {
                this.modalOpen = false;
                setTimeout(() => {
                    this.activeAgenda = null;
                    document.body.style.overflow = 'auto';
                }, 300); // Wait for transition
            }
        }
    }
</script>
@endpush
