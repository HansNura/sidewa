@extends('layouts.frontend')

@section('title', 'Data Kelompok Umur - Desa Sindangmukti')

@push('scripts')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script>
    function dataKelompokUmur() {
        return {
            statistikKelompokUmur: @json($statistikKelompokUmur),

            getTotalPenduduk() {
                const totalStat = this.statistikKelompokUmur.find(
                    (s) => s.isHighlight
                );
                return totalStat ? totalStat.value : 0;
            },
            calculatePercentage(value, total) {
                if (total === 0) return "0.0%";
                const val = value;
                return ((val / total) * 100).toFixed(1) + "%";
            },
            getCardIconClass(stat) {
                if (stat.isHighlight)
                    return "bg-white bg-opacity-25 text-white";

                const colors = {
                    blue: "bg-blue-100 text-blue-600",
                    cyan: "bg-cyan-100 text-cyan-600",
                    teal: "bg-teal-100 text-teal-600",
                    green: "bg-green-100 text-green-600",
                    lime: "bg-lime-100 text-lime-600",
                    yellow: "bg-yellow-100 text-yellow-600",
                    amber: "bg-amber-100 text-amber-600",
                    orange: "bg-orange-100 text-orange-600",
                    deeporange: "bg-orange-100 text-orange-600", // Tailwind doesn't have deep-orange exactly, mapping to orange
                    brown: "bg-[#f5f5f4] text-[#78716c]", // mapping to stone
                    gray: "bg-gray-200 text-gray-600",
                    bluegray: "bg-slate-100 text-slate-600", // mapping to slate
                    purple: "bg-purple-100 text-purple-600",
                    indigo: "bg-indigo-100 text-indigo-600",
                    pink: "bg-pink-100 text-pink-600",
                };
                return (
                    colors[stat.color] || "bg-gray-100 text-gray-700"
                );
            },
            getCardProgressClass(stat) {
                if (stat.isHighlight) return "";
                const colors = {
                    blue: "text-blue-500",
                    cyan: "text-cyan-500",
                    teal: "text-teal-500",
                    green: "text-green-500",
                    lime: "text-lime-500",
                    yellow: "text-yellow-500",
                    amber: "text-amber-500",
                    orange: "text-orange-500",
                    deeporange: "text-orange-500",
                    brown: "text-[#78716c]",
                    gray: "text-gray-500",
                    bluegray: "text-slate-500",
                    purple: "text-purple-500",
                    indigo: "text-indigo-500",
                    pink: "text-pink-500",
                };
                return colors[stat.color] || "text-gray-500";
            },
            getProgressBarClass(stat) {
                if (stat.isHighlight) return "";
                const colors = {
                    blue: "bg-blue-500",
                    cyan: "bg-cyan-500",
                    teal: "bg-teal-500",
                    green: "bg-green-500",
                    lime: "bg-lime-500",
                    yellow: "bg-yellow-500",
                    amber: "bg-amber-500",
                    orange: "bg-orange-500",
                    deeporange: "bg-orange-500",
                    brown: "bg-[#78716c]",
                    gray: "bg-gray-500",
                    bluegray: "bg-slate-500",
                    purple: "bg-purple-500",
                    indigo: "bg-indigo-500",
                    pink: "bg-pink-500",
                };
                return colors[stat.color] || "bg-gray-500";
            },

            setHighchartsTheme() {
                Highcharts.setOptions({
                    colors: [
                        "#26A69A",
                        "#0288D1",
                        "#5E35B1",
                        "#2E7D32",
                        "#FBC02D",
                        "#D32F2F",
                        "#FFB300",
                        "#1E88E5",
                        "#43A047",
                        "#E53935",
                        "#8E24AA",
                        "#6D4C41",
                        "#757575",
                        "#00ACC1",
                        "#C0CA33",
                    ],
                    chart: {
                        style: {
                            fontFamily: "Poppins, sans-serif",
                        },
                        backgroundColor: "transparent",
                    },
                    title: {
                        style: {
                            color: "#1f2937",
                            fontSize: "1.25rem",
                            fontWeight: "600",
                        },
                    },
                    subtitle: {
                        style: {
                            color: "#4B5563",
                        },
                    },
                    tooltip: {
                        backgroundColor: "#1F2937",
                        style: {
                            color: "#FFFFFF",
                        },
                    },
                    credits: {
                        enabled: false,
                    },
                });
            },

            initKelompokUmurChart() {
                const totalPenduduk = this.getTotalPenduduk();
                const chartData = this.statistikKelompokUmur.filter(
                    (item) => !item.isHighlight
                );

                const categories = chartData.map((item) => item.label);
                const seriesData = chartData.map((item) => item.value);

                Highcharts.chart("kelompok-umur-chart", {
                    chart: {
                        type: "column", 
                    },
                    title: {
                        text: "Grafik Penduduk Berdasarkan Kelompok Umur",
                        align: "center",
                    },
                    subtitle: {
                        text:
                            "Data Per 9 November 2025. Total Penduduk (6th+): " +
                            totalPenduduk.toLocaleString("id-ID"),
                        align: "center",
                    },
                    xAxis: {
                        categories: categories,
                        crosshair: true,
                        labels: {
                            rotation: -45,
                            style: {
                                fontSize: "10px",
                            },
                        },
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: "Jumlah Jiwa",
                        },
                    },
                    tooltip: {
                        headerFormat:
                            '<span style="font-size:10px">{point.key}</span><table>',
                        pointFormat:
                            '<tr><td style="color:{series.color};padding:0">Jumlah: </td>' +
                            '<td style="padding:0"><b>{point.y:,.0f} jiwa</b></td></tr>',
                        footerFormat: "</table>",
                        shared: true,
                        useHTML: true,
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0,
                            borderRadius: 5,
                        },
                    },
                    series: [
                        {
                            name: "Jumlah Penduduk",
                            colorByPoint: true,
                            data: seriesData,
                        },
                    ],
                    legend: {
                        enabled: false,
                    },
                });
            },
        };
    }
