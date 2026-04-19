{{-- Bottleneck Alert --}}
@if ($overdueCount > 0)
    <section class="bg-red-50 border border-red-200 rounded-2xl p-4 flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center shadow-sm">
        <div class="flex gap-3 items-start">
            <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center shrink-0 mt-0.5 animate-pulse">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <h4 class="font-bold text-red-900 text-sm">Peringatan: Bottleneck Layanan Terdeteksi</h4>
                <p class="text-xs text-red-800 mt-0.5">
                    Terdapat <span class="font-bold">{{ $overdueCount }} surat</span> yang sudah
                    <span class="underline decoration-red-400">melebihi batas waktu SLA (24 Jam)</span>.
                    Harap segera ditindaklanjuti.
                </p>
            </div>
        </div>
        <a href="{{ route('admin.layanan-surat.index', ['status' => 'verifikasi']) }}"
           class="bg-white border border-red-300 text-red-700 hover:bg-red-100 px-4 py-2 rounded-xl text-xs font-bold transition-colors shrink-0 shadow-sm flex items-center gap-2">
            Tindak Lanjuti <i class="fa-solid fa-arrow-right"></i>
        </a>
    </section>
@endif
