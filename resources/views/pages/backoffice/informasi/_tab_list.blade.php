<!-- TAB 1: DAFTAR INFORMASI (TABLE VIEW) -->
<div x-show="activeTab === 'list'" x-transition.opacity class="space-y-6">
    <!-- Filter Panel -->
    <section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-4 transition-all">
        <form action="{{ route('admin.informasi.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
            <input type="hidden" name="tab" value="list">
            
            <!-- Search -->
            <div class="flex-1 min-w-[200px] relative">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul kegiatan atau pengumuman..." class="w-full bg-gray-50 border border-gray-200 text-sm rounded-xl pl-11 pr-4 py-2.5 focus:ring-2 focus:ring-green-500 outline-none transition-all">
            </div>
            
            <!-- Filter Type -->
            <div class="w-full md:w-48 relative shrink-0">
                <select name="type" onchange="this.form.submit()" class="w-full bg-gray-50 border border-gray-200 text-sm rounded-xl px-4 py-2.5 appearance-none focus:ring-2 focus:ring-green-500 outline-none cursor-pointer font-medium text-gray-700">
                    <option value="">Semua Tipe</option>
                    <option value="pengumuman" {{ request('type') == 'pengumuman' ? 'selected' : '' }}>Pengumuman Resmi</option>
                    <option value="agenda" {{ request('type') == 'agenda' ? 'selected' : '' }}>Agenda Kegiatan</option>
                </select>
                <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
            </div>
            
            <!-- Filter Status -->
            <div class="w-full md:w-48 relative shrink-0">
                <select name="status" onchange="this.form.submit()" class="w-full bg-gray-50 border border-gray-200 text-sm rounded-xl px-4 py-2.5 appearance-none focus:ring-2 focus:ring-green-500 outline-none cursor-pointer font-medium text-gray-700">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif (Tampil)</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Selesai/Expired</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
                <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
            </div>
            <button type="submit" class="hidden">Filter</button>
        </form>
    </section>

    <!-- Page Table -->
    <div class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden">
        
        <!-- Bulk Action -->
        <div x-show="selectedRows.length > 0" x-collapse x-cloak class="bg-green-50 border-b border-green-100 px-5 py-3 flex items-center justify-between">
            <span class="text-sm font-bold text-green-800"><span x-text="selectedRows.length"></span> Item Terpilih</span>
            <form action="{{ route('admin.informasi.bulk') }}" method="POST" id="bulkActionForm" class="flex gap-2">
                @csrf
                <input type="hidden" name="selected_ids" :value="selectedRows.join(',')">
                <button type="submit" name="action" value="archive" class="text-xs font-bold px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50 transition-colors">Arsipkan</button>
                <button type="submit" name="action" value="delete" onclick="return confirm('Hapus permanen item terpilih?')" class="text-xs font-bold px-3 py-1.5 bg-red-600 border border-red-700 text-white rounded-lg hover:bg-red-700 transition-colors">Hapus</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-gray-50/80 text-gray-500 text-[10px] uppercase tracking-wider border-b border-gray-200">
                        <th class="p-4 w-12 text-center">
                            <input type="checkbox" class="custom-checkbox inline-block" x-model="selectAll" @change="selectedRows = selectAll ? [{{ $informasi->pluck('id')->join(',') }}] : []">
                        </th>
                        <th class="p-4 font-bold">Judul & Tipe Informasi</th>
                        <th class="p-4 font-bold text-center">Waktu Pelaksanaan / Durasi</th>
                        <th class="p-4 font-bold">Lokasi</th>
                        <th class="p-4 font-bold text-center">Status</th>
                        <th class="p-4 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    @forelse($informasi as $item)
                    <tr class="hover:bg-gray-50 transition-colors group {{ $item->status == \App\Models\InformasiPublik::STATUS_ARCHIVED ? 'opacity-80' : '' }}" :class="selectedRows.includes({{ $item->id }}) ? 'bg-green-50/30' : ''">
                        <td class="p-4 text-center">
                            <input type="checkbox" class="custom-checkbox inline-block" value="{{ $item->id }}" x-model="selectedRows">
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                @if($item->type == \App\Models\InformasiPublik::TYPE_AGENDA)
                                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center border border-purple-100 shadow-inner shrink-0 group-hover:scale-105 transition-transform">
                                        <i class="fa-regular fa-calendar-check text-xl"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900 leading-tight w-48 sm:w-64 md:w-80 truncate group-hover:text-purple-700 transition-colors" title="{{ $item->title }}">{{ $item->title }}</div>
                                        <span class="text-[9px] font-bold px-2 py-0.5 bg-purple-100 text-purple-700 rounded-md uppercase tracking-wider mt-1 inline-block">Agenda</span>
                                    </div>
                                @else
                                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100 shadow-inner shrink-0 group-hover:scale-105 transition-transform">
                                        <i class="fa-solid fa-bullhorn text-xl"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900 leading-tight w-48 sm:w-64 md:w-80 truncate group-hover:text-blue-700 transition-colors" title="{{ $item->title }}">{{ $item->title }}</div>
                                        <span class="text-[9px] font-bold px-2 py-0.5 bg-blue-100 text-blue-700 rounded-md uppercase tracking-wider mt-1 inline-block">Pengumuman</span>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="p-4 text-center">
                            @if($item->type == \App\Models\InformasiPublik::TYPE_AGENDA)
                                <span class="text-xs font-bold text-gray-800 block">{{ $item->start_date->format('d M Y') }}</span>
                                <span class="text-[10px] text-gray-500 font-mono">{{ $item->start_date->format('H:i') }} - {{ $item->end_date ? $item->end_date->format('H:i') : 'Selesai' }}</span>
                            @else
                                <span class="text-[10px] font-semibold text-gray-500 block">Tampil hingga:</span>
                                <span class="text-xs font-bold text-gray-800">{{ $item->end_date->format('d M Y') }}</span>
                            @endif
                        </td>
                        <td class="p-4">
                            @if($item->type == \App\Models\InformasiPublik::TYPE_AGENDA)
                                <span class="text-xs font-semibold text-gray-700 flex items-center gap-1.5"><i class="fa-solid fa-location-dot text-[10px] text-gray-400"></i> {{ $item->location ?? '-' }}</span>
                            @else
                                <span class="text-[10px] text-gray-400 italic bg-gray-50 px-2 py-1 rounded border border-gray-100"><i class="fa-solid fa-globe mr-1"></i>Publik</span>
                            @endif
                        </td>
                        <td class="p-4 text-center">
                            @if($item->status == \App\Models\InformasiPublik::STATUS_PUBLISH)
                                <span class="bg-green-50 text-green-700 text-[9px] font-black px-2.5 py-1 rounded border border-green-200 uppercase tracking-widest block mb-1">Published</span>
                            @elseif($item->status == \App\Models\InformasiPublik::STATUS_DRAFT)
                                <span class="bg-gray-100 text-gray-500 text-[9px] font-black px-2.5 py-1 rounded border border-gray-200 uppercase tracking-widest block mb-1">Draft</span>
                            @else
                                <span class="bg-gray-100 text-gray-600 text-[9px] font-black px-2.5 py-1 rounded border border-gray-200 uppercase tracking-widest block mb-1">Archived</span>
                            @endif
                            
                            @php $disp = $item->status_display; @endphp
                            <span class="text-[9px] font-bold 
                                {{ $disp == 'Selesai/Expired' || $disp == 'Selesai' ? 'text-red-500' : 'text-blue-500' }}">{{ $disp }}</span>
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button @click="openDetail({{ $item->id }})" class="w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:text-green-700 hover:bg-green-50 flex items-center justify-center transition-colors border border-gray-200 shadow-sm" title="Preview"><i class="fa-solid fa-eye text-xs"></i></button>
                                <button @click="openForm({{ $item->id }}, '{{ $item->type }}')" class="w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:text-amber-600 hover:bg-amber-50 flex items-center justify-center transition-colors border border-gray-200 shadow-sm" title="Edit"><i class="fa-solid fa-pen text-xs"></i></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-12 text-center text-gray-400">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center mb-3">
                                    <i class="fa-solid fa-file-invoice text-3xl opacity-30"></i>
                                </div>
                                <p class="text-sm font-medium">Data belum tersedia.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($informasi->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $informasi->links('pagination::tailwind') }}
            </div>
        @endif
    </div>
</div>
