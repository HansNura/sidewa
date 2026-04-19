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
                    return "bg-white bg-opacity-25 text-white";

                const colors = {
                    blue: "bg-blue-100 text-blue-600",
                    pink: "bg-pink-100 text-pink-600",
                    amber: "bg-amber-100 text-amber-600",
                };
                return (
                    colors[stat.color] || "bg-gray-100 text-gray-700"
                );
            },

            // 3. Styling Teks Persentase
            getCardProgressClass(stat) {
                if (stat.isHighlight) return "";
                const colors = {
                    blue: "text-blue-500",
                    pink: "text-pink-500",
                    amber: "text-amber-500",
                };
                return colors[stat.color] || "text-gray-500";
            },

            // 4. Styling Progress Bar
            getProgressBarClass(stat) {
                if (stat.isHighlight) return "";
                // Sembunyikan progress bar untuk KK
                if (stat.label === "Total Kepala Keluarga")
                    return "bg-transparent";

                const colors = {
                    blue: "bg-blue-500",
                    pink: "bg-pink-500",
                    amber: "bg-amber-500",
                };
                return colors[stat.color] || "bg-gray-500";
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
                const data = [
                    {
                        name: "Laki-laki",
                        y: this.totalPenduduk.l,
                        selected:
                            this.totalPenduduk.l > this.totalPenduduk.p,
                        sliced:
                            this.totalPenduduk.l > this.totalPenduduk.p,
                        color: "rgba(59, 130, 246, 0.8)", // Biru
                    },
                    {
                        name: "Perempuan",
                        y: this.totalPenduduk.p,
                        selected:
                            this.totalPenduduk.p > this.totalPenduduk.l,
                        sliced:
                            this.totalPenduduk.p > this.totalPenduduk.l,
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
                        pointFormat:
                            '<span style="color:{point.color}">\u25CF</span> {series.name}: ' +
                            "<b>{point.y:,.0f} ({point.percentage:.1f}%)</b>",
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: "pointer",
                            borderRadius: 5,
                            dataLabels: [
                                {
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
                    series: [
                        {
                            name: "Jumlah",
                            colorByPoint: false,
                            data: data,
                        },
                    ],
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
                        pointFormat:
                            '<span style="color:{point.color}">\u25CF</span> {point.name}: ' +
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
                    series: [
                        {
                            name: "Jumlah Penduduk",
                            data: chartData,
                        },
                    ],
                });
            },
        };
    }
</script>
@endpush

@section('content')
<div x-data="dataPenduduk()" class="pt-24 bg-gray-50" x-init="setHighchartsTheme(); initGenderPieChart(); initAgeColumnChart();">
    <section class="py-12 bg-white border-b border-gray-200">
        <div class="px-6 mx-auto max-w-7xl">
            <nav class="flex mb-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 text-sm text-gray-500 md:space-x-2">
                    <li class="inline-flex items-center">
                        <a href="{{ url('/') }}" class="inline-flex items-center transition-colors hover:text-[#2e7d32]">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            Beranda
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 md:ml-2">Data Desa</span>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 font-medium text-gray-800 md:ml-2">{{ $pageTitle }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-3xl font-bold text-[#2e7d32] md:text-4xl">{{ $pageTitle }}</h1>
            <p class="mt-2 text-lg text-gray-600">{{ $pageSubtitle }}</p>
        </div>
    </section>

    <section class="py-16">
        <div class="px-6 mx-auto max-w-7xl lg:px-8">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                <div class="grid grid-cols-1 gap-8 lg:col-span-2 md:grid-cols-2">
                    <div class="p-6 bg-white border border-gray-100 shadow-lg rounded-xl">
                        <div id="genderPieChart" class="relative w-full h-80"></div>
                    </div>
                    <div class="p-6 bg-white border border-gray-100 shadow-lg rounded-xl">
                        <div id="ageColumnChart" class="relative w-full h-80"></div>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-[#1f2937]">Ringkasan Data Penduduk</h3>

                        @php
                            $highlightStat = collect($statistikPenduduk)->firstWhere('isHighlight', true);
                            $otherStats = collect($statistikPenduduk)->where('isHighlight', false);
                        @endphp

                        @if($highlightStat)
                            <!-- Highlight Stat (Total Penduduk) -->
                            <div class="flex items-center max-w-4xl p-4 mx-auto space-x-4 transition-all shadow-lg rounded-xl hover:shadow-xl bg-[#2e7d32] text-white">
                                <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 rounded-full bg-white bg-opacity-25 text-white">
                                    <svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        {!! $highlightStat['icon'] !!}
                                    </svg>
                                </div>
                                <div class="flex-grow">
                                    <p class="text-base font-medium text-green-100">{{ $highlightStat['label'] }}</p>
                                    <p class="text-2xl font-bold text-white">{{ $highlightStat['value'] }} {{ $highlightStat['unit'] }}</p>
                                </div>
                                <div class="flex-shrink-0 w-32 text-right">
                                    <p class="text-sm font-medium text-green-100">Data Per</p>
                                    <p class="text-xs text-green-100">{{ $highlightStat['timestamp'] }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Other Stats -->
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-3 lg:grid-cols-3 max-w-7xl">
                            @foreach($otherStats as $index => $stat)
                                <div class="flex flex-col justify-between h-full p-5 transition-all bg-white shadow-lg rounded-xl hover:shadow-xl text-[#1f2937]">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 rounded-full" :class="getCardIconClass({ color: '{{ $stat['color'] }}', isHighlight: false })">
                                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                {!! $stat['icon'] !!}
                                            </svg>
                                        </div>
                                        <p class="font-medium text-gray-700">{{ $stat['label'] }}</p>
                                    </div>

                                    <div class="flex items-end justify-between mt-4">
                                        <p class="text-3xl font-bold text-gray-900">{{ number_format($stat['value'], 0, ',', '.') }}</p>

                                        <div class="flex-shrink-0 w-24 text-right">
                                            <p class="mb-1 text-base font-bold" :class="getCardProgressClass({ color: '{{ $stat['color'] }}', isHighlight: false })" x-text="calculatePercentage({{ $stat['value'] }}, totalPenduduk.total)"></p>
                                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                <div class="h-2.5 rounded-full" :class="getProgressBarClass({ color: '{{ $stat['color'] }}', isHighlight: false, label: '{{ $stat['label'] }}' })" :style="`width: ${calculatePercentage({{ $stat['value'] }}, totalPenduduk.total)}`"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <a href="{{ url('data/wilayah') }}" class="inline-flex items-center justify-center w-full gap-2 px-4 py-3 mt-2 font-medium text-white transition-colors rounded-lg shadow-lg bg-[#2e7d32] hover:bg-[#1b6d21]">
                            Lihat Rincian per Wilayah
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                <path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 1 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
