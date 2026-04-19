<!-- 2. DRAWER: DETAIL PENGUMUMAN / AGENDA -->
<div x-show="detailDrawerOpen" class="fixed inset-0 z-[100] flex justify-end" x-cloak>
    <div x-show="detailDrawerOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="detailDrawerOpen = false"></div>

    <div x-show="detailDrawerOpen" x-transition:enter="transition ease-transform duration-300"
        x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-transform duration-300" x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="relative bg-white w-full max-w-md h-full shadow-2xl flex flex-col border-l border-gray-200">

        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 shrink-0">
            <div class="flex items-center gap-2">
                <template x-if="previewData.status === 'publish'">
                    <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-0.5 rounded border border-green-200 uppercase tracking-widest"><i class="fa-solid fa-check mr-1"></i> Published</span>
                </template>
                <template x-if="previewData.status === 'draft'">
                    <span class="bg-gray-100 text-gray-700 text-[10px] font-bold px-2 py-0.5 rounded border border-gray-200 uppercase tracking-widest">Draft</span>
                </template>
                <template x-if="previewData.status === 'archived'">
                    <span class="bg-gray-100 text-gray-500 text-[10px] font-bold px-2 py-0.5 rounded border border-gray-200 uppercase tracking-widest">Archived/Expired</span>
                </template>
            </div>
            <div class="flex gap-2">
                <button @click="openForm(previewData.id, previewData.type); detailDrawerOpen = false"
                    class="text-gray-400 hover:text-amber-500 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-amber-50 transition-colors"><i class="fa-solid fa-pen"></i></button>
                <button @click="detailDrawerOpen = false" class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 transition-colors"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
        </div>

        <div class="overflow-y-auto custom-scrollbar flex-1">
            <div class="p-6 space-y-6">
                <!-- Header Info -->
                <div>
                    <h2 class="text-xl font-extrabold text-gray-900 leading-tight mb-3" x-text="previewData.title"></h2>
                    <p class="text-[10px] font-mono p-1 bg-gray-100 text-gray-500 rounded mb-2">/informasi/detail/<span x-text="previewData.slug"></span></p>
                </div>

                <!-- Event Info Box -->
                <template x-if="previewData.type === 'agenda'">
                    <div class="bg-purple-50 rounded-2xl p-4 border border-purple-100">
                        <h4 class="text-[10px] font-bold text-purple-800 uppercase tracking-wider mb-3">Detail Pelaksanaan</h4>
                        <div class="space-y-3">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-white text-purple-600 flex items-center justify-center shrink-0 border border-purple-200">
                                    <i class="fa-regular fa-clock"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-900" x-text="previewData.start_date ? previewData.start_date.slice(0,10) : ''"></p>
                                    <p class="text-[10px] text-gray-600">Jam: <span x-text="previewData.start_date ? previewData.start_date.slice(11,16) : ''"></span> - <span x-text="previewData.end_date ? previewData.end_date.slice(11,16) : 'Selesai'"></span></p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-white text-purple-600 flex items-center justify-center shrink-0 border border-purple-200">
                                    <i class="fa-solid fa-location-dot"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-900" x-text="previewData.location"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                <template x-if="previewData.type === 'pengumuman'">
                    <div class="bg-blue-50 rounded-2xl p-4 border border-blue-100">
                        <h4 class="text-[10px] font-bold text-blue-800 uppercase tracking-wider mb-3">Info Tayang</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center bg-white p-2 border border-blue-200 rounded">
                                <span class="text-[10px] text-gray-500 font-bold uppercase">Mulai Tayang</span>
                                <span class="font-bold text-xs" x-text="previewData.start_date ? previewData.start_date.slice(0,10) : ''"></span>
                            </div>
                            <div class="flex justify-between items-center bg-white p-2 border border-blue-200 rounded">
                                <span class="text-[10px] text-gray-500 font-bold uppercase">Berakhir Pada</span>
                                <span class="font-bold text-xs" x-text="previewData.end_date ? previewData.end_date.slice(0,10) : ''"></span>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Content Viewer -->
                <div>
                    <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 border-b border-gray-100 pb-2">Deskripsi Lengkap</h4>
                    <div class="prose prose-sm prose-gray max-w-none font-serif text-gray-800 leading-relaxed" x-html="previewData.content_html">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
