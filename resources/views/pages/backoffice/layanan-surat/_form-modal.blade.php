{{-- Create Surat Modal --}}
<div x-show="addModalOpen" class="fixed inset-0 z-[110] flex items-center justify-center p-4 sm:p-6" x-cloak>
    <div x-show="addModalOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
         @click="addModalOpen = false"></div>

    <div x-show="addModalOpen" x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
         class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden flex flex-col max-h-[90vh]">

        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 shrink-0">
            <h3 class="font-extrabold text-lg text-gray-900">Buat Permohonan Surat</h3>
            <button @click="addModalOpen = false; selectedPenduduk = null"
                class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <div class="p-6 overflow-y-auto custom-scrollbar flex-1 space-y-5">
            <form id="suratForm" method="POST" action="{{ route('admin.layanan-surat.store') }}" class="space-y-5">
                @csrf

                {{-- Jenis Surat --}}
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700">Jenis Surat <span class="text-red-500">*</span></label>
                    <select name="jenis_surat" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none font-semibold cursor-pointer">
                        @foreach (\App\Models\SuratPermohonan::JENIS_LABELS as $key => $label)
                            <option value="{{ $key }}" {{ old('jenis_surat') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Search Pemohon --}}
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700">Pemohon (Warga) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" placeholder="Ketik NIK atau Nama..."
                               x-show="!selectedPenduduk"
                               @input.debounce.300ms="searchPenduduk($event.target.value)"
                               class="w-full bg-white border border-gray-300 rounded-lg pl-9 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none">

                        <div x-show="selectedPenduduk" class="flex items-center justify-between bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center border border-gray-200">
                                    <i class="fa-solid fa-user text-gray-400"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900" x-text="selectedPenduduk?.nama"></p>
                                    <p class="text-[10px] font-mono text-gray-500" x-text="'NIK: ' + selectedPenduduk?.nik"></p>
                                </div>
                            </div>
                            <button type="button" @click="selectedPenduduk = null" class="text-red-400 hover:text-red-600 cursor-pointer">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>

                        <input type="hidden" name="penduduk_id" :value="selectedPenduduk?.id ?? ''">
                    </div>

                    <div x-show="searchResults.length > 0 && !selectedPenduduk"
                         class="mt-2 bg-white border border-gray-200 rounded-lg shadow-lg max-h-48 overflow-y-auto divide-y divide-gray-50">
                        <template x-for="item in searchResults" :key="item.id">
                            <button type="button" @click="selectPenduduk(item)"
                                    class="w-full text-left px-4 py-2.5 hover:bg-green-50 transition-colors cursor-pointer">
                                <p class="text-sm font-bold text-gray-800" x-text="item.nama"></p>
                                <p class="text-[10px] text-gray-500"><span x-text="'NIK: ' + item.nik"></span></p>
                            </button>
                        </template>
                    </div>
                </div>

                {{-- Prioritas --}}
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700">Prioritas</label>
                    <select name="prioritas"
                            class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none cursor-pointer">
                        <option value="normal" {{ old('prioritas', 'normal') === 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="tinggi" {{ old('prioritas') === 'tinggi' ? 'selected' : '' }}>Tinggi (Urgent)</option>
                    </select>
                </div>

                {{-- Catatan --}}
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700">Catatan</label>
                    <textarea name="catatan" rows="2" placeholder="Catatan tambahan (opsional)..."
                              class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none resize-none">{{ old('catatan') }}</textarea>
                </div>

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
            <button type="submit" form="suratForm"
                class="px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-green-700 hover:bg-green-800 shadow-md transition-all cursor-pointer">
                <i class="fa-solid fa-paper-plane mr-2"></i> Ajukan Surat
            </button>
        </div>
    </div>
</div>
