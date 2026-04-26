<div x-show="activeTab === 'media'" x-transition.opacity class="space-y-6">
    <!-- Filter Panel -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-4 transition-all">
        <div class="flex flex-col flex-wrap lg:flex-nowrap md:flex-row gap-3">
            <div class="flex-1 min-w-[200px] relative">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" placeholder="Cari nama file atau tag..." class="w-full bg-gray-50 border border-gray-200 text-sm rounded-xl pl-11 pr-4 py-2.5 focus:ring-2 focus:ring-green-500 outline-none transition-all">
            </div>
            <form action="{{ route('admin.galeri.index') }}" method="GET" class="w-full md:w-48 relative shrink-0">
                <input type="hidden" name="tab" value="media">
                <select name="album_id" onchange="this.form.submit()" class="w-full bg-gray-50 border border-gray-200 text-sm rounded-xl px-4 py-2.5 appearance-none focus:ring-2 focus:ring-green-500 outline-none cursor-pointer font-medium text-gray-700">
                    <option value="">Semua Album</option>
                    @foreach($albums as $ab)
                        <option value="{{ $ab->id }}" {{ request('album_id') == $ab->id ? 'selected' : '' }}>{{ $ab->nama_album }}</option>
                    @endforeach
                </select>
                <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
            </form>
        </div>
    </div>

    <!-- Media Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
        @forelse($medias as $m)
            <div class="bg-gray-200 rounded-xl aspect-square relative group overflow-hidden border border-gray-200 cursor-pointer shadow-sm">
                @if($m->file_type == 'image')
                    <img src="{{ $m->url }}" alt="{{ $m->file_name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                @else
                    <video src="{{ $m->url }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" preload="metadata"></video>
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-10">
                        <div class="w-10 h-10 bg-white/30 backdrop-blur-sm rounded-full flex items-center justify-center text-white">
                            <i class="fa-solid fa-play"></i>
                        </div>
                    </div>
                @endif
                
                <div class="absolute inset-0 media-overlay opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-between p-2 sm:p-3 z-20">
                    <div class="flex justify-end gap-1">
                        <form action="{{ route('admin.galeri.media.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Yakin hapus media ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-7 h-7 bg-white/20 hover:bg-white text-white hover:text-red-500 rounded backdrop-blur-sm transition-colors flex items-center justify-center"><i class="fa-solid fa-trash text-[10px]"></i></button>
                        </form>
                    </div>
                    <div class="text-white">
                        <p class="text-[10px] font-bold truncate">{{ $m->file_name }}</p>
                        <div class="flex justify-between items-center mt-1">
                            <span class="text-[8px] bg-green-600 px-1.5 py-0.5 rounded truncate max-w-[60px]">{{ $m->album ? $m->album->nama_album : 'Umum' }}</span>
                            <button @click="openDetail({
                                id: {{ $m->id }},
                                url: '{{ $m->url }}',
                                title: '{{ $m->file_name }}',
                                album: '{{ $m->album ? $m->album->nama_album : 'Tanpa Album' }}',
                                date: '{{ $m->created_at->format('d M Y') }}',
                                size: '{{ $m->formatted_size }}',
                                type: '{{ $m->mime_type }}',
                                uploader: '{{ $m->uploader_name }}'
                            })" class="text-[9px] hover:text-green-300 font-bold flex items-center gap-1 shrink-0">Detail <i class="fa-solid fa-arrow-right"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-gray-400">
                <i class="fa-solid fa-images text-3xl mb-3 opacity-30"></i>
                <p>Belum ada media terunggah pada album ini.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8 flex justify-center">
        {{ $medias->appends(request()->query())->links() }}
    </div>
</div>
