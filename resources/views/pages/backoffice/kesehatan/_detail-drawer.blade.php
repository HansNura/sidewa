{{-- Detail Kesehatan Drawer --}}
<div x-show="detailDrawerOpen" class="fixed inset-0 z-[100] flex justify-end" x-cloak>
    <div x-show="detailDrawerOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
         @click="detailDrawerOpen = false"></div>

    <div x-show="detailDrawerOpen"
         x-transition:enter="transition ease-transform duration-300"
         x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-transform duration-300"
         x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
         class="relative bg-white w-full max-w-md h-full shadow-2xl flex flex-col border-l border-gray-200">

        {{-- Header --}}
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-start bg-gray-50/50 shrink-0">
            <div>
                <template x-if="detail?.is_stunting">
                    <span class="text-[10px] font-bold text-red-600 bg-red-100 px-2 py-0.5 rounded uppercase tracking-wider mb-1 inline-flex items-center gap-1">
                        <i class="fa-solid fa-triangle-exclamation"></i> Status: Stunting
                    </span>
                </template>
                <template x-if="!detail?.is_stunting">
                    <span class="text-[10px] font-bold text-green-600 bg-green-100 px-2 py-0.5 rounded uppercase tracking-wider mb-1 inline-flex items-center gap-1">
                        <i class="fa-solid fa-circle-check"></i> Status: Normal
                    </span>
                </template>
                <h3 class="font-extrabold text-xl text-gray-900 mt-1" x-text="detail?.nama ?? ''"></h3>
                <p class="text-xs font-mono text-gray-500" x-text="'NIK: ' + (detail?.nik ?? '')"></p>
            </div>
            <button @click="detailDrawerOpen = false"
                class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 -mr-2 cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        {{-- Body --}}
        <div class="p-6 overflow-y-auto custom-scrollbar flex-1 space-y-6" x-show="detail">

            {{-- Family Info --}}
            <div>
                <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 border-b border-gray-100 pb-2">Informasi Keluarga</h5>
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-[10px] text-gray-500 mb-0.5">Nama Orang Tua / Wali</p>
                        <p class="text-sm font-bold text-gray-900" x-text="detail?.nama_ortu ?? 'Tidak tercatat'"></p>
                        <template x-if="detail?.no_kk">
                            <p class="text-[10px] font-bold font-mono text-green-600 mt-1">
                                KK: <span x-text="detail.no_kk"></span>
                            </p>
                        </template>
                    </div>
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center border border-gray-200 text-gray-400">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
            </div>

            {{-- Measurement History (Timeline) --}}
            <div>
                <div class="flex justify-between items-center mb-3 border-b border-gray-100 pb-2">
                    <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Riwayat Posyandu</h5>
                    <button type="button" @click="addModalOpen = true"
                        class="text-[10px] font-bold text-green-600 bg-green-50 px-2 py-1 rounded hover:bg-green-100 cursor-pointer">
                        <i class="fa-solid fa-plus"></i> Input Baru
                    </button>
                </div>

                <div class="relative ml-2 space-y-4" style="position: relative;">
                    {{-- Timeline line --}}
                    <div class="absolute left-[11px] top-6 bottom-0 w-0.5 bg-gray-200 z-0"
                         x-show="detail?.riwayat?.length > 1"></div>

                    <template x-for="(r, idx) in (detail?.riwayat ?? [])" :key="r.id">
                        <div class="relative z-10 flex gap-4 items-start bg-white group">
                            {{-- Timeline dot --}}
                            <div class="w-6 h-6 rounded-full border-2 border-white flex items-center justify-center shrink-0 shadow-sm mt-0.5"
                                 :class="r.is_stunting ? (r.status_gizi === 'sangat_pendek' ? 'bg-red-100 text-red-500' : 'bg-amber-100 text-amber-500') : 'bg-green-100 text-green-500'">
                                <i class="fa-solid fa-ruler-vertical text-[10px]"></i>
                            </div>

                            {{-- Content card --}}
                            <div class="flex-1 p-3 rounded-xl border transition-colors"
                                 :class="idx === 0 && r.is_stunting ? 'bg-red-50/50 border-red-100' : 'bg-gray-50 border-gray-100 hover:border-gray-200'">
                                <div class="flex justify-between items-center mb-1">
                                    <p class="text-xs font-bold text-gray-900" x-text="'Bulan Ke-' + r.umur_bulan"></p>
                                    <p class="text-[10px] text-gray-500" x-text="r.tanggal_pengukuran"></p>
                                </div>
                                <div class="flex gap-4 mt-2 text-sm">
                                    <div>
                                        <span class="text-[10px] text-gray-500 block">Tinggi</span>
                                        <span class="font-bold text-gray-800" x-text="r.tinggi_badan + ' cm'"></span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] text-gray-500 block">Berat</span>
                                        <span class="font-bold text-gray-800" x-text="r.berat_badan + ' kg'"></span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] text-gray-500 block">Status Gizi</span>
                                        <span class="font-bold text-xs uppercase tracking-wide"
                                              :class="r.is_stunting ? (r.status_gizi === 'sangat_pendek' ? 'text-red-600' : 'text-amber-600') : 'text-green-600'"
                                              x-text="r.status_label"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <template x-if="!detail?.riwayat?.length">
                        <div class="text-center text-gray-400 text-xs py-4">
                            Belum ada riwayat pengukuran.
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="p-6 border-t border-gray-100 bg-gray-50 shrink-0">
            <button @click="detailDrawerOpen = false"
                class="w-full bg-white border border-gray-200 text-gray-700 px-4 py-2.5 rounded-xl font-bold text-sm hover:bg-gray-100 flex items-center justify-center gap-2 transition-colors cursor-pointer">
                <i class="fa-solid fa-xmark text-xs"></i> Tutup
            </button>
        </div>
    </div>
</div>
