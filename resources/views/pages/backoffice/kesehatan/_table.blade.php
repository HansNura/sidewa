{{-- Data Table --}}
<section class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden flex flex-col xl:col-span-2">
    <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row justify-between sm:items-center gap-4 bg-gray-50/50">
        <div>
            <h3 class="font-bold text-gray-800">Daftar Pengukuran Balita</h3>
            <p class="text-xs text-gray-500 mt-0.5">Hasil penimbangan dan pengukuran tinggi badan terbaru.</p>
        </div>
        <form method="GET" action="{{ route('admin.kesehatan.index') }}" class="flex gap-2">
            @if ($dusun)
                <input type="hidden" name="dusun" value="{{ $dusun }}">
            @endif
            <div class="relative w-full sm:w-48 shrink-0">
                <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari NIK / Nama..."
                       class="w-full bg-white border border-gray-300 rounded-lg pl-9 pr-4 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none">
            </div>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead>
                <tr class="bg-gray-50/80 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                    <th class="p-4 font-semibold">Identitas Anak</th>
                    <th class="p-4 font-semibold text-center">Umur (Bln)</th>
                    <th class="p-4 font-semibold text-center">Tinggi / Berat</th>
                    <th class="p-4 font-semibold text-center">Status Gizi (TB/U)</th>
                    <th class="p-4 font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-100">
                @forelse ($pengukuran as $p)
                    @php
                        $badge = $p->statusBadge();
                        $isStunting = $p->isStunting();
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors {{ $isStunting ? ($p->status_gizi === 'sangat_pendek' ? 'bg-red-50/30' : 'bg-red-50/10') : '' }}">
                        {{-- Identity --}}
                        <td class="p-4">
                            <div class="font-bold text-gray-900 leading-tight flex items-center gap-2">
                                {{ $p->penduduk?->nama ?? '-' }}
                                @if ($p->status_gizi === 'sangat_pendek')
                                    <i class="fa-solid fa-circle-exclamation text-red-500 text-xs" title="Perlu Intervensi Segera"></i>
                                @endif
                            </div>
                            <div class="text-[10px] font-mono text-gray-500 mt-0.5">{{ $p->penduduk?->nik }}</div>
                            @if ($p->nama_ortu)
                                <div class="text-[10px] text-gray-400 mt-0.5">Ortu: {{ $p->nama_ortu }}</div>
                            @endif
                        </td>

                        {{-- Age --}}
                        <td class="p-4 text-center">
                            <span class="font-bold text-gray-700">{{ $p->umur_bulan }}</span>
                        </td>

                        {{-- Height / Weight --}}
                        <td class="p-4 text-center">
                            <span class="font-bold text-gray-800">{{ $p->tinggi_badan }} cm</span>
                            @if ($isStunting)
                                <i class="fa-solid fa-arrow-down text-[10px] text-red-500"></i>
                            @endif
                            <br>
                            <span class="text-xs text-gray-500">{{ $p->berat_badan }} kg</span>
                        </td>

                        {{-- Status --}}
                        <td class="p-4 text-center">
                            <span class="{{ $badge['bg'] }} {{ $badge['text'] }} text-[10px] font-bold px-2.5 py-1 rounded border {{ $badge['border'] }} uppercase tracking-wide">
                                {{ $badge['label'] }}
                            </span>
                        </td>

                        {{-- Actions --}}
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button" @click="openDetail({{ $p->penduduk_id }})"
                                    class="w-7 h-7 rounded-lg bg-gray-50 text-gray-500 hover:text-green-600 hover:bg-green-50 flex items-center justify-center transition-colors border border-gray-200 cursor-pointer"
                                    title="Detail Riwayat">
                                    <i class="fa-solid fa-notes-medical text-xs"></i>
                                </button>
                                <form method="POST" action="{{ route('admin.kesehatan.destroy', $p) }}"
                                      onsubmit="return confirm('Hapus data pengukuran ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-7 h-7 rounded-lg bg-gray-50 text-gray-500 hover:text-red-500 hover:bg-red-50 flex items-center justify-center transition-colors border border-gray-200 cursor-pointer"
                                        title="Hapus">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-gray-400">
                            <i class="fa-solid fa-stethoscope text-3xl mb-2 block"></i>
                            <p class="font-semibold">Belum ada data pengukuran balita.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if ($pengukuran->hasPages())
        <div class="p-4 border-t border-gray-100 bg-gray-50 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-sm text-gray-500 font-medium">
                Menampilkan <span class="font-bold text-gray-900">{{ $pengukuran->firstItem() }}-{{ $pengukuran->lastItem() }}</span>
                dari <span class="font-bold text-gray-900">{{ $pengukuran->total() }}</span> Balita
            </p>
            {{ $pengukuran->links('vendor.pagination.tailwind') }}
        </div>
    @endif
</section>
