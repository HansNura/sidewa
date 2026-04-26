{{-- Recipients Table --}}
<section class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden flex flex-col">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead>
                <tr class="bg-gray-50/80 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                    <th class="p-4 font-semibold">Penerima Manfaat (KPM)</th>
                    <th class="p-4 font-semibold">Program</th>
                    <th class="p-4 font-semibold text-center">Tanggal Distribusi</th>
                    <th class="p-4 font-semibold">Status Tracking</th>
                    <th class="p-4 font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-100">
                @forelse ($penerima as $p)
                    @php $badge = $p->statusBadge(); @endphp
                    <tr class="hover:bg-gray-50 transition-colors {{ $p->is_duplikat ? 'bg-red-50/10 hover:bg-red-50/20' : '' }}">
                        {{-- Identity --}}
                        <td class="p-4">
                            <div class="font-bold text-gray-900 leading-tight flex items-center gap-2">
                                {{ $p->penduduk?->nama ?? '-' }}
                                @if ($p->is_duplikat)
                                    <i class="fa-solid fa-triangle-exclamation text-amber-500 text-xs" title="Indikasi Duplikat"></i>
                                @endif
                            </div>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="text-[10px] font-mono text-gray-500" title="NIK">{{ $p->penduduk?->nik }}</span>
                                @if ($p->desil)
                                    <span class="text-[9px] font-bold text-blue-600 bg-blue-50 px-1 rounded border border-blue-100">Desil {{ $p->desil }}</span>
                                @endif
                            </div>
                        </td>

                        {{-- Program --}}
                        <td class="p-4">
                            <span class="font-semibold text-gray-800">{{ $p->program?->nama }}</span>
                            @if ($p->tahap)
                                <div class="text-[10px] text-gray-500 mt-0.5">{{ $p->tahap }}</div>
                            @endif
                        </td>

                        {{-- Date --}}
                        <td class="p-4 text-center">
                            @if ($p->tanggal_distribusi)
                                <span class="text-xs text-gray-600">{{ $p->tanggal_distribusi->format('d M Y') }}</span>
                            @else
                                <span class="text-xs text-gray-400 italic">Belum diambil</span>
                            @endif
                        </td>

                        {{-- Status --}}
                        <td class="p-4">
                            <span class="inline-flex items-center gap-1.5 {{ $badge['bg'] }} {{ $badge['text'] }} text-[10px] font-bold px-2.5 py-1 rounded-full border {{ $badge['border'] }} uppercase tracking-wide">
                                <i class="fa-solid {{ $badge['icon'] }}"></i> {{ $badge['label'] }}
                            </span>
                        </td>

                        {{-- Actions --}}
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button" @click="openDetail({{ $p->id }})"
                                    class="w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:text-green-600 hover:bg-green-50 flex items-center justify-center transition-colors border border-gray-200 cursor-pointer"
                                    title="Detail & Tracking">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </button>

                                @if ($p->status_distribusi !== 'diterima')
                                    <form method="POST" action="{{ route('admin.bansos.update-status', $p) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status_distribusi" value="diterima">
                                        <button type="submit"
                                            class="w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:text-green-600 hover:bg-green-50 flex items-center justify-center transition-colors border border-gray-200 cursor-pointer"
                                            title="Tandai Diterima"
                                            onclick="return confirm('Tandai bantuan sudah diterima?')">
                                            <i class="fa-solid fa-check text-xs"></i>
                                        </button>
                                    </form>
                                @endif

                                <form method="POST" action="{{ route('admin.bansos.destroy', $p) }}"
                                      onsubmit="return confirm('Hapus penerima ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:text-red-500 hover:bg-red-50 flex items-center justify-center transition-colors border border-gray-200 cursor-pointer"
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
                            <i class="fa-solid fa-hand-holding-heart text-3xl mb-2 block"></i>
                            <p class="font-semibold">Belum ada data penerima bantuan.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="p-4 border-t border-gray-100 bg-gray-50 flex flex-col sm:flex-row items-center justify-between gap-4">
        <p class="text-xs text-gray-500 font-medium">
            Menampilkan <span class="font-bold text-gray-900">{{ $penerima->firstItem() ?? 0 }}-{{ $penerima->lastItem() ?? 0 }}</span>
            dari <span class="font-bold text-gray-900">{{ $penerima->total() }}</span> Penerima
        </p>
        @if ($penerima->hasPages())
            <div class="scale-90 origin-right">
                {{ $penerima->links() }}
            </div>
        @endif
    </div>
</section>
