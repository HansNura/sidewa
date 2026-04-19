{{-- Map Visualization --}}
<section class="bg-white rounded-2xl shadow-sm border border-gray-100 lg:col-span-2 overflow-hidden flex flex-col h-[500px]">
    <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center shrink-0">
        <div>
            <h3 class="font-bold text-gray-800">
                <i class="fa-solid fa-map-location-dot text-green-600 mr-2"></i> Peta Distribusi Wilayah
            </h3>
            <p class="text-xs text-gray-500 mt-0.5">Memilih area di peta akan menyorot data terkait.</p>
        </div>
    </div>
    <div class="flex-1 relative z-0 bg-gray-100 w-full h-full">
        <div id="mapWilayah" class="absolute inset-0"></div>

        {{-- Legend --}}
        <div class="absolute bottom-4 right-4 bg-white/95 backdrop-blur shadow-md border border-gray-200 rounded-xl p-3 z-[400]">
            <h4 class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Legend Poligon</h4>
            <div class="space-y-1.5">
                @foreach ($tree as $dusun)
                    @php
                        $legendColors = ['#60a5fa', '#4ade80', '#fbbf24', '#f87171', '#a78bfa'];
                        $borderColors = ['#2563eb', '#16a34a', '#d97706', '#dc2626', '#7c3aed'];
                        $idx = $loop->index % count($legendColors);
                    @endphp
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded opacity-60 border"
                             style="background-color: {{ $legendColors[$idx] }}; border-color: {{ $borderColors[$idx] }}"></div>
                        <span class="text-xs text-gray-700">Dusun {{ $dusun->nama }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
