<div x-show="detailDrawerOpen" class="fixed inset-0 z-[100] flex justify-end" x-cloak>
    <div x-show="detailDrawerOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
        @click="detailDrawerOpen = false"></div>

    <div x-show="detailDrawerOpen" x-transition:enter="transition ease-transform duration-300"
        x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-transform duration-300" x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="relative bg-white w-full max-w-4xl h-full shadow-2xl flex flex-col border-l border-gray-200">

        <!-- Loading -->
        <div x-show="loading" class="absolute inset-0 z-50 bg-white/80 flex items-center justify-center backdrop-blur-sm">
            <i class="fa-solid fa-spinner fa-spin text-4xl text-green-600"></i>
        </div>

        <template x-if="drawerData">
            <div class="flex flex-col h-full">
                <!-- Header Drawer -->
                <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-start bg-gray-50/50 shrink-0">
                    <div class="flex gap-4 items-center">
                        <div class="w-12 h-12 rounded-xl bg-green-100 text-green-700 flex items-center justify-center text-xl shrink-0 border border-green-200 shadow-sm">
                            <i class="fa-solid fa-id-card-clip"></i>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-xl text-gray-900" x-text="drawerData.pegawai.nama"></h3>
                            <p class="text-sm text-gray-500 mt-0.5">Analisis Kehadiran Periode <span class="font-bold text-green-700" x-text="drawerData.period"></span></p>
                        </div>
                    </div>
                    <button @click="detailDrawerOpen = false"
                        class="text-gray-400 hover:text-red-500 w-10 h-10 flex items-center justify-center rounded-xl hover:bg-red-50 transition-colors -mr-2 cursor-pointer">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <!-- Body Drawer -->
                <div class="p-6 overflow-y-auto custom-scrollbar flex-1 space-y-8">

                    <!-- Section Statistics & Performance -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Identity Card -->
                        <div class="md:col-span-1 bg-gray-50 border border-gray-200 rounded-2xl p-5 flex flex-col items-center text-center">
                            <img :src="drawerData.pegawai.avatar" class="w-24 h-24 rounded-full border-4 border-white shadow-md mb-4" alt="Profil">
                            <h4 class="font-bold text-gray-900 leading-tight" x-text="drawerData.pegawai.nama"></h4>
                            <p class="text-xs text-gray-500 mt-1 font-mono" x-text="drawerData.pegawai.nip"></p>
                            <span class="mt-3 bg-green-100 text-green-800 border border-green-200 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider" x-text="drawerData.pegawai.jabatan"></span>
                        </div>

                        <!-- Statistics Bar Chart Small -->
                        <div class="md:col-span-2 bg-white border border-gray-200 rounded-2xl p-5 flex flex-col justify-center">
                            <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 border-b border-gray-50 pb-2">Skor Kinerja Kehadiran</h5>
                            <div class="space-y-5">
                                <div>
                                    <div class="flex justify-between text-xs font-bold mb-1">
                                        <span class="text-gray-700">Persentase Kehadiran</span>
                                        <span class="text-green-700" x-text="drawerData.stats.performance + '%'"></span>
                                    </div>
                                    <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                                        <div class="bg-green-500 h-full transition-all duration-1000" :style="`width: ${drawerData.stats.performance}%`"></div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                                        <p class="text-[10px] text-gray-500 uppercase font-bold">Waktu Ideal Check-in</p>
                                        <p class="text-base font-extrabold text-gray-800 mt-1"><span x-text="drawerData.stats.avgCheckin"></span> <span class="text-[10px] font-normal text-gray-400">WIB Maks</span></p>
                                    </div>
                                    <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                                        <p class="text-[10px] text-gray-500 uppercase font-bold">Poin Keterlambatan</p>
                                        <p class="text-base font-extrabold mt-1" :class="drawerData.stats.poinTerlambat < 0 ? 'text-amber-600' : 'text-gray-400'"><span x-text="drawerData.stats.poinTerlambat"></span> <span class="text-[10px] font-normal text-gray-400">Poin</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section Trend Chart (Personal) -->
                    <div>
                        <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Grafik Performa Mingguan (Hadir)</h5>
                        <div id="personalAttendanceChart" class="w-full h-48"></div>
                    </div>

                    <!-- Section Monthly Breakdown Table -->
                    <div>
                        <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Rincian Kehadiran Harian (Log)</h5>
                        <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                            <table class="w-full text-left bg-white text-xs whitespace-nowrap">
                                <thead class="bg-gray-50 border-b border-gray-200 text-gray-500">
                                    <tr>
                                        <th class="p-3 font-bold">Tanggal</th>
                                        <th class="p-3 font-bold text-center">Masuk</th>
                                        <th class="p-3 font-bold text-center">Pulang</th>
                                        <th class="p-3 font-bold text-center">Status</th>
                                        <th class="p-3">Catatan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <template x-for="log in drawerData.logs" :key="log.tanggal">
                                        <tr :class="log.status === 'terlambat' ? 'bg-amber-50/20' : (log.status === 'alpha' ? 'bg-red-50/20' : '')">
                                            <td class="p-3 font-semibold text-gray-700" x-text="log.tanggal"></td>
                                            <td class="p-3 text-center font-bold" :class="log.status === 'terlambat' ? 'text-amber-600' : 'text-gray-800'" x-text="log.waktu_masuk"></td>
                                            <td class="p-3 text-center text-gray-600" x-text="log.waktu_pulang"></td>
                                            <td class="p-3 text-center">
                                                <span class="px-2 py-0.5 rounded border font-bold text-[9px] uppercase"
                                                    :class="{
                                                        'bg-green-50 text-green-700 border-green-200': log.status === 'hadir',
                                                        'bg-amber-50 text-amber-700 border-amber-200': log.status === 'terlambat',
                                                        'bg-blue-50 text-blue-700 border-blue-200': ['izin','dinas','sakit'].includes(log.status),
                                                        'bg-red-50 text-red-700 border-red-200': log.status === 'alpha'
                                                    }" x-text="log.statusLabel"></span>
                                            </td>
                                            <td class="p-3 text-gray-500 italic max-w-xs truncate" x-text="log.catatan" :title="log.catatan"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                            <template x-if="drawerData.logs.length === 0">
                                <div class="p-4 text-center text-gray-400 text-sm">Tidak ada log presensi untuk periode ini.</div>
                            </template>
                        </div>
                    </div>

                </div>
            </div>
        </template>
    </div>
</div>
