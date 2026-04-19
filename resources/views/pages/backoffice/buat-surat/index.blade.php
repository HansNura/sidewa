@extends('layouts.backoffice')

@section('title', 'Buat Surat Baru - Panel Administrasi')

@section('content')

{{-- Alpine.js Wizard State --}}
<div x-data="{
        step: 1,
        maxStep: 5,
        isSubmitting: false,
        showSuccessModal: false,
        draftModalOpen: false,
        successData: {},
        validationErrors: [],

        // ── Template Selection (Step 1) ──────────────────
        selectedTemplate: null,
        templateName: '',
        templateKey: '',
        searchTemplate: '',

        // ── Penduduk Search (Step 2) ─────────────────────
        searchNik: '',
        isSearching: false,
        searchResults: [],
        selectedResident: null,

        // ── Form Data (Step 3) ───────────────────────────
        formData: {
            keperluan: '',
            keteranganLain: '',
            berlakuHingga: '1 Bulan',
            namaUsaha: ''
        },

        // ── Submit Action (Step 5) ───────────────────────
        submitAction: 'proses',

        // ── Draft Data ──────────────────────────────────
        drafts: [],
        isLoadingDrafts: false,

        // ── Methods ─────────────────────────────────────
        selectTemplate(index, key, label) {
            this.selectedTemplate = index;
            this.templateKey = key;
            this.templateName = label;
        },

        async findResident() {
            if (this.searchNik.length < 2) return;
            this.isSearching = true;
            this.searchResults = [];
            try {
                const res = await fetch(`{{ route('admin.layanan-surat.search-penduduk') }}?q=${encodeURIComponent(this.searchNik)}`);
                this.searchResults = await res.json();
            } catch (e) {
                this.searchResults = [];
            }
            this.isSearching = false;
        },

        pickResident(r) {
            this.selectedResident = r;
            this.searchResults = [];
            this.searchNik = r.nik;
        },

        async loadDrafts() {
            this.isLoadingDrafts = true;
            try {
                const res = await fetch(`{{ route('admin.layanan-surat.drafts') }}`);
                this.drafts = await res.json();
            } catch (e) {
                this.drafts = [];
            }
            this.isLoadingDrafts = false;
        },

        async loadDraft(id) {
            try {
                const res = await fetch(`{{ url('admin/layanan-surat') }}/${id}/edit-wizard`);
                const data = await res.json();
                if (data.error) { alert(data.error); return; }

                this.templateKey = data.jenis_surat;
                this.templateName = data.jenis_label;
                this.selectedTemplate = data.jenis_surat;
                this.formData.keperluan = data.keperluan || '';
                this.formData.berlakuHingga = data.berlaku_hingga || '1 Bulan';
                this.formData.namaUsaha = data.nama_usaha || '';
                this.formData.keteranganLain = data.catatan || '';

                if (data.penduduk) {
                    this.selectedResident = data.penduduk;
                    this.searchNik = data.penduduk.nik;
                }
                this.step = 3;
                this.draftModalOpen = false;
            } catch (e) {
                alert('Gagal memuat draft.');
            }
        },

        async saveDraftQuick() {
            if (!this.selectedTemplate || !this.selectedResident) {
                alert('Pilih template dan data penduduk terlebih dahulu untuk menyimpan draft.');
                return;
            }
            this.submitAction = 'draft';
            await this.submitSurat();
        },

        async submitSurat() {
            this.isSubmitting = true;
            this.validationErrors = [];

            const body = {
                penduduk_id: this.selectedResident?.id,
                jenis_surat: this.templateKey,
                keperluan: this.formData.keperluan,
                berlaku_hingga: this.formData.berlakuHingga,
                nama_usaha: this.formData.namaUsaha || null,
                keterangan_lain: this.formData.keteranganLain || null,
                submit_action: this.submitAction,
            };

            try {
                const res = await fetch(`{{ route('admin.layanan-surat.store-wizard') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(body)
                });

                if (res.status === 422) {
                    const err = await res.json();
                    this.validationErrors = Object.values(err.errors || {}).flat();
                    this.isSubmitting = false;
                    return;
                }

                const data = await res.json();
                if (data.success) {
                    this.successData = data;
                    this.showSuccessModal = true;
                }
            } catch (e) {
                this.validationErrors = ['Terjadi kesalahan jaringan. Silakan coba lagi.'];
            }
            this.isSubmitting = false;
        },

        canProceed() {
            if (this.step === 1) return this.selectedTemplate !== null;
            if (this.step === 2) return this.selectedResident !== null;
            if (this.step === 3) return this.formData.keperluan.trim().length > 0;
            return true;
        }
     }"
     class="flex flex-col h-full -m-4 sm:-m-6 lg:-m-8"
     style="min-height: calc(100vh - 4rem);">

    {{-- Sticky Wizard Header & Stepper --}}
    @include('pages.backoffice.buat-surat._stepper')

    {{-- Wizard Steps Content (Scrollable) --}}
    <div class="flex-1 overflow-y-auto custom-scrollbar p-4 sm:p-6 lg:p-8">
        <div class="max-w-5xl mx-auto">

            {{-- Validation Errors --}}
            <template x-if="validationErrors.length > 0">
                <div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-6">
                    <div class="flex items-start gap-3">
                        <i class="fa-solid fa-circle-exclamation text-red-500 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-bold text-red-800 mb-1">Terdapat kesalahan:</p>
                            <ul class="text-sm text-red-700 list-disc pl-4 space-y-0.5">
                                <template x-for="err in validationErrors" :key="err">
                                    <li x-text="err"></li>
                                </template>
                            </ul>
                        </div>
                    </div>
                </div>
            </template>

            @include('pages.backoffice.buat-surat._step1-template')
            @include('pages.backoffice.buat-surat._step2-penduduk')
            @include('pages.backoffice.buat-surat._step3-data')
            @include('pages.backoffice.buat-surat._step4-preview')
            @include('pages.backoffice.buat-surat._step5-submit')

        </div>
    </div>

    {{-- Sticky Footer Navigation --}}
    <div class="bg-white border-t border-gray-200 px-4 sm:px-6 lg:px-8 py-4 shrink-0 z-10 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
        <div class="max-w-5xl mx-auto flex justify-between items-center">
            <button @click="step--" x-show="step > 1"
                class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition-colors flex items-center gap-2 shadow-sm cursor-pointer">
                <i class="fa-solid fa-arrow-left"></i> Sebelumnya
            </button>
            <div x-show="step === 1"></div>

            {{-- Next Button (Steps 1-4) --}}
            <button @click="step++" x-show="step < maxStep"
                :disabled="!canProceed()"
                class="px-8 py-2.5 rounded-xl text-sm font-bold text-white bg-green-700 hover:bg-green-800 disabled:opacity-50 disabled:cursor-not-allowed shadow-md transition-all flex items-center gap-2 cursor-pointer">
                Selanjutnya <i class="fa-solid fa-arrow-right"></i>
            </button>

            {{-- Final Submit (Step 5) --}}
            <button @click="submitSurat()" x-show="step === maxStep" :disabled="isSubmitting"
                class="px-8 py-3 rounded-xl text-sm font-extrabold text-white bg-green-600 hover:bg-green-700 shadow-lg hover:shadow-xl transition-all flex items-center gap-2 cursor-pointer disabled:opacity-60">
                <i class="fa-solid fa-spinner fa-spin" x-show="isSubmitting" x-cloak></i>
                <span x-text="isSubmitting ? 'Memproses...' : 'Selesaikan Pembuatan Surat'"></span>
            </button>
        </div>
    </div>

    {{-- Modals --}}
    @include('pages.backoffice.buat-surat._modal-success')
    @include('pages.backoffice.buat-surat._modal-draft')

</div>

@endsection

@push('styles')
<style>
    /* Letter preview print paper shadow */
    .print-paper {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
    }
    /* Inline edit focus style */
    [contenteditable="true"]:focus {
        outline: 2px dashed #16a34a;
        background-color: #fef08a !important;
    }
</style>
@endpush
