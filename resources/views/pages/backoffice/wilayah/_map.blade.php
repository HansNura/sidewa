{{-- Map Visualization --}}
<section x-data="mapOverview" x-init="initMap()"
    class="bg-white rounded-2xl shadow-sm border border-gray-100 lg:col-span-2 overflow-hidden flex flex-col h-[500px] relative">
    <div
        class="p-4 border-b border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row justify-between items-start sm:items-center shrink-0 gap-3">
        <div>
            <h3 class="font-bold text-gray-800">
                <i class="fa-solid fa-map-location-dot text-green-600 mr-2"></i> Peta Distribusi Wilayah
            </h3>
            <p class="text-[11px] text-gray-500 mt-0.5">Memilih area di peta akan menyorot data terkait.</p>
        </div>

        {{-- Filters --}}
        <div class="flex flex-wrap sm:flex-nowrap items-center gap-2.5 w-full sm:w-auto mt-2 sm:mt-0">
            <div class="relative w-full sm:w-44">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fa-solid fa-filter text-gray-400 text-[10px]"></i>
                </div>
                <select x-model="filterDusun"
                    class="w-full appearance-none bg-white border border-gray-200 text-gray-700 text-xs font-bold rounded-lg pl-8 pr-8 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all cursor-pointer">
                    <option value="">Semua Wilayah</option>
                    @foreach ($dusunList as $dusun)
                        <option value="{{ $dusun->id }}">Dusun {{ $dusun->nama }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500">
                    <i class="fa-solid fa-chevron-down text-[10px]"></i>
                </div>
            </div>

            <div
                class="flex items-center gap-1 bg-gray-100 p-1 rounded-lg border border-gray-200/80 shadow-inner w-full sm:w-auto">
                <label class="flex-1 sm:flex-none cursor-pointer relative group">
                    <input type="checkbox" value="dusun" x-model="filterTipe" class="peer sr-only">
                    <div
                        class="text-center px-3 sm:px-4 py-1.5 rounded-md text-xs font-bold transition-all duration-200
                                peer-checked:bg-white peer-checked:text-blue-700 peer-checked:shadow-sm peer-checked:ring-1 peer-checked:ring-gray-900/5
                                text-gray-500 hover:text-gray-700 select-none flex items-center justify-center gap-1.5">
                        <div
                            class="w-1.5 h-1.5 rounded-full bg-gray-300 peer-checked:bg-blue-500 transition-colors shadow-inner">
                        </div>
                        Dusun
                    </div>
                </label>
                <label class="flex-1 sm:flex-none cursor-pointer relative group">
                    <input type="checkbox" value="rw" x-model="filterTipe" class="peer sr-only">
                    <div
                        class="text-center px-3 sm:px-4 py-1.5 rounded-md text-xs font-bold transition-all duration-200
                                peer-checked:bg-white peer-checked:text-green-700 peer-checked:shadow-sm peer-checked:ring-1 peer-checked:ring-gray-900/5
                                text-gray-500 hover:text-gray-700 select-none flex items-center justify-center gap-1.5">
                        <div
                            class="w-1.5 h-1.5 rounded-full bg-gray-300 peer-checked:bg-green-500 transition-colors shadow-inner">
                        </div>
                        RW
                    </div>
                </label>
                <label class="flex-1 sm:flex-none cursor-pointer relative group">
                    <input type="checkbox" value="rt" x-model="filterTipe" class="peer sr-only">
                    <div
                        class="text-center px-3 sm:px-4 py-1.5 rounded-md text-xs font-bold transition-all duration-200
                                peer-checked:bg-white peer-checked:text-amber-600 peer-checked:shadow-sm peer-checked:ring-1 peer-checked:ring-gray-900/5
                                text-gray-500 hover:text-gray-700 select-none flex items-center justify-center gap-1.5">
                        <div
                            class="w-1.5 h-1.5 rounded-full bg-gray-300 peer-checked:bg-amber-500 transition-colors shadow-inner">
                        </div>
                        RT
                    </div>
                </label>
            </div>
        </div>
    </div>

    <div class="flex-1 relative z-0 bg-gray-100 w-full h-full">
        <div id="mapWilayah" class="absolute inset-0 z-10"></div>

        {{-- Legend --}}
        <div
            class="absolute bottom-4 left-4 bg-white/95 backdrop-blur shadow-md border border-gray-200 rounded-xl p-3 z-[400] pointer-events-none">
            <h4 class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Legend</h4>
            <div class="space-y-1.5">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded opacity-60 border bg-blue-400 border-blue-600"></div>
                    <span class="text-xs text-gray-700 font-medium">Dusun</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded opacity-60 border bg-green-400 border-green-600"></div>
                    <span class="text-xs text-gray-700 font-medium">RW</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded opacity-60 border bg-amber-400 border-amber-600"></div>
                    <span class="text-xs text-gray-700 font-medium">RT</span>
                </div>
            </div>
        </div>
    </div>
</section>
