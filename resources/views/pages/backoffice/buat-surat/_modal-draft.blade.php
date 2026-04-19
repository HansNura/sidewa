{{-- Draft Management Modal --}}
<div x-show="draftModalOpen" class="fixed inset-0 z-[120] flex items-center justify-center p-4 sm:p-6" x-cloak>
    <div x-show="draftModalOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
        @click="draftModalOpen = false"></div>

    <div x-show="draftModalOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden flex flex-col max-h-[80vh]">

        {{-- Modal Header --}}
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 shrink-0">
            <h3 class="font-extrabold text-lg text-gray-900">Manajemen Draft Surat</h3>
            <button @click="draftModalOpen = false"
                class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        {{-- Modal Body --}}
        <div class="p-6 overflow-y-auto custom-scrollbar flex-1">
            {{-- Loading State --}}
            <div x-show="isLoadingDrafts" class="text-center py-8" x-cloak>
                <i class="fa-solid fa-spinner fa-spin text-2xl text-gray-300 mb-2"></i>
                <p class="text-sm text-gray-500">Memuat draft...</p>
            </div>

            {{-- Empty State --}}
            <div x-show="!isLoadingDrafts && drafts.length === 0" class="text-center py-8" x-cloak>
                <i class="fa-regular fa-folder-open text-4xl text-gray-200 mb-3"></i>
                <p class="text-sm text-gray-500 font-medium">Belum ada draft tersimpan.</p>
            </div>

            {{-- Draft List --}}
            <div x-show="!isLoadingDrafts && drafts.length > 0" class="space-y-3" x-cloak>
                <template x-for="draft in drafts" :key="draft.id">
                    <div class="border border-gray-200 rounded-xl p-4 hover:border-green-400 hover:shadow-sm transition-all cursor-pointer bg-white group flex justify-between items-center"
                        @click="loadDraft(draft.id)">
                        <div>
                            <h4 class="font-bold text-gray-900 text-sm mb-1 group-hover:text-green-700"
                                x-text="draft.jenis_short + ' - ' + draft.nama_pemohon"></h4>
                            <p class="text-[10px] text-gray-500 font-mono">
                                <i class="fa-regular fa-clock mr-1"></i>
                                Disimpan: <span x-text="draft.updated_at"></span>
                            </p>
                        </div>
                        <i class="fa-solid fa-chevron-right text-gray-300 group-hover:text-green-500 transition-colors"></i>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
