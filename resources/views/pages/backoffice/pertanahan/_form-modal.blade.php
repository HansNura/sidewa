{{-- Add Land Modal --}}
<div x-show="addModalOpen" class="fixed inset-0 z-[110] flex items-center justify-center p-4 sm:p-6" x-cloak>
    <div x-show="addModalOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
         @click="addModalOpen = false"></div>

    <div x-show="addModalOpen" x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
         class="relative bg-white rounded-2xl shadow-2xl w-full max-w-4xl overflow-hidden flex flex-col max-h-[90vh]">

        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 shrink-0">
            <h3 class="font-extrabold text-lg text-gray-900">Form Pendataan Lahan Baru</h3>
            <button @click="addModalOpen = false; selectedPenduduk = null"
                class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <div class="overflow-y-auto custom-scrollbar flex-1 flex flex-col md:flex-row">

            {{-- Left: Form Fields --}}
            <div class="w-full md:w-1/2 p-6 border-b md:border-b-0 md:border-r border-gray-100 space-y-5">
                <form id="pertanahanForm" method="POST" action="{{ route('admin.pertanahan.store') }}" class="space-y-5">
                    @csrf

                    {{-- Kepemilikan --}}
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700">Pilih Kepemilikan Lahan <span class="text-red-500">*</span></label>
                        <select name="kepemilikan" x-model="kepemilikanField" required
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none cursor-pointer">
                            <option value="desa">Aset Pemerintah Desa (TKD)</option>
                            <option value="warga">Milik Pribadi (Warga)</option>
                            <option value="fasum">Fasilitas Umum</option>
                        </select>
                    </div>

                    {{-- Owner Search (only for warga) --}}
                    <div class="space-y-1" x-show="kepemilikanField === 'warga'" x-transition>
                        <label class="text-xs font-bold text-gray-700">Cari Pemilik (Warga) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input type="text" placeholder="Ketik NIK atau Nama..."
                                   x-show="!selectedPenduduk"
                                   @input.debounce.300ms="searchPenduduk($event.target.value)"
                                   class="w-full bg-white border border-gray-300 rounded-xl pl-9 pr-4 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none">

                            {{-- Selected --}}
                            <div x-show="selectedPenduduk" class="flex items-center justify-between bg-gray-50 border border-gray-200 rounded-xl px-4 py-2">
                                <div>
                                    <p class="text-sm font-bold text-gray-900" x-text="selectedPenduduk?.nama"></p>
                                    <p class="text-[10px] font-mono text-gray-500" x-text="'NIK: ' + selectedPenduduk?.nik"></p>
                                </div>
                                <button type="button" @click="selectedPenduduk = null" class="text-red-400 hover:text-red-600 cursor-pointer">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>

                            <input type="hidden" name="penduduk_id" :value="selectedPenduduk?.id ?? ''">
                        </div>

                        {{-- Dropdown --}}
                        <div x-show="searchResults.length > 0 && !selectedPenduduk"
                             class="mt-2 bg-white border border-gray-200 rounded-lg shadow-lg max-h-48 overflow-y-auto divide-y divide-gray-50">
                            <template x-for="item in searchResults" :key="item.id">
                                <button type="button" @click="selectPenduduk(item)"
                                        class="w-full text-left px-4 py-2.5 hover:bg-green-50 transition-colors cursor-pointer">
                                    <p class="text-sm font-bold text-gray-800" x-text="item.nama"></p>
                                    <p class="text-[10px] text-gray-500">
                                        <span x-text="'NIK: ' + item.nik"></span> · <span x-text="'Dusun ' + (item.dusun || '-')"></span>
                                    </p>
                                </button>
                            </template>
                        </div>
                    </div>

                    {{-- Luas & Legalitas --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-700">Luas Tanah <span class="text-red-500">*</span></label>
                            <div class="flex">
                                <input type="number" name="luas" value="{{ old('luas') }}" required min="1"
                                       class="w-full bg-gray-50 border border-gray-200 rounded-l-xl px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none">
                                <span class="bg-gray-100 border border-l-0 border-gray-200 rounded-r-xl px-3 py-2 text-xs font-semibold text-gray-500 flex items-center">m²</span>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-700">Status Legalitas <span class="text-red-500">*</span></label>
                            <select name="legalitas" required
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none cursor-pointer">
                                <option value="shm" {{ old('legalitas') === 'shm' ? 'selected' : '' }}>SHM</option>
                                <option value="shgb" {{ old('legalitas') === 'shgb' ? 'selected' : '' }}>SHGB / SHP</option>
                                <option value="girik" {{ old('legalitas') === 'girik' ? 'selected' : '' }}>Girik / Letter C</option>
                                <option value="ajb" {{ old('legalitas') === 'ajb' ? 'selected' : '' }}>AJB</option>
                                <option value="belum_sertifikat" {{ old('legalitas') === 'belum_sertifikat' ? 'selected' : '' }}>Belum Bersertifikat</option>
                            </select>
                        </div>
                    </div>

                    {{-- Lokasi & Nomor Sertifikat --}}
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700">Blok / Lokasi Tanah <span class="text-red-500">*</span></label>
                        <input type="text" name="lokasi_blok" value="{{ old('lokasi_blok') }}" required
                               placeholder="Misal: Kompleks Balai Desa"
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none">
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-700">Dusun</label>
                            <input type="text" name="dusun" value="{{ old('dusun') }}" placeholder="Kaler"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none">
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-700">RT</label>
                            <input type="text" name="rt" value="{{ old('rt') }}" placeholder="01"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none">
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-700">RW</label>
                            <input type="text" name="rw" value="{{ old('rw') }}" placeholder="02"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none">
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700">Nomor Sertifikat</label>
                        <input type="text" name="nomor_sertifikat" value="{{ old('nomor_sertifikat') }}"
                               placeholder="No. 12.34.56.78.9.00123"
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none">
                    </div>

                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 rounded-lg p-2.5 flex items-start gap-2">
                            <i class="fa-solid fa-circle-exclamation text-red-600 mt-0.5 text-xs"></i>
                            <div>
                                @foreach ($errors->all() as $error)
                                    <p class="text-[10px] text-red-800 font-medium">{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </form>
            </div>

            {{-- Right: GeoJSON Input --}}
            <div class="w-full md:w-1/2 p-6 bg-gray-50/50 flex flex-col space-y-4">
                <div>
                    <h4 class="text-sm font-bold text-gray-800 mb-1"><i class="fa-solid fa-draw-polygon text-green-600 mr-1"></i> Data Spasial Lahan</h4>
                    <p class="text-[10px] text-gray-500">Input manual koordinat GeoJSON (Poligon) lahan. Opsional.</p>
                </div>
                <textarea form="pertanahanForm" name="geojson" rows="6"
                    placeholder='{"type":"Feature","geometry":{"type":"Polygon","coordinates":[[[107.63,−6.82],[107.64,−6.82],[107.64,−6.83],[107.63,−6.83],[107.63,−6.82]]]}}'
                    class="w-full bg-gray-900 text-green-400 font-mono border border-gray-200 rounded-xl px-4 py-3 text-xs focus:ring-2 focus:ring-green-500 outline-none resize-none">{{ old('geojson') }}</textarea>

                <div class="text-xs text-gray-500">
                    <p class="font-semibold mb-1">Tips:</p>
                    <ul class="list-disc list-inside space-y-0.5 text-[10px]">
                        <li>Format: GeoJSON Feature dengan geometry tipe Polygon</li>
                        <li>Koordinat: [longitude, latitude] (WGS84)</li>
                        <li>Bisa diekspor dari QGIS atau Google Earth Pro</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="px-6 py-4 border-t border-gray-100 bg-white shrink-0 flex justify-end gap-3 rounded-b-2xl">
            <button type="button" @click="addModalOpen = false; selectedPenduduk = null"
                class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-600 border border-gray-200 hover:bg-gray-50 transition-colors cursor-pointer">Batal</button>
            <button type="submit" form="pertanahanForm"
                class="px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-green-700 hover:bg-green-800 shadow-md transition-all cursor-pointer">
                <i class="fa-solid fa-save mr-2"></i> Simpan Lahan
            </button>
        </div>
    </div>
</div>
