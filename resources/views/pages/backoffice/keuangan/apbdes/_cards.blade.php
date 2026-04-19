<section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <!-- Total Pendapatan -->
    <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between">
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Total Pendapatan</p>
            <h3 class="text-xl font-extrabold text-gray-900" x-text="formatIDR({{ $summary['pendapatan'] }})">
                Rp {{ number_format($summary['pendapatan']) }}
            </h3>
        </div>
        <div class="flex items-center justify-between mt-4">
            <span class="text-[10px] font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded uppercase">Murni</span>
            <div class="w-8 h-8 rounded-lg bg-green-100 text-green-600 flex items-center justify-center">
                <i class="fa-solid fa-arrow-trend-up text-xs"></i>
            </div>
        </div>
    </article>

    <!-- Total Belanja -->
    <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between">
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Total Belanja</p>
            <h3 class="text-xl font-extrabold text-gray-900" x-text="formatIDR({{ $summary['belanja'] }})">
                Rp {{ number_format($summary['belanja']) }}
            </h3>
        </div>
        <div class="flex items-center justify-between mt-4">
            <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded uppercase">Alokasi Terdata</span>
            <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                <i class="fa-solid fa-money-bill-wave text-xs"></i>
            </div>
        </div>
    </article>

    <!-- Surplus / Defisit -->
    <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between {{ $summary['surplus'] >= 0 ? 'border-l-4 border-l-green-600' : 'border-l-4 border-l-red-600' }}">
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Surplus / Defisit</p>
            <h3 class="text-xl font-extrabold {{ $summary['surplus'] >= 0 ? 'text-green-700' : 'text-red-700' }}" x-text="formatIDR({{ $summary['surplus'] }})">
                {{ $summary['surplus'] >= 0 ? '+' : '' }} Rp {{ number_format($summary['surplus']) }}
            </h3>
        </div>
        <div class="flex items-center justify-between mt-4">
            @if($summary['surplus'] >= 0)
                <span class="text-[10px] font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded uppercase">Anggaran Sehat</span>
                <div class="w-8 h-8 rounded-lg bg-green-100 text-green-700 flex items-center justify-center">
                    <i class="fa-solid fa-scale-balanced text-xs"></i>
                </div>
            @else
                <span class="text-[10px] font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded uppercase">Defisit</span>
                <div class="w-8 h-8 rounded-lg bg-red-100 text-red-700 flex items-center justify-center">
                    <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                </div>
            @endif
        </div>
    </article>

    <!-- Total Item Anggaran -->
    <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-center text-center">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Item Mata Anggaran</p>
        <h3 class="text-3xl font-extrabold text-gray-800">{{ $summary['item_count'] }}</h3>
        <p class="text-[10px] text-gray-500 mt-1">Kegiatan APBDes TA {{ $tahun }}</p>
    </article>
</section>
