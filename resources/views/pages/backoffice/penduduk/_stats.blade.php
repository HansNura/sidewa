{{-- Statistics Cards --}}
<section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    {{-- Total Penduduk --}}
    <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 text-xl">
            <i class="fa-solid fa-users"></i>
        </div>
        <div>
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Total Penduduk</p>
            <h3 class="text-2xl font-extrabold text-gray-900 leading-none">{{ number_format($stats['total']) }}</h3>
        </div>
    </article>

    {{-- Laki-laki / Perempuan --}}
    <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 text-xl">
            <i class="fa-solid fa-venus-mars"></i>
        </div>
        <div>
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Laki-laki / Perempuan</p>
            <div class="flex items-center text-sm font-bold text-gray-900">
                <span class="text-blue-600">{{ number_format($stats['laki']) }}</span>
                <span class="mx-2 text-gray-300">|</span>
                <span class="text-pink-500">{{ number_format($stats['perempuan']) }}</span>
            </div>
        </div>
    </article>

    {{-- Usia Produktif --}}
    <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 text-xl">
            <i class="fa-solid fa-user-tie"></i>
        </div>
        <div>
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Usia Produktif (15-64)</p>
            <h3 class="text-2xl font-extrabold text-gray-900 leading-none">
                {{ number_format($stats['produktif']) }}
                @if ($stats['total_hidup'] > 0)
                    <span class="text-sm font-medium text-gray-500">({{ round($stats['produktif'] / $stats['total_hidup'] * 100) }}%)</span>
                @endif
            </h3>
        </div>
    </article>

    {{-- Status Hidup --}}
    <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-green-50 text-green-600 flex items-center justify-center shrink-0 text-xl">
            <i class="fa-solid fa-heart-pulse"></i>
        </div>
        <div>
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Penduduk Aktif (Hidup)</p>
            <h3 class="text-2xl font-extrabold text-gray-900 leading-none">{{ number_format($stats['total_hidup']) }}</h3>
        </div>
    </article>
</section>
