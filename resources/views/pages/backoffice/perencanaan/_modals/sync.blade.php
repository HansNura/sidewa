<div x-show="syncModalOpen" class="fixed inset-0 z-[120] flex items-center justify-center p-4 sm:p-6" x-cloak>
    <div x-show="syncModalOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
        @click="syncModalOpen = false"></div>

    <div x-show="syncModalOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        class="relative bg-white rounded-3xl shadow-2xl w-full max-w-3xl overflow-hidden flex flex-col max-h-[90vh]">

        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 shrink-0">
            <h3 class="font-extrabold text-lg text-gray-900">Sinkronisasi & Konversi Rencana</h3>
            <button @click="syncModalOpen = false" class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 transition-colors"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>

        <!-- Modal Body -->
        <form method="POST" id="sync-form" class="p-6 overflow-y-auto custom-scrollbar flex-1 space-y-6">
            @csrf
            
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 p-4 border-2 border-dashed border-gray-200 rounded-2xl bg-gray-50">
                <div class="w-full sm:w-5/12 text-center p-3 bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mx-auto mb-2">
                        <i class="fa-solid fa-file-contract"></i>
                    </div>
                    <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">Data Rencana (Asal)</p>
                    <h4 class="text-xs font-bold text-gray-800 leading-tight" id="sync-judul-asal">Memuat...</h4>
                </div>

                <div class="text-gray-400 text-2xl rotate-90 sm:rotate-0"><i class="fa-solid fa-arrow-right-arrow-left"></i></div>

                <div class="w-full sm:w-5/12 text-center p-3 bg-green-50 rounded-xl shadow-sm border border-green-200 relative">
                    <span class="absolute -top-2 -right-2 flex h-4 w-4"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span><span class="relative inline-flex rounded-full h-4 w-4 bg-green-500 border-2 border-white"></span></span>
                    <div class="w-8 h-8 rounded-full bg-green-100 text-green-700 flex items-center justify-center mx-auto mb-2">
                        <i class="fa-solid fa-person-digging"></i>
                    </div>
                    <p class="text-[10px] text-green-600 uppercase font-bold tracking-widest mb-1">Proyek Fisik (Tujuan)</p>
                    <h4 class="text-xs font-bold text-green-900 leading-tight" id="sync-judul-tujuan">Memuat...</h4>
                </div>
            </div>

            <p class="text-xs text-gray-500 text-center px-4">Anda akan mengubah draft rencana ini menjadi Proyek Pembangunan nyata yang dapat dilacak progres fisik dan anggarannya (Akan di set sebagai Draft Proyek).</p>

            <!-- Form Mapping (Gap Analysis) -->
            <div>
                <h5 class="text-xs font-bold text-gray-700 uppercase mb-3 border-b border-gray-100 pb-2">Gap Analysis Anggaran</h5>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white border border-gray-200 p-3 rounded-xl shadow-sm opacity-70">
                        <p class="text-[10px] text-gray-500 font-semibold mb-1">Estimasi Draf Rencana</p>
                        <p class="text-sm font-bold text-gray-600" id="sync-pagu-draf">Memuat...</p>
                    </div>
                    <div class="bg-white border-2 border-green-200 p-3 rounded-xl shadow-sm">
                        <p class="text-[10px] text-green-700 font-bold mb-1">Pagu Riil Target</p>
                        <div class="relative">
                            <span class="absolute left-2 top-1/2 -translate-y-1/2 text-gray-400 text-xs font-bold">Rp</span>
                            <input type="number" id="sync-pagu-final" disabled class="w-full bg-gray-100 border border-gray-300 rounded-lg pl-8 pr-3 py-1.5 text-xs outline-none font-bold text-gray-800">
                        </div>
                    </div>
                </div>
                <div class="mt-2 text-[10px] text-green-600 font-semibold flex items-center gap-1"><i class="fa-solid fa-check-circle"></i> Anggaran terkalkulasi baik. Sinkronisasi aman.</div>
            </div>
            
            <div class="border-t border-gray-100 mt-6 pt-4 flex justify-end gap-3">
                <button type="button" @click="syncModalOpen = false" class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-600 border border-gray-200 hover:bg-gray-50 transition-colors">Batal</button>
                <button type="submit" class="px-8 py-2.5 rounded-xl text-sm font-bold text-white bg-green-700 hover:bg-green-800 shadow-md transition-all flex items-center gap-2"><i class="fa-solid fa-rocket"></i> Jadikan Proyek Baru</button>
            </div>
        </form>
    </div>
</div>
