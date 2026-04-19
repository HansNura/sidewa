@extends('layouts.frontend')

@section('title', $produk['nama'] . ' - Lapak Desa Sindangmukti')

@push('styles')
<style>
    .hide-scroll-bar::-webkit-scrollbar {
        display: none;
    }
    .hide-scroll-bar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush

@section('content')
<main class="flex-grow pb-16 pt-24 bg-gray-50">

    <div class="px-4 pt-6 pb-10 mx-auto max-w-7xl sm:px-6 lg:px-8">

        <!-- BREADCRUMB NAVIGATION -->
        <nav class="flex mb-8 text-sm text-gray-500" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center transition-colors hover:text-green-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Beranda
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        <a href="{{ route('lapak') }}" class="transition-colors hover:text-green-600">Lapak Desa</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        <span class="text-gray-500">{{ $produk['kategori'] }}</span>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        <span class="font-medium text-gray-800 line-clamp-1">{{ $produk['nama'] }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- SECTION 1: PRODUCT HERO (Gallery & Main Info) -->
        <section class="p-6 mb-12 bg-white border border-gray-200 shadow-sm rounded-3xl md:p-8 lg:p-10">
            <div class="grid grid-cols-1 gap-10 lg:grid-cols-2 lg:gap-12">

                <!-- KIRI: PRODUCT IMAGE GALLERY -->
                <div class="flex flex-col gap-4" x-data="{ activeImage: '{{ $produk['gambar_utama'] }}' }">
                    <!-- Main Image -->
                    <figure class="relative overflow-hidden border border-gray-200 bg-gray-100 w-full aspect-square rounded-2xl">
                        <img :src="activeImage" alt="{{ $produk['nama'] }}" class="object-cover w-full h-full transition-opacity duration-300">
                        <!-- Badge -->
                        <span class="absolute px-3 py-1.5 text-xs font-bold tracking-wide text-white bg-green-600 shadow-md top-4 left-4 rounded-lg">Terlaris</span>
                    </figure>

                    <!-- Thumbnails -->
                    <div class="flex gap-3 pb-2 overflow-x-auto hide-scroll-bar">
                        @foreach($produk['galeri'] as $index => $gambar)
                        <button @click="activeImage = '{{ $gambar }}'" 
                                :class="{ 'border-green-500 opacity-100': activeImage === '{{ $gambar }}', 'border-transparent opacity-70 hover:border-green-300 hover:opacity-100': activeImage !== '{{ $gambar }}' }"
                                class="relative overflow-hidden transition-all border-2 rounded-xl shrink-0 w-20 h-20 md:w-24 md:h-24">
                            <img src="{{ $gambar }}" alt="Thumbnail {{ $index + 1 }}" class="object-cover w-full h-full">
                        </button>
                        @endforeach
                    </div>
                </div>

                <!-- KANAN: PRODUCT INFO & CTA -->
                <div class="flex flex-col justify-start">

                    <!-- Nama & Harga -->
                    <div class="pb-6 mb-6 border-b border-gray-100">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="px-3 py-1 text-sm font-semibold text-green-600 transition-colors rounded-md bg-green-50 hover:bg-green-100">
                                {{ $produk['kategori'] }}
                            </span>
                            <span class="text-sm text-gray-400">•</span>
                            <span class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                {{ $produk['rating'] }} ({{ $produk['ulasan_count'] }} Ulasan)
                            </span>
                        </div>
                        <h1 class="mb-4 text-3xl font-bold leading-tight text-gray-900 md:text-4xl">{{ $produk['nama'] }}</h1>
                        <p class="text-4xl font-black text-green-700">{{ $produk['harga'] }}</p>
                    </div>

                    <!-- Product Attributes -->
                    <div class="grid grid-cols-2 gap-4 mb-8">
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <p class="mb-1 text-xs font-semibold tracking-wider text-gray-500 uppercase">Status Stok</p>
                            <p class="flex items-center text-sm font-bold text-gray-800">
                                <svg class="w-4 h-4 mr-1.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                                {{ $produk['stok'] }}
                            </p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <p class="mb-1 text-xs font-semibold tracking-wider text-gray-500 uppercase">Berat Produk</p>
                            <p class="flex items-center text-sm font-bold text-gray-800">
                                <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg> 
                                {{ $produk['berat'] }}
                            </p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <p class="mb-1 text-xs font-semibold tracking-wider text-gray-500 uppercase">Ketahanan</p>
                            <p class="flex items-center text-sm font-bold text-gray-800">
                                <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> 
                                {{ $produk['ketahanan'] }}
                            </p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <p class="mb-1 text-xs font-semibold tracking-wider text-gray-500 uppercase">Pengiriman</p>
                            <p class="flex items-center text-sm font-bold text-gray-800">
                                <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg> 
                                {{ $produk['pengiriman'] }}
                            </p>
                        </div>
                    </div>

                    <!-- Short Description -->
                    <div class="mb-8">
                        <h3 class="mb-2 text-sm font-bold text-gray-800">Sekilas Tentang Produk</h3>
                        <p class="text-sm leading-relaxed text-gray-600">
                            {{ $produk['deskripsi_singkat'] }}
                        </p>
                    </div>

                    <!-- CTA SECTION -->
                    <div class="mt-auto space-y-4">
                        <a href="https://wa.me/6281234567890?text={{ urlencode('Halo '.$produk['toko']['nama'].', saya ingin memesan produk:\n\n*'.$produk['nama'].'*\n\nApakah stoknya masih tersedia?') }}" target="_blank" class="flex items-center justify-center w-full gap-3 px-6 py-4 text-lg font-bold text-white transition-transform transform shadow-lg rounded-xl bg-green-600 hover:bg-green-700 hover:-translate-y-1">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M12.0006 2.05203C6.46337 2.05203 1.95465 6.55018 1.95465 12.0838C1.95465 13.8427 2.40938 15.5451 3.25997 17.0673L2.09115 21.4338L6.61109 20.2526C8.0645 21.0503 9.7118 21.4883 11.3934 21.4883C11.3963 21.4883 11.3992 21.4883 12.0006 21.4883C17.535 21.4883 22.0466 16.99 22.0466 11.4565C22.0437 8.78441 21.0001 6.27364 19.1054 4.38289C17.2098 2.49021 14.6932 1.44855 12.0006 1.44855V2.05203ZM12.0006 19.8055C10.4907 19.8055 9.02055 19.4005 7.74737 18.6438L7.44754 18.4655L4.76727 19.1678L5.49129 16.536L5.2971 16.2238C4.47141 14.9224 4.03265 13.3857 4.03265 11.8211C4.03265 7.42418 7.61869 3.84196 12.0209 3.84196C14.1565 3.84486 16.1472 4.6766 17.6534 6.18522C19.1576 7.69383 19.9855 9.68832 19.9855 11.8288C19.9855 16.2248 16.4024 19.8055 12.0006 19.8055ZM16.3861 13.805C16.1457 13.6841 14.9657 13.102 14.7472 13.0217C14.5267 12.9424 14.3663 12.9018 14.2059 13.1436C14.0454 13.3854 13.5843 13.928 13.4442 14.1283C13.3031 14.3295 13.162 14.3508 12.9215 14.2299C12.6811 14.109 11.8986 13.8546 10.9703 13.0333C10.2458 12.394 9.76159 11.597 9.62047 11.3562C9.47936 11.1154 9.60505 10.9858 9.72688 10.8658C9.83516 10.7585 9.96665 10.5844 10.0875 10.4442C10.2074 10.304 10.247 10.2034 10.3273 10.043C10.4075 9.8825 10.3679 9.7423 10.3079 9.6214C10.2479 9.5005 9.76642 8.31575 9.56637 7.83415C9.36632 7.35255 9.16723 7.43282 9.02611 7.43282C8.885 7.43282 8.72559 7.41249 8.56617 7.41249C8.40675 7.41249 8.14629 7.47245 7.92583 7.71325C7.70537 7.95405 7.08343 8.53582 7.08343 9.72051C7.08343 10.9052 7.94614 12.0494 8.06604 12.2099C8.18594 12.3704 9.76545 14.8576 12.23 15.922C12.8166 16.1754 13.2721 16.3263 13.6294 16.4385C14.2202 16.6261 14.7578 16.598 15.1833 16.5275C15.6562 16.4491 16.6385 15.9269 16.8395 15.3655C17.0396 14.804 17.0396 14.3225 16.9796 14.2229C16.9196 14.1223 16.7592 14.0624 16.5187 13.9425L16.3861 13.805Z"></path></svg> 
                            Pesan via WhatsApp
                        </a>
                        <p class="text-xs font-medium text-center text-gray-500">
                            <svg class="inline w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg> 
                            Transaksi dilakukan langsung antara pembeli dan penjual.
                        </p>
                    </div>

                </div>
            </div>
        </section>

        <!-- SECTION 2: SELLER INFO & FULL DESCRIPTION (Grid Layout) -->
        <div class="grid grid-cols-1 gap-8 mb-16 lg:grid-cols-3">

            <!-- KOLOM KIRI (Full Description) -->
            <div class="space-y-8 lg:col-span-2">
                <section class="p-8 bg-white border border-gray-200 shadow-sm rounded-3xl">
                    <h2 class="pb-4 mb-6 text-2xl font-bold text-gray-900 border-b border-gray-100">Deskripsi Lengkap</h2>
                    <div class="text-sm leading-loose text-gray-600 prose max-w-none">
                        <p class="mb-4">Hadirkan cita rasa tradisional yang autentik di setiap gigitan dengan <strong>{{ $produk['nama'] }}</strong> produksi {{ $produk['toko']['nama'] }} Desa Sindangmukti. Dibuat menggunakan resep turun-temurun, produk ini merupakan pilihan terbaik.</p>

                        <h4 class="mt-6 mb-2 font-bold text-gray-800">Komposisi/Bahan Pembentuk Produk:</h4>
                        <ul class="pl-5 mb-4 space-y-1 list-disc">
                            <li>Bahan baku segar dari petani/pengrajin lokal</li>
                            <li>Kualitas premium</li>
                            <li>Proses pembuatan higienis</li>
                        </ul>

                        <h4 class="mt-6 mb-2 font-bold text-gray-800">Keunggulan Produk Kami:</h4>
                        <ul class="pl-5 mb-4 space-y-1 list-disc">
                            <li><strong>Berkualitas:</strong> Dibuat dengan standar yang baik.</li>
                            <li><strong>Pemberdayaan Lokal:</strong> Dengan membeli produk ini, Anda turut membantu perekonomian warga Desa Sindangmukti.</li>
                        </ul>

                        <p class="p-4 mt-6 border rounded-lg text-amber-600 bg-amber-50 border-amber-100">
                            <em>Saran: Hubungi penjual terlebih dahulu untuk memastikan ketersediaan stok atau varian produk.</em>
                        </p>
                    </div>
                </section>
            </div>

            <!-- KOLOM KANAN (Seller Info Sidebar) -->
            <div class="space-y-6 lg:col-span-1">
                <!-- Seller Profile Card -->
                <section class="p-6 bg-white border border-gray-200 shadow-sm rounded-3xl">
                    <h3 class="pb-2 mb-4 text-xs font-bold tracking-wider text-gray-500 uppercase border-b border-gray-100">Informasi Penjual</h3>
                    <div class="flex items-center gap-4 mb-5">
                        <div class="flex items-center justify-center w-16 h-16 text-green-600 border border-green-200 shrink-0 bg-green-100 rounded-full">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <div>
                            <h4 class="flex items-center gap-2 text-lg font-bold text-gray-900">
                                {{ $produk['toko']['nama'] }}
                                @if($produk['toko']['terverifikasi'])
                                    <svg class="w-4 h-4 text-blue-500" title="Terverifikasi Pemdes" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                @endif
                            </h4>
                            <p class="flex items-center mt-1 text-xs text-gray-500">
                                <svg class="w-3 h-3 mr-1.5 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg> 
                                {{ $produk['toko']['lokasi'] }}
                            </p>
                        </div>
                    </div>
                    <div class="mb-6 space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Bergabung sejak</span>
                            <span class="font-medium text-gray-800">{{ $produk['toko']['bergabung'] }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Total Produk</span>
                            <span class="font-medium text-gray-800">{{ $produk['toko']['total_produk'] }} Produk</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Pengiriman via</span>
                            <span class="font-medium text-gray-800">{{ $produk['toko']['kurir'] }}</span>
                        </div>
                    </div>
                    <a href="#" class="block w-full py-2.5 text-sm font-bold text-center text-green-600 transition-colors bg-white border-2 border-green-600 rounded-xl hover:bg-green-600 hover:text-white">
                        Kunjungi Toko
                    </a>
                </section>

                <!-- Trust Badge -->
                <section class="flex gap-4 items-start p-5 border rounded-2xl bg-blue-50 border-blue-100">
                    <svg class="w-8 h-8 mt-1 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    <div>
                        <h4 class="mb-1 text-sm font-bold text-blue-900">Produk UMKM Binaan Desa</h4>
                        <p class="text-xs leading-relaxed text-blue-700">Produk ini telah didata dan direkomendasikan kelayakannya oleh BUMDes Sindangmukti. Kami mendukung penuh pemberdayaan ekonomi warga lokal.</p>
                    </div>
                </section>
            </div>
        </div>

        <!-- SECTION 3: RELATED PRODUCTS (Produk Serupa) -->
        <section class="pt-12 border-t border-gray-200">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-bold text-gray-900">Mungkin Anda Juga Suka</h2>
                <a href="{{ route('lapak') }}" class="text-sm font-semibold text-green-600 transition-colors hover:text-green-800">
                    Lihat Semua <svg class="inline w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>

            <!-- Grid Reusable -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($terkait as $item)
                <article class="flex flex-col overflow-hidden transition-all bg-white border border-gray-200 shadow-sm rounded-2xl hover:shadow-lg hover:-translate-y-1 group">
                    <figure class="relative overflow-hidden bg-gray-100 aspect-square">
                        <img src="{{ $item['gambar'] }}" alt="{{ $item['nama'] }}" class="object-cover w-full h-full transition-transform duration-500 group-hover:scale-105">
                        <span class="absolute top-3 left-3 bg-white/90 backdrop-blur text-gray-800 text-xs font-bold px-2.5 py-1 rounded-md shadow-sm">{{ $item['kategori'] }}</span>
                    </figure>
                    <div class="flex flex-col flex-grow p-4">
                        <h3 class="mb-1 font-bold leading-tight text-gray-800 transition-colors line-clamp-2 group-hover:text-green-600">
                            {{ $item['nama'] }}
                        </h3>
                        <div class="flex items-center gap-2 mb-3">
                            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            <span class="text-xs font-medium text-gray-500">{{ $item['toko'] }}</span>
                        </div>
                        <div class="mt-auto">
                            <p class="mb-4 text-lg font-black text-green-700">{{ $item['harga'] }}</p>
                            <a href="{{ route('lapak.detail', $item['slug']) }}" class="flex items-center justify-center w-full gap-2 py-2 text-sm font-bold text-green-600 transition-colors bg-white border border-green-300 rounded-lg hover:bg-green-50">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>
        </section>

    </div>
</main>
@endsection
