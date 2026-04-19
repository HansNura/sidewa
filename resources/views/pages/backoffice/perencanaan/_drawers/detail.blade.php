<div x-show="detailDrawerOpen" class="fixed inset-0 z-[100] flex justify-end" x-cloak>
    <div x-show="detailDrawerOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
        @click="detailDrawerOpen = false"></div>

    <div x-show="detailDrawerOpen" x-transition:enter="transition ease-transform duration-300"
        x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-transform duration-300" x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="relative bg-white w-full max-w-md h-full shadow-2xl flex flex-col border-l border-gray-200">

        <!-- Outer Header Container -->
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-start bg-gray-50/50 shrink-0">
            <h3 class="font-extrabold text-lg text-gray-900 leading-tight">Detail Perencanaan</h3>
            <button @click="detailDrawerOpen = false" class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 -mr-2"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>

        <div id="drawer-content" class="flex-1 overflow-y-auto custom-scrollbar relative">
             <!-- Ajax Inject -->
        </div>
        
    </div>
</div>
