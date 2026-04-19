<section class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden flex flex-col">
    <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row justify-between sm:items-center gap-4 bg-gray-50/50">
        <div>
            <h3 class="font-bold text-gray-800">Tabel Rekapitulasi Per Pegawai</h3>
            <p class="text-xs text-gray-500 mt-0.5">Total hari kerja pada periode terpilih: <span class="font-bold border px-1 rounded">{{ $workingDays }}</span> hari.</p>
        </div>
        <div class="flex items-center gap-2">
            <form action="{{ route('admin.presensi.rekap') }}" method="GET" class="relative w-full sm:w-64 shrink-0">
                <input type="hidden" name="period" value="{{ $periodInput }}">
                <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama Pegawai..."
                    class="w-full bg-white border border-gray-300 rounded-lg pl-8 pr-4 py-2 text-xs focus:ring-2 focus:ring-green-500 outline-none">
            </form>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead>
                <tr class="bg-gray-100/80 text-gray-500 text-[10px] uppercase tracking-wider border-b border-gray-200">
                    <th class="p-4 font-bold">Pegawai</th>
                    <th class="p-4 font-bold text-center" title="Hadir">Hadir (H)</th>
                    <th class="p-4 font-bold text-center" title="Terlambat">Terlambat (T)</th>
                    <th class="p-4 font-bold text-center" title="Izin/Sakit/Dinas">Izin/Dinas (I)</th>
                    <th class="p-4 font-bold text-center" title="Alpha/Tanpa Keterangan">Alpha (A)</th>
                    <th class="p-4 font-bold text-center">Performa (%)</th>
                    <th class="p-4 font-bold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-100">
                @forelse($pegawais as $user)
                    @php
                        $perf = $user->performance;
                        $perfColor = $perf >= 90 ? 'green' : ($perf >= 70 ? 'amber' : 'red');
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $user->avatarUrl() }}"
                                    class="w-9 h-9 rounded-lg shadow-sm" alt="Avatar">
                                <div>
                                    <p class="font-bold text-gray-900 leading-tight">{{ $user->name }}</p>
                                    <p class="text-[10px] text-gray-500 mt-0.5 font-medium">{{ $user->jabatan ?? $user->roleName() }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-4 text-center font-bold text-gray-700">{{ $user->recap['hadir'] }}</td>
                        <td class="p-4 text-center font-bold text-amber-600">{{ $user->recap['terlambat'] }}</td>
                        <td class="p-4 text-center font-bold text-blue-600">{{ $user->recap['izin'] }}</td>
                        <td class="p-4 text-center font-bold text-red-600">{{ $user->recap['alpha'] }}</td>
                        <td class="p-4 text-center">
                            <div class="flex flex-col items-center">
                                <span class="text-xs font-extrabold text-{{ $perfColor }}-600">{{ $perf }}%</span>
                                <div class="w-16 h-1 bg-gray-100 rounded-full mt-1">
                                    <div class="h-1 bg-{{ $perfColor }}-500 rounded-full" style="width: {{ $perf }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="p-4 text-center">
                            <button @click="openDrawer({{ $user->id }})"
                                class="bg-white border border-gray-200 text-gray-600 hover:text-green-700 hover:bg-green-50 px-3 py-1.5 rounded-lg text-xs font-bold transition-all shadow-sm cursor-pointer">
                                Detail
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-gray-500 text-sm">Belum ada data pegawai untuk ditampilkan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
