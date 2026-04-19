<section class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden flex flex-col">
    <!-- Filter & Search Panel -->
    <div class="p-5 border-b border-gray-100 flex flex-col md:flex-row justify-between md:items-center gap-4 bg-gray-50/50">
        <form action="{{ route('admin.buku-tamu.index') }}" method="GET" class="flex flex-wrap items-center gap-2 w-full md:w-auto">
            <input type="hidden" name="period" value="{{ $periodInput }}">
            
            <div class="relative w-full sm:w-64">
                <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama, Instansi..."
                    class="w-full bg-white border border-gray-300 rounded-lg pl-8 pr-4 py-2 text-xs focus:ring-2 focus:ring-green-500 outline-none">
            </div>
            
            <select name="filter_tujuan" onchange="this.form.submit()"
                class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-xs font-semibold text-gray-600 outline-none cursor-pointer">
                <option value="Semua Tujuan">Semua Kategori</option>
                <option value="Layanan Surat" {{ request('filter_tujuan') == 'Layanan Surat' ? 'selected' : '' }}>Layanan Surat</option>
                <option value="Koordinasi" {{ request('filter_tujuan') == 'Koordinasi' ? 'selected' : '' }}>Koordinasi</option>
                <option value="Lain-lain" {{ request('filter_tujuan') == 'Lain-lain' ? 'selected' : '' }}>Lain-lain</option>
            </select>
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead>
                <tr class="bg-gray-100/80 text-gray-500 text-[10px] uppercase tracking-wider border-b border-gray-200">
                    <th class="p-4 font-bold">Waktu & Tanggal</th>
                    <th class="p-4 font-bold">Nama Tamu</th>
                    <th class="p-4 font-bold">Asal / Instansi</th>
                    <th class="p-4 font-bold">Kategori Kunjungan</th>
                    <th class="p-4 font-bold text-center">Status</th>
                    <th class="p-4 font-bold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-100">
                @forelse($bukuTamus as $tamu)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="p-4">
                            <div class="font-bold text-gray-900">{{ $tamu->waktu_masuk->translatedFormat('d M Y') }}</div>
                            <div class="text-[10px] text-gray-500 mt-0.5">{{ $tamu->waktu_masuk->format('H:i') }} WIB</div>
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 shrink-0 text-[10px]">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <span class="font-bold text-gray-800">{{ $tamu->nama_tamu }}</span>
                            </div>
                        </td>
                        <td class="p-4 text-gray-600 {!! $tamu->instansi ? 'italic' : '' !!}">
                            {{ $tamu->instansi ?? 'Pribadi / Warga' }}
                        </td>
                        <td class="p-4">
                            @php
                                $tColor = $tamu->tujuanColor();
                                if ($tColor == 'primary') $tColor = 'green';
                            @endphp
                            <span class="bg-{{ $tColor }}-50 text-{{ $tColor }}-700 text-[10px] font-bold px-2 py-0.5 rounded border border-{{ $tColor }}-200">
                                {{ $tamu->tujuan_kategori }}
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            @if($tamu->status === 'selesai')
                                <span class="bg-green-50 text-green-700 text-[9px] font-bold px-2 py-1 rounded-full uppercase"><i class="fa-solid fa-check mr-1"></i>Selesai</span>
                            @else
                                <span class="bg-amber-50 text-amber-700 text-[9px] font-bold px-2 py-1 rounded-full uppercase"><i class="fa-solid fa-clock mr-1"></i>Menunggu</span>
                            @endif
                        </td>
                        <td class="p-4 text-center">
                            <button type="button" @click="openDetailDrawer({{ $tamu->id }})"
                                class="bg-white border border-gray-200 text-gray-600 hover:text-green-700 hover:bg-green-50 px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm transition-all cursor-pointer">
                                Detail
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-500">Belum ada kunjungan pada periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="p-4 bg-gray-50 border-t border-gray-100 shrink-0">
        {{ $bukuTamus->links('pagination::tailwind') }}
    </div>
</section>
