{{--
    Sidebar Navigation — Back-Office Panel
    Uses Alpine.js x-data from parent layout (sidebarOpen)
--}}
@php
    $user = auth()->user();
    $currentRoute = request()->route()?->getName() ?? '';

    // Sidebar menu structure — centralised for easy maintenance
    $menuGroups = [
        [
            'label' => 'Dashboard',
            'icon' => 'fa-solid fa-chart-pie',
            'items' => [
                ['label' => 'Dashboard Eksekutif', 'route' => 'admin.dashboard'],
                ['label' => 'Dashboard Operasional', 'route' => 'operator.dashboard'],
            ],
        ],
        [
            'label' => 'Manajemen Sistem',
            'icon' => 'fa-solid fa-server',
            'items' => [
                ['label' => 'Manajemen User', 'route' => 'admin.users.index'],
                ['label' => 'Role & Hak Akses', 'route' => 'admin.roles.index'],
                ['label' => 'Identitas Desa', 'route' => 'admin.village-settings.edit'],
                ['label' => 'Konfigurasi Sistem', 'route' => 'admin.system-config.edit'],
            ],
        ],
        [
            'label' => 'Data Kependudukan',
            'icon' => 'fa-solid fa-users',
            'items' => [
                ['label' => 'Data Penduduk', 'route' => 'admin.penduduk.index'],
                ['label' => 'Data Keluarga (KK)', 'route' => 'admin.kartu-keluarga.index'],
                ['label' => 'Wilayah Administratif', 'route' => 'admin.wilayah.index'],
                ['label' => 'Kesehatan & Stunting', 'route' => 'admin.kesehatan.index'],
                ['label' => 'Bantuan Sosial', 'route' => 'admin.bansos.index'],
                ['label' => 'Pertanahan Desa', 'route' => 'admin.pertanahan.index'],
            ],
        ],
        [
            'label' => 'Layanan Administrasi',
            'icon' => 'fa-solid fa-envelope-open-text',
            'items' => [
                ['label' => 'Dashboard Layanan', 'route' => 'admin.layanan-surat.index'],
                ['label' => 'Buat Surat Baru', 'route' => 'admin.layanan-surat.create'],
                ['label' => 'Arsip Surat', 'route' => 'admin.arsip-surat.index'],
                ['label' => 'Template Surat', 'route' => 'admin.template-surat.index'],
                ['label' => 'Verifikasi & TTE', 'route' => 'admin.verifikasi-surat.index'],
            ],
        ],
        [
            'label' => 'Presensi & Tamu',
            'icon' => 'fa-solid fa-fingerprint',
            'items' => [
                ['label' => 'Monitoring Presensi', 'route' => 'admin.presensi.monitoring'],
                ['label' => 'Rekap Kehadiran', 'route' => 'admin.presensi.rekap'],
                ['label' => 'Buku Tamu', 'route' => 'admin.buku-tamu.index'],
            ],
        ],
        [
            'label' => 'Keuangan Desa',
            'icon' => 'fa-solid fa-wallet',
            'items' => [
                ['label' => 'Manajemen APBDes', 'route' => 'admin.apbdes.index'],
                ['label' => 'Realisasi Anggaran', 'route' => 'admin.realisasi.index'],
                ['label' => 'Laporan Keuangan', 'route' => 'admin.laporan.index'],
            ],
        ],
        [
            'label' => 'Pembangunan Desa',
            'icon' => 'fa-solid fa-person-digging',
            'items' => [
                ['label' => 'Data Pembangunan', 'route' => 'admin.pembangunan.index'],
                ['label' => 'Data Perencanaan', 'route' => 'admin.perencanaan.index'],
            ],
        ],
        [
            'label' => 'Konten Website',
            'icon' => 'fa-solid fa-globe',
            'items' => [
                ['label' => 'Berita & Artikel', 'route' => 'admin.artikel.index'],
                ['label' => 'Halaman Publik', 'route' => 'admin.halaman.index'],
                ['label' => 'Galeri & Publik', 'route' => 'admin.galeri.index'],
                ['label' => 'Pengumuman & Agenda', 'route' => 'admin.informasi.index'],
                ['label' => 'Produk Hukum (JDIH)', 'route' => 'admin.jdih.index'],
                ['label' => 'Produk UMKM', 'route' => 'admin.umkm.index'],
            ],
        ],
        [
            'label' => 'Laporan & Integrasi',
            'icon' => 'fa-solid fa-file-lines',
            'items' => [
                ['label' => 'Laporan Statistik', 'route' => '#'],
                ['label' => 'Export / Import Data', 'route' => '#'],
            ],
        ],
    ];
