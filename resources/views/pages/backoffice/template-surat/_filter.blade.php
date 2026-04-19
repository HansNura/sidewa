{{-- Filter & Search Bar --}}
<section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 transition-all">
    <form method="GET" action="{{ route('admin.template-surat.index') }}" class="flex flex-col md:flex-row gap-4">
        {{-- Search --}}
        <div class="flex-1 relative">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text" name="search" value="{{ $search ?? '' }}"
                placeholder="Cari nama template (misal: Domisili, SKTM)..."
                class="w-full bg-gray-50 border border-gray-200 text-sm rounded-xl pl-11 pr-4 py-2.5 focus:ring-2 focus:ring-green-500 outline-none transition-all">
        </div>

        {{-- Kategori Filter --}}
        <div class="w-full md:w-56 relative shrink-0">
            <select name="kategori"
                class="w-full bg-gray-50 border border-gray-200 text-sm rounded-xl px-4 py-2.5 appearance-none focus:ring-2 focus:ring-green-500 outline-none cursor-pointer"
                onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                @foreach ($kategoris as $key => $label)
                    <option value="{{ $key }}" {{ ($selKategori ?? '') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
        </div>

        {{-- Status Filter --}}
        <div class="w-full md:w-48 relative shrink-0">
            <select name="status"
                class="w-full bg-gray-50 border border-gray-200 text-sm rounded-xl px-4 py-2.5 appearance-none focus:ring-2 focus:ring-green-500 outline-none cursor-pointer"
                onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="aktif" {{ ($selStatus ?? '') === 'aktif' ? 'selected' : '' }}>Aktif (Digunakan)</option>
                <option value="nonaktif" {{ ($selStatus ?? '') === 'nonaktif' ? 'selected' : '' }}>Nonaktif (Draft/Arsip)</option>
            </select>
            <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
        </div>

        <button type="submit" class="sr-only">Cari</button>
    </form>
</section>
