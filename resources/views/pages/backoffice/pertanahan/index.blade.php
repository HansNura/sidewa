@extends('layouts.backoffice')

@section('title', 'Data Pertanahan Desa - Panel Administrasi')

@section('content')

<div x-data="{
        addModalOpen: {{ $errors->any() ? 'true' : 'false' }},
        detailDrawerOpen: false,
        detail: null,
        miniMap: null,
        selectedPenduduk: null,
        searchResults: [],
        kepemilikanField: '{{ old('kepemilikan', 'warga') }}',

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

            if (this.miniMap) { this.miniMap.remove(); this.miniMap = null; }

            this.miniMap = L.map(el, { zoomControl: false, attributionControl: false, dragging: false, scrollWheelZoom: false });
            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { maxZoom: 19 }).addTo(this.miniMap);

            const geo = this.detail.geojson;
            const layer = L.geoJSON(geo, {
                style: { color: this.detail.borderColor, fillColor: this.detail.color, fillOpacity: 0.6, weight: 2 }
            }).addTo(this.miniMap);
            this.miniMap.fitBounds(layer.getBounds(), { padding: [20, 20] });
        },

        async searchPenduduk(q) {
            if (q.length < 2) { this.searchResults = []; return; }
            try {
                const res = await fetch(`{{ route('admin.pertanahan.search-penduduk') }}?q=${encodeURIComponent(q)}`);
                this.searchResults = await res.json();
            } catch (e) { this.searchResults = []; }
        },

        selectPenduduk(item) {
            this.selectedPenduduk = item;
            this.searchResults = [];
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
document.addEventListener('DOMContentLoaded', function () {
    if (typeof L === 'undefined') return;

    const mapMain = L.map('mapPertanahan', {
        zoomControl: false,
        attributionControl: false
    }).setView([-6.8211, 107.6312], 15);

    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { maxZoom: 19 }).addTo(mapMain);
    L.control.zoom({ position: 'bottomright' }).addTo(mapMain);

    const mapData = @json($mapData);
    const layers = { all: [], desa: [], warga: [], fasum: [] };

    mapData.forEach(item => {
        if (!item.geojson) return;

        const layer = L.geoJSON(item.geojson, {
            style: { color: item.borderColor, fillColor: item.color, fillOpacity: 0.6, weight: 2 }
        }).bindPopup(`<b>${item.pemilik}</b><br>Luas: ${item.luas} m²<br><span style="font-size:10px;color:#888">${item.kode_lahan}</span>`);

        layer.addTo(mapMain);
        layers.all.push(layer);
        layers[item.kepemilikan]?.push(layer);
    });

    // Fit bounds if data exists
    if (layers.all.length > 0) {
        const group = L.featureGroup(layers.all);
        mapMain.fitBounds(group.getBounds(), { padding: [30, 30] });
    }

    // Layer toggle via Alpine
    window.toggleMapLayer = function (type) {
        layers.all.forEach(l => mapMain.removeLayer(l));
        const target = type === 'all' ? layers.all : (layers[type] || []);
        target.forEach(l => l.addTo(mapMain));
    };
});
</script>
@endpush
