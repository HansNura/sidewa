@extends('layouts.backoffice')

@section('title', 'Rekap Kehadiran Pegawai - Panel Administrasi')

@section('content')
<div class="max-w-7xl mx-auto space-y-6" x-data="{ 
        detailDrawerOpen: false,
        reportModalOpen: false,
        
        // Helper untuk backend communication
        loading: false,
        activeUserId: null,
        drawerData: null,
        
        // Parameter pencarian (Bulan & Tahun format Y-m)
        activePeriod: '{{ $periodInput }}',

        init() {
            this.$watch('activePeriod', value => {
                if(value) {
                    window.location.href = `{{ route('admin.presensi.rekap') }}?period=${value}`;
                }
            });
        },

        async openDrawer(userId) {
            this.activeUserId = userId;
            this.loading = true;
            this.detailDrawerOpen = true;
            
            try {
                const res = await fetch(`{{ url('admin/presensi/rekap') }}/${userId}/info?period=${this.activePeriod}`, {
                    headers: { 'Accept': 'application/json' }
                });
                this.drawerData = await res.json();

                // Trigger chart rendering in drawer if Highcharts exists
                if(typeof Highcharts !== 'undefined' && this.drawerData.chart) {
                    this.$nextTick(() => {
                        this.renderPersonalChart(this.drawerData.chart);
                    });
                }
            } catch(e) {
                console.error(e);
            }
            this.loading = false;
        },

        renderPersonalChart(chartData) {
            Highcharts.chart('personalAttendanceChart', {
                chart: { type: 'column', backgroundColor: 'transparent', style: { fontFamily: 'Inter, sans-serif' } },
                title: { text: null },
                xAxis: { categories: chartData.categories, gridLineWidth: 0 },
                yAxis: { title: { text: 'Hari Kerja' }, gridLineColor: '#f1f5f9' },
                legend: { enabled: false },
                credits: { enabled: false },
                series: [{
                    name: 'Kehadiran (Hadir/Terlambat)',
                    data: chartData.data,
                    color: '#15803d',
                    borderRadius: 4
                }]
            });
        }
    }">

    {{-- SECTION: HEADER & DATE RANGE FILTER --}}
    <section class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Rekapitulasi Kehadiran</h1>
            <p class="text-sm text-gray-500 mt-1">Laporan kumulatif performa kehadiran pegawai desa ({{ $activePeriod }}).</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <div class="relative shrink-0">
                <select x-model="activePeriod"
                    class="bg-white border border-gray-200 rounded-xl pl-4 pr-10 py-2.5 shadow-sm text-sm font-bold text-gray-700 focus:ring-2 focus:ring-green-500 outline-none cursor-pointer appearance-none">
                    @foreach($monthOptions as $val => $label)
                        <option value="{{ $val }}" {{ $periodInput === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <i class="fa-solid fa-calendar-days absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
            </div>

            <button @click="reportModalOpen = true"
                class="bg-green-700 hover:bg-green-800 text-white shadow-md rounded-xl px-5 py-2.5 text-sm font-bold transition-all flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-file-invoice"></i>
                <span>Generate Laporan</span>
            </button>
        </div>
    </section>

    {{-- SECTION: REKAP SUMMARY CARDS --}}
    @include('pages.backoffice.presensi.rekap._cards')

    {{-- SECTION: VISUALIZATION CHART --}}
    @include('pages.backoffice.presensi.rekap._chart')

    {{-- SECTION: ATTENDANCE RECAP TABLE --}}
    @include('pages.backoffice.presensi.rekap._table')

    {{-- SECTION: EXPORT ACTION PANEL (Optional Call to action) --}}
    <section class="bg-green-700 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden group">
        <div class="absolute right-0 top-0 text-white/5 group-hover:scale-110 transition-transform duration-700">
            <i class="fa-solid fa-file-contract text-[12rem] -mt-10 -mr-10"></i>
        </div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="max-w-xl">
                <h3 class="text-xl font-extrabold mb-1">Cetak Rekap Bulanan Cepat</h3>
                <p class="text-green-100 text-sm">Download laporan rekapitulasi seluruh pegawai dalam format PDF atau Excel yang sudah dioptimasi untuk pencetakan dokumen fisik.</p>
            </div>
            <div class="flex gap-3 shrink-0">
                <form action="{{ route('admin.presensi.rekap.export') }}" method="POST" target="_blank">
                    @csrf
                    <input type="hidden" name="period" value="{{ $periodInput }}">
                    <input type="hidden" name="type" value="pdf">
                    <button type="submit" class="px-6 py-3 bg-white text-green-700 rounded-xl font-bold text-sm shadow-md hover:bg-green-50 transition-colors flex items-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-file-pdf"></i> Export PDF
                    </button>
                </form>
                <form action="{{ route('admin.presensi.rekap.export') }}" method="POST" target="_blank">
                    @csrf
                    <input type="hidden" name="period" value="{{ $periodInput }}">
                    <input type="hidden" name="type" value="excel">
                    <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-xl font-bold text-sm shadow-md hover:bg-blue-700 transition-colors flex items-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-file-excel"></i> Export Excel
                    </button>
                </form>
            </div>
        </div>
    </section>

    {{-- Overlay Modals & Drawers --}}
    @include('pages.backoffice.presensi.rekap._drawer')
    @include('pages.backoffice.presensi.rekap._modal')

</div>
@endsection

@push('scripts')
<script src="https://code.highcharts.com/highcharts.js"></script>
@endpush
