@extends('layouts.backoffice')

@section('title', 'Data Penduduk - Panel Administrasi')

@section('content')

{{-- Alpine.js State --}}
<div x-data="{
        addModalOpen: {{ $errors->any() ? 'true' : 'false' }},
        detailDrawerOpen: false,
        advFilterOpen: {{ request()->hasAny(['jenis_kelamin', 'perkawinan', 'usia', 'status']) ? 'true' : 'false' }},
        selectedRows: [],
        selectAll: false,
        editMode: false,
        editId: null,
        detail: null,

        toggleSelectAll() {
            if (this.selectAll) {
                this.selectedRows = [...document.querySelectorAll('[data-row-id]')].map(el => el.dataset.rowId);
            } else {
                this.selectedRows = [];
            }
        },

        async openDetail(id) {
            try {
                const response = await fetch(`{{ url('admin/penduduk') }}/${id}`);
                if (!response.ok) throw new Error('Failed');
                this.detail = await response.json();
                this.detailDrawerOpen = true;
            } catch (e) {
                console.error('Failed to load detail:', e);
            }
        },

        openAddModal() {
            this.editMode = false;
            this.editId = null;
            this.addModalOpen = true;
        },
     }"
     class="space-y-6">

    {{-- Flash Messages --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="bg-green-50 border border-green-200 text-green-800 rounded-2xl p-4 flex items-start gap-3 shadow-sm">
            <i class="fa-solid fa-circle-check text-green-600 mt-0.5"></i>
            <div class="flex-1"><p class="text-sm font-semibold">{{ session('success') }}</p></div>
            <button @click="show = false" class="text-green-400 hover:text-green-600 cursor-pointer"><i class="fa-solid fa-xmark"></i></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-2xl p-4 shadow-sm">
            <div class="flex items-center gap-2 mb-2">
                <i class="fa-solid fa-triangle-exclamation text-red-500"></i>
                <p class="text-sm font-bold">Terdapat kesalahan pada formulir:</p>
            </div>
            <ul class="list-disc list-inside text-sm space-y-1 ml-6">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Page Header --}}
    <section class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Data Penduduk</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola data individu warga desa (Master Data Kependudukan).</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <button @click="openAddModal()"
                class="bg-green-700 hover:bg-green-800 text-white shadow-md rounded-xl px-5 py-2.5 text-sm font-bold transition-all flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah Warga</span>
            </button>
        </div>
    </section>

    {{-- Statistics Cards --}}
    @include('pages.backoffice.penduduk._stats')

    {{-- Search & Filters --}}
    @include('pages.backoffice.penduduk._filters')

    {{-- Data Table --}}
    @include('pages.backoffice.penduduk._table')

    {{-- Add/Edit Modal --}}
    @include('pages.backoffice.penduduk._form-modal')

    {{-- Detail Drawer --}}
    @include('pages.backoffice.penduduk._detail-drawer')

</div>

@endsection
