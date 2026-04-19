<section class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm" x-data="{
        initChart() {
            if(typeof Highcharts === 'undefined') return;
            Highcharts.chart('visitorTrendChart', {
                chart: { type: 'areaspline', backgroundColor: 'transparent', style: { fontFamily: 'Inter, sans-serif' } },
                title: { text: null },
                xAxis: { 
                    categories: {!! json_encode($trendData['categories']) !!}, 
                    labels: { style: { fontSize: '10px', color: '#94a3b8' } },
                    gridLineWidth: 0
                },
                yAxis: { 
                    title: { text: 'Kunjungan' }, 
                    gridLineColor: '#f1f5f9',
                    labels: { style: { fontSize: '10px', color: '#94a3b8' } }
                },
                tooltip: { backgroundColor: '#1e293b', style: { color: '#ffffff' }, borderWidth: 0, borderRadius: 8 },
                legend: { enabled: false },
                credits: { enabled: false },
                plotOptions: { areaspline: { fillOpacity: 0.1, marker: { enabled: false } } },
                series: [{
                    name: 'Jumlah Tamu',
                    data: {!! json_encode($trendData['data']) !!},
                    color: '#22c55e',
                    lineWidth: 3
                }]
            });
        }
    }" x-init="initChart()">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
        <div>
            <h3 class="font-bold text-gray-800">Grafik Tren Kunjungan</h3>
            <p class="text-xs text-gray-500">Visualisasi jumlah tamu per hari selama periode berjalan.</p>
        </div>
    </div>
    <div id="visitorTrendChart" class="w-full h-64"></div>
</section>
