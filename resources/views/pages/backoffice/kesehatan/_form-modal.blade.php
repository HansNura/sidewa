{{-- Form Input Penimbangan Modal --}}
<div x-show="addModalOpen" class="fixed inset-0 z-[110] flex items-center justify-center p-4 sm:p-6 !m-0" x-cloak>
    <div x-show="addModalOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
        @click="addModalOpen = false"></div>

    <div x-show="addModalOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden flex flex-col max-h-[90vh]">

        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 shrink-0">
            <h3 class="font-extrabold text-lg text-gray-900">Input Data Kesehatan Anak</h3>
            <button @click="addModalOpen = false; selectedPenduduk = null"
                class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <div class="p-6 overflow-y-auto custom-scrollbar flex-1">
            <form id="pengukuranForm" method="POST" action="{{ route('admin.kesehatan.store') }}" class="space-y-6">
                @csrf

                {{-- Select Child Card --}}
                <div class="border rounded-xl p-4 transition-colors duration-300"
                    :class="{
                        'bg-green-50/50 border-green-100': !selectedPenduduk,
                        'bg-blue-50/50 border-blue-100': selectedPenduduk && selectedPenduduk
                            .jenis_kelamin === 'L',
                        'bg-pink-50/50 border-pink-100': selectedPenduduk && selectedPenduduk
                            .jenis_kelamin === 'P'
                    }">
                    <label class="text-xs font-bold block mb-3 transition-colors"
                        :class="{
                            'text-green-800': !selectedPenduduk,
                            'text-blue-800': selectedPenduduk && selectedPenduduk.jenis_kelamin === 'L',
                            'text-pink-800': selectedPenduduk && selectedPenduduk.jenis_kelamin === 'P'
                        }">Data
                        Balita <span class="text-red-500">*</span></label>

                    {{-- Before Selection (Empty State) --}}
                    <div x-show="!selectedPenduduk"
                        class="flex flex-col items-center justify-center py-6 border-2 border-dashed border-green-200 rounded-xl bg-white gap-3">
                        <div class="w-12 h-12 rounded-full bg-green-50 text-green-500 flex items-center justify-center">
                            <i class="fa-solid fa-users-viewfinder text-xl"></i>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-bold text-gray-700">Belum Ada Balita Dipilih</p>
                            <p class="text-[11px] text-gray-500 mt-0.5">Pilih dari daftar data balita untuk memulai
                                input.</p>
                        </div>
                        <button type="button" @click="openBrowseModal()"
                            class="mt-1 bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg shadow-sm text-sm font-bold transition-colors cursor-pointer flex items-center gap-2">
                            <i class="fa-solid fa-list"></i>
                            Pilih Data Balita
                        </button>
                    </div>

                    {{-- Selected Child Display --}}
                    <div x-show="selectedPenduduk"
                        class="bg-white border rounded-xl p-4 shadow-sm flex items-start justify-between gap-4 transition-colors duration-300"
                        :class="selectedPenduduk?.jenis_kelamin === 'L' ? 'border-blue-200' : 'border-pink-200'">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0 transition-colors"
                                :class="selectedPenduduk?.jenis_kelamin === 'L' ? 'bg-blue-100 text-blue-600' :
                                    'bg-pink-100 text-pink-600'">
                                <i class="fa-solid text-xl"
                                    :class="selectedPenduduk?.jenis_kelamin === 'L' ? 'fa-child-reaching' :
                                        'fa-child-dress'"></i>
                            </div>
                            <div>
                                <p class="text-sm font-extrabold text-gray-900" x-text="selectedPenduduk?.nama"></p>
                                <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1">
                                    <p class="text-[11px] font-mono text-gray-500 flex items-center gap-1">
                                        <i class="fa-regular fa-id-card"></i> <span
                                            x-text="selectedPenduduk?.nik"></span>
                                    </p>
                                    <p class="text-[11px] text-gray-500 flex items-center gap-1">
                                        <i class="fa-solid fa-cake-candles"></i> <span
                                            x-text="selectedPenduduk?.umur_bulan + ' bln'"></span>
                                    </p>
                                    <p class="text-[11px] text-gray-500 flex items-center gap-1">
                                        <i class="fa-solid"
                                            :class="selectedPenduduk?.jenis_kelamin === 'L' ? 'fa-mars text-blue-400' :
                                                'fa-venus text-pink-400'"></i>
                                        <span
                                            x-text="selectedPenduduk?.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'"></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <button type="button" @click="selectedPenduduk = null" title="Ganti Balita"
                            class="w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 hover:text-red-700 flex items-center justify-center cursor-pointer transition-colors shrink-0">
                            <i class="fa-solid fa-rotate-right text-sm"></i>
                        </button>
                    </div>

                    <input type="hidden" name="penduduk_id" :value="selectedPenduduk?.id ?? ''">
                    @error('penduduk_id')
                        <p class="text-red-500 text-[11px] font-bold mt-2 bg-red-50 p-2 rounded border border-red-100"><i
                                class="fa-solid fa-triangle-exclamation mr-1"></i> {{ $message }}</p>
                    @enderror
                </div>

                {{-- Measurement Data --}}
                <div class="bg-green-50/30 border border-green-100 rounded-xl p-4">
                    <h4 class="font-extrabold text-green-800 mb-4 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-scale-balanced text-green-500"></i> Hasil Pengukuran Posyandu
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-700">Tanggal Pengukuran <span
                                    class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_pengukuran" x-model="tanggalPengukuran"
                                @change="zScoreResult = null"
                                class="w-full bg-white border @error('tanggal_pengukuran') border-red-300 ring-1 ring-red-300 @else border-gray-200 @enderror rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none"
                                required>
                            @error('tanggal_pengukuran')
                                <p class="text-red-500 text-[10px]">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-700">Tinggi Badan (cm) <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="tinggi_badan" x-model="tinggiBadan" step="0.1"
                                placeholder="Contoh: 88.5"
                                class="w-full bg-white border @error('tinggi_badan') border-red-300 ring-1 ring-red-300 @else border-gray-200 @enderror rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none font-mono"
                                required>
                            @error('tinggi_badan')
                                <p class="text-red-500 text-[10px]">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-700">Berat Badan (kg) <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="berat_badan" step="0.1" value="{{ old('berat_badan') }}"
                                placeholder="Contoh: 11.2"
                                class="w-full bg-white border @error('berat_badan') border-red-300 ring-1 ring-red-300 @else border-gray-200 @enderror rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none font-mono"
                                required>
                            @error('berat_badan')
                                <p class="text-red-500 text-[10px]">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Calculate Button --}}
                        <div class="col-span-1 sm:col-span-2 pt-1">
                            <button type="button" @click="hitungStatusGizi()"
                                class="w-full bg-green-100 hover:bg-green-200 text-green-800 font-bold py-2.5 rounded-lg border border-green-300 transition-colors flex justify-center items-center gap-2 cursor-pointer shadow-sm">
                                <i class="fa-solid fa-calculator"></i> Kalkulasi Status Gizi Z-Score
                            </button>
                        </div>

                        <div class="grid grid-cols-2 gap-4 col-span-1 sm:col-span-2">
                            <div class="space-y-1">
                                <label class="text-xs font-bold text-gray-700">Nilai Z-Score</label>
                                <input type="text" :value="zScoreResult?.z || ''" disabled placeholder="-"
                                    class="w-full bg-gray-100 border border-gray-200 text-gray-600 rounded-lg px-3 py-2 text-sm font-bold font-mono focus:outline-none cursor-not-allowed text-center">
                                <p class="text-[10px] text-gray-500 mt-1"><i
                                        class="fa-solid fa-calculator text-gray-400 mr-1"></i> Hasil kalkulasi.</p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-bold text-gray-700">Status Gizi (TB/U) <span
                                        class="text-red-500">*</span></label>
                                <input type="hidden" name="status_gizi" :value="statusGizi">
                                <input type="text" disabled
                                    :value="statusGizi === 'normal' ? 'Normal' : statusGizi === 'pendek' ?
                                        'Pendek (Stunting)' : statusGizi === 'sangat_pendek' ? 'Sangat Pendek' :
                                        statusGizi === 'tinggi' ? 'Tinggi' : ''"
                                    class="w-full bg-gray-100 border @error('status_gizi') border-red-300 ring-1 ring-red-300 @else border-gray-200 @enderror text-gray-600 rounded-lg px-3 py-2 text-sm font-bold focus:outline-none cursor-not-allowed capitalize">
                                <p class="text-[10px] text-gray-500 mt-1"><i
                                        class="fa-solid fa-lock text-gray-400 mr-1"></i> Terkunci (Otomatis).</p>
                                @error('status_gizi')
                                    <p class="text-red-500 text-[10px]">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Z-Score Info Box --}}
                    <div x-show="zScoreResult" x-transition
                        class="mt-4 bg-green-100/50 border border-green-200 rounded-lg p-3 text-xs text-gray-700">
                        <p class="font-bold text-green-800 mb-1"><i class="fa-solid fa-circle-info mr-1"></i> Rincian
                            Perhitungan WHO (TB/U)</p>
                        <p>Umur: <span class="font-mono font-bold" x-text="zScoreResult?.umur"></span> bln. Standar
                            Median: <span class="font-mono font-bold" x-text="zScoreResult?.m"></span> cm (SD <span
                                class="font-mono font-bold" x-text="zScoreResult?.sd"></span>).</p>
                        <p class="mt-1">Nilai <span class="font-bold text-gray-900">Z-Score = <span
                                    x-text="zScoreResult?.z"></span></span>.
                            Maka balita dikategorikan <span class="font-bold uppercase"
                                x-text="zScoreResult?.status.replace('_', ' ')"></span>.</p>
                        <p class="text-[10px] text-gray-500 mt-2 italic">* Rumus: (Tinggi Badan - Median) / SD.</p>
                    </div>

                    {{-- Panduan Status Gizi (Always Visible) --}}
                    <div class="mt-4 border-t border-green-100/50 pt-3">
                        <p class="text-[10px] font-bold text-green-700 mb-2 uppercase tracking-wider"><i
                                class="fa-solid fa-book-medical mr-1"></i> Panduan Status Gizi (WHO TB/U)</p>
                        <div class="bg-white rounded-lg border border-green-100 overflow-hidden">
                            <table class="w-full text-[10px] text-left">
                                <thead class="bg-green-50 text-green-800">
                                    <tr>
                                        <th class="px-3 py-2 font-bold">Z-Score (TB/U)</th>
                                        <th class="px-3 py-2 font-bold">Status Gizi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-green-50 text-gray-600">
                                    <tr>
                                        <td class="px-3 py-1.5">&ge; -2 SD sampai &le; +3 SD</td>
                                        <td class="px-3 py-1.5 font-bold text-green-600">Normal</td>
                                    </tr>
                                    <tr>
                                        <td class="px-3 py-1.5">&lt; -2 SD sampai &ge; -3 SD</td>
                                        <td class="px-3 py-1.5 font-bold text-amber-500">Pendek (Stunting)</td>
                                    </tr>
                                    <tr>
                                        <td class="px-3 py-1.5">&lt; -3 SD</td>
                                        <td class="px-3 py-1.5 font-bold text-red-500">Sangat Pendek (Severe)</td>
                                    </tr>
                                    <tr>
                                        <td class="px-3 py-1.5">&gt; +3 SD</td>
                                        <td class="px-3 py-1.5 font-bold text-blue-500">Tinggi</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Additional Data --}}
                <div class="bg-gray-50 border border-gray-100 rounded-xl p-4">
                    <h4 class="font-extrabold text-gray-800 mb-4 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-address-book text-gray-400"></i> Data Tambahan & Orang Tua
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-700">Nama Orang Tua / Wali</label>
                            <input type="text" name="nama_ortu"
                                :value="selectedPenduduk?.nama_ortu_gabungan || '{{ old('nama_ortu') }}'"
                                placeholder="Nama ayah/ibu/wali"
                                class="w-full bg-white border @error('nama_ortu') border-red-300 ring-1 ring-red-300 @else border-gray-200 @enderror rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-gray-400 outline-none transition-all">
                            @error('nama_ortu')
                                <p class="text-red-500 text-[10px]">{{ $message }}</p>
                            @else
                                <p class="text-[10px] text-gray-500 mt-1"><i class="fa-solid fa-magic text-amber-500"></i>
                                    Terisi otomatis jika data tersedia.</p>
                            @enderror
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-700">Catatan Kondisi Anak</label>
                            <textarea name="catatan" rows="2" placeholder="Catatan tambahan (opsional)..."
                                class="w-full bg-white border @error('catatan') border-red-300 ring-1 ring-red-300 @else border-gray-200 @enderror rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-gray-400 outline-none resize-none">{{ old('catatan') }}</textarea>
                            @error('catatan')
                                <p class="text-red-500 text-[10px]">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 shrink-0 flex justify-end gap-3 rounded-b-2xl">
            <button type="button" @click="addModalOpen = false; selectedPenduduk = null"
                class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-600 border border-gray-200 hover:bg-gray-100 transition-colors bg-white cursor-pointer">Batal</button>
            <button type="submit" form="pengukuranForm"
                class="px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-green-700 hover:bg-green-800 shadow-md transition-all cursor-pointer">
                <i class="fa-solid fa-save mr-2"></i> Simpan Pengukuran
            </button>
        </div>
    </div>
