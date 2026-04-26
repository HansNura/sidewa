<!-- 1. FULL-SCREEN MODAL: EDITOR ARTIKEL -->
<div x-show="editorOpen" class="fixed inset-0 z-[150] bg-gray-50 flex flex-col overflow-hidden" x-cloak
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-full opacity-0"
    x-transition:enter-end="translate-y-0 opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="translate-y-full opacity-0"
    x-init="
        $nextTick(() => {
            if(!window.contentEditor) {
                window.contentEditor = new Quill('.editor-content', {
                    theme: 'snow',
                    placeholder: 'Mulai menulis konten artikel yang luar biasa di sini...',
                    modules: {
                        toolbar: [
                            [{ 'header': [2, 3, 4, false] }],
                            ['bold', 'italic', 'underline', 'strike'],
                            [{ 'color': [] }, { 'background': [] }],
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                            [{ 'align': [] }],
                            ['link', 'image', 'video'],
                            ['clean']
                        ]
                    }
                });

                window.contentEditor.on('text-change', function() {
                    document.getElementById('editor_konten_html').value = window.contentEditor.root.innerHTML;
                });
            }
        });
    ">

    <form id="editorForm" action="{{ route('admin.artikel.store') }}" method="POST" enctype="multipart/form-data" class="flex-1 flex flex-col h-full overflow-hidden w-full m-0 p-0">
        @csrf
        <input type="hidden" name="id" :value="articleId">
        <input type="hidden" name="konten_html" id="editor_konten_html">
        <input type="hidden" name="status" id="editor_status" x-model="publishStatus">

        <!-- Editor Header -->
        <header class="h-16 bg-white border-b border-gray-200 px-4 sm:px-6 flex items-center justify-between shrink-0 shadow-sm z-20">
            <div class="flex items-center gap-4">
                <button type="button" @click="editorOpen = false" class="text-gray-500 hover:text-red-600 hover:bg-red-50 transition-colors flex items-center gap-2 font-bold text-sm bg-white px-3 py-1.5 rounded-xl border border-gray-200 shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </button>
                <div class="h-6 w-px bg-gray-300 hidden sm:block"></div>
                <div class="hidden sm:block">
                    <h2 class="font-extrabold text-gray-900 text-sm leading-none" x-text="articleTitle || 'Artikel Baru Tanpa Judul'"></h2>
                    <div class="flex items-center gap-2 mt-1">
                        <div class="w-1.5 h-1.5 rounded-full" :class="publishStatus === 'publish' ? 'bg-green-500' : (publishStatus === 'schedule' ? 'bg-blue-500' : 'bg-gray-400')"></div>
                        <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold" x-text="publishStatus"></p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2 sm:gap-3">
                <button type="button" @click="previewModalOpen = true" class="bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 hover:text-green-600 shadow-sm rounded-xl px-4 py-2.5 text-sm font-bold transition-all hidden sm:flex items-center gap-2">
                    <i class="fa-solid fa-desktop"></i> Pratinjau
                </button>
                <button type="submit" @click="publishStatus = 'draft'" class="bg-gray-100 text-gray-600 hover:bg-gray-200 border border-transparent rounded-xl px-4 py-2.5 text-sm font-bold transition-all flex items-center gap-2">
                    <i class="fa-regular fa-floppy-disk"></i> Simpan Draft
                </button>
                <button type="submit" @click="publishStatus = 'publish'" class="bg-green-700 hover:bg-green-800 text-white shadow-md shadow-green-900/10 rounded-xl px-5 py-2.5 text-sm font-bold transition-all flex items-center gap-2 active:scale-95">
                    <i class="fa-solid fa-paper-plane"></i> <span class="hidden sm:inline">Publikasikan</span>
                </button>
            </div>
        </header>

        <!-- Editor Workspace -->
        <div class="flex-1 flex flex-col lg:flex-row overflow-hidden pb-16 lg:pb-0">

            <!-- LEFT COLUMN: CONTENT EDITOR -->
            <div class="flex-1 flex flex-col bg-white z-0 overflow-hidden relative">
                <div class="flex-1 flex flex-col overflow-y-auto custom-scrollbar">
                    <div class="max-w-4xl mx-auto w-full p-6 sm:p-10 space-y-6">

                        <!-- Cover Image Upload -->
                        <div class="relative group">
                            <label class="block cursor-pointer">
                                <!-- Area jika gambar sudah dipilih -->
                                <div x-show="coverPreview" class="relative w-full aspect-[21/9] rounded-2xl overflow-hidden group/cover shadow-sm">
                                    <img :src="coverPreview" class="w-full h-full object-cover transition-transform duration-700 group-hover/cover:scale-105" alt="Cover Preview">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/cover:opacity-100 transition-opacity flex flex-col items-center justify-center text-white">
                                        <i class="fa-solid fa-camera text-3xl mb-2"></i>
                                        <span class="text-xs font-bold uppercase tracking-widest">Ganti Gambar Sampul</span>
                                    </div>
                                </div>
                                
                                <!-- Area jika gambar belum ada -->
                                <div x-show="!coverPreview" class="w-full aspect-[21/9] rounded-2xl border-2 border-dashed border-gray-300 bg-gray-50 flex flex-col items-center justify-center text-gray-400 hover:text-green-600 hover:border-green-500 hover:bg-green-50 transition-colors">
                                    <i class="fa-regular fa-image text-5xl mb-3"></i>
                                    <span class="text-sm font-bold">Pilih Gambar Sampul Artikel</span>
                                    <span class="text-[10px] mt-1">Format: JPG, PNG, WEBP (Max: 2MB). Resolusi ideal 21:9</span>
                                </div>

                                <input type="file" name="cover_image" class="hidden" accept="image/*" @change="
                                    if($event.target.files.length > 0) {
                                        coverPreview = URL.createObjectURL($event.target.files[0]);
                                    }
                                ">
                            </label>
                        </div>

                        <!-- Title Input -->
                        <div class="relative group">
                            <textarea id="editor_title" name="judul" x-model="articleTitle" @input="if(!articleId) { articleSlug = articleTitle.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, ''); }" required placeholder="Tulis judul artikel yang menarik di sini..." class="w-full text-4xl sm:text-5xl font-black text-gray-900 placeholder-gray-300 border-none outline-none bg-transparent focus:ring-0 resize-none overflow-hidden" rows="2" oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"></textarea>
                        </div>

                        <!-- Content Area (Quill) -->
                        <div class="editor-container min-h-[500px]">
                            <div class="editor-content border-0 !p-0 !text-lg !font-serif text-gray-800 leading-relaxed"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: SETTINGS PANEL -->
            <div class="w-full lg:w-80 bg-gray-50/80 border-l border-gray-200 flex flex-col shrink-0 overflow-y-auto custom-scrollbar z-10">
                <div class="p-6 space-y-8">

                    <!-- Publish Settings -->
                    <div class="space-y-3">
                        <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                            <i class="fa-solid fa-sliders"></i> Status Publikasi
                        </h4>
                        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                            <label class="flex items-center gap-3 p-3.5 border-b border-gray-100 cursor-pointer hover:bg-green-50 transition-colors" :class="publishStatus === 'publish' ? 'bg-green-50/50' : ''">
                                <input type="radio" value="publish" x-model="publishStatus" class="w-4 h-4 text-green-600 focus:ring-green-500 border-gray-300"> 
                                <span class="text-sm font-bold text-gray-700">Publikasi Langsung</span>
                            </label>
                            <label class="flex items-center gap-3 p-3.5 border-b border-gray-100 cursor-pointer hover:bg-gray-50 transition-colors" :class="publishStatus === 'draft' ? 'bg-gray-50' : ''">
                                <input type="radio" value="draft" x-model="publishStatus" class="w-4 h-4 text-gray-600 focus:ring-gray-500 border-gray-300"> 
                                <span class="text-sm font-bold text-gray-700">Simpan sebagai Draft</span>
                            </label>
                            <label class="flex items-center gap-3 p-3.5 cursor-pointer hover:bg-blue-50 transition-colors" :class="publishStatus === 'schedule' ? 'bg-blue-50/50' : ''">
                                <input type="radio" value="schedule" x-model="publishStatus" class="w-4 h-4 text-blue-600 focus:ring-blue-500 border-gray-300"> 
                                <span class="text-sm font-bold text-gray-700">Jadwalkan Terbit</span>
                            </label>
                        </div>
                        
                        <div x-show="publishStatus === 'schedule'" x-collapse>
                            <div class="bg-white border border-blue-200 p-4 rounded-2xl shadow-sm mt-2 relative overflow-hidden">
                                <div class="absolute top-0 left-0 w-1 h-full bg-blue-500"></div>
                                <label class="block text-xs font-bold text-gray-700 mb-2">Pilih Tanggal & Waktu Terbit</label>
                                <input type="datetime-local" name="published_at" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-200">

                    <!-- URL / Slug -->
                    <div class="space-y-3">
                        <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                            <i class="fa-solid fa-link"></i> Permalink (SEO)
                        </h4>
                        <div class="relative">
                            <input type="text" id="editor_slug" name="slug_input" x-model="articleSlug" placeholder="otomatis-dari-judul" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none font-mono text-gray-600 shadow-sm transition-all" readonly>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fa-solid fa-lock text-gray-300 text-xs"></i>
                            </div>
                        </div>
                        <p class="text-[10px] text-gray-400 leading-relaxed">URL dibuat otomatis dari judul artikel untuk performa SEO terbaik.</p>
                    </div>

                    <hr class="border-gray-200">

                    <!-- Category -->
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                                <i class="fa-solid fa-folder-tree"></i> Kategori
                            </h4>
                            <button type="button" @click="catModalOpen = true" class="text-[10px] font-bold text-green-600 hover:underline">Tambah Baru</button>
                        </div>
                        <div class="relative">
                            <select name="kategori_id" id="editor_kategori_id" required class="w-full bg-white border border-gray-200 rounded-xl pl-4 pr-10 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none cursor-pointer appearance-none shadow-sm transition-all font-medium text-gray-700">
                                <option value="">Pilih Kategori...</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->nama_kategori }}</option>
                                @endforeach
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                        </div>
                    </div>

                    <hr class="border-gray-200">

                    <!-- Tags / Topik -->
                    <div class="space-y-3" x-data="{ 
                        tagInput: '', 
                        selectedTags: [],
                        availableTags: {{ $tags->pluck('nama_tag') }},
                        
                        addTag() {
                            let tags = this.tagInput.split(',').map(t => t.trim()).filter(t => t);
                            tags.forEach(tag => {
                                if(!this.selectedTags.includes(tag)) {
                                    this.selectedTags.push(tag);
                                }
                            });
                            this.tagInput = '';
                            this.updateHiddenInput();
                        },
                        removeTag(index) {
                            this.selectedTags.splice(index, 1);
                            this.updateHiddenInput();
                        },
                        toggleTag(tag) {
                            let index = this.selectedTags.indexOf(tag);
                            if(index > -1) {
                                this.selectedTags.splice(index, 1);
                            } else {
                                this.selectedTags.push(tag);
                            }
                            this.updateHiddenInput();
                        },
                        updateHiddenInput() {
                            document.getElementById('editor_tags').value = this.selectedTags.join(',');
                        },
                        initTagsFromDB(tagsString) {
                            if(tagsString) {
                                this.selectedTags = tagsString.split(',').map(t => t.trim()).filter(t => t);
                                this.updateHiddenInput();
                            } else {
                                this.selectedTags = [];
                                this.updateHiddenInput();
                            }
                        }
                    }" @open-editor-tags.window="initTagsFromDB($event.detail)">
                        
                        <div class="flex justify-between items-center">
                            <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                                <i class="fa-solid fa-tags"></i> Tags & Topik
                            </h4>
                        </div>
                        
                        <!-- Selected Tags Display -->
                        <div class="flex flex-wrap gap-2 mb-2" x-show="selectedTags.length > 0">
                            <template x-for="(tag, index) in selectedTags" :key="index">
                                <span class="bg-blue-50 text-blue-700 border border-blue-200 text-xs font-bold px-2.5 py-1 rounded-lg flex items-center gap-1.5 shadow-sm">
                                    <span x-text="tag"></span>
                                    <button type="button" @click="removeTag(index)" class="text-blue-400 hover:text-red-500 transition-colors focus:outline-none">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </span>
                            </template>
                        </div>

                        <!-- Tag Input -->
                        <div class="relative">
                            <i class="fa-solid fa-hashtag absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
                            <input type="text" x-model="tagInput" @keydown.enter.prevent="addTag()" @keydown.comma.prevent="addTag()" placeholder="Ketik tag lalu tekan Enter/Koma..." class="w-full bg-white border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none shadow-sm transition-all">
                        </div>
                        <input type="hidden" name="tags_input" id="editor_tags">

                        <!-- Suggested Tags -->
                        <div class="mt-3">
                            <p class="text-[10px] text-gray-400 mb-2 font-bold uppercase tracking-wider">Saran Tag:</p>
                            <div class="flex flex-wrap gap-1.5">
                                <template x-for="tag in availableTags" :key="tag">
                                    <button type="button" @click="toggleTag(tag)" 
                                        :class="selectedTags.includes(tag) ? 'bg-blue-600 text-white border-blue-600 shadow-md' : 'bg-white text-gray-500 border-gray-200 hover:bg-gray-50 hover:border-gray-300'"
                                        class="border text-[10px] px-2 py-1 rounded-lg transition-all flex items-center gap-1 focus:outline-none">
                                        <i class="fa-solid fa-plus text-[8px]" x-show="!selectedTags.includes(tag)"></i>
                                        <i class="fa-solid fa-check text-[8px]" x-show="selectedTags.includes(tag)"></i>
                                        <span x-text="tag"></span>
                                    </button>
                                </template>
                                <span x-show="availableTags.length === 0" class="text-xs text-gray-400 italic">Belum ada tag di database.</span>
                            </div>
                        </div>
                        <p class="text-[10px] text-gray-400 mt-2 leading-relaxed">Sistem akan otomatis membuat tag baru jika Anda mengetikkan tag yang belum ada.</p>
                    </div>

                    <hr class="border-gray-200">

                    <!-- Author Info -->
                    <div>
                        <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2 mb-3">
                            <i class="fa-solid fa-feather"></i> Penulis Artikel
                        </h4>
                        <div class="bg-white border border-gray-200 p-4 rounded-2xl flex items-center gap-4 shadow-sm">
                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 shadow-inner">
                                <i class="fa-solid fa-user text-lg"></i>
                            </div>
                            <div>
                                <p class="text-sm font-extrabold text-gray-900">{{ auth()->user()->name ?? 'Administrator' }}</p>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Verifikator Desa</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </form>
</div>
