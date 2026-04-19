<!-- 1. FULL-SCREEN MODAL: EDITOR HALAMAN (Page Builder Simulation) -->
<div x-show="editorOpen" class="fixed inset-0 z-[150] bg-gray-100 flex flex-col overflow-hidden" x-cloak
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-full"
    x-transition:enter-end="translate-y-0" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full"
    x-init="
        $nextTick(() => {
            if(!window.pageEditor) {
                window.pageEditor = new Quill('#page_editor_container', {
                    theme: 'snow',
                    placeholder: 'Tuliskan isi halaman Anda di sini...',
                    modules: {
                        toolbar: [
                            [{ 'header': [1, 2, 3, false] }],
                            ['bold', 'italic', 'underline', 'strike'],
                            ['blockquote', 'code-block'],
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                            [{ 'indent': '-1'}, { 'indent': '+1' }],
                            [{ 'align': [] }],
                            ['link', 'image', 'video'],
                            ['clean']
                        ]
                    }
                });

                window.pageEditor.on('text-change', function() {
                    document.getElementById('editor_content_html').value = window.pageEditor.root.innerHTML;
                });
            }
        });
    ">

    <form id="pageEditorForm" action="{{ route('admin.halaman.store') }}" method="POST" class="flex-1 flex flex-col h-full overflow-hidden w-full m-0 p-0">
        @csrf
        <input type="hidden" name="id" :value="pageId">
        <input type="hidden" name="content_html" id="editor_content_html">
        <input type="hidden" name="status" id="editor_status" x-model="publishStatus">

        <!-- Editor Header -->
        <header class="h-16 bg-white border-b border-gray-200 px-4 sm:px-6 flex items-center justify-between shrink-0 shadow-sm z-20">
            <div class="flex items-center gap-4">
                <button type="button" @click="editorOpen = false" class="text-gray-500 hover:text-gray-800 transition-colors flex items-center gap-2 font-bold text-sm bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-200">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </button>
                <div class="h-6 w-px bg-gray-300 hidden sm:block"></div>
                <div class="hidden sm:block">
                    <h2 class="font-extrabold text-gray-900 text-sm leading-none" x-text="pageTitle || 'Halaman Baru'"></h2>
                    <p class="text-[10px] text-gray-500 mt-1 font-mono">/ <span x-text="pageSlug"></span></p>
                </div>
            </div>

            <div class="flex items-center gap-2 sm:gap-3">
                <button type="submit" @click="publishStatus = 'draft'" class="bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-xl px-4 py-2 text-sm font-bold transition-all w-full sm:w-auto text-center" x-show="pageType === 'custom'">Simpan Draft</button>
                <button type="submit" @click="publishStatus = 'publish'" class="bg-green-700 hover:bg-green-800 text-white shadow-md rounded-xl px-5 py-2 text-sm font-bold transition-all flex items-center gap-2"><i class="fa-solid fa-globe"></i> <span class="hidden sm:inline">Simpan & Publikasikan</span></button>
            </div>
        </header>

        <!-- Editor Workspace (2 Columns) -->
        <div class="flex-1 flex flex-col lg:flex-row overflow-hidden pb-16 lg:pb-0">

            <!-- LEFT COLUMN: PAGE BUILDER -->
            <div class="flex-1 flex flex-col bg-white border-r border-gray-200 z-0 overflow-hidden">
                <div class="p-6 pb-2 shrink-0 bg-white border-b border-gray-100 flex items-center gap-4">
                    <input type="text" id="editor_title" name="title" x-model="pageTitle" @input="generateSlug()" required placeholder="Judul Halaman..." class="w-full text-3xl font-extrabold text-gray-900 placeholder-gray-300 border-none outline-none bg-transparent">
                </div>

                <!-- Quill Container -->
                <div class="flex-1 flex flex-col overflow-hidden">
                    <div class="flex-1 overflow-y-auto custom-scrollbar">
                        <div id="page_editor_container" class="border-0 bg-white min-h-[500px]"></div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: PAGE SETTINGS PANEL -->
            <div class="w-full lg:w-80 bg-gray-50 flex flex-col shrink-0 overflow-y-auto custom-scrollbar z-10 shadow-[-4px_0_10px_rgba(0,0,0,0.02)]">
                <div class="p-5 space-y-6">
                    
                    <!-- Visibility Settings -->
                    <div class="bg-white border border-gray-200 p-4 rounded-xl shadow-sm" x-show="pageType === 'custom'">
                        <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wider mb-3">Status Visibilitas</h4>
                        <select x-model="publishStatus" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none font-semibold cursor-pointer">
                            <option value="publish">Publik (Live)</option>
                            <option value="draft">Draft (Tersembunyi)</option>
                        </select>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 p-4 rounded-xl shadow-sm" x-show="pageType === 'system'">
                        <h4 class="text-xs font-bold text-blue-800 uppercase tracking-wider mb-2"><i class="fa-solid fa-lock mr-1"></i> Halaman Sistem</h4>
                        <p class="text-[10px] text-blue-600 leading-tight">Halaman ini bersifat fundamental untuk website desa. Slug dan Induk tidak dapat diubah.</p>
                    </div>

                    <!-- URL & Structure Settings -->
                    <div>
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">URL & Struktur Halaman</h4>
                        <div class="space-y-3">
                            <div>
                                <label class="text-[10px] text-gray-500 font-bold">Slug URL</label>
                                <div class="flex items-center bg-gray-50 border border-gray-200 rounded-lg overflow-hidden mt-1" :class="pageType === 'system' ? 'opacity-70 bg-gray-100' : ''">
                                    <span class="text-[10px] text-gray-400 pl-2 font-mono">/</span>
                                    <input type="text" id="editor_slug" name="slug_input" x-model="pageSlug" class="w-full bg-transparent px-2 py-2 text-xs font-mono text-gray-700 outline-none" :readonly="pageType === 'system'">
                                </div>
                            </div>
                            
                            <div x-show="pageType === 'custom'">
                                <label class="text-[10px] text-gray-500 font-bold">Halaman Induk (Parent Page)</label>
                                <select name="parent_id" id="editor_parent_id" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-xs focus:ring-2 focus:ring-green-500 outline-none mt-1 cursor-pointer">
                                    <option value="">(Tidak Ada - Top Level)</option>
                                    @php
                                        // Retrieve only parentable pages
                                        $parents = \App\Models\PublicPage::whereNull('parent_id')->orderBy('title')->get();
                                    @endphp
                                    @foreach($parents as $p)
                                        <option value="{{ $p->id }}">{{ $p->title }} (/{{ $p->slug }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- SEO Settings -->
                    <div>
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 flex items-center gap-1.5"><i class="fa-brands fa-google text-blue-500"></i> Pengaturan SEO</h4>
                        <div class="space-y-3">
                            <div>
                                <label class="text-[10px] text-gray-500 font-bold">Meta Title</label>
                                <input type="text" id="editor_meta_title" name="meta_title" :placeholder="pageTitle + ' - SID Desa'" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-xs focus:ring-2 focus:ring-green-500 outline-none mt-1">
                            </div>
                            <div>
                                <label class="text-[10px] text-gray-500 font-bold">Meta Description</label>
                                <textarea id="editor_meta_description" name="meta_description" rows="3" placeholder="Deskripsi singkat yang muncul di hasil pencarian Google..." class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-xs focus:ring-2 focus:ring-green-500 outline-none mt-1 resize-none"></textarea>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </form>
</div>
