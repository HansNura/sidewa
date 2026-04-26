<!-- Sticky Priority & Title Header -->
<div class="px-6 py-5 border-b border-gray-100 flex justify-between items-start bg-white shrink-0">
    <div>
        @if($rencana->prioritas == 'tinggi')
            <span class="text-[10px] font-black text-red-600 bg-red-50 px-2.5 py-1 rounded-lg uppercase tracking-widest mb-2 inline-flex items-center gap-1 border border-red-200"><i class="fa-solid fa-angles-up text-[8px]"></i> Sangat Mendesak</span>
        @elseif($rencana->prioritas == 'sedang')
            <span class="text-[10px] font-black text-amber-600 bg-amber-50 px-2.5 py-1 rounded-lg uppercase tracking-widest mb-2 inline-flex items-center gap-1 border border-amber-200"><i class="fa-solid fa-angle-up text-[8px]"></i> Mendesak</span>
        @else
            <span class="text-[10px] font-black text-blue-600 bg-blue-50 px-2.5 py-1 rounded-lg uppercase tracking-widest mb-2 inline-flex items-center gap-1 border border-blue-200"><i class="fa-solid fa-minus text-[8px]"></i> Normal</span>
        @endif
        
        <h3 class="font-extrabold text-xl text-gray-900 mt-2 leading-tight nama-program-text">{{ $rencana->nama_program }}</h3>
        <p class="text-xs text-gray-500 mt-1.5 flex items-center gap-2">
            <span class="bg-gray-100 text-gray-600 text-[10px] font-bold px-2 py-0.5 rounded">{{ strtoupper($rencana->jenis_rencana) }}</span>
            <span>Tahun/Periode {{ $rencana->tahun_pelaksanaan }}</span>
        </p>
    </div>
</div>

<div class="p-6 overflow-y-auto custom-scrollbar flex-1 space-y-6 pb-20">

    <!-- Objective & Target -->
    <div class="bg-gray-50/80 rounded-2xl p-5 border border-gray-100">
        <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-2">
            <i class="fa-solid fa-bullseye text-emerald-500"></i> Tujuan & Sasaran Program
        </h5>
        <p class="text-sm text-gray-800 leading-relaxed mb-4">{{ $rencana->tujuan_sasaran ?: 'Tidak ada deskripsi tujuan spesifik yang diisikan.' }}</p>
        <div class="flex flex-wrap gap-2 pt-3 border-t border-gray-200 text-xs">
            <span class="bg-white border border-gray-200 text-gray-600 font-bold px-2.5 py-1 rounded-lg shadow-sm">Bidang: <b>{{ $rencana->kategori }}</b></span>
        </div>
    </div>

    <!-- Budget Plan -->
    <div>
        <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-2">
            <i class="fa-solid fa-coins text-amber-500"></i> Estimasi Anggaran (RAB)
        </h5>
        <div class="flex items-center justify-between bg-white border border-gray-200 p-5 rounded-2xl shadow-sm">
            <div>
                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">Total Pagu Draft</p>
                <p class="text-xl font-black text-gray-900 pagu-text">Rp {{ number_format($rencana->estimasi_pagu, 0, ',', '.') }}</p>
            </div>
            <div class="text-right">
                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">Sumber</p>
                <span class="bg-blue-50 text-blue-700 text-[10px] font-bold px-2.5 py-1 rounded-lg border border-blue-100 shadow-sm">{{ $rencana->sumber_dana ?? '-' }}</span>
            </div>
        </div>
    </div>

    <!-- Timeline Planning -->
    <div>
        <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-2">
            <i class="fa-regular fa-calendar text-blue-500"></i> Rencana Pelaksanaan (Timeline)
        </h5>
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-white border border-gray-200 p-4 rounded-2xl shadow-sm">
                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1.5">Mulai Perencanaan</p>
                <p class="text-sm font-bold text-gray-800 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center text-[10px]"><i class="fa-regular fa-calendar"></i></span>
                    {{ $rencana->target_mulai ?? '-' }}
                </p>
            </div>
            <div class="bg-white border border-gray-200 p-4 rounded-2xl shadow-sm">
                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1.5">Target Selesai</p>
                <p class="text-sm font-bold text-gray-800 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-emerald-50 text-emerald-500 flex items-center justify-center text-[10px]"><i class="fa-regular fa-calendar-check"></i></span>
                    {{ $rencana->target_selesai ?? '-' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Status Relasi Pembangunan -->
    @if($rencana->status == 'dikonversi')
        <div class="bg-green-50/80 border border-green-200 rounded-2xl p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-2xl bg-green-500 text-white flex items-center justify-center shadow-lg shadow-green-500/20">
                    <i class="fa-solid fa-check-double"></i>
                </div>
                <div>
                    <h5 class="text-sm font-black text-green-900">Program Tengah Dikerjakan</h5>
                    <p class="text-[10px] text-green-700 font-medium">Proyek fisik sudah dibuat dari rencana ini</p>
                </div>
            </div>
            <p class="text-xs text-green-800 leading-relaxed bg-green-100/50 p-3 rounded-xl">Rencana ini telah disinkronisasikan ke dalam draft Pembangunan Fisik / Proyek berjalan.</p>
        </div>
    @else
        <div class="bg-amber-50/80 border border-amber-200 rounded-2xl p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-2xl bg-amber-500 text-white flex items-center justify-center shadow-lg shadow-amber-500/20">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div>
                    <h5 class="text-sm font-black text-amber-900">Belum Direalisasikan</h5>
                    <p class="text-[10px] text-amber-700 font-medium">Rencana masih berstatus "Draft"</p>
                </div>
            </div>
            <p class="text-xs text-amber-800 leading-relaxed bg-amber-100/50 p-3 rounded-xl mb-4">Rencana ini belum dipetakan (sinkronisasi) ke data Proyek Pembangunan Fisik sesungguhnya.</p>
            <button @click="openSyncModal({{ $rencana->id }})" class="w-full bg-amber-600 text-white font-bold text-xs px-5 py-3 rounded-2xl hover:bg-amber-700 transition-all shadow-lg shadow-amber-500/20 flex items-center justify-center gap-2">
                <i class="fa-solid fa-rocket"></i> Sinkronkan ke Proyek Fisik
            </button>
        </div>
    @endif

</div>
