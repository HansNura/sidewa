{{-- Map Section --}}
<section class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col overflow-hidden h-[500px]">
    <div class="p-4 border-b border-gray-100 flex flex-col sm:flex-row justify-between sm:items-center gap-4 bg-gray-50/50 shrink-0">
        <h3 class="font-bold text-gray-800"><i class="fa-solid fa-layer-group text-green-600 mr-2"></i> Peta Distribusi Lahan</h3>

        {{-- Map Layer Toggles --}}
        <div x-data="{ layer: 'all' }" class="bg-white border border-gray-200 rounded-lg p-1 flex shadow-sm w-fit">
            <button @click="layer = 'all'; toggleMapLayer('all')"
                :class="layer === 'all' ? 'bg-gray-100 text-gray-800 font-bold' : 'text-gray-500 hover:text-gray-700'"
                class="px-3 py-1.5 text-xs rounded-md transition-colors cursor-pointer">Semua</button>
            <button @click="layer = 'desa'; toggleMapLayer('desa')"
                :class="layer === 'desa' ? 'bg-green-50 text-green-700 font-bold' : 'text-gray-500 hover:text-gray-700'"
                class="px-3 py-1.5 text-xs rounded-md transition-colors flex items-center gap-1 cursor-pointer">
                <div class="w-2 h-2 rounded bg-green-500"></div> Aset Desa
            </button>
            <button @click="layer = 'warga'; toggleMapLayer('warga')"
                :class="layer === 'warga' ? 'bg-blue-50 text-blue-700 font-bold' : 'text-gray-500 hover:text-gray-700'"
                class="px-3 py-1.5 text-xs rounded-md transition-colors flex items-center gap-1 cursor-pointer">
                <div class="w-2 h-2 rounded bg-blue-500"></div> Warga
            </button>
            <button @click="layer = 'fasum'; toggleMapLayer('fasum')"
                :class="layer === 'fasum' ? 'bg-amber-50 text-amber-700 font-bold' : 'text-gray-500 hover:text-gray-700'"
                class="px-3 py-1.5 text-xs rounded-md transition-colors flex items-center gap-1 cursor-pointer">
                <div class="w-2 h-2 rounded bg-amber-400"></div> Fasum
            </button>
        </div>
    </div>

    {{-- Map Container --}}
    <div class="flex-1 relative z-0 bg-gray-100 w-full h-full">
        <div id="mapPertanahan" class="absolute inset-0"></div>

        <div class="absolute bottom-4 left-4 bg-white/95 backdrop-blur shadow-lg border border-gray-200 rounded-xl p-3 z-[400] w-64">
            <h4 class="text-xs font-bold text-gray-800 mb-1"><i class="fa-solid fa-circle-info text-blue-500 mr-1"></i> Informasi Peta</h4>
            <p class="text-[10px] text-gray-500 leading-relaxed">Klik pada area/poligon lahan di peta untuk melihat detail kepemilikan dan batas wilayah aset.</p>
        </div>
    </div>
</section>
