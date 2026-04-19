<!-- TAB 2: KALENDER AGENDA DESA -->
<div x-show="activeTab === 'calendar'" x-transition.opacity class="space-y-6" x-cloak>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">

        <!-- Calendar Header -->
        <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.informasi.index', ['tab' => 'calendar', 'month' => $calendarDate->copy()->subMonth()->format('Y-m')]) }}" class="w-8 h-8 rounded-lg bg-white border border-gray-200 text-gray-500 hover:bg-gray-50 flex items-center justify-center transition-colors"><i class="fa-solid fa-chevron-left text-xs"></i></a>
                <h3 class="font-extrabold text-lg text-gray-900 w-40 text-center">{{ $calendarDate->translatedFormat('F Y') }}</h3>
                <a href="{{ route('admin.informasi.index', ['tab' => 'calendar', 'month' => $calendarDate->copy()->addMonth()->format('Y-m')]) }}" class="w-8 h-8 rounded-lg bg-white border border-gray-200 text-gray-500 hover:bg-gray-50 flex items-center justify-center transition-colors"><i class="fa-solid fa-chevron-right text-xs"></i></a>
                <a href="{{ route('admin.informasi.index', ['tab' => 'calendar']) }}" class="ml-2 text-xs font-bold text-green-600 bg-green-50 px-3 py-1.5 rounded-lg hover:bg-green-100 transition-colors hidden sm:block">Bulan Ini</a>
            </div>
            <div class="flex items-center gap-4 text-[10px] font-bold uppercase tracking-wider text-gray-500">
                <span class="flex items-center gap-1.5">
                    <div class="w-3 h-3 rounded bg-purple-500"></div> Agenda Desa
                </span>
                <span class="flex items-center gap-1.5">
                    <div class="w-3 h-3 rounded bg-blue-500"></div> Pengumuman
                </span>
            </div>
        </div>

        <!-- The Calendar Grid -->
        <div class="p-4 bg-gray-50/30">
            <!-- Days of Week -->
            <div class="grid grid-cols-7 gap-1 text-center mb-1">
                <div class="py-2 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Sen</div>
                <div class="py-2 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Sel</div>
                <div class="py-2 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Rab</div>
                <div class="py-2 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Kam</div>
                <div class="py-2 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Jum</div>
                <div class="py-2 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Sab</div>
                <div class="py-2 text-[10px] font-extrabold text-red-400 uppercase tracking-widest">Min</div>
            </div>

            <!-- Grid Cells -->
            <div class="calendar-grid rounded-xl overflow-hidden shadow-sm">
                @foreach($calendarGrid as $cell)
                    <div class="calendar-cell {{ !$cell['is_current_month'] ? 'inactive' : '' }} {{ $cell['is_today'] ? 'today shadow-inner border-2 border-green-500 relative' : '' }}">
                        @if($cell['is_today'])
                            <span class="absolute -top-2 -left-2 w-4 h-4 bg-green-500 text-white text-[8px] font-bold rounded-full flex items-center justify-center">{{ $cell['day'] }}</span>
                            <span class="text-xs font-bold text-green-700 block text-right">HARI INI</span>
                        @else
                            <span class="text-xs font-bold {{ Carbon\Carbon::parse($cell['date'])->dayOfWeekIso == 7 ? 'text-red-500' : '' }}">{{ $cell['day'] }}</span>
                        @endif

                        @foreach($cell['items'] as $item)
                            <div @click="openDetail({{ $item->id }})" class="mt-1 text-[9px] font-bold p-1 rounded truncate border leading-none cursor-pointer group hover:opacity-80 transition-opacity {{ $item->type == 'agenda' ? 'bg-purple-100 text-purple-700 border-purple-200' : 'bg-blue-100 text-blue-700 border-blue-200' }}" title="{{ $item->title }}">
                                {{ $item->type == 'agenda' ? '' : '📢 ' }} {{ \Illuminate\Support\Str::limit($item->title, 15) }}
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
