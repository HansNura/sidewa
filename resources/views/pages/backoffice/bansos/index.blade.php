@extends('layouts.backoffice')

@section('title', 'Bantuan Sosial - Panel Administrasi')

@section('content')

<div x-data="{
        addModalOpen: {{ $errors->any() ? 'true' : 'false' }},
        detailDrawerOpen: false,
        detail: null,
        selectedPenduduk: null,
        searchResults: [],

        async openDetail(id) {
            try {
                const res = await fetch(`{{ url('admin/bansos') }}/${id}`);
                if (!res.ok) throw new Error('Failed');
                this.detail = await res.json();
                this.detailDrawerOpen = true;
            } catch (e) {
                console.error('Failed to load detail:', e);
            }
        },

        async searchPenduduk(q) {
            if (q.length < 2) { this.searchResults = []; return; }
            try {
                const res = await fetch(`{{ route('admin.bansos.search-penduduk') }}?q=${encodeURIComponent(q)}`);
                this.searchResults = await res.json();
            } catch (e) { this.searchResults = []; }
        },

        selectPenduduk(item) {
            this.selectedPenduduk = item;
            this.searchResults = [];
        }
     }"
     class="space-y-6">

    {{-- Flash Messages --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="bg-green-50 border border-green-200 text-green-800 rounded-2xl p-4 flex items-start gap-3 shadow-sm">
            <i class="fa-solid fa-circle-check text-green-600 mt-0.5"></i>
            <div class="flex-1"><p class="text-sm font-semibold">{{ session('success') }}</p></div>
            <button @click="show = false" class="text-green-400 hover:text-green-600 cursor-pointer"><i class="fa-solid fa-xmark"></i></button>
        </div>
    @endif

    {{-- Page Header --}}
    @include('pages.backoffice.bansos._header')

    {{-- Audit Alert --}}
    @include('pages.backoffice.bansos._audit-alert')

    {{-- Program Cards --}}
    @include('pages.backoffice.bansos._programs')

    {{-- Filter Bar --}}
    @include('pages.backoffice.bansos._filter')

    {{-- Recipients Table --}}
    @include('pages.backoffice.bansos._table')

    {{-- Modals & Drawers --}}
    @include('pages.backoffice.bansos._form-modal')
    @include('pages.backoffice.bansos._detail-drawer')

</div>

@endsection
