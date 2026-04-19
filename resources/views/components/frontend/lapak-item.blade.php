@props(['produk'])

<div class="flex flex-col overflow-hidden transition bg-white border border-gray-100 shadow-sm rounded-2xl hover:shadow-lg">
    <img src="{{ $produk['foto'] }}" alt="{{ $produk['nama'] }}" class="object-cover w-full h-52" />
    <div class="flex flex-col flex-1 p-5">
        <h4 class="mb-1 text-lg font-semibold text-gray-800">{{ $produk['nama'] }}</h4>
        <p class="text-[#2E7D32] font-bold mb-2">Rp {{ number_format($produk['harga'], 0, ',', '.') }}</p>
        <p class="mb-1 text-sm text-gray-600">
            <strong>Kategori:</strong>
            <span>{{ $produk['kategori'] }}</span>
        </p>
        <p class="mb-3 text-sm text-gray-600">
            <strong>Pelapak:</strong>
            <span>{{ $produk['pelapak'] }}</span>
        </p>
        <div class="flex gap-3 mt-auto">
            <!-- Beli -->
            <button class="flex-1 bg-[#2E7D32] text-white py-2 px-3 rounded-lg text-sm hover:bg-[#256928] transition flex items-center justify-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4">
                    <path d="M2.5 3A1.5 1.5 0 0 0 1 4.5V5a.5.5 0 0 0 .5.5H2a.5.5 0 0 0 .484-.375A2.484 2.484 0 0 1 4.312 3H2.5zM14 4.5A1.5 1.5 0 0 0 12.5 3h-8.188a2.484 2.484 0 0 1 1.826 1.5H13.5a.5.5 0 0 0 .5-.5V4.5zM1.5 6a.5.5 0 0 0-.5.5v2A1.5 1.5 0 0 0 2.5 10H3a.5.5 0 0 0 .484-.375A2.484 2.484 0 0 1 5.312 8H1.5V6zM14.5 6H5.312a2.484 2.484 0 0 1 1.826 1.5H14.5a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zM3.484 10.625A.5.5 0 0 0 3 11h1.312a2.484 2.484 0 0 1 1.826 1.5H1.5v-1a.5.5 0 0 0-.5-.5A1.5 1.5 0 0 0 0 12.5v2A1.5 1.5 0 0 0 1.5 16h13A1.5 1.5 0 0 0 16 14.5v-2A1.5 1.5 0 0 0 14.5 11H6.688a2.484 2.484 0 0 1-1.826-1.5H3.484z" />
                </svg>
                Beli
            </button>
            <!-- Detail -->
            <button class="flex-1 border border-[#2E7D32] text-[#2E7D32] py-2 px-3 rounded-lg text-sm hover:bg-[#2E7D32]/10 transition flex items-center justify-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4">
                    <path fill-rule="evenodd" d="M8.5 3a.5.5 0 0 0-1 0v.518a3.5 3.5 0 0 0-2.35 1.14l-.367-.368a.5.5 0 0 0-.708.708l.368.367A3.5 3.5 0 0 0 3.518 7.5H3a.5.5 0 0 0 0 1h.518a3.5 3.5 0 0 0 1.14 2.35l-.368.367a.5.5 0 1 0 .708.708l.367-.368A3.5 3.5 0 0 0 7.5 12.482V13a.5.5 0 0 0 1 0v-.518a3.5 3.5 0 0 0 2.35-1.14l.367.368a.5.5 0 0 0 .708-.708l-.368-.367A3.5 3.5 0 0 0 12.482 8.5H13a.5.5 0 0 0 0-1h-.518a3.5 3.5 0 0 0-1.14-2.35l.368-.367a.5.5 0 1 0-.708-.708l-.367.368A3.5 3.5 0 0 0 8.5 3.518V3zM8 5.5A2.5 2.5 0 1 0 8 10.5 2.5 2.5 0 0 0 8 5.5z" clip-rule="evenodd" />
                </svg>
                Detail
            </button>
        </div>
    </div>
</div>
