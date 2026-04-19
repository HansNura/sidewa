@extends('layouts.backoffice')

@section('title', 'Data Keluarga (KK) - Panel Administrasi')

@section('content')

{{-- Alpine.js State --}}
<div x-data="{
        addModalOpen: {{ $errors->any() ? 'true' : 'false' }},
        detailDrawerOpen: false,
        addMemberModalOpen: false,
        detail: null,
        memberSearchResults: [],
        memberSearchQuery: '',
        selectedMemberId: null,
        selectedMemberRelasi: 'Istri',

        async openDetail(id) {
            try {
                const response = await fetch(`{{ url('admin/kartu-keluarga') }}/${id}`);
                if (!response.ok) throw new Error('Failed');
                this.detail = await response.json();
                this.detailDrawerOpen = true;
            } catch (e) {
                console.error('Failed to load KK detail:', e);
            }
        },

        async searchMember() {
            if (this.memberSearchQuery.length < 2) { this.memberSearchResults = []; return; }
            try {
                const response = await fetch(`{{ route('admin.kartu-keluarga.search-penduduk') }}?q=${encodeURIComponent(this.memberSearchQuery)}`);
                this.memberSearchResults = await response.json();
            } catch (e) {
                console.error('Search failed:', e);
            }
        },

        selectMember(id) {
            this.selectedMemberId = id;
        },

        relationColor(rel) {
            const map = {
                'Kepala Keluarga': 'bg-green-100 text-green-700 border-green-200',
                'Istri': 'bg-blue-100 text-blue-700 border-blue-200',
                'Anak': 'bg-purple-100 text-purple-700 border-purple-200',
            };
            return map[rel] || 'bg-gray-100 text-gray-700 border-gray-200';
        }
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
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Data Keluarga (KK)</h1>
            <p class="text-sm text-gray-500 mt-1">Mengelola data unit keluarga dan hierarki anggota keluarga.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <button @click="addModalOpen = true"
                class="bg-green-700 hover:bg-green-800 text-white shadow-md rounded-xl px-5 py-2.5 text-sm font-bold transition-all flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah KK</span>
            </button>
        </div>
    </section>

    {{-- Sync Alert --}}
    @include('pages.backoffice.kartu-keluarga._sync-alert')

    {{-- Search & Filters --}}
    @include('pages.backoffice.kartu-keluarga._filters')

    {{-- Data Table --}}
    @include('pages.backoffice.kartu-keluarga._table')

    {{-- Add KK Modal --}}
    @include('pages.backoffice.kartu-keluarga._form-modal')

    {{-- Detail Drawer --}}
    @include('pages.backoffice.kartu-keluarga._detail-drawer')

    {{-- Add Member Modal --}}
    @include('pages.backoffice.kartu-keluarga._add-member-modal')

</div>

@endsection
