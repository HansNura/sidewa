<div x-show="updateModalOpen" class="fixed inset-0 z-[110] flex items-center justify-center p-4" x-cloak>
    <div x-show="updateModalOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
        @click="updateModalOpen = false"></div>

    <div x-show="updateModalOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        class="relative bg-white rounded-[2rem] shadow-2xl w-full max-w-lg flex flex-col max-h-[90vh] overflow-hidden border border-white/20">

        <!-- Header -->
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-white shrink-0">
            <div>
                <h3 class="font-black text-xl text-gray-900 tracking-tight leading-none">Pembaruan Progres</h3>
                <p class="text-[11px] text-gray-500 font-medium mt-1.5 uppercase tracking-wider">Log & Dokumentasi Lapangan</p>
            </div>
            <button @click="updateModalOpen = false"
                class="text-gray-400 hover:text-red-500 w-10 h-10 flex items-center justify-center rounded-2xl hover:bg-red-50 transition-all">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <!-- Scrollable Form Body -->
        <form :action="`/admin/pembangunan/data/${activeProyekId}/progress`" method="POST"
            enctype="multipart/form-data" class="flex-1 overflow-y-auto custom-scrollbar bg-white">
            @csrf

            <div class="p-6 space-y-6">
                <!-- Judul Update -->
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Judul Pembaruan <span class="text-red-500">*</span></label>
                    <input type="text" name="judul_update" required placeholder="Contoh: Pemasangan keramik lantai 2"
                        class="w-full bg-gray-50/50 border border-gray-200 rounded-2xl px-5 py-3 text-sm font-bold text-gray-700 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all placeholder:font-medium placeholder:text-gray-400">
                </div>

                <!-- Progress Slider Widget -->
                <div class="bg-emerald-50/30 border border-emerald-100/50 rounded-3xl p-6" 
                    x-data="{ targetProgres: 0 }"
                    x-init="$watch('currentProgres', val => targetProgres = val)"
                    x-effect="if (targetProgres < currentProgres) targetProgres = currentProgres">
                    
                    <div class="flex items-center justify-between mb-5">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-2xl bg-emerald-500 text-white flex items-center justify-center text-lg shadow-lg shadow-emerald-500/20">
                                <i class="fa-solid fa-person-digging"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-black text-gray-900 leading-none">Target Progres Fisik</h3>
                                <p class="text-[10px] text-emerald-600/70 font-bold mt-1 uppercase tracking-tighter">Geser slider ke arah kanan</p>
                            </div>
                        </div>
                    </div>

                    <div class="relative pt-2 pb-1" :style="`--current: ${currentProgres}%; --target: ${targetProgres}%;`">
                        <div class="flex justify-between text-[11px] font-bold text-gray-500 mb-4 px-1">
                            <span class="flex items-center gap-2 px-2.5 py-1.5 bg-white border border-gray-100 rounded-lg shadow-sm">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span class="text-gray-400">Awal:</span> <span class="text-gray-900" x-text="currentProgres + '%'"></span>
                            </span>
                            <span class="flex items-center gap-2 px-2.5 py-1.5 bg-emerald-600 text-white rounded-lg shadow-lg shadow-emerald-600/20">
                                <span class="text-emerald-100">Baru:</span> <span class="font-black text-sm" x-text="targetProgres + '%'"></span>
                            </span>
                        </div>

                        <input type="range" name="progres_fisik" min="0" max="100" 
                            x-model="targetProgres" 
                            @input="if (targetProgres < currentProgres) targetProgres = currentProgres"
                            class="range-custom slider-emerald cursor-pointer"
                            aria-label="Set target persentase">

                        <div class="flex justify-between text-[10px] text-gray-400 mt-3 px-1 font-bold italic uppercase tracking-tighter">
                            <span>0% (AWAL)</span>
                            <span>100% (SELESAI)</span>
                        </div>
                    </div>
                </div>

                <!-- Date and Photo -->
                <div class="grid grid-cols-1 gap-5">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Tanggal Laporan <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal" required value="{{ date('Y-m-d') }}"
                            class="w-full bg-gray-50/50 border border-gray-200 rounded-2xl px-5 py-3 text-sm font-bold text-gray-700 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all">
                    </div>

                    <div class="space-y-2" x-data="{ imageUrl: null }" x-init="$watch('updateModalOpen', val => { if(!val) imageUrl = null })">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Foto Dokumentasi</label>
                        <input type="file" name="foto_lapangan" accept="image/*" id="foto-lapangan-upload" class="hidden"
                            @change="const file = $event.target.files[0]; if (file) { imageUrl = URL.createObjectURL(file) }">
                        
                        <!-- Upload Placeholder -->
                        <label for="foto-lapangan-upload" x-show="!imageUrl"
                            class="block border-2 border-dashed border-gray-200 rounded-3xl p-8 text-center hover:bg-emerald-50/30 hover:border-emerald-200 transition-all cursor-pointer group relative overflow-hidden">
                            <div class="relative z-10">
                                <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-300 group-hover:text-emerald-500 mb-3 transition-all transform group-hover:-translate-y-1"></i>
                                <p class="text-xs font-black text-gray-700 tracking-tight">Ketuk Untuk Unggah Foto</p>
                                <p class="text-[10px] text-gray-400 mt-1 font-medium">Format JPG/PNG (Max. 5MB)</p>
                            </div>
                        </label>

                        <!-- Image Preview -->
                        <div x-show="imageUrl" class="relative group rounded-3xl overflow-hidden border-4 border-white shadow-xl aspect-video bg-gray-100 mt-1" x-cloak>
                            <img :src="imageUrl" class="w-full h-full object-cover shadow-inner">
                            <div class="absolute inset-0 bg-gray-900/40 opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center justify-center gap-3 backdrop-blur-[2px]">
                                <label for="foto-lapangan-upload" class="px-4 py-2 bg-white rounded-xl text-emerald-700 text-xs font-black cursor-pointer hover:bg-emerald-50 transition-all shadow-lg flex items-center gap-2">
                                    <i class="fa-solid fa-rotate text-[10px]"></i> GANTI FOTO
                                </label>
                                <button type="button" @click="imageUrl = null; document.getElementById('foto-lapangan-upload').value = ''" 
                                    class="px-4 py-2 bg-red-500 rounded-xl text-white text-xs font-black hover:bg-red-600 transition-all shadow-lg flex items-center gap-2">
                                    <i class="fa-solid fa-trash text-[10px]"></i> HAPUS
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Note -->
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Catatan Tambahan</label>
                    <textarea name="deskripsi_update" rows="3" placeholder="Misal: Kendala cuaca hujan deras di lokasi..."
                        class="w-full bg-gray-50/50 border border-gray-200 rounded-2xl px-5 py-4 text-sm font-medium text-gray-700 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all resize-none"></textarea>
                </div>
            </div>

            <!-- Footer Action -->
            <div class="px-6 py-5 border-t border-gray-100 bg-gray-50/50 shrink-0 flex gap-3">
                <button type="button" @click="updateModalOpen = false"
                    class="flex-1 py-3.5 rounded-2xl text-sm font-black text-gray-500 border border-gray-200 hover:bg-white transition-all">BATAL</button>
                <button type="submit"
                    class="flex-[1.5] py-3.5 rounded-2xl text-sm font-black text-white bg-emerald-600 hover:bg-emerald-700 shadow-xl shadow-emerald-600/20 transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-paper-plane text-xs"></i> SIMPAN PROGRES
                </button>
            </div>
        </form>
    </div>
</div>
