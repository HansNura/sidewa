{{-- KK Data Table --}}
<section class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden flex flex-col">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead>
                <tr class="bg-gray-50/80 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                    <th class="p-4 font-semibold">Nomor Kartu Keluarga</th>
                    <th class="p-4 font-semibold">Kepala Keluarga</th>
                    <th class="p-4 font-semibold text-center">Jml Anggota</th>
                    <th class="p-4 font-semibold">Alamat Wilayah</th>
                    <th class="p-4 font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-100">
                @forelse ($kkList as $kk)
                    @php
                        $kepala = $kk->kepalaKeluarga;
                        $count = $kk->anggota_count;
                        $countColor = $count >= 5 ? 'bg-amber-50 text-amber-700 border-amber-100' : 'bg-green-50 text-green-700 border-green-100';
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors cursor-pointer" @click="openDetail({{ $kk->id }})">
                        {{-- KK Number --}}
                        <td class="p-4">
                            <div class="font-bold text-green-700 font-mono text-base">{{ $kk->no_kk }}</div>
                            @if ($kk->tanggal_dikeluarkan)
                                <div class="text-[10px] text-gray-500 mt-0.5">
                                    Diterbitkan: {{ $kk->tanggal_dikeluarkan->translatedFormat('d M Y') }}
                                </div>
                            @endif
                        </td>

                        {{-- Kepala Keluarga --}}
                        <td class="p-4">
                            @if ($kepala)
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full {{ $kepala->jenis_kelamin === 'L' ? 'bg-blue-50 text-blue-600' : 'bg-pink-50 text-pink-600' }} flex items-center justify-center shrink-0">
                                        <i class="fa-solid {{ $kepala->jenis_kelamin === 'L' ? 'fa-user-tie' : 'fa-user-nurse' }} text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900 leading-tight">{{ $kepala->nama }}</p>
                                        <p class="text-[10px] text-gray-500 font-mono mt-0.5">{{ $kepala->nik }}</p>
                                    </div>
                                </div>
                            @else
                                <span class="text-gray-400 italic text-xs">Belum ditentukan</span>
                            @endif
                        </td>

                        {{-- Member count --}}
                        <td class="p-4 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 {{ $countColor }} rounded-lg font-bold border">
                                {{ $count }}
                            </span>
                        </td>

                        {{-- Address --}}
                        <td class="p-4">
                            @if ($kk->rt && $kk->rw)
                                <div class="text-gray-800 font-medium">RT {{ $kk->rt }} / RW {{ $kk->rw }}</div>
                            @endif
                            @if ($kk->dusun)
                                <div class="text-xs text-gray-500 mt-0.5">Dusun {{ $kk->dusun }}</div>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="p-4 text-center" @click.stop>
                            <div class="flex items-center justify-center gap-2">
                                <button type="button" @click="openDetail({{ $kk->id }})"
                                    class="w-8 h-8 rounded-lg bg-white border border-gray-200 text-gray-500 hover:text-green-600 hover:bg-green-50 flex items-center justify-center transition-colors shadow-sm cursor-pointer"
                                    title="Detail KK">
                                    <i class="fa-solid fa-folder-open text-xs"></i>
                                </button>
                                <form method="POST" action="{{ route('admin.kartu-keluarga.destroy', $kk) }}"
                                      onsubmit="return confirm('Hapus KK {{ $kk->no_kk }}? Semua anggota akan dilepas.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-8 h-8 rounded-lg bg-white border border-gray-200 text-gray-500 hover:text-red-500 hover:bg-red-50 flex items-center justify-center transition-colors shadow-sm cursor-pointer"
                                        title="Hapus KK">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-gray-400">
                            <i class="fa-solid fa-inbox text-3xl mb-2 block"></i>
                            <p class="font-semibold">Belum ada data Kartu Keluarga.</p>
                            <p class="text-xs mt-1">Klik "Tambah KK" untuk menambahkan data baru.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if ($kkList->hasPages())
        <div class="p-4 border-t border-gray-100 bg-gray-50 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-sm text-gray-500 font-medium">
                Menampilkan <span class="font-bold text-gray-900">{{ $kkList->firstItem() }}-{{ $kkList->lastItem() }}</span>
                dari <span class="font-bold text-gray-900">{{ number_format($kkList->total()) }}</span> Kartu Keluarga
            </p>
            <div>{{ $kkList->links() }}</div>
        </div>
    @endif
</section>
