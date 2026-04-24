{{--
    Halaman: Riwayat Permohonan Surat
    Route: warga.surat.riwayat
    Guard: auth:warga
    Sumber Desain: Riwayat-Permohonan.html (Google Stitch)
--}}

@extends('layouts.warga')

@section('title', 'Riwayat Permohonan - Layanan Mandiri Desa Sindangmukti')
@section('meta_description', 'Lacak status dan unduh dokumen permohonan surat Anda.')

@php $pageTitle = 'Riwayat Permohonan'; @endphp

@section('content')

    {{-- Header Halaman --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight mb-1">Riwayat Permohonan</h1>
            <p class="text-sm text-gray-500">Lacak status dan unduh dokumen permohonan surat Anda di sini.</p>
        </div>
        <a href="{{ route('warga.surat.ajukan') }}"
           class="inline-flex items-center justify-center gap-2 bg-green-700 hover:bg-green-800 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-md transition-all sm:w-auto w-full">
            <i class="fa-solid fa-plus"></i> Ajukan Surat Baru
        </a>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 rounded-2xl px-5 py-4 mb-6 flex items-start gap-3">
            <i class="fa-solid fa-circle-check text-green-500 text-lg mt-0.5 shrink-0"></i>
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Pengajuan</p>
            <p class="text-2xl font-extrabold text-gray-900 mt-1">{{ $suratPermohonan->total() }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
            <p class="text-[10px] font-bold text-amber-500 uppercase tracking-wider">Sedang Proses</p>
            <p class="text-2xl font-extrabold text-amber-600 mt-1">{{ $statsCount['aktif'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
            <p class="text-[10px] font-bold text-green-500 uppercase tracking-wider">Selesai</p>
            <p class="text-2xl font-extrabold text-green-600 mt-1">{{ $statsCount['selesai'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
            <p class="text-[10px] font-bold text-blue-500 uppercase tracking-wider">Estimasi SLA</p>
            <p class="text-2xl font-extrabold text-blue-600 mt-1">24 Jam</p>
        </div>
    </div>

    {{-- Filter & Search --}}
    <section class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
        <form method="GET" action="{{ route('warga.surat.riwayat') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Search --}}
            <div class="lg:col-span-2 relative">
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Pencarian</label>
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-8 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No. Surat atau Jenis..."
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none transition-all">
            </div>

            {{-- Status Filter --}}
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Status</label>
                <select name="status" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none transition-all appearance-none cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="pengajuan" {{ request('status') === 'pengajuan' ? 'selected' : '' }}>Pengajuan Baru</option>
                    <option value="verifikasi" {{ request('status') === 'verifikasi' ? 'selected' : '' }}>Verifikasi</option>
                    <option value="menunggu_tte" {{ request('status') === 'menunggu_tte' ? 'selected' : '' }}>Menunggu TTE</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            {{-- Submit & Reset --}}
            <div class="flex items-end gap-2">
                <button type="submit"
                        class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-xl font-bold text-sm transition-colors shadow-sm">
                    <i class="fa-solid fa-filter mr-1"></i> Filter
                </button>
                <a href="{{ route('warga.surat.riwayat') }}"
                   class="bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 px-4 py-2.5 rounded-xl font-bold text-sm transition-colors shadow-sm">
                    <i class="fa-solid fa-arrow-rotate-right"></i>
                </a>
            </div>
        </form>
    </section>

    {{-- Data Table/List --}}
    <section class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">

        {{-- Desktop View (Table) --}}
        <div class="hidden md:block overflow-x-auto w-full">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead class="bg-gray-50/80 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">No. Permohonan</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Jenis Surat</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal Pengajuan</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($suratPermohonan as $surat)
                        @php $badge = $surat->statusBadge(); @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <span class="font-mono font-bold text-green-700 text-sm">{{ $surat->nomor_tiket }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-semibold text-gray-800 text-sm">{{ $surat->jenisLabel() }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-600">{{ $surat->tanggal_pengajuan?->format('d M Y') ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase tracking-wide border {{ $badge['bg'] }} {{ $badge['text'] }} {{ $badge['border'] }}">
                                    <i class="fa-solid {{ $badge['icon'] }} mr-1"></i> {{ $badge['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('warga.surat.detail', $surat) }}"
                                   class="bg-white border border-gray-200 text-gray-600 hover:text-green-600 hover:border-green-300 hover:bg-green-50 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-300 mx-auto mb-4 border border-gray-100">
                                    <i class="fa-solid fa-folder-open text-2xl"></i>
                                </div>
                                <p class="font-bold text-gray-800 text-lg">Belum ada permohonan</p>
                                <p class="text-sm text-gray-500 mt-1">Mulai ajukan surat pertama Anda.</p>
                                <a href="{{ route('warga.surat.ajukan') }}" class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-green-600 hover:underline">
                                    <i class="fa-solid fa-plus"></i> Ajukan Sekarang
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile View (Card List) --}}
        <div class="md:hidden divide-y divide-gray-100">
            @forelse($suratPermohonan as $surat)
                @php $badge = $surat->statusBadge(); @endphp
                <div class="p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex justify-between items-start mb-2">
                        <span class="font-mono font-bold text-green-700 text-xs bg-green-50 px-2 py-0.5 rounded">{{ $surat->nomor_tiket }}</span>
                        <span class="text-[9px] font-bold px-2 py-1 rounded-lg uppercase tracking-wide border flex items-center gap-1 {{ $badge['bg'] }} {{ $badge['text'] }} {{ $badge['border'] }}">
                            <i class="fa-solid {{ $badge['icon'] }}"></i> {{ $badge['label'] }}
                        </span>
                    </div>
                    <h3 class="font-bold text-gray-800 text-sm mb-1 leading-tight">{{ $surat->jenisLabel() }}</h3>
                    <p class="text-xs text-gray-500 mb-4">
                        <i class="fa-regular fa-calendar mr-1"></i> {{ $surat->tanggal_pengajuan?->format('d M Y') ?? '-' }}
                    </p>
                    <a href="{{ route('warga.surat.detail', $surat) }}"
                       class="w-full block text-center bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 py-2 rounded-xl text-xs font-bold transition-colors shadow-sm">
                        Lihat Detail Status
                    </a>
                </div>
            @empty
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-300 mx-auto mb-4 border border-gray-100">
                        <i class="fa-solid fa-folder-open text-2xl"></i>
                    </div>
                    <p class="font-bold text-gray-800 text-lg">Belum ada permohonan</p>
                    <p class="text-sm text-gray-500 mt-1 mb-4">Mulai ajukan surat pertama Anda.</p>
                    <a href="{{ route('warga.surat.ajukan') }}" class="text-sm font-semibold text-green-600 hover:underline">Ajukan Sekarang</a>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($suratPermohonan->hasPages())
            <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $suratPermohonan->links() }}
            </div>
        @endif
    </section>

@endsection
