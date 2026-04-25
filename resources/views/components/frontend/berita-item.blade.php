@props(['artikel'])

@php
    $is_array = is_array($artikel);
    $judul = $is_array ? ($artikel['judul'] ?? '') : ($artikel->judul ?? '');
    $slug = $is_array ? ($artikel['slug'] ?? '') : ($artikel->slug ?? '');
    $url = $is_array ? ($artikel['url'] ?? route('informasi.berita-detail', $slug)) : route('informasi.berita-detail', $slug);
    $foto = $is_array ? ($artikel['imgSrc'] ?? $artikel['thumbnail_url'] ?? '') : ($artikel->thumbnail_url ?? '');
    $tanggal = $is_array ? ($artikel['tanggal'] ?? $artikel['formatted_date'] ?? '') : ($artikel->formatted_date ?? '');
    $views = $is_array ? ($artikel['views'] ?? $artikel['view_count'] ?? 0) : ($artikel->view_count ?? 0);
    $admin = $is_array ? ($artikel['admin'] ?? $artikel['author_name'] ?? 'Admin') : ($artikel->author_name ?? 'Admin');
    $kategori = $is_array ? ($artikel['kategori'] ?? $artikel['category_name'] ?? '') : ($artikel->category_name ?? '');
    $excerpt = $is_array ? ($artikel['excerpt'] ?? '') : ($artikel->excerpt ?? '');
@endphp

<article class="group flex flex-col h-full overflow-hidden transition-all duration-300 bg-white border border-slate-100 shadow-sm rounded-[2rem] hover:shadow-xl hover:-translate-y-1">
    {{-- IMAGE --}}
    <div class="relative h-56 overflow-hidden bg-slate-100 shrink-0">
        <a href="{{ $url }}" class="block w-full h-full">
            <img src="{{ $foto }}" alt="{{ $judul }}" class="object-cover w-full h-full transition-transform duration-700 group-hover:scale-110" loading="lazy" />
        </a>
        @if($kategori)
        <div class="absolute top-4 left-4">
            <span class="bg-white/90 backdrop-blur-md text-emerald-700 text-[10px] font-black px-3 py-1.5 rounded-full shadow-sm uppercase tracking-wider">
                {{ $kategori }}
            </span>
        </div>
        @endif
    </div>

    {{-- CONTENT --}}
    <div class="flex flex-col flex-1 p-6 sm:p-8">
        {{-- META INFO --}}
        <div class="flex items-center gap-3 mb-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">
            <span class="flex items-center gap-1.5">
                <i class="fa-regular fa-calendar text-emerald-500 text-sm"></i> {{ $tanggal }}
            </span>
            @if((int)$views > 0)
            <span class="text-slate-300">|</span>
            <span class="flex items-center gap-1.5">
                <i class="fa-regular fa-eye text-emerald-500 text-sm"></i> {{ number_format((int)$views) }}
            </span>
            @endif
        </div>

        {{-- TITLE --}}
        <h4 class="mb-3 text-xl font-bold text-slate-800 line-clamp-2 group-hover:text-emerald-600 transition-colors leading-snug">
            <a href="{{ $url }}">{{ $judul }}</a>
        </h4>

        {{-- EXCERPT --}}
        @if($excerpt)
        <p class="flex-grow mb-6 text-sm leading-relaxed text-slate-600 line-clamp-3">
            {{ $excerpt }}
        </p>
        @else
        <div class="flex-grow mb-6"></div>
        @endif

        {{-- AUTHOR & CTA --}}
        <div class="flex items-center justify-between mt-auto pt-5 border-t border-slate-100">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 font-bold text-xs uppercase">
                    {{ substr($admin, 0, 1) }}
                </div>
                <span class="text-xs font-bold text-slate-700">{{ $admin }}</span>
            </div>
            <a href="{{ $url }}" class="inline-flex items-center text-sm font-bold text-emerald-600 group-hover:text-emerald-700 transition-colors">
                Baca <i class="fa-solid fa-arrow-right ml-1.5 transform group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>
</article>
