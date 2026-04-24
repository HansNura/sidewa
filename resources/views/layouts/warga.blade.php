{{--
    Layout: Layanan Mandiri Warga
    Guard: auth:warga
    Standalone layout (tidak extends backoffice/frontend)
    Includes dedicated warga sidebar + mobile topbar
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Portal Layanan Warga - Desa Sindangmukti')</title>
    <meta name="description" content="@yield('meta_description', 'Portal layanan mandiri warga Desa Sindangmukti - urus administrasi desa secara online.')">

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    {{-- Google Font: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />

    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }

        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #d1d5db; }

        /* Warga nav active state */
        .warga-nav-active {
            color: #15803d;
            background-color: #f0fdf4;
            font-weight: 600;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-[#F8FAFC] text-gray-800 antialiased font-sans flex h-screen overflow-hidden"
      x-data="{
          sidebarOpen: false,
          getGreeting() {
              const hour = new Date().getHours();
              if (hour < 11) return 'Selamat Pagi';
              if (hour < 15) return 'Selamat Siang';
              if (hour < 18) return 'Selamat Sore';
              return 'Selamat Malam';
          },
          getCurrentDate() {
              const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
              return new Date().toLocaleDateString('id-ID', options);
          }
      }">

    {{-- ═══════════════════════════════════════════════════════════
         SIDEBAR NAVIGATION (WARGA PORTAL)
    ═══════════════════════════════════════════════════════════ --}}
    @include('components.warga.sidebar')

    {{-- Overlay Mobile --}}
    <div x-show="sidebarOpen" @click="sidebarOpen = false"
         class="fixed inset-0 bg-gray-900/50 z-20 lg:hidden backdrop-blur-sm"
         x-transition.opacity x-cloak></div>

    {{-- ═══════════════════════════════════════════════════════════
         MAIN CONTENT WRAPPER
    ═══════════════════════════════════════════════════════════ --}}
    <div class="flex-1 flex flex-col h-full overflow-hidden w-full relative">

        {{-- Mobile Topbar --}}
        @include('components.warga.topbar')

        {{-- Main Scrollable Area --}}
        <main class="flex-1 overflow-y-auto custom-scrollbar p-4 sm:p-6 lg:p-8">
            <div class="max-w-6xl mx-auto space-y-6 sm:space-y-8">

                @yield('content')

                {{-- Footer --}}
                <footer class="text-center py-6 text-xs text-gray-400">
                    &copy; {{ date('Y') }} Desa Sindangmukti &mdash; Portal Layanan Mandiri Warga
                </footer>
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
