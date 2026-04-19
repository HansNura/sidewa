<!-- TAB 1: DAFTAR HALAMAN (TABLE VIEW) -->
<div x-show="activeTab === 'list'" x-transition.opacity class="space-y-6">
    <!-- Filter Panel -->
    <div class="flex flex-col md:flex-row gap-4 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
        <form action="{{ route('admin.halaman.index') }}" method="GET" class="flex-1 relative flex gap-4 w-full">
            <input type="hidden" name="tab" value="list">
            
            <div class="flex-1 relative">
                <i class="fa-solid fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul halaman..." class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none transition-all">
            </div>
            <select name="status" onchange="this.form.submit()" class="w-full md:w-48 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none cursor-pointer font-medium text-gray-700">
                <option value="">Semua Status</option>
                <option value="publish" {{ request('status') == 'publish' ? 'selected' : '' }}>Terpublikasi</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
            </select>
            <button type="submit" class="hidden">Filter</button>
        </form>
    </div>

    <!-- Page Table -->
    <div class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden">
        
        <!-- Bulk Action -->
        <div x-show="selectedRows.length > 0" x-collapse x-cloak class="bg-green-50 border-b border-green-100 px-5 py-3 flex items-center justify-between">
            <span class="text-sm font-bold text-green-800"><span x-text="selectedRows.length"></span> Halaman Terpilih</span>
            <form action="{{ route('admin.halaman.bulk') }}" method="POST" id="bulkActionForm" class="flex gap-2">
                @csrf
                <input type="hidden" name="selected_ids" :value="selectedRows.join(',')">
                <button type="submit" name="action" value="draft" class="text-xs font-bold px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50">Jadikan Draft</button>
                <button type="submit" name="action" value="delete" onclick="return confirm('Halaman sistem (System) akan diabaikan dan tidak akan terhapus. Lanjutkan hapus permanen halaman kustom?')" class="text-xs font-bold px-3 py-1.5 bg-red-600 border border-red-700 text-white rounded-lg hover:bg-red-700">Hapus Permanen</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-gray-50/80 text-gray-500 text-[10px] uppercase tracking-wider border-b border-gray-200">
                        <th class="p-4 w-12 text-center">
                            <input type="checkbox" class="custom-checkbox inline-block" x-model="selectAll" @change="selectedRows = selectAll ? [{{ $pages->pluck('id')->join(',') }}] : []">
                        </th>
                        <th class="p-4 font-bold">Judul Halaman</th>
                        <th class="p-4 font-bold text-center">URL / Slug</th>
                        <th class="p-4 font-bold text-center">Tipe</th>
                        <th class="p-4 font-bold text-center">Status / Update</th>
                        <th class="p-4 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    @forelse($pages as $page)
                    <tr class="hover:bg-gray-50 transition-colors {{ $page->type == 'system' ? 'bg-gray-50/50' : '' }}">
                        <td class="p-4 text-center">
                            <input type="checkbox" class="custom-checkbox inline-block" value="{{ $page->id }}" disabled x-model="selectedRows" @if($page->type !== 'system') :disabled="false" @endif>
                        </td>
                        <td class="p-4">
                            <div class="font-bold text-gray-900 leading-tight">
                                @if($page->type == 'system')
                                    <i class="fa-solid fa-lock text-gray-400 mr-2 text-xs"></i>
                                @else
                                    <i class="fa-regular fa-file-lines text-blue-400 mr-2 text-xs"></i>
                                @endif
                                {{ $page->title }}
                            </div>
                        </td>
                        <td class="p-4 text-center">
                            <span class="font-mono text-[10px] text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                {{ $page->parent ? '/' . $page->parent->slug . '/' . $page->slug : '/' . $page->slug }}
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            @if($page->type == 'system')
                                <span class="bg-gray-200 text-gray-600 text-[9px] font-bold px-2 py-0.5 rounded uppercase tracking-widest">Sistem</span>
                            @else
                                <span class="bg-blue-50 text-blue-600 text-[9px] font-bold px-2 py-0.5 rounded uppercase tracking-widest">Halaman Kustom</span>
                            @endif
                        </td>
                        <td class="p-4 text-center">
                            @if($page->status == 'publish')
                                <span class="bg-green-50 text-green-700 text-[9px] font-black px-2 py-0.5 rounded border border-green-200 uppercase tracking-widest block mb-1">Published</span>
                            @else
                                <span class="bg-gray-100 text-gray-500 text-[9px] font-black px-2 py-0.5 rounded border border-gray-200 uppercase tracking-widest block mb-1">Draft</span>
                            @endif
                            <span class="text-[10px] text-gray-500">{{ $page->updated_at->format('d M Y') }}</span>
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button @click="openEditor({{ $page->id }})" class="w-8 h-8 rounded-lg bg-white text-gray-500 hover:text-amber-600 hover:bg-amber-50 flex items-center justify-center transition-colors border border-gray-200 shadow-sm" title="Edit Konten"><i class="fa-solid fa-pen text-xs"></i></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-12 text-center text-gray-400">
                            <i class="fa-solid fa-file-lines text-3xl mb-3 opacity-30"></i>
                            <p>Belum ada halaman.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
