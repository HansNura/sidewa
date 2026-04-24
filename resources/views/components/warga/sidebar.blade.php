{{--
    Component: Sidebar Warga Portal
    Digunakan oleh layout warga.blade.php
    Variabel yang tersedia dari controller:
        $notifications (collection)
--}}
@php
    $warga = Auth::guard('warga')->user();
    $newNotifCount = $notifications->where('isNew', true)->count() ?? 0;

    // Current route untuk active state
    $currentRoute = request()->route()?->getName();
@endphp

<aside class="bg-white border-r border-gray-200 flex flex-col transition-transform duration-300 z-30 h-full fixed lg:relative shadow-xl lg:shadow-none shrink-0"
       :class="sidebarOpen ? 'w-72 translate-x-0' : '-translate-x-full lg:translate-x-0 lg:w-72'">

    {{-- ── Sidebar Header ── --}}
    <header class="h-16 flex items-center justify-between lg:justify-center border-b border-gray-100 px-6 shrink-0">
        <div class="flex items-center gap-3 w-full">
            <div class="w-9 h-9 bg-green-100 text-green-700 rounded-lg flex items-center justify-center shrink-0">
                <i class="fa-solid fa-leaf text-xl"></i>
            </div>
            <div class="flex flex-col overflow-hidden">
                <span class="font-bold text-sm text-gray-900 leading-tight whitespace-nowrap">Desa Sindangmukti</span>
                <span class="text-[10px] text-gray-500 font-medium whitespace-nowrap">Portal Layanan Warga</span>
            </div>
        </div>
        <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-red-500 focus:outline-none">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
    </header>

    {{-- ── Sidebar Nav ── --}}
    <nav class="flex-1 overflow-y-auto custom-scrollbar py-6 px-4 space-y-1.5">

        <p class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Menu Utama</p>

        {{-- Dashboard --}}
        <a href="{{ route('warga.dashboard') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-colors text-sm
                  {{ $currentRoute === 'warga.dashboard' ? 'warga-nav-active' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <i class="fa-solid fa-house w-5 text-center"></i>
            <span>Dashboard</span>
        </a>

        {{-- Profil Warga --}}
        <a href="{{ route('warga.profil') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-colors text-sm
                  {{ $currentRoute === 'warga.profil' ? 'warga-nav-active' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <i class="fa-solid fa-user w-5 text-center"></i>
            <span>Profil Warga</span>
        </a>

        {{-- Layanan Surat (Submenu) --}}
        @php
            $suratRoutes = ['warga.surat.ajukan', 'warga.surat.riwayat'];
            $isSuratOpen = in_array($currentRoute, $suratRoutes);
        @endphp
        <div x-data="{ openSurat: {{ $isSuratOpen ? 'true' : 'false' }} }" class="mt-1">
            <button @click="openSurat = !openSurat"
                    class="w-full flex items-center justify-between text-gray-600 hover:bg-gray-50 hover:text-gray-900 px-3 py-2.5 rounded-xl font-medium transition-colors text-sm
                           {{ $isSuratOpen ? 'warga-nav-active' : '' }}">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-envelope-open-text w-5 text-center"></i>
                    <span>Layanan Surat</span>
                </div>
                <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-200" :class="{'rotate-180': openSurat}"></i>
            </button>
            <div x-show="openSurat" x-collapse x-cloak class="mt-1 space-y-1">
                <a href="{{ route('warga.surat.ajukan') }}"
                   class="block pl-11 pr-3 py-2 text-sm rounded-lg transition-colors
                          {{ $currentRoute === 'warga.surat.ajukan' ? 'text-green-700 bg-green-50 font-semibold' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">
                    Ajukan Permohonan
                </a>
                <a href="{{ route('warga.surat.riwayat') }}"
                   class="block pl-11 pr-3 py-2 text-sm rounded-lg transition-colors
                          {{ $currentRoute === 'warga.surat.riwayat' ? 'text-green-700 bg-green-50 font-semibold' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">
                    Riwayat Permohonan
                </a>
            </div>
        </div>

        {{-- Bantuan Sosial --}}
        <a href="{{ route('warga.bansos') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-colors text-sm
                  {{ $currentRoute === 'warga.bansos' ? 'warga-nav-active' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <i class="fa-solid fa-hand-holding-heart w-5 text-center"></i>
            <span>Bantuan Sosial</span>
        </a>

        {{-- Pengaduan Warga --}}
        <a href="{{ route('warga.pengaduan') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-colors text-sm
                  {{ in_array($currentRoute, ['warga.pengaduan', 'warga.pengaduan.store']) ? 'warga-nav-active' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <i class="fa-solid fa-bullhorn w-5 text-center"></i>
            <span>Pengaduan Warga</span>
        </a>

        {{-- Notifikasi & Status --}}
        <a href="{{ route('warga.notifikasi') }}"
           class="flex items-center justify-between px-3 py-2.5 rounded-xl font-medium transition-colors text-sm
                  {{ $currentRoute === 'warga.notifikasi' ? 'warga-nav-active' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-bell w-5 text-center"></i>
                <span>Notifikasi & Status</span>
            </div>
            @if($newNotifCount > 0)
                <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $newNotifCount }}</span>
            @endif
        </a>

        {{-- Divider --}}
        <div class="border-t border-gray-100 my-3"></div>
        <p class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Portal Desa</p>

        <a href="{{ route('home') }}" target="_blank"
           class="flex items-center gap-3 text-gray-600 hover:bg-gray-50 hover:text-gray-900 px-3 py-2.5 rounded-xl font-medium transition-colors text-sm">
            <i class="fa-solid fa-globe w-5 text-center"></i>
            <span>Website Desa</span>
            <i class="fa-solid fa-arrow-up-right-from-square text-[10px] ml-auto text-gray-400"></i>
        </a>

    </nav>

    {{-- ── Sidebar Footer (User Info + Logout) ── --}}
    <footer class="p-4 border-t border-gray-100 bg-gray-50 shrink-0">
        {{-- User Info --}}
        <div class="flex items-center gap-3 px-3 py-2 mb-2">
            <div class="w-8 h-8 rounded-full bg-green-700 text-white flex items-center justify-center text-xs font-bold shrink-0">
                {{ $warga?->initials() }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-bold text-gray-800 truncate">{{ $warga?->nama }}</p>
                <p class="text-[10px] text-gray-500 truncate">{{ $warga?->nik }}</p>
            </div>
        </div>
        {{-- Logout --}}
        <form action="{{ route('warga.logout') }}" method="POST">
            @csrf
            <button type="submit"
                    class="flex items-center gap-3 text-red-600 hover:bg-red-50 hover:text-red-700 px-3 py-2.5 rounded-xl transition-colors font-medium text-sm w-full cursor-pointer">
                <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center"></i>
                <span>Keluar Akun</span>
            </button>
        </form>
    </footer>
</aside>
