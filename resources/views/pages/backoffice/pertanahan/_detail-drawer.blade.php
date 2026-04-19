{{-- Detail Land Drawer --}}
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
                <span x-show="detail?.kepemilikan === 'warga'" class="text-[10px] font-bold text-blue-700 bg-blue-100 px-2 py-0.5 rounded uppercase tracking-wider border border-blue-200 mb-1 inline-block">Milik Pribadi</span>
                <span x-show="detail?.kepemilikan === 'desa'" class="text-[10px] font-bold text-green-700 bg-green-100 px-2 py-0.5 rounded uppercase tracking-wider border border-green-200 mb-1 inline-block">Aset Desa</span>
                <span x-show="detail?.kepemilikan === 'fasum'" class="text-[10px] font-bold text-amber-700 bg-amber-100 px-2 py-0.5 rounded uppercase tracking-wider border border-amber-200 mb-1 inline-block">Fasilitas Umum</span>
                <h3 class="font-extrabold text-xl text-gray-900 mt-1" x-text="detail?.lokasi_blok"></h3>
                <p class="text-xs font-mono text-gray-500 mt-0.5" x-text="'ID: ' + (detail?.kode_lahan ?? '')"></p>
            </div>
            <button @click="detailDrawerOpen = false"
                class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 -mr-2 cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        {{-- Body --}}
        <div class="p-6 overflow-y-auto custom-scrollbar flex-1 space-y-6" x-show="detail">

            {{-- Mini Map --}}
            <template x-if="detail?.geojson">
                <div class="w-full h-40 bg-gray-100 rounded-xl border border-gray-200 overflow-hidden relative z-0">
                    <div id="miniMapDetail" class="absolute inset-0"></div>
                </div>
            </template>

            {{-- Physical Details --}}
            <div class="grid grid-cols-2 gap-4 border-b border-gray-100 pb-5">
                <div>
                    <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-1">Luas Tercatat</p>
                    <p class="text-lg font-extrabold text-gray-900" x-text="detail ? new Intl.NumberFormat('id-ID').format(detail.luas) + ' m²' : ''"></p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-1">Status Legalitas</p>
                    <p class="text-sm font-bold text-gray-900">
                        <i class="fa-solid fa-certificate text-amber-500 mr-1"></i>
                        <span x-text="detail?.legalitas_label"></span>
                    </p>
                    <template x-if="detail?.nomor_sertifikat">
                        <p class="text-[10px] text-gray-500 font-mono mt-0.5" x-text="'No. ' + detail.nomor_sertifikat"></p>
                    </template>
                </div>
            </div>

            {{-- Location --}}
            <div class="border-b border-gray-100 pb-5">
                <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Lokasi</h5>
                <p class="text-sm font-semibold text-gray-800" x-text="detail?.lokasi_blok"></p>
                <p class="text-xs text-gray-500 mt-0.5" x-text="(detail?.dusun ? 'Dusun ' + detail.dusun : '') + (detail?.rt ? ', RT ' + detail.rt : '') + (detail?.rw ? '/RW ' + detail.rw : '')"></p>
            </div>

            {{-- Owner Info (for private land) --}}
            <template x-if="detail?.kepemilikan === 'warga'">
                <div>
                    <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Informasi Pemilik</h5>
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 flex justify-between items-center gap-4">
                        <div>
                            <p class="text-sm font-bold text-gray-900 leading-tight" x-text="detail?.pemilik"></p>
                            <template x-if="detail?.penduduk_nik">
                                <p class="text-[10px] font-mono text-gray-500 mt-1" x-text="'NIK: ' + detail.penduduk_nik"></p>
                            </template>
                        </div>
                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center border border-gray-200 text-green-600 shrink-0">
                            <i class="fa-solid fa-user"></i>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Notes --}}
            <template x-if="detail?.catatan">
                <div class="bg-blue-50/50 border border-blue-100 rounded-xl p-3 flex gap-2 items-start">
                    <i class="fa-solid fa-circle-info text-blue-500 text-xs mt-0.5"></i>
                    <div>
                        <p class="text-[10px] font-bold text-blue-800 uppercase tracking-wider mb-0.5">Catatan</p>
                        <p class="text-xs text-gray-600 leading-relaxed" x-text="detail.catatan"></p>
                    </div>
                </div>
            </template>

            {{-- Metadata --}}
            <div class="text-[10px] text-gray-400 pt-2 border-t border-gray-50">
                <p>Data ditambahkan: <span x-text="detail?.created_at"></span></p>
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
