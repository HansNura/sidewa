<!-- 2. DRAWER: DETAIL PENGUMUMAN / AGENDA -->
<div x-show="detailDrawerOpen" class="fixed inset-0 z-[100] flex justify-end" x-cloak>
    <div x-show="detailDrawerOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="detailDrawerOpen = false"></div>

    <div x-show="detailDrawerOpen" x-transition:enter="transition ease-transform duration-300"
        x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-transform duration-300" x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="relative bg-white w-full max-w-lg h-full shadow-2xl flex flex-col border-l border-gray-200">

        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 shrink-0">
            <div class="flex items-center gap-2">
                <template x-if="previewData.status === 'publish'">
                    <span class="bg-green-100 text-green-700 text-[10px] font-black px-2.5 py-1 rounded-full border border-green-200 uppercase tracking-widest"><i class="fa-solid fa-check mr-1"></i> Published</span>
                </template>
                <template x-if="previewData.status === 'draft'">
                    <span class="bg-gray-100 text-gray-700 text-[10px] font-black px-2.5 py-1 rounded-full border border-gray-200 uppercase tracking-widest">Draft</span>
                </template>
                <template x-if="previewData.status === 'archived'">
                    <span class="bg-gray-100 text-gray-500 text-[10px] font-black px-2.5 py-1 rounded-full border border-gray-200 uppercase tracking-widest">Archived/Expired</span>
                </template>
            </div>
            <div class="flex gap-2">
                <button @click="openForm(previewData.id, previewData.type); detailDrawerOpen = false"
                    class="text-gray-400 hover:text-amber-500 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-amber-50 transition-colors border border-transparent hover:border-amber-200"><i class="fa-solid fa-pen"></i></button>
                <button @click="detailDrawerOpen = false" class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 transition-colors border border-transparent hover:border-red-200"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
        </div>

        <div class="overflow-y-auto custom-scrollbar flex-1 bg-gray-50/30">
            <div class="p-6 sm:p-8 space-y-6">
                <!-- Header Info -->
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <template x-if="previewData.type === 'agenda'">
                            <span class="text-[9px] font-bold px-2 py-0.5 bg-purple-100 text-purple-700 rounded-md uppercase tracking-wider inline-block">Agenda</span>
                        </template>
                        <template x-if="previewData.type === 'pengumuman'">
                            <span class="text-[9px] font-bold px-2 py-0.5 bg-blue-100 text-blue-700 rounded-md uppercase tracking-wider inline-block">Pengumuman</span>
                        </template>
                    </div>
                    <h2 class="text-2xl font-extrabold text-gray-900 leading-tight mb-4" x-text="previewData.title"></h2>
                    <p class="text-[10px] font-mono p-2 bg-gray-100 text-gray-500 rounded-xl mb-2 flex items-center gap-2 border border-gray-200 shadow-inner">
                        <i class="fa-solid fa-link text-gray-400"></i>
                        <span>/informasi/detail/<span x-text="previewData.slug"></span></span>
                    </p>
                </div>

                <!-- Event Info Box -->
                <template x-if="previewData.type === 'agenda'">
                    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-1 h-full bg-purple-500"></div>
                        <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2"><i class="fa-solid fa-calendar-check text-purple-500"></i> Detail Pelaksanaan</h4>
                        <div class="space-y-4">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0 shadow-inner border border-purple-100">
                                    <i class="fa-regular fa-clock text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900 mb-0.5" x-text="previewData.start_date ? previewData.start_date.slice(0,10) : ''"></p>
                                    <p class="text-xs font-semibold text-purple-600 bg-purple-50 px-2 py-0.5 rounded-lg inline-block border border-purple-100"><span x-text="previewData.start_date ? previewData.start_date.slice(11,16) : ''"></span> - <span x-text="previewData.end_date ? previewData.end_date.slice(11,16) : 'Selesai'"></span></p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0 shadow-inner border border-purple-100">
                                    <i class="fa-solid fa-location-dot text-lg"></i>
                                </div>
                                <div class="pt-1">
                                    <p class="text-sm font-bold text-gray-900" x-text="previewData.location"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                <template x-if="previewData.type === 'pengumuman'">
                    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-1 h-full bg-blue-500"></div>
                        <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2"><i class="fa-solid fa-bullhorn text-blue-500"></i> Info Tayang</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center bg-gray-50 p-3 border border-gray-100 rounded-xl">
                                <span class="text-[10px] text-gray-500 font-bold uppercase tracking-wider flex items-center gap-1.5"><i class="fa-regular fa-circle-play text-gray-400"></i> Mulai Tayang</span>
                                <span class="font-extrabold text-xs text-gray-900" x-text="previewData.start_date ? previewData.start_date.slice(0,10) : ''"></span>
                            </div>
                            <div class="flex justify-between items-center bg-gray-50 p-3 border border-gray-100 rounded-xl">
                                <span class="text-[10px] text-gray-500 font-bold uppercase tracking-wider flex items-center gap-1.5"><i class="fa-regular fa-circle-stop text-gray-400"></i> Berakhir Pada</span>
                                <span class="font-extrabold text-xs text-gray-900" x-text="previewData.end_date ? previewData.end_date.slice(0,10) : ''"></span>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Content Viewer -->
                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                    <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 border-b border-gray-100 pb-3 flex items-center gap-2"><i class="fa-solid fa-align-left"></i> Deskripsi Lengkap</h4>
                    <div class="prose prose-sm prose-gray max-w-none font-serif text-gray-800 leading-relaxed" x-html="previewData.content_html">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
