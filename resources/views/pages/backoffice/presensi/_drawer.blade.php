{{-- Drawer Overlay & Panel --}}
<div x-show="detailDrawerOpen" class="fixed inset-0 z-[100] flex justify-end" x-cloak>
    <!-- Backdrop -->
    <div x-show="detailDrawerOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
        @click="detailDrawerOpen = false"></div>

    <!-- Drawer Panel -->
    <div x-show="detailDrawerOpen" x-transition:enter="transition ease-transform duration-300"
        x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-transform duration-300" x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="relative bg-white w-full max-w-md h-full shadow-2xl flex flex-col border-l border-gray-200">

        <!-- Loading State -->
        <div x-show="drawerLoading" class="absolute inset-0 z-50 bg-white/80 flex items-center justify-center backdrop-blur-sm">
            <i class="fa-solid fa-spinner fa-spin text-3xl text-green-600"></i>
        </div>

        <!-- Content (Only render if drawerData exists) -->
        <template x-if="drawerData">
            <div class="flex flex-col h-full">
                <!-- Drawer Header -->
                <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-start bg-gray-50/50 shrink-0">
                    <div>
                        <div class="flex items-center gap-2 mb-1.5">
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded border uppercase tracking-wider"
                                  :class="{
                                      'bg-green-100 text-green-800 border-green-200': drawerData.daily.status === 'hadir',
                                      'bg-amber-100 text-amber-800 border-amber-200': drawerData.daily.status === 'terlambat',
                                      'bg-blue-100 text-blue-800 border-blue-200': ['izin','sakit','dinas'].includes(drawerData.daily.status),
                                      'bg-red-100 text-red-800 border-red-200': drawerData.daily.status === 'alpha'
                                  }">
                                <i class="mr-1 fa-solid"
                                   :class="{
                                       'fa-check': drawerData.daily.status === 'hadir',
                                       'fa-clock': drawerData.daily.status === 'terlambat',
                                       'fa-bed': drawerData.daily.status === 'sakit',
                                       'fa-car-side': drawerData.daily.status === 'dinas',
                                       'fa-user-xmark': drawerData.daily.status === 'alpha'
                                   }"></i>
                                <span x-text="drawerData.daily.statusLabel"></span> Hari Ini
                            </span>
                        </div>
                        <h3 class="font-extrabold text-lg text-gray-900 leading-tight">Log Aktivitas Harian</h3>
                        <p class="text-xs text-gray-500 mt-1" x-text="drawerData.daily.tanggal"></p>
                    </div>
                    <button @click="detailDrawerOpen = false"
                        class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 -mr-2 transition-colors cursor-pointer"><i
                            class="fa-solid fa-xmark text-lg"></i></button>
                </div>

                <!-- Drawer Body -->
                <div class="p-6 overflow-y-auto custom-scrollbar flex-1 space-y-6">

                    <!-- Profile Summary -->
                    <div class="flex items-center gap-4">
                        <div class="relative shrink-0">
                            <img :src="drawerData.pegawai.avatar" class="w-16 h-16 rounded-full shadow-md border-2 border-white" alt="Avatar">
                            <span x-show="drawerData.pegawai.isLive" class="absolute bottom-0 right-0 w-4 h-4 bg-green-500 border-2 border-white rounded-full animate-pulse"></span>
                            <span x-show="!drawerData.pegawai.isLive && drawerData.daily.waktu_pulang" class="absolute bottom-0 right-0 w-4 h-4 bg-gray-400 border-2 border-white rounded-full"></span>
                        </div>
                        <div>
                            <h4 class="text-lg font-extrabold text-gray-900 leading-none mb-1" x-text="drawerData.pegawai.nama"></h4>
                            <p class="text-xs font-mono text-gray-500 mb-1" x-text="'NIP: ' + drawerData.pegawai.nip"></p>
                            <span class="bg-gray-100 text-gray-600 text-[10px] font-bold px-2 py-0.5 rounded border border-gray-200 uppercase tracking-wide" x-text="drawerData.pegawai.jabatan"></span>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div>
                        <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Timeline Presensi (Hari Ini)</h5>
                        
                        <template x-if="drawerData.daily.status === 'alpha'">
                            <div class="text-center py-6 bg-red-50 border border-red-100 rounded-xl">
                                <i class="fa-solid fa-user-xmark text-2xl text-red-300 mb-2"></i>
                                <p class="text-sm font-semibold text-red-800">Belum Ada Riwayat Kehadiran</p>
                                <p class="text-xs text-red-600 mt-1">Sistem menandai sebagai Alpha.</p>
                            </div>
                        </template>

                        <template x-if="['izin','sakit','dinas'].includes(drawerData.daily.status)">
                            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex gap-3 items-start">
                                <i class="fa-solid fa-envelope-open-text text-blue-500 mt-1"></i>
                                <div>
                                    <h4 class="text-sm font-bold text-blue-800 mb-1" x-text="drawerData.daily.statusLabel"></h4>
                                    <p class="text-xs text-blue-600" x-text="drawerData.daily.catatan || 'Tidak ada catatan tambahan'"></p>
                                    
                                    <div class="mt-3 bg-white p-2 rounded border border-blue-100 flex items-center justify-center gap-2 text-[10px] text-gray-500">
                                        <i class="fa-solid fa-keyboard"></i> Di-input melalui modul Koreksi Manual
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template x-if="['hadir','terlambat'].includes(drawerData.daily.status)">
                            <div class="relative ml-2 space-y-6">
                                <!-- Check-out -->
                                <div class="relative z-10 flex gap-4 items-start">
                                    <div class="timeline-track"></div>
                                    <div class="w-6 h-6 rounded-full border-4 border-white flex items-center justify-center shrink-0 shadow-sm mt-0.5 -ml-1"
                                         :class="drawerData.daily.waktu_pulang ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-400'">
                                        <i class="fa-solid fa-arrow-right-from-bracket text-[10px]"></i>
                                    </div>
                                    <div class="flex-1" :class="!drawerData.daily.waktu_pulang ? 'opacity-50' : ''">
                                        <div class="flex justify-between items-start">
                                            <h4 class="text-sm font-bold text-gray-600">Check-out (Pulang)</h4>
                                            <span x-show="drawerData.daily.waktu_pulang" class="text-xs font-bold text-gray-800" x-text="drawerData.daily.waktu_pulang"></span>
                                        </div>
                                        <p class="text-[10px] text-gray-400 mt-0.5" x-text="drawerData.daily.waktu_pulang ? 'Validasi: ' + (drawerData.daily.metode_pulang || '-') : 'Belum melakukan presensi pulang.'"></p>
                                    </div>
                                </div>

                                <!-- Check-in -->
                                <div class="relative z-10 flex gap-4 items-start">
                                    <div class="w-6 h-6 rounded-full border-4 border-white flex items-center justify-center shrink-0 shadow-sm mt-0.5 -ml-1"
                                         :class="drawerData.daily.status === 'terlambat' ? 'bg-amber-100 text-amber-600' : 'bg-green-100 text-green-600'">
                                        <i class="fa-solid fa-arrow-right-to-bracket text-[10px]"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start">
                                            <h4 class="text-sm font-bold text-gray-900">Check-in (Masuk)</h4>
                                            <span class="text-xs font-bold" :class="drawerData.daily.status === 'terlambat' ? 'text-amber-600' : 'text-green-600'" x-text="drawerData.daily.waktu_masuk"></span>
                                        </div>
                                        
                                        <template x-if="drawerData.daily.status === 'terlambat'">
                                            <p class="text-[10px] text-red-500 font-semibold mb-2 mt-0.5">Melewati batas waktu</p>
                                        </template>

                                        <!-- Device/Location/Foto Info -->
                                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-3 flex flex-col gap-2 mt-2">
                                            <div class="flex items-start gap-2">
                                                <i class="fa-solid fa-location-dot text-green-600 mt-0.5 text-xs"></i>
                                                <div>
                                                    <p class="text-[10px] font-bold text-gray-700">Metode Presensi</p>
                                                    <p class="text-[10px] text-gray-500 uppercase" x-text="drawerData.daily.metode_masuk || '-'"></p>
                                                </div>
                                            </div>
                                            <template x-if="drawerData.daily.foto_masuk">
                                                <div class="flex items-start gap-2">
                                                    <i class="fa-solid fa-camera text-blue-600 mt-0.5 text-xs"></i>
                                                    <div>
                                                        <p class="text-[10px] font-bold text-gray-700">Bukti Foto</p>
                                                        <img :src="drawerData.daily.foto_masuk" class="w-16 h-16 rounded-lg object-cover mt-1 border border-gray-200 shadow-sm">
                                                    </div>
                                                </div>
                                            </template>
                                            <template x-if="drawerData.daily.metode_masuk === 'manual' && drawerData.daily.catatan">
                                                <div class="flex items-start gap-2 border-t border-gray-200 pt-2 mt-1">
                                                    <i class="fa-solid fa-pen text-amber-500 mt-0.5 text-xs"></i>
                                                    <div>
                                                        <p class="text-[10px] font-bold text-gray-700">Catatan Manual</p>
                                                        <p class="text-[10px] text-gray-500" x-text="drawerData.daily.catatan"></p>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Monthly Stats -->
                    <div>
                        <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 border-b border-gray-100 pb-2">Ringkasan Bulan Ini</h5>
                        <div class="grid grid-cols-3 gap-2">
                            <div class="bg-green-50 p-2 rounded-lg text-center border border-green-100">
                                <p class="text-lg font-extrabold text-green-700 leading-none" x-text="drawerData.monthly.hadir"></p>
                                <p class="text-[9px] font-semibold text-green-800 uppercase mt-1">Hadir Tepat</p>
                            </div>
                            <div class="bg-amber-50 p-2 rounded-lg text-center border border-amber-100">
                                <p class="text-lg font-extrabold text-amber-700 leading-none" x-text="drawerData.monthly.terlambat"></p>
                                <p class="text-[9px] font-semibold text-amber-800 uppercase mt-1">Terlambat</p>
                            </div>
                            <div class="bg-red-50 p-2 rounded-lg text-center border border-red-100">
                                <p class="text-lg font-extrabold text-red-700 leading-none" x-text="drawerData.monthly.alphaIzin"></p>
                                <p class="text-[9px] font-semibold text-red-800 uppercase mt-1">Alpha/Izin</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 border-t border-gray-100 bg-gray-50 shrink-0 flex gap-3">
                    <button @click="openKoreksi(drawerData.pegawai.id)"
                        class="w-full bg-white border border-gray-200 text-gray-700 px-4 py-2.5 rounded-xl font-bold text-sm hover:bg-gray-100 flex items-center justify-center gap-2 transition-colors shadow-sm cursor-pointer"><i
                            class="fa-solid fa-pen text-xs"></i> Koreksi Manual</button>
                </div>
            </div>
        </template>
    </div>
</div>
