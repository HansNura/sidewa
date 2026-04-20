@extends('layouts.backoffice')

@section('title', 'Integrasi API & Layanan Eksternal - Panel Administrasi')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .code-block { background-color: #1e293b; color: #e2e8f0; padding: 1rem; border-radius: 0.75rem; font-family: 'Consolas', monospace; font-size: 0.75rem; overflow-x: auto; }
        .code-string { color: #86efac; }
        .code-number { color: #93c5fd; }
        .code-key { color: #f87171; }
        .custom-checkbox {
            appearance: none; background-color: #fff; margin: 0; font: inherit; color: currentColor; width: 1.15em; height: 1.15em;
            border: 2px solid #cbd5e1; border-radius: 0.25em; display: grid; place-content: center; cursor: pointer; transition: all 0.2s ease-in-out;
        }
        .custom-checkbox::before {
            content: ""; width: 0.65em; height: 0.65em; transform: scale(0); transition: 120ms transform ease-in-out;
            box-shadow: inset 1em 1em white; background-color: transform; transform-origin: center;
            clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%);
        }
        .custom-checkbox:checked { background-color: #16a34a; border-color: #16a34a; }
        .custom-checkbox:checked::before { transform: scale(1); }
        .toggle-checkbox:checked { right: 0; border-color: #16a34a; }
        .toggle-checkbox:checked+.toggle-label { background-color: #16a34a; }
        .toggle-checkbox { right: 0; z-index: 1; border-color: #e2e8f0; transition: all 0.3s; }
        .toggle-label { background-color: #e2e8f0; transition: all 0.3s; }
    </style>
@endpush

@section('content')
<div class="flex-1 flex flex-col h-full overflow-hidden w-full relative bg-[#F8FAFC]"
    x-data="{ 
        activeTab: '{{ session('syncProcessing') ? 'sync' : 'dashboard' }}',
        keyModalOpen: false,
        activeEndpoint: 'penduduk_get',
        isSyncing: {{ session('syncProcessing') ? 'true' : 'false' }},
        init() {
            if(this.isSyncing) {
                setTimeout(() => { this.isSyncing = false; }, 3000);
            }
        },
        runSync() {
            this.isSyncing = true;
            document.getElementById('syncForm').submit();
        }
    }">

    <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 sm:px-6 shrink-0 z-10 shadow-sm mt-0">
        <div class="flex items-center gap-4">
            <div class="hidden sm:flex items-center gap-2">
                <span class="text-xs font-semibold px-2.5 py-1 bg-purple-100 text-purple-700 rounded-md uppercase tracking-wider">Super Admin</span>
                <h2 class="font-bold text-gray-800">Sistem Inti & Integrasi</h2>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button class="flex items-center gap-2.5 focus:outline-none hover:bg-gray-50 p-1 rounded-full sm:rounded-xl sm:pr-3 transition-colors">
                <img src="{{ Auth::user()->avatarUrl() }}" alt="User" class="w-8 h-8 rounded-full shadow-sm">
                <div class="hidden md:flex flex-col text-left">
                    <span class="text-sm font-bold text-gray-800 leading-none mb-1">{{ Auth::user()->name }}</span>
                    <span class="text-[10px] font-medium text-gray-500 leading-none">Administrator</span>
                </div>
            </button>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto custom-scrollbar p-4 sm:p-6 lg:p-8">

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline font-bold">{{ session('success') }}</span>
            </div>
        @endif

        <div class="max-w-7xl mx-auto space-y-6">

            <section class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Integrasi API & Layanan Eksternal</h1>
                    <p class="text-sm text-gray-500 mt-1">Kelola koneksi sistem SID dengan ekosistem luar seperti OpenDK, Prodeskel.</p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <button @click="activeTab = 'sync'" class="bg-primary-700 hover:bg-primary-800 text-white shadow-md rounded-xl px-5 py-2.5 text-sm font-bold transition-all flex items-center gap-2">
                        <i class="fa-solid fa-rotate"></i> <span>Menu Sinkronisasi</span>
                    </button>
                </div>
            </section>

            <section class="flex gap-2 p-1 bg-white rounded-xl w-full md:w-auto overflow-x-auto shadow-sm border border-gray-100">
                <button @click="activeTab = 'dashboard'" :class="activeTab === 'dashboard' ? 'bg-primary-50 text-primary-700 font-bold border border-primary-200' : 'text-gray-500 hover:text-gray-700 border border-transparent hover:bg-gray-50'" class="flex-1 md:flex-none px-6 py-2.5 text-sm rounded-lg transition-all whitespace-nowrap"><i class="fa-solid fa-chart-line mr-1.5"></i> Dashboard</button>
                <button @click="activeTab = 'apikey'" :class="activeTab === 'apikey' ? 'bg-primary-50 text-primary-700 font-bold border border-primary-200' : 'text-gray-500 hover:text-gray-700 border border-transparent hover:bg-gray-50'" class="flex-1 md:flex-none px-6 py-2.5 text-sm rounded-lg transition-all whitespace-nowrap"><i class="fa-solid fa-key mr-1.5"></i> API Keys</button>
                <button @click="activeTab = 'sync'" :class="activeTab === 'sync' ? 'bg-primary-50 text-primary-700 font-bold border border-primary-200' : 'text-gray-500 hover:text-gray-700 border border-transparent hover:bg-gray-50'" class="flex-1 md:flex-none px-6 py-2.5 text-sm rounded-lg transition-all whitespace-nowrap"><i class="fa-solid fa-arrows-rotate mr-1.5"></i> Sinkronisasi Data</button>
                <button @click="activeTab = 'docs'" :class="activeTab === 'docs' ? 'bg-primary-50 text-primary-700 font-bold border border-primary-200' : 'text-gray-500 hover:text-gray-700 border border-transparent hover:bg-gray-50'" class="flex-1 md:flex-none px-6 py-2.5 text-sm rounded-lg transition-all whitespace-nowrap"><i class="fa-solid fa-book mr-1.5"></i> Dokumentasi API</button>
            </section>

            <!-- TAB 1: DASHBOARD -->
            <div x-show="activeTab === 'dashboard'" x-transition.opacity class="space-y-6">
                <!-- Overview -->
                <section class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between group">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0"><i class="fa-solid fa-sitemap"></i></div>
                                <div><h3 class="font-extrabold text-gray-900 leading-tight">OpenDK Kecamatan</h3><p class="text-[10px] text-gray-500 mt-0.5">Two-way Sync</p></div>
                            </div>
                            <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-0.5 rounded border border-green-200 uppercase tracking-widest"><i class="fa-solid fa-circle-check mr-1"></i> Aktif</span>
                        </div>
                    </article>
                    <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between group">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0"><i class="fa-solid fa-building-columns"></i></div>
                                <div><h3 class="font-extrabold text-gray-900 leading-tight">Prodeskel Bina Pemdes</h3><p class="text-[10px] text-gray-500 mt-0.5">Push Only</p></div>
                            </div>
                            <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-0.5 rounded border border-green-200 uppercase tracking-widest"><i class="fa-solid fa-circle-check mr-1"></i> Aktif</span>
                        </div>
                    </article>
                    <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between group">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0"><i class="fa-solid fa-wallet"></i></div>
                                <div><h3 class="font-extrabold text-gray-900 leading-tight">Siskeudes Online</h3><p class="text-[10px] text-gray-500 mt-0.5">Pull Only</p></div>
                            </div>
                            <span class="bg-amber-100 text-amber-700 text-[10px] font-bold px-2 py-0.5 rounded border border-amber-200 uppercase tracking-widest"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Warning</span>
                        </div>
                    </article>
                </section>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <section class="bg-white border border-gray-100 shadow-sm rounded-2xl p-5 lg:col-span-2 flex flex-col">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h3 class="font-bold text-gray-800">API Request Traffic & Latency</h3>
                                <p class="text-[10px] text-gray-500 mt-0.5">Volume request harian dan rata-rata waktu respon.</p>
                            </div>
                        </div>
                        <div id="apiHealthChart" class="w-full h-72 flex-1"></div>
                    </section>

                    <section class="flex flex-col gap-6">
                        <div class="bg-gray-900 rounded-2xl p-5 text-white shadow-xl">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">API Metrics (24 Jam)</h4>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-[10px] text-gray-400 mb-1">Total Requests</p>
                                    <p class="text-2xl font-extrabold text-white">{{ number_format($totalRequests) }} <span class="text-xs font-normal text-green-400 ml-1"><i class="fa-solid fa-arrow-up"></i> 5%</span></p>
                                </div>
                                <div class="grid grid-cols-2 gap-4 border-t border-gray-700 pt-4">
                                    <div>
                                        <p class="text-[10px] text-gray-400 mb-1">Success Rate</p>
                                        <p class="text-lg font-bold text-green-400">99.8%</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-gray-400 mb-1">Avg Latency</p>
                                        <p class="text-lg font-bold text-amber-400">{{ round($avgLatency) }}ms</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex-1 flex flex-col justify-center gap-3">
                            <h4 class="text-sm font-bold text-gray-800 mb-1">Tindakan Cepat</h4>
                            <button @click="activeTab = 'sync'" class="w-full bg-gray-50 border border-gray-200 text-gray-700 hover:bg-gray-100 hover:text-primary-700 px-4 py-2.5 rounded-xl font-bold text-sm transition-colors flex justify-center items-center gap-2"><i class="fa-solid fa-rotate"></i> Sinkronisasi Manual</button>
                        </div>
                    </section>
                </div>

                <section class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden flex flex-col">
                    <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="font-bold text-gray-800">Log Aktivitas (Recent Requests)</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left whitespace-nowrap">
                            <thead>
                                <tr class="bg-white text-gray-500 text-[10px] uppercase tracking-wider border-b border-gray-100">
                                    <th class="p-4 font-bold">Timestamp</th>
                                    <th class="p-4 font-bold">Method & Endpoint</th>
                                    <th class="p-4 font-bold text-center">Status</th>
                                    <th class="p-4 font-bold text-right">Latency</th>
                                    <th class="p-4 font-bold text-center">IP Address</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs font-mono text-gray-600 divide-y divide-gray-50">
                                @forelse($logs as $log)
                                    <tr class="hover:bg-gray-50">
                                        <td class="p-4 text-gray-400">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                        <td class="p-4">
                                            <span class="bg-blue-100 text-blue-700 font-bold px-1.5 py-0.5 rounded mr-2">{{ $log->method }}</span>{{ $log->endpoint }}
                                        </td>
                                        <td class="p-4 text-center">
                                            <span class="{{ $log->status_code == 200 ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200' }} font-bold px-2 py-0.5 rounded">
                                                {{ $log->status_code }}
                                            </span>
                                        </td>
                                        <td class="p-4 text-right {{ $log->latency_ms > 200 ? 'text-red-500' : '' }}">{{ $log->latency_ms }}ms</td>
                                        <td class="p-4 text-center text-gray-400">{{ $log->ip_address }}</td>
                                    </tr>
                                @empty
                                    <!-- Fallback Data -->
                                    <tr class="hover:bg-gray-50 bg-red-50/10">
                                        <td class="p-4 text-gray-400">Baru Saja</td>
                                        <td class="p-4 text-red-600"><span class="bg-amber-100 text-amber-700 font-bold px-1.5 py-0.5 rounded mr-2">PUT</span>/api/v1/apbdes</td>
                                        <td class="p-4 text-center"><span class="bg-red-50 text-red-700 font-bold px-2 py-0.5 rounded border border-red-200">401 Unauthorized</span></td>
                                        <td class="p-4 text-right text-red-500">45ms</td>
                                        <td class="p-4 text-center text-gray-400">192.168.1.5</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            <!-- TAB 2: MANAJEMEN API KEY -->
            <div x-show="activeTab === 'apikey'" x-transition.opacity class="space-y-6" x-cloak>
                <section class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                    <div>
                        <h3 class="font-bold text-gray-800">Daftar API Keys Akses Pihak Ketiga</h3>
                        <p class="text-xs text-gray-500 mt-1">Gunakan Custom Token System (tabel api_clients) tanpa mencederai sumber daya server.</p>
                    </div>
                    <button @click="keyModalOpen = true" class="bg-primary-700 hover:bg-primary-800 text-white shadow-md rounded-xl px-5 py-2.5 text-sm font-bold transition-all flex items-center gap-2 shrink-0">
                        <i class="fa-solid fa-key"></i> Buat API Key Baru
                    </button>
                </section>

                <section class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden flex flex-col">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left whitespace-nowrap">
                            <thead>
                                <tr class="bg-gray-50 text-gray-500 text-[10px] uppercase tracking-wider border-b border-gray-100">
                                    <th class="p-4 font-bold">Nama Aplikasi / Client</th>
                                    <th class="p-4 font-bold">Token Key (Masked)</th>
                                    <th class="p-4 font-bold">Scopes</th>
                                    <th class="p-4 font-bold text-center">Status</th>
                                    <th class="p-4 font-bold text-right">Terakhir Digunakan</th>
                                    <th class="p-4 font-bold text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-gray-50">
                                @forelse($clients as $c)
                                <tr class="hover:bg-gray-50 {{ $c->status == 'revoked' ? 'opacity-60' : '' }}">
                                    <td class="p-4">
                                        <div class="font-bold text-gray-900 {{ $c->status == 'revoked' ? 'line-through' : '' }}">{{ $c->name }}</div>
                                    </td>
                                    <td class="p-4 font-mono text-xs text-gray-500 flex items-center gap-2">
                                        {{ $c->plain_token_suffix }}
                                    </td>
                                    <td class="p-4">
                                        @foreach($c->scopes as $scope)
                                        <span class="bg-gray-100 text-gray-600 text-[10px] font-bold px-2 py-0.5 rounded border border-gray-200 uppercase">{{ $scope }}</span>
                                        @endforeach
                                    </td>
                                    <td class="p-4 text-center">
                                        @if($c->status == 'active')
                                            <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-0.5 rounded-full border border-green-200 uppercase tracking-widest">Aktif</span>
                                        @else
                                            <span class="bg-red-50 text-red-600 text-[10px] font-bold px-2 py-0.5 rounded-full border border-red-200 uppercase tracking-widest">Revoked</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-right text-xs text-gray-500">{{ $c->last_used_at ? $c->last_used_at->diffForHumans() : 'Belum pernah' }}</td>
                                    <td class="p-4 text-center">
                                        @if($c->status == 'active')
                                        <form action="{{ route('admin.api.revoke', $c->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-gray-400 hover:text-red-500 mx-1" title="Revoke/Hapus"><i class="fa-solid fa-ban"></i></button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="p-6 text-center text-gray-500">Belum ada API Key eksternal di-generate.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            <!-- TAB 3: SYNC DATA -->
            <div x-show="activeTab === 'sync'" x-transition.opacity class="grid grid-cols-1 lg:grid-cols-2 gap-6" x-cloak>
                <section class="bg-white border border-gray-100 shadow-sm rounded-2xl p-6 flex flex-col h-fit">
                    <div class="border-b border-gray-100 pb-4 mb-6"><h3 class="font-bold text-gray-800">Konfigurasi Sinkronisasi Eksternal</h3></div>
                    <form class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Modul Data</label>
                            <select class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm outline-none">
                                <option>Data Penduduk & Keluarga</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Arah Sinkronisasi</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="cursor-pointer">
                                    <input type="radio" name="direction" value="push" class="peer sr-only" checked>
                                    <div class="p-3 border-2 border-gray-200 rounded-xl text-center peer-checked:border-primary-500 peer-checked:bg-primary-50 transition-all text-sm font-semibold text-gray-600 peer-checked:text-primary-700">Push (Ke Atas)</div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="direction" value="pull" class="peer sr-only">
                                    <div class="p-3 border-2 border-gray-200 rounded-xl text-center peer-checked:border-primary-500 peer-checked:bg-primary-50 transition-all text-sm font-semibold text-gray-600 peer-checked:text-primary-700">Pull (Tarik Data)</div>
                                </label>
                            </div>
                        </div>
                        <div class="pt-4 border-t border-gray-100 flex justify-end">
                            <button type="button" class="bg-gray-100 text-gray-700 font-bold px-5 py-2.5 rounded-xl">Simpan Konfigurasi</button>
                        </div>
                    </form>
                </section>

                <div class="space-y-6">
                    <section class="bg-gradient-to-br from-primary-700 to-primary-900 border border-primary-800 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
                        <i class="fa-solid fa-rotate absolute -right-6 -bottom-6 text-9xl text-white/10"></i>
                        <div class="relative z-10">
                            <h3 class="font-extrabold text-xl mb-1">Eksekusi Sinkronisasi Manual</h3>
                            <p class="text-sm text-primary-200 mb-6">Mendorong queue worker segera bertindak.</p>

                            <form id="syncForm" action="{{ route('admin.api.sync') }}" method="POST">
                                @csrf
                                <div x-show="!isSyncing">
                                    <button type="button" @click="runSync" class="bg-white text-primary-800 font-extrabold px-6 py-3 rounded-xl shadow-md flex items-center gap-2"><i class="fa-solid fa-play"></i> Mulai Sinkronisasi</button>
                                </div>
                                <div x-show="isSyncing" x-cloak class="bg-white/20 backdrop-blur border border-white/30 rounded-xl p-4">
                                    <div class="flex justify-between text-xs font-bold mb-2"><span>Memproses Data Kependudukan...</span><span>Sedang Berjalan</span></div>
                                    <div class="w-full bg-black/20 rounded-full h-2"><div class="bg-white h-2 rounded-full animate-pulse w-[45%]"></div></div>
                                </div>
                            </form>
                        </div>
                    </section>
                </div>
            </div>

            <!-- TAB 4: DOKUMENTASI -->
            <div x-show="activeTab === 'docs'" x-transition.opacity class="flex flex-col lg:flex-row gap-6 h-[600px]" x-cloak>
                <div class="w-full lg:w-72 bg-white rounded-2xl border border-gray-100 shadow-sm flex flex-col overflow-hidden">
                    <div class="p-4 border-b border-gray-100 bg-gray-50/50"><h3 class="font-bold text-gray-800">Endpoint Reference</h3></div>
                    <div class="flex-1 p-2 space-y-1">
                        <p class="text-[10px] font-bold text-gray-400 uppercase ml-2 my-2">Kependudukan</p>
                        <button @click="activeEndpoint = 'penduduk_get'" :class="activeEndpoint === 'penduduk_get' ? 'bg-primary-50 text-primary-700' : 'text-gray-600'" class="w-full text-left px-3 py-2 rounded-lg text-xs font-mono transition-colors flex items-center gap-2"><span class="text-[9px] font-bold text-blue-600 bg-blue-100 px-1 rounded w-10 text-center">GET</span>/api/penduduk</button>
                    </div>
                </div>

                <div class="flex-1 bg-white rounded-2xl border border-gray-100 shadow-sm flex flex-col overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                        <h2 class="text-xl font-extrabold text-gray-900">Ambil Daftar Penduduk</h2>
                        <div class="flex items-center gap-3 mt-2">
                            <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2 py-0.5 rounded font-mono">GET</span>
                            <span class="text-sm font-mono text-gray-600">{{ url('/api/v1/penduduk') }}</span>
                        </div>
                    </div>
                    <div class="flex-1 p-6 flex flex-col lg:flex-row gap-6">
                        <div class="flex-1 space-y-6">
                            <div>
                                <h4 class="text-sm font-bold text-gray-800 mb-2">Authentication</h4>
                                <p class="text-sm text-gray-600">Diperlukan <code class="bg-gray-100 text-red-500 px-1 rounded">Bearer Token</code> yang dihasilkan dari tab API Keys.</p>
                            </div>
                        </div>
                        <div class="w-full lg:w-5/12 flex flex-col gap-4">
                            <div class="bg-gray-900 rounded-xl overflow-hidden shadow-sm flex flex-col">
                                <div class="px-4 py-2 bg-gray-800 flex justify-between"><span class="text-[10px] font-bold text-gray-400">Response 200 OK</span></div>
                                <div class="code-block flex-1 max-h-64"><pre><code>{
  <span class="code-key">"status"</span>: <span class="code-string">"success"</span>,
  <span class="code-key">"data"</span>: [
    { <span class="code-key">"nik"</span>: <span class="code-string">"32091234..."</span> }
  ]
}</code></pre></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- MODAL: BUAT API KEY -->
<div x-show="keyModalOpen" class="fixed inset-0 z-[110] flex items-center justify-center p-4 sm:p-6" x-cloak>
    <div x-show="keyModalOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="keyModalOpen = false"></div>
    <form action="{{ route('admin.api.generate') }}" method="POST" class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg flex flex-col z-10">
        @csrf
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="font-extrabold text-lg text-gray-900">Buat API Key Baru</h3>
        </div>
        <div class="p-6 space-y-5">
            <div class="space-y-1">
                <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Nama Aplikasi <span class="text-red-500">*</span></label>
                <input type="text" name="name" required placeholder="Misal: Sistem Kabupaten" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-4 py-2.5 text-sm outline-none">
            </div>
            <div class="space-y-2">
                <label class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-2 block">Scope Akses</label>
                <label class="flex items-center gap-2 p-2 hover:bg-gray-50"><input type="checkbox" name="scopes[]" value="read_penduduk" class="custom-checkbox" checked><span class="text-sm text-gray-700">Data Penduduk (Read)</span></label>
                <label class="flex items-center gap-2 p-2 hover:bg-gray-50"><input type="checkbox" name="scopes[]" value="write_penduduk" class="custom-checkbox"><span class="text-sm text-gray-700">Data Penduduk (Write / Update)</span></label>
                <label class="flex items-center gap-2 p-2 hover:bg-gray-50"><input type="checkbox" name="scopes[]" value="sync_apbdes" class="custom-checkbox"><span class="text-sm text-gray-700">Manajemen APBDes</span></label>
            </div>
        </div>
        <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3 rounded-b-3xl border-t border-gray-100">
            <button type="button" @click="keyModalOpen = false" class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-600 bg-white border border-gray-200">Batal</button>
            <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-primary-700 shadow-md flex items-center gap-2"><i class="fa-solid fa-key"></i> Generate Key</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof Highcharts !== 'undefined') {
            Highcharts.chart('apiHealthChart', {
                chart: { type: 'spline', backgroundColor: 'transparent', style: { fontFamily: 'Inter, sans-serif' } },
                title: { text: null },
                xAxis: { categories: {!! json_encode($chartData['hours']) !!}, gridLineWidth: 0, labels: { style: { fontSize: '10px' } } },
                yAxis: [{ title: { text: 'Traffic (Requests)', style: { color: '#3b82f6' } }, gridLineColor: '#f1f5f9' }, { title: { text: 'Latency (ms)', style: { color: '#f59e0b' } }, opposite: true }],
                legend: { enabled: false }, credits: { enabled: false },
                tooltip: { backgroundColor: '#1e293b', style: { color: '#ffffff' }, borderWidth: 0, borderRadius: 8, shared: true },
                series: [{ name: 'Traffic', type: 'column', data: {!! json_encode($chartData['traffic']) !!}, color: '#3b82f6', borderRadius: 3 }, { name: 'Latency', type: 'spline', yAxis: 1, data: {!! json_encode($chartData['latency']) !!}, color: '#f59e0b', lineWidth: 3, marker: { radius: 4 } }]
            });
        }
    });
</script>
@endsection
