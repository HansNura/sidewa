{{-- Status Workflow Stats --}}
<section class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    {{-- Menunggu Verifikasi (Hanya Operator/Admin) --}}
    @if (auth()->user()->isOperator() || auth()->user()->isAdministrator())
        <article
            class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4 border-l-4 border-l-amber-500">
            <div
                class="w-12 h-12 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center shrink-0 text-xl">
                <i class="fa-solid fa-list-check"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Menunggu Verifikasi
                    (Operator)</p>
                <div class="flex items-end gap-2">
                    <h3 class="text-2xl font-extrabold text-gray-900 leading-none">{{ $countVerifikasi }}</h3>
                    <span class="text-xs font-semibold text-amber-600 mb-0.5">Berkas</span>
                </div>
            </div>
        </article>
    @endif

    {{-- Menunggu TTE (Hanya Kades/Admin) --}}
    @if (auth()->user()->isKades() || auth()->user()->isAdministrator())
        <article
            class="bg-green-50 rounded-2xl p-5 border border-green-200 shadow-sm flex items-center gap-4 relative overflow-hidden">
            @if ($countTTE > 0)
                <div
                    class="absolute right-0 top-0 bg-green-600 text-white text-[10px] font-bold px-3 py-1 rounded-bl-xl uppercase tracking-wider animate-pulse">
                    Perlu Aksi Anda
                </div>
            @endif
            <div
                class="w-12 h-12 rounded-full bg-white text-green-600 flex items-center justify-center shrink-0 text-xl shadow-sm">
                <i class="fa-solid fa-signature"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-green-800 uppercase tracking-wider mb-1">Menunggu TTE (Kepala Desa)
                </p>
                <div class="flex items-end gap-2">
                    <h3 class="text-2xl font-extrabold text-green-900 leading-none">{{ $countTTE }}</h3>
                    <span class="text-xs font-semibold text-green-700 mb-0.5">Dokumen Siap</span>
                </div>
            </div>
        </article>
    @endif

    {{-- Selesai Hari Ini (Semua Petugas) --}}
    <article
        class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4 border-l-4 border-l-green-500">
        <div
            class="w-12 h-12 rounded-full bg-green-50 text-green-600 flex items-center justify-center shrink-0 text-xl">
            <i class="fa-solid fa-check-double"></i>
        </div>
        <div>
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Selesai Hari Ini</p>
            <div class="flex items-end gap-2">
                <h3 class="text-2xl font-extrabold text-gray-900 leading-none">{{ $countSelesaiToday }}</h3>
                <span class="text-xs font-semibold text-green-600 mb-0.5">Surat</span>
            </div>
        </div>
    </article>
</section>
