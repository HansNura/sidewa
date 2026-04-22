@extends('layouts.backoffice')
@use('App\Models\User')

@section('title', 'Dashboard Operasional - Panel Administrasi')

@push('styles')
<style>
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -1.4rem;
        top: 0.25rem;
        width: 0.75rem;
        height: 0.75rem;
        border-radius: 50%;
        background-color: white;
        border: 2px solid #cbd5e1;
        z-index: 10;
    }
    .timeline-item.active::before {
        border-color: #16a34a;
        background-color: #16a34a;
    }
</style>
@endpush

@section('content')

    {{-- HEADER --}}
    <section class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Dasbor Operasional</h1>
            <p class="text-sm text-gray-500 mt-1">Pantau aktivitas sistem, kelola antrean layanan, dan jalankan tugas real-time.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <span class="text-xs font-semibold px-2.5 py-1 bg-amber-100 text-amber-700 rounded-md uppercase tracking-wider">
                Akses {{ $user->jabatan ?? User::ROLES[$user->role] ?? 'Operator' }}
            </span>
            <a href="{{ route('admin.layanan-surat.create') }}"
                class="bg-green-700 hover:bg-green-800 text-white shadow-md hover:shadow-lg rounded-xl px-4 py-2.5 text-sm font-bold transition-all flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> <span>Buat Surat</span>
            </a>
        </div>
    </section>

    {{-- QUICK ACTION PANEL --}}
    <section class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        @php
            $qColors = [
                'green'  => ['bg' => 'bg-green-50',  'text' => 'text-green-600',  'hover' => 'hover:bg-green-100'],
                'blue'   => ['bg' => 'bg-blue-50',   'text' => 'text-blue-600',   'hover' => 'hover:bg-blue-100'],
                'purple' => ['bg' => 'bg-purple-50',  'text' => 'text-purple-600',  'hover' => 'hover:bg-purple-100'],
                'amber'  => ['bg' => 'bg-amber-50',  'text' => 'text-amber-600',  'hover' => 'hover:bg-amber-100'],
            ];
        @endphp
        @foreach($quickActions as $qa)
            @php $qc = $qColors[$qa['color']] ?? $qColors['green']; @endphp
            <a href="{{ $qa['route'] }}"
                class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 hover:-translate-y-1 hover:shadow-md transition-all duration-300 flex flex-col items-center justify-center text-center group">
                <div class="w-12 h-12 {{ $qc['bg'] }} {{ $qc['text'] }} rounded-full flex items-center justify-center mb-3 group-{{ $qc['hover'] }} transition-colors">
                    <i class="{{ $qa['icon'] }} text-xl"></i>
                </div>
                <h4 class="font-semibold text-gray-800 text-sm">{{ $qa['title'] }}</h4>
                <p class="text-[10px] text-gray-500 mt-1">{{ $qa['description'] }}</p>
            </a>
        @endforeach
    </section>

    {{-- QUICK STATS (Hari Ini) --}}
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Surat Masuk --}}
        <article class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-inbox text-2xl"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Surat Masuk</p>
                <div class="flex items-end gap-2">
                    <h3 class="text-2xl font-extrabold text-gray-900 leading-none">{{ $suratMasukHariIni }}</h3>
                    <span class="text-xs text-gray-500 mb-0.5">Hari ini</span>
                </div>
            </div>
        </article>

        {{-- Surat Diproses --}}
        <article class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-file-signature text-2xl"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Diproses</p>
                <div class="flex items-end gap-2">
                    <h3 class="text-2xl font-extrabold text-gray-900 leading-none">{{ $suratDiproses }}</h3>
                    <span class="text-xs text-gray-500 mb-0.5">Antrean aktif</span>
                </div>
            </div>
        </article>

        {{-- Tamu --}}
        <article class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-purple-50 text-purple-500 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-id-card-clip text-2xl"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Tamu Hadir</p>
                <div class="flex items-end gap-2">
                    <h3 class="text-2xl font-extrabold text-gray-900 leading-none">{{ $tamuHariIni }}</h3>
                    <span class="text-xs text-gray-500 mb-0.5">Orang</span>
                </div>
            </div>
        </article>

        {{-- Presensi --}}
        <article class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-fingerprint text-2xl"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Presensi</p>
                <div class="flex items-end gap-2">
                    <h3 class="text-2xl font-extrabold text-gray-900 leading-none">{{ $hadirHariIni }}/{{ $totalPegawai }}</h3>
                    <span class="text-xs text-gray-500 mb-0.5">Pegawai</span>
                </div>
            </div>
        </article>
    </section>

    {{-- MAIN GRID --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- KIRI: Status Layanan + Antrean --}}
        <div class="xl:col-span-2 space-y-6">

            {{-- Status Layanan Keseluruhan --}}
            <section class="bg-white border border-gray-100 shadow-sm rounded-2xl p-5">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="font-bold text-gray-800">Status Layanan Keseluruhan</h3>
                </div>

                <div class="grid grid-cols-3 gap-4 mb-4">
                    <div class="bg-amber-50 rounded-xl p-4 border border-amber-100 text-center">
                        <h4 class="text-xl font-extrabold text-amber-600 mb-1">{{ $statusPending }}</h4>
                        <span class="text-xs font-semibold text-amber-700 uppercase">Pending</span>
                    </div>
                    <div class="bg-blue-50 rounded-xl p-4 border border-blue-100 text-center">
                        <h4 class="text-xl font-extrabold text-blue-600 mb-1">{{ $statusDiproses }}</h4>
                        <span class="text-xs font-semibold text-blue-700 uppercase">Diproses</span>
                    </div>
                    <div class="bg-green-50 rounded-xl p-4 border border-green-100 text-center">
                        <h4 class="text-xl font-extrabold text-green-600 mb-1">{{ $statusSelesai }}</h4>
                        <span class="text-xs font-semibold text-green-700 uppercase">Selesai</span>
                    </div>
                </div>

                @if($overdueCount > 0)
                <div class="bg-red-50 border border-red-200 rounded-xl p-3 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-bold text-red-800">Perhatian: Bottleneck Terdeteksi</p>
                        <p class="text-xs text-red-600 mt-0.5">Terdapat <strong>{{ $overdueCount }} surat</strong> yang berstatus pending melebihi {{ \App\Models\SuratPermohonan::SLA_HOURS }} jam. Segera tindak lanjuti.</p>
                    </div>
                    <a href="{{ route('admin.layanan-surat.index') }}" class="text-xs font-semibold bg-white text-red-600 border border-red-200 px-3 py-1.5 rounded-lg hover:bg-red-50 transition-colors shrink-0">Lihat</a>
                </div>
                @endif
            </section>

            {{-- Daftar Antrean Pekerjaan --}}
            <section class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden flex flex-col">
                <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <div>
                        <h3 class="font-bold text-gray-800">Daftar Antrean Pekerjaan</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Prioritaskan tugas yang memiliki urgensi tinggi.</p>
                    </div>
                    <a href="{{ route('admin.layanan-surat.index') }}" class="text-green-600 hover:text-green-800 text-sm font-medium">Lihat Semua</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/80 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                                <th class="p-4 font-semibold">Jenis Layanan / Pemohon</th>
                                <th class="p-4 font-semibold">Waktu Masuk</th>
                                <th class="p-4 font-semibold">Prioritas</th>
                                <th class="p-4 font-semibold">PIC / Operator</th>
                                <th class="p-4 font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-gray-100">
                            @forelse($antreanSurat as $surat)
                                @php
                                    $sla = $surat->slaInfo();
                                    $prBadge = $surat->prioritasBadge();
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="p-4">
                                        <p class="font-semibold text-gray-800">{{ $surat->jenisShort() }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $surat->penduduk?->nama ?? '-' }} (NIK: {{ Str::limit($surat->penduduk?->nik ?? '-', 8) }}...)</p>
                                    </td>
                                    <td class="p-4">
                                        <span class="text-gray-600">{{ $surat->tanggal_pengajuan?->translatedFormat('d M, H:i') }}</span>
                                        @if($sla['overdue'])
                                            <br><span class="text-[10px] text-red-500 font-medium">{{ $sla['label'] }}</span>
                                        @else
                                            <br><span class="text-[10px] text-gray-400">{{ $sla['label'] }}</span>
                                        @endif
                                    </td>
                                    <td class="p-4">
                                        <span class="{{ $prBadge['bg'] }} {{ $prBadge['text'] }} text-xs font-bold px-2.5 py-1 rounded-md border {{ $prBadge['border'] }}">{{ $prBadge['label'] }}</span>
                                    </td>
                                    <td class="p-4">
                                        @if($surat->operator)
                                            <div class="flex items-center gap-2">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($surat->operator->name) }}&background=fbc02d&color=000&size=24"
                                                    class="w-6 h-6 rounded-full" alt="PIC">
                                                <span class="text-gray-700 font-medium text-xs">{{ $surat->operator->name }}</span>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Belum di-assign</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-right">
                                        <a href="{{ route('admin.layanan-surat.index') }}"
                                            class="bg-green-50 text-green-700 hover:bg-green-600 hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-colors inline-block">
                                            Proses
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-gray-400 text-sm">
                                        <i class="fa-solid fa-check-circle text-green-300 text-2xl mb-2"></i>
                                        <p>Tidak ada antrean surat yang aktif. 🎉</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        {{-- KANAN: Alert & Activity Feed --}}
        <div class="xl:col-span-1 space-y-6 flex flex-col">

            {{-- Alert Operasional --}}
            <section class="bg-white border border-gray-100 shadow-sm rounded-2xl p-5">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation text-amber-500"></i> Alert Operasional
                </h3>
                <div class="space-y-3">
                    @forelse($alerts as $alert)
                        <div class="p-3 {{ $alert['type'] === 'error' ? 'bg-red-50 border-red-100' : 'bg-amber-50 border-amber-100' }} border rounded-xl">
                            <div class="flex justify-between items-start mb-1">
                                <span class="text-xs font-bold {{ $alert['type'] === 'error' ? 'text-red-800' : 'text-amber-800' }}">{{ $alert['title'] }}</span>
                                <span class="text-[10px] {{ $alert['type'] === 'error' ? 'text-red-500' : 'text-amber-500' }}">{{ $alert['time'] }}</span>
                            </div>
                            <p class="text-xs {{ $alert['type'] === 'error' ? 'text-red-700' : 'text-amber-700' }}">{{ $alert['message'] }}</p>
                        </div>
                    @empty
                        <div class="text-center py-4 text-sm text-gray-400">
                            <i class="fa-solid fa-shield-check text-green-300 text-xl mb-2"></i>
                            <p>Tidak ada alert aktif saat ini.</p>
                        </div>
                    @endforelse
                </div>
            </section>

            {{-- Activity Feed (Timeline) --}}
            <section class="bg-white border border-gray-100 shadow-sm rounded-2xl p-5 flex-1 overflow-hidden flex flex-col">
                <div class="flex justify-between items-center mb-5 shrink-0">
                    <h3 class="font-bold text-gray-800">Log Aktivitas Terbaru</h3>
                </div>

                <div class="relative border-l-2 border-gray-200 ml-3 pl-5 py-2 space-y-6 overflow-y-auto custom-scrollbar flex-1 pr-2">
                    @forelse($activityLogs as $log)
                        <div class="timeline-item {{ $log['isRecent'] ? 'active' : '' }} relative">
                            <p class="text-xs text-gray-400 font-medium mb-0.5">{{ $log['timeLabel'] }}</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $log['title'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                <span class="font-medium text-gray-700">{{ $log['userName'] }}</span> — {{ $log['description'] }}
                            </p>
                        </div>
                    @empty
                        <div class="text-center py-6 text-sm text-gray-400">
                            <i class="fa-solid fa-clock-rotate-left text-gray-300 text-xl mb-2"></i>
                            <p>Belum ada aktivitas tercatat.</p>
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>

    {{-- BOTTOM: PRESENSI & BUKU TAMU --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Presensi Pegawai Snapshot --}}
        <section class="bg-white border border-gray-100 shadow-sm rounded-2xl flex flex-col overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <div>
                    <h3 class="font-bold text-gray-800">Status Kehadiran Pegawai</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Rekap presensi hari ini.</p>
                </div>
                <a href="{{ route('admin.presensi.monitoring') }}" class="text-xs font-semibold text-green-600 hover:underline">Detail Absensi</a>
            </div>
            <div class="p-5 overflow-x-auto">
                <table class="w-full text-left">
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @foreach($presensiHariIni as $presensi)
                            @php
                                $statusMap = [
                                    'hadir'     => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Hadir'],
                                    'terlambat' => ['bg' => 'bg-red-100',   'text' => 'text-red-700',   'label' => 'Terlambat'],
                                    'izin'      => ['bg' => 'bg-blue-100',  'text' => 'text-blue-700',  'label' => 'Izin'],
                                    'sakit'     => ['bg' => 'bg-blue-100',  'text' => 'text-blue-700',  'label' => 'Sakit'],
                                    'dinas'     => ['bg' => 'bg-purple-100','text' => 'text-purple-700','label' => 'Dinas Luar'],
                                ];
                                $sb = $statusMap[$presensi->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => ucfirst($presensi->status)];
                            @endphp
                            <tr class="py-3 flex items-center justify-between">
                                <td class="flex items-center gap-3 py-2">
                                    <div class="w-8 h-8 rounded-full bg-gray-200 overflow-hidden shrink-0">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($presensi->pegawai?->name ?? 'N') }}&background=e2e8f0&color=475569&size=32"
                                            alt="Pegawai" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $presensi->pegawai?->name ?? '-' }}</p>
                                        <p class="text-[10px] text-gray-500">{{ $presensi->pegawai?->jabatan ?? User::ROLES[$presensi->pegawai?->role ?? ''] ?? '-' }}</p>
                                    </div>
                                </td>
                                <td class="py-2 text-right">
                                    <span class="text-xs font-medium {{ $presensi->status === 'terlambat' ? 'text-red-600' : 'text-gray-800' }} block">{{ $presensi->formatMasuk() }}</span>
                                    <span class="{{ $sb['bg'] }} {{ $sb['text'] }} text-[10px] font-bold px-2 py-0.5 rounded uppercase mt-1 inline-block">{{ $sb['label'] }}</span>
                                </td>
                            </tr>
                        @endforeach

                        {{-- Pegawai yang belum hadir --}}
                        @foreach($pegawaiAlpha as $alpha)
                            <tr class="py-3 flex items-center justify-between opacity-60">
                                <td class="flex items-center gap-3 py-2">
                                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center shrink-0 text-gray-400">
                                        <i class="fa-solid fa-user text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $alpha->name }}</p>
                                        <p class="text-[10px] text-gray-500">{{ $alpha->jabatan ?? User::ROLES[$alpha->role] ?? '-' }}</p>
                                    </div>
                                </td>
                                <td class="py-2 text-right">
                                    <span class="text-xs font-medium text-gray-400 block">-</span>
                                    <span class="bg-gray-100 text-gray-600 text-[10px] font-bold px-2 py-0.5 rounded uppercase mt-1 inline-block">Belum Hadir</span>
                                </td>
                            </tr>
                        @endforeach

                        @if($presensiHariIni->isEmpty() && $pegawaiAlpha->isEmpty())
                            <tr>
                                <td colspan="2" class="text-center py-6 text-sm text-gray-400">
                                    Tidak ada data presensi hari ini.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </section>

        {{-- Buku Tamu Hari Ini --}}
        <section class="bg-white border border-gray-100 shadow-sm rounded-2xl flex flex-col overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <div>
                    <h3 class="font-bold text-gray-800">Buku Tamu (Hari Ini)</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Daftar pengunjung kantor desa.</p>
                </div>
                <a href="{{ route('admin.buku-tamu.index') }}"
                    class="w-8 h-8 rounded-full bg-green-50 text-green-600 flex items-center justify-center hover:bg-green-100 transition-colors"
                    title="Lihat Semua">
                    <i class="fa-solid fa-arrow-right text-sm"></i>
                </a>
            </div>
            <div class="p-5 flex flex-col gap-4 overflow-y-auto custom-scrollbar" style="max-height: 300px;">
                @forelse($tamuList as $tamu)
                    <div class="flex gap-4 items-start p-3 rounded-xl hover:bg-gray-50 border border-transparent hover:border-gray-100 transition-colors">
                        @if($tamu->foto_ktp_url)
                            <div class="w-12 h-12 rounded-lg bg-gray-200 overflow-hidden shrink-0 border border-gray-300">
                                <img src="{{ asset('storage/' . $tamu->foto_ktp_url) }}" alt="Foto" class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="w-12 h-12 rounded-lg bg-gray-100 text-gray-400 flex items-center justify-center shrink-0 border border-gray-200">
                                <i class="fa-solid fa-address-card text-xl"></i>
                            </div>
                        @endif
                        <div class="flex-1">
                            <div class="flex justify-between">
                                <h4 class="font-semibold text-sm text-gray-900">{{ $tamu->nama_tamu }}</h4>
                                <span class="text-[10px] text-gray-500">{{ $tamu->waktu_masuk?->format('H:i') }}</span>
                            </div>
                            @if($tamu->instansi)
                                <p class="text-xs text-gray-600 mt-1"><i class="fa-solid fa-building text-gray-400 mr-1"></i> {{ $tamu->instansi }}</p>
                            @endif
                            @if($tamu->keperluan)
                                <p class="text-[10px] font-medium text-green-700 mt-1.5 bg-green-50 px-2 py-0.5 rounded inline-block">
                                    Tujuan: {{ Str::limit($tamu->keperluan, 40) }}
                                </p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="fa-solid fa-user-clock text-gray-300 text-2xl mb-2"></i>
                        <p class="text-xs text-gray-400">Belum ada pengunjung hari ini.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>

@endsection
