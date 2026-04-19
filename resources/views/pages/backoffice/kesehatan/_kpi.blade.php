{{-- KPI Statistics Cards --}}
<section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    {{-- Total Balita --}}
    <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-center relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-16 h-16 bg-blue-50 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
        <div class="relative z-10 flex justify-between items-start">
            <div>
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Total Balita (0-59 Bln)</p>
                <h3 class="text-3xl font-extrabold text-gray-900 leading-none">{{ number_format($totalBalita) }}</h3>
            </div>
            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-children"></i>
            </div>
        </div>
    </article>

    {{-- Stunting Cases --}}
    <article class="bg-white rounded-2xl p-5 border border-red-100 shadow-sm flex flex-col justify-center relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-16 h-16 bg-red-50 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
        <div class="relative z-10 flex justify-between items-start">
            <div>
                <p class="text-[10px] font-bold text-red-500 uppercase tracking-wider mb-1">Kasus Stunting Aktif</p>
                <h3 class="text-3xl font-extrabold text-red-600 leading-none">{{ number_format($stuntingAktif) }}</h3>
            </div>
            <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
        </div>
        @if ($sangatPendek > 0)
            <p class="text-xs font-medium text-red-500 mt-2 relative z-10">{{ $sangatPendek }} Kasus Kategori Sangat Pendek</p>
        @endif
    </article>

    {{-- Prevalence --}}
    <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-center relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-16 h-16 bg-amber-50 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
        <div class="relative z-10 flex justify-between items-start">
            <div>
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Prevalensi Stunting</p>
                <h3 class="text-3xl font-extrabold text-amber-600 leading-none">
                    {{ $prevalensi }}<span class="text-lg">%</span>
                </h3>
            </div>
            <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-percent"></i>
            </div>
        </div>
        <p class="text-xs font-medium text-gray-500 mt-2 relative z-10">Target Nasional: &lt; 14%</p>
    </article>

    {{-- Trend --}}
    <article class="bg-white rounded-2xl p-5 border {{ $trend <= 0 ? 'border-green-100' : 'border-red-100' }} shadow-sm flex flex-col justify-center relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-16 h-16 {{ $trend <= 0 ? 'bg-green-50' : 'bg-red-50' }} rounded-full group-hover:scale-150 transition-transform duration-500"></div>
        <div class="relative z-10 flex justify-between items-start">
            <div>
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Tren Kasus (MoM)</p>
                <h3 class="text-3xl font-extrabold {{ $trend <= 0 ? 'text-green-600' : 'text-red-600' }} leading-none">
                    {{ $trend > 0 ? '+' : '' }}{{ $trend }}
                </h3>
            </div>
            <div class="w-10 h-10 rounded-full {{ $trend <= 0 ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} flex items-center justify-center shrink-0">
                <i class="fa-solid {{ $trend <= 0 ? 'fa-arrow-trend-down' : 'fa-arrow-trend-up' }}"></i>
            </div>
        </div>
        @if ($trend <= 0)
            <p class="text-xs font-bold text-green-600 mt-2 relative z-10 flex items-center gap-1">
                <i class="fa-solid fa-circle-check"></i> Menurun dari bulan lalu
            </p>
        @else
            <p class="text-xs font-bold text-red-600 mt-2 relative z-10 flex items-center gap-1">
                <i class="fa-solid fa-circle-exclamation"></i> Meningkat dari bulan lalu
            </p>
        @endif
    </article>
</section>