</div>

{{-- Browse Balita Modal --}}
<div x-show="browseModalOpen" class="fixed inset-0 z-[120] flex items-center justify-center p-4 sm:p-6" x-cloak>
    <div x-show="browseModalOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
        @click="browseModalOpen = false"></div>

    <div x-show="browseModalOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="relative bg-white rounded-2xl shadow-2xl w-full max-w-4xl overflow-hidden flex flex-col max-h-[90vh]">

        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 shrink-0">
            <div>
                <h3 class="font-extrabold text-lg text-gray-900">Pilih Data Balita (0-59 Bulan)</h3>
                <p class="text-xs text-gray-500 mt-0.5">Hanya menampilkan penduduk dengan usia 0-59 bulan.</p>
            </div>
            <button @click="browseModalOpen = false"
                class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        {{-- Filters for Browse --}}
        <div class="px-6 py-4 border-b border-gray-100 bg-white shrink-0 flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                <input type="text" x-model="browseFilters.q" @input.debounce.500ms="fetchBrowseData()"
                    placeholder="Cari NIK / Nama..."
                    class="w-full bg-white border border-gray-300 rounded-lg pl-8 pr-3 py-2 text-xs font-semibold focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <select x-model="browseFilters.umur" @change="fetchBrowseData()"
                class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-xs font-semibold focus:ring-2 focus:ring-blue-500 outline-none cursor-pointer">
                <option value="">Semua Umur</option>
                <option value="0-6">0 - 6 Bulan</option>
                <option value="7-24">7 - 24 Bulan</option>
                <option value="25-59">25 - 59 Bulan</option>
            </select>
            <select x-model="browseFilters.jk" @change="fetchBrowseData()"
                class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-xs font-semibold focus:ring-2 focus:ring-blue-500 outline-none cursor-pointer">
                <option value="">Semua Gender</option>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
            </select>
        </div>

        <div class="overflow-y-auto custom-scrollbar flex-1 bg-gray-50/30 relative">
            <div x-show="isBrowsing"
                class="absolute inset-0 bg-white/80 backdrop-blur-sm z-10 flex flex-col items-center justify-center">
                <i class="fa-solid fa-circle-notch fa-spin text-blue-500 text-3xl mb-2"></i>
                <p class="text-sm font-bold text-gray-600">Memuat Data...</p>
            </div>

            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead class="sticky top-0 z-0">
                    <tr class="bg-gray-100 text-gray-600 text-xs uppercase tracking-wider border-b border-gray-200">
                        <th class="px-6 py-3 font-semibold">Identitas Anak</th>
                        <th class="px-6 py-3 font-semibold text-center">L/P</th>
                        <th class="px-6 py-3 font-semibold text-center">Umur</th>
                        <th class="px-6 py-3 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100 bg-white">
                    <template x-for="item in browseData" :key="item.id">
                        <tr class="hover:bg-blue-50/50 transition-colors">
                            <td class="px-6 py-3">
                                <div class="font-bold text-gray-900" x-text="item.nama"></div>
                                <div class="text-[10px] font-mono text-gray-500 mt-0.5" x-text="'NIK: ' + item.nik">
                                </div>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <span class="text-xs font-bold px-2 py-1 rounded border"
                                    :class="item.jenis_kelamin === 'L' ? 'bg-blue-100 text-blue-700 border-blue-200' :
                                        'bg-pink-100 text-pink-700 border-pink-200'"
                                    x-text="item.jenis_kelamin"></span>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <span class="font-bold text-gray-700" x-text="item.umur_bulan + ' bln'"></span>
                            </td>
                            <td class="px-6 py-3 text-right">
                                <button type="button" @click="selectFromBrowse(item)"
                                    class="px-4 py-1.5 bg-blue-100 text-blue-700 hover:bg-blue-600 hover:text-white rounded-lg text-xs font-bold transition-colors cursor-pointer">
                                    Pilih
                                </button>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="!isBrowsing && browseData.length === 0">
                        <td colspan="4" class="px-6 py-10 text-center text-gray-400">
                            <i class="fa-solid fa-folder-open text-3xl mb-2 block"></i>
                            <p class="font-semibold">Data balita tidak ditemukan.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
