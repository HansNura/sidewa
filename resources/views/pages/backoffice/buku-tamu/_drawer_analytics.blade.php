<!-- DRAWER: GLOBAL ANALYTICS -->
<div x-show="analyticsDrawerOpen" class="fixed inset-0 z-[100] flex justify-end" x-cloak>
    <div x-show="analyticsDrawerOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
        @click="analyticsDrawerOpen = false"></div>

    <div x-show="analyticsDrawerOpen" x-transition:enter="transition ease-transform duration-300"
        x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-transform duration-300" x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="relative bg-white w-full max-w-4xl h-full shadow-2xl flex flex-col border-l border-gray-200"
        x-data="{
            initAnalytics() {
                if(typeof Highcharts === 'undefined') return;
                
                // 2. Chart Distribusi Tujuan (Pie/Donut)
                Highcharts.chart('purposeDistributionChart', {
                    chart: { type: 'pie', backgroundColor: 'transparent', style: { fontFamily: 'Inter, sans-serif' } },
                    title: { text: null },
                    credits: { enabled: false },
                    plotOptions: { pie: { innerSize: '65%', dataLabels: { enabled: false }, showInLegend: true } },
                    legend: { layout: 'vertical', align: 'right', verticalAlign: 'middle', itemStyle: { fontSize: '9px' } },
                    series: [{
                        name: 'Persentase',
                        data: {!! json_encode($analytics['pie']) !!}
                    }]
                });

                // 3. Time-based Analysis (Column)
                Highcharts.chart('timeAnalysisChart', {
                    chart: { type: 'column', backgroundColor: 'transparent', style: { fontFamily: 'Inter, sans-serif' } },
                    title: { text: null },
                    credits: { enabled: false },
                    xAxis: { categories: {!! json_encode($analytics['bar']['categories']) !!}, labels: { style: { fontSize: '9px' } } },
                    yAxis: { title: { text: null }, gridLineColor: '#f1f5f9' },
                    legend: { enabled: false },
                    series: [{
                        name: 'Frekuensi Trafik',
                        data: {!! json_encode($analytics['bar']['data']) !!},
                        color: '#f59e0b',
                        borderRadius: 6
                    }]
                });
            }
        }" x-init="$watch('analyticsDrawerOpen', val => { if(val) setTimeout(() => initAnalytics(), 100) })">

        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-start bg-gray-50/50 shrink-0">
            <div class="flex gap-4 items-center">
                <div class="w-14 h-14 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center text-2xl shrink-0 shadow-sm border-2 border-white">
                    <i class="fa-solid fa-chart-pie"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-xl text-gray-900">Analisis Kunjungan Spesifik</h3>
                    <p class="text-sm text-gray-500 mt-0.5">Analitik sebaran tujuan dan waktu kunjungan Periode {{ $activePeriod }}.</p>
                </div>
            </div>
            <button @click="analyticsDrawerOpen = false"
                class="text-gray-400 hover:text-red-500 w-10 h-10 flex items-center justify-center rounded-xl hover:bg-red-50 transition-colors -mr-2 cursor-pointer">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <div class="p-6 overflow-y-auto custom-scrollbar flex-1 space-y-8">
            <!-- Analysis Charts -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-50 border border-gray-200 rounded-2xl p-5">
                    <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 border-b border-gray-200 pb-2">Distribusi Tujuan</h5>
                    <div id="purposeDistributionChart" class="w-full h-48"></div>
                </div>
                <div class="bg-gray-50 border border-gray-200 rounded-2xl p-5">
                    <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 border-b border-gray-200 pb-2">Analisis Jam Ramai</h5>
                    <div id="timeAnalysisChart" class="w-full h-48"></div>
                </div>
            </div>

            <!-- Top Visitor Source Table -->
            <div>
                <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Instansi/Asal Terbanyak Bulan Ini</h5>
                <div class="border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                    <table class="w-full text-left bg-white text-xs">
                        <thead class="bg-gray-50 border-b border-gray-200 text-gray-500">
                            <tr>
                                <th class="p-3 font-bold">Nama Instansi / Asal</th>
                                <th class="p-3 font-bold text-center">Tujuan Terdokumentasi Terbanyak</th>
                                <th class="p-3 font-bold text-center">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($analytics['top_instansi'] as $row)
                            <tr>
                                <td class="p-3 font-bold text-gray-800">{{ $row->instansi }}</td>
                                <td class="p-3 text-center border font-semibold text-[10px] text-gray-600 bg-gray-50/50 uppercase">
                                    {{ $row->tujuan_kategori }}
                                </td>
                                <td class="p-4 text-center font-extrabold text-blue-700">{{ $row->total }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="p-4 text-center text-gray-500">Belum cukup data untuk dianalisis.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Notes & Insights -->
            <div class="bg-blue-50 border border-blue-100 rounded-3xl p-5 flex gap-4">
                <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-blue-600 shadow-sm shrink-0">
                    <i class="fa-solid fa-lightbulb text-xl"></i>
                </div>
                <div>
                    <h4 class="font-extrabold text-blue-900 mb-1">Poin Insight Otomatis</h4>
                    <p class="text-sm text-blue-800/80 leading-relaxed">Persentase mandiri lewat **Kiosk** mencapai {{ $stats['persentase_kiosk'] }}%. Disarankan edukasi kepada pengunjung langsung agar mengoptimalkan Kiosk di Lobby guna mengurai kepadatan resionis saat jam sibuk ({{ array_search(max($analytics['bar']['data']), $analytics['bar']['data'] ?: [0]) !== false ? $analytics['bar']['categories'][array_search(max($analytics['bar']['data']), $analytics['bar']['data'])] : 'Lainnya' }} WIB).</p>
                </div>
            </div>
        </div>
    </div>
</div>
