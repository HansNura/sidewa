@extends('layouts.backoffice')

@section('title', 'Wilayah Administratif - Panel Administrasi')

@push('styles')
<style>
    .tree-line {
        position: absolute; left: 11px; top: 24px; bottom: 0;
        width: 2px; background-color: #e2e8f0; z-index: 0;
    }
    .tree-item-line {
        position: absolute; left: -13px; top: 15px;
        width: 12px; height: 2px; background-color: #e2e8f0;
    }
    /* Hide Leaflet Draw default toolbar - we use custom buttons */
    .leaflet-draw-toolbar { display: none !important; }
    .leaflet-draw { display: none !important; }
</style>
@endpush

@section('content')

<div x-data="{
        addModalOpen: {{ $errors->any() ? 'true' : 'false' }},
        detailDrawerOpen: false,
        detail: null,
        formType: '{{ old('tipe', 'dusun') }}',

        async openDetail(id) {
            try {
                const res = await fetch(`{{ url('admin/wilayah') }}/${id}`);
                if (!res.ok) throw new Error('Failed');
                this.detail = await res.json();
                this.detailDrawerOpen = true;
            } catch (e) {
                console.error('Failed to load wilayah detail:', e);
            }
        }
     }"
     class="space-y-6">

    {{-- Flash Messages --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="bg-green-50 border border-green-200 text-green-800 rounded-2xl p-4 flex items-start gap-3 shadow-sm">
            <i class="fa-solid fa-circle-check text-green-600 mt-0.5"></i>
            <div class="flex-1"><p class="text-sm font-semibold">{{ session('success') }}</p></div>
            <button @click="show = false" class="text-green-400 hover:text-green-600 cursor-pointer"><i class="fa-solid fa-xmark"></i></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-2xl p-4 shadow-sm">
            <div class="flex items-center gap-2 mb-2">
                <i class="fa-solid fa-triangle-exclamation text-red-500"></i>
                <p class="text-sm font-bold">Terdapat kesalahan:</p>
            </div>
            <ul class="list-disc list-inside text-sm space-y-1 ml-6">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Page Header --}}
    <section class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Wilayah Administratif</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola hierarki batas wilayah desa (Dusun, RW, RT) beserta pemetaannya.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <button @click="addModalOpen = true"
                class="bg-green-700 hover:bg-green-800 text-white shadow-md rounded-xl px-5 py-2.5 text-sm font-bold transition-all flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah Wilayah Baru</span>
            </button>
        </div>
    </section>

    {{-- Grid: Tree View & Map --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @include('pages.backoffice.wilayah._tree')
        @include('pages.backoffice.wilayah._map')
    </div>

    {{-- Data Table --}}
    @include('pages.backoffice.wilayah._table')

    {{-- Modals & Drawers --}}
    @include('pages.backoffice.wilayah._form-modal')
    @include('pages.backoffice.wilayah._detail-drawer')

</div>

@push('scripts')
<script>
// Map features data (shared between index map and editor)
var __mapFeatures = @json($mapFeatures);

// ═══ Alpine Component: Map Editor ═══
document.addEventListener('alpine:init', function() {
    Alpine.data('mapEditor', function() {
        return {
            map: null,
            drawnItems: null,
            currentDrawer: null,
            isDrawing: false,
            geojsonOutput: '',
            drawMode: 'none',

            initMap: function() {
                var self = this;
                // If map already exists, just refresh and reset
                if (self.map) {
                    self.resetState();
                    setTimeout(function() { self.map.invalidateSize(); }, 200);
                    return;
                }

                self.$nextTick(function() {
                    var el = document.getElementById('mapEditor');
                    if (!el || typeof L === 'undefined') return;

                    self.map = L.map('mapEditor', {
                        zoomControl: false,
                        attributionControl: false
                    }).setView([-7.1726, 108.1963], 14);

                    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                        maxZoom: 19,
                        attribution: '© OpenStreetMap'
                    }).addTo(self.map);

                    L.control.zoom({ position: 'bottomright' }).addTo(self.map);

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

                    // Load existing wilayah polygons as reference (dashed)
                    var colors = { dusun: '#2563eb', rw: '#16a34a', rt: '#d97706' };
                    var fills  = { dusun: '#60a5fa', rw: '#4ade80', rt: '#fbbf24' };

                    __mapFeatures.forEach(function(f) {
                        if (f.geojson && f.geojson.coordinates) {
                            try {
                                var refLayer = L.geoJSON(f.geojson, {
                                    style: {
                                        color: colors[f.tipe] || '#666',
                                        fillColor: fills[f.tipe] || '#999',
                                        fillOpacity: 0.2,
                                        weight: 1.5,
                                        dashArray: '4 4'
                                    }
                                }).addTo(self.map);
                                refLayer.bindPopup('<b>' + f.label + '</b>');
                            } catch(err) {
                                console.warn('Invalid GeoJSON for', f.label, err);
                            }
                        }
                    });

                    // Fix map size after modal animation
                    setTimeout(function() { self.map.invalidateSize(); }, 300);
                });
            },

            resetState: function() {
                this.cancelDraw();
                if (this.drawnItems) this.drawnItems.clearLayers();
                this.geojsonOutput = '';
                this.isDrawing = false;
                this.drawMode = 'none';
                var input = document.getElementById('geojsonInput');
                if (input) input.value = '';
                var form = document.getElementById('wilayahForm');
                if (form) form.reset();
            },

            startDraw: function() {
                if (!this.map) return;
                this.cancelDraw();

                this.drawMode = 'draw';
                this.isDrawing = true;

                this.currentDrawer = new L.Draw.Polygon(this.map, {
                    shapeOptions: {
                        color: '#16a34a',
                        fillColor: '#4ade80',
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
                    document.getElementById('geojsonInput').value = '';
                    return;
                }
                var gj = layers[0].toGeoJSON().geometry;
                var str = JSON.stringify(gj);
                this.geojsonOutput = JSON.stringify(gj, null, 2);
                document.getElementById('geojsonInput').value = str;
            },

            zoomIn: function() { if (this.map) this.map.zoomIn(); },
            zoomOut: function() { if (this.map) this.map.zoomOut(); },

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

// ═══ Alpine Component: Map Overview ═══
document.addEventListener('alpine:init', function() {
    Alpine.data('mapOverview', function() {
        return {
            map: null,
            featureGroup: null,
            filterTipe: ['dusun', 'rw', 'rt'],
            filterDusun: '',

            initMap: function() {
                var self = this;
                if (typeof L === 'undefined') return;

                self.map = L.map('mapWilayah', {
                    zoomControl: false,
                    attributionControl: false
                }).setView([-7.1726, 108.1963], 14);

                L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                    maxZoom: 19
                }).addTo(self.map);

                L.control.zoom({ position: 'bottomright' }).addTo(self.map);
                self.featureGroup = L.featureGroup().addTo(self.map);

                self.$watch('filterTipe', function() { self.renderMap(); });
                self.$watch('filterDusun', function() { self.renderMap(); });

                // Render initially after a short delay to ensure container size is correct
                setTimeout(function() {
                    self.map.invalidateSize();
                    self.renderMap();
                }, 200);
            },

            getDescendantIds: function(parentId) {
                var self = this;
                var ids = [parseInt(parentId)];
                var children = __mapFeatures.filter(function(f) { return f.parent_id == parentId; }).map(function(f) { return f.id; });
                children.forEach(function(childId) {
                    ids = ids.concat(self.getDescendantIds(childId));
                });
                return ids;
            },

            renderMap: function() {
                var self = this;
                self.featureGroup.clearLayers();

                var colors = { dusun: '#2563eb', rw: '#16a34a', rt: '#d97706' };
                var fills  = { dusun: '#60a5fa', rw: '#4ade80', rt: '#fbbf24' };

                var allowedIds = null;
                if (self.filterDusun) {
                    allowedIds = self.getDescendantIds(self.filterDusun);
                }

                var hasFeatures = false;

                __mapFeatures.forEach(function(f) {
                    // Check Type Filter
                    if (!self.filterTipe.includes(f.tipe)) return;

                    // Check Dusun Filter
                    if (allowedIds && !allowedIds.includes(f.id)) return;

                    if (f.geojson && f.geojson.coordinates) {
                        hasFeatures = true;
                        try {
                            var layer = L.geoJSON(f.geojson, {
                                style: {
                                    color: colors[f.tipe] || '#666',
                                    fillColor: fills[f.tipe] || '#999',
                                    fillOpacity: f.tipe === 'dusun' ? 0.2 : (f.tipe === 'rw' ? 0.3 : 0.4),
                                    weight: f.tipe === 'dusun' ? 3 : (f.tipe === 'rw' ? 2 : 1.5),
                                    dashArray: f.tipe === 'dusun' ? '' : (f.tipe === 'rw' ? '4 4' : '2 4')
                                }
                            });
                            
                            layer.on('click', function() {
                                if (typeof self.openDetail === 'function') {
                                    self.openDetail(f.id);
                                }
                            });
                            
                            layer.bindTooltip('<b>' + f.label + '</b>');
                            self.featureGroup.addLayer(layer);
                        } catch(e) {
                            console.warn('Invalid GeoJSON for', f.label, e);
                        }
                    }
                });

                if (hasFeatures) {
                    try {
                        self.map.fitBounds(self.featureGroup.getBounds(), { padding: [20, 20] });
                    } catch (e) {}
                }

                // Fallback dummy polygons (only if NO features in DB at all)
                if (__mapFeatures.length === 0) {
                    var kalerCoords = [[-7.170, 108.190], [-7.165, 108.198], [-7.172, 108.200], [-7.175, 108.195]];
                    var kidulCoords = [[-7.175, 108.195], [-7.172, 108.200], [-7.180, 108.205], [-7.185, 108.195]];
                    var tengahCoords = [[-7.168, 108.183], [-7.165, 108.190], [-7.170, 108.192], [-7.173, 108.186]];

                    L.polygon(kalerCoords, { color: '#2563eb', fillColor: '#60a5fa', fillOpacity: 0.4, weight: 2 }).addTo(self.featureGroup).bindPopup('<b>Dusun Kaler</b>');
                    L.polygon(kidulCoords, { color: '#16a34a', fillColor: '#4ade80', fillOpacity: 0.4, weight: 2 }).addTo(self.featureGroup).bindPopup('<b>Dusun Kidul</b>');
                    L.polygon(tengahCoords, { color: '#d97706', fillColor: '#fbbf24', fillOpacity: 0.4, weight: 2 }).addTo(self.featureGroup).bindPopup('<b>Dusun Tengah</b>');
                }
            }
        };
    });
});
</script>
@endpush

@endsection

