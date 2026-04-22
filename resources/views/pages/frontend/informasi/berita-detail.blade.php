@extends('layouts.frontend')

@section('title', $article->judul . ' - Desa Sindangmukti')

@push('styles')
<style>
    .prose img { border-radius: 0.75rem; margin: 1.5rem 0; }
    .prose h2 { font-size: 1.5rem; font-weight: 700; margin-top: 2rem; margin-bottom: 0.75rem; color: #1f2937; }
    .prose h3 { font-size: 1.25rem; font-weight: 600; margin-top: 1.5rem; margin-bottom: 0.5rem; color: #374151; }
    .prose p { margin-bottom: 1rem; line-height: 1.8; color: #4b5563; }
    .prose ul, .prose ol { margin-left: 1.5rem; margin-bottom: 1rem; }
    .prose li { margin-bottom: 0.25rem; color: #4b5563; }
    .prose blockquote { border-left: 4px solid #10b981; padding: 1rem 1.5rem; margin: 1.5rem 0; background: #f0fdf4; border-radius: 0 0.5rem 0.5rem 0; }
    .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
</style>
@endpush

@section('content')
<main class="flex-grow pt-16 bg-gray-50">

    <!-- Breadcrumb -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <nav class="flex items-center text-sm text-gray-500 gap-2">
                <a href="{{ route('home') }}" class="hover:text-green-600 transition-colors">Beranda</a>
                <i class="fa-solid fa-chevron-right text-xs text-gray-300"></i>
                <a href="{{ route('informasi.berita-artikel') }}" class="hover:text-green-600 transition-colors">Berita & Artikel</a>
                <i class="fa-solid fa-chevron-right text-xs text-gray-300"></i>
                @if($article->category)
                    <a href="{{ route('informasi.berita-artikel', ['kategori' => $article->category->slug]) }}" class="hover:text-green-600 transition-colors">{{ $article->category_name }}</a>
                    <i class="fa-solid fa-chevron-right text-xs text-gray-300"></i>
                @endif
                <span class="text-gray-800 font-medium truncate max-w-xs">{{ \Str::limit($article->judul, 50) }}</span>
            </nav>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

            <!-- Main Content -->
            <article class="lg:col-span-2">
                <!-- Cover Image -->
                <figure class="relative rounded-2xl overflow-hidden mb-8 shadow-md">
                    <img src="{{ $article->thumbnail_url }}" alt="{{ $article->judul }}"
                         class="w-full h-72 md:h-96 object-cover">
                    @if($article->category)
                        <span class="absolute top-4 left-4 bg-green-600 text-white text-xs font-bold px-3 py-1.5 rounded-md shadow-md uppercase tracking-wide">
                            {{ $article->category_name }}
                        </span>
                    @endif
                </figure>

                <!-- Meta -->
                <div class="flex flex-wrap items-center gap-4 mb-6 text-sm text-gray-500">
                    <span class="flex items-center gap-1.5">
                        <i class="fa-regular fa-calendar"></i> {{ $article->formatted_date }}
                    </span>
                    <span class="flex items-center gap-1.5">
                        <i class="fa-regular fa-user"></i> {{ $article->author_name }}
                    </span>
                    <span class="flex items-center gap-1.5">
                        <i class="fa-regular fa-eye"></i> {{ number_format($article->view_count) }} kali dibaca
                    </span>
                </div>

                <!-- Title -->
                <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-6 leading-tight">
                    {{ $article->judul }}
                </h1>

                <!-- Content Body -->
                <div class="prose max-w-none">
                    {!! $article->konten_html !!}
                </div>

                <!-- Share Buttons -->
                <div class="mt-10 pt-6 border-t border-gray-200">
                    <p class="text-sm font-semibold text-gray-700 mb-3">Bagikan Artikel:</p>
                    <div class="flex gap-3">
                        <a href="https://wa.me/?text={{ urlencode($article->judul . ' - ' . url()->current()) }}" target="_blank"
                           class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
                            <i class="fa-brands fa-whatsapp"></i> WhatsApp
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank"
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
                            <i class="fa-brands fa-facebook-f"></i> Facebook
                        </a>
                        <button onclick="navigator.clipboard.writeText('{{ url()->current() }}'); alert('Link berhasil disalin!')"
                                class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
                            <i class="fa-regular fa-copy"></i> Salin Link
                        </button>
                    </div>
                </div>
            </article>

            <!-- Sidebar -->
            <aside class="lg:col-span-1 space-y-8">

                <!-- Author Info -->
                <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                    <h3 class="font-bold text-gray-800 mb-4 text-sm uppercase tracking-wider">Penulis</h3>
                    <div class="flex items-center gap-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($article->author_name) }}&background=16a34a&color=fff&size=80"
                             alt="{{ $article->author_name }}" class="w-12 h-12 rounded-full">
                        <div>
                            <p class="font-bold text-gray-800">{{ $article->author_name }}</p>
                            <p class="text-xs text-gray-500">Diterbitkan {{ $article->formatted_date }}</p>
                        </div>
                    </div>
                </div>

                <!-- Related Articles -->
                @if($related->count() > 0)
                <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                    <h3 class="font-bold text-gray-800 mb-4 text-sm uppercase tracking-wider">Artikel Terkait</h3>
                    <div class="space-y-4">
                        @foreach($related as $rel)
                        <a href="{{ route('informasi.berita-detail', $rel->slug) }}" class="flex gap-3 group">
                            <img src="{{ $rel->thumbnail_url }}" alt="{{ $rel->judul }}"
                                 class="w-20 h-16 object-cover rounded-lg flex-shrink-0">
                            <div class="min-w-0">
                                <h4 class="text-sm font-semibold text-gray-800 line-clamp-2 group-hover:text-green-600 transition-colors">
                                    {{ $rel->judul }}
                                </h4>
                                <p class="text-xs text-gray-500 mt-1">{{ $rel->formatted_date }}</p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Back Link -->
                <a href="{{ route('informasi.berita-artikel') }}"
                   class="block w-full text-center bg-gray-50 hover:bg-green-600 hover:text-white text-green-600 font-semibold py-3 rounded-xl border border-gray-200 transition-colors">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Semua Artikel
                </a>
            </aside>

        </div>
    </div>
</main>
@endsection
