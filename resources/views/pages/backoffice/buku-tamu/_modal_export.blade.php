<!-- MODAL: EXPORT & CETAK LAPORAN -->
<div x-show="reportModalOpen" class="fixed inset-0 z-[110] flex items-center justify-center p-4 sm:p-6" x-cloak>
    <div x-show="reportModalOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
        @click="reportModalOpen = false"></div>

    <div x-show="reportModalOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4"
        class="relative bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden flex flex-col max-h-[90vh]">

        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 shrink-0">
            <h3 class="font-extrabold text-lg text-gray-900">Konfigurasi Laporan Buku Tamu</h3>
            <button @click="reportModalOpen = false"
                class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 transition-colors cursor-pointer"><i
                    class="fa-solid fa-xmark text-lg"></i></button>
        </div>

        <form action="{{ route('admin.buku-tamu.export') }}" method="POST" target="_blank" class="flex flex-col flex-1 overflow-hidden" x-data="{ exportFormat: 'pdf' }">
            @csrf
            <div class="p-6 overflow-y-auto custom-scrollbar flex-1 space-y-6">
                <!-- Report Generator Panel -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700">Jenis Laporan</label>
                        <select
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none cursor-not-allowed text-gray-500" disabled>
                            <option>Laporan Kunjungan Bulanan</option>
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700">Pilih Periode</label>
                        <select name="period" required
                            class="w-full bg-white border border-gray-300 rounded-xl pl-4 pr-10 py-2 shadow-sm text-sm font-bold text-gray-700 focus:ring-2 focus:ring-green-500 outline-none cursor-pointer">
                            @foreach($monthOptions as $val => $label)
                                <option value="{{ $val }}" {{ $periodInput === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Report Preview Box -->
                <div class="border-2 border-dashed border-gray-200 rounded-3xl p-10 bg-gray-50 flex flex-col items-center justify-center text-center">
                    <div class="w-20 h-20 bg-white rounded-2xl shadow-sm border border-gray-100 flex items-center justify-center text-gray-300 text-4xl mb-4">
                        <i class="fa-solid fa-file-invoice"></i>
                    </div>
                    <h4 class="font-bold text-gray-800">Pratinjau Sinkronisasi</h4>
                    <p class="text-xs text-gray-500 max-w-xs mt-1">Laporan akan merender data kunjungan tamu khusus pada bulan/periode terpilih di atas.</p>
                </div>

                <!-- Format Selection -->
                <div class="space-y-3">
                    <p class="text-xs font-bold text-gray-700 uppercase tracking-widest">Pilih Format Berkas</p>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="format" value="pdf" class="sr-only peer" x-model="exportFormat">
                            <div class="p-4 border-2 border-gray-100 rounded-2xl bg-white peer-checked:border-green-500 peer-checked:bg-green-50 transition-all flex items-center gap-3">
                                <i class="fa-solid fa-file-pdf text-red-500 text-xl"></i>
                                <span class="text-sm font-bold">Dokumen PDF</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="format" value="excel" class="sr-only peer" x-model="exportFormat">
                            <div class="p-4 border-2 border-gray-100 rounded-2xl bg-white peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all flex items-center gap-3">
                                <i class="fa-solid fa-file-excel text-green-600 text-xl"></i>
                                <span class="text-sm font-bold">Excel (.xlsx)</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 shrink-0 flex justify-end gap-3 rounded-b-3xl">
                <button type="button" @click="reportModalOpen = false"
                    class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-600 bg-white border border-gray-300 hover:bg-gray-100 transition-colors cursor-pointer">Batal</button>
                <button type="submit" @click="setTimeout(() => reportModalOpen = false, 500)"
                    class="px-6 py-2.5 rounded-xl text-sm font-bold text-white shadow-lg transition-all flex items-center gap-2 cursor-pointer"
                    :class="exportFormat === 'pdf' ? 'bg-green-700 hover:bg-green-800 shadow-green-100' : 'bg-blue-600 hover:bg-blue-700 shadow-blue-100'">
                    <i class="fa-solid fa-print"></i> Generate & Cetak
                </button>
            </div>
        </form>
    </div>
</div>
