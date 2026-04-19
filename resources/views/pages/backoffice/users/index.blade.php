@extends('layouts.backoffice')

@section('title', 'Manajemen User - Panel Administrasi')

@section('content')

{{-- Alpine.js State Manager --}}
<div x-data="{
        {{-- Modal state --}}
        modalOpen: {{ $errors->any() ? 'true' : 'false' }},
        modalMode: 'create',
        editUserData: {},
        formData: {
            name: '',
            email: '',
            nik: '',
            role: '',
            is_active: '1',
        },

        {{-- Drawer state --}}
        drawerOpen: false,
        drawerUser: null,
        drawerLoading: false,

        {{-- Selection state --}}
        selectAll: false,
        selectedUsers: [],

        {{-- Methods --}}
        openCreateModal() {
            this.modalMode = 'create';
            this.editUserData = {};
            this.formData = { name: '', email: '', nik: '', role: '', is_active: '1' };
            this.modalOpen = true;
        },

        openEditModal(userData) {
            this.modalMode = 'edit';
            this.editUserData = userData;
            this.formData = {
                name: userData.name,
                email: userData.email,
                nik: userData.nik || '',
                role: userData.role,
                is_active: userData.is_active ? '1' : '0',
            };
            this.modalOpen = true;
        },

        async openDrawer(userId) {
            this.drawerOpen = true;
            this.drawerLoading = true;
            this.drawerUser = null;
            try {
                const response = await fetch(`{{ url('admin/users') }}/${userId}`);
                if (!response.ok) throw new Error('Failed');
                this.drawerUser = await response.json();
            } catch (e) {
                console.error('Failed to load user detail:', e);
                this.drawerUser = null;
            } finally {
                this.drawerLoading = false;
            }
        },
     }"
     class="space-y-6">

    {{-- Flash Messages --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="bg-green-50 border border-green-200 text-green-800 rounded-2xl p-4 flex items-start gap-3 shadow-sm">
            <i class="fa-solid fa-circle-check text-green-600 mt-0.5"></i>
            <div class="flex-1">
                <p class="text-sm font-semibold">{{ session('success') }}</p>
            </div>
            <button @click="show = false" class="text-green-400 hover:text-green-600 cursor-pointer">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="bg-red-50 border border-red-200 text-red-800 rounded-2xl p-4 flex items-start gap-3 shadow-sm">
            <i class="fa-solid fa-circle-exclamation text-red-600 mt-0.5"></i>
            <div class="flex-1">
                <p class="text-sm font-semibold">{{ session('error') }}</p>
            </div>
            <button @click="show = false" class="text-red-400 hover:text-red-600 cursor-pointer">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    {{-- Validation Errors --}}
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

    {{-- Page Header & Controls --}}
    <section class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Manajemen User</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola akun, hak akses, dan pengaturan keamanan seluruh pengguna sistem.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            {{-- Bulk Action --}}
            <div class="relative" x-data="{ bulkOpen: false }">
                <button @click="selectedUsers.length > 0 ? bulkOpen = !bulkOpen : null"
                    :class="selectedUsers.length > 0
                        ? 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50 cursor-pointer'
                        : 'bg-gray-50 border-gray-200 text-gray-400 cursor-not-allowed'"
                    class="border shadow-sm rounded-xl px-4 py-2.5 text-sm font-semibold transition-all flex items-center gap-2">
                    <span>Aksi Massal</span>
                    <span x-show="selectedUsers.length > 0"
                          x-text="`(${selectedUsers.length})`"
                          class="bg-gray-200 text-gray-800 text-xs px-1.5 py-0.5 rounded-md"></span>
                    <i class="fa-solid fa-chevron-down text-xs ml-1"></i>
                </button>

                <div x-show="bulkOpen" @click.away="bulkOpen = false" x-transition x-cloak
                     class="absolute right-0 mt-2 w-52 bg-white border border-gray-100 shadow-xl rounded-2xl py-2 z-50">
                    {{-- Deactivate --}}
                    <form action="{{ route('admin.users.bulk-action') }}" method="POST"
                          onsubmit="return confirm('Nonaktifkan user yang dipilih?')">
                        @csrf
                        <input type="hidden" name="action" value="deactivate">
                        <template x-for="id in selectedUsers" :key="id">
                            <input type="hidden" name="user_ids[]" :value="id">
                        </template>
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer">
                            <i class="fa-solid fa-user-lock w-4 mr-2 text-center text-amber-500"></i> Nonaktifkan User
                        </button>
                    </form>

                    {{-- Delete --}}
                    <form action="{{ route('admin.users.bulk-action') }}" method="POST"
                          onsubmit="return confirm('Hapus permanen user yang dipilih? Tindakan ini tidak dapat dibatalkan.')">
                        @csrf
                        <input type="hidden" name="action" value="delete">
                        <template x-for="id in selectedUsers" :key="id">
                            <input type="hidden" name="user_ids[]" :value="id">
                        </template>
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 cursor-pointer">
                            <i class="fa-solid fa-trash w-4 mr-2 text-center"></i> Hapus Permanen
                        </button>
                    </form>
                </div>
            </div>

            {{-- Add User Button --}}
            <button @click="openCreateModal()"
                class="bg-green-700 hover:bg-green-800 text-white shadow-md hover:shadow-lg rounded-xl px-5 py-2.5 text-sm font-bold transition-all flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-user-plus"></i>
                <span>Tambah User Baru</span>
            </button>
        </div>
    </section>

    {{-- Filters --}}
    @include('pages.backoffice.users._filters')

    {{-- User Table --}}
    @include('pages.backoffice.users._user-table')

    {{-- Modal (Create / Edit) --}}
    @include('pages.backoffice.users._user-form-modal')

    {{-- Detail Drawer --}}
    @include('pages.backoffice.users._user-detail-drawer')

</div>

@endsection
