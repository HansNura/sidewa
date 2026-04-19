<section class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm" x-data="{
        initChart() {
            if(typeof Highcharts === 'undefined') return;
            Highcharts.chart('attendanceTrendChart', {
                chart: { type: 'areaspline', backgroundColor: 'transparent', style: { fontFamily: 'Inter, sans-serif' } },
                title: { text: null },
                xAxis: {
                    categories: {!! json_encode($trendData['dates'] ?? []) !!},
                    gridLineWidth: 0,
                    labels: { style: { fontSize: '10px', color: '#94a3b8' } }
                },
                yAxis: {
                    title: { text: 'Jumlah Pegawai' },
                    gridLineColor: '#f1f5f9',
                    labels: { style: { fontSize: '10px', color: '#94a3b8' } }
                },
                tooltip: { backgroundColor: '#1e293b', style: { color: '#ffffff' }, borderWidth: 0, borderRadius: 8 },
                legend: { enabled: false },
                credits: { enabled: false },
                plotOptions: { areaspline: { fillOpacity: 0.1, marker: { enabled: false }, lineWidth: 3 } },
                series: [{
                    name: 'Hadir',
                    data: {!! json_encode($trendData['hadir'] ?? []) !!},
                    color: '#22c55e'
                }, {
                    name: 'Terlambat',
                    data: {!! json_encode($trendData['terlambat'] ?? []) !!},
                    color: '#f59e0b'
                }]
            });
        }
    }" x-init="initChart()">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
        <div>
            <h3 class="font-bold text-gray-800">Tren Kehadiran Pegawai</h3>
            <p class="text-xs text-gray-500">Perbandingan kehadiran harian dalam periode aktif terpilih.</p>
        </div>
        <div class="flex gap-2">
            <span class="flex items-center gap-1.5 text-[10px] font-semibold text-gray-500">
                <div class="w-3 h-3 rounded-full bg-green-500"></div> Hadir
            </span>
            <span class="flex items-center gap-1.5 text-[10px] font-semibold text-gray-500">
                <div class="w-3 h-3 rounded-full bg-amber-400"></div> Terlambat
            </span>
        </div>
    </div>
    <div id="attendanceTrendChart" class="w-full h-64"></div>
</section>
