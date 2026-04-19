<!-- MODAL: KATEGORI --><div x-show="catModalOpen" class="fixed inset-0 z-[110] flex items-center justify-center p-4 sm:p-6" x-cloak>
    <div x-show="catModalOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
        @click="catModalOpen = false"></div>

    <div x-show="catModalOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden flex flex-col">

        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="font-extrabold text-lg text-gray-900">Tambah Kategori Baru</h3>
            <button @click="catModalOpen = false" class="text-gray-400 hover:text-red-500 transition-colors"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>

        <form action="{{ route('admin.artikel.kategori.store') }}" method="POST" class="p-6 space-y-5">
            @csrf
            <div class="space-y-1">
                <label class="text-xs font-bold text-gray-700 uppercase">Nama Kategori <span class="text-red-500">*</span></label>
                <input type="text" name="nama_kategori" required placeholder="Misal: Info Bantuan..." class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none">
                <p class="text-[10px] text-gray-400 mt-1">Slug akan tergenerate secara otomatis.</p>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end gap-3 mt-4">
                <button type="button" @click="catModalOpen = false" class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-600 bg-white border border-gray-200 hover:bg-gray-100 transition-colors">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-green-700 hover:bg-green-800 shadow-md transition-all flex items-center gap-2"><i class="fa-solid fa-save"></i> Submit</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: PREVIEW WEB PUBLIK (SIMULATOR) -->
<div x-show="previewModalOpen" class="fixed inset-0 z-[200] flex items-center justify-center p-4 sm:p-6" x-cloak>
    <div x-show="previewModalOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/80 backdrop-blur-sm"
        @click="previewModalOpen = false"></div>

    <div x-show="previewModalOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        class="relative bg-gray-100 rounded-3xl shadow-2xl w-full max-w-5xl overflow-hidden flex flex-col h-[90vh]">

        <div class="px-4 py-3 bg-white border-b border-gray-200 flex justify-between items-center shrink-0 shadow-sm z-10">
            <div class="flex gap-2 w-32">
                <div class="w-3 h-3 rounded-full bg-red-400"></div>
                <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                <div class="w-3 h-3 rounded-full bg-green-400"></div>
            </div>

            <div class="flex-1 max-w-md mx-auto bg-gray-100 rounded-lg px-3 py-1.5 flex items-center justify-between text-xs text-gray-500 font-mono">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-lock text-gray-400"></i> desa-sindangmukti.id/berita/<span x-text="(articleTitle ? articleTitle.toLowerCase().replace(/ /g, '-') : 'judul-artikel')"></span>
                </div>
                <i class="fa-solid fa-rotate-right hover:text-gray-800 cursor-pointer"></i>
            </div>

            <div class="flex items-center gap-2 justify-end w-32">
                <div class="bg-gray-100 rounded-lg flex p-1">
                    <button type="button" @click="previewDevice = 'desktop'" :class="previewDevice === 'desktop' ? 'bg-white shadow-sm text-gray-800' : 'text-gray-400'" class="w-7 h-7 rounded flex items-center justify-center transition-all"><i class="fa-solid fa-desktop text-xs"></i></button>
                    <button type="button" @click="previewDevice = 'mobile'" :class="previewDevice === 'mobile' ? 'bg-white shadow-sm text-gray-800' : 'text-gray-400'" class="w-7 h-7 rounded flex items-center justify-center transition-all"><i class="fa-solid fa-mobile-screen text-xs"></i></button>
                </div>
                <div class="w-px h-5 bg-gray-300 mx-1"></div>
                <button type="button" @click="previewModalOpen = false" class="text-gray-400 hover:text-red-500 transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto custom-scrollbar flex justify-center bg-gray-200 p-4 sm:p-8 transition-all duration-500">
            <div class="bg-white shadow-lg overflow-hidden transition-all duration-500 ease-in-out border border-gray-200"
                :class="previewDevice === 'desktop' ? 'w-full max-w-3xl rounded-t-xl min-h-full' : 'w-[375px] h-[812px] rounded-3xl ring-8 ring-gray-800 relative isolate'">

                <div class="p-6 sm:p-10">
                    <!-- Fake Village Web Header -->
                    <div class="flex items-center gap-3 mb-10 pb-4 border-b border-gray-100">
                        <div class="w-10 h-10 bg-green-100 text-green-700 rounded-full flex items-center justify-center"><i class="fa-solid fa-leaf"></i></div>
                        <div>
                            <h4 class="font-bold text-gray-900 leading-none">Pemdes Sindangmukti</h4>
                            <span class="text-[10px] text-gray-500 uppercase tracking-widest">Portal Resmi</span>
                        </div>
                    </div>

                    <!-- Article Body Preview -->
                    <div class="mb-4 flex items-center gap-2">
                        <span class="bg-green-100 text-green-800 text-[10px] font-bold px-2 py-1 rounded">Berita Desa</span>
                        <span class="text-[10px] text-gray-400">{{ \Carbon\Carbon::now()->format('d M Y') }}</span>
                    </div>

                    <h1 class="text-3xl font-extrabold text-gray-900 mb-6 font-serif leading-tight" x-text="articleTitle || 'Judul Artikel Anda Akan Tampil Di Sini'"></h1>
                    
                    <div class="w-full aspect-video bg-gray-100 rounded-xl mb-8 flex items-center justify-center text-gray-400 border border-gray-200">
                        <i class="fa-regular fa-image text-4xl"></i>
                    </div>

                    <div class="prose prose-gray prose-lg max-w-none font-serif text-gray-800 leading-relaxed mb-10" x-html="document.getElementById('editor_konten_html').value || '<p class=\'text-gray-400 italic\'>Konten artikel akan tampil di sini...</p>'"></div>

                    <!-- Tags Preview -->
                    <div class="pt-6 border-t border-gray-100 flex gap-2">
                        <span class="bg-gray-100 text-gray-500 text-xs px-3 py-1 rounded-full">#berita</span>
                        <span class="bg-gray-100 text-gray-500 text-xs px-3 py-1 rounded-full">#update</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
