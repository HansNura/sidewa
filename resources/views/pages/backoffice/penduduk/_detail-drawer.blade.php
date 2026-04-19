{{-- Detail Penduduk Slide-over Drawer --}}
<div x-show="detailDrawerOpen" class="fixed inset-0 z-[100] flex justify-end" x-cloak>
    <div x-show="detailDrawerOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm"
         @click="detailDrawerOpen = false"></div>

    <div x-show="detailDrawerOpen"
         x-transition:enter="transition ease-transform duration-300"
         x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-transform duration-300"
         x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
         class="relative bg-white w-full max-w-lg h-full shadow-2xl flex flex-col border-l border-gray-200">

        {{-- Header --}}
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-start bg-gray-50/50 shrink-0">
            <div>
                <h3 class="font-extrabold text-lg text-gray-900">Detail Penduduk</h3>
                <p class="text-xs text-gray-500 font-mono mt-0.5" x-text="'NIK: ' + (detail ? detail.nik : '')"></p>
            </div>
            <button @click="detailDrawerOpen = false"
                class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 -mr-2 cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        {{-- Body --}}
        <div class="p-6 overflow-y-auto custom-scrollbar flex-1 space-y-6" x-show="detail">

            {{-- Profile Summary --}}
            <div class="flex gap-4 items-center">
                <div class="w-16 h-16 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-400 text-3xl shrink-0">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div>
                    <h4 class="text-xl font-extrabold text-gray-900 leading-none mb-1" x-text="detail?.nama"></h4>
                    <div class="flex flex-wrap gap-2 mt-2">
                        <template x-if="detail">
                            <span :class="detail.status_color.bg + ' ' + detail.status_color.text"
                                  class="text-[10px] font-bold px-2 py-0.5 rounded border"
                                  :class="detail.status_color.border"
                                  x-text="detail.status_color.label"></span>
                        </template>
                        <template x-if="detail">
                            <span :class="detail.jenis_kelamin === 'L' ? 'bg-blue-100 text-blue-700 border-blue-200' : 'bg-pink-100 text-pink-700 border-pink-200'"
                                  class="text-[10px] font-bold px-2 py-0.5 rounded border"
                                  x-text="detail.jenis_kelamin_label"></span>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Biodata Pribadi --}}
            <div>
                <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 pb-2 border-b border-gray-100">Biodata Pribadi</h5>
                <div class="grid grid-cols-2 gap-y-4 gap-x-2 text-sm">
                    <div>
                        <p class="text-[10px] text-gray-500">Tempat, Tanggal Lahir</p>
                        <p class="font-semibold text-gray-800" x-text="(detail?.tempat_lahir || '-') + ', ' + (detail?.tanggal_lahir || '-')"></p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500">Umur</p>
                        <p class="font-semibold text-gray-800" x-text="(detail?.umur || '-') + ' Tahun'"></p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500">Agama</p>
                        <p class="font-semibold text-gray-800" x-text="detail?.agama || '-'"></p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500">Golongan Darah</p>
                        <p class="font-semibold text-gray-800" x-text="detail?.golongan_darah || '-'"></p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500">Pendidikan Terakhir</p>
                        <p class="font-semibold text-gray-800" x-text="detail?.pendidikan || '-'"></p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500">Pekerjaan</p>
                        <p class="font-semibold text-gray-800" x-text="detail?.pekerjaan || '-'"></p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500">Status Perkawinan</p>
                        <p class="font-semibold text-gray-800" x-text="detail?.status_perkawinan || '-'"></p>
                    </div>
                </div>
            </div>

            {{-- Informasi Keluarga --}}
            <div>
                <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 pb-2 border-b border-gray-100">Informasi Keluarga</h5>
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 mb-3">
                    <p class="text-[10px] text-gray-500 mb-1">Nomor Kartu Keluarga (KK)</p>
                    <span class="text-sm font-mono font-bold text-green-600" x-text="detail?.no_kk"></span>
                </div>
                <div class="grid grid-cols-2 gap-y-4 gap-x-2 text-sm">
                    <div>
                        <p class="text-[10px] text-gray-500">Status dlm Keluarga</p>
                        <p class="font-semibold text-gray-800" x-text="detail?.status_hubungan || '-'"></p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500">Nama Ayah</p>
                        <p class="font-semibold text-gray-800" x-text="detail?.nama_ayah || '-'"></p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-[10px] text-gray-500">Nama Ibu</p>
                        <p class="font-semibold text-gray-800" x-text="detail?.nama_ibu || '-'"></p>
                    </div>
                </div>
            </div>

            {{-- Alamat --}}
            <div>
                <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 pb-2 border-b border-gray-100">Alamat & Wilayah</h5>
                <p class="text-sm font-semibold text-gray-800 leading-relaxed" x-text="detail?.alamat_lengkap || '-'"></p>
                <p class="text-xs text-gray-500 mt-1" x-text="detail?.alamat || ''"></p>
            </div>
        </div>

        {{-- Footer --}}
        <div class="p-6 border-t border-gray-100 bg-gray-50 shrink-0">
            <button @click="detailDrawerOpen = false"
                class="w-full bg-white border border-gray-200 text-gray-700 px-4 py-2.5 rounded-xl font-bold text-sm hover:bg-gray-100 cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>
