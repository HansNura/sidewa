<div x-show="syncModalOpen" class="fixed inset-0 z-[120] flex items-center justify-center p-4" x-cloak>
    <div x-show="syncModalOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
        @click="syncModalOpen = false"></div>

    <div x-show="syncModalOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        class="relative bg-white rounded-[2rem] shadow-2xl w-full max-w-3xl flex flex-col max-h-[90vh] overflow-hidden border border-white/20">

        <!-- Header -->
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-white shrink-0">
            <div>
                <h3 class="font-black text-xl text-gray-900 tracking-tight leading-none">Sinkronisasi & Konversi</h3>
                <p class="text-[11px] text-gray-500 font-medium mt-1.5 uppercase tracking-wider">Draft Rencana → Proyek Fisik</p>
            </div>
            <button @click="syncModalOpen = false"
                class="text-gray-400 hover:text-red-500 w-10 h-10 flex items-center justify-center rounded-2xl hover:bg-red-50 transition-all">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <!-- Scrollable Body -->
        <form method="POST" id="sync-form" class="flex-1 overflow-y-auto custom-scrollbar bg-white">
            @csrf
            
            <div class="p-6 space-y-6">
                <!-- Visual Flow -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 p-5 border-2 border-dashed border-gray-200 rounded-3xl bg-gray-50/50">
                    <div class="w-full sm:w-5/12 text-center p-4 bg-white rounded-2xl shadow-sm border border-gray-100">
                        <div class="w-10 h-10 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center mx-auto mb-3 shadow-inner">
                            <i class="fa-solid fa-file-contract"></i>
                        </div>
                        <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mb-1">Data Rencana (Asal)</p>
                        <h4 class="text-xs font-bold text-gray-800 leading-tight" id="sync-judul-asal">Memuat...</h4>
                    </div>

                    <div class="text-gray-300 text-3xl rotate-90 sm:rotate-0">
                        <i class="fa-solid fa-arrow-right-long"></i>
                    </div>

                    <div class="w-full sm:w-5/12 text-center p-4 bg-emerald-50 rounded-2xl shadow-sm border border-emerald-200 relative">
                        <span class="absolute -top-2 -right-2 flex h-4 w-4">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-4 w-4 bg-green-500 border-2 border-white"></span>
                        </span>
                        <div class="w-10 h-10 rounded-2xl bg-emerald-500 text-white flex items-center justify-center mx-auto mb-3 shadow-lg shadow-emerald-500/20">
                            <i class="fa-solid fa-person-digging"></i>
                        </div>
                        <p class="text-[10px] text-emerald-600 uppercase font-black tracking-widest mb-1">Proyek Fisik (Tujuan)</p>
                        <h4 class="text-xs font-bold text-emerald-900 leading-tight" id="sync-judul-tujuan">Memuat...</h4>
                    </div>
                </div>

                <p class="text-xs text-gray-500 text-center px-4 leading-relaxed">Anda akan mengubah draft rencana ini menjadi <strong>Proyek Pembangunan nyata</strong> yang dapat dilacak progres fisik dan anggarannya.</p>

                <!-- Gap Analysis -->
                <div class="bg-emerald-50/30 border border-emerald-100/50 rounded-3xl p-6">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="h-10 w-10 rounded-2xl bg-emerald-500 text-white flex items-center justify-center text-lg shadow-lg shadow-emerald-500/20">
                            <i class="fa-solid fa-chart-pie"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-gray-900 leading-none">Gap Analysis Anggaran</h3>
                            <p class="text-[10px] text-emerald-600/70 font-bold mt-1 uppercase tracking-tighter">Perbandingan estimasi vs pagu riil</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white border border-gray-200 p-4 rounded-2xl shadow-sm opacity-70">
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Estimasi Draf Rencana</p>
                            <p class="text-sm font-black text-gray-600" id="sync-pagu-draf">Memuat...</p>
                        </div>
                        <div class="bg-white border-2 border-emerald-200 p-4 rounded-2xl shadow-sm">
                            <p class="text-[10px] text-emerald-700 font-black uppercase tracking-widest mb-1">Pagu Riil Target</p>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs font-bold">Rp</span>
                                <input type="number" id="sync-pagu-final" disabled class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-9 pr-3 py-2 text-sm outline-none font-bold text-gray-800">
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 text-[10px] text-emerald-600 font-bold flex items-center gap-1.5 bg-emerald-100/50 px-3 py-2 rounded-xl">
                        <i class="fa-solid fa-check-circle"></i> Anggaran terkalkulasi baik. Sinkronisasi aman.
                    </div>
                </div>
            </div>

            <!-- Footer Action -->
            <div class="px-6 py-5 border-t border-gray-100 bg-gray-50/50 shrink-0 flex gap-3">
                <button type="button" @click="syncModalOpen = false"
                    class="flex-1 py-3.5 rounded-2xl text-sm font-black text-gray-500 border border-gray-200 hover:bg-white transition-all">BATAL</button>
                <button type="submit"
                    class="flex-[1.5] py-3.5 rounded-2xl text-sm font-black text-white bg-emerald-600 hover:bg-emerald-700 shadow-xl shadow-emerald-600/20 transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-rocket text-xs"></i> JADIKAN PROYEK BARU
                </button>
            </div>
        </form>
    </div>
</div>
