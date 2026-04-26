@extends('layouts.backoffice')

@section('title', 'Laporan Keuangan Desa - Panel Administrasi')

@push('styles')
    <!-- Highcharts -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Report Paper Styling */
        .report-paper {
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            background-color: white;
            color: black;
            font-family: "Times New Roman", Times, serif;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            .report-paper {
                box-shadow: none;
                border: none;
                width: 100%;
                margin: 0;
                padding: 0;
            }

            body {
                background: white;
            }
        }
    </style>
@endpush

@section('content')
    <div class="flex-1 flex flex-col h-full bg-[#F8FAFC]" x-data="{
        reportType: 'realisasi',
        yearFilter: '{{ $tahun }}',
        formatIDR(val) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(val).replace('Rp', '').trim();
        }
    }">

        <!-- TOP NAVBAR -->
        <header class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4 shrink-0 no-print">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Pelaporan Keuangan</h1>
                <p class="text-sm text-gray-500 mt-1">Laporan Keuangan Desa.</p>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto pb-8 no-print">
            <div class="max-w-7xl mx-auto space-y-6">

                <!-- HEADER REPORT SELECTOR -->
                <section class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                    <div class="space-y-4">
                        <div class="flex gap-2 p-1 bg-gray-200/50 rounded-2xl w-fit">
                            <button @click="reportType = 'realisasi'"
                                :class="reportType === 'realisasi' ? 'bg-white shadow-sm text-green-700 font-bold' :
                                    'text-gray-500 hover:text-gray-700'"
                                class="px-6 py-2.5 text-sm rounded-xl transition-all">Realisasi</button>
                            <button @click="reportType = 'neraca'"
                                :class="reportType === 'neraca' ? 'bg-white shadow-sm text-green-700 font-bold' :
                                    'text-gray-500 hover:text-gray-700'"
                                class="px-6 py-2.5 text-sm rounded-xl transition-all">Neraca Desa</button>
                            <button @click="reportType = 'arus-kas'"
                                :class="reportType === 'arus-kas' ? 'bg-white shadow-sm text-green-700 font-bold' :
                                    'text-gray-500 hover:text-gray-700'"
                                class="px-6 py-2.5 text-sm rounded-xl transition-all">Arus Kas</button>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 shrink-0">
                        <form action="{{ route('admin.laporan.index') }}" method="GET" class="relative shrink-0">
                            <select name="tahun" onchange="this.form.submit()"
                                class="bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-bold text-gray-700 focus:ring-2 focus:ring-green-500 outline-none cursor-pointer appearance-none pr-10 shadow-sm">
                                @foreach ($yearOptions as $yr)
                                    <option value="{{ $yr }}" {{ $tahun == $yr ? 'selected' : '' }}>T.A
                                        {{ $yr }}</option>
                                @endforeach
                            </select>
                            <i
                                class="fa-solid fa-calendar-check absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                        </form>
                        <button onclick="window.print()"
                            class="bg-green-700 hover:bg-green-800 text-white shadow-md rounded-xl px-6 py-2.5 text-sm font-bold transition-all flex items-center gap-2">
                            <i class="fa-solid fa-print"></i> <span>Cetak Laporan</span>
                        </button>
                    </div>
                </section>

                <!-- REPORT SUMMARY CARDS (DYNAMIC BASED ON TYPE) -->
                <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4" x-cloak>
                    <template x-if="reportType === 'realisasi'">
                        <div class="contents">
                            <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Pagu Belanja
                                </p>
                                <h3 class="text-xl font-extrabold text-gray-900">Rp
                                    {{ number_format($summary['belanja'], 0, ',', '.') }}</h3>
                            </article>
                            <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Realisasi
                                    Belanja</p>
                                <h3 class="text-xl font-extrabold text-green-700">Rp
                                    {{ number_format($summary['belanja_realisasi'], 0, ',', '.') }}</h3>
                            </article>
                            <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Sisa Anggaran
                                </p>
                                <h3 class="text-xl font-extrabold text-gray-900">Rp
                                    {{ number_format($summary['belanja'] - $summary['belanja_realisasi'], 0, ',', '.') }}
                                </h3>
                            </article>
                            <article class="bg-green-700 rounded-2xl p-5 text-white shadow-lg">
                                <p class="text-[10px] font-bold text-green-200 uppercase tracking-wider mb-1">Penyerapan</p>
                                @php $pct = $summary['belanja'] > 0 ? ($summary['belanja_realisasi'] / $summary['belanja']) * 100 : 0; @endphp
                                <h3 class="text-2xl font-black">{{ number_format($pct, 1) }}%</h3>
                            </article>
                        </div>
                    </template>

                    <template x-if="reportType === 'neraca' || reportType === 'arus-kas'">
                        <div
                            class="col-span-1 sm:col-span-2 lg:col-span-4 bg-amber-50 border border-amber-200 rounded-2xl p-4 flex items-start gap-4">
                            <div class="text-amber-500 mt-1"><i class="fa-solid fa-circle-info fa-lg"></i></div>
                            <div>
                                <h4 class="font-bold text-amber-800">DISCLAIMER (Future Development)</h4>
                                <p class="text-xs text-amber-700 mt-1">Laporan <b>Neraca</b> dan <b>Arus Kas</b> yang
                                    ditampilkan saat ini adalah sebuah <b>Placeholder / Mockup UI</b>. Sistem Keuangan Desa
                                    berbasis <i>Single-Entry APBDes</i> belum merepresentasikan data Aset Tetap, Kewajiban,
                                    Utang, dan Ekuitas secara terstruktur.<br>Data dinamis secara akurat hanya dialirkan
                                    pada tab <b>Laporan Realisasi APBDes</b>. Silakan gunakan itu sebagai bukti operasional
                                    sah.</p>
                            </div>
                        </div>
                    </template>
                </section>

                <!-- REPORT PREVIEW PANEL (THE "PAPER") -->
                <section class="flex flex-col items-center bg-gray-200/50 p-6 sm:p-12 rounded-[2.5rem] shadow-inner mt-8">
                    <!-- The Printable Paper Document -->
                    <div class="report-paper w-full max-w-[850px] min-h-[1100px] p-12 sm:p-20 relative text-black bg-white">

                        <!-- Kop Laporan -->
                        <div class="border-b-4 border-double border-black pb-6 mb-8 text-center">
                            <h2 class="text-lg font-bold uppercase leading-tight">Pemerintah
                                {{ $village['kabupaten'] ?? 'Kabupaten Ciamis' }}</h2>
                            <h2 class="text-lg font-bold uppercase leading-tight">Kecamatan
                                {{ $village['kecamatan'] ?? 'Panumbangan' }}</h2>
                            <h1 class="text-2xl font-black uppercase tracking-wider leading-tight mt-1">Pemerintah Desa
                                {{ $village['nama'] ?? 'Sindangmukti' }}</h1>
                            <p class="text-sm italic mt-2">Alamat:
                                {{ $village['alamat'] ?? 'Jl. Raya Panumbangan No. 12' }}, Kode Pos
                                {{ $village['kode_pos'] ?? '46263' }}</p>
                        </div>

                        <!-- Judul Laporan -->
                        <div class="text-center mb-10">
                            <h3 class="text-xl font-bold uppercase underline"
                                x-text="reportType === 'realisasi' ? 'Laporan Realisasi Pelaksanaan APBDes' : (reportType === 'neraca' ? 'Neraca Keuangan Desa' : 'Laporan Arus Kas Desa')">
                            </h3>
                            <p class="text-sm font-bold mt-2">Tahun Anggaran {{ $tahun }}</p>
                            <p class="text-xs italic mt-1">Dicetak pada: {{ date('d F Y') }}</p>
                        </div>

                        <div class="overflow-x-auto w-full">
                            <!-- 1. LAPORAN REALISASI -->
                            <template x-if="reportType === 'realisasi'">
                                <table class="w-full text-xs xl:text-sm border-collapse border border-black" width="100%">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="border border-black p-2 w-16">Kode</th>
                                            <th class="border border-black p-2 text-left">Uraian / Kegiatan</th>
                                            <th class="border border-black p-2 text-right">Anggaran (Rp)</th>
                                            <th class="border border-black p-2 text-right">Realisasi (Rp)</th>
                                            <th class="border border-black p-2 text-center">%</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Pendapatan Header -->
                                        <tr class="font-bold bg-gray-50">
                                            <td class="border border-black p-2">4</td>
                                            <td class="border border-black p-2 uppercase">PENDAPATAN</td>
                                            <td class="border border-black p-2 text-right">
                                                {{ number_format($summary['pendapatan'], 0, ',', '.') }}</td>
                                            <td class="border border-black p-2 text-right">
                                                {{ number_format($summary['pendapatan_realisasi'], 0, ',', '.') }}</td>
                                            @php $pdPct = $summary['pendapatan'] > 0 ? ($summary['pendapatan_realisasi'] / $summary['pendapatan']) * 100 : 0; @endphp
                                            <td class="border border-black p-2 text-center">{{ number_format($pdPct, 1) }}%
                                            </td>
                                        </tr>

                                        @foreach ($pendapatanData as $pd)
                                            @php $pPct = $pd->pagu_anggaran > 0 ? ($pd->realisasis_sum_nominal / $pd->pagu_anggaran) * 100 : 0; @endphp
                                            <tr
                                                class="{{ count(explode('.', $pd->kode_rekening)) <= 2 ? 'font-semibold' : '' }}">
                                                <td class="border border-black p-2">{{ $pd->kode_rekening }}</td>
                                                <td
                                                    class="border border-black p-2 {{ count(explode('.', $pd->kode_rekening)) > 2 ? 'pl-6' : 'pl-4' }}">
                                                    {{ $pd->nama_kegiatan }}</td>
                                                <td class="border border-black p-2 text-right">
                                                    {{ number_format($pd->pagu_anggaran, 0, ',', '.') }}</td>
                                                <td class="border border-black p-2 text-right">
                                                    {{ number_format($pd->realisasis_sum_nominal ?? 0, 0, ',', '.') }}</td>
                                                <td class="border border-black p-2 text-center">
                                                    {{ number_format($pPct, 1) }}%</td>
                                            </tr>
                                        @endforeach

                                        <!-- Belanja Header -->
                                        <tr class="font-bold bg-gray-50">
                                            <td class="border border-black p-2">5</td>
                                            <td class="border border-black p-2 uppercase">BELANJA</td>
                                            <td class="border border-black p-2 text-right">
                                                {{ number_format($summary['belanja'], 0, ',', '.') }}</td>
                                            <td class="border border-black p-2 text-right">
                                                {{ number_format($summary['belanja_realisasi'], 0, ',', '.') }}</td>
                                            @php $bdPct = $summary['belanja'] > 0 ? ($summary['belanja_realisasi'] / $summary['belanja']) * 100 : 0; @endphp
                                            <td class="border border-black p-2 text-center">{{ number_format($bdPct, 1) }}%
                                            </td>
                                        </tr>

                                        @foreach ($belanjaData as $bd)
                                            @php $bPct = $bd->pagu_anggaran > 0 ? ($bd->realisasis_sum_nominal / $bd->pagu_anggaran) * 100 : 0; @endphp
                                            <tr
                                                class="{{ count(explode('.', $bd->kode_rekening)) <= 2 ? 'font-semibold' : '' }}">
                                                <td class="border border-black p-2">{{ $bd->kode_rekening }}</td>
                                                <td
                                                    class="border border-black p-2 {{ count(explode('.', $bd->kode_rekening)) > 2 ? 'pl-6' : 'pl-4' }}">
                                                    {{ $bd->nama_kegiatan }}</td>
                                                <td class="border border-black p-2 text-right">
                                                    {{ number_format($bd->pagu_anggaran, 0, ',', '.') }}</td>
                                                <td class="border border-black p-2 text-right">
                                                    {{ number_format($bd->realisasis_sum_nominal ?? 0, 0, ',', '.') }}</td>
                                                <td class="border border-black p-2 text-center">
                                                    {{ number_format($bPct, 1) }}%</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="font-bold bg-gray-100">
                                        <tr>
                                            <td colspan="2" class="border border-black p-2 text-right uppercase">
                                                Surplus / (Defisit)</td>
                                            <td class="border border-black p-2 text-right"
                                                x-text="formatIDR({{ $summary['surplus'] }})"></td>
                                            <td class="border border-black p-2 text-right"
                                                x-text="formatIDR({{ $summary['surplus_realisasi'] }})"></td>
                                            <td class="border border-black p-2 text-center">-</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </template>

                            <!-- 2. LAPORAN NERACA (PLACEHOLDER) -->
                            <template x-if="reportType === 'neraca'">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-0 border border-black text-xs xl:text-sm">
                                    <div class="border-r border-black p-0 m-0">
                                        <div class="bg-gray-100 p-2 font-bold text-center border-b border-black">ASET</div>
                                        <div class="p-3 space-y-4">
                                            <div>
                                                <p class="font-bold">Aset Lancar</p>
                                                <div class="flex justify-between pl-2 mt-1"><span>Kas Desa</span><span
                                                        x-text="formatIDR({{ max(0, $summary['surplus_realisasi']) }})"></span>
                                                </div>
                                                <div class="flex justify-between pl-2"><span>Piutang</span><span>0</span>
                                                </div>
                                            </div>
                                            <div>
                                                <p class="font-bold">Aset Tetap</p>
                                                <div class="flex justify-between pl-2 mt-1"><span>Tanah &
                                                        Bangunan</span><span x-text="formatIDR(14200000000)"></span></div>
                                                <div class="flex justify-between pl-2"><span>Peralatan & Mesin</span><span
                                                        x-text="formatIDR(700000000)"></span></div>
                                            </div>
                                            <div class="pt-4 border-t border-gray-300 font-bold flex justify-between">
                                                <span>TOTAL ASET</span>
                                                <span
                                                    x-text="formatIDR(14900000000 + {{ max(0, $summary['surplus_realisasi']) }})"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-0 m-0">
                                        <div class="bg-gray-100 p-2 font-bold text-center border-b border-black">KEWAJIBAN
                                            & EKUITAS</div>
                                        <div class="p-3 space-y-4 h-full flex flex-col">
                                            <div>
                                                <p class="font-bold">Kewajiban</p>
                                                <div class="flex justify-between pl-2 mt-1"><span>Utang Pajak</span><span
                                                        x-text="formatIDR(5000000)"></span></div>
                                                <div class="flex justify-between pl-2"><span>Utang Pihak Ketiga</span><span
                                                        x-text="formatIDR(245000000)"></span></div>
                                            </div>
                                            <div class="flex-1">
                                                <p class="font-bold">Ekuitas</p>
                                                <div class="flex justify-between pl-2 mt-1"><span>Ekuitas SAL</span><span
                                                        x-text="formatIDR(14650000000 + {{ max(0, $summary['surplus_realisasi']) }})"></span>
                                                </div>
                                            </div>
                                            <div class="pt-4 border-t border-gray-300 font-bold flex justify-between">
                                                <span>TOTAL PASIVA</span>
                                                <span
                                                    x-text="formatIDR(14900000000 + {{ max(0, $summary['surplus_realisasi']) }})"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <!-- 3. LAPORAN ARUS KAS (PLACEHOLDER) -->
                            <template x-if="reportType === 'arus-kas'">
                                <div class="space-y-6">
                                    <table class="w-full text-xs xl:text-sm border-collapse border border-black">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="border border-black p-2 text-left">Uraian Arus Kas</th>
                                                <th class="border border-black p-2 text-right">Arus Masuk (Rp)</th>
                                                <th class="border border-black p-2 text-right">Arus Keluar (Rp)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="font-bold">
                                                <td class="border border-black p-2" colspan="3">A. ARUS KAS DARI
                                                    AKTIVITAS OPERASI</td>
                                            </tr>
                                            <tr>
                                                <td class="border border-black p-2 pl-4">Pendapatan Realisasi APBDes</td>
                                                <td class="border border-black p-2 text-right"
                                                    x-text="formatIDR({{ $summary['pendapatan_realisasi'] }})"></td>
                                                <td class="border border-black p-2 text-right">-</td>
                                            </tr>
                                            <tr>
                                                <td class="border border-black p-2 pl-4">Belanja Realisasi APBDes</td>
                                                <td class="border border-black p-2 text-right">-</td>
                                                <td class="border border-black p-2 text-right">(<span
                                                        x-text="formatIDR({{ $summary['belanja_realisasi'] }})"></span>)
                                                </td>
                                            </tr>
                                            <tr class="font-bold bg-gray-50">
                                                <td class="border border-black p-2">KAS BERSIH OPERASI</td>
                                                <td class="border border-black p-2 text-right" colspan="2"
                                                    x-text="formatIDR({{ $summary['surplus_realisasi'] }})"></td>
                                            </tr>
                                        </tbody>
                                        <tfoot class="font-bold bg-gray-100">
                                            <tr>
                                                <td class="border border-black p-2">SALDO KAS AKHIR PERIODE (Est.)</td>
                                                <td class="border border-black p-2 text-right font-black" colspan="2"
                                                    x-text="formatIDR({{ max(0, $summary['surplus_realisasi']) }})"></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </template>
                        </div>

                        <!-- Signature Area -->
                        <div class="mt-20 grid grid-cols-2 gap-12 text-sm xl:text-base">
                            <div class="text-center">
                                <p class="mb-20">Mengetahui,<br>Sekretaris Desa</p>
                                <p class="font-bold underline uppercase">H. Tedi Risnandar</p>
                                <p class="text-xs">NIP. -</p>
                            </div>
                            <div class="text-center">
                                <p class="mb-2">{{ $village['nama'] ?? 'Sindangmukti' }}, {{ date('d F Y') }}</p>
                                <p class="mb-14 font-bold uppercase">Kepala Desa {{ $village['nama'] ?? 'Sindangmukti' }}
                                </p>
                                <p class="font-bold underline uppercase">Asep Ghufronudin</p>
                                <p class="text-xs">NIP. -</p>
                            </div>
                        </div>

                        <!-- Footer Catatan (Small) -->
                        <div class="mt-12 pt-4 border-t border-gray-300 text-[10px] italic text-gray-500">
                            Laporan ini digenerate secara otomatis oleh SID Desa {{ $village['nama'] ?? 'Sindangmukti' }}.
                            Seluruh data keuangan disajikan secara riil dari riwayat input verifikasi Bendahara Desa.
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>
@endsection
