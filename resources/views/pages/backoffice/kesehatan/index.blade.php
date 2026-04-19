@extends('layouts.backoffice')

@section('title', 'Kesehatan & Stunting - Panel Administrasi')

@section('content')

<div x-data="{
        addModalOpen: {{ $errors->any() ? 'true' : 'false' }},
        detailDrawerOpen: false,
        detail: null,
        selectedPenduduk: null,

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

        async searchBalita(q) {
            if (q.length < 2) { this.searchResults = []; return; }
            try {
                const res = await fetch(`{{ route('admin.kesehatan.search-balita') }}?q=${encodeURIComponent(q)}`);
                this.searchResults = await res.json();
            } catch (e) { this.searchResults = []; }
        },

        searchResults: [],
        selectBalita(item) {
            this.selectedPenduduk = item;
            this.searchResults = [];
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
