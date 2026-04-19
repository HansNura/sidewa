{{-- Program Cards --}}
<section class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @foreach ($programs as $prog)
        @php $badge = $prog->statusBadge(); @endphp
        <article class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group {{ $prog->status === 'selesai' ? 'opacity-80' : '' }}">
            <div class="absolute top-0 right-0 {{ $badge['bg'] }} {{ $badge['text'] }} text-[10px] font-bold px-3 py-1 rounded-bl-xl uppercase tracking-wider">
                {{ $badge['label'] }}
            </div>
            <div class="w-12 h-12 rounded-xl {{ $prog->icon_bg }} {{ $prog->icon_color }} flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition-transform">
                <i class="fa-solid {{ $prog->icon }}"></i>
            </div>
            <h3 class="text-lg font-extrabold text-gray-900 leading-tight">{{ $prog->nama }}</h3>
            @if ($prog->deskripsi)
                <p class="text-xs text-gray-500 mt-1">{{ $prog->deskripsi }}</p>
            @endif
            <div class="mt-4 pt-4 border-t border-gray-50 flex justify-between items-center">
                <div>
                    <p class="text-[10px] font-semibold text-gray-400 uppercase">Periode</p>
                    <p class="text-sm font-bold text-gray-800">{{ $prog->periode ?? '-' }}</p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-semibold text-gray-400 uppercase">Penerima</p>
                    <p class="text-sm font-bold {{ $prog->status === 'aktif' ? 'text-green-700' : 'text-gray-600' }}">
                        {{ $prog->penerima_count }} KPM
                    </p>
                </div>
            </div>
        </article>
    @endforeach
</section>
