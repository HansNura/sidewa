{{-- Filter & Date Control --}}
<section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 transition-all">
    <form method="GET" action="{{ route('admin.presensi.monitoring') }}" class="flex flex-col md:flex-row gap-4" id="filterForm">
        <!-- Date Control (Alpine watch will submit form if this changes, but fallback is standard submit) -->
        <div class="w-full md:w-48 relative shrink-0">
            <input type="date" name="tanggal" x-model="today"
                class="w-full bg-gray-50 border border-gray-200 text-sm rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-green-500 outline-none transition-all text-gray-700 font-semibold cursor-pointer">
        </div>

        <!-- Search Box -->
        <div class="flex-1 relative">
            <i class="fa-solid fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari Nama Pegawai atau NIP..."
                class="w-full bg-gray-50 border border-gray-200 text-sm rounded-xl pl-11 pr-4 py-2.5 focus:ring-2 focus:ring-green-500 outline-none transition-all"
                onkeypress="if(event.keyCode==13){this.form.submit();}">
        </div>

        <!-- Filters -->
        <div class="w-full md:w-48 relative shrink-0">
            <select name="status" onchange="this.form.submit()"
                class="w-full bg-gray-50 border border-gray-200 text-sm rounded-xl px-4 py-2.5 appearance-none focus:ring-2 focus:ring-green-500 outline-none cursor-pointer">
                <option value="">Semua Status</option>
                <option value="hadir" {{ ($selStatus ?? '') === 'hadir' ? 'selected' : '' }}>Hadir (Tepat Waktu)</option>
                <option value="terlambat" {{ ($selStatus ?? '') === 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                <option value="izin" {{ ($selStatus ?? '') === 'izin' ? 'selected' : '' }}>Izin / Sakit / Dinas</option>
                <option value="alpha" {{ ($selStatus ?? '') === 'alpha' ? 'selected' : '' }}>Belum Hadir / Alpha</option>
            </select>
            <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
        </div>
        
        <button type="submit" class="hidden">Cari</button>
    </form>
</section>
