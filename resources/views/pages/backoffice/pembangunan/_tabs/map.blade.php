<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-[500px]">
    <div class="p-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center shrink-0">
        <h3 class="font-bold text-gray-800"><i class="fa-solid fa-map-location-dot text-green-700 mr-2"></i> Peta Sebaran Proyek</h3>
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2 text-xs">
                <span class="w-3 h-3 rounded-full bg-blue-500"></span> <span class="text-gray-600">Berjalan</span>
                <span class="w-3 h-3 rounded-full bg-green-500 ml-2"></span> <span class="text-gray-600">Selesai</span>
                <span class="w-3 h-3 rounded-full bg-red-500 ml-2"></span> <span class="text-gray-600">Terlambat</span>
            </div>
        </div>
    </div>
    <div class="flex-1 relative z-0 bg-gray-100 w-full h-full">
        <div id="projectMap" class="absolute inset-0"></div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof L !== 'undefined') {
            const rawProyeks = @json($proyeks);

            setTimeout(() => {
                // Initialize map
                var map = L.map('projectMap', { zoomControl: false, attributionControl: false }).setView([-7.1726, 108.1963], 14);
                L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { maxZoom: 19 }).addTo(map);
                L.control.zoom({ position: 'bottomright' }).addTo(map);

                // Define Icons
                var iconOngoing = L.divIcon({ className: 'custom-icon', html: '<div style="background-color:#3b82f6; width:16px; height:16px; border-radius:50%; border:2px solid white; box-shadow:0 2px 5px rgba(0,0,0,0.3);"></div>' });
                var iconDone = L.divIcon({ className: 'custom-icon', html: '<div style="background-color:#16a34a; width:16px; height:16px; border-radius:50%; border:2px solid white; box-shadow:0 2px 5px rgba(0,0,0,0.3);"></div>' });
                var iconDelayed = L.divIcon({ className: 'custom-icon', html: '<div style="background-color:#ef4444; width:16px; height:16px; border-radius:50%; border:2px solid white; box-shadow:0 2px 5px rgba(0,0,0,0.3); animation: pulse 2s infinite;"></div>' });
                var iconPlan = L.divIcon({ className: 'custom-icon', html: '<div style="background-color:#94a3b8; width:16px; height:16px; border-radius:50%; border:2px solid white; box-shadow:0 2px 5px rgba(0,0,0,0.3);"></div>' });

                rawProyeks.forEach(p => {
                    if(p.latitude && p.longitude) {
                        let iconTouse = iconPlan;
                        if(p.status == 'berjalan') iconTouse = iconOngoing;
                        if(p.status == 'selesai') iconTouse = iconDone;
                        if(p.status == 'terlambat') iconTouse = iconDelayed;

                        L.marker([p.latitude, p.longitude], { icon: iconTouse }).addTo(map)
                            .bindPopup(`<b>${p.nama_proyek}</b><br>Progres: ${p.progres_fisik}%<br>Status: ${p.status}`);
                    }
                });

            }, 1000); // give Alpine time to render before initializing size
        }
    });
</script>
@endpush
