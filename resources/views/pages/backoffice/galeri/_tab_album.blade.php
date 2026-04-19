<div x-show="activeTab === 'album'" x-transition.opacity class="space-y-6" x-cloak>
    <!-- Search Album -->
    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
        <div class="relative w-full md:w-96">
            <i class="fa-solid fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" placeholder="Cari nama album..." class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none transition-all">
        </div>
    </div>

    <!-- Album Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($albums as $a)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden group">
                <!-- Cover -->
                <div class="w-full aspect-video bg-gray-200 relative overflow-hidden cursor-pointer" onclick="window.location='{{ route('admin.galeri.index', ['tab' => 'media', 'album_id' => $a->id]) }}'">
                    @if($a->cover_image)
                        <img src="{{ asset('storage/'.$a->cover_image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="Cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-100"><i class="fa-solid fa-folder-open text-4xl text-gray-300"></i></div>
                    @endif
                    <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-colors"></div>
                    <div class="absolute bottom-2 right-2 bg-black/60 text-white text-[10px] font-bold px-2 py-1 rounded backdrop-blur-md flex items-center gap-1">
                        <i class="fa-regular fa-images"></i> {{ $a->medias_count }} Items
                    </div>
                </div>
                <!-- Info -->
                <div class="p-4">
                    <h3 class="font-extrabold text-gray-900 leading-tight mb-1 group-hover:text-green-600 transition-colors">{{ $a->nama_album }}</h3>
                    <p class="text-xs text-gray-500 mb-4 line-clamp-2">{{ $a->deskripsi ?: 'Tidak ada deskripsi.' }}</p>
                    <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                        <span class="text-[10px] text-gray-400 font-medium">Dibuat: {{ $a->created_at->format('d M Y') }}</span>
                        <div class="flex gap-2">
                            <form action="{{ route('admin.galeri.album.destroy', $a->id) }}" method="POST" onsubmit="return confirm('Yakin hapus album ini? Media di dalamnya tidak akan terhapus namun statusnya menjadi tanpa album.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors" title="Hapus Album"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-gray-400">
                <i class="fa-solid fa-folder-open text-3xl mb-3 opacity-30"></i>
                <p>Belum ada album dibuat.</p>
            </div>
        @endforelse
    </div>
</div>
