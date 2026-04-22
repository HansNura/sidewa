{{--
    Dashboard Layanan Mandiri Warga — Desa Sindangmukti
    Protected route: auth:warga guard
    Layout: Standalone (dedicated sidebar + topbar)
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Warga - Desa Sindangmukti</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 4px; }
    </style>
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
    <aside class="bg-white border-r border-gray-200 flex flex-col transition-transform duration-300 z-30 h-full fixed lg:relative shadow-xl lg:shadow-none shrink-0"
        :class="sidebarOpen ? 'w-72 translate-x-0' : '-translate-x-full lg:translate-x-0 lg:w-72'">

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

        <nav class="flex-1 overflow-y-auto custom-scrollbar py-6 px-4 space-y-1.5">
            <p class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Menu Utama</p>

            <a href="{{ route('warga.dashboard') }}"
                class="flex items-center gap-3 text-green-700 bg-green-50 px-3 py-2.5 rounded-xl font-semibold transition-colors">
                <i class="fa-solid fa-house w-5 text-center"></i>
                <span class="text-sm">Dashboard</span>
            </a>

            <a href="#" class="flex items-center gap-3 text-gray-600 hover:bg-gray-50 hover:text-gray-900 px-3 py-2.5 rounded-xl font-medium transition-colors">
                <i class="fa-solid fa-user w-5 text-center"></i>
                <span class="text-sm">Profil Warga</span>
            </a>

            <div x-data="{ openSurat: false }" class="mt-1">
                <button @click="openSurat = !openSurat"
                    class="w-full flex items-center justify-between text-gray-600 hover:bg-gray-50 hover:text-gray-900 px-3 py-2.5 rounded-xl font-medium transition-colors">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-envelope-open-text w-5 text-center"></i>
                        <span class="text-sm">Layanan Surat</span>
                    </div>
                    <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-200" :class="{'rotate-180': openSurat}"></i>
                </button>
                <div x-show="openSurat" x-collapse x-cloak class="mt-1 space-y-1">
                    <a href="#" class="block pl-11 pr-3 py-2 text-sm text-gray-500 hover:text-gray-900 hover:bg-gray-50 rounded-lg">Ajukan Permohonan</a>
                    <a href="#" class="block pl-11 pr-3 py-2 text-sm text-gray-500 hover:text-gray-900 hover:bg-gray-50 rounded-lg">Riwayat Permohonan</a>
                </div>
            </div>

            <a href="#" class="flex items-center gap-3 text-gray-600 hover:bg-gray-50 hover:text-gray-900 px-3 py-2.5 rounded-xl font-medium transition-colors">
                <i class="fa-solid fa-hand-holding-heart w-5 text-center"></i>
                <span class="text-sm">Bantuan Sosial</span>
            </a>

            <a href="#" class="flex items-center gap-3 text-gray-600 hover:bg-gray-50 hover:text-gray-900 px-3 py-2.5 rounded-xl font-medium transition-colors">
                <i class="fa-solid fa-bullhorn w-5 text-center"></i>
                <span class="text-sm">Pengaduan Warga</span>
            </a>

            <a href="#" class="flex items-center justify-between text-gray-600 hover:bg-gray-50 hover:text-gray-900 px-3 py-2.5 rounded-xl font-medium transition-colors">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-bell w-5 text-center"></i>
                    <span class="text-sm">Notifikasi & Status</span>
                </div>
                @if($notifications->where('isNew', true)->count() > 0)
                <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $notifications->where('isNew', true)->count() }}</span>
                @endif
            </a>
        </nav>

        <footer class="p-4 border-t border-gray-100 bg-gray-50 shrink-0">
            <form action="{{ route('warga.logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center gap-3 text-red-600 hover:bg-red-100 hover:text-red-700 px-3 py-2.5 rounded-xl transition-colors font-medium text-sm w-full cursor-pointer">
                    <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center"></i>
                    <span>Keluar Akun</span>
                </button>
            </form>
        </footer>
    </aside>

    {{-- Overlay Mobile --}}
    <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-gray-900/50 z-20 lg:hidden backdrop-blur-sm" x-transition.opacity x-cloak></div>

    {{-- ═══════════════════════════════════════════════════════════
         MAIN CONTENT
    ═══════════════════════════════════════════════════════════ --}}
    <div class="flex-1 flex flex-col h-full overflow-hidden w-full relative">

        {{-- Mobile Topbar --}}
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 sm:px-6 shrink-0 z-10 shadow-sm lg:hidden">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="text-gray-500 hover:text-green-700 focus:outline-none transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
                <h2 class="font-bold text-gray-800 text-sm">Dashboard Warga</h2>
            </div>
            <button class="relative w-9 h-9 flex items-center justify-center text-gray-500 hover:bg-gray-100 rounded-full transition-colors">
                <i class="fa-regular fa-bell text-lg"></i>
                @if($notifications->where('isNew', true)->count() > 0)
                <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 border-2 border-white rounded-full"></span>
                @endif
            </button>
        </header>

        {{-- Main Scrollable Area --}}
        <main class="flex-1 overflow-y-auto custom-scrollbar p-4 sm:p-6 lg:p-8">
            <div class="max-w-6xl mx-auto space-y-6 sm:space-y-8">

                {{-- ═══════════════════════════════════════
                     1. WELCOME BANNER
                ═══════════════════════════════════════ --}}
                <section class="relative bg-gradient-to-r from-green-700 to-green-900 rounded-3xl p-6 sm:p-8 shadow-lg overflow-hidden flex flex-col md:flex-row items-center gap-6">
                    <div class="absolute -right-20 -top-20 w-64 h-64 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-yellow-400/20 rounded-full blur-2xl pointer-events-none"></div>

                    <div class="relative shrink-0 z-10">
                        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full border-4 border-white/20 shadow-md bg-white/20 backdrop-blur-sm flex items-center justify-center text-3xl font-bold text-white">
                            {{ $warga->initials() }}
                        </div>
                        <div class="absolute bottom-0 right-0 bg-green-400 border-2 border-green-800 w-5 h-5 rounded-full" title="Akun Aktif"></div>
                    </div>

                    <div class="flex-1 text-center md:text-left z-10 text-white">
                        <p class="text-green-100 text-sm font-medium mb-1" x-text="getGreeting() + ','"></p>
                        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight mb-2">{{ $warga->nama }}</h1>
                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 text-sm text-green-50">
                            <span class="flex items-center gap-1.5"><i class="fa-regular fa-id-card opacity-70"></i> {{ $warga->formattedNik() }}</span>
                            <span class="hidden sm:inline opacity-50">|</span>
                            <span class="flex items-center gap-1.5"><i class="fa-solid fa-location-dot opacity-70"></i> {{ $warga->alamatLengkap() }}</span>
                        </div>
                    </div>

                    <div class="flex flex-col items-center md:items-end gap-3 z-10 w-full md:w-auto mt-4 md:mt-0 border-t md:border-t-0 md:border-l border-white/20 pt-4 md:pt-0 md:pl-6">
                        <p class="text-sm font-medium text-green-100 flex items-center gap-2">
                            <i class="fa-regular fa-calendar"></i> <span x-text="getCurrentDate()"></span>
                        </p>
                        <span class="inline-flex items-center gap-1.5 bg-white text-green-800 text-xs font-bold px-3 py-1.5 rounded-lg shadow-sm">
                            <i class="fa-solid fa-circle-check text-green-500"></i> Akun Terverifikasi
                        </span>
                    </div>
                </section>

                {{-- ═══════════════════════════════════════
                     2. QUICK ACTION CARDS
                ═══════════════════════════════════════ --}}
                <section>
                    <h2 class="text-lg font-bold text-gray-800 mb-4 px-1">Layanan Cepat</h2>
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">
                        @php
                            $colorMap = [
                                'blue'    => ['bg' => 'bg-blue-50',    'text' => 'text-blue-600'],
                                'emerald' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600'],
                                'amber'   => ['bg' => 'bg-amber-50',   'text' => 'text-amber-600'],
                                'red'     => ['bg' => 'bg-red-50',     'text' => 'text-red-600'],
                            ];
                        @endphp
                        @foreach($quickActions as $action)
                            @php $c = $colorMap[$action['color']] ?? $colorMap['blue']; @endphp
                            <a href="{{ $action['route'] }}"
                                class="group bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-green-300 transition-all duration-300 flex flex-col items-center justify-center text-center transform hover:-translate-y-1">
                                <div class="w-12 h-12 {{ $c['bg'] }} {{ $c['text'] }} rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                    <i class="{{ $action['icon'] }} text-xl"></i>
                                </div>
                                <h3 class="font-bold text-gray-800 text-sm mb-1">{{ $action['title'] }}</h3>
                                <p class="text-[10px] text-gray-500">{{ $action['description'] }}</p>
                            </a>
                        @endforeach
                    </div>
                </section>

                {{-- ═══════════════════════════════════════
                     TWO-COLUMN GRID
                ═══════════════════════════════════════ --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">

                    {{-- KIRI: Status & Notifikasi --}}
                    <div class="lg:col-span-2 space-y-6 sm:space-y-8">

                        {{-- 3. STATUS PERMOHONAN SURAT --}}
                        <section class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
                            <header class="px-5 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                                <div>
                                    <h3 class="font-bold text-gray-800 text-base">Status Permohonan Surat</h3>
                                    <p class="text-xs text-gray-500 mt-0.5">Pantau progres pengajuan terakhir Anda.</p>
                                </div>
                            </header>
                            <div class="flex-1 divide-y divide-gray-50">
                                @forelse($suratPermohonan as $surat)
                                    @php $badge = $surat->statusBadge(); @endphp
                                    <article class="p-5 hover:bg-gray-50 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-4 {{ $surat->status == 'ditolak' ? 'opacity-80' : '' }}">
                                        <div class="flex items-start gap-4">
                                            <div class="w-10 h-10 rounded-xl {{ $badge['bg'] }} {{ $badge['text'] }} flex items-center justify-center shrink-0 border {{ $badge['border'] }}">
                                                @if($surat->status === 'pengajuan' || $surat->status === 'verifikasi')
                                                    <i class="fa-solid fa-spinner animate-spin"></i>
                                                @elseif($surat->status === 'selesai')
                                                    <i class="fa-solid fa-check-double"></i>
                                                @elseif($surat->status === 'ditolak')
                                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                                @else
                                                    <i class="fa-solid fa-{{ $badge['icon'] }}"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-gray-800 text-sm">{{ $surat->jenisLabel() }}</h4>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    @if($surat->status === 'ditolak')
                                                        <span class="text-red-500 font-medium">Catatan: {{ $surat->alasan_tolak ?? 'Silakan ajukan ulang.' }}</span>
                                                    @else
                                                        Diajukan: {{ $surat->tanggal_pengajuan?->translatedFormat('d M Y, H:i') }} WIB
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex flex-row sm:flex-col items-center sm:items-end justify-between sm:justify-center mt-2 sm:mt-0">
                                            <span class="{{ $badge['bg'] }} {{ $badge['text'] }} text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase tracking-wide border {{ $badge['border'] }}">{{ $badge['label'] }}</span>
                                        </div>
                                    </article>
                                @empty
                                    <div class="p-8 text-center">
                                        <i class="fa-solid fa-inbox text-gray-300 text-3xl mb-3"></i>
                                        <p class="text-sm text-gray-500">Belum ada permohonan surat yang diajukan.</p>
                                    </div>
                                @endforelse
                            </div>
                        </section>

                        {{-- 4. NOTIFIKASI TERBARU --}}
                        <section class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
                            <header class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
                                <h3 class="font-bold text-gray-800 text-base flex items-center gap-2">
                                    Pemberitahuan
                                    @if($notifications->where('isNew', true)->count() > 0)
                                    <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full leading-none">{{ $notifications->where('isNew', true)->count() }} Baru</span>
                                    @endif
                                </h3>
                            </header>
                            <div class="divide-y divide-gray-50 max-h-[300px] overflow-y-auto custom-scrollbar">
                                @forelse($notifications as $notif)
                                    <a href="#" class="block p-4 {{ $notif['isNew'] ? 'bg-blue-50/30' : '' }} hover:bg-gray-50 transition-colors">
                                        <div class="flex gap-4 items-start relative">
                                            @if($notif['isNew'])
                                            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-1.5 bg-green-500 rounded-full"></div>
                                            @endif
                                            <div class="w-8 h-8 rounded-full {{ $notif['iconBg'] }} flex items-center justify-center shrink-0 ml-3">
                                                <i class="{{ $notif['icon'] }} text-xs"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-900 {{ $notif['isNew'] ? 'font-semibold' : 'font-medium' }} leading-snug">{{ $notif['message'] }}</p>
                                                <p class="text-[10px] text-gray-500 mt-1"><i class="fa-regular fa-clock mr-1"></i> {{ $notif['time'] }}</p>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="p-6 text-center text-sm text-gray-400">
                                        <i class="fa-regular fa-bell-slash text-2xl mb-2"></i>
                                        <p>Belum ada pemberitahuan.</p>
                                    </div>
                                @endforelse
                            </div>
                        </section>
                    </div>

                    {{-- KANAN: Info Desa --}}
                    <div class="lg:col-span-1">
                        <section class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden sticky top-6">
                            <header class="px-5 py-4 border-b border-gray-100 bg-green-700 text-white flex justify-between items-center">
                                <h3 class="font-bold text-base flex items-center gap-2">
                                    <i class="fa-regular fa-newspaper"></i> Info Desa
                                </h3>
                            </header>
                            <div class="p-4 space-y-4">
                                @forelse($berita as $news)
                                    <article class="group flex gap-3 cursor-pointer">
                                        @if($news->thumbnail_url)
                                            <img src="{{ $news->thumbnail_url }}" alt="{{ $news->judul }}"
                                                class="w-16 h-16 rounded-xl object-cover shadow-sm group-hover:opacity-80 transition-opacity shrink-0">
                                        @else
                                            <div class="w-16 h-16 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400 shadow-sm shrink-0">
                                                <i class="fa-solid fa-image text-xl"></i>
                                            </div>
                                        @endif
                                        <div class="flex flex-col justify-between py-0.5">
                                            <h4 class="text-sm font-bold text-gray-800 leading-tight group-hover:text-green-600 transition-colors line-clamp-2">
                                                {{ $news->judul }}
                                            </h4>
                                            <p class="text-[10px] text-gray-500"><i class="fa-regular fa-calendar mr-1"></i> {{ $news->published_at?->translatedFormat('d M Y') }}</p>
                                        </div>
                                    </article>
                                @empty
                                    <div class="text-center py-4 text-sm text-gray-400">
                                        Belum ada berita terbaru.
                                    </div>
                                @endforelse
                            </div>
                            <div class="px-4 pb-4">
                                <a href="{{ route('home') }}" class="block w-full text-center py-2 text-xs font-semibold text-green-700 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                                    Baca Berita Lainnya
                                </a>
                            </div>
                        </section>
                    </div>
                </div>

                {{-- Last Login Info --}}
                @if ($warga->last_login_at)
                    <div class="text-center text-xs text-gray-400 pb-4">
                        <i class="fa-regular fa-clock mr-1"></i>
                        Login terakhir: {{ $warga->last_login_at->translatedFormat('d F Y, H:i') }} WIB
                    </div>
                @endif

            </div>
        </main>
    </div>

</body>
</html>
