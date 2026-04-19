{{-- Data Table --}}
<section class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden flex flex-col">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead>
                <tr class="bg-gray-50/80 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                    <th class="p-4 font-semibold">Identitas Warga</th>
                    <th class="p-4 font-semibold">No. Kartu Keluarga</th>
                    <th class="p-4 font-semibold">Alamat</th>
                    <th class="p-4 font-semibold text-center">Umur & JK</th>
                    <th class="p-4 font-semibold">Status</th>
                    <th class="p-4 font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-100">
                @forelse ($penduduk as $p)
                    @php
                        $sc = $p->statusColor();
                        $isDead = $p->status === 'meninggal';
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors {{ $isDead ? 'opacity-75' : '' }}" data-row-id="{{ $p->id }}">
                        {{-- Identity --}}
                        <td class="p-4">
                            <div class="font-bold leading-tight {{ $isDead ? 'text-gray-600 line-through' : 'text-gray-900' }}">
                                {{ $p->nama }}
                            </div>
                            <div class="text-xs font-mono {{ $isDead ? 'text-gray-400' : 'text-gray-500' }} mt-0.5" title="NIK">
                                {{ $p->nik }}
                            </div>
                        </td>

                        {{-- KK --}}
                        <td class="p-4">
                            <span class="font-mono font-medium {{ $isDead ? 'text-gray-500' : 'text-green-600' }}">{{ $p->no_kk }}</span>
                            <div class="text-[10px] text-gray-500 mt-0.5">{{ $p->status_hubungan }}</div>
                        </td>

                        {{-- Address --}}
                        <td class="p-4">
                            <div class="{{ $isDead ? 'text-gray-500' : 'text-gray-800' }}">{{ $p->alamatLengkap() }}</div>
                        </td>

                        {{-- Age & Gender --}}
                        <td class="p-4 text-center">
                            <span class="font-bold {{ $isDead ? 'text-gray-400' : 'text-gray-700' }}">{{ $p->umur() }}</span> Thn<br>
                            @php
                                $genderClass = $isDead
                                    ? 'text-gray-500 bg-gray-200'
                                    : ($p->jenis_kelamin === 'L' ? 'text-blue-600 bg-blue-50' : 'text-pink-600 bg-pink-50');
                            @endphp
                            <span class="text-[10px] font-bold {{ $genderClass }} px-1.5 py-0.5 rounded mt-1 inline-block">
                                {{ $p->jenis_kelamin }}
                            </span>
                        </td>

                        {{-- Status --}}
                        <td class="p-4">
                            <span class="{{ $sc['bg'] }} {{ $sc['text'] }} text-[10px] font-bold px-2 py-0.5 rounded uppercase border {{ $sc['border'] }}">
                                {{ $sc['label'] }}
                            </span>
                        </td>

                        {{-- Actions --}}
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button" @click="openDetail({{ $p->id }})"
                                    class="w-7 h-7 rounded-lg bg-gray-50 text-gray-500 hover:text-green-600 hover:bg-green-50 flex items-center justify-center transition-colors border border-gray-200 cursor-pointer"
                                    title="Detail">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </button>
                                @unless ($isDead)
                                    <form method="POST" action="{{ route('admin.penduduk.destroy', $p) }}"
                                          onsubmit="return confirm('Hapus data {{ $p->nama }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="w-7 h-7 rounded-lg bg-gray-50 text-gray-500 hover:text-red-500 hover:bg-red-50 flex items-center justify-center transition-colors border border-gray-200 cursor-pointer"
                                            title="Hapus">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                @endunless
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-400">
                            <i class="fa-solid fa-inbox text-3xl mb-2 block"></i>
                            <p class="font-semibold">Belum ada data penduduk.</p>
                            <p class="text-xs mt-1">Klik "Tambah Warga" untuk menambahkan data baru.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if ($penduduk->hasPages())
        <div class="p-4 border-t border-gray-100 bg-gray-50 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-sm text-gray-500 font-medium">
                Menampilkan <span class="font-bold text-gray-900">{{ $penduduk->firstItem() }}-{{ $penduduk->lastItem() }}</span>
                dari <span class="font-bold text-gray-900">{{ number_format($penduduk->total()) }}</span> penduduk
            </p>
            <div>
                {{ $penduduk->links() }}
            </div>
        </div>
    @endif
</section>
