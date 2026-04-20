@extends('layouts.backoffice')

@section('title', 'Laporan Presensi - Panel Administrasi')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
@endpush

@section('content')
<div class="flex-1 flex flex-col h-full overflow-hidden w-full relative bg-[#F8FAFC]">

    <!-- TOP NAVBAR -->
    <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 sm:px-6 shrink-0 z-10 shadow-sm mt-0">
        <div class="flex items-center gap-4">
            <div class="hidden sm:flex items-center gap-2">
                <span class="text-xs font-semibold px-2.5 py-1 bg-amber-100 text-amber-700 rounded-md uppercase tracking-wider">Akses Sekretariat</span>
                <h2 class="font-bold text-gray-800">Evaluasi Kinerja</h2>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2.5">
                <img src="https://ui-avatars.com/api/?name=Admin&background=fbc02d&color=000" class="w-8 h-8 rounded-full shadow-sm">
            </div>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto custom-scrollbar p-4 sm:p-6 lg:p-8" x-data="{ 
          activeTab: 'rekap', // rekap, log
          filterBulan: '{{ $filterBulan }}'
      }">
        <div class="max-w-7xl mx-auto space-y-6">

            <section class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Laporan Presensi Pegawai</h1>
                    <p class="text-sm text-gray-500 mt-1">Evaluasi kedisiplinan dan rekapitulasi daftar hadir aparatur pemerintahan desa.</p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex rounded-xl shadow-md border border-gray-200 overflow-hidden bg-primary-700">
                        <button class="px-5 py-2.5 text-sm font-bold text-white hover:bg-primary-800 transition-colors flex items-center gap-2 border-r border-primary-600">
                            <i class="fa-solid fa-file-pdf"></i> Cetak Laporan
                        </button>
                        <button class="px-5 py-2.5 text-sm font-bold text-white hover:bg-primary-800 transition-colors flex items-center gap-2">
                            <i class="fa-solid fa-file-excel"></i> Excel
                        </button>
                    </div>
                </div>
            </section>

            <section class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col sm:flex-row gap-4 justify-between items-center">
                <form action="{{ route('admin.presensi.index') }}" method="GET" class="w-full flex-1" id="filterForm">
                    <div class="flex flex-col md:flex-row gap-4 w-full">
                        <div class="relative w-full md:w-56">
                            <i class="fa-solid fa-calendar-days absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="month" name="bulan" x-model="filterBulan" onchange="document.getElementById('filterForm').submit()"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none cursor-pointer font-bold text-gray-700">
                        </div>

                        <div class="relative flex-1">
                            <i class="fa-solid fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama pegawai atau NIP..."
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
                        </div>

                        <div class="relative w-full md:w-48">
                            <select name="jabatan" onchange="document.getElementById('filterForm').submit()"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none cursor-pointer font-medium text-gray-700 appearance-none">
                                <option value="">Semua Jabatan</option>
                                <option value="Kasi" @if($jabatan == 'Kasi') selected @endif>Kasi / Kaur</option>
                                <option value="Dusun" @if($jabatan == 'Dusun') selected @endif>Kepala Dusun</option>
                                <option value="Staf" @if($jabatan == 'Staf') selected @endif>Staf Administrasi</option>
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                        </div>
                        
                        <button type="submit" class="hidden">Submit</button>
                    </div>
                </form>
            </section>

            <!-- KPI Cards -->
            <section class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between group hover:-translate-y-1 transition-transform">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Hadir Tepat Waktu</p>
                        <div class="w-8 h-8 rounded-lg bg-green-50 text-green-600 flex items-center justify-center"><i class="fa-solid fa-user-check text-xs"></i></div>
                    </div>
                    <div class="flex items-end gap-2">
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-gray-900">{{ number_format($kpiHadir, 0, ',', '.') }}</h3>
                        <span class="text-xs font-bold text-gray-500 mb-1">Hari Kerja</span>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-2">Kumulatif seluruh pegawai</p>
                </article>

                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between group border-b-4 border-b-amber-500 hover:-translate-y-1 transition-transform">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Terlambat</p>
                        <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center"><i class="fa-solid fa-user-clock text-xs"></i></div>
                    </div>
                    <div class="flex items-end gap-2">
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-amber-600">{{ number_format($kpiTerlambat, 0, ',', '.') }}</h3>
                        <span class="text-xs font-bold text-gray-500 mb-1">Kali</span>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-2">Kumulatif bulan ini</p>
                </article>

                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between group border-b-4 border-b-blue-500 hover:-translate-y-1 transition-transform">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Izin / Sakit / Dinas</p>
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center"><i class="fa-solid fa-user-nurse text-xs"></i></div>
                    </div>
                    <div class="flex items-end gap-2">
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-blue-600">{{ number_format($kpiIzinSakit, 0, ',', '.') }}</h3>
                        <span class="text-xs font-bold text-gray-500 mb-1">Hari</span>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-2">Dengan keterangan resmi</p>
                </article>

                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between group border-b-4 border-b-red-500 hover:-translate-y-1 transition-transform">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Alpha (Tanpa Keterangan)</p>
                        <div class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center"><i class="fa-solid fa-user-xmark text-xs"></i></div>
                    </div>
                    <div class="flex items-end gap-2">
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-red-600">{{ number_format($kpiAlfa, 0, ',', '.') }}</h3>
                        <span class="text-xs font-bold text-gray-500 mb-1">Hari</span>
                    </div>
                    @if($kpiAlfa > 0)
                        <p class="text-[10px] text-red-500 font-medium mt-2">Perlu teguran indisipliner</p>
                    @endif
                </article>
            </section>

            <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Trend -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col lg:col-span-2 overflow-hidden">
                    <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center shrink-0">
                        <div>
                            <h3 class="font-bold text-gray-800">Tren Kedisiplinan Harian</h3>
                            <p class="text-[10px] text-gray-500 mt-0.5">Perbandingan pegawai hadir tepat waktu vs terlambat.</p>
                        </div>
                    </div>
                    <div class="p-5 flex-1"><div id="chartTrenPresensi" class="w-full h-72"></div></div>
                </div>

                <!-- Highlights -->
                <div class="flex flex-col gap-6 lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-sm border border-green-100 p-5 flex-1">
                        <h3 class="font-bold text-green-800 text-sm mb-4 flex items-center gap-2"><i class="fa-solid fa-award text-amber-400"></i> Pegawai Paling Disiplin</h3>
                        <ul class="space-y-3">
                            @foreach($topPegawai as $idx => $pegawaiRank)
                            <li class="flex items-center gap-3">
                                <div class="w-8 h-8 {{ $idx == 0 ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }} rounded-full flex items-center justify-center font-bold text-xs">{{ $idx + 1 }}</div>
                                <div class="flex-1">
                                    <p class="font-bold text-gray-800 text-sm">{{ $pegawaiRank->pegawai->name }}</p>
                                    <p class="text-[10px] text-gray-500">Hadir: {{ $pegawaiRank->total_hadir }}/{{ $totalHariKerja }} Hari ({{ $pegawaiRank->performa }}%)</p>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    @if($bottomPegawai && ($bottomPegawai->terlambat > 0 || $bottomPegawai->alfa > 0))
                    <div class="bg-red-50 rounded-2xl shadow-sm border border-red-100 p-5 flex-1">
                        <h3 class="font-bold text-red-800 text-sm mb-4 flex items-center gap-2"><i class="fa-solid fa-circle-exclamation text-red-500"></i> Evaluasi Kedisiplinan</h3>
                        <ul class="space-y-3">
                            <li class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-red-100 text-red-600 rounded-full flex items-center justify-center"><i class="fa-solid fa-user-clock text-xs"></i></div>
                                <div class="flex-1">
                                    <p class="font-bold text-gray-800 text-sm">{{ $bottomPegawai->pegawai->name }}</p>
                                    <p class="text-[10px] text-red-600 font-medium">Terlambat {{ $bottomPegawai->terlambat }}x, Alpha {{ $bottomPegawai->alfa }}x</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                    @endif
                </div>
            </section>

            <!-- Tabs Navigation -->
            <section class="flex gap-2 p-1 bg-white rounded-xl w-full md:w-auto overflow-x-auto shadow-sm border border-gray-100">
                <button @click="activeTab = 'rekap'" :class="activeTab === 'rekap' ? 'bg-primary-50 text-primary-700 font-bold border border-primary-200' : 'text-gray-500 hover:text-gray-700 border border-transparent'" class="px-6 py-2 text-sm rounded-lg transition-all whitespace-nowrap"><i class="fa-solid fa-table-list mr-1"></i> Rekapitulasi Presensi</button>
                <button @click="activeTab = 'log'" :class="activeTab === 'log' ? 'bg-primary-50 text-primary-700 font-bold border border-primary-200' : 'text-gray-500 hover:text-gray-700 border border-transparent'" class="px-6 py-2 text-sm rounded-lg transition-all whitespace-nowrap"><i class="fa-solid fa-clock-rotate-left mr-1"></i> Log Aktivitas Harian</button>
            </section>

            <!-- TAB 1: REKAP -->
            <section x-show="activeTab === 'rekap'" x-transition.opacity class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden flex flex-col">
                <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <div>
                        <h3 class="font-bold text-gray-800">Tabel Rekapitulasi Bulan {{ $startOfMonth->translatedFormat('F Y') }}</h3>
                        <p class="text-[10px] text-gray-500 mt-0.5">Total hari kerja efektif: {{ $totalHariKerja }} Hari.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 text-[10px] uppercase tracking-wider border-b border-gray-200">
                                <th class="p-4 font-bold">Pegawai / Jabatan</th>
                                <th class="p-4 font-bold text-center">Hadir Tepat (H)</th>
                                <th class="p-4 font-bold text-center text-amber-600">Terlambat (T)</th>
                                <th class="p-4 font-bold text-center text-blue-600">Izin/Sakit/Dnst (I/S)</th>
                                <th class="p-4 font-bold text-center text-red-600">Alpha (A)</th>
                                <th class="p-4 font-bold text-center">Performa (%)</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-gray-100">
                            @forelse($rekapData as $rk)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $rk->pegawai->avatarUrl() }}" class="w-8 h-8 rounded-lg shadow-sm" alt="Avatar">
                                        <div>
                                            <p class="font-bold text-gray-900 leading-tight">{{ $rk->pegawai->name }}</p>
                                            <p class="text-[10px] text-gray-500 mt-0.5 font-medium">{{ $rk->pegawai->jabatan }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 text-center font-bold text-gray-700">{{ $rk->hadir_tepat }}</td>
                                <td class="p-4 text-center font-bold text-amber-600">{{ $rk->terlambat }}</td>
                                <td class="p-4 text-center font-bold text-blue-600">{{ $rk->izin_sakit }}</td>
                                <td class="p-4 text-center font-bold text-red-600">{{ $rk->alfa }}</td>
                                <td class="p-4 text-center">
                                    <div class="flex flex-col items-center">
                                        <span class="text-xs font-extrabold {{ $rk->performa >= 90 ? 'text-green-600' : ($rk->performa >= 75 ? 'text-amber-600' : 'text-red-600') }}">{{ $rk->performa }}%</span>
                                        <div class="w-16 h-1 bg-gray-100 rounded-full mt-1">
                                            <div class="h-1 {{ $rk->performa >= 90 ? 'bg-green-500' : ($rk->performa >= 75 ? 'bg-amber-500' : 'bg-red-500') }} rounded-full" style="width: {{ $rk->performa }}%"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="p-6 text-center text-gray-500">Tidak ada rekap data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- TAB 2: LOG -->
            <section x-show="activeTab === 'log'" x-transition.opacity class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden flex flex-col" x-cloak>
                <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row justify-between sm:items-center gap-4 bg-gray-50/50">
                    <div>
                        <h3 class="font-bold text-gray-800">Log Presensi Harian</h3>
                        <p class="text-[10px] text-gray-500 mt-0.5">Catatan waktu check-in, check-out, dan lokasi presensi.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 text-[10px] uppercase tracking-wider border-b border-gray-200">
                                <th class="p-4 font-bold">Tanggal</th>
                                <th class="p-4 font-bold">Pegawai</th>
                                <th class="p-4 font-bold text-center">Masuk / Pulang</th>
                                <th class="p-4 font-bold">Sumber / Lokasi</th>
                                <th class="p-4 font-bold text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-gray-100 font-mono text-xs">
                            @forelse($logHarian as $log)
                            <tr class="hover:bg-gray-50">
                                <td class="p-4 text-gray-500 font-sans">{{ $log->tanggal->format('d-m-Y') }}</td>
                                <td class="p-4 font-sans font-semibold text-gray-800">{{ $log->pegawai->name }}</td>
                                <td class="p-4 text-center">
                                    @if($log->waktu_masuk)
                                        <span class="{{ $log->status == 'terlambat' ? 'text-red-600' : 'text-gray-800' }} font-bold">{{ \Carbon\Carbon::parse($log->waktu_masuk)->format('H:i') }}</span>
                                        <span class="text-gray-400 mx-1">-</span>
                                        <span class="text-gray-800 font-bold">{{ $log->waktu_pulang ? \Carbon\Carbon::parse($log->waktu_pulang)->format('H:i') : '??:??' }}</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="p-4 font-sans text-[10px] text-gray-500">
                                    @if($log->metode_masuk == 'kiosk')
                                        <i class="fa-solid fa-desktop text-blue-500 mr-1"></i> Kiosk Balai
                                    @elseif($log->metode_masuk == 'manual')
                                        <i class="fa-solid fa-user-pen text-amber-500 mr-1"></i> Input Manual
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="p-4 text-center font-sans">
                                    @php
                                        $bgStatus = 'bg-gray-100 text-gray-700';
                                        if($log->status == 'hadir') $bgStatus = 'bg-green-100 text-green-700';
                                        elseif($log->status == 'terlambat') $bgStatus = 'bg-amber-100 text-amber-700';
                                        elseif(in_array($log->status, ['izin','sakit','dinas'])) $bgStatus = 'bg-blue-100 text-blue-700';
                                        elseif($log->status == 'alpha') $bgStatus = 'bg-red-100 text-red-700';
                                    @endphp
                                    <span class="{{ $bgStatus }} px-2 py-0.5 rounded font-bold text-[10px] uppercase">{{ $log->statusLabel() }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="p-6 font-sans text-center text-gray-500">Belum ada log aktivitas.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($logHarian->hasPages())
                <div class="px-5 py-4 border-t border-gray-100">
                    {{ $logHarian->withQueryString()->links() }}
                </div>
                @endif
            </section>

        </div>
    </main>

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dtTren = @json($chartTren);

        if (typeof Highcharts !== 'undefined') {
            Highcharts.setOptions({
                colors: ['#22c55e', '#f59e0b', '#3b82f6', '#ef4444'],
                chart: { style: { fontFamily: 'Inter, sans-serif' }, backgroundColor: 'transparent' },
                title: { text: null },
                credits: { enabled: false },
                legend: { itemStyle: { fontWeight: '500', color: '#4b5563', fontSize: '10px' } },
                tooltip: { backgroundColor: '#1e293b', style: { color: '#ffffff' }, borderWidth: 0, borderRadius: 8 }
            });

            Highcharts.chart('chartTrenPresensi', {
                chart: { type: 'areaspline' },
                xAxis: {
                    categories: dtTren.categories,
                    gridLineWidth: 0,
                    labels: { style: { fontSize: '10px' } }
                },
                yAxis: {
                    title: { text: 'Jumlah Pegawai' },
                    gridLineColor: '#f1f5f9'
                },
                plotOptions: {
                    areaspline: { fillOpacity: 0.1, lineWidth: 3, marker: { symbol: 'circle' } }
                },
                series: [{
                    name: 'Hadir Tepat Waktu',
                    data: dtTren.hadir,
                    color: '#16a34a'
                }, {
                    name: 'Terlambat',
                    data: dtTren.terlambat,
                    color: '#f59e0b'
                }]
            });
        }
    });
</script>
@endpush
@endsection
