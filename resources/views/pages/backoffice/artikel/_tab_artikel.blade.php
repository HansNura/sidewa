<div x-show="activeTab === 'artikel'" x-transition.opacity class="space-y-6">
    <!-- Filter Panel -->
    <section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-4 transition-all">
        <form method="GET" action="{{ route('admin.artikel.index') }}"
            class="flex flex-col flex-wrap lg:flex-nowrap md:flex-row gap-3">
            <input type="hidden" name="tab" value="artikel">

            <!-- Search -->
            <div class="flex-1 min-w-[200px] relative">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Judul Artikel..."
                    class="w-full bg-gray-50 border border-gray-200 text-sm rounded-xl pl-11 pr-4 py-2.5 focus:ring-2 focus:ring-green-500 outline-none transition-all">
            </div>

            <!-- Filter Status -->
            <div class="w-full md:w-40 relative shrink-0">
                <select name="status" onchange="this.form.submit()"
                    class="w-full bg-gray-50 border border-gray-200 text-sm rounded-xl px-4 py-2.5 appearance-none focus:ring-2 focus:ring-green-500 outline-none cursor-pointer font-medium text-gray-700">
                    <option value="">Semua Status</option>
                    <option value="publish" {{ request('status') == 'publish' ? 'selected' : '' }}>Terpublikasi</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="schedule" {{ request('status') == 'schedule' ? 'selected' : '' }}>Terjadwal</option>
                </select>
                <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
            </div>

            <!-- Filter Kategori -->
            <div class="w-full md:w-48 relative shrink-0">
                <select name="kategori_id" onchange="this.form.submit()"
                    class="w-full bg-gray-50 border border-gray-200 text-sm rounded-xl px-4 py-2.5 appearance-none focus:ring-2 focus:ring-green-500 outline-none cursor-pointer font-medium text-gray-700">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('kategori_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nama_kategori }}</option>
                    @endforeach
                </select>
                <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
            </div>
            <button type="submit" class="sr-only">Cari</button>
        </form>
    </section>

    <!-- Data Table -->
    <div class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden">

        <!-- Bulk Action Bar -->
        <div x-show="selectedRows.length > 0" x-collapse x-cloak class="bg-green-50 border-b border-green-100 px-5 py-3 flex items-center justify-between">
            <span class="text-sm font-bold text-green-800"><span x-text="selectedRows.length"></span> Artikel Terpilih</span>
            <form action="{{ route('admin.artikel.bulk') }}" method="POST" id="bulkActionForm" class="flex gap-2">
                @csrf
                <input type="hidden" name="selected_ids" :value="selectedRows.join(',')">
                <button type="submit" name="action" value="draft" class="text-xs font-bold px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50 transition-colors">Jadikan Draft</button>
                <button type="submit" name="action" value="delete" onclick="return confirm('Hapus massal artikel terpilih?')" class="text-xs font-bold px-3 py-1.5 bg-red-600 border border-red-700 text-white rounded-lg hover:bg-red-700 transition-colors">Hapus Permanen</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-gray-50/80 text-gray-500 text-[10px] uppercase tracking-wider border-b border-gray-200">
                        <th class="p-4 w-12 text-center"><input type="checkbox" class="custom-checkbox inline-block" x-model="selectAll" @change="selectedRows = selectAll ? [{{ collect($articles->items())->pluck('id')->join(',') }}] : []"></th>
                        <th class="p-4 font-bold">Judul Artikel & Media</th>
                        <th class="p-4 font-bold text-center">Kategori</th>
                        <th class="p-4 font-bold text-center">Penulis</th>
                        <th class="p-4 font-bold text-center">Status / Tgl Publish</th>
                        <th class="p-4 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    @forelse($articles as $a)
                    <tr class="hover:bg-gray-50 transition-colors group" :class="selectedRows.includes({{ $a->id }}) ? 'bg-green-50/30' : ''">
                        <td class="p-4 text-center">
                            <input type="checkbox" class="custom-checkbox inline-block" value="{{ $a->id }}" x-model="selectedRows">
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                @if($a->cover_image)
                                    <img src="{{ $a->thumbnail_url }}" class="w-12 h-10 object-cover rounded-lg shadow-sm border border-gray-200" alt="Thumbnail">
                                @else
                                    <div class="w-12 h-10 bg-gray-100 text-gray-300 rounded-lg flex items-center justify-center border border-gray-200 border-dashed">
                                        <i class="fa-solid fa-image"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="font-bold text-gray-900 leading-tight w-64 truncate group-hover:text-green-700 transition-colors" title="{{ $a->judul }}">{{ $a->judul }}</div>
                                    <div class="text-[10px] text-gray-400 mt-0.5"><i class="fa-solid fa-eye mr-1"></i> {{ number_format($a->view_count) }} Views</div>
                                </div>
                            </div>
                        </td>
                        <td class="p-4 text-center">
                            <span class="bg-gray-100 text-gray-600 text-[10px] font-bold px-2.5 py-1 rounded border border-gray-200">{{ $a->category ? $a->category->nama_kategori : 'Uncategorized' }}</span>
                        </td>
                        <td class="p-4 text-center">
                            <span class="text-xs font-semibold text-gray-700">{{ $a->user ? $a->user->name : 'Sistem' }}</span>
                        </td>
                        <td class="p-4 text-center">
                            @if($a->status == 'publish')
                                <span class="bg-green-50 text-green-700 text-[10px] font-bold px-2.5 py-1 rounded border border-green-200 uppercase tracking-wider"><i class="fa-solid fa-globe mr-1"></i>Published</span>
                                <div class="text-[10px] text-gray-500 mt-1">{{ $a->published_at ? $a->published_at->format('d M Y') : '-' }}</div>
                            @elseif($a->status == 'schedule')
                                <span class="bg-blue-50 text-blue-700 text-[10px] font-bold px-2.5 py-1 rounded border border-blue-200 uppercase tracking-wider"><i class="fa-regular fa-clock mr-1"></i>Scheduled</span>
                                <div class="text-[10px] text-blue-600 font-semibold mt-1">{{ $a->published_at ? $a->published_at->format('d M Y') : '-' }}</div>
                            @else
                                <span class="bg-gray-50 text-gray-500 text-[10px] font-bold px-2.5 py-1 rounded border border-gray-200 uppercase tracking-wider"><i class="fa-solid fa-clipboard text-gray-400 mr-1"></i>Draft</span>
                                <div class="text-[10px] text-gray-400 italic mt-1">{{ $a->updated_at->diffForHumans() }}</div>
                            @endif
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button @click="openEditor({{ $a->id }})" class="w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:text-amber-600 hover:bg-amber-50 flex items-center justify-center transition-colors border border-gray-200 shadow-sm" title="Edit Artikel"><i class="fa-solid fa-pen text-xs"></i></button>

                                <form action="{{ route('admin.artikel.bulk') }}" method="POST" class="inline" onsubmit="return confirm('Hapus artikel ini secara permanen?')">
                                    @csrf
                                    <input type="hidden" name="selected_ids" value="{{ $a->id }}">
                                    <button type="submit" name="action" value="delete" class="w-8 h-8 rounded-lg bg-gray-50 text-gray-400 hover:text-red-700 hover:bg-red-50 flex items-center justify-center transition-colors border border-gray-200 shadow-sm" title="Hapus Artikel"><i class="fa-solid fa-trash-can text-xs"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-400">Belum ada artikel ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($articles->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $articles->links() }}
            </div>
        @endif
    </div>
</div>
