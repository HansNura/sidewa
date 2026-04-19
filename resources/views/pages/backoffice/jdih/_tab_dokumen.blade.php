<!-- TAB 1: DAFTAR DOKUMEN (TABLE VIEW) -->
<div x-show="activeTab === 'dokumen'" x-transition.opacity class="space-y-6">
    <!-- Filter -->
    <div class="flex flex-col md:flex-row gap-4 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
        <form action="{{ route('admin.jdih.index') }}" method="GET" class="flex-1 relative flex gap-4 w-full flex-col md:flex-row">
            <input type="hidden" name="tab" value="dokumen">
            
            <div class="flex-1 relative">
                <i class="fa-solid fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul, nomor dokumen..." class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none transition-all">
            </div>
            
            <select name="year" onchange="this.form.submit()" class="w-full md:w-32 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none cursor-pointer font-medium text-gray-700">
                <option value="">Semua Tahun</option>
                @foreach($yearsList as $y)
                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>

            <select name="category" onchange="this.form.submit()" class="w-full md:w-48 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none cursor-pointer font-medium text-gray-700">
                <option value="">Semua Kategori</option>
                @foreach($categoriesList as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>

            <select name="status" onchange="this.form.submit()" class="w-full md:w-40 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none cursor-pointer font-medium text-gray-700">
                <option value="">Semua Status</option>
                <option value="berlaku" {{ request('status') == 'berlaku' ? 'selected' : '' }}>Berlaku Aktif</option>
                <option value="dicabut" {{ request('status') == 'dicabut' ? 'selected' : '' }}>Dicabut/Arsip</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
            </select>
            <button type="submit" class="hidden">Filter</button>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden">
        
        <div x-show="selectedRows.length > 0" x-collapse x-cloak class="bg-red-50 border-b border-red-100 px-5 py-3 flex items-center justify-between">
            <span class="text-sm font-bold text-red-800"><span x-text="selectedRows.length"></span> Dokumen Terpilih</span>
            <form action="{{ route('admin.jdih.destroyDocument') }}" method="POST" class="flex gap-2">
                @csrf
                <input type="hidden" name="selected_ids" :value="selectedRows.join(',')">
                <button type="submit" onclick="return confirm('Hapus permanen dokumen terpilih dari sistem JDIH? File PDF juga akan terhapus.')" class="text-xs font-bold px-3 py-1.5 bg-red-600 border border-red-700 text-white rounded-lg hover:bg-red-700"><i class="fa-solid fa-trash mr-1"></i>Hapus</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-gray-50/80 text-gray-500 text-[10px] uppercase tracking-wider border-b border-gray-200">
                        <th class="p-4 w-12 text-center">
                            <input type="checkbox" class="custom-checkbox inline-block" x-model="selectAll" @change="selectedRows = selectAll ? [{{ isset($documents) ? $documents->pluck('id')->join(',') : '' }}] : []">
                        </th>
                        <th class="p-4 font-bold">Judul & Nomor Dokumen</th>
                        <th class="p-4 font-bold text-center">Kategori</th>
                        <th class="p-4 font-bold text-center">Tgl Ditetapkan</th>
                        <th class="p-4 font-bold text-center">Status</th>
                        <th class="p-4 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    @if(isset($documents) && count($documents) > 0)
                        @foreach($documents as $doc)
                        <tr class="hover:bg-gray-50 transition-colors {{ $doc->status != 'berlaku' ? 'opacity-80' : '' }}" :class="selectedRows.includes({{ $doc->id }}) ? 'bg-primary-50/30' : ''">
                            <td class="p-4 text-center">
                                <input type="checkbox" class="custom-checkbox inline-block" value="{{ $doc->id }}" x-model="selectedRows">
                            </td>
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center border shadow-sm shrink-0 {{ $doc->file_path ? 'bg-red-50 text-red-500 border-red-100' : 'bg-gray-100 text-gray-400 border-gray-200' }}">
                                        <i class="fa-solid fa-file-pdf text-xl"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900 leading-tight w-64 md:w-80 truncate" title="{{ $doc->title }}">{{ $doc->title }}</div>
                                        <div class="text-[10px] font-mono text-gray-500 mt-0.5">Nomor: {{ $doc->document_number }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 text-center">
                                <span class="bg-primary-50 text-primary-700 text-[10px] font-bold px-2 py-1 rounded">{{ $doc->category->name }}</span>
                            </td>
                            <td class="p-4 text-center">
                                <span class="text-xs font-semibold text-gray-800">{{ $doc->established_date->translatedFormat('d M Y') }}</span>
                            </td>
                            <td class="p-4 text-center">
                                @if($doc->status == 'berlaku')
                                    <span class="bg-green-50 text-green-700 text-[9px] font-black px-2.5 py-1 rounded-full border border-green-200 uppercase tracking-widest block mx-auto content-center w-24"><i class="fa-solid fa-check mr-1"></i>Berlaku</span>
                                @elseif($doc->status == 'dicabut')
                                    <span class="bg-red-50 text-red-700 text-[9px] font-black px-2.5 py-1 rounded-full border border-red-200 uppercase tracking-widest block mx-auto content-center w-24"><i class="fa-solid fa-ban mr-1"></i>Dicabut</span>
                                @else
                                    <span class="bg-gray-100 text-gray-500 text-[9px] font-black px-2.5 py-1 rounded-full border border-gray-200 uppercase tracking-widest block mx-auto content-center w-24"><i class="fa-solid fa-pen-ruler mr-1"></i>Draft</span>
                                @endif
                            </td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button @click="openDetail({{ $doc->id }})" class="w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:text-primary-700 hover:bg-primary-50 flex items-center justify-center transition-colors border border-gray-200 shadow-sm" title="Preview Detail"><i class="fa-solid fa-eye text-xs"></i></button>
                                    <button @click="openDocForm({{ $doc->id }})" class="w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:text-amber-600 hover:bg-amber-50 flex items-center justify-center transition-colors border border-gray-200 shadow-sm" title="Edit"><i class="fa-solid fa-pen text-xs"></i></button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="p-12 text-center text-gray-400">
                                <i class="fa-solid fa-folder-open text-text-3xl mb-3 opacity-30"></i>
                                <p>Belum ada dokumen untuk ditampilkan.</p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        @if(isset($documents) && $documents->hasPages())
            <div class="p-4 border-t border-gray-100 bg-gray-50 flex justify-between items-center shrink-0">
                {{ $documents->links('pagination::tailwind') }}
            </div>
        @endif
    </div>
</div>
