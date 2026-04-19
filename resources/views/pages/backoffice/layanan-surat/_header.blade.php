{{-- Page Header --}}
<section class="flex flex-col xl:flex-row xl:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Dashboard Layanan Surat</h1>
        <p class="text-sm text-gray-500 mt-1">Monitoring antrian, performa pelayanan administrasi desa, dan verifikasi dokumen.</p>
    </div>

    <div class="flex flex-wrap items-center gap-3">
        {{-- Period Filter --}}
        <form method="GET" action="{{ route('admin.layanan-surat.index') }}" class="flex rounded-xl shadow-sm border border-gray-200 overflow-hidden bg-white">
            <select name="periode" onchange="this.form.submit()"
                    class="px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 border-r border-gray-200 outline-none cursor-pointer bg-transparent">
                <option value="hari" {{ $periode === 'hari' ? 'selected' : '' }}>Hari Ini</option>
                <option value="minggu" {{ $periode === 'minggu' ? 'selected' : '' }}>Minggu Ini</option>
                <option value="bulan" {{ $periode === 'bulan' ? 'selected' : '' }}>Bulan Ini</option>
            </select>
        </form>

        <button @click="addModalOpen = true"
            class="bg-green-700 hover:bg-green-800 text-white shadow-md rounded-xl px-5 py-2.5 text-sm font-bold transition-all flex items-center gap-2 cursor-pointer">
            <i class="fa-solid fa-plus"></i>
            <span>Buat Surat Baru</span>
        </button>
    </div>
</section>
