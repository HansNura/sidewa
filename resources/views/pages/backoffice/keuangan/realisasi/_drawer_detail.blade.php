<div x-show="detailDrawerOpen" class="fixed inset-0 z-[100] flex justify-end" x-cloak>
    <div x-show="detailDrawerOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
        @click="detailDrawerOpen = false"></div>

    <div x-show="detailDrawerOpen" x-transition:enter="transition ease-transform duration-300"
        x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-transform duration-300" x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="relative bg-white w-full max-w-2xl h-full shadow-2xl flex flex-col border-l border-gray-200 overflow-hidden">

        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-start bg-gray-50/50 shrink-0">
            <div class="flex gap-4 items-center">
                <div class="w-12 h-12 rounded-xl bg-green-700 text-white flex items-center justify-center text-xl shrink-0 shadow-lg border-2 border-white">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-lg text-gray-900 leading-tight">Detail Realisasi</h3>
                    <p class="text-[10px] font-bold text-green-700 uppercase tracking-widest mt-1">Sistem Keuangan Desa</p>
                </div>
            </div>
            <button @click="detailDrawerOpen = false" class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 transition-colors -mr-2">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <div id="drawer-content" class="flex-1 overflow-y-auto custom-scrollbar relative">
            <div class="flex items-center justify-center h-full text-gray-400">
                <i class="fa-solid fa-spinner fa-spin text-2xl"></i> <span class="ml-2 font-medium">Memuat data...</span>
            </div>
        </div>

    </div>
</div>
