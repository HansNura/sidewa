<div x-show="addModalOpen" class="fixed inset-0 z-[110] flex items-center justify-center p-4" x-cloak>
    <div x-show="addModalOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
        @click="addModalOpen = false"></div>

    <div x-show="addModalOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        class="relative bg-white rounded-[2rem] shadow-2xl w-full max-w-3xl flex flex-col max-h-[90vh] overflow-hidden border border-white/20">

        <!-- Header -->
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-white shrink-0">
            <div>
                <h3 class="font-black text-xl text-gray-900 tracking-tight leading-none">Input Usulan Perencanaan</h3>
                <p class="text-[11px] text-gray-500 font-medium mt-1.5 uppercase tracking-wider">Draft RKPDes / RPJMDes Baru</p>
            </div>
            <button @click="addModalOpen = false"
                class="text-gray-400 hover:text-red-500 w-10 h-10 flex items-center justify-center rounded-2xl hover:bg-red-50 transition-all">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <!-- Scrollable Form Body -->
        <div class="flex-1 overflow-y-auto custom-scrollbar bg-white">
            <form action="{{ route('admin.perencanaan.store') }}" method="POST" class="space-y-6" id="addRencanaForm">
                @csrf
                <div class="p-6 space-y-6">

                    <!-- Jenis & Periode -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 bg-gray-50/50 border border-gray-100 p-5 rounded-2xl">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Jenis Rencana <span class="text-red-500">*</span></label>
                            <select name="jenis_rencana" required class="w-full bg-white border border-gray-200 rounded-2xl px-5 py-3 text-sm font-bold text-gray-700 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none cursor-pointer transition-all">
                                <option value="rkpdes">RKPDes (Tahunan)</option>
                                <option value="rpjmdes">RPJMDes (Jangka Menengah)</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Periode / Tahun <span class="text-red-500">*</span></label>
                            <input type="text" name="tahun_pelaksanaan" required value="{{ date('Y') + 1 }}" placeholder="Contoh: 2026 atau 2024-2030"
                                class="w-full bg-white border border-gray-200 rounded-2xl px-5 py-3 text-sm font-bold text-gray-700 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all placeholder:font-medium placeholder:text-gray-400">
                        </div>
                    </div>

                    <!-- Prioritas & Kategori -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Tingkat Prioritas <span class="text-red-500">*</span></label>
                            <select name="prioritas" required class="w-full bg-gray-50/50 border border-gray-200 rounded-2xl px-5 py-3 text-sm font-bold text-gray-700 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none cursor-pointer transition-all">
                                <option value="tinggi">Sangat Mendesak (Wajib)</option>
                                <option value="sedang" selected>Mendesak (Prioritas 2)</option>
                                <option value="normal">Normal / Tambahan</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Kategori / Bidang <span class="text-red-500">*</span></label>
                            <select name="kategori" required class="w-full bg-gray-50/50 border border-gray-200 rounded-2xl px-5 py-3 text-sm font-bold text-gray-700 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none cursor-pointer transition-all">
                                <option value="Pelaksanaan Pembangunan">Pembangunan Fisik</option>
                                <option value="Pemberdayaan Masyarakat">Pemberdayaan Masyarakat</option>
                                <option value="Penyelenggaraan Pemdes">Penyelenggaraan Pemdes</option>
                            </select>
                        </div>
                    </div>

                    <!-- Nama Program -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Program / Kegiatan <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_program" required placeholder="Misal: Pembangunan Saluran Irigasi Tersier..."
                            class="w-full bg-gray-50/50 border border-gray-200 rounded-2xl px-5 py-3 text-sm font-bold text-gray-700 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all placeholder:font-medium placeholder:text-gray-400">
                    </div>

                    <!-- Tujuan -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Tujuan & Sasaran (Objective)</label>
                        <textarea name="tujuan_sasaran" rows="3" placeholder="Jelaskan secara singkat manfaat dari program ini bagi masyarakat..."
                            class="w-full bg-gray-50/50 border border-gray-200 rounded-2xl px-5 py-4 text-sm font-medium text-gray-700 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all resize-none"></textarea>
                    </div>

                    <!-- Anggaran Widget -->
                    <div class="bg-emerald-50/30 border border-emerald-100/50 rounded-3xl p-6">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="h-10 w-10 rounded-2xl bg-emerald-500 text-white flex items-center justify-center text-lg shadow-lg shadow-emerald-500/20">
                                <i class="fa-solid fa-coins"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-black text-gray-900 leading-none">Rencana Anggaran Biaya</h3>
                                <p class="text-[10px] text-emerald-600/70 font-bold mt-1 uppercase tracking-tighter">Estimasi pagu & sumber dana</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1"><i class="fa-solid fa-coins mr-1 text-emerald-500"></i> Estimasi Pagu <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-sm">Rp</span>
                                    <input type="number" name="estimasi_pagu" required placeholder="0" min="0" value="0"
                                        class="w-full bg-white border border-gray-200 rounded-2xl pl-12 pr-5 py-3 text-sm font-bold text-gray-700 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all">
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1"><i class="fa-solid fa-piggy-bank mr-1 text-emerald-500"></i> Sumber Dana</label>
                                <select name="sumber_dana" class="w-full bg-white border border-gray-200 rounded-2xl px-5 py-3 text-sm font-bold text-gray-700 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none cursor-pointer transition-all">
                                    <option value="Dana Desa (DD)">Dana Desa (DD)</option>
                                    <option value="Alokasi Dana Desa (ADD)">Alokasi Dana Desa (ADD)</option>
                                    <option value="Pendapatan Asli Desa (PADesa)">Pendapatan Asli Desa (PADesa)</option>
                                    <option value="Bantuan Provinsi / Kab">Bantuan Provinsi / Kab</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Target Waktu Mulai</label>
                            <input type="month" name="target_mulai" class="w-full bg-gray-50/50 border border-gray-200 rounded-2xl px-5 py-3 text-sm font-bold text-gray-700 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Target Selesai (Estimasi)</label>
                            <input type="month" name="target_selesai" class="w-full bg-gray-50/50 border border-gray-200 rounded-2xl px-5 py-3 text-sm font-bold text-gray-700 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all">
                        </div>
                    </div>

                </div>
            </form>
        </div>

        <!-- Footer Action -->
        <div class="px-6 py-5 border-t border-gray-100 bg-gray-50/50 shrink-0 flex gap-3">
            <button type="button" @click="addModalOpen = false"
                class="flex-1 py-3.5 rounded-2xl text-sm font-black text-gray-500 border border-gray-200 hover:bg-white transition-all">BATAL</button>
            <button type="submit" form="addRencanaForm"
                class="flex-[1.5] py-3.5 rounded-2xl text-sm font-black text-white bg-emerald-600 hover:bg-emerald-700 shadow-xl shadow-emerald-600/20 transition-all flex items-center justify-center gap-2">
                <i class="fa-solid fa-check text-xs"></i> DAFTARKAN RENCANA
            </button>
        </div>
    </div>
</div>
