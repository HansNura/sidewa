@extends('layouts.backoffice')

@section('title', 'Dashboard Eksekutif - Panel Administrasi')

@section('content')

    {{-- HEADER --}}
    <section class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Dasbor Eksekutif</h1>
            <p class="text-sm text-gray-500 mt-1">Ringkasan strategis kependudukan, keuangan, layanan, dan pembangunan.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <div class="bg-white border border-gray-200 rounded-xl px-4 py-2 flex items-center gap-2 shadow-sm text-sm font-medium text-gray-700">
                <i class="fa-regular fa-calendar text-gray-400"></i>
                <span>Tahun {{ $tahun }}</span>
            </div>
        </div>
    </section>

    {{-- INSIGHT CERDAS --}}
    <section class="bg-gradient-to-r from-blue-50 to-indigo-50/50 border border-blue-100 rounded-2xl p-5 shadow-sm flex flex-col sm:flex-row gap-4 items-start sm:items-center relative overflow-hidden">
        <div class="absolute right-0 top-0 w-32 h-32 bg-blue-500/5 rounded-full blur-3xl -z-0"></div>
        <div class="bg-blue-100 text-blue-600 p-3 rounded-full shrink-0 relative z-10">
            <i class="fa-solid fa-wand-magic-sparkles text-xl"></i>
        </div>
        <div class="flex-1 relative z-10">
            <h4 class="font-bold text-blue-900 text-sm mb-1 flex items-center gap-2">
                Insight Cerdas Hari Ini
                <span class="bg-blue-200 text-blue-800 text-[10px] px-2 py-0.5 rounded-full uppercase tracking-wide">AI Generatif</span>
            </h4>
            <p class="text-sm text-blue-800/90 leading-relaxed">{!! $insightText !!}</p>
        </div>
    </section>

    {{-- KPI SUMMARY CARDS --}}
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 sm:gap-6">

        {{-- Penduduk --}}
        <article class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:-translate-y-1 transition-transform duration-300 group relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-16 h-16 bg-blue-50 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wider">Total Penduduk</p>
                    <h3 class="text-2xl font-extrabold text-gray-900">{{ number_format($totalPenduduk) }}</h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0 shadow-inner"><i class="fa-solid fa-users"></i></div>
            </div>
            <div class="mt-3 flex items-center text-xs font-medium text-gray-500 relative z-10">
                <span class="{{ $pertumbuhanPct >= 0 ? 'text-green-600' : 'text-red-600' }} flex items-center">
                    <i class="fa-solid fa-arrow-trend-{{ $pertumbuhanPct >= 0 ? 'up' : 'down' }} mr-1"></i>{{ $pertumbuhanPct >= 0 ? '+' : '' }}{{ $pertumbuhanPct }}%
                </span>
                <span class="ml-1.5">vs Tahun Lalu</span>
            </div>
        </article>

        {{-- KK --}}
        <article class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:-translate-y-1 transition-transform duration-300 group relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-16 h-16 bg-emerald-50 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wider">Total KK</p>
                    <h3 class="text-2xl font-extrabold text-gray-900">{{ number_format($totalKK) }}</h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 shadow-inner"><i class="fa-solid fa-house-chimney"></i></div>
            </div>
            <div class="mt-3 text-xs text-gray-500 font-medium relative z-10">Tersebar di {{ $totalRT }} RT</div>
        </article>

        {{-- Surat --}}
        <article class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:-translate-y-1 transition-transform duration-300 group relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-16 h-16 bg-amber-50 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wider">Surat Diproses</p>
                    <h3 class="text-2xl font-extrabold text-gray-900">{{ $suratBulanIni }}</h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center shrink-0 shadow-inner"><i class="fa-solid fa-file-signature"></i></div>
            </div>
            @if($suratNeedTTE > 0)
            <div class="mt-3 flex items-center text-xs text-amber-600 font-bold relative z-10">
                <i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $suratNeedTTE }} Butuh TTE Anda
            </div>
            @endif
        </article>

        {{-- APBDes --}}
        <article class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:-translate-y-1 transition-transform duration-300 group relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-16 h-16 bg-green-50 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wider">Serapan APBDes</p>
                    <h3 class="text-2xl font-extrabold text-gray-900">{{ $serapanPct }}%</h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center shrink-0 shadow-inner"><i class="fa-solid fa-chart-line"></i></div>
            </div>
            <div class="mt-4 w-full bg-gray-100 rounded-full h-1.5 relative z-10">
                <div class="bg-green-500 h-1.5 rounded-full" style="width: {{ $serapanPct }}%"></div>
            </div>
        </article>

        {{-- UMKM --}}
        <article class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:-translate-y-1 transition-transform duration-300 group relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-16 h-16 bg-purple-50 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wider">UMKM Aktif</p>
                    <h3 class="text-2xl font-extrabold text-gray-900">{{ $umkmAktif }}</h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center shrink-0 shadow-inner"><i class="fa-solid fa-store"></i></div>
            </div>
        </article>

        {{-- Stunting --}}
        <article class="bg-red-50/50 rounded-2xl p-5 shadow-sm border border-red-100 hover:-translate-y-1 transition-transform duration-300 group relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-16 h-16 bg-red-100 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-bold text-red-600 mb-1 uppercase tracking-wider">Kasus Stunting</p>
                    <h3 class="text-2xl font-extrabold text-red-700">{{ $stuntingCount }}</h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-red-200 text-red-700 flex items-center justify-center shrink-0 shadow-inner"><i class="fa-solid fa-child-reaching"></i></div>
            </div>
        </article>
    </section>

    {{-- STATISTIK KEPENDUDUKAN --}}
    <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col lg:col-span-2">
            <div class="flex justify-between items-center mb-4 border-b border-gray-50 pb-4">
                <div>
                    <h3 class="font-bold text-gray-800">Demografi & Tren Pertumbuhan</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Sebaran usia dan gender penduduk aktif.</p>
                </div>
            </div>
            <div id="chartDemografiGabungan" class="w-full h-80 flex-1"></div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col">
            <div class="flex justify-between items-center mb-4 border-b border-gray-50 pb-4">
                <h3 class="font-bold text-gray-800">Distribusi Pendidikan</h3>
            </div>
            <div id="chartPendidikan" class="w-full h-80 flex-1"></div>
        </div>
    </section>

    {{-- KEUANGAN & LAYANAN SURAT --}}
    <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Realisasi APBDes --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 lg:col-span-1 flex flex-col">
            <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 rounded-t-2xl">
                <h3 class="font-bold text-gray-800">Realisasi APBDes</h3>
                <a href="{{ route('admin.apbdes.index') }}" class="text-xs font-semibold text-green-600 hover:text-green-800">Detail &rarr;</a>
            </div>
            <div class="p-5 flex-1 flex flex-col">
                <div id="chartKeuangan" class="w-full h-48 mb-6"></div>
                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">Top 5 Pengeluaran Berjalan</h4>
                <ul class="space-y-4">
                    @foreach($topPengeluaran as $item)
                    <li>
                        <div class="flex justify-between text-sm mb-1.5">
                            <span class="font-semibold text-gray-700">{{ $item['nama'] }}</span>
                            <span class="text-gray-900 font-bold">{{ $item['pct'] }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="{{ $item['color'] }} h-2 rounded-full" style="width: {{ $item['pct'] }}%"></div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- Layanan Surat Insight --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 lg:col-span-2 flex flex-col">
            <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 rounded-t-2xl">
                <div>
                    <h3 class="font-bold text-gray-800">Performa Layanan Surat</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Metrik kualitas dan kecepatan layanan mandiri warga.</p>
                </div>
            </div>
            <div class="p-6 flex flex-col md:flex-row gap-6">
                <div class="w-full md:w-1/2 flex flex-col">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Sebaran Jenis Layanan</h4>
                    <div id="chartSuratPie" class="w-full h-56 flex-1"></div>
                </div>
                <div class="w-full md:w-1/2 flex flex-col gap-4">
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase">Rata-Rata Penyelesaian</p>
                            <div class="flex items-end gap-2 mt-1">
                                <span class="text-3xl font-extrabold text-gray-900">{{ $avgSelesai }}</span>
                                <span class="text-sm text-gray-500 font-medium mb-1">Hari</span>
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-stopwatch text-xl"></i>
                        </div>
                    </div>

                    @if($suratNeedTTE > 0)
                    <div class="bg-green-700 text-white rounded-xl p-5 shadow-md relative overflow-hidden group">
                        <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
                        <i class="fa-solid fa-signature absolute right-2 bottom-2 text-6xl opacity-10"></i>
                        <div class="relative z-10">
                            <h4 class="font-semibold text-green-100 text-sm">Menunggu Otorisasi Kades</h4>
                            <div class="mt-1 text-3xl font-bold flex items-center gap-3">
                                {{ $suratNeedTTE }} <span class="text-sm font-normal text-green-200">Dokumen</span>
                            </div>
                            <a href="{{ route('admin.verifikasi-surat.index') }}" class="mt-4 inline-flex items-center gap-2 text-xs font-bold bg-white text-green-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                                Lakukan TTE Sekarang <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- PEMBANGUNAN & PETA --}}
    <section class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:justify-between sm:items-center bg-gray-50/50">
            <div>
                <h3 class="font-bold text-gray-800">Pemetaan & Proyek Pembangunan</h3>
                <p class="text-xs text-gray-500 mt-0.5">Visualisasi geospasial infrastruktur desa.</p>
            </div>
            <div class="flex gap-2 mt-3 sm:mt-0">
                <span class="bg-amber-100 text-amber-700 text-xs font-bold px-3 py-1.5 rounded-lg flex items-center gap-1.5 border border-amber-200">
                    <i class="fa-solid fa-person-digging"></i> {{ $proyekAktifCount }} Proyek Aktif
                </span>
                <span class="bg-purple-100 text-purple-700 text-xs font-bold px-3 py-1.5 rounded-lg flex items-center gap-1.5 border border-purple-200">
                    <i class="fa-solid fa-store"></i> {{ $umkmAktif }} Titik UMKM
                </span>
            </div>
        </div>
        <div class="flex flex-col lg:flex-row h-auto lg:h-[400px]">
            <div class="w-full lg:w-1/3 bg-white border-r border-gray-100 p-5 overflow-y-auto custom-scrollbar">
                <h4 class="text-sm font-bold text-gray-800 mb-4">Highlight Proyek Prioritas</h4>
                <div class="space-y-4">
                    @forelse($proyekAktif as $proyek)
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 hover:border-green-300 transition-colors cursor-pointer">
                        <div class="flex gap-3">
                            <div class="w-14 h-14 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center shrink-0 shadow-sm">
                                <i class="fa-solid fa-person-digging text-2xl"></i>
                            </div>
                            <div class="flex-1">
                                <h5 class="text-sm font-bold text-gray-900 leading-tight">{{ $proyek['nama'] }}</h5>
                                <p class="text-[10px] text-gray-500 mt-1">Status: {{ ucfirst($proyek['status']) }}</p>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="flex justify-between text-xs font-semibold mb-1">
                                <span class="text-gray-700">Progress</span>
                                <span class="{{ $proyek['progres'] >= 50 ? 'text-green-600' : 'text-amber-600' }}">{{ $proyek['progres'] }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-1.5">
                                <div class="{{ $proyek['progres'] >= 50 ? 'bg-green-500' : 'bg-amber-500' }} h-1.5 rounded-full" style="width: {{ $proyek['progres'] }}%"></div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-400 text-sm">
                        <i class="fa-solid fa-hard-hat text-2xl mb-2"></i>
                        <p>Belum ada proyek aktif dengan koordinat.</p>
                    </div>
                    @endforelse
                </div>
            </div>
            <div class="w-full lg:w-2/3 relative h-64 lg:h-full">
                <div id="mapOverview" class="absolute inset-0 z-0"></div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ══════════════════════════════════════════════════
    // HIGHCHARTS CONFIG
    // ══════════════════════════════════════════════════

    if (typeof Highcharts !== 'undefined') {
        Highcharts.setOptions({
            colors: ['#16a34a', '#3b82f6', '#f59e0b', '#ec4899', '#8b5cf6', '#14b8a6'],
            chart: { style: { fontFamily: 'Inter, sans-serif' }, backgroundColor: 'transparent' },
            title: { text: null },
            credits: { enabled: false },
            legend: { itemStyle: { fontWeight: '500', color: '#4b5563', fontSize: '11px' } },
            tooltip: { backgroundColor: '#1f2937', style: { color: '#ffffff' }, borderWidth: 0, borderRadius: 8 }
        });

        // 1. Demografi
        Highcharts.chart('chartDemografiGabungan', {
            chart: { zoomType: 'xy' },
            xAxis: [{ categories: {!! json_encode($chartDemografi['categories']) !!}, crosshair: true }],
            yAxis: [{ title: { text: 'Jumlah Jiwa', style: { color: '#4b5563' } } }],
            plotOptions: { column: { borderRadius: 3, borderWidth: 0 } },
            series: [
                { name: 'Laki-laki', type: 'column', data: {!! json_encode($chartDemografi['laki']) !!}, color: '#3b82f6' },
                { name: 'Perempuan', type: 'column', data: {!! json_encode($chartDemografi['perempuan']) !!}, color: '#ec4899' }
            ]
        });

        // 2. Pendidikan Pie
        Highcharts.chart('chartPendidikan', {
            chart: { type: 'pie' },
            plotOptions: {
                pie: { allowPointSelect: true, cursor: 'pointer',
                    dataLabels: { enabled: true, format: '<b>{point.name}</b>: {point.percentage:.1f} %', style: { fontSize: '10px' } },
                    showInLegend: false }
            },
            series: [{ name: 'Jumlah', colorByPoint: true, data: {!! json_encode($chartPendidikan) !!} }]
        });

        // 3. Keuangan Donut
        Highcharts.chart('chartKeuangan', {
            chart: { type: 'pie' },
            plotOptions: { pie: { innerSize: '75%', borderRadius: 4, dataLabels: { enabled: false }, borderWidth: 0 } },
            series: [{ name: 'Persentase', data: {!! json_encode($chartKeuangan) !!} }],
            title: {
                text: '<div style="text-align:center"><span style="font-size:28px;font-weight:800;color:#111827">{{ $serapanPct }}%</span><br/><span style="font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Terserap</span></div>',
                align: 'center', verticalAlign: 'middle', useHTML: true, y: 15
            }
        });

        // 4. Surat Pie
        Highcharts.chart('chartSuratPie', {
            chart: { type: 'pie' },
            plotOptions: { pie: { innerSize: '50%', borderRadius: 2, dataLabels: { enabled: false }, showInLegend: true } },
            legend: { align: 'right', verticalAlign: 'middle', layout: 'vertical' },
            series: [{ name: 'Total Pengajuan', data: {!! json_encode($chartSuratPie) !!} }]
        });
    }

    // ══════════════════════════════════════════════════
    // LEAFLET MAP
    // ══════════════════════════════════════════════════

    if (typeof L !== 'undefined') {
        var proyekData = {!! json_encode($proyekAktif) !!};
        var defaultLat = -7.1726, defaultLng = 108.1963;

        if (proyekData.length > 0) {
            defaultLat = proyekData[0].lat;
            defaultLng = proyekData[0].lng;
        }

        var map = L.map('mapOverview', { zoomControl: false, attributionControl: false })
            .setView([defaultLat, defaultLng], 14);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { maxZoom: 19 }).addTo(map);
        L.control.zoom({ position: 'bottomright' }).addTo(map);

        var projectIcon = L.divIcon({
            className: 'custom-icon',
            html: '<div style="background-color:#f59e0b; color:white; width:28px; height:28px; border-radius:50%; display:flex; align-items:center; justify-content:center; border:2px solid white; box-shadow:0 3px 6px rgba(0,0,0,0.2);"><i class="fa-solid fa-person-digging text-[12px]"></i></div>',
            iconSize: [28, 28], iconAnchor: [14, 14]
        });

        proyekData.forEach(function(p) {
            L.marker([p.lat, p.lng], { icon: projectIcon })
                .addTo(map)
                .bindPopup('<b>' + p.nama + '</b><br>Progress: ' + p.progres + '%');
        });
    }
});
</script>
@endpush
