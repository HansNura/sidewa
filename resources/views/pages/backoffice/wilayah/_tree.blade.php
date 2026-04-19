{{-- Hierarchy Tree View --}}
<section class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col lg:col-span-1 overflow-hidden h-[500px]">
    <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center shrink-0">
        <h3 class="font-bold text-gray-800">
            <i class="fa-solid fa-sitemap text-green-600 mr-2"></i> Struktur Wilayah
        </h3>
    </div>

    <div class="p-5 overflow-y-auto custom-scrollbar flex-1 relative">
        {{-- Root: Desa --}}
        <div class="font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-building-flag text-green-700"></i>
            Desa Sindangmukti
        </div>

        @foreach ($tree as $dusun)
            <div class="relative ml-2" x-data="{ expanded: {{ $loop->first ? 'true' : 'false' }} }">
                {{-- Tree line --}}
                <div class="tree-line" x-show="expanded"></div>

                {{-- Dusun item --}}
                <div class="flex items-center gap-2 mb-2 relative z-10 cursor-pointer hover:bg-gray-50 p-1.5 rounded-lg -ml-1.5 transition-colors"
                     @click="expanded = !expanded">
                    <i class="fa-solid fa-chevron-right text-gray-400 text-xs transition-transform duration-200 w-3 text-center"
                       :class="expanded ? 'rotate-90' : ''"></i>
                    <i class="fa-solid fa-map text-amber-500 text-sm w-4 text-center"></i>
                    <span class="text-sm font-semibold text-gray-800 select-none">Dusun {{ $dusun->nama }}</span>
                    <span class="text-[10px] text-gray-400 ml-auto bg-gray-100 px-1.5 rounded">
                        {{ number_format($dusun->populasi()) }} Jiwa
                    </span>
                </div>

                {{-- RW children --}}
                <div x-show="expanded" x-collapse class="ml-6 space-y-2 pb-2">
                    @foreach ($dusun->childrenRecursive as $rw)
                        <div class="relative" x-data="{ expandedRw: false }">
                            <div class="tree-item-line"></div>
                            <div class="tree-line" x-show="expandedRw" style="top: 20px; left: 5px;"></div>

                            <div class="flex items-center gap-2 relative z-10 cursor-pointer hover:bg-gray-50 p-1 rounded-lg -ml-1 transition-colors"
                                 @click="expandedRw = !expandedRw">
                                <i class="fa-solid fa-chevron-right text-gray-400 text-[10px] transition-transform duration-200 w-3 text-center"
                                   :class="expandedRw ? 'rotate-90' : ''"></i>
                                <i class="fa-regular fa-folder text-blue-500 text-sm"></i>
                                <span class="text-sm font-medium text-gray-700 select-none">RW {{ $rw->nama }}</span>
                            </div>

                            {{-- RT children --}}
                            <div x-show="expandedRw" x-collapse class="ml-5 space-y-1 mt-1">
                                @foreach ($rw->childrenRecursive ?? [] as $rt)
                                    <div class="flex items-center gap-2 relative cursor-pointer hover:text-green-600 group"
                                         @click="openDetail({{ $rt->id }})">
                                        <div class="tree-item-line" style="left: -8px;"></div>
                                        <i class="fa-solid fa-circle text-gray-300 text-[6px] group-hover:text-green-500 z-10 bg-white ml-0.5"></i>
                                        <span class="text-xs text-gray-600 group-hover:font-semibold group-hover:text-green-700">
                                            RT {{ $rt->nama }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        @if ($tree->isEmpty())
            <div class="text-center text-gray-400 text-sm py-8">
                <i class="fa-solid fa-folder-tree text-2xl mb-2 block"></i>
                <p>Belum ada data wilayah.</p>
            </div>
        @endif
    </div>
</section>
