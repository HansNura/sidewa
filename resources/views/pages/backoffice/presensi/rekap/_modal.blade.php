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

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 shrink-0">
            <h3 class="font-extrabold text-lg text-gray-900">Generate Laporan Kehadiran</h3>
            <button @click="reportModalOpen = false"
                class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 transition-colors cursor-pointer"><i
                    class="fa-solid fa-xmark text-lg"></i></button>
        </div>

        <form action="{{ route('admin.presensi.rekap.export') }}" method="POST" target="_blank" class="flex flex-col flex-1 overflow-hidden" x-data="{ exportType: 'pdf' }">
            @csrf
            <div class="p-6 overflow-y-auto custom-scrollbar flex-1 space-y-6">
                <!-- Report Generator Panel -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700">Tipe Laporan</label>
                        <select
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none cursor-not-allowed text-gray-500" disabled>
                            <option>Laporan Kumulatif Bulanan Desa</option>
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700">Periode</label>
                        <select name="period" required
                            class="w-full bg-white border border-gray-300 rounded-xl pl-4 pr-10 py-2 shadow-sm text-sm font-bold text-gray-700 focus:ring-2 focus:ring-green-500 outline-none cursor-pointer">
                            @foreach($monthOptions as $val => $label)
                                <option value="{{ $val }}" {{ $periodInput === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700">Format Output File <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="flex items-center gap-3 p-4 border rounded-xl cursor-pointer transition-all" :class="exportType === 'pdf' ? 'bg-green-50 border-green-500' : 'bg-white border-gray-200 hover:bg-gray-50'">
                            <input type="radio" name="type" value="pdf" class="hidden" x-model="exportType">
                            <i class="fa-solid fa-file-pdf text-3xl" :class="exportType === 'pdf' ? 'text-green-600' : 'text-gray-400'"></i>
                            <div>
                                <h4 class="font-bold text-sm text-gray-900">PDF Document</h4>
                                <p class="text-[10px] text-gray-500 mt-0.5">Siap dicetak (.pdf)</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-4 border rounded-xl cursor-pointer transition-all" :class="exportType === 'excel' ? 'bg-blue-50 border-blue-500' : 'bg-white border-gray-200 hover:bg-gray-50'">
                            <input type="radio" name="type" value="excel" class="hidden" x-model="exportType">
                            <i class="fa-solid fa-file-excel text-3xl" :class="exportType === 'excel' ? 'text-blue-600' : 'text-gray-400'"></i>
                            <div>
                                <h4 class="font-bold text-sm text-gray-900">Excel / Spreadsheet</h4>
                                <p class="text-[10px] text-gray-500 mt-0.5">Untuk olah data (.xlsx)</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Approval / Validation Info -->
                <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 flex gap-3">
                    <i class="fa-solid fa-signature text-blue-500 mt-1"></i>
                    <div>
                        <h4 class="text-xs font-bold text-blue-800 mb-1">Verifikasi & Tanda Tangan</h4>
                        <p class="text-[10px] text-blue-600 leading-relaxed">Daftar presensi pegawai bulanan akan diekstrak menggunakan data _Real-time_ yang mencakup absensi sukses, keterlambatan, dan riwayat alfa.</p>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 shrink-0 flex justify-end gap-3 rounded-b-3xl">
                <button type="button" @click="reportModalOpen = false"
                    class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-600 bg-white border border-gray-300 hover:bg-gray-100 transition-colors cursor-pointer">Batal</button>
                <button type="submit" @click="setTimeout(() => reportModalOpen = false, 500)"
                    class="px-5 py-2.5 rounded-xl text-sm font-bold text-white shadow-md transition-all flex items-center gap-2 cursor-pointer"
                    :class="exportType === 'pdf' ? 'bg-green-700 hover:bg-green-800' : 'bg-blue-600 hover:bg-blue-700'">
                    <i class="fa-solid fa-file-export"></i> Mulai Export
                </button>
            </div>
        </form>
    </div>
</div>
