{{-- Add/Edit Wilayah Modal - Full Map Editor --}}
<div x-show="addModalOpen" class="fixed inset-0 z-[110] !m-0" x-cloak
     x-data="mapEditor()" x-init="$watch('addModalOpen', v => { if (v) { $nextTick(() => initMap()); } })">

    {{-- Backdrop --}}
    <div x-show="addModalOpen" x-transition.opacity class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"
         @click="addModalOpen = false"></div>

    {{-- Full-screen Map Container --}}
    <div x-show="addModalOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-4 sm:inset-6 bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col z-10">

        {{-- Map fills the entire container --}}
        <div class="relative flex-1 bg-gray-100">
            <div id="mapEditor" class="absolute inset-0 z-0"></div>

            {{-- Close Button (Top Right) --}}
            <button @click="addModalOpen = false; cancelDraw()"
                class="absolute top-4 right-4 z-[500] w-10 h-10 bg-white/95 backdrop-blur-md rounded-xl shadow-lg border border-gray-200 flex items-center justify-center text-gray-500 hover:text-red-500 hover:bg-red-50 hover:border-red-200 transition-all cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            {{-- ═══ Left Floating Panel Toggle & Container ═══ --}}
            <div x-data="{ showForm: true }" class="absolute top-4 left-4 z-[500] flex flex-col gap-3 max-h-[calc(100%-32px)]">

                {{-- Toggle Icon Button (Visible when collapsed) --}}
                <button x-show="!showForm" @click="showForm = true" x-transition.opacity
                    class="w-12 h-12 bg-gradient-to-r from-gray-900 to-gray-800 hover:from-gray-800 hover:to-gray-700 shadow-xl border border-gray-700 rounded-2xl flex items-center justify-center cursor-pointer transition-all">
                    <i class="fa-solid fa-draw-polygon text-green-400 text-xl"></i>
                </button>

                {{-- Full Panel --}}
                <div x-show="showForm"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 -translate-x-4"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 -translate-x-4"
                    class="w-80 bg-white/95 backdrop-blur-md rounded-2xl shadow-xl border border-gray-100 flex flex-col overflow-hidden max-h-full">

                    {{-- Panel Header --}}
                    <div class="bg-gradient-to-r from-gray-900 to-gray-800 text-white p-4 shrink-0 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white/10 border border-white/10 flex items-center justify-center shadow-inner shrink-0">
                                <i class="fa-solid fa-draw-polygon text-green-400 text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-[13px] tracking-wide">Batas Wilayah</h3>
                                <p class="text-gray-400 text-[10px] mt-0.5 leading-snug">Gambar dan simpan area peta</p>
                            </div>
                        </div>
                        <button type="button" @click="showForm = false" title="Tutup Panel"
                            class="w-8 h-8 rounded-full hover:bg-white/10 flex items-center justify-center cursor-pointer transition-all text-gray-400 hover:text-red-400 shrink-0">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </button>
                    </div>

                    {{-- Form Content --}}
                    <div class="p-5 overflow-y-auto custom-scrollbar flex-1">
                    <form id="wilayahForm" method="POST" action="{{ route('admin.wilayah.store') }}" class="space-y-4">
                        @csrf

                        {{-- Tipe --}}
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Tingkat Wilayah</label>
                            <select name="tipe" x-model="formType"
                                class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none cursor-pointer">
                                <option value="dusun">Dusun (Tingkat 1)</option>
                                <option value="rw">RW (Tingkat 2)</option>
                                <option value="rt">RT (Tingkat 3)</option>
                            </select>
                        </div>

                        {{-- Nama --}}
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Nama / Nomor Wilayah</label>
                            <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Misal: Kaler atau 01"
                                class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none" required>
                        </div>

                        {{-- Parent (dynamic) --}}
                        <div x-show="formType !== 'dusun'" x-collapse>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Induk Wilayah (Parent)</label>
                            <select name="parent_id"
                                class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none cursor-pointer">
                                <option value="">Pilih Induk Wilayah...</option>
                                <template x-if="formType === 'rw'">
                                    <optgroup label="Dusun">
                                        @foreach ($dusunList as $d)
                                            <option value="{{ $d->id }}" {{ old('parent_id') == $d->id ? 'selected' : '' }}>
                                                Dusun {{ $d->nama }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                </template>
                                <template x-if="formType === 'rt'">
                                    <optgroup label="RW">
                                        @foreach ($rwList as $rw)
                                            <option value="{{ $rw->id }}" {{ old('parent_id') == $rw->id ? 'selected' : '' }}>
                                                RW {{ $rw->nama }} (Dusun {{ $rw->parent?->nama }})
                                            </option>
                                        @endforeach
                                    </optgroup>
                                </template>
                            </select>
                            <p class="text-[10px] text-gray-400 mt-1">Pilih wilayah satu tingkat di atasnya.</p>
                        </div>

                        {{-- Kepala Wilayah (Collapsed) --}}
                        <div x-data="{ showKepala: false }" class="border-t border-gray-100 pt-3">
                            <button type="button" @click="showKepala = !showKepala"
                                class="flex items-center justify-between w-full text-left cursor-pointer group">
                                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Data Kepala Wilayah</span>
                                <i class="fa-solid fa-chevron-down text-gray-400 text-[10px] transition-transform duration-200" :class="showKepala && 'rotate-180'"></i>
                            </button>
                            <div x-show="showKepala" x-collapse class="mt-3 space-y-3">
                                <input type="text" name="kepala_nama" value="{{ old('kepala_nama') }}" placeholder="Nama kepala / ketua"
                                    class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none">
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="text" name="kepala_jabatan" value="{{ old('kepala_jabatan') }}" placeholder="Jabatan"
                                        class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none">
                                    <input type="text" name="kepala_telepon" value="{{ old('kepala_telepon') }}" placeholder="No. Telp"
                                        class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none">
                                </div>
                            </div>
                        </div>

                        {{-- GeoJSON Preview --}}
                        <div class="border-t border-gray-100 pt-3">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5 flex justify-between items-center">
                                <span class="flex items-center gap-1.5">
                                    <i class="fa-solid fa-code text-green-500"></i> GeoJSON
                                </span>
                                <span class="text-[9px] bg-gray-100 px-1.5 py-0.5 rounded text-gray-500 font-medium normal-case">Readonly</span>
                            </label>
                            <textarea readonly rows="3"
                                class="w-full border border-gray-200 rounded-lg p-2.5 text-[10px] font-mono bg-gray-50 text-gray-600 focus:outline-none resize-none custom-scrollbar"
                                x-text="geojsonOutput || '{}'"></textarea>
                            <input type="hidden" name="geojson" id="geojsonInput" value="{{ old('geojson') }}">

                            {{-- Status Indicator --}}
                            <div class="mt-2 flex items-center gap-2">
                                <template x-if="hasShape">
                                    <div class="flex items-center gap-1.5 text-[10px] text-green-600 font-semibold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
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
                    </form>
                </div>

                {{-- Footer Action --}}
                <div class="p-4 border-t border-gray-100 bg-gray-50/80 shrink-0 space-y-2">
                    <button type="submit" form="wilayahForm"
                        class="w-full bg-green-700 hover:bg-green-800 text-white py-2.5 rounded-xl text-sm font-bold transition-all shadow-md shadow-green-700/20 cursor-pointer flex items-center justify-center gap-2">
                        <i class="fa-solid fa-save"></i> Simpan Wilayah
                    </button>
                    <button type="button" @click="addModalOpen = false; cancelDraw()"
                        class="w-full bg-white border border-gray-200 text-gray-600 py-2 rounded-xl text-xs font-semibold hover:bg-gray-100 transition-colors cursor-pointer">
                        Batal
                    </button>
                </div>
                </div>
            </div>

            {{-- ═══ Right Floating Tools ═══ --}}
            <div class="absolute top-[72px] right-4 flex flex-col gap-3 z-[500]">

                {{-- Drawing Tools Group --}}
                <div class="bg-white/95 backdrop-blur-md rounded-xl shadow-md border border-gray-200 flex flex-col overflow-hidden w-10">
                    {{-- Draw Polygon --}}
                    <button @click="startDraw()" type="button"
                        class="p-2.5 border-b border-gray-100 transition-all relative group cursor-pointer flex justify-center items-center h-10"
                        :class="drawMode === 'draw' ? 'text-green-600 bg-green-50' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800'"
                        title="Draw Polygon">
                        <i class="fa-solid fa-pen-nib text-[15px]"></i>
                        <div class="absolute right-full top-1/2 -translate-y-1/2 mr-3 bg-gray-800 text-white text-[11px] font-medium px-2.5 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 pointer-events-none whitespace-nowrap transition-opacity shadow-lg">Gambar Poligon Baru</div>
                    </button>
                    {{-- Edit Shape --}}
                    <button @click="drawMode === 'edit' ? finishEdit() : startEdit()" type="button"
                        class="p-2.5 border-b border-gray-100 transition-all relative group cursor-pointer flex justify-center items-center h-10"
                        :class="drawMode === 'edit' ? 'text-blue-600 bg-blue-50' : (hasShape ? 'text-gray-500 hover:bg-gray-50 hover:text-gray-800' : 'text-gray-300 cursor-not-allowed')"
                        :disabled="!hasShape"
                        title="Edit Shape">
                        <i class="fa-solid fa-draw-polygon text-[15px]"></i>
                        <div class="absolute right-full top-1/2 -translate-y-1/2 mr-3 bg-gray-800 text-white text-[11px] font-medium px-2.5 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 pointer-events-none whitespace-nowrap transition-opacity shadow-lg" x-text="drawMode === 'edit' ? 'Selesai Edit' : 'Edit Titik Poligon'"></div>
                    </button>
                    {{-- Delete Shape --}}
                    <button @click="deleteShape()" type="button"
                        class="p-2.5 transition-all relative group cursor-pointer flex justify-center items-center h-10"
                        :class="hasShape ? 'text-red-500 hover:bg-red-50' : 'text-gray-300 cursor-not-allowed'"
                        :disabled="!hasShape"
                        title="Delete">
                        <i class="fa-regular fa-trash-can text-[15px]"></i>
                        <div class="absolute right-full top-1/2 -translate-y-1/2 mr-3 bg-gray-800 text-white text-[11px] font-medium px-2.5 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 pointer-events-none whitespace-nowrap transition-opacity shadow-lg">Hapus Poligon</div>
                    </button>
                </div>

                {{-- Map Controls Group --}}
                <div class="bg-white/95 backdrop-blur-md rounded-xl shadow-md border border-gray-200 flex flex-col overflow-hidden w-10">
                    {{-- Layer toggle --}}
                    <button type="button" class="p-2.5 text-gray-500 hover:bg-gray-50 hover:text-gray-800 border-b border-gray-100 transition cursor-pointer relative group flex justify-center items-center h-10"
                        title="Layers">
                        <i class="fa-solid fa-layer-group text-[14px]"></i>
                        <div class="absolute right-full top-1/2 -translate-y-1/2 mr-3 bg-gray-800 text-white text-[11px] font-medium px-2.5 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 pointer-events-none whitespace-nowrap transition-opacity shadow-lg">Pilihan Layer Peta</div>
                    </button>
                    {{-- Zoom In --}}
                    <button @click="zoomIn()" type="button" class="p-2.5 text-gray-500 hover:bg-gray-50 hover:text-gray-800 border-b border-gray-100 transition cursor-pointer flex justify-center items-center h-10" title="Zoom In">
                        <i class="fa-solid fa-plus text-[15px]"></i>
                    </button>
                    {{-- Zoom Out --}}
                    <button @click="zoomOut()" type="button" class="p-2.5 text-gray-500 hover:bg-gray-50 hover:text-gray-800 transition cursor-pointer flex justify-center items-center h-10" title="Zoom Out">
                        <i class="fa-solid fa-minus text-[15px]"></i>
                    </button>
                </div>
            </div>

            {{-- ═══ Drawing Status Bar (Bottom) ═══ --}}
            <div x-show="isDrawing" x-transition
                 class="absolute bottom-4 left-1/2 -translate-x-1/2 z-[500] bg-gray-900/90 backdrop-blur-md text-white px-5 py-3 rounded-xl shadow-lg flex items-center gap-3">
                <template x-if="drawMode === 'draw'">
                    <div class="flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                        <span class="text-sm font-medium">Klik pada peta untuk menggambar poligon. Klik titik pertama untuk menutup area.</span>
                        <button @click="cancelDraw()" type="button" class="ml-2 text-xs bg-white/10 hover:bg-white/20 px-3 py-1 rounded-lg transition cursor-pointer">Batal</button>
                    </div>
                </template>
                <template x-if="drawMode === 'edit'">
                    <div class="flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                        <span class="text-sm font-medium">Geser titik-titik untuk mengedit area. Klik "Selesai Edit" jika sudah.</span>
                        <button @click="finishEdit()" type="button" class="ml-2 text-xs bg-green-600 hover:bg-green-700 px-3 py-1 rounded-lg transition cursor-pointer">Selesai</button>
                    </div>
                </template>
            </div>

            {{-- ═══ Legend (Top Center) ═══ --}}
            <div class="absolute top-4 left-1/2 -translate-x-1/2 z-[500] bg-white/95 backdrop-blur-md shadow-md border border-gray-200 rounded-full px-4 py-2 flex items-center gap-4 hidden sm:flex">
                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider border-r border-gray-200 pr-4">Wilayah Existing</span>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-1.5">
                        <div class="w-2.5 h-2.5 rounded-full bg-blue-400 border border-blue-600 opacity-80"></div>
                        <span class="text-[10px] font-bold text-gray-700">Dusun</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <div class="w-2.5 h-2.5 rounded-full bg-green-400 border border-green-600 opacity-80"></div>
                        <span class="text-[10px] font-bold text-gray-700">RW</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <div class="w-2.5 h-2.5 rounded-full bg-amber-400 border border-amber-600 opacity-80"></div>
                        <span class="text-[10px] font-bold text-gray-700">RT</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
