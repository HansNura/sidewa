{{-- Map Section --}}
<section class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col overflow-hidden h-[500px] relative">
    <div
        class="p-4 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-gray-50/50 shrink-0">
        <div>
            <h3 class="font-bold text-gray-800">
                <i class="fa-solid fa-layer-group text-emerald-600 mr-2"></i> Peta Distribusi Lahan
            </h3>
            <p class="text-[11px] text-gray-500 mt-0.5">Pilih filter untuk menyorot status kepemilikan aset pada peta.
            </p>
        </div>

        {{-- Map Layer Toggles --}}
        <div class="flex flex-wrap sm:flex-nowrap items-center w-full sm:w-auto mt-2 sm:mt-0">
            <div x-data="{ layer: 'all' }"
                class="flex items-center gap-1 bg-gray-100 p-1 rounded-lg border border-gray-200/80 shadow-inner w-full sm:w-auto overflow-x-auto">
                <button @click="layer = 'all'; toggleMapLayer('all')"
                    :class="layer === 'all' ? 'bg-white text-gray-800 shadow-sm ring-1 ring-gray-900/5' :
                        'text-gray-500 hover:text-gray-700'"
                    class="flex-1 sm:flex-none px-3 sm:px-4 py-1.5 rounded-md text-xs font-bold transition-all duration-200 select-none flex items-center justify-center gap-1.5 cursor-pointer whitespace-nowrap">
                    Semua
                </button>
                <button @click="layer = 'desa'; toggleMapLayer('desa')"
                    :class="layer === 'desa' ? 'bg-white text-emerald-700 shadow-sm ring-1 ring-gray-900/5' :
                        'text-gray-500 hover:text-gray-700'"
                    class="flex-1 sm:flex-none px-3 sm:px-4 py-1.5 rounded-md text-xs font-bold transition-all duration-200 select-none flex items-center justify-center gap-1.5 cursor-pointer whitespace-nowrap">
                    <div class="w-1.5 h-1.5 rounded-full transition-colors shadow-inner"
                        :class="layer === 'desa' ? 'bg-emerald-500' : 'bg-gray-300'"></div>
                    Aset Desa
                </button>
                <button @click="layer = 'warga'; toggleMapLayer('warga')"
                    :class="layer === 'warga' ? 'bg-white text-blue-700 shadow-sm ring-1 ring-gray-900/5' :
                        'text-gray-500 hover:text-gray-700'"
                    class="flex-1 sm:flex-none px-3 sm:px-4 py-1.5 rounded-md text-xs font-bold transition-all duration-200 select-none flex items-center justify-center gap-1.5 cursor-pointer whitespace-nowrap">
                    <div class="w-1.5 h-1.5 rounded-full transition-colors shadow-inner"
                        :class="layer === 'warga' ? 'bg-blue-500' : 'bg-gray-300'"></div>
                    Warga
                </button>
                <button @click="layer = 'fasum'; toggleMapLayer('fasum')"
                    :class="layer === 'fasum' ? 'bg-white text-amber-600 shadow-sm ring-1 ring-gray-900/5' :
                        'text-gray-500 hover:text-gray-700'"
                    class="flex-1 sm:flex-none px-3 sm:px-4 py-1.5 rounded-md text-xs font-bold transition-all duration-200 select-none flex items-center justify-center gap-1.5 cursor-pointer whitespace-nowrap">
                    <div class="w-1.5 h-1.5 rounded-full transition-colors shadow-inner"
                        :class="layer === 'fasum' ? 'bg-amber-500' : 'bg-gray-300'"></div>
                    Fasum
                </button>
            </div>
        </div>
    </div>

    {{-- Map Container --}}
    <div class="flex-1 relative z-0 bg-gray-100 w-full h-full">
        <div id="mapPertanahan" class="absolute inset-0 z-10"></div>

        {{-- Legend --}}
        <div
            class="absolute bottom-4 left-4 bg-white/95 backdrop-blur shadow-md border border-gray-200 rounded-xl p-3 z-[400] pointer-events-none">
            <h4 class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Legenda Kepemilikan</h4>
            <div class="space-y-1.5">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded opacity-60 border bg-emerald-400 border-emerald-600"></div>
                    <span class="text-xs text-gray-700 font-medium">Aset Desa</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded opacity-60 border bg-blue-400 border-blue-600"></div>
                    <span class="text-xs text-gray-700 font-medium">Milik Warga</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded opacity-60 border bg-amber-400 border-amber-600"></div>
                    <span class="text-xs text-gray-700 font-medium">Fasilitas Umum</span>
                </div>
            </div>
        </div>
    </div>
</section>
