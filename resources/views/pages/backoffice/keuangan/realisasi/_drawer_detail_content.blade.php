@php
    $realisasiSum = $kegiatan->realisasis->sum('nominal');
    $pct = $kegiatan->pagu_anggaran > 0 ? ($realisasiSum / $kegiatan->pagu_anggaran) * 100 : 0;
    $isOverBudget = $pct > 100;
@endphp

<div class="p-6 space-y-8">
    <div class="mb-2">
        <h3 class="font-bold text-gray-800 text-lg border-b border-gray-100 pb-2">{{ $kegiatan->nama_kegiatan }}</h3>
        <p class="text-xs text-gray-500 mt-2">Kode Rekening: <span class="font-mono text-gray-700 bg-gray-100 px-1 rounded">{{ $kegiatan->kode_rekening }}</span></p>
    </div>

    <!-- Budget vs Realization Detail -->
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4">
            <p class="text-[10px] font-bold text-gray-500 uppercase mb-1">Pagu Anggaran</p>
            <p class="text-base font-extrabold text-gray-800">
                Rp {{ number_format($kegiatan->pagu_anggaran, 0, ',', '.') }}
            </p>
        </div>
        <div class="{{ $isOverBudget ? 'bg-red-50 border-red-200' : 'bg-gray-50 border-gray-200' }} border rounded-2xl p-4">
            <p class="text-[10px] font-bold {{ $isOverBudget ? 'text-red-500' : 'text-gray-500' }} uppercase mb-1">Total Realisasi</p>
            <p class="text-base font-extrabold {{ $isOverBudget ? 'text-red-700' : 'text-green-700' }}">
                Rp {{ number_format($realisasiSum, 0, ',', '.') }}
            </p>
        </div>
    </div>

    <!-- Progress Circle / Indicator -->
    <div class="flex flex-col items-center {{ $isOverBudget ? 'bg-red-50 border-red-100' : 'bg-green-50 border-green-100' }} border rounded-3xl p-6">
        <p class="text-xs font-bold {{ $isOverBudget ? 'text-red-800' : 'text-green-800' }} mb-4 uppercase">Status Penyerapan</p>
        <div class="flex items-center gap-6">
            <div class="relative w-24 h-24 flex items-center justify-center shrink-0">
                <svg class="w-full h-full transform -rotate-90">
                    <circle cx="48" cy="48" r="40" stroke="currentColor" stroke-width="8" fill="transparent"
                        class="{{ $isOverBudget ? 'text-red-100' : 'text-green-100' }}" />
                    <circle cx="48" cy="48" r="40" stroke="currentColor" stroke-width="8" fill="transparent"
                        class="{{ $isOverBudget ? 'text-red-600' : 'text-green-500' }}" stroke-dasharray="251.2" stroke-dashoffset="{{ 251.2 - (251.2 * min(100, $pct) / 100) }}" />
                </svg>
                <span class="absolute text-xl font-black {{ $isOverBudget ? 'text-red-900' : 'text-green-900' }}">{{ number_format($pct, 0) }}%</span>
            </div>
            <div class="space-y-2">
                @if($isOverBudget)
                    <p class="text-[10px] text-red-700 leading-relaxed max-w-[180px]">Realisasi dana telah melampaui Pagu yang dianggarkan. Diperlukan review perubahan APBDes.</p>
                    <span class="inline-flex items-center gap-1.5 bg-red-600 text-white text-[10px] font-extrabold px-2 py-1 rounded-lg shadow-sm"><i class="fa-solid fa-triangle-exclamation"></i> Over Budget</span>
                @else
                    <p class="text-[10px] text-green-700 leading-relaxed max-w-[180px]">Penyerapan anggaran berjalan sesuai batasan porsi kegiatan.</p>
                    <span class="inline-flex items-center gap-1.5 bg-white text-green-600 text-[10px] font-extrabold px-2 py-1 rounded-lg border border-green-100 shadow-sm"><i class="fa-solid fa-circle-check"></i> On Track</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Realization History Table -->
    <div>
        <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 border-b border-gray-100 pb-2">Histori Transaksi Realisasi</h5>
        <div class="border border-gray-100 rounded-xl overflow-hidden shadow-sm">
            <table class="w-full text-left bg-white text-xs whitespace-nowrap">
                <thead class="bg-gray-50 border-b border-gray-100 text-gray-500 font-bold">
                    <tr>
                        <th class="p-3">Tanggal</th>
                        <th class="p-3 text-right">Nominal</th>
                        <th class="p-3">Dokumen/Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($kegiatan->realisasis as $r)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-3">{{ $r->tanggal_transaksi->format('d M Y') }}</td>
                            <td class="p-3 text-right font-bold text-gray-700">Rp {{ number_format($r->nominal, 0, ',', '.') }}</td>
                            <td class="p-3 text-gray-500 whitespace-normal">
                                @if($r->bukti_file_path)
                                    <a href="{{ $r->bukti_url }}" target="_blank" class="text-blue-600 font-bold hover:underline mb-1 inline-block">
                                        <i class="fa-solid fa-paperclip"></i> Lampiran File
                                    </a><br>
                                @endif
                                <span class="italic text-[10px]">{{ $r->catatan ?? '-' }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-4 text-center text-gray-400">Belum ada riwayat transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
