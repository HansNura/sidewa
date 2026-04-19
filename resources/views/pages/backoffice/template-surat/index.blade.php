@extends('layouts.backoffice')

@section('title', 'Template Surat - Panel Administrasi')

@section('content')

<div x-data="{
        previewModalOpen: false,
        previewData: null,
        isLoadingPreview: false,

        async openPreview(id) {
            this.isLoadingPreview = true;
            this.previewModalOpen = true;
            try {
                const res = await fetch(`{{ url('admin/template-surat') }}/${id}`, {
                    headers: { 'Accept': 'application/json' }
                });
                this.previewData = await res.json();
            } catch (e) {
                this.previewData = null;
            }
            this.isLoadingPreview = false;
        },

        async toggleStatus(id, el) {
            try {
                const res = await fetch(`{{ url('admin/template-surat') }}/${id}/toggle`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                });
                const data = await res.json();
                if (!data.success) el.checked = !el.checked;
            } catch (e) {
                el.checked = !el.checked;
            }
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

    {{-- Header --}}
    @include('pages.backoffice.template-surat._header')

    {{-- Filter & Search --}}
    @include('pages.backoffice.template-surat._filter')

    {{-- Template Table --}}
    @include('pages.backoffice.template-surat._table')

    {{-- Preview Modal --}}
    @include('pages.backoffice.template-surat._preview-modal')

</div>

@endsection
