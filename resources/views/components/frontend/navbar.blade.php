@php
    // Navbar hanya transparan di halaman Home. Di halaman lain, dia harus solid sejak awal.
    $isHome = request()->routeIs('home');
@endphp

<header :class="{ 'shadow-md bg-white/90 backdrop-blur-lg text-gray-700': sticky || !@json($isHome), 'text-white': !sticky && @json($isHome) }"
    class="fixed top-0 left-0 z-50 w-full transition-all duration-300">
    <div class="flex items-center justify-between px-6 py-4 mx-auto max-w-7xl">
        <a href="{{ url('/') }}" class="flex items-center gap-2">
            <img src="{{ asset('assets/img/logo.webp') }}" alt="Logo Desa" class="w-10 h-10 rounded-full" />
            <div class="flex flex-col leading-tight">
                <span class="text-lg font-semibold transition-colors"
                    :class="{ 'text-primary': sticky || !@json($isHome), 'text-white': !sticky && @json($isHome) }">Desa Sindangmukti</span>
                <span class="text-xs">Kec. Panumbangan, Kab. Ciamis</span>
            </div>
        </a>
        <nav class="hidden lg:flex items-center space-x-8 text-[15px] font-medium">
            <a href="{{ route('home') }}" class="hover:text-primary">Beranda</a>
            <div x-data="{ profilOpen: false }" @mouseenter="profilOpen = true" @mouseleave="profilOpen = false"
                class="relative">
                <button type="button" class="flex items-center gap-1 transition hover:text-primary"
                    :class="{ 'text-primary': profilOpen }" aria-haspopup="true" :aria-expanded="profilOpen">
                    Profil Desa
                    <svg class="w-4 h-4 mt-[2px] transition-transform duration-200"
                        :class="{ 'rotate-180': profilOpen }" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-cloak x-show="profilOpen" x-transition.opacity.duration.150ms
                    class="absolute left-0 w-56 py-2 mt-3 bg-white border border-gray-200 rounded-lg shadow-md text-textdark"
                    role="menu">
                    <a href="{{ route('profil.identitas') }}" class="block px-4 py-2 hover:bg-gray-100">Identitas
                        Desa</a>
                    <a href="{{ route('profil.visi-misi') }}" class="block px-4 py-2 hover:bg-gray-100">Visi & Misi</a>
                    <a href="{{ route('profil.struktur-bpd') }}" class="block px-4 py-2 hover:bg-gray-100">BPD &
                        Lembaga</a>
                    <a href="{{ route('profil.sejarah-desa') }}" class="block px-4 py-2 hover:bg-gray-100">Sejarah
                        Desa</a>
                </div>
            </div>
            <div x-data="{ dataOpen: false }" @mouseenter="dataOpen = true" @mouseleave="dataOpen = false" class="relative">
                <button type="button" class="flex items-center gap-1 transition hover:text-primary"
                    :class="{ 'text-primary': dataOpen }">
                    Data Desa
                    <svg class="w-4 h-4 mt-[2px] transition-transform duration-200" :class="{ 'rotate-180': dataOpen }"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-cloak x-show="dataOpen" x-transition.opacity.duration.150ms
                    class="absolute left-0 w-56 py-2 mt-3 bg-white border border-gray-200 rounded-lg shadow-md text-textdark">
                    <a href="{{ route('data.penduduk') }}" class="block px-4 py-2 hover:bg-gray-100">Data Penduduk</a>
                    <a href="{{ route('data.wilayah') }}" class="block px-4 py-2 hover:bg-gray-100">Data Wilayah</a>
                    <a href="{{ route('data.pendidikan-ditempuh') }}"
                        class="block px-4 py-2 hover:bg-gray-100">Pendidikan (Ditempuh)</a>
                    <a href="{{ route('data.pekerjaan') }}" class="block px-4 py-2 hover:bg-gray-100">Pekerjaan</a>
                    <a href="{{ route('data.agama') }}" class="block px-4 py-2 hover:bg-gray-100">Agama</a>
                    <a href="{{ route('data.jenis-kelamin') }}" class="block px-4 py-2 hover:bg-gray-100">Jenis
                        Kelamin</a>
                    <a href="{{ route('data.kelompok-umur') }}" class="block px-4 py-2 hover:bg-gray-100">Kelompok
                        Umur</a>
                </div>
            </div>
            <div @mouseenter="infoOpen = true" @mouseleave="infoOpen = false" class="relative" x-data="{ infoOpen: false }">
                <button type="button" class="flex items-center gap-1 transition hover:text-primary"
                    :class="{ 'text-primary': infoOpen }">
                    Informasi
                    <svg class="w-4 h-4 mt-[2px] transition-transform duration-200" :class="{ 'rotate-180': infoOpen }"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-cloak x-show="infoOpen" x-transition.opacity.duration.150ms
                    class="absolute left-0 py-2 mt-3 overflow-y-auto bg-white border border-gray-200 rounded-lg shadow-md w-56 text-textdark max-h-96">
                    <a href="{{ route('informasi.berita-artikel') }}" class="block px-4 py-2 hover:bg-gray-100">Berita
                        & Artikel</a>
                    <a href="{{ route('informasi.pengumuman') }}" class="block px-4 py-2 hover:bg-gray-100">Pengumuman
                        & Agenda</a>
                    <a href="{{ route('informasi.hukum') }}" class="block px-4 py-2 hover:bg-gray-100">Produk Hukum
                        Desa</a>
                    <a href="{{ route('informasi.publik') }}" class="block px-4 py-2 hover:bg-gray-100">Informasi
                        Publik</a>
                    <a href="{{ route('informasi.galeri') }}" class="block px-4 py-2 hover:bg-gray-100">Galeri Desa</a>
                </div>
            </div>
            <a href="{{ route('transparansi') }}" class="{{ request()->routeIs('transparansi') ? 'text-primary border-b-2 border-primary pb-1' : 'hover:text-primary' }}">Transparansi</a>
            <a href="{{ route('layanan') }}" class="{{ request()->routeIs('layanan') ? 'text-primary border-b-2 border-primary pb-1' : 'hover:text-primary' }}">Layanan</a>
            <a href="{{ route('lapak') }}" class="{{ request()->routeIs('lapak*') ? 'text-primary border-b-2 border-primary pb-1' : 'hover:text-primary' }}">Lapak Desa</a>
            
            {{-- TOMBOL MASUK (DROPDOWN) --}}
            <div x-data="{ loginOpen: false }" @mouseenter="loginOpen = true" @mouseleave="loginOpen = false" class="relative">
                <button type="button" class="flex items-center gap-2 px-5 py-2.5 text-sm font-bold transition-all rounded-full shadow-sm"
                    :class="{ 'bg-emerald-600 text-white hover:bg-emerald-700': sticky || !@json($isHome), 'bg-white text-emerald-700 hover:bg-emerald-50': !sticky && @json($isHome) }">
                    <i class="fa-solid fa-right-to-bracket"></i> Masuk
                </button>
                <div x-cloak x-show="loginOpen" x-transition.opacity.duration.150ms
                    class="absolute right-0 w-48 py-2 mt-2 bg-white border border-gray-100 rounded-xl shadow-xl text-slate-700">
                    <a href="{{ route('login') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                        <i class="fa-solid fa-user-tie w-4 text-center"></i> Aparatur Desa
                    </a>
                    <a href="{{ route('layanan.mandiri.login') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium hover:bg-blue-50 hover:text-blue-700 transition-colors">
                        <i class="fa-solid fa-users w-4 text-center"></i> Warga Desa
                    </a>
                </div>
            </div>
        </nav>
        <div @click.outside="mobileMenuOpen = false" class="lg:hidden">
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="focus:outline-none" aria-label="Toggle menu"
                :class="{ 'text-primary': sticky || !@json($isHome), 'text-white': !sticky && @json($isHome) }">
                <svg x-show="!mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="mobileMenuOpen" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-7 w-7"
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
                class="absolute left-0 w-full bg-white border-t border-gray-200 shadow-lg top-full">
                <ul class="flex flex-col px-6 py-4 space-y-2 font-medium text-textdark">
                    <li>
                        <a href="{{ url('/') }}" class="block py-2 hover:text-primary">Beranda</a>
                    </li>
                    <li x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center justify-between w-full py-2 hover:text-primary">
                            <span>Profil Desa</span>
                            <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform transform"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <ul x-show="open" x-transition x-cloak
                            class="pl-3 ml-4 space-y-1 text-sm text-gray-700 border-l border-gray-200">
                            <li>
                                <a href="{{ route('profil.identitas') }}"
                                    class="block py-1 hover:text-primary">Identitas Desa</a>
                            </li>
                            <li>
                                <a href="{{ route('profil.visi-misi') }}" class="block py-1 hover:text-primary">Visi
                                    & Misi</a>
                            </li>
                            <li>
                                <a href="{{ route('profil.struktur-bpd') }}"
                                    class="block py-1 hover:text-primary">BPD
                                    & Lembaga</a>
                            </li>
                            <li>
                                <a href="{{ route('profil.sejarah-desa') }}"
                                    class="block py-1 hover:text-primary">Sejarah
                                    Desa</a>
                            </li>
                        </ul>
                    </li>
                    <li x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center justify-between w-full py-2 hover:text-primary">
                            <span>Data Desa</span>
                            <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform transform"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <ul x-show="open" x-transition x-cloak
                            class="pl-3 ml-4 space-y-1 text-sm text-gray-700 border-l border-gray-200">
                            <li>
                                <a href="{{ route('data.penduduk') }}" class="block py-1 hover:text-primary">Data
                                    Penduduk</a>
                            </li>
                            <li>
                                <a href="{{ route('data.wilayah') }}" class="block py-1 hover:text-primary">Data
                                    Wilayah</a>
                            </li>
                            <li>
                                <a href="{{ route('data.pendidikan-ditempuh') }}"
                                    class="block py-1 hover:text-primary">Pendidikan (Ditempuh)</a>
                            </li>
                            <li>
                                <a href="{{ route('data.pekerjaan') }}"
                                    class="block py-1 hover:text-primary">Pekerjaan</a>
                            </li>
                            <li>
                                <a href="{{ route('data.agama') }}" class="block py-1 hover:text-primary">Agama</a>
                            </li>
                            <li>
                                <a href="{{ route('data.jenis-kelamin') }}"
                                    class="block py-1 hover:text-primary">Jenis Kelamin</a>
                            </li>
                            <li>
                                <a href="{{ route('data.kelompok-umur') }}"
                                    class="block py-1 hover:text-primary">Kelompok Umur</a>
                            </li>
                        </ul>
                    </li>
                    <li x-data="{ openInformasi: false }">
                        <button @click="openInformasi = !openInformasi"
                            class="flex items-center justify-between w-full py-2 hover:text-primary">
                            <span>Informasi</span>
                            <svg :class="{ 'rotate-180': openInformasi }"
                                class="w-4 h-4 transition-transform transform" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <ul x-show="openInformasi" x-transition x-cloak
                            class="pl-3 ml-4 space-y-1 overflow-y-auto text-sm text-gray-700 border-l border-gray-200 max-h-64">
                            <li>
                                <a href="{{ route('informasi.berita-artikel') }}"
                                    class="block py-1 hover:text-primary">Berita & Artikel</a>
                            </li>
                            <li>
                                <a href="{{ route('informasi.pengumuman') }}"
                                    class="block py-1 hover:text-primary">Pengumuman
                                    &
                                    Agenda</a>
                            </li>
                            <li>
                                <a href="{{ route('informasi.hukum') }}" class="block py-1 hover:text-primary">Produk
                                    Hukum Desa</a>
                            </li>
                            <li>
                                <a href="{{ route('informasi.publik') }}"
                                    class="block py-1 hover:text-primary">Informasi Publik</a>
                            </li>
                            <li>
                                <a href="{{ route('informasi.galeri') }}"
                                    class="block py-1 hover:text-primary">Galeri
                                    Desa</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="{{ route('transparansi') }}" class="block py-2 {{ request()->routeIs('transparansi') ? 'text-primary font-bold' : 'hover:text-primary' }}">Transparansi</a>
                    </li>
                    <li>
                        <a href="{{ route('layanan') }}" class="block py-2 {{ request()->routeIs('layanan') ? 'text-primary font-bold' : 'hover:text-primary' }}">Layanan</a>
                    </li>
                    <li>
                        <a href="{{ route('lapak') }}" class="block py-2 {{ request()->routeIs('lapak*') ? 'text-primary font-bold' : 'hover:text-primary' }}">Lapak Desa</a>
                    </li>
                    
                    {{-- TOMBOL MASUK MOBILE --}}
                    <li class="pt-4 mt-2 border-t border-slate-100">
                        <p class="mb-3 text-xs font-bold text-slate-400 uppercase tracking-wider">Akses Sistem</p>
                        <div class="flex flex-col gap-2">
                            <a href="{{ route('login') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-emerald-700 bg-emerald-50 rounded-xl hover:bg-emerald-100 transition-colors">
                                <i class="fa-solid fa-user-tie w-4 text-center"></i> Aparatur Desa
                            </a>
                            <a href="{{ route('layanan.mandiri.login') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-blue-700 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors">
                                <i class="fa-solid fa-users w-4 text-center"></i> Warga Desa
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</header>
