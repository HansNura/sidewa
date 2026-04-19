<div class="px-6 py-5 border-b border-gray-100 flex justify-between items-start bg-gray-50/50 shrink-0 border-t">
    <div>
        @if($rencana->prioritas == 'tinggi')
            <span class="text-[10px] font-bold text-red-600 bg-red-100 px-2 py-0.5 rounded uppercase tracking-wider mb-1 inline-flex items-center gap-1 border border-red-200">Sangat Mendesak</span>
        @elseif($rencana->prioritas == 'sedang')
            <span class="text-[10px] font-bold text-amber-600 bg-amber-100 px-2 py-0.5 rounded uppercase tracking-wider mb-1 inline-flex items-center gap-1 border border-amber-200">Mendesak</span>
        @else
            <span class="text-[10px] font-bold text-blue-600 bg-blue-100 px-2 py-0.5 rounded uppercase tracking-wider mb-1 inline-flex items-center gap-1 border border-blue-200">Normal</span>
        @endif
        
        <h3 class="font-extrabold text-xl text-gray-900 mt-1 leading-tight nama-program-text">{{ $rencana->nama_program }}</h3>
        <p class="text-xs text-gray-500 mt-1">{{ strtoupper($rencana->jenis_rencana) }} Tahun/Periode {{ $rencana->tahun_pelaksanaan }}</p>
    </div>
</div>

<div class="p-6 overflow-y-auto custom-scrollbar flex-1 space-y-6 pb-20">

    <!-- Objective & Target -->
    <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100">
        <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Tujuan & Sasaran Program</h5>
        <p class="text-sm text-gray-800 leading-relaxed mb-3">{{ $rencana->tujuan_sasaran ?: 'Tidak ada deskripsi tujuan spesifik yang diisikan.' }}</p>
        <div class="flex flex-wrap gap-2 mt-2 pt-3 border-t border-gray-200 text-xs">
            <span class="bg-white border border-gray-200 text-gray-600 font-bold px-2 py-1 rounded">Bidang: <b>{{ $rencana->kategori }}</b></span>
        </div>
    </div>

    <!-- Budget Plan -->
    <div>
        <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Estimasi Anggaran (RAB)</h5>
        <div class="flex items-center justify-between bg-white border border-gray-200 p-4 rounded-xl shadow-sm">
            <div>
                <p class="text-[10px] text-gray-500 uppercase font-semibold">Total Pagu Draft</p>
                <p class="text-lg font-extrabold text-gray-900 pagu-text">Rp {{ number_format($rencana->estimasi_pagu, 0, ',', '.') }}</p>
            </div>
            <div class="text-right">
                <p class="text-[10px] text-gray-500 uppercase font-semibold">Sumber</p>
                <span class="bg-blue-50 text-blue-700 text-[10px] font-bold px-2 py-0.5 rounded border border-blue-100">{{ $rencana->sumber_dana ?? '-' }}</span>
            </div>
        </div>
    </div>

    <!-- Timeline Planning -->
    <div>
        <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Rencana Pelaksanaan (Timeline)</h5>
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-white border border-gray-200 p-3 rounded-xl shadow-sm">
                <p class="text-[10px] text-gray-500 uppercase font-semibold mb-1">Mulai Perencanaan</p>
                <p class="text-sm font-bold text-gray-800"><i class="fa-regular fa-calendar text-gray-400 mr-1"></i> {{ $rencana->target_mulai ?? '-' }}</p>
            </div>
            <div class="bg-white border border-gray-200 p-3 rounded-xl shadow-sm">
                <p class="text-[10px] text-gray-500 uppercase font-semibold mb-1">Target Selesai</p>
                <p class="text-sm font-bold text-gray-800"><i class="fa-regular fa-calendar-check text-gray-400 mr-1"></i> {{ $rencana->target_selesai ?? '-' }}</p>
            </div>
        </div>
    </div>

    <!-- Status Relasi Pembangunan -->
    @if($rencana->status == 'dikonversi')
        <div class="bg-green-50 border border-green-200 rounded-2xl p-4">
            <div class="flex items-center gap-2 mb-2">
                <i class="fa-solid fa-check-circle text-green-600"></i>
                <h5 class="text-sm font-bold text-green-900">Program Tengah Dikerjakan</h5>
            </div>
            <p class="text-xs text-green-800 leading-relaxed">Rencana ini telah disinkronisasikan ke dalam draft Pembangunan Fisik / Proyek berjalan.</p>
        </div>
    @else
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4">
            <div class="flex items-center gap-2 mb-2">
                <i class="fa-solid fa-triangle-exclamation text-amber-600"></i>
                <h5 class="text-sm font-bold text-amber-900">Belum Direalisasikan</h5>
            </div>
            <p class="text-xs text-amber-800 leading-relaxed mb-3">Rencana ini masih berstatus "Draft" dan belum dipetakan (sinkronisasi) ke data Proyek Pembangunan Fisik sesungguhnya.</p>
            <button @click="openSyncModal({{ $rencana->id }})" class="w-full bg-white border border-amber-300 text-amber-700 font-bold text-xs px-4 py-2 rounded-lg hover:bg-amber-100 transition-colors shadow-sm">Sinkronkan ke Proyek Fisik</button>
        </div>
    @endif

</div>
