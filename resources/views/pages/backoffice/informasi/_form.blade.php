<!-- 1. FULL-SCREEN MODAL: KELOLA PENGUMUMAN / AGENDA (Form) -->
<div x-show="formModalOpen" class="fixed inset-0 z-[150] bg-gray-100 flex flex-col overflow-hidden" x-cloak
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-full"
    x-transition:enter-end="translate-y-0" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full"
    x-init="
        $nextTick(() => {
            if(!window.infoEditor) {
                window.infoEditor = new Quill('#info_editor_container', {
                    theme: 'snow',
                    placeholder: 'Tuliskan informasi lengkap di sini...',
                    modules: {
                        toolbar: [
                            [{ 'header': [2, 3, false] }],
                            ['bold', 'italic', 'underline'],
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                            [{ 'align': [] }],
                            ['link', 'clean']
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
                <button type="button" @click="formModalOpen = false" class="text-gray-500 hover:text-red-600 transition-colors flex items-center gap-2 font-bold text-sm bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-200">
                    <i class="fa-solid fa-xmark"></i> Batal
                </button>
                <div class="h-6 w-px bg-gray-300 hidden sm:block"></div>
                <div class="hidden sm:block">
                    <h2 class="font-extrabold text-gray-900 text-sm leading-none" x-text="itemTitle !== '' ? 'Edit Informasi' : 'Buat Informasi Baru'"></h2>
                    <p class="text-[10px] text-gray-500 mt-1 font-mono uppercase tracking-widest" x-text="formType"></p>
                </div>
            </div>

            <div class="flex items-center gap-2 sm:gap-3">
                <button type="submit" @click="publishStatus = 'draft'" class="bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-xl px-4 py-2 text-sm font-bold transition-all">Simpan Draft</button>
                <button type="submit" @click="publishStatus = 'publish'" class="bg-green-700 hover:bg-green-800 text-white shadow-md rounded-xl px-5 py-2 text-sm font-bold transition-all flex items-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i> <span class="hidden sm:inline">Publikasikan</span>
                </button>
            </div>
        </header>

        <!-- Editor Workspace (2 Columns) -->
        <div class="flex-1 flex flex-col lg:flex-row overflow-hidden pb-16 lg:pb-0">

            <!-- LEFT COLUMN: SETTINGS PANEL -->
            <div class="w-full lg:w-80 bg-white border-r border-gray-200 flex flex-col shrink-0 overflow-y-auto custom-scrollbar z-10 shadow-[4px_0_10px_-5px_rgba(0,0,0,0.05)]">
                <div class="p-5 space-y-6">

                    <!-- Type Selector -->
                    <div>
                        <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wider mb-2">Tipe Informasi <span class="text-red-500">*</span></h4>
                        <div class="grid grid-cols-2 gap-2 bg-gray-50 p-1.5 rounded-xl border border-gray-200">
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="pengumuman" x-model="formType" class="peer sr-only">
                                <div class="text-center py-2 rounded-lg text-xs font-bold text-gray-500 peer-checked:bg-white peer-checked:text-blue-600 peer-checked:shadow-sm shadow-black/5 transition-all">
                                    <i class="fa-solid fa-bullhorn mr-1 block sm:inline"></i> Pengumuman
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="agenda" x-model="formType" class="peer sr-only">
                                <div class="text-center py-2 rounded-lg text-xs font-bold text-gray-500 peer-checked:bg-white peer-checked:text-purple-600 peer-checked:shadow-sm shadow-black/5 transition-all">
                                    <i class="fa-regular fa-calendar-check mr-1 block sm:inline"></i> Agenda Kegiatan
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Date & Time Info (Dynamic based on type) -->
                    <div class="bg-gray-50 border border-gray-200 p-4 rounded-xl shadow-sm">
                        <!-- If Agenda -->
                        <div x-show="formType === 'agenda'" class="space-y-4">
                            <h4 class="text-xs font-bold text-purple-800 uppercase tracking-wider flex items-center gap-1.5">
                                <i class="fa-regular fa-calendar text-purple-600"></i> Jadwal Pelaksanaan
                            </h4>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-gray-500 uppercase">Waktu Mulai <span class="text-red-500">*</span></label>
                                <input type="datetime-local" id="agenda_start_date" :name="formType === 'agenda' ? 'start_date' : ''" :required="formType === 'agenda'" class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-xs focus:ring-2 focus:ring-green-500 outline-none">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-gray-500 uppercase">Waktu Selesai (Opsional)</label>
                                <input type="datetime-local" id="agenda_end_date" :name="formType === 'agenda' ? 'end_date' : ''" class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-xs focus:ring-2 focus:ring-green-500 outline-none">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-gray-500 uppercase">Lokasi Kegiatan <span class="text-red-500">*</span></label>
                                <input type="text" id="agenda_location" :name="formType === 'agenda' ? 'location' : ''" :required="formType === 'agenda'" placeholder="Misal: Lapangan Desa..." class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-xs focus:ring-2 focus:ring-green-500 outline-none">
                            </div>
                        </div>

                        <!-- If Pengumuman -->
                        <div x-show="formType === 'pengumuman'" class="space-y-4">
                            <h4 class="text-xs font-bold text-blue-800 uppercase tracking-wider flex items-center gap-1.5">
                                <i class="fa-solid fa-stopwatch text-blue-600"></i> Durasi Penayangan
                            </h4>
                            <p class="text-[10px] text-gray-500 mb-2">Tentukan berapa lama pengumuman ini tampil di website publik.</p>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-gray-500 uppercase">Mulai Tayang <span class="text-red-500">*</span></label>
                                <input type="date" id="peng_start_date" :name="formType === 'pengumuman' ? 'start_date' : ''" :required="formType === 'pengumuman'" class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-xs focus:ring-2 focus:ring-green-500 outline-none">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-gray-500 uppercase">Berakhir Pada <span class="text-red-500">*</span></label>
                                <input type="date" id="peng_end_date" :name="formType === 'pengumuman' ? 'end_date' : ''" :required="formType === 'pengumuman'" class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-xs focus:ring-2 focus:ring-green-500 outline-none">
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- RIGHT COLUMN: CONTENT EDITOR -->
            <div class="flex-1 flex flex-col bg-white z-0 overflow-hidden">
                <!-- Title Input -->
                <div class="p-6 pb-2 shrink-0 border-b border-gray-100">
                    <input type="text" id="editor_title" name="title" x-model="itemTitle" required placeholder="Judul Pengumuman atau Agenda..." class="w-full text-2xl sm:text-3xl font-extrabold text-gray-900 placeholder-gray-300 border-none outline-none bg-transparent">
                </div>

                <!-- Quill Container -->
                <div class="flex-1 flex flex-col overflow-hidden bg-gray-50">
                    <div class="flex-1 overflow-y-auto custom-scrollbar flex flex-col p-6">
                        <div class="w-full max-w-3xl mx-auto flex-1 bg-white border border-gray-200 shadow-sm flex flex-col">
                            <div id="info_editor_container" class="border-0 flex-1"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
