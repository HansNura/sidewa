@if($terlambat > 0)
<div class="bg-red-50 border border-red-200 rounded-2xl p-4 flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center shadow-sm">
    <div class="flex gap-3 items-start">
        <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center shrink-0 mt-0.5 animate-pulse">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div>
            <h4 class="font-bold text-red-900 text-sm">{{ $terlambat }} Proyek Mengalami Keterlambatan (Delay)</h4>
            <p class="text-xs text-red-800 mt-0.5">Pantau detail setiap proyek untuk evaluasi target lebih lanjut.</p>
        </div>
    </div>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Chart: Kategori Distribusi -->
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex flex-col">
        <h3 class="font-bold text-gray-800 mb-4 text-sm">Distribusi Proyek per Bidang</h3>
        <div id="chartCategoryDist" class="w-full h-64 flex-1"></div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof Highcharts !== 'undefined') {
            const rawCatDist = @json($kategoriDist);
            const pieData = Object.keys(rawCatDist).map((key, index) => {
                const colors = ['#3b82f6', '#16a34a', '#f59e0b', '#8b5cf6', '#ef4444'];
                return { name: key, y: rawCatDist[key], color: colors[index % colors.length] };
            });

            Highcharts.chart('chartCategoryDist', {
                chart: { type: 'pie', backgroundColor: 'transparent' },
                title: { text: null },
                plotOptions: {
                    pie: { innerSize: '60%', dataLabels: { enabled: false }, showInLegend: true, borderWidth: 2, borderColor: '#fff' }
                },
                legend: { layout: 'vertical', align: 'right', verticalAlign: 'middle', itemStyle: { fontSize: '11px' } },
                series: [{ name: 'Jumlah Proyek', data: pieData }]
            });
        }
    });
</script>
@endpush
