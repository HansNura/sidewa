@extends('layouts.backoffice')

@section('title', 'Dashboard Layanan Surat - Panel Administrasi')

@section('content')

<div x-data="{
        addModalOpen: {{ $errors->any() ? 'true' : 'false' }},
        statusModalOpen: false,
        statusTarget: null,
        selectedPenduduk: null,
        searchResults: [],

        openStatusModal(id, tiket, currentStatus) {
            this.statusTarget = { id, tiket, currentStatus };
            this.statusModalOpen = true;
        },

        async searchPenduduk(q) {
            if (q.length < 2) { this.searchResults = []; return; }
            try {
                const res = await fetch(`{{ route('admin.layanan-surat.search-penduduk') }}?q=${encodeURIComponent(q)}`);
                this.searchResults = await res.json();
            } catch (e) { this.searchResults = []; }
        },

        selectPenduduk(item) {
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
    @include('pages.backoffice.layanan-surat._header')

    {{-- Bottleneck Alert --}}
    @include('pages.backoffice.layanan-surat._alert')

    {{-- KPI Cards --}}
    @include('pages.backoffice.layanan-surat._kpi')

    {{-- Status Pipeline --}}
    @include('pages.backoffice.layanan-surat._pipeline')

    {{-- Main Grid: Queue + Activity --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 space-y-6 flex flex-col">
            @include('pages.backoffice.layanan-surat._queue')
            @include('pages.backoffice.layanan-surat._chart')
        </div>
        <div class="xl:col-span-1 flex flex-col">
            @include('pages.backoffice.layanan-surat._timeline')
        </div>
    </div>

    {{-- Modals --}}
    @include('pages.backoffice.layanan-surat._form-modal')
    @include('pages.backoffice.layanan-surat._status-modal')

</div>

@endsection

@push('scripts')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof Highcharts === 'undefined') return;

    Highcharts.setOptions({
        chart: { style: { fontFamily: 'Inter, sans-serif' }, backgroundColor: 'transparent' },
        title: { text: null },
        credits: { enabled: false },
        tooltip: { backgroundColor: '#1f2937', style: { color: '#ffffff' }, borderWidth: 0, borderRadius: 8 }
    });

    const chartData = @json($chartData);

    Highcharts.chart('chartJenisSurat', {
        chart: { type: 'bar' },
        xAxis: {
            categories: chartData.map(d => d.name),
            title: { text: null },
            gridLineWidth: 0,
            lineWidth: 0
        },
        yAxis: {
            min: 0,
            title: { text: 'Jumlah Pemohon', align: 'high' },
            labels: { overflow: 'justify' },
            gridLineColor: '#f3f4f6'
        },
        plotOptions: {
            bar: { dataLabels: { enabled: true }, borderRadius: 4, borderWidth: 0 }
        },
        legend: { enabled: false },
        series: [{
            name: 'Jumlah Surat',
            data: chartData.map(d => d.count),
            color: '#16a34a'
        }]
    });
});
</script>
@endpush
