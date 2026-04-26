<div x-show="activeTab === 'album'" x-transition.opacity class="space-y-6" x-cloak>
    <!-- Search Album -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-4 transition-all">
        <div class="flex flex-col flex-wrap lg:flex-nowrap md:flex-row gap-3">
            <div class="flex-1 min-w-[200px] relative">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" placeholder="Cari nama album..." class="w-full bg-gray-50 border border-gray-200 text-sm rounded-xl pl-11 pr-4 py-2.5 focus:ring-2 focus:ring-green-500 outline-none transition-all">
            </div>
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
                    @elseif($a->medias->count() > 0)
                        {{-- Collage Preview --}}
                        <div class="grid grid-cols-2 w-full h-full gap-0.5 bg-gray-200">
                            @foreach($a->medias->take(4) as $m)
                                <div class="relative overflow-hidden bg-white">
                                    @if($m->file_type == 'image')
                                        <img src="{{ $m->url }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="Preview">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gray-900 text-white">
                                            <i class="fa-solid fa-play text-[10px] opacity-40"></i>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                            @for($i = $a->medias->count(); $i < 4; $i++)
                                <div class="bg-gray-50 flex items-center justify-center">
                                    <i class="fa-solid fa-image text-gray-200 text-xs"></i>
                                </div>
                            @endfor
                        </div>
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-100 group-hover:bg-gray-200 transition-colors">
                            <i class="fa-solid fa-folder-open text-4xl text-gray-300 group-hover:scale-110 transition-transform"></i>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gray-900/10 group-hover:bg-transparent transition-colors"></div>
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
