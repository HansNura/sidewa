<!-- TAB 2: MANAJEMEN KATEGORI & TAG -->
<div x-show="activeTab === 'kategori'" x-transition.opacity class="grid grid-cols-1 lg:grid-cols-2 gap-6" x-cloak>
    
    <!-- Kategori List -->
    <div class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden flex flex-col">
        <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <h4 class="font-bold text-gray-800">Kategori Artikel</h4>
            <button @click="catModalOpen = true" class="text-xs font-bold text-green-600 hover:underline">Tambah Baru</button>
        </div>
        <ul class="divide-y divide-gray-100 flex-1">
            @forelse($categories as $cat)
            <li class="p-4 flex justify-between items-center hover:bg-gray-50">
                <div>
                    <p class="text-sm font-bold text-gray-800">{{ $cat->nama_kategori }}</p>
                    <p class="text-[10px] text-gray-500">{{ $cat->slug }}</p>
                </div>
                <span class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded font-mono">{{ $cat->articles_count }} Artikel</span>
            </li>
            @empty
            <li class="p-8 text-center text-gray-400">Belum ada Kategori.</li>
            @endforelse
        </ul>
    </div>

    <!-- Tag List -->
    <div class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden flex flex-col">
        <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <h4 class="font-bold text-gray-800">Tags / Topik Populer</h4>
        </div>
        <div class="p-5 flex-1 flex flex-wrap gap-2 align-start content-start">
            @forelse($tags as $tag)
            <span class="border border-gray-200 bg-gray-50 text-gray-600 text-xs px-3 py-1.5 rounded-lg flex items-center gap-2 hover:border-gray-300 cursor-pointer">
                #{{ $tag->nama_tag }} <span class="bg-gray-200 text-[10px] px-1.5 rounded">{{ $tag->articles_count }}</span>
            </span>
            @empty
            <p class="text-center text-gray-400 w-full pt-4">Belum ada Tag/Topik.</p>
            @endforelse
        </div>
    </div>
    
</div>
