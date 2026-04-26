@extends('layouts.backoffice')

@section('title', 'Data Pembangunan Desa - Panel Administrasi')

@push('styles')
    <!-- Highcharts -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <!-- Leaflet.js -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }

        .timeline-line::before {
            content: '';
            position: absolute;
            left: 11px;
            top: 24px;
            bottom: -10px;
            width: 2px;
            background-color: #e2e8f0;
            z-index: 0;
        }

        .timeline-line:last-child::before {
            display: none;
        }

        /* Custom Range Slider for Progress Update */
        .range-custom {
            -webkit-appearance: none;
            appearance: none;
            background: transparent;
            width: 100%;
        }

        .range-custom:focus {
            outline: none;
        }

        .slider-emerald::-webkit-slider-runnable-track {
            height: 8px;
            background: linear-gradient(to right, 
                #10b981 0%, #10b981 var(--current, 0%), 
                #a7f3d0 var(--current, 0%), #a7f3d0 var(--target, 0%), 
                #f1f5f9 var(--target, 0%), #f1f5f9 100%);
            border-radius: 9999px;
            cursor: pointer;
        }
        .slider-emerald::-moz-range-track {
            height: 8px;
            background: linear-gradient(to right, 
                #10b981 0%, #10b981 var(--current, 0%), 
                #a7f3d0 var(--current, 0%), #a7f3d0 var(--target, 0%), 
                #f1f5f9 var(--target, 0%), #f1f5f9 100%);
            border-radius: 9999px;
            cursor: pointer;
        }
        .slider-emerald::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 24px;
            height: 24px;
            background: #10b981;
            box-shadow: 0 0 0 4px white, 0 4px 6px -1px rgb(0 0 0 / 0.1);
            border-radius: 50%;
            cursor: pointer;
            margin-top: -8px;
            transition: transform 0.15s ease;
        }
        .slider-emerald::-webkit-slider-thumb:hover {
            transform: scale(1.1);
        }
        .slider-emerald::-moz-range-thumb {
            width: 24px;
            height: 24px;
            background: #10b981;
            box-shadow: 0 0 0 4px white, 0 4px 6px -1px rgb(0 0 0 / 0.1);
            border: none;
            border-radius: 50%;
            cursor: pointer;
            transition: transform 0.15s ease;
        }
        .slider-emerald::-moz-range-thumb:hover {
            transform: scale(1.1);
        }
    </style>
@endpush

@section('content')
    <div class="flex-1 flex flex-col h-full bg-[#F8FAFC]" x-data="{
        addModalOpen: false,
        editModalOpen: false,
        detailDrawerOpen: false,
        updateModalOpen: false,
        activeProyekId: null,
        proyekToEdit: null,
        currentProgres: 0,

        formatIDR(val) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(val);
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

        openUpdateModal(currentProgress = 0) {
            this.currentProgres = currentProgress;
            this.detailDrawerOpen = false;
            // Set slight delay for animation
            setTimeout(() => {
                this.updateModalOpen = true;
            }, 300);
        }
    }">

        <!-- TOP NAVBAR -->
        <header class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4 shrink-0 ">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Manajemen Pembangunan</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola proyek fisik, pantau progres, dan catat realisasi
                    anggaran.</p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <form action="{{ route('admin.pembangunan.index') }}" method="GET" class="relative">
                    <select name="tahun" onchange="this.form.submit()"
                        class="bg-white border border-gray-200 rounded-xl px-4 py-2 text-sm font-bold text-gray-700 focus:ring-2 focus:ring-green-500 outline-none cursor-pointer appearance-none pr-10 shadow-sm">
                        @foreach (range(date('Y') + 1, 2021) as $yr)
                            <option value="{{ $yr }}" {{ $tahun == $yr ? 'selected' : '' }}>Tahun
                                {{ $yr }}</option>
                        @endforeach
                    </select>
                    <i
                        class="fa-solid fa-calendar-alt absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                </form>
                <button
                    class="bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 shadow-sm rounded-xl px-4 py-2.5 text-sm font-bold transition-all flex items-center gap-2">
                    <i class="fa-solid fa-file-export text-green-600"></i> <span class="hidden sm:inline">Export
                        Excel</span>
                </button>
                <button @click="addModalOpen = true"
                    class="bg-green-700 hover:bg-green-800 text-white shadow-md rounded-xl px-5 py-2.5 text-sm font-bold transition-all flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Tambah Proyek
                </button>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto pb-8 custom-scrollbar">
            @if (session('success'))
                <div
                    class="mb-4 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm font-bold shadow-sm">
                    <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
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

                <!-- SUMMARY CARDS -->
                <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    {{-- Total Proyek --}}
                    <article
                        class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-all flex items-center gap-4 group relative overflow-hidden">
                        <div
                            class="absolute top-0 right-0 p-2 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity">
                            <i class="fa-solid fa-diagram-project text-7xl rotate-12"></i>
                        </div>
                        <div
                            class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-600 flex items-center justify-center shrink-0 text-xl shadow-inner group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-diagram-project"></i>
                        </div>
                        <div class="relative z-10">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Total Proyek</p>
                            <h3 class="text-2xl font-black text-gray-900 tracking-tight leading-none">{{ $totalProyek }}
                            </h3>
                        </div>
                    </article>

                    {{-- Sedang Berjalan --}}
                    <article
                        class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-all flex items-center gap-4 group relative overflow-hidden">
                        <div
                            class="absolute top-0 right-0 p-2 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity text-blue-600">
                            <i class="fa-solid fa-person-digging text-7xl rotate-12"></i>
                        </div>
                        <div
                            class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 text-xl shadow-inner group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-person-digging"></i>
                        </div>
                        <div class="relative z-10">
                            <p class="text-[10px] font-bold text-blue-400 uppercase tracking-widest mb-0.5">Progres Aktif
                            </p>
                            <h3 class="text-2xl font-black text-blue-700 tracking-tight leading-none">{{ $berjalan }}
                            </h3>
                        </div>
                    </article>

                    {{-- Selesai --}}
                    <article
                        class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-all flex items-center gap-4 group relative overflow-hidden">
                        <div
                            class="absolute top-0 right-0 p-2 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity text-emerald-600">
                            <i class="fa-solid fa-circle-check text-7xl rotate-12"></i>
                        </div>
                        <div
                            class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 text-xl shadow-inner group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <div class="relative z-10">
                            <p class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest mb-0.5">Sudah Selesai
                            </p>
                            <h3 class="text-2xl font-black text-emerald-700 tracking-tight leading-none">{{ $selesai }}
                            </h3>
                        </div>
                    </article>

                    {{-- Terlambat --}}
                    <article
                        class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-all flex items-center gap-4 group relative overflow-hidden">
                        <div
                            class="absolute top-0 right-0 p-2 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity text-rose-600">
                            <i class="fa-solid fa-clock-rotate-left text-7xl rotate-12"></i>
                        </div>
                        <div
                            class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0 text-xl shadow-inner group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </div>
                        <div class="relative z-10">
                            <p class="text-[10px] font-bold text-rose-400 uppercase tracking-widest mb-0.5">Terhambat</p>
                            <h3 class="text-2xl font-black text-rose-700 tracking-tight leading-none">{{ $terlambat }}
                            </h3>
                        </div>
                    </article>
                </section>


                <div class="grid grid-cols-1 lg:grid-cols-10 gap-6 pt-6 border-t border-gray-100">
                    <!-- CONTENT 1: PETA LOKASI (MAP) - 70% (7/10) -->
                    <div class="lg:col-span-7 flex flex-col">
                        @include('pages.backoffice.pembangunan._tabs.map')
                    </div>

                    <!-- CONTENT 2: MONITOR & ANALITIK - 30% (3/10) -->
                    <div class="lg:col-span-3 flex flex-col gap-4">
                        @include('pages.backoffice.pembangunan._tabs.monitor')
                    </div>
                </div>

                <!-- CONTENT 3: DAFTAR PROYEK (LIST) - 100% -->
                <div class="pt-6 border-t border-gray-100 pb-10">
                    @include('pages.backoffice.pembangunan._tabs.list')
                </div>
            </div>
        </main>

        <!-- MODALS & DRAWERS -->
        @include('pages.backoffice.pembangunan._modals.add')
        @include('pages.backoffice.pembangunan._modals.edit')
        @include('pages.backoffice.pembangunan._modals.update')
        @include('pages.backoffice.pembangunan._drawers.detail')
    </div>
@endsection
