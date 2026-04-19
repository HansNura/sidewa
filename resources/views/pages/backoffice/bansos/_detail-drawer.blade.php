{{-- Detail Penerima Drawer --}}
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
                <div class="flex items-center gap-2 mb-1">
                    <template x-if="detail && !detail.is_duplikat">
                        <span class="text-[10px] font-bold text-green-700 bg-green-100 px-2 py-0.5 rounded uppercase tracking-wider border border-green-200">
                            Kelayakan: Valid
                        </span>
                    </template>
                    <template x-if="detail?.is_duplikat">
                        <span class="text-[10px] font-bold text-red-700 bg-red-100 px-2 py-0.5 rounded uppercase tracking-wider border border-red-200">
                            <i class="fa-solid fa-triangle-exclamation mr-1"></i> Duplikat
                        </span>
                    </template>
                    <template x-if="detail?.desil">
                        <span class="text-[10px] font-bold text-blue-700 bg-blue-100 px-2 py-0.5 rounded uppercase tracking-wider border border-blue-200"
                              x-text="'Desil ' + detail.desil"></span>
                    </template>
                </div>
                <h3 class="font-extrabold text-xl text-gray-900 mt-1" x-text="detail?.penduduk_nama"></h3>
                <p class="text-xs font-mono text-gray-500" x-text="'NIK: ' + (detail?.penduduk_nik ?? '')"></p>
            </div>
            <button @click="detailDrawerOpen = false"
                class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 -mr-2 cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        {{-- Body --}}
        <div class="p-6 overflow-y-auto custom-scrollbar flex-1 space-y-6" x-show="detail">

            {{-- KPM Info --}}
            <div>
                <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 border-b border-gray-100 pb-2">Informasi Penerima (KPM)</h5>
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-bold text-gray-900 mb-1"
                           x-text="'Dusun ' + (detail?.penduduk_dusun ?? '-') + ', RT ' + (detail?.penduduk_rt ?? '-') + '/RW ' + (detail?.penduduk_rw ?? '-')"></p>
                        <template x-if="detail?.penduduk_pekerjaan">
                            <p class="text-[10px] text-gray-600 mb-1" x-text="'Pekerjaan: ' + detail.penduduk_pekerjaan"></p>
                        </template>
                        <template x-if="detail?.penduduk_no_kk">
                            <p class="text-[10px] font-bold font-mono text-green-600 mt-1" x-text="'KK: ' + detail.penduduk_no_kk"></p>
                        </template>
                    </div>
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center border border-gray-200 text-gray-400 shrink-0">
                        <i class="fa-solid fa-house"></i>
                    </div>
                </div>
            </div>

            {{-- Aid History --}}
            <div>
                <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 border-b border-gray-100 pb-2">Riwayat Bantuan Diterima</h5>
                <div class="space-y-2">
                    <template x-for="r in (detail?.riwayat ?? [])" :key="r.id">
                        <div class="bg-white border border-gray-100 rounded-lg p-3 flex justify-between items-center shadow-sm">
                            <div>
                                <p class="text-xs font-bold text-gray-800" x-text="r.program_nama + (r.tahap ? ' (' + r.tahap + ')' : '')"></p>
                                <p class="text-[10px] text-gray-500" x-text="r.tanggal_distribusi ?? 'Menunggu'"></p>
                            </div>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded"
                                  :class="r.status === 'diterima' ? 'bg-green-50 text-green-600' : (r.status === 'tertahan' ? 'bg-red-50 text-red-600' : 'bg-amber-50 text-amber-600')"
                                  x-text="r.status_label"></span>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Current Distribution Tracking --}}
            <div>
                <div class="flex justify-between items-center mb-3 border-b border-gray-100 pb-2">
                    <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Tracking Distribusi Saat Ini</h5>
                    <span class="text-[10px] font-bold bg-green-50 text-green-700 px-2 py-1 rounded"
                          x-text="detail?.program_nama + (detail?.tahap ? ' (' + detail.tahap + ')' : '')"></span>
                </div>

                <div class="relative ml-3 mt-4 space-y-6">
                    {{-- Track: Diterima --}}
                    <div class="relative z-10 flex gap-4 items-start" x-show="detail?.status_distribusi === 'diterima'">
                        <div class="w-6 h-6 rounded-full bg-green-500 border-4 border-white flex items-center justify-center text-white shrink-0 shadow-sm mt-0.5 -ml-[3px]">
                            <i class="fa-solid fa-check text-[10px]"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-xs font-bold text-gray-900">Bantuan Diterima (Disalurkan)</h4>
                            <p class="text-[10px] text-gray-500" x-text="detail?.tanggal_distribusi ?? ''"></p>
                        </div>
                    </div>

                    {{-- Track: Siap Diambil --}}
                    <div class="relative z-10 flex gap-4 items-start">
                        <div class="w-6 h-6 rounded-full border-4 border-white flex items-center justify-center shrink-0 shadow-sm mt-0.5 -ml-[3px]"
                             :class="['diterima','siap_diambil'].includes(detail?.status_distribusi) ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-500'">
                            <template x-if="['diterima','siap_diambil'].includes(detail?.status_distribusi)">
                                <i class="fa-solid fa-check text-[10px]"></i>
                            </template>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-xs font-bold" :class="['diterima','siap_diambil'].includes(detail?.status_distribusi) ? 'text-gray-900' : 'text-gray-600'">Dana/Barang Siap Diambil</h4>
                        </div>
                    </div>

                    {{-- Track: Verifikasi --}}
                    <div class="relative z-10 flex gap-4 items-start">
                        <div class="w-6 h-6 rounded-full bg-green-500 text-white border-4 border-white flex items-center justify-center shrink-0 shadow-sm mt-0.5 -ml-[3px]"
                             x-show="detail?.status_distribusi !== 'tertahan'">
                            <i class="fa-solid fa-check text-[10px]"></i>
                        </div>
                        <div class="w-6 h-6 rounded-full bg-red-500 text-white border-4 border-white flex items-center justify-center shrink-0 shadow-sm mt-0.5 -ml-[3px]"
                             x-show="detail?.status_distribusi === 'tertahan'">
                            <i class="fa-solid fa-ban text-[10px]"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-xs font-bold" :class="detail?.status_distribusi === 'tertahan' ? 'text-red-700' : 'text-gray-900'">Verifikasi Kelayakan</h4>
                            <p class="text-[10px]" :class="detail?.status_distribusi === 'tertahan' ? 'text-red-500' : 'text-gray-400'"
                               x-text="detail?.status_distribusi === 'tertahan' ? 'Tertahan - Memerlukan audit' : 'Verifikasi oleh operator'"></p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Audit Note --}}
            <template x-if="detail?.catatan_audit">
                <div class="bg-blue-50/50 border border-blue-100 rounded-xl p-3 flex gap-2 items-start">
                    <i class="fa-solid fa-clock-rotate-left text-blue-500 text-xs mt-0.5"></i>
                    <div>
                        <p class="text-[10px] font-bold text-blue-800 uppercase tracking-wider mb-0.5">Catatan Audit</p>
                        <p class="text-xs text-gray-600 leading-relaxed" x-text="detail.catatan_audit"></p>
                    </div>
                </div>
            </template>
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
