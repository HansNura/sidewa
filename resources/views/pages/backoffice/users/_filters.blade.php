{{-- Search & Filter Bar --}}
<section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
    <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col md:flex-row gap-4">

        {{-- Search Box --}}
        <div class="flex-1 relative">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text"
                   name="search"
                   value="{{ $filters['search'] ?? '' }}"
                   placeholder="Cari nama, email, atau NIK..."
                   class="w-full bg-gray-50 border border-gray-200 text-sm rounded-xl pl-10 pr-4 py-2.5 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all placeholder:text-gray-400">
        </div>

        {{-- Filter Role --}}
        <div class="w-full md:w-48 relative shrink-0">
            <select name="role"
                    onchange="this.form.submit()"
                    class="w-full bg-gray-50 border border-gray-200 text-sm rounded-xl px-4 py-2.5 appearance-none focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none cursor-pointer">
                <option value="">Semua Role</option>
                @foreach ($roles as $key => $label)
                    <option value="{{ $key }}" @selected(($filters['role'] ?? '') === $key)>{{ $label }}</option>
                @endforeach
            </select>
            <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
        </div>

        {{-- Filter Status --}}
        <div class="w-full md:w-40 relative shrink-0">
            <select name="status"
                    onchange="this.form.submit()"
                    class="w-full bg-gray-50 border border-gray-200 text-sm rounded-xl px-4 py-2.5 appearance-none focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none cursor-pointer">
                <option value="">Semua Status</option>
                <option value="aktif" @selected(($filters['status'] ?? '') === 'aktif')>Aktif</option>
                <option value="nonaktif" @selected(($filters['status'] ?? '') === 'nonaktif')>Nonaktif / Suspend</option>
            </select>
            <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
        </div>

        {{-- Submit (for search text input) --}}
        <button type="submit" class="hidden md:flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-xl text-sm font-semibold transition-colors shrink-0">
            <i class="fa-solid fa-filter text-xs"></i> Filter
        </button>
    </form>
</section>
