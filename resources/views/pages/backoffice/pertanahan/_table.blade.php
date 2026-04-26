{{-- Filter + Table --}}
<section class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden flex flex-col">

    {{-- Filter Panel --}}
    <div class="p-5 border-b border-gray-100 bg-gray-50/50">
        <form method="GET" action="{{ route('admin.pertanahan.index') }}" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <i class="fa-solid fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" name="search" value="{{ $search }}"
                    placeholder="Cari ID Lahan, Nama Pemilik, atau Blok Lokasi..."
                    class="w-full bg-white border border-gray-300 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none">
            </div>

            <div class="w-full md:w-48 relative shrink-0">
                <select name="kepemilikan" onchange="this.form.submit()"
                    class="w-full bg-white border border-gray-300 text-sm rounded-xl px-4 py-2.5 appearance-none focus:ring-2 focus:ring-green-500 outline-none cursor-pointer">
                    <option value="">Status Kepemilikan</option>
                    <option value="desa" {{ $kepemilikan === 'desa' ? 'selected' : '' }}>Aset Desa (TKD)</option>
                    <option value="warga" {{ $kepemilikan === 'warga' ? 'selected' : '' }}>Milik Pribadi (Warga)
                    </option>
                    <option value="fasum" {{ $kepemilikan === 'fasum' ? 'selected' : '' }}>Fasilitas Umum</option>
                </select>
                <i
                    class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
            </div>

            <div class="w-full md:w-40 relative shrink-0">
                <select name="legalitas" onchange="this.form.submit()"
                    class="w-full bg-white border border-gray-300 text-sm rounded-xl px-4 py-2.5 appearance-none focus:ring-2 focus:ring-green-500 outline-none cursor-pointer">
                    <option value="">Legalitas</option>
                    <option value="shm" {{ $legalitas === 'shm' ? 'selected' : '' }}>SHM</option>
                    <option value="shgb" {{ $legalitas === 'shgb' ? 'selected' : '' }}>SHP / SHGB</option>
                    <option value="girik" {{ $legalitas === 'girik' ? 'selected' : '' }}>Girik / Letter C</option>
                    <option value="ajb" {{ $legalitas === 'ajb' ? 'selected' : '' }}>AJB</option>
                    <option value="belum_sertifikat" {{ $legalitas === 'belum_sertifikat' ? 'selected' : '' }}>Belum
                        Sertifikat</option>
                </select>
                <i
                    class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead>
                <tr class="bg-gray-50/80 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-200">
                    <th class="p-4 font-semibold">ID Lahan & Pemilik</th>
                    <th class="p-4 font-semibold text-center">Luas (m²)</th>
                    <th class="p-4 font-semibold">Lokasi / Blok</th>
                    <th class="p-4 font-semibold">Status Legalitas</th>
                    <th class="p-4 font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-100">
                @forelse ($lahan as $item)
                    @php
                        $kBadge = $item->kepemilikanBadge();
                        $lBadge = $item->legalitasBadge();
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="p-4">
                            <div
                                class="font-bold leading-tight flex items-center gap-1.5 {{ $item->kepemilikan === 'desa' ? 'text-green-700' : 'text-gray-900' }}">
                                @if ($item->kepemilikan === 'desa')
                                    <i class="fa-solid fa-building-flag text-[10px]"></i>
                                @endif
                                {{ $item->displayPemilik() }}
                            </div>
                            <div class="text-[10px] font-mono text-gray-500 mt-0.5">ID: {{ $item->kode_lahan }}</div>
                        </td>

                        <td class="p-4 text-center">
                            <span class="font-bold text-gray-800">{{ number_format($item->luas) }}</span>
                            <span class="text-[10px] text-gray-500">m²</span>
                        </td>

                        <td class="p-4 text-gray-600">
                            {{ $item->lokasi_blok }}<br>
                            <span class="text-[10px] text-gray-400">
                                @if ($item->dusun)
                                    Dusun {{ $item->dusun }}
                                @endif
                                @if ($item->rt)
                                    , RT {{ $item->rt }}
                                @endif
                            </span>
                        </td>

                        <td class="p-4">
                            <span
                                class="{{ $lBadge['bg'] }} {{ $lBadge['text'] }} text-[10px] font-bold px-2 py-0.5 rounded border {{ $lBadge['border'] }}">
                                {{ $lBadge['label'] }}
                            </span>
                        </td>

                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button" @click="openDetail({{ $item->id }})"
                                    class="w-7 h-7 rounded-lg bg-gray-50 text-gray-500 hover:text-green-600 hover:bg-green-50 flex items-center justify-center transition-colors border border-gray-200 cursor-pointer"
                                    title="Detail">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </button>

                                <form method="POST" action="{{ route('admin.pertanahan.destroy', $item) }}"
                                    onsubmit="return confirm('Hapus data lahan ini?')">
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
                            <i class="fa-solid fa-map-location text-3xl mb-2 block"></i>
                            <p class="font-semibold">Belum ada data pertanahan.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if ($lahan->hasPages())
        <div
            class="p-4 border-t border-gray-100 bg-gray-50 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-sm text-gray-500 font-medium">
                Menampilkan <span
                    class="font-bold text-gray-900">{{ $lahan->firstItem() }}-{{ $lahan->lastItem() }}</span>
                dari <span class="font-bold text-gray-900">{{ $lahan->total() }}</span> Bidang Tanah
            </p>
            {{ $lahan->links('pagination::tailwind') }}
        </div>
    @endif
</section>
