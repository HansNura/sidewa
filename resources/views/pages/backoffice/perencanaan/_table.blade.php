<!-- Filter Panel -->
<div class="flex flex-col md:flex-row gap-4 mb-4">
    <div class="flex-1 relative">
        <i class="fa-solid fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
        <input type="text" placeholder="Cari nama program / kegiatan..."
            class="w-full bg-white border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none shadow-sm">
    </div>
    <select class="w-full md:w-48 bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none shadow-sm cursor-pointer">
        <option value="">Semua Prioritas</option>
        <option value="tinggi">Sangat Mendesak</option>
        <option value="sedang">Mendesak</option>
        <option value="normal">Normal</option>
    </select>
</div>

<!-- Data Table -->
<div class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead>
                <tr class="bg-gray-50/80 text-gray-500 text-[10px] uppercase tracking-wider border-b border-gray-200">
                    <th class="p-4 w-12 text-center"><input type="checkbox" class="custom-checkbox inline-block" x-model="selectAll" @change="selectedRows = selectAll ? [{{ $rencanaList->pluck('id')->join(',') }}] : []"></th>
                    <th class="p-4 font-bold">Nama Program / Kegiatan</th>
                    <th class="p-4 font-bold text-center">Prioritas</th>
                    <th class="p-4 font-bold text-right">Estimasi Anggaran</th>
                    <th class="p-4 font-bold text-center">Status Realisasi</th>
                    <th class="p-4 font-bold text-center">Aksi Cepat</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-100">
                @forelse($rencanaList as $r)
                    <tr class="hover:bg-gray-50 transition-colors group {{ $r->status == 'dikonversi' ? 'bg-green-50/10' : '' }}" :class="selectedRows.includes({{ $r->id }}) ? 'bg-green-50/30' : ''">
                        <td class="p-4 text-center">
                            <input type="checkbox" class="custom-checkbox inline-block" value="{{ $r->id }}" x-model="selectedRows">
                        </td>
                        <td class="p-4">
                            <div class="font-bold text-gray-900 leading-tight">{{ $r->nama_program }}</div>
                            <div class="text-[10px] text-gray-500 mt-0.5">Bidang: {{ $r->kategori }}</div>
                        </td>
                        <td class="p-4 text-center">
                            @if($r->prioritas == 'tinggi')
                                <span class="bg-red-50 text-red-600 text-[9px] font-black px-2 py-1 rounded border border-red-100 uppercase tracking-widest"><i class="fa-solid fa-angles-up mr-1"></i>Sgt Mendesak</span>
                            @elseif($r->prioritas == 'sedang')
                                <span class="bg-amber-50 text-amber-600 text-[9px] font-black px-2 py-1 rounded border border-amber-100 uppercase tracking-widest"><i class="fa-solid fa-angle-up mr-1"></i>Mendesak</span>
                            @else
                                <span class="bg-blue-50 text-blue-600 text-[9px] font-black px-2 py-1 rounded border border-blue-100 uppercase tracking-widest"><i class="fa-solid fa-minus mr-1"></i>Normal</span>
                            @endif
                        </td>
                        <td class="p-4 text-right">
                            <div class="font-bold text-gray-700">Rp {{ number_format($r->estimasi_pagu, 0, ',', '.') }}</div>
                            <div class="text-[10px] text-gray-400 mt-0.5">{{ $r->sumber_dana }}</div>
                        </td>
                        <td class="p-4 text-center">
                            @if($r->status == 'dikonversi')
                                <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2.5 py-1 rounded border border-green-200 uppercase tracking-wider"><i class="fa-solid fa-check mr-1"></i>Sdg Dikerjakan</span>
                            @else
                                <span class="bg-gray-100 text-gray-500 text-[10px] font-bold px-2.5 py-1 rounded border border-gray-200 uppercase tracking-wider">Draft Rencana</span>
                            @endif
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button @click="fetchDetail({{ $r->id }})" class="w-8 h-8 rounded-lg bg-white text-gray-500 hover:text-green-700 hover:bg-green-50 flex items-center justify-center transition-colors border border-gray-200 shadow-sm" title="Detail"><i class="fa-solid fa-eye text-xs"></i></button>
                                
                                @if($r->status == 'draft')
                                    <button @click="openSyncModal({{ $r->id }})" class="px-3 py-1.5 rounded-lg bg-green-50 text-green-700 hover:bg-green-600 hover:text-white font-bold text-[10px] uppercase transition-all border border-green-200 shadow-sm" title="Konversi ke Proyek Fisik"><i class="fa-solid fa-link mr-1"></i> Jadikan Proyek</button>
                                @else
                                    <a href="{{ route('admin.pembangunan.index') }}" class="px-3 py-1.5 rounded-lg bg-white text-gray-600 hover:bg-gray-100 font-bold text-[10px] uppercase transition-all border border-gray-200 shadow-sm"><i class="fa-solid fa-chart-line mr-1"></i> Lacak Progres</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-400">Belum ada data perencanaan untuk periode terpilih.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
