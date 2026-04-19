{{-- Step 1: Pilih Template Surat --}}
<section x-show="step === 1" x-transition.opacity.duration.300ms>
    <h3 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-200 pb-2">Langkah 1: Pilih Template Surat</h3>

    {{-- Search Box --}}
    <div class="relative mb-6">
        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
        <input type="text" x-model="searchTemplate"
            placeholder="Cari template (misal: SKTM, Domisili)..."
            class="w-full bg-white border border-gray-300 rounded-xl pl-11 pr-4 py-3 text-sm focus:ring-2 focus:ring-green-500 outline-none shadow-sm transition-all">
    </div>

    {{-- Grid Templates (Server-rendered from controller) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach ($templates as $index => $tpl)
        <div x-show="!searchTemplate || '{{ strtolower($tpl['label'] . ' ' . $tpl['short']) }}'.includes(searchTemplate.toLowerCase())"
             class="border-2 rounded-2xl p-5 transition-all relative overflow-hidden group bg-white"
             :class="templateKey === '{{ $tpl['key'] }}'
                 ? 'border-green-500 bg-green-50/50 shadow-md'
                 : 'border-gray-200 hover:border-green-300'">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-full {{ $tpl['iconBg'] }} {{ $tpl['iconColor'] }} flex items-center justify-center shrink-0">
                    <i class="{{ $tpl['icon'] }} text-xl"></i>
                </div>
                <div x-show="templateKey === '{{ $tpl['key'] }}'" class="text-green-500" x-cloak>
                    <i class="fa-solid fa-circle-check text-xl"></i>
                </div>
            </div>
            <h4 class="font-bold text-gray-900 leading-tight mb-2">{{ $tpl['label'] }}</h4>
            <p class="text-xs text-gray-500 mb-5">{{ $tpl['description'] }}</p>

            <div class="flex gap-2 mt-auto">
                <button
                    @click="selectTemplate({{ $index }}, '{{ $tpl['key'] }}', '{{ $tpl['label'] }}')"
                    class="flex-1 px-3 py-2 rounded-lg text-xs font-bold transition-colors cursor-pointer"
                    :class="templateKey === '{{ $tpl['key'] }}'
                        ? 'bg-green-600 text-white'
                        : 'bg-green-50 text-green-700 hover:bg-green-100'">
                    <span x-text="templateKey === '{{ $tpl['key'] }}' ? '✓ Dipilih' : 'Pilih'"></span>
                </button>
            </div>
        </div>
        @endforeach
    </div>
</section>
