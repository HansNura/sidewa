<div x-show="activeTab === 'artikel'" x-transition.opacity class="space-y-6">
    <!-- Filter Panel -->
    <div class="flex flex-col md:flex-row gap-4">
        <form action="{{ route('admin.artikel.index') }}" method="GET" class="flex-1 relative flex gap-4">
            <input type="hidden" name="tab" value="artikel">
            
            <div class="flex-1 relative">
                <i class="fa-solid fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul artikel..." class="w-full bg-white border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none shadow-sm">
            </div>
            <select name="status" onchange="this.form.submit()" class="w-full md:w-40 bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none shadow-sm cursor-pointer">
                <option value="">Semua Status</option>
                <option value="publish" {{ request('status') == 'publish' ? 'selected' : '' }}>Terpublikasi</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="schedule" {{ request('status') == 'schedule' ? 'selected' : '' }}>Terjadwal</option>
            </select>
            <select name="kategori_id" onchange="this.form.submit()" class="w-full md:w-48 bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none shadow-sm cursor-pointer">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('kategori_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nama_kategori }}</option>
                @endforeach
            </select>
            <button type="submit" class="hidden">Filter</button>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden">

        <!-- Bulk Action Bar -->
        <div x-show="selectedRows.length > 0" x-collapse x-cloak class="bg-green-50 border-b border-green-100 px-5 py-3 flex items-center justify-between">
            <span class="text-sm font-bold text-green-800"><span x-text="selectedRows.length"></span> Artikel Terpilih</span>
            <form action="{{ route('admin.artikel.bulk') }}" method="POST" id="bulkActionForm" class="flex gap-2">
                @csrf
                <input type="hidden" name="selected_ids" :value="selectedRows.join(',')">
                <button type="submit" name="action" value="draft" class="text-xs font-bold px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50">Jadikan Draft</button>
                <button type="submit" name="action" value="delete" onclick="return confirm('Hapus massal artikel terpilih?')" class="text-xs font-bold px-3 py-1.5 bg-red-600 border border-red-700 text-white rounded-lg hover:bg-red-700">Hapus Permanen</button>
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
                                    <img src="{{ $a->thumbnail_url }}" class="w-12 h-10 object-cover rounded shadow-sm border border-gray-200" alt="Thumbnail">
                                @else
                                    <div class="w-12 h-10 bg-gray-100 text-gray-300 rounded flex items-center justify-center border border-gray-200 border-dashed">
                                        <i class="fa-solid fa-image"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="font-bold text-gray-900 leading-tight w-64 truncate" title="{{ $a->judul }}">{{ $a->judul }}</div>
                                    <div class="text-[10px] text-gray-400 mt-0.5"><i class="fa-solid fa-eye mr-1"></i> {{ number_format($a->view_count) }} Views</div>
                                </div>
                            </div>
                        </td>
                        <td class="p-4 text-center">
                            <span class="bg-gray-100 text-gray-600 text-[10px] font-bold px-2 py-1 rounded">{{ $a->category ? $a->category->nama_kategori : 'Uncategorized' }}</span>
                        </td>
                        <td class="p-4 text-center">
                            <span class="text-xs font-semibold text-gray-700">{{ $a->user ? $a->user->name : 'Sistem' }}</span>
                        </td>
                        <td class="p-4 text-center">
                            @if($a->status == 'publish')
                                <span class="bg-green-50 text-green-700 text-[9px] font-black px-2 py-0.5 rounded border border-green-200 uppercase tracking-widest block mb-1">Published</span>
                                <span class="text-[10px] text-gray-500">{{ $a->published_at ? $a->published_at->format('d M Y') : '-' }}</span>
                            @elseif($a->status == 'schedule')
                                <span class="bg-blue-50 text-blue-600 text-[9px] font-black px-2 py-0.5 rounded border border-blue-200 uppercase tracking-widest block mb-1"><i class="fa-regular fa-clock mr-1"></i>Scheduled</span>
                                <span class="text-[10px] text-blue-600 font-semibold">{{ $a->published_at ? $a->published_at->format('d M Y') : '-' }}</span>
                            @else
                                <span class="bg-gray-100 text-gray-500 text-[9px] font-black px-2 py-0.5 rounded border border-gray-200 uppercase tracking-widest block mb-1">Draft</span>
                                <span class="text-[10px] text-gray-400 italic">Disimpan {{ $a->updated_at->diffForHumans() }}</span>
                            @endif
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button @click="openEditor({{ $a->id }})" class="w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:text-amber-600 hover:bg-amber-50 flex items-center justify-center transition-colors border border-gray-200 shadow-sm" title="Edit"><i class="fa-solid fa-pen text-xs"></i></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-12 text-center text-gray-400">
                            <i class="fa-solid fa-file-lines text-3xl mb-3 opacity-30"></i>
                            <p>Belum ada artikel ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100 bg-gray-50 flex justify-between items-center shrink-0">
            <span class="text-[11px] text-gray-500 font-medium">Menampilkan {{ $articles->firstItem() ?? 0 }}-{{ $articles->lastItem() ?? 0 }} dari {{ $articles->total() }} Artikel</span>
            <div class="flex gap-1">
                {{ $articles->links('pagination::simple-tailwind') }}
            </div>
        </div>
    </div>
</div>
