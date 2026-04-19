{{--
    Top Navbar — Back-Office Panel
    Shows workspace label, notifications, and profile menu
--}}
@php
    $user = auth()->user();
@endphp

<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 sm:px-6 shrink-0 z-10 shadow-sm">
    <div class="flex items-center gap-4">
        {{-- Sidebar Toggle --}}
        <button @click="sidebarOpen = !sidebarOpen"
            class="text-gray-500 hover:text-green-700 focus:outline-none transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 cursor-pointer">
            <i class="fa-solid fa-bars text-lg"></i>
        </button>

        {{-- Workspace Label --}}
        <div class="hidden sm:flex items-center gap-2">
            <span class="text-xs font-semibold px-2.5 py-1 bg-gray-100 text-gray-600 rounded-md uppercase tracking-wider">
                Ruang Kerja
            </span>
            <h2 class="font-bold text-gray-800">{{ $user->role_label ?? ucfirst($user->role) }}</h2>
        </div>
    </div>

    <div class="flex items-center gap-3 sm:gap-4">
        {{-- Search (desktop) --}}
        <button class="w-9 h-9 flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition-colors hidden sm:flex cursor-pointer">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>

        {{-- Notifications --}}
        <div class="relative" x-data="{ notifOpen: false }">
            <button @click="notifOpen = !notifOpen" @click.away="notifOpen = false"
                class="relative w-9 h-9 flex items-center justify-center text-gray-500 hover:bg-gray-100 rounded-full transition-colors focus:outline-none cursor-pointer">
                <i class="fa-regular fa-bell text-lg"></i>
                <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 border-2 border-white rounded-full"></span>
            </button>

            {{-- Dropdown --}}
            <div x-show="notifOpen" x-transition x-cloak
                 class="absolute right-0 mt-2 w-80 bg-white border border-gray-100 shadow-xl rounded-2xl py-2 z-50 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-50 flex justify-between items-center">
                    <span class="font-bold text-gray-800 text-sm">Notifikasi</span>
                    <span class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-bold">3 Baru</span>
                </div>
                <div class="max-h-64 overflow-y-auto">
                    <a href="#" class="px-4 py-3 hover:bg-gray-50 flex gap-3 border-b border-gray-50 transition-colors">
                        <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-signature text-xs"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800 leading-snug">3 Dokumen butuh TTE Anda</p>
                            <p class="text-xs text-gray-500 mt-0.5">5 Menit yang lalu</p>
                        </div>
                    </a>
                    <a href="#" class="px-4 py-3 hover:bg-gray-50 flex gap-3 transition-colors">
                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-info text-xs"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800 leading-snug">Laporan Bulanan telah digenerate</p>
                            <p class="text-xs text-gray-500 mt-0.5">1 Jam yang lalu</p>
                        </div>
                    </a>
                </div>
                <a href="#" class="block text-center text-xs font-semibold text-green-600 py-2 border-t border-gray-50 hover:bg-gray-50">
                    Lihat Semua
                </a>
            </div>
        </div>

        {{-- Profile Menu --}}
        <div class="relative" x-data="{ profileOpen: false }">
            <button @click="profileOpen = !profileOpen" @click.away="profileOpen = false"
                class="flex items-center gap-2.5 focus:outline-none hover:bg-gray-50 p-1 rounded-full sm:rounded-xl sm:pr-3 transition-colors cursor-pointer">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=15803d&color=fff"
                     alt="{{ $user->name }}" class="w-8 h-8 rounded-full shadow-sm">
                <div class="hidden md:flex flex-col text-left">
                    <span class="text-sm font-bold text-gray-800 leading-none mb-1">{{ $user->name }}</span>
                    <span class="text-[10px] font-medium text-gray-500 leading-none">{{ $user->role_label ?? ucfirst($user->role) }}</span>
                </div>
                <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 hidden md:block"></i>
            </button>

            {{-- Dropdown Profile --}}
            <div x-show="profileOpen" x-transition x-cloak
                 class="absolute right-0 mt-2 w-48 bg-white border border-gray-100 shadow-xl rounded-2xl py-2 z-50">
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-green-600">
                    <i class="fa-regular fa-user mr-2 w-4 text-center"></i> Profil Saya
                </a>
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-green-600">
                    <i class="fa-solid fa-key mr-2 w-4 text-center"></i> Ubah Password
                </a>
                <div class="border-t border-gray-100 my-1"></div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50 cursor-pointer">
                        <i class="fa-solid fa-arrow-right-from-bracket mr-2 w-4 text-center"></i> Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
