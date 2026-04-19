{{-- Page Header --}}
<section class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Data Pertanahan Desa</h1>
        <p class="text-sm text-gray-500 mt-1">Inventarisasi aset lahan desa, hak milik warga, dan fasilitas umum secara spasial.</p>
    </div>

    <div class="flex flex-wrap items-center gap-3">
        <button @click="addModalOpen = true"
            class="bg-green-700 hover:bg-green-800 text-white shadow-md rounded-xl px-5 py-2.5 text-sm font-bold transition-all flex items-center gap-2 cursor-pointer">
            <i class="fa-solid fa-map-location-dot"></i>
            <span>Tambah Data Tanah</span>
        </button>
    </div>
</section>
