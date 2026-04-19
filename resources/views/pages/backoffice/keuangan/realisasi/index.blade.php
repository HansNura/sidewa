@extends('layouts.backoffice')

@section('title', 'Realisasi Anggaran - Panel Administrasi')

@push('styles')
    <!-- Highcharts -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
@endpush

@section('content')
<div class="flex-1 flex flex-col h-full bg-[#F8FAFC]">

    <!-- TOP NAVBAR & HEADERS -->
    <header class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4 shrink-0 px-4 sm:px-6 pt-6">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-semibold px-2.5 py-1 bg-green-100 text-green-700 rounded-md uppercase tracking-wider">Akses Bendahara</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Realisasi & Monitoring</h1>
        </div>

        <!-- Filters & Quick Action -->
        <div class="flex items-center gap-3">
            <form action="{{ route('admin.realisasi.index') }}" method="GET" class="relative shrink-0 hidden md:block">
                <select name="tahun" onchange="this.form.submit()"
                    class="bg-gray-100 border border-gray-200 rounded-xl px-4 py-2 text-sm font-bold text-gray-700 focus:ring-2 focus:ring-green-500 outline-none cursor-pointer appearance-none pr-10">
                    @foreach($yearOptions as $yr)
                        <option value="{{ $yr }}" {{ $tahun == $yr ? 'selected' : '' }}>T.A {{ $yr }}</option>
                    @endforeach
                </select>
                <i class="fa-solid fa-calendar-check absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
            </form>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1 overflow-y-auto px-4 sm:px-6 lg:px-8 pb-8" x-data="{ 
            inputModalOpen: false,
            detailDrawerOpen: false,
            
            // Formatters
            formatIDR(val) {
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(val);
            },

            // Drawer content fetcher
            fetchDetail(id) {
                this.detailDrawerOpen = true;
                // document.getElementById('drawer-content').innerHTML = 'Loading...';
                
                fetch(`/admin/keuangan/realisasi/drawer/${id}`)
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('drawer-content').innerHTML = html;
                    });
            }
        }">

        @if(session('success'))
            <div class="mb-4 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm font-bold shadow-sm">
                <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm font-bold shadow-sm">
                <i class="fa-solid fa-triangle-exclamation mr-2"></i>Terdapat kesalahan input:
                <ul class="list-disc pl-5 mt-1 text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="space-y-6">
            
            <!-- SECTION 1: SUMMARY CARDS -->
            @include('pages.backoffice.keuangan.realisasi._cards')

            <!-- SECTION 2: CHARTS & ALERTS -->
            @include('pages.backoffice.keuangan.realisasi._charts')

            <!-- SECTION 3: TABLE REALISASI -->
            @include('pages.backoffice.keuangan.realisasi._table')

        </div>

        <!-- MODALS & DRAWERS -->
        @include('pages.backoffice.keuangan.realisasi._modal_input')
        @include('pages.backoffice.keuangan.realisasi._drawer_detail')

    </main>
</div>
@endsection
