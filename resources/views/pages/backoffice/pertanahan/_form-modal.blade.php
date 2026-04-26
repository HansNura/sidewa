{{-- Add/Edit Land Modal - Full Map Editor --}}
<div x-show="addModalOpen" class="fixed inset-0 z-[110] !m-0" x-cloak x-data="pertanahanMapEditor()" x-init="$watch('addModalOpen', v => { if (v) { $nextTick(() => initMap()); } })">

    {{-- Backdrop --}}
    <div x-show="addModalOpen" x-transition.opacity class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"
        @click="addModalOpen = false"></div>

    {{-- Full-screen Map Container --}}
    <div x-show="addModalOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-4 sm:inset-6 bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col z-10">

        {{-- Map fills the entire container --}}
        <div class="relative flex-1 bg-gray-100">
            <div id="mapPertanahanEditor" class="absolute inset-0 z-0"></div>

            {{-- Close Button (Top Right) --}}
            <button @click="addModalOpen = false; cancelDraw()"
                class="absolute top-4 right-4 z-[500] w-10 h-10 bg-white/95 backdrop-blur-md rounded-xl shadow-lg border border-gray-200 flex items-center justify-center text-gray-500 hover:text-red-500 hover:bg-red-50 hover:border-red-200 transition-all cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            {{-- ═══ Left Floating Panel Toggle & Container ═══ --}}
            <div x-data="{ showForm: true }"
                class="absolute top-4 left-4 z-[500] flex flex-col gap-3 max-h-[calc(100%-32px)]">

                {{-- Toggle Icon Button (Visible when collapsed) --}}
                <button x-show="!showForm" @click="showForm = true" x-transition.opacity
                    class="w-12 h-12 bg-gradient-to-r from-gray-900 to-gray-800 hover:from-gray-800 hover:to-gray-700 shadow-xl border border-gray-700 rounded-2xl flex items-center justify-center cursor-pointer transition-all">
                    <i class="fa-solid fa-layer-group text-emerald-400 text-xl"></i>
                </button>

                {{-- Full Panel --}}
                <div x-show="showForm" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 -translate-x-4"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 -translate-x-4"
                    class="w-[340px] bg-white/95 backdrop-blur-md rounded-2xl shadow-xl border border-gray-100 flex flex-col overflow-hidden max-h-full">

                    {{-- Panel Header --}}
                    <div
                        class="bg-gradient-to-r from-gray-900 to-gray-800 text-white p-4 shrink-0 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-white/10 border border-white/10 flex items-center justify-center shadow-inner shrink-0">
                                <i class="fa-solid fa-layer-group text-emerald-400 text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-[13px] tracking-wide">Pendataan Lahan</h3>
                                <p class="text-gray-400 text-[10px] mt-0.5 leading-snug">Data administratif & spasial
                                </p>
                            </div>
                        </div>
                        <button type="button" @click="showForm = false" title="Tutup Panel"
                            class="w-8 h-8 rounded-full hover:bg-white/10 flex items-center justify-center cursor-pointer transition-all text-gray-400 hover:text-red-400 shrink-0">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </button>
                    </div>

                    {{-- Form Content --}}
                    <div class="p-5 overflow-y-auto custom-scrollbar flex-1">
                        <form id="pertanahanForm" method="POST" action="{{ route('admin.pertanahan.store') }}"
                            class="space-y-4">
                            @csrf

                            {{-- Kepemilikan --}}
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Pilih
                                    Kepemilikan <span class="text-red-500">*</span></label>
                                <select name="kepemilikan" x-model="kepemilikanField" required
                                    class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 outline-none cursor-pointer">
                                    <option value="desa">Aset Pemerintah Desa (TKD)</option>
                                    <option value="warga">Milik Pribadi (Warga)</option>
                                    <option value="fasum">Fasilitas Umum</option>
                                </select>
                            </div>

                            {{-- Owner Search (only for warga) --}}
                            <div x-show="kepemilikanField === 'warga'" x-transition x-collapse>
                                {{-- Select Warga Card --}}
                                <div class="border rounded-xl p-4 transition-colors duration-300"
                                    :class="!selectedPenduduk ? 'bg-emerald-50/50 border-emerald-100' : 'bg-white border-emerald-200 shadow-sm'">
                                    
                                    <label class="text-xs font-bold block mb-3 transition-colors"
                                        :class="!selectedPenduduk ? 'text-emerald-800' : 'text-emerald-900'">
                                        Pemilik Lahan (Warga) <span class="text-red-500">*</span>
                                    </label>

                                    {{-- Before Selection (Empty State) --}}
                                    <div x-show="!selectedPenduduk"
                                        class="flex flex-col items-center justify-center py-6 border-2 border-dashed border-emerald-200 rounded-xl bg-white gap-3">
                                        <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center">
                                            <i class="fa-solid fa-user-tag text-xl"></i>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-sm font-bold text-gray-700">Belum Ada Warga Terpilih</p>
                                            <p class="text-[11px] text-gray-500 mt-0.5">Pilih dari data penduduk desa.</p>
                                        </div>
                                        <button type="button" @click="openBrowseModal()"
                                            class="mt-1 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-lg shadow-sm text-sm font-bold transition-colors cursor-pointer flex items-center gap-2">
                                            <i class="fa-solid fa-list"></i>
                                            Pilih Data Warga
                                        </button>
                                    </div>

                                    {{-- Selected Citizen Display --}}
                                    <div x-show="selectedPenduduk"
                                        class="flex items-start justify-between gap-4">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                                                <i class="fa-solid fa-user-check text-xl"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-extrabold text-gray-900" x-text="selectedPenduduk?.nama"></p>
                                                <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1">
                                                    <p class="text-[11px] font-mono text-gray-500 flex items-center gap-1">
                                                        <i class="fa-regular fa-id-card"></i> <span x-text="selectedPenduduk?.nik"></span>
                                                    </p>
                                                    <p class="text-[11px] text-gray-500 flex items-center gap-1">
                                                        <i class="fa-solid fa-location-dot"></i> <span x-text="'Dusun ' + (selectedPenduduk?.dusun || '-')"></span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" @click="selectedPenduduk = null; selectedDusun = ''; selectedRw = ''; selectedRt = ''" title="Ganti Pemilik"
                                            class="w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 hover:text-red-700 flex items-center justify-center cursor-pointer transition-colors shrink-0">
                                            <i class="fa-solid fa-rotate-right text-sm"></i>
                                        </button>
                                    </div>

                                    <input type="hidden" name="penduduk_id" :value="selectedPenduduk?.id ?? ''">
                                </div>
                            </div>

                            {{-- Luas & Legalitas --}}
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label
                                        class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5 flex justify-between items-center">
                                        <span>Luas Tanah <span class="text-red-500">*</span></span>
                                        <span
                                            class="text-[8px] bg-emerald-100 text-emerald-600 px-1.5 py-0.5 rounded font-bold normal-case"
                                            x-show="hasShape" x-cloak>Otomatis</span>
                                    </label>
                                    <div class="relative flex items-center">
                                        <input type="number" name="luas" x-model="calculatedArea" required
                                            min="1" :readonly="hasShape"
                                            class="w-full border border-gray-300 rounded-lg pl-3 pr-8 py-2 text-sm focus:ring-2 focus:ring-emerald-500 outline-none transition-colors"
                                            :class="hasShape ?
                                                'bg-emerald-50/50 cursor-not-allowed text-emerald-700 font-bold border-emerald-200' :
                                                'bg-white text-gray-900'">
                                        <span class="absolute right-3 text-xs font-semibold"
                                            :class="hasShape ? 'text-emerald-600' : 'text-gray-400'">m²</span>
                                    </div>
                                </div>
                                <div>
                                    <label
                                        class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Legalitas
                                        <span class="text-red-500">*</span></label>
                                    <select name="legalitas" required
                                        class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 outline-none cursor-pointer">
                                        <option value="shm" {{ old('legalitas') === 'shm' ? 'selected' : '' }}>SHM
                                        </option>
                                        <option value="shgb" {{ old('legalitas') === 'shgb' ? 'selected' : '' }}>
                                            SHP/SHGB</option>
                                        <option value="girik" {{ old('legalitas') === 'girik' ? 'selected' : '' }}>
                                            Girik</option>
                                        <option value="ajb" {{ old('legalitas') === 'ajb' ? 'selected' : '' }}>AJB
                                        </option>
                                        <option value="belum_sertifikat"
                                            {{ old('legalitas') === 'belum_sertifikat' ? 'selected' : '' }}>Belum
                                        </option>
                                    </select>
                                </div>
                            </div>

                            {{-- Lokasi Blok --}}
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Blok
                                    / Lokasi <span class="text-red-500">*</span></label>
                                <input type="text" name="lokasi_blok" value="{{ old('lokasi_blok') }}" required
                                    placeholder="Misal: Blok Sawah"
                                    class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                            </div>

                            {{-- Lokasi Administratif (Collapsed) --}}
                            <div x-data="{ showLokasi: false }" class="border-t border-gray-100 pt-3">
                                <button type="button" @click="showLokasi = !showLokasi"
                                    class="flex items-center justify-between w-full text-left cursor-pointer group">
                                    <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Alamat
                                        Administratif</span>
                                    <i class="fa-solid fa-chevron-down text-gray-400 text-[10px] transition-transform duration-200"
                                        :class="showLokasi && 'rotate-180'"></i>
                                </button>
                                <div x-show="showLokasi" x-collapse class="mt-3 grid grid-cols-3 gap-2">
                                    <div class="col-span-1">
                                        <select name="dusun" x-model="selectedDusun"
                                            @change="selectedRw = ''; selectedRt = ''"
                                            class="w-full bg-white border border-gray-200 rounded-lg px-2 py-2 text-xs focus:ring-2 focus:ring-emerald-500 outline-none cursor-pointer">
                                            <option value="">-- Dusun --</option>
                                            <template x-for="dusun in wilayahTree" :key="dusun.id">
                                                <option :value="dusun.nama" x-text="dusun.nama"
                                                    :selected="dusun.nama === selectedDusun"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <div class="col-span-1">
                                        <select name="rw" x-model="selectedRw" @change="selectedRt = ''"
                                            :disabled="!selectedDusun"
                                            class="w-full bg-white border border-gray-200 rounded-lg px-2 py-2 text-xs focus:ring-2 focus:ring-emerald-500 outline-none cursor-pointer disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed">
                                            <option value="">-- RW --</option>
                                            <template x-for="rw in availableRws" :key="rw.id">
                                                <option :value="rw.nama" x-text="rw.nama"
                                                    :selected="rw.nama === selectedRw"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <div class="col-span-1">
                                        <select name="rt" x-model="selectedRt" :disabled="!selectedRw"
                                            class="w-full bg-white border border-gray-200 rounded-lg px-2 py-2 text-xs focus:ring-2 focus:ring-emerald-500 outline-none cursor-pointer disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed">
                                            <option value="">-- RT --</option>
                                            <template x-for="rt in availableRts" :key="rt.id">
                                                <option :value="rt.nama" x-text="rt.nama"
                                                    :selected="rt.nama === selectedRt"></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- Nomor Sertifikat --}}
                            <div class="border-t border-gray-100 pt-3">
                                <label
                                    class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">No.
                                    Sertifikat (Opsional)</label>
                                <input type="text" name="nomor_sertifikat" value="{{ old('nomor_sertifikat') }}"
                                    placeholder="No. Sertifikat"
                                    class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                            </div>

                            {{-- GeoJSON Preview --}}
                            <div class="border-t border-gray-100 pt-3">
                                <label
                                    class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5 flex justify-between items-center">
                                    <span class="flex items-center gap-1.5">
                                        <i class="fa-solid fa-code text-emerald-500"></i> GeoJSON
                                    </span>
                                    <span
                                        class="text-[9px] bg-gray-100 px-1.5 py-0.5 rounded text-gray-500 font-medium normal-case">Readonly</span>
                                </label>
                                <textarea readonly rows="2"
                                    class="w-full border border-gray-200 rounded-lg p-2.5 text-[10px] font-mono bg-gray-50 text-gray-600 focus:outline-none resize-none custom-scrollbar"
                                    x-text="geojsonOutput || '{}'"></textarea>
                                <input type="hidden" name="geojson" id="geojsonInput" value="{{ old('geojson') }}">

                                {{-- Status Indicator --}}
                                <div class="mt-2 flex items-center gap-2">
                                    <template x-if="hasShape">
                                        <div
                                            class="flex items-center gap-1.5 text-[10px] text-emerald-600 font-semibold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            <span x-text="pointCount + ' titik tergambar'"></span>
                                        </div>
                                    </template>
                                    <template x-if="!hasShape">
                                        <div class="flex items-center gap-1.5 text-[10px] text-gray-400 font-medium">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span>
                                            Belum ada area digambar
                                        </div>
                                    </template>
                                </div>
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

                    {{-- Footer Action --}}
                    <div class="p-4 border-t border-gray-100 bg-gray-50/80 shrink-0 space-y-2">
                        <button type="submit" form="pertanahanForm"
                            class="w-full bg-emerald-700 hover:bg-emerald-800 text-white py-2.5 rounded-xl text-sm font-bold transition-all shadow-md shadow-emerald-700/20 cursor-pointer flex items-center justify-center gap-2">
                            <i class="fa-solid fa-save"></i> Simpan Lahan
                        </button>
                        <button type="button" @click="addModalOpen = false; cancelDraw(); selectedPenduduk = null;"
                            class="w-full bg-white border border-gray-200 text-gray-600 py-2 rounded-xl text-xs font-semibold hover:bg-gray-100 transition-colors cursor-pointer">
                            Batal
                        </button>
                    </div>
                </div>
            </div>

            {{-- ═══ Right Floating Tools ═══ --}}
            <div class="absolute top-[72px] right-4 flex flex-col gap-3 z-[500]">
                {{-- Drawing Tools Group --}}
                <div
                    class="bg-white/95 backdrop-blur-md rounded-xl shadow-md border border-gray-200 flex flex-col overflow-hidden w-10">
                    {{-- Draw Polygon --}}
                    <button @click="startDraw()" type="button"
                        class="p-2.5 border-b border-gray-100 transition-all relative group cursor-pointer flex justify-center items-center h-10"
                        :class="drawMode === 'draw' ? 'text-emerald-600 bg-emerald-50' :
                            'text-gray-500 hover:bg-gray-50 hover:text-gray-800'"
                        title="Draw Polygon">
                        <i class="fa-solid fa-pen-nib text-[15px]"></i>
                        <div
                            class="absolute right-full top-1/2 -translate-y-1/2 mr-3 bg-gray-800 text-white text-[11px] font-medium px-2.5 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 pointer-events-none whitespace-nowrap transition-opacity shadow-lg">
                            Gambar Poligon Baru</div>
                    </button>
                    {{-- Edit Shape --}}
                    <button @click="drawMode === 'edit' ? finishEdit() : startEdit()" type="button"
                        class="p-2.5 border-b border-gray-100 transition-all relative group cursor-pointer flex justify-center items-center h-10"
                        :class="drawMode === 'edit' ? 'text-blue-600 bg-blue-50' : (hasShape ?
                            'text-gray-500 hover:bg-gray-50 hover:text-gray-800' :
                            'text-gray-300 cursor-not-allowed')"
                        :disabled="!hasShape" title="Edit Shape">
                        <i class="fa-solid fa-draw-polygon text-[15px]"></i>
                        <div class="absolute right-full top-1/2 -translate-y-1/2 mr-3 bg-gray-800 text-white text-[11px] font-medium px-2.5 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 pointer-events-none whitespace-nowrap transition-opacity shadow-lg"
                            x-text="drawMode === 'edit' ? 'Selesai Edit' : 'Edit Titik Poligon'"></div>
                    </button>
                    {{-- Delete Shape --}}
                    <button @click="deleteShape()" type="button"
                        class="p-2.5 transition-all relative group cursor-pointer flex justify-center items-center h-10"
                        :class="hasShape ? 'text-red-500 hover:bg-red-50' : 'text-gray-300 cursor-not-allowed'"
                        :disabled="!hasShape" title="Delete">
                        <i class="fa-regular fa-trash-can text-[15px]"></i>
                        <div
                            class="absolute right-full top-1/2 -translate-y-1/2 mr-3 bg-gray-800 text-white text-[11px] font-medium px-2.5 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 pointer-events-none whitespace-nowrap transition-opacity shadow-lg">
                            Hapus Poligon</div>
                    </button>
                </div>

                {{-- Map Controls Group --}}
                <div
                    class="bg-white/95 backdrop-blur-md rounded-xl shadow-md border border-gray-200 flex flex-col overflow-hidden w-10">
                    {{-- Layer toggle --}}
                    <button type="button"
                        class="p-2.5 text-gray-500 hover:bg-gray-50 hover:text-gray-800 border-b border-gray-100 transition cursor-pointer relative group flex justify-center items-center h-10"
                        title="Layers">
                        <i class="fa-solid fa-layer-group text-[14px]"></i>
                        <div
                            class="absolute right-full top-1/2 -translate-y-1/2 mr-3 bg-gray-800 text-white text-[11px] font-medium px-2.5 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 pointer-events-none whitespace-nowrap transition-opacity shadow-lg">
                            Pilihan Layer Peta</div>
                    </button>
                    {{-- Zoom In --}}
                    <button @click="zoomIn()" type="button"
                        class="p-2.5 text-gray-500 hover:bg-gray-50 hover:text-gray-800 border-b border-gray-100 transition cursor-pointer flex justify-center items-center h-10"
                        title="Zoom In">
                        <i class="fa-solid fa-plus text-[15px]"></i>
                    </button>
                    {{-- Zoom Out --}}
                    <button @click="zoomOut()" type="button"
                        class="p-2.5 text-gray-500 hover:bg-gray-50 hover:text-gray-800 transition cursor-pointer flex justify-center items-center h-10"
                        title="Zoom Out">
                        <i class="fa-solid fa-minus text-[15px]"></i>
                    </button>
                </div>
            </div>

            {{-- ═══ Drawing Status Bar (Bottom) ═══ --}}
            <div x-show="isDrawing" x-transition
                class="absolute bottom-4 left-1/2 -translate-x-1/2 z-[500] bg-gray-900/90 backdrop-blur-md text-white px-5 py-3 rounded-xl shadow-lg flex items-center gap-3">
                <template x-if="drawMode === 'draw'">
                    <div class="flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span class="text-sm font-medium">Klik pada peta untuk menggambar batas lahan. Klik titik
                            pertama untuk menutup area.</span>
                        <button @click="cancelDraw()" type="button"
                            class="ml-2 text-xs bg-white/10 hover:bg-white/20 px-3 py-1 rounded-lg transition cursor-pointer">Batal</button>
                    </div>
                </template>
                <template x-if="drawMode === 'edit'">
                    <div class="flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                        <span class="text-sm font-medium">Geser titik-titik untuk mengedit batas lahan. Klik "Selesai
                            Edit" jika sudah.</span>
                        <button @click="finishEdit()" type="button"
                            class="ml-2 text-xs bg-emerald-600 hover:bg-emerald-700 px-3 py-1 rounded-lg transition cursor-pointer">Selesai</button>
                    </div>
                </template>
            </div>

            {{-- ═══ Legend (Top Center) ═══ --}}
            <div
                class="absolute top-4 left-1/2 -translate-x-1/2 z-[500] bg-white/95 backdrop-blur-md shadow-md border border-gray-200 rounded-full px-4 py-2 flex items-center gap-4 hidden sm:flex">
                <span
                    class="text-[10px] font-bold text-gray-500 uppercase tracking-wider border-r border-gray-200 pr-4">Kepemilikan
                    Lahan</span>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-1.5">
                        <div class="w-2.5 h-2.5 rounded-full bg-emerald-400 border border-emerald-600 opacity-80">
                        </div>
                        <span class="text-[10px] font-bold text-gray-700">Desa</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <div class="w-2.5 h-2.5 rounded-full bg-blue-400 border border-blue-600 opacity-80"></div>
                        <span class="text-[10px] font-bold text-gray-700">Warga</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <div class="w-2.5 h-2.5 rounded-full bg-amber-400 border border-amber-600 opacity-80"></div>
                        <span class="text-[10px] font-bold text-gray-700">Fasum</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Browse Pemilik Modal --}}
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
                <h3 class="font-extrabold text-lg text-gray-900">Pilih Data Pemilik Lahan (Warga)</h3>
                <p class="text-xs text-gray-500 mt-0.5">Cari berdasarkan NIK atau Nama penduduk desa.</p>
            </div>
            <button @click="browseModalOpen = false"
                class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        {{-- Filters for Browse --}}
        <div class="px-6 py-4 border-b border-gray-100 bg-white shrink-0 flex gap-3">
            <div class="relative flex-1">
                <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                <input type="text" x-model="browseFilters.q" @input.debounce.500ms="fetchBrowseData()"
                    placeholder="Ketik NIK atau Nama..."
                    class="w-full bg-white border border-gray-300 rounded-lg pl-8 pr-3 py-2 text-xs font-semibold focus:ring-2 focus:ring-emerald-500 outline-none">
            </div>
        </div>

        <div class="overflow-y-auto custom-scrollbar flex-1 bg-gray-50/30 relative">
            <div x-show="isBrowsing"
                class="absolute inset-0 bg-white/80 backdrop-blur-sm z-10 flex flex-col items-center justify-center">
                <i class="fa-solid fa-circle-notch fa-spin text-emerald-500 text-3xl mb-2"></i>
                <p class="text-sm font-bold text-gray-600">Memuat Data...</p>
            </div>

            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead class="sticky top-0 z-0">
                    <tr class="bg-gray-100 text-gray-600 text-xs uppercase tracking-wider border-b border-gray-200">
                        <th class="px-6 py-3 font-semibold">Identitas Warga</th>
                        <th class="px-6 py-3 font-semibold text-center">Alamat (Dusun)</th>
                        <th class="px-6 py-3 font-semibold text-center">RT/RW</th>
                        <th class="px-6 py-3 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100 bg-white">
                    <template x-for="item in browseData" :key="item.id">
                        <tr class="hover:bg-emerald-50/50 transition-colors">
                            <td class="px-6 py-3">
                                <div class="font-bold text-gray-900" x-text="item.nama"></div>
                                <div class="text-[10px] font-mono text-gray-500 mt-0.5" x-text="'NIK: ' + item.nik">
                                </div>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <span class="font-bold text-gray-700" x-text="item.dusun || '-'"></span>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <span class="font-bold text-gray-700" x-text="(item.rt || '-') + '/' + (item.rw || '-')"></span>
                            </td>
                            <td class="px-6 py-3 text-right">
                                <button type="button" @click="selectFromBrowse(item)"
                                    class="px-4 py-1.5 bg-emerald-100 text-emerald-700 hover:bg-emerald-600 hover:text-white rounded-lg text-xs font-bold transition-colors cursor-pointer">
                                    Pilih
                                </button>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="!isBrowsing && browseData.length === 0">
                        <td colspan="4" class="px-6 py-10 text-center text-gray-400">
                            <i class="fa-solid fa-folder-open text-3xl mb-2 block"></i>
                            <p class="font-semibold">Data warga tidak ditemukan.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
