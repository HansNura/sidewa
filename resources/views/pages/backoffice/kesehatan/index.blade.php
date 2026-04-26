@extends('layouts.backoffice')

@section('title', 'Kesehatan & Stunting - Panel Administrasi')

@section('content')

<div x-data="{
        addModalOpen: {{ $errors->any() ? 'true' : 'false' }},
        detailDrawerOpen: false,
        detail: null,
        selectedPenduduk: null,
        browseModalOpen: false,
        browseData: [],
        browseFilters: { q: '', jk: '', umur: '' },
        isBrowsing: false,

        tinggiBadan: '{{ old('tinggi_badan') }}',
        tanggalPengukuran: '{{ old('tanggal_pengukuran', now()->format('Y-m-d')) }}',
        statusGizi: '{{ old('status_gizi', 'normal') }}',
        zScoreResult: null,

        hitungStatusGizi() {
            if (!this.selectedPenduduk) { alert('Silakan pilih balita terlebih dahulu.'); return; }
            if (!this.tanggalPengukuran) { alert('Silakan isi tanggal pengukuran.'); return; }
            const tb = parseFloat(this.tinggiBadan);
            if (isNaN(tb)) { alert('Silakan isi tinggi badan dengan angka yang valid.'); return; }
            
            const tglLahir = new Date(this.selectedPenduduk.tanggal_lahir);
            const tglUkur = new Date(this.tanggalPengukuran);
            let umurBulan = (tglUkur.getFullYear() - tglLahir.getFullYear()) * 12 + (tglUkur.getMonth() - tglLahir.getMonth());
            if (tglUkur.getDate() < tglLahir.getDate()) umurBulan--;
            if (umurBulan < 0) umurBulan = 0;
            if (umurBulan > 60) umurBulan = 60;
            
            const jk = this.selectedPenduduk.jenis_kelamin;
            if (!window.whoStandards || !window.whoStandards[jk]) {
                alert('Data standar WHO gagal dimuat. Silakan muat ulang halaman.');
                return;
            }
            
            const std = window.whoStandards[jk][umurBulan];
            const z = (tb - std.m) / std.sd;
            
            let status = '';
            if (z < -3) status = 'sangat_pendek';
            else if (z < -2) status = 'pendek';
            else if (z <= 3) status = 'normal';
            else status = 'tinggi';
            
            this.statusGizi = status;
            this.zScoreResult = { z: z.toFixed(2), umur: umurBulan, status: status, sd: std.sd.toFixed(2), m: std.m.toFixed(2) };
        },

        async openDetail(pendudukId) {
            try {
                const res = await fetch(`{{ url('admin/kesehatan') }}/${pendudukId}`);
                if (!res.ok) throw new Error('Failed');
                this.detail = await res.json();
                this.detailDrawerOpen = true;
            } catch (e) {
                console.error('Failed to load detail:', e);
            }
        },

        async openBrowseModal() {
            this.browseModalOpen = true;
            this.fetchBrowseData();
        },

        async fetchBrowseData() {
            this.isBrowsing = true;
            const params = new URLSearchParams({ mode: 'browse', ...this.browseFilters });
            try {
                const res = await fetch(`{{ route('admin.kesehatan.search-balita') }}?${params.toString()}`);
                this.browseData = await res.json();
            } catch (e) { this.browseData = []; }
            this.isBrowsing = false;
        },

        selectFromBrowse(item) {
            this.selectedPenduduk = item;
            this.browseModalOpen = false;
        }
     }"
     class="space-y-6">

    {{-- Flash Messages --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="bg-green-50 border border-green-200 text-green-800 rounded-2xl p-4 flex items-start gap-3 shadow-sm">
            <i class="fa-solid fa-circle-check text-green-600 mt-0.5"></i>
            <div class="flex-1"><p class="text-sm font-semibold">{{ session('success') }}</p></div>
            <button @click="show = false" class="text-green-400 hover:text-green-600 cursor-pointer"><i class="fa-solid fa-xmark"></i></button>
        </div>
    @endif

    {{-- Page Header --}}
    @include('pages.backoffice.kesehatan._header')

    {{-- KPI Cards --}}
    @include('pages.backoffice.kesehatan._kpi')

    {{-- Charts --}}
    @include('pages.backoffice.kesehatan._charts')

    {{-- Table + Monitoring --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        @include('pages.backoffice.kesehatan._table')
        @include('pages.backoffice.kesehatan._monitoring')
    </div>

    {{-- Modals & Drawers --}}
    @include('pages.backoffice.kesehatan._form-modal')
    @include('pages.backoffice.kesehatan._detail-drawer')

</div>

@push('scripts')
<script>
window.whoStandards = {
    'L': [ // Laki-laki (0-60 bulan) TB/U
        {m: 49.9, sd: 1.89}, {m: 54.7, sd: 1.95}, {m: 58.4, sd: 1.99}, {m: 61.4, sd: 2.02}, {m: 63.9, sd: 2.05}, {m: 65.9, sd: 2.07}, {m: 67.6, sd: 2.10}, {m: 69.2, sd: 2.12}, {m: 70.6, sd: 2.14}, {m: 72.0, sd: 2.17}, {m: 73.3, sd: 2.19}, {m: 74.5, sd: 2.22}, {m: 75.7, sd: 2.25}, {m: 76.9, sd: 2.28}, {m: 78.0, sd: 2.31}, {m: 79.1, sd: 2.35}, {m: 80.2, sd: 2.38}, {m: 81.2, sd: 2.42}, {m: 82.3, sd: 2.46}, {m: 83.2, sd: 2.49}, {m: 84.2, sd: 2.53}, {m: 85.1, sd: 2.57}, {m: 86.0, sd: 2.61}, {m: 86.9, sd: 2.65}, {m: 87.8, sd: 2.69}, {m: 88.4, sd: 3.19}, {m: 89.2, sd: 3.23}, {m: 89.9, sd: 3.27}, {m: 90.6, sd: 3.32}, {m: 91.2, sd: 3.36}, {m: 91.9, sd: 3.41}, {m: 92.5, sd: 3.45}, {m: 93.1, sd: 3.50}, {m: 93.7, sd: 3.55}, {m: 94.3, sd: 3.59}, {m: 94.9, sd: 3.64}, {m: 95.4, sd: 3.69}, {m: 95.9, sd: 3.73}, {m: 96.5, sd: 3.78}, {m: 97.0, sd: 3.82}, {m: 97.5, sd: 3.87}, {m: 98.0, sd: 3.91}, {m: 98.5, sd: 3.96}, {m: 99.0, sd: 4.00}, {m: 99.5, sd: 4.04}, {m: 99.9, sd: 4.09}, {m: 100.4, sd: 4.13}, {m: 100.8, sd: 4.17}, {m: 101.3, sd: 4.22}, {m: 101.7, sd: 4.26}, {m: 102.1, sd: 4.30}, {m: 102.5, sd: 4.34}, {m: 103.0, sd: 4.38}, {m: 103.4, sd: 4.42}, {m: 103.8, sd: 4.46}, {m: 104.2, sd: 4.50}, {m: 104.6, sd: 4.54}, {m: 105.0, sd: 4.58}, {m: 105.4, sd: 4.62}, {m: 105.8, sd: 4.65}, {m: 106.2, sd: 4.69}
    ],
    'P': [ // Perempuan (0-60 bulan) TB/U
        {m: 49.1, sd: 1.83}, {m: 53.7, sd: 1.89}, {m: 57.1, sd: 1.93}, {m: 59.8, sd: 1.96}, {m: 62.1, sd: 1.99}, {m: 64.0, sd: 2.01}, {m: 65.7, sd: 2.03}, {m: 67.3, sd: 2.05}, {m: 68.7, sd: 2.08}, {m: 70.1, sd: 2.10}, {m: 71.5, sd: 2.13}, {m: 72.8, sd: 2.16}, {m: 74.0, sd: 2.19}, {m: 75.2, sd: 2.22}, {m: 76.4, sd: 2.25}, {m: 77.5, sd: 2.29}, {m: 78.6, sd: 2.32}, {m: 79.7, sd: 2.36}, {m: 80.7, sd: 2.40}, {m: 81.7, sd: 2.44}, {m: 82.7, sd: 2.48}, {m: 83.7, sd: 2.52}, {m: 84.6, sd: 2.56}, {m: 85.5, sd: 2.60}, {m: 86.4, sd: 2.65}, {m: 87.1, sd: 3.12}, {m: 87.8, sd: 3.17}, {m: 88.5, sd: 3.22}, {m: 89.2, sd: 3.27}, {m: 89.9, sd: 3.32}, {m: 90.6, sd: 3.37}, {m: 91.2, sd: 3.42}, {m: 91.9, sd: 3.47}, {m: 92.5, sd: 3.52}, {m: 93.1, sd: 3.57}, {m: 93.8, sd: 3.62}, {m: 94.4, sd: 3.67}, {m: 95.0, sd: 3.72}, {m: 95.6, sd: 3.77}, {m: 96.2, sd: 3.82}, {m: 96.7, sd: 3.87}, {m: 97.3, sd: 3.92}, {m: 97.9, sd: 3.97}, {m: 98.4, sd: 4.02}, {m: 99.0, sd: 4.07}, {m: 99.5, sd: 4.12}, {m: 100.1, sd: 4.17}, {m: 100.6, sd: 4.22}, {m: 101.1, sd: 4.27}, {m: 101.6, sd: 4.31}, {m: 102.1, sd: 4.36}, {m: 102.6, sd: 4.41}, {m: 103.1, sd: 4.46}, {m: 103.6, sd: 4.51}, {m: 104.1, sd: 4.55}, {m: 104.6, sd: 4.60}, {m: 105.0, sd: 4.65}, {m: 105.5, sd: 4.69}, {m: 105.9, sd: 4.74}, {m: 106.4, sd: 4.78}, {m: 106.8, sd: 4.83}
    ]
};

document.addEventListener('DOMContentLoaded', function () {
    if (typeof Highcharts === 'undefined') return;

    Highcharts.setOptions({
        chart: { style: { fontFamily: 'Inter, sans-serif' }, backgroundColor: 'transparent' },
        title: { text: null },
        credits: { enabled: false },
        tooltip: { backgroundColor: '#1f2937', style: { color: '#ffffff' }, borderWidth: 0, borderRadius: 8 }
    });

    // Trend line chart
    Highcharts.chart('chartTren', {
        chart: { type: 'areaspline' },
        xAxis: { categories: @json($trendLabels), gridLineWidth: 0 },
        yAxis: { title: { text: 'Jumlah Kasus' }, gridLineColor: '#f3f4f6' },
        plotOptions: {
            areaspline: { fillOpacity: 0.1, marker: { radius: 4, symbol: 'circle' }, lineWidth: 3 }
        },
        legend: { enabled: false },
        series: [{ name: 'Balita Stunting', data: @json($trendData), color: '#ef4444' }]
    });

    // Donut chart
    Highcharts.chart('chartGizi', {
        chart: { type: 'pie' },
        plotOptions: {
            pie: { innerSize: '60%', dataLabels: { enabled: false }, showInLegend: true, borderWidth: 2, borderColor: '#ffffff' }
        },
        legend: { layout: 'vertical', align: 'right', verticalAlign: 'middle', itemStyle: { fontSize: '11px' } },
        series: [{ name: 'Jumlah Balita', data: @json($giziDistribution) }]
    });
});
</script>
@endpush

@endsection
