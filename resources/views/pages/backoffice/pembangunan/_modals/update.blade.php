<div x-show="updateModalOpen" class="fixed inset-0 z-[110] flex items-center justify-center p-4 sm:p-6" x-cloak>
    <div x-show="updateModalOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
        @click="updateModalOpen = false"></div>

    <div x-show="updateModalOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden flex flex-col">

        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 shrink-0">
            <h3 class="font-extrabold text-lg text-gray-900">Update Progres & Log</h3>
            <button @click="updateModalOpen = false" class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 transition-colors"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>

        <!-- Binding action dinamis via Alpine -->
        <form :action="`/admin/pembangunan/data/${activeProyekId}/progress`" method="POST" enctype="multipart/form-data" class="overflow-y-auto w-full custom-scrollbar flex flex-col">
            @csrf
            
            <div class="p-6 space-y-5">
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700 uppercase">Judul Pembaruan <span class="text-red-500">*</span></label>
                    <input type="text" name="judul_update" required placeholder="Misal: Pengecoran Tahap 1 Selesai"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700 uppercase">Persentase (Fisik) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="number" name="progres_fisik" required min="0" max="100" placeholder="65"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-4 pr-10 py-2.5 text-sm font-bold focus:ring-2 focus:ring-green-500 outline-none">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">%</span>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700 uppercase">Tanggal Laporan <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal" required value="{{ date('Y-m-d') }}"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none">
                    </div>
                </div>

                <!-- Document Upload -->
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700 uppercase">Dokumentasi Foto Lapangan</label>
                    <input type="file" name="foto_lapangan" accept="image/*" id="foto-lapangan-upload" class="hidden">
                    <label for="foto-lapangan-upload" class="block border-2 border-dashed border-gray-200 rounded-2xl p-6 text-center hover:bg-gray-50 transition-colors cursor-pointer group">
                        <i class="fa-solid fa-camera text-2xl text-gray-300 group-hover:text-green-500 mb-2 transition-colors"></i>
                        <p class="text-xs font-bold text-gray-700">Unggah Foto Kejadian (JPG/PNG)</p>
                    </label>
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700 uppercase">Catatan Ekstra</label>
                    <textarea name="deskripsi_update" rows="2" placeholder="Kendala cuaca, bahan kurang, dll..."
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-500 outline-none resize-none"></textarea>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 shrink-0 flex justify-end gap-3 rounded-b-3xl">
                <button type="button" @click="updateModalOpen = false" class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-600 border border-gray-200 hover:bg-gray-50 bg-white transition-colors">Batal</button>
                <button type="submit" class="px-8 py-2.5 rounded-xl text-sm font-bold text-white bg-green-700 hover:bg-green-800 shadow-md transition-all"><i class="fa-solid fa-upload mr-1"></i> Submit Progress</button>
            </div>
        </form>
    </div>
</div>
