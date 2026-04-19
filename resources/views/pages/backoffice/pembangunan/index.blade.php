@extends('layouts.backoffice')

@section('title', 'Data Pembangunan Desa - Panel Administrasi')

@push('styles')
    <!-- Highcharts -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <!-- Leaflet.js -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        
        .timeline-line::before {
            content: ''; position: absolute; left: 11px; top: 24px; bottom: -10px; width: 2px; background-color: #e2e8f0; z-index: 0;
        }
        .timeline-line:last-child::before { display: none; }
    </style>
@endpush

@section('content')
<div class="flex-1 flex flex-col h-full bg-[#F8FAFC]" x-data="{ 
        activeTab: 'list', // list, map, monitor
        addModalOpen: false,
        detailDrawerOpen: false,
        updateModalOpen: false,
        activeProyekId: null,
        
        formatIDR(val) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(val);
        },
        
        // Trigger Leaflet resize when map tab is activated
        activateMapTab() {
            this.activeTab = 'map';
            setTimeout(() => { window.dispatchEvent(new Event('resize')); }, 100);
        },

        fetchDetail(id) {
            this.activeProyekId = id;
            this.detailDrawerOpen = true;
            document.getElementById('drawer-content').innerHTML = '<div class=\'p-6 text-center text-gray-500\'><i class=\'fa-solid fa-spinner fa-spin\'></i> Memuat data...</div>';
            
            fetch(`/admin/pembangunan/data/drawer/${id}`)
                .then(res => res.text())
                .then(html => {
                    document.getElementById('drawer-content').innerHTML = html;
                });
        },

        openUpdateModal() {
            this.detailDrawerOpen = false;
            // Set slight delay for animation
            setTimeout(() => {
                this.updateModalOpen = true;
            }, 300);
        }
    }">

    <!-- TOP NAVBAR -->
    <header class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4 shrink-0 px-4 sm:px-6 pt-6">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-semibold px-2.5 py-1 bg-amber-100 text-amber-700 rounded-md uppercase tracking-wider">Akses Kasi Kesra</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Manajemen Pembangunan</h1>
        </div>
        
        <div class="flex items-center gap-3">
             <form action="{{ route('admin.pembangunan.index') }}" method="GET" class="relative">
                <select name="tahun" onchange="this.form.submit()"
                    class="bg-white border border-gray-200 rounded-xl px-4 py-2 text-sm font-bold text-gray-700 focus:ring-2 focus:ring-green-500 outline-none cursor-pointer appearance-none pr-10 shadow-sm">
                    @foreach(range(date('Y') + 1, 2021) as $yr)
                        <option value="{{ $yr }}" {{ $tahun == $yr ? 'selected' : '' }}>Tahun {{ $yr }}</option>
                    @endforeach
                </select>
                <i class="fa-solid fa-calendar-alt absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
            </form>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto px-4 sm:px-6 lg:px-8 pb-8 custom-scrollbar">
        @if(session('success'))
            <div class="mb-4 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm font-bold shadow-sm">
                <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm font-bold shadow-sm">
                <i class="fa-solid fa-triangle-exclamation mr-2"></i>Terdapat kesalahan:
                <ul class="list-disc pl-5 mt-1 text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="max-w-7xl mx-auto space-y-6">
            <section class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Data Pembangunan Desa</h2>
                    <p class="text-sm text-gray-500 mt-1">Kelola proyek fisik, pantau progres, dan catat realisasi anggaran.</p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <button class="bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 shadow-sm rounded-xl px-4 py-2.5 text-sm font-bold transition-all flex items-center gap-2">
                        <i class="fa-solid fa-file-export text-green-600"></i> <span class="hidden sm:inline">Export Excel</span>
                    </button>
                    <button @click="addModalOpen = true" class="bg-green-700 hover:bg-green-800 text-white shadow-md rounded-xl px-5 py-2.5 text-sm font-bold transition-all flex items-center gap-2">
                        <i class="fa-solid fa-plus"></i> Tambah Proyek
                    </button>
                </div>
            </section>

            <!-- SUMMARY CARDS -->
            <section class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between group">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Total Proyek</p>
                        <h3 class="text-2xl font-extrabold text-gray-900">{{ $totalProyek }}</h3>
                    </div>
                </article>
                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between group border-b-4 border-b-blue-500">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Sedang Berjalan</p>
                        <h3 class="text-2xl font-extrabold text-blue-600">{{ $berjalan }}</h3>
                    </div>
                </article>
                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between group border-b-4 border-b-green-500">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Selesai (100%)</p>
                        <h3 class="text-2xl font-extrabold text-green-600">{{ $selesai }}</h3>
                    </div>
                </article>
                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between group border-b-4 border-b-red-500">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Terlambat</p>
                        <h3 class="text-2xl font-extrabold text-red-600">{{ $terlambat }}</h3>
                    </div>
                </article>
            </section>

            <!-- TABS CONTROL -->
            <section class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-3 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex gap-2 p-1 bg-gray-100 rounded-xl overflow-x-auto">
                    <button @click="activeTab = 'list'" :class="activeTab === 'list' ? 'bg-white shadow-sm text-green-700 font-bold' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-2 text-sm rounded-lg transition-all whitespace-nowrap"><i class="fa-solid fa-list mr-1"></i> Daftar Proyek</button>
                    <button @click="activateMapTab()" :class="activeTab === 'map' ? 'bg-white shadow-sm text-green-700 font-bold' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-2 text-sm rounded-lg transition-all whitespace-nowrap"><i class="fa-solid fa-map-location-dot mr-1"></i> Peta Lokasi</button>
                    <button @click="activeTab = 'monitor'" :class="activeTab === 'monitor' ? 'bg-white shadow-sm text-green-700 font-bold' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-2 text-sm rounded-lg transition-all whitespace-nowrap"><i class="fa-solid fa-chart-pie mr-1"></i> Monitoring & Analitik</button>
                </div>
            </section>

            <!-- TAB CONTENT 1: DAFTAR PROYEK (LIST) -->
            <div x-show="activeTab === 'list'" x-transition.opacity class="space-y-6">
                @include('pages.backoffice.pembangunan._tabs.list')
            </div>

            <!-- TAB CONTENT 2: PETA LOKASI (MAP) -->
            <div x-show="activeTab === 'map'" class="space-y-6" x-cloak>
                @include('pages.backoffice.pembangunan._tabs.map')
            </div>

            <!-- TAB CONTENT 3: MONITOR & ANALITIK -->
            <div x-show="activeTab === 'monitor'" class="space-y-6" x-cloak>
                @include('pages.backoffice.pembangunan._tabs.monitor')
            </div>

        </div>
    </main>

    <!-- MODALS & DRAWERS -->
    @include('pages.backoffice.pembangunan._modals.add')
    @include('pages.backoffice.pembangunan._modals.update')
    @include('pages.backoffice.pembangunan._drawers.detail')
</div>
@endsection
