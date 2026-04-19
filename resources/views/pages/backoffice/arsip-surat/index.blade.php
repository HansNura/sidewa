@extends('layouts.backoffice')

@section('title', 'Arsip Surat - Panel Administrasi')

@section('content')

<div x-data="{
        advFilterOpen: false,
        detailDrawerOpen: false,
        detailData: null,
        isLoadingDetail: false,
        selectAll: false,
        selectedRows: [],
        pdfZoom: 100,

        async openDetail(id) {
            this.isLoadingDetail = true;
            this.detailDrawerOpen = true;
            try {
                const res = await fetch(`{{ url('admin/arsip-surat') }}/${id}`, {
                    headers: { 'Accept': 'application/json' }
                });
                this.detailData = await res.json();
            } catch (e) {
                this.detailData = null;
            }
            this.isLoadingDetail = false;
        },

        closeDrawer() {
            this.detailDrawerOpen = false;
            this.pdfZoom = 100;
            this.detailData = null;
        },

        toggleSelectAll() {
            if (this.selectAll) {
                this.selectedRows = {{ Js::from($arsip->pluck('id')->toArray()) }};
            } else {
                this.selectedRows = [];
            }
        },

        async bulkDelete() {
            if (!confirm('Hapus ' + this.selectedRows.length + ' arsip surat yang dipilih?')) return;
            try {
                const res = await fetch('{{ route('admin.arsip-surat.bulk-destroy') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ ids: this.selectedRows })
                });
                const data = await res.json();
                if (data.success) {
                    window.location.reload();
                }
            } catch (e) {
                alert('Gagal menghapus arsip.');
            }
        }
     }"
     class="space-y-6">

    {{-- Header --}}
    @include('pages.backoffice.arsip-surat._header')

    {{-- Filter & Search --}}
    @include('pages.backoffice.arsip-surat._filter')

    {{-- Bulk Action Bar --}}
    <div x-show="selectedRows.length > 0" x-transition.opacity x-cloak
        class="bg-green-50 border border-green-200 rounded-xl p-3 flex flex-col sm:flex-row justify-between items-center gap-3 shadow-sm">
        <div class="flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-green-600 text-white flex items-center justify-center text-xs font-bold"
                  x-text="selectedRows.length"></span>
            <span class="text-sm font-bold text-green-800">Surat Terpilih</span>
        </div>
        <div class="flex gap-2">
            <button @click="bulkDelete()"
                class="px-3 py-1.5 bg-white border border-red-200 text-red-600 text-xs font-bold rounded-lg hover:bg-red-50 transition-colors shadow-sm cursor-pointer">
                <i class="fa-solid fa-trash mr-1"></i> Hapus Arsip
            </button>
        </div>
    </div>

    {{-- Table --}}
    @include('pages.backoffice.arsip-surat._table')

    {{-- Detail Drawer --}}
    @include('pages.backoffice.arsip-surat._detail-drawer')

</div>

@endsection

@push('styles')
<style>
    .custom-checkbox {
        appearance: none;
        background-color: #fff;
        width: 1.15em;
        height: 1.15em;
        border: 2px solid #cbd5e1;
        border-radius: 0.25em;
        display: grid;
        place-content: center;
        cursor: pointer;
        transition: all 0.15s;
    }
    .custom-checkbox::before {
        content: "";
        width: 0.65em;
        height: 0.65em;
        transform: scale(0);
        transition: 120ms transform ease-in-out;
        box-shadow: inset 1em 1em white;
        clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%);
    }
    .custom-checkbox:checked {
        background-color: #16a34a;
        border-color: #16a34a;
    }
    .custom-checkbox:checked::before {
        transform: scale(1);
    }
    .pdf-paper {
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.2), 0 8px 10px -6px rgba(0,0,0,0.1);
    }
    .timeline-track { position: relative; }
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
    .timeline-track:last-child::before { display: none; }
</style>
@endpush
