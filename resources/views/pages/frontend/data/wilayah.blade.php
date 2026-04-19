@extends('layouts.frontend')

@section('title', 'Data Wilayah Administratif - Desa Sindangmukti')

@push('alpine_plugins')
<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
@endpush

@push('scripts')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script>
    function dataWilayah() {
        return {
            openDusun: null, 
            openRW: null, 
            
            dataDusun: @json($dataDusun),
            totalPenduduk: @json($totalPenduduk),
            mapData: @json($mapData),

            getCardIconClass(stat) {
                if (stat.isHighlight)
                    return "bg-white bg-opacity-25 text-white";

                const colors = {
                    blue: "bg-blue-100 text-blue-700",
                    yellow: "bg-yellow-100 text-yellow-700",
                    amber: "bg-amber-100 text-amber-700",
                    indigo: "bg-indigo-100 text-indigo-700",
                    purple: "bg-purple-100 text-purple-700",
                };
                return (
                    colors[stat.color] || "bg-gray-100 text-gray-700"
                );
            },

            toggleDusun(namaDusun) {
                this.openDusun =
                    this.openDusun === namaDusun ? null : namaDusun;
            },
            
            toggleRW(namaRWUnik) {
                this.openRW =
                    this.openRW === namaRWUnik ? null : namaRWUnik;
            },

            initMap() {
                const mapEl = document.getElementById("mapWilayah");
                if (!mapEl) return;

                const map = L.map("mapWilayah").setView(
                    this.mapData.center,
                    this.mapData.zoom
                );

                L.tileLayer(
                    "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
                    {
                        attribution:
                            '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                        maxZoom: 19,
                    }
                ).addTo(map);

                const styleDusun = (feature) => {
                    const namaDusun = feature.properties.NAMOBJ;
                    const color =
                        this.mapData.colors[namaDusun.toUpperCase()] ||
                        "#9E9E9E";
                    return {
                        color: color,
                        weight: 2,
                        opacity: 1,
                        fillColor: color,
                        fillOpacity: 0.2,
                    };
                };

                const geoJSONUrl = new URL(
                    this.mapData.urlGeoJSON,
                    document.baseURI
                ).href;

                fetch(geoJSONUrl)
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error(
                                `Network response was not ok: ${response.statusText}`
                            );
                        }
                        return response.json();
                    })
                    .then((data) => {
                        L.geoJSON(data, {
                            style: styleDusun,
                            onEachFeature: (feature, layer) => {
                                const namaDusun =
                                    feature.properties.NAMOBJ;
                                const dusunData = this.dataDusun.find(
                                    (d) =>
                                        d.nama.toUpperCase() ===
                                        namaDusun.toUpperCase()
                                );

                                if (dusunData) {
                                    layer.bindTooltip(
                                        `
                    <div class="font-sans">
                      <strong class="text-base text-[#2e7d32]">${dusunData.nama}</strong>
                      <hr class="my-1">
                      <p class="text-xs text-gray-700">Kepala: ${dusunData.kadus}</p>
                      <p class="text-xs text-gray-700">Total KK: <span class="font-bold">${dusunData.kk}</span></p>
                      <p class="text-xs text-gray-700">Total Jiwa: <span class="font-bold">${dusunData.total}</span></p>
                    </div>
                  `,
                                        { sticky: true }
                                    );
                                } else {
                                    layer.bindTooltip(
                                        `<strong class="text-[#2e7d32]">${namaDusun}</strong>`
                                    );
                                }
                            },
                        }).addTo(map);
                    })
                    .catch((err) => {
                        console.error(
                            "Error: Gagal memuat file GeoJSON.",
                            err
                        );
                    });
            },

            setHighchartsTheme() {
                Highcharts.setOptions({
                    colors: [
                        "#2E7D32", // primary
                        "#3B82F6", // Blue
                        "#EC4899", // Pink
                        "#FBC02D", // accent yellow
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
                            color: "#1f2937",
                            fontSize: "1.125rem",
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
                    legend: {
                        itemStyle: {
                            color: "#374151", 
                        },
                        itemHoverStyle: {
                            color: "#000000",
                        },
                    },
                    credits: {
                        enabled: false,
                    },
                });
            },

            initWilayahColumnChart() {
                const ctxEl = document.getElementById(
                    "wilayahGroupedChart"
                );
                if (!ctxEl) return;

                const categories = this.dataDusun.map((d) => d.nama);
                const seriesData = [
                    {
                        name: "Total Jiwa",
                        data: this.dataDusun.map((d) => d.total),
                    },
                    {
                        name: "Laki-laki",
                        data: this.dataDusun.map((d) => d.l),
                    },
                    {
                        name: "Perempuan",
                        data: this.dataDusun.map((d) => d.p),
                    },
                    {
                        name: "Kepala Keluarga (KK)",
                        data: this.dataDusun.map((d) => d.kk),
                    },
                ];

                Highcharts.chart("wilayahGroupedChart", {
                    chart: {
                        type: "column",
                    },
                    title: {
                        text: "Populasi per Dusun",
                        align: "left",
                    },
                    xAxis: {
                        categories: categories,
                        crosshair: true,
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: "Jumlah Jiwa / KK",
                        },
                    },
                    tooltip: {
                        headerFormat:
                            '<span style="font-size:10px">{point.key}</span><table>',
                        pointFormat:
                            '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                            '<td style="padding:0"><b>{point.y:,.0f}</b></td></tr>',
                        footerFormat: "</table>",
                        shared: true,
                        useHTML: true,
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0,
                        },
                    },
                    series: seriesData,
                });
            },
        };
    }
