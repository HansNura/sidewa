@extends('layouts.backoffice')

@section('title', 'Laporan Statistik Desa - Panel Administrasi')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    </style>
@endpush

@section('content')
<div class="flex-1 flex flex-col h-full overflow-hidden w-full relative bg-[#F8FAFC]">

    <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 sm:px-6 shrink-0 z-10 shadow-sm mt-0">
        <div class="flex items-center gap-4">
            <div class="hidden sm:flex items-center gap-2">
                <span class="text-xs font-semibold px-2.5 py-1 bg-amber-100 text-amber-700 rounded-md uppercase tracking-wider">Akses Eksekutif</span>
                <h2 class="font-bold text-gray-800">Pusat Data & Analitik</h2>
            </div>
        </div>
        
        <!-- Profile Info mock -->
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2.5">
                <img src="https://ui-avatars.com/api/?name=Admin&background=fbc02d&color=000" class="w-8 h-8 rounded-full shadow-sm">
            </div>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto custom-scrollbar p-4 sm:p-6 lg:p-8" x-data="{
        filterTahun: '{{ $yearFilter }}',
        filterWilayah: '{{ strtolower($dusunFilter) }}'
    }">
        <div class="max-w-7xl mx-auto space-y-6">

            <section class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Laporan Statistik Desa</h1>
                    <p class="text-sm text-gray-500 mt-1">Ringkasan demografi, sosial, dan kesehatan untuk analisa kebijakan pembangunan.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <button class="bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 shadow-sm rounded-xl px-4 py-2.5 text-sm font-bold transition-all flex items-center gap-2">
                        <i class="fa-solid fa-share-nodes text-blue-500"></i> <span class="hidden sm:inline">Bagikan</span>
                    </button>
                    <div class="flex rounded-xl shadow-md border border-gray-200 overflow-hidden bg-primary-700">
                        <button class="px-5 py-2.5 text-sm font-bold text-white hover:bg-primary-800 transition-colors flex items-center gap-2 border-r border-primary-600">
                            <i class="fa-solid fa-file-pdf"></i> Export PDF
                        </button>
                        <button class="px-5 py-2.5 text-sm font-bold text-white hover:bg-primary-800 transition-colors flex items-center gap-2">
                            <i class="fa-solid fa-file-excel"></i> Excel
                        </button>
                    </div>
                </div>
            </section>

            <section class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col sm:flex-row gap-4 justify-between items-center">
                <form action="{{ route('admin.statistik.index') }}" method="GET" class="w-full flex flex-col sm:flex-row gap-4 justify-between items-center" id="filterForm">
                    <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
                        <div class="relative w-full sm:w-48">
                            <i class="fa-solid fa-calendar-days absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <select name="tahun" x-model="filterTahun" class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none cursor-pointer font-bold text-gray-700">
                                @for($y = date('Y'); $y >= 2022; $y--)
                                <option value="{{ $y }}">Tahun {{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="relative w-full sm:w-56">
                            <i class="fa-solid fa-location-dot absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <select name="wilayah" x-model="filterWilayah" class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none cursor-pointer font-medium text-gray-700">
                                <option value="semua">Seluruh Wilayah Desa</option>
                                <option value="kaler">Dusun Kaler</option>
                                <option value="kidul">Dusun Kidul</option>
                                <option value="wetan">Dusun Wetan</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="w-full sm:w-auto bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-xl font-bold text-sm transition-colors flex justify-center gap-2">
                        <i class="fa-solid fa-filter"></i> Terapkan Filter
                    </button>
                </form>
            </section>

            @if($insightStunting)
            <section class="bg-gradient-to-r from-blue-50 to-indigo-50/50 border border-blue-100 rounded-2xl p-5 shadow-sm flex flex-col sm:flex-row gap-4 items-start sm:items-center relative overflow-hidden">
                <div class="absolute right-0 top-0 w-32 h-32 bg-blue-500/5 rounded-full blur-3xl -z-0"></div>
                <div class="bg-blue-100 text-blue-600 p-3 rounded-full shrink-0 relative z-10 shadow-sm border border-white">
                    <i class="fa-solid fa-lightbulb text-xl"></i>
                </div>
                <div class="flex-1 relative z-10">
                    <h4 class="font-bold text-blue-900 text-sm mb-1 flex items-center gap-2">
                        Insight Analitik Otomatis
                        <span class="bg-blue-200 text-blue-800 text-[9px] px-2 py-0.5 rounded uppercase tracking-widest font-bold">Ringkasan</span>
                    </h4>
                    <p class="text-sm text-blue-800/90 leading-relaxed">{{ $insightStunting }}</p>
                </div>
            </section>
            @endif

            <section class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between group hover:-translate-y-1 transition-transform">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Penduduk</p>
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center"><i class="fa-solid fa-users text-xs"></i></div>
                    </div>
                    <div class="flex items-end gap-2">
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-gray-900">{{ number_format($totalPenduduk, 0, ',', '.') }}</h3>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-2">L: {{ $lakilaki }} | P: {{ $perempuan }}</p>
                </article>

                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between group border-b-4 border-b-emerald-500 hover:-translate-y-1 transition-transform">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Kartu Keluarga (KK)</p>
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center"><i class="fa-solid fa-house-chimney text-xs"></i></div>
                    </div>
                    <div class="flex items-end gap-2">
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-emerald-600">{{ number_format($totalKk, 0, ',', '.') }}</h3>
                    </div>
                    @if($totalKk > 0)
                        <p class="text-[10px] text-gray-400 mt-2">Rata-rata {{ round($totalPenduduk / $totalKk, 1) }} jiwa / KK</p>
                    @endif
                </article>

                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between group border-b-4 border-b-amber-500 hover:-translate-y-1 transition-transform">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Keluarga Pra-Sejahtera</p>
                        <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center"><i class="fa-solid fa-hand-holding-heart text-xs"></i></div>
                    </div>
                    <div class="flex items-end gap-2"><h3 class="text-2xl sm:text-3xl font-extrabold text-amber-600">{{ $totalPraSejahtera }}</h3></div>
                    <p class="text-[10px] text-gray-400 mt-2">{{ $totalBansosPercent }}% dari Total KK</p>
                </article>

                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between group border-b-4 border-b-red-500 hover:-translate-y-1 transition-transform">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Prevalensi Stunting</p>
                        <div class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center"><i class="fa-solid fa-child-reaching text-xs"></i></div>
                    </div>
                    <div class="flex items-end gap-2"><h3 class="text-2xl sm:text-3xl font-extrabold text-red-600">{{ $stuntingPercent }}%</h3></div>
                    <p class="text-[10px] text-gray-400 mt-2">{{ $totalStunting }} Balita (Kategori Pendek)</p>
                </article>
            </section>

            <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Demografi -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col lg:col-span-2 overflow-hidden">
                    <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center shrink-0">
                        <div>
                            <h3 class="font-bold text-gray-800">Demografi Usia & Jenis Kelamin</h3>
                            <p class="text-[10px] text-gray-500 mt-0.5">Distribusi penduduk berdasarkan kelompok usia.</p>
                        </div>
                    </div>
                    <div class="p-5 flex-1"><div id="chartDemografi" class="w-full h-72"></div></div>
                </div>

                <!-- Pendidikan -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col lg:col-span-1 overflow-hidden">
                    <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center shrink-0">
                        <div>
                            <h3 class="font-bold text-gray-800">Tingkat Pendidikan</h3>
                            <p class="text-[10px] text-gray-500 mt-0.5">Persentase pendidikan terakhir.</p>
                        </div>
                    </div>
                    <div class="p-5 flex-1 flex flex-col justify-center"><div id="chartPendidikan" class="w-full h-64"></div></div>
                </div>
            </section>

            <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Tren -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col lg:col-span-2 overflow-hidden">
                    <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center shrink-0">
                        <div>
                            <h3 class="font-bold text-gray-800">Tren Pertumbuhan Penduduk & KK Baru</h3>
                            <p class="text-[10px] text-gray-500 mt-0.5">Perbandingan historis selama 5 tahun terakhir.</p>
                        </div>
                    </div>
                    <div class="p-5 flex-1"><div id="chartTren" class="w-full h-72"></div></div>
                </div>

                <!-- Pekerjaan -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col lg:col-span-1 overflow-hidden">
                    <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center shrink-0">
                        <div>
                            <h3 class="font-bold text-gray-800">Status Pekerjaan</h3>
                            <p class="text-[10px] text-gray-500 mt-0.5">Top 5 profesi mayoritas warga.</p>
                        </div>
                    </div>
                    <div class="p-5 flex-1"><div id="chartPekerjaan" class="w-full h-72"></div></div>
                </div>
            </section>

            <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col lg:col-span-2 overflow-hidden h-[450px]">
                    <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center shrink-0 z-10">
                        <div>
                            <h3 class="font-bold text-gray-800"><i class="fa-solid fa-map-location-dot text-primary-600 mr-2"></i> Peta Distribusi Wilayah</h3>
                            <p class="text-[10px] text-gray-500 mt-0.5">Persebaran kepadatan penduduk dan indikator sosial per Dusun.</p>
                        </div>
                        <div class="flex gap-2">
                            <span class="flex items-center gap-1 text-[10px] font-bold text-gray-600"><div class="w-2 h-2 rounded-full bg-blue-500"></div> Kepadatan</span>
                            <span class="flex items-center gap-1 text-[10px] font-bold text-gray-600"><div class="w-2 h-2 rounded-full bg-red-500"></div> Stunting</span>
                        </div>
                    </div>
                    <div class="flex-1 relative z-0 w-full h-full bg-gray-100">
                        <div id="mapSebaran" class="absolute inset-0"></div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col lg:col-span-1 overflow-hidden">
                    <div class="p-5 border-b border-gray-100 bg-gray-50/50 shrink-0">
                        <h3 class="font-bold text-gray-800">Indikator Sosial & Kesejahteraan</h3>
                    </div>
                    <div class="p-5 flex-1 overflow-y-auto custom-scrollbar space-y-5">
                        <div>
                            <div class="flex justify-between items-end mb-1">
                                <span class="text-xs font-bold text-gray-700">Cakupan Bantuan Sosial</span>
                                <span class="text-xs font-extrabold text-amber-600">{{ $totalBansosPercent }}%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2 mb-1">
                                <div class="bg-amber-500 h-2 rounded-full" style="width: {{ min($totalBansosPercent, 100) }}%"></div>
                            </div>
                            <p class="text-[9px] text-gray-500">Dari total target keluarga miskin/rentan.</p>
                        </div>
                        
                        <div>
                            <div class="flex justify-between items-end mb-1">
                                <span class="text-xs font-bold text-gray-700">Balita Stunting dlm Penanganan</span>
                                <span class="text-xs font-extrabold text-green-600">100%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2 mb-1"><div class="bg-green-500 h-2 rounded-full" style="width: 100%"></div></div>
                            <p class="text-[9px] text-gray-500">{{ $totalStunting }} dari {{ $totalStunting }} balita ruitn ke posyandu.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden flex flex-col">
                <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row justify-between sm:items-center gap-4 bg-gray-50/50">
                    <div>
                        <h3 class="font-bold text-gray-800">Tabel Rincian Data Kewilayahan</h3>
                        <p class="text-[10px] text-gray-500 mt-0.5">Data mentah tabular sebagai pendukung visualisasi.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 text-[10px] uppercase tracking-wider border-b border-gray-200">
                                <th class="p-4 font-bold">Wilayah (Dusun/RW)</th>
                                <th class="p-4 font-bold text-right">Populasi (Jiwa)</th>
                                <th class="p-4 font-bold text-center">Rasio (L / P)</th>
                                <th class="p-4 font-bold text-right">Jml. KK</th>
                                <th class="p-4 font-bold text-center">Penerima Bansos</th>
                                <th class="p-4 font-bold text-center">Balita Stunting</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-gray-100">
                            @php $totP = 0; $totL = 0; $totPr = 0; $totKK = 0; $totBan = 0; $totStun = 0; @endphp
                            @forelse($dusunTable as $d_row)
                            @php 
                                $totP += $d_row->populasi; $totL += $d_row->L; $totPr += $d_row->P;
                                $totKK += $d_row->jml_kk; $totBan += $d_row->bansos; $totStun += $d_row->stunting;
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="p-4 font-bold text-gray-800"><i class="fa-solid fa-map text-primary-500 mr-2 text-xs"></i> Dusun {{ $d_row->dusun }}</td>
                                <td class="p-4 text-right font-bold text-gray-700">{{ $d_row->populasi }}</td>
                                <td class="p-4 text-center text-xs text-gray-500">{{ $d_row->L }} <span class="mx-1">/</span> {{ $d_row->P }}</td>
                                <td class="p-4 text-right font-semibold text-gray-600">{{ $d_row->jml_kk }}</td>
                                <td class="p-4 text-center"><span class="bg-amber-50 text-amber-700 text-[10px] font-bold px-2 py-0.5 rounded border border-amber-100">{{ $d_row->bansos }} KK</span></td>
                                <td class="p-4 text-center"><span class="bg-red-50 text-red-600 text-[10px] font-bold px-2 py-0.5 rounded border border-red-100">{{ $d_row->stunting }} Anak</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="p-6 text-center text-gray-500">Data belum tersedia untuk filter ini.</td></tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-gray-50 font-bold border-t-2 border-gray-200">
                            <tr>
                                <td class="p-4 text-gray-900">Total Keseluruhan</td>
                                <td class="p-4 text-right text-gray-900">{{ $totP }}</td>
                                <td class="p-4 text-center text-gray-900">{{ $totL }} <span class="mx-1">/</span> {{ $totPr }}</td>
                                <td class="p-4 text-right text-gray-900">{{ $totKK }}</td>
                                <td class="p-4 text-center text-gray-900">{{ $totBan }} KK</td>
                                <td class="p-4 text-center text-red-600">{{ $totStun }} Anak</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </section>

        </div>
    </main>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Highcharts Data
        const dtDemo = @json($chartDemografi);
        const dtEdu = @json($chartPendidikan);
        const dtJob = @json($chartPekerjaan);
        const dtTrend = @json($chartTren);

        if (typeof Highcharts !== 'undefined') {
            Highcharts.setOptions({
                colors: ['#3b82f6', '#ec4899', '#22c55e', '#f59e0b', '#8b5cf6', '#64748b'],
                chart: { style: { fontFamily: 'Inter, sans-serif' }, backgroundColor: 'transparent' },
                title: { text: null },
                credits: { enabled: false },
                legend: { itemStyle: { fontWeight: '500', color: '#4b5563', fontSize: '10px' } },
                tooltip: { backgroundColor: '#1e293b', style: { color: '#ffffff' }, borderWidth: 0, borderRadius: 8 }
            });

            Highcharts.chart('chartDemografi', {
                chart: { type: 'column' },
                xAxis: { categories: ['0-4 Thn', '5-14 Thn', '15-24 Thn', '25-54 Thn', '> 55 Thn'], gridLineWidth: 0, labels: { style: { fontSize: '10px' } } },
                yAxis: { title: { text: 'Jumlah Jiwa' }, gridLineColor: '#f1f5f9' },
                plotOptions: { column: { borderRadius: 2, borderWidth: 0, groupPadding: 0.1 } },
                series: [{ name: 'Laki-laki', data: dtDemo.laki }, { name: 'Perempuan', data: dtDemo.perempuan }]
            });

            Highcharts.chart('chartPendidikan', {
                chart: { type: 'pie' },
                plotOptions: { pie: { innerSize: '40%', allowPointSelect: true, cursor: 'pointer', dataLabels: { enabled: false }, showInLegend: true, borderWidth: 2, borderColors: '#fff' } },
                series: [{ name: 'Jiwa', colorByPoint: true, data: dtEdu }]
            });

            Highcharts.chart('chartTren', {
                chart: { type: 'areaspline' },
                xAxis: { categories: dtTrend.categories, gridLineWidth: 0 },
                yAxis: [{ title: { text: 'Total Penduduk' }, gridLineColor: '#f1f5f9' }, { title: { text: 'KK Baru' }, opposite: true }],
                plotOptions: { areaspline: { fillOpacity: 0.1, lineWidth: 3, marker: { symbol: 'circle' } } },
                series: [{ name: 'Pertumbuhan Populasi', data: dtTrend.populasi, color: '#16a34a' },
                         { name: 'Penambahan KK Baru', type: 'column', yAxis: 1, data: dtTrend.kk_baru, color: '#f59e0b', opacity: 0.8 }]
            });

            Highcharts.chart('chartPekerjaan', {
                chart: { type: 'bar' },
                xAxis: { categories: dtJob.categories, gridLineWidth: 0 },
                yAxis: { title: { text: null }, gridLineColor: '#f1f5f9' },
                legend: { enabled: false },
                plotOptions: { bar: { borderRadius: 3, borderWidth: 0, dataLabels: { enabled: true, style: { fontSize: '9px', fontWeight: 'bold' } } } },
                series: [{ name: 'Jumlah Warga', data: dtJob.data, color: '#8b5cf6' }]
            });
        }

        // Leaflet
        const mapLocations = @json($mapData);
        if (typeof L !== 'undefined' && mapLocations.length > 0) {
            var map = L.map('mapSebaran', { zoomControl: false, attributionControl: false }).setView([-7.175, 108.196], 14);
            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { maxZoom: 19 }).addTo(map);
            L.control.zoom({ position: 'bottomright' }).addTo(map);

            mapLocations.forEach(d => {
                L.circle([d.lat, d.lng], { color: '#3b82f6', fillColor: '#3b82f6', fillOpacity: 0.3, radius: d.radiusKepadatan }).addTo(map)
                 .bindPopup("<b>" + d.dusun + "</b><br>" + d.statusKepadatan);
                 
                if(d.radiusStunting > 0) {
                    L.circle([d.lat - 0.001, d.lng + 0.002], { color: '#ef4444', fillColor: '#ef4444', fillOpacity: 0.8, radius: d.radiusStunting }).addTo(map).bindPopup("Titik Rentan Stunting (" + d.stunting + " Kasus)");
                }
            });
        }
    });
</script>
@endpush
@endsection
