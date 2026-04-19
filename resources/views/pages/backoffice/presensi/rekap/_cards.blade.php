<section class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Total Kehadiran</p>
        <div class="flex items-center gap-3">
            <h3 class="text-3xl font-extrabold text-gray-900">{{ $stats['hadir'] }}</h3>
        </div>
        <p class="text-[10px] text-gray-400 mt-2">Log masuk tepat waktu</p>
    </article>
    <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm border-l-4 border-l-amber-500">
        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Total Terlambat</p>
        <div class="flex items-center gap-3">
            <h3 class="text-3xl font-extrabold text-amber-600">{{ $stats['terlambat'] }}</h3>
        </div>
        <p class="text-[10px] text-gray-400 mt-2">Masuk melewati batas</p>
    </article>
    <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm border-l-4 border-l-blue-500">
        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Izin / Sakit / Dinas</p>
        <div class="flex items-center gap-3">
            <h3 class="text-3xl font-extrabold text-blue-600">{{ $stats['izin'] }}</h3>
        </div>
        <p class="text-[10px] text-gray-400 mt-2">Terdokumentasi sistem</p>
    </article>
    <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm border-l-4 border-l-red-500">
        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Alpha / Belum Presensi</p>
        <div class="flex items-center gap-3">
            <h3 class="text-3xl font-extrabold text-red-600">{{ $stats['alpha'] }}</h3>
        </div>
        <p class="text-[10px] text-gray-400 mt-2">Memerlukan tindak lanjut</p>
    </article>
</section>
