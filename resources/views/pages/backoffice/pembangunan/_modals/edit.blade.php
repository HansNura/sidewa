<div x-show="editModalOpen" class="fixed inset-0 z-[110] !m-0" x-cloak x-data="pembangunanEditMapEditor()" x-init="$watch('editModalOpen', v => { if (v) { $nextTick(() => initMap(proyekToEdit)); } else { destroyMap(); } })">

    {{-- Backdrop --}}
    <div x-show="editModalOpen" x-transition.opacity class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"
        @click="editModalOpen = false"></div>

    {{-- Full-screen Map Container --}}
    <div x-show="editModalOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-4 sm:inset-6 bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col z-10">

        {{-- Map fills the entire container --}}
        <div class="relative flex-1 bg-gray-100">
            <div id="mapPembangunanEditEditor" class="absolute inset-0 z-0"></div>

            {{-- Close Button (Top Right) --}}
            <button @click="editModalOpen = false"
                class="absolute top-4 right-4 z-[500] w-10 h-10 bg-white/95 backdrop-blur-md rounded-xl shadow-lg border border-gray-200 flex items-center justify-center text-gray-500 hover:text-red-500 hover:bg-red-50 hover:border-red-200 transition-all cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            {{-- ═══ Left Floating Panel Toggle & Container ═══ --}}
            <div x-data="{ showForm: true }"
                class="absolute top-4 left-4 z-[500] flex flex-col gap-3 max-h-[calc(100%-32px)] w-full max-w-[360px] pointer-events-none">

                {{-- Toggle Icon Button (Visible when collapsed) --}}
                <button x-show="!showForm" @click="showForm = true" x-transition.opacity
                    class="w-12 h-12 bg-gradient-to-r from-gray-900 to-gray-800 hover:from-gray-800 hover:to-gray-700 shadow-xl border border-gray-700 rounded-2xl flex items-center justify-center cursor-pointer transition-all pointer-events-auto">
                    <i class="fa-solid fa-pen-to-square text-emerald-400 text-xl"></i>
                </button>

                {{-- Full Panel --}}
                <div x-show="showForm" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 -translate-x-4"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 -translate-x-4"
                    class="w-full bg-white/95 backdrop-blur-md rounded-2xl shadow-xl border border-gray-100 flex flex-col overflow-hidden max-h-full pointer-events-auto">

                    {{-- Panel Header --}}
                    <div
                        class="bg-gradient-to-r from-gray-900 to-gray-800 text-white p-4 shrink-0 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-white/10 border border-white/10 flex items-center justify-center shadow-inner shrink-0">
                                <i class="fa-solid fa-pen-to-square text-emerald-400 text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-[13px] tracking-wide text-white">Edit Data Master</h3>
                                <p class="text-gray-400 text-[10px] mt-0.5 leading-snug">Koreksi informasi proyek
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
                        <form id="pembangunanEditForm" method="POST"
                            :action="`/admin/pembangunan/data/${proyekToEdit?.id}/master`" class="space-y-4">
                            @csrf

                            {{-- Nama Proyek --}}
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Nama
                                    Proyek <span class="text-red-500">*</span></label>
                                <input type="text" name="nama_proyek" required x-model="formData.nama_proyek"
                                    placeholder="Misal: Pembangunan Sumur Bor..."
                                    class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 outline-none transition-colors">
                            </div>

                            {{-- Deskripsi --}}
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Deskripsi
                                    & Volume</label>
                                <textarea name="deskripsi" rows="2" placeholder="Detail fisik (panjang, luas, unit)..."
                                    x-model="formData.deskripsi"
                                    class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 outline-none resize-none transition-colors"></textarea>
                            </div>

                            {{-- Link APBDes --}}
                            <div class="p-3 bg-emerald-50 border border-emerald-100 rounded-xl">
                                <label
                                    class="block text-[10px] font-bold text-emerald-800 uppercase tracking-widest mb-1.5"><i
                                        class="fa-solid fa-link"></i> Link ke APBDes</label>
                                <select name="apbdes_id" x-model="formData.apbdes_id"
                                    class="w-full bg-white border border-emerald-200 rounded-lg px-3 py-2 text-xs focus:ring-2 focus:ring-emerald-500 outline-none text-gray-700 font-semibold cursor-pointer transition-colors">
                                    <option value="">-- Pagu Mandiri / Tanpa Link --</option>
                                    @foreach ($apbdesOptions as $ap)
                                        <option value="{{ $ap->id }}">{{ $ap->kode_rekening }} -
                                            {{ $ap->nama_kegiatan }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Kategori --}}
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Kategori
                                    <span class="text-red-500">*</span></label>
                                <select name="kategori" required x-model="formData.kategori"
                                    class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-xs focus:ring-2 focus:ring-emerald-500 outline-none cursor-pointer transition-colors">
                                    <option value="Infrastruktur Jalan">Infrastruktur Jalan</option>
                                    <option value="Fasilitas Umum">Fasilitas Umum</option>
                                    <option value="Irigasi/Pertanian">Irigasi/Pertanian</option>
                                    <option value="Sanitasi/Air Bersih">Sanitasi</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>

                            {{-- Tanggal --}}
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label
                                        class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Tgl
                                        Mulai</label>
                                    <input type="date" name="tanggal_mulai" x-model="formData.tanggal_mulai"
                                        class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-xs focus:ring-2 focus:ring-emerald-500 outline-none transition-colors">
                                </div>
                                <div>
                                    <label
                                        class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Target
                                        Selesai</label>
                                    <input type="date" name="target_selesai" x-model="formData.target_selesai"
                                        class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-xs focus:ring-2 focus:ring-emerald-500 outline-none transition-colors">
                                </div>
                            </div>

                            {{-- Lokasi Administrasi --}}
                            <div class="border-t border-gray-100 pt-3">
                                <label
                                    class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5 flex justify-between items-center">
                                    <span>Lokasi Proyek</span>
                                </label>

                                <input type="hidden" name="lokasi_dusun" :value="selectedDusun">
                                <input type="hidden" name="rt_rw"
                                    :value="(selectedRt ? selectedRt : '-') + ' / ' + (selectedRw ? selectedRw : '-')">

                                <div class="grid grid-cols-3 gap-2">
                                    <div class="col-span-1">
                                        <select x-model="selectedDusun" @change="selectedRw = ''; selectedRt = ''"
                                            class="w-full bg-white border border-gray-200 rounded-lg px-2 py-2 text-xs focus:ring-2 focus:ring-emerald-500 outline-none cursor-pointer">
                                            <option value="">-- Dusun --</option>
                                            <template x-for="dusun in wilayahTree" :key="dusun.id">
                                                <option :value="dusun.nama" x-text="dusun.nama"
                                                    :selected="dusun.nama === selectedDusun"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <div class="col-span-1">
                                        <select x-model="selectedRw" @change="selectedRt = ''"
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
                                        <select x-model="selectedRt" :disabled="!selectedRw"
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

                            {{-- Koordinat --}}
                            <div class="border-t border-gray-100 pt-3">
                                <label
                                    class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5 flex justify-between items-center">
                                    <span>Titik Koordinat <span class="text-red-500">*</span></span>
                                    <span
                                        class="text-[9px] bg-emerald-100 text-emerald-600 px-1.5 py-0.5 rounded font-bold normal-case">Klik
                                        Peta</span>
                                </label>
                                <div class="grid grid-cols-2 gap-3">
                                    <input type="text" name="latitude" x-model="lat" required
                                        placeholder="-7.1726" readonly
                                        class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-xs outline-none cursor-not-allowed text-gray-600">
                                    <input type="text" name="longitude" x-model="lng" required
                                        placeholder="108.1963" readonly
                                        class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-xs outline-none cursor-not-allowed text-gray-600">
                                </div>
                            </div>

                        </form>
                    </div>

                    {{-- Footer Action --}}
                    <div class="p-4 border-t border-gray-100 bg-gray-50/80 shrink-0 space-y-2">
                        <button type="submit" form="pembangunanEditForm"
                            class="w-full bg-emerald-700 hover:bg-emerald-800 text-white py-2.5 rounded-xl text-sm font-bold transition-all shadow-md shadow-emerald-700/20 cursor-pointer flex items-center justify-center gap-2">
                            <i class="fa-solid fa-save"></i> Perbarui Data Master
                        </button>
                        <button type="button" @click="editModalOpen = false"
                            class="w-full bg-white border border-gray-200 text-gray-600 py-2 rounded-xl text-xs font-semibold hover:bg-gray-100 transition-colors cursor-pointer">
                            Batal
                        </button>
                    </div>
                </div>
            </div>

            {{-- ═══ Right Floating Tools ═══ --}}
            <div class="absolute top-[72px] right-4 flex flex-col gap-3 z-[500]">
                {{-- Map Controls Group --}}
                <div
                    class="bg-white/95 backdrop-blur-md rounded-xl shadow-md border border-gray-200 flex flex-col overflow-hidden w-10">
                    {{-- Layer toggle --}}
                    <button type="button"
                        class="p-2.5 text-gray-500 hover:bg-gray-50 hover:text-gray-800 border-b border-gray-100 transition cursor-pointer relative group flex justify-center items-center h-10"
                        title="Layers">
                        <i class="fa-solid fa-layer-group text-[14px]"></i>
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
            <div
                class="absolute bottom-4 left-1/2 -translate-x-1/2 z-[500] bg-gray-900/90 backdrop-blur-md text-white px-5 py-3 rounded-xl shadow-lg flex items-center gap-3">
                <div class="flex items-center gap-3">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-sm font-medium">Anda sedang mengedit lokasi proyek. Klik pada peta untuk
                        memindahkan titik.</span>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('pembangunanEditMapEditor', () => ({
                mapEditor: null,
                marker: null,
                lat: '',
                lng: '',
                hasMarker: false,

                formData: {
                    nama_proyek: '',
                    deskripsi: '',
                    apbdes_id: '',
                    kategori: '',
                    status: '',
                    tanggal_mulai: '',
                    target_selesai: ''
                },

                wilayahTree: @json($wilayahTree ?? []),
                selectedDusun: '',
                selectedRw: '',
                selectedRt: '',

                get availableRws() {
                    if (!this.selectedDusun) return [];
                    const dusun = this.wilayahTree.find(d => d.nama === this.selectedDusun);
                    return dusun ? dusun.children_recursive : [];
                },
                get availableRts() {
                    if (!this.selectedRw) return [];
                    const rw = this.availableRws.find(r => r.nama === this.selectedRw);
                    return rw ? rw.children_recursive : [];
                },

                initMap(proyek) {
                    if (!proyek) return;

                    // Fill Form Data
                    this.formData.nama_proyek = proyek.nama_proyek;
                    this.formData.deskripsi = proyek.deskripsi || '';
                    this.formData.apbdes_id = proyek.apbdes_id || '';
                    this.formData.kategori = proyek.kategori;
                    this.formData.status = proyek.status;

                    // Dates parsing (assuming YYYY-MM-DD from server)
                    if (proyek.tanggal_mulai) {
                        this.formData.tanggal_mulai = proyek.tanggal_mulai.split('T')[0];
                    }
                    if (proyek.target_selesai) {
                        this.formData.target_selesai = proyek.target_selesai.split('T')[0];
                    }

                    // Wilayah Parsing
                    this.selectedDusun = proyek.lokasi_dusun || '';
                    if (proyek.rt_rw) {
                        const parts = proyek.rt_rw.split(' / ');
                        this.selectedRt = parts[0] !== '-' ? parts[0] : '';
                        this.selectedRw = (parts[1] && parts[1] !== '-') ? parts[1] : '';
                    }

                    // Map Coordinates
                    this.lat = proyek.latitude || '';
                    this.lng = proyek.longitude || '';
                    this.hasMarker = !!(this.lat && this.lng);

                    if (this.mapEditor) {
                        this.mapEditor.remove();
                        this.mapEditor = null;
                    }

                    // Initialize map
                    this.mapEditor = L.map('mapPembangunanEditEditor', {
                        zoomControl: false,
                        attributionControl: false
                    }).setView([this.lat || -7.1726, this.lng || 108.1963], 15);

                    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                        maxZoom: 19
                    }).addTo(this.mapEditor);

                    // Add existing marker
                    if (this.hasMarker) {
                        var iconLoc = L.divIcon({
                            className: 'custom-icon',
                            html: '<div style="background-color:#10b981; width:16px; height:16px; border-radius:50%; border:2px solid white; box-shadow:0 2px 5px rgba(0,0,0,0.3);"></div>'
                        });
                        this.marker = L.marker([this.lat, this.lng], {
                            icon: iconLoc
                        }).addTo(this.mapEditor);
                    }

                    // Map click event to move marker
                    this.mapEditor.on('click', (e) => {
                        this.lat = e.latlng.lat.toFixed(6);
                        this.lng = e.latlng.lng.toFixed(6);
                        this.hasMarker = true;

                        if (this.marker) {
                            this.marker.setLatLng(e.latlng);
                        } else {
                            var iconLoc = L.divIcon({
                                className: 'custom-icon',
                                html: '<div style="background-color:#10b981; width:16px; height:16px; border-radius:50%; border:2px solid white; box-shadow:0 2px 5px rgba(0,0,0,0.3);"></div>'
                            });
                            this.marker = L.marker(e.latlng, {
                                icon: iconLoc
                            }).addTo(this.mapEditor);
                        }
                    });

                    // Fix map size on modal open
                    setTimeout(() => {
                        this.mapEditor.invalidateSize();
                    }, 400);
                },

                destroyMap() {
                    if (this.mapEditor) {
                        this.mapEditor.remove();
                        this.mapEditor = null;
                        this.marker = null;
                        this.lat = '';
                        this.lng = '';
                        this.hasMarker = false;
                    }
                    this.selectedDusun = '';
                    this.selectedRw = '';
                    this.selectedRt = '';
                },

                zoomIn() {
                    if (this.mapEditor) this.mapEditor.zoomIn();
                },
                zoomOut() {
                    if (this.mapEditor) this.mapEditor.zoomOut();
                }
            }));
        });
    </script>
@endpush
