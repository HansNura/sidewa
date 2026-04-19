<section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Total Kunjungan</p>
        <div class="flex items-center gap-3">
            <h3 class="text-3xl font-extrabold text-gray-900">{{ $stats['total'] }}</h3>
            @if($stats['pertumbuhan'] > 0)
                <span class="text-xs font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded-full">+{{ $stats['pertumbuhan'] }}%</span>
            @elseif($stats['pertumbuhan'] < 0)
                <span class="text-xs font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded-full">{{ $stats['pertumbuhan'] }}%</span>
            @endif
        </div>
        <p class="text-[10px] text-gray-400 mt-2">Seluruh sumber input (Kiosk & Admin)</p>
    </article>
    
    <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm border-l-4 border-l-blue-500">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Rata-rata Harian</p>
        <div class="flex items-center gap-3">
            <h3 class="text-3xl font-extrabold text-blue-600">{{ $stats['rata_rata_harian'] }}</h3>
            <span class="text-xs font-medium text-gray-400">tamu/hari</span>
        </div>
        <p class="text-[10px] text-gray-400 mt-2">Berdasarkan hari berjalan bulan ini</p>
    </article>

    <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm border-l-4 border-l-amber-500">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Instansi Terbanyak</p>
        <div class="flex items-center gap-3">
            <h3 class="text-xl font-extrabold text-gray-800 truncate" title="{{ $stats['instansi_teratas'] }}">{{ Str::limit($stats['instansi_teratas'], 15) }}</h3>
        </div>
        <p class="text-[10px] text-gray-400 mt-2">{{ $stats['instansi_count'] }} Kunjungan total</p>
    </article>

    <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm border-l-4 border-l-green-500">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Layanan Mandiri</p>
        <div class="flex items-center gap-3">
            <h3 class="text-3xl font-extrabold text-green-700">{{ $stats['persentase_kiosk'] }}%</h3>
        </div>
        <p class="text-[10px] text-gray-400 mt-2">Efektivitas penggunaan Kiosk</p>
    </article>
</section>