</script>
@endpush

@section('content')
<main class="pt-24 bg-gray-50" x-data="dataWilayah()" x-init="setHighchartsTheme(); initMap(); initWilayahColumnChart();">
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
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                <div class="p-6 bg-white border border-gray-100 shadow-lg lg:col-span-2 rounded-xl">
                    <h3 class="mb-4 text-lg font-semibold text-[#1f2937]">
                        Peta Wilayah Dusun
                    </h3>
                    <p class="mb-4 text-sm text-gray-600">
                        Arahkan kursor atau klik pada wilayah dusun di
                        peta untuk melihat informasi detail.
                    </p>
                    <div id="mapWilayah" class="w-full h-[450px] rounded-lg z-10 bg-gray-200"></div>
                </div>

                <div class="lg:col-span-1">
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-[#1f2937]">
                            Ringkasan Wilayah
                        </h3>

                        @foreach($statistikWilayah as $stat)
                            <div class="flex items-center p-4 space-x-4 transition-all bg-white shadow-lg rounded-xl hover:shadow-xl text-[#1f2937]">
                                <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 rounded-full" :class="getCardIconClass({ color: '{{ $stat['color'] }}', isHighlight: {{ $stat['isHighlight'] ? 'true' : 'false' }} })">
                                    <svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        {!! $stat['icon'] !!}
                                    </svg>
                                </div>
                                <div class="flex-grow">
                                    <p class="text-base font-medium text-gray-700">{{ $stat['label'] }}</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stat['value'], 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="pb-16">
        <div class="px-6 mx-auto max-w-7xl lg:px-8">
            <div class="overflow-hidden bg-white border border-gray-100 shadow-lg rounded-xl">
                <h3 class="p-6 text-lg font-semibold border-b text-[#1f2937]">
                    Data Rincian Penduduk per Wilayah
                </h3>

                <div class="p-6 border-b border-gray-200">
                    <h4 class="mb-2 text-base font-semibold text-gray-700">
                        Visualisasi Data per Dusun
                    </h4>
                    <p class="mb-4 text-sm text-gray-600">
                        Grafik perbandingan jumlah Total Jiwa,
                        Laki-laki, Perempuan, dan Kepala Keluarga (KK)
                        di setiap dusun.
                    </p>
                    <div class="w-full h-96">
                        <div id="wilayahGroupedChart" class="w-full h-96"></div>
                    </div>
                </div>

                <div class="p-4 space-y-3 md:p-6 bg-gray-50">
                    <template x-for="(dusun, dusunIndex) in dataDusun" :key="dusun.nama">
                        <div class="overflow-hidden border border-gray-200 rounded-lg shadow-sm">
                            <button @click="toggleDusun(dusun.nama)" class="flex flex-col w-full p-4 text-left transition md:flex-row md:items-center" :class="openDusun === dusun.nama ? 'bg-green-100' : 'bg-white hover:bg-gray-50'">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3">
                                        <span class="flex items-center justify-center w-8 h-8 text-sm font-bold text-white rounded-full bg-[#2e7d32]" x-text="dusunIndex + 1">
                                        </span>
                                        <div>
                                            <h4 class="text-lg font-semibold text-[#2e7d32]" x-text="'Dusun ' + dusun.nama"></h4>
                                            <p class="text-xs text-gray-600" x-text="'Kepala: ' + dusun.kadus"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="grid flex-shrink-0 grid-cols-4 gap-4 mt-4 text-center md:mt-0 md:ml-8">
                                    <div>
                                        <span class="text-xs text-gray-500">KK</span>
                                        <p class="text-lg font-bold text-[#1f2937]" x-text="dusun.kk"></p>
                                    </div>
                                    <div>
                                        <span class="text-xs text-gray-500">Total Jiwa</span>
                                        <p class="text-lg font-bold text-[#1f2937]" x-text="dusun.total"></p>
                                    </div>
                                    <div>
                                        <span class="text-xs text-gray-500">Laki-laki</span>
                                        <p class="text-lg font-bold text-[#1f2937]" x-text="dusun.l"></p>
                                    </div>
                                    <div>
                                        <span class="text-xs text-gray-500">Perempuan</span>
                                        <p class="text-lg font-bold text-[#1f2937]" x-text="dusun.p"></p>
                                    </div>
                                </div>
                                <div class="flex-shrink-0 hidden w-8 ml-6 text-gray-400 md:block">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 transition-transform duration-300" :class="openDusun === dusun.nama ? 'rotate-180' : ''">
                                        <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>

                            <div x-show="openDusun === dusun.nama" x-collapse class="p-4 space-y-2 bg-white border-t border-gray-200">
                                <template x-for="(rw, rwIndex) in dusun.rw" :key="rw.nama">
                                    <div class="overflow-hidden border border-gray-200 rounded-md">
                                        <button @click="toggleRW(dusun.nama + '-' + rw.nama)" class="flex flex-col w-full p-3 text-left transition md:flex-row md:items-center" :class="openRW === (dusun.nama + '-' + rw.nama) ? 'bg-gray-200' : 'bg-gray-100 hover:bg-gray-200'">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-3">
                                                    <span class="flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-gray-500 rounded-full" x-text="rwIndex + 1">
                                                    </span>
                                                    <div>
                                                        <h5 class="font-semibold text-gray-800" x-text="rw.nama"></h5>
                                                        <p class="text-xs text-gray-600" x-text="'Ketua: ' + rw.ketua"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="grid flex-shrink-0 grid-cols-4 gap-4 mt-3 text-center md:mt-0 md:ml-8">
                                                <div>
                                                    <span class="text-xs text-gray-500">KK</span>
                                                    <p class="font-semibold text-[#1f2937]" x-text="rw.kk"></p>
                                                </div>
                                                <div>
                                                    <span class="text-xs text-gray-500">Total</span>
                                                    <p class="font-semibold text-[#1f2937]" x-text="rw.total"></p>
                                                </div>
                                                <div>
                                                    <span class="text-xs text-gray-500">L</span>
                                                    <p class="font-semibold text-[#1f2937]" x-text="rw.l"></p>
                                                </div>
                                                <div>
                                                    <span class="text-xs text-gray-500">P</span>
                                                    <p class="font-semibold text-[#1f2937]" x-text="rw.p"></p>
                                                </div>
                                            </div>
                                            <div class="flex-shrink-0 w-6 text-gray-400 md:ml-6">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 transition-transform duration-300" :class="openRW === (dusun.nama + '-' + rw.nama) ? 'rotate-180' : ''">
                                                    <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </button>

                                        <div x-show="openRW === (dusun.nama + '-' + rw.nama)" x-collapse class="border-t border-gray-200">
                                            <table class="min-w-full text-sm">
                                                <thead class="text-xs text-gray-700 bg-gray-50">
                                                    <tr>
                                                        <th class="px-4 py-2 text-center">No</th>
                                                        <th class="px-4 py-2 text-left">Wilayah / Ketua</th>
                                                        <th class="px-4 py-2 text-center">KK</th>
                                                        <th class="px-4 py-2 text-center">Total</th>
                                                        <th class="px-4 py-2 text-center">L</th>
                                                        <th class="px-4 py-2 text-center">P</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-100">
                                                    <template x-for="(rt, rtIndex) in rw.rt" :key="rt.nama">
                                                        <tr>
                                                            <td class="px-4 py-2 text-center" x-text="rtIndex + 1"></td>
                                                            <td class="px-4 py-2">
                                                                <span class="font-medium text-gray-800" x-text="rt.nama"></span>
                                                                <span class="block text-xs text-gray-500" x-text="'Ketua: ' + rt.ketua"></span>
                                                            </td>
                                                            <td class="px-4 py-2 text-center" x-text="rt.kk"></td>
                                                            <td class="px-4 py-2 text-center" x-text="rt.total"></td>
                                                            <td class="px-4 py-2 text-center" x-text="rt.l"></td>
                                                            <td class="px-4 py-2 text-center" x-text="rt.p"></td>
                                                        </tr>
                                                    </template>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <div class="flex flex-col p-4 mt-4 font-bold border-t-2 rounded-lg md:flex-row md:items-center bg-[#2e7d32]/10 border-[#2e7d32] text-[#2e7d32]">
                        <div class="flex-1">
                            <h4 class="text-xl font-bold text-[#2e7d32]">
                                TOTAL KESELURUHAN
                            </h4>
                        </div>
                        <div class="grid flex-shrink-0 grid-cols-4 gap-4 mt-4 text-center md:mt-0 md:ml-8">
                            <div>
                                <span class="text-xs font-medium">KK</span>
                                <p class="text-2xl font-bold" x-text="totalPenduduk.kk"></p>
                            </div>
                            <div>
                                <span class="text-xs font-medium">Total Jiwa</span>
                                <p class="text-2xl font-bold" x-text="totalPenduduk.total"></p>
                            </div>
                            <div>
                                <span class="text-xs font-medium">Laki-laki</span>
                                <p class="text-2xl font-bold" x-text="totalPenduduk.l"></p>
                            </div>
                            <div>
                                <span class="text-xs font-medium">Perempuan</span>
                                <p class="text-2xl font-bold" x-text="totalPenduduk.p"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
