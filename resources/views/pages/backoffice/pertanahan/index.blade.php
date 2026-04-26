@extends('layouts.backoffice')

@section('title', 'Data Pertanahan Desa - Panel Administrasi')

@push('styles')
    <style>
        /* Hide Leaflet Draw default toolbar - we use custom buttons in the modal */
        .leaflet-draw-toolbar {
            display: none !important;
        }

        .leaflet-draw {
            display: none !important;
        }
    </style>
@endpush

@section('content')

    <script>
        window.__wilayahTree = @json($wilayahTree);
    </script>
    <div x-data="{
        addModalOpen: {{ $errors->any() ? 'true' : 'false' }},
        detailDrawerOpen: false,
        detail: null,
        miniMap: null,
        selectedPenduduk: null,
        searchResults: [],
        kepemilikanField: '{{ old('kepemilikan', 'warga') }}',

        wilayahTree: window.__wilayahTree,
        selectedDusun: '{{ old('dusun') }}',
        selectedRw: '{{ old('rw') }}',
        selectedRt: '{{ old('rt') }}',

        get availableRws() {
            if (!this.selectedDusun) return [];
            const dusun = this.wilayahTree.find(d => d.nama === this.selectedDusun);
            return dusun ? (dusun.children_recursive || []) : [];
        },

        get availableRts() {
            if (!this.selectedRw) return [];
            const rws = this.availableRws;
            const rw = rws.find(r => r.nama === this.selectedRw);
            return rw ? (rw.children_recursive || []) : [];
        },

        async openDetail(id) {
            try {
                const res = await fetch(`{{ url('admin/pertanahan') }}/${id}`);
                if (!res.ok) throw new Error('Failed');
                this.detail = await res.json();
                this.detailDrawerOpen = true;

                this.$nextTick(() => this.initMiniMap());
            } catch (e) {
                console.error('Failed to load detail:', e);
            }
        },

        initMiniMap() {
            const el = document.getElementById('miniMapDetail');
            if (!el || !this.detail?.geojson) return;

            if (this.miniMap) {
                this.miniMap.remove();
                this.miniMap = null;
            }

            this.miniMap = L.map(el, { zoomControl: false, attributionControl: false, dragging: false, scrollWheelZoom: false });
            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { maxZoom: 19 }).addTo(this.miniMap);

            const geo = this.detail.geojson;
            const layer = L.geoJSON(geo, {
                style: { color: this.detail.borderColor, fillColor: this.detail.color, fillOpacity: 0.6, weight: 2 }
            }).addTo(this.miniMap);
            this.miniMap.fitBounds(layer.getBounds(), { padding: [20, 20] });
        },

        browseModalOpen: false,
        isBrowsing: false,
        browseData: [],
        browseFilters: { q: '' },

        openBrowseModal() {
            this.browseModalOpen = true;
            if (this.browseData.length === 0) {
                this.fetchBrowseData();
            }
        },

        async fetchBrowseData() {
            this.isBrowsing = true;
            try {
                const res = await fetch(`{{ route('admin.pertanahan.search-penduduk') }}?q=${encodeURIComponent(this.browseFilters.q)}`);
                if (!res.ok) throw new Error('Failed to fetch');
                this.browseData = await res.json();
            } catch (e) {
                this.browseData = [];
                console.error('Error fetching data:', e);
            } finally {
                this.isBrowsing = false;
            }
        },

        selectFromBrowse(item) {
            this.selectedPenduduk = item;
            this.selectedDusun = item.dusun || '';
            this.selectedRw = item.rw || '';
            this.selectedRt = item.rt || '';
            this.browseModalOpen = false;
        }
    }" class="space-y-6">

        {{-- Flash Messages --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="bg-green-50 border border-green-200 text-green-800 rounded-2xl p-4 flex items-start gap-3 shadow-sm">
                <i class="fa-solid fa-circle-check text-green-600 mt-0.5"></i>
                <div class="flex-1">
                    <p class="text-sm font-semibold">{{ session('success') }}</p>
                </div>
                <button @click="show = false" class="text-green-400 hover:text-green-600 cursor-pointer"><i
                        class="fa-solid fa-xmark"></i></button>
            </div>
        @endif

        {{-- Page Header --}}
        @include('pages.backoffice.pertanahan._header')

        {{-- KPI Stats --}}
        @include('pages.backoffice.pertanahan._kpi')

        {{-- Map Section --}}
        @include('pages.backoffice.pertanahan._map')

        {{-- Filter + Table --}}
        @include('pages.backoffice.pertanahan._table')

        {{-- Modals & Drawers --}}
        @include('pages.backoffice.pertanahan._form-modal')
        @include('pages.backoffice.pertanahan._detail-drawer')

    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof L === 'undefined') return;

            const mapMain = L.map('mapPertanahan', {
                zoomControl: false,
                attributionControl: false
            }).setView([-6.8211, 107.6312], 15);

            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                maxZoom: 19
            }).addTo(mapMain);
            L.control.zoom({
                position: 'bottomright'
            }).addTo(mapMain);

            const mapData = @json($mapData);
            const layers = {
                all: [],
                desa: [],
                warga: [],
                fasum: []
            };

            mapData.forEach(item => {
                if (!item.geojson) return;

                const layer = L.geoJSON(item.geojson, {
                    style: {
                        color: item.borderColor,
                        fillColor: item.color,
                        fillOpacity: 0.6,
                        weight: 2
                    }
                }).bindPopup(
                    `<b>${item.pemilik}</b><br>Luas: ${item.luas} m²<br><span style="font-size:10px;color:#888">${item.kode_lahan}</span>`
                );

                layer.addTo(mapMain);
                layers.all.push(layer);
                layers[item.kepemilikan]?.push(layer);
            });

            // Fit bounds if data exists
            if (layers.all.length > 0) {
                const group = L.featureGroup(layers.all);
                mapMain.fitBounds(group.getBounds(), {
                    padding: [30, 30]
                });
            }

            // Layer toggle via Alpine
            window.toggleMapLayer = function(type) {
                layers.all.forEach(l => mapMain.removeLayer(l));
                const target = type === 'all' ? layers.all : (layers[type] || []);
                target.forEach(l => l.addTo(mapMain));
            };
        });

        // ═══ Alpine Component: Map Editor (Pertanahan) ═══
        document.addEventListener('alpine:init', function() {
            Alpine.data('pertanahanMapEditor', function() {
                return {
                    map: null,
                    drawnItems: null,
                    currentDrawer: null,
                    isDrawing: false,
                    geojsonOutput: '',
                    calculatedArea: '{{ old('luas', '') }}',
                    drawMode: 'none',

                    initMap: function() {
                        var self = this;
                        // If map already exists, just refresh and reset
                        if (self.map) {
                            self.resetState();
                            setTimeout(function() {
                                self.map.invalidateSize();
                            }, 200);
                            return;
                        }

                        self.$nextTick(function() {
                            var el = document.getElementById('mapPertanahanEditor');
                            if (!el || typeof L === 'undefined') return;

                            self.map = L.map('mapPertanahanEditor', {
                                zoomControl: false,
                                attributionControl: false
                            }).setView([-6.8211, 107.6312], 15); // Adjust coordinate

                            L.tileLayer(
                                'https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                                    maxZoom: 19,
                                    attribution: '© OpenStreetMap'
                                }).addTo(self.map);

                            // Drawn items layer
                            self.drawnItems = new L.FeatureGroup();
                            self.map.addLayer(self.drawnItems);

                            // Bind draw:created event ONCE
                            self.map.on(L.Draw.Event.CREATED, function(e) {
                                self.drawnItems.clearLayers();
                                self.drawnItems.addLayer(e.layer);
                                self.updateGeoJSON();
                                self.isDrawing = false;
                                self.drawMode = 'none';
                            });

                            // Load existing polygons as reference (dashed)
                            var mapData = @json($mapData);
                            mapData.forEach(function(f) {
                                if (f.geojson) {
                                    try {
                                        var refLayer = L.geoJSON(f.geojson, {
                                            style: {
                                                color: f.borderColor || '#666',
                                                fillColor: f.color || '#999',
                                                fillOpacity: 0.2,
                                                weight: 1.5,
                                                dashArray: '4 4'
                                            }
                                        }).addTo(self.map);
                                        refLayer.bindPopup('<b>' + (f.pemilik ||
                                            'Lahan') + '</b>');
                                    } catch (err) {
                                        console.warn('Invalid GeoJSON', err);
                                    }
                                }
                            });

                            // Fix map size after modal animation
                            setTimeout(function() {
                                self.map.invalidateSize();
                            }, 300);
                        });
                    },

                    resetState: function() {
                        this.cancelDraw();
                        if (this.drawnItems) this.drawnItems.clearLayers();
                        this.geojsonOutput = '';
                        this.calculatedArea = '';
                        this.isDrawing = false;
                        this.drawMode = 'none';
                        var input = document.getElementById('geojsonInput');
                        if (input) input.value = '';
                        var form = document.getElementById('pertanahanForm');
                        if (form) form.reset();
                    },

                    startDraw: function() {
                        if (!this.map) return;
                        this.cancelDraw();

                        this.drawMode = 'draw';
                        this.isDrawing = true;

                        this.currentDrawer = new L.Draw.Polygon(this.map, {
                            shapeOptions: {
                                color: '#10b981', // emerald-500
                                fillColor: '#34d399', // emerald-400
                                fillOpacity: 0.4,
                                weight: 3
                            },
                            showArea: true,
                            allowIntersection: false,
                        });
                        this.currentDrawer.enable();
                    },

                    startEdit: function() {
                        if (!this.map || this.drawnItems.getLayers().length === 0) return;
                        this.cancelDraw();

                        this.drawMode = 'edit';
                        this.isDrawing = true;

                        this.drawnItems.eachLayer(function(layer) {
                            if (layer.editing) layer.editing.enable();
                        });
                    },

                    finishEdit: function() {
                        this.drawnItems.eachLayer(function(layer) {
                            if (layer.editing) layer.editing.disable();
                        });
                        this.updateGeoJSON();
                        this.isDrawing = false;
                        this.drawMode = 'none';
                    },

                    deleteShape: function() {
                        this.cancelDraw();
                        this.drawnItems.clearLayers();
                        this.geojsonOutput = '';
                        this.calculatedArea = '';
                        document.getElementById('geojsonInput').value = '';
                    },

                    cancelDraw: function() {
                        if (this.currentDrawer) {
                            this.currentDrawer.disable();
                            this.currentDrawer = null;
                        }
                        if (this.drawMode === 'edit') {
                            this.drawnItems.eachLayer(function(layer) {
                                if (layer.editing) layer.editing.disable();
                            });
                        }
                        this.isDrawing = false;
                        this.drawMode = 'none';
                    },

                    updateGeoJSON: function() {
                        var layers = this.drawnItems.getLayers();
                        if (layers.length === 0) {
                            this.geojsonOutput = '';
                            this.calculatedArea = '';
                            document.getElementById('geojsonInput').value = '';
                            return;
                        }
                        var gj = layers[0].toGeoJSON().geometry;
                        var str = JSON.stringify(gj);
                        this.geojsonOutput = JSON.stringify(gj, null, 2);
                        document.getElementById('geojsonInput').value = str;

                        try {
                            var latlngs = layers[0].getLatLngs()[0];
                            if (latlngs && L.GeometryUtil && L.GeometryUtil.geodesicArea) {
                                var area = L.GeometryUtil.geodesicArea(latlngs);
                                this.calculatedArea = Math.round(area);
                            }
                        } catch (e) {
                            console.warn('Could not calculate area', e);
                        }
                    },

                    zoomIn: function() {
                        if (this.map) this.map.zoomIn();
                    },
                    zoomOut: function() {
                        if (this.map) this.map.zoomOut();
                    },

                    get hasShape() {
                        return this.drawnItems && this.drawnItems.getLayers().length > 0;
                    },
                    get pointCount() {
                        if (!this.drawnItems) return 0;
                        var layers = this.drawnItems.getLayers();
                        if (layers.length === 0) return 0;
                        var latlngs = layers[0].getLatLngs();
                        return latlngs[0] ? latlngs[0].length : 0;
                    }
                };
            });
        });
    </script>
@endpush
