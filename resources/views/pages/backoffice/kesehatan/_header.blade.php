{{-- Page Header --}}
<section class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Kesehatan & Stunting</h1>
        <p class="text-sm text-gray-500 mt-1">Pemantauan gizi balita, riwayat posyandu, dan manajemen intervensi stunting.</p>
    </div>

    <div class="flex flex-wrap items-center gap-3">
        {{-- Filters --}}
        <form method="GET" action="{{ route('admin.kesehatan.index') }}"
              class="flex rounded-xl shadow-sm border border-gray-200 overflow-hidden bg-white">
            <select name="dusun" onchange="this.form.submit()"
                    class="px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 outline-none cursor-pointer bg-transparent">
                <option value="">Semua Wilayah</option>
                @foreach ($dusunList as $d)
                    <option value="{{ $d }}" {{ $dusun === $d ? 'selected' : '' }}>Dusun {{ $d }}</option>
                @endforeach
            </select>
        </form>

        <button @click="addModalOpen = true"
            class="bg-green-700 hover:bg-green-800 text-white shadow-md rounded-xl px-5 py-2.5 text-sm font-bold transition-all flex items-center gap-2 cursor-pointer">
            <i class="fa-solid fa-plus"></i>
            <span>Input Penimbangan</span>
        </button>
    </div>
</section>
