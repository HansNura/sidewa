@php
    // Navbar hanya transparan di halaman Home. Di halaman lain, dia harus solid sejak awal.
    $isHome = request()->routeIs('home');
@endphp

<header
    :class="{ 'shadow-sm bg-white/90 backdrop-blur-xl border-b border-slate-200/50 text-slate-700': sticky || !
            @json($isHome), 'text-white': !sticky && @json($isHome) }"
    class="fixed top-0 left-0 z-50 w-full transition-all duration-300">
    <div class="flex items-center justify-between px-6 py-4 mx-auto max-w-7xl">
        <a href="{{ url('/') }}" class="flex items-center gap-3 group">
            <div class="p-1 bg-white rounded-full shadow-sm group-hover:shadow-md transition-shadow">
                <img src="{{ asset('assets/img/logo.webp') }}" alt="Logo Desa"
                    class="w-10 h-10 rounded-full object-contain" />
            </div>
            <div class="flex flex-col leading-tight">
                <span class="text-lg font-black tracking-tight transition-colors"
                    :class="{ 'text-slate-900': sticky || !@json($isHome), 'text-white': !sticky &&
                            @json($isHome) }">Desa
                    Sindangmukti</span>
                <span class="text-[10px] font-bold uppercase tracking-widest"
                    :class="{ 'text-emerald-600': sticky || !@json($isHome), 'text-emerald-300': !sticky &&
                            @json($isHome) }">Kab.
                    Ciamis</span>
            </div>
        </a>

        <!-- Desktop Nav -->
        <nav class="hidden lg:flex items-center space-x-8 text-[15px] font-bold tracking-wide">
            <a href="{{ route('home') }}"
                class="transition-colors hover:text-emerald-500"
                :class="{ 'text-emerald-600': (sticky || !@json($isHome)) && @json(request()->routeIs('home')), 'text-emerald-300': !sticky && @json($isHome) && @json(request()->routeIs('home')) }">Beranda</a>

            <div x-data="{ profilOpen: false }" @mouseenter="profilOpen = true" @mouseleave="profilOpen = false"
                class="relative">
                <button type="button" class="flex items-center gap-1 transition-colors hover:text-emerald-500"
                    :class="{ 'text-emerald-500': profilOpen }" aria-haspopup="true" :aria-expanded="profilOpen">
                    Profil Desa
                    <svg class="w-4 h-4 mt-[2px] transition-transform duration-200"
                        :class="{ 'rotate-180': profilOpen }" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-cloak x-show="profilOpen" x-transition.opacity.duration.150ms
                    class="absolute left-0 w-56 py-2 mt-3 bg-white border border-slate-100 rounded-2xl shadow-xl text-slate-700 overflow-hidden"
                    role="menu">
                    <a href="{{ route('profil.identitas') }}"
                        class="block px-5 py-2.5 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">Identitas
                        Desa</a>
                    <a href="{{ route('profil.visi-misi') }}"
                        class="block px-5 py-2.5 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">Visi &
                        Misi</a>
                    <a href="{{ route('profil.struktur-bpd') }}"
                        class="block px-5 py-2.5 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">BPD &
                        Lembaga</a>
                    <a href="{{ route('profil.sejarah-desa') }}"
                        class="block px-5 py-2.5 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">Sejarah
                        Desa</a>
                </div>
            </div>

            <div x-data="{ dataOpen: false }" @mouseenter="dataOpen = true" @mouseleave="dataOpen = false" class="relative">
                <button type="button" class="flex items-center gap-1 transition-colors hover:text-emerald-500"
                    :class="{ 'text-emerald-500': dataOpen }">
                    Data Desa
                    <svg class="w-4 h-4 mt-[2px] transition-transform duration-200" :class="{ 'rotate-180': dataOpen }"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-cloak x-show="dataOpen" x-transition.opacity.duration.150ms
                    class="absolute left-0 w-60 py-2 mt-3 bg-white border border-slate-100 rounded-2xl shadow-xl text-slate-700 overflow-hidden">
                    <a href="{{ route('data.penduduk') }}"
                        class="block px-5 py-2.5 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">Data
                        Penduduk</a>
                    <a href="{{ route('data.wilayah') }}"
                        class="block px-5 py-2.5 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">Data
                        Wilayah</a>
                    <a href="{{ route('data.pendidikan-ditempuh') }}"
                        class="block px-5 py-2.5 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">Pendidikan
                        (Ditempuh)</a>
                    <a href="{{ route('data.pekerjaan') }}"
                        class="block px-5 py-2.5 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">Pekerjaan</a>
                    <a href="{{ route('data.agama') }}"
                        class="block px-5 py-2.5 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">Agama</a>
                    <a href="{{ route('data.jenis-kelamin') }}"
                        class="block px-5 py-2.5 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">Jenis
                        Kelamin</a>
                    <a href="{{ route('data.kelompok-umur') }}"
                        class="block px-5 py-2.5 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">Kelompok
                        Umur</a>
                </div>
            </div>

            <div @mouseenter="infoOpen = true" @mouseleave="infoOpen = false" class="relative" x-data="{ infoOpen: false }">
                <button type="button" class="flex items-center gap-1 transition-colors hover:text-emerald-500"
                    :class="{ 'text-emerald-500': infoOpen }">
                    Informasi
                    <svg class="w-4 h-4 mt-[2px] transition-transform duration-200" :class="{ 'rotate-180': infoOpen }"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-cloak x-show="infoOpen" x-transition.opacity.duration.150ms
                    class="absolute left-0 w-56 py-2 mt-3 bg-white border border-slate-100 rounded-2xl shadow-xl text-slate-700 overflow-hidden">
                    <a href="{{ route('informasi.berita-artikel') }}"
                        class="block px-5 py-2.5 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">Berita &
                        Artikel</a>
                    <a href="{{ route('informasi.pengumuman') }}"
                        class="block px-5 py-2.5 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">Pengumuman
                        & Agenda</a>
                    <a href="{{ route('informasi.hukum') }}"
                        class="block px-5 py-2.5 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">Produk
                        Hukum Desa</a>
                    <a href="{{ route('informasi.publik') }}"
                        class="block px-5 py-2.5 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">Informasi
                        Publik</a>
                    <a href="{{ route('informasi.galeri') }}"
                        class="block px-5 py-2.5 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">Galeri
                        Desa</a>
                </div>
            </div>

            <a href="{{ route('transparansi') }}"
                class="transition-colors hover:text-emerald-500 {{ request()->routeIs('transparansi') ? 'text-emerald-500' : '' }}">Transparansi</a>
            <a href="{{ route('layanan') }}"
                class="transition-colors hover:text-emerald-500 {{ request()->routeIs('layanan') ? 'text-emerald-500' : '' }}">Layanan</a>
            <a href="{{ route('lapak') }}"
                class="transition-colors hover:text-emerald-500 {{ request()->routeIs('lapak*') ? 'text-emerald-500' : '' }}">Lapak
                Desa</a>

            {{-- TOMBOL MASUK (DROPDOWN) --}}
            <div x-data="{ loginOpen: false }" @mouseenter="loginOpen = true" @mouseleave="loginOpen = false"
                class="relative">
                <button type="button"
                    class="flex items-center gap-2 px-6 py-2.5 text-sm font-bold transition-all rounded-full shadow-sm border"
                    :class="{ 'bg-emerald-600 text-white hover:bg-emerald-700 border-emerald-600': sticky || !
                            @json($isHome), 'bg-white/10 text-white hover:bg-white/20 border-white/20': !
                            sticky && @json($isHome) }">
                    <i class="fa-solid fa-right-to-bracket"></i> Masuk
                </button>
                <div x-cloak x-show="loginOpen" x-transition.opacity.duration.150ms
                    class="absolute right-0 w-52 py-2 mt-3 bg-white border border-slate-100 rounded-2xl shadow-xl text-slate-700 overflow-hidden">
                    <a href="{{ route('login') }}"
                        class="flex items-center gap-3 px-5 py-3 text-sm font-bold hover:bg-emerald-50 hover:text-emerald-700 transition-colors border-b border-slate-50">
                        <div
                            class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-user-tie"></i>
                        </div>
                        Aparatur Desa
                    </a>
                    <a href="{{ route('layanan.mandiri.login') }}"
                        class="flex items-center gap-3 px-5 py-3 text-sm font-bold hover:bg-blue-50 hover:text-blue-700 transition-colors">
                        <div
                            class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        Warga Desa
                    </a>
                </div>
            </div>
        </nav>

        <!-- Mobile Menu Toggle -->
        <div @click.outside="mobileMenuOpen = false" class="lg:hidden">
            <button @click="mobileMenuOpen = !mobileMenuOpen"
                class="p-2 transition-colors rounded-xl focus:outline-none hover:bg-slate-100/20"
                aria-label="Toggle menu"
                :class="{ 'text-slate-800': sticky || !@json($isHome), 'text-white': !sticky &&
                        @json($isHome) }">
                <svg x-show="!mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="mobileMenuOpen" x-cloak xmlns="http://www.w3.org/2000/svg" class="w-7 h-7"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <nav x-cloak x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform -translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-4"
                class="absolute left-0 top-full w-full bg-white border-b border-slate-200 shadow-2xl max-h-[calc(100vh-80px)] overflow-y-auto">
                <ul class="flex flex-col px-6 py-6 space-y-3 font-bold text-slate-700">
                    <li>
                        <a href="{{ url('/') }}"
                            class="block px-4 py-3 rounded-xl hover:bg-emerald-50 hover:text-emerald-600 transition-colors {{ request()->routeIs('home') ? 'bg-emerald-50 text-emerald-600' : '' }}">Beranda</a>
                    </li>
                    <li x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center justify-between w-full px-4 py-3 rounded-xl hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                            <span>Profil Desa</span>
                            <svg :class="{ 'rotate-180': open }" class="w-5 h-5 transition-transform transform"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <ul x-show="open" x-transition x-cloak
                            class="pl-4 ml-6 space-y-1 mt-1 text-sm font-semibold border-l-2 border-slate-100">
                            <li>
                                <a href="{{ route('profil.identitas') }}"
                                    class="block px-4 py-2 text-slate-500 hover:text-emerald-600 transition-colors">Identitas
                                    Desa</a>
                            </li>
                            <li>
                                <a href="{{ route('profil.visi-misi') }}"
                                    class="block px-4 py-2 text-slate-500 hover:text-emerald-600 transition-colors">Visi
                                    & Misi</a>
                            </li>
                            <li>
                                <a href="{{ route('profil.struktur-bpd') }}"
                                    class="block px-4 py-2 text-slate-500 hover:text-emerald-600 transition-colors">BPD
                                    & Lembaga</a>
                            </li>
                            <li>
                                <a href="{{ route('profil.sejarah-desa') }}"
                                    class="block px-4 py-2 text-slate-500 hover:text-emerald-600 transition-colors">Sejarah
                                    Desa</a>
                            </li>
                        </ul>
                    </li>
                    <li x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center justify-between w-full px-4 py-3 rounded-xl hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                            <span>Data Desa</span>
                            <svg :class="{ 'rotate-180': open }" class="w-5 h-5 transition-transform transform"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <ul x-show="open" x-transition x-cloak
                            class="pl-4 ml-6 space-y-1 mt-1 text-sm font-semibold border-l-2 border-slate-100">
                            <li>
                                <a href="{{ route('data.penduduk') }}"
                                    class="block px-4 py-2 text-slate-500 hover:text-emerald-600 transition-colors">Data
                                    Penduduk</a>
                            </li>
                            <li>
                                <a href="{{ route('data.wilayah') }}"
                                    class="block px-4 py-2 text-slate-500 hover:text-emerald-600 transition-colors">Data
                                    Wilayah</a>
                            </li>
                            <li>
                                <a href="{{ route('data.pendidikan-ditempuh') }}"
                                    class="block px-4 py-2 text-slate-500 hover:text-emerald-600 transition-colors">Pendidikan
                                    (Ditempuh)</a>
                            </li>
                            <li>
                                <a href="{{ route('data.pekerjaan') }}"
                                    class="block px-4 py-2 text-slate-500 hover:text-emerald-600 transition-colors">Pekerjaan</a>
                            </li>
                            <li>
                                <a href="{{ route('data.agama') }}"
                                    class="block px-4 py-2 text-slate-500 hover:text-emerald-600 transition-colors">Agama</a>
                            </li>
                            <li>
                                <a href="{{ route('data.jenis-kelamin') }}"
                                    class="block px-4 py-2 text-slate-500 hover:text-emerald-600 transition-colors">Jenis
                                    Kelamin</a>
                            </li>
                            <li>
                                <a href="{{ route('data.kelompok-umur') }}"
                                    class="block px-4 py-2 text-slate-500 hover:text-emerald-600 transition-colors">Kelompok
                                    Umur</a>
                            </li>
                        </ul>
                    </li>
                    <li x-data="{ openInformasi: false }">
                        <button @click="openInformasi = !openInformasi"
                            class="flex items-center justify-between w-full px-4 py-3 rounded-xl hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                            <span>Informasi</span>
                            <svg :class="{ 'rotate-180': openInformasi }"
                                class="w-5 h-5 transition-transform transform" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <ul x-show="openInformasi" x-transition x-cloak
                            class="pl-4 ml-6 space-y-1 mt-1 text-sm font-semibold border-l-2 border-slate-100">
                            <li>
                                <a href="{{ route('informasi.berita-artikel') }}"
                                    class="block px-4 py-2 text-slate-500 hover:text-emerald-600 transition-colors">Berita
                                    & Artikel</a>
                            </li>
                            <li>
                                <a href="{{ route('informasi.pengumuman') }}"
                                    class="block px-4 py-2 text-slate-500 hover:text-emerald-600 transition-colors">Pengumuman
                                    & Agenda</a>
                            </li>
                            <li>
                                <a href="{{ route('informasi.hukum') }}"
                                    class="block px-4 py-2 text-slate-500 hover:text-emerald-600 transition-colors">Produk
                                    Hukum Desa</a>
                            </li>
                            <li>
                                <a href="{{ route('informasi.publik') }}"
                                    class="block px-4 py-2 text-slate-500 hover:text-emerald-600 transition-colors">Informasi
                                    Publik</a>
                            </li>
                            <li>
                                <a href="{{ route('informasi.galeri') }}"
                                    class="block px-4 py-2 text-slate-500 hover:text-emerald-600 transition-colors">Galeri
                                    Desa</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="{{ route('transparansi') }}"
                            class="block px-4 py-3 rounded-xl hover:bg-emerald-50 hover:text-emerald-600 transition-colors {{ request()->routeIs('transparansi') ? 'bg-emerald-50 text-emerald-600' : '' }}">Transparansi</a>
                    </li>
                    <li>
                        <a href="{{ route('layanan') }}"
                            class="block px-4 py-3 rounded-xl hover:bg-emerald-50 hover:text-emerald-600 transition-colors {{ request()->routeIs('layanan') ? 'bg-emerald-50 text-emerald-600' : '' }}">Layanan</a>
                    </li>
                    <li>
                        <a href="{{ route('lapak') }}"
                            class="block px-4 py-3 rounded-xl hover:bg-emerald-50 hover:text-emerald-600 transition-colors {{ request()->routeIs('lapak*') ? 'bg-emerald-50 text-emerald-600' : '' }}">Lapak
                            Desa</a>
                    </li>

                    {{-- TOMBOL MASUK MOBILE --}}
                    <li class="pt-6 mt-4 border-t border-slate-100">
                        <p class="px-4 mb-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Akses Sistem
                        </p>
                        <div class="flex flex-col gap-3 px-4">
                            <a href="{{ route('login') }}"
                                class="flex items-center gap-4 px-5 py-4 text-sm font-bold text-emerald-700 bg-emerald-50 rounded-2xl hover:bg-emerald-100 hover:-translate-y-0.5 transition-all shadow-sm">
                                <div
                                    class="w-10 h-10 rounded-full bg-emerald-200/50 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-user-tie text-emerald-600 text-lg"></i>
                                </div>
                                <span>Aparatur Desa</span>
                            </a>
                            <a href="{{ route('layanan.mandiri.login') }}"
                                class="flex items-center gap-4 px-5 py-4 text-sm font-bold text-blue-700 bg-blue-50 rounded-2xl hover:bg-blue-100 hover:-translate-y-0.5 transition-all shadow-sm">
                                <div
                                    class="w-10 h-10 rounded-full bg-blue-200/50 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-users text-blue-600 text-lg"></i>
                                </div>
                                <span>Warga Desa</span>
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</header>
