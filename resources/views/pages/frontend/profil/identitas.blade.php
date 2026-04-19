@extends('layouts.frontend')

@section('title', 'Identitas Desa - Desa Sukakerta')

@section('content')
<div class="pt-24 text-gray-800 bg-gray-50">
    <section class="py-12 bg-white border-b border-gray-200">
        <div class="px-6 mx-auto max-w-7xl">
            <nav class="flex mb-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 text-sm text-gray-500 md:space-x-2">
                    <li class="inline-flex items-center">
                        <a href="{{ url('/') }}" class="inline-flex items-center transition-colors hover:text-primary">
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
                            <span class="ml-1 font-medium text-gray-800 md:ml-2">Identitas Desa</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-3xl font-bold md:text-4xl" style="color: #2e7d32;">
                Identitas Desa Sukakerta
            </h1>
            <p class="mt-2 text-lg text-gray-600">
                Informasi administrasi resmi, lokasi geografis, dan data ringkas Desa Sukakerta.
            </p>
        </div>
    </section>

    <!-- KONTEN UTAMA -->
    <section id="identitas" class="py-16">
        <div class="px-6 mx-auto max-w-7xl lg:px-8">
            <!-- Grid: Identitas + Map -->
            <div class="grid gap-8 mb-12 md:grid-cols-5 lg:gap-12">
                <!-- Card Identitas (Dinamis) -->
                <div class="p-6 bg-white border border-gray-100 shadow-lg rounded-xl md:col-span-3">
                    <div class="flex items-center gap-4 mb-5">
                        <img src="{{ asset('assets/img/logo.webp') }}" alt="Logo Desa" class="w-12 h-12 border border-gray-200 rounded-full" />
                        <div>
                            <h3 class="text-xl font-semibold" style="color: #2e7d32;">
                                Identitas Administratif
                            </h3>
                            <p class="text-sm text-gray-500">
                                Pemerintah Desa Sukakerta
                            </p>
                        </div>
                    </div>

                    <!-- Tabel Dinamis (Blade) -->
                    <div class="space-y-3 text-sm text-gray-700">
                        @foreach($infoAdministratif as $item)
                        <div class="flex flex-col py-2 border-b border-gray-100 sm:flex-row sm:items-center sm:justify-between last:border-b-0">
                            <span class="text-gray-600">{{ $item['label'] }}</span>
                            <span class="font-semibold" style="color: #1f2937;">{{ $item['value'] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Map Section -->
                <div class="flex flex-col overflow-hidden bg-white border border-gray-100 shadow-lg rounded-xl md:col-span-2">
                    <div id="mapIdentitas" class="relative z-10 w-full h-64 md:h-80"></div>
                    <div class="p-5 text-sm text-gray-600">
                        <p class="font-semibold" style="color: #2e7d32;">
                            Lokasi Kantor Desa
                        </p>
                        <p>
                            Jl. Raya Sukakerta No.10, Panumbangan,
                            Ciamis, Jawa Barat 46263
                        </p>
                    </div>
                </div>
            </div>

            <!-- Statistik Dinamis (Blade) -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($statistik as $stat)
                <div class="flex items-center p-5 bg-white border border-gray-100 shadow-lg rounded-xl">
                    <div class="flex-shrink-0 p-3 rounded-full {{ $stat['bgColor'] }}">
                        <svg class="w-6 h-6 {{ $stat['textColor'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $stat['icon'] }}"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-2xl font-bold" style="color: #1f2937;">{{ $stat['value'] }}</h3>
                        <p class="text-sm text-gray-600">{{ $stat['label'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Leaflet map if map container exists
        if(document.getElementById('mapIdentitas')) {
            const map = L.map('mapIdentitas').setView([-7.2023, 108.1887], 14); // Coordinate for Panumbangan, adjust as necessary

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            L.marker([-7.2023, 108.1887]).addTo(map)
                .bindPopup('<b>Kantor Desa Sukakerta</b><br>Jl. Raya Sukakerta No.10.')
                .openPopup();
        }
    });
</script>
@endpush
