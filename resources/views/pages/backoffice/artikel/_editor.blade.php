<!-- 1. FULL-SCREEN MODAL: EDITOR ARTIKEL -->
<div x-show="editorOpen" class="fixed inset-0 z-[150] bg-gray-100 flex flex-col overflow-hidden" x-cloak
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-full"
    x-transition:enter-end="translate-y-0" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full"
    x-init="
        $nextTick(() => {
            if(!window.contentEditor) {
                window.contentEditor = new Quill('.editor-content', {
                    theme: 'snow',
                    placeholder: 'Mulai menulis cerita Anda di sini...',
                    modules: {
                        toolbar: [
                            ['bold', 'italic', 'underline'],
                            [{ 'header': [2, 3, false] }],
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                            ['link', 'image']
                        ]
                    }
                });

                // Update hidden input on text change
                window.contentEditor.on('text-change', function() {
                    document.getElementById('editor_konten_html').value = window.contentEditor.root.innerHTML;
                });
            }
        });
    ">

    <form id="editorForm" action="{{ route('admin.artikel.store') }}" method="POST" class="flex-1 flex flex-col h-full overflow-hidden w-full m-0 p-0">
        @csrf
        <input type="hidden" name="id" :value="articleId">
        <input type="hidden" name="konten_html" id="editor_konten_html">
        <input type="hidden" name="status" id="editor_status" x-model="publishStatus">

        <!-- Editor Header -->
        <header class="h-16 bg-white border-b border-gray-200 px-4 sm:px-6 flex items-center justify-between shrink-0 shadow-sm z-20">
            <div class="flex items-center gap-4">
                <button type="button" @click="editorOpen = false" class="text-gray-500 hover:text-gray-800 transition-colors flex items-center gap-2 font-bold text-sm bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-200">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </button>
                <div class="h-6 w-px bg-gray-300 hidden sm:block"></div>
                <div class="hidden sm:block">
                    <h2 class="font-extrabold text-gray-900 text-sm leading-none" x-text="articleTitle || 'Artikel Tanpa Judul'"></h2>
                    <p class="text-[10px] text-gray-500 mt-1">Status: <span class="uppercase tracking-widest font-bold" :class="publishStatus === 'publish' ? 'text-green-600' : 'text-gray-400'" x-text="publishStatus"></span></p>
                </div>
            </div>

            <div class="flex items-center gap-2 sm:gap-3">
                <button type="button" @click="previewModalOpen = true" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 shadow-sm rounded-xl px-4 py-2 text-sm font-bold transition-all hidden sm:block"><i class="fa-solid fa-desktop mr-1"></i> Preview</button>
                <button type="submit" @click="publishStatus = 'draft'" class="bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-xl px-4 py-2 text-sm font-bold transition-all">Simpan Draft</button>
                <button type="submit" @click="publishStatus = 'publish'" class="bg-green-700 hover:bg-green-800 text-white shadow-md rounded-xl px-5 py-2 text-sm font-bold transition-all flex items-center gap-2"><i class="fa-solid fa-paper-plane"></i> <span class="hidden sm:inline">Publikasikan</span></button>
            </div>
        </header>

        <!-- Editor Workspace -->
        <div class="flex-1 flex flex-col lg:flex-row overflow-hidden pb-16 lg:pb-0">

            <!-- LEFT COLUMN: CONTENT EDITOR -->
            <div class="flex-1 flex flex-col bg-white border-r border-gray-200 z-0 overflow-hidden">
                <!-- Title Input -->
                <div class="p-6 pb-2 shrink-0">
                    <input type="text" id="editor_title" name="judul" x-model="articleTitle" required placeholder="Tulis judul artikel di sini..." class="w-full text-3xl font-extrabold text-gray-900 placeholder-gray-300 border-none outline-none bg-transparent focus:ring-0">
                </div>

                <!-- Content Area (Quill) -->
                <div class="flex-1 flex flex-col overflow-hidden">
                    <div class="flex-1 overflow-y-auto custom-scrollbar">
                        <div class="editor-content border-0"></div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: SETTINGS PANEL -->
            <div class="w-full lg:w-80 bg-gray-50 flex flex-col shrink-0 overflow-y-auto custom-scrollbar z-10 shadow-[-4px_0_10px_rgba(0,0,0,0.02)]">
                <div class="p-5 space-y-6">

                    <!-- Publish Settings -->
                    <div class="bg-white border border-gray-200 p-4 rounded-xl shadow-sm">
                        <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wider mb-3">Pengaturan Publikasi</h4>
                        <div class="space-y-3">
                            <label class="flex items-center gap-2 text-sm cursor-pointer">
                                <input type="radio" value="publish" x-model="publishStatus" class="text-green-600 focus:ring-green-500"> Publish Langsung
                            </label>
                            <label class="flex items-center gap-2 text-sm cursor-pointer">
                                <input type="radio" value="draft" x-model="publishStatus" class="text-gray-600 focus:ring-gray-500"> Simpan sbg Draft
                            </label>
                            <label class="flex items-center gap-2 text-sm cursor-pointer">
                                <input type="radio" value="schedule" x-model="publishStatus" class="text-blue-600 focus:ring-blue-500"> Jadwalkan
                            </label>

                            <div x-show="publishStatus === 'schedule'" x-collapse class="pt-2">
                                <input type="datetime-local" name="published_at" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-xs outline-none focus:ring-1 focus:ring-green-500">
                            </div>
                        </div>
                    </div>

                    <!-- URL / Slug -->
                    <div>
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">URL Slug (SEO)</h4>
                        <input type="text" id="editor_slug" name="slug_input" placeholder="otomatis-dari-judul" class="w-full bg-white border border-gray-200 rounded-xl px-3 py-2 text-xs focus:ring-2 focus:ring-green-500 outline-none font-mono text-gray-500">
                    </div>

                    <!-- Category & Tags -->
                    <div>
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Kategori</h4>
                        <select name="kategori_id" id="editor_kategori_id" required class="w-full bg-white border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none cursor-pointer">
                            <option value="">Pilih Kategori...</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Tags / Topik</h4>
                        <input type="text" name="tags_input" placeholder="Misal: Jalan, Bansos (pisahkan koma)" class="w-full bg-white border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none">
                        <p class="text-[10px] text-gray-400 mt-1">Sistem akan buat otomatis bila blm ada.</p>
                    </div>

                    <!-- Author Info -->
                    <div>
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Penulis</h4>
                        <div class="bg-white border border-gray-200 p-3 rounded-xl flex items-center gap-3">
                            <i class="fa-solid fa-circle-user text-2xl text-gray-300"></i>
                            <div>
                                <p class="text-xs font-bold text-gray-800">{{ auth()->user()->name ?? 'Administrator' }}</p>
                                <p class="text-[9px] text-gray-400">Verifikator</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </form>
</div>
