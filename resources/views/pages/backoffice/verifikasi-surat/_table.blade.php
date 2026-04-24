{{-- Verification Queue Table --}}
<section class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden flex flex-col">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead>
                <tr class="bg-gray-50/80 text-gray-500 text-[11px] uppercase tracking-wider border-b border-gray-200">
                    <th class="p-4 font-semibold">TKT & Jenis Layanan</th>
                    <th class="p-4 font-semibold">Data Pemohon</th>
                    <th class="p-4 font-semibold text-center">Waktu Pengajuan</th>
                    <th class="p-4 font-semibold text-center">Posisi / Status</th>
                    <th class="p-4 font-semibold text-center">Aksi Cepat</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-100">
                @php $hasVisible = false; @endphp
                @foreach ($queue as $surat)
                    @php
                        $badge = $surat->statusBadge();
                        $isTTE = $surat->status === 'menunggu_tte';

                        // Role-based visibility logic
                        $shouldShow = false;
                        if (auth()->user()->isAdministrator()) {
                            $shouldShow = true;
                        } elseif (auth()->user()->isKades() && $isTTE) {
                            $shouldShow = true;
                        } elseif (auth()->user()->isOperator() && !$isTTE) {
                            $shouldShow = true;
                        }

                        if (!$shouldShow) {
                            continue;
                        }
                        $hasVisible = true;
                    @endphp
                    <tr
                        class="transition-colors {{ $isTTE ? 'hover:bg-green-50/30 bg-green-50/10' : 'hover:bg-gray-50' }}">
                        {{-- Tiket & Jenis --}}
                        <td class="p-4">
                            <div class="font-bold text-gray-900 font-mono text-[12px] mb-0.5">{{ $surat->nomor_tiket }}
                            </div>
                            <div class="font-semibold {{ $isTTE ? 'text-green-700' : 'text-gray-700' }} hover:underline cursor-pointer"
                                @click="openWorkspace({{ $surat->id }})">
                                {{ $surat->jenisLabel() }}
                            </div>
                        </td>

                        {{-- Pemohon --}}
                        <td class="p-4">
                            @if ($surat->penduduk)
                                <div class="font-bold text-gray-800">{{ $surat->penduduk->nama }}</div>
                                <div class="text-[10px] text-gray-500 mt-0.5">NIK: {{ $surat->penduduk->nik }}</div>
                            @else
                                <span class="text-gray-400 italic">-</span>
                            @endif
                        </td>

                        {{-- Waktu --}}
                        <td class="p-4 text-center">
                            @if ($surat->tanggal_pengajuan->isToday())
                                <span class="text-xs font-semibold text-gray-700">Hari ini</span><br>
                            @else
                                <span
                                    class="text-xs font-semibold text-gray-700">{{ $surat->tanggal_pengajuan->translatedFormat('d M Y') }}</span><br>
                            @endif
                            <span class="text-[10px] text-gray-400">{{ $surat->tanggal_pengajuan->format('H:i') }}
                                WIB</span>
                        </td>

                        {{-- Status Badge --}}
                        <td class="p-4 text-center">
                            @if ($isTTE)
                                <span
                                    class="inline-flex items-center gap-1.5 bg-green-100 text-green-800 text-[10px] font-bold px-2.5 py-1 rounded border border-green-200 uppercase tracking-wide">
                                    <i class="fa-solid fa-signature"></i> Menunggu TTE
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center gap-1.5 bg-amber-100 text-amber-800 text-[10px] font-bold px-2.5 py-1 rounded border border-amber-200 uppercase tracking-wide">
                                    <i class="fa-solid fa-list-check"></i> Menunggu Verifikasi
                                </span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                @if ($isTTE)
                                    <button @click="openWorkspace({{ $surat->id }})"
                                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-colors shadow-sm flex items-center gap-1.5 cursor-pointer">
                                        <i class="fa-solid fa-magnifying-glass-chart"></i> Review & TTE
                                    </button>
                                @else
                                    <button @click="openWorkspace({{ $surat->id }})"
                                        class="bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors shadow-sm cursor-pointer">
                                        Buka & Verifikasi
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach

                @if (!$hasVisible)
                    <tr>
                        <td colspan="5" class="p-8 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <i class="fa-solid fa-check-double text-4xl text-gray-200"></i>
                                <p class="text-sm text-gray-500 font-medium">Tidak ada antrian untuk Anda.</p>
                                <p class="text-xs text-gray-400">Semua tugas saat ini telah selesai diproses. 🎉</p>
                            </div>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    @if ($queue->hasPages())
        <div class="p-4 border-t border-gray-100 bg-gray-50">
            {{ $queue->links() }}
        </div>
    @endif
</section>
