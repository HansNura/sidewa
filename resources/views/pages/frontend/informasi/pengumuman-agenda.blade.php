@extends('layouts.frontend')

@section('title', $pageTitle . ' - Desa Sindangmukti')

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush

@section('content')
<main class="flex-grow pt-16 bg-gray-50">

    <!-- SECTION 1: HEADER SECTION -->
    <section class="relative py-12 overflow-hidden text-white bg-gradient-to-br from-green-800 to-green-600 md:py-16">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        <div class="relative z-10 px-4 mx-auto text-center max-w-7xl sm:px-6 lg:px-8">
            <span class="inline-block px-4 py-1 mb-4 text-sm font-semibold border rounded-full bg-green-700/50 text-green-100 border-green-500/30">
                <i class="fa-solid fa-bullhorn mr-1"></i> Informasi Publik
            </span>
            <h1 class="mb-4 text-4xl font-bold tracking-tight md:text-5xl">{{ $pageTitle }}</h1>
            <p class="max-w-2xl mx-auto text-lg leading-relaxed text-green-100">
                {{ $pageSubtitle }}
            </p>
        </div>
    </section>

    <div class="px-4 py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">

        <!-- SECTION 2: PENGUMUMAN PENTING (Highlight) -->
        @if($pengumumanPenting)
        <section class="mb-12">
            <div class="relative flex flex-col items-start gap-6 p-6 overflow-hidden transition-all border-l-4 border-amber-500 cursor-pointer bg-amber-50 rounded-r-2xl shadow-sm md:flex-row md:items-center group hover:shadow-md"
                x-data x-on:click="$refs.modalPengumuman.showModal()">
                <!-- Live indicator -->
                <div class="absolute flex w-3 h-3 top-4 right-4">
                    <span class="absolute inline-flex w-full h-full rounded-full opacity-75 animate-ping bg-amber-400"></span>
                    <span class="relative inline-flex w-3 h-3 rounded-full bg-amber-500"></span>
                </div>

                <div class="flex items-center justify-center shrink-0 w-14 h-14 rounded-full bg-amber-100 text-amber-600">
                    <i class="fa-solid fa-bell text-2xl"></i>
                </div>

                <div class="flex-grow">
                    <div class="flex items-center gap-3 mb-2 text-xs font-bold tracking-wide uppercase text-amber-700">
                        <span><i class="fa-solid fa-triangle-exclamation mr-1"></i> Pengumuman Penting</span>
                        <span class="text-amber-300">|</span>
                        <span>Diterbitkan: {{ $pengumumanPenting->formatted_date }}</span>
                    </div>
                    <h2 class="mb-2 text-xl font-bold text-gray-800 transition-colors md:text-2xl group-hover:text-amber-700">
                        {{ $pengumumanPenting->title }}
                    </h2>
                    <p class="text-gray-600 line-clamp-2">
                        {{ $pengumumanPenting->excerpt_text }}
                    </p>
                </div>

                <div class="shrink-0 md:pl-4 md:border-l border-amber-200">
                    <span class="flex items-center px-5 py-2 font-semibold text-white transition-colors rounded-lg whitespace-nowrap bg-amber-500 hover:bg-amber-600">
                        Lihat Detail <i class="fa-solid fa-chevron-right ml-2 text-sm"></i>
                    </span>
                </div>
            </div>

            <!-- Modal Detail Pengumuman -->
            <dialog x-ref="modalPengumuman" class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full p-0 overflow-hidden backdrop:bg-black/60 backdrop:backdrop-blur-sm">
                <div class="relative h-48 bg-amber-600">
                    <img src="{{ $pengumumanPenting->image_url }}" alt="Cover" class="object-cover w-full h-full opacity-60 mix-blend-multiply">
                    <div class="absolute left-6 right-6 bottom-4">
                        <span class="inline-block px-3 py-1 mb-2 text-xs font-bold tracking-wider text-white uppercase bg-amber-500 rounded-md shadow-sm">Pengumuman</span>
                        <h2 class="text-2xl font-bold text-white drop-shadow-md">{{ $pengumumanPenting->title }}</h2>
                    </div>
                    <form method="dialog" class="absolute top-4 right-4">
                        <button class="flex items-center justify-center w-8 h-8 text-white transition-colors rounded-full bg-black/20 hover:bg-black/40"><i class="fa-solid fa-times"></i></button>
                    </form>
                </div>
                <div class="p-6 md:p-8">
                    <div class="grid grid-cols-1 gap-4 p-4 mb-6 border border-gray-100 sm:grid-cols-2 bg-gray-50 rounded-xl">
                        <div class="flex gap-3">
                            <i class="fa-regular fa-calendar-check text-amber-600 mt-1 text-lg"></i>
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase">Tanggal</p>
                                <p class="text-sm font-bold text-gray-800">{{ $pengumumanPenting->formatted_date_full }}</p>
                                @if($pengumumanPenting->time_text)
                                    <p class="text-sm text-gray-600">{{ $pengumumanPenting->time_text }}</p>
                                @endif
                            </div>
                        </div>
                        @if($pengumumanPenting->location)
                        <div class="flex gap-3">
                            <i class="fa-solid fa-location-dot text-red-500 mt-1 text-lg"></i>
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase">Lokasi</p>
                                <p class="text-sm font-bold text-gray-800">{{ $pengumumanPenting->location }}</p>
                            </div>
                        </div>
                        @endif
                        @if($pengumumanPenting->contact_person)
                        <div class="flex gap-3 sm:col-span-2">
                            <i class="fa-regular fa-user text-blue-500 mt-1 text-lg"></i>
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase">Narahubung</p>
                                <p class="text-sm font-bold text-gray-800">{{ $pengumumanPenting->contact_person }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                    <h3 class="pb-2 mb-2 font-bold text-gray-800 border-b border-gray-200">Detail Pengumuman</h3>
                    <div class="mb-6 space-y-3 text-sm leading-relaxed text-gray-600 prose max-w-none">
                        {!! $pengumumanPenting->content_html !!}
                    </div>
                    <div class="flex justify-end gap-3 pt-5 border-t border-gray-100">
                        <form method="dialog">
                            <button class="px-4 py-2 text-sm font-semibold text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Tutup</button>
                        </form>
                    </div>
                </div>
            </dialog>
        </section>
        @endif

        <!-- LAYOUT GRID -->
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12 lg:gap-10">

            <!-- KOLOM KIRI (Sidebar) -->
            <aside class="flex flex-col gap-6 lg:col-span-4">

                <!-- FILTER WAKTU -->
                <section class="p-6 bg-white border border-gray-200 shadow-sm rounded-2xl">
                    <h3 class="flex items-center mb-4 text-lg font-bold text-gray-800">
                        <i class="fa-solid fa-filter text-green-600 mr-2"></i> Filter Agenda
                    </h3>
                    <form action="{{ route('informasi.pengumuman') }}" method="GET" class="flex flex-col gap-4">
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700">Bulan</label>
                            <select name="bulan" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block p-2.5">
                                @php
                                    $namaBulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                                @endphp
                                @for($b = 1; $b <= 12; $b++)
                                    <option value="{{ $b }}" {{ $bulan == $b ? 'selected' : '' }}>{{ $namaBulan[$b-1] }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700">Tahun</label>
                            <select name="tahun" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block p-2.5">
                                @foreach($availableYears as $yr)
                                    <option value="{{ $yr }}" {{ $tahun == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="w-full text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mt-2 transition-colors">
                            Terapkan Filter
                        </button>
                    </form>
                </section>

                <!-- KALENDER AGENDA (Dynamic) -->
                <section class="p-6 bg-white border border-gray-200 shadow-sm rounded-2xl" x-data="calendarWidget()">
                    <div class="flex items-center justify-between mb-4">
                        <a href="{{ route('informasi.pengumuman', ['bulan' => $bulan == 1 ? 12 : $bulan - 1, 'tahun' => $bulan == 1 ? $tahun - 1 : $tahun]) }}"
                           class="text-gray-400 transition-colors hover:text-green-600">
                            <i class="fa-solid fa-chevron-left"></i>
                        </a>
                        <h3 class="font-bold text-gray-800">
                            @php $namaBulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember']; @endphp
                            {{ $namaBulan[$bulan - 1] }} {{ $tahun }}
                        </h3>
                        <a href="{{ route('informasi.pengumuman', ['bulan' => $bulan == 12 ? 1 : $bulan + 1, 'tahun' => $bulan == 12 ? $tahun + 1 : $tahun]) }}"
                           class="text-gray-400 transition-colors hover:text-green-600">
                            <i class="fa-solid fa-chevron-right"></i>
                        </a>
                    </div>

                    <!-- Nama Hari -->
                    <div class="grid grid-cols-7 gap-1 mb-2 text-xs font-semibold text-center text-gray-500">
                        <div>M</div><div>S</div><div>S</div><div>R</div><div>K</div><div>J</div><div>S</div>
                    </div>

                    <!-- Grid Tanggal (Dynamic via Alpine.js) -->
                    <div class="grid grid-cols-7 gap-1 text-sm font-medium text-center text-gray-700">
                        @php
                            $firstDay = \Carbon\Carbon::create($tahun, $bulan, 1);
                            $startDow = $firstDay->dayOfWeek; // 0=Sunday
                            $daysInMonth = $firstDay->daysInMonth;
                            $prevMonth = $firstDay->copy()->subMonth();
                            $prevDays = $prevMonth->daysInMonth;
                            $today = now();
                            $isCurrentMonth = ($today->month == $bulan && $today->year == $tahun);
                            $todayDay = $today->day;

                            // Build event map
                            $eventMap = [];
                            foreach ($calendarEvents as $ev) {
                                $eventMap[$ev['day']] = $ev['type'];
                            }
                        @endphp

                        {{-- Previous month trailing days --}}
                        @for($i = $startDow - 1; $i >= 0; $i--)
                            <div class="py-1.5 text-gray-300">{{ $prevDays - $i }}</div>
                        @endfor

                        {{-- Current month days --}}
                        @for($d = 1; $d <= $daysInMonth; $d++)
                            @php
                                $hasEvent = isset($eventMap[$d]);
                                $eventType = $hasEvent ? $eventMap[$d] : null;
                                $isToday = $isCurrentMonth && $d == $todayDay;
                            @endphp

                            @if($isToday)
                                <div class="py-1.5 bg-green-600 text-white shadow-md font-bold rounded-md cursor-pointer relative">
                                    {{ $d }}
                                    @if($hasEvent)
                                        <span class="absolute w-1.5 h-1.5 bg-white rounded-full bottom-0.5 left-1/2 transform -translate-x-1/2"></span>
                                    @endif
                                </div>
                            @elseif($hasEvent && $eventType === 'agenda')
                                <div class="py-1.5 bg-green-100 text-green-700 font-bold rounded-md cursor-pointer ring-1 ring-green-400 relative">
                                    {{ $d }}
                                    <span class="absolute w-1.5 h-1.5 bg-green-600 rounded-full bottom-0.5 left-1/2 transform -translate-x-1/2"></span>
                                </div>
                            @elseif($hasEvent && $eventType === 'pengumuman')
                                <div class="py-1.5 bg-red-100 text-red-700 font-bold rounded-md cursor-pointer ring-1 ring-red-400 relative">
                                    {{ $d }}
                                    <span class="absolute w-1.5 h-1.5 bg-red-600 rounded-full bottom-0.5 left-1/2 transform -translate-x-1/2"></span>
                                </div>
                            @else
                                <div class="py-1.5 hover:bg-gray-100 rounded-md cursor-pointer transition-colors">{{ $d }}</div>
                            @endif
                        @endfor

                        {{-- Next month leading days --}}
                        @php $remaining = 7 - (($startDow + $daysInMonth) % 7); @endphp
                        @if($remaining < 7)
                            @for($d = 1; $d <= $remaining; $d++)
                                <div class="py-1.5 text-gray-300">{{ $d }}</div>
                            @endfor
                        @endif
                    </div>

                    <!-- Keterangan Kalender -->
                    <div class="flex flex-wrap gap-3 pt-4 mt-4 text-xs text-gray-500 border-t border-gray-100">
                        <span class="flex items-center"><span class="w-2.5 h-2.5 bg-green-600 rounded-full mr-1.5"></span> Hari Ini</span>
                        <span class="flex items-center"><span class="w-2.5 h-2.5 bg-green-300 rounded-full mr-1.5"></span> Agenda</span>
                        <span class="flex items-center"><span class="w-2.5 h-2.5 bg-red-500 rounded-full mr-1.5"></span> Pengumuman</span>
                    </div>
                </section>
            </aside>

            <!-- KOLOM KANAN (List Agenda) -->
            <article class="lg:col-span-8">
                <div class="flex items-center justify-between pb-2 mb-6 border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-800">
                        Daftar Kegiatan
                        @php $namaBulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember']; @endphp
                        {{ $namaBulan[$bulan - 1] }} {{ $tahun }}
                    </h2>
                    <span class="px-3 py-1 text-sm font-semibold text-gray-600 bg-gray-100 rounded-full">{{ $daftarAgenda->total() }} Agenda</span>
                </div>

                @if($daftarAgenda->count() > 0)
                <div class="flex flex-col gap-5">
                    @foreach($daftarAgenda as $index => $agenda)
                    @php
                        $colors = ['green', 'amber', 'blue', 'purple', 'rose'];
                        $color = $colors[$index % count($colors)];

                        $bgMap = ['green' => 'from-green-50', 'amber' => 'from-amber-50', 'blue' => 'from-blue-50', 'purple' => 'from-purple-50', 'rose' => 'from-rose-50'];
                        $borderMap = ['green' => 'border-green-100', 'amber' => 'border-amber-100', 'blue' => 'border-blue-100', 'purple' => 'border-purple-100', 'rose' => 'border-rose-100'];
                        $textMap = ['green' => 'text-green-700', 'amber' => 'text-amber-700', 'blue' => 'text-blue-700', 'purple' => 'text-purple-700', 'rose' => 'text-rose-700'];
                        $clockMap = ['green' => 'text-green-500', 'amber' => 'text-amber-500', 'blue' => 'text-blue-500', 'purple' => 'text-purple-500', 'rose' => 'text-rose-500'];
                    @endphp
                    <div class="flex flex-col gap-5 p-5 transition-all bg-white border border-gray-200 shadow-sm sm:flex-row rounded-xl hover:-translate-y-1 hover:shadow-md group"
                         x-data x-on:click="$refs.modalAgenda{{ $agenda->id }}.showModal()">
                        <!-- Date Box -->
                        <div class="flex flex-col items-center justify-center w-full shadow-inner sm:w-24 h-20 sm:h-24 shrink-0 bg-gradient-to-b {{ $bgMap[$color] }} to-white border {{ $borderMap[$color] }} rounded-lg {{ $textMap[$color] }}">
                            <span class="mb-1 text-sm font-bold tracking-wider uppercase">{{ $agenda->month_short }}</span>
                            <span class="text-3xl font-black leading-none transition-transform group-hover:scale-110">{{ $agenda->day }}</span>
                        </div>

                        <!-- Info -->
                        <div class="flex flex-col justify-center flex-grow">
                            <h3 class="mb-2 text-lg font-bold text-gray-800 transition-colors md:text-xl group-hover:text-green-600">
                                {{ $agenda->title }}
                            </h3>
                            <div class="flex flex-wrap items-center gap-4 mb-3 text-sm text-gray-600">
                                @if($agenda->time_text)
                                <span class="flex items-center">
                                    <i class="fa-regular fa-clock {{ $clockMap[$color] }} mr-2"></i> {{ $agenda->time_text }}
                                </span>
                                @endif
                                @if($agenda->location)
                                <span class="flex items-center">
                                    <i class="fa-solid fa-location-dot text-red-400 mr-2"></i> {{ $agenda->location }}
                                </span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-500 line-clamp-2">
                                {{ $agenda->excerpt_text }}
                            </p>
                        </div>

                        <!-- Action -->
                        <div class="flex items-center sm:border-l sm:border-gray-100 sm:pl-5 shrink-0">
                            <button class="w-full px-4 py-2 text-sm font-semibold text-green-600 transition-colors border border-green-200 rounded-lg sm:w-auto bg-gray-50 hover:bg-green-600 hover:text-white hover:border-green-600">
                                Detail Acara
                            </button>
                        </div>
                    </div>

                    <!-- Modal Detail for each Agenda -->
                    <dialog x-ref="modalAgenda{{ $agenda->id }}" class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full p-0 overflow-hidden backdrop:bg-black/60 backdrop:backdrop-blur-sm">
                        <div class="relative h-48 bg-green-700">
                            <img src="{{ $agenda->image_url }}" alt="Cover Agenda" class="object-cover w-full h-full opacity-60 mix-blend-multiply">
                            <div class="absolute left-6 right-6 bottom-4">
                                <span class="inline-block px-3 py-1 mb-2 text-xs font-bold tracking-wider text-white uppercase bg-green-500 rounded-md shadow-sm">Agenda Desa</span>
                                <h2 class="text-2xl font-bold text-white drop-shadow-md">{{ $agenda->title }}</h2>
                            </div>
                            <form method="dialog" class="absolute top-4 right-4">
                                <button class="flex items-center justify-center w-8 h-8 text-white transition-colors rounded-full bg-black/20 hover:bg-black/40"><i class="fa-solid fa-times"></i></button>
                            </form>
                        </div>
                        <div class="p-6 md:p-8">
                            <div class="grid grid-cols-1 gap-4 p-4 mb-6 border border-gray-100 sm:grid-cols-2 bg-gray-50 rounded-xl">
                                <div class="flex gap-3">
                                    <i class="fa-regular fa-calendar-check text-green-600 mt-1 text-lg"></i>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 uppercase">Tanggal Pelaksanaan</p>
                                        <p class="text-sm font-bold text-gray-800">{{ $agenda->formatted_date_full }}</p>
                                        @if($agenda->time_text)
                                            <p class="text-sm text-gray-600">Pukul {{ $agenda->time_text }}</p>
                                        @endif
                                    </div>
                                </div>
                                @if($agenda->location)
                                <div class="flex gap-3">
                                    <i class="fa-solid fa-location-dot text-red-500 mt-1 text-lg"></i>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 uppercase">Lokasi Acara</p>
                                        <p class="text-sm font-bold text-gray-800">{{ $agenda->location }}</p>
                                    </div>
                                </div>
                                @endif
                                @if($agenda->contact_person)
                                <div class="flex gap-3 sm:col-span-2">
                                    <i class="fa-regular fa-user text-blue-500 mt-1 text-lg"></i>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 uppercase">Koordinator / Narahubung</p>
                                        <p class="text-sm font-bold text-gray-800">{{ $agenda->contact_person }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <h3 class="pb-2 mb-2 font-bold text-gray-800 border-b border-gray-200">Deskripsi Kegiatan</h3>
                            <div class="mb-6 space-y-3 text-sm leading-relaxed text-gray-600 prose max-w-none">
                                {!! $agenda->content_html !!}
                            </div>
                            <div class="flex justify-end gap-3 pt-5 border-t border-gray-100">
                                <a href="https://wa.me/?text={{ urlencode($agenda->title . ' - ' . $agenda->formatted_date_full . ' di ' . ($agenda->location ?? 'TBA')) }}" target="_blank"
                                   class="px-4 py-2 text-sm font-semibold text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    <i class="fa-solid fa-share-nodes mr-2"></i> Bagikan
                                </a>
                                <form method="dialog">
                                    <button class="px-6 py-2 text-sm font-semibold text-white bg-green-600 rounded-lg shadow-md hover:bg-green-700 transition-colors">
                                        Tutup
                                    </button>
                                </form>
                            </div>
                        </div>
                    </dialog>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($daftarAgenda->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $daftarAgenda->links() }}
                </div>
                @endif

                @else
                <div class="text-center py-16 bg-white rounded-2xl border border-gray-200">
                    <i class="fa-regular fa-calendar-xmark text-5xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-bold text-gray-700 mb-2">Belum Ada Agenda</h3>
                    <p class="text-sm text-gray-500">
                        Tidak ada kegiatan yang dijadwalkan untuk bulan {{ $namaBulan[$bulan - 1] }} {{ $tahun }}.
                    </p>
                </div>
                @endif

            </article>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    function calendarWidget() {
        return {}; // Calendar is rendered server-side via Blade
    }
</script>
@endpush
