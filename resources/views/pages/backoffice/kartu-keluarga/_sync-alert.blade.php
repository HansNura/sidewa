{{-- Validation & Sync Alert --}}
@if ($unlinkedCount > 0)
<section class="bg-amber-50 border border-amber-200 rounded-2xl p-4 flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center shadow-sm">
    <div class="flex gap-3 items-start">
        <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center shrink-0 mt-0.5">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div>
            <h4 class="font-bold text-amber-900 text-sm">Validasi & Sinkronisasi Sistem</h4>
            <p class="text-xs text-amber-800 mt-0.5">
                Terdapat <span class="font-bold underline decoration-amber-400">{{ $unlinkedCount }} data penduduk</span>
                yang terdaftar namun belum ditautkan ke Nomor Kartu Keluarga mana pun.
            </p>
        </div>
    </div>
    <a href="{{ route('admin.penduduk.index') }}"
       class="bg-white border border-amber-300 text-amber-700 hover:bg-amber-100 px-4 py-2 rounded-xl text-xs font-bold transition-colors shrink-0 shadow-sm">
        <i class="fa-solid fa-link mr-1"></i> Lihat Data
    </a>
</section>
@endif
