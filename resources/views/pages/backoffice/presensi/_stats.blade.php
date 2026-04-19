{{-- Attendance Summary Cards --}}
<section class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    {{-- Tepat Waktu / Hadir --}}
    <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-16 h-16 bg-green-50 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
        <div class="flex justify-between items-start relative z-10">
            <div>
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Hadir (Tepat Waktu)</p>
                <h3 class="text-3xl font-extrabold text-gray-900">{{ $stats['hadir'] }}</h3>
            </div>
            <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center shrink-0 shadow-inner">
                <i class="fa-solid fa-user-check"></i>
            </div>
        </div>
        <p class="text-[10px] text-gray-500 mt-2 relative z-10 font-medium">Dari {{ $pegawai->count() }} Total Pegawai</p>
    </article>

    {{-- Terlambat --}}
    <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm relative overflow-hidden group border-l-4 border-l-amber-500">
        <div class="flex justify-between items-start relative z-10">
            <div>
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Terlambat</p>
                <h3 class="text-3xl font-extrabold text-amber-600">{{ $stats['terlambat'] }}</h3>
            </div>
            <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center shrink-0 shadow-inner">
                <i class="fa-solid fa-user-clock"></i>
            </div>
        </div>
        <p class="text-[10px] text-gray-500 mt-2 relative z-10 font-medium">Melewati batas waktu</p>
    </article>

    {{-- Izin / Sakit --}}
    <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm relative overflow-hidden group">
        <div class="flex justify-between items-start relative z-10">
            <div>
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Izin / Sakit / Dinas</p>
                <h3 class="text-3xl font-extrabold text-blue-600">{{ $stats['izin'] }}</h3>
            </div>
            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0 shadow-inner">
                <i class="fa-solid fa-user-nurse"></i>
            </div>
        </div>
        <p class="text-[10px] text-gray-500 mt-2 relative z-10 font-medium">Absen dengan Keterangan</p>
    </article>

    {{-- Belum Hadir / Alpha --}}
    <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm relative overflow-hidden group">
        <div class="flex justify-between items-start relative z-10">
            <div>
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Belum Hadir / Alpha</p>
                <h3 class="text-3xl font-extrabold text-red-600">{{ $stats['alpha'] }}</h3>
            </div>
            <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center shrink-0 shadow-inner">
                <i class="fa-solid fa-user-xmark"></i>
            </div>
        </div>
        <p class="text-[10px] text-gray-500 mt-2 relative z-10 font-medium">Belum Presensi Masuk</p>
    </article>
</section>
