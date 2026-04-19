@extends('layouts.backoffice')

@section('title', 'Manajemen Pengumuman & Agenda - Panel Administrasi')

@push('styles')
    <style>
        [x-cloak] { display: none !important; }
        .custom-checkbox {
            appearance: none; background-color: #fff; margin: 0;
            width: 1.15em; height: 1.15em; border: 2px solid #cbd5e1; border-radius: 0.25em; display: grid; place-content: center; cursor: pointer; transition: all 0.2s ease-in-out;
        }
        .custom-checkbox::before {
            content: ""; width: 0.65em; height: 0.65em; transform: scale(0); transition: 120ms transform ease-in-out; box-shadow: inset 1em 1em white; background-color: transform; transform-origin: center; clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%);
        }
        .custom-checkbox:checked { background-color: #16a34a; border-color: #16a34a; }
        .custom-checkbox:checked::before { transform: scale(1); }
        
        .calendar-grid { display: grid; grid-template-columns: repeat(7, minmax(0, 1fr)); gap: 1px; background-color: #e2e8f0; border: 1px solid #e2e8f0; }
        .calendar-cell { background-color: white; min-height: 100px; padding: 0.5rem; }
        .calendar-cell.inactive { background-color: #f8fafc; color: #94a3b8; }
        .calendar-cell.today { background-color: #f0fdf4; }

        .ql-toolbar { border: none !important; border-bottom: 1px solid #e2e8f0 !important; background: #f8fafc; padding: 12px !important; }
        .ql-container { border: none !important; font-family: 'Merriweather', serif; font-size: 1rem; }
        .ql-editor { padding: 24px !important; min-height: 400px; }
    </style>
@endpush

@section('content')
<div class="flex-1 flex flex-col h-full bg-[#F8FAFC]" x-data="{ 
        activeTab: '{{ $tab }}', 
        formModalOpen: false,
        detailDrawerOpen: false,
        previewModalOpen: false,
        selectAll: false,
        selectedRows: [],
        previewDevice: 'desktop',
        
        // Form & Editor State
        itemId: null,
        formType: 'pengumuman',
        itemTitle: '',
        publishStatus: 'publish',
        previewData: {},

        async openForm(id = null, type = 'pengumuman') {
            this.itemId = id;
            this.formType = type;
            if(id) {
                const res = await fetch(`/konten/informasi/api/${id}`);
                const data = await res.json();
                
                this.formType = data.type;
                this.itemTitle = data.title;
                this.publishStatus = data.status;
                
                document.getElementById('editor_title').value = data.title;
                if(data.type === 'agenda') {
                    if(data.start_date) document.getElementById('agenda_start_date').value = data.start_date.slice(0, 16);
                    if(data.end_date) document.getElementById('agenda_end_date').value = data.end_date.slice(0, 16);
                    document.getElementById('agenda_location').value = data.location || '';
                } else {
                    if(data.start_date) document.getElementById('peng_start_date').value = data.start_date.slice(0, 10);
                    if(data.end_date) document.getElementById('peng_end_date').value = data.end_date.slice(0, 10);
                }
                
                if(window.infoEditor) {
                    window.infoEditor.root.innerHTML = data.content_html || '';
                }
            } else {
                this.itemTitle = '';
                this.publishStatus = 'publish';
                document.getElementById('infoEditorForm').reset();
                if(window.infoEditor) window.infoEditor.root.innerHTML = '';
            }
            this.formModalOpen = true;
        },

        async openDetail(id) {
            const res = await fetch(`/konten/informasi/api/${id}`);
            this.previewData = await res.json();
            this.detailDrawerOpen = true;
        }
    }">

    <header class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4 shrink-0 px-4 sm:px-6 pt-6 -z-10">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-semibold px-2.5 py-1 bg-amber-100 text-amber-700 rounded-md uppercase tracking-wider">Akses Publikasi</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Pengumuman & Agenda</h1>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto px-4 sm:px-6 lg:px-8 pb-8 custom-scrollbar">
        @if(session('success'))
            <div class="mb-4 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm font-bold shadow-sm">
                <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
            </div>
        @endif
        
        @if($errors->any())
            <div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm shadow-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <p><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="max-w-7xl mx-auto space-y-6">

            <!-- HEADER ACTIONS -->
            <section class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Manajemen Informasi</h2>
                    <p class="text-sm text-gray-500 mt-1">Kelola pemberitahuan resmi dan jadwal kegiatan desa.</p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <button @click="openForm(null, 'pengumuman')" class="bg-green-700 hover:bg-green-800 text-white shadow-md rounded-xl px-4 py-2.5 text-sm font-bold transition-all flex items-center gap-2">
                        <i class="fa-solid fa-bullhorn"></i> Buat Informasi
                    </button>
                    <button @click="openForm(null, 'agenda')" class="bg-purple-700 hover:bg-purple-800 text-white shadow-md rounded-xl px-4 py-2.5 text-sm font-bold transition-all flex items-center gap-2">
                        <i class="fa-regular fa-calendar-check"></i> Buat Agenda
                    </button>
                </div>
            </section>

            <!-- PAGE SUMMARY CARDS -->
            <section class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between group hover:-translate-y-1 transition-transform">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Data</p>
                        <div class="w-8 h-8 rounded-lg bg-gray-50 text-gray-500 flex items-center justify-center"><i class="fa-solid fa-layer-group text-xs"></i></div>
                    </div>
                    <h3 class="text-2xl font-extrabold text-gray-900">{{ $totalData }}</h3>
                </article>

                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between group border-b-4 border-b-green-500 hover:-translate-y-1 transition-transform">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Info Aktif / Tayang</p>
                        <div class="w-8 h-8 rounded-lg bg-green-50 text-green-600 flex items-center justify-center"><i class="fa-solid fa-broadcast-tower text-xs"></i></div>
                    </div>
                    <h3 class="text-2xl font-extrabold text-green-600">{{ $totalActive }}</h3>
                </article>

                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between group border-b-4 border-b-purple-500 hover:-translate-y-1 transition-transform">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Agenda Terdekat</p>
                        <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center"><i class="fa-regular fa-calendar-check text-xs"></i></div>
                    </div>
                    <h3 class="text-2xl font-extrabold text-purple-600">{{ $agendaTerdekat }}</h3>
                </article>

                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between group border-b-4 border-b-gray-400 hover:-translate-y-1 transition-transform">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Expired / Arsip</p>
                        <div class="w-8 h-8 rounded-lg bg-gray-100 text-gray-500 flex items-center justify-center"><i class="fa-solid fa-box-archive text-xs"></i></div>
                    </div>
                    <h3 class="text-2xl font-extrabold text-gray-500">{{ $totalArchive }}</h3>
                </article>
            </section>

            <!-- TABS CONTROL -->
            <section class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-3 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex gap-2 p-1 bg-gray-100 rounded-xl w-full md:w-auto overflow-x-auto">
                    <form action="{{ route('admin.informasi.index') }}" method="GET" class="flex-1 md:flex-none">
                        <input type="hidden" name="tab" value="list">
                        <button type="submit" :class="activeTab === 'list' ? 'bg-white shadow-sm text-green-700 font-bold' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-2 text-sm rounded-lg transition-all whitespace-nowrap w-full"><i class="fa-solid fa-list mr-1"></i> Daftar Informasi</button>
                    </form>
                    <form action="{{ route('admin.informasi.index') }}" method="GET" class="flex-1 md:flex-none">
                        <input type="hidden" name="tab" value="calendar">
                        <button type="submit" :class="activeTab === 'calendar' ? 'bg-white shadow-sm text-green-700 font-bold' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-2 text-sm rounded-lg transition-all whitespace-nowrap w-full"><i class="fa-regular fa-calendar-days mr-1"></i> Tampilan Kalender</button>
                    </form>
                </div>
            </section>

            <!-- TAB CONTENT -->
            @if($tab == 'list')
                @include('pages.backoffice.informasi._tab_list')
            @else
                @include('pages.backoffice.informasi._tab_calendar')
            @endif

        </div>
    </main>

    @include('pages.backoffice.informasi._form')
    @include('pages.backoffice.informasi._drawer')
</div>
@endsection
