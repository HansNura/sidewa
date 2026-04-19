@extends('layouts.backoffice')

@section('title', 'Role & Hak Akses - Panel Administrasi')

@section('content')

{{-- Alpine.js State Manager --}}
<div x-data="{
        {{-- Modal state --}}
        modalOpen: {{ $errors->any() ? 'true' : 'false' }},
        modalMode: 'create',
        editRoleId: null,

        formData: {
            display_name: '',
            slug: '',
            description: '',
            icon: 'fa-solid fa-shield-halved',
            color: 'gray',
        },

        {{-- Permission data: { module_id: { can_view: bool, ... } } --}}
        permissionData: {},

        {{-- Methods --}}
        openCreateModal() {
            this.modalMode = 'create';
            this.editRoleId = null;
            this.formData = {
                display_name: '',
                slug: '',
                description: '',
                icon: 'fa-solid fa-shield-halved',
                color: 'gray',
            };
            this.permissionData = {};
            this.modalOpen = true;
        },

        async openEditModal(roleId) {
            this.modalMode = 'edit';
            this.editRoleId = roleId;
            try {
                const response = await fetch(`{{ url('admin/roles') }}/${roleId}`);
                if (!response.ok) throw new Error('Failed');
                const data = await response.json();
                this.formData = {
                    display_name: data.display_name,
                    slug: data.slug,
                    description: data.description || '',
                    icon: data.icon || 'fa-solid fa-shield-halved',
                    color: data.color || 'gray',
                };

                // Build permission data from API response
                this.permissionData = {};
                if (data.permissions) {
                    for (const [moduleId, perms] of Object.entries(data.permissions)) {
                        this.permissionData[moduleId] = {
                            can_view: perms.can_view || false,
                            can_create: perms.can_create || false,
                            can_edit: perms.can_edit || false,
                            can_delete: perms.can_delete || false,
                        };
                    }
                }
                this.modalOpen = true;
            } catch (e) {
                console.error('Failed to load role:', e);
            }
        },

        toggleAllPermissions() {
            const modules = @json($modules->pluck('id'));
            const abilities = ['can_view', 'can_create', 'can_edit', 'can_delete'];

            // Check if all are already checked
            let allChecked = true;
            for (const mid of modules) {
                for (const ab of abilities) {
                    if (!this.permissionData[mid] || !this.permissionData[mid][ab]) {
                        allChecked = false;
                        break;
                    }
                }
                if (!allChecked) break;
            }

            // Toggle
            for (const mid of modules) {
                if (!this.permissionData[mid]) this.permissionData[mid] = {};
                for (const ab of abilities) {
                    this.permissionData[mid][ab] = !allChecked;
                }
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
    <section class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Role & Hak Akses</h1>
            <p class="text-sm text-gray-500 mt-1">Mengatur peran (<em>role</em>) dan membatasi izin akses (<em>permission</em>) setiap modul sistem.</p>
        </div>

        <div class="flex items-center gap-3">
            <button @click="openCreateModal()"
                class="bg-green-700 hover:bg-green-800 text-white shadow-md hover:shadow-lg rounded-xl px-5 py-2.5 text-sm font-bold transition-all flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-shield-halved"></i>
                <span>Tambah Role</span>
            </button>
        </div>
    </section>

    {{-- Role Cards --}}
    @include('pages.backoffice.roles._role-cards')

    {{-- Permission Matrix Table --}}
    @include('pages.backoffice.roles._permission-matrix')

    {{-- Role Form Modal --}}
    @include('pages.backoffice.roles._role-form-modal')

</div>

@endsection
