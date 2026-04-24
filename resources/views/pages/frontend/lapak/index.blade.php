@extends('layouts.frontend')

@section('title', $pageTitle . ' - Desa Sindangmukti')

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .hide-scroll-bar::-webkit-scrollbar { display: none; }
    .hide-scroll-bar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush

@section('content')
<main class="flex-grow pt-16 bg-gray-50">

    {{-- SECTION 1: HEADER LAPAK DESA --}}
    <section class="relative py-12 overflow-hidden text-white bg-gradient-to-br from-green-900 to-green-700 md:py-16">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        <div class="relative z-10 px-4 mx-auto text-center max-w-7xl sm:px-6 lg:px-8">
            <span class="bg-green-800/80 text-green-100 text-sm font-semibold px-4 py-1.5 rounded-full border border-green-500/50 mb-4 inline-flex items-center gap-1.5">
                <i class="fa-solid fa-shop"></i> UMKM & Ekonomi Kreatif
            </span>
            <h1 class="mb-4 text-4xl font-bold tracking-tight md:text-5xl">{{ $pageTitle }}</h1>
            <p class="max-w-2xl mx-auto text-lg leading-relaxed text-green-100">
                {{ $pageSubtitle }}
            </p>
            <p class="mt-3 text-sm text-green-200/80">
                <i class="mr-1 fa-solid fa-box-open"></i> {{ $totalProducts }} produk tersedia
            </p>
        </div>
    </section>

    <div class="px-4 py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">

        {{-- SECTION 2: BANNER PROMOSI --}}
        <section class="mb-10">
            <div class="relative overflow-hidden shadow-md cursor-pointer bg-gradient-to-r from-amber-500 to-orange-500 rounded-2xl group">
                <div class="absolute top-0 right-0 h-full transition-transform duration-700 origin-top-right transform w-64 bg-white/10 skew-x-12 group-hover:scale-110"></div>
                <div class="relative z-10 flex flex-col items-center justify-between px-8 py-8 md:p-10 md:flex-row">
                    <div class="mb-6 text-center text-white md:w-2/3 md:text-left md:mb-0">
                        <span class="inline-block px-3 py-1 mb-3 text-xs font-bold tracking-wider text-white uppercase rounded bg-white/20 backdrop-blur-sm">Promo Spesial Bulan Ini</span>
                        <h2 class="mb-2 text-2xl font-bold md:text-3xl">Festival Kerajinan Bambu Sindangmukti</h2>
                        <p class="text-sm opacity-90 text-amber-50 md:text-base">Dapatkan diskon hingga 20% untuk semua produk anyaman bambu asli buatan pengrajin lokal desa kami.</p>
                    </div>
                    <div class="shrink-0">
                        <button class="flex items-center gap-2 px-6 py-3 font-bold transition-transform transform bg-white rounded-xl shadow-lg text-amber-600 hover:bg-gray-50 hover:-translate-y-1">
                            Lihat Koleksi <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        {{-- SECTION 3: SEARCH, FILTER & SORT --}}
        <form method="GET" action="{{ route('lapak') }}" id="filterForm"
              class="sticky flex flex-col justify-between gap-4 p-4 mb-10 bg-white border border-gray-200 shadow-sm md:p-5 rounded-2xl lg:flex-row top-20 z-40">

            {{-- Kategori Filter Tags --}}
            <div class="flex gap-2 pb-2 overflow-x-auto md:pb-0 hide-scroll-bar flex-grow">
                <a href="{{ route('lapak', array_merge(request()->except('kategori', 'page'), [])) }}"
                   class="px-5 py-2.5 text-sm font-semibold rounded-full shadow-sm shrink-0 transition-colors
                       {{ !$activeCategory || $activeCategory === 'semua' ? 'bg-green-600 text-white' : 'bg-gray-50 text-gray-600 border border-gray-200 hover:bg-green-50 hover:text-green-600 hover:border-green-300' }}">
                    Semua
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('lapak', array_merge(request()->except('page'), ['kategori' => $cat->slug])) }}"
                       class="px-5 py-2.5 text-sm font-medium rounded-full shrink-0 transition-colors
                           {{ $activeCategory === $cat->slug ? 'bg-green-600 text-white font-semibold shadow-sm' : 'bg-gray-50 text-gray-600 border border-gray-200 hover:bg-green-50 hover:text-green-600 hover:border-green-300' }}">
                        @if($cat->icon)
                            <i class="{{ $cat->icon }} mr-1.5"></i>
                        @endif
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>

            <div class="flex flex-col gap-3 shrink-0 sm:flex-row">
                {{-- Search Bar --}}
                <div class="relative w-full sm:w-64">
                    <input type="text" name="q" value="{{ $search }}" placeholder="Cari produk..."
                        class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>

                {{-- Sort Option --}}
                <select name="sort" onchange="document.getElementById('filterForm').submit()"
                    class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 p-2.5 font-medium outline-none cursor-pointer">
                    <option value="terbaru" {{ $sort === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                    <option value="harga_rendah" {{ $sort === 'harga_rendah' ? 'selected' : '' }}>Harga: Rendah ke Tinggi</option>
                    <option value="harga_tinggi" {{ $sort === 'harga_tinggi' ? 'selected' : '' }}>Harga: Tinggi ke Rendah</option>
                    <option value="nama" {{ $sort === 'nama' ? 'selected' : '' }}>Nama A-Z</option>
                </select>

                {{-- Hidden: preserve kategori --}}
                @if($activeCategory)
                    <input type="hidden" name="kategori" value="{{ $activeCategory }}">
                @endif
            </div>
        </form>

        {{-- SECTION 4: GRID PRODUK UMKM --}}
        <section class="mb-14">
            @if($products->count() > 0)
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach($products as $produk)
                        <x-frontend.lapak-item :produk="$produk" />
                    @endforeach
                </div>
            @else
                {{-- Empty State --}}
                <div class="py-16 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 mb-6 rounded-full bg-gray-100">
                        <i class="fa-solid fa-box-open text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">Produk Tidak Ditemukan</h3>
                    <p class="text-gray-500 mb-6 max-w-md mx-auto">
                        @if($search)
                            Tidak ada produk yang cocok dengan pencarian "<strong>{{ $search }}</strong>".
                        @else
                            Belum ada produk di kategori ini.
                        @endif
                    </p>
                    <a href="{{ route('lapak') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fa-solid fa-arrow-left"></i> Lihat Semua Produk
                    </a>
                </div>
            @endif
        </section>

        {{-- SECTION 5: PAGINATION --}}
        @if($products->hasPages())
            <section class="flex justify-center mb-16">
                {{ $products->links() }}
            </section>
        @endif

        {{-- SECTION 6: CTA UMKM --}}
        <section class="relative overflow-hidden border border-green-800 shadow-xl bg-green-900 rounded-3xl">
            <div class="absolute w-64 h-64 bg-green-500 rounded-full opacity-20 -left-10 -bottom-10 blur-3xl"></div>
            <div class="absolute right-10 top-10 text-white/5">
                <i class="fa-solid fa-store text-9xl"></i>
            </div>
            <div class="relative z-10 flex flex-col items-center justify-between gap-8 px-8 py-12 text-center md:flex-row md:px-14 md:py-16 md:text-left">
                <div class="md:w-2/3">
                    <h2 class="mb-4 text-3xl font-bold text-white md:text-4xl">Punya Usaha di Desa Sindangmukti?</h2>
                    <p class="mb-0 text-lg leading-relaxed text-green-100">
                        Ayo perluas jangkauan pasar Anda. Daftarkan produk UMKM, kerajinan, atau hasil tani Anda secara gratis di Lapak Desa dan temukan pembeli baru.
                    </p>
                </div>
                <div class="flex justify-center w-full shrink-0 md:justify-end md:w-1/3">
                    <a href="https://wa.me/6281234567890?text={{ urlencode('Halo Admin, saya ingin mendaftarkan produk UMKM saya di Lapak Desa Sindangmukti.') }}"
                       target="_blank" rel="noopener"
                       class="flex items-center justify-center w-full gap-2 px-8 py-4 font-bold text-white transition-transform transform shadow-lg bg-amber-500 hover:bg-amber-600 rounded-2xl hover:scale-105 md:w-auto">
                        <i class="fa-solid fa-user-plus text-xl"></i> Daftar Penjual
                    </a>
                </div>
            </div>
        </section>

    </div>
</main>
@endsection
