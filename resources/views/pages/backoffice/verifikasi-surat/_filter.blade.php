{{-- Filter & Search --}}
<section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 transition-all">
    <form method="GET" action="{{ route(auth()->user()->routePrefix() . '.verifikasi-surat.index') }}"
        class="flex flex-col md:flex-row gap-4">
        <div class="flex-1 relative">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text" name="search" value="{{ $search ?? '' }}"
                placeholder="Cari Nama Pemohon atau No. Tiket..."
                class="w-full bg-gray-50 border border-gray-200 text-sm rounded-xl pl-11 pr-4 py-2.5 focus:ring-2 focus:ring-green-500 outline-none transition-all">
        </div>
        <div class="w-full md:w-56 relative shrink-0">
            <select name="status" onchange="this.form.submit()"
                class="w-full bg-gray-50 border border-gray-200 text-sm rounded-xl px-4 py-2.5 appearance-none focus:ring-2 focus:ring-green-500 outline-none cursor-pointer font-medium text-gray-700">
                <option value="">Semua Status Antrean</option>
                <option value="menunggu_tte" {{ ($selStatus ?? '') === 'menunggu_tte' ? 'selected' : '' }}>Menunggu TTE
                    ({{ $countTTE }})</option>
                <option value="verifikasi" {{ ($selStatus ?? '') === 'verifikasi' ? 'selected' : '' }}>Menunggu
                    Verifikasi ({{ $countVerifikasi }})</option>
            </select>
            <i
                class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
        </div>
        <div class="w-full md:w-48 shrink-0">
            <input type="date" name="tanggal" value="{{ $selTanggal ?? '' }}" onchange="this.form.submit()"
                class="w-full bg-gray-50 border border-gray-200 text-sm rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-green-500 outline-none transition-all text-gray-600">
        </div>
        <button type="submit" class="sr-only">Cari</button>
    </form>
</section>
