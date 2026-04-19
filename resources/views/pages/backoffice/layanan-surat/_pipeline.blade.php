{{-- Status Pipeline --}}
<section class="bg-white border border-gray-100 shadow-sm rounded-2xl p-6 overflow-hidden">
    <h3 class="font-bold text-gray-800 mb-6">Status Pipeline Alur Layanan Saat Ini</h3>

    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 relative">
        <div class="hidden sm:block absolute left-[10%] right-[10%] top-6 h-1 bg-gray-100 z-0"></div>

        {{-- Step 1: Pengajuan --}}
        <div class="relative z-10 flex flex-col items-center w-full sm:w-1/4">
            <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 border-4 border-white flex items-center justify-center text-lg shadow-sm mb-3 relative">
                <i class="fa-solid fa-file-import"></i>
                @if ($pipeline['pengajuan'] > 0)
                    <span class="absolute -top-2 -right-2 bg-blue-600 text-white text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-full border-2 border-white">{{ $pipeline['pengajuan'] }}</span>
                @endif
            </div>
            <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wider text-center">Pengajuan Baru</h4>
            <p class="text-[10px] text-gray-500 text-center mt-1">Inbox Warga</p>
        </div>

        {{-- Step 2: Verifikasi --}}
        <div class="relative z-10 flex flex-col items-center w-full sm:w-1/4">
            <div class="w-12 h-12 rounded-full bg-amber-100 text-amber-600 border-4 border-white flex items-center justify-center text-lg shadow-sm mb-3 relative">
                <i class="fa-solid fa-list-check"></i>
                @if ($pipeline['verifikasi'] > 0)
                    <span class="absolute -top-2 -right-2 bg-amber-600 text-white text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-full border-2 border-white">{{ $pipeline['verifikasi'] }}</span>
                @endif
            </div>
            <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wider text-center">Verifikasi Operator</h4>
            <p class="text-[10px] text-gray-500 text-center mt-1">Cek Syarat & Data</p>
        </div>

        {{-- Step 3: Menunggu TTE --}}
        <div class="relative z-10 flex flex-col items-center w-full sm:w-1/4">
            <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-600 border-4 border-white flex items-center justify-center text-lg shadow-sm mb-3 relative">
                <i class="fa-solid fa-signature"></i>
                @if ($pipeline['menunggu_tte'] > 0)
                    <span class="absolute -top-2 -right-2 bg-purple-600 text-white text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-full border-2 border-white">{{ $pipeline['menunggu_tte'] }}</span>
                @endif
            </div>
            <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wider text-center">Menunggu TTE</h4>
            <p class="text-[10px] text-gray-500 text-center mt-1">Persetujuan Kades</p>
        </div>

        {{-- Step 4: Selesai --}}
        <div class="relative z-10 flex flex-col items-center w-full sm:w-1/4">
            <div class="w-12 h-12 rounded-full bg-green-100 text-green-600 border-4 border-white flex items-center justify-center text-lg shadow-sm mb-3 relative">
                <i class="fa-solid fa-flag-checkered"></i>
            </div>
            <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wider text-center">Selesai (Diarsipkan)</h4>
            <p class="text-[10px] text-gray-500 text-center mt-1">{{ $pipeline['selesai'] }} Surat</p>
        </div>
    </div>
</section>
