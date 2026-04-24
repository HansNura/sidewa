@extends('layouts.backoffice')

@section('title', 'Verifikasi & TTE Surat - Panel Administrasi')

@section('content')
@php $verifikasiBaseUrl = url(auth()->user()->routePrefix() . '/verifikasi-surat'); @endphp
<div x-data="{
        verifyWorkspaceOpen: false,
        pinModalOpen: false,
        rejectModalOpen: false,
        revisiModalOpen: false,
        isSigning: false,
        showToast: false,
        toastMessage: '',
        toastType: 'success',
        pdfZoom: 100,

        // Current surat being reviewed
        currentSurat: null,
        isLoadingDetail: false,

        // Checklist Validation
        check1: false,
        check2: false,
        check3: false,
        catatanRevisi: '',
        alasanTolak: '',
        pinInput: '',

        get isValidated() {
            return this.check1 && this.check2 && this.check3;
        },

        // ── Open verification workspace ────────
        async openWorkspace(id) {
            this.isLoadingDetail = true;
            this.verifyWorkspaceOpen = true;
            this.resetChecklist();
            try {
                const res = await fetch(`{{ $verifikasiBaseUrl }}/${id}`, {
                    headers: { 'Accept': 'application/json' }
                });
                this.currentSurat = await res.json();
            } catch (e) {
                this.currentSurat = null;
            }
            this.isLoadingDetail = false;
        },

        closeWorkspace() {
            this.verifyWorkspaceOpen = false;
            this.pdfZoom = 100;
            this.currentSurat = null;
            this.resetChecklist();
        },

        resetChecklist() {
            this.check1 = false;
            this.check2 = false;
            this.check3 = false;
            this.catatanRevisi = '';
            this.alasanTolak = '';
            this.pinInput = '';
        },

        // ── TTE Process ────────────────────────
        async processTTE() {
            if (!this.pinInput || this.pinInput.length !== 6) {
                this.showNotification('PIN harus 6 digit.', 'error');
                return;
            }
            this.isSigning = true;
            try {
                const res = await fetch(`{{ $verifikasiBaseUrl }}/${this.currentSurat.id}/approve`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ pin: this.pinInput })
                });
                const data = await res.json();
                if (data.success) {
                    this.pinModalOpen = false;
                    this.closeWorkspace();
                    this.showNotification(data.message, 'success');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    this.showNotification(data.message, 'error');
                }
            } catch (e) {
                this.showNotification('Gagal memproses TTE.', 'error');
            }
            this.isSigning = false;
        },

        // ── Reject ─────────────────────────────
        async processReject() {
            if (!this.alasanTolak.trim()) {
                this.showNotification('Alasan penolakan wajib diisi.', 'error');
                return;
            }
            try {
                const res = await fetch(`{{ $verifikasiBaseUrl }}/${this.currentSurat.id}/reject`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ alasan_tolak: this.alasanTolak })
                });
                const data = await res.json();
                if (data.success) {
                    this.rejectModalOpen = false;
                    this.closeWorkspace();
                    this.showNotification(data.message, 'success');
                    setTimeout(() => window.location.reload(), 1500);
                }
            } catch (e) {
                this.showNotification('Gagal menolak surat.', 'error');
            }
        },

        // ── Revisi ─────────────────────────────
        async processRevisi() {
            if (!this.catatanRevisi.trim()) {
                this.showNotification('Catatan revisi wajib diisi.', 'error');
                return;
            }
            try {
                const res = await fetch(`{{ $verifikasiBaseUrl }}/${this.currentSurat.id}/revisi`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ catatan: this.catatanRevisi })
                });
                const data = await res.json();
                if (data.success) {
                    this.revisiModalOpen = false;
                    this.closeWorkspace();
                    this.showNotification(data.message, 'success');
                    setTimeout(() => window.location.reload(), 1500);
                }
            } catch (e) {
                this.showNotification('Gagal mengembalikan surat.', 'error');
            }
        },

        // ── Verify (operator advances to TTE) ──
        async processVerify(id) {
            try {
                const res = await fetch(`{{ $verifikasiBaseUrl }}/${id}/verify`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                });
                const data = await res.json();
                if (data.success) {
                    this.showNotification(data.message, 'success');
                    setTimeout(() => window.location.reload(), 1500);
                }
            } catch (e) {
                this.showNotification('Gagal memverifikasi.', 'error');
            }
        },

        showNotification(msg, type = 'success') {
            this.toastMessage = msg;
            this.toastType = type;
            this.showToast = true;
            setTimeout(() => this.showToast = false, 4000);
        }
     }"
     class="space-y-6">

    {{-- Header --}}
    @include('pages.backoffice.verifikasi-surat._header')

    {{-- Stats Cards --}}
    @include('pages.backoffice.verifikasi-surat._stats')

    {{-- Filter --}}
    @include('pages.backoffice.verifikasi-surat._filter')

    {{-- Queue Table --}}
    @include('pages.backoffice.verifikasi-surat._table')

    {{-- Full-Screen Verification Workspace --}}
    @include('pages.backoffice.verifikasi-surat._workspace')

    {{-- PIN Modal --}}
    @include('pages.backoffice.verifikasi-surat._pin-modal')

    {{-- Reject Modal --}}
    @include('pages.backoffice.verifikasi-surat._reject-modal')

    {{-- Revisi Modal --}}
    @include('pages.backoffice.verifikasi-surat._revisi-modal')

    {{-- Toast --}}
    @include('pages.backoffice.verifikasi-surat._toast')

</div>

@endsection

@push('styles')
<style>
    .print-paper {
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.15), 0 8px 10px -6px rgba(0,0,0,0.1);
    }
    .check-valid {
        appearance: none;
        width: 1.25rem;
        height: 1.25rem;
        border: 2px solid #cbd5e1;
        border-radius: 0.375rem;
        display: grid;
        place-content: center;
        cursor: pointer;
        transition: all 0.2s;
        background-color: white;
    }
    .check-valid::before {
        content: "\f00c";
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        color: white;
        font-size: 0.75rem;
        transform: scale(0);
        transition: transform 0.2s;
    }
    .check-valid:checked {
        background-color: #16a34a;
        border-color: #16a34a;
    }
    .check-valid:checked::before {
        transform: scale(1);
    }
</style>
@endpush
