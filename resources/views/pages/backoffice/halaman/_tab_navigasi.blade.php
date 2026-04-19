<!-- TAB 2: STRUKTUR MENU NAVIGASI -->
<div x-show="activeTab === 'structure'" x-transition.opacity class="grid grid-cols-1 lg:grid-cols-2 gap-6" x-cloak>
    <!-- Arrow Based Tree View -->
    <div class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden flex flex-col">
        <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <div>
                <h4 class="font-bold text-gray-800">Hierarki Menu Utama</h4>
                <p class="text-[10px] text-gray-500 mt-0.5">Atur urutan menu di header web menggunakan panah naik turun.</p>
            </div>
            <!-- Dummy btn for style parity -->
            <button class="text-xs font-bold text-green-600 bg-green-50 px-3 py-1.5 rounded-lg border border-green-200 shadow-sm hover:bg-green-100 transition-colors cursor-default">Disimpan Otomatis</button>
        </div>
        <div class="p-5 flex-1 space-y-2 bg-gray-50/30">

            @foreach($rootPages as $root)
                <div class="bg-white border border-gray-200 rounded-xl p-3 flex flex-col gap-2 shadow-sm transition-colors hover:border-green-300">
                    <div class="flex items-center gap-3">
                        <!-- Up/Down Controls -->
                        <div class="flex flex-col gap-1 items-center justify-center">
                            @if(!$loop->first)
                                <a href="{{ route('admin.halaman.move', [$root->id, 'up']) }}" class="text-gray-300 hover:text-green-600"><i class="fa-solid fa-caret-up text-xs"></i></a>
                            @else
                                <span class="text-transparent"><i class="fa-solid fa-caret-up text-xs"></i></span>
                            @endif

                            @if(!$loop->last)
                                <a href="{{ route('admin.halaman.move', [$root->id, 'down']) }}" class="text-gray-300 hover:text-green-600"><i class="fa-solid fa-caret-down text-xs"></i></a>
                            @else
                                <span class="text-transparent"><i class="fa-solid fa-caret-down text-xs"></i></span>
                            @endif
                        </div>

                        <span class="text-sm font-bold text-gray-800 flex-1">{{ $root->title }}</span>
                        <span class="text-[10px] text-gray-400 bg-gray-100 px-2 py-0.5 rounded font-mono">/{{ $root->slug }}</span>
                    </div>

                    <!-- Children -->
                    @if($root->children->count() > 0)
                        <div class="ml-6 pl-4 border-l-2 border-dashed border-gray-200 space-y-2 pt-2">
                            @foreach($root->children as $child)
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-2 flex items-center gap-3 shadow-sm hover:border-green-300">
                                    <div class="flex flex-col gap-1 items-center justify-center -ml-1">
                                        @if(!$loop->first)
                                            <a href="{{ route('admin.halaman.move', [$child->id, 'up']) }}" class="text-gray-300 hover:text-green-600"><i class="fa-solid fa-caret-up text-[10px]"></i></a>
                                        @else
                                            <span class="text-transparent"><i class="fa-solid fa-caret-up text-[10px]"></i></span>
                                        @endif

                                        @if(!$loop->last)
                                            <a href="{{ route('admin.halaman.move', [$child->id, 'down']) }}" class="text-gray-300 hover:text-green-600"><i class="fa-solid fa-caret-down text-[10px]"></i></a>
                                        @else
                                            <span class="text-transparent"><i class="fa-solid fa-caret-down text-[10px]"></i></span>
                                        @endif
                                    </div>
                                    <span class="text-xs font-semibold text-gray-700 flex-1">{{ $child->title }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach

        </div>
    </div>

    <!-- Navigation Preview Simulation -->
    <div class="bg-gray-800 rounded-2xl shadow-sm border border-gray-700 overflow-hidden flex flex-col h-64 sticky top-6">
        <div class="p-4 border-b border-gray-700 flex justify-between items-center">
            <h4 class="font-bold text-white text-sm"><i class="fa-solid fa-desktop text-gray-400 mr-2"></i> Pratinjau Header Web</h4>
        </div>
        <div class="flex-1 bg-white p-6 flex flex-col items-center justify-center">
            <!-- Simulated Navbar -->
            <div class="w-full max-w-md bg-white border border-gray-200 shadow-md rounded-full px-6 py-3 flex items-center justify-between">
                <div class="font-black text-green-700 text-sm tracking-widest">DESAKU</div>
                <div class="flex gap-4 text-[10px] font-bold text-gray-600">
                    @foreach($rootPages as $root)
                        @if($root->slug == '/')
                            <span class="text-green-600">{{ $root->title }}</span>
                        @else
                            <span class="flex items-center gap-1 cursor-pointer">
                                {{ $root->title }}
                                @if($root->children->count() > 0)
                                    <i class="fa-solid fa-angle-down text-[8px]"></i>
                                @endif
                            </span>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
