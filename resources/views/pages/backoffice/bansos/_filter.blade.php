{{-- Filter & Search Bar --}}
<section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
    <form method="GET" action="{{ route('admin.bansos.index') }}" class="flex flex-col md:flex-row gap-4">
        {{-- Search --}}
        <div class="flex-1 relative">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text" name="search" value="{{ $search }}"
                   placeholder="Cari NIK, No. KK, atau Nama Penerima..."
                   class="w-full bg-gray-50 border border-gray-200 text-sm rounded-xl pl-10 pr-4 py-2.5 focus:ring-2 focus:ring-green-500 outline-none transition-all">
        </div>

        {{-- Program Filter --}}
        <div class="w-full md:w-40 relative shrink-0">
            <select name="program" onchange="this.form.submit()"
                    class="w-full bg-gray-50 border border-gray-200 text-sm rounded-xl px-4 py-2.5 appearance-none focus:ring-2 focus:ring-green-500 outline-none cursor-pointer">
                <option value="">Semua Program</option>
                @foreach ($programList as $prog)
                    <option value="{{ $prog->id }}" {{ $selectedProgram == $prog->id ? 'selected' : '' }}>{{ $prog->nama }}</option>
                @endforeach
            </select>
            <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
        </div>

        {{-- Status Filter --}}
        <div class="w-full md:w-44 relative shrink-0">
            <select name="status" onchange="this.form.submit()"
                    class="w-full bg-gray-50 border border-gray-200 text-sm rounded-xl px-4 py-2.5 appearance-none focus:ring-2 focus:ring-green-500 outline-none cursor-pointer">
                <option value="">Status Distribusi</option>
                <option value="pending" {{ $selectedStatus === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="siap_diambil" {{ $selectedStatus === 'siap_diambil' ? 'selected' : '' }}>Siap Diambil</option>
                <option value="diterima" {{ $selectedStatus === 'diterima' ? 'selected' : '' }}>Diterima</option>
                <option value="tertahan" {{ $selectedStatus === 'tertahan' ? 'selected' : '' }}>Tertahan (Audit)</option>
            </select>
            <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
        </div>
    </form>
</section>
