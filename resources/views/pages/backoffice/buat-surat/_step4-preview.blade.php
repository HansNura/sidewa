{{-- Step 4: Pratinjau Dokumen Final (A4 Paper) --}}
<section x-show="step === 4" x-transition.opacity.duration.300ms x-cloak>
    <div class="mb-4 border-b border-gray-200 pb-2 flex flex-col sm:flex-row justify-between sm:items-center gap-2">
        <div>
            <h3 class="text-lg font-bold text-gray-900">Langkah 4: Pratinjau Dokumen Final</h3>
            <p class="text-xs text-amber-600 font-medium mt-1">
                <i class="fa-solid fa-lightbulb"></i>
                Tips: Anda dapat mengklik teks berwarna kuning untuk <b>edit inline</b> sebelum memproses surat.
            </p>
        </div>
        <div class="flex gap-2 shrink-0">
            <button onclick="window.print()"
                class="text-xs font-bold text-gray-600 bg-white hover:bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-300 transition-colors shadow-sm cursor-pointer">
                <i class="fa-solid fa-print mr-1"></i> Cetak Test
            </button>
        </div>
    </div>

    {{-- A4 Paper Container --}}
    <div class="bg-gray-200 p-4 sm:p-8 rounded-2xl overflow-x-auto flex justify-center shadow-inner">
        <div class="print-paper bg-white w-full max-w-[800px] min-h-[1000px] p-8 sm:p-12 font-serif text-black relative outline-none">

            {{-- KOP SURAT --}}
            <div class="flex items-start gap-4 border-b-4 border-double border-black pb-4 mb-6">
                <div class="w-20 h-24 shrink-0 flex items-center justify-center">
                    <img src="{{ $village->logoUrl() }}" class="max-h-full opacity-80" alt="Logo Desa">
                </div>
                <div class="flex-1 text-center pr-10">
                    <h2 class="text-lg font-bold uppercase tracking-wide leading-tight">
                        Pemerintah {{ $village->kabupaten ? 'Kabupaten ' . $village->kabupaten : '' }}
                    </h2>
                    <h2 class="text-lg font-bold uppercase tracking-wide leading-tight">
                        Kecamatan {{ $village->kecamatan ?? '' }}
                    </h2>
                    <h1 class="text-2xl font-extrabold uppercase tracking-wider leading-tight mt-1">
                        {{ $village->fullName() }}
                    </h1>
                    <p class="text-sm mt-2">{{ $village->alamat ?? '' }}, Kode Pos {{ $village->kode_pos ?? '' }}</p>
                    @if ($village->email || $village->website)
                        <p class="text-xs">
                            @if ($village->email) Email: {{ $village->email }} @endif
                            @if ($village->email && $village->website) | @endif
                            @if ($village->website) Web: {{ $village->website }} @endif
                        </p>
                    @endif
                </div>
            </div>

            {{-- JUDUL SURAT --}}
            <div class="text-center mb-8">
                <h3 class="text-xl font-bold uppercase underline" x-text="templateName">JUDUL SURAT</h3>
                <p class="text-sm mt-1">Nomor : 470 / <span class="text-gray-400">____</span> / Desa / {{ date('Y') }}</p>
            </div>

            {{-- ISI SURAT --}}
            <div class="text-justify leading-relaxed space-y-4">
                <p>Yang bertanda tangan di bawah ini {{ $village->jabatan_kades ?? 'Kepala Desa' }} {{ $village->nama_desa ?? '' }},
                   Kecamatan {{ $village->kecamatan ?? '' }}, Kabupaten {{ $village->kabupaten ?? '' }},
                   menerangkan dengan sebenarnya bahwa:</p>

                {{-- Dynamic Data Table (Inline Editable) --}}
                <table class="w-full ml-4 my-4">
                    <tr>
                        <td class="w-48 py-1">Nama Lengkap</td>
                        <td class="w-4">:</td>
                        <td class="font-bold bg-yellow-100/50 hover:bg-yellow-200 px-1 cursor-text transition-colors rounded"
                            contenteditable="true"
                            x-text="selectedResident?.nama || '____________________'"></td>
                    </tr>
                    <tr>
                        <td class="py-1">NIK</td>
                        <td>:</td>
                        <td class="font-mono bg-yellow-100/50 hover:bg-yellow-200 px-1 cursor-text transition-colors rounded"
                            contenteditable="true"
                            x-text="selectedResident?.nik || '____________________'"></td>
                    </tr>
                    <tr>
                        <td class="py-1">Tempat, Tanggal Lahir</td>
                        <td>:</td>
                        <td class="bg-yellow-100/50 hover:bg-yellow-200 px-1 cursor-text transition-colors rounded"
                            contenteditable="true"
                            x-text="selectedResident?.ttl || '____________________'"></td>
                    </tr>
                    <tr>
                        <td class="py-1">Pekerjaan</td>
                        <td>:</td>
                        <td class="bg-yellow-100/50 hover:bg-yellow-200 px-1 cursor-text transition-colors rounded"
                            contenteditable="true"
                            x-text="selectedResident?.pekerjaan || '____________________'"></td>
                    </tr>
                    <tr>
                        <td class="py-1 align-top">Alamat Lengkap</td>
                        <td class="align-top">:</td>
                        <td class="bg-yellow-100/50 hover:bg-yellow-200 px-1 cursor-text transition-colors rounded"
                            contenteditable="true"
                            x-text="selectedResident?.alamat || '____________________'"></td>
                    </tr>
                    {{-- Conditional: Nama Usaha row --}}
                    <tr x-show="templateKey === 'pengantar_usaha' && formData.namaUsaha" x-cloak>
                        <td class="py-1">Nama Usaha</td>
                        <td>:</td>
                        <td class="font-bold bg-yellow-100/50 hover:bg-yellow-200 px-1 cursor-text transition-colors rounded"
                            contenteditable="true"
                            x-text="formData.namaUsaha || '____________________'"></td>
                    </tr>
                </table>

                <p>Orang tersebut di atas adalah benar-benar warga yang berdomisili di {{ $village->fullName() }}.
                   Surat keterangan ini dibuat untuk keperluan:</p>

                <div class="text-center my-2">
                    <span class="font-bold border-b border-black inline-block min-w-[200px] bg-yellow-100/50 hover:bg-yellow-200 px-2 cursor-text transition-colors"
                        contenteditable="true"
                        x-text="formData.keperluan || '......................................................'"></span>
                </div>

                <p class="mt-4">Demikian surat keterangan ini dibuat dengan sebenarnya agar dapat dipergunakan
                   sebagaimana mestinya. Surat keterangan ini berlaku selama
                    <span class="font-bold bg-yellow-100/50 hover:bg-yellow-200 px-1 cursor-text transition-colors rounded"
                        contenteditable="true"
                        x-text="formData.berlakuHingga || '1 Bulan'"></span>.
                </p>
            </div>

            {{-- TANDA TANGAN --}}
            <div class="mt-16 flex justify-end">
                <div class="w-64 text-center">
                    <p class="mb-1">{{ $village->nama_desa ?? '' }}, <span x-text="new Date().toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'})"></span></p>
                    <p class="font-bold mb-16">{{ $village->jabatan_kades ?? 'Kepala Desa' }} {{ $village->nama_desa ?? '' }}</p>

                    {{-- QR/TTE Area --}}
                    <div class="absolute w-20 h-20 border-2 border-dashed border-gray-300 left-1/2 -ml-10 text-gray-200 flex flex-col items-center justify-center opacity-50 mt-[-60px]">
                        <i class="fa-solid fa-qrcode text-2xl"></i>
                        <span class="text-[8px] mt-1">Area TTE</span>
                    </div>

                    <p class="font-bold underline uppercase">{{ $village->nama_kades ?? '...........................' }}</p>
                    @if ($village->nip_kades)
                        <p class="text-sm mt-0.5">NIP. {{ $village->nip_kades }}</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</section>