@endphp

<aside
    class="bg-white border-r border-gray-200 flex flex-col transition-all duration-300 z-30 h-full fixed lg:relative shadow-sm lg:shadow-none"
    :class="sidebarOpen ? 'w-72 translate-x-0' : '-translate-x-full lg:translate-x-0 lg:w-20'">

    {{-- Sidebar Header --}}
    <div class="h-16 flex items-center justify-center border-b border-gray-100 px-4 shrink-0">
        <div class="flex items-center gap-3 w-full" :class="!sidebarOpen && 'lg:justify-center'">
            <div class="w-9 h-9 bg-green-100 text-green-700 rounded-lg flex items-center justify-center shrink-0">
                <i class="fa-solid fa-leaf text-xl"></i>
            </div>
            <div class="flex flex-col transition-opacity overflow-hidden" :class="!sidebarOpen && 'lg:hidden'">
                <span class="font-bold text-sm text-gray-900 leading-tight whitespace-nowrap">SID Sindangmukti</span>
                <span class="text-[10px] text-gray-500 font-medium whitespace-nowrap">Panel Administrasi</span>
            </div>
        </div>
    </div>

    {{-- Sidebar Menu (Scrollable) --}}
    <nav class="flex-1 overflow-y-auto custom-scrollbar py-4 px-3 space-y-1">
        @foreach ($menuGroups as $index => $group)
            @php
                // Check if any child route is currently active
                $isGroupActive = collect($group['items'])->contains(
                    fn($item) => $item['route'] !== '#' && $currentRoute === $item['route'],
                );
            @endphp
            <div x-data="{ open: {{ $isGroupActive || $index === 0 ? 'true' : 'false' }} }" class="mb-1">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl transition-colors cursor-pointer"
                    :class="open
                        ?
                        'bg-green-50 text-green-700 font-semibold' :
                        'text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium'">
                    <div class="flex items-center gap-3">
                        <i class="{{ $group['icon'] }} w-5 text-center"></i>
                        <span class="whitespace-nowrap text-sm" :class="!sidebarOpen && 'lg:hidden'">
                            {{ $group['label'] }}
                        </span>
                    </div>
                    <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-200"
                        :class="{ 'rotate-180': open, 'hidden': !sidebarOpen }"></i>
                </button>

                <div x-show="open" x-collapse x-cloak class="mt-1 space-y-1" :class="!sidebarOpen && 'lg:hidden'">
                    @foreach ($group['items'] as $item)
                        @php
                            $href = $item['route'] !== '#' ? route($item['route']) : '#';
                            $isActive = $item['route'] !== '#' && $currentRoute === $item['route'];
                        @endphp
                        <a href="{{ $href }}"
                            class="block pl-11 pr-3 py-2 text-sm rounded-lg transition-colors relative
                                  {{ $isActive
                                      ? 'text-green-700 bg-green-50/50 font-medium before:content-[\'\'] before:absolute before:left-5 before:top-1/2 before:-translate-y-1/2 before:w-1.5 before:h-1.5 before:bg-green-500 before:rounded-full'
                                      : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </nav>

    {{-- Sidebar Footer (Logout) --}}
    <div class="p-4 border-t border-gray-100 bg-gray-50 shrink-0">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="flex items-center gap-3 text-red-600 hover:bg-red-100 hover:text-red-700 px-3 py-2.5 rounded-xl transition-colors w-full cursor-pointer"
                :class="!sidebarOpen && 'lg:justify-center'">
                <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center"></i>
                <span class="whitespace-nowrap font-medium text-sm" :class="!sidebarOpen && 'lg:hidden'">
                    Keluar Sistem
                </span>
            </button>
        </form>
    </div>
</aside>
