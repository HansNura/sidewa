{{-- Monitoring Intervensi Sidebar --}}
<section class="bg-white border border-gray-100 shadow-sm rounded-2xl p-5 flex flex-col xl:col-span-1">
    <div class="flex justify-between items-center mb-5 pb-3 border-b border-gray-100">
        <h3 class="font-bold text-gray-800">
            <i class="fa-solid fa-shield-heart text-red-500 mr-2"></i> Monitoring Intervensi
        </h3>
    </div>

    <div class="space-y-4 overflow-y-auto custom-scrollbar flex-1 pr-2">
        @forelse ($intervensi as $program)
            @php $badge = $program->statusBadge(); @endphp
            <div class="border border-gray-100 rounded-xl p-4 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-2">
                    <h4 class="text-sm font-bold text-gray-900 leading-tight">{{ $program->nama }}</h4>
                    <span class="text-[10px] font-bold {{ $badge['text'] }} {{ $badge['bg'] }} px-2 py-0.5 rounded border {{ $badge['border'] }} capitalize whitespace-nowrap">
                        {{ ucfirst($program->status) }}
                    </span>
                </div>
                @if ($program->deskripsi)
                    <p class="text-xs text-gray-500 mb-3">{{ $program->deskripsi }}</p>
                @endif

                <div>
                    <div class="flex justify-between text-[10px] font-bold mb-1">
                        <span class="text-gray-500">Progres</span>
                        <span class="{{ $badge['text'] }}">
                            {{ $program->progres }}%
                            @if ($program->target_peserta > 0)
                                ({{ $program->peserta_terdaftar }}/{{ $program->target_peserta }} Orang)
                            @endif
                        </span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-1.5">
                        <div class="{{ $program->progressColor() }} h-1.5 rounded-full transition-all"
                             style="width: {{ $program->progres }}%"></div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-gray-400 text-sm py-8">
                <i class="fa-solid fa-clipboard-list text-2xl mb-2 block"></i>
                <p>Belum ada program intervensi.</p>
            </div>
        @endforelse
    </div>
</section>
