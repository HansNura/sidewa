{{--
    Component: Mobile Topbar Warga Portal
    Hanya tampil pada layar < lg (mobile/tablet)
--}}
@php
    $newNotifCount = $notifications->where('isNew', true)->count() ?? 0;
    $pageTitle = $pageTitle ?? 'Portal Warga';
@endphp

<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 sm:px-6 shrink-0 z-10 shadow-sm lg:hidden">
    <div class="flex items-center gap-4">
        {{-- Hamburger --}}
        <button @click="sidebarOpen = true"
                class="text-gray-500 hover:text-green-700 focus:outline-none transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100">
            <i class="fa-solid fa-bars text-lg"></i>
        </button>
        <h2 class="font-bold text-gray-800 text-sm">{{ $pageTitle }}</h2>
    </div>

    {{-- Quick Notification Bell --}}
    <a href="{{ route('warga.notifikasi') }}"
       class="relative w-9 h-9 flex items-center justify-center text-gray-500 hover:bg-gray-100 rounded-full transition-colors">
        <i class="fa-regular fa-bell text-lg"></i>
        @if($newNotifCount > 0)
            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 border-2 border-white rounded-full"></span>
        @endif
    </a>
</header>
