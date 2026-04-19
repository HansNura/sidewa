<section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <!-- Total APBDes (Belanja) -->
    <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between group">
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Pagu Anggaran Belanja</p>
            <h3 class="text-xl font-extrabold text-gray-900">
                Rp {{ number_format($summary['belanja'], 0, ',', '.') }}
            </h3>
        </div>
        <div class="mt-4 flex items-center justify-between">
            <span class="text-[9px] font-bold text-gray-400 bg-gray-50 px-2 py-0.5 rounded border border-gray-100 uppercase">Target T.A {{ $tahun }}</span>
            <div class="w-8 h-8 rounded-lg bg-gray-100 text-gray-400 group-hover:bg-green-50 group-hover:text-green-600 transition-colors flex items-center justify-center">
                <i class="fa-solid fa-bullseye text-xs"></i>
            </div>
        </div>
    </article>

    <!-- Total Realisasi -->
    <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between group">
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Total Realisasi Dana</p>
            <h3 class="text-xl font-extrabold text-green-700">
                Rp {{ number_format($summary['belanja_realisasi'], 0, ',', '.') }}
            </h3>
        </div>
        <div class="mt-4 flex items-center justify-between">
            <span class="text-[9px] font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded border border-green-100 uppercase">Per Hari Ini</span>
            <div class="w-8 h-8 rounded-lg bg-green-100 text-green-700 flex items-center justify-center">
                <i class="fa-solid fa-check-double text-xs"></i>
            </div>
        </div>
    </article>

    <!-- Sisa Anggaran -->
    <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between group border-l-4 border-l-amber-500">
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Sisa Pagu Anggaran</p>
            @php $sisa = $summary['belanja'] - $summary['belanja_realisasi'] @endphp
            <h3 class="text-xl font-extrabold text-gray-900">
               Rp {{ number_format($sisa, 0, ',', '.') }}
            </h3>
        </div>
        <div class="mt-4 flex items-center justify-between">
            <span class="text-[9px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded border border-amber-100 uppercase">Sedia Digunakan</span>
            <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center">
                <i class="fa-solid fa-clock-rotate-left text-xs"></i>
            </div>
        </div>
    </article>

    <!-- Persentase Penyerapan -->
    @php 
        $pctTotal = $summary['belanja'] > 0 ? ($summary['belanja_realisasi'] / $summary['belanja']) * 100 : 0; 
    @endphp
    <article class="bg-gray-900 rounded-2xl p-5 text-white shadow-xl flex flex-col justify-center text-center relative overflow-hidden">
        <div class="absolute inset-0 bg-green-900/20 backdrop-blur-3xl z-0"></div>
        <div class="relative z-10">
            <p class="text-[10px] font-bold text-green-400 uppercase tracking-wider mb-1">Persentase Penyerapan</p>
            <h3 class="text-4xl font-black text-white">{{ number_format($pctTotal, 1) }}<span class="text-xl font-medium text-green-400">%</span></h3>
            <div class="w-full bg-gray-700 h-1.5 rounded-full mt-3 overflow-hidden">
                <div class="bg-green-500 h-full" style="width: {{ min(100, $pctTotal) }}%"></div>
            </div>
        </div>
    </article>
</section>
