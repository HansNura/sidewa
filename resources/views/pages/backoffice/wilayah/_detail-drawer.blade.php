{{-- Detail Wilayah Drawer --}}
<div x-show="detailDrawerOpen" class="fixed inset-0 z-[100] flex justify-end" x-cloak>
    <div x-show="detailDrawerOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
        @click="detailDrawerOpen = false"></div>

    <div x-show="detailDrawerOpen" x-transition:enter="transition ease-transform duration-300"
        x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-transform duration-300" x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="relative bg-white w-full max-w-md h-full shadow-2xl flex flex-col border-l border-gray-200">

        {{-- Header --}}
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-start bg-gray-50/50 shrink-0">
            <div>
                <span
                    class="text-[10px] font-bold text-amber-600 bg-amber-100 px-2 py-0.5 rounded uppercase tracking-wider mb-1 inline-block"
                    x-text="'Profil Wilayah — ' + (detail?.tipe ?? '')"></span>
                <h3 class="font-extrabold text-xl text-gray-900" x-text="detail?.label ?? ''"></h3>
            </div>
            <button @click="detailDrawerOpen = false"
                class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 -mr-2 cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        {{-- Body --}}
        <div class="p-6 overflow-y-auto custom-scrollbar flex-1 space-y-6" x-show="detail">

            {{-- Leader Info --}}
            <template x-if="detail?.kepala_nama">
                <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm flex items-center gap-4">
                    <div
                        class="w-12 h-12 rounded-full bg-gray-200 overflow-hidden shrink-0 border-2 border-white shadow-sm flex items-center justify-center bg-amber-100 text-amber-700">
                        <i class="fa-solid fa-user-tie text-lg"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-0.5">Kepala Wilayah
                        </p>
                        <h4 class="font-bold text-gray-900 leading-tight" x-text="detail.kepala_nama"></h4>
                        <p class="text-xs text-gray-600 mt-0.5" x-show="detail.kepala_jabatan"
                            x-text="detail.kepala_jabatan"></p>
                        <p class="text-xs text-gray-500 mt-1" x-show="detail.kepala_telepon">
                            <i class="fa-solid fa-phone text-gray-400 mr-1"></i>
                            <span x-text="detail.kepala_telepon"></span>
                        </p>
                    </div>
                </div>
            </template>

            {{-- Stats --}}
            <div>
                <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Statistik Demografi</h5>
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-blue-50 border border-blue-100 p-3 rounded-xl text-center">
                        <p class="text-xs text-blue-600 font-bold mb-1">Total Penduduk</p>
                        <h3 class="text-xl font-extrabold text-blue-800"
                            x-text="detail?.populasi?.toLocaleString() ?? '0'"></h3>
                    </div>
                    <div class="bg-green-50 border border-green-100 p-3 rounded-xl text-center">
                        <p class="text-xs text-green-600 font-bold mb-1">Total KK</p>
                        <h3 class="text-xl font-extrabold text-green-800"
                            x-text="detail?.jumlah_kk?.toLocaleString() ?? '0'"></h3>
                    </div>
                </div>
            </div>

            {{-- Map Preview --}}
            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm flex flex-col"
                x-data="{
                    drawerMap: null,
                    featureGroup: null,
                    initDrawerMap() {
                        if (this.drawerMap) return;
                        this.drawerMap = L.map($refs.drawerMapContainer, {
                            zoomControl: false,
                            attributionControl: false
                        }).setView([-7.1726, 108.1963], 14);

                        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                            maxZoom: 19
                        }).addTo(this.drawerMap);

                        this.featureGroup = L.featureGroup().addTo(this.drawerMap);
                    },
                    updateDrawerMap() {
                        if (!this.detail || !this.detail.descendant_ids) return;
                        if (!this.drawerMap) this.initDrawerMap();

                        this.featureGroup.clearLayers();

                        let hasLayers = false;
                        const colors = { dusun: '#2563eb', rw: '#16a34a', rt: '#d97706' };
                        const fills = { dusun: '#60a5fa', rw: '#4ade80', rt: '#fbbf24' };

                        if (typeof __mapFeatures !== 'undefined') {
                            __mapFeatures.forEach(f => {
                                if (this.detail.descendant_ids.includes(f.id) && f.geojson && f.geojson.coordinates) {
                                    try {
                                        const isMain = f.id === this.detail.id;
                                        const layer = L.geoJSON(f.geojson, {
                                            style: {
                                                color: colors[f.tipe] || '#666',
                                                fillColor: fills[f.tipe] || '#999',
                                                fillOpacity: isMain ? 0.5 : 0.2,
                                                weight: isMain ? 2.5 : 1.5,
                                                dashArray: isMain ? '' : '4 4'
                                            }
                                        });
                                        layer.bindTooltip('<b>' + f.label + '</b>');
                                        this.featureGroup.addLayer(layer);
                                        hasLayers = true;
                                    } catch (e) {}
                                }
                            });
                        }

                        if (hasLayers) {
                            this.drawerMap.fitBounds(this.featureGroup.getBounds(), { padding: [10, 10] });
                        }

                        setTimeout(() => { if (this.drawerMap) this.drawerMap.invalidateSize(); }, 300);
                    }
                }" x-init="$watch('detail', () => {
                    if (detailDrawerOpen) updateDrawerMap();
                });
                $watch('detailDrawerOpen', (open) => {
                    if (open) {
                        setTimeout(() => {
                            if (drawerMap) drawerMap.invalidateSize();
                            updateDrawerMap();
                        }, 300);
                    }
                });">
                <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Peta Wilayah</h5>
                </div>
                <div x-ref="drawerMapContainer" class="h-48 w-full bg-gray-100 z-10 relative"></div>
            </div>

            {{-- Penduduk Mini List --}}
            <div>
                <div class="flex justify-between items-center mb-3">
                    <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Penduduk Terdaftar</h5>
                    <a href="{{ route('admin.penduduk.index') }}"
                        class="text-[10px] font-bold text-green-600 bg-green-50 hover:bg-green-100 px-2 py-1 rounded transition-colors border border-green-100">
                        Lihat Semua
                    </a>
                </div>
                <div
                    class="bg-white border border-gray-100 rounded-xl overflow-hidden shadow-sm divide-y divide-gray-50">
                    <template x-for="p in (detail?.penduduk ?? [])" :key="p.id">
                        <div class="p-3 flex justify-between items-center hover:bg-gray-50">
                            <div>
                                <p class="text-sm font-bold text-gray-800" x-text="p.nama"></p>
                                <p class="text-[10px] text-gray-500 font-mono" x-text="p.nik"></p>
                            </div>
                            <span class="text-[10px] bg-gray-100 text-gray-600 px-2 py-0.5 rounded font-semibold"
                                x-text="'RT ' + (p.rt ?? '-')"></span>
                        </div>
                    </template>
                    <template x-if="!detail?.penduduk?.length">
                        <div class="p-4 text-center text-gray-400 text-xs">
                            Belum ada penduduk terdaftar.
                        </div>
                    </template>
                </div>
            </div>

            {{-- Parent Path --}}
            <div class="bg-gray-50 border border-gray-100 rounded-xl p-3">
                <p class="text-[10px] font-bold text-gray-500 uppercase mb-1">Induk Wilayah</p>
                <p class="text-sm font-medium text-gray-700" x-text="detail?.parent_path ?? '-'"></p>
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
