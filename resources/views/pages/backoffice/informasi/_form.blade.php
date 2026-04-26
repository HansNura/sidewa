<!-- 1. FULL-SCREEN MODAL: KELOLA PENGUMUMAN / AGENDA (Form) -->
<div x-show="formModalOpen" class="fixed inset-0 z-[150] bg-gray-50 flex flex-col overflow-hidden" x-cloak
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-full opacity-0"
    x-transition:enter-end="translate-y-0 opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="translate-y-full opacity-0"
    x-init="
        $nextTick(() => {
            if(!window.infoEditor) {
                window.infoEditor = new Quill('#info_editor_container', {
                    theme: 'snow',
                    placeholder: 'Tuliskan informasi lengkap di sini...',
                    modules: {
                        toolbar: [
                            [{ 'header': [2, 3, 4, false] }],
                            ['bold', 'italic', 'underline', 'strike'],
                            [{ 'color': [] }, { 'background': [] }],
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                            [{ 'align': [] }],
                            ['link', 'image', 'clean']
                        ]
                    }
                });

                window.infoEditor.on('text-change', function() {
                    document.getElementById('editor_content_html').value = window.infoEditor.root.innerHTML;
                });
            }
        });
    ">

    <form id="infoEditorForm" action="{{ route('admin.informasi.store') }}" method="POST" class="flex-1 flex flex-col h-full overflow-hidden w-full m-0 p-0">
        @csrf
        <input type="hidden" name="id" :value="itemId">
        <input type="hidden" name="content_html" id="editor_content_html">
        <input type="hidden" name="status" id="editor_status" x-model="publishStatus">

        <!-- Editor Header -->
        <header class="h-16 bg-white border-b border-gray-200 px-4 sm:px-6 flex items-center justify-between shrink-0 shadow-sm z-20">
            <div class="flex items-center gap-4">
                <button type="button" @click="formModalOpen = false" class="text-gray-500 hover:text-red-600 hover:bg-red-50 transition-colors flex items-center gap-2 font-bold text-sm bg-white px-3 py-1.5 rounded-xl border border-gray-200 shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </button>
                <div class="h-6 w-px bg-gray-300 hidden sm:block"></div>
                <div class="hidden sm:block">
                    <h2 class="font-extrabold text-gray-900 text-sm leading-none" x-text="itemTitle !== '' ? 'Edit Informasi' : 'Buat Informasi Baru'"></h2>
                    <p class="text-[10px] text-gray-500 mt-1 font-mono uppercase tracking-widest" x-text="formType"></p>
                </div>
            </div>

            <div class="flex items-center gap-2 sm:gap-3">
                <button type="submit" @click="publishStatus = 'draft'" class="bg-gray-100 text-gray-600 hover:bg-gray-200 border border-transparent rounded-xl px-4 py-2.5 text-sm font-bold transition-all flex items-center gap-2">
                    <i class="fa-regular fa-floppy-disk"></i> Simpan Draft
                </button>
                <button type="submit" @click="publishStatus = 'publish'" class="bg-green-700 hover:bg-green-800 text-white shadow-md shadow-green-900/10 rounded-xl px-5 py-2.5 text-sm font-bold transition-all flex items-center gap-2 active:scale-95">
                    <i class="fa-solid fa-paper-plane"></i> <span class="hidden sm:inline">Publikasikan</span>
                </button>
            </div>
        </header>

        <!-- Editor Workspace (2 Columns) -->
        <div class="flex-1 flex flex-col lg:flex-row overflow-hidden pb-16 lg:pb-0">

            <!-- LEFT COLUMN: CONTENT EDITOR -->
            <div class="flex-1 flex flex-col bg-white z-0 overflow-hidden relative">
                <div class="flex-1 flex flex-col overflow-y-auto custom-scrollbar">
                    <div class="max-w-4xl mx-auto w-full p-6 sm:p-10 space-y-6">

                        <!-- Title Input -->
                        <div class="relative group">
                            <textarea id="editor_title" name="title" x-model="itemTitle" required placeholder="Judul Pengumuman atau Agenda..." class="w-full text-4xl sm:text-5xl font-black text-gray-900 placeholder-gray-300 border-none outline-none bg-transparent focus:ring-0 resize-none overflow-hidden" rows="2" oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"></textarea>
                        </div>

                        <!-- Content Area (Quill) -->
                        <div class="editor-container min-h-[500px]">
                            <div id="info_editor_container" class="border-0 !p-0 !text-lg !font-serif text-gray-800 leading-relaxed"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: SETTINGS PANEL -->
            <div class="w-full lg:w-80 bg-gray-50/80 border-l border-gray-200 flex flex-col shrink-0 overflow-y-auto custom-scrollbar z-10">
                <div class="p-6 space-y-8">

                    <!-- Type Selector -->
                    <div class="space-y-3">
                        <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                            <i class="fa-solid fa-layer-group"></i> Tipe Informasi
                        </h4>
                        <div class="grid grid-cols-2 gap-2 bg-gray-100 p-1.5 rounded-xl border border-gray-200">
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="pengumuman" x-model="formType" class="peer sr-only">
                                <div class="text-center py-2.5 rounded-lg text-xs font-bold text-gray-500 peer-checked:bg-white peer-checked:text-blue-600 peer-checked:shadow-sm shadow-black/5 transition-all">
                                    <i class="fa-solid fa-bullhorn mr-1 block sm:inline"></i> Pengumuman
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="agenda" x-model="formType" class="peer sr-only">
                                <div class="text-center py-2.5 rounded-lg text-xs font-bold text-gray-500 peer-checked:bg-white peer-checked:text-purple-600 peer-checked:shadow-sm shadow-black/5 transition-all">
                                    <i class="fa-regular fa-calendar-check mr-1 block sm:inline"></i> Agenda
                                </div>
                            </label>
                        </div>
                    </div>

                    <hr class="border-gray-200">

                    <!-- Date & Time Info (Dynamic based on type) -->
                    <div class="space-y-3">
                        <!-- If Agenda -->
                        <div x-show="formType === 'agenda'" class="space-y-4">
                            <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                                <i class="fa-regular fa-calendar text-purple-600"></i> Jadwal Pelaksanaan
                            </h4>
                            <div class="bg-white border border-purple-200 rounded-2xl overflow-hidden shadow-sm relative">
                                <div class="absolute top-0 left-0 w-1 h-full bg-purple-500"></div>
                                <div class="p-4 space-y-4">
                                    <div class="space-y-1.5">
                                        <label class="text-[10px] font-bold text-gray-500 uppercase">Waktu Mulai <span class="text-red-500">*</span></label>
                                        <input type="datetime-local" id="agenda_start_date" :name="formType === 'agenda' ? 'start_date' : ''" :required="formType === 'agenda'" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-xs focus:ring-2 focus:ring-purple-500 outline-none transition-all">
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="text-[10px] font-bold text-gray-500 uppercase">Waktu Selesai (Opsional)</label>
                                        <input type="datetime-local" id="agenda_end_date" :name="formType === 'agenda' ? 'end_date' : ''" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-xs focus:ring-2 focus:ring-purple-500 outline-none transition-all">
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="text-[10px] font-bold text-gray-500 uppercase">Lokasi Kegiatan <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <i class="fa-solid fa-location-dot absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 text-xs"></i>
                                            <input type="text" id="agenda_location" :name="formType === 'agenda' ? 'location' : ''" :required="formType === 'agenda'" placeholder="Misal: Balai Desa..." class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-9 pr-4 py-2.5 text-xs focus:ring-2 focus:ring-purple-500 outline-none transition-all">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- If Pengumuman -->
                        <div x-show="formType === 'pengumuman'" class="space-y-4">
                            <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                                <i class="fa-solid fa-stopwatch text-blue-600"></i> Durasi Penayangan
                            </h4>
                            <div class="bg-white border border-blue-200 rounded-2xl overflow-hidden shadow-sm relative">
                                <div class="absolute top-0 left-0 w-1 h-full bg-blue-500"></div>
                                <div class="p-4 space-y-4">
                                    <div class="space-y-1.5">
                                        <label class="text-[10px] font-bold text-gray-500 uppercase">Mulai Tayang <span class="text-red-500">*</span></label>
                                        <input type="date" id="peng_start_date" :name="formType === 'pengumuman' ? 'start_date' : ''" :required="formType === 'pengumuman'" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-xs focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="text-[10px] font-bold text-gray-500 uppercase">Berakhir Pada <span class="text-red-500">*</span></label>
                                        <input type="date" id="peng_end_date" :name="formType === 'pengumuman' ? 'end_date' : ''" :required="formType === 'pengumuman'" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-xs focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                                    </div>
                                </div>
                            </div>
                            <p class="text-[10px] text-gray-400 leading-relaxed">Pengumuman akan otomatis diarsipkan (tidak tampil) setelah melewati tanggal Berakhir Pada.</p>
                        </div>
                    </div>

                    <hr class="border-gray-200">

                    <!-- Author Info -->
                    <div>
                        <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2 mb-3">
                            <i class="fa-solid fa-feather"></i> Dipublikasi Oleh
                        </h4>
                        <div class="bg-white border border-gray-200 p-4 rounded-2xl flex items-center gap-4 shadow-sm">
                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 shadow-inner">
                                <i class="fa-solid fa-user text-lg"></i>
                            </div>
                            <div>
                                <p class="text-sm font-extrabold text-gray-900">{{ auth()->user()->name ?? 'Administrator' }}</p>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Pemerintah Desa</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </form>
</div>
