{{-- Activity Timeline --}}
<section class="bg-white border border-gray-100 shadow-sm rounded-2xl p-5 flex-1 overflow-hidden flex flex-col">
    <div class="flex justify-between items-center mb-5 shrink-0 border-b border-gray-100 pb-3">
        <div>
            <h3 class="font-bold text-gray-800"><i class="fa-solid fa-clock-rotate-left text-green-600 mr-1.5"></i> Aktivitas Terbaru</h3>
            <p class="text-[10px] text-gray-500 mt-0.5">Jejak aksi operator & sistem.</p>
        </div>
    </div>

    <div class="relative ml-2 pl-4 py-2 space-y-6 overflow-y-auto custom-scrollbar flex-1 pr-2">
        @forelse ($activities as $act)
            @php
                $badge = $act->statusBadge();
                $isError = in_array($act->status, ['ditolak']);
                $isSuccess = $act->status === 'selesai';
                $dotColor = $isError ? 'bg-red-100 text-red-600' : ($isSuccess ? 'bg-green-100 text-green-600' : ($act->status === 'pengajuan' ? 'bg-blue-100 text-blue-600' : 'bg-amber-100 text-amber-600'));
                $dotIcon = $badge['icon'];
            @endphp
            <div class="relative z-10 border-l-2 border-gray-100 pl-4 -ml-0.5">
                <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full {{ $dotColor }} border-2 border-white flex items-center justify-center">
                    <i class="fa-solid {{ $dotIcon }} text-[8px]"></i>
                </div>
                <p class="text-[10px] text-gray-400 font-medium mb-0.5">{{ $act->updated_at->diffForHumans() }}</p>
                <p class="text-sm font-semibold {{ $isError ? 'text-red-700' : 'text-gray-800' }}">{{ $badge['label'] }}</p>
                <p class="text-xs text-gray-500 mt-1">
                    {{ $act->jenisShort() }} a.n
                    <span class="font-medium text-gray-700">{{ $act->penduduk?->nama ?? '-' }}</span>
                    <span class="text-gray-400">({{ $act->nomor_tiket }})</span>
                </p>
            </div>
        @empty
            <p class="text-sm text-gray-400 text-center py-4">Belum ada aktivitas.</p>
        @endforelse
    </div>
</section>
