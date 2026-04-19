{{-- Search & Advanced Filters --}}
<section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
    <form method="GET" action="{{ route('admin.penduduk.index') }}">
        {{-- Basic Search --}}
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                       placeholder="Cari NIK, Nama Lengkap..."
                       class="w-full bg-gray-50 border border-gray-200 text-sm rounded-xl pl-10 pr-4 py-2.5 focus:ring-2 focus:ring-green-500 outline-none transition-all">
            </div>
            <div class="w-full md:w-48 relative shrink-0">
                <select name="dusun"
                        class="w-full bg-gray-50 border border-gray-200 text-sm rounded-xl px-4 py-2.5 appearance-none focus:ring-2 focus:ring-green-500 outline-none cursor-pointer">
                    <option value="">Semua Dusun</option>
                    @foreach ($dusunList as $dusun)
                        <option value="{{ $dusun }}" {{ ($filters['dusun'] ?? '') === $dusun ? 'selected' : '' }}>
                            {{ $dusun }}
                        </option>
                    @endforeach
                </select>
                <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
            </div>
            <button type="button" @click="advFilterOpen = !advFilterOpen"
                class="w-full md:w-auto px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-semibold text-gray-600 hover:bg-gray-100 flex items-center justify-center gap-2 transition-colors cursor-pointer">
                <i class="fa-solid fa-sliders"></i> Filter Lanjut
            </button>
            <button type="submit"
                class="w-full md:w-auto px-5 py-2.5 bg-gray-800 text-white rounded-xl text-sm font-bold hover:bg-gray-900 cursor-pointer">
                <i class="fa-solid fa-search mr-1"></i> Cari
            </button>
        </div>

        {{-- Advanced Filters --}}
        <div x-show="advFilterOpen" x-collapse x-cloak class="mt-4 pt-4 border-t border-gray-100">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <label class="text-xs font-bold text-gray-600 block mb-1">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="w-full bg-white border border-gray-200 text-sm rounded-lg px-3 py-2 outline-none cursor-pointer">
                        <option value="">Semua</option>
                        <option value="L" {{ ($filters['jenis_kelamin'] ?? '') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ ($filters['jenis_kelamin'] ?? '') === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-600 block mb-1">Status Perkawinan</label>
                    <select name="perkawinan" class="w-full bg-white border border-gray-200 text-sm rounded-lg px-3 py-2 outline-none cursor-pointer">
                        <option value="">Semua</option>
                        @foreach (['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati'] as $p)
                            <option value="{{ $p }}" {{ ($filters['perkawinan'] ?? '') === $p ? 'selected' : '' }}>{{ $p }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-600 block mb-1">Kelompok Usia</label>
                    <select name="usia" class="w-full bg-white border border-gray-200 text-sm rounded-lg px-3 py-2 outline-none cursor-pointer">
                        <option value="">Semua</option>
                        <option value="balita" {{ ($filters['usia'] ?? '') === 'balita' ? 'selected' : '' }}>Balita (0-4)</option>
                        <option value="anak" {{ ($filters['usia'] ?? '') === 'anak' ? 'selected' : '' }}>Anak (5-14)</option>
                        <option value="pemuda" {{ ($filters['usia'] ?? '') === 'pemuda' ? 'selected' : '' }}>Pemuda (15-24)</option>
                        <option value="dewasa" {{ ($filters['usia'] ?? '') === 'dewasa' ? 'selected' : '' }}>Dewasa (25-64)</option>
                        <option value="lansia" {{ ($filters['usia'] ?? '') === 'lansia' ? 'selected' : '' }}>Lansia (>65)</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-600 block mb-1">Status Warga</label>
                    <select name="status" class="w-full bg-white border border-gray-200 text-sm rounded-lg px-3 py-2 outline-none cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="hidup" {{ ($filters['status'] ?? '') === 'hidup' ? 'selected' : '' }}>Hidup</option>
                        <option value="pindah" {{ ($filters['status'] ?? '') === 'pindah' ? 'selected' : '' }}>Pindah</option>
                        <option value="meninggal" {{ ($filters['status'] ?? '') === 'meninggal' ? 'selected' : '' }}>Meninggal</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <a href="{{ route('admin.penduduk.index') }}" class="px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-gray-700">Reset</a>
                <button type="submit" class="px-4 py-1.5 text-xs font-bold bg-gray-800 text-white rounded-lg hover:bg-gray-900 cursor-pointer">Terapkan Filter</button>
            </div>
        </div>
    </form>
</section>
