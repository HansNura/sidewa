@props(['produk'])

@php
    // Normalisasi data agar mendukung array (dari JSON/Alpine) maupun objek (dari Eloquent)
    $nama = is_array($produk) ? ($produk['nama'] ?? $produk['name'] ?? '') : ($produk->nama ?? $produk->name ?? '');
    $foto = is_array($produk) ? ($produk['foto'] ?? $produk['image_url'] ?? '') : ($produk->foto ?? $produk->image_url ?? '');
    $harga = is_array($produk) ? ($produk['harga'] ?? $produk['price'] ?? 0) : ($produk->harga ?? $produk->price ?? 0);
    $kategori = is_array($produk) ? ($produk['kategori'] ?? '') : ($produk->category->name ?? $produk->kategori ?? '');
    $pelapak = is_array($produk) ? ($produk['pelapak'] ?? $produk['seller_name'] ?? '') : ($produk->pelapak ?? $produk->seller_name ?? '');
    $slug = is_array($produk) ? ($produk['slug'] ?? '') : ($produk->slug ?? '');
    $whatsapp = is_array($produk) ? ($produk['whatsapp_link'] ?? '#') : ($produk->whatsapp_link ?? '#');
@endphp

<div class="group flex flex-col overflow-hidden transition-all duration-300 bg-white border border-slate-100 shadow-sm rounded-[2rem] hover:shadow-xl hover:-translate-y-1">
    {{-- IMAGE --}}
    <div class="relative h-56 overflow-hidden">
        <img src="{{ $foto }}" alt="{{ $nama }}" class="object-cover w-full h-full transition-transform duration-700 group-hover:scale-110" />
        <div class="absolute top-4 left-4">
            <span class="bg-white/90 backdrop-blur-md text-emerald-700 text-[10px] font-black px-3 py-1.5 rounded-full shadow-sm uppercase tracking-wider">
                {{ $kategori }}
            </span>
        </div>
    </div>

    {{-- CONTENT --}}
    <div class="flex flex-col flex-1 p-6">
        <h4 class="mb-1 text-lg font-bold text-slate-800 line-clamp-1 group-hover:text-emerald-600 transition-colors">
            {{ $nama }}
        </h4>
        <div class="flex items-center gap-1.5 mb-3">
            <div class="w-5 h-5 rounded-full bg-emerald-50 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3 text-emerald-600">
                    <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
                </svg>
            </div>
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-tight">{{ $pelapak }}</span>
        </div>
        
        <p class="text-emerald-600 font-black text-xl mb-5">
            Rp {{ number_format((float)$harga, 0, ',', '.') }}
        </p>

        {{-- BUTTONS --}}
        <div class="flex gap-3 mt-auto">
            <a href="{{ $whatsapp }}" target="_blank" rel="noopener"
               class="flex-1 bg-emerald-600 text-white py-3 px-4 rounded-2xl text-sm font-bold hover:bg-emerald-700 shadow-sm hover:shadow-md transition flex items-center justify-center gap-2">
                <i class="fa-brands fa-whatsapp text-base"></i>
                Beli
            </a>
            <a href="{{ route('lapak.detail', $slug) }}"
               class="flex-1 border-2 border-emerald-50 text-emerald-700 py-3 px-4 rounded-2xl text-sm font-bold hover:bg-emerald-50 transition flex items-center justify-center gap-2">
                Detail
            </a>
        </div>
    </div>
</div>
