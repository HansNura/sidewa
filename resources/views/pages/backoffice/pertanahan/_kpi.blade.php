{{-- KPI Statistics --}}
<section class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    {{-- Total Luas --}}
    <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-center">
        <div class="flex items-center gap-2 mb-2">
            <div class="w-8 h-8 rounded-lg bg-gray-100 text-gray-600 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-map"></i>
            </div>
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Luas Desa</p>
        </div>
        <h3 class="text-2xl font-extrabold text-gray-900 leading-none">{{ $totalHa }} <span class="text-sm font-medium text-gray-500">Ha</span></h3>
    </article>

    {{-- Aset Desa --}}
    <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-center">
        <div class="flex items-center gap-2 mb-2">
            <div class="w-8 h-8 rounded-lg bg-green-50 text-green-600 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-building-flag"></i>
            </div>
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Aset Desa (TKD)</p>
        </div>
        <h3 class="text-2xl font-extrabold text-green-700 leading-none">{{ $desaHa }} <span class="text-sm font-medium text-green-600/70">Ha ({{ $pctDesa }}%)</span></h3>
    </article>

    {{-- Milik Warga --}}
    <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-center">
        <div class="flex items-center gap-2 mb-2">
            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-users"></i>
            </div>
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Tanah Warga / Pribadi</p>
        </div>
        <h3 class="text-2xl font-extrabold text-blue-700 leading-none">{{ $wargaHa }} <span class="text-sm font-medium text-blue-600/70">Ha ({{ $pctWarga }}%)</span></h3>
    </article>

    {{-- Fasilitas Umum --}}
    <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-center">
        <div class="flex items-center gap-2 mb-2">
            <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-road"></i>
            </div>
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Fasilitas Umum</p>
        </div>
        <h3 class="text-2xl font-extrabold text-amber-700 leading-none">{{ $fasumHa }} <span class="text-sm font-medium text-amber-600/70">Ha ({{ $pctFasum }}%)</span></h3>
    </article>
</section>
