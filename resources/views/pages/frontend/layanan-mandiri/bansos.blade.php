{{--
    Halaman: Bantuan Sosial (Cek Status Kelayakan)
    Route: warga.bansos
    Guard: auth:warga
    Sumber Desain: Bantuan-Sosial.html (Google Stitch)
--}}

@extends('layouts.warga')

@section('title', 'Bantuan Sosial - Layanan Mandiri Desa Sindangmukti')
@section('meta_description', 'Cek status kelayakan dan informasi penerimaan bantuan sosial Anda.')

@php $pageTitle = 'Bantuan Sosial'; @endphp

@section('content')

    <div x-data="{
        activeTab: 'status',
        detailOpen: false,
        detailData: null,

        showDetail(item) {
            this.detailData = item;
            this.detailOpen = true;
        }
    }">

        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight mb-1">Bantuan Sosial</h1>
            <p class="text-sm text-gray-500">Cek status penerimaan dan histori penyaluran bantuan sosial kepada keluarga Anda.</p>
        </div>

        {{-- Tabs --}}
        <div class="flex items-center gap-1 p-1 bg-gray-100 rounded-xl mb-6 w-fit">
            <button @click="activeTab = 'status'"
                    class="px-4 py-2 rounded-lg text-sm font-bold transition-all"
                    :class="activeTab === 'status' ? 'bg-white text-green-700 shadow-sm' : 'text-gray-500 hover:text-gray-700'">
                <i class="fa-solid fa-clipboard-check mr-1"></i> Status Saya
            </button>
            <button @click="activeTab = 'program'"
                    class="px-4 py-2 rounded-lg text-sm font-bold transition-all"
                    :class="activeTab === 'program' ? 'bg-white text-green-700 shadow-sm' : 'text-gray-500 hover:text-gray-700'">
                <i class="fa-solid fa-list-ul mr-1"></i> Daftar Program
            </button>
        </div>

        {{-- ═══ TAB 1: STATUS SAYA ═══ --}}
        <div x-show="activeTab === 'status'" x-transition.opacity.duration.200ms>

            {{-- Status Card --}}
            @if($penerimaBansos->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                    <div class="bg-green-50 border border-green-100 rounded-2xl p-5 flex items-center gap-4">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-xl text-green-600 shrink-0">
                            <i class="fa-solid fa-check-double"></i>
                        </div>
                        <div>
                            <p class="text-sm font-extrabold text-green-800">Anda Terdaftar</p>
                            <p class="text-xs text-green-600 mt-0.5">Tercatat sebagai penerima {{ $penerimaBansos->count() }} program bantuan</p>
                        </div>
                    </div>
                    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-xl text-blue-600 shrink-0">
                            <i class="fa-solid fa-hand-holding-heart"></i>
                        </div>
                        <div>
                            <p class="text-sm font-extrabold text-gray-800">{{ $penerimaBansos->where('status_distribusi', 'diterima')->count() }} Tersalurkan</p>
                            <p class="text-xs text-gray-500 mt-0.5">Bantuan yang sudah diterima</p>
                        </div>
                    </div>
                </div>

                {{-- History Table --}}
                <section class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <header class="px-5 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                        <h2 class="font-bold text-gray-800 text-sm">Riwayat Penerimaan Bantuan</h2>
                        <span class="text-xs text-gray-400">Total: {{ $penerimaBansos->count() }} data</span>
                    </header>

                    {{-- Desktop Table --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-left whitespace-nowrap">
                            <thead class="bg-gray-50/80 border-b border-gray-100">
                                <tr>
                                    <th class="px-5 py-3 text-xs font-bold text-gray-500 uppercase">Program</th>
                                    <th class="px-5 py-3 text-xs font-bold text-gray-500 uppercase">Tahap</th>
                                    <th class="px-5 py-3 text-xs font-bold text-gray-500 uppercase">Status</th>
                                    <th class="px-5 py-3 text-xs font-bold text-gray-500 uppercase">Tgl Distribusi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($penerimaBansos as $item)
                                    @php $sBadge = $item->statusBadge(); @endphp
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-5 py-4">
                                            <span class="font-semibold text-gray-800 text-sm">{{ $item->program?->nama ?? '-' }}</span>
                                        </td>
                                        <td class="px-5 py-4 text-sm text-gray-600">{{ $item->tahap ?? '-' }}</td>
                                        <td class="px-5 py-4">
                                            <span class="text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase tracking-wide border {{ $sBadge['bg'] }} {{ $sBadge['text'] }} {{ $sBadge['border'] }}">
                                                <i class="fa-solid {{ $sBadge['icon'] }} mr-0.5"></i> {{ $sBadge['label'] }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4 text-sm text-gray-600">{{ $item->tanggal_distribusi?->format('d M Y') ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile Cards --}}
                    <div class="md:hidden divide-y divide-gray-100">
                        @foreach($penerimaBansos as $item)
                            @php $sBadge = $item->statusBadge(); @endphp
                            <div class="p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="font-bold text-gray-800 text-sm">{{ $item->program?->nama ?? '-' }}</h3>
                                    <span class="text-[9px] font-bold px-2 py-1 rounded-lg uppercase border {{ $sBadge['bg'] }} {{ $sBadge['text'] }} {{ $sBadge['border'] }}">
                                        {{ $sBadge['label'] }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-4 text-xs text-gray-500">
                                    <span><i class="fa-solid fa-layer-group mr-1"></i> Tahap: {{ $item->tahap ?? '-' }}</span>
                                    <span><i class="fa-regular fa-calendar mr-1"></i> {{ $item->tanggal_distribusi?->format('d M Y') ?? '-' }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @else
                {{-- Empty State --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm py-16 text-center">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center text-gray-300 mx-auto mb-6 border border-gray-100">
                        <i class="fa-solid fa-hand-holding-heart text-3xl"></i>
                    </div>
                    <h2 class="text-xl font-extrabold text-gray-800 mb-2">Belum Ada Data</h2>
                    <p class="text-sm text-gray-500 max-w-sm mx-auto leading-relaxed">
                        Saat ini Anda belum terdaftar sebagai penerima program bantuan sosial di desa ini.
                    </p>
                    <p class="text-xs text-gray-400 mt-4">
                        <i class="fa-solid fa-circle-info mr-1"></i> Data ditetapkan oleh petugas desa berdasarkan verifikasi DTKS.
                    </p>
                </div>
            @endif
        </div>

        {{-- ═══ TAB 2: DAFTAR PROGRAM ═══ --}}
        <div x-show="activeTab === 'program'" x-transition.opacity.duration.200ms x-cloak>

            @if($semuaProgram->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($semuaProgram as $prog)
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md transition-all hover:-translate-y-0.5 group">
                            <div class="flex items-start gap-4 mb-4">
                                <div class="w-10 h-10 rounded-xl bg-green-100 text-green-600 flex items-center justify-center text-lg shrink-0 group-hover:bg-green-500 group-hover:text-white transition-colors">
                                    <i class="fa-solid fa-hand-holding-dollar"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 text-sm">{{ $prog->nama }}</h3>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $prog->sumber ?? 'Program Desa' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between text-xs pt-3 border-t border-gray-100">
                                <span class="text-gray-500 flex items-center gap-1">
                                    <i class="fa-solid fa-users text-gray-400"></i>
                                    {{ $prog->penerima_count ?? 0 }} Penerima
                                </span>
                                @if($prog->jenis_bantuan)
                                    <span class="bg-blue-50 text-blue-600 font-bold px-2 py-0.5 rounded text-[10px] uppercase">
                                        {{ $prog->jenis_bantuan }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm py-12 text-center">
                    <p class="text-gray-500 text-sm">Belum ada program bantuan yang tersedia.</p>
                </div>
            @endif
        </div>

        {{-- Info Panel --}}
        <div class="mt-6 bg-blue-50 border border-blue-100 rounded-2xl p-5">
            <h4 class="text-sm font-bold text-blue-800 mb-2 flex items-center gap-2">
                <i class="fa-solid fa-circle-info text-blue-500"></i> Informasi Penting
            </h4>
            <ul class="space-y-1.5 text-xs text-blue-700">
                <li class="flex items-start gap-2">
                    <i class="fa-solid fa-check text-blue-400 mt-0.5 shrink-0"></i>
                    Data penerima bantuan sosial ditetapkan oleh petugas desa berdasarkan verifikasi DTKS (Data Terpadu Kesejahteraan Sosial).
                </li>
                <li class="flex items-start gap-2">
                    <i class="fa-solid fa-check text-blue-400 mt-0.5 shrink-0"></i>
                    Jika Anda merasa berhak namun tidak terdaftar, silakan melapor ke Kantor Desa.
                </li>
            </ul>
        </div>

    </div>

@endsection
