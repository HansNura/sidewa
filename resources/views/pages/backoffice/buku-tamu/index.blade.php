@extends('layouts.backoffice')

@section('title', 'Laporan Buku Tamu - Panel Administrasi')

@section('content')
<div class="max-w-7xl mx-auto space-y-6" x-data="{ 
        detailDrawerOpen: false,
        analyticsDrawerOpen: false,
        reportModalOpen: false,
        
        detailData: null,
        loading: false,
        
        activePeriod: '{{ $periodInput }}',

        init() {
            this.$watch('activePeriod', value => {
                if(value) {
                    window.location.href = `{{ route('admin.buku-tamu.index') }}?period=${value}`;
                }
            });
        },

        async openDetailDrawer(id) {
            this.loading = true;
            this.detailDrawerOpen = true;
            
            try {
                const res = await fetch(`{{ url('admin/buku-tamu') }}/${id}/detail`, {
                    headers: { 'Accept': 'application/json' }
                });
                this.detailData = await res.json();
            } catch(e) {
                console.error(e);
            }
            this.loading = false;
        }
    }">

    {{-- SECTION: HEADER & DATE RANGE SELECTOR --}}
    <section class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Analisis Buku Tamu</h1>
            <p class="text-sm text-gray-500 mt-1">Rekapitulasi data kunjungan untuk evaluasi efektivitas pelayanan publik.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <div class="relative shrink-0">
                <select x-model="activePeriod"
                    class="bg-white border border-gray-200 rounded-xl pl-4 pr-10 py-2.5 shadow-sm text-sm font-bold text-gray-700 focus:ring-2 focus:ring-green-500 outline-none cursor-pointer appearance-none">
                    @foreach($monthOptions as $val => $label)
                        <option value="{{ $val }}" {{ $periodInput === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <i class="fa-solid fa-calendar-week absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
            </div>
            
            <button @click="analyticsDrawerOpen = true"
                class="bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 shadow-sm rounded-xl px-4 py-2.5 text-sm font-bold transition-all flex items-center gap-2">
                <i class="fa-solid fa-chart-pie"></i>
                <span class="hidden sm:inline">Analisis Lanjut</span>
            </button>

            <button @click="reportModalOpen = true"
                class="bg-green-700 hover:bg-green-800 text-white shadow-md rounded-xl px-5 py-2.5 text-sm font-bold transition-all flex items-center gap-2">
                <i class="fa-solid fa-file-export"></i>
                <span>Cetak Laporan</span>
            </button>
        </div>
    </section>

    {{-- SECTION: VISITOR SUMMARY CARDS --}}
    @include('pages.backoffice.buku-tamu._cards')

    {{-- SECTION: VISITOR TREND CHART --}}
    @include('pages.backoffice.buku-tamu._charts')

    {{-- SECTION: VISITOR LOG TABLE & FILTER --}}
    @include('pages.backoffice.buku-tamu._table')

    {{-- Overlay Modals & Drawers --}}
    @include('pages.backoffice.buku-tamu._drawer_detail')
    @include('pages.backoffice.buku-tamu._drawer_analytics')
    @include('pages.backoffice.buku-tamu._modal_export')

</div>
@endsection

@push('scripts')
<script src="https://code.highcharts.com/highcharts.js"></script>
@endpush
