<div class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead>
                <tr class="bg-gray-50/80 text-gray-500 text-[10px] uppercase tracking-wider border-b border-gray-200">
                    <th class="p-4 font-bold">Nama Proyek & Info</th>
                    <th class="p-4 font-bold">Lokasi</th>
                    <th class="p-4 font-bold text-right">Nilai Anggaran</th>
                    <th class="p-4 font-bold text-center">Status</th>
                    <th class="p-4 font-bold text-center w-40">Progres Fisik</th>
                    <th class="p-4 font-bold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-100">
                @forelse($proyeks as $p)
                <tr class="{{ $p->status == 'terlambat' ? 'bg-red-50/10 hover:bg-red-50/20' : 'hover:bg-gray-50' }} transition-colors cursor-pointer group" @click="fetchDetail({{ $p->id }})">
                    <td class="p-4">
                        <div class="font-bold text-gray-900 leading-tight group-hover:text-green-700 transition-colors flex items-center gap-2">
                            {{ $p->nama_proyek }} 
                            @if($p->status == 'terlambat') <i class="fa-solid fa-circle-exclamation text-red-500 text-[10px]" title="Terlambat"></i> @endif
                        </div>
                        <div class="text-[10px] text-gray-500 mt-0.5">Kategori: {{ $p->kategori }}</div>
                    </td>
                    <td class="p-4">
                        <div class="text-xs font-semibold text-gray-700">{{ $p->lokasi_dusun ?? 'Belum diset' }}</div>
                        <div class="text-[10px] text-gray-400">{{ $p->rt_rw ?? '-' }}</div>
                    </td>
                    <td class="p-4 text-right">
                        @if($p->apbdes_id)
                            <div class="font-bold text-gray-700">Rp {{ number_format($p->apbdes->pagu_anggaran, 0, ',', '.') }}</div>
                            <div class="text-[10px] text-green-600">Terhubung APBDes</div>
                        @else
                            <div class="font-bold text-gray-400">Rp 0</div>
                            <div class="text-[10px] text-gray-400">Pagu Mandiri</div>
                        @endif
                    </td>
                    <td class="p-4 text-center">
                        @if($p->status == 'berjalan')
                            <span class="bg-blue-50 text-blue-700 text-[10px] font-bold px-2.5 py-1 rounded border border-blue-200 uppercase tracking-wider"><i class="fa-solid fa-person-digging mr-1"></i>Berjalan</span>
                        @elseif($p->status == 'selesai')
                            <span class="bg-green-50 text-green-700 text-[10px] font-bold px-2.5 py-1 rounded border border-green-200 uppercase tracking-wider"><i class="fa-solid fa-check-double mr-1"></i>Selesai</span>
                        @elseif($p->status == 'terlambat')
                            <span class="bg-red-50 text-red-700 text-[10px] font-bold px-2.5 py-1 rounded border border-red-200 uppercase tracking-wider"><i class="fa-solid fa-clock mr-1"></i>Terlambat</span>
                        @else 
                            <span class="bg-gray-50 text-gray-500 text-[10px] font-bold px-2.5 py-1 rounded border border-gray-200 uppercase tracking-wider"><i class="fa-solid fa-clipboard text-gray-400 mr-1"></i>Perencanaan</span>
                        @endif
                    </td>
                    <td class="p-4">
                        <div class="flex items-center gap-2">
                            <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                                <div class="bg-{{ $p->status == 'selesai' ? 'green' : ($p->status == 'terlambat' ? 'amber' : 'blue') }}-500 h-full" style="width: {{ $p->progres_fisik }}%"></div>
                            </div>
                            <span class="text-xs font-bold text-gray-700 w-8">{{ $p->progres_fisik }}%</span>
                        </div>
                    </td>
                    <td class="p-4 text-center" @click.stop>
                        <button @click="fetchDetail({{ $p->id }})" class="w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:text-green-700 hover:bg-green-50 flex items-center justify-center transition-colors border border-gray-200 shadow-sm" title="Detail Proyek"><i class="fa-solid fa-eye text-xs"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-gray-400">Belum ada proyek yang direkam.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
