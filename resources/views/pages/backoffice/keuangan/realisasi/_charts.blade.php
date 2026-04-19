<section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Progress Monitoring Chart (Large) -->
    <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex flex-col">
        <h3 class="font-bold text-gray-800 mb-6 flex items-center gap-2">
            <i class="fa-solid fa-chart-line text-green-600"></i> Monitoring Penyerapan per Bidang
        </h3>
        <div id="realizationChart" class="w-full h-80"></div>
    </div>

    <!-- Alert & Over-budget Panel -->
    <div class="flex flex-col gap-6">
        <!-- Warning Card -->
        @if(count($anomali) > 0)
        <div class="bg-red-50 border border-red-200 rounded-2xl p-5 shadow-sm max-h-[50%] overflow-y-auto custom-scrollbar">
            <h4 class="text-sm font-bold text-red-800 flex items-center gap-2 mb-3">
                <i class="fa-solid fa-triangle-exclamation {{ count(array_filter($anomali, fn($a) => $a['jenis'] == 'OVER_BUDGET')) > 0 ? 'animate-pulse' : '' }}"></i> Peringatan Anomali
            </h4>
            <div class="space-y-3">
                @foreach($anomali as $alert)
                    @if($alert['jenis'] == 'OVER_BUDGET')
                    <div class="p-3 bg-white rounded-xl border border-red-200 shadow-sm">
                        <p class="text-[10px] font-bold text-red-600 uppercase">Over-Budget Terdeteksi</p>
                        <p class="text-xs font-bold text-gray-800 mt-1">{{ $alert['kegiatan'] }}</p>
                        <p class="text-[10px] text-gray-500 mt-0.5">{{ $alert['keterangan'] }}</p>
                    </div>
                    @else
                    <div class="p-3 bg-white rounded-xl border border-amber-200 opacity-90 shadow-sm">
                        <p class="text-[10px] font-bold text-amber-600 uppercase">Mendekati Pagu</p>
                        <p class="text-xs font-bold text-gray-800 mt-1">{{ $alert['kegiatan'] }}</p>
                        <p class="text-[10px] text-gray-500 mt-0.5">{{ $alert['keterangan'] }}</p>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
        @else
        <div class="bg-green-50 border border-green-200 rounded-2xl p-5 shadow-sm flex flex-col justify-center text-center max-h-[50%]">
             <i class="fa-solid fa-shield-check text-3xl text-green-400 mb-2"></i>
             <h4 class="text-sm font-bold text-green-800">Sehat & Terkendali</h4>
             <p class="text-[10px] text-green-600 mt-1">Tidak ada anomali over-budget.</p>
        </div>
        @endif

        <!-- Trend Analysis -->
        <div class="bg-white border border-gray-100 rounded-2xl p-5 flex-1 shadow-sm">
            <h4 class="text-sm font-bold text-gray-800 mb-4 uppercase tracking-widest text-[10px]">Analisis Tren Penyerapan</h4>
            <div class="space-y-4 max-h-48 overflow-y-auto custom-scrollbar pr-2">
                @foreach(collect($trenPerBidang)->sortByDesc(fn($t) => $t['total_pagu'] > 0 ? ($t['total_realisasi'] / $t['total_pagu']) : 0)->take(3) as $tren)
                    @php 
                        $pct = $tren['total_pagu'] > 0 ? ($tren['total_realisasi'] / $tren['total_pagu']) * 100 : 0; 
                    @endphp
                    <div>
                        <div class="flex justify-between text-[11px] font-bold mb-1">
                            <span class="text-gray-600 truncate mr-2" title="{{ $tren['nama_bidang'] }}">{{ $tren['nama_bidang'] }}</span>
                            <span class="text-green-700 shrink-0">{{ number_format($pct, 1) }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 h-1.5 rounded-full overflow-hidden">
                            <div class="bg-blue-500 h-full" style="width: {{ min(100, $pct) }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof Highcharts !== 'undefined') {
            
            const rawData = @json(array_values($trenPerBidang));
            
            const categories = rawData.map(item => item.nama_bidang.substring(0, 15) + (item.nama_bidang.length > 15 ? '...' : ''));
            const dataPagu = rawData.map(item => item.total_pagu / 1000000); // in millions
            const dataRealisasi = rawData.map(item => item.total_realisasi / 1000000);

            Highcharts.chart('realizationChart', {
                chart: { type: 'column', backgroundColor: 'transparent', style: { fontFamily: 'Inter, sans-serif' } },
                title: { text: null },
                xAxis: {
                    categories: categories,
                    gridLineWidth: 0,
                    labels: { style: { fontSize: '10px', fontWeight: '600' } }
                },
                yAxis: {
                    title: { text: 'Nominal (Juta)' },
                    gridLineColor: '#f3f4f6',
                    labels: { format: '{value} Jt' }
                },
                tooltip: { 
                    backgroundColor: '#1e293b', 
                    style: { color: '#ffffff' }, 
                    borderWidth: 0, 
                    borderRadius: 12, 
                    shared: true,
                    valuePrefix: 'Rp ',
                    valueSuffix: ' Jt'
                },
                legend: { itemStyle: { fontWeight: '600', color: '#64748b' } },
                credits: { enabled: false },
                plotOptions: {
                    column: { borderRadius: 6, borderWidth: 0 }
                },
                series: [{
                    name: 'Rencana Pagu',
                    data: dataPagu,
                    color: '#e2e8f0'
                }, {
                    name: 'Realisasi Aktual',
                    data: dataRealisasi,
                    color: '#16a34a'
                }]
            });
        }
    });
</script>
@endpush
