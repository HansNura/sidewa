{{-- KPI Summary Cards --}}
<section class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    {{-- Total Permohonan --}}
    <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-center">
        <div class="flex justify-between items-start mb-2">
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Total Permohonan</p>
            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-inbox text-sm"></i>
            </div>
        </div>
        <h3 class="text-3xl font-extrabold text-gray-900 leading-none">{{ $totalPermohonan }}</h3>
        <p class="text-[10px] text-gray-500 mt-2">Periode: {{ $periode === 'hari' ? 'Hari Ini' : ($periode === 'minggu' ? 'Minggu Ini' : 'Bulan Ini') }}</p>
    </article>

    {{-- Sedang Diproses --}}
    <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-center">
        <div class="flex justify-between items-start mb-2">
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Sedang Diproses (Aktif)</p>
            <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-spinner text-sm"></i>
            </div>
        </div>
        <h3 class="text-3xl font-extrabold text-amber-600 leading-none">{{ $sedangDiproses }}</h3>
        <p class="text-[10px] text-gray-500 mt-2">Membutuhkan perhatian operator</p>
    </article>

    {{-- Selesai --}}
    <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-center">
        <div class="flex justify-between items-start mb-2">
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Surat Selesai</p>
            <div class="w-8 h-8 rounded-lg bg-green-50 text-green-600 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-check-double text-sm"></i>
            </div>
        </div>
        <h3 class="text-3xl font-extrabold text-green-600 leading-none">{{ $suratSelesai }}</h3>
        <p class="text-[10px] text-gray-500 mt-2">Telah dicetak dan di-TTE</p>
    </article>

    {{-- Rata-rata Proses --}}
    <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-center">
        <div class="flex justify-between items-start mb-2">
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Rata-rata Proses</p>
            <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-stopwatch text-sm"></i>
            </div>
        </div>
        <h3 class="text-3xl font-extrabold text-purple-700 leading-none">{{ $avgJam }} <span class="text-sm font-medium text-gray-500">Jam</span></h3>
        <p class="text-[10px] text-gray-500 mt-2">
            @if ($avgJam > 0 && $avgJam <= 24)
                <span class="text-green-500 font-bold">✓</span> Di bawah SLA (24 Jam)
            @elseif ($avgJam > 24)
                <span class="text-red-500 font-bold">!</span> Melebihi SLA
            @else
                Belum ada data
            @endif
        </p>
    </article>
</section>
