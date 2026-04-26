<section class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col overflow-hidden h-[500px] relative">
    <div class="p-4 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-gray-50/50 shrink-0">
        <div>
            <h3 class="font-bold text-gray-800">
                <i class="fa-solid fa-map-location-dot text-green-700 mr-2"></i> Peta Sebaran Proyek
            </h3>
            <p class="text-[11px] text-gray-500 mt-0.5">Pilih filter untuk menyorot status proyek pembangunan pada peta.</p>
        </div>

        {{-- Map Layer Toggles --}}
        <div class="flex flex-wrap sm:flex-nowrap items-center w-full sm:w-auto mt-2 sm:mt-0">
            <div x-data="{ layer: 'all' }"
                class="flex items-center gap-1 bg-gray-100 p-1 rounded-lg border border-gray-200/80 shadow-inner w-full sm:w-auto overflow-x-auto">
                <button @click="layer = 'all'; window.renderPembangunanMarkers('all')"
                    :class="layer === 'all' ? 'bg-white text-gray-800 shadow-sm ring-1 ring-gray-900/5' : 'text-gray-500 hover:text-gray-700'"
                    class="flex-1 sm:flex-none px-3 sm:px-4 py-1.5 rounded-md text-xs font-bold transition-all duration-200 select-none flex items-center justify-center gap-1.5 cursor-pointer whitespace-nowrap">
                    Semua
                </button>
                <button @click="layer = 'berjalan'; window.renderPembangunanMarkers('berjalan')"
                    :class="layer === 'berjalan' ? 'bg-white text-blue-700 shadow-sm ring-1 ring-gray-900/5' : 'text-gray-500 hover:text-gray-700'"
                    class="flex-1 sm:flex-none px-3 sm:px-4 py-1.5 rounded-md text-xs font-bold transition-all duration-200 select-none flex items-center justify-center gap-1.5 cursor-pointer whitespace-nowrap">
                    <div class="w-1.5 h-1.5 rounded-full transition-colors shadow-inner" :class="layer === 'berjalan' ? 'bg-blue-500' : 'bg-gray-300'"></div>
                    Berjalan
                </button>
                <button @click="layer = 'selesai'; window.renderPembangunanMarkers('selesai')"
                    :class="layer === 'selesai' ? 'bg-white text-green-700 shadow-sm ring-1 ring-gray-900/5' : 'text-gray-500 hover:text-gray-700'"
                    class="flex-1 sm:flex-none px-3 sm:px-4 py-1.5 rounded-md text-xs font-bold transition-all duration-200 select-none flex items-center justify-center gap-1.5 cursor-pointer whitespace-nowrap">
                    <div class="w-1.5 h-1.5 rounded-full transition-colors shadow-inner" :class="layer === 'selesai' ? 'bg-green-500' : 'bg-gray-300'"></div>
                    Selesai
                </button>
                <button @click="layer = 'terlambat'; window.renderPembangunanMarkers('terlambat')"
                    :class="layer === 'terlambat' ? 'bg-white text-red-600 shadow-sm ring-1 ring-gray-900/5' : 'text-gray-500 hover:text-gray-700'"
                    class="flex-1 sm:flex-none px-3 sm:px-4 py-1.5 rounded-md text-xs font-bold transition-all duration-200 select-none flex items-center justify-center gap-1.5 cursor-pointer whitespace-nowrap">
                    <div class="w-1.5 h-1.5 rounded-full transition-colors shadow-inner" :class="layer === 'terlambat' ? 'bg-red-500' : 'bg-gray-300'"></div>
                    Terlambat
                </button>
            </div>
        </div>
    </div>
    <div class="flex-1 relative z-0 bg-gray-100 w-full h-full">
        <div id="projectMap" class="absolute inset-0 z-10"></div>
        
        {{-- Legend --}}
        <div class="absolute bottom-4 left-4 bg-white/95 backdrop-blur shadow-md border border-gray-200 rounded-xl p-3 z-[400] pointer-events-none">
            <h4 class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Status Proyek</h4>
            <div class="space-y-1.5">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded opacity-60 border bg-blue-400 border-blue-600"></div>
                    <span class="text-xs text-gray-700 font-medium">Berjalan</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded opacity-60 border bg-green-400 border-green-600"></div>
                    <span class="text-xs text-gray-700 font-medium">Selesai</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded opacity-60 border bg-red-400 border-red-600"></div>
                    <span class="text-xs text-gray-700 font-medium">Terlambat</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded opacity-60 border bg-slate-400 border-slate-600"></div>
                    <span class="text-xs text-gray-700 font-medium">Rencana</span>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof L !== 'undefined') {
            const rawProyeks = @json($semuaProyek ?? []);
            let mapMarkers = null;

            setTimeout(() => {
                // Initialize map
                var map = L.map('projectMap', { zoomControl: false, attributionControl: false }).setView([-7.1726, 108.1963], 14);
                L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { maxZoom: 19 }).addTo(map);
                L.control.zoom({ position: 'bottomright' }).addTo(map);

                // Create a layer group for easy clearing
                mapMarkers = L.layerGroup().addTo(map);

                // Define Icons
                var iconOngoing = L.divIcon({ className: 'custom-icon', html: '<div style="background-color:#3b82f6; width:16px; height:16px; border-radius:50%; border:2px solid white; box-shadow:0 2px 5px rgba(0,0,0,0.3);"></div>' });
                var iconDone = L.divIcon({ className: 'custom-icon', html: '<div style="background-color:#16a34a; width:16px; height:16px; border-radius:50%; border:2px solid white; box-shadow:0 2px 5px rgba(0,0,0,0.3);"></div>' });
                var iconDelayed = L.divIcon({ className: 'custom-icon', html: '<div style="background-color:#ef4444; width:16px; height:16px; border-radius:50%; border:2px solid white; box-shadow:0 2px 5px rgba(0,0,0,0.3); animation: pulse 2s infinite;"></div>' });
                var iconPlan = L.divIcon({ className: 'custom-icon', html: '<div style="background-color:#94a3b8; width:16px; height:16px; border-radius:50%; border:2px solid white; box-shadow:0 2px 5px rgba(0,0,0,0.3);"></div>' });

                window.renderPembangunanMarkers = function(filterStatus = 'all') {
                    if (!mapMarkers) return;
                    mapMarkers.clearLayers();

                    rawProyeks.forEach(p => {
                        if(p.latitude && p.longitude) {
                            // Filter logic
                            if (filterStatus !== 'all' && p.status !== filterStatus) return;

                            let iconTouse = iconPlan;
                            if(p.status == 'berjalan') iconTouse = iconOngoing;
                            if(p.status == 'selesai') iconTouse = iconDone;
                            if(p.status == 'terlambat') iconTouse = iconDelayed;

                            L.marker([p.latitude, p.longitude], { icon: iconTouse }).addTo(mapMarkers)
                                .bindPopup(`<div style="font-family: Inter, sans-serif;">
                                    <b style="color: #1f2937; font-size: 13px;">${p.nama_proyek}</b><br>
                                    <div style="margin-top: 4px; font-size: 11px; color: #6b7280;">
                                        Progres: <span style="font-weight: bold; color: #16a34a;">${p.progres_fisik}%</span><br>
                                        Status: <span style="text-transform: capitalize;">${p.status}</span>
                                    </div>
                                </div>`);
                        }
                    });
                };

                // Initial render
                window.renderPembangunanMarkers('all');

            }, 1000); // give Alpine time to render before initializing size
        }
    });
</script>
@endpush
