<!-- TAB 2: MANAJEMEN KATEGORI PRODUK HUKUM -->
<div x-show="activeTab === 'kategori'" x-transition.opacity class="space-y-6" x-cloak>
    <div class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden flex flex-col max-w-4xl mx-auto">
        <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <div>
                <h4 class="font-bold text-gray-800">Daftar Kategori JDIH</h4>
                <p class="text-[10px] text-gray-500 mt-0.5">Kelola tipe/hierarki produk hukum yang ada di desa.</p>
            </div>
            <button @click="openCategoryForm()" class="text-xs font-bold text-primary-600 bg-primary-50 px-3 py-1.5 rounded-lg border border-primary-200 shadow-sm hover:bg-primary-100 transition-colors flex items-center gap-1.5">
                <i class="fa-solid fa-plus"></i> Kategori Baru
            </button>
        </div>

        <div class="overflow-x-auto p-4">
            <ul class="divide-y divide-gray-100 border border-gray-100 rounded-xl overflow-hidden bg-white">
                @foreach($categories as $cat)
                <li class="p-4 flex justify-between items-center hover:bg-gray-50 transition-colors">
                    <div>
                        <p class="text-sm font-bold text-gray-800">{{ $cat->name }}</p>
                        <p class="text-[10px] text-gray-500 mt-0.5">{{ $cat->description }}</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <p class="text-[10px] font-bold text-gray-400 uppercase">Jumlah Dokumen</p>
                            <p class="text-sm font-bold text-gray-700">{{ $cat->documents_count }} File</p>
                        </div>
                        <div class="flex gap-2 border-l border-gray-200 pl-4 items-center">
                            <button @click="openCategoryForm({{ $cat->id }}, '{{ addslashes($cat->name) }}', '{{ addslashes($cat->description ?? '') }}')" class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-amber-500 hover:bg-amber-50 transition-colors" title="Edit"><i class="fa-solid fa-pen text-xs"></i></button>
                            <form action="{{ route('admin.jdih.destroyCategory', $cat->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus kategori ini? Kategori tidak dapat dihapus jika masih ada dokumen tertaut.')">
                                @csrf
                                <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 transition-colors" title="Hapus"><i class="fa-solid fa-trash text-xs"></i></button>
                            </form>
                        </div>
                    </div>
                </li>
                @endforeach

                @if($categories->count() == 0)
                <li class="p-8 text-center text-gray-400">Belum ada kategori tersedia.</li>
                @endif
            </ul>
        </div>
    </div>
</div>
