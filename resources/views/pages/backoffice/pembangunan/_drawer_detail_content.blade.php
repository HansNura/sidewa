<div class="px-6 py-5 border-b border-gray-100 bg-white">
    @if($proyek->status == 'berjalan')
        <span class="text-[10px] font-bold text-blue-700 bg-blue-100 px-2 py-0.5 rounded uppercase tracking-wider mb-1 inline-flex items-center gap-1"><i class="fa-solid fa-person-digging"></i> Sedang Berjalan</span>
    @elseif($proyek->status == 'selesai')
        <span class="text-[10px] font-bold text-green-700 bg-green-100 px-2 py-0.5 rounded uppercase tracking-wider mb-1 inline-flex items-center gap-1"><i class="fa-solid fa-check-double"></i> Selesai Tuntas</span>
    @elseif($proyek->status == 'terlambat')
        <span class="text-[10px] font-bold text-red-700 bg-red-100 px-2 py-0.5 rounded uppercase tracking-wider mb-1 inline-flex items-center gap-1"><i class="fa-solid fa-triangle-exclamation"></i> Terlambat / Terkendala</span>
    @else
        <span class="text-[10px] font-bold text-gray-500 bg-gray-100 px-2 py-0.5 rounded uppercase tracking-wider mb-1 inline-flex items-center gap-1"><i class="fa-solid fa-clipboard"></i> Perencanaan</span>
    @endif
    
    <h3 class="font-extrabold text-xl text-gray-900 mt-1 leading-tight">{{ $proyek->nama_proyek }}</h3>
    <p class="text-xs text-gray-500 mt-1"><i class="fa-solid fa-location-dot mr-1"></i> {{ $proyek->lokasi_dusun ?? 'Lokasi belum diset' }}, {{ $proyek->rt_rw }} ({{ $proyek->kategori }})</p>
</div>

<div class="p-6 space-y-8 pb-20">

    <!-- Overview & Progress -->
    <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
        <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Status & Progres Fisik</h5>
        <div class="flex items-end justify-between mb-2">
            <span class="text-3xl font-extrabold text-{{ $proyek->status == 'selesai' ? 'green' : ($proyek->status == 'terlambat' ? 'red' : 'blue') }}-600">{{ $proyek->progres_fisik }}%</span>
        </div>
        <div class="w-full bg-gray-100 rounded-full h-2 mb-4 overflow-hidden">
            <div class="bg-{{ $proyek->status == 'selesai' ? 'green' : ($proyek->status == 'terlambat' ? 'red' : 'blue') }}-500 h-full" style="width: {{ $proyek->progres_fisik }}%"></div>
        </div>
        <div class="grid grid-cols-2 gap-4 text-sm mt-4 pt-4 border-t border-gray-100">
            <div>
                <p class="text-[10px] text-gray-500 uppercase font-semibold">Tgl Mulai</p>
                <p class="font-bold text-gray-800">{{ $proyek->tanggal_mulai ? $proyek->tanggal_mulai->format('d M Y') : '-' }}</p>
            </div>
            <div>
                <p class="text-[10px] text-gray-500 uppercase font-semibold">Target Selesai</p>
                <p class="font-bold text-gray-800">{{ $proyek->target_selesai ? $proyek->target_selesai->format('d M Y') : '-' }}</p>
            </div>
        </div>
    </div>

    <!-- Budget vs Realization -->
    @if($proyek->apbdes_id)
        <div>
            <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Linked Anggaran (Pagu APBDes)</h5>
            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 flex items-center justify-between gap-4">
                <div>
                    <p class="text-[10px] text-gray-500 font-bold uppercase mb-0.5">Pagu Anggaran {{ $proyek->apbdes->sumber_dana }}</p>
                    <p class="text-lg font-extrabold text-gray-900">Rp {{ number_format($paguAnggaran, 0, ',', '.') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] text-gray-500 font-bold uppercase mb-0.5">Telah Terserap</p>
                    <p class="text-lg font-extrabold text-green-700">Rp {{ number_format($totalRealisasi, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Photo Gallery -->
    @if(count($proyek->fotos) > 0)
        <div>
            <div class="flex justify-between items-center mb-3">
                <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Dokumentasi Lapangan</h5>
            </div>
            <div class="grid grid-cols-3 gap-3">
                @foreach($proyek->fotos as $foto)
                <div class="aspect-square bg-gray-200 rounded-lg overflow-hidden border border-gray-200 group relative">
                    <img src="{{ $foto->foto_url }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform" alt="{{ $foto->keterangan }}">
                    <div class="absolute bottom-0 inset-x-0 bg-black/60 backdrop-blur-sm text-white text-[9px] p-1.5 text-center font-bold">{{ $foto->keterangan }}</div>
                </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Activity Log / Timeline -->
    <div>
        <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Histori Pembaruan Progres</h5>
        <div class="relative ml-2 space-y-5 timeline-line">
            @forelse($proyek->historis as $histori)
                <div class="relative z-10 flex gap-4 items-start pt-1">
                    @if($histori->is_milestone)
                        <div class="w-6 h-6 rounded-full bg-green-100 border-4 border-white flex items-center justify-center text-green-600 shrink-0 shadow-sm mt-0.5 -ml-1">
                            <i class="fa-solid fa-flag-checkered text-[10px]"></i>
                        </div>
                    @else
                        <div class="w-6 h-6 rounded-full bg-blue-100 border-4 border-white flex items-center justify-center text-blue-600 shrink-0 shadow-sm mt-0.5 -ml-1">
                            <i class="fa-solid fa-person-digging text-[10px]"></i>
                        </div>
                    @endif

                    <div class="flex-1 pb-2">
                        <h5 class="text-sm font-bold text-gray-800 leading-none mb-1">{{ $histori->judul_update }}</h5>
                        <p class="text-[10px] text-gray-500 mb-1.5">{{ $histori->tanggal->format('d M Y') }} - <b>{{ $histori->oleh_siapa }}</b> (Progres: {{ $histori->progres_dicapai }}%)</p>
                        @if($histori->deskripsi)
                            <p class="text-xs text-gray-600 bg-gray-50/80 p-2 rounded-lg border border-gray-200 mt-1 shadow-sm">{{ $histori->deskripsi }}</p>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-xs text-gray-400 italic">Belum ada pembaruan log lapangan ter-rekam.</p>
            @endforelse
        </div>
    </div>
</div>

<div class="absolute bottom-0 left-0 w-full p-4 border-t border-gray-100 bg-white shrink-0 flex gap-3 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
    <button class="flex-1 bg-white border border-gray-200 text-gray-700 px-4 py-2.5 rounded-xl font-bold text-sm hover:bg-gray-100 transition-colors">
        <i class="fa-solid fa-pen text-xs mr-1"></i> Edit Info Master
    </button>
    <button @click="openUpdateModal()" class="flex-1 bg-green-700 text-white px-4 py-2.5 rounded-xl font-bold text-sm hover:bg-green-800 shadow-md transition-all">
        <i class="fa-solid fa-location-arrow text-xs mr-1"></i> Update Progres
    </button>
</div>
