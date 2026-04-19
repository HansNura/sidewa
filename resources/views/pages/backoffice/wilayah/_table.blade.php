{{-- Data Table --}}
<section class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden flex flex-col">
    <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row justify-between sm:items-center gap-4 bg-gray-50/50">
        <div>
            <h3 class="font-bold text-gray-800">Daftar Wilayah Administratif</h3>
            <p class="text-xs text-gray-500 mt-0.5">Tabel integrasi data wilayah dengan jumlah penduduk dan keluarga.</p>
        </div>
        <form method="GET" action="{{ route('admin.wilayah.index') }}" class="relative w-full sm:w-64 shrink-0">
            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama wilayah..."
                   class="w-full bg-white border border-gray-300 rounded-lg pl-9 pr-4 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none">
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead>
                <tr class="bg-gray-50/80 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                    <th class="p-4 font-semibold">Nama Wilayah</th>
                    <th class="p-4 font-semibold text-center">Tipe Tingkat</th>
                    <th class="p-4 font-semibold">Induk Wilayah (Parent)</th>
                    <th class="p-4 font-semibold text-center">Kepala/Ketua</th>
                    <th class="p-4 font-semibold text-center">Populasi</th>
                    <th class="p-4 font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-100">
                @forelse ($flatList as $item)
                    @php
                        $w = $item['wilayah'];
                        $depth = $item['depth'];
                        $badge = $w->tipeBadge();
                        $populasi = $w->populasi();
                        $jumlahKK = $w->jumlahKK();
                        $paddingLeft = match($depth) {
                            0 => 'pl-4',
                            1 => 'pl-8',
                            2 => 'pl-12',
                        };
                        $icons = match($w->tipe) {
                            'dusun' => '<i class="fa-solid fa-map text-amber-500 mr-2 text-xs"></i>',
                            'rw'    => '<i class="fa-regular fa-folder text-blue-500 mr-2 text-xs"></i>',
                            'rt'    => '<i class="fa-solid fa-circle text-gray-300 mr-2 text-[6px] align-middle"></i>',
                        };
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors {{ $populasi === 0 && $w->tipe === 'rt' ? 'bg-red-50/20' : '' }}">
                        {{-- Name --}}
                        <td class="p-4 font-bold text-gray-900 {{ $paddingLeft }} relative">
                            @if ($depth > 0)
                                <div class="absolute {{ $depth === 1 ? 'left-4' : 'left-8' }} top-1/2 w-3 h-px bg-gray-300"></div>
                            @endif
                            {!! $icons !!} {{ $w->label() }}
                        </td>

                        {{-- Type badge --}}
                        <td class="p-4 text-center">
                            <span class="{{ $badge['bg'] }} {{ $badge['text'] }} text-[10px] font-bold px-2 py-0.5 rounded border {{ $badge['border'] }}">
                                {{ strtoupper($w->tipe) }}
                            </span>
                        </td>

                        {{-- Parent --}}
                        <td class="p-4">
                            @if ($w->parent_id)
                                <span class="text-xs text-gray-600 bg-gray-100 px-2 py-1 rounded font-medium">
                                    {{ $w->parentPath() }}
                                </span>
                            @else
                                <span class="text-xs text-gray-400 italic">- (Root)</span>
                            @endif
                        </td>

                        {{-- Leader --}}
                        <td class="p-4 text-center">
                            @if ($w->kepala_nama)
                                <div class="font-medium text-gray-800">{{ $w->kepala_nama }}</div>
                                @if ($w->kepala_jabatan)
                                    <div class="text-[10px] text-gray-500">{{ $w->kepala_jabatan }}</div>
                                @endif
                            @else
                                <span class="text-xs text-gray-400 italic">Belum Ditunjuk</span>
                            @endif
                        </td>

                        {{-- Population --}}
                        <td class="p-4 text-center">
                            <div class="flex flex-col items-center gap-1">
                                @if ($populasi === 0 && $w->tipe === 'rt')
                                    <span class="text-xs font-bold text-red-600 bg-red-50 border border-red-200 px-2 py-0.5 rounded flex items-center gap-1 w-24 justify-center">
                                        0 Jiwa <i class="fa-solid fa-triangle-exclamation text-[10px]"></i>
                                    </span>
                                @else
                                    <span class="text-xs font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded w-24 border border-blue-100">
                                        {{ number_format($populasi) }} Jiwa
                                    </span>
                                    @if ($w->tipe === 'dusun')
                                        <span class="text-xs font-bold text-green-700 bg-green-50 px-2 py-0.5 rounded w-24 border border-green-100">
                                            {{ number_format($jumlahKK) }} KK
                                        </span>
                                    @endif
                                @endif
                            </div>
                        </td>

                        {{-- Actions --}}
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button" @click="openDetail({{ $w->id }})"
                                    class="w-8 h-8 rounded-lg text-gray-400 hover:text-green-600 hover:bg-green-50 flex items-center justify-center transition-colors border border-gray-200 cursor-pointer"
                                    title="Detail">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                                <form method="POST" action="{{ route('admin.wilayah.destroy', $w) }}"
                                      onsubmit="return confirm('Hapus {{ $w->label() }}? Semua sub-wilayah juga akan dihapus.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-8 h-8 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 flex items-center justify-center transition-colors border border-gray-200 cursor-pointer"
                                        title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-400">
                            <i class="fa-solid fa-inbox text-3xl mb-2 block"></i>
                            <p class="font-semibold">Belum ada data wilayah.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
