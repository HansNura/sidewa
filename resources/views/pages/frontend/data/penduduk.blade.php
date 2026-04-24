@extends('layouts.frontend')

@section('title', 'Data Penduduk - Desa Sindangmukti')

@push('scripts')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/drilldown.js"></script>
    <script>
        function dataPenduduk() {
            return {
                totalPenduduk: @json($totalPenduduk),
                dataKelompokUmur: @json($dataKelompokUmur),

                // 1. Menghitung persentase
                calculatePercentage(value, total) {
                    if (total === 0) return "0.0%";
                    // Khusus untuk KK, persentase tidak relevan,
                    // jadi kita tidak tampilkan
                    if (value === 1234) return "";
                    return ((value / total) * 100).toFixed(1) + "%";
                },

                // 2. Styling Ikon
                getCardIconClass(stat) {
                    if (stat.isHighlight)
                        return "bg-white/20 text-white";

                    const colors = {
                        blue: "bg-blue-50 text-blue-700 border border-blue-100",
                        pink: "bg-pink-50 text-pink-700 border border-pink-100",
                        amber: "bg-amber-50 text-amber-700 border border-amber-100",
                    };
                    return (
                        colors[stat.color] || "bg-gray-50 text-gray-700 border border-gray-100"
                    );
                },

                // 3. Styling Teks Persentase
                getCardProgressClass(stat) {
                    if (stat.isHighlight) return "";
                    const colors = {
                        blue: "text-blue-600",
                        pink: "text-pink-600",
                        amber: "text-amber-600",
                    };
                    return colors[stat.color] || "text-gray-600";
                },

                // 4. Styling Progress Bar
                getProgressBarClass(stat) {
                    if (stat.isHighlight) return "";
                    // Sembunyikan progress bar untuk KK
                    if (stat.label === "Total Kepala Keluarga")
                        return "bg-transparent";

                    const colors = {
                        blue: "bg-blue-600",
                        pink: "bg-pink-600",
                        amber: "bg-amber-600",
                    };
                    return colors[stat.color] || "bg-gray-600";
                },

                // --- Tema Global Highcharts ---
                setHighchartsTheme() {
                    Highcharts.setOptions({
                        colors: [
                            "#2E7D32", // primary
                            "#0288D1", // accent blue
                            "#FBC02D", // accent yellow
                            "#D32F2F", // error
                            "#5E35B1", // deep purple
                            "#43A047", // green light
                            "#FFB300", // amber
                            "#1E88E5", // blue light
                        ],
                        chart: {
                            style: {
                                fontFamily: "Poppins, sans-serif",
                            },
                            backgroundColor: "transparent",
                        },
                        title: {
                            style: {
                                color: "#1f2937", // text-textdark
                                fontSize: "1.125rem", // text-lg
                                fontWeight: "600",
                            },
                        },
                        subtitle: {
                            style: {
                                color: "#4B5563", // gray-600
                            },
                        },
                        tooltip: {
                            backgroundColor: "#1F2937", // text-textdark
                            style: {
                                color: "#FFFFFF",
                            },
                        },
                        plotOptions: {
                            series: {
                                dataLabels: {
                                    style: {
                                        color: "#1F2937",
                                        textOutline: "none",
                                    },
                                },
                            },
                            pie: {
                                dataLabels: {
                                    style: {
                                        color: "#1F2937",
                                        textOutline: "none",
                                    },
                                },
                            },
                        },
                        credits: {
                            enabled: false,
                        },
                    });
                },

                // --- Fungsi Pie Chart (Highcharts) ---
                initGenderPieChart() {
                    const data = [{
                            name: "Laki-laki",
                            y: this.totalPenduduk.l,
                            selected: this.totalPenduduk.l > this.totalPenduduk.p,
                            sliced: this.totalPenduduk.l > this.totalPenduduk.p,
                            color: "rgba(59, 130, 246, 0.8)", // Biru
                        },
                        {
                            name: "Perempuan",
                            y: this.totalPenduduk.p,
                            selected: this.totalPenduduk.p > this.totalPenduduk.l,
                            sliced: this.totalPenduduk.p > this.totalPenduduk.l,
                            color: "rgba(236, 72, 153, 0.8)", // Pink
                        },
                    ];

                    Highcharts.chart("genderPieChart", {
                        chart: {
                            type: "pie",
                        },
                        title: {
                            text: "Grafik Jenis Kelamin",
                        },
                        subtitle: {
                            text: `Total: ${this.totalPenduduk.total} Jiwa`,
                        },
                        tooltip: {
                            pointFormat: '<span style="color:{point.color}">\u25CF</span> {series.name}: ' +
                                "<b>{point.y:,.0f} ({point.percentage:.1f}%)</b>",
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: "pointer",
                                borderRadius: 5,
                                dataLabels: [{
                                        enabled: true,
                                        distance: 15,
                                        format: "{point.name}",
                                    },
                                    {
                                        enabled: true,
                                        distance: "-30%",
                                        filter: {
                                            property: "percentage",
                                            operator: ">",
                                            value: 5,
                                        },
                                        format: "{point.percentage:.1f}%",
                                        style: {
                                            fontSize: "0.9em",
                                            color: "white",
                                            textOutline: "none",
                                        },
                                    },
                                ],
                            },
                        },
                        series: [{
                            name: "Jumlah",
                            colorByPoint: false,
                            data: data,
                        }, ],
                    });
                },

                // --- Fungsi Column Chart (Highcharts) ---
                initAgeColumnChart() {
                    const chartData = this.dataKelompokUmur.labels.map(
                        (label, index) => {
                            return {
                                name: label,
                                y: this.dataKelompokUmur.data[index],
                            };
                        }
                    );

                    Highcharts.chart("ageColumnChart", {
                        chart: {
                            type: "column",
                        },
                        title: {
                            text: "Grafik Kelompok Umur",
                        },
                        subtitle: {
                            text: `Total: ${this.totalPenduduk.total} Jiwa`,
                        },
                        xAxis: {
                            type: "category",
                            title: {
                                text: "Kelompok Umur",
                            },
                        },
                        yAxis: {
                            title: {
                                text: "Jumlah Jiwa",
                            },
                        },
                        legend: {
                            enabled: false,
                        },
                        tooltip: {
                            pointFormat: '<span style="color:{point.color}">\u25CF</span> {point.name}: ' +
                                "<b>{point.y:,.0f} Jiwa</b>",
                        },
                        plotOptions: {
                            column: {
                                colorByPoint: true,
                                borderWidth: 0,
                                dataLabels: {
                                    enabled: true,
                                    format: "{point.y:,.0f}",
                                    style: {
                                        fontSize: "0.8em",
                                    },
                                },
                            },
                        },
                        series: [{
                            name: "Jumlah Penduduk",
                            data: chartData,
                        }, ],
                    });
                },
            };
        }
    </script>
