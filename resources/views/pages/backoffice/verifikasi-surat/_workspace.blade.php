{{-- Full-Screen Verification Workspace --}}
<div x-show="verifyWorkspaceOpen" class="fixed inset-0 z-[100] bg-gray-100 flex flex-col overflow-hidden" x-cloak
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="translate-y-full"
     x-transition:enter-end="translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="translate-y-0"
     x-transition:leave-end="translate-y-full">

    {{-- Workspace Header --}}
    <header class="h-16 bg-white border-b border-gray-200 px-4 sm:px-6 flex items-center justify-between shrink-0 shadow-sm z-20">
        <div class="flex items-center gap-4">
            <button @click="closeWorkspace()"
                class="text-gray-500 hover:text-red-600 transition-colors flex items-center gap-2 font-semibold text-sm bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-200 cursor-pointer">
                <i class="fa-solid fa-xmark"></i> Batal / Tutup
            </button>
            <div class="h-6 w-px bg-gray-300 hidden sm:block"></div>
            <div class="hidden sm:block">
                <div class="flex items-center gap-2">
                    <h2 class="font-extrabold text-gray-900 text-lg leading-none">Verifikasi Berkas & TTE</h2>
                    <span class="bg-green-100 text-green-800 text-[10px] font-bold px-2 py-0.5 rounded border border-green-200"
                          x-text="currentSurat?.nomor_tiket || '...'"></span>
                </div>
                <p class="text-[10px] text-gray-500 mt-1">
                    Pemohon: <span x-text="currentSurat?.penduduk?.nama || '...'"></span>
                    (<span x-text="currentSurat?.jenis_short || '...'"></span>)
                </p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            {{-- Reject --}}
            <button @click="rejectModalOpen = true" x-show="currentSurat"
                class="bg-white border border-red-200 text-red-600 hover:bg-red-50 shadow-sm rounded-xl px-4 py-2 text-sm font-bold transition-all cursor-pointer">
                <i class="fa-solid fa-ban mr-1"></i> Tolak
            </button>
            {{-- Revisi (only for menunggu_tte) --}}
            <button @click="revisiModalOpen = true" x-show="currentSurat?.status === 'menunggu_tte'"
                class="bg-white border border-amber-300 text-amber-600 hover:bg-amber-50 shadow-sm rounded-xl px-4 py-2 text-sm font-bold transition-all cursor-pointer">
                <i class="fa-solid fa-pen-to-square mr-1"></i> Kembalikan (Revisi)
            </button>
            @if(auth()->user()->hasRole('administrator', 'kades'))
            {{-- Approve & TTE — Kades / Admin only --}}
            <button @click="pinModalOpen = true" x-show="currentSurat?.status === 'menunggu_tte'"
                :disabled="!isValidated"
                :class="!isValidated ? 'opacity-50 cursor-not-allowed bg-gray-400' : 'bg-green-700 hover:bg-green-800 shadow-md'"
                class="text-white rounded-xl px-5 py-2 text-sm font-bold transition-all flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-signature"></i> Setujui & TTE
            </button>
            @endif
            @if(auth()->user()->hasRole('administrator', 'operator'))
            {{-- Verify — Operator / Admin advances to TTE --}}
            <button @click="processVerify(currentSurat?.id)" x-show="currentSurat?.status === 'verifikasi'"
                :disabled="!isValidated"
                :class="!isValidated ? 'opacity-50 cursor-not-allowed bg-gray-400' : 'bg-blue-700 hover:bg-blue-800 shadow-md'"
                class="text-white rounded-xl px-5 py-2 text-sm font-bold transition-all flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-arrow-right"></i> Verifikasi & Lanjutkan ke TTE
            </button>
            @endif
        </div>
    </header>

    {{-- Workspace Layout (2 Columns) --}}
    <div class="flex-1 flex overflow-hidden">

        {{-- LEFT: DOCUMENT PREVIEW --}}
        <div class="flex-1 flex flex-col bg-gray-600 border-r border-gray-300 z-0 relative shadow-inner">
            {{-- PDF Toolbar --}}
            <div class="bg-gray-800 text-white p-2 flex justify-between items-center shadow-md z-10 text-sm">
                <div class="flex items-center gap-4 px-2">
                    <span class="font-semibold text-gray-300 text-xs">
                        <i class="fa-solid fa-file-pdf text-red-400 mr-1.5"></i>
                        <span x-text="currentSurat ? ('DRAFT_' + currentSurat.jenis_short + '_' + (currentSurat.penduduk?.nama || '').replace(/\\s/g, '_') + '.pdf') : 'Memuat...'"></span>
                    </span>
                </div>
                <div class="flex items-center gap-1 bg-gray-900 rounded p-1">
                    <button @click="pdfZoom = Math.max(50, pdfZoom - 10)"
                        class="w-8 h-7 flex items-center justify-center hover:bg-gray-700 rounded transition-colors text-gray-300 cursor-pointer">
                        <i class="fa-solid fa-minus"></i>
                    </button>
                    <span class="w-12 text-center text-xs font-mono select-none" x-text="pdfZoom + '%'"></span>
                    <button @click="pdfZoom = Math.min(200, pdfZoom + 10)"
                        class="w-8 h-7 flex items-center justify-center hover:bg-gray-700 rounded transition-colors text-gray-300 cursor-pointer">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
            </div>

            {{-- Paper Container --}}
            <div class="flex-1 overflow-auto p-4 sm:p-8 flex justify-center custom-scrollbar">
                {{-- Loading --}}
                <div x-show="isLoadingDetail" class="flex items-center justify-center flex-1" x-cloak>
                    <i class="fa-solid fa-spinner fa-spin text-4xl text-gray-300"></i>
                </div>

                {{-- A4 Paper --}}
                <div x-show="!isLoadingDetail && currentSurat" class="transition-transform duration-200" style="transform-origin: top center;"
                     :style="'transform: scale(' + pdfZoom/100 + ');'" x-cloak>
                    <div class="print-paper bg-white w-[794px] min-h-[1123px] mx-auto font-serif text-black relative"
                         style="padding: 3cm 2.5cm 3cm 3cm;">
                        {{-- KOP --}}
                        <div class="flex items-start gap-4 border-b-4 border-double border-black pb-4 mb-6">
                            <div class="w-20 h-24 shrink-0 flex items-center justify-center">
                                <img :src="currentSurat?.village?.logo_url || ''" class="max-h-full opacity-80" alt="Logo">
                            </div>
                            <div class="flex-1 text-center pr-10">
                                <h2 class="text-lg font-bold uppercase tracking-wide leading-tight" x-text="'Pemerintah Kabupaten ' + (currentSurat?.village?.kabupaten || '')"></h2>
                                <h2 class="text-lg font-bold uppercase tracking-wide leading-tight" x-text="'Kecamatan ' + (currentSurat?.village?.kecamatan || '')"></h2>
                                <h1 class="text-2xl font-extrabold uppercase tracking-wider leading-tight mt-1" x-text="'Desa ' + (currentSurat?.village?.nama_desa || '')"></h1>
                                <p class="text-sm mt-2" x-text="(currentSurat?.village?.alamat || '') + ', Kode Pos ' + (currentSurat?.village?.kode_pos || '')"></p>
                            </div>
                        </div>

                        {{-- Judul --}}
                        <div class="text-center mb-8">
                            <h3 class="text-xl font-bold uppercase underline" x-text="currentSurat?.jenis_label || ''"></h3>
                            <p class="text-sm mt-1">Nomor : 470 / <span class="bg-yellow-200 px-1 font-mono text-xs">___</span> / Desa / {{ date('Y') }}</p>
                        </div>

                        {{-- Body --}}
                        <div class="text-justify leading-relaxed space-y-4 text-[15px]">
                            <p x-text="'Yang bertanda tangan di bawah ini ' + (currentSurat?.village?.jabatan_kades || 'Kepala Desa') + ' ' + (currentSurat?.village?.nama_desa || '') + ', Kecamatan ' + (currentSurat?.village?.kecamatan || '') + ', Kabupaten ' + (currentSurat?.village?.kabupaten || '') + ', menerangkan dengan sebenarnya bahwa:'"></p>

                            <table class="w-full ml-4 my-4" x-show="currentSurat?.penduduk">
                                <tr>
                                    <td class="w-48 py-1.5">Nama Lengkap</td>
                                    <td class="w-4">:</td>
                                    <td class="font-bold" x-text="currentSurat?.penduduk?.nama || '-'"></td>
                                </tr>
                                <tr>
                                    <td class="py-1.5">NIK</td>
                                    <td>:</td>
                                    <td class="font-mono" x-text="currentSurat?.penduduk?.nik || '-'"></td>
                                </tr>
                                <tr>
                                    <td class="py-1.5">Tempat, Tanggal Lahir</td>
                                    <td>:</td>
                                    <td x-text="currentSurat?.penduduk?.ttl || '-'"></td>
                                </tr>
                                <tr>
                                    <td class="py-1.5">Pekerjaan</td>
                                    <td>:</td>
                                    <td x-text="currentSurat?.penduduk?.pekerjaan || '-'"></td>
                                </tr>
                                <tr>
                                    <td class="py-1.5 align-top">Alamat Lengkap</td>
                                    <td class="align-top">:</td>
                                    <td x-text="currentSurat?.penduduk?.alamat || '-'"></td>
                                </tr>
                                <template x-if="currentSurat?.nama_usaha">
                                    <tr>
                                        <td class="py-1.5">Nama Usaha</td>
                                        <td>:</td>
                                        <td class="font-bold" x-text="currentSurat.nama_usaha"></td>
                                    </tr>
                                </template>
                            </table>

                            <p>Orang tersebut di atas adalah benar-benar warga yang berdomisili di wilayah kami.</p>

                            <template x-if="currentSurat?.keperluan">
                                <p>Surat keterangan ini dibuat untuk keperluan: <b x-text="currentSurat.keperluan"></b>.</p>
                            </template>

                            <p class="mt-4">Demikian surat keterangan ini dibuat dengan sebenarnya agar dapat dipergunakan sebagaimana mestinya.</p>
                        </div>

                        {{-- TTD Block --}}
                        <div class="mt-16 flex justify-end">
                            <div class="w-64 text-center">
                                <p class="mb-1" x-text="(currentSurat?.village?.nama_desa || '') + ', ' + new Date().toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'})"></p>
                                <p class="font-bold mb-4" x-text="(currentSurat?.village?.jabatan_kades || 'Kepala Desa') + ' ' + (currentSurat?.village?.nama_desa || '')"></p>

                                {{-- TTE Area --}}
                                <div class="w-24 h-24 mx-auto border-2 border-dashed border-green-400 bg-green-50/50 flex flex-col items-center justify-center mb-2 text-green-500">
                                    <i class="fa-solid fa-signature text-2xl mb-1"></i>
                                    <span class="text-[7px] font-bold uppercase text-center">Menunggu<br>TTE Anda</span>
                                </div>

                                <p class="font-bold underline uppercase" x-text="currentSurat?.village?.nama_kades || '...'"></p>
                                <template x-if="currentSurat?.village?.nip_kades">
                                    <p class="text-[11px] mt-0.5" x-text="'NIP. ' + currentSurat.village.nip_kades"></p>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT: VALIDATION PANEL --}}
        <div class="w-[420px] bg-white flex flex-col shrink-0 z-10 shadow-[-4px_0_10px_-5px_rgba(0,0,0,0.05)]">
            <div class="flex border-b border-gray-200 bg-gray-50 p-2 gap-1 shrink-0">
                <div class="flex-1 py-2 text-xs rounded-lg bg-white shadow-sm text-green-600 font-bold text-center">
                    <i class="fa-solid fa-clipboard-check mr-1"></i> Checklist Validasi
                </div>
            </div>

            <div class="flex-1 overflow-y-auto custom-scrollbar p-6 space-y-6">
                {{-- Info Pemohon --}}
                <div>
                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Informasi Pemohon</h4>
                    <div class="bg-gray-50 border border-gray-100 rounded-xl p-4 flex gap-3">
                        <div class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-400 shrink-0">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <div>
                            <p class="font-bold text-gray-900 text-sm mb-0.5" x-text="currentSurat?.penduduk?.nama || '-'"></p>
                            <p class="text-[10px] text-gray-500 font-mono mb-1.5" x-text="'NIK: ' + (currentSurat?.penduduk?.nik || '-')"></p>
                        </div>
                    </div>
                </div>

                {{-- Checklist --}}
                <div>
                    <div class="flex justify-between items-end mb-3">
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Tugas Verifikator</h4>
                        <span class="text-[10px] font-bold"
                              :class="isValidated ? 'text-green-600' : 'text-amber-500'"
                              x-text="isValidated ? 'Lengkap ✓' : 'Wajib Diisi'"></span>
                    </div>
                    <div class="bg-blue-50 border border-blue-100 p-3 rounded-lg text-[10px] text-blue-800 leading-relaxed mb-4">
                        Mohon periksa kesesuaian dokumen draft (di sebelah kiri) dengan data kependudukan sebelum memberikan persetujuan.
                    </div>

                    <div class="space-y-3">
                        <label class="flex items-start gap-3 p-3 rounded-xl border transition-colors cursor-pointer"
                               :class="check1 ? 'bg-green-50 border-green-200' : 'bg-white border-gray-200 hover:bg-gray-50'">
                            <input type="checkbox" x-model="check1" class="check-valid mt-0.5">
                            <div>
                                <p class="text-sm font-semibold text-gray-800 leading-tight">Kesesuaian Identitas (NIK/Nama)</p>
                                <p class="text-[10px] text-gray-500 mt-0.5">Sesuai dengan database kependudukan.</p>
                            </div>
                        </label>
                        <label class="flex items-start gap-3 p-3 rounded-xl border transition-colors cursor-pointer"
                               :class="check2 ? 'bg-green-50 border-green-200' : 'bg-white border-gray-200 hover:bg-gray-50'">
                            <input type="checkbox" x-model="check2" class="check-valid mt-0.5">
                            <div>
                                <p class="text-sm font-semibold text-gray-800 leading-tight">Kebenaran Alamat & Domisili</p>
                                <p class="text-[10px] text-gray-500 mt-0.5">Benar warga desa yang bersangkutan.</p>
                            </div>
                        </label>
                        <label class="flex items-start gap-3 p-3 rounded-xl border transition-colors cursor-pointer"
                               :class="check3 ? 'bg-green-50 border-green-200' : 'bg-white border-gray-200 hover:bg-gray-50'">
                            <input type="checkbox" x-model="check3" class="check-valid mt-0.5">
                            <div>
                                <p class="text-sm font-semibold text-gray-800 leading-tight">Kesesuaian Keperluan</p>
                                <p class="text-[10px] text-gray-500 mt-0.5">Tujuan surat logis & relevan dengan jenis layanan.</p>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Catatan --}}
                <template x-if="currentSurat?.catatan">
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-3">
                        <h5 class="text-xs font-bold text-amber-700 uppercase tracking-wider mb-1"><i class="fa-solid fa-note-sticky mr-1"></i> Catatan Sebelumnya</h5>
                        <p class="text-sm text-amber-800" x-text="currentSurat.catatan"></p>
                    </div>
                </template>
            </div>

            {{-- Footer --}}
            <div class="p-6 border-t border-gray-100 bg-gray-50 shrink-0">
                <p class="text-[10px] text-gray-500 mb-3 text-center">Dengan menyetujui, Anda menempelkan TTE (QR Code) secara legal pada dokumen ini.</p>

                @if(auth()->user()->hasRole('administrator', 'kades'))
                {{-- TTE Button — Kades / Admin only --}}
                <button @click="pinModalOpen = true" x-show="currentSurat?.status === 'menunggu_tte'"
                    :disabled="!isValidated"
                    :class="!isValidated ? 'opacity-50 cursor-not-allowed bg-gray-400' : 'bg-green-700 hover:bg-green-800 shadow-md hover:-translate-y-0.5'"
                    class="w-full text-white rounded-xl px-5 py-3.5 text-sm font-extrabold transition-all flex items-center justify-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-signature"></i> Otorisasi & Bubuhkan TTE
                </button>
                @endif

                @if(auth()->user()->hasRole('administrator', 'operator'))
                {{-- Verify Button — Operator / Admin --}}
                <button @click="processVerify(currentSurat?.id)" x-show="currentSurat?.status === 'verifikasi'"
                    :disabled="!isValidated"
                    :class="!isValidated ? 'opacity-50 cursor-not-allowed bg-gray-400' : 'bg-blue-700 hover:bg-blue-800 shadow-md hover:-translate-y-0.5'"
                    class="w-full text-white rounded-xl px-5 py-3.5 text-sm font-extrabold transition-all flex items-center justify-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-arrow-right"></i> Verifikasi & Kirim ke Kepala Desa
                </button>
                @endif
            </div>
        </div>

    </div>
</div>
