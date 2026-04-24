@extends('layouts.frontend')

@section('title', $pageTitle . ' - Desa Sindangmukti')

@push('styles')
<style>
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush

@section('content')
<main class="flex-grow pt-16 bg-gray-50">

    <!-- SECTION 1: HEADER SECTION -->
    <section class="bg-gradient-to-br from-green-800 to-green-600 text-white py-16 md:py-20 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]">
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <span
                class="bg-green-700/50 text-green-100 text-sm font-semibold px-3 py-1 rounded-full border border-green-500/30 mb-4 inline-block">Warta Desa</span>
            <h1 class="text-4xl md:text-5xl font-bold mb-4 tracking-tight">{{ $pageTitle }}</h1>
            <p class="text-lg text-green-100 max-w-2xl mx-auto leading-relaxed">
                {{ $pageSubtitle }}
            </p>
        </div>
    </section>

    <div class="px-4 py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">

        <!-- SECTION 2: SEARCH BAR & KATEGORI BERITA -->
        <section class="flex flex-col items-center justify-between gap-4 p-4 mb-10 bg-white border border-gray-200 shadow-sm rounded-2xl md:p-5 md:flex-row">

            <!-- Kategori Berita (Filter Pills) -->
            <div class="flex flex-wrap w-full gap-2 md:w-auto">
                <a href="{{ route('informasi.berita-artikel') }}"
                   class="px-4 py-2 text-sm font-semibold rounded-full shadow-sm transition-colors
                   {{ !$activeCategory || $activeCategory === 'semua' ? 'bg-green-600 text-white hover:bg-green-700' : 'bg-gray-50 text-gray-600 border border-gray-200 hover:bg-gray-100 hover:text-green-600' }}">
                    Semua
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('informasi.berita-artikel', ['kategori' => $cat->slug]) }}"
                       class="px-4 py-2 text-sm font-medium rounded-full transition-colors
                       {{ $activeCategory === $cat->slug ? 'bg-green-600 text-white font-semibold shadow-sm hover:bg-green-700' : 'bg-gray-50 text-gray-600 border border-gray-200 hover:bg-gray-100 hover:text-green-600' }}">
                        {{ $cat->nama_kategori }}
                    </a>
                @endforeach
            </div>

            <!-- Search Bar -->
            <form action="{{ route('informasi.berita-artikel') }}" method="GET" class="relative w-full md:w-72 lg:w-96">
                @if($activeCategory)
                    <input type="hidden" name="kategori" value="{{ $activeCategory }}">
                @endif
                <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Cari artikel..."
                    class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </form>
        </section>

        <!-- SECTION 3: FEATURED NEWS (Sorotan Utama) -->
        @if($sorotanUtama && !$search && (!$activeCategory || $activeCategory === 'semua'))
        <section class="mb-12">
            <div class="flex items-center gap-2 mb-6">
                <i class="fa-solid fa-fire text-orange-500 text-xl"></i>
                <h2 class="text-2xl font-bold text-gray-800">Sorotan Utama</h2>
            </div>

            <a href="{{ route('informasi.berita-detail', $sorotanUtama->slug) }}"
               class="bg-white rounded-2xl shadow-md border border-gray-200 overflow-hidden flex flex-col md:flex-row group hover:shadow-lg transition-all block">
                <!-- Image -->
                <figure class="relative w-full overflow-hidden h-64 md:h-auto md:w-1/2 lg:w-3/5">
                    <img src="{{ $sorotanUtama->thumbnail_url }}" alt="{{ $sorotanUtama->judul }}"
                         class="object-cover w-full h-full transition-transform duration-700 transform group-hover:scale-105">
                    <div class="absolute top-4 left-4 bg-red-600 text-white text-xs font-bold px-3 py-1.5 rounded-md shadow-md uppercase tracking-wide">
                        Headline
                    </div>
                </figure>
                <!-- Content -->
                <div class="flex flex-col justify-center w-full p-6 md:w-1/2 lg:w-2/5 md:p-8">
                    <div class="flex items-center gap-3 mb-3 text-xs font-medium text-gray-500">
                        <span class="flex items-center">
                            <i class="fa-regular fa-calendar mr-1.5"></i> {{ $sorotanUtama->formatted_date }}
                        </span>
                        <span class="text-gray-300">|</span>
                        <span class="flex items-center text-green-600">
                            <i class="fa-regular fa-folder mr-1.5"></i> {{ $sorotanUtama->category_name }}
                        </span>
                    </div>
                    <h3 class="mb-4 text-2xl font-bold text-gray-800 transition-colors lg:text-3xl group-hover:text-green-700">
                        {{ $sorotanUtama->judul }}
                    </h3>
                    <p class="mb-6 leading-relaxed text-gray-600 line-clamp-3">
                        {{ $sorotanUtama->excerpt }}
                    </p>
                    <div class="flex items-center justify-between mt-auto">
                        <div class="flex items-center gap-2">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($sorotanUtama->author_name) }}&background=0D8ABC&color=fff" alt="Author" class="w-8 h-8 rounded-full">
                            <span class="text-sm font-semibold text-gray-700">{{ $sorotanUtama->author_name }}</span>
                        </div>
                        <span class="inline-flex items-center text-green-600 font-semibold text-sm group-hover:translate-x-1 transition-transform duration-300">
                            Baca Selengkapnya <i class="fa-solid fa-arrow-right ml-1"></i>
                        </span>
                    </div>
                </div>
            </a>
        </section>
        @endif

        <!-- SECTION 4: LIST BERITA (Grid Card Artikel) -->
        <section class="mb-14">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">
                    @if($search)
                        Hasil Pencarian: "{{ $search }}"
                    @elseif($activeCategory && $activeCategory !== 'semua')
                        Artikel Kategori: {{ $categories->firstWhere('slug', $activeCategory)?->nama_kategori ?? $activeCategory }}
                    @else
                        Artikel Terbaru
                    @endif
                </h2>
                @if($search || ($activeCategory && $activeCategory !== 'semua'))
                    <a href="{{ route('informasi.berita-artikel') }}" class="text-sm text-green-600 hover:text-green-800 font-medium flex items-center gap-1">
                        <i class="fa-solid fa-times"></i> Reset Filter
                    </a>
                @endif
            </div>

            @if($daftarArtikel->count() > 0)
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 md:gap-8">
                @foreach($daftarArtikel as $artikel)
                <article class="flex flex-col h-full overflow-hidden transition-all bg-white border border-gray-200 shadow-sm rounded-xl hover:-translate-y-1 hover:shadow-md group">
                    <figure class="relative h-48 overflow-hidden">
                        <img src="{{ $artikel->thumbnail_url }}" alt="{{ $artikel->judul }}"
                             class="object-cover w-full h-full transition-transform duration-500 transform group-hover:scale-105">
                        <span class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm text-green-700 text-xs font-bold px-2.5 py-1 rounded-md shadow-sm">
                            {{ $artikel->category_name }}
                        </span>
                    </figure>
                    <div class="flex flex-col flex-grow p-5">
                        <div class="flex items-center gap-3 mb-2 text-xs font-medium text-gray-500">
                            <span><i class="fa-regular fa-clock mr-1"></i> {{ $artikel->formatted_date }}</span>
                            @if($artikel->view_count > 0)
                                <span class="text-gray-300">|</span>
                                <span><i class="fa-regular fa-eye mr-1"></i> {{ number_format($artikel->view_count) }}x</span>
                            @endif
                        </div>
                        <h3 class="mb-2 text-lg font-bold leading-tight text-gray-800 transition-colors group-hover:text-green-600">
                            {{ $artikel->judul }}
                        </h3>
                        <p class="flex-grow mb-4 text-sm text-gray-600 line-clamp-3">
                            {{ $artikel->excerpt }}
                        </p>
                        <a href="{{ route('informasi.berita-detail', $artikel->slug) }}"
                           class="block w-full py-2 mt-auto text-sm font-semibold text-center text-green-600 transition-colors border border-gray-200 rounded-lg bg-gray-50 hover:bg-green-600 hover:text-white">
                            Baca Artikel
                        </a>
                    </div>
                </article>
                @endforeach
            </div>
            @else
            <div class="text-center py-16 bg-white rounded-2xl border border-gray-200">
                <i class="fa-regular fa-newspaper text-5xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-bold text-gray-700 mb-2">Belum Ada Artikel</h3>
                <p class="text-sm text-gray-500">
                    @if($search)
                        Tidak ditemukan artikel dengan kata kunci "{{ $search }}".
                    @else
                        Artikel untuk kategori ini belum tersedia saat ini.
                    @endif
                </p>
            </div>
            @endif
        </section>

        <!-- SECTION 5: PAGINATION (Laravel native) -->
        @if($daftarArtikel->hasPages())
        <section class="mt-12 flex justify-center">
            {{ $daftarArtikel->links() }}
        </section>
        @endif

    </div>
</main>
@endsection
