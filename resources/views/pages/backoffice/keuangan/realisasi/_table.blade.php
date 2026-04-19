<section class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden flex flex-col">
    <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row justify-between sm:items-center gap-4 bg-gray-50/50">
        <div>
            <h3 class="font-bold text-gray-800">Rincian Realisasi per Kegiatan</h3>
            <p class="text-xs text-gray-500 mt-0.5">Daftar penggunaan dana berdasarkan pos kegiatan APBDes.</p>
        </div>
        <div class="relative w-full sm:w-64">
            <button @click="inputModalOpen = true" class="w-full bg-green-700 hover:bg-green-800 text-white shadow-md rounded-xl px-4 py-2 text-sm font-bold transition-all flex items-center justify-center gap-2">
                <i class="fa-solid fa-plus"></i> Input Realisasi Baru
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead>
                <tr class="bg-gray-100 text-gray-500 text-[10px] uppercase tracking-wider border-b border-gray-200">
                    <th class="p-4 font-bold">Kode Akun</th>
                    <th class="p-4 font-bold">Uraian Kegiatan</th>
                    <th class="p-4 font-bold text-right">Pagu Anggaran</th>
                    <th class="p-4 font-bold text-right">Realisasi Aktu</th>
                    <th class="p-4 font-bold text-center">Progres (%)</th>
                    <th class="p-4 font-bold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-100" x-data="{ searchQuery: '' }">
                @forelse($kegiatans as $keg)
                    @php
                        $realisasi = $keg->realisasis_sum_nominal ?? 0;
                        $pct = $keg->pagu_anggaran > 0 ? ($realisasi / $keg->pagu_anggaran) * 100 : 0;
                        $isOverBudget =  $pct > 100;
                    @endphp
                    <tr class="{{ $isOverBudget ? 'bg-red-50/30 hover:bg-red-50/50' : 'hover:bg-gray-50' }} transition-colors">
                        <td class="p-4 font-mono text-xs {{ $isOverBudget ? 'text-red-400' : 'text-gray-500' }}">{{ $keg->kode_rekening }}</td>
                        <td class="p-4">
                            <div class="font-bold text-gray-800 leading-tight truncate max-w-[200px] lg:max-w-xs" title="{{ $keg->nama_kegiatan }}">{{ $keg->nama_kegiatan }}</div>
                            @if($isOverBudget)
                                <div class="text-[10px] text-red-500 mt-0.5 font-bold uppercase"><i class="fa-solid fa-circle-exclamation mr-1"></i> Over Budget</div>
                            @else
                                <div class="text-[10px] text-gray-500 mt-0.5">Bidang {{ explode('.', $keg->kode_rekening)[0] }}</div>
                            @endif
                        </td>
                        <td class="p-4 text-right font-semibold text-gray-600">
                            Rp {{ number_format($keg->pagu_anggaran, 0, ',', '.') }}
                        </td>
                        <td class="p-4 text-right font-bold {{ $isOverBudget ? 'text-red-600' : 'text-green-700' }}">
                            Rp {{ number_format($realisasi, 0, ',', '.') }}
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex flex-col items-center">
                                <span class="text-xs font-extrabold {{ $isOverBudget ? 'text-red-600' : 'text-blue-600' }}">{{ number_format($pct, 0) }}%</span>
                                <div class="w-20 h-1 bg-gray-100 rounded-full mt-1 overflow-hidden">
                                    <div class="{{ $isOverBudget ? 'bg-red-500' : 'bg-blue-500' }} h-full" style="width: {{ min(100, $pct) }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="p-4 text-center">
                            <button @click="fetchDetail({{ $keg->id }})" class="w-8 h-8 rounded-lg {{ $isOverBudget ? 'bg-white text-gray-400 hover:text-red-600 hover:bg-red-50 border border-red-100' : 'bg-gray-50 text-gray-500 hover:text-green-700 hover:bg-green-50 border border-gray-100' }} flex items-center justify-center transition-colors shadow-sm">
                                <i class="fa-solid fa-eye text-xs"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-500 text-sm">Tidak ada data kegiatan APBDes pada tahun tersebut.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
