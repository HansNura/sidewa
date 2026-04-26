<div x-show="detailDrawerOpen" class="fixed inset-0 z-[100] flex justify-end" x-cloak>
    <div x-show="detailDrawerOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
        @click="detailDrawerOpen = false"></div>

    <div x-show="detailDrawerOpen" x-transition:enter="transition ease-transform duration-300"
        x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-transform duration-300" x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="relative bg-white w-full max-w-2xl h-full shadow-2xl flex flex-col border-l border-gray-200">

        <!-- Header container is static to hold the close button immediately -->
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-start bg-gray-50/50 shrink-0">
             <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-full bg-green-100 text-green-700 flex items-center justify-center shrink-0">
                      <i class="fa-solid fa-folder-tree"></i>
                  </div>
                  <h3 class="font-extrabold text-lg text-gray-900 leading-tight">Timeline Proyek</h3>
             </div>
             
             <button @click="detailDrawerOpen = false" class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 -mr-2"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>

        <div id="drawer-content" class="flex-1 flex flex-col relative bg-gray-50/20 overflow-hidden">
             <!-- Ajax Inject -->
        </div>
        
    </div>
</div>
