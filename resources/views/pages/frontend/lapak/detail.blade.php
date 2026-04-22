@extends('layouts.frontend')

@section('title', $product->name . ' - Lapak Desa Sindangmukti')

@push('styles')
<style>
    .hide-scroll-bar::-webkit-scrollbar { display: none; }
    .hide-scroll-bar { -ms-overflow-style: none; scrollbar-width: none; }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush

@section('content')
<main class="flex-grow pt-16 pb-16 bg-gray-50">
    <div class="px-4 pt-6 pb-10 mx-auto max-w-7xl sm:px-6 lg:px-8">

        {{-- BREADCRUMB NAVIGATION --}}
        <nav class="flex mb-8 text-sm text-gray-500" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="transition-colors hover:text-green-600">
                        <i class="mr-2 fa-solid fa-house"></i>Beranda
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="mx-2 text-xs fa-solid fa-chevron-right"></i>
                        <a href="{{ route('lapak') }}" class="transition-colors hover:text-green-600">Lapak Desa</a>
                    </div>
                </li>
                @if($product->category)
                <li>
                    <div class="flex items-center">
                        <i class="mx-2 text-xs fa-solid fa-chevron-right"></i>
                        <a href="{{ route('lapak', ['kategori' => $product->category->slug]) }}"
                           class="transition-colors hover:text-green-600">{{ $product->category->name }}</a>
                    </div>
                </li>
                @endif
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="mx-2 text-xs fa-solid fa-chevron-right"></i>
                        <span class="font-medium text-gray-800 line-clamp-1">{{ $product->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        {{-- ══════════════════════════════════════════════════ --}}
        {{-- SECTION 1: PRODUCT HERO (Gallery & Main Info)     --}}
        {{-- ══════════════════════════════════════════════════ --}}
        <section class="p-6 mb-12 bg-white border border-gray-200 shadow-sm rounded-3xl md:p-8 lg:p-10">
            <div class="grid grid-cols-1 gap-10 lg:grid-cols-2 lg:gap-12">

                {{-- KIRI: PRODUCT IMAGE GALLERY --}}
                <div class="flex flex-col gap-4" x-data="{ mainImage: '{{ $product->image_url }}' }">
                    {{-- Main Image --}}
                    <figure class="relative w-full overflow-hidden bg-gray-100 border border-gray-200 aspect-square rounded-2xl">
                        <img :src="mainImage" alt="{{ $product->name }}"
                             class="object-cover w-full h-full transition-opacity duration-300">
                        @if($product->stock > 0)
                            <span class="absolute top-4 left-4 bg-green-600 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-md tracking-wide">
                                <i class="mr-1 fa-solid fa-check"></i> Tersedia
                            </span>
                        @else
                            <span class="absolute top-4 left-4 bg-red-500 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-md tracking-wide">
                                Stok Habis
                            </span>
                        @endif
                    </figure>
                </div>

                {{-- KANAN: PRODUCT INFO & CTA --}}
                <div class="flex flex-col justify-start">

                    {{-- Nama & Harga --}}
                    <div class="pb-6 mb-6 border-b border-gray-100">
                        <div class="flex items-center gap-2 mb-3">
                            @if($product->category)
                                <a href="{{ route('lapak', ['kategori' => $product->category->slug]) }}"
                                   class="text-sm font-semibold text-green-600 px-3 py-1 rounded-md bg-green-50 hover:bg-green-100 transition-colors">
                                    {{ $product->category->name }}
                                </a>
                            @endif
                        </div>
                        <h1 class="mb-4 text-3xl font-bold leading-tight text-gray-900 md:text-4xl">{{ $product->name }}</h1>
                        <p class="text-4xl font-black text-green-700">{{ $product->formatted_price }}</p>
                    </div>

                    {{-- Product Attributes --}}
                    <div class="grid grid-cols-2 gap-4 mb-8">
                        <div class="p-4 border border-gray-100 bg-gray-50 rounded-xl">
                            <p class="mb-1 text-xs font-semibold text-gray-500 uppercase">Status Stok</p>
                            <p class="text-sm font-bold text-gray-800">
                                @if($product->stock > 0)
                                    <i class="fa-solid fa-check-circle text-green-500 mr-1.5"></i>
                                @else
                                    <i class="fa-solid fa-times-circle text-red-500 mr-1.5"></i>
                                @endif
                                {{ $product->stock_label }}
                            </p>
                        </div>
                        <div class="p-4 border border-gray-100 bg-gray-50 rounded-xl">
                            <p class="mb-1 text-xs font-semibold text-gray-500 uppercase">Kategori</p>
                            <p class="text-sm font-bold text-gray-800">
                                <i class="fa-solid fa-tag text-gray-400 mr-1.5"></i>
                                {{ $product->category?->name ?? 'Lainnya' }}
                            </p>
                        </div>
                        <div class="p-4 border border-gray-100 bg-gray-50 rounded-xl">
                            <p class="mb-1 text-xs font-semibold text-gray-500 uppercase">Penjual</p>
                            <p class="text-sm font-bold text-gray-800">
                                <i class="fa-solid fa-store text-gray-400 mr-1.5"></i>
                                {{ $product->seller_name }}
                            </p>
                        </div>
                        <div class="p-4 border border-gray-100 bg-gray-50 rounded-xl">
                            <p class="mb-1 text-xs font-semibold text-gray-500 uppercase">Pengiriman</p>
                            <p class="text-sm font-bold text-gray-800">
                                <i class="fa-solid fa-truck-fast text-gray-400 mr-1.5"></i> Dari Desa Sindangmukti
                            </p>
                        </div>
                    </div>

                    {{-- Short Description --}}
                    @if($product->description_html)
                        <div class="mb-8">
                            <h3 class="mb-2 text-sm font-bold text-gray-800">Sekilas Tentang Produk</h3>
                            <div class="text-sm leading-relaxed text-gray-600">
                                {!! Str::limit(strip_tags($product->description_html), 200) !!}
                            </div>
                        </div>
                    @endif

                    {{-- CTA SECTION --}}
                    <div class="mt-auto space-y-4">
                        <a href="{{ $product->whatsapp_link }}" target="_blank" rel="noopener"
                           class="flex items-center justify-center w-full gap-3 px-6 py-4 text-lg font-bold text-white transition-transform transform bg-green-600 shadow-lg hover:bg-green-700 rounded-xl hover:-translate-y-1">
                            <i class="text-2xl fa-brands fa-whatsapp"></i> Pesan via WhatsApp
                        </a>
                        <p class="text-xs font-medium text-center text-gray-500">
                            <i class="mr-1 fa-solid fa-shield-halved"></i> Transaksi dilakukan langsung antara pembeli dan penjual.
                        </p>
                    </div>

                </div>
            </div>
        </section>

        {{-- ══════════════════════════════════════════════════ --}}
        {{-- SECTION 2: DESCRIPTION & SELLER SIDEBAR           --}}
        {{-- ══════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 gap-8 mb-16 lg:grid-cols-3">

            {{-- KOLOM KIRI: Deskripsi Lengkap --}}
            <div class="space-y-8 lg:col-span-2">
                <section class="p-8 bg-white border border-gray-200 shadow-sm rounded-3xl">
                    <h2 class="pb-4 mb-6 text-2xl font-bold text-gray-900 border-b border-gray-100">Deskripsi Lengkap</h2>
                    @if($product->description_html)
                        <div class="text-sm leading-loose text-gray-600 prose max-w-none">
                            {!! $product->description_html !!}
                        </div>
                    @else
                        <p class="text-sm italic text-gray-500">Belum ada deskripsi lengkap untuk produk ini.</p>
                    @endif
                </section>
            </div>

            {{-- KOLOM KANAN: Seller Info Sidebar --}}
            <div class="space-y-6 lg:col-span-1">
                {{-- Seller Profile Card --}}
                <section class="p-6 bg-white border border-gray-200 shadow-sm rounded-3xl">
                    <h3 class="pb-2 mb-4 text-xs font-bold tracking-wider text-gray-500 uppercase border-b border-gray-100">
                        Informasi Penjual
                    </h3>
                    <div class="flex items-center gap-4 mb-5">
                        <div class="flex items-center justify-center w-16 h-16 text-green-600 bg-green-100 border border-green-200 rounded-full shrink-0">
                            <i class="text-2xl fa-solid fa-store"></i>
                        </div>
                        <div>
                            <h4 class="flex items-center gap-2 text-lg font-bold text-gray-900">
                                {{ $product->seller_name }}
                                @if($product->seller_phone)
                                    <i class="text-sm text-blue-500 fa-solid fa-circle-check" title="Terverifikasi Pemdes"></i>
                                @endif
                            </h4>
                            <p class="flex items-center mt-1 text-xs text-gray-500">
                                <i class="fa-solid fa-location-dot mr-1.5 text-red-400"></i> Desa Sindangmukti
                            </p>
                        </div>
                    </div>

                    <div class="mb-6 space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Bergabung sejak</span>
                            <span class="font-medium text-gray-800">{{ $product->created_at->translatedFormat('F Y') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Pengiriman via</span>
                            <span class="font-medium text-gray-800">COD / Ambil Sendiri</span>
                        </div>
                    </div>

                    <a href="{{ route('lapak', ['q' => $product->seller_name]) }}"
                       class="block w-full py-2.5 text-sm font-bold text-center text-green-600 transition-colors bg-white border-2 border-green-600 hover:bg-green-600 hover:text-white rounded-xl">
                        Lihat Produk Lainnya
                    </a>
                </section>

                {{-- Trust Badge --}}
                <section class="flex items-start gap-4 p-5 border border-blue-100 bg-blue-50 rounded-2xl">
                    <i class="mt-1 text-2xl text-blue-500 fa-solid fa-shield-halved"></i>
                    <div>
                        <h4 class="mb-1 text-sm font-bold text-blue-900">Produk UMKM Binaan Desa</h4>
                        <p class="text-xs leading-relaxed text-blue-700">
                            Produk ini telah didata dan diverifikasi kelayakannya oleh Pemerintah Desa Sindangmukti.
                            Kami mendukung penuh pemberdayaan ekonomi warga.
                        </p>
                    </div>
                </section>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════ --}}
        {{-- SECTION 3: RELATED PRODUCTS                       --}}
        {{-- ══════════════════════════════════════════════════ --}}
        @if($related->count() > 0)
            <section class="pt-12 border-t border-gray-200">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl font-bold text-gray-900">Mungkin Anda Juga Suka</h2>
                    <a href="{{ route('lapak') }}"
                       class="text-sm font-semibold text-green-600 transition-colors hover:text-green-800">
                        Lihat Semua <i class="ml-1 fa-solid fa-arrow-right"></i>
                    </a>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach($related as $item)
                        <article class="flex flex-col overflow-hidden transition-all bg-white border border-gray-200 shadow-sm rounded-2xl hover:shadow-lg hover:-translate-y-1 group">
                            <figure class="relative overflow-hidden bg-gray-100 aspect-square">
                                <img src="{{ $item->image_url }}" alt="{{ $item->name }}"
                                     class="object-cover w-full h-full transition-transform duration-500 group-hover:scale-105" loading="lazy">
                                @if($item->category)
                                    <span class="absolute top-3 left-3 bg-white/90 backdrop-blur text-gray-800 text-xs font-bold px-2.5 py-1 rounded-md shadow-sm">
                                        {{ $item->category->name }}
                                    </span>
                                @endif
                            </figure>
                            <div class="flex flex-col flex-grow p-4">
                                <h3 class="mb-1 font-bold leading-tight text-gray-800 transition-colors line-clamp-2 group-hover:text-green-600">
                                    {{ $item->name }}
                                </h3>
                                <div class="flex items-center gap-2 mb-3">
                                    <i class="text-xs text-gray-400 fa-solid fa-store"></i>
                                    <span class="text-xs font-medium text-gray-500">{{ $item->seller_name }}</span>
                                    @if($item->seller_phone)
                                        <i class="fa-solid fa-circle-check text-blue-500 text-[10px]"></i>
                                    @endif
                                </div>
                                <div class="mt-auto">
                                    <p class="mb-4 text-lg font-black text-green-700">{{ $item->formatted_price }}</p>
                                    <a href="{{ route('lapak.detail', $item->slug) }}"
                                       class="flex items-center justify-center w-full gap-2 py-2 text-sm font-bold text-green-600 transition-colors bg-white border border-green-300 rounded-lg hover:bg-green-50">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

    </div>
</main>
@endsection
