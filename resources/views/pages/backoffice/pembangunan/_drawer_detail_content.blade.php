@php
    $statusMap = match($proyek->status) {
        'selesai' => [
            'icon' => 'fa-check-double',
            'label' => 'Selesai Tuntas',
            'badge' => 'text-emerald-700 bg-emerald-100',
            'text' => 'text-emerald-600',
            'bg' => 'bg-emerald-500'
        ],
        'terlambat' => [
            'icon' => 'fa-triangle-exclamation',
            'label' => 'Terlambat / Terkendala',
            'badge' => 'text-red-700 bg-red-100',
            'text' => 'text-red-600',
            'bg' => 'bg-red-500'
        ],
        'berjalan' => [
            'icon' => 'fa-person-digging',
            'label' => 'Sedang Berjalan',
            'badge' => 'text-blue-700 bg-blue-100',
            'text' => 'text-blue-600',
            'bg' => 'bg-blue-500'
        ],
        default => [
            'icon' => 'fa-clipboard',
            'label' => 'Perencanaan',
            'badge' => 'text-gray-500 bg-gray-100',
            'text' => 'text-gray-600',
            'bg' => 'bg-gray-500'
        ]
    };
@endphp

<div class="px-6 py-5 border-b border-gray-100 bg-white shrink-0">
    <span class="text-[10px] font-bold {{ $statusMap['badge'] }} px-2 py-0.5 rounded uppercase tracking-wider mb-1 inline-flex items-center gap-1">
        <i class="fa-solid {{ $statusMap['icon'] }}"></i> {{ $statusMap['label'] }}
    </span>
    
    <h3 class="font-extrabold text-xl text-gray-900 mt-1 leading-tight">{{ $proyek->nama_proyek }}</h3>
    <p class="text-xs text-gray-500 mt-1"><i class="fa-solid fa-location-dot mr-1"></i> {{ $proyek->lokasi_dusun ?? 'Lokasi belum diset' }}, {{ $proyek->rt_rw }} ({{ $proyek->kategori }})</p>
</div>

<div class="flex-1 overflow-y-auto custom-scrollbar bg-gray-50/30">
    <div class="p-6 space-y-8 pb-10">
        <!-- Overview & Progress -->
        <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
            <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Status & Progres Fisik</h5>
            <div class="flex items-end justify-between mb-2">
                <span class="text-3xl font-extrabold {{ $statusMap['text'] }}">{{ $proyek->progres_fisik }}%</span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-2 mb-4 overflow-hidden">
                <div class="{{ $statusMap['bg'] }} h-full transition-all duration-500 ease-out" style="width: {{ $proyek->progres_fisik }}%"></div>
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
                <div class="bg-emerald-50/50 p-4 rounded-xl border border-emerald-100 flex items-center justify-between gap-4 shadow-inner">
                    <div>
                        <p class="text-[10px] text-emerald-800 font-bold uppercase mb-0.5">Pagu {{ $proyek->apbdes->sumber_dana }}</p>
                        <p class="text-lg font-extrabold text-gray-900">Rp {{ number_format($paguAnggaran, 0, ',', '.') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] text-emerald-800 font-bold uppercase mb-0.5">Telah Terserap</p>
                        <p class="text-lg font-extrabold text-emerald-600">Rp {{ number_format($totalRealisasi, 0, ',', '.') }}</p>
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
                    <div class="aspect-square bg-gray-200 rounded-xl overflow-hidden border border-gray-200 group relative shadow-sm">
                        <img src="{{ Storage::url($foto->foto_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" alt="{{ $foto->keterangan }}">
                        <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-gray-900/90 to-transparent pt-6 pb-2 px-2 text-white text-[9px] text-center font-bold">{{ $foto->keterangan }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Activity Log / Timeline -->
        <div>
            <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Histori Pembaruan Progres</h5>
            <div class="relative ml-2 space-y-5 border-l-2 border-gray-100 pb-4">
                @forelse($proyek->historis as $histori)
                    <div class="relative z-10 flex gap-4 items-start pt-1">
                        @if($histori->is_milestone)
                            <div class="w-6 h-6 rounded-full bg-emerald-100 border-4 border-white flex items-center justify-center text-emerald-600 shrink-0 shadow-sm mt-0.5 -ml-[13px]">
                                <i class="fa-solid fa-flag-checkered text-[10px]"></i>
                            </div>
                        @else
                            <div class="w-6 h-6 rounded-full bg-blue-100 border-4 border-white flex items-center justify-center text-blue-600 shrink-0 shadow-sm mt-0.5 -ml-[13px]">
                                <i class="fa-solid fa-person-digging text-[10px]"></i>
                            </div>
                        @endif

                        <div class="flex-1 pb-2">
                            <h5 class="text-sm font-bold text-gray-800 leading-none mb-1">{{ $histori->judul_update }}</h5>
                            <p class="text-[10px] text-gray-500 mb-1.5">{{ $histori->tanggal->format('d M Y') }} - <b>{{ $histori->oleh_siapa }}</b> (Progres: {{ $histori->progres_dicapai }}%)</p>
                            @if($histori->deskripsi)
                                <p class="text-xs text-gray-600 bg-gray-50/80 p-3 rounded-xl border border-gray-200 mt-2 shadow-sm">{{ $histori->deskripsi }}</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="relative z-10 flex gap-4 items-start pt-1">
                        <div class="w-6 h-6 rounded-full bg-gray-100 border-4 border-white flex items-center justify-center text-gray-400 shrink-0 shadow-sm mt-0.5 -ml-[13px]">
                            <i class="fa-solid fa-clock text-[10px]"></i>
                        </div>
                        <div class="flex-1 pb-2">
                            <p class="text-xs text-gray-400 italic mt-1">Belum ada pembaruan log lapangan ter-rekam.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="p-4 border-t border-gray-100 bg-white shrink-0 flex gap-3 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] z-20">
    <button @click="proyekToEdit = {{ $proyek->toJson() }}; detailDrawerOpen = false; setTimeout(() => editModalOpen = true, 300)" class="flex-1 bg-white border border-gray-200 text-gray-700 px-4 py-2.5 rounded-xl font-bold text-sm hover:bg-gray-100 transition-colors cursor-pointer">
        <i class="fa-solid fa-pen text-xs mr-1"></i> Edit Info Master
    </button>
    @if($proyek->progres_fisik < 100)
        <button @click="openUpdateModal({{ $proyek->progres_fisik }})" class="flex-1 bg-emerald-700 text-white px-4 py-2.5 rounded-xl font-bold text-sm hover:bg-emerald-800 shadow-md transition-all cursor-pointer">
            <i class="fa-solid fa-location-arrow text-xs mr-1"></i> Update Progres
        </button>
    @else
        <button disabled class="flex-1 bg-gray-100 text-gray-400 px-4 py-2.5 rounded-xl font-bold text-sm cursor-not-allowed border border-gray-200">
            <i class="fa-solid fa-check-double text-xs mr-1"></i> Selesai 100%
        </button>
    @endif
</div>