</script>
@endpush

@section('content')
<main class="pt-24 bg-gray-50" x-data="dataKelompokUmur()" x-init="setHighchartsTheme(); initKelompokUmurChart();">
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
            <div class="w-full p-6 mx-auto bg-white shadow-lg rounded-xl md:p-8">
                <div id="kelompok-umur-chart" style="height: 500px"></div>
            </div>
        </div>
    </section>

    <section class="pb-16 -mt-8" x-data="{ total: getTotalPenduduk() }">
        <div class="px-6 mx-auto space-y-6 max-w-7xl lg:px-8">
            <h2 class="mb-6 text-2xl font-bold text-center text-[#1f2937]">
                Rincian Statistik Kelompok Umur
            </h2>

            @php
                $highlightStat = collect($statistikKelompokUmur)->firstWhere('isHighlight', true);
                $otherStats = collect($statistikKelompokUmur)->where('isHighlight', false);
            @endphp

            @if($highlightStat)
                <div class="flex items-center max-w-4xl p-4 mx-auto space-x-4 transition-all shadow-lg rounded-xl hover:shadow-xl bg-[#2e7d32] text-white">
                    <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 rounded-full bg-white bg-opacity-25 text-white">
                        <svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            {!! $highlightStat['icon'] !!}
                        </svg>
                    </div>
                    <div class="flex-grow">
                        <p class="text-base font-medium text-green-100">{{ $highlightStat['label'] }}</p>
                        <p class="text-2xl font-bold text-white">{{ number_format($highlightStat['value'], 0, ',', '.') }} {{ $highlightStat['unit'] }}</p>
                    </div>
                    <div class="flex-shrink-0 w-32 text-right">
                        <p class="text-sm font-medium text-green-100">Data Per</p>
                        <p class="text-xs text-green-100">{{ $highlightStat['timestamp'] }}</p>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($otherStats as $stat)
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
                                <p class="mb-1 text-base font-bold" :class="getCardProgressClass({ color: '{{ $stat['color'] }}', isHighlight: false })" x-text="calculatePercentage({{ $stat['value'] }}, total)"></p>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="h-2.5 rounded-full" :class="getProgressBarClass({ color: '{{ $stat['color'] }}', isHighlight: false })" :style="`width: ${calculatePercentage({{ $stat['value'] }}, total)}`"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</main>
@endsection
