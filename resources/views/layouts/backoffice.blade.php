<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Panel Administrasi - Desa Sindangmukti')</title>

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Google Font: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }

        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #d1d5db; }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-50 text-gray-800 antialiased font-sans flex h-screen overflow-hidden"
      x-data="{ sidebarOpen: true }">

    {{-- Sidebar Navigation --}}
    @include('components.backoffice.sidebar')

    {{-- Overlay Sidebar Mobile --}}
    <div x-show="sidebarOpen" @click="sidebarOpen = false"
         class="fixed inset-0 bg-gray-900/50 z-20 lg:hidden"
         x-transition.opacity x-cloak></div>

    {{-- Main Content Wrapper --}}
    <div class="flex-1 flex flex-col h-full overflow-hidden w-full relative bg-[#F8FAFC]">

        {{-- Top Navbar --}}
        @include('components.backoffice.topbar')

        {{-- Main Content Area (Scrollable) --}}
        <main class="flex-1 overflow-y-auto custom-scrollbar p-4 sm:p-6 lg:p-8">
            <div class="max-w-7xl mx-auto space-y-6">
                @yield('content')

                {{-- Footer --}}
                <footer class="text-center py-6 text-sm text-gray-400 font-medium">
                    &copy; {{ date('Y') }} Sistem Informasi Desa Sindangmukti. Dikembangkan berdasarkan Arsitektur ADPL.
                </footer>
            </div>
        </main>
    </div>

    {{-- Highcharts --}}
    <script src="https://code.highcharts.com/highcharts.js"></script>

    {{-- Leaflet JS --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    @stack('scripts')
</body>

</html>
