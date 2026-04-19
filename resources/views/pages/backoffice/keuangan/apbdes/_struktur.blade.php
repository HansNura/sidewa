<section x-show="activeTab === 'overview'" x-transition.opacity class="space-y-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <h4 class="font-bold text-gray-800">Hierarki Penganggaran Desa (Belanja)</h4>
            <div class="flex gap-2">
                <span class="text-xs font-bold text-gray-500">TA {{ $tahun }}</span>
            </div>
        </div>

        <div class="p-4 space-y-4">
            @forelse($strukturs as $bidangKey => $bidangData)
                @php $bItem = $bidangData['bidang_item']; @endphp
                
                {{-- BIDANG --}}
                <div class="border border-gray-100 rounded-xl overflow-hidden" x-data="{ expanded: false }">
                    <div class="p-4 bg-gray-50 flex items-center justify-between cursor-pointer group"
                        @click="expanded = !expanded">
                        <div class="flex items-center gap-4">
                            <i class="fa-solid fa-chevron-right text-gray-400 transition-transform duration-300"
                                :class="expanded ? 'rotate-90' : ''"></i>
                            <div>
                                <span class="text-[10px] font-bold text-green-700 bg-green-100 px-2 py-0.5 rounded">
                                    BIDANG {{ $bidangKey }}
                                </span>
                                <h5 class="font-bold text-gray-900 mt-0.5">
                                    {{ $bItem ? $bItem->nama_kegiatan : "Belum ditentukan (Kode $bidangKey)" }}
                                </h5>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-bold text-gray-400 uppercase">Sub-Total Bidang</p>
                            <p class="text-sm font-extrabold text-gray-800" x-text="formatIDR({{ $bItem ? $bItem->pagu_anggaran : 0 }})">
                                Rp {{ number_format($bItem ? $bItem->pagu_anggaran : 0) }}
                            </p>
                        </div>
                    </div>
                    
                    {{-- SUB-BIDANG --}}
                    <div x-show="expanded" x-collapse x-cloak class="p-4 border-t border-gray-100 space-y-3 bg-white">
                        @foreach($bidangData['subs'] as $subKey => $subData)
                            @php $sItem = $subData['sub_item']; @endphp
                            
                            <div class="ml-6 border-l-2 border-gray-100 pl-4 space-y-2">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center gap-2">
                                        <i class="fa-solid fa-folder-open text-amber-500 text-xs"></i>
                                        <span class="text-xs font-bold text-gray-700">
                                            {{ $subKey }} {{ $sItem ? $sItem->nama_kegiatan : "Sub Bidang $subKey" }}
                                        </span>
                                    </div>
                                    <span class="text-xs font-bold text-gray-600" x-text="formatIDR({{ $sItem ? $sItem->pagu_anggaran : 0 }})">
                                        Rp {{ number_format($sItem ? $sItem->pagu_anggaran : 0) }}
                                    </span>
                                </div>
                                
                                {{-- KEGIATAN --}}
                                <div class="ml-6 space-y-2">
                                    @foreach($subData['kegiatans'] as $kegiatan)
                                        <div class="flex justify-between items-center bg-gray-50 p-2 rounded-lg group">
                                            <div class="flex items-center gap-3">
                                                <span class="text-[10px] font-mono text-gray-400">{{ $kegiatan->kode_rekening }}</span>
                                                <span class="text-xs text-gray-600 group-hover:text-green-700 transition-colors">
                                                    {{ $kegiatan->nama_kegiatan }}
                                                </span>
                                            </div>
                                            <div class="flex items-center gap-4">
                                                <span class="text-[10px] text-gray-400 font-bold bg-white px-2 py-0.5 rounded border border-gray-200">
                                                    {{ $kegiatan->sumber_dana }}
                                                </span>
                                                <span class="text-xs font-bold text-gray-800" x-text="formatIDR({{ $kegiatan->pagu_anggaran }})">
                                                    Rp {{ number_format($kegiatan->pagu_anggaran) }}
                                                </span>
                                                <button class="text-gray-300 hover:text-amber-500"><i class="fa-solid fa-pen-to-square"></i></button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="text-center p-8 text-gray-500">
                    Belum ada data belanja APBDes pada Tahun Anggaran {{ $tahun }}.
                </div>
            @endforelse
        </div>
    </div>
</section>
