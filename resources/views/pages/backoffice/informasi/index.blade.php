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
                const res = await fetch(`/admin/konten/informasi/api/${id}`);
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
            const res = await fetch(`/admin/konten/informasi/api/${id}`);
            this.previewData = await res.json();
            this.detailDrawerOpen = true;
        }
    }">

    <!-- GLOBAL HEADER (Consistent SaaS Aesthetic) -->
    <header class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4 shrink-0 px-4 sm:px-6 pt-6 -z-10">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <span class="w-8 h-1 bg-green-500 rounded-full"></span>
                <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Akses Publikasi</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Pengumuman & Agenda</h1>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto px-4 sm:px-6 lg:px-8 pb-8 custom-scrollbar">
        @if(session('success'))
            <div class="mb-4 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm font-bold shadow-sm flex items-center">
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
                    <button @click="openForm(null, 'pengumuman')" class="bg-green-700 hover:bg-green-800 text-white shadow-md shadow-green-900/10 rounded-xl px-5 py-2.5 text-sm font-bold transition-all flex items-center gap-2 active:scale-95">
                        <i class="fa-solid fa-bullhorn"></i> Buat Informasi
                    </button>
                    <button @click="openForm(null, 'agenda')" class="bg-purple-700 hover:bg-purple-800 text-white shadow-md shadow-purple-900/10 rounded-xl px-5 py-2.5 text-sm font-bold transition-all flex items-center gap-2 active:scale-95">
                        <i class="fa-regular fa-calendar-check"></i> Buat Agenda
                    </button>
                </div>
            </section>

            <!-- PAGE SUMMARY CARDS (SaaS Pattern) -->
            <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Card 1 -->
                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm relative overflow-hidden group hover:shadow-md hover:-translate-y-1 transition-all">
                    <i class="fa-solid fa-layer-group absolute -right-4 -bottom-4 text-7xl text-gray-100 rotate-12 group-hover:scale-110 transition-transform duration-500"></i>
                    <div class="relative z-10 flex flex-col justify-between h-full gap-4">
                        <div class="flex justify-between items-start">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Data</p>
                            <div class="w-8 h-8 rounded-xl bg-gray-50 text-gray-600 flex items-center justify-center shadow-inner border border-gray-100"><i class="fa-solid fa-folder-open text-xs"></i></div>
                        </div>
                        <h3 class="text-3xl font-extrabold text-gray-900">{{ $totalData }}</h3>
                    </div>
                </article>

                <!-- Card 2 -->
                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm relative overflow-hidden group hover:shadow-md hover:-translate-y-1 transition-all">
                    <i class="fa-solid fa-bullhorn absolute -right-4 -bottom-4 text-7xl text-green-50 rotate-12 group-hover:scale-110 transition-transform duration-500"></i>
                    <div class="relative z-10 flex flex-col justify-between h-full gap-4">
                        <div class="flex justify-between items-start">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Info Tayang</p>
                            <div class="w-8 h-8 rounded-xl bg-green-50 text-green-600 flex items-center justify-center shadow-inner border border-green-100"><i class="fa-solid fa-broadcast-tower text-xs"></i></div>
                        </div>
                        <h3 class="text-3xl font-extrabold text-green-600">{{ $totalActive }}</h3>
                    </div>
                </article>

                <!-- Card 3 -->
                <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm relative overflow-hidden group hover:shadow-md hover:-translate-y-1 transition-all">
                    <i class="fa-regular fa-calendar-check absolute -right-4 -bottom-4 text-7xl text-purple-50 rotate-12 group-hover:scale-110 transition-transform duration-500"></i>
                    <div class="relative z-10 flex flex-col justify-between h-full gap-4">
                        <div class="flex justify-between items-start">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Agenda Terdekat</p>
                            <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shadow-inner border border-purple-100"><i class="fa-solid fa-calendar-days text-xs"></i></div>
                        </div>
                        <h3 class="text-3xl font-extrabold text-purple-600">{{ $agendaTerdekat }}</h3>
                    </div>
                </article>

                <!-- Card 4 (Dark Accent) -->
                <article class="bg-gray-900 rounded-2xl p-5 border-l-4 border-l-green-500 shadow-lg shadow-gray-900/10 relative overflow-hidden group hover:shadow-xl hover:-translate-y-1 transition-all">
                    <i class="fa-solid fa-box-archive absolute -right-4 -bottom-4 text-7xl text-gray-800 rotate-12 group-hover:scale-110 transition-transform duration-500"></i>
                    <div class="relative z-10 flex flex-col justify-between h-full gap-4">
                        <div class="flex justify-between items-start">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Expired / Arsip</p>
                            <div class="w-8 h-8 rounded-xl bg-gray-800 text-gray-300 flex items-center justify-center shadow-inner border border-gray-700"><i class="fa-solid fa-clock-rotate-left text-xs"></i></div>
                        </div>
                        <h3 class="text-3xl font-extrabold text-white">{{ $totalArchive }}</h3>
                    </div>
                </article>
            </section>

            <!-- TABS CONTROL -->
            <section class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex gap-2 p-1 bg-white rounded-xl w-full md:w-auto overflow-x-auto shadow-sm border border-gray-100">
                    <form action="{{ route('admin.informasi.index') }}" method="GET" class="flex-1 md:flex-none">
                        <input type="hidden" name="tab" value="list">
                        <button type="submit" :class="activeTab === 'list' ? 'bg-green-50 text-green-700 font-bold border-green-200 shadow-sm' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50 border-transparent'" class="border px-6 py-2 text-sm rounded-lg transition-all whitespace-nowrap w-full flex items-center gap-2 justify-center"><i class="fa-solid fa-list-ul"></i> Daftar Informasi</button>
                    </form>
                    <form action="{{ route('admin.informasi.index') }}" method="GET" class="flex-1 md:flex-none">
                        <input type="hidden" name="tab" value="calendar">
                        <button type="submit" :class="activeTab === 'calendar' ? 'bg-green-50 text-green-700 font-bold border-green-200 shadow-sm' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50 border-transparent'" class="border px-6 py-2 text-sm rounded-lg transition-all whitespace-nowrap w-full flex items-center gap-2 justify-center"><i class="fa-regular fa-calendar-days"></i> Kalender Agenda</button>
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
