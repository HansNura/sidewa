{{-- Input Penerima Bantuan Modal --}}
<div x-show="addModalOpen" class="fixed inset-0 z-[110] flex items-center justify-center p-4 sm:p-6 !m-0" x-cloak>
    <div x-show="addModalOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
        @click="addModalOpen = false"></div>

    <div x-show="addModalOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden flex flex-col max-h-[90vh]">

        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 shrink-0">
            <h3 class="font-extrabold text-lg text-gray-900">Input Penerima Bantuan</h3>
            <button @click="addModalOpen = false; selectedPenduduk = null"
                class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <div class="p-6 overflow-y-auto custom-scrollbar flex-1 space-y-5">
            <form id="bansosForm" method="POST" action="{{ route('admin.bansos.store') }}" class="space-y-5">
                @csrf

                {{-- Program --}}
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700">Pilih Program Bantuan <span
                            class="text-red-500">*</span></label>
                    <select name="program_bansos_id" required
                        class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none font-semibold cursor-pointer">
                        <option value="">-- Pilih Program --</option>
                        @foreach ($programList->where('status', 'aktif') as $prog)
                            <option value="{{ $prog->id }}"
                                {{ old('program_bansos_id') == $prog->id ? 'selected' : '' }}>
                                {{ $prog->nama }} - {{ $prog->periode }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Search Penduduk --}}
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700">Cari KPM (Penduduk/KK) <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" placeholder="Ketik NIK atau Nama..." x-show="!selectedPenduduk"
                            @input.debounce.300ms="searchPenduduk($event.target.value)"
                            class="w-full bg-white border border-gray-300 rounded-lg pl-9 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none">

                        {{-- Selected display --}}
                        <div x-show="selectedPenduduk"
                            class="flex items-center justify-between bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 bg-white rounded-full flex items-center justify-center border border-gray-200">
                                    <i class="fa-solid fa-user text-gray-400"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900" x-text="selectedPenduduk?.nama"></p>
                                    <p class="text-[10px] font-mono text-gray-500"
                                        x-text="'NIK: ' + selectedPenduduk?.nik"></p>
                                </div>
                            </div>
                            <button type="button" @click="selectedPenduduk = null"
                                class="text-red-400 hover:text-red-600 cursor-pointer">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>

                        <input type="hidden" name="penduduk_id" :value="selectedPenduduk?.id ?? ''">
                    </div>

                    {{-- Search results --}}
                    <div x-show="searchResults.length > 0 && !selectedPenduduk"
                        class="mt-2 bg-white border border-gray-200 rounded-lg shadow-lg max-h-48 overflow-y-auto divide-y divide-gray-50">
                        <template x-for="item in searchResults" :key="item.id">
                            <button type="button" @click="selectPenduduk(item)"
                                class="w-full text-left px-4 py-2.5 hover:bg-green-50 transition-colors cursor-pointer">
                                <p class="text-sm font-bold text-gray-800" x-text="item.nama"></p>
                                <p class="text-[10px] text-gray-500">
                                    <span x-text="'NIK: ' + item.nik"></span> ·
                                    <span x-text="'Dusun ' + (item.dusun || '-')"></span>
                                </p>
                            </button>
                        </template>
                    </div>
                </div>

                {{-- Tahap & Desil --}}
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700">Tahap</label>
                        <input type="text" name="tahap" value="{{ old('tahap') }}"
                            placeholder="Contoh: Tahap 2 (April)"
                            class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700">Desil Kemiskinan</label>
                        <select name="desil"
                            class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none cursor-pointer">
                            <option value="">-- Pilih --</option>
                            @for ($i = 1; $i <= 4; $i++)
                                <option value="{{ $i }}" {{ old('desil') == $i ? 'selected' : '' }}>Desil
                                    {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                {{-- Validation Errors --}}
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-lg p-2.5 flex items-start gap-2">
                        <i class="fa-solid fa-circle-exclamation text-red-600 mt-0.5 text-xs"></i>
                        <div>
                            @foreach ($errors->all() as $error)
                                <p class="text-[10px] text-red-800 font-medium">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif
            </form>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 shrink-0 flex justify-end gap-3 rounded-b-2xl">
            <button type="button" @click="addModalOpen = false; selectedPenduduk = null"
                class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-600 border border-gray-200 hover:bg-gray-100 transition-colors bg-white cursor-pointer">Batal</button>
            <button type="submit" form="bansosForm"
                class="px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-green-700 hover:bg-green-800 shadow-md transition-all cursor-pointer">
                <i class="fa-solid fa-plus mr-2"></i> Tambahkan
            </button>
        </div>
    </div>
</div>
