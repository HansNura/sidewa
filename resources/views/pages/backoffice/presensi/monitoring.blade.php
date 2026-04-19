@extends('layouts.backoffice')

@section('title', 'Monitoring Presensi Pegawai - Panel Administrasi')

@section('content')
<div class="max-w-7xl mx-auto space-y-6" x-data="{ 
        detailDrawerOpen: false,
        koreksiModalOpen: false,
        selectAll: false, 
        selectedRows: [],
        
        // Helper untuk filter
        today: '{{ $tanggal }}',
        activeUserId: null,
        drawerLoading: false,
        drawerData: null,

        init() {
            this.$watch('today', value => {
                if(value) {
                    window.location.href = `{{ route('admin.presensi.monitoring') }}?tanggal=${value}`;
                }
            });
        },

        async openDrawer(userId) {
            this.activeUserId = userId;
            this.drawerLoading = true;
            this.detailDrawerOpen = true;
            
            try {
                const res = await fetch(`{{ url('admin/presensi') }}/${userId}/info?tanggal=${this.today}`, {
                    headers: { 'Accept': 'application/json' }
                });
                this.drawerData = await res.json();
            } catch(e) {
                console.error(e);
            }
            this.drawerLoading = false;
        },

        openKoreksi(userId = null) {
            this.detailDrawerOpen = false;
            // Optionally set User ID to the form
            if (userId) {
                this.$nextTick(() => {
                    const select = document.getElementById('koreksi_user_id');
                    if (select) select.value = userId;
                });
            }
            this.koreksiModalOpen = true;
        }
    }">

    {{-- Error/Success Alerts --}}
    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-xl bg-green-50 border border-green-200">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="p-4 mb-4 text-sm text-red-800 rounded-xl bg-red-50 border border-red-200">
            <ul class="list-disc pl-4 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Header --}}
    <section class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Monitoring Presensi</h1>
            <p class="text-sm text-gray-500 mt-1">Pantau kehadiran, keterlambatan, dan aktivitas aparatur desa secara *real-time*.</p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('admin.presensi.monitoring') }}"
                class="bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 shadow-sm rounded-xl px-4 py-2.5 text-sm font-bold transition-all flex items-center gap-2">
                <i class="fa-solid fa-rotate text-gray-400"></i>
                <span class="hidden sm:inline">Refresh Data</span>
            </a>
            <button @click="openKoreksi()"
                class="bg-green-700 hover:bg-green-800 text-white shadow-md rounded-xl px-5 py-2.5 text-sm font-bold transition-all flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-user-pen"></i>
                <span>Koreksi / Input Manual</span>
            </button>
        </div>
    </section>

    {{-- Stats Cards --}}
    @include('pages.backoffice.presensi._stats')

    {{-- Filter --}}
    @include('pages.backoffice.presensi._filter')

    {{-- Queue Table --}}
    @include('pages.backoffice.presensi._table')

    {{-- Drawer --}}
    @include('pages.backoffice.presensi._drawer')

    {{-- Modal Koreksi --}}
    @include('pages.backoffice.presensi._modal')

</div>
@endsection

@push('styles')
<style>
    /* Custom Checkbox */
    .custom-checkbox {
        appearance: none;
        background-color: #fff;
        margin: 0;
        font: inherit;
        color: currentColor;
        width: 1.15em;
        height: 1.15em;
        border: 2px solid #cbd5e1;
        border-radius: 0.25em;
        display: grid;
        place-content: center;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }

    .custom-checkbox::before {
        content: "";
        width: 0.65em;
        height: 0.65em;
        transform: scale(0);
        transition: 120ms transform ease-in-out;
        box-shadow: inset 1em 1em white;
        background-color: transform;
        transform-origin: center;
        clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%);
    }

    .custom-checkbox:checked {
        background-color: #16a34a;
        border-color: #16a34a;
    }

    .custom-checkbox:checked::before {
        transform: scale(1);
    }

    /* Timeline Tracking Styles */
    .timeline-track::before {
        content: '';
        position: absolute;
        left: 11px;
        top: 24px;
        bottom: -10px;
        width: 2px;
        background-color: #e2e8f0;
        z-index: 0;
    }

    .timeline-track:last-child::before {
        display: none;
    }
</style>
@endpush
