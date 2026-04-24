@extends('layouts.frontend')

@section('title', 'Identitas Desa - Desa Sindangmukti')

@section('content')
    <main class="flex-grow bg-gray-50 pt-16">

        <!-- SECTION 1: HEADER IDENTITAS -->
        <section class="bg-gradient-to-br from-green-800 to-green-600 text-white py-16 md:py-20 relative overflow-hidden">
            <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]">
            </div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
                <span
                    class="bg-green-700/50 text-green-100 text-sm font-semibold px-3 py-1 rounded-full border border-green-500/30 mb-4 inline-block">Profil
                    Pemerintahan Desa</span>
                <h1 class="text-4xl md:text-5xl font-bold mb-4 tracking-tight">Identitas Desa Sindangmukti</h1>
                <p class="text-lg text-green-100 max-w-2xl mx-auto leading-relaxed">
                    Informasi administrasi resmi, lokasi geografis, dan data ringkas Desa Sindangmukti.
                </p>
            </div>
        </section>

        <!-- KONTEN UTAMA -->
        <section id="identitas" class="py-16">
            <div class="px-6 mx-auto max-w-7xl lg:px-8">
                @php
                    $kodeDesa = collect($infoAdministratif)->firstWhere('label', 'Kode Desa')['value'] ?? '-';
                    $kecamatan = collect($infoAdministratif)->firstWhere('label', 'Kecamatan')['value'] ?? '-';
                    $kabupaten = collect($infoAdministratif)->firstWhere('label', 'Kabupaten')['value'] ?? '-';
                    $provinsi = collect($infoAdministratif)->firstWhere('label', 'Provinsi')['value'] ?? '-';
                    $kepalaDesa = collect($infoAdministratif)->firstWhere('label', 'Kepala Desa')['value'] ?? '-';
                    $masaJabatan = collect($infoAdministratif)->firstWhere('label', 'Masa Jabatan')['value'] ?? '-';
                @endphp

                <!-- SPLIT PROFILE CARD -->
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden flex flex-col md:flex-row hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300 mb-12">
                    
                    <!-- Kiri: Identitas Utama (Green Theme) -->
                    <div class="md:w-5/12 bg-green-950 text-white p-8 sm:p-10 relative overflow-hidden flex flex-col justify-center">
                        <!-- Dekorasi Background -->
                        <div class="absolute top-0 right-0 w-48 h-48 bg-green-500 rounded-full mix-blend-screen filter blur-3xl opacity-20 transform translate-x-1/2 -translate-y-1/2"></div>
                        <div class="absolute bottom-0 left-0 w-48 h-48 bg-emerald-500 rounded-full mix-blend-screen filter blur-3xl opacity-20 transform -translate-x-1/4 translate-y-1/4"></div>
                        
                        <div class="relative z-10">
                            <div class="w-14 h-14 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center mb-6 border border-white/20 shadow-lg">
                                <img src="{{ asset('assets/img/logo.webp') }}" alt="Logo Desa" class="w-10 h-10 object-contain drop-shadow-md" />
                            </div>
                            <h3 class="text-green-300 font-bold text-xs uppercase tracking-widest mb-3">Identitas Administratif</h3>
                            <h2 class="text-3xl font-bold text-white leading-tight mb-8">Pemerintah Desa Sindangmukti</h2>
                            
                            <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-5 inline-block w-full">
                                <p class="text-xs text-slate-400 uppercase tracking-widest mb-1.5">Kode Desa</p>
                                <p class="text-2xl font-mono font-bold text-white tracking-widest flex items-center gap-2">
                                    {{ $kodeDesa }}
                                    <button class="text-slate-400 hover:text-white transition-colors" title="Salin Kode">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                    </button>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Kanan: Rincian Wilayah & Pemimpin -->
                    <div class="md:w-7/12 p-8 sm:p-10 bg-white flex flex-col justify-center">
                        
                        <div class="space-y-8">
                            <!-- Wilayah Grid -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-8 gap-x-6">
                                
                                <!-- Kecamatan -->
                                <div class="group relative pl-4 border-l-2 border-slate-100 hover:border-green-500 transition-colors">
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1 flex items-center gap-1.5">
                                        Kecamatan
                                    </p>
                                    <p class="text-lg font-bold text-slate-800">{{ $kecamatan }}</p>
                                </div>
                                
                                <!-- Kabupaten -->
                                <div class="group relative pl-4 border-l-2 border-slate-100 hover:border-green-500 transition-colors">
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1 flex items-center gap-1.5">
                                        Kabupaten
                                    </p>
                                    <p class="text-lg font-bold text-slate-800">{{ $kabupaten }}</p>
                                </div>

                                <!-- Provinsi -->
                                <div class="group relative pl-4 border-l-2 border-slate-100 hover:border-green-500 transition-colors sm:col-span-2">
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1 flex items-center gap-1.5">
                                        Provinsi
                                    </p>
                                    <p class="text-lg font-bold text-slate-800">{{ $provinsi }}</p>
                                </div>
                            </div>

                            <hr class="border-slate-100">

                            <!-- Pemimpin Section -->
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Kepemimpinan</p>
                                <div class="flex items-center gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-100 hover:border-slate-200 transition-all duration-300">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($kepalaDesa) }}&background=f0fdf4&color=15803d&bold=true" alt="Avatar Kades" class="w-12 h-12 rounded-full border-2 border-white shadow-sm shrink-0">
                                    <div class="flex-1">
                                        <h4 class="text-base font-bold text-slate-900">{{ $kepalaDesa }}</h4>
                                        <p class="text-sm font-medium text-slate-500">Kepala Desa</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-block bg-white text-xs font-bold text-slate-600 px-3 py-1.5 rounded-lg border border-slate-200 shadow-sm">
                                            {{ $masaJabatan }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- SECTION: STATISTIK DESA -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                    @foreach ($statistik as $index => $stat)
                        @php
                            // Mapping icons and colors from the modern design snippet
                            $configs = [
                                0 => [
                                    'bg' => 'bg-green-50', 
                                    'text' => 'text-green-600', 
                                    'hoverBg' => 'group-hover:bg-green-600', 
                                    'hoverText' => 'group-hover:text-green-600', 
                                    'border' => 'hover:border-green-100', 
                                    'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'
                                ],
                                1 => [
                                    'bg' => 'bg-blue-50', 
                                    'text' => 'text-blue-600', 
                                    'hoverBg' => 'group-hover:bg-blue-600', 
                                    'hoverText' => 'group-hover:text-blue-600', 
                                    'border' => 'hover:border-blue-100', 
                                    'icon' => 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7'
                                ],
                                2 => [
                                    'bg' => 'bg-amber-50', 
                                    'text' => 'text-amber-600', 
                                    'hoverBg' => 'group-hover:bg-amber-600', 
                                    'hoverText' => 'group-hover:text-amber-600', 
                                    'border' => 'hover:border-amber-100', 
                                    'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'
                                ],
                                3 => [
                                    'bg' => 'bg-purple-50', 
                                    'text' => 'text-purple-600', 
                                    'hoverBg' => 'group-hover:bg-purple-600', 
                                    'hoverText' => 'group-hover:text-purple-600', 
                                    'border' => 'hover:border-purple-100', 
                                    'icon' => 'M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z'
                                ],
                            ];
                            $c = $configs[$index % 4];
                        @endphp
                        
                        <div class="bg-white rounded-[2rem] p-6 sm:p-8 shadow-sm border border-slate-100 {{ $c['border'] }} hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] hover:-translate-y-1 transition-all duration-300 group">
                            <div class="w-12 h-12 {{ $c['bg'] }} {{ $c['text'] }} rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 {{ $c['hoverBg'] }} group-hover:text-white transition-all duration-300 shadow-sm relative z-10">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $c['icon'] }}" />
                                </svg>
                            </div>
                            <div class="relative z-0">
                                <h3 class="text-3xl font-black text-slate-900 tracking-tight {{ $c['hoverText'] }} transition-colors">
                                    @php
                                        $valParts = explode(' ', $stat['value'], 2);
                                    @endphp
                                    {{ $valParts[0] }}
                                    @if(isset($valParts[1]))
                                        <span class="text-lg font-bold text-slate-400 group-hover:text-slate-500 ml-1">{{ $valParts[1] }}</span>
                                    @endif
                                </h3>
                                <p class="text-sm font-medium text-slate-500 mt-1">{{ $stat['label'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- SECTION: MAP LOKASI -->
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300">
                    <div class="flex flex-col lg:flex-row">
                        <div class="lg:w-2/3">
                            <div id="mapIdentitas" class="relative z-10 w-full h-[400px]"></div>
                        </div>
                        <div class="lg:w-1/3 p-8 sm:p-12 flex flex-col justify-center bg-white">
                            <div class="w-16 h-16 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center mb-8 shadow-sm">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-slate-900 mb-4">Lokasi Kantor Desa</h3>
                            <p class="text-slate-500 leading-relaxed text-lg mb-8">
                                Jl. Raya Sindangmukti No. 1, Kec. Panumbangan, Kab. Ciamis, Jawa Barat 46263
                            </p>
                            <a href="https://www.google.com/maps/search/?api=1&query=-7.2023,108.1887" target="_blank" class="inline-flex items-center justify-center px-6 py-3 bg-slate-900 text-white font-bold rounded-xl hover:bg-green-700 transition-colors gap-2 self-start">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                                Buka di Google Maps
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Leaflet map if map container exists
            if (document.getElementById('mapIdentitas')) {
                const map = L.map('mapIdentitas').setView([-7.2023, 108.1887],
                    14); // Coordinate for Panumbangan, adjust as necessary

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                L.marker([-7.2023, 108.1887]).addTo(map)
                    .bindPopup('<b>Kantor Desa Sindangmukti</b><br>Jl. Raya Sindangmukti No. 1')
                    .openPopup();
            }
        });
    </script>
@endpush
