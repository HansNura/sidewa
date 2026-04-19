@extends('layouts.backoffice')

@section('title', 'Data Perencanaan Desa - Panel Administrasi')

@push('styles')
    <style>
        [x-cloak] { display: none !important; }
        .custom-checkbox {
            appearance: none; background-color: #fff; margin: 0; font: inherit; color: currentColor;
            width: 1.15em; height: 1.15em; border: 2px solid #cbd5e1; border-radius: 0.25em; display: grid;
            place-content: center; cursor: pointer; transition: all 0.2s ease-in-out;
        }
        .custom-checkbox::before {
            content: ""; width: 0.65em; height: 0.65em; transform: scale(0); transition: 120ms transform ease-in-out;
            box-shadow: inset 1em 1em white; background-color: transform; transform-origin: center;
            clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%);
        }
        .custom-checkbox:checked { background-color: #16a34a; border-color: #16a34a; }
        .custom-checkbox:checked::before { transform: scale(1); }
    </style>
@endpush

@section('content')
<div class="flex-1 flex flex-col h-full bg-[#F8FAFC]" x-data="{ 
        activeTab: '{{ $tab }}', // rkpdes, rpjmdes
        addModalOpen: false,
        detailDrawerOpen: false,
        syncModalOpen: false,
        selectAll: false,
        selectedRows: [],
        activeRencanaId: null,
        
        formatIDR(val) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(val);
        },

        fetchDetail(id) {
            this.activeRencanaId = id;
            this.detailDrawerOpen = true;
            document.getElementById('drawer-content').innerHTML = '<div class=\'p-6 text-center text-gray-500\'><i class=\'fa-solid fa-spinner fa-spin\'></i> Memuat data...</div>';
            
            fetch(`/admin/pembangunan/perencanaan/drawer/${id}`)
                .then(res => res.text())
                .then(html => {
                    document.getElementById('drawer-content').innerHTML = html;
                });
        },
        
        openSyncModal(id) {
            this.activeRencanaId = id;
            this.detailDrawerOpen = false;
            // Delay for drawer to close before opening modal
            setTimeout(() => {
                this.syncModalOpen = true;
                // Fetch basic info for modal UI
                fetch(`/admin/pembangunan/perencanaan/drawer/${id}`)
                    .then(res => res.text())
                    .then(html => {
                        let parser = new DOMParser();
                        let doc = parser.parseFromString(html, 'text/html');
                        // Get data attributes or text to populate form
                        let namaProg = doc.querySelector('.nama-program-text').innerText;
                        let paguText = doc.querySelector('.pagu-text').innerText;
                        let cleanPagu = paguText.replace(/[^0-9]/g, '');
                        
                        document.getElementById('sync-judul-asal').innerText = namaProg;
                        document.getElementById('sync-judul-tujuan').innerText = 'Pembangunan ' + namaProg;
                        document.getElementById('sync-pagu-draf').innerText = paguText;
                        document.getElementById('sync-pagu-final').value = cleanPagu;
                        
                        let form = document.getElementById('sync-form');
                        form.action = `/admin/pembangunan/perencanaan/${id}/sync`;
                    });
            }, 300);
        }
    }">

    <header class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4 shrink-0 px-4 sm:px-6 pt-6">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-semibold px-2.5 py-1 bg-amber-100 text-amber-700 rounded-md uppercase tracking-wider">Akses Perencanaan</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Perencanaan Pembangunan</h1>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto px-4 sm:px-6 lg:px-8 pb-8 custom-scrollbar">
        @if(session('success'))
            <div class="mb-4 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm font-bold shadow-sm">
                <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm font-bold shadow-sm">
                <i class="fa-solid fa-circle-xmark mr-2"></i>{{ session('error') }}
            </div>
        @endif

        <div class="max-w-7xl mx-auto space-y-6">
            <section class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Data Perencanaan Desa</h2>
                    <p class="text-sm text-gray-500 mt-1">Kelola RKPDes dan RPJMDes sebagai dasar pelaksanaan fisik desa.</p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <button class="bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 shadow-sm rounded-xl px-4 py-2.5 text-sm font-bold transition-all flex items-center gap-2">
                        <i class="fa-solid fa-file-pdf text-red-600"></i> <span class="hidden sm:inline">Cetak Dokumen RKP</span>
                    </button>
                    <button @click="addModalOpen = true" class="bg-green-700 hover:bg-green-800 text-white shadow-md rounded-xl px-5 py-2.5 text-sm font-bold transition-all flex items-center gap-2">
                        <i class="fa-solid fa-folder-plus"></i> Tambah Usulan
                    </button>
                </div>
            </section>

            <!-- SUMMARY CARDS -->
            <section class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between group">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Total Program ({{ strtoupper($tab) }})</p>
                        <h3 class="text-2xl font-extrabold text-gray-900">{{ $totalProgram }}</h3>
                    </div>
                </article>

                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between group border-l-4 border-l-green-500">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Estimasi Anggaran</p>
                        <h3 class="text-xl font-extrabold text-green-700">Rp {{ number_format($estimasiAnggaran, 0, ',', '.') }}</h3>
                    </div>
                </article>

                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between group">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Telah Terealisasi (Sync)</p>
                        <div class="flex items-end gap-2">
                            <h3 class="text-2xl font-extrabold text-green-600">{{ $telahRealisasi }}</h3>
                            <span class="text-xs font-semibold text-gray-500 mb-1">Program</span>
                        </div>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-1.5 mt-3 overflow-hidden">
                        <div class="bg-green-500 h-full" style="width: {{ $persenRealisasi }}%"></div>
                    </div>
                </article>

                <article class="bg-amber-50 rounded-2xl p-5 border border-amber-100 shadow-sm flex flex-col justify-between group">
                    <div>
                        <p class="text-[10px] font-bold text-amber-600 uppercase tracking-wider mb-1">Prioritas Sangat Mendesak</p>
                        <h3 class="text-2xl font-extrabold text-amber-700">{{ $mendesak }}</h3>
                    </div>
                    <div class="text-[10px] font-medium text-amber-700 mt-3">Perlu segera disinkronkan ke APBDes</div>
                </article>
            </section>

            <!-- TABS CONTROL & FILTER -->
            <section class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-3 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex gap-2 p-1 bg-gray-100 rounded-xl overflow-x-auto w-full md:w-auto">
                    <form action="{{ route('admin.perencanaan.index') }}" method="GET" class="w-full flex">
                        <input type="hidden" name="tab" value="rkpdes">
                        <button type="submit" :class="activeTab === 'rkpdes' ? 'bg-white shadow-sm text-green-700 font-bold' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-2 text-sm rounded-lg transition-all whitespace-nowrap flex-1 md:flex-none">RKPDes (Tahunan)</button>
                    </form>
                    <form action="{{ route('admin.perencanaan.index') }}" method="GET" class="w-full flex">
                        <input type="hidden" name="tab" value="rpjmdes">
                        <button type="submit" :class="activeTab === 'rpjmdes' ? 'bg-white shadow-sm text-purple-700 font-bold' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-2 text-sm rounded-lg transition-all whitespace-nowrap flex-1 md:flex-none">RPJMDes (Multi-Tahun)</button>
                    </form>
                </div>

                <div class="flex items-center gap-2 pr-2 w-full md:w-auto">
                    <form action="{{ route('admin.perencanaan.index') }}" method="GET" class="relative w-full md:w-48">
                        <input type="hidden" name="tab" value="{{ $tab }}">
                        @if($tab == 'rkpdes')
                            <select name="rkp_tahun" onchange="this.form.submit()" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-xs font-bold text-gray-700 focus:ring-2 focus:ring-green-500 outline-none appearance-none pr-10 cursor-pointer">
                                @foreach(range(date('Y') + 1, 2021) as $yr)
                                    <option value="{{ $yr }}" {{ $rkpTahun == $yr ? 'selected' : '' }}>Tahun {{ $yr }}</option>
                                @endforeach
                            </select>
                        @else
                            <select name="rpjm_periode" onchange="this.form.submit()" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-xs font-bold text-gray-700 focus:ring-2 focus:ring-green-500 outline-none appearance-none pr-10 cursor-pointer">
                                <option value="2024-2030" {{ $rpjmPeriode == '2024-2030' ? 'selected' : '' }}>Periode 2024 - 2030</option>
                                <option value="2018-2024" {{ $rpjmPeriode == '2018-2024' ? 'selected' : '' }}>Periode 2018 - 2024</option>
                            </select>
                        @endif
                        <i class="fa-solid fa-calendar-alt absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </form>
                </div>
            </section>

            <!-- TAB CONTENT: DATA TABLE -->
            <div class="space-y-6">
                @include('pages.backoffice.perencanaan._table')
            </div>

        </div>
    </main>

    <!-- MODALS & DRAWERS -->
    @include('pages.backoffice.perencanaan._modals.add')
    @include('pages.backoffice.perencanaan._modals.sync')
    @include('pages.backoffice.perencanaan._drawers.detail')
</div>
@endsection
