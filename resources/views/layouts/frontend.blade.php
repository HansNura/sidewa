<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Desa Sindangmukti')</title>
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('alpine_plugins')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- Map Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Colors Reference: #2f7f33 rgb(47, 127, 51) #fbc02d rgb(251, 192, 45) #0287cf rgb(2, 135, 207) #f9fafb rgb(249, 250, 251) #384252 rgb(56, 66, 82) -->

    <style>
        [x-cloak] { display: none !important; }
    </style>
    
    @stack('styles')
</head>

<body x-data="{ sticky: false, mobileMenuOpen: false }" class="font-sans text-gray-800 bg-gray-50" @scroll.window="sticky = (window.scrollY > 50)">
    
    @include('components.frontend.navbar')

    <main>
        @yield('content')
    </main>

    @include('components.frontend.footer')

    <!-- CHART.JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- App Scripts (Ensure these files are created in public/assets/js) -->
    <!-- <script src="{{ asset('assets/js/main.js') }}"></script> -->
    <!-- <script src="{{ asset('assets/js/map.js') }}"></script> -->
    <!-- <script src="{{ asset('assets/js/chart.js') }}"></script> -->
    
    @stack('scripts')
</body>

</html>