@endpush

@section('content')
    <div x-data="dataPenduduk()" class="bg-gray-50 pt-16" x-init="setHighchartsTheme();
    initGenderPieChart();
    initAgeColumnChart();">

        <!-- HEADER DATA: GRADIENT SaaS STYLE -->
        <section class="bg-gradient-to-br from-green-800 to-green-600 text-white py-16 md:py-24 relative overflow-hidden">
            <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]">
            </div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
                <span
                    class="bg-green-700/50 text-green-100 text-sm font-semibold px-4 py-1.5 rounded-full border border-green-500/30 mb-6 inline-block backdrop-blur-sm">Statistik
                    Desa</span>
                <h1 class="text-4xl md:text-6xl font-extrabold mb-6 tracking-tight">{{ $pageTitle }}</h1>
                <p class="text-lg md:text-xl text-green-50 max-w-2xl mx-auto leading-relaxed opacity-90">
                    {{ $pageSubtitle }}
                </p>
            </div>
            <!-- Decorative circle -->
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-green-500/20 rounded-full blur-3xl"></div>
            <div class="absolute -top-24 -left-24 w-72 h-72 bg-emerald-500/20 rounded-full blur-3xl"></div>
        </section>

        <section class="py-12 -mt-10 relative z-20">
            <div class="px-6 mx-auto max-w-7xl lg:px-8">
                
                <!-- SUMMARY CARDS -->
                <div class="mb-12">
                    @php
                        $highlightStat = collect($statistikPenduduk)->firstWhere('isHighlight', true);
                        $otherStats = collect($statistikPenduduk)->where('isHighlight', false);
                    @endphp

                    @if ($highlightStat)
                        <!-- Highlight Stat (Total Penduduk) -->
                        <div class="bg-gradient-to-r from-emerald-700 to-green-600 rounded-[2rem] p-8 md:p-10 shadow-2xl shadow-emerald-900/20 mb-8 border border-white/10 relative overflow-hidden group">
                            <div class="absolute right-0 top-0 opacity-10 group-hover:scale-110 transition-transform duration-700">
                                <svg class="w-64 h-64 -mr-20 -mt-20 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    {!! $highlightStat['icon'] !!}
                                </svg>
                            </div>
                            
                            <div class="flex flex-col md:flex-row items-center justify-between relative z-10 gap-8">
                                <div class="flex items-center gap-6">
                                    <div class="flex items-center justify-center flex-shrink-0 w-20 h-20 rounded-2xl bg-white/20 backdrop-blur-md border border-white/30 text-white shadow-xl">
                                        <svg class="w-10 h-10" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            {!! $highlightStat['icon'] !!}
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-emerald-100 font-medium tracking-wide uppercase text-sm mb-1">{{ $highlightStat['label'] }}</p>
                                        <h3 class="text-4xl md:text-5xl font-black text-white">
                                            {{ is_numeric($highlightStat['value']) ? number_format($highlightStat['value'], 0, ',', '.') : $highlightStat['value'] }}
                                            <span class="text-xl md:text-2xl font-medium opacity-80 ml-1">{{ $highlightStat['unit'] }}</span>
                                        </h3>
                                    </div>
                                </div>
                                <div class="bg-black/10 backdrop-blur-sm px-6 py-4 rounded-2xl border border-white/5 text-right self-stretch md:self-center flex flex-col justify-center">
                                    <p class="text-emerald-100/60 text-xs font-semibold uppercase tracking-widest mb-1">Update Terakhir</p>
                                    <p class="text-white font-medium">{{ $highlightStat['timestamp'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Other Stats Grid -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        @foreach ($otherStats as $index => $stat)
                            <div class="bg-white rounded-[1.5rem] p-6 shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 rounded-xl transition-colors duration-300"
                                        :class="getCardIconClass({ color: '{{ $stat['color'] }}', isHighlight: false })">
                                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            {!! $stat['icon'] !!}
                                        </svg>
                                    </div>
                                    <h4 class="font-bold text-slate-800 leading-tight">{{ $stat['label'] }}</h4>
                                </div>

                                <div class="space-y-4">
                                    <div class="flex items-baseline justify-between">
                                        <p class="text-3xl font-black text-slate-900 tracking-tight">
                                            {{ is_numeric($stat['value']) ? number_format($stat['value'], 0, ',', '.') : $stat['value'] }}
                                        </p>
                                        <p class="font-bold text-sm"
                                            :class="getCardProgressClass({ color: '{{ $stat['color'] }}', isHighlight: false })"
                                            x-text="calculatePercentage({{ $stat['value'] }}, totalPenduduk.total)">
                                        </p>
                                    </div>
                                    
                                    <div class="relative w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                                        <div class="absolute inset-y-0 left-0 rounded-full transition-all duration-1000"
                                            :class="getProgressBarClass({ color: '{{ $stat['color'] }}', isHighlight: false, label: '{{ $stat['label'] }}' })"
                                            :style="`width: ${calculatePercentage({{ $stat['value'] }}, totalPenduduk.total)}`"
                                            x-init="$el.style.width = '0%'; setTimeout(() => $el.style.width = calculatePercentage({{ $stat['value'] }}, totalPenduduk.total), 500)">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- CHARTS SECTION -->
                <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                        <div id="genderPieChart" class="w-full h-96"></div>
                    </div>
                    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                        <div id="ageColumnChart" class="w-full h-96"></div>
                    </div>
                </div>

                <!-- CALL TO ACTION -->
                <div class="mt-12">
                    <a href="{{ url('data/wilayah') }}"
                        class="group flex items-center justify-center w-full gap-3 px-8 py-5 font-bold text-white transition-all rounded-[1.5rem] shadow-lg bg-emerald-600 hover:bg-emerald-700 hover:shadow-emerald-200">
                        Lihat Rincian per Wilayah Administratif
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                            class="w-6 h-6 group-hover:translate-x-1 transition-transform">
                            <path fill-rule="evenodd"
                                d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 1 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
            </div>
        </section>
    </div>
@endsection
