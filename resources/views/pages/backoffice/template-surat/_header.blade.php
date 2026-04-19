{{-- Header & Controls --}}
<section class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Template Surat</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola format standar, layout, dan <em>field</em> dinamis untuk pembuatan surat otomatis.</p>
    </div>

    <div class="flex items-center gap-3 shrink-0">
        <a href="{{ route('admin.template-surat.create') }}"
           class="bg-green-700 hover:bg-green-800 text-white shadow-md rounded-xl px-5 py-2.5 text-sm font-bold transition-all flex items-center gap-2">
            <i class="fa-solid fa-file-circle-plus"></i>
            <span>Buat Template Baru</span>
        </a>
    </div>
</section>
