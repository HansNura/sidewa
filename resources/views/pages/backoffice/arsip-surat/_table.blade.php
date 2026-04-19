{{-- Archive Table --}}
<section class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden flex flex-col">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead>
                <tr class="bg-gray-50/80 text-gray-500 text-[11px] uppercase tracking-wider border-b border-gray-200">
                    <th class="p-4 w-12 text-center">
                        <input type="checkbox" class="custom-checkbox inline-block" x-model="selectAll" @change="toggleSelectAll()">
                    </th>
                    <th class="p-4 font-semibold">Nomor & Detail Surat</th>
                    <th class="p-4 font-semibold">Nama Pemohon (Warga)</th>
                    <th class="p-4 font-semibold text-center">Tanggal Diterbitkan</th>
                    <th class="p-4 font-semibold text-center">Status Akhir</th>
                    <th class="p-4 font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-100">
                @forelse ($arsip as $surat)
                    @php
                        $badge = $surat->statusBadge();
                        $isDitolak = $surat->status === 'ditolak';
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors {{ $isDitolak ? 'bg-red-50/10' : '' }}"
                        :class="selectedRows.includes({{ $surat->id }}) ? 'bg-green-50/30' : ''">
                        {{-- Checkbox --}}
                        <td class="p-4 text-center">
                            <input type="checkbox" class="custom-checkbox inline-block"
                                   value="{{ $surat->id }}"
                                   x-model.number="selectedRows">
                        </td>

                        {{-- Nomor & Jenis --}}
                        <td class="p-4">
                            <div class="font-bold font-mono text-[13px] cursor-pointer {{ $isDitolak ? 'text-gray-500 line-through' : 'text-gray-900 hover:text-green-600' }}"
                                 @click="openDetail({{ $surat->id }})">
                                {{ $surat->nomor_tiket }}
                            </div>
                            <div class="text-[11px] text-gray-500 mt-0.5">
                                <span class="font-semibold text-gray-700">Jenis:</span> {{ $surat->jenisLabel() }}
                            </div>
                        </td>

                        {{-- Pemohon --}}
                        <td class="p-4">
                            @if ($surat->penduduk)
                                <div class="font-semibold text-gray-800">{{ $surat->penduduk->nama }}</div>
                                <div class="text-[10px] text-gray-400 mt-0.5">NIK: {{ $surat->penduduk->nik }}</div>
                            @else
                                <span class="text-gray-400 italic">Data tidak tersedia</span>
                            @endif
                        </td>

                        {{-- Tanggal --}}
                        <td class="p-4 text-center">
                            @if ($surat->tanggal_selesai)
                                <span class="text-xs font-semibold text-gray-700">{{ $surat->tanggal_selesai->translatedFormat('d M Y') }}</span><br>
                                <span class="text-[10px] text-gray-400">{{ $surat->tanggal_selesai->format('H:i') }} WIB</span>
                            @else
                                <span class="text-xs font-semibold text-gray-700">{{ $surat->updated_at->translatedFormat('d M Y') }}</span><br>
                                <span class="text-[10px] text-gray-400">{{ $surat->updated_at->format('H:i') }} WIB</span>
                            @endif
                        </td>

                        {{-- Status Badge --}}
                        <td class="p-4 text-center">
                            <span class="inline-flex items-center gap-1.5 {{ $badge['bg'] }} {{ $badge['text'] }} text-[10px] font-bold px-2.5 py-1 rounded border {{ $badge['border'] }} uppercase tracking-wide">
                                <i class="fa-solid {{ $badge['icon'] }}"></i> {{ $badge['label'] }}
                            </span>
                        </td>

                        {{-- Actions --}}
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button @click="openDetail({{ $surat->id }})"
                                    class="w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:text-green-600 hover:bg-green-50 flex items-center justify-center transition-colors border border-gray-200 cursor-pointer"
                                    title="Buka Detail & Preview">
                                    <i class="fa-solid fa-folder-open text-xs"></i>
                                </button>
                                @unless ($isDitolak)
                                    <button class="w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:text-blue-600 hover:bg-blue-50 flex items-center justify-center transition-colors border border-gray-200 cursor-pointer"
                                        title="Download PDF">
                                        <i class="fa-solid fa-download text-xs"></i>
                                    </button>
                                    <button class="w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:text-gray-900 hover:bg-gray-200 flex items-center justify-center transition-colors border border-gray-200 cursor-pointer"
                                        title="Print Fisik">
                                        <i class="fa-solid fa-print text-xs"></i>
                                    </button>
                                @endunless
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <i class="fa-regular fa-folder-open text-4xl text-gray-200"></i>
                                <p class="text-sm text-gray-500 font-medium">Belum ada arsip surat.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination Footer --}}
    @if ($arsip->hasPages())
        <div class="p-4 border-t border-gray-100 bg-gray-50 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-sm text-gray-500 font-medium">
                Menampilkan <span class="font-bold text-gray-900">{{ $arsip->firstItem() }}-{{ $arsip->lastItem() }}</span>
                dari <span class="font-bold text-gray-900">{{ number_format($arsip->total()) }}</span> Arsip
            </p>
            <div>{{ $arsip->links() }}</div>
        </div>
    @elseif ($arsip->isNotEmpty())
        <div class="p-4 border-t border-gray-100 bg-gray-50">
            <p class="text-sm text-gray-500 font-medium text-center">
                Menampilkan <span class="font-bold text-gray-900">{{ $arsip->count() }}</span> dari
                <span class="font-bold text-gray-900">{{ number_format($arsip->total()) }}</span> Arsip
            </p>
        </div>
    @endif
</section>
