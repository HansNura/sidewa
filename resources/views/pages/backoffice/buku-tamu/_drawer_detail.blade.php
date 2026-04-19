<!-- DRAWER: SPECIFIC GUEST DETAIL -->
<div x-show="detailDrawerOpen" class="fixed inset-0 z-[110] flex justify-end" x-cloak>
    <div x-show="detailDrawerOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
        @click="detailDrawerOpen = false"></div>

    <div x-show="detailDrawerOpen" x-transition:enter="transition ease-transform duration-300"
        x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-transform duration-300" x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="relative bg-white w-full max-w-lg h-full shadow-2xl flex flex-col border-l border-gray-200">

        <!-- Loading -->
        <div x-show="loading" class="absolute inset-0 z-[120] bg-white/80 flex items-center justify-center backdrop-blur-sm">
            <i class="fa-solid fa-spinner fa-spin text-4xl text-green-600"></i>
        </div>

        <template x-if="detailData">
            <div class="flex flex-col h-full bg-slate-50">
                <!-- Header -->
                <div class="px-6 py-6 border-b border-gray-100 bg-white flex justify-between items-start shrink-0 relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 opacity-5">
                        <i class="fa-solid fa-fingerprint text-9xl"></i>
                    </div>
                    <div class="relative z-10 flex gap-4 w-full">
                        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 shrink-0 text-3xl border-4 border-white shadow-sm">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <div class="pt-1 flex-1">
                            <h3 class="font-extrabold text-2xl text-gray-900" x-text="detailData.nama_tamu"></h3>
                            <p class="text-sm font-semibold text-gray-500 mt-1 flex items-center gap-2">
                                <i class="fa-solid fa-building text-gray-400"></i>
                                <span x-text="detailData.instansi"></span>
                            </p>
                        </div>
                        <button @click="detailDrawerOpen = false" class="text-gray-400 hover:text-red-500 bg-gray-50 w-8 h-8 flex items-center justify-center rounded-full hover:bg-red-50 transition-colors pointer-events-auto cursor-pointer self-start -mr-2">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto px-6 py-8 space-y-6">
                    
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100/50 space-y-5">
                        <div class="flex justify-between items-center pb-4 border-b border-gray-50">
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kategori Kunjungan</p>
                                <p class="text-sm font-bold text-gray-900 mt-1" x-text="detailData.tujuan_kategori"></p>
                            </div>
                            <span class="px-3 py-1 text-[10px] uppercase font-bold rounded-full border"
                                  :class="`bg-${detailData.status_color}-50 text-${detailData.status_color}-700 border-${detailData.status_color}-200`" 
                                  x-text="detailData.status">
                            </span>
                        </div>

                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Penjelasan Keperluan</p>
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                                <p class="text-sm text-gray-700 leading-relaxed italic" x-text="`&quot;${detailData.keperluan}&quot;`"></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100/50">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Informasi Waktu Kunjungan</p>
                        
                        <div class="space-y-4">
                            <div class="flex items-start gap-4">
                                <div class="w-8 h-8 rounded-full bg-green-50 text-green-600 flex items-center justify-center shrink-0 border border-green-100">
                                    <i class="fa-solid fa-door-open text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-[11px] font-semibold text-gray-500">Waktu Kedatangan</p>
                                    <p class="text-sm font-bold text-gray-900" x-text="detailData.waktu_masuk"></p>
                                </div>
                            </div>
                            <div class="pl-4 py-1 flex flex-col items-center">
                                <div class="w-px h-6 bg-gray-200"></div>
                                <div class="text-[10px] text-gray-400 font-bold bg-white px-2 py-0.5 border border-gray-100 rounded-lg absolute -translate-y-1/2 ml-16 transform whitespace-nowrap z-10" x-text="`Durasi: ${detailData.durasi}`"></div>
                                <div class="w-px h-6 bg-gray-200"></div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="w-8 h-8 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center shrink-0 border border-gray-200">
                                    <i class="fa-solid fa-door-closed text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-[11px] font-semibold text-gray-500">Waktu Kepulangan</p>
                                    <p class="text-sm font-bold text-gray-900" x-text="detailData.waktu_keluar"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100/50 flex gap-3 text-sm">
                        <i class="fa-solid fa-circle-info text-blue-500 mt-0.5"></i>
                        <div>
                            <p class="text-blue-900 font-semibold text-xs">Informasi Tambahan</p>
                            <p class="text-[11px] text-blue-700 mt-1">Data kunjungan ini dimasukkan melalui metode <strong><span x-text="detailData.metode_input"></span></strong>.</p>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>
