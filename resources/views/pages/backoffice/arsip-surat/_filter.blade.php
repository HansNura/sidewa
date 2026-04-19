{{-- Filter & Search --}}
<section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 transition-all">
    <form method="GET" action="{{ route('admin.arsip-surat.index') }}">
        {{-- Basic Filters --}}
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ $search ?? '' }}"
                    placeholder="Cari Nomor Tiket atau Nama Pemohon..."
                    class="w-full bg-gray-50 border border-gray-200 text-sm rounded-xl pl-11 pr-4 py-2.5 focus:ring-2 focus:ring-green-500 outline-none transition-all">
            </div>
            <div class="w-full md:w-48 relative shrink-0">
                <select name="jenis" onchange="this.form.submit()"
                    class="w-full bg-gray-50 border border-gray-200 text-sm rounded-xl px-4 py-2.5 appearance-none focus:ring-2 focus:ring-green-500 outline-none cursor-pointer">
                    <option value="">Semua Jenis Surat</option>
                    @foreach ($jenisLabels as $key => $label)
                        <option value="{{ $key }}" {{ ($selJenis ?? '') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
            </div>
            <div class="w-full md:w-48 relative shrink-0">
                <select name="status" onchange="this.form.submit()"
                    class="w-full bg-gray-50 border border-gray-200 text-sm rounded-xl px-4 py-2.5 appearance-none focus:ring-2 focus:ring-green-500 outline-none cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="selesai" {{ ($selStatus ?? '') === 'selesai' ? 'selected' : '' }}>Selesai (Diarsipkan)</option>
                    <option value="ditolak" {{ ($selStatus ?? '') === 'ditolak' ? 'selected' : '' }}>Ditolak / Batal</option>
                </select>
                <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
            </div>
            <button type="button" @click="advFilterOpen = !advFilterOpen"
                class="w-full md:w-auto px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-600 hover:bg-gray-50 flex items-center justify-center gap-2 transition-colors cursor-pointer">
                <i class="fa-solid fa-sliders"></i> <span class="hidden xl:inline">Filter Lanjut</span>
            </button>
        </div>

        {{-- Advanced Filter --}}
        <div x-show="advFilterOpen" x-collapse x-cloak class="mt-4 pt-4 border-t border-gray-100">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="text-xs font-bold text-gray-600 block mb-1">Dari Tanggal (Mulai)</label>
                    <input type="date" name="dari_tanggal" value="{{ $dariTanggal ?? '' }}"
                        class="w-full bg-gray-50 border border-gray-200 text-sm rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-600 block mb-1">Sampai Tanggal (Selesai)</label>
                    <input type="date" name="sampai_tanggal" value="{{ $sampaiTanggal ?? '' }}"
                        class="w-full bg-gray-50 border border-gray-200 text-sm rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-600 block mb-1">Dibuat Oleh (Operator)</label>
                    <select name="operator"
                        class="w-full bg-gray-50 border border-gray-200 text-sm rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-green-500 cursor-pointer">
                        <option value="">Semua Operator</option>
                        @foreach ($operators as $op)
                            <option value="{{ $op->id }}" {{ ($selOperator ?? '') == $op->id ? 'selected' : '' }}>{{ $op->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <a href="{{ route('admin.arsip-surat.index') }}"
                    class="px-4 py-2 text-xs font-bold text-gray-500 hover:text-gray-700 transition-colors">Reset Filter</a>
                <button type="submit"
                    class="px-4 py-2 text-xs font-bold bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition-colors shadow-sm cursor-pointer">Terapkan Filter</button>
            </div>
        </div>

        <button type="submit" class="sr-only">Cari</button>
    </form>
</section>
