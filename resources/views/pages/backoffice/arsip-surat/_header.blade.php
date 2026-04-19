{{-- Header & Controls --}}
<section class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Arsip Surat</h1>
        <p class="text-sm text-gray-500 mt-1">Penyimpanan sentral seluruh dokumen pelayanan publik yang telah diterbitkan atau ditolak.</p>
    </div>

    <div class="flex items-center gap-3 shrink-0">
        {{-- Stats Badges --}}
        <div class="hidden lg:flex items-center gap-2">
            <span class="bg-green-50 text-green-700 border border-green-200 text-xs font-bold px-3 py-1.5 rounded-lg">
                <i class="fa-solid fa-check mr-1"></i> {{ number_format($totalSelesai) }} Selesai
            </span>
            <span class="bg-red-50 text-red-700 border border-red-200 text-xs font-bold px-3 py-1.5 rounded-lg">
                <i class="fa-solid fa-xmark mr-1"></i> {{ number_format($totalDitolak) }} Ditolak
            </span>
        </div>
    </div>
</section>
