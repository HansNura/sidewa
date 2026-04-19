{{-- Search & Filters --}}
<section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
    <form method="GET" action="{{ route('admin.kartu-keluarga.index') }}">
        <div class="flex flex-col md:flex-row gap-4">
            {{-- Search --}}
            <div class="flex-1 relative">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                       placeholder="Cari No. KK atau Nama Kepala Keluarga..."
                       class="w-full bg-gray-50 border border-gray-200 text-sm rounded-xl pl-10 pr-4 py-2.5 focus:ring-2 focus:ring-green-500 outline-none transition-all">
            </div>

            {{-- Dusun filter --}}
            <div class="w-full md:w-48 relative shrink-0">
                <select name="dusun"
                        class="w-full bg-gray-50 border border-gray-200 text-sm rounded-xl px-4 py-2.5 appearance-none focus:ring-2 focus:ring-green-500 outline-none cursor-pointer">
                    <option value="">Semua Wilayah</option>
                    @foreach ($dusunList as $dusun)
                        <option value="{{ $dusun }}" {{ ($filters['dusun'] ?? '') === $dusun ? 'selected' : '' }}>
                            Dusun {{ $dusun }}
                        </option>
                    @endforeach
                </select>
                <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
            </div>

            {{-- Sort by member count --}}
            <div class="w-full md:w-48 relative shrink-0">
                <select name="sort_anggota"
                        class="w-full bg-gray-50 border border-gray-200 text-sm rounded-xl px-4 py-2.5 appearance-none focus:ring-2 focus:ring-green-500 outline-none cursor-pointer">
                    <option value="">Urutkan Anggota</option>
                    <option value="desc" {{ ($filters['sort_anggota'] ?? '') === 'desc' ? 'selected' : '' }}>Anggota Terbanyak</option>
                    <option value="asc" {{ ($filters['sort_anggota'] ?? '') === 'asc' ? 'selected' : '' }}>Anggota Sedikit</option>
                </select>
                <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
            </div>

            <button type="submit"
                class="w-full md:w-auto px-5 py-2.5 bg-gray-800 text-white rounded-xl text-sm font-bold hover:bg-gray-900 cursor-pointer">
                <i class="fa-solid fa-search mr-1"></i> Cari
            </button>
        </div>
    </form>
</section>
