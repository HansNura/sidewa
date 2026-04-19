@extends('layouts.backoffice')

@section('title', 'Konfigurasi Sistem - Panel Administrasi')

@section('content')

{{-- Alpine.js State Manager --}}
<div x-data="{
        activeTab: '{{ request('tab', 'umum') }}',
        config: {
            appName: '{{ $config['app_name'] }}',
            timezone: '{{ $config['timezone'] }}',
            dateFormat: '{{ $config['date_format'] }}',
            language: '{{ $config['language'] }}',
            passwordPolicy: '{{ $config['password_policy'] }}',
            sessionTimeout: {{ $config['session_timeout'] }},
            twoFactor: {{ $config['two_factor'] ? 'true' : 'false' }},
            notifInternal: {{ $config['notif_internal'] ? 'true' : 'false' }},
            notifEmail: {{ $config['notif_email'] ? 'true' : 'false' }},
            notifWhatsapp: {{ $config['notif_whatsapp'] ? 'true' : 'false' }},
            waProvider: '{{ $config['wa_provider'] }}',
            dukcapilUrl: '{{ $config['dukcapil_url'] }}',
            dukcapilApiKey: '{{ $config['dukcapil_api_key'] }}',
            showApiKey: false,
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
            <div class="flex-1">
                <p class="text-sm font-semibold">{{ session('success') }}</p>
            </div>
            <button @click="show = false" class="text-green-400 hover:text-green-600 cursor-pointer">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-2xl p-4 shadow-sm">
            <div class="flex items-center gap-2 mb-2">
                <i class="fa-solid fa-triangle-exclamation text-red-500"></i>
                <p class="text-sm font-bold">Terdapat kesalahan:</p>
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
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Konfigurasi Sistem</h1>
            <p class="text-sm text-gray-500 mt-1">Pengaturan teknis tingkat lanjut mencakup keamanan, integrasi, dan perilaku aplikasi.</p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <button type="submit" form="systemConfigForm"
                class="bg-green-700 hover:bg-green-800 text-white shadow-md hover:shadow-lg rounded-xl px-6 py-2.5 text-sm font-bold transition-all flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-save"></i>
                <span>Simpan Konfigurasi</span>
            </button>
        </div>
    </section>

    {{-- Main Form wrapping all tabs --}}
    <form id="systemConfigForm"
          action="{{ route('admin.system-config.update') }}"
          method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="active_tab" :value="activeTab">

        <div class="flex flex-col md:flex-row gap-6">

            {{-- Tab Navigation (Left) --}}
            <aside class="w-full md:w-64 shrink-0">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <nav class="flex flex-col p-2 space-y-1">
                        @php
                            $tabs = [
                                ['key' => 'umum',        'icon' => 'fa-solid fa-sliders',             'label' => 'Pengaturan Umum'],
                                ['key' => 'keamanan',    'icon' => 'fa-solid fa-shield-halved',       'label' => 'Keamanan & Sesi'],
                                ['key' => 'notifikasi',  'icon' => 'fa-solid fa-bell',                'label' => 'Pengaturan Notifikasi'],
                                ['key' => 'integrasi',   'icon' => 'fa-solid fa-link',                'label' => 'Integrasi & API'],
                                ['key' => 'log',         'icon' => 'fa-solid fa-clock-rotate-left',   'label' => 'Audit & System Log'],
                            ];
                        @endphp
                        @foreach ($tabs as $tab)
                            <button type="button" @click="activeTab = '{{ $tab['key'] }}'"
                                :class="activeTab === '{{ $tab['key'] }}'
                                    ? 'bg-green-50 text-green-700 font-bold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium'"
                                class="flex items-center gap-3 w-full px-4 py-3 rounded-xl transition-colors text-left text-sm cursor-pointer">
                                <i class="{{ $tab['icon'] }} w-5 text-center"
                                   :class="activeTab === '{{ $tab['key'] }}' ? 'text-green-600' : 'text-gray-400'"></i>
                                {{ $tab['label'] }}
                            </button>
                        @endforeach
                    </nav>
                </div>
            </aside>

            {{-- Tab Content (Right) --}}
            <div class="flex-1 bg-white rounded-2xl shadow-sm border border-gray-100 p-6 min-h-[400px]">

                {{-- TAB: Pengaturan Umum --}}
                @include('pages.backoffice.system-config._tab-umum')

                {{-- TAB: Keamanan & Sesi --}}
                @include('pages.backoffice.system-config._tab-keamanan')

                {{-- TAB: Notifikasi --}}
                @include('pages.backoffice.system-config._tab-notifikasi')

                {{-- TAB: Integrasi & API --}}
                @include('pages.backoffice.system-config._tab-integrasi')

                {{-- TAB: Audit Log (outside form since it's read-only) --}}
                @include('pages.backoffice.system-config._tab-log')

            </div>
        </div>
    </form>

</div>

@endsection
