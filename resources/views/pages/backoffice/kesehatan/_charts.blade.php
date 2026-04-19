{{-- Charts Section --}}
<section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Line Chart --}}
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm lg:col-span-2 flex flex-col">
        <h3 class="font-bold text-gray-800 mb-4">Tren Prevalensi Stunting (12 Bulan Terakhir)</h3>
        <div id="chartTren" class="w-full h-72 flex-1"></div>
    </div>

    {{-- Donut Chart --}}
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm lg:col-span-1 flex flex-col">
        <h3 class="font-bold text-gray-800 mb-4">Sebaran Status Gizi Balita (TB/U)</h3>
        <div id="chartGizi" class="w-full h-72 flex-1"></div>
    </div>
</section>
