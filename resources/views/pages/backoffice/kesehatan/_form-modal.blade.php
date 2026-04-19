{{-- Form Input Penimbangan Modal --}}
<div x-show="addModalOpen" class="fixed inset-0 z-[110] flex items-center justify-center p-4 sm:p-6" x-cloak>
    <div x-show="addModalOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
         @click="addModalOpen = false"></div>

    <div x-show="addModalOpen" x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
         class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden flex flex-col max-h-[90vh]">

        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 shrink-0">
            <h3 class="font-extrabold text-lg text-gray-900">Input Data Kesehatan Anak</h3>
            <button @click="addModalOpen = false; selectedPenduduk = null"
                class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <div class="p-6 overflow-y-auto custom-scrollbar flex-1">
            <form id="pengukuranForm" method="POST" action="{{ route('admin.kesehatan.store') }}" class="space-y-6">
                @csrf

                {{-- Search Child --}}
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                    <label class="text-xs font-bold text-blue-800 block mb-2">Cari Data Balita <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-blue-400"></i>
                        <input type="text" placeholder="Ketik NIK atau Nama Anak..."
                               x-show="!selectedPenduduk"
                               @input.debounce.300ms="searchBalita($event.target.value)"
                               class="w-full bg-white border border-blue-200 rounded-lg pl-9 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">

                        {{-- Selected child display --}}
                        <div x-show="selectedPenduduk" class="flex items-center justify-between bg-white border border-blue-200 rounded-lg px-4 py-2.5">
                            <div>
                                <p class="text-sm font-bold text-gray-900" x-text="selectedPenduduk?.nama"></p>
                                <p class="text-[10px] font-mono text-gray-500" x-text="'NIK: ' + selectedPenduduk?.nik"></p>
                            </div>
                            <button type="button" @click="selectedPenduduk = null"
                                    class="text-red-400 hover:text-red-600 cursor-pointer">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>

                        <input type="hidden" name="penduduk_id" :value="selectedPenduduk?.id ?? ''">
                    </div>

                    {{-- Search results dropdown --}}
                    <div x-show="searchResults.length > 0 && !selectedPenduduk"
                         class="mt-2 bg-white border border-blue-200 rounded-lg shadow-lg max-h-48 overflow-y-auto divide-y divide-gray-50">
                        <template x-for="item in searchResults" :key="item.id">
                            <button type="button" @click="selectBalita(item)"
                                    class="w-full text-left px-4 py-2.5 hover:bg-blue-50 transition-colors cursor-pointer">
                                <p class="text-sm font-bold text-gray-800" x-text="item.nama"></p>
                                <p class="text-[10px] text-gray-500">
                                    <span x-text="'NIK: ' + item.nik"></span> ·
                                    <span x-text="item.umur_bulan + ' bulan'"></span> ·
                                    <span x-text="item.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'"></span>
                                </p>
                            </button>
                        </template>
                    </div>
                </div>

                {{-- Measurement Data --}}
                <div>
                    <h4 class="font-bold text-gray-800 mb-3 text-sm border-b border-gray-100 pb-2">Hasil Pengukuran Posyandu</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-700">Tanggal Pengukuran <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_pengukuran" value="{{ old('tanggal_pengukuran', now()->format('Y-m-d')) }}"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none" required>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-700">Umur Terhitung (Bulan)</label>
                            <input type="text" :value="selectedPenduduk ? selectedPenduduk.umur_bulan + ' bulan' : ''" readonly
                                   class="w-full bg-gray-100 border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-500 outline-none cursor-not-allowed">
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-700">Tinggi Badan (cm) <span class="text-red-500">*</span></label>
                            <input type="number" name="tinggi_badan" step="0.1" value="{{ old('tinggi_badan') }}" placeholder="Contoh: 88.5"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none font-mono" required>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-700">Berat Badan (kg) <span class="text-red-500">*</span></label>
                            <input type="number" name="berat_badan" step="0.1" value="{{ old('berat_badan') }}" placeholder="Contoh: 11.2"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none font-mono" required>
                        </div>
                    </div>
                </div>

                {{-- Status / Parent --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700">Status Gizi (TB/U) <span class="text-red-500">*</span></label>
                        <select name="status_gizi"
                                class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm font-semibold focus:ring-2 focus:ring-green-500 outline-none cursor-pointer">
                            <option value="normal" {{ old('status_gizi') === 'normal' ? 'selected' : '' }}>Normal</option>
                            <option value="pendek" {{ old('status_gizi') === 'pendek' ? 'selected' : '' }}>Pendek (Stunting)</option>
                            <option value="sangat_pendek" {{ old('status_gizi') === 'sangat_pendek' ? 'selected' : '' }}>Sangat Pendek</option>
                            <option value="tinggi" {{ old('status_gizi') === 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700">Nama Orang Tua / Wali</label>
                        <input type="text" name="nama_ortu" value="{{ old('nama_ortu') }}" placeholder="Nama ayah/ibu/wali"
                               class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none">
                    </div>
                </div>

                {{-- Notes --}}
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700">Catatan</label>
                    <textarea name="catatan" rows="2" placeholder="Catatan tambahan..."
                              class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none resize-none">{{ old('catatan') }}</textarea>
                </div>
            </form>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 shrink-0 flex justify-end gap-3 rounded-b-2xl">
            <button type="button" @click="addModalOpen = false; selectedPenduduk = null"
                class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-600 border border-gray-200 hover:bg-gray-100 transition-colors bg-white cursor-pointer">Batal</button>
            <button type="submit" form="pengukuranForm"
                class="px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-green-700 hover:bg-green-800 shadow-md transition-all cursor-pointer">
                <i class="fa-solid fa-save mr-2"></i> Simpan Pengukuran
            </button>
        </div>
    </div>
</div>
