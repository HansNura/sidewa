<!-- TAB 2: MANAJEMEN KATEGORI & TAG -->
<div x-show="activeTab === 'kategori'" x-transition.opacity class="grid grid-cols-1 lg:grid-cols-2 gap-6" x-cloak>
    
    <!-- Kategori List -->
    <div class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden flex flex-col">
        <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-green-50 text-green-600 flex items-center justify-center shadow-inner">
                    <i class="fa-solid fa-folder-tree text-sm"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800">Kategori Artikel</h4>
                    <p class="text-[10px] text-gray-400">Kelompokkan artikel berdasarkan topik.</p>
                </div>
            </div>
            <button @click="catModalOpen = true" class="bg-green-700 hover:bg-green-800 text-white shadow-sm rounded-xl px-4 py-2 text-xs font-bold transition-all flex items-center gap-2">
                <i class="fa-solid fa-plus text-[10px]"></i> Tambah
            </button>
        </div>
        <ul class="divide-y divide-gray-100 flex-1">
            @forelse($categories as $cat)
            <li class="p-4 flex justify-between items-center hover:bg-gray-50 transition-colors group">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-tag text-xs"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-800 group-hover:text-green-700 transition-colors">{{ $cat->nama_kategori }}</p>
                        <p class="text-[10px] text-gray-400 font-mono">{{ $cat->slug }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="bg-gray-100 text-gray-600 text-[10px] font-bold px-2.5 py-1 rounded border border-gray-200">{{ $cat->articles_count }} Artikel</span>
                    <form action="{{ route('admin.artikel.kategori.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini? Artikel di dalamnya tidak akan terhapus.')" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-7 h-7 rounded-lg bg-transparent text-gray-300 hover:text-red-500 hover:bg-red-50 flex items-center justify-center transition-all opacity-0 group-hover:opacity-100" title="Hapus Kategori">
                            <i class="fa-solid fa-trash-can text-[10px]"></i>
                        </button>
                    </form>
                </div>
            </li>
            @empty
            <li class="p-8 text-center text-gray-400">
                <i class="fa-solid fa-folder-open text-2xl mb-2 opacity-30"></i>
                <p>Belum ada Kategori.</p>
            </li>
            @endforelse
        </ul>
    </div>

    <!-- Tag List -->
    <div class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden flex flex-col">
        <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shadow-inner">
                    <i class="fa-solid fa-hashtag text-sm"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800">Tags / Topik Populer</h4>
                    <p class="text-[10px] text-gray-400">Tag untuk SEO dan pengelompokan tematik.</p>
                </div>
            </div>
            <button @click="tagModalOpen = true" class="bg-blue-600 hover:bg-blue-700 text-white shadow-sm rounded-xl px-4 py-2 text-xs font-bold transition-all flex items-center gap-2">
                <i class="fa-solid fa-plus text-[10px]"></i> Tambah
            </button>
        </div>
        <div class="p-5 flex-1 flex flex-wrap gap-2 align-start content-start">
            @forelse($tags as $tag)
            <div class="group relative inline-flex">
                <span class="border border-gray-200 bg-gray-50 text-gray-600 text-xs px-3 py-1.5 rounded-lg flex items-center gap-2 hover:border-green-300 hover:bg-green-50 transition-all cursor-default">
                    <i class="fa-solid fa-hashtag text-[8px] text-gray-400"></i>{{ $tag->nama_tag }} 
                    <span class="bg-gray-200 text-[10px] px-1.5 rounded font-bold">{{ $tag->articles_count }}</span>
                </span>
                <form action="{{ route('admin.artikel.tag.destroy', $tag->id) }}" method="POST" onsubmit="return confirm('Hapus tag ini?')" class="absolute -top-1.5 -right-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-5 h-5 rounded-full bg-red-500 text-white flex items-center justify-center shadow-sm hover:bg-red-600 transition-colors">
                        <i class="fa-solid fa-xmark text-[8px]"></i>
                    </button>
                </form>
            </div>
            @empty
            <div class="text-center text-gray-400 w-full pt-4">
                <i class="fa-solid fa-hashtag text-2xl mb-2 opacity-30"></i>
                <p>Belum ada Tag/Topik.</p>
            </div>
            @endforelse
        </div>
    </div>
    
</div>
