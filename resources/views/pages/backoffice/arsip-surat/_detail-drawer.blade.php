{{-- Detail & Document Viewer Drawer --}}
<div x-show="detailDrawerOpen" class="fixed inset-0 z-[100] flex justify-end" x-cloak>
    {{-- Backdrop --}}
    <div x-show="detailDrawerOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/70 backdrop-blur-sm"
        @click="closeDrawer()"></div>

    {{-- Drawer Panel --}}
    <div x-show="detailDrawerOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="relative bg-white w-full max-w-6xl h-full shadow-2xl flex flex-col border-l border-gray-200 overflow-hidden">

        {{-- Drawer Header --}}
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50 shrink-0 z-20 shadow-sm">
            <div class="flex items-center gap-4">
                {{-- Loading --}}
                <template x-if="isLoadingDetail">
                    <span class="text-sm text-gray-500"><i class="fa-solid fa-spinner fa-spin mr-2"></i> Memuat...</span>
                </template>

                {{-- Loaded --}}
                <template x-if="!isLoadingDetail && detailData">
                    <div class="flex items-center gap-4">
                        {{-- Status Badge --}}
                        <span class="px-3 py-1 rounded font-bold text-xs uppercase tracking-wider border"
                              :class="detailData.status === 'selesai'
                                  ? 'bg-green-100 text-green-700 border-green-200'
                                  : 'bg-red-100 text-red-700 border-red-200'">
                            <i class="fa-solid mr-1" :class="detailData.status === 'selesai' ? 'fa-check' : 'fa-xmark'"></i>
                            <span x-text="detailData.status === 'selesai' ? 'Arsip Selesai' : 'Ditolak / Batal'"></span>
                        </span>
                        <div>
                            <h3 class="font-bold text-lg text-gray-900 leading-tight">Detail Arsip Surat</h3>
                            <p class="text-xs font-mono text-gray-500 mt-0.5" x-text="'No: ' + detailData.nomor_tiket"></p>
                        </div>
                    </div>
                </template>
            </div>

            <div class="flex items-center gap-2">
                <template x-if="detailData && detailData.status === 'selesai'">
                    <div class="flex items-center gap-2">
                        <button class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg text-sm font-bold shadow-sm transition-colors flex items-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-download"></i> Download
                        </button>
                        <button class="bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-sm transition-colors flex items-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-print"></i> Print Surat
                        </button>
                        <div class="w-px h-6 bg-gray-300 mx-2"></div>
                    </div>
                </template>
                <button @click="closeDrawer()"
                    class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 transition-colors cursor-pointer">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
        </div>

        {{-- Drawer Body (2 Columns) --}}
        <div class="flex-1 flex flex-col lg:flex-row overflow-hidden bg-gray-100">

            {{-- LEFT: DOCUMENT VIEWER --}}
            <div class="w-full lg:w-3/5 bg-gray-500 relative flex flex-col overflow-hidden border-r border-gray-300 shadow-inner">
                {{-- PDF Toolbar --}}
                <div class="bg-gray-800 text-white p-2 flex justify-between items-center shadow-md z-10 text-sm">
                    <div class="flex items-center gap-4 px-2">
                        <span class="font-semibold text-gray-300 text-xs" x-text="detailData ? (detailData.jenis_short + '_' + (detailData.penduduk?.nama || 'Dokumen').replace(/\s/g, '_') + '.pdf') : 'Memuat...'"></span>
                        <span class="text-gray-400 text-xs">1/1</span>
                    </div>
                    <div class="flex items-center gap-1 bg-gray-900 rounded p-1">
                        <button @click="pdfZoom = Math.max(50, pdfZoom - 10)"
                            class="w-8 h-7 flex items-center justify-center hover:bg-gray-700 rounded transition-colors text-gray-300 cursor-pointer"
                            title="Zoom Out"><i class="fa-solid fa-minus"></i></button>
                        <span class="w-12 text-center text-xs font-mono select-none" x-text="pdfZoom + '%'"></span>
                        <button @click="pdfZoom = Math.min(200, pdfZoom + 10)"
                            class="w-8 h-7 flex items-center justify-center hover:bg-gray-700 rounded transition-colors text-gray-300 cursor-pointer"
                            title="Zoom In"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>

                {{-- Paper Container --}}
                <div class="flex-1 overflow-auto p-4 sm:p-8 flex justify-center custom-scrollbar">
                    {{-- Loading --}}
                    <div x-show="isLoadingDetail" class="flex items-center justify-center flex-1" x-cloak>
                        <i class="fa-solid fa-spinner fa-spin text-4xl text-gray-300"></i>
                    </div>

                    {{-- A4 Paper --}}
                    <div x-show="!isLoadingDetail && detailData" class="transition-transform duration-200" style="transform-origin: top center;"
                         :style="'transform: scale(' + pdfZoom/100 + ');'" x-cloak>
                        <div class="pdf-paper bg-white w-[794px] min-h-[1123px] mx-auto p-12 font-serif text-black relative">
                            {{-- KOP SURAT --}}
                            <div class="flex items-start gap-4 border-b-4 border-double border-black pb-4 mb-6">
                                <div class="w-20 h-24 shrink-0 flex items-center justify-center">
                                    <img :src="detailData?.village?.logo_url || ''" class="max-h-full opacity-80" alt="Logo">
                                </div>
                                <div class="flex-1 text-center pr-10">
                                    <h2 class="text-lg font-bold uppercase tracking-wide leading-tight" x-text="'Pemerintah Kabupaten ' + (detailData?.village?.kabupaten || '')"></h2>
                                    <h2 class="text-lg font-bold uppercase tracking-wide leading-tight" x-text="'Kecamatan ' + (detailData?.village?.kecamatan || '')"></h2>
                                    <h1 class="text-2xl font-extrabold uppercase tracking-wider leading-tight mt-1" x-text="'Desa ' + (detailData?.village?.nama_desa || '')"></h1>
                                    <p class="text-sm mt-2" x-text="(detailData?.village?.alamat || '') + ', Kode Pos ' + (detailData?.village?.kode_pos || '')"></p>
                                </div>
                            </div>

                            {{-- JUDUL --}}
                            <div class="text-center mb-8">
                                <h3 class="text-xl font-bold uppercase underline" x-text="detailData?.jenis_label || ''"></h3>
                                <p class="text-sm mt-1" x-text="'Nomor : ' + (detailData?.nomor_tiket || '')"></p>
                            </div>

                            {{-- ISI --}}
                            <div class="text-justify leading-relaxed space-y-4 text-[15px]">
                                <p x-text="'Yang bertanda tangan di bawah ini ' + (detailData?.village?.jabatan_kades || 'Kepala Desa') + ' ' + (detailData?.village?.nama_desa || '') + ', Kecamatan ' + (detailData?.village?.kecamatan || '') + ', Kabupaten ' + (detailData?.village?.kabupaten || '') + ', menerangkan dengan sebenarnya bahwa:'"></p>

                                <table class="w-full ml-4 my-4" x-show="detailData?.penduduk">
                                    <tr>
                                        <td class="w-48 py-1.5">Nama Lengkap</td>
                                        <td class="w-4">:</td>
                                        <td class="font-bold" x-text="detailData?.penduduk?.nama || '-'"></td>
                                    </tr>
                                    <tr>
                                        <td class="py-1.5">NIK</td>
                                        <td>:</td>
                                        <td class="font-mono" x-text="detailData?.penduduk?.nik || '-'"></td>
                                    </tr>
                                    <tr>
                                        <td class="py-1.5">Tempat, Tanggal Lahir</td>
                                        <td>:</td>
                                        <td x-text="detailData?.penduduk?.ttl || '-'"></td>
                                    </tr>
                                    <tr>
                                        <td class="py-1.5">Pekerjaan</td>
                                        <td>:</td>
                                        <td x-text="detailData?.penduduk?.pekerjaan || '-'"></td>
                                    </tr>
                                    <tr>
                                        <td class="py-1.5 align-top">Alamat Lengkap</td>
                                        <td class="align-top">:</td>
                                        <td x-text="detailData?.penduduk?.alamat || '-'"></td>
                                    </tr>
                                    <template x-if="detailData?.nama_usaha">
                                        <tr>
                                            <td class="py-1.5">Nama Usaha</td>
                                            <td>:</td>
                                            <td class="font-bold" x-text="detailData?.nama_usaha"></td>
                                        </tr>
                                    </template>
                                </table>

                                <p>Orang tersebut di atas adalah benar-benar warga yang berdomisili di wilayah kami.</p>

                                <template x-if="detailData?.keperluan">
                                    <p>Surat keterangan ini dibuat untuk keperluan: <b x-text="detailData.keperluan"></b>.</p>
                                </template>

                                <p class="mt-4">Demikian surat keterangan ini dibuat dengan sebenarnya agar dapat dipergunakan sebagaimana mestinya.</p>
                            </div>

                            {{-- Rejection banner --}}
                            <template x-if="detailData?.status === 'ditolak'">
                                <div class="absolute inset-0 bg-red-600/5 flex items-center justify-center pointer-events-none">
                                    <div class="border-8 border-red-400/30 text-red-500/30 font-extrabold text-6xl uppercase tracking-widest transform -rotate-45">
                                        DIBATALKAN
                                    </div>
                                </div>
                            </template>

                            {{-- TTE & QR --}}
                            <div class="mt-16 flex justify-end" x-show="detailData?.status === 'selesai'">
                                <div class="w-64 text-center">
                                    <p class="mb-1" x-text="(detailData?.village?.nama_desa || '') + ', ' + (detailData?.tanggal_selesai || '')"></p>
                                    <p class="font-bold mb-4" x-text="(detailData?.village?.jabatan_kades || 'Kepala Desa') + ' ' + (detailData?.village?.nama_desa || '')"></p>
                                    <div class="w-24 h-24 mx-auto border-4 border-gray-800 p-1 flex flex-col items-center justify-center opacity-80 mb-2">
                                        <i class="fa-solid fa-qrcode text-[3.5rem] text-gray-800"></i>
                                        <span class="text-[6px] font-bold mt-1 tracking-wider uppercase">Telah di-TTE</span>
                                    </div>
                                    <p class="font-bold underline uppercase mt-2" x-text="detailData?.village?.nama_kades || '...'"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT: METADATA & TIMELINE --}}
            <div class="w-full lg:w-2/5 bg-white flex flex-col overflow-y-auto custom-scrollbar border-l border-gray-200">
                <div class="p-6 space-y-8">

                    {{-- Loading --}}
                    <div x-show="isLoadingDetail" class="flex flex-col items-center justify-center py-12" x-cloak>
                        <i class="fa-solid fa-spinner fa-spin text-3xl text-gray-300 mb-3"></i>
                        <p class="text-sm text-gray-500">Memuat detail arsip...</p>
                    </div>

                    {{-- Rejection Reason --}}
                    <template x-if="!isLoadingDetail && detailData?.alasan_tolak">
                        <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                            <h5 class="text-xs font-bold text-red-700 uppercase tracking-wider mb-1"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Alasan Penolakan</h5>
                            <p class="text-sm text-red-800" x-text="detailData.alasan_tolak"></p>
                        </div>
                    </template>

                    {{-- Info Detail --}}
                    <div x-show="!isLoadingDetail && detailData" x-cloak>
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Informasi Metadata Surat</h4>
                        <div class="space-y-4">
                            <div>
                                <p class="text-[10px] font-semibold text-gray-500 uppercase">Jenis Layanan</p>
                                <p class="text-sm font-bold text-gray-900 mt-0.5" x-text="detailData?.jenis_label || '-'"></p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-[10px] font-semibold text-gray-500 uppercase">Tanggal Dibuat</p>
                                    <p class="text-sm font-semibold text-gray-800 mt-0.5" x-text="detailData?.tanggal_pengajuan || '-'"></p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-semibold text-gray-500 uppercase">Tanggal Selesai</p>
                                    <p class="text-sm font-semibold text-gray-800 mt-0.5" x-text="detailData?.tanggal_selesai || '-'"></p>
                                </div>
                            </div>
                            <div>
                                <p class="text-[10px] font-semibold text-gray-500 uppercase">Operator</p>
                                <p class="text-sm font-semibold text-gray-800 mt-0.5" x-text="detailData?.operator_name || '-'"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Pemohon --}}
                    <div x-show="!isLoadingDetail && detailData?.penduduk" x-cloak>
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Pemohon</h4>
                        <div class="bg-gray-50 border border-gray-100 rounded-xl p-4 flex items-center gap-4">
                            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center border border-gray-200 text-gray-400 shrink-0">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-gray-900 leading-tight mb-1" x-text="detailData?.penduduk?.nama || '-'"></p>
                                <p class="text-[10px] font-mono text-gray-500" x-text="'NIK: ' + (detailData?.penduduk?.nik || '-')"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Timeline --}}
                    <div x-show="!isLoadingDetail && detailData?.timeline?.length > 0" x-cloak>
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Riwayat & Tracking Dokumen</h4>
                        <div class="relative ml-2 space-y-5 mt-2">
                            <template x-for="(step, idx) in (detailData?.timeline || [])" :key="idx">
                                <div class="relative z-10 flex gap-4 items-start">
                                    <div class="timeline-track"></div>
                                    <div class="w-6 h-6 rounded-full border-4 border-white flex items-center justify-center shrink-0 shadow-sm mt-0.5 -ml-1"
                                         :class="idx === 0
                                             ? (detailData.status === 'selesai' ? 'bg-green-500 text-white' : 'bg-red-500 text-white')
                                             : 'bg-gray-200 text-gray-500'">
                                        <i class="text-[10px]"
                                           :class="step.action === 'selesai' ? 'fa-solid fa-check'
                                                  : step.action === 'ditolak' ? 'fa-solid fa-xmark'
                                                  : step.action === 'menunggu_tte' ? 'fa-solid fa-signature'
                                                  : step.action === 'verifikasi' ? 'fa-solid fa-user-check'
                                                  : 'fa-solid fa-file-import'"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h5 class="text-sm font-bold leading-none mb-1" :class="idx === 0 ? 'text-gray-900' : 'text-gray-600'" x-text="step.description"></h5>
                                        <p class="text-[10px] text-gray-500 mb-1" x-text="step.at"></p>
                                        <div x-show="step.by"
                                             class="bg-gray-50 border border-gray-200 p-2 rounded-lg text-[10px] text-gray-600 font-medium">
                                            Oleh: <span class="font-bold" x-text="step.by"></span>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
