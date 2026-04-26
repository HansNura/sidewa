<!-- Filter Panel -->
<section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-4 transition-all">
    <div class="flex flex-col md:flex-row gap-3">
        <div class="flex-1 min-w-[200px] relative">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text" placeholder="Cari nama program / kegiatan..."
                class="w-full bg-gray-50 border border-gray-200 text-sm rounded-xl pl-11 pr-4 py-2.5 focus:ring-2 focus:ring-green-500 outline-none transition-all">
        </div>
        <div class="w-full md:w-48 relative shrink-0">
            <select class="w-full bg-gray-50 border border-gray-200 text-sm rounded-xl px-4 py-2.5 appearance-none focus:ring-2 focus:ring-green-500 outline-none cursor-pointer font-medium text-gray-700">
                <option value="">Semua Prioritas</option>
                <option value="tinggi">Sangat Mendesak</option>
                <option value="sedang">Mendesak</option>
                <option value="normal">Normal</option>
            </select>
            <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
        </div>
        <div class="w-full md:w-48 relative shrink-0">
            <select class="w-full bg-gray-50 border border-gray-200 text-sm rounded-xl px-4 py-2.5 appearance-none focus:ring-2 focus:ring-green-500 outline-none cursor-pointer font-medium text-gray-700">
                <option value="">Semua Status</option>
                <option value="draft">Draft Rencana</option>
                <option value="dikonversi">Sdg Dikerjakan</option>
            </select>
            <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
        </div>
    </div>
</section>

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
                    <th class="p-4 font-bold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-100">
                @forelse($rencanaList as $r)
                    <tr class="hover:bg-gray-50 transition-colors group cursor-pointer {{ $r->status == 'dikonversi' ? 'bg-green-50/10' : '' }}" 
                        :class="selectedRows.includes({{ $r->id }}) ? 'bg-green-50/30' : ''"
                        @click="fetchDetail({{ $r->id }})">
                        <td class="p-4 text-center" @click.stop>
                            <input type="checkbox" class="custom-checkbox inline-block" value="{{ $r->id }}" x-model="selectedRows">
                        </td>
                        <td class="p-4">
                            <div class="font-bold text-gray-900 leading-tight group-hover:text-green-700 transition-colors flex items-center gap-2">
                                {{ $r->nama_program }}
                                @if($r->prioritas == 'tinggi' && $r->status == 'draft')
                                    <i class="fa-solid fa-circle-exclamation text-amber-500 text-[10px]" title="Sangat Mendesak"></i>
                                @endif
                            </div>
                            <div class="text-[10px] text-gray-500 mt-0.5">Bidang: {{ $r->kategori }}</div>
                        </td>
                        <td class="p-4 text-center">
                            @if($r->prioritas == 'tinggi')
                                <span class="bg-red-50 text-red-600 text-[10px] font-bold px-2.5 py-1 rounded border border-red-200 uppercase tracking-wider"><i class="fa-solid fa-angles-up mr-1"></i>Sgt Mendesak</span>
                            @elseif($r->prioritas == 'sedang')
                                <span class="bg-amber-50 text-amber-600 text-[10px] font-bold px-2.5 py-1 rounded border border-amber-200 uppercase tracking-wider"><i class="fa-solid fa-angle-up mr-1"></i>Mendesak</span>
                            @else
                                <span class="bg-blue-50 text-blue-600 text-[10px] font-bold px-2.5 py-1 rounded border border-blue-200 uppercase tracking-wider"><i class="fa-solid fa-minus mr-1"></i>Normal</span>
                            @endif
                        </td>
                        <td class="p-4 text-right">
                            <div class="font-bold text-gray-700">Rp {{ number_format($r->estimasi_pagu, 0, ',', '.') }}</div>
                            <div class="text-[10px] text-gray-400 mt-0.5">{{ $r->sumber_dana }}</div>
                        </td>
                        <td class="p-4 text-center">
                            @if($r->status == 'dikonversi')
                                <span class="bg-green-50 text-green-700 text-[10px] font-bold px-2.5 py-1 rounded border border-green-200 uppercase tracking-wider"><i class="fa-solid fa-check-double mr-1"></i>Sdg Dikerjakan</span>
                            @else
                                <span class="bg-gray-50 text-gray-500 text-[10px] font-bold px-2.5 py-1 rounded border border-gray-200 uppercase tracking-wider"><i class="fa-solid fa-file-lines mr-1 text-gray-400"></i>Draft Rencana</span>
                            @endif
                        </td>
                        <td class="p-4 text-center" @click.stop>
                            <div class="flex items-center justify-center gap-2">
                                <button @click="fetchDetail({{ $r->id }})"
                                    class="w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:text-green-700 hover:bg-green-50 flex items-center justify-center transition-colors border border-gray-200 shadow-sm"
                                    title="Detail"><i class="fa-solid fa-eye text-xs"></i></button>
                                
                                @if($r->status == 'draft')
                                    <button @click="openSyncModal({{ $r->id }})"
                                        class="px-3 py-1.5 rounded-lg bg-green-50 text-green-700 hover:bg-green-600 hover:text-white font-bold text-[10px] uppercase transition-all border border-green-200 shadow-sm"
                                        title="Konversi ke Proyek Fisik"><i class="fa-solid fa-link mr-1"></i> Jadikan Proyek</button>
                                @else
                                    <a href="{{ route('admin.pembangunan.index') }}"
                                        class="px-3 py-1.5 rounded-lg bg-white text-gray-600 hover:bg-gray-100 font-bold text-[10px] uppercase transition-all border border-gray-200 shadow-sm"><i class="fa-solid fa-chart-line mr-1"></i> Lacak Progres</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-400">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center text-gray-300 text-3xl">
                                    <i class="fa-solid fa-folder-open"></i>
                                </div>
                                <p class="font-bold text-gray-500">Belum ada data perencanaan</p>
                                <p class="text-xs text-gray-400">Belum ada data perencanaan untuk periode terpilih.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
