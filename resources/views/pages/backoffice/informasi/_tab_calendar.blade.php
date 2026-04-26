<!-- TAB 2: KALENDER AGENDA DESA -->
<div x-show="activeTab === 'calendar'" x-transition.opacity class="space-y-6" x-cloak>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">

        <!-- Calendar Header -->
        <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.informasi.index', ['tab' => 'calendar', 'month' => $calendarDate->copy()->subMonth()->format('Y-m')]) }}" class="w-8 h-8 rounded-lg bg-white border border-gray-200 text-gray-500 hover:bg-gray-50 hover:text-green-600 flex items-center justify-center transition-all shadow-sm"><i class="fa-solid fa-chevron-left text-xs"></i></a>
                <h3 class="font-extrabold text-lg text-gray-900 w-40 text-center">{{ $calendarDate->translatedFormat('F Y') }}</h3>
                <a href="{{ route('admin.informasi.index', ['tab' => 'calendar', 'month' => $calendarDate->copy()->addMonth()->format('Y-m')]) }}" class="w-8 h-8 rounded-lg bg-white border border-gray-200 text-gray-500 hover:bg-gray-50 hover:text-green-600 flex items-center justify-center transition-all shadow-sm"><i class="fa-solid fa-chevron-right text-xs"></i></a>
                <a href="{{ route('admin.informasi.index', ['tab' => 'calendar']) }}" class="ml-2 text-xs font-bold text-green-600 bg-green-50 border border-green-200 px-3 py-1.5 rounded-lg hover:bg-green-100 transition-colors shadow-sm hidden sm:block">Bulan Ini</a>
            </div>
            <div class="flex items-center gap-4 text-[10px] font-bold uppercase tracking-wider text-gray-500">
                <span class="flex items-center gap-1.5">
                    <div class="w-3 h-3 rounded-full bg-purple-500 shadow-inner"></div> Agenda Desa
                </span>
                <span class="flex items-center gap-1.5">
                    <div class="w-3 h-3 rounded-full bg-blue-500 shadow-inner"></div> Pengumuman
                </span>
            </div>
        </div>

        <!-- The Calendar Grid -->
        <div class="p-5 bg-gray-50/50">
            <!-- Days of Week -->
            <div class="grid grid-cols-7 gap-1 text-center mb-2">
                <div class="py-2 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Sen</div>
                <div class="py-2 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Sel</div>
                <div class="py-2 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Rab</div>
                <div class="py-2 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Kam</div>
                <div class="py-2 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Jum</div>
                <div class="py-2 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Sab</div>
                <div class="py-2 text-[10px] font-extrabold text-red-400 uppercase tracking-widest">Min</div>
            </div>

            <!-- Grid Cells -->
            <div class="calendar-grid rounded-xl overflow-hidden shadow-sm border border-gray-200">
                @foreach($calendarGrid as $cell)
                    <div class="calendar-cell {{ !$cell['is_current_month'] ? 'inactive' : '' }} {{ $cell['is_today'] ? 'today shadow-inner border-2 border-green-500 relative' : '' }}">
                        @if($cell['is_today'])
                            <span class="absolute -top-2 -left-2 w-5 h-5 bg-green-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center shadow-sm">{{ $cell['day'] }}</span>
                            <span class="text-[9px] font-extrabold text-green-700 block text-right mt-1">HARI INI</span>
                        @else
                            <span class="text-xs font-bold {{ Carbon\Carbon::parse($cell['date'])->dayOfWeekIso == 7 ? 'text-red-500' : 'text-gray-600' }} block text-right">{{ $cell['day'] }}</span>
                        @endif

                        <div class="mt-2 space-y-1">
                        @foreach($cell['items'] as $item)
                            <div @click="openDetail({{ $item->id }})" class="text-[9px] font-bold p-1.5 rounded-lg truncate border leading-none cursor-pointer group hover:-translate-y-0.5 hover:shadow-sm transition-all {{ $item->type == 'agenda' ? 'bg-purple-50 text-purple-700 border-purple-200' : 'bg-blue-50 text-blue-700 border-blue-200' }}" title="{{ $item->title }}">
                                <span class="mr-1"><i class="{{ $item->type == 'agenda' ? 'fa-regular fa-calendar-check' : 'fa-solid fa-bullhorn' }}"></i></span> {{ \Illuminate\Support\Str::limit($item->title, 15) }}
                            </div>
                        @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
